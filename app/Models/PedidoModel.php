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
        'notas'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'usuario_id' => 'required|integer',
        'plato_id' => 'required|integer',
        'cantidad' => 'required|integer|greater_than[0]',
    ];
}