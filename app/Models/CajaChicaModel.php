<?php

namespace App\Models;

use CodeIgniter\Model;

class CajaChicaModel extends Model
{
    protected $table            = 'caja_chica';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['fecha', 'hora', 'concepto', 'tipo', 'monto', 'es_digital', 'user_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'monto' => 'float'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'fecha'    => 'required|valid_date',
        'hora'     => 'required',
        'concepto' => 'required|min_length[3]|max_length[255]',
        'tipo'     => 'required|in_list[entrada,salida]',
        'monto'    => 'required|decimal|greater_than[0]',
    ];
    protected $validationMessages   = [
        'fecha' => [
            'required'   => 'La fecha es obligatoria',
            'valid_date' => 'Debe ingresar una fecha válida',
        ],
        'hora' => [
            'required' => 'La hora es obligatoria',
        ],
        'concepto' => [
            'required'   => 'El concepto es obligatorio',
            'min_length' => 'El concepto debe tener al menos 3 caracteres',
            'max_length' => 'El concepto no puede exceder 255 caracteres',
        ],
        'tipo' => [
            'required' => 'El tipo es obligatorio',
            'in_list'  => 'El tipo debe ser entrada o salida',
        ],
        'monto' => [
            'required'     => 'El monto es obligatorio',
            'decimal'      => 'El monto debe ser un número válido',
            'greater_than' => 'El monto debe ser mayor a 0',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Obtener movimientos de una fecha específica
     */
    public function getMovimientosPorFecha($fecha)
    {
        return $this->where('fecha', $fecha)
                    ->orderBy('hora', 'ASC')
                    ->findAll();
    }

    /**
     * Calcular el saldo del día
     */
    public function getSaldoDia($fecha)
    {
        $movimientos = $this->getMovimientosPorFecha($fecha);

        $totalEntradas = 0;
        $totalSalidas = 0;
        $totalEfectivo = 0;
        $totalDigital = 0;

        foreach ($movimientos as $mov) {
            if ($mov['tipo'] === 'entrada') {
                $totalEntradas += $mov['monto'];

                // Separar efectivo y digital solo para entradas
                if (isset($mov['es_digital']) && $mov['es_digital'] == 1) {
                    $totalDigital += $mov['monto'];
                } else {
                    $totalEfectivo += $mov['monto'];
                }
            } else {
                $totalSalidas += $mov['monto'];
            }
        }

        return [
            'entradas' => $totalEntradas,
            'salidas'  => $totalSalidas,
            'saldo'    => $totalEntradas - $totalSalidas,
            'efectivo' => $totalEfectivo,
            'digital'  => $totalDigital,
        ];
    }

    /**
     * Obtener fechas únicas con movimientos (para el archivo)
     */
    public function getFechasConMovimientos($limit = 30)
    {
        return $this->select('fecha, COUNT(*) as cantidad')
                    ->groupBy('fecha')
                    ->orderBy('fecha', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Obtener saldo acumulado hasta una fecha
     */
    public function getSaldoAcumuladoHasta($fecha)
    {
        $resultado = $this->selectSum('CASE WHEN tipo = "entrada" THEN monto ELSE -monto END', 'saldo_acumulado')
                          ->where('fecha <=', $fecha)
                          ->get()
                          ->getRowArray();

        return $resultado['saldo_acumulado'] ?? 0;
    }
}
