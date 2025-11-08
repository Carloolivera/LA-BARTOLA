<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning">
            <i class="bi bi-receipt"></i> Detalle de Caja #<?= $caja['id'] ?>
        </h2>
        <a href="<?= site_url('admin/caja/historial') ?>" class="btn btn-outline-warning">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-dark border-warning">
                <div class="card-header bg-transparent border-warning">
                    <h5 class="mb-0 text-warning"><i class="bi bi-info-circle"></i> Información General</h5>
                </div>
                <div class="card-body">
                    <table class="table table-dark table-borderless">
                        <tr>
                            <th class="text-muted">Fecha Apertura:</th>
                            <td class="text-end text-beige"><?= date('d/m/Y H:i', strtotime($caja['fecha_apertura'])) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Fecha Cierre:</th>
                            <td class="text-end text-beige"><?= date('d/m/Y H:i', strtotime($caja['fecha_cierre'])) ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Duración:</th>
                            <td class="text-end text-beige">
                                <?php
                                $inicio = new DateTime($caja['fecha_apertura']);
                                $fin = new DateTime($caja['fecha_cierre']);
                                $diff = $inicio->diff($fin);
                                echo $diff->format('%h horas %i minutos');
                                ?>
                            </td>
                        </tr>
                        <?php if ($caja['notas_apertura']): ?>
                        <tr>
                            <th class="text-muted">Notas Apertura:</th>
                            <td class="text-end text-beige"><?= esc($caja['notas_apertura']) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($caja['notas_cierre']): ?>
                        <tr>
                            <th class="text-muted">Notas Cierre:</th>
                            <td class="text-end text-beige"><?= esc($caja['notas_cierre']) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark border-warning">
                <div class="card-header bg-transparent border-warning">
                    <h5 class="mb-0 text-warning"><i class="bi bi-calculator"></i> Arqueo de Caja</h5>
                </div>
                <div class="card-body">
                    <table class="table table-dark table-borderless">
                        <tr>
                            <th class="text-muted">Monto Inicial:</th>
                            <td class="text-end text-beige">$<?= number_format($caja['monto_inicial'], 2) ?></td>
                        </tr>
                        <tr class="border-top border-secondary">
                            <th class="text-success">+ Ventas Efectivo:</th>
                            <td class="text-end text-success">$<?= number_format($resumen['total_ventas_efectivo'] ?? 0, 2) ?></td>
                        </tr>
                        <tr>
                            <th class="text-success">+ Ingresos:</th>
                            <td class="text-end text-success">$<?= number_format($resumen['total_ingresos_efectivo'], 2) ?></td>
                        </tr>
                        <tr>
                            <th class="text-danger">- Egresos:</th>
                            <td class="text-end text-danger">$<?= number_format($resumen['total_egresos'], 2) ?></td>
                        </tr>
                        <tr class="border-top border-warning">
                            <th class="text-warning">Monto Esperado:</th>
                            <td class="text-end text-warning fw-bold">$<?= number_format($caja['monto_esperado'], 2) ?></td>
                        </tr>
                        <tr>
                            <th class="text-beige">Monto Real:</th>
                            <td class="text-end text-beige fw-bold">$<?= number_format($caja['monto_final'], 2) ?></td>
                        </tr>
                        <tr class="border-top border-secondary">
                            <th class="text-muted">Diferencia:</th>
                            <td class="text-end">
                                <?php if ($caja['diferencia'] == 0): ?>
                                    <span class="badge bg-success">Exacta</span>
                                <?php elseif ($caja['diferencia'] > 0): ?>
                                    <span class="badge bg-warning text-dark">Sobrante: $<?= number_format($caja['diferencia'], 2) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Faltante: $<?= number_format(abs($caja['diferencia']), 2) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-dark border-warning">
                <div class="card-header bg-transparent border-warning">
                    <h5 class="mb-0 text-warning"><i class="bi bi-credit-card"></i> Resumen por Métodos de Pago</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3 border border-secondary rounded">
                                <small class="text-muted d-block">Efectivo</small>
                                <h4 class="text-success mb-0">$<?= number_format($resumen['total_ventas_efectivo'] ?? 0, 2) ?></h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border border-secondary rounded">
                                <small class="text-muted d-block">QR</small>
                                <h4 class="text-info mb-0">$<?= number_format($resumen['total_ventas_qr'] ?? 0, 2) ?></h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border border-secondary rounded">
                                <small class="text-muted d-block">Mercado Pago</small>
                                <h4 class="text-primary mb-0">$<?= number_format($resumen['total_ventas_mercado_pago'] ?? 0, 2) ?></h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border border-secondary rounded">
                                <small class="text-muted d-block">Tarjeta</small>
                                <h4 class="text-beige mb-0">$<?= number_format($resumen['total_ventas_tarjeta'] ?? 0, 2) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-dark border-warning">
        <div class="card-header bg-transparent border-warning">
            <h5 class="mb-0 text-warning"><i class="bi bi-list-ul"></i> Movimientos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Tipo</th>
                            <th>Concepto</th>
                            <th>Método</th>
                            <th>Monto</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($movimientos)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay movimientos registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($movimientos as $mov): ?>
                                <tr>
                                    <td><?= date('H:i', strtotime($mov['created_at'])) ?></td>
                                    <td>
                                        <?php if ($mov['tipo'] === 'ingreso'): ?>
                                            <span class="badge bg-success">Ingreso</span>
                                        <?php elseif ($mov['tipo'] === 'egreso'): ?>
                                            <span class="badge bg-danger">Egreso</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Venta</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($mov['concepto']) ?></td>
                                    <td><?= $mov['metodo_pago'] ? ucfirst($mov['metodo_pago']) : '-' ?></td>
                                    <td class="<?= $mov['tipo'] === 'egreso' ? 'text-danger' : 'text-success' ?>">
                                        <?= $mov['tipo'] === 'egreso' ? '-' : '+' ?>$<?= number_format($mov['monto'], 2) ?>
                                    </td>
                                    <td><?= esc($mov['username']) ?></td>
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
