<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CajaChicaModel;

class CajaChica extends BaseController
{
    protected $cajaModel;

    public function __construct()
    {
        $this->cajaModel = new CajaChicaModel();
    }

    /**
     * Página principal - Caja del día actual
     */
    public function index()
    {
        $fechaHoy = date('Y-m-d');
        return $this->ver($fechaHoy);
    }

    /**
     * Ver caja de una fecha específica
     */
    public function ver($fecha = null)
    {
        $fecha = $fecha ?? date('Y-m-d');

        // Obtener movimientos del día
        $movimientos = $this->cajaModel->getMovimientosPorFecha($fecha);

        // Calcular totales
        $saldo = $this->cajaModel->getSaldoDia($fecha);

        $data = [
            'title'       => 'Caja Chica',
            'fecha'       => $fecha,
            'movimientos' => $movimientos,
            'entradas'    => $saldo['entradas'],
            'salidas'     => $saldo['salidas'],
            'saldo'       => $saldo['saldo'],
            'efectivo'    => $saldo['efectivo'],
            'digital'     => $saldo['digital'],
            'esHoy'       => ($fecha === date('Y-m-d')),
        ];

        return view('admin/caja_chica/index', $data);
    }

    /**
     * Agregar movimiento (entrada o salida)
     */
    public function agregar()
    {
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Método no permitido');
        }

        $data = [
            'fecha'      => $this->request->getPost('fecha'),
            'hora'       => $this->request->getPost('hora'),
            'concepto'   => $this->request->getPost('concepto'),
            'tipo'       => $this->request->getPost('tipo'),
            'monto'      => $this->request->getPost('monto'),
            'es_digital' => $this->request->getPost('es_digital') ? 1 : 0,
            'user_id'    => auth()->id(),
        ];

        if ($this->cajaModel->save($data)) {
            return redirect()->to('/admin/caja-chica/ver/' . $data['fecha'])
                             ->with('success', 'Movimiento agregado correctamente');
        }

        return redirect()->back()
                         ->with('error', 'Error al agregar movimiento: ' . implode(', ', $this->cajaModel->errors()))
                         ->withInput();
    }

    /**
     * Editar movimiento
     */
    public function editar($id)
    {
        $movimiento = $this->cajaModel->find($id);

        if (!$movimiento) {
            return redirect()->back()->with('error', 'Movimiento no encontrado');
        }

        if ($this->request->is('post')) {
            $data = [
                'fecha'      => $this->request->getPost('fecha'),
                'hora'       => $this->request->getPost('hora'),
                'concepto'   => $this->request->getPost('concepto'),
                'tipo'       => $this->request->getPost('tipo'),
                'monto'      => $this->request->getPost('monto'),
                'es_digital' => $this->request->getPost('es_digital') ? 1 : 0,
            ];

            if ($this->cajaModel->update($id, $data)) {
                return redirect()->to('/admin/caja-chica/ver/' . $data['fecha'])
                                 ->with('success', 'Movimiento actualizado correctamente');
            }

            return redirect()->back()
                             ->with('error', 'Error al actualizar: ' . implode(', ', $this->cajaModel->errors()))
                             ->withInput();
        }

        $data = [
            'title'      => 'Editar Movimiento',
            'movimiento' => $movimiento,
        ];

        return view('admin/caja_chica/editar', $data);
    }

    /**
     * Eliminar movimiento
     */
    public function eliminar($id)
    {
        $movimiento = $this->cajaModel->find($id);

        if (!$movimiento) {
            return redirect()->back()->with('error', 'Movimiento no encontrado');
        }

        $fecha = $movimiento['fecha'];

        if ($this->cajaModel->delete($id)) {
            return redirect()->to('/admin/caja-chica/ver/' . $fecha)
                             ->with('success', 'Movimiento eliminado correctamente');
        }

        return redirect()->back()->with('error', 'Error al eliminar el movimiento');
    }

    /**
     * Ver archivo de fechas anteriores
     */
    public function archivo()
    {
        $fechas = $this->cajaModel->getFechasConMovimientos(30);

        $data = [
            'title'  => 'Archivo - Caja Chica',
            'fechas' => $fechas,
        ];

        return view('admin/caja_chica/archivo', $data);
    }

    /**
     * Imprimir reporte del día
     */
    public function imprimir($fecha = null)
    {
        $fecha = $fecha ?? date('Y-m-d');

        // Obtener movimientos del día
        $movimientos = $this->cajaModel->getMovimientosPorFecha($fecha);

        // Calcular totales
        $saldo = $this->cajaModel->getSaldoDia($fecha);

        $data = [
            'fecha'       => $fecha,
            'movimientos' => $movimientos,
            'entradas'    => $saldo['entradas'],
            'salidas'     => $saldo['salidas'],
            'saldo'       => $saldo['saldo'],
            'efectivo'    => $saldo['efectivo'],
            'digital'     => $saldo['digital'],
        ];

        return view('admin/caja_chica/imprimir', $data);
    }

    /**
     * Exportar a Excel (CSV) el reporte del día
     */
    public function exportarExcel($fecha = null)
    {
        $fecha = $fecha ?? date('Y-m-d');

        // Obtener movimientos del día
        $movimientos = $this->cajaModel->getMovimientosPorFecha($fecha);

        // Calcular totales
        $saldo = $this->cajaModel->getSaldoDia($fecha);

        // Configurar headers para descarga CSV
        $filename = 'caja_chica_' . $fecha . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Crear archivo en memoria
        $output = fopen('php://output', 'w');

        // BOM para UTF-8 (necesario para que Excel reconozca acentos)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Encabezado del archivo
        fputcsv($output, ['CAJA CHICA - LA BARTOLA'], ';');
        fputcsv($output, ['Fecha: ' . date('d/m/Y', strtotime($fecha))], ';');
        fputcsv($output, [''], ';');

        // Encabezados de columnas
        fputcsv($output, ['Fecha', 'Hora', 'Concepto', 'Tipo Pago', 'Entrada', 'Salida', 'Saldo'], ';');

        // Datos
        $saldoAcumulado = 0;
        foreach ($movimientos as $mov) {
            if ($mov['tipo'] === 'entrada') {
                $saldoAcumulado += $mov['monto'];
                $entrada = '$' . number_format($mov['monto'], 2, ',', '.');
                $salida = '-';
            } else {
                $saldoAcumulado -= $mov['monto'];
                $entrada = '-';
                $salida = '$' . number_format($mov['monto'], 2, ',', '.');
            }

            $tipoPago = ($mov['es_digital'] == 1) ? 'Digital' : 'Efectivo';

            fputcsv($output, [
                date('d/m/Y', strtotime($mov['fecha'])),
                date('H:i', strtotime($mov['hora'])),
                $mov['concepto'],
                $tipoPago,
                $entrada,
                $salida,
                '$' . number_format($saldoAcumulado, 2, ',', '.')
            ], ';');
        }

        // Totales
        fputcsv($output, [''], ';');
        fputcsv($output, ['RESUMEN'], ';');
        fputcsv($output, ['Total Entradas (Efectivo):', '$' . number_format($saldo['efectivo'], 2, ',', '.')], ';');
        fputcsv($output, ['Total Digital:', '$' . number_format($saldo['digital'], 2, ',', '.')], ';');
        fputcsv($output, ['Total Entradas:', '$' . number_format($saldo['entradas'], 2, ',', '.')], ';');
        fputcsv($output, ['Total Salidas:', '$' . number_format($saldo['salidas'], 2, ',', '.')], ';');
        fputcsv($output, ['SALDO FINAL:', '$' . number_format($saldo['saldo'], 2, ',', '.')], ';');

        fclose($output);
        exit;
    }
}
