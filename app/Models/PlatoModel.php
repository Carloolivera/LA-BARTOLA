<?php

namespace App\Models;

use CodeIgniter\Model;

class PlatoModel extends Model
{
    protected $table = 'platos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'descripcion', 'precio', 'imagen', 'activo'];
}
