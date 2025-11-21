<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingFieldsToPedidos extends Migration
{
    public function up()
    {
        // Agregar campos faltantes a la tabla pedidos
        $fields = [
            'tipo_entrega' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'estado',
            ],
            'direccion' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'tipo_entrega',
            ],
            'forma_pago' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'direccion',
            ],
        ];

        $this->forge->addColumn('pedidos', $fields);

        // Modificar usuario_id para permitir NULL (pedidos públicos sin usuario)
        $this->forge->modifyColumn('pedidos', [
            'usuario_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Ahora permite NULL
            ],
        ]);
    }

    public function down()
    {
        // Eliminar campos agregados
        $this->forge->dropColumn('pedidos', ['tipo_entrega', 'direccion', 'forma_pago']);

        // Revertir usuario_id a NOT NULL
        $this->forge->modifyColumn('pedidos', [
            'usuario_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
    }
}
