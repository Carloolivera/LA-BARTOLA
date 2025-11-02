<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PedidoModel;
use App\Models\PlatoModel;

class Pedidos extends BaseController
{
    protected $pedidoModel;
    protected $platoModel;

    public function __construct()
    {
        $this->pedidoModel = new PedidoModel();
        $this->platoModel = new PlatoModel();
    }

    public function index()
    {
        $data['pedidos'] = $this->pedidoModel
            ->select('pedidos.*, users.username, platos.nombre as plato_nombre, platos.precio as plato_precio')
            ->join('users', 'users.id = pedidos.usuario_id', 'left')
            ->join('platos', 'platos.id = pedidos.plato_id')
            ->orderBy('pedidos.created_at', 'DESC')
            ->findAll();

        return view('admin/pedidos/index', $data);
    }

    public function ver($id)
    {
        $pedido = $this->pedidoModel
            ->select('pedidos.*, users.username, users.email, platos.nombre as plato_nombre, platos.precio as plato_precio, platos.imagen')
            ->join('users', 'users.id = pedidos.usuario_id', 'left')
            ->join('platos', 'platos.id = pedidos.plato_id')
            ->find($id);

        if (!$pedido) {
            return redirect()->to('/admin/pedidos')->with('error', 'Pedido no encontrado');
        }

        $data['pedido'] = $pedido;
        return view('admin/pedidos/ver', $data);
    }

    public function editar($id)
    {
        $pedido = $this->pedidoModel->find($id);

        if (!$pedido) {
            return redirect()->to('/admin/pedidos')->with('error', 'Pedido no encontrado');
        }

        if ($this->request->getMethod() === 'post') {
            $estado = $this->request->getPost('estado');
            $notas = $this->request->getPost('notas');

            $this->pedidoModel->update($id, [
                'estado' => $estado,
                'notas' => $notas
            ]);

            return redirect()->to('/admin/pedidos')->with('success', 'Pedido actualizado exitosamente');
        }

        $data['pedido'] = $pedido;
        $data['estados'] = ['pendiente', 'en_preparacion', 'listo', 'entregado', 'cancelado'];
        
        return view('admin/pedidos/editar', $data);
    }

    public function cambiarEstado($id)
    {
        $estado = $this->request->getPost('estado');
        
        $this->pedidoModel->update($id, ['estado' => $estado]);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ]);
    }

    public function eliminar($id)
    {
        $this->pedidoModel->delete($id);
        return redirect()->to('/admin/pedidos')->with('success', 'Pedido eliminado exitosamente');
    }
}