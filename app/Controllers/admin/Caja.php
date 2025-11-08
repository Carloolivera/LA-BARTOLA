<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CajaModel;
use App\Models\CajaMovimientoModel;

class Caja extends BaseController
{
    protected $cajaModel;
    protected $movimientoModel;

    public function __construct()
    {
        $this->cajaModel = new CajaModel();
        $this->movimientoModel = new CajaMovimientoModel();
    }

    /**
     * Vista principal de caja
     */
    public function index()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $data['caja_abierta'] = $this->cajaModel->obtenerCajaAbierta();

        // Si hay caja abierta, obtener resumen y movimientos
        if ($data['caja_abierta']) {
            $data['resumen'] = $this->cajaModel->obtenerResumen($data['caja_abierta']['id']);
            $data['movimientos'] = $this->movimientoModel->obtenerMovimientosConUsuario($data['caja_abierta']['id']);
            $data['monto_esperado'] = $this->cajaModel->calcularMontoEsperado($data['caja_abierta']['id']);
        }

        return view('admin/caja/index', $data);
    }

    /**
     * Abrir caja
     */
    public function abrir()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $monto_inicial = $this->request->getPost('monto_inicial');
        $notas = $this->request->getPost('notas_apertura');

        if (!is_numeric($monto_inicial) || $monto_inicial < 0) {
            return redirect()->back()->with('error', 'El monto inicial debe ser un número válido');
        }

        try {
            $this->cajaModel->abrirCaja($user->id, $monto_inicial, $notas);
            return redirect()->to('admin/caja')->with('success', 'Caja abierta exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cerrar caja
     */
    public function cerrar($id)
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $monto_final = $this->request->getPost('monto_final');
        $notas = $this->request->getPost('notas_cierre');

        if (!is_numeric($monto_final) || $monto_final < 0) {
            return redirect()->back()->with('error', 'El monto final debe ser un número válido');
        }

        try {
            $this->cajaModel->cerrarCaja($id, $monto_final, $notas);
            return redirect()->to('admin/caja')->with('success', 'Caja cerrada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Registrar ingreso manual
     */
    public function registrarIngreso()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $caja_abierta = $this->cajaModel->obtenerCajaAbierta();

        if (!$caja_abierta) {
            return $this->response->setJSON(['success' => false, 'message' => 'No hay caja abierta']);
        }

        $concepto = $this->request->getPost('concepto');
        $monto = $this->request->getPost('monto');
        $notas = $this->request->getPost('notas');

        if (empty($concepto) || !is_numeric($monto) || $monto <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        $data = [
            'caja_turno_id' => $caja_abierta['id'],
            'tipo' => 'ingreso',
            'concepto' => $concepto,
            'monto' => $monto,
            'metodo_pago' => 'efectivo',
            'usuario_id' => $user->id,
            'notas' => $notas
        ];

        try {
            $this->movimientoModel->registrarMovimiento($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Ingreso registrado exitosamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Registrar egreso manual
     */
    public function registrarEgreso()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $caja_abierta = $this->cajaModel->obtenerCajaAbierta();

        if (!$caja_abierta) {
            return $this->response->setJSON(['success' => false, 'message' => 'No hay caja abierta']);
        }

        $concepto = $this->request->getPost('concepto');
        $monto = $this->request->getPost('monto');
        $notas = $this->request->getPost('notas');

        if (empty($concepto) || !is_numeric($monto) || $monto <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        $data = [
            'caja_turno_id' => $caja_abierta['id'],
            'tipo' => 'egreso',
            'concepto' => $concepto,
            'monto' => $monto,
            'usuario_id' => $user->id,
            'notas' => $notas
        ];

        try {
            $this->movimientoModel->registrarMovimiento($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Egreso registrado exitosamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Historial de cajas
     */
    public function historial()
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $data['historial'] = $this->cajaModel->obtenerHistorial(50);

        return view('admin/caja/historial', $data);
    }

    /**
     * Ver detalle de una caja cerrada
     */
    public function ver($id)
    {
        $auth = service('auth');
        $user = $auth->user();

        if (!$user || !$user->inGroup('admin')) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $data['caja'] = $this->cajaModel->find($id);

        if (!$data['caja']) {
            return redirect()->to('admin/caja/historial')->with('error', 'Caja no encontrada');
        }

        $data['resumen'] = $this->cajaModel->obtenerResumen($id);
        $data['movimientos'] = $this->movimientoModel->obtenerMovimientosConUsuario($id);

        return view('admin/caja/ver', $data);
    }
}
