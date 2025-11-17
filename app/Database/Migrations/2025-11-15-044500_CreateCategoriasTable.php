<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriasTable extends Migration
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
                'constraint' => '100',
                'unique'     => true,
            ],
            'orden' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'activa' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->createTable('categorias');

        // Insertar categorías predeterminadas
        $categorias = [
            ['nombre' => 'Bebidas', 'orden' => 1, 'activa' => 1],
            ['nombre' => 'Empanadas', 'orden' => 2, 'activa' => 1],
            ['nombre' => 'Pizzas', 'orden' => 3, 'activa' => 1],
            ['nombre' => 'Tartas', 'orden' => 4, 'activa' => 1],
            ['nombre' => 'Postres', 'orden' => 5, 'activa' => 1],
        ];

        foreach ($categorias as $cat) {
            $cat['created_at'] = date('Y-m-d H:i:s');
            $cat['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('categorias')->insert($cat);
        }
    }

    public function down()
    {
        $this->forge->dropTable('categorias');
    }
}
