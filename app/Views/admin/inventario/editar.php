<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-4" style="background-color: #000; min-height: 80vh;">
    <div class="container">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h1 style="color: #D4B68A;"><i class="bi bi-pencil"></i> Editar Item de Inventario</h1>
            <a href="<?= base_url('admin/inventario') ?>" class="btn btn-secondary btn-sm mt-3 mt-md-0">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <!-- Alertas -->
        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <div class="card" style="background-color: #1a1a1a; border: 1px solid #D4B68A;">
            <div class="card-header" style="background-color: #D4B68A; color: #000;">
                <h5 class="mb-0"><i class="bi bi-box-seam"></i> Información del Item</h5>
            </div>
            <div class="card-body text-light">
                <form action="<?= base_url('admin/inventario/editar/' . $item['id']) ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="categoria_id" class="form-label">Categoría *</label>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $item['categoria_id'] == $cat['id'] ? 'selected' : '' ?>>
                                        <?= esc($cat['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre del Item *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                   value="<?= esc($item['nombre']) ?>" required>
                        </div>

                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="2"><?= esc($item['descripcion']) ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="unidad_medida" class="form-label">Unidad de Medida *</label>
                            <select class="form-select" id="unidad_medida" name="unidad_medida" required>
                                <option value="unidad" <?= $item['unidad_medida'] == 'unidad' ? 'selected' : '' ?>>Unidad</option>
                                <option value="kg" <?= $item['unidad_medida'] == 'kg' ? 'selected' : '' ?>>Kilogramos (kg)</option>
                                <option value="g" <?= $item['unidad_medida'] == 'g' ? 'selected' : '' ?>>Gramos (g)</option>
                                <option value="l" <?= $item['unidad_medida'] == 'l' ? 'selected' : '' ?>>Litros (l)</option>
                                <option value="ml" <?= $item['unidad_medida'] == 'ml' ? 'selected' : '' ?>>Mililitros (ml)</option>
                                <option value="paquete" <?= $item['unidad_medida'] == 'paquete' ? 'selected' : '' ?>>Paquete</option>
                                <option value="caja" <?= $item['unidad_medida'] == 'caja' ? 'selected' : '' ?>>Caja</option>
                                <option value="botella" <?= $item['unidad_medida'] == 'botella' ? 'selected' : '' ?>>Botella</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="cantidad_actual" class="form-label">Cantidad Actual</label>
                            <input type="number" class="form-control bg-secondary text-light" id="cantidad_actual"
                                   value="<?= number_format($item['cantidad_actual'], 2) ?>" readonly>
                            <small class="text-muted">Use "Registrar Movimiento" para cambiar la cantidad</small>
                        </div>

                        <div class="col-md-4">
                            <label for="cantidad_minima" class="form-label">Cantidad Mínima (Alerta)</label>
                            <input type="number" class="form-control" id="cantidad_minima" name="cantidad_minima"
                                   step="0.01" min="0" value="<?= $item['cantidad_minima'] ?>">
                            <small class="text-muted">Se alertará cuando el stock llegue a este nivel</small>
                        </div>

                        <div class="col-md-6">
                            <label for="precio_unitario" class="form-label">Precio Unitario</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="precio_unitario" name="precio_unitario"
                                       step="0.01" min="0" value="<?= $item['precio_unitario'] ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="proveedor" class="form-label">Proveedor</label>
                            <input type="text" class="form-control" id="proveedor" name="proveedor"
                                   value="<?= esc($item['proveedor']) ?>">
                        </div>

                        <div class="col-12">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion"
                                   value="<?= esc($item['ubicacion']) ?>"
                                   placeholder="Ej: Heladera 1, Estante 3, Depósito">
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo"
                                       value="1" <?= $item['activo'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="activo">
                                    Item Activo
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 justify-content-end">
                        <a href="<?= base_url('admin/inventario') ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg"></i> Actualizar Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
