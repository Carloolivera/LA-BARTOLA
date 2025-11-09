<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use League\OAuth2\Client\Provider\Google;
use CodeIgniter\Shield\Models\UserModel;

class OAuth extends Controller
{
    protected $googleProvider;

    public function __construct()
    {
        helper('setting');

        // Configuración del provider de Google
        $this->googleProvider = new Google([
            'clientId'     => getenv('GOOGLE_CLIENT_ID'),
            'clientSecret' => getenv('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => base_url('oauth/google/callback'),
        ]);
    }

    /**
     * Redirige al usuario a Google para autenticación
     */
    public function googleRedirect()
    {
        // Obtener URL de autorización de Google
        $authUrl = $this->googleProvider->getAuthorizationUrl([
            'scope' => ['email', 'profile']
        ]);

        // Guardar el estado en sesión para validación posterior
        session()->set('oauth2state', $this->googleProvider->getState());

        // Redirigir a Google
        return redirect()->to($authUrl);
    }

    /**
     * Callback de Google después de la autenticación
     */
    public function googleCallback()
    {
        // Validar el estado para prevenir CSRF
        $state = $this->request->getGet('state');
        $sessionState = session()->get('oauth2state');

        if (empty($state) || ($state !== $sessionState)) {
            session()->remove('oauth2state');
            return redirect()->to('/login')->with('error', 'Estado de OAuth inválido. Por favor, intenta nuevamente.');
        }

        // Verificar si hay código de autorización
        if (!$code = $this->request->getGet('code')) {
            return redirect()->to('/login')->with('error', 'No se recibió código de autorización de Google.');
        }

        try {
            // Obtener token de acceso
            $token = $this->googleProvider->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            // Obtener detalles del usuario de Google
            $googleUser = $this->googleProvider->getResourceOwner($token);
            $googleData = $googleUser->toArray();

            // Datos del usuario
            $email = $googleData['email'];
            $name = $googleData['name'];
            $googleId = $googleData['sub']; // ID único de Google
            $picture = $googleData['picture'] ?? null;

            // Buscar usuario por identidad de Google primero
            $identityModel = model('CodeIgniter\Shield\Models\UserIdentityModel');
            $identity = $identityModel->where('type', 'google')
                                      ->where('secret', $googleId)
                                      ->first();

            // Si existe la identidad, obtener el usuario
            $userModel = new UserModel();
            if ($identity) {
                $user = $userModel->find($identity->user_id);
            } else {
                // Si no, buscar por email
                $user = $userModel->findByCredentials(['email' => $email]);
            }

            if ($user === null) {
                // Usuario no existe, crear nuevo
                $userData = [
                    'username' => $this->generateUsername($email),
                    'email'    => $email,
                    'active'   => 1,
                ];

                // Crear usuario con Shield
                $userId = $userModel->insert($userData, true);

                if (!$userId) {
                    log_message('error', 'Error al crear usuario OAuth: ' . json_encode($userModel->errors()));
                    return redirect()->to('/login')->with('error', 'Error al crear tu cuenta. Por favor, intenta nuevamente.');
                }

                // Obtener el usuario recién creado
                $user = $userModel->find($userId);

                // Guardar identidad de Google
                $identityModel = model('CodeIgniter\Shield\Models\UserIdentityModel');

                // Verificar si ya existe la identidad antes de insertar
                $existingIdentity = $identityModel->where('user_id', $userId)
                                                   ->where('type', 'google')
                                                   ->first();

                if (!$existingIdentity) {
                    $identityModel->insert([
                        'user_id' => $userId,
                        'type'    => 'google',
                        'secret'  => $googleId,
                        'name'    => $name,
                        'extra'   => json_encode([
                            'email'   => $email,
                            'picture' => $picture,
                        ]),
                    ]);
                }

                log_message('info', "Nuevo usuario creado vía Google OAuth: {$email}");
            } else {
                // Usuario existe, verificar si tiene identidad de Google
                if (!$identity) {
                    // Agregar identidad de Google al usuario existente (solo si no la tiene)
                    $identityModel->insert([
                        'user_id' => $user->id,
                        'type'    => 'google',
                        'secret'  => $googleId,
                        'name'    => $name,
                        'extra'   => json_encode([
                            'email'   => $email,
                            'picture' => $picture,
                        ]),
                    ]);
                    log_message('info', "Identidad de Google agregada a usuario existente: {$email}");
                }

                log_message('info', "Usuario existente inició sesión vía Google OAuth: {$email}");
            }

            // Iniciar sesión con Shield
            auth()->login($user);

            // Limpiar estado de sesión
            session()->remove('oauth2state');

            // Redirigir al home o a la URL anterior
            $redirect = session()->getTempdata('beforeLoginUrl') ?? '/';
            return redirect()->to($redirect)->with('success', '¡Bienvenido, ' . $name . '!');

        } catch (\Exception $e) {
            log_message('error', 'Error en Google OAuth: ' . $e->getMessage());
            return redirect()->to('/login')->with('error', 'Error al iniciar sesión con Google: ' . $e->getMessage());
        }
    }

    /**
     * Genera un username único basado en el email
     */
    private function generateUsername(string $email): string
    {
        $userModel = new UserModel();

        // Obtener la parte antes del @
        $base = explode('@', $email)[0];
        $base = preg_replace('/[^a-zA-Z0-9]/', '', $base); // Solo alfanuméricos

        // Verificar si el username ya existe
        $username = $base;
        $counter = 1;

        while ($userModel->where('username', $username)->first() !== null) {
            $username = $base . $counter;
            $counter++;
        }

        return $username;
    }
}
