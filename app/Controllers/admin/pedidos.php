<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Database\BaseConnection;

class Pedidos extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return redirect()->to('/')->with('error', 'Acceso denegado');
        }

        // Obtener todos los pedidos con información del usuario y plato
        $pedidos = $this->db->table('pedidos as p')
            ->select('p.*, 
                      u.username, 
                      ai.secret as email,
                      pl.nombre as plato_nombre, 
                      pl.precio')
            ->join('users as u', 'u.id = p.usuario_id', 'left')
            ->join('auth_identities as ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
            ->join('platos as pl', 'pl.id = p.plato_id', 'left')
            ->orderBy('p.id', 'DESC')
            ->get()
            ->getResultArray();

        // Procesar las notas para extraer información
        foreach ($pedidos as &$pedido) {
            $pedido['info_pedido'] = $this->extraerInfoPedido($pedido['notas']);
        }

        $data['pedidos'] = $pedidos;
        
        return view('admin/pedidos/index', $data);
    }

    public function ver($id)
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return redirect()->to('/')->with('error', 'Acceso denegado');
        }

        $pedido = $this->db->table('pedidos as p')
            ->select('p.*, 
                      u.username, 
                      ai.secret as email,
                      pl.nombre as plato_nombre, 
                      pl.precio')
            ->join('users as u', 'u.id = p.usuario_id', 'left')
            ->join('auth_identities as ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
            ->join('platos as pl', 'pl.id = p.plato_id', 'left')
            ->where('p.id', $id)
            ->get()
            ->getRowArray();

        if (!$pedido) {
            return redirect()->to('/admin/pedidos')->with('error', 'Pedido no encontrado');
        }

        $pedido['info_pedido'] = $this->extraerInfoPedido($pedido['notas']);

        $data['pedido'] = $pedido;
        
        return view('admin/pedidos/ver', $data);
    }

    public function editar($id)
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return redirect()->to('/')->with('error', 'Acceso denegado');
        }

        $pedido = $this->db->table('pedidos')->where('id', $id)->get()->getRowArray();

        if (!$pedido) {
            return redirect()->to('/admin/pedidos')->with('error', 'Pedido no encontrado');
        }

        if ($this->request->getMethod() === 'post') {
            $estado = $this->request->getPost('estado');
            
            $this->db->table('pedidos')
                ->where('id', $id)
                ->update(['estado' => $estado]);

            return redirect()->to('/admin/pedidos')->with('success', 'Estado actualizado correctamente');
        }

        $pedido['info_pedido'] = $this->extraerInfoPedido($pedido['notas']);
        $data['pedido'] = $pedido;
        
        return view('admin/pedidos/editar', $data);
    }

    public function cambiarEstado($id)
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso denegado']);
        }

        $estado = $this->request->getPost('estado');

        $this->db->table('pedidos')
            ->where('id', $id)
            ->update(['estado' => $estado]);

        return $this->response->setJSON(['success' => true, 'message' => 'Estado actualizado']);
    }

    public function eliminar($id)
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return redirect()->to('/')->with('error', 'Acceso denegado');
        }

        $this->db->table('pedidos')->where('id', $id)->delete();

        return redirect()->to('/admin/pedidos')->with('success', 'Pedido eliminado correctamente');
    }

    public function imprimirTicket($id)
    {
        // Verificar que sea admin
        if (!auth()->user()->inGroup('admin')) {
            return redirect()->to('/')->with('error', 'Acceso denegado');
        }

        $pedido = $this->db->table('pedidos as p')
            ->select('p.*, 
                      u.username, 
                      ai.secret as email,
                      pl.nombre as plato_nombre, 
                      pl.precio')
            ->join('users as u', 'u.id = p.usuario_id', 'left')
            ->join('auth_identities as ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
            ->join('platos as pl', 'pl.id = p.plato_id', 'left')
            ->where('p.id', $id)
            ->get()
            ->getRowArray();

        if (!$pedido) {
            return redirect()->to('/admin/pedidos')->with('error', 'Pedido no encontrado');
        }

        $pedido['info_pedido'] = $this->extraerInfoPedido($pedido['notas']);

        $data['pedido'] = $pedido;
        
        return view('admin/pedidos/ticket', $data);
    }

    private function extraerInfoPedido($notas)
    {
        $info = [
            'nombre_cliente' => '',
            'tipo_entrega' => '',
            'direccion' => '',
            'forma_pago' => '',
            'detalle' => ''
        ];

        if (empty($notas)) {
            return $info;
        }

        // Extraer "A nombre de"
        if (preg_match('/A nombre de:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['nombre_cliente'] = trim($matches[1]);
        }

        // Extraer "Tipo de entrega"
        if (preg_match('/Tipo de entrega:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['tipo_entrega'] = trim($matches[1]);
        }

        // Extraer "Dirección"
        if (preg_match('/Direccion:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['direccion'] = trim($matches[1]);
        }

        // Extraer "Forma de pago"
        if (preg_match('/Forma de pago:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['forma_pago'] = trim($matches[1]);
        }

        // Extraer detalle del pedido
        if (preg_match('/Detalle del pedido:\s*(.+)/is', $notas, $matches)) {
            $info['detalle'] = trim($matches[1]);
        }

        return $info;
    }
}