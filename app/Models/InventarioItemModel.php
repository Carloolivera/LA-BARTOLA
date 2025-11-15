<?php

namespace App\Models;

use CodeIgniter\Model;

class InventarioItemModel extends Model
{
    protected $table            = 'inventario_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'categoria_id', 'nombre', 'descripcion', 'unidad_medida',
        'cantidad_actual', 'cantidad_minima', 'precio_unitario',
        'proveedor', 'ubicacion', 'activo'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'cantidad_actual' => 'float',
        'cantidad_minima' => 'float',
        'precio_unitario' => 'float',
        'activo' => 'boolean',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'categoria_id'    => 'required|integer',
        'nombre'          => 'required|min_length[3]|max_length[255]',
        'unidad_medida'   => 'required|max_length[50]',
        'cantidad_actual' => 'permit_empty|decimal|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Obtener items con su categoría
     */
    public function getItemsConCategoria($categoriaId = null)
    {
        $builder = $this->db->table($this->table)
            ->select('inventario_items.*, inventario_categorias.nombre as categoria_nombre, inventario_categorias.color as categoria_color, inventario_categorias.icono as categoria_icono')
            ->join('inventario_categorias', 'inventario_categorias.id = inventario_items.categoria_id');

        if ($categoriaId) {
            $builder->where('inventario_items.categoria_id', $categoriaId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener items con stock bajo
     */
    public function getItemsStockBajo()
    {
        return $this->db->table($this->table)
            ->select('inventario_items.*, inventario_categorias.nombre as categoria_nombre, inventario_categorias.color as categoria_color')
            ->join('inventario_categorias', 'inventario_categorias.id = inventario_items.categoria_id')
            ->where('inventario_items.cantidad_minima IS NOT NULL')
            ->where('inventario_items.cantidad_actual <= inventario_items.cantidad_minima')
            ->where('inventario_items.activo', 1)
            ->get()
            ->getResultArray();
    }
}
