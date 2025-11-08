<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning">
            <i class="bi bi-clock-history"></i> Historial de Cajas
        </h2>
        <a href="<?= site_url('admin/caja') ?>" class="btn btn-outline-warning">
            <i class="bi bi-arrow-left"></i> Volver a Caja
        </a>
    </div>

    <div class="card bg-dark border-warning">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha Apertura</th>
                            <th>Fecha Cierre</th>
                            <th>Usuario</th>
                            <th>Inicial</th>
                            <th>Esperado</th>
                            <th>Final</th>
                            <th>Diferencia</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($historial)): ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                    No hay historial de cajas
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($historial as $caja): ?>
                                <tr>
                                    <td><?= $caja['id'] ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($caja['fecha_apertura'])) ?></td>
                                    <td>
                                        <?php if ($caja['fecha_cierre']): ?>
                                            <?= date('d/m/Y H:i', strtotime($caja['fecha_cierre'])) ?>
                                        <?php else: ?>
                                            <span class="badge bg-success">Abierta</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($caja['username']) ?></td>
                                    <td>$<?= number_format($caja['monto_inicial'], 2) ?></td>
                                    <td>
                                        <?php if ($caja['monto_esperado']): ?>
                                            $<?= number_format($caja['monto_esperado'], 2) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($caja['monto_final']): ?>
                                            $<?= number_format($caja['monto_final'], 2) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($caja['diferencia'] !== null): ?>
                                            <?php if ($caja['diferencia'] == 0): ?>
                                                <span class="badge bg-success">Exacta</span>
                                            <?php elseif ($caja['diferencia'] > 0): ?>
                                                <span class="badge bg-warning text-dark">+$<?= number_format($caja['diferencia'], 2) ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">-$<?= number_format(abs($caja['diferencia']), 2) ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($caja['estado'] === 'abierta'): ?>
                                            <span class="badge bg-success">Abierta</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Cerrada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($caja['estado'] === 'cerrada'): ?>
                                            <a href="<?= site_url('admin/caja/ver/' . $caja['id']) ?>" class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-eye"></i> Ver
                                            </a>
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

<?= $this->endSection() ?>
