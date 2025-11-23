<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotificacionModel;
use CodeIgniter\Database\BaseConnection;

class Pedidos extends BaseController
{
    protected $db;
    protected $notificacionModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->notificacionModel = new NotificacionModel();
    }

    public function index()
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return redirect()->to('/')->with('error', 'Acceso denegado');
        }

        // Obtener pedidos recientes con información del usuario y plato
        // AGREGAR LIMIT para evitar cargar miles de pedidos
        $pedidos = $this->db->table('pedidos as p')
            ->select('p.*,
                      u.username,
                      ai.secret as email,
                      pl.nombre as plato_nombre,
                      pl.precio,
                      pl.stock,
                      pl.stock_ilimitado')
            ->join('users as u', 'u.id = p.usuario_id', 'left')
            ->join('auth_identities as ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
            ->join('platos as pl', 'pl.id = p.plato_id', 'left')
            ->orderBy('p.created_at', 'DESC')
            ->orderBy('p.id', 'DESC')
            ->limit(500) // Solo los últimos 500 pedidos para rendimiento
            ->get()
            ->getResultArray();

        // Procesar las notas para extraer información
        foreach ($pedidos as &$pedido) {
            $pedido['info_pedido'] = $this->extraerInfoPedido($pedido['notas']);
        }

        $data['pedidos'] = $pedidos;
        
        return view('admin/pedidos/index', $data);
    }

    public function ver($id)
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return redirect()->to('/')->with('error', 'Acceso denegado');
        }

        $pedido = $this->db->table('pedidos as p')
            ->select('p.*, 
                      u.username, 
                      ai.secret as email,
                      pl.nombre as plato_nombre, 
                      pl.precio')
            ->join('users as u', 'u.id = p.usuario_id', 'left')
            ->join('auth_identities as ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
            ->join('platos as pl', 'pl.id = p.plato_id', 'left')
            ->where('p.id', $id)
            ->get()
            ->getRowArray();

        if (!$pedido) {
            return redirect()->to('/admin/pedidos')->with('error', 'Pedido no encontrado');
        }

        $pedido['info_pedido'] = $this->extraerInfoPedido($pedido['notas']);

        $data['pedido'] = $pedido;
        
        return view('admin/pedidos/ver', $data);
    }

    public function editar($id)
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return redirect()->to('/')->with('error', 'Acceso denegado');
        }

        $pedido = $this->db->table('pedidos')->where('id', $id)->get()->getRowArray();

        if (!$pedido) {
            return redirect()->to('/admin/pedidos')->with('error', 'Pedido no encontrado');
        }

        if ($this->request->getMethod() === 'post') {
            $estado = $this->request->getPost('estado');
            
            $this->db->table('pedidos')
                ->where('id', $id)
                ->update(['estado' => $estado]);

            return redirect()->to('/admin/pedidos')->with('success', 'Estado actualizado correctamente');
        }

        $pedido['info_pedido'] = $this->extraerInfoPedido($pedido['notas']);
        $data['pedido'] = $pedido;
        
        return view('admin/pedidos/editar', $data);
    }

    public function cambiarEstado($id)
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso denegado']);
        }

        $nuevoEstado = $this->request->getPost('estado');
        $notaCancelacion = $this->request->getPost('nota_cancelacion');

        // Obtener el pedido actual
        $pedido = $this->db->table('pedidos')->where('id', $id)->get()->getRowArray();

        if (!$pedido) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pedido no encontrado']);
        }

        $estadoAnterior = $pedido['estado'];

        // Preparar datos para actualización
        $datosActualizacion = ['estado' => $nuevoEstado];

        // Si se está cancelando un pedido completado y hay nota de cancelación, agregarla
        if ($nuevoEstado === 'cancelado' && $estadoAnterior === 'completado' && !empty($notaCancelacion)) {
            $notasActuales = $pedido['notas'] ?? '';
            $nuevaNota = "\n\n--- CANCELACIÓN ---\nMotivo: " . $notaCancelacion . "\nFecha: " . date('d/m/Y H:i') . "\nAdmin: " . auth()->user()->username;
            $datosActualizacion['notas'] = $notasActuales . $nuevaNota;
            log_message('info', 'Pedidos::cambiarEstado - Nota de cancelación agregada al pedido #' . $id);
        }

        // Actualizar el estado del pedido (y notas si aplica)
        $this->db->table('pedidos')
            ->where('id', $id)
            ->update($datosActualizacion);

        // SI EL NUEVO ESTADO ES "COMPLETADO", DESCONTAR DEL STOCK
        if ($nuevoEstado === 'completado' && $estadoAnterior !== 'completado') {
            $this->descontarStock($pedido['plato_id'], $pedido['cantidad']);
        }

        // SI SE CANCELA UN PEDIDO QUE ESTABA COMPLETADO, DEVOLVER AL STOCK
        if ($nuevoEstado === 'cancelado' && $estadoAnterior === 'completado') {
            $this->devolverStock($pedido['plato_id'], $pedido['cantidad']);
        }

        // REGISTRAR EN CAJA CHICA cuando el estado cambia a "completado" o "cancelado"
        if ($nuevoEstado === 'completado' && $estadoAnterior !== 'completado') {
            $this->registrarEnCajaChica($pedido, 'entrada');
        } elseif ($nuevoEstado === 'cancelado' && $estadoAnterior === 'completado') {
            // Si se cancela un pedido completado, registrar como salida (devolución)
            $this->registrarEnCajaChica($pedido, 'salida');
        }

        // Crear notificación para el usuario del pedido
        $mensajesEstado = [
            'pendiente' => 'Tu pedido está pendiente de confirmación',
            'confirmado' => 'Tu pedido ha sido confirmado y está siendo preparado',
            'en_camino' => 'Tu pedido está en camino',
            'completado' => '¡Tu pedido ha sido completado!',
            'cancelado' => 'Tu pedido ha sido cancelado'
        ];

        $iconosEstado = [
            'pendiente' => 'bi-clock-fill',
            'confirmado' => 'bi-check-circle-fill',
            'en_camino' => 'bi-truck',
            'completado' => 'bi-check-circle-fill',
            'cancelado' => 'bi-x-circle-fill'
        ];

        // Solo crear notificación si hay un usuario_id válido (pedidos no públicos)
        if (isset($mensajesEstado[$nuevoEstado]) && !empty($pedido['usuario_id'])) {
            try {
                $this->notificacionModel->crearNotificacion([
                    'usuario_id' => $pedido['usuario_id'],
                    'tipo' => 'cambio_estado_pedido',
                    'titulo' => 'Actualización de Pedido #' . $id,
                    'mensaje' => $mensajesEstado[$nuevoEstado],
                    'icono' => $iconosEstado[$nuevoEstado] ?? 'bi-info-circle',
                    'url' => site_url('pedido'),
                    'leida' => 0,
                    'data' => json_encode([
                        'pedido_id' => $id,
                        'estado_anterior' => $estadoAnterior,
                        'estado_nuevo' => $nuevoEstado
                    ])
                ]);
            } catch (\Exception $e) {
                // Log el error pero no interrumpir el flujo
                log_message('error', 'Error al crear notificación para pedido #' . $id . ': ' . $e->getMessage());
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ]);
    }

    public function eliminar($id)
    {
        // Establecer header JSON
        $this->response->setContentType('application/json');

        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            log_message('warning', 'Pedidos::eliminar - Acceso denegado para usuario no admin');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso denegado'
            ])->setStatusCode(403);
        }

        log_message('info', 'Pedidos::eliminar - Intentando eliminar pedido ID: ' . $id);

        // Verificar si se debe devolver stock
        $devolverStock = $this->request->getPost('devolver_stock') === 'true';
        log_message('info', 'Pedidos::eliminar - Devolver stock: ' . ($devolverStock ? 'SI' : 'NO'));

        try {
            // Obtener el pedido para identificar el grupo
            $pedido = $this->db->table('pedidos')->where('id', $id)->get()->getRowArray();

            if (!$pedido) {
                log_message('error', 'Pedidos::eliminar - Pedido no encontrado ID: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Pedido no encontrado'
                ])->setStatusCode(404);
            }

            log_message('info', 'Pedidos::eliminar - Eliminando pedido único ID: ' . $id . ', Estado: ' . $pedido['estado']);

            // Devolver stock SI el usuario lo confirmó Y el pedido tiene plato asociado
            if ($devolverStock && $pedido['plato_id']) {
                // Obtener info del plato
                $plato = $this->db->table('platos')->where('id', $pedido['plato_id'])->get()->getRowArray();

                if ($plato && $plato['stock_ilimitado'] == 0) {
                    $this->devolverStock($pedido['plato_id'], $pedido['cantidad']);
                    log_message('info', 'Pedidos::eliminar - Stock devuelto para plato ID: ' . $pedido['plato_id'] . ', cantidad: ' . $pedido['cantidad']);
                }
            }

            // Eliminar SOLO este pedido (NO eliminar registro de caja chica automáticamente)
            $this->db->table('pedidos')
                ->where('id', $id)
                ->delete();

            log_message('info', 'Pedidos::eliminar - Pedido eliminado correctamente (NO se modificó caja chica)');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pedido eliminado correctamente'
            ])->setStatusCode(200);

        } catch (\Exception $e) {
            log_message('error', 'Pedidos::eliminar - Excepción: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar pedido: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function actualizarItem()
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso denegado'
            ]);
        }

        $itemId = $this->request->getPost('item_id');
        $cantidad = (int)$this->request->getPost('cantidad');

        if (!$itemId || $cantidad < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos'
            ]);
        }

        // Obtener el pedido actual
        $pedido = $this->db->table('pedidos')->where('id', $itemId)->get()->getRowArray();

        if (!$pedido) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ]);
        }

        // Si la cantidad es 0, eliminar el pedido
        if ($cantidad === 0) {
            // SI el pedido estaba completado, DEVOLVER el stock
            if ($pedido['estado'] === 'completado' && $pedido['plato_id']) {
                // Obtener plato para verificar si tiene stock limitado
                $plato = $this->db->table('platos')->where('id', $pedido['plato_id'])->get()->getRowArray();
                if ($plato && $plato['stock_ilimitado'] == 0) {
                    $this->devolverStock($pedido['plato_id'], $pedido['cantidad']);
                    log_message('info', 'Pedidos::actualizarItem - Stock devuelto al eliminar item ID: ' . $itemId . ', cantidad: ' . $pedido['cantidad']);
                }
            }

            $this->db->table('pedidos')->where('id', $itemId)->delete();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Item eliminado',
                'subtotal' => 0,
                'cantidad' => 0
            ]);
        }

        // Obtener información del plato para verificar stock
        $plato = $this->db->table('platos')->where('id', $pedido['plato_id'])->get()->getRowArray();

        if (!$plato) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Plato no encontrado'
            ]);
        }

        // Verificar stock (solo si no es ilimitado)
        if ($plato['stock_ilimitado'] == 0) {
            // Si el pedido NO está completado, el stock no ha sido descontado todavía
            // Entonces podemos usar toda la cantidad disponible
            if ($pedido['estado'] !== 'completado') {
                if ($cantidad > $plato['stock']) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Stock insuficiente. Disponible: {$plato['stock']} unidad(es)",
                        'stock_disponible' => $plato['stock']
                    ]);
                }
            } else {
                // Si el pedido YA está completado, el stock ya fue descontado
                // Necesitamos considerar la cantidad actual del pedido
                $cantidadActual = $pedido['cantidad'];
                $diferenciaStock = $cantidad - $cantidadActual;

                // Si queremos AUMENTAR la cantidad, verificar que haya stock disponible
                if ($diferenciaStock > 0) {
                    if ($diferenciaStock > $plato['stock']) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => "Stock insuficiente. Solo puedes agregar {$plato['stock']} unidad(es) más",
                            'stock_disponible' => $plato['stock']
                        ]);
                    }
                    // Descontar del stock la diferencia
                    $this->descontarStock($pedido['plato_id'], $diferenciaStock);
                } elseif ($diferenciaStock < 0) {
                    // Si queremos REDUCIR la cantidad, devolver al stock la diferencia
                    $this->devolverStock($pedido['plato_id'], abs($diferenciaStock));
                }

                // NOTA: NO actualizamos caja chica cuando se editan cantidades en pedidos completados
                // El usuario tiene control manual sobre los registros de caja chica
            }
        }

        // Calcular nuevo subtotal
        $nuevoTotal = $plato['precio'] * $cantidad;

        // Actualizar pedido
        $this->db->table('pedidos')
            ->where('id', $itemId)
            ->update([
                'cantidad' => $cantidad,
                'total' => $nuevoTotal
            ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Cantidad actualizada',
            'subtotal' => $nuevoTotal,
            'cantidad' => $cantidad
        ]);
    }

    public function agregarPlato()
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso denegado'
            ]);
        }

        $pedidoKey = $this->request->getPost('pedido_key');
        $platoId = $this->request->getPost('plato_id');
        $cantidad = (int)$this->request->getPost('cantidad');

        if (!$pedidoKey || !$platoId || $cantidad < 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
        }

        // Obtener información del plato
        $plato = $this->db->table('platos')->where('id', $platoId)->get()->getRowArray();

        if (!$plato) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Plato no encontrado'
            ]);
        }

        // Verificar stock (solo si no es ilimitado)
        if ($plato['stock_ilimitado'] == 0) {
            if ($cantidad > $plato['stock']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Stock insuficiente. Disponible: {$plato['stock']} unidad(es)"
                ]);
            }
        }

        // Obtener un pedido existente del mismo grupo para copiar datos
        // El key tiene formato: nombreCliente_fecha (ej: "Juan Perez_2025-01-17 14:30")
        $keyParts = explode('_', $pedidoKey, 2); // Limitar a 2 partes para no romper la fecha
        $nombreCliente = $keyParts[0];
        $fechaPedido = isset($keyParts[1]) ? $keyParts[1] : null;

        // Buscar un pedido existente de este cliente/grupo con la misma fecha
        // EVITAR LIKE, usar WHERE para mayor precisión y seguridad
        $query = $this->db->table('pedidos')
            ->orderBy('id', 'DESC');

        // Si tenemos la fecha, filtrar también por fecha para mayor precisión
        if ($fechaPedido) {
            $fechaFormateada = date('Y-m-d H:i', strtotime($fechaPedido));
            $query->where('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") =', $fechaFormateada);
        }

        $pedidoExistente = $query->get()->getRowArray();

        if (!$pedidoExistente) {
            // Si no encontramos con fecha exacta, tomar el más reciente
            $pedidoExistente = $this->db->table('pedidos')
                ->orderBy('id', 'DESC')
                ->get()
                ->getRowArray();
        }

        if (!$pedidoExistente) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se pudo encontrar el pedido original'
            ]);
        }

        // Verificar si ya existe este plato en el mismo pedido (misma fecha y plato)
        $platoYaExiste = $this->db->table('pedidos')
            ->where('plato_id', $platoId)
            ->where('created_at', $pedidoExistente['created_at'])
            ->get()
            ->getRowArray();

        log_message('info', 'agregarPlato - Buscando plato existente. Plato ID: ' . $platoId . ', Cliente: ' . $nombreCliente);
        log_message('info', 'agregarPlato - Plato ya existe: ' . ($platoYaExiste ? 'SI (ID: ' . $platoYaExiste['id'] . ', Cantidad actual: ' . $platoYaExiste['cantidad'] . ')' : 'NO'));

        if ($platoYaExiste) {
            // Si ya existe, actualizar la cantidad en lugar de crear un nuevo registro
            $cantidadAnterior = $platoYaExiste['cantidad'];
            $nuevaCantidad = $cantidadAnterior + $cantidad;
            $nuevoTotal = $plato['precio'] * $nuevaCantidad;

            log_message('info', 'agregarPlato - SUMANDO cantidad. Anterior: ' . $cantidadAnterior . ', Agregar: ' . $cantidad . ', Nueva: ' . $nuevaCantidad);

            $this->db->table('pedidos')
                ->where('id', $platoYaExiste['id'])
                ->update([
                    'cantidad' => $nuevaCantidad,
                    'total' => $nuevoTotal
                ]);

            log_message('info', 'agregarPlato - Plato actualizado correctamente');
        } else {
            log_message('info', 'agregarPlato - Creando nuevo registro de pedido para el plato');
            // Crear nuevo registro en pedidos con los mismos datos del grupo
            $subtotal = $plato['precio'] * $cantidad;

            $nuevoPedido = [
                'usuario_id' => $pedidoExistente['usuario_id'],
                'plato_id' => $platoId,
                'cantidad' => $cantidad,
                'total' => $subtotal,
                'estado' => $pedidoExistente['estado'],
                'tipo_entrega' => $pedidoExistente['tipo_entrega'],
                'direccion' => $pedidoExistente['direccion'],
                'forma_pago' => $pedidoExistente['forma_pago'],
                'notas' => $pedidoExistente['notas'],
                'created_at' => $pedidoExistente['created_at'] // Usar la misma fecha del grupo
            ];

            $this->db->table('pedidos')->insert($nuevoPedido);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Plato agregado al pedido'
        ]);
    }

    public function imprimirTicket($id)
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return redirect()->to('/')->with('error', 'Acceso denegado');
        }

        $pedido = $this->db->table('pedidos as p')
            ->select('p.*, 
                      u.username, 
                      ai.secret as email,
                      pl.nombre as plato_nombre, 
                      pl.precio')
            ->join('users as u', 'u.id = p.usuario_id', 'left')
            ->join('auth_identities as ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
            ->join('platos as pl', 'pl.id = p.plato_id', 'left')
            ->where('p.id', $id)
            ->get()
            ->getRowArray();

        if (!$pedido) {
            return redirect()->to('/admin/pedidos')->with('error', 'Pedido no encontrado');
        }

        $pedido['info_pedido'] = $this->extraerInfoPedido($pedido['notas']);

        $data['pedido'] = $pedido;
        
        return view('admin/pedidos/ticket', $data);
    }

    /**
     * DESCONTAR STOCK CUANDO UN PEDIDO SE COMPLETA
     */
    private function descontarStock($platoId, $cantidad)
    {
        // Obtener información del plato
        $plato = $this->db->table('platos')->where('id', $platoId)->get()->getRowArray();

        if (!$plato) {
            log_message('error', "Plato ID {$platoId} no encontrado para descontar stock");
            return false;
        }

        // Si el plato tiene stock ilimitado, no hacer nada
        if ($plato['stock_ilimitado'] == 1) {
            return true;
        }

        // Descontar del stock
        $nuevoStock = max(0, $plato['stock'] - $cantidad);

        $this->db->table('platos')
            ->where('id', $platoId)
            ->update(['stock' => $nuevoStock]);

        log_message('info', "Stock descontado: Plato #{$platoId} - Cantidad: {$cantidad} - Stock restante: {$nuevoStock}");

        return true;
    }

    /**
     * DEVOLVER STOCK CUANDO UN PEDIDO COMPLETADO SE CANCELA
     */
    private function devolverStock($platoId, $cantidad)
    {
        // Obtener información del plato
        $plato = $this->db->table('platos')->where('id', $platoId)->get()->getRowArray();

        if (!$plato) {
            log_message('error', "Plato ID {$platoId} no encontrado para devolver stock");
            return false;
        }

        // Si el plato tiene stock ilimitado, no hacer nada
        if ($plato['stock_ilimitado'] == 1) {
            return true;
        }

        // Devolver al stock
        $nuevoStock = $plato['stock'] + $cantidad;

        $this->db->table('platos')
            ->where('id', $platoId)
            ->update(['stock' => $nuevoStock]);

        log_message('info', "Stock devuelto: Plato #{$platoId} - Cantidad: {$cantidad} - Stock actual: {$nuevoStock}");

        return true;
    }

    /**
     * REGISTRAR MOVIMIENTO EN CAJA CHICA
     */
    private function registrarEnCajaChica($pedido, $tipo)
    {
        try {
            // Extraer información del pedido
            $info = $this->extraerInfoPedido($pedido['notas']);
            $nombreCliente = $info['nombre'] ?? 'Cliente';
            $formaPago = strtolower($info['forma_pago'] ?? 'efectivo');

            // Determinar si es digital o efectivo
            $esDigital = in_array($formaPago, ['qr', 'mercado_pago', 'mercadopago', 'tarjeta', 'transferencia']) ? 1 : 0;

            // Mapear formas de pago a nombres legibles
            $formasPagoNombres = [
                'qr' => 'QR',
                'mercado_pago' => 'Mercado Pago',
                'mercadopago' => 'Mercado Pago',
                'tarjeta' => 'Tarjeta',
                'transferencia' => 'Transferencia',
                'efectivo' => 'Efectivo'
            ];

            $formaPagoTexto = $formasPagoNombres[$formaPago] ?? ucfirst($formaPago);

            // Preparar datos para caja chica con el método de pago
            $concepto = $tipo === 'entrada'
                ? "Pedido #{$pedido['id']} - {$nombreCliente} ({$formaPagoTexto})"
                : "Devolución Pedido #{$pedido['id']} - {$nombreCliente} ({$formaPagoTexto})";

            $datosMovimiento = [
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s'),
                'concepto' => $concepto,
                'tipo' => $tipo,
                'monto' => $pedido['total'],
                'es_digital' => $esDigital,
                'user_id' => auth()->id(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Insertar en caja_chica
            $this->db->table('caja_chica')->insert($datosMovimiento);

            log_message('info', "Movimiento en caja chica registrado: Pedido #{$pedido['id']} - Tipo: {$tipo} - Monto: {$pedido['total']}");

            return true;
        } catch (\Exception $e) {
            log_message('error', "Error al registrar en caja chica: " . $e->getMessage());
            return false;
        }
    }

    private function extraerInfoPedido($notas)
    {
        $info = [
            'nombre_cliente' => '',
            'tipo_entrega' => '',
            'direccion' => '',
            'forma_pago' => '',
            'detalle' => ''
        ];

        if (empty($notas)) {
            return $info;
        }

        // Extraer "A nombre de"
        if (preg_match('/A nombre de:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['nombre_cliente'] = trim($matches[1]);
        }

        // Extraer "Tipo de entrega"
        if (preg_match('/Tipo de entrega:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['tipo_entrega'] = trim($matches[1]);
        }

        // Extraer "Dirección"
        if (preg_match('/Direccion:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['direccion'] = trim($matches[1]);
        }

        // Extraer "Forma de pago"
        if (preg_match('/Forma de pago:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['forma_pago'] = trim($matches[1]);
        }

        // Extraer detalle del pedido
        if (preg_match('/Detalle del pedido:\s*(.+)/is', $notas, $matches)) {
            $info['detalle'] = trim($matches[1]);
        }

        return $info;
    }
}