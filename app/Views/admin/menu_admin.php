<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color: #000; min-height: 80vh;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-5">
      <h1 style="color: #D4B68A;">Gestión del Menú</h1>
      <a href="<?= site_url('admin/menu/crear') ?>" class="btn btn-warning">
        <i class="bi bi-plus-circle"></i> Agregar Plato
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

    <div class="row g-4">
      <?php if (empty($platos)): ?>
        <div class="col-12">
          <div class="alert alert-info text-center">
            <p class="mb-0">No hay platos registrados. Comienza agregando uno nuevo.</p>
          </div>
        </div>
      <?php else: ?>
        <?php foreach ($platos as $plato): ?>
          <div class="col-md-6 col-lg-4">
            <div class="card bg-dark text-light h-100">
              <?php if ($plato['imagen']): ?>
                <img src="<?= base_url('assets/images/platos/' . $plato['imagen']) ?>" 
                     class="card-img-top" 
                     alt="<?= esc($plato['nombre']) ?>"
                     style="height: 200px; object-fit: cover;">
              <?php endif; ?>
              
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                  <h5 class="card-title" style="color: #D4B68A;"><?= esc($plato['nombre']) ?></h5>
                  <span class="badge bg-<?= $plato['disponible'] ? 'success' : 'danger' ?>">
                    <?= $plato['disponible'] ? 'Disponible' : 'No disponible' ?>
                  </span>
                </div>
                
                <?php if (!empty($plato['categoria'])): ?>
                  <p class="text-muted small mb-2">
                    <i class="bi bi-tag"></i> <?= esc($plato['categoria']) ?>
                  </p>
                <?php endif; ?>
                
                <p class="card-text small"><?= esc($plato['descripcion']) ?></p>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <p class="card-text mb-0"><strong>Precio:</strong> $<?= number_format($plato['precio'], 2) ?></p>
                  <p class="card-text mb-0"><strong>Stock:</strong> <?= $plato['stock'] ?? 0 ?></p>
                </div>
                
                <div class="d-flex gap-2">
                  <a href="<?= site_url('admin/menu/editar/' . $plato['id']) ?>" 
                     class="btn btn-sm btn-outline-warning flex-fill">
                    <i class="bi bi-pencil"></i> Editar
                  </a>
                  <button class="btn btn-sm btn-outline-danger" 
                          onclick="confirmarEliminar(<?= $plato['id'] ?>)">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
function confirmarEliminar(id) {
  if (confirm('¿Estás seguro de eliminar este plato? Esta acción no se puede deshacer.')) {
    window.location.href = '<?= site_url('admin/menu/eliminar/') ?>' + id;
  }
}
</script>

<?= $this->endSection() ?>