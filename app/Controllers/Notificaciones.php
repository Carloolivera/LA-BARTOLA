<?php

namespace App\Controllers;

use App\Models\NotificacionModel;

class Notificaciones extends BaseController
{
    protected $notificacionModel;

    public function __construct()
    {
        $this->notificacionModel = new NotificacionModel();
    }

    /**
     * Obtener notificaciones del usuario (AJAX)
     */
    public function obtener()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $usuario_id = auth()->id();
        $notificaciones = $this->notificacionModel->obtenerPorUsuario($usuario_id, 20);
        $no_leidas = $this->notificacionModel->contarNoLeidas($usuario_id);

        return $this->response->setJSON([
            'success' => true,
            'notificaciones' => $notificaciones,
            'no_leidas' => $no_leidas
        ]);
    }

    /**
     * Server-Sent Events para notificaciones en tiempo real
     */
    public function stream()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setStatusCode(401)->setBody('No autorizado');
        }

        $usuario_id = auth()->id();

        // Configurar headers para SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Deshabilitar buffering en nginx

        // Deshabilitar output buffering de PHP
        if (ob_get_level()) ob_end_clean();

        // Mantener la conexión abierta
        set_time_limit(0);

        $last_check = time();

        while (true) {
            // Verificar si la conexión sigue activa
            if (connection_aborted()) {
                break;
            }

            // Obtener notificaciones nuevas (últimos 30 segundos)
            $notificaciones = $this->notificacionModel->obtenerRecientes($usuario_id, 1);

            if (!empty($notificaciones)) {
                $no_leidas = $this->notificacionModel->contarNoLeidas($usuario_id);

                $data = [
                    'notificaciones' => $notificaciones,
                    'no_leidas' => $no_leidas,
                    'timestamp' => time()
                ];

                // Enviar evento SSE
                echo "data: " . json_encode($data) . "\n\n";
                flush();
            }

            // Enviar heartbeat cada 30 segundos
            if (time() - $last_check >= 30) {
                echo ": heartbeat\n\n";
                flush();
                $last_check = time();
            }

            // Esperar 2 segundos antes de la siguiente verificación
            sleep(2);
        }
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarLeida($id = null)
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID no proporcionado']);
        }

        $usuario_id = auth()->id();
        $notificacion = $this->notificacionModel->find($id);

        // Verificar que la notificación pertenezca al usuario
        if (!$notificacion || $notificacion['usuario_id'] != $usuario_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Notificación no encontrada']);
        }

        $this->notificacionModel->marcarComoLeida($id);

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Marcar todas como leídas
     */
    public function marcarTodasLeidas()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $usuario_id = auth()->id();
        $this->notificacionModel->marcarTodasComoLeidas($usuario_id);

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Vista del panel de notificaciones
     */
    public function index()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Debe iniciar sesión');
        }

        $usuario_id = auth()->id();
        $data['notificaciones'] = $this->notificacionModel->obtenerPorUsuario($usuario_id, 100);

        return view('notificaciones/index', $data);
    }

    /**
     * Eliminar notificación
     */
    public function eliminar($id = null)
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID no proporcionado']);
        }

        $usuario_id = auth()->id();
        $notificacion = $this->notificacionModel->find($id);

        // Verificar que la notificación pertenezca al usuario
        if (!$notificacion || $notificacion['usuario_id'] != $usuario_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Notificación no encontrada']);
        }

        $this->notificacionModel->delete($id);

        return $this->response->setJSON(['success' => true]);
    }
}
