<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'usuario_id',
        'plato_id',
        'cantidad',
        'estado',
        'total',
        'notas',
        'tipo_entrega',  // Agregado
        'direccion',     // Agregado
        'forma_pago',    // Agregado
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'usuario_id' => 'permit_empty|integer',  // Cambiado: permite NULL para pedidos públicos
        'plato_id' => 'required|integer',
        'cantidad' => 'required|integer|greater_than[0]',
        'total' => 'required|decimal',  // Agregado
        'estado' => 'permit_empty|in_list[pendiente,en_proceso,completado,cancelado]',  // Agregado
    ];
}