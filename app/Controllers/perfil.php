<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Perfil extends Controller
{
    public function index()
    {
        if (! auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $data['usuario'] = auth()->user();
        return view('perfil/index', $data);
    }
}
