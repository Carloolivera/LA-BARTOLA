<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PlatoModel;
use App\Models\NotificacionModel;

class Carrito extends Controller
{
    protected $session;
    protected $platoModel;
    protected $notificacionModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->platoModel = new PlatoModel();
        $this->notificacionModel = new NotificacionModel();
    }

    public function index()
    {
        $carrito = $this->session->get('carrito') ?? [];
        $data['carrito'] = $carrito;
        return view('carrito/index', $data);
    }

    public function agregar()
    {
        try {
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
        try {
            $plato_id = $this->request->getPost('plato_id');
            $cantidad = (int)$this->request->getPost('cantidad');

            if ($cantidad < 1) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'La cantidad debe ser al menos 1'
                ]);
            }

            $carrito = $this->session->get('carrito') ?? [];

            if (!isset($carrito[$plato_id])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Plato no encontrado en el carrito'
                ]);
            }

            // Verificar stock del plato
            $plato = $this->platoModel->find($plato_id);

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

            $carrito[$plato_id]['cantidad'] = $cantidad;
            $this->session->set('carrito', $carrito);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cantidad actualizada'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en Carrito::actualizar - ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar cantidad: ' . $e->getMessage()
            ]);
        }
    }

    public function eliminar()
    {
        try {
            $plato_id = $this->request->getPost('plato_id');

            $carrito = $this->session->get('carrito') ?? [];

            if (isset($carrito[$plato_id])) {
                unset($carrito[$plato_id]);
                $this->session->set('carrito', $carrito);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Plato eliminado del carrito'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Plato no encontrado en el carrito'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en Carrito::eliminar - ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar producto: ' . $e->getMessage()
            ]);
        }
    }

    public function vaciar()
    {
        $this->session->remove('carrito');
        return redirect()->to('/carrito')->with('success', 'Carrito vaciado');
    }

    public function finalizar()
{
    $isAjax = $this->request->isAJAX();

    $carrito = $this->session->get('carrito') ?? [];

    if (empty($carrito)) {
        if ($isAjax) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El carrito está vacío'
            ]);
        }
        return redirect()->to('/carrito')->with('error', 'El carrito esta vacio');
    }

    // Los pedidos públicos no tienen usuario asociado
    $usuarioId = null;

    $nombre_cliente = esc($this->request->getPost('nombre_cliente'));
    $tipo_entrega = esc($this->request->getPost('tipo_entrega'));
    $direccion = esc($this->request->getPost('direccion'));
    $forma_pago = esc($this->request->getPost('forma_pago'));

    // Validar datos requeridos
    if (empty($nombre_cliente) || empty($tipo_entrega) || empty($forma_pago)) {
        if ($isAjax) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Todos los campos son obligatorios'
            ]);
        }
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

    // Construir notas del pedido
    $notas = "A nombre de: {$nombre_cliente}\n";
    $notas .= "Tipo de entrega: {$tipo_entrega}\n";
    if ($tipo_entrega === 'delivery' && !empty($direccion)) {
        $notas .= "Direccion: {$direccion}\n";
    }
    $notas .= "Forma de pago: {$forma_pago}\n";

    // Crear un pedido por cada plato en el carrito
    $primer_pedido_id = null;

    foreach ($carrito as $plato_id => $item) {
        $subtotal = $item['precio'] * $item['cantidad'];

        $pedidoData = [
            'usuario_id' => null,
            'plato_id' => $plato_id,
            'cantidad' => $item['cantidad'],
            'total' => $subtotal,
            'estado' => 'pendiente',
            'tipo_entrega' => $tipo_entrega,
            'direccion' => $direccion,
            'forma_pago' => $forma_pago,
            'notas' => $notas
        ];

        $pedido_id = $db->table('pedidos')->insert($pedidoData);

        if (!$primer_pedido_id) {
            $primer_pedido_id = $pedido_id;
        }

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

            // Limpiar caché de platos cuando cambia el stock
            cache()->delete('platos_disponibles');
        }
    }

    $pedido_id = $primer_pedido_id;

    // TODO: Registrar venta en caja si es en efectivo (funcionalidad deshabilitada temporalmente)
    // if ($forma_pago === 'efectivo') {
    //     // Lógica de caja comentada hasta implementar sistema completo
    // }

    // TODO: Crear notificación para administradores (funcionalidad deshabilitada temporalmente)
    // El método notificarAdmins() no existe en NotificacionModel
    // $this->notificacionModel->notificarAdmins(
    //     'nuevo_pedido',
    //     'Nuevo Pedido Recibido',
    //     "Pedido #{$pedido_id} de {$nombre_cliente} por $" . number_format($total, 2),
    //     'bi-bag-check-fill',
    //     site_url("admin/pedidos/ver/{$pedido_id}"),
    //     [
    //         'pedido_id' => $pedido_id,
    //         'total' => $total,
    //         'tipo_entrega' => $tipo_entrega,
    //         'forma_pago' => $forma_pago
    //     ]
    // );

    // Limpiar carrito de la sesión
    $this->session->remove('carrito');

    // Si es una petición AJAX, devolver JSON
    if ($this->request->isAJAX() || $forma_pago === 'qr') {
        return $this->response->setJSON([
            'success' => true,
            'pedido_id' => $pedido_id,
            'message' => 'Pedido realizado exitosamente'
        ]);
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
}