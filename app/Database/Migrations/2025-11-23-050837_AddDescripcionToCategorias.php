<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDescripcionToCategorias extends Migration
{
    public function up()
    {
        $fields = [
            'descripcion' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'nombre'
            ]
        ];

        $this->forge->addColumn('categorias', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('categorias', 'descripcion');
    }
}
