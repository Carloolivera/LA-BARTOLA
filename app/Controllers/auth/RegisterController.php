<?php

namespace App\Controllers\Auth;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Controllers\RegisterController as ShieldRegister;

class RegisterController extends ShieldRegister
{
    public function registerView()
    {
        // Guardar el redirect si viene en la URL
        if ($redirect = $this->request->getGet('redirect')) {
            session()->set('redirect_url', urldecode($redirect));
        }
        
        return parent::registerView();
    }

    public function registerAction(): RedirectResponse
    {
        $response = parent::registerAction();
        
        // Si el registro fue exitoso, verificar si hay redirección guardada
        if (auth()->loggedIn() && session()->has('redirect_url')) {
            $redirectUrl = session()->get('redirect_url');
            session()->remove('redirect_url');
            return redirect()->to($redirectUrl)->with('message', 'Registro exitoso. Bienvenido!');
        }
        
        return $response;
    }
}