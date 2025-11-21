<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-4" style="background-color: #000; min-height: 80vh;">
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 style="color: #D4B68A;">
                <i class="bi bi-pencil"></i> Editar Movimiento
            </h1>
            <a href="<?= base_url('admin/caja-chica/ver/' . $movimiento['fecha']) ?>"
               class="btn btn-secondary">
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
        <div class="card bg-dark text-light" style="border: 2px solid #D4B68A;">
            <div class="card-header" style="background-color: #D4B68A; color: #000;">
                <h5 class="mb-0 fw-bold">Datos del Movimiento</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/caja-chica/editar/' . $movimiento['id']) ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="color: #D4B68A;">Fecha</label>
                            <input type="date"
                                   name="fecha"
                                   value="<?= esc($movimiento['fecha']) ?>"
                                   required
                                   class="form-control bg-dark text-light"
                                   style="border-color: #D4B68A;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="color: #D4B68A;">Hora</label>
                            <input type="time"
                                   name="hora"
                                   value="<?= esc($movimiento['hora']) ?>"
                                   required
                                   class="form-control bg-dark text-light"
                                   style="border-color: #D4B68A;">
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label" style="color: #D4B68A;">Concepto</label>
                        <input type="text"
                               name="concepto"
                               value="<?= esc($movimiento['concepto']) ?>"
                               required
                               class="form-control bg-dark text-light"
                               style="border-color: #D4B68A;"
                               placeholder="Descripción del movimiento">
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="color: #D4B68A;">Tipo de Movimiento</label>
                            <select name="tipo"
                                    required
                                    class="form-select bg-dark text-light"
                                    style="border-color: #D4B68A;">
                                <option value="entrada" <?= $movimiento['tipo'] === 'entrada' ? 'selected' : '' ?>>
                                    💰 Entrada
                                </option>
                                <option value="salida" <?= $movimiento['tipo'] === 'salida' ? 'selected' : '' ?>>
                                    📤 Salida
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="color: #D4B68A;">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-light" style="border-color: #D4B68A;">$</span>
                                <input type="number"
                                       name="monto"
                                       value="<?= esc($movimiento['monto']) ?>"
                                       step="0.01"
                                       min="0.01"
                                       required
                                       class="form-control bg-dark text-light"
                                       style="border-color: #D4B68A;">
                            </div>
                        </div>
                    </div>

                    <div class="form-check mt-3">
                        <input class="form-check-input"
                               type="checkbox"
                               name="es_digital"
                               value="1"
                               id="esDigital"
                               <?= (!empty($movimiento['es_digital']) && $movimiento['es_digital'] == 1) ? 'checked' : '' ?>>
                        <label class="form-check-label text-light" for="esDigital">
                            📱 Dinero Digital (QR, Mercado Pago, Transferencia)
                        </label>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success flex-fill">
                            <i class="bi bi-check-lg"></i> Guardar Cambios
                        </button>
                        <a href="<?= base_url('admin/caja-chica/ver/' . $movimiento['fecha']) ?>"
                           class="btn btn-secondary flex-fill">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.form-control:focus,
.form-select:focus {
    background-color: #1a1a1a !important;
    border-color: #FFD700 !important;
    color: #fff !important;
}
</style>

<?= $this->endSection() ?>
