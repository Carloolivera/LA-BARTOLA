<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color: #000; min-height: 80vh;">
  <div class="container">
    <h1 class="text-center mb-5" style="color: #D4B68A;">Gestión de Pedidos</h1>

    <div class="card bg-dark text-light">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-dark table-hover">
            <thead>
              <tr style="border-color: #d4af37;">
                <th>ID</th>
                <th>Usuario</th>
                <th>Plato</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($pedidos)): ?>
                <tr>
                  <td colspan="9" class="text-center text-muted">No hay pedidos registrados</td>
                </tr>
              <?php else: ?>
                <?php foreach ($pedidos as $pedido): ?>
                  <tr>
                    <td><?= $pedido['id'] ?></td>
                    <td><?= $pedido['username'] ?></td>
                    <td><?= $pedido['plato_nombre'] ?></td>
                    <td><?= $pedido['cantidad'] ?></td>
                    <td>$<?= number_format($pedido['precio'], 2) ?></td>
                    <td>$<?= number_format($pedido['precio'] * $pedido['cantidad'], 2) ?></td>
                    <td>
                      <span class="badge bg-<?= $pedido['estado'] == 'pendiente' ? 'warning' : ($pedido['estado'] == 'en_proceso' ? 'info' : 'success') ?>">
                        <?= ucfirst($pedido['estado']) ?>
                      </span>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></td>
                    <td>
                      <select class="form-select form-select-sm bg-dark text-light" 
                              onchange="cambiarEstado(<?= $pedido['id'] ?>, this.value)"
                              style="border-color: #d4af37;">
                        <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="en_proceso" <?= $pedido['estado'] == 'en_proceso' ? 'selected' : '' ?>>En Proceso</option>
                        <option value="completado" <?= $pedido['estado'] == 'completado' ? 'selected' : '' ?>>Completado</option>
                        <option value="cancelado" <?= $pedido['estado'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                      </select>
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

<script>
function cambiarEstado(id, estado) {
  fetch('<?= site_url('admin/actualizar-estado-pedido') ?>', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `id=${id}&estado=${estado}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Estado actualizado correctamente');
      location.reload();
    }
  });
}
</script>

<?= $this->endSection() ?>