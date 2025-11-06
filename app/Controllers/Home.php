<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Obtener solo los platos disponibles Y con stock (igual que en Menu)
        $platos = $db->table('platos')
            ->where('disponible', 1)
            ->groupStart()
                ->where('stock_ilimitado', 1)
                ->orWhere('stock >', 0)
            ->groupEnd()
            ->orderBy('categoria', 'ASC')
            ->orderBy('nombre', 'ASC')
            ->get()
            ->getResultArray();

        $data['platos'] = $platos;

        return view('home', $data);
    }
}
