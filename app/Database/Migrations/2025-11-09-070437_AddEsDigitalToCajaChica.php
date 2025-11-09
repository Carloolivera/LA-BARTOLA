<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEsDigitalToCajaChica extends Migration
{
    public function up()
    {
        $fields = [
            'es_digital' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'monto'
            ]
        ];

        $this->forge->addColumn('caja_chica', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('caja_chica', 'es_digital');
    }
}
