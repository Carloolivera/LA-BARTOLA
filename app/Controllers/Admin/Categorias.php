<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;
use App\Models\PlatoModel;

class Categorias extends BaseController
{
    protected $categoriaModel;
    protected $platoModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
        $this->platoModel = new PlatoModel();
    }

    public function index()
    {
        $categorias = $this->categoriaModel->getTodas();

        // Contar platos por categoría
        foreach ($categorias as &$cat) {
            $cat['total_platos'] = $this->platoModel
                ->where('categoria', $cat['nombre'])
                ->countAllResults();
        }

        return view('admin/categorias/index', ['categorias' => $categorias]);
    }

    public function crear()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/categorias');
        }

        $nombre = $this->request->getPost('nombre');
        $orden = $this->request->getPost('orden') ?: 0;

        if (empty($nombre)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El nombre es obligatorio'
            ]);
        }

        $data = [
            'nombre' => trim($nombre),
            'orden' => (int)$orden,
            'activa' => 1
        ];

        try {
            $id = $this->categoriaModel->insert($data);

            if ($id) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Categoría creada correctamente',
                    'categoria' => array_merge(['id' => $id], $data)
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear la categoría',
                    'errors' => $this->categoriaModel->errors()
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function actualizar($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/categorias');
        }

        $categoria = $this->categoriaModel->find($id);
        if (!$categoria) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ]);
        }

        $nombre = $this->request->getPost('nombre');
        $orden = $this->request->getPost('orden');
        $activa = $this->request->getPost('activa');

        $data = [];
        if ($nombre !== null) $data['nombre'] = trim($nombre);
        if ($orden !== null) $data['orden'] = (int)$orden;
        if ($activa !== null) $data['activa'] = (int)$activa;

        if (empty($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No hay datos para actualizar'
            ]);
        }

        try {
            // Si se está cambiando el nombre, actualizar los platos
            if (isset($data['nombre']) && $data['nombre'] !== $categoria['nombre']) {
                $this->platoModel
                    ->where('categoria', $categoria['nombre'])
                    ->set(['categoria' => $data['nombre']])
                    ->update();
            }

            $this->categoriaModel->update($id, $data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Categoría actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function eliminar($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/categorias');
        }

        $categoria = $this->categoriaModel->find($id);
        if (!$categoria) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ]);
        }

        // Verificar si tiene platos asociados
        $totalPlatos = $this->platoModel
            ->where('categoria', $categoria['nombre'])
            ->countAllResults();

        if ($totalPlatos > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "No se puede eliminar. Hay {$totalPlatos} plato(s) en esta categoría."
            ]);
        }

        try {
            $this->categoriaModel->delete($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Categoría eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function obtenerTodas()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/categorias');
        }

        $categorias = $this->categoriaModel->getActivas();

        return $this->response->setJSON([
            'success' => true,
            'categorias' => $categorias
        ]);
    }
}
