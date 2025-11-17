<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-4" style="background-color: #000; min-height: 80vh;">
    <div class="container">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h1 style="color: #D4B68A;">
                <i class="bi bi-folder-open"></i> Archivo - Caja Chica
            </h1>
            <a href="<?= base_url('admin/caja-chica') ?>"
               class="btn btn-warning btn-sm mt-3 mt-md-0">
                <i class="bi bi-calendar-today"></i> Volver a Hoy
            </a>
        </div>

        <!-- Lista de Fechas -->
        <div class="card bg-dark text-light">
            <div class="card-header" style="background-color: #1a1a1a; border-bottom: 2px solid #D4B68A;">
                <h5 class="mb-0" style="color: #D4B68A;">
                    <i class="bi bi-calendar3"></i> Días con Movimientos
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead style="background-color: #2a2a2a;">
                            <tr>
                                <th style="color: #D4B68A;">Fecha</th>
                                <th class="text-center" style="color: #D4B68A;">Movimientos</th>
                                <th class="text-center" style="color: #D4B68A;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($fechas)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-light" style="opacity: 0.7;">
                                        <i class="bi bi-inbox"></i> No hay registros en el archivo
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($fechas as $f): ?>
                                    <?php
                                    $timestamp = strtotime($f['fecha']);
                                    $diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                                    $diaSemana = $diasSemana[date('w', $timestamp)];
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-light"><?= date('d/m/Y', $timestamp) ?></span>
                                                <small style="color: #D4B68A;"><?= $diaSemana ?></small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge" style="background-color: #D4B68A; color: #000;">
                                                <?= $f['cantidad'] ?> movimiento<?= $f['cantidad'] != 1 ? 's' : '' ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= base_url('admin/caja-chica/ver/' . $f['fecha']) ?>"
                                                   class="btn btn-warning">
                                                    <i class="bi bi-eye"></i>
                                                    <span class="d-none d-md-inline"> Ver</span>
                                                </a>
                                                <a href="<?= base_url('admin/caja-chica/imprimir/' . $f['fecha']) ?>"
                                                   target="_blank"
                                                   class="btn btn-secondary">
                                                    <i class="bi bi-printer"></i>
                                                    <span class="d-none d-md-inline"> Imprimir</span>
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
<?= $this->endSection() ?>
