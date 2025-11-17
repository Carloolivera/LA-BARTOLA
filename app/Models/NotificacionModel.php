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
}
