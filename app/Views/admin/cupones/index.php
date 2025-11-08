<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning">
            <i class="bi bi-ticket-perforated"></i> Gestión de Cupones
        </h2>
        <a href="<?= site_url('admin/cupones/crear') ?>" class="btn btn-warning">
            <i class="bi bi-plus-circle"></i> Nuevo Cupón
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($cupones)): ?>
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> No hay cupones creados.
            <a href="<?= site_url('admin/cupones/crear') ?>" class="alert-link">Crear el primero</a>
        </div>
    <?php else: ?>
        <div class="card bg-dark border-warning">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Usos</th>
                                <th>Monto Mín.</th>
                                <th>Vigencia</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cupones as $cupon): ?>
                                <tr>
                                    <td>
                                        <strong class="text-warning"><?= esc($cupon['codigo']) ?></strong>
                                        <?php if ($cupon['descripcion']): ?>
                                            <br><small class="text-muted"><?= esc($cupon['descripcion']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($cupon['tipo'] === 'porcentaje'): ?>
                                            <span class="badge bg-info">Porcentaje</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Monto Fijo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($cupon['tipo'] === 'porcentaje'): ?>
                                            <strong><?= $cupon['valor'] ?>%</strong>
                                        <?php else: ?>
                                            <strong>$<?= number_format($cupon['valor'], 2) ?></strong>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $cupon['usos_actuales'] ?> /
                                        <?= $cupon['usos_maximos'] ? $cupon['usos_maximos'] : '∞' ?>
                                        <br>
                                        <small class="text-muted">
                                            <?= $cupon['usos_por_usuario'] ?> por usuario
                                        </small>
                                    </td>
                                    <td>
                                        <?= $cupon['monto_minimo'] ? '$' . number_format($cupon['monto_minimo'], 2) : '-' ?>
                                    </td>
                                    <td>
                                        <?php
                                        $now = date('Y-m-d H:i:s');
                                        $inicio = $cupon['fecha_inicio'];
                                        $fin = $cupon['fecha_expiracion'];
                                        ?>
                                        <?php if ($inicio && $now < $inicio): ?>
                                            <small class="text-info">
                                                <i class="bi bi-clock"></i> Desde: <?= date('d/m/Y', strtotime($inicio)) ?>
                                            </small>
                                        <?php elseif ($fin && $now > $fin): ?>
                                            <small class="text-danger">
                                                <i class="bi bi-x-circle"></i> Expiró: <?= date('d/m/Y', strtotime($fin)) ?>
                                            </small>
                                        <?php elseif ($fin): ?>
                                            <small class="text-success">
                                                <i class="bi bi-check-circle"></i> Hasta: <?= date('d/m/Y', strtotime($fin)) ?>
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted">Sin límite</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   id="estado_<?= $cupon['id'] ?>"
                                                   <?= $cupon['activo'] ? 'checked' : '' ?>
                                                   onchange="toggleEstado(<?= $cupon['id'] ?>)">
                                            <label class="form-check-label" for="estado_<?= $cupon['id'] ?>">
                                                <?= $cupon['activo'] ? 'Activo' : 'Inactivo' ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= site_url('admin/cupones/editar/' . $cupon['id']) ?>"
                                               class="btn btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger"
                                                    onclick="eliminarCupon(<?= $cupon['id'] ?>, '<?= esc($cupon['codigo']) ?>')"
                                                    title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function toggleEstado(id) {
    fetch(`<?= site_url('admin/cupones/toggleEstado/') ?>${id}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error al cambiar el estado');
        }
    });
}

function eliminarCupon(id, codigo) {
    if (confirm(`¿Estás seguro de eliminar el cupón "${codigo}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/cupones/eliminar/') ?>${id}`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?= $this->endSection() ?>
