<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificacionesTable extends Migration
{
    public function up()
    {
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
                'null' => true, // null = notificación para todos los admins
            ],
            'tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'nuevo_pedido, cambio_estado, stock_bajo, cupon_usado, etc.',
            ],
            'titulo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'mensaje' => [
                'type' => 'TEXT',
            ],
            'icono' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Bootstrap Icons class',
            ],
            'url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'URL de destino al hacer click',
            ],
            'leida' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'data' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Datos adicionales de la notificación',
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
        $this->forge->addKey('leida');
        $this->forge->addKey('created_at');

        $this->forge->createTable('notificaciones');

        // Agregar foreign key a users
        $this->forge->addForeignKey('usuario_id', 'users', 'id', 'CASCADE', 'CASCADE', 'fk_notificaciones_user');
    }

    public function down()
    {
        if ($this->db->DBDriver === 'MySQLi') {
            $this->forge->dropForeignKey('notificaciones', 'fk_notificaciones_user');
        }
        $this->forge->dropTable('notificaciones');
    }
}
