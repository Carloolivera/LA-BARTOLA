<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-4" style="background-color: #000; min-height: 80vh;">
    <div class="container">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div class="mb-3 mb-md-0">
                <h1 style="color: #D4B68A;" class="mb-1">Caja Chica</h1>
                <p class="text-light mb-0">
                    <i class="bi bi-calendar3"></i> <?= date('d/m/Y', strtotime($fecha)) ?>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/caja-chica/archivo') ?>"
                   class="btn btn-secondary btn-sm">
                    <i class="bi bi-folder"></i> Archivo
                </a>
                <a href="<?= base_url('admin/caja-chica/imprimir/' . $fecha) ?>"
                   target="_blank"
                   class="btn btn-warning btn-sm">
                    <i class="bi bi-printer"></i> Imprimir
                </a>
            </div>
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

        <!-- Formulario Agregar Movimiento -->
        <?php if ($esHoy): ?>
        <div class="card mb-4" style="background-color: #1a1a1a; border: 1px solid #D4B68A;">
            <div class="card-header" style="background-color: #D4B68A; color: #000;">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle"></i> Agregar Movimiento</h5>
            </div>
            <div class="card-body text-light">
                <form action="<?= base_url('admin/caja-chica/agregar') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label small">Fecha</label>
                            <input type="date" name="fecha" id="inputFecha" value="<?= $fecha ?>" required
                                   class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Hora</label>
                            <input type="time" name="hora" id="inputHora" value="<?= date('H:i') ?>" required
                                   class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Concepto</label>
                            <input type="text" name="concepto" required
                                   class="form-control form-control-sm"
                                   placeholder="Descripción del movimiento">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Tipo</label>
                            <select name="tipo" required class="form-select form-select-sm">
                                <option value="entrada">Entrada</option>
                                <option value="salida">Salida</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Monto</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" name="monto" step="0.01" min="0.01" required
                                       class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="es_digital" value="1" id="esDigital">
                                <label class="form-check-label small" for="esDigital">
                                    Dinero Digital
                                </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-auto d-flex align-items-end">
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="bi bi-check-lg"></i> Agregar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tabla de Movimientos -->
        <div class="card bg-dark text-light">
            <div class="card-header" style="background-color: #1a1a1a; border-bottom: 2px solid #D4B68A;">
                <h5 class="mb-0" style="color: #D4B68A;"><i class="bi bi-list-ul"></i> Movimientos del Día</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover mb-0">
                        <thead style="background-color: #2a2a2a;">
                            <tr>
                                <th class="d-none d-md-table-cell" style="color: #D4B68A;">Fecha</th>
                                <th style="color: #D4B68A;">Hora</th>
                                <th style="color: #D4B68A;">Concepto</th>
                                <th class="text-end" style="color: #D4B68A;">Entrada</th>
                                <th class="text-end" style="color: #D4B68A;">Salida</th>
                                <th class="text-end" style="color: #D4B68A;">Saldo</th>
                                <?php if ($esHoy): ?>
                                <th class="text-center" style="color: #D4B68A;">Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $saldoAcumulado = 0;
                            if (empty($movimientos)):
                            ?>
                                <tr>
                                    <td colspan="<?= $esHoy ? 7 : 6 ?>" class="text-center py-4 text-light" style="opacity: 0.7;">
                                        <i class="bi bi-inbox"></i> No hay movimientos para esta fecha
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($movimientos as $mov): ?>
                                    <?php
                                    if ($mov['tipo'] === 'entrada') {
                                        $saldoAcumulado += $mov['monto'];
                                    } else {
                                        $saldoAcumulado -= $mov['monto'];
                                    }
                                    ?>
                                    <tr>
                                        <td class="d-none d-md-table-cell small text-light">
                                            <?= date('d/m/Y', strtotime($mov['fecha'])) ?>
                                        </td>
                                        <td class="small text-light"><?= date('H:i', strtotime($mov['hora'])) ?></td>
                                        <td class="small text-light"><?= esc($mov['concepto']) ?></td>
                                        <td class="text-end fw-bold" style="color: #28a745;">
                                            <?= $mov['tipo'] === 'entrada' ? '$' . number_format($mov['monto'], 2) : '-' ?>
                                        </td>
                                        <td class="text-end fw-bold" style="color: #dc3545;">
                                            <?= $mov['tipo'] === 'salida' ? '$' . number_format($mov['monto'], 2) : '-' ?>
                                        </td>
                                        <td class="text-end fw-bold" style="color: #D4B68A;">
                                            $<?= number_format($saldoAcumulado, 2) ?>
                                        </td>
                                        <?php if ($esHoy): ?>
                                        <td class="text-center">
                                            <a href="<?= base_url('admin/caja-chica/editar/' . $mov['id']) ?>"
                                               class="btn btn-sm btn-outline-warning me-1"
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="<?= base_url('admin/caja-chica/eliminar/' . $mov['id']) ?>"
                                               onclick="return confirm('¿Eliminar este movimiento?')"
                                               class="btn btn-sm btn-outline-danger"
                                               title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot style="background-color: #2a2a2a;">
                            <tr class="fw-bold">
                                <td colspan="<?= $esHoy ? 3 : 2 ?>" class="text-end" style="color: #fff;">Total Entradas (*):</td>
                                <td class="text-end text-success">$<?= number_format($entradas, 2) ?></td>
                                <td colspan="<?= $esHoy ? 3 : 2 ?>"></td>
                            </tr>
                            <tr class="fw-bold">
                                <td colspan="<?= $esHoy ? 3 : 2 ?>" class="text-end" style="color: #fff;">Total Salidas:</td>
                                <td colspan="2" class="text-end text-danger">$<?= number_format($salidas, 2) ?></td>
                                <td colspan="<?= $esHoy ? 2 : 1 ?>"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Resumen -->
        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <p class="card-text mb-1 small">(*) Total Efectivo</p>
                        <h3 class="mb-0">$<?= number_format($efectivo, 2) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white" style="background-color: #6f42c1;">
                    <div class="card-body text-center">
                        <p class="card-text mb-1 small">Total Dinero Digital</p>
                        <h3 class="mb-0">$<?= number_format($digital, 2) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Saldo Final -->
        <div class="card mt-3" style="background-color: #D4B68A; color: #000;">
            <div class="card-body text-center">
                <p class="card-text mb-1 fw-bold">SALDO ACTUAL</p>
                <h2 class="mb-0 fw-bold">$<?= number_format($saldo, 2) ?></h2>
            </div>
        </div>
    </div>
</section>

<script>
// Actualizar hora automáticamente
function actualizarHora() {
    const ahora = new Date();
    const horas = String(ahora.getHours()).padStart(2, '0');
    const minutos = String(ahora.getMinutes()).padStart(2, '0');
    const horaActual = `${horas}:${minutos}`;

    const inputHora = document.getElementById('inputHora');
    if (inputHora) {
        inputHora.value = horaActual;
    }
}

// Actualizar fecha y hora al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Establecer hora inicial
    actualizarHora();

    // Actualizar la hora cuando el usuario interactúa con el formulario
    const form = document.querySelector('form');
    if (form) {
        // Actualizar hora al hacer foco en cualquier campo del formulario
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.name !== 'hora') { // No actualizar cuando se edita manualmente la hora
                input.addEventListener('focus', actualizarHora);
            }
        });

        // Actualizar hora justo antes de enviar el formulario
        form.addEventListener('submit', function() {
            actualizarHora();
        });
    }

    // Actualizar automáticamente cada 30 segundos
    setInterval(actualizarHora, 30000);
});
</script>

<?= $this->endSection() ?>
