<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMetodoPagoToCajaChica extends Migration
{
    public function up()
    {
        $fields = [
            'metodo_pago' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'es_digital'
            ]
        ];

        $this->forge->addColumn('caja_chica', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('caja_chica', 'metodo_pago');
    }
}
