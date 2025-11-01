<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStockToPlatos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('platos', [
            'stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'disponible'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('platos', 'stock');
    }
}