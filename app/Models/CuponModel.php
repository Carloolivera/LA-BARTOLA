<?php

namespace App\Models;

use CodeIgniter\Model;

class CuponModel extends Model
{
    protected $table      = 'cupones';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'codigo',
        'descripcion',
        'tipo',
        'valor',
        'monto_minimo',
        'usos_maximos',
        'usos_actuales',
        'usos_por_usuario',
        'fecha_inicio',
        'fecha_expiracion',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'codigo' => 'required|min_length[3]|max_length[50]|is_unique[cupones.codigo,id,{id}]',
        'tipo'   => 'required|in_list[porcentaje,monto_fijo]',
        'valor'  => 'required|numeric|greater_than[0]',
    ];

    protected $validationMessages = [
        'codigo' => [
            'required'  => 'El código del cupón es obligatorio',
            'is_unique' => 'Este código ya existe',
        ],
        'valor' => [
            'required'      => 'El valor del descuento es obligatorio',
            'greater_than'  => 'El valor debe ser mayor a 0',
        ],
    ];

    /**
     * Valida si un cupón puede ser usado
     */
    public function validarCupon(string $codigo, int $usuario_id, float $total_compra): array
    {
        $cupon = $this->where('codigo', $codigo)->first();

        if (!$cupon) {
            return ['valido' => false, 'mensaje' => 'Cupón no válido'];
        }

        // Verificar si está activo
        if (!$cupon['activo']) {
            return ['valido' => false, 'mensaje' => 'Este cupón no está disponible'];
        }

        // Verificar fechas
        $now = date('Y-m-d H:i:s');

        if ($cupon['fecha_inicio'] && $now < $cupon['fecha_inicio']) {
            return ['valido' => false, 'mensaje' => 'Este cupón aún no está disponible'];
        }

        if ($cupon['fecha_expiracion'] && $now > $cupon['fecha_expiracion']) {
            return ['valido' => false, 'mensaje' => 'Este cupón ha expirado'];
        }

        // Verificar monto mínimo
        if ($cupon['monto_minimo'] && $total_compra < $cupon['monto_minimo']) {
            return [
                'valido' => false,
                'mensaje' => 'Monto mínimo requerido: $' . number_format($cupon['monto_minimo'], 2)
            ];
        }

        // Verificar usos máximos globales
        if ($cupon['usos_maximos'] && $cupon['usos_actuales'] >= $cupon['usos_maximos']) {
            return ['valido' => false, 'mensaje' => 'Este cupón ha alcanzado su límite de usos'];
        }

        // Verificar usos por usuario
        $db = db_connect();
        $usosUsuario = $db->table('cupones_usos')
            ->where('cupon_id', $cupon['id'])
            ->where('usuario_id', $usuario_id)
            ->countAllResults();

        if ($usosUsuario >= $cupon['usos_por_usuario']) {
            return ['valido' => false, 'mensaje' => 'Ya has usado este cupón el máximo de veces permitido'];
        }

        // Calcular descuento
        $descuento = 0;
        if ($cupon['tipo'] === 'porcentaje') {
            $descuento = ($total_compra * $cupon['valor']) / 100;
        } else {
            $descuento = $cupon['valor'];
        }

        // El descuento no puede ser mayor que el total
        $descuento = min($descuento, $total_compra);

        return [
            'valido' => true,
            'cupon' => $cupon,
            'descuento' => $descuento,
            'mensaje' => '¡Cupón aplicado! Descuento: $' . number_format($descuento, 2)
        ];
    }

    /**
     * Registra el uso de un cupón
     */
    public function registrarUso(int $cupon_id, int $usuario_id, float $descuento, int $pedido_id = null): bool
    {
        $db = db_connect();

        // Insertar registro de uso
        $db->table('cupones_usos')->insert([
            'cupon_id' => $cupon_id,
            'usuario_id' => $usuario_id,
            'pedido_id' => $pedido_id,
            'descuento_aplicado' => $descuento,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Incrementar contador de usos
        $this->set('usos_actuales', 'usos_actuales + 1', false)
             ->where('id', $cupon_id)
             ->update();

        return true;
    }

    /**
     * Obtiene los usos de un cupón
     */
    public function obtenerUsos(int $cupon_id): array
    {
        $db = db_connect();

        return $db->table('cupones_usos')
            ->select('cupones_usos.*, users.username, users.email')
            ->join('users', 'users.id = cupones_usos.usuario_id')
            ->where('cupon_id', $cupon_id)
            ->orderBy('cupones_usos.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
