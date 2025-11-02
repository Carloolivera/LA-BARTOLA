<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Shield\Entities\User;

class Usuario extends Controller
{
    protected $helpers = ['auth', 'form'];

    public function __construct()
    {
        if (!auth()->loggedIn() || !auth()->user()->inGroup('admin')) {
            redirect()->to('/')->send();
            exit;
        }
    }

    public function index()
    {
        $db = \Config\Database::connect();
        
        $usuarios = $db->table('users')
            ->select('users.id, users.username, users.active, users.created_at, auth_groups_users.group')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
            ->orderBy('users.id', 'DESC')
            ->get()
            ->getResultArray();
        
        $data['usuarios'] = $usuarios;
        
        return view('usuario/index', $data);
    }

    public function crear()
    {
        return view('usuario/crear');
    }

    public function guardar()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[8]',
            'grupo'    => 'required|in_list[admin,vendedor,cliente]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $users = auth()->getProvider();

        $user = new User([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        $users->save($user);

        $user = $users->findById($users->getInsertID());
        
        $grupo = $this->request->getPost('grupo');
        $user->addGroup($grupo);

        return redirect()->to('/usuario')->with('success', 'Usuario creado exitosamente');
    }

    public function editar($id)
    {
        $db = \Config\Database::connect();
        
        $usuario = $db->table('users')
            ->select('users.*, auth_groups_users.group')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
            ->where('users.id', $id)
            ->get()
            ->getRowArray();

        if (!$usuario) {
            return redirect()->to('/usuario')->with('error', 'Usuario no encontrado');
        }

        $data['usuario'] = $usuario;
        
        return view('usuario/editar', $data);
    }

    public function actualizar($id)
    {
        $db = \Config\Database::connect();
        
        $usuario = $db->table('users')->where('id', $id)->get()->getRowArray();
        
        if (!$usuario) {
            return redirect()->to('/usuario')->with('error', 'Usuario no encontrado');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[30]|is_unique[users.username,id,{$id}]",
            'grupo'    => 'required|in_list[admin,vendedor,cliente]',
            'active'   => 'required|in_list[0,1]'
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'username' => $this->request->getPost('username'),
            'active'   => $this->request->getPost('active')
        ];

        $db->table('users')->where('id', $id)->update($updateData);

        if ($this->request->getPost('password')) {
            $users = auth()->getProvider();
            $user = $users->findById($id);
            $user->password = $this->request->getPost('password');
            $users->save($user);
        }

        $grupo = $this->request->getPost('grupo');
        $db->table('auth_groups_users')->where('user_id', $id)->delete();
        $db->table('auth_groups_users')->insert([
            'user_id' => $id,
            'group'   => $grupo
        ]);

        return redirect()->to('/usuario')->with('success', 'Usuario actualizado exitosamente');
    }

    public function eliminar($id)
    {
        if ($id == auth()->id()) {
            return redirect()->to('/usuario')->with('error', 'No puedes eliminar tu propio usuario');
        }

        $db = \Config\Database::connect();
        
        $db->table('auth_groups_users')->where('user_id', $id)->delete();
        $db->table('auth_identities')->where('user_id', $id)->delete();
        $db->table('users')->where('id', $id)->delete();

        return redirect()->to('/usuario')->with('success', 'Usuario eliminado exitosamente');
    }

    public function toggleEstado($id)
    {
        if ($id == auth()->id()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No puedes desactivar tu propio usuario'
            ]);
        }

        $db = \Config\Database::connect();
        $usuario = $db->table('users')->where('id', $id)->get()->getRowArray();

        if (!$usuario) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }

        $nuevoEstado = $usuario['active'] == 1 ? 0 : 1;
        $db->table('users')->where('id', $id)->update(['active' => $nuevoEstado]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Estado actualizado',
            'active'  => $nuevoEstado
        ]);
    }
}