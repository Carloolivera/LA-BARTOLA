<?php

namespace App\Models;

use CodeIgniter\Model;

class CajaModel extends Model
{
    protected $table      = 'caja_turnos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'usuario_id',
        'fecha_apertura',
        'fecha_cierre',
        'monto_inicial',
        'monto_final',
        'monto_esperado',
        'diferencia',
        'estado',
        'notas_apertura',
        'notas_cierre'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Obtener caja abierta actual
     */
    public function obtenerCajaAbierta(): ?array
    {
        return $this->where('estado', 'abierta')
            ->orderBy('fecha_apertura', 'DESC')
            ->first();
    }

    /**
     * Abrir nueva caja
     */
    public function abrirCaja(int $usuario_id, float $monto_inicial, ?string $notas = null): int
    {
        // Verificar que no haya una caja abierta
        $cajaAbierta = $this->obtenerCajaAbierta();
        if ($cajaAbierta) {
            throw new \Exception('Ya existe una caja abierta. Debe cerrarla antes de abrir una nueva.');
        }

        $data = [
            'usuario_id' => $usuario_id,
            'fecha_apertura' => date('Y-m-d H:i:s'),
            'monto_inicial' => $monto_inicial,
            'estado' => 'abierta',
            'notas_apertura' => $notas
        ];

        $this->insert($data);
        return $this->getInsertID();
    }

    /**
     * Cerrar caja
     */
    public function cerrarCaja(int $caja_id, float $monto_final, ?string $notas = null): bool
    {
        $caja = $this->find($caja_id);

        if (!$caja || $caja['estado'] !== 'abierta') {
            throw new \Exception('La caja no está abierta o no existe.');
        }

        // Calcular monto esperado
        $monto_esperado = $this->calcularMontoEsperado($caja_id);
        $diferencia = $monto_final - $monto_esperado;

        return $this->update($caja_id, [
            'fecha_cierre' => date('Y-m-d H:i:s'),
            'monto_final' => $monto_final,
            'monto_esperado' => $monto_esperado,
            'diferencia' => $diferencia,
            'estado' => 'cerrada',
            'notas_cierre' => $notas
        ]);
    }

    /**
     * Calcular monto esperado (inicial + ingresos - egresos)
     */
    public function calcularMontoEsperado(int $caja_id): float
    {
        $caja = $this->find($caja_id);
        $db = \Config\Database::connect();

        // Sumar ingresos (ventas en efectivo + ingresos manuales)
        $ingresos = $db->table('caja_movimientos')
            ->selectSum('monto')
            ->where('caja_turno_id', $caja_id)
            ->whereIn('tipo', ['ingreso', 'venta'])
            ->where('metodo_pago', 'efectivo')
            ->get()
            ->getRow()
            ->monto ?? 0;

        // Restar egresos
        $egresos = $db->table('caja_movimientos')
            ->selectSum('monto')
            ->where('caja_turno_id', $caja_id)
            ->where('tipo', 'egreso')
            ->get()
            ->getRow()
            ->monto ?? 0;

        return $caja['monto_inicial'] + $ingresos - $egresos;
    }

    /**
     * Obtener resumen de movimientos
     */
    public function obtenerResumen(int $caja_id): array
    {
        $db = \Config\Database::connect();

        $resumen = [
            'total_ingresos_efectivo' => 0,
            'total_egresos' => 0,
            'total_ventas_efectivo' => 0,
            'total_ventas_qr' => 0,
            'total_ventas_tarjeta' => 0,
            'total_ventas_mercadopago' => 0,
            'cantidad_ventas' => 0,
        ];

        // Ingresos manuales en efectivo
        $ingresos = $db->table('caja_movimientos')
            ->selectSum('monto')
            ->where('caja_turno_id', $caja_id)
            ->where('tipo', 'ingreso')
            ->where('metodo_pago', 'efectivo')
            ->get()
            ->getRow();

        $resumen['total_ingresos_efectivo'] = $ingresos->monto ?? 0;

        // Egresos
        $egresos = $db->table('caja_movimientos')
            ->selectSum('monto')
            ->where('caja_turno_id', $caja_id)
            ->where('tipo', 'egreso')
            ->get()
            ->getRow();

        $resumen['total_egresos'] = $egresos->monto ?? 0;

        // Ventas por método de pago
        $ventas = $db->table('caja_movimientos')
            ->select('metodo_pago, SUM(monto) as total, COUNT(*) as cantidad')
            ->where('caja_turno_id', $caja_id)
            ->where('tipo', 'venta')
            ->groupBy('metodo_pago')
            ->get()
            ->getResultArray();

        foreach ($ventas as $venta) {
            $metodo = $venta['metodo_pago'] ?? 'efectivo';
            $resumen["total_ventas_{$metodo}"] = $venta['total'];
            $resumen['cantidad_ventas'] += $venta['cantidad'];
        }

        return $resumen;
    }

    /**
     * Obtener historial de cajas
     */
    public function obtenerHistorial(int $limit = 30): array
    {
        $db = \Config\Database::connect();

        return $db->table('caja_turnos as c')
            ->select('c.*, u.username')
            ->join('users as u', 'u.id = c.usuario_id')
            ->orderBy('c.fecha_apertura', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
