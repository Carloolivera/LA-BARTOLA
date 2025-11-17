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

        // Obtener todos los pedidos con información del usuario y plato
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
            ->orderBy('p.id', 'DESC')
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

        // Obtener el pedido actual
        $pedido = $this->db->table('pedidos')->where('id', $id)->get()->getRowArray();

        if (!$pedido) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pedido no encontrado']);
        }

        $estadoAnterior = $pedido['estado'];

        // Actualizar el estado del pedido
        $this->db->table('pedidos')
            ->where('id', $id)
            ->update(['estado' => $nuevoEstado]);

        // SI EL NUEVO ESTADO ES "COMPLETADO", DESCONTAR DEL STOCK
        if ($nuevoEstado === 'completado' && $estadoAnterior !== 'completado') {
            $this->descontarStock($pedido['plato_id'], $pedido['cantidad']);
        }

        // SI SE CANCELA UN PEDIDO QUE ESTABA COMPLETADO, DEVOLVER AL STOCK
        if ($nuevoEstado === 'cancelado' && $estadoAnterior === 'completado') {
            $this->devolverStock($pedido['plato_id'], $pedido['cantidad']);
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

        if (isset($mensajesEstado[$nuevoEstado])) {
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
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ]);
    }

    public function eliminar($id)
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso denegado'
            ]);
        }

        $this->db->table('pedidos')->where('id', $id)->delete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pedido eliminado correctamente'
        ]);
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
            if ($cantidad > $plato['stock']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Stock insuficiente. Disponible: {$plato['stock']} unidad(es)"
                ]);
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
        $query = $this->db->table('pedidos')
            ->like('notas', "A nombre de: {$nombreCliente}")
            ->orderBy('id', 'DESC');

        // Si tenemos la fecha, filtrar también por fecha para mayor precisión
        if ($fechaPedido) {
            $fechaFormateada = date('Y-m-d H:i', strtotime($fechaPedido));
            $query->where('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") =', $fechaFormateada);
        }

        $pedidoExistente = $query->get()->getRowArray();

        if (!$pedidoExistente) {
            // Si no encontramos con fecha exacta, buscar solo por nombre
            $pedidoExistente = $this->db->table('pedidos')
                ->like('notas', "A nombre de: {$nombreCliente}")
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

        // Verificar si ya existe este plato en el mismo pedido (mismo cliente, fecha y plato)
        $platoYaExiste = $this->db->table('pedidos')
            ->where('plato_id', $platoId)
            ->like('notas', "A nombre de: {$nombreCliente}")
            ->where('created_at', $pedidoExistente['created_at'])
            ->get()
            ->getRowArray();

        if ($platoYaExiste) {
            // Si ya existe, actualizar la cantidad en lugar de crear un nuevo registro
            $nuevaCantidad = $platoYaExiste['cantidad'] + $cantidad;
            $nuevoTotal = $plato['precio'] * $nuevaCantidad;

            $this->db->table('pedidos')
                ->where('id', $platoYaExiste['id'])
                ->update([
                    'cantidad' => $nuevaCantidad,
                    'total' => $nuevoTotal
                ]);
        } else {
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