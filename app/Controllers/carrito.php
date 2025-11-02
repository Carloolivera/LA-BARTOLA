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

            $carrito = $this->session->get('carrito') ?? [];

            if (isset($carrito[$plato_id])) {
                $carrito[$plato_id]['cantidad'] += $cantidad;
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
        $cantidad = $this->request->getPost('cantidad');

        $carrito = $this->session->get('carrito') ?? [];

        if (isset($carrito[$plato_id])) {
            $carrito[$plato_id]['cantidad'] = $cantidad;
            $this->session->set('carrito', $carrito);
            return redirect()->to('/carrito')->with('success', 'Cantidad actualizada');
        }

        return redirect()->to('/carrito')->with('error', 'Plato no encontrado en el carrito');
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