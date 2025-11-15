<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning">
            <i class="bi bi-graph-up"></i> Dashboard de Analytics
        </h2>
        <div>
            <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#exportarModal">
                <i class="bi bi-download"></i> Exportar Datos
            </button>
        </div>
    </div>

    <!-- FILTROS DE FECHA -->
    <div class="card bg-dark border-warning mb-4">
        <div class="card-body">
            <form method="GET" action="<?= site_url('admin/analytics') ?>" class="row g-3">
                <div class="col-md-4">
                    <label for="fecha_desde" class="form-label text-beige">Fecha Desde:</label>
                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="<?= $fecha_desde ?>">
                </div>
                <div class="col-md-4">
                    <label for="fecha_hasta" class="form-label text-beige">Fecha Hasta:</label>
                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="<?= $fecha_hasta ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-dark border-warning text-center">
                <div class="card-body">
                    <h5 class="card-title text-beige">Total Pedidos</h5>
                    <h2 class="text-warning"><?= number_format($stats['total_pedidos']) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark border-warning text-center">
                <div class="card-body">
                    <h5 class="card-title text-beige">Total Ventas</h5>
                    <h2 class="text-warning">$<?= number_format($stats['total_ventas'], 2) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark border-warning text-center">
                <div class="card-body">
                    <h5 class="card-title text-beige">Cupones Usados</h5>
                    <h2 class="text-warning"><?= number_format($stats['total_cupones_usados']) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark border-warning text-center">
                <div class="card-body">
                    <h5 class="card-title text-beige">Descuentos Aplicados</h5>
                    <h2 class="text-warning">$<?= number_format($stats['total_descuentos'], 2) ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS -->
    <div class="row mb-4">
        <!-- VENTAS POR DÍA -->
        <div class="col-md-8">
            <div class="card bg-dark border-warning">
                <div class="card-header bg-transparent border-warning">
                    <h5 class="mb-0 text-warning">Ventas por Día</h5>
                </div>
                <div class="card-body">
                    <canvas id="ventasPorDiaChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- ESTADOS DE PEDIDOS -->
        <div class="col-md-4">
            <div class="card bg-dark border-warning">
                <div class="card-header bg-transparent border-warning">
                    <h5 class="mb-0 text-warning">Estado de Pedidos</h5>
                </div>
                <div class="card-body">
                    <canvas id="estadosPedidosChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- MÉTODOS DE PAGO -->
        <div class="col-md-6">
            <div class="card bg-dark border-warning">
                <div class="card-header bg-transparent border-warning">
                    <h5 class="mb-0 text-warning">Métodos de Pago</h5>
                </div>
                <div class="card-body">
                    <canvas id="metodosPagoChart"></canvas>
                </div>
            </div>
        </div>

        <!-- TIPOS DE ENTREGA -->
        <div class="col-md-6">
            <div class="card bg-dark border-warning">
                <div class="card-header bg-transparent border-warning">
                    <h5 class="mb-0 text-warning">Tipos de Entrega</h5>
                </div>
                <div class="card-body">
                    <canvas id="tiposEntregaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLAS -->
    <div class="row mb-4">
        <!-- PLATOS MÁS VENDIDOS -->
        <div class="col-md-6">
            <div class="card bg-dark border-warning">
                <div class="card-header bg-transparent border-warning">
                    <h5 class="mb-0 text-warning">Top 10 Platos Más Vendidos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Plato</th>
                                    <th>Cantidad</th>
                                    <th>Ingresos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($platos_mas_vendidos as $plato): ?>
                                <tr>
                                    <td><?= esc($plato['nombre']) ?></td>
                                    <td><?= number_format($plato['total_vendido']) ?></td>
                                    <td class="text-warning">$<?= number_format($plato['total_ingresos'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($platos_mas_vendidos)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No hay datos disponibles</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- CUPONES MÁS USADOS -->
        <div class="col-md-6">
            <div class="card bg-dark border-warning">
                <div class="card-header bg-transparent border-warning">
                    <h5 class="mb-0 text-warning">Top 10 Cupones Más Usados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Usos</th>
                                    <th>Descuento Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cupones_mas_usados as $cupon): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($cupon['codigo']) ?></strong>
                                        <br><small class="text-muted"><?= esc($cupon['descripcion']) ?></small>
                                    </td>
                                    <td><?= number_format($cupon['usos']) ?></td>
                                    <td class="text-danger">-$<?= number_format($cupon['total_descuento'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($cupones_mas_usados)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No hay datos disponibles</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EXPORTAR -->
<div class="modal fade" id="exportarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-beige">
            <div class="modal-header border-warning">
                <h5 class="modal-title text-warning">Exportar Datos</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formExportar">
                    <div class="mb-3">
                        <label class="form-label">Tipo de datos:</label>
                        <select class="form-select" name="tipo" required>
                            <option value="ventas">Ventas / Pedidos</option>
                            <option value="cupones">Uso de Cupones</option>
                        </select>
                    </div>
                    <input type="hidden" name="fecha_desde" value="<?= $fecha_desde ?>">
                    <input type="hidden" name="fecha_hasta" value="<?= $fecha_hasta ?>">
                </form>
            </div>
            <div class="modal-footer border-warning">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="exportarDatos()">
                    <i class="bi bi-download"></i> Descargar CSV
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Configuración de colores
const goldColor = '#d4af37';
const beigeColor = '#f5f5dc';
const colors = [
    '#d4af37', '#ffd700', '#ffed4e', '#daa520', '#b8860b',
    '#ff6384', '#36a2eb', '#4bc0c0', '#9966ff', '#ff9f40'
];

// 1. Ventas por Día
const ventasPorDiaCtx = document.getElementById('ventasPorDiaChart').getContext('2d');
new Chart(ventasPorDiaCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($ventas_por_dia, 'fecha')) ?>,
        datasets: [
            {
                label: 'Cantidad de Pedidos',
                data: <?= json_encode(array_column($ventas_por_dia, 'cantidad_pedidos')) ?>,
                borderColor: goldColor,
                backgroundColor: 'rgba(212, 175, 55, 0.1)',
                yAxisID: 'y',
            },
            {
                label: 'Total Ventas ($)',
                data: <?= json_encode(array_column($ventas_por_dia, 'total_ventas')) ?>,
                borderColor: beigeColor,
                backgroundColor: 'rgba(245, 245, 220, 0.1)',
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                labels: { color: beigeColor }
            }
        },
        scales: {
            x: {
                ticks: { color: beigeColor },
                grid: { color: 'rgba(245, 245, 220, 0.1)' }
            },
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                ticks: { color: goldColor },
                grid: { color: 'rgba(212, 175, 55, 0.1)' },
                title: {
                    display: true,
                    text: 'Cantidad de Pedidos',
                    color: goldColor
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                ticks: { color: beigeColor },
                grid: { drawOnChartArea: false },
                title: {
                    display: true,
                    text: 'Ventas ($)',
                    color: beigeColor
                }
            }
        }
    }
});

// 2. Estados de Pedidos
const estadosPedidosCtx = document.getElementById('estadosPedidosChart').getContext('2d');
new Chart(estadosPedidosCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($estados_pedidos, 'estado')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($estados_pedidos, 'cantidad')) ?>,
            backgroundColor: colors
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: beigeColor }
            }
        }
    }
});

// 3. Métodos de Pago
const metodosPagoCtx = document.getElementById('metodosPagoChart').getContext('2d');
new Chart(metodosPagoCtx, {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_column($metodos_pago, 'forma_pago')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($metodos_pago, 'cantidad')) ?>,
            backgroundColor: colors
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: beigeColor }
            }
        }
    }
});

// 4. Tipos de Entrega
const tiposEntregaCtx = document.getElementById('tiposEntregaChart').getContext('2d');
new Chart(tiposEntregaCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($tipos_entrega, 'tipo_entrega')) ?>,
        datasets: [{
            label: 'Cantidad',
            data: <?= json_encode(array_column($tipos_entrega, 'cantidad')) ?>,
            backgroundColor: goldColor,
            borderColor: beigeColor,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                ticks: { color: beigeColor },
                grid: { color: 'rgba(245, 245, 220, 0.1)' }
            },
            y: {
                ticks: { color: beigeColor },
                grid: { color: 'rgba(245, 245, 220, 0.1)' },
                beginAtZero: true
            }
        }
    }
});

// Función para exportar datos
function exportarDatos() {
    const form = document.getElementById('formExportar');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);

    window.location.href = '<?= site_url('admin/analytics/exportar') ?>?' + params.toString();

    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('exportarModal'));
    modal.hide();
}
</script>

<?= $this->endSection() ?>
