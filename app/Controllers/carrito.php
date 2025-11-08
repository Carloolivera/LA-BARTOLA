<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PlatoModel;
use App\Models\NotificacionModel;
use App\Models\CajaModel;
use App\Models\CajaMovimientoModel;

class Carrito extends Controller
{
    protected $session;
    protected $platoModel;
    protected $notificacionModel;
    protected $cajaModel;
    protected $movimientoModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->platoModel = new PlatoModel();
        $this->notificacionModel = new NotificacionModel();
        $this->cajaModel = new CajaModel();
        $this->movimientoModel = new CajaMovimientoModel();
    }

    public function index()
    {
        // Verificar que el usuario logueado no sea admin o vendedor
        if (auth()->loggedIn()) {
            $user = auth()->user();
            if ($user && ($user->inGroup('admin') || $user->inGroup('vendedor'))) {
                return redirect()->to('/admin/pedidos')->with('error', 'Los administradores deben usar el panel de administración para gestionar pedidos');
            }
        }

        $carrito = $this->session->get('carrito') ?? [];

        $data['carrito'] = $carrito;

        return view('carrito/index', $data);
    }

    public function agregar()
    {
        try {
            // Verificar que el usuario logueado no sea admin o vendedor
            if (auth()->loggedIn()) {
                $user = auth()->user();
                if ($user && ($user->inGroup('admin') || $user->inGroup('vendedor'))) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Los administradores y vendedores no pueden realizar pedidos como clientes. Use el panel de administración.'
                    ]);
                }
            }

            $plato_id = $this->request->getPost('plato_id');
            $cantidad = (int)$this->request->getPost('cantidad');

            if (!$plato_id || !$cantidad) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos incompletos'
                ]);
            }

            $plato = $this->platoModel->find($plato_id);

            if (!$plato) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Plato no encontrado'
                ]);
            }

            // Verificar disponibilidad
            if (!$plato['disponible']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Este plato no está disponible actualmente'
                ]);
            }

            $carrito = $this->session->get('carrito') ?? [];

            // Calcular cantidad total que tendría en el carrito
            $cantidadActual = isset($carrito[$plato_id]) ? $carrito[$plato_id]['cantidad'] : 0;
            $cantidadTotal = $cantidadActual + $cantidad;

            // Verificar stock (solo si no es ilimitado)
            if ($plato['stock_ilimitado'] == 0) {
                if ($plato['stock'] <= 0) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Este plato está agotado'
                    ]);
                }

                if ($cantidadTotal > $plato['stock']) {
                    $disponible = $plato['stock'] - $cantidadActual;
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Solo puedes agregar {$disponible} unidad(es) más. Stock disponible: {$plato['stock']}"
                    ]);
                }
            }

            if (isset($carrito[$plato_id])) {
                $carrito[$plato_id]['cantidad'] = $cantidadTotal;
            } else {
                $carrito[$plato_id] = [
                    'nombre' => $plato['nombre'],
                    'precio' => $plato['precio'],
                    'cantidad' => $cantidad
                ];
            }

            $this->session->set('carrito', $carrito);

            $cart_count = array_sum(array_column($carrito, 'cantidad'));

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Plato agregado al carrito',
                'cart_count' => $cart_count
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en Carrito::agregar - ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al agregar al carrito: ' . $e->getMessage()
            ]);
        }
    }

    public function actualizar()
    {
        $plato_id = $this->request->getPost('plato_id');
        $cantidad = (int)$this->request->getPost('cantidad');

        if ($cantidad < 1) {
            return redirect()->to('/carrito')->with('error', 'La cantidad debe ser al menos 1');
        }

        $carrito = $this->session->get('carrito') ?? [];

        if (!isset($carrito[$plato_id])) {
            return redirect()->to('/carrito')->with('error', 'Plato no encontrado en el carrito');
        }

        // Verificar stock del plato
        $plato = $this->platoModel->find($plato_id);

        if (!$plato) {
            return redirect()->to('/carrito')->with('error', 'Plato no encontrado');
        }

        // Verificar stock (solo si no es ilimitado)
        if ($plato['stock_ilimitado'] == 0) {
            if ($cantidad > $plato['stock']) {
                return redirect()->to('/carrito')->with('error', "Stock insuficiente. Disponible: {$plato['stock']} unidad(es)");
            }
        }

        $carrito[$plato_id]['cantidad'] = $cantidad;
        $this->session->set('carrito', $carrito);
        return redirect()->to('/carrito')->with('success', 'Cantidad actualizada');
    }

    public function eliminar()
    {
        $plato_id = $this->request->getPost('plato_id');

        $carrito = $this->session->get('carrito') ?? [];

        if (isset($carrito[$plato_id])) {
            unset($carrito[$plato_id]);
            $this->session->set('carrito', $carrito);
            return redirect()->to('/carrito')->with('success', 'Plato eliminado del carrito');
        }

        return redirect()->to('/carrito')->with('error', 'Plato no encontrado en el carrito');
    }

    public function vaciar()
    {
        $this->session->remove('carrito');
        return redirect()->to('/carrito')->with('success', 'Carrito vaciado');
    }

    public function finalizar()
{
    // VERIFICAR QUE EL USUARIO ESTE LOGUEADO
    if (!auth()->loggedIn()) {
        // Guardar la URL actual para redirigir después del login
        session()->set('redirect_url', current_url());

        return redirect()->to('/login')
            ->with('error', 'Debe iniciar sesion para realizar un pedido');
    }

    // Verificar que no sea admin o vendedor
    $user = auth()->user();
    if ($user && ($user->inGroup('admin') || $user->inGroup('vendedor'))) {
        return redirect()->to('/admin/pedidos')->with('error', 'Los administradores no pueden realizar pedidos como clientes');
    }

    $carrito = $this->session->get('carrito') ?? [];

    if (empty($carrito)) {
        return redirect()->to('/carrito')->with('error', 'El carrito esta vacio');
    }

    // OBTENER EL ID DEL USUARIO
    $usuarioId = auth()->id();
    
    // Validacion adicional por seguridad
    if (empty($usuarioId)) {
        return redirect()->to('/login')
            ->with('error', 'Error de sesion. Por favor, inicie sesion nuevamente');
    }

    $nombre_cliente = esc($this->request->getPost('nombre_cliente'));
    $tipo_entrega = esc($this->request->getPost('tipo_entrega'));
    $direccion = esc($this->request->getPost('direccion'));
    $forma_pago = esc($this->request->getPost('forma_pago'));

    // Validar datos requeridos
    if (empty($nombre_cliente) || empty($tipo_entrega) || empty($forma_pago)) {
        return redirect()->to('/carrito')->with('error', 'Todos los campos son obligatorios');
    }

    // Validar tipo de entrega
    if (!in_array($tipo_entrega, ['retiro', 'delivery'])) {
        return redirect()->to('/carrito')->with('error', 'Tipo de entrega inválido');
    }

    // Validar forma de pago
    if (!in_array($forma_pago, ['efectivo', 'qr', 'tarjeta'])) {
        return redirect()->to('/carrito')->with('error', 'Forma de pago inválida');
    }

    $db = db_connect();

    // Calcular total del carrito
    $total = 0;
    foreach ($carrito as $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $total += $subtotal;
    }

    // Verificar si hay cupón aplicado
    $cupon_aplicado = $this->session->get('cupon_aplicado');
    $cupon_id = null;
    $descuento_cupon = 0;

    if ($cupon_aplicado) {
        $cupon_id = $cupon_aplicado['cupon']['id'];
        $descuento_cupon = $cupon_aplicado['descuento'];
        $total -= $descuento_cupon; // Aplicar descuento al total
    }

    // Construir notas del pedido
    $notas = "A nombre de: {$nombre_cliente}\n";
    $notas .= "Tipo de entrega: {$tipo_entrega}\n";
    if ($tipo_entrega === 'delivery' && !empty($direccion)) {
        $notas .= "Direccion: {$direccion}\n";
    }
    $notas .= "Forma de pago: {$forma_pago}\n";
    if ($cupon_aplicado) {
        $notas .= "Cupón aplicado: {$cupon_aplicado['codigo']} (-$" . number_format($descuento_cupon, 2) . ")\n";
    }

    // Crear el pedido principal
    $pedidoData = [
        'usuario_id' => $usuarioId,
        'total' => $total,
        'cupon_id' => $cupon_id,
        'descuento_cupon' => $descuento_cupon,
        'estado' => 'pendiente',
        'tipo_entrega' => $tipo_entrega,
        'direccion' => $direccion,
        'forma_pago' => $forma_pago,
        'notas' => $notas
    ];

    $pedido_id = $db->table('pedidos')->insert($pedidoData);

    // Insertar los detalles del pedido
    foreach ($carrito as $plato_id => $item) {
        $subtotal = $item['precio'] * $item['cantidad'];

        $detalleData = [
            'pedido_id' => $pedido_id,
            'plato_id' => $plato_id,
            'cantidad' => $item['cantidad'],
            'precio_unitario' => $item['precio'],
            'subtotal' => $subtotal
        ];

        $db->table('pedido_detalle')->insert($detalleData);

        // Descontar stock y marcar como no disponible si se agota
        $plato = $this->platoModel->find($plato_id);

        if ($plato && $plato['stock_ilimitado'] == 0) {
            $nuevoStock = $plato['stock'] - $item['cantidad'];

            // Si el stock llega a 0 o menos, marcar como no disponible
            $updateData = ['stock' => max(0, $nuevoStock)];

            if ($nuevoStock <= 0) {
                $updateData['disponible'] = 0;
            }

            $this->platoModel->update($plato_id, $updateData);
        }
    }

    // Registrar uso del cupón si se aplicó uno
    if ($cupon_aplicado) {
        $cuponModel = new \App\Models\CuponModel();
        $cuponModel->registrarUso(
            $cupon_id,
            $usuarioId,
            $descuento_cupon,
            $pedido_id
        );
    }

    // Registrar venta en caja si es en efectivo
    if ($forma_pago === 'efectivo') {
        $caja_abierta = $this->cajaModel->obtenerCajaAbierta();

        if ($caja_abierta) {
            $this->movimientoModel->registrarVenta(
                $caja_abierta['id'],
                $pedido_id,
                $total,
                'efectivo',
                $usuarioId
            );
        }
    }

    // Crear notificación para administradores
    $this->notificacionModel->notificarAdmins(
        'nuevo_pedido',
        'Nuevo Pedido Recibido',
        "Pedido #{$pedido_id} de {$nombre_cliente} por $" . number_format($total, 2),
        'bi-bag-check-fill',
        site_url("admin/pedidos/ver/{$pedido_id}"),
        [
            'pedido_id' => $pedido_id,
            'total' => $total,
            'tipo_entrega' => $tipo_entrega,
            'forma_pago' => $forma_pago
        ]
    );

    // Limpiar carrito y cupón de la sesión
    $this->session->remove('carrito');
    $this->session->remove('cupon_aplicado');

    if ($forma_pago === 'qr') {
        return $this->response->setJSON(['success' => true]);
    }

    return redirect()->to('/pedido')->with('success', 'Pedido realizado exitosamente');
}

    public function mostrarQR()
    {
        return view('carrito/qr');
    }

    public function getCount()
    {
        $carrito = $this->session->get('carrito') ?? [];
        $cart_count = array_sum(array_column($carrito, 'cantidad'));

        return $this->response->setJSON(['cart_count' => $cart_count]);
    }

    /**
     * Validar cupón
     */
    public function validarCupon()
    {
        $codigo = esc($this->request->getPost('codigo'));

        if (empty($codigo)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ingrese un código de cupón'
            ]);
        }

        // Calcular total del carrito
        $carrito = $this->session->get('carrito') ?? [];
        $total = 0;

        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        // Validar cupón
        $cuponModel = model('App\Models\CuponModel');
        $resultado = $cuponModel->validarCupon($codigo, auth()->id(), $total);

        return $this->response->setJSON([
            'success' => $resultado['valido'],
            'message' => $resultado['mensaje'],
            'descuento' => $resultado['descuento'] ?? 0,
            'cupon_id' => $resultado['cupon']['id'] ?? null
        ]);
    }

    /**
     * Aplicar cupón
     */
    public function aplicarCupon()
    {
        $codigo = esc($this->request->getPost('codigo'));

        if (empty($codigo)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ingrese un código de cupón'
            ]);
        }

        // Calcular total del carrito
        $carrito = $this->session->get('carrito') ?? [];
        $total = 0;

        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        // Validar cupón
        $cuponModel = model('App\Models\CuponModel');
        $resultado = $cuponModel->validarCupon($codigo, auth()->id(), $total);

        if (!$resultado['valido']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $resultado['mensaje']
            ]);
        }

        // Guardar cupón en sesión
        $this->session->set('cupon_aplicado', [
            'id' => $resultado['cupon']['id'],
            'codigo' => $codigo,
            'descuento' => $resultado['descuento']
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => $resultado['mensaje'],
            'descuento' => $resultado['descuento'],
            'total' => $total,
            'total_con_descuento' => $total - $resultado['descuento']
        ]);
    }

    /**
     * Quitar cupón
     */
    public function quitarCupon()
    {
        $this->session->remove('cupon_aplicado');

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Cupón removido'
        ]);
    }
}