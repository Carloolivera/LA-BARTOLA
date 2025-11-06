<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color:#000; min-height:80vh;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 text-warning">Gestión del Menú</h1>
      <a href="<?= site_url('admin/menu/crear') ?>" class="btn btn-warning">
        <i class="bi bi-plus-circle"></i> Agregar Plato
      </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="row g-4">
      <?php if (empty($platos)): ?>
        <div class="col-12">
          <div class="alert alert-info text-center">No hay platos registrados.</div>
        </div>
      <?php else: ?>
        <?php foreach ($platos as $plato): ?>
          <div class="col-md-6 col-lg-4">
            <div class="card bg-dark text-light h-100">
              <?php if (! empty($plato['imagen'])): ?>
                <img src="<?= base_url('assets/images/platos/' . $plato['imagen']) ?>" class="card-img-top" style="height:200px; object-fit:cover;">
              <?php endif; ?>
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <h5 class="card-title text-warning"><?= esc($plato['nombre']) ?></h5>
                  <span class="badge bg-<?= $plato['disponible'] ? 'success' : 'secondary' ?>">
                    <?= $plato['disponible'] ? 'Disponible' : 'No disponible' ?>
                  </span>
                </div>

                <p class="small text-muted mb-2"><?= esc($plato['categoria']) ?></p>
                <p class="card-text small mb-2"><?= esc($plato['descripcion']) ?></p>

                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div><strong>$<?= number_format($plato['precio'], 2) ?></strong></div>
                  <div class="small">Stock: <?= esc($plato['stock'] ?? 0) ?></div>
                </div>

                <div class="d-flex gap-2">
                  <a href="<?= site_url('admin/menu/editar/' . $plato['id']) ?>" class="btn btn-sm btn-outline-warning flex-fill">
                    <i class="bi bi-pencil"></i> Editar
                  </a>
                  <a href="<?= site_url('admin/menu/eliminar/' . $plato['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Eliminar?')">
                    <i class="bi bi-trash"></i> Eliminar
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
