<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlatoModel;

class Stock extends BaseController
{
    protected $platoModel;

    public function __construct()
    {
        $this->platoModel = new PlatoModel();
    }

    public function index()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        // Obtener todos los platos ordenados por stock (los de menor stock primero)
        $platos = $this->platoModel
            ->orderBy('stock', 'ASC')
            ->orderBy('nombre', 'ASC')
            ->findAll();

        return view('admin/stock/index', ['platos' => $platos]);
    }

    public function editar($id)
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $plato = $this->platoModel->find($id);

        if (!$plato) {
            return redirect()->to('/admin/stock')->with('error', 'Plato no encontrado');
        }

        return view('admin/stock/editar', ['plato' => $plato]);
    }

    public function actualizar($id)
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $rules = [
            'stock' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $stock = (int)$this->request->getPost('stock');
        $stockIlimitado = $this->request->getPost('stock_ilimitado') ? 1 : 0;
        $disponible = $this->request->getPost('disponible') ? 1 : 0;

        // Si el stock es mayor a 0, automáticamente marcar como disponible
        if ($stock > 0 && !$stockIlimitado) {
            $disponible = 1;
        }

        // Si stock ilimitado está activado, también marcar como disponible
        if ($stockIlimitado) {
            $disponible = 1;
        }

        $data = [
            'stock' => $stock,
            'stock_ilimitado' => $stockIlimitado,
            'disponible' => $disponible,
        ];

        $this->platoModel->update($id, $data);

        return redirect()->to('/admin/stock')->with('success', 'Stock actualizado correctamente');
    }

    public function ajusteRapido()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No autorizado'
            ]);
        }

        $id = $this->request->getPost('plato_id');
        $accion = $this->request->getPost('accion'); // 'sumar' o 'restar'
        $cantidad = (int)$this->request->getPost('cantidad');

        $plato = $this->platoModel->find($id);

        if (!$plato) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Plato no encontrado'
            ]);
        }

        $nuevoStock = $plato['stock'];

        if ($accion === 'sumar') {
            $nuevoStock += $cantidad;
        } elseif ($accion === 'restar') {
            $nuevoStock = max(0, $nuevoStock - $cantidad);
        }

        // Si el stock queda en 0, marcar como no disponible
        $disponible = $plato['disponible'];
        if ($nuevoStock <= 0 && $plato['stock_ilimitado'] == 0) {
            $disponible = 0;
        } elseif ($nuevoStock > 0) {
            $disponible = 1;
        }

        $this->platoModel->update($id, [
            'stock' => $nuevoStock,
            'disponible' => $disponible
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Stock actualizado',
            'nuevo_stock' => $nuevoStock,
            'disponible' => $disponible
        ]);
    }
}
