<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning">
            <i class="bi bi-cash-coin"></i> Gestión de Caja
        </h2>
        <a href="<?= site_url('admin/caja/historial') ?>" class="btn btn-outline-warning">
            <i class="bi bi-clock-history"></i> Historial
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!isset($caja_abierta)): ?>
        <!-- ABRIR CAJA -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-dark border-warning">
                    <div class="card-header bg-transparent border-warning">
                        <h5 class="mb-0 text-warning"><i class="bi bi-unlock-fill"></i> Abrir Caja</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('admin/caja/abrir') ?>" method="post">
                            <div class="mb-3">
                                <label for="monto_inicial" class="form-label text-beige">Monto Inicial en Efectivo</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="monto_inicial" name="monto_inicial" required value="0">
                                </div>
                                <small class="text-muted">Dinero inicial con el que se abre la caja</small>
                            </div>

                            <div class="mb-3">
                                <label for="notas_apertura" class="form-label text-beige">Notas (opcional)</label>
                                <textarea class="form-control" id="notas_apertura" name="notas_apertura" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-unlock"></i> Abrir Caja
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- CAJA ABIERTA -->
        <div class="row mb-4">
            <!-- RESUMEN DE CAJA -->
            <div class="col-md-8">
                <div class="card bg-dark border-warning mb-4">
                    <div class="card-header bg-transparent border-warning d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-warning"><i class="bi bi-calculator"></i> Resumen de Caja</h5>
                        <span class="badge bg-success">ABIERTA</span>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="p-3 border border-secondary rounded">
                                    <small class="text-muted d-block">Monto Inicial</small>
                                    <h4 class="text-beige mb-0">$<?= number_format($caja_abierta['monto_inicial'], 2) ?></h4>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 border border-success rounded">
                                    <small class="text-muted d-block">Ventas Efectivo</small>
                                    <h4 class="text-success mb-0">+$<?= number_format($resumen['total_ventas_efectivo'] ?? 0, 2) ?></h4>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 border border-danger rounded">
                                    <small class="text-muted d-block">Egresos</small>
                                    <h4 class="text-danger mb-0">-$<?= number_format($resumen['total_egresos'], 2) ?></h4>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 border border-warning rounded">
                                    <small class="text-muted d-block">Esperado en Caja</small>
                                    <h4 class="text-warning mb-0">$<?= number_format($monto_esperado, 2) ?></h4>
                                </div>
                            </div>
                        </div>

                        <hr class="border-secondary">

                        <h6 class="text-beige mb-3"><i class="bi bi-credit-card"></i> Ventas por Método de Pago</h6>
                        <div class="row text-center">
                            <div class="col-md-3">
                                <small class="text-muted d-block">Efectivo</small>
                                <strong class="text-beige">$<?= number_format($resumen['total_ventas_efectivo'] ?? 0, 2) ?></strong>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block">QR</small>
                                <strong class="text-beige">$<?= number_format($resumen['total_ventas_qr'] ?? 0, 2) ?></strong>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block">Mercado Pago</small>
                                <strong class="text-beige">$<?= number_format($resumen['total_ventas_mercado_pago'] ?? 0, 2) ?></strong>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block">Tarjeta</small>
                                <strong class="text-beige">$<?= number_format($resumen['total_ventas_tarjeta'] ?? 0, 2) ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACCIONES RÁPIDAS -->
            <div class="col-md-4">
                <div class="card bg-dark border-warning mb-3">
                    <div class="card-header bg-transparent border-warning">
                        <h6 class="mb-0 text-warning"><i class="bi bi-plus-circle"></i> Registrar Ingreso</h6>
                    </div>
                    <div class="card-body">
                        <form id="formIngreso" onsubmit="registrarMovimiento(event, 'ingreso')">
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm" name="concepto" placeholder="Concepto" required>
                            </div>
                            <div class="mb-2">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" name="monto" placeholder="Monto" required>
                                </div>
                            </div>
                            <div class="mb-2">
                                <textarea class="form-control form-control-sm" name="notas" placeholder="Notas (opcional)" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="bi bi-plus"></i> Registrar Ingreso
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card bg-dark border-warning mb-3">
                    <div class="card-header bg-transparent border-warning">
                        <h6 class="mb-0 text-warning"><i class="bi bi-dash-circle"></i> Registrar Egreso</h6>
                    </div>
                    <div class="card-body">
                        <form id="formEgreso" onsubmit="registrarMovimiento(event, 'egreso')">
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm" name="concepto" placeholder="Concepto" required>
                            </div>
                            <div class="mb-2">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" name="monto" placeholder="Monto" required>
                                </div>
                            </div>
                            <div class="mb-2">
                                <textarea class="form-control form-control-sm" name="notas" placeholder="Notas (opcional)" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="bi bi-dash"></i> Registrar Egreso
                            </button>
                        </form>
                    </div>
                </div>

                <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#cerrarCajaModal">
                    <i class="bi bi-lock-fill"></i> Cerrar Caja y Arqueo
                </button>
            </div>
        </div>

        <!-- MOVIMIENTOS -->
        <div class="card bg-dark border-warning">
            <div class="card-header bg-transparent border-warning">
                <h5 class="mb-0 text-warning"><i class="bi bi-list-ul"></i> Movimientos del Día</h5>
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

        <!-- MODAL CERRAR CAJA -->
        <div class="modal fade" id="cerrarCajaModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-beige">
                    <div class="modal-header border-warning">
                        <h5 class="modal-title text-warning"><i class="bi bi-lock-fill"></i> Cerrar Caja y Arqueo</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= site_url('admin/caja/cerrar/' . $caja_abierta['id']) ?>" method="post">
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <strong>Monto esperado en caja:</strong> $<?= number_format($monto_esperado, 2) ?>
                            </div>

                            <div class="mb-3">
                                <label for="monto_final" class="form-label">Monto Real en Caja (Efectivo)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="monto_final" name="monto_final" required>
                                </div>
                                <small class="text-muted">Contar el dinero físico en la caja</small>
                            </div>

                            <div class="mb-3">
                                <label for="notas_cierre" class="form-label">Notas de Cierre</label>
                                <textarea class="form-control" id="notas_cierre" name="notas_cierre" rows="3"></textarea>
                            </div>

                            <div id="diferencia_preview" class="alert d-none"></div>
                        </div>
                        <div class="modal-footer border-warning">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-lock"></i> Cerrar Caja
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function registrarMovimiento(event, tipo) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);

        const url = tipo === 'ingreso'
            ? '<?= site_url('admin/caja/registrarIngreso') ?>'
            : '<?= site_url('admin/caja/registrarEgreso') ?>';

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al registrar el movimiento');
        });
    }

    // Calcular diferencia en tiempo real
    const montoFinalInput = document.getElementById('monto_final');
    if (montoFinalInput) {
        montoFinalInput.addEventListener('input', function() {
            const esperado = <?= $monto_esperado ?? 0 ?>;
            const real = parseFloat(this.value) || 0;
            const diferencia = real - esperado;

            const previewDiv = document.getElementById('diferencia_preview');
            previewDiv.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');

            if (diferencia === 0) {
                previewDiv.classList.add('alert-success');
                previewDiv.innerHTML = '<strong>✓ Caja exacta</strong> - No hay diferencia';
            } else if (diferencia > 0) {
                previewDiv.classList.add('alert-warning');
                previewDiv.innerHTML = `<strong>⚠ Sobrante:</strong> $${diferencia.toFixed(2)}`;
            } else {
                previewDiv.classList.add('alert-danger');
                previewDiv.innerHTML = `<strong>✗ Faltante:</strong> $${Math.abs(diferencia).toFixed(2)}`;
            }
        });
    }
</script>

<?= $this->endSection() ?>
