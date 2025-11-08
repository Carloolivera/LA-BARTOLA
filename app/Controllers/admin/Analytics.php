<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Analytics extends BaseController
{
    /**
     * Dashboard principal de analytics
     */
    public function index()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $db = \Config\Database::connect();

        // Obtener rango de fechas (últimos 30 días por defecto)
        $fecha_desde = $this->request->getGet('fecha_desde') ?: date('Y-m-d', strtotime('-30 days'));
        $fecha_hasta = $this->request->getGet('fecha_hasta') ?: date('Y-m-d');

        // 1. ESTADÍSTICAS GENERALES
        $data['stats'] = [
            'total_pedidos' => $db->table('pedidos')
                ->where('created_at >=', $fecha_desde)
                ->where('created_at <=', $fecha_hasta . ' 23:59:59')
                ->countAllResults(),

            'total_ventas' => $db->table('pedidos')
                ->selectSum('total')
                ->where('created_at >=', $fecha_desde)
                ->where('created_at <=', $fecha_hasta . ' 23:59:59')
                ->where('estado !=', 'cancelado')
                ->get()->getRow()->total ?? 0,

            'total_cupones_usados' => $db->table('cupones_usos')
                ->where('created_at >=', $fecha_desde)
                ->where('created_at <=', $fecha_hasta . ' 23:59:59')
                ->countAllResults(),

            'total_descuentos' => $db->table('cupones_usos')
                ->selectSum('descuento_aplicado')
                ->where('created_at >=', $fecha_desde)
                ->where('created_at <=', $fecha_hasta . ' 23:59:59')
                ->get()->getRow()->descuento_aplicado ?? 0,
        ];

        // 2. PLATOS MÁS VENDIDOS
        $data['platos_mas_vendidos'] = $db->table('pedido_detalle')
            ->select('platos.nombre, SUM(pedido_detalle.cantidad) as total_vendido, SUM(pedido_detalle.subtotal) as total_ingresos')
            ->join('platos', 'platos.id = pedido_detalle.plato_id')
            ->join('pedidos', 'pedidos.id = pedido_detalle.pedido_id')
            ->where('pedidos.created_at >=', $fecha_desde)
            ->where('pedidos.created_at <=', $fecha_hasta . ' 23:59:59')
            ->where('pedidos.estado !=', 'cancelado')
            ->groupBy('pedido_detalle.plato_id')
            ->orderBy('total_vendido', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // 3. VENTAS POR DÍA
        $data['ventas_por_dia'] = $db->table('pedidos')
            ->select('DATE(created_at) as fecha, COUNT(*) as cantidad_pedidos, SUM(total) as total_ventas')
            ->where('created_at >=', $fecha_desde)
            ->where('created_at <=', $fecha_hasta . ' 23:59:59')
            ->where('estado !=', 'cancelado')
            ->groupBy('DATE(created_at)')
            ->orderBy('fecha', 'ASC')
            ->get()
            ->getResultArray();

        // 4. MÉTODOS DE PAGO
        $data['metodos_pago'] = $db->table('pedidos')
            ->select('forma_pago, COUNT(*) as cantidad, SUM(total) as total_ventas')
            ->where('created_at >=', $fecha_desde)
            ->where('created_at <=', $fecha_hasta . ' 23:59:59')
            ->where('estado !=', 'cancelado')
            ->groupBy('forma_pago')
            ->get()
            ->getResultArray();

        // 5. CUPONES MÁS USADOS
        $data['cupones_mas_usados'] = $db->table('cupones_usos')
            ->select('cupones.codigo, cupones.descripcion, COUNT(*) as usos, SUM(cupones_usos.descuento_aplicado) as total_descuento')
            ->join('cupones', 'cupones.id = cupones_usos.cupon_id')
            ->where('cupones_usos.created_at >=', $fecha_desde)
            ->where('cupones_usos.created_at <=', $fecha_hasta . ' 23:59:59')
            ->groupBy('cupones_usos.cupon_id')
            ->orderBy('usos', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // 6. TIPOS DE ENTREGA
        $data['tipos_entrega'] = $db->table('pedidos')
            ->select('tipo_entrega, COUNT(*) as cantidad')
            ->where('created_at >=', $fecha_desde)
            ->where('created_at <=', $fecha_hasta . ' 23:59:59')
            ->where('estado !=', 'cancelado')
            ->groupBy('tipo_entrega')
            ->get()
            ->getResultArray();

        // 7. ESTADOS DE PEDIDOS
        $data['estados_pedidos'] = $db->table('pedidos')
            ->select('estado, COUNT(*) as cantidad')
            ->where('created_at >=', $fecha_desde)
            ->where('created_at <=', $fecha_hasta . ' 23:59:59')
            ->groupBy('estado')
            ->get()
            ->getResultArray();

        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

        return view('admin/analytics/index', $data);
    }

    /**
     * Exportar datos a CSV
     */
    public function exportar()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $tipo = $this->request->getGet('tipo') ?? 'ventas';
        $fecha_desde = $this->request->getGet('fecha_desde') ?: date('Y-m-d', strtotime('-30 days'));
        $fecha_hasta = $this->request->getGet('fecha_hasta') ?: date('Y-m-d');

        $db = \Config\Database::connect();

        switch ($tipo) {
            case 'ventas':
                $data = $db->table('pedidos')
                    ->where('created_at >=', $fecha_desde)
                    ->where('created_at <=', $fecha_hasta . ' 23:59:59')
                    ->get()
                    ->getResultArray();
                $filename = 'ventas_' . date('Y-m-d') . '.csv';
                break;

            case 'cupones':
                $data = $db->table('cupones_usos')
                    ->select('cupones_usos.*, cupones.codigo, users.username')
                    ->join('cupones', 'cupones.id = cupones_usos.cupon_id')
                    ->join('users', 'users.id = cupones_usos.usuario_id')
                    ->where('cupones_usos.created_at >=', $fecha_desde)
                    ->where('cupones_usos.created_at <=', $fecha_hasta . ' 23:59:59')
                    ->get()
                    ->getResultArray();
                $filename = 'cupones_usos_' . date('Y-m-d') . '.csv';
                break;

            default:
                return redirect()->back()->with('error', 'Tipo de exportación inválido');
        }

        // Generar CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Escribir encabezados
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));

            // Escribir datos
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }

        fclose($output);
        exit;
    }
}
