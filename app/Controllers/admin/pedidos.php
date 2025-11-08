<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotificacionModel;
use CodeIgniter\Database\BaseConnection;

class Pedidos extends BaseController
{
    protected $db;
    protected $notificacionModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->notificacionModel = new NotificacionModel();
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
                      pl.precio,
                      pl.stock,
                      pl.stock_ilimitado')
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

        $nuevoEstado = $this->request->getPost('estado');

        // Obtener el pedido actual
        $pedido = $this->db->table('pedidos')->where('id', $id)->get()->getRowArray();

        if (!$pedido) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pedido no encontrado']);
        }

        $estadoAnterior = $pedido['estado'];

        // Actualizar el estado del pedido
        $this->db->table('pedidos')
            ->where('id', $id)
            ->update(['estado' => $nuevoEstado]);

        // SI EL NUEVO ESTADO ES "COMPLETADO", DESCONTAR DEL STOCK
        if ($nuevoEstado === 'completado' && $estadoAnterior !== 'completado') {
            $this->descontarStock($pedido['plato_id'], $pedido['cantidad']);
        }

        // SI SE CANCELA UN PEDIDO QUE ESTABA COMPLETADO, DEVOLVER AL STOCK
        if ($nuevoEstado === 'cancelado' && $estadoAnterior === 'completado') {
            $this->devolverStock($pedido['plato_id'], $pedido['cantidad']);
        }

        // Crear notificación para el usuario del pedido
        $mensajesEstado = [
            'pendiente' => 'Tu pedido está pendiente de confirmación',
            'confirmado' => 'Tu pedido ha sido confirmado y está siendo preparado',
            'en_camino' => 'Tu pedido está en camino',
            'completado' => '¡Tu pedido ha sido completado!',
            'cancelado' => 'Tu pedido ha sido cancelado'
        ];

        $iconosEstado = [
            'pendiente' => 'bi-clock-fill',
            'confirmado' => 'bi-check-circle-fill',
            'en_camino' => 'bi-truck',
            'completado' => 'bi-check-circle-fill',
            'cancelado' => 'bi-x-circle-fill'
        ];

        if (isset($mensajesEstado[$nuevoEstado])) {
            $this->notificacionModel->crearNotificacion([
                'usuario_id' => $pedido['usuario_id'],
                'tipo' => 'cambio_estado_pedido',
                'titulo' => 'Actualización de Pedido #' . $id,
                'mensaje' => $mensajesEstado[$nuevoEstado],
                'icono' => $iconosEstado[$nuevoEstado] ?? 'bi-info-circle',
                'url' => site_url('pedido'),
                'leida' => 0,
                'data' => json_encode([
                    'pedido_id' => $id,
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo' => $nuevoEstado
                ])
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ]);
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

    /**
     * DESCONTAR STOCK CUANDO UN PEDIDO SE COMPLETA
     */
    private function descontarStock($platoId, $cantidad)
    {
        // Obtener información del plato
        $plato = $this->db->table('platos')->where('id', $platoId)->get()->getRowArray();

        if (!$plato) {
            log_message('error', "Plato ID {$platoId} no encontrado para descontar stock");
            return false;
        }

        // Si el plato tiene stock ilimitado, no hacer nada
        if ($plato['stock_ilimitado'] == 1) {
            return true;
        }

        // Descontar del stock
        $nuevoStock = max(0, $plato['stock'] - $cantidad);

        $this->db->table('platos')
            ->where('id', $platoId)
            ->update(['stock' => $nuevoStock]);

        log_message('info', "Stock descontado: Plato #{$platoId} - Cantidad: {$cantidad} - Stock restante: {$nuevoStock}");

        return true;
    }

    /**
     * DEVOLVER STOCK CUANDO UN PEDIDO COMPLETADO SE CANCELA
     */
    private function devolverStock($platoId, $cantidad)
    {
        // Obtener información del plato
        $plato = $this->db->table('platos')->where('id', $platoId)->get()->getRowArray();

        if (!$plato) {
            log_message('error', "Plato ID {$platoId} no encontrado para devolver stock");
            return false;
        }

        // Si el plato tiene stock ilimitado, no hacer nada
        if ($plato['stock_ilimitado'] == 1) {
            return true;
        }

        // Devolver al stock
        $nuevoStock = $plato['stock'] + $cantidad;

        $this->db->table('platos')
            ->where('id', $platoId)
            ->update(['stock' => $nuevoStock]);

        log_message('info', "Stock devuelto: Plato #{$platoId} - Cantidad: {$cantidad} - Stock actual: {$nuevoStock}");

        return true;
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