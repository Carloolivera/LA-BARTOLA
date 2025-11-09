<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventarioTables extends Migration
{
    public function up()
    {
        // Tabla de categorías de inventario
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'null'       => true,
                'default'    => '#6c757d',
            ],
            'icono' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
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
        $this->forge->createTable('inventario_categorias');

        // Tabla de items de inventario
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'categoria_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'unidad_medida' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'unidad',
            ],
            'cantidad_actual' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
                'default'    => 0.00,
            ],
            'cantidad_minima' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'precio_unitario' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'proveedor' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'ubicacion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'activo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
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
        $this->forge->addKey('categoria_id');
        $this->forge->addForeignKey('categoria_id', 'inventario_categorias', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inventario_items');

        // Tabla de movimientos de inventario
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'item_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tipo' => [
                'type'       => 'ENUM',
                'constraint' => ['entrada', 'salida', 'ajuste'],
                'null'       => false,
            ],
            'cantidad' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'motivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('item_id');
        $this->forge->addForeignKey('item_id', 'inventario_items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('inventario_movimientos');

        // Insertar categorías por defecto
        $categorias = [
            [
                'nombre' => 'Ingredientes',
                'descripcion' => 'Ingredientes para preparación de platos',
                'color' => '#28a745',
                'icono' => 'basket',
            ],
            [
                'nombre' => 'Elementos de Cocina',
                'descripcion' => 'Utensilios y equipamiento de cocina',
                'color' => '#fd7e14',
                'icono' => 'tools',
            ],
            [
                'nombre' => 'Elementos de Limpieza',
                'descripcion' => 'Productos y elementos de limpieza',
                'color' => '#17a2b8',
                'icono' => 'droplet',
            ],
            [
                'nombre' => 'Bebidas',
                'descripcion' => 'Bebidas y líquidos',
                'color' => '#6f42c1',
                'icono' => 'cup-straw',
            ],
            [
                'nombre' => 'Otros',
                'descripcion' => 'Otros elementos del inventario',
                'color' => '#6c757d',
                'icono' => 'box',
            ],
        ];

        $this->db->table('inventario_categorias')->insertBatch($categorias);
    }

    public function down()
    {
        $this->forge->dropTable('inventario_movimientos');
        $this->forge->dropTable('inventario_items');
        $this->forge->dropTable('inventario_categorias');
    }
}
