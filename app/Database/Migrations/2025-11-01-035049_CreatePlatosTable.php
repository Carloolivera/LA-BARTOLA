<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePlatosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'precio' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'categoria' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'disponible' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'imagen' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('platos');
    }

    public function down()
    {
        $this->forge->dropTable('platos');
    }
}