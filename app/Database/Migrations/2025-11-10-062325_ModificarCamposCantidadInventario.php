<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModificarCamposCantidadInventario extends Migration
{
    public function up()
    {
        // Modificar campos de cantidad en inventario_items a INT
        $fields = [
            'cantidad_actual' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 0,
            ],
            'cantidad_minima' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('inventario_items', $fields);

        // Modificar campo cantidad en inventario_movimientos a INT
        $fields = [
            'cantidad' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ];
        $this->forge->modifyColumn('inventario_movimientos', $fields);
    }

    public function down()
    {
        // Revertir a DECIMAL
        $fields = [
            'cantidad_actual' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'cantidad_minima' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('inventario_items', $fields);

        $fields = [
            'cantidad' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
        ];
        $this->forge->modifyColumn('inventario_movimientos', $fields);
    }
}
