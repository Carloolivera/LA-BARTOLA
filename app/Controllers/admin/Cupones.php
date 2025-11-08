<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CuponModel;

class Cupones extends BaseController
{
    protected $cuponModel;

    public function __construct()
    {
        $this->cuponModel = new CuponModel();
    }

    /**
     * Lista de cupones
     */
    public function index()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $data['cupones'] = $this->cuponModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/cupones/index', $data);
    }

    /**
     * Formulario de crear cupón
     */
    public function crear()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        return view('admin/cupones/crear');
    }

    /**
     * Guardar nuevo cupón
     */
    public function guardar()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $rules = [
            'codigo' => 'required|min_length[3]|max_length[50]|is_unique[cupones.codigo]',
            'tipo'   => 'required|in_list[porcentaje,monto_fijo]',
            'valor'  => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'codigo'            => strtoupper($this->request->getPost('codigo')),
            'descripcion'       => $this->request->getPost('descripcion'),
            'tipo'              => $this->request->getPost('tipo'),
            'valor'             => $this->request->getPost('valor'),
            'monto_minimo'      => $this->request->getPost('monto_minimo') ?: null,
            'usos_maximos'      => $this->request->getPost('usos_maximos') ?: null,
            'usos_por_usuario'  => $this->request->getPost('usos_por_usuario') ?: 1,
            'fecha_inicio'      => $this->request->getPost('fecha_inicio') ?: null,
            'fecha_expiracion'  => $this->request->getPost('fecha_expiracion') ?: null,
            'activo'            => $this->request->getPost('activo') ? 1 : 0,
        ];

        try {
            $this->cuponModel->insert($data);
            return redirect()->to('/admin/cupones')->with('success', 'Cupón creado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al crear cupón: ' . $e->getMessage());
        }
    }

    /**
     * Formulario de editar cupón
     */
    public function editar($id)
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $cupon = $this->cuponModel->find($id);

        if (!$cupon) {
            return redirect()->to('/admin/cupones')->with('error', 'Cupón no encontrado');
        }

        // Obtener usos del cupón
        $cupon['usos'] = $this->cuponModel->obtenerUsos($id);

        $data['cupon'] = $cupon;

        return view('admin/cupones/editar', $data);
    }

    /**
     * Actualizar cupón
     */
    public function actualizar($id)
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $rules = [
            'codigo' => "required|min_length[3]|max_length[50]|is_unique[cupones.codigo,id,{$id}]",
            'tipo'   => 'required|in_list[porcentaje,monto_fijo]',
            'valor'  => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'codigo'            => strtoupper($this->request->getPost('codigo')),
            'descripcion'       => $this->request->getPost('descripcion'),
            'tipo'              => $this->request->getPost('tipo'),
            'valor'             => $this->request->getPost('valor'),
            'monto_minimo'      => $this->request->getPost('monto_minimo') ?: null,
            'usos_maximos'      => $this->request->getPost('usos_maximos') ?: null,
            'usos_por_usuario'  => $this->request->getPost('usos_por_usuario') ?: 1,
            'fecha_inicio'      => $this->request->getPost('fecha_inicio') ?: null,
            'fecha_expiracion'  => $this->request->getPost('fecha_expiracion') ?: null,
            'activo'            => $this->request->getPost('activo') ? 1 : 0,
        ];

        try {
            $this->cuponModel->update($id, $data);
            return redirect()->to('/admin/cupones')->with('success', 'Cupón actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar cupón: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar cupón
     */
    public function eliminar($id)
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $this->cuponModel->delete($id);
        return redirect()->to('/admin/cupones')->with('success', 'Cupón eliminado correctamente');
    }

    /**
     * Cambiar estado (activar/desactivar)
     */
    public function toggleEstado($id)
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $cupon = $this->cuponModel->find($id);

        if (!$cupon) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cupón no encontrado']);
        }

        $nuevoEstado = $cupon['activo'] ? 0 : 1;
        $this->cuponModel->update($id, ['activo' => $nuevoEstado]);

        return $this->response->setJSON([
            'success' => true,
            'nuevoEstado' => $nuevoEstado
        ]);
    }
}
