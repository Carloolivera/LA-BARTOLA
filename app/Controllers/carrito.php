<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Carrito extends Controller
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
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

            $db = \Config\Database::connect();
            $plato = $db->table('platos')->where('id', $plato_id)->get()->getRowArray();

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
        $db = \Config\Database::connect();
        $plato = $db->table('platos')->where('id', $plato_id)->get()->getRowArray();

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

    $nombre_cliente = $this->request->getPost('nombre_cliente');
    $tipo_entrega = $this->request->getPost('tipo_entrega');
    $direccion = $this->request->getPost('direccion');
    $forma_pago = $this->request->getPost('forma_pago');

    $db = \Config\Database::connect();
    
    $notas = "A nombre de: {$nombre_cliente}\n";
    $notas .= "Tipo de entrega: {$tipo_entrega}\n";
    if ($tipo_entrega === 'delivery' && !empty($direccion)) {
        $notas .= "Direccion: {$direccion}\n";
    }
    $notas .= "Forma de pago: {$forma_pago}\n\n";
    $notas .= "Detalle del pedido:\n";

    $total = 0;
    foreach ($carrito as $plato_id => $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $total += $subtotal;

        $pedidoData = [
            'usuario_id' => $usuarioId,
            'plato_id' => $plato_id,
            'cantidad' => $item['cantidad'],
            'total' => $subtotal,
            'estado' => 'pendiente',
            'notas' => $notas . $item['nombre'] . ' x' . $item['cantidad']
        ];

        $db->table('pedidos')->insert($pedidoData);

        // Descontar stock y marcar como no disponible si se agota
        $plato = $db->table('platos')->where('id', $plato_id)->get()->getRowArray();

        if ($plato && $plato['stock_ilimitado'] == 0) {
            $nuevoStock = $plato['stock'] - $item['cantidad'];

            // Si el stock llega a 0 o menos, marcar como no disponible
            $updateData = ['stock' => max(0, $nuevoStock)];

            if ($nuevoStock <= 0) {
                $updateData['disponible'] = 0;
            }

            $db->table('platos')->where('id', $plato_id)->update($updateData);
        }
    }

    $this->session->remove('carrito');

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
}