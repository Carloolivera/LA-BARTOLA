<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClienteGroup extends Migration
{
    public function up()
    {
        // Verificar si el grupo 'cliente' ya existe
        $builder = $this->db->table('auth_groups');
        $existing = $builder->where('group', 'cliente')->get()->getRow();

        if (!$existing) {
            // Insertar el grupo 'cliente' si no existe
            $builder->insert([
                'group'       => 'cliente',
                'title'       => 'Cliente',
                'description' => 'Clientes del restaurante que pueden realizar pedidos.',
            ]);
        }
    }

    public function down()
    {
        // Eliminar el grupo 'cliente'
        $this->db->table('auth_groups')
            ->where('group', 'cliente')
            ->delete();
    }
}
