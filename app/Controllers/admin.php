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
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
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

    // ==================== CRUD PLATOS ====================
    
    public function stock()
    {
        $platoModel = new PlatoModel();
        $data['platos'] = $platoModel->orderBy('categoria', 'ASC')->findAll();
        
        return view('admin/stock', $data);
    }

    public function crearPlato()
    {
        return view('admin/plato_crear');
    }

    public function guardarPlato()
    {
        $platoModel = new PlatoModel();
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nombre' => 'required|min_length[3]|max_length[255]',
            'precio' => 'required|decimal',
            'stock' => 'required|integer',
            'categoria' => 'required|max_length[100]',
            'imagen' => 'uploaded[imagen]|max_size[imagen,5120]|ext_in[imagen,jpg,jpeg,png,gif,webp]'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Subir imagen
        $img = $this->request->getFile('imagen');
        $nombreImagen = null;
        
        if ($img->isValid() && !$img->hasMoved()) {
            $nombreImagen = $img->getRandomName();
            $img->move(ROOTPATH . 'public/assets/images/platos', $nombreImagen);
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio'),
            'categoria' => $this->request->getPost('categoria'),
            'stock' => $this->request->getPost('stock'),
            'disponible' => $this->request->getPost('disponible') ? 1 : 0,
            'imagen' => $nombreImagen
        ];
        
        $platoModel->insert($data);
        
        return redirect()->to('/menu')->with('success', 'Plato creado correctamente');
    }

    public function editarPlato($id)
    {
        $platoModel = new PlatoModel();
        $data['plato'] = $platoModel->find($id);
        
        if (!$data['plato']) {
            return redirect()->to('/menu')->with('error', 'Plato no encontrado');
        }
        
        return view('admin/plato_editar', $data);
    }

    public function actualizarPlato($id)
    {
        $platoModel = new PlatoModel();
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nombre' => 'required|min_length[3]|max_length[255]',
            'precio' => 'required|decimal',
            'stock' => 'required|integer',
            'categoria' => 'required|max_length[100]',
            'imagen' => 'if_exist|max_size[imagen,2048]|is_image[imagen]'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $plato = $platoModel->find($id);
        $nombreImagen = $plato['imagen'];

        // Si se sube nueva imagen
        $img = $this->request->getFile('imagen');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            // Eliminar imagen anterior si existe
            if ($nombreImagen && file_exists(ROOTPATH . 'public/assets/images/platos/' . $nombreImagen)) {
                unlink(ROOTPATH . 'public/assets/images/platos/' . $nombreImagen);
            }
            
            $nombreImagen = $img->getRandomName();
            $img->move(ROOTPATH . 'public/assets/images/platos', $nombreImagen);
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio'),
            'categoria' => $this->request->getPost('categoria'),
            'stock' => $this->request->getPost('stock'),
            'disponible' => $this->request->getPost('disponible') ? 1 : 0,
            'imagen' => $nombreImagen
        ];
        
        $platoModel->update($id, $data);
        
        return redirect()->to('/menu')->with('success', 'Plato actualizado correctamente');
    }

    public function eliminarPlato($id)
    {
        $platoModel = new PlatoModel();
        $plato = $platoModel->find($id);
        
        if ($plato) {
            // Eliminar imagen si existe
            if ($plato['imagen'] && file_exists(ROOTPATH . 'public/assets/images/platos/' . $plato['imagen'])) {
                unlink(ROOTPATH . 'public/assets/images/platos/' . $plato['imagen']);
            }
            
            $platoModel->delete($id);
            return redirect()->to('/menu')->with('success', 'Plato eliminado correctamente');
        }
        
        return redirect()->to('/menu')->with('error', 'Plato no encontrado');
    }

    // ==================== FIN CRUD PLATOS ====================

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
}