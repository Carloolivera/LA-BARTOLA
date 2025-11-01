<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PedidoModel;
use App\Models\PlatoModel;

class Admin extends Controller
{
    protected $helpers = ['auth'];

    public function __construct()
    {
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin', 'superadmin')) {
            redirect()->to('/')->send();
            exit;
        }
    }

    public function pedidos()
    {
        $pedidoModel = new PedidoModel();
        
        $data['pedidos'] = $pedidoModel
            ->select('pedidos.*, platos.nombre as plato_nombre, platos.precio, users.username')
            ->join('platos', 'platos.id = pedidos.plato_id')
            ->join('users', 'users.id = pedidos.usuario_id')
            ->orderBy('pedidos.created_at', 'DESC')
            ->findAll();
        
        return view('admin/pedidos', $data);
    }

    public function stock()
    {
        $platoModel = new PlatoModel();
        
        $data['platos'] = $platoModel->findAll();
        
        return view('admin/stock', $data);
    }

    public function usuarios()
    {
        $db = \Config\Database::connect();
        
        $data['usuarios'] = $db->table('users')
            ->select('users.*, auth_groups_users.group')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
            ->get()
            ->getResultArray();
        
        return view('admin/usuarios', $data);
    }

    public function actualizarEstadoPedido()
    {
        $pedidoModel = new PedidoModel();
        
        $id = $this->request->getPost('id');
        $estado = $this->request->getPost('estado');
        
        $pedidoModel->update($id, ['estado' => $estado]);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ]);
    }

    public function agregarPlato()
    {
        $platoModel = new PlatoModel();
        
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio'),
            'imagen' => $this->request->getPost('imagen'),
            'activo' => 1
        ];
        
        $platoModel->insert($data);
        
        return redirect()->to('/admin/stock')->with('success', 'Plato agregado correctamente');
    }

    public function editarPlato($id)
    {
        $platoModel = new PlatoModel();
        
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio'),
            'imagen' => $this->request->getPost('imagen'),
            'activo' => $this->request->getPost('activo')
        ];
        
        $platoModel->update($id, $data);
        
        return redirect()->to('/admin/stock')->with('success', 'Plato actualizado correctamente');
    }

    public function eliminarPlato($id)
    {
        $platoModel = new PlatoModel();
        $platoModel->delete($id);
        
        return redirect()->to('/admin/stock')->with('success', 'Plato eliminado correctamente');
    }
}