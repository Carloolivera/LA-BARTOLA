<?php

namespace App\Models;

use CodeIgniter\Model;

class PlatoModel extends Model
{
    protected $table      = 'platos';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields = [
        'nombre',
        'descripcion',
        'precio',
        'categoria',
        'disponible',
        'imagen'
    ];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[255]',
        'precio' => 'required|decimal',
    ];
    
    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del plato es obligatorio',
        ],
        'precio' => [
            'required' => 'El precio es obligatorio',
        ],
    ];
}