<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color: #000; min-height: 80vh;">
  <div class="container">
    <h1 class="text-center mb-5" style="color: #D4B68A;">Gestión de Pedidos</h1>

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

    <div class="card bg-dark text-light">


      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-dark table-hover">
            <thead>
              <tr style="border-color: #d4af37;">
                <th>ID</th>
                <th>Cliente</th>
                <th>Plato</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($pedidos)): ?>
                <tr>
                  <td colspan="8" class="text-center text-muted">No hay pedidos registrados</td>
                </tr>
              <?php else: ?>
                <?php foreach ($pedidos as $pedido): ?>
                  <tr>
                    <td>#<?= $pedido['id'] ?></td>
                    <td><?= $pedido['username'] ?? 'Invitado' ?></td>
                    <td><?= $pedido['plato_nombre'] ?></td>
                    

<td><?= $pedido['cantidad'] ?></td>
                    <td>$<?= number_format($pedido['total'], 2) ?></td>
                    <td>
                      <select class="form-select form-select-sm bg-dark text-light estado-select" 
                              data-id="<?= $pedido['id'] ?>"
                              style="border-color: #d4af37; width: auto;">
                        <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="en_preparacion" <?= $pedido['estado'] == 'en_preparacion' ? 'selected' : '' ?>>En Preparación</option>
                        <option value="listo" <?= $pedido['estado'] == 'listo' ? 'selected' : '' ?>>Listo</option>
                        <option value="entregado" <?= $pedido['estado'] == 'entregado' ? 'selected' : '' ?>>Entregado</option>
                        <option value="cancelado" <?= $pedido['estado'] == 'cancelado' ? 'selected' : '' ?>>Cancelado

</option>
                      </select>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></td>
                    <td>
                      <a href="/admin/pedidos/ver/<?= $pedido['id'] ?>" class="btn btn-sm btn-info" title="Ver">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="/admin/pedidos/editar/<?= $pedido['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <form action="/admin/pedidos/eliminar/<?= $pedido['id'] ?>" method="post" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este pedido?')">
                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
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
document.querySelectorAll('.estado-select').forEach(select => {
  select.addEventListener('change', function() {
    const pedidoId = this.dataset.id;
    const nuevoEstado = this.value;
    
    fetch(`/admin/pedidos/cambiarEstado/${pedidoId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `estado=${nuevoEstado}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        alert.style.zIndex = '9999';
        alert.innerHTML = `
          ${data.message}
          <button type="button" class="btn-close" data-bs-dismiss="

alert"></button>
        `;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 3000);
      }
    });
  });
});
</script>

<?= $this->endSection() ?>