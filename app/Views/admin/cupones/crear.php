<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-dark border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-ticket-perforated"></i> Crear Nuevo Cupón
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

                    <form action="<?= site_url('admin/cupones/guardar') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="codigo" class="form-label text-warning">
                                    Código del Cupón <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="codigo" name="codigo"
                                       value="<?= old('codigo') ?>" required
                                       placeholder="Ej: BIENVENIDA20"
                                       style="text-transform: uppercase;">
                                <small class="text-muted">Será convertido a mayúsculas automáticamente</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label text-warning">
                                    Tipo de Descuento <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="tipo" name="tipo" required onchange="actualizarLabel()">
                                    <option value="porcentaje" <?= old('tipo') === 'porcentaje' ? 'selected' : '' ?>>
                                        Porcentaje (%)
                                    </option>
                                    <option value="monto_fijo" <?= old('tipo') === 'monto_fijo' ? 'selected' : '' ?>>
                                        Monto Fijo ($)
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="valor" class="form-label text-warning">
                                    <span id="valorLabel">Valor (%)</span> <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="valor" name="valor"
                                       value="<?= old('valor') ?>" required min="0" step="0.01"
                                       placeholder="Ej: 10">
                                <small class="text-muted" id="valorHelp">
                                    Ejemplo: 10 para 10% de descuento
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="monto_minimo" class="form-label text-warning">
                                    Monto Mínimo de Compra
                                </label>
                                <input type="number" class="form-control" id="monto_minimo" name="monto_minimo"
                                       value="<?= old('monto_minimo') ?>" min="0" step="0.01"
                                       placeholder="Opcional">
                                <small class="text-muted">Deja vacío si no hay monto mínimo</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label text-warning">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion"
                                      rows="2" placeholder="Ej: Descuento especial para nuevos clientes"><?= old('descripcion') ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="usos_maximos" class="form-label text-warning">
                                    Usos Máximos (Total)
                                </label>
                                <input type="number" class="form-control" id="usos_maximos" name="usos_maximos"
                                       value="<?= old('usos_maximos') ?>" min="1"
                                       placeholder="Ilimitado">
                                <small class="text-muted">Deja vacío para ilimitado</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="usos_por_usuario" class="form-label text-warning">
                                    Usos por Usuario
                                </label>
                                <input type="number" class="form-control" id="usos_por_usuario" name="usos_por_usuario"
                                       value="<?= old('usos_por_usuario', 1) ?>" min="1" required>
                                <small class="text-muted">Cuántas veces puede usar cada usuario</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo"
                                           <?= old('activo', true) ? 'checked' : '' ?>>
                                    <label class="form-check-label text-warning" for="activo">
                                        Activar cupón inmediatamente
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
                                       value="<?= old('fecha_inicio') ?>">
                                <small class="text-muted">Deja vacío para activar desde ahora</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fecha_expiracion" class="form-label text-warning">
                                    Fecha de Expiración
                                </label>
                                <input type="datetime-local" class="form-control" id="fecha_expiracion" name="fecha_expiracion"
                                       value="<?= old('fecha_expiracion') ?>">
                                <small class="text-muted">Deja vacío para que no expire</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= site_url('admin/cupones') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> Guardar Cupón
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function actualizarLabel() {
    const tipo = document.getElementById('tipo').value;
    const valorLabel = document.getElementById('valorLabel');
    const valorHelp = document.getElementById('valorHelp');

    if (tipo === 'porcentaje') {
        valorLabel.textContent = 'Valor (%)';
        valorHelp.textContent = 'Ejemplo: 10 para 10% de descuento';
    } else {
        valorLabel.textContent = 'Valor ($)';
        valorHelp.textContent = 'Ejemplo: 500 para $500 de descuento';
    }
}

// Convertir código a mayúsculas automáticamente
document.getElementById('codigo').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>

<?= $this->endSection() ?>
