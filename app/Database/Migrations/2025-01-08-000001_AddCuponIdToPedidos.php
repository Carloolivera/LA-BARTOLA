<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCuponIdToPedidos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pedidos', [
            'cupon_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'total'
            ],
            'descuento_cupon' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'default' => 0,
                'after' => 'cupon_id'
            ]
        ]);

        // Agregar foreign key a cupones
        $this->forge->addForeignKey('cupon_id', 'cupones', 'id', 'SET NULL', 'CASCADE', 'fk_pedidos_cupon');
    }

    public function down()
    {
        // Eliminar foreign key primero
        if ($this->db->DBDriver === 'MySQLi') {
            $this->forge->dropForeignKey('pedidos', 'fk_pedidos_cupon');
        }

        // Eliminar columnas
        $this->forge->dropColumn('pedidos', ['cupon_id', 'descuento_cupon']);
    }
}
