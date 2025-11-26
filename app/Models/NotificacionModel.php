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
        'user_id',
        'titulo',
        'mensaje',
        'url',
        'icono',
        'leida',
        'tipo'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Obtener notificaciones de un usuario
     */
    public function getByUser($userId, $limit = 20)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Contar notificaciones no leídas
     */
    public function countUnread($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('leida', 0)
                    ->countAllResults();
    }

    /**
     * Marcar como leída
     */
    public function markAsRead($id)
    {
        return $this->update($id, ['leida' => 1]);
    }

    /**
     * Marcar todas como leídas
     */
    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)
                    ->set(['leida' => 1])
                    ->update();
    }

    /**
     * Crear una nueva notificación
     *
     * @param array $data Datos de la notificación: usuario_id, tipo, titulo, mensaje, icono, url, leida, data
     * @return int|false ID de la notificación creada o false si falla
     */
    public function crearNotificacion($data)
    {
        // Mapear campos si vienen con nombres diferentes
        $notificacion = [
            'user_id' => $data['usuario_id'] ?? $data['user_id'] ?? null,
            'tipo'    => $data['tipo'] ?? 'general',
            'titulo'  => $data['titulo'] ?? '',
            'mensaje' => $data['mensaje'] ?? '',
            'icono'   => $data['icono'] ?? 'bi-bell',
            'url'     => $data['url'] ?? '',
            'leida'   => $data['leida'] ?? 0,
        ];

        // Validar que al menos haya un user_id
        if (empty($notificacion['user_id'])) {
            log_message('error', 'NotificacionModel::crearNotificacion - user_id es requerido');
            return false;
        }

        try {
            return $this->insert($notificacion);
        } catch (\Exception $e) {
            log_message('error', 'NotificacionModel::crearNotificacion - Error: ' . $e->getMessage());
            return false;
        }
    }
}
