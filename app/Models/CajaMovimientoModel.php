<?php

namespace App\Models;

use CodeIgniter\Model;

class CajaMovimientoModel extends Model
{
    protected $table      = 'caja_movimientos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'caja_turno_id',
        'tipo',
        'concepto',
        'monto',
        'metodo_pago',
        'pedido_id',
        'usuario_id',
        'notas'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

    /**
     * Registrar movimiento de caja
     */
    public function registrarMovimiento(array $data): int
    {
        $this->insert($data);
        return $this->getInsertID();
    }

    /**
     * Registrar venta desde pedido
     */
    public function registrarVenta(int $caja_turno_id, int $pedido_id, float $monto, string $metodo_pago, int $usuario_id): int
    {
        $data = [
            'caja_turno_id' => $caja_turno_id,
            'tipo' => 'venta',
            'concepto' => "Venta - Pedido #{$pedido_id}",
            'monto' => $monto,
            'metodo_pago' => $metodo_pago,
            'pedido_id' => $pedido_id,
            'usuario_id' => $usuario_id,
        ];

        return $this->registrarMovimiento($data);
    }

    /**
     * Obtener movimientos de una caja
     */
    public function obtenerMovimientosCaja(int $caja_turno_id): array
    {
        return $this->where('caja_turno_id', $caja_turno_id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Obtener movimientos con información del usuario
     */
    public function obtenerMovimientosConUsuario(int $caja_turno_id): array
    {
        $db = \Config\Database::connect();

        return $db->table('caja_movimientos as cm')
            ->select('cm.*, u.username')
            ->join('users as u', 'u.id = cm.usuario_id')
            ->where('cm.caja_turno_id', $caja_turno_id)
            ->orderBy('cm.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
