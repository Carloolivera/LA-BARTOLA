<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PedidoModel;
use App\Models\PlatoModel;

class Pedido extends Controller
{
    protected $helpers = ['auth'];

    public function index()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión para ver tus pedidos');
        }

        $pedidoModel = new PedidoModel();
        
        $data['pedidos'] = $pedidoModel
            ->select('pedidos.*, platos.nombre as plato_nombre, platos.precio, platos.imagen')
            ->join('platos', 'platos.id = pedidos.plato_id')
            ->where('pedidos.usuario_id', auth()->id())
            ->orderBy('pedidos.created_at', 'DESC')
            ->findAll();

        return view('pedido/index', $data);
    }

    public function crear()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión para hacer un pedido');
        }

        $pedidoModel = new PedidoModel();
        $platoModel = new PlatoModel();

        $platoId = $this->request->getPost('plato_id');
        $cantidad = (int)$this->request->getPost('cantidad');
        
        // Validación básica
        if ($cantidad < 1) {
            return redirect()->back()->with('error', 'La cantidad debe ser mayor a 0');
        }
        
        // Obtener el plato para calcular el total
        $plato = $platoModel->find($platoId);
        
        if (!$plato) {
            return redirect()->back()->with('error', 'Plato no encontrado');
        }

        // Verificar stock si existe
        if (isset($plato['stock']) && $plato['stock'] > 0 && $cantidad > $plato['stock']) {
            return redirect()->back()->with('error', 'No hay suficiente stock disponible');
        }

        $total = $plato['precio'] * $cantidad;
        $notas = $this->request->getPost('notas') ?? null;

        try {
            $pedidoModel->insert([
                'usuario_id' => auth()->id(),
                'plato_id'   => $platoId,
                'cantidad'   => $cantidad,
                'total'      => $total,
                'estado'     => 'pendiente',
                'notas'      => $notas
            ]);

            // Opcional: Reducir stock
            if (isset($plato['stock']) && $plato['stock'] > 0) {
                $platoModel->update($platoId, [
                    'stock' => $plato['stock'] - $cantidad
                ]);
            }

            return redirect()->to('/pedido')->with('success', 'Pedido realizado con éxito');
        } catch (\Exception $e) {
            log_message('error', 'Error al crear pedido: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar el pedido. Intenta nuevamente.');
        }
    }
}