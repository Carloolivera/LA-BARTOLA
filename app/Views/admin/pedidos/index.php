<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning">
            <i class="bi bi-receipt"></i> Gestión de Pedidos
        </h2>
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

    <!-- Filtros de estado -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-warning active" data-filter="todos">
                    Todos (<?= count($pedidos) ?>)
                </button>
                <button type="button" class="btn btn-outline-warning" data-filter="pendiente">
                    Pendientes
                </button>
                <button type="button" class="btn btn-outline-success" data-filter="en_proceso">
                    En Proceso
                </button>
                <button type="button" class="btn btn-outline-info" data-filter="completado">
                    Completados
                </button>
                <button type="button" class="btn btn-outline-danger" data-filter="cancelado">
                    Cancelados
                </button>
            </div>
        </div>
    </div>

    <div class="card bg-dark text-light">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover" id="tablaPedidos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>A nombre de</th>
                            <th>Plato</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Tipo Entrega</th>
                            <th>Dirección</th>
                            <th>Pago</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pedidos)): ?>
                            <tr>
                                <td colspan="12" class="text-center text-muted">No hay pedidos registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr data-estado="<?= esc($pedido['estado']) ?>">
                                    <td><strong>#<?= $pedido['id'] ?></strong></td>
                                    <td>
                                        <?= esc($pedido['username'] ?? 'Usuario eliminado') ?>
                                        <br>
                                        <small class="text-light" style="opacity: 0.7;"><?= esc($pedido['email'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <strong><?= esc($pedido['info_pedido']['nombre_cliente']) ?></strong>
                                    </td>
                                    <td><?= esc($pedido['plato_nombre']) ?></td>
                                    <td><span class="badge bg-info"><?= $pedido['cantidad'] ?></span></td>
                                    <td><strong class="text-success">$<?= number_format($pedido['total'], 2) ?></strong></td>
                                    <td>
                                        <?php if ($pedido['info_pedido']['tipo_entrega'] === 'delivery'): ?>
                                            <span class="badge bg-primary">
                                                <i class="bi bi-truck"></i> Delivery
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-bag"></i> Para llevar
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($pedido['info_pedido']['direccion'])): ?>
                                            <small><?= esc($pedido['info_pedido']['direccion']) ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $pago = $pedido['info_pedido']['forma_pago'];
                                        $iconPago = match($pago) {
                                            'efectivo' => 'cash',
                                            'qr' => 'qr-code',
                                            'mercado_pago' => 'credit-card',
                                            default => 'currency-exchange'
                                        };
                                        ?>
                                        <i class="bi bi-<?= $iconPago ?>"></i>
                                        <?= ucfirst(esc($pago)) ?>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm estado-pedido bg-dark text-light" 
                                                data-pedido-id="<?= $pedido['id'] ?>" 
                                                style="width: 140px;">
                                            <option value="pendiente" <?= $pedido['estado'] === 'pendiente' ? 'selected' : '' ?>>
                                                🟡 Pendiente
                                            </option>
                                            <option value="en_proceso" <?= $pedido['estado'] === 'en_proceso' ? 'selected' : '' ?>>
                                                🔵 En Proceso
                                            </option>
                                            <option value="completado" <?= $pedido['estado'] === 'completado' ? 'selected' : '' ?>>
                                                🟢 Completado
                                            </option>
                                            <option value="cancelado" <?= $pedido['estado'] === 'cancelado' ? 'selected' : '' ?>>
                                                🔴 Cancelado
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <small><?= date('d/m/Y', strtotime($pedido['created_at'] ?? 'now')) ?></small>
                                        <br>
                                        <small class="text-muted"><?= date('H:i', strtotime($pedido['created_at'] ?? 'now')) ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= site_url('admin/pedidos/ver/' . $pedido['id']) ?>" 
                                               class="btn btn-info" 
                                               title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= site_url('admin/pedidos/imprimir/' . $pedido['id']) ?>" 
                                               class="btn btn-secondary" 
                                               title="Imprimir ticket"
                                               target="_blank">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger btn-eliminar" 
                                                    data-pedido-id="<?= $pedido['id'] ?>"
                                                    title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
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

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-warning">
                <h5 class="modal-title text-warning">Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar este pedido? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer border-warning">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminar" method="post" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cambiar estado de pedido
    document.querySelectorAll('.estado-pedido').forEach(select => {
        select.addEventListener('change', function() {
            const pedidoId = this.dataset.pedidoId;
            const nuevoEstado = this.value;
            
            fetch('<?= site_url('admin/pedidos/cambiarEstado') ?>/' + pedidoId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'estado=' + nuevoEstado
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar visualmente
                    const row = this.closest('tr');
                    row.dataset.estado = nuevoEstado;
                    
                    // Mostrar notificación
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                    alert.style.zIndex = '9999';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);
                    
                    setTimeout(() => alert.remove(), 3000);
                }
            });
        });
    });

    // Filtrar por estado
    document.querySelectorAll('[data-filter]').forEach(btn => {
        btn.addEventListener('click', function() {
            const filtro = this.dataset.filter;
            
            // Actualizar botones activos
            document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filtrar filas
            document.querySelectorAll('#tablaPedidos tbody tr').forEach(row => {
                if (filtro === 'todos' || row.dataset.estado === filtro) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // Eliminar pedido
    const modalEliminar = new bootstrap.Modal(document.getElementById('modalEliminar'));
    
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const pedidoId = this.dataset.pedidoId;
            const form = document.getElementById('formEliminar');
            form.action = '<?= site_url('admin/pedidos/eliminar') ?>/' + pedidoId;
            modalEliminar.show();
        });
    });
});
</script>

<style>
.table-dark th {
    background-color: #212529;
    border-color: #454d55;
    position: sticky;
    top: 0;
    z-index: 10;
}

.form-select-sm {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>

<?= $this->endSection() ?>