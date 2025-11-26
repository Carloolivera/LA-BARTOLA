<?php

namespace App\Controllers\Auth;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Controllers\LoginController as ShieldLogin;

class LoginController extends ShieldLogin
{
    public function loginView()
    {
        // Guardar el redirect si viene en la URL
        if ($redirect = $this->request->getGet('redirect')) {
            session()->set('redirect_url', urldecode($redirect));
        }
        
        return parent::loginView();
    }

    public function loginAction(): RedirectResponse
    {
        $response = parent::loginAction();
        
        // Si el login fue exitoso, verificar si hay redirección guardada
        if (auth()->loggedIn() && session()->has('redirect_url')) {
            $redirectUrl = session()->get('redirect_url');
            session()->remove('redirect_url');
            return redirect()->to($redirectUrl)->with('message', 'Bienvenido de nuevo');
        }
        
        return $response;
    }
}