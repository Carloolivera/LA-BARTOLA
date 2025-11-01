<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PlatoModel;

class Carrito extends Controller
{
    public function index()
    {
        helper(['form']);
        $platoModel = new PlatoModel();
        
        $data['platos'] = $platoModel->findAll();
        
        return view('carrito/index', $data);
    }

    public function agregar()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Producto agregado al carrito'
        ]);
    }

    public function actualizar()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Carrito actualizado'
        ]);
    }

    public function eliminar()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Producto eliminado del carrito'
        ]);
    }

    public function vaciar()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Carrito vaciado'
        ]);
    }

    public function checkout()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión para finalizar la compra');
        }

        return view('carrito/checkout');
    }
}