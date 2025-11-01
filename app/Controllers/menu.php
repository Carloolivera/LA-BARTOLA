<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PlatoModel;

class Menu extends Controller
{
    public function index()
    {
        $platoModel = new PlatoModel();

        // Obtiene los platos activos (si existe la tabla)
        $data['platos'] = $platoModel->findAll();

        return view('menu/index', $data);
    }
}
