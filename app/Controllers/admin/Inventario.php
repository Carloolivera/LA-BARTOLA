<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InventarioCategoriaModel;
use App\Models\InventarioItemModel;
use App\Models\InventarioMovimientoModel;

class Inventario extends BaseController
{
    protected $categoriaModel;
    protected $itemModel;
    protected $movimientoModel;

    public function __construct()
    {
        $this->categoriaModel = new InventarioCategoriaModel();
        $this->itemModel = new InventarioItemModel();
        $this->movimientoModel = new InventarioMovimientoModel();
    }

    public function index()
    {
        $categoriaId = $this->request->getGet('categoria');

        $categorias = $this->categoriaModel->findAll();
        $items = $this->itemModel->getItemsConCategoria($categoriaId);
        $itemsStockBajo = $this->itemModel->getItemsStockBajo();

        $data = [
            'title' => 'Inventario',
            'categorias' => $categorias,
            'items' => $items,
            'itemsStockBajo' => $itemsStockBajo,
            'categoriaSeleccionada' => $categoriaId,
        ];

        return view('admin/inventario/index', $data);
    }

    public function crear()
    {
        if ($this->request->is('post')) {
            $data = [
                'categoria_id' => $this->request->getPost('categoria_id'),
                'nombre' => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion'),
                'unidad_medida' => $this->request->getPost('unidad_medida'),
                'cantidad_actual' => $this->request->getPost('cantidad_actual') ?: 0,
                'cantidad_minima' => $this->request->getPost('cantidad_minima'),
                'precio_unitario' => $this->request->getPost('precio_unitario'),
                'proveedor' => $this->request->getPost('proveedor'),
                'ubicacion' => $this->request->getPost('ubicacion'),
            ];

            if ($this->itemModel->save($data)) {
                return redirect()->to('/admin/inventario')->with('success', 'Item agregado correctamente');
            }

            return redirect()->back()->withInput()->with('error', 'Error al agregar item: ' . implode(', ', $this->itemModel->errors()));
        }

        $categorias = $this->categoriaModel->findAll();
        return view('admin/inventario/crear', ['categorias' => $categorias, 'title' => 'Agregar Item']);
    }

    public function editar($id)
    {
        $item = $this->itemModel->find($id);

        if (!$item) {
            return redirect()->to('/admin/inventario')->with('error', 'Item no encontrado');
        }

        if ($this->request->is('post')) {
            $data = [
                'categoria_id' => $this->request->getPost('categoria_id'),
                'nombre' => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion'),
                'unidad_medida' => $this->request->getPost('unidad_medida'),
                'cantidad_minima' => $this->request->getPost('cantidad_minima'),
                'precio_unitario' => $this->request->getPost('precio_unitario'),
                'proveedor' => $this->request->getPost('proveedor'),
                'ubicacion' => $this->request->getPost('ubicacion'),
                'activo' => $this->request->getPost('activo') ? 1 : 0,
            ];

            if ($this->itemModel->update($id, $data)) {
                return redirect()->to('/admin/inventario')->with('success', 'Item actualizado correctamente');
            }

            return redirect()->back()->withInput()->with('error', 'Error al actualizar item');
        }

        $categorias = $this->categoriaModel->findAll();
        return view('admin/inventario/editar', [
            'item' => $item,
            'categorias' => $categorias,
            'title' => 'Editar Item'
        ]);
    }

    public function eliminar($id)
    {
        $item = $this->itemModel->find($id);

        if (!$item) {
            return redirect()->to('/admin/inventario')->with('error', 'Item no encontrado');
        }

        if ($this->itemModel->delete($id)) {
            return redirect()->to('/admin/inventario')->with('success', 'Item eliminado correctamente');
        }

        return redirect()->to('/admin/inventario')->with('error', 'Error al eliminar item');
    }

    public function movimiento($id)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/inventario')->with('error', 'Método no permitido');
        }

        $item = $this->itemModel->find($id);
        if (!$item) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item no encontrado']);
        }

        $tipo = $this->request->getPost('tipo');
        $cantidad = (float)$this->request->getPost('cantidad');
        $motivo = $this->request->getPost('motivo');

        if ($cantidad <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cantidad inválida']);
        }

        $nuevaCantidad = $item['cantidad_actual'];

        if ($tipo === 'entrada') {
            $nuevaCantidad += $cantidad;
        } elseif ($tipo === 'salida') {
            if ($nuevaCantidad < $cantidad) {
                return $this->response->setJSON(['success' => false, 'message' => 'Stock insuficiente']);
            }
            $nuevaCantidad -= $cantidad;
        } elseif ($tipo === 'ajuste') {
            $nuevaCantidad = $cantidad;
        }

        // Actualizar cantidad del item
        $this->itemModel->update($id, ['cantidad_actual' => $nuevaCantidad]);

        // Registrar movimiento
        $this->movimientoModel->save([
            'item_id' => $id,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'motivo' => $motivo,
            'user_id' => auth()->id(),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Movimiento registrado correctamente',
            'nueva_cantidad' => $nuevaCantidad
        ]);
    }
}
