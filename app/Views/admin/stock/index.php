<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color:#000; min-height:80vh;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 text-warning">Gestión de Stock</h1>
      <a href="<?= site_url('admin/menu') ?>" class="btn btn-outline-warning">
        <i class="bi bi-box-seam"></i> Gestión Menú
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

    <div class="table-responsive">
      <table class="table table-dark table-striped table-hover">
        <thead>
          <tr>
            <th>Imagen</th>
            <th>Plato</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th style="width: 150px;">Stock Actual</th>
            <th>Estado</th>
            <th style="width: 250px;">Ajuste Rápido</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($platos)): ?>
            <tr>
              <td colspan="8" class="text-center py-4">No hay platos registrados.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($platos as $plato): ?>
              <tr id="plato-<?= $plato['id'] ?>">
                <td>
                  <?php if (!empty($plato['imagen'])): ?>
                    <img src="<?= base_url('assets/images/platos/' . $plato['imagen']) ?>"
                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                  <?php else: ?>
                    <div class="bg-secondary d-flex align-items-center justify-content-center"
                         style="width: 60px; height: 60px; border-radius: 8px;">
                      <i class="bi bi-image text-muted"></i>
                    </div>
                  <?php endif; ?>
                </td>
                <td>
                  <strong class="text-warning"><?= esc($plato['nombre']) ?></strong>
                </td>
                <td>
                  <span class="badge bg-secondary"><?= esc($plato['categoria']) ?></span>
                </td>
                <td>
                  <strong>$<?= number_format($plato['precio'], 2) ?></strong>
                </td>
                <td>
                  <?php if ($plato['stock_ilimitado'] == 1): ?>
                    <span class="badge bg-success" style="font-size: 1rem;">
                      <i class="bi bi-infinity"></i> Ilimitado
                    </span>
                  <?php else: ?>
                    <span class="badge <?= $plato['stock'] <= 0 ? 'bg-danger' : ($plato['stock'] <= 5 ? 'bg-warning text-dark' : 'bg-info') ?>"
                          style="font-size: 1rem;"
                          id="stock-badge-<?= $plato['id'] ?>">
                      <?= $plato['stock'] ?> unidades
                    </span>
                  <?php endif; ?>
                </td>
                <td>
                  <span class="badge <?= $plato['disponible'] ? 'bg-success' : 'bg-secondary' ?>" id="disponible-badge-<?= $plato['id'] ?>">
                    <?= $plato['disponible'] ? 'Disponible' : 'No disponible' ?>
                  </span>
                </td>
                <td>
                  <?php if ($plato['stock_ilimitado'] == 0): ?>
                    <div class="btn-group btn-group-sm" role="group">
                      <button type="button"
                              class="btn btn-danger btn-ajuste"
                              data-id="<?= $plato['id'] ?>"
                              data-accion="restar"
                              data-cantidad="1">
                        <i class="bi bi-dash-lg"></i> 1
                      </button>
                      <button type="button"
                              class="btn btn-success btn-ajuste"
                              data-id="<?= $plato['id'] ?>"
                              data-accion="sumar"
                              data-cantidad="1">
                        <i class="bi bi-plus-lg"></i> 1
                      </button>
                      <button type="button"
                              class="btn btn-success btn-ajuste"
                              data-id="<?= $plato['id'] ?>"
                              data-accion="sumar"
                              data-cantidad="5">
                        <i class="bi bi-plus-lg"></i> 5
                      </button>
                    </div>
                  <?php else: ?>
                    <span class="text-muted small">Stock ilimitado</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="<?= site_url('admin/stock/editar/' . $plato['id']) ?>"
                     class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil"></i> Editar
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Leyenda -->
    <div class="card bg-dark border-warning mt-4">
      <div class="card-body">
        <h6 class="text-warning mb-3">Leyenda de colores:</h6>
        <div class="row g-3">
          <div class="col-md-3">
            <span class="badge bg-danger">0 unidades</span> - Sin stock
          </div>
          <div class="col-md-3">
            <span class="badge bg-warning text-dark">1-5 unidades</span> - Stock bajo
          </div>
          <div class="col-md-3">
            <span class="badge bg-info">6+ unidades</span> - Stock normal
          </div>
          <div class="col-md-3">
            <span class="badge bg-success"><i class="bi bi-infinity"></i> Ilimitado</span> - Stock ilimitado
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const botonesAjuste = document.querySelectorAll('.btn-ajuste');

  botonesAjuste.forEach(boton => {
    boton.addEventListener('click', function() {
      const platoId = this.dataset.id;
      const accion = this.dataset.accion;
      const cantidad = parseInt(this.dataset.cantidad);

      const formData = new FormData();
      formData.append('plato_id', platoId);
      formData.append('accion', accion);
      formData.append('cantidad', cantidad);

      fetch('<?= site_url('admin/stock/ajusteRapido') ?>', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Actualizar badge de stock
          const stockBadge = document.getElementById('stock-badge-' + platoId);
          if (stockBadge) {
            stockBadge.textContent = data.nuevo_stock + ' unidades';

            // Actualizar color del badge según el stock
            stockBadge.className = 'badge';
            if (data.nuevo_stock <= 0) {
              stockBadge.classList.add('bg-danger');
            } else if (data.nuevo_stock <= 5) {
              stockBadge.classList.add('bg-warning', 'text-dark');
            } else {
              stockBadge.classList.add('bg-info');
            }
            stockBadge.style.fontSize = '1rem';
          }

          // Actualizar badge de disponibilidad
          const disponibleBadge = document.getElementById('disponible-badge-' + platoId);
          if (disponibleBadge) {
            disponibleBadge.textContent = data.disponible ? 'Disponible' : 'No disponible';
            disponibleBadge.className = 'badge ' + (data.disponible ? 'bg-success' : 'bg-secondary');
          }

          // Mostrar mensaje de éxito
          mostrarAlerta('success', data.message);
        } else {
          mostrarAlerta('danger', data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        mostrarAlerta('danger', 'Error al actualizar el stock');
      });
    });
  });

  function mostrarAlerta(tipo, mensaje) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${tipo} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    alert.style.zIndex = '9999';
    alert.style.minWidth = '300px';
    alert.innerHTML = `
      ${mensaje}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alert);

    setTimeout(() => alert.remove(), 3000);
  }
});
</script>

<?= $this->endSection() ?>
