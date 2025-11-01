<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Contacto extends Controller
{
    public function index()
    {
        helper(['form']);
        return view('contacto/index');
    }

    public function enviar()
    {
        $nombre  = $this->request->getPost('nombre');
        $email   = $this->request->getPost('email');
        $mensaje = $this->request->getPost('mensaje');

        // Aquí se podría integrar un servicio de correo
        // o simplemente registrar en base de datos.

        return redirect()->to('/contacto')->with('success', 'Mensaje enviado correctamente.');
    }
}
