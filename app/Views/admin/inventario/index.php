<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-4" style="background-color: #000; min-height: 80vh;">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h1 style="color: #D4B68A;"><i class="bi bi-box-seam"></i> Inventario</h1>
            <a href="<?= base_url('admin/inventario/crear') ?>" class="btn btn-success btn-sm mt-3 mt-md-0">
                <i class="bi bi-plus-circle"></i> Agregar Item
            </a>
        </div>

        <!-- Alertas -->
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Alerta de Stock Bajo -->
        <?php if (!empty($itemsStockBajo)): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Alerta de Stock Bajo</h5>
                <p class="mb-0">Hay <?= count($itemsStockBajo) ?> item(s) con stock por debajo del mínimo:</p>
                <ul class="mb-0 mt-2">
                    <?php foreach ($itemsStockBajo as $item): ?>
                        <li><strong><?= esc($item['nombre']) ?></strong>: <?= $item['cantidad_actual'] ?> <?= esc($item['unidad_medida']) ?> (Mínimo: <?= $item['cantidad_minima'] ?>)</li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Filtros por Categoría -->
        <div class="card mb-4" style="background-color: #1a1a1a; border: 1px solid #D4B68A;">
            <div class="card-header" style="background-color: #D4B68A; color: #000;">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtrar por Categoría</h5>
            </div>
            <div class="card-body">
                <div class="btn-group flex-wrap" role="group">
                    <a href="<?= base_url('admin/inventario') ?>"
                       class="btn btn-sm <?= !$categoriaSeleccionada ? 'btn-warning' : 'btn-outline-warning' ?>">
                        <i class="bi bi-grid"></i> Todas
                    </a>
                    <?php foreach ($categorias as $cat): ?>
                        <a href="<?= base_url('admin/inventario?categoria=' . $cat['id']) ?>"
                           class="btn btn-sm <?= $categoriaSeleccionada == $cat['id'] ? 'btn-warning' : 'btn-outline-warning' ?>"
                           style="<?= $categoriaSeleccionada == $cat['id'] ? '' : 'border-color: ' . $cat['color'] . '; color: ' . $cat['color'] ?>">
                            <i class="bi bi-<?= $cat['icono'] ?>"></i> <?= esc($cat['nombre']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Tabla de Items -->
        <div class="card bg-dark text-light">
            <div class="card-header" style="background-color: #1a1a1a; border-bottom: 2px solid #D4B68A;">
                <h5 class="mb-0" style="color: #D4B68A;">
                    <i class="bi bi-list-ul"></i> Items de Inventario
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover mb-0">
                        <thead style="background-color: #2a2a2a;">
                            <tr>
                                <th style="color: #D4B68A;">Categoría</th>
                                <th style="color: #D4B68A;">Nombre</th>
                                <th class="d-none d-md-table-cell" style="color: #D4B68A;">Descripción</th>
                                <th class="text-center" style="color: #D4B68A;">Cantidad</th>
                                <th class="d-none d-lg-table-cell" style="color: #D4B68A;">Ubicación</th>
                                <th class="text-center" style="color: #D4B68A;">Estado</th>
                                <th class="text-center" style="color: #D4B68A;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox"></i> No hay items en el inventario
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <?php
                                    $stockBajo = isset($item['cantidad_minima']) && $item['cantidad_actual'] <= $item['cantidad_minima'];
                                    ?>
                                    <tr class="<?= $stockBajo ? 'table-warning' : '' ?>">
                                        <td>
                                            <span class="badge" style="background-color: <?= $item['categoria_color'] ?>; color: #fff;">
                                                <i class="bi bi-<?= $item['categoria_icono'] ?>"></i> <?= esc($item['categoria_nombre']) ?>
                                            </span>
                                        </td>
                                        <td class="text-light fw-bold"><?= esc($item['nombre']) ?></td>
                                        <td class="d-none d-md-table-cell text-light small"><?= esc($item['descripcion'] ?: '-') ?></td>
                                        <td class="text-center">
                                            <span class="badge <?= $stockBajo ? 'bg-danger' : 'bg-info' ?> fs-6">
                                                <?= number_format($item['cantidad_actual'], 2) ?> <?= esc($item['unidad_medida']) ?>
                                            </span>
                                            <?php if (isset($item['cantidad_minima'])): ?>
                                                <br><small class="text-muted">Min: <?= $item['cantidad_minima'] ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-lg-table-cell text-light small"><?= esc($item['ubicacion'] ?: '-') ?></td>
                                        <td class="text-center">
                                            <?php if ($item['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-success"
                                                        onclick="abrirModalMovimiento(<?= $item['id'] ?>, '<?= esc($item['nombre']) ?>', <?= $item['cantidad_actual'] ?>)"
                                                        title="Registrar Movimiento">
                                                    <i class="bi bi-arrow-left-right"></i>
                                                </button>
                                                <a href="<?= base_url('admin/inventario/editar/' . $item['id']) ?>"
                                                   class="btn btn-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= base_url('admin/inventario/eliminar/' . $item['id']) ?>"
                                                   class="btn btn-danger"
                                                   onclick="return confirm('¿Eliminar este item?')"
                                                   title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
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

<!-- Modal para Movimientos -->
<div class="modal fade" id="modalMovimiento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header" style="border-bottom: 1px solid #D4B68A;">
                <h5 class="modal-title" style="color: #D4B68A;">
                    <i class="bi bi-arrow-left-right"></i> Registrar Movimiento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formMovimiento">
                    <input type="hidden" id="item_id" name="item_id">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Item:</label>
                        <p id="item_nombre" class="text-light"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Cantidad Actual:</label>
                        <p id="cantidad_actual" class="text-info fs-5"></p>
                    </div>

                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Movimiento</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="entrada">Entrada (Agregar)</option>
                            <option value="salida">Salida (Quitar)</option>
                            <option value="ajuste">Ajuste (Establecer cantidad)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad"
                               step="0.01" min="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="motivo" class="form-label">Motivo</label>
                        <input type="text" class="form-control" id="motivo" name="motivo"
                               placeholder="Ej: Compra, Uso en cocina, Inventario físico, etc.">
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #D4B68A;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarMovimiento()">
                    <i class="bi bi-check-lg"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let modalMovimiento;

document.addEventListener('DOMContentLoaded', function() {
    modalMovimiento = new bootstrap.Modal(document.getElementById('modalMovimiento'));
});

function abrirModalMovimiento(itemId, itemNombre, cantidadActual) {
    document.getElementById('item_id').value = itemId;
    document.getElementById('item_nombre').textContent = itemNombre;
    document.getElementById('cantidad_actual').textContent = cantidadActual;
    document.getElementById('formMovimiento').reset();
    document.getElementById('item_id').value = itemId;
    modalMovimiento.show();
}

function guardarMovimiento() {
    const itemId = document.getElementById('item_id').value;
    const tipo = document.getElementById('tipo').value;
    const cantidad = document.getElementById('cantidad').value;
    const motivo = document.getElementById('motivo').value;

    if (!cantidad || cantidad <= 0) {
        alert('Por favor ingrese una cantidad válida');
        return;
    }

    const formData = new FormData();
    formData.append('tipo', tipo);
    formData.append('cantidad', cantidad);
    formData.append('motivo', motivo);

    fetch('<?= base_url('admin/inventario/movimiento/') ?>' + itemId, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            modalMovimiento.hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
}
</script>

<?= $this->endSection() ?>
