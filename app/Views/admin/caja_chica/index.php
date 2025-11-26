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
                <a href="<?= base_url('admin/caja-chica/exportarExcel/' . $fecha) ?>"
                   class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-excel"></i> Exportar Excel
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

        <!-- Resumen -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <p class="card-text mb-1 small">Total Efectivo</p>
                        <h3 class="mb-0">$<?= number_format($efectivo, 2) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white" style="background-color: #6f42c1;">
                    <div class="card-body text-center">
                        <p class="card-text mb-1 small">Total Dinero Digital</p>
                        <h3 class="mb-0">$<?= number_format($digital, 2) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="background-color: #D4B68A; color: #000;">
                    <div class="card-body text-center">
                        <p class="card-text mb-1 fw-bold">SALDO ACTUAL</p>
                        <h3 class="mb-0 fw-bold">$<?= number_format($saldo, 2) ?></h3>
                    </div>
                </div>
            </div>
        </div>

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
                        <div class="col-6 col-md-2">
                            <label class="form-label small">Fecha</label>
                            <input type="date" name="fecha" id="inputFecha" value="<?= $fecha ?>" required
                                   class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label small">Hora</label>
                            <input type="time" name="hora" id="inputHora" value="<?= date('H:i') ?>" required
                                   class="form-control form-control-sm">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small">Concepto</label>
                            <input type="text" name="concepto" required
                                   class="form-control form-control-sm"
                                   placeholder="Descripción del movimiento">
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label small">Tipo</label>
                            <select name="tipo" required class="form-select form-select-sm">
                                <option value="entrada">Entrada</option>
                                <option value="salida">Salida</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label small">Monto</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" name="monto" step="0.01" min="0.01" required
                                       class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label small d-block">Método de Pago</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="es_digital" value="1" id="esDigital">
                                <label class="form-check-label small" for="esDigital">
                                    <i class="bi bi-phone"></i> Dinero Digital
                                </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <button type="submit" class="btn btn-success btn-sm w-100 mt-md-4">
                                <i class="bi bi-check-lg"></i> Agregar Movimiento
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
                                <th style="color: #D4B68A;">Hora / Detalle</th>
                                <th class="text-end" style="color: #D4B68A;">Entrada</th>
                                <th class="text-end" style="color: #D4B68A;">Salida</th>
                                <th class="text-end d-none d-md-table-cell" style="color: #D4B68A;">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $saldoAcumulado = 0;
                            if (empty($movimientos)):
                            ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-light" style="opacity: 0.7;">
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
                                    
                                    // Extraer número de pedido del concepto si existe
                                    preg_match('/Pedido #(\d+)/', $mov['concepto'], $matches);
                                    $numPedido = isset($matches[1]) ? '#' . $matches[1] : '';
                                    
                                    // Extraer nombre del cliente
                                    preg_match('/- (.+?) \(/', $mov['concepto'], $matchesNombre);
                                    $nombreCliente = isset($matchesNombre[1]) ? $matchesNombre[1] : '';
                                    
                                    // Extraer método de pago
                                    preg_match('/\((.+?)\)/', $mov['concepto'], $matchesPago);
                                    $metodoPago = isset($matchesPago[1]) ? $matchesPago[1] : '';
                                    
                                    // Si no es un pedido, mostrar el concepto completo
                                    $esPedido = !empty($numPedido);
                                    ?>
                                    <tr>
                                        <td class="small text-light">
                                            <div><strong><?= date('H:i', strtotime($mov['hora'])) ?></strong></div>
                                            <div class="text-white" style="font-size: 0.85rem;">
                                                <?php if ($esPedido): ?>
                                                    <?php if ($numPedido): ?>
                                                        <span class="text-warning"><?= $numPedido ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($nombreCliente): ?>
                                                        - <?= esc($nombreCliente) ?>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <?= esc($mov['concepto']) ?>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($esPedido && $metodoPago): ?>
                                                <div>
                                                    <?php if (!empty($mov['es_digital']) && $mov['es_digital'] == 1): ?>
                                                        <span class="badge bg-info text-dark" style="font-size: 0.7rem;">
                                                            <i class="bi bi-phone"></i> <?= esc($metodoPago) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success" style="font-size: 0.7rem;">
                                                            <i class="bi bi-cash"></i> <?= esc($metodoPago) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php elseif (!$esPedido): ?>
                                                <div>
                                                    <?php if (!empty($mov['es_digital']) && $mov['es_digital'] == 1): ?>
                                                        <span class="badge bg-info text-dark" style="font-size: 0.7rem;">
                                                            <i class="bi bi-phone"></i> Dinero Digital
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success" style="font-size: 0.7rem;">
                                                            <i class="bi bi-cash"></i> Efectivo
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end fw-bold" style="color: #28a745;">
                                            <?= $mov['tipo'] === 'entrada' ? '$' . number_format($mov['monto'], 2) : '-' ?>
                                        </td>
                                        <td class="text-end fw-bold" style="color: #dc3545;">
                                            <?= $mov['tipo'] === 'salida' ? '$' . number_format($mov['monto'], 2) : '-' ?>
                                        </td>
                                        <td class="text-end fw-bold d-none d-md-table-cell" style="color: #D4B68A;">
                                            $<?= number_format($saldoAcumulado, 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot style="background-color: #2a2a2a;">
                            <tr class="fw-bold">
                                <td class="text-end" style="color: #fff;">Total Entradas:</td>
                                <td class="text-end text-success">$<?= number_format($entradas, 2) ?></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr class="fw-bold">
                                <td class="text-end" style="color: #fff;">Total Salidas:</td>
                                <td></td>
                                <td class="text-end text-danger">$<?= number_format($salidas, 2) ?></td>
                                <td class="d-none d-md-table-cell"></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Admin -->
    <footer class="text-center text-light py-4 mt-5" style="background-color: #1a1a1a; border-top: 2px solid #D4B68A;">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
                <a href="https://docs.google.com/spreadsheets" target="_blank" class="text-decoration-none" title="Google Sheets">
                    <i class="bi bi-file-earmark-excel" style="font-size: 1.8rem; color: #1D6F42;"></i>
                </a>
                <a href="https://docs.google.com/document" target="_blank" class="text-decoration-none" title="Google Docs">
                    <i class="bi bi-file-earmark-word" style="font-size: 1.8rem; color: #2B579A;"></i>
                </a>
                <a href="https://hpanel.hostinger.com" target="_blank" class="text-decoration-none" title="Hostinger Panel">
                    <i class="bi bi-hdd-rack" style="font-size: 1.8rem; color: #673DE6;"></i>
                </a>
                <a href="https://mail.google.com" target="_blank" class="text-decoration-none" title="Gmail">
                    <i class="bi bi-envelope" style="font-size: 1.8rem; color: #D93025;"></i>
                </a>
                <a href="https://www.instagram.com/aido_agenciaweb/" target="_blank" class="text-decoration-none" title="Soporte Técnico">
                    <i class="bi bi-life-preserver" style="font-size: 1.8rem; color: #D4B68A;"></i>
                </a>
            </div>
            <p class="mb-0 mt-3 small" style="color: #999;">© 2025 La Bartola | Panel Administrativo</p>
        </div>
    </footer>
</section>

<!-- Modal de Confirmación para Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light" style="border: 2px solid #D4B68A;">
            <div class="modal-header" style="border-bottom: 1px solid #D4B68A;">
                <h5 class="modal-title" style="color: #D4B68A;">
                    <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">¿Estás seguro de que deseas eliminar este movimiento?</p>
                <p class="text-warning mt-2 mb-0" id="conceptoEliminar"></p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #D4B68A;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Eliminar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light" style="border: 2px solid #D4B68A;">
            <div class="modal-header" style="border-bottom: 1px solid #D4B68A;">
                <h5 class="modal-title" style="color: #D4B68A;">
                    <i class="bi bi-pencil"></i> Confirmar Edición
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">¿Deseas editar este movimiento?</p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #D4B68A;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="btnConfirmarEditar" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Editar
                </a>
            </div>
        </div>
    </div>
</div>

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

// Confirmar eliminación de movimiento
function confirmarEliminar(id, concepto) {
    document.getElementById('conceptoEliminar').textContent = concepto;
    document.getElementById('btnConfirmarEliminar').href = '<?= base_url('admin/caja-chica/eliminar/') ?>' + id;
    const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
    modal.show();
}

// Confirmar edición de movimiento
function confirmarEditar(id) {
    document.getElementById('btnConfirmarEditar').href = '<?= base_url('admin/caja-chica/editar/') ?>' + id;
    const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
    modal.show();
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
