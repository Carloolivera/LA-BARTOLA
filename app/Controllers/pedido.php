<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PedidoModel;
use App\Models\PlatoModel;

class Pedido extends Controller
{
    public function index()
    {
        helper(['form']);

        $platoModel = new PlatoModel();
        $data['platos'] = $platoModel->findAll();

        return view('pedido/index', $data);
    }

    public function crear()
    {
        helper(['form']);
        $pedidoModel = new PedidoModel();

        $pedidoModel->insert([
            'usuario_id' => auth()->id(),
            'plato_id'   => $this->request->getPost('plato_id'),
            'cantidad'   => $this->request->getPost('cantidad'),
            'estado'     => 'pendiente',
        ]);

        return redirect()->to('/pedido')->with('success', 'Pedido realizado con éxito.');
    }
}
