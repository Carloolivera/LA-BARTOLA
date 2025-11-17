<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table      = 'categorias';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nombre',
        'orden',
        'activa'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nombre' => 'required|min_length[2]|max_length[100]|is_unique[categorias.nombre,id,{id}]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'  => 'El nombre de la categoría es obligatorio',
            'is_unique' => 'Ya existe una categoría con ese nombre',
        ],
    ];

    /**
     * Obtener todas las categorías activas ordenadas
     */
    public function getActivas()
    {
        return $this->where('activa', 1)
                    ->orderBy('orden', 'ASC')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener todas las categorías (activas e inactivas)
     */
    public function getTodas()
    {
        return $this->orderBy('orden', 'ASC')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }
}
