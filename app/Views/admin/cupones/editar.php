<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card bg-dark border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> Editar Cupón: <?= esc($cupon['codigo']) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <!-- Formulario de edición -->
                        <div class="col-md-7">
                            <form action="<?= site_url('admin/cupones/actualizar/' . $cupon['id']) ?>" method="post">
                                <?= csrf_field() ?>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo" class="form-label text-warning">
                                            Código del Cupón <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="codigo" name="codigo"
                                               value="<?= old('codigo', $cupon['codigo']) ?>" required
                                               style="text-transform: uppercase;">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="tipo" class="form-label text-warning">
                                            Tipo de Descuento <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="tipo" name="tipo" required onchange="actualizarLabel()">
                                            <option value="porcentaje" <?= old('tipo', $cupon['tipo']) === 'porcentaje' ? 'selected' : '' ?>>
                                                Porcentaje (%)
                                            </option>
                                            <option value="monto_fijo" <?= old('tipo', $cupon['tipo']) === 'monto_fijo' ? 'selected' : '' ?>>
                                                Monto Fijo ($)
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="valor" class="form-label text-warning">
                                            <span id="valorLabel">
                                                Valor (<?= $cupon['tipo'] === 'porcentaje' ? '%' : '$' ?>)
                                            </span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control" id="valor" name="valor"
                                               value="<?= old('valor', $cupon['valor']) ?>" required min="0" step="0.01">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="monto_minimo" class="form-label text-warning">
                                            Monto Mínimo de Compra
                                        </label>
                                        <input type="number" class="form-control" id="monto_minimo" name="monto_minimo"
                                               value="<?= old('monto_minimo', $cupon['monto_minimo']) ?>" min="0" step="0.01">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label text-warning">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="2"><?= old('descripcion', $cupon['descripcion']) ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="usos_maximos" class="form-label text-warning">
                                            Usos Máximos
                                        </label>
                                        <input type="number" class="form-control" id="usos_maximos" name="usos_maximos"
                                               value="<?= old('usos_maximos', $cupon['usos_maximos']) ?>" min="1">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="usos_por_usuario" class="form-label text-warning">
                                            Usos por Usuario
                                        </label>
                                        <input type="number" class="form-control" id="usos_por_usuario" name="usos_por_usuario"
                                               value="<?= old('usos_por_usuario', $cupon['usos_por_usuario']) ?>" min="1" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" id="activo" name="activo"
                                                   <?= old('activo', $cupon['activo']) ? 'checked' : '' ?>>
                                            <label class="form-check-label text-warning" for="activo">
                                                Cupón Activo
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_inicio" class="form-label text-warning">
                                            Fecha de Inicio
                                        </label>
                                        <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                               value="<?= old('fecha_inicio', $cupon['fecha_inicio'] ? date('Y-m-d\TH:i', strtotime($cupon['fecha_inicio'])) : '') ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_expiracion" class="form-label text-warning">
                                            Fecha de Expiración
                                        </label>
                                        <input type="datetime-local" class="form-control" id="fecha_expiracion" name="fecha_expiracion"
                                               value="<?= old('fecha_expiracion', $cupon['fecha_expiracion'] ? date('Y-m-d\TH:i', strtotime($cupon['fecha_expiracion'])) : '') ?>">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="<?= site_url('admin/cupones') ?>" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Volver
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-save"></i> Actualizar Cupón
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Estadísticas de uso -->
                        <div class="col-md-5">
                            <div class="card bg-secondary">
                                <div class="card-header">
                                    <h5 class="mb-0 text-warning">
                                        <i class="bi bi-graph-up"></i> Estadísticas de Uso
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="text-muted">Usos Totales</h6>
                                        <h3 class="text-warning">
                                            <?= $cupon['usos_actuales'] ?>
                                            <?php if ($cupon['usos_maximos']): ?>
                                                / <?= $cupon['usos_maximos'] ?>
                                            <?php endif; ?>
                                        </h3>
                                        <?php if ($cupon['usos_maximos']): ?>
                                            <div class="progress">
                                                <?php
                                                $porcentaje = ($cupon['usos_actuales'] / $cupon['usos_maximos']) * 100;
                                                $color = $porcentaje < 50 ? 'bg-success' : ($porcentaje < 80 ? 'bg-warning' : 'bg-danger');
                                                ?>
                                                <div class="progress-bar <?= $color ?>" style="width: <?= min($porcentaje, 100) ?>%">
                                                    <?= number_format($porcentaje, 1) ?>%
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($cupon['usos'])): ?>
                                        <h6 class="text-muted mt-4">Últimos Usos</h6>
                                        <div class="list-group">
                                            <?php foreach (array_slice($cupon['usos'], 0, 5) as $uso): ?>
                                                <div class="list-group-item bg-dark text-light border-secondary">
                                                    <div class="d-flex justify-content-between">
                                                        <span>
                                                            <i class="bi bi-person"></i>
                                                            <?= esc($uso['username']) ?>
                                                        </span>
                                                        <span class="badge bg-warning text-dark">
                                                            -$<?= number_format($uso['descuento_aplicado'], 2) ?>
                                                        </span>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y H:i', strtotime($uso['created_at'])) ?>
                                                    </small>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i> Aún no hay usos registrados
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function actualizarLabel() {
    const tipo = document.getElementById('tipo').value;
    const valorLabel = document.getElementById('valorLabel');

    if (tipo === 'porcentaje') {
        valorLabel.textContent = 'Valor (%)';
    } else {
        valorLabel.textContent = 'Valor ($)';
    }
}

document.getElementById('codigo').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>

<?= $this->endSection() ?>
