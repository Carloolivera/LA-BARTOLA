<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCajaTable extends Migration
{
    public function up()
    {
        // Tabla de turnos/sesiones de caja
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Usuario que abrió la caja',
            ],
            'fecha_apertura' => [
                'type' => 'DATETIME',
            ],
            'fecha_cierre' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'monto_inicial' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'monto_final' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'monto_esperado' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Monto que debería haber según movimientos',
            ],
            'diferencia' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Diferencia entre esperado y real',
            ],
            'estado' => [
                'type' => 'ENUM',
                'constraint' => ['abierta', 'cerrada'],
                'default' => 'abierta',
            ],
            'notas_apertura' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notas_cierre' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('usuario_id');
        $this->forge->addKey('estado');
        $this->forge->addKey('fecha_apertura');
        $this->forge->addForeignKey('usuario_id', 'users', 'id', 'CASCADE', 'CASCADE', 'fk_caja_turnos_user');

        $this->forge->createTable('caja_turnos');

        // Tabla de movimientos de caja
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'caja_turno_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'tipo' => [
                'type' => 'ENUM',
                'constraint' => ['ingreso', 'egreso', 'venta'],
                'comment' => 'ingreso: entrada manual, egreso: salida manual, venta: de pedido',
            ],
            'concepto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'monto' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'metodo_pago' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'efectivo, qr, mercado_pago, tarjeta',
            ],
            'pedido_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'notas' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('caja_turno_id');
        $this->forge->addKey('tipo');
        $this->forge->addKey('pedido_id');
        $this->forge->addForeignKey('caja_turno_id', 'caja_turnos', 'id', 'CASCADE', 'CASCADE', 'fk_movimientos_turno');
        $this->forge->addForeignKey('usuario_id', 'users', 'id', 'CASCADE', 'CASCADE', 'fk_movimientos_user');
        $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'SET NULL', 'CASCADE', 'fk_movimientos_pedido');

        $this->forge->createTable('caja_movimientos');
    }

    public function down()
    {
        if ($this->db->DBDriver === 'MySQLi') {
            // Eliminar foreign keys de caja_movimientos
            $this->db->query('ALTER TABLE caja_movimientos DROP FOREIGN KEY fk_movimientos_turno');
            $this->db->query('ALTER TABLE caja_movimientos DROP FOREIGN KEY fk_movimientos_user');
            $this->db->query('ALTER TABLE caja_movimientos DROP FOREIGN KEY fk_movimientos_pedido');

            // Eliminar foreign keys de caja_turnos
            $this->db->query('ALTER TABLE caja_turnos DROP FOREIGN KEY fk_caja_turnos_user');
        }

        $this->forge->dropTable('caja_movimientos');
        $this->forge->dropTable('caja_turnos');
    }
}
