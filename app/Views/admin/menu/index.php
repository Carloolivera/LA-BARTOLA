<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
  .admin-menu-section {
    background-color: #000;
    min-height: 80vh;
    padding: 2rem 0;
  }

  .admin-menu-card {
    background-color: #1a1a1a;
    border: 1px solid #D4B68A;
    border-radius: 12px;
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
  }

  .admin-menu-card:hover {
    box-shadow: 0 4px 16px rgba(212, 182, 138, 0.3);
    transform: translateY(-2px);
  }

  .admin-menu-card img {
    height: 180px;
    object-fit: cover;
  }

  .admin-category-badge {
    background: #D4B68A;
    color: #000;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
  }

  .admin-btn-primary {
    background-color: #D4B68A;
    border: none;
    color: #000;
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 600;
    transition: transform 0.2s;
  }

  .admin-btn-primary:hover {
    transform: scale(1.02);
    color: #000;
    background-color: #c9a770;
  }

  .admin-btn-secondary {
    background: transparent;
    border: 2px solid #D4B68A;
    color: #D4B68A;
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 500;
    margin-left: 10px;
  }

  .admin-btn-secondary:hover {
    background: #D4B68A;
    color: #000;
  }

  .filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
  }

  .filter-tab {
    padding: 8px 16px;
    border-radius: 20px;
    background: #1a1a1a;
    border: 2px solid #D4B68A;
    color: #D4B68A;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 500;
  }

  .filter-tab.active {
    background: #D4B68A;
    border-color: #D4B68A;
    color: #000;
  }

  .filter-tab:hover {
    background: rgba(212, 182, 138, 0.2);
  }

  .card-body {
    background-color: #1a1a1a;
  }
</style>

<section class="admin-menu-section">
  <div class="container">
    <div class="mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="mb-3 mb-md-0">
          <h1 class="h3 mb-1" style="color: #D4B68A; font-weight: 700;">Gestión del Menú</h1>
          <p class="text-light mb-0">Administra los platos y categorías de tu restaurante</p>
        </div>
        <div>
          <a href="<?= site_url('admin/categorias') ?>" class="admin-btn-secondary">
            <i class="bi bi-tags"></i> Gestionar Categorías
          </a>
          <a href="<?= site_url('admin/menu/crear') ?>" class="admin-btn-primary">
            <i class="bi bi-plus-circle"></i> Agregar Plato
          </a>
        </div>
      </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Filtros por categoría -->
    <div class="filter-tabs">
      <div class="filter-tab active" data-category="todas">Todas</div>
      <?php if (!empty($categorias)): ?>
        <?php foreach ($categorias as $cat): ?>
          <div class="filter-tab" data-category="<?= esc($cat['nombre']) ?>"><?= esc($cat['nombre']) ?></div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="row g-4">
      <?php if (empty($platos)): ?>
        <div class="col-12">
          <div class="alert alert-info text-center">No hay platos registrados.</div>
        </div>
      <?php else: ?>
        <?php foreach ($platos as $plato): ?>
          <div class="col-md-6 col-lg-4 plato-item" data-category="<?= esc($plato['categoria']) ?>">
            <div class="admin-menu-card">
              <?php if (!empty($plato['imagen'])): ?>
                <img src="<?= base_url('assets/images/platos/' . $plato['imagen']) ?>" class="card-img-top" alt="<?= esc($plato['nombre']) ?>">
              <?php else: ?>
                <div style="height: 180px; background: #2a2a2a; display: flex; align-items: center; justify-content: center;">
                  <i class="bi bi-image" style="font-size: 3rem; color: #D4B68A;"></i>
                </div>
              <?php endif; ?>
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h5 class="card-title mb-0" style="color: #D4B68A; font-weight: 600;"><?= esc($plato['nombre']) ?></h5>
                  <span class="badge bg-<?= $plato['disponible'] ? 'success' : 'secondary' ?>">
                    <?= $plato['disponible'] ? 'Disponible' : 'No disponible' ?>
                  </span>
                </div>

                <span class="admin-category-badge mb-2 d-inline-block"><?= esc($plato['categoria']) ?></span>
                <p class="card-text small text-light mb-3"><?= esc($plato['descripcion']) ?></p>

                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div><strong style="color: #D4B68A; font-size: 1.2rem;">$<?= number_format($plato['precio'], 0, ',', '.') ?></strong></div>
                  <div class="small text-light">
                    Stock: <strong><?= $plato['stock_ilimitado'] ? '∞' : esc($plato['stock'] ?? 0) ?></strong>
                  </div>
                </div>

                <div class="d-flex gap-2">
                  <a href="<?= site_url('admin/menu/editar/' . $plato['id']) ?>" class="btn btn-sm btn-outline-warning flex-fill">
                    <i class="bi bi-pencil"></i> Editar
                  </a>
                  <a href="<?= site_url('admin/menu/eliminar/' . $plato['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este plato?')">
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

<script>
  // Filtro por categorías
  document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function() {
      // Remover active de todos
      document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
      // Agregar active al clickeado
      this.classList.add('active');

      const category = this.getAttribute('data-category');

      // Mostrar/ocultar platos
      document.querySelectorAll('.plato-item').forEach(item => {
        if (category === 'todas' || item.getAttribute('data-category') === category) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });
</script>

<?= $this->endSection() ?>
