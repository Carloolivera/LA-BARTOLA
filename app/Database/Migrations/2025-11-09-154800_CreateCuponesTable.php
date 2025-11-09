<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCuponesTable extends Migration
{
    public function up()
    {
        // Tabla de cupones
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'unique'     => true,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tipo_descuento' => [
                'type'       => 'ENUM',
                'constraint' => ['porcentaje', 'monto_fijo'],
                'null'       => false,
                'default'    => 'porcentaje',
            ],
            'valor_descuento' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'monto_minimo' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'fecha_inicio' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'fecha_expiracion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'usos_maximos' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'usos_por_usuario' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => 1,
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
        $this->forge->addKey('codigo');
        $this->forge->createTable('cupones');

        // Tabla de usos de cupones
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'cupon_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'pedido_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'monto_descuento' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('cupon_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('pedido_id');
        $this->forge->addForeignKey('cupon_id', 'cupones', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('cupones_usos');
    }

    public function down()
    {
        $this->forge->dropTable('cupones_usos');
        $this->forge->dropTable('cupones');
    }
}
