<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PlatoModel;

class Menu extends Controller
{
    public function index()
    {
        $platoModel = new PlatoModel();

        // Si es admin, muestra vista con gestión
        if (auth()->loggedIn() && auth()->user()->inGroup('admin')) {
            $data['platos'] = $platoModel->orderBy('categoria', 'ASC')->findAll();
            return view('admin/menu_admin', $data);
        }

        // Si es público, solo muestra platos disponibles
        $data['platos'] = $platoModel
            ->where('disponible', 1)
            ->orderBy('categoria', 'ASC')
            ->findAll();
        
        return view('menu/index', $data);
    }
}