<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlatoModel;
use CodeIgniter\Database\Exceptions\DataException;

class Menu extends BaseController
{
    protected $platoModel;
    protected $uploadPath;

    public function __construct()
    {
        $this->platoModel = new PlatoModel();
        // Carpeta de imágenes en public
        $this->uploadPath = ROOTPATH . 'public/assets/images/platos';
    }

    public function index()
    {
        $platos = $this->platoModel
            ->orderBy('categoria', 'ASC')
            ->orderBy('nombre', 'ASC')
            ->findAll();

        return view('admin/menu/index', ['platos' => $platos]);
    }

    public function crear()
    {
        $auth = service('auth');
        $user = $auth->user();
        if (! $user || ! ($user->inGroup('admin') || $user->inGroup('vendedor'))) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        return view('admin/menu/crear');
    }

    public function guardar()
    {
        $auth = service('auth');
        $user = $auth->user();
        if (! $user || ! ($user->inGroup('admin') || $user->inGroup('vendedor'))) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        // Reglas del lado servidor
        $rules = [
            'nombre'  => 'required|min_length[3]|max_length[255]',
            'precio'  => 'required|numeric',
            'imagen'  => 'uploaded[imagen]|is_image[imagen]',
            'stock'   => 'required|integer|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre'          => $this->request->getPost('nombre'),
            'categoria'       => $this->request->getPost('categoria'),
            'descripcion'     => $this->request->getPost('descripcion'),
            'precio'          => $this->request->getPost('precio'),
            'stock'           => (int) $this->request->getPost('stock'),
            'stock_ilimitado' => $this->request->getPost('stock_ilimitado') ? 1 : 0,
            'disponible'      => $this->request->getPost('disponible') ? 1 : 0,
        ];

        // Manejo de imagen: nombre aleatorio (hash)
        $img = $this->request->getFile('imagen');
        if ($img && $img->isValid() && ! $img->hasMoved()) {
            // getRandomName ya devuelve nombre aleatorio; prefiero prefix con tiempo para menor colisión
            $newName = bin2hex(random_bytes(8)) . '_' . $img->getRandomName();
            // Asegurar carpeta
            if (! is_dir($this->uploadPath)) {
                mkdir($this->uploadPath, 0755, true);
            }
            $img->move($this->uploadPath, $newName);
            $data['imagen'] = $newName;
        }

        try {
            $this->platoModel->insert($data);
            return redirect()->to('/admin/menu')->with('success', 'Plato agregado correctamente');
        } catch (DataException $e) {
            return redirect()->back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function editar($id)
    {
        $auth = service('auth');
        $user = $auth->user();
        if (! $user || ! ($user->inGroup('admin') || $user->inGroup('vendedor'))) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $plato = $this->platoModel->find($id);
        if (! $plato) {
            return redirect()->to('/admin/menu')->with('error', 'Plato no encontrado');
        }

        return view('admin/menu/editar', ['plato' => $plato]);
    }

    public function actualizar($id)
    {
        $auth = service('auth');
        $user = $auth->user();
        if (! $user || ! ($user->inGroup('admin') || $user->inGroup('vendedor'))) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        $rules = [
            'nombre' => 'required|min_length[3]|max_length[255]',
            'precio' => 'required|numeric',
            'imagen' => 'permit_empty|is_image[imagen]',
            'stock'  => 'required|integer|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre'          => $this->request->getPost('nombre'),
            'categoria'       => $this->request->getPost('categoria'),
            'descripcion'     => $this->request->getPost('descripcion'),
            'precio'          => $this->request->getPost('precio'),
            'stock'           => (int) $this->request->getPost('stock'),
            'stock_ilimitado' => $this->request->getPost('stock_ilimitado') ? 1 : 0,
            'disponible'      => $this->request->getPost('disponible') ? 1 : 0,
        ];

        $img = $this->request->getFile('imagen');
        if ($img && $img->isValid() && ! $img->hasMoved()) {
            $newName = bin2hex(random_bytes(8)) . '_' . $img->getRandomName();
            if (! is_dir($this->uploadPath)) {
                mkdir($this->uploadPath, 0755, true);
            }
            $img->move($this->uploadPath, $newName);
            $data['imagen'] = $newName;
        }

        try {
            $this->platoModel->update($id, $data);
            return redirect()->to('/admin/menu')->with('success', 'Plato actualizado correctamente');
        } catch (DataException $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        $auth = service('auth');
        $user = $auth->user();
        if (! $user || ! ($user->inGroup('admin') || $user->inGroup('vendedor'))) {
            return redirect()->to('/login')->with('error', 'No autorizado');
        }

        // Opcional: borrar archivo físico si existe
        $plato = $this->platoModel->find($id);
        if ($plato && ! empty($plato['imagen'])) {
            $path = $this->uploadPath . DIRECTORY_SEPARATOR . $plato['imagen'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $this->platoModel->delete($id);
        return redirect()->to('/admin/menu')->with('success', 'Plato eliminado correctamente');
    }
}
