<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-4" style="background-color: #000; min-height: 80vh;">
    <div class="container">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div class="mb-3 mb-md-0">
                <h1 style="color: #D4B68A;" class="mb-1"><i class="bi bi-tags"></i> Gestión de Categorías</h1>
                <p class="text-light mb-0">Administra las categorías del menú</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/menu') ?>" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Volver al Menú
                </a>
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearCategoria">
                    <i class="bi bi-plus-circle"></i> Nueva Categoría
                </button>
            </div>
        </div>

        <!-- Alertas -->
        <div id="alertContainer"></div>

        <!-- Tabla de Categorías -->
        <div class="card bg-dark text-light">
            <div class="card-header" style="background-color: #1a1a1a; border-bottom: 2px solid #D4B68A;">
                <h5 class="mb-0" style="color: #D4B68A;"><i class="bi bi-list-ul"></i> Categorías Registradas</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover mb-0">
                        <thead style="background-color: #2a2a2a;">
                            <tr>
                                <th style="color: #D4B68A; width: 60px;">#</th>
                                <th style="color: #D4B68A;">Nombre</th>
                                <th style="color: #D4B68A; width: 100px;">Orden</th>
                                <th style="color: #D4B68A; width: 100px;">Estado</th>
                                <th style="color: #D4B68A; width: 120px;">Platos</th>
                                <th class="text-center" style="color: #D4B68A; width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaCategorias">
                            <?php if (empty($categorias)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox"></i> No hay categorías registradas
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($categorias as $cat): ?>
                                    <tr data-categoria-id="<?= $cat['id'] ?>">
                                        <td class="text-light"><?= $cat['id'] ?></td>
                                        <td class="fw-bold text-light"><?= esc($cat['nombre']) ?></td>
                                        <td class="text-light"><?= $cat['orden'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $cat['activa'] ? 'success' : 'secondary' ?>">
                                                <?= $cat['activa'] ? 'Activa' : 'Inactiva' ?>
                                            </span>
                                        </td>
                                        <td class="text-light">
                                            <i class="bi bi-basket"></i> <?= $cat['total_platos'] ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-warning me-1"
                                                    onclick="editarCategoria(<?= $cat['id'] ?>, '<?= esc($cat['nombre']) ?>', <?= $cat['orden'] ?>, <?= $cat['activa'] ?>)"
                                                    title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-<?= $cat['activa'] ? 'secondary' : 'success' ?> me-1"
                                                    onclick="toggleEstado(<?= $cat['id'] ?>, <?= $cat['activa'] ?>)"
                                                    title="<?= $cat['activa'] ? 'Desactivar' : 'Activar' ?>">
                                                <i class="bi bi-<?= $cat['activa'] ? 'eye-slash' : 'eye' ?>"></i>
                                            </button>
                                            <?php if ($cat['total_platos'] == 0): ?>
                                                <button class="btn btn-sm btn-outline-danger"
                                                        onclick="eliminarCategoria(<?= $cat['id'] ?>, '<?= esc($cat['nombre']) ?>')"
                                                        title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Crear Categoría -->
<div class="modal fade" id="modalCrearCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header" style="background-color: #D4B68A; color: #000;">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle"></i> Nueva Categoría</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearCategoria">
                    <div class="mb-3">
                        <label for="nombreNuevaCategoria" class="form-label">Nombre de la Categoría</label>
                        <input type="text" class="form-control" id="nombreNuevaCategoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="ordenNuevaCategoria" class="form-label">Orden (opcional)</label>
                        <input type="number" class="form-control" id="ordenNuevaCategoria" value="0" min="0">
                        <small class="text-muted">Determina el orden de aparición en el menú</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="crearCategoria()">
                    <i class="bi bi-check-lg"></i> Crear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Categoría -->
<div class="modal fade" id="modalEditarCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header" style="background-color: #D4B68A; color: #000;">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil"></i> Editar Categoría</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarCategoria">
                    <input type="hidden" id="editarCategoriaId">
                    <div class="mb-3">
                        <label for="editarNombre" class="form-label">Nombre de la Categoría</label>
                        <input type="text" class="form-control" id="editarNombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editarOrden" class="form-label">Orden</label>
                        <input type="number" class="form-control" id="editarOrden" min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="actualizarCategoria()">
                    <i class="bi bi-check-lg"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function mostrarAlerta(mensaje, tipo = 'success') {
    const alertHtml = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            <i class="bi bi-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    document.getElementById('alertContainer').innerHTML = alertHtml;
    setTimeout(() => {
        document.getElementById('alertContainer').innerHTML = '';
    }, 5000);
}

function crearCategoria() {
    const nombre = document.getElementById('nombreNuevaCategoria').value.trim();
    const orden = document.getElementById('ordenNuevaCategoria').value;

    if (!nombre) {
        mostrarAlerta('El nombre es obligatorio', 'danger');
        return;
    }

    fetch('<?= base_url('admin/categorias/crear') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            nombre: nombre,
            orden: orden
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('modalCrearCategoria')).hide();
            document.getElementById('formCrearCategoria').reset();
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        mostrarAlerta('Error al crear la categoría', 'danger');
        console.error(error);
    });
}

function editarCategoria(id, nombre, orden, activa) {
    document.getElementById('editarCategoriaId').value = id;
    document.getElementById('editarNombre').value = nombre;
    document.getElementById('editarOrden').value = orden;
    new bootstrap.Modal(document.getElementById('modalEditarCategoria')).show();
}

function actualizarCategoria() {
    const id = document.getElementById('editarCategoriaId').value;
    const nombre = document.getElementById('editarNombre').value.trim();
    const orden = document.getElementById('editarOrden').value;

    if (!nombre) {
        mostrarAlerta('El nombre es obligatorio', 'danger');
        return;
    }

    fetch(`<?= base_url('admin/categorias/actualizar') ?>/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            nombre: nombre,
            orden: orden
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('modalEditarCategoria')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        mostrarAlerta('Error al actualizar la categoría', 'danger');
        console.error(error);
    });
}

function toggleEstado(id, estadoActual) {
    const nuevoEstado = estadoActual ? 0 : 1;

    fetch(`<?= base_url('admin/categorias/actualizar') ?>/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            activa: nuevoEstado
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        mostrarAlerta('Error al cambiar el estado', 'danger');
        console.error(error);
    });
}

function eliminarCategoria(id, nombre) {
    if (!confirm(`¿Estás seguro de eliminar la categoría "${nombre}"?`)) {
        return;
    }

    fetch(`<?= base_url('admin/categorias/eliminar') ?>/${id}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        mostrarAlerta('Error al eliminar la categoría', 'danger');
        console.error(error);
    });
}
</script>

<?= $this->endSection() ?>
