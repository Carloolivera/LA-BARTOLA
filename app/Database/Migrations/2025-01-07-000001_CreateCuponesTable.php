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
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tipo' => [
                'type' => 'ENUM',
                'constraint' => ['porcentaje', 'monto_fijo'],
                'default' => 'porcentaje',
            ],
            'valor' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Porcentaje (ej: 10 para 10%) o monto fijo (ej: 500 para $500)',
            ],
            'monto_minimo' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Monto mínimo de compra para aplicar el cupón',
            ],
            'usos_maximos' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Cantidad máxima de veces que se puede usar (NULL = ilimitado)',
            ],
            'usos_actuales' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Cantidad de veces que se ha usado',
            ],
            'usos_por_usuario' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
                'comment' => 'Cantidad de veces que un usuario puede usar este cupón',
            ],
            'fecha_inicio' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'fecha_expiracion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'activo' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
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
        $this->forge->createTable('cupones');

        // Tabla de usos de cupones (para rastrear quién usó qué)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'cupon_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'pedido_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'descuento_aplicado' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Monto del descuento que se aplicó',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['cupon_id', 'usuario_id']);
        $this->forge->addForeignKey('cupon_id', 'cupones', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('cupones_usos');
    }

    public function down()
    {
        $this->forge->dropTable('cupones_usos', true);
        $this->forge->dropTable('cupones', true);
    }
}
