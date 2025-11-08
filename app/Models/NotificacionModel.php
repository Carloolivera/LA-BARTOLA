<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificacionModel extends Model
{
    protected $table      = 'notificaciones';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'usuario_id',
        'tipo',
        'titulo',
        'mensaje',
        'icono',
        'url',
        'leida',
        'data'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Crear notificación para un usuario específico
     */
    public function crearNotificacion(array $data): bool
    {
        return $this->insert($data) !== false;
    }

    /**
     * Crear notificación para todos los admins
     */
    public function notificarAdmins(string $tipo, string $titulo, string $mensaje, ?string $icono = null, ?string $url = null, ?array $data = null): bool
    {
        $db = \Config\Database::connect();

        // Obtener todos los usuarios admin
        $admins = $db->table('auth_groups_users')
            ->select('user_id')
            ->where('group', 'admin')
            ->get()
            ->getResultArray();

        $notificacionData = [
            'tipo' => $tipo,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'icono' => $icono,
            'url' => $url,
            'data' => $data ? json_encode($data) : null,
            'leida' => 0,
        ];

        foreach ($admins as $admin) {
            $notificacionData['usuario_id'] = $admin['user_id'];
            $this->insert($notificacionData);
        }

        return true;
    }

    /**
     * Obtener notificaciones no leídas de un usuario
     */
    public function obtenerNoLeidas(int $usuario_id): array
    {
        return $this->where('usuario_id', $usuario_id)
            ->where('leida', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Obtener todas las notificaciones de un usuario
     */
    public function obtenerPorUsuario(int $usuario_id, int $limit = 50): array
    {
        return $this->where('usuario_id', $usuario_id)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Contar notificaciones no leídas
     */
    public function contarNoLeidas(int $usuario_id): int
    {
        return $this->where('usuario_id', $usuario_id)
            ->where('leida', 0)
            ->countAllResults();
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarComoLeida(int $id): bool
    {
        return $this->update($id, ['leida' => 1]);
    }

    /**
     * Marcar todas las notificaciones de un usuario como leídas
     */
    public function marcarTodasComoLeidas(int $usuario_id): bool
    {
        return $this->where('usuario_id', $usuario_id)
            ->set(['leida' => 1])
            ->update();
    }

    /**
     * Eliminar notificaciones antiguas (más de 30 días)
     */
    public function limpiarAntiguas(int $dias = 30): bool
    {
        $fecha_limite = date('Y-m-d H:i:s', strtotime("-{$dias} days"));

        return $this->where('created_at <', $fecha_limite)
            ->where('leida', 1)
            ->delete();
    }

    /**
     * Obtener notificaciones recientes (últimos 5 minutos)
     */
    public function obtenerRecientes(int $usuario_id, int $minutos = 5): array
    {
        $fecha_desde = date('Y-m-d H:i:s', strtotime("-{$minutos} minutes"));

        return $this->where('usuario_id', $usuario_id)
            ->where('created_at >=', $fecha_desde)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
