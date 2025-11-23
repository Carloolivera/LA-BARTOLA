<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
        $cache = \Config\Services::cache();

        // Intentar obtener platos del caché (caché de 5 minutos)
        $platos = $cache->get('platos_disponibles');

        if ($platos === null) {
            // Si no está en caché, obtener de la base de datos
            $db = \Config\Database::connect();

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

            // Guardar en caché por 5 minutos (300 segundos)
            $cache->save('platos_disponibles', $platos, 300);
        }

        // Obtener categorías con sus descripciones
        $categorias = $cache->get('categorias_con_descripcion');
        if ($categorias === null) {
            $db = \Config\Database::connect();
            $categorias = $db->table('categorias')
                ->where('activa', 1)
                ->orderBy('orden', 'ASC')
                ->orderBy('nombre', 'ASC')
                ->get()
                ->getResultArray();

            // Guardar en caché por 5 minutos
            $cache->save('categorias_con_descripcion', $categorias, 300);
        }

        $data['platos'] = $platos;
        $data['categorias_info'] = $categorias;

        // Pasar el carrito de la sesión para restaurarlo
        $session = session();
        $data['carrito'] = $session->get('carrito') ?? [];

        return view('home', $data);
    }
}
