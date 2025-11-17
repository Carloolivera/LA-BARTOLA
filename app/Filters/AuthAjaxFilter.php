<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthAjaxFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Si el usuario está logueado, permitir acceso
        if (auth()->loggedIn()) {
            return;
        }

        // Si es una petición AJAX, no bloquear aquí
        // Dejar que el controlador maneje la respuesta JSON
        if ($request->isAJAX()) {
            return;
        }

        // Si no está logueado y no es AJAX, redirigir a login
        session()->set('redirect_url', current_url());
        return redirect()->to('/login')->with('error', 'Debe iniciar sesión');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
