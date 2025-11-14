<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
  .admin-menu-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 80vh;
    padding: 2rem 0;
  }

  .admin-menu-header {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }

  .admin-menu-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
  }

  .admin-menu-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    transform: translateY(-2px);
  }

  .admin-menu-card img {
    height: 180px;
    object-fit: cover;
  }

  .admin-category-badge {
    background: #6c757d;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
  }

  .admin-btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 600;
    transition: transform 0.2s;
  }

  .admin-btn-primary:hover {
    transform: scale(1.02);
    color: white;
  }

  .admin-btn-secondary {
    background: #6c757d;
    border: none;
    color: white;
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 500;
    margin-left: 10px;
  }

  .admin-btn-secondary:hover {
    background: #5a6268;
    color: white;
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
    background: white;
    border: 2px solid #e0e0e0;
    color: #495057;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 500;
  }

  .filter-tab.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
  }

  .filter-tab:hover {
    border-color: #667eea;
  }
</style>

<section class="admin-menu-section">
  <div class="container">
    <div class="admin-menu-header">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <h1 class="h3 mb-1" style="color: #495057; font-weight: 700;">Gestión del Menú</h1>
          <p class="text-muted mb-0">Administra los platos y categorías de tu restaurante</p>
        </div>
        <div>
          <button type="button" class="admin-btn-secondary" data-bs-toggle="modal" data-bs-target="#modalCategorias">
            <i class="bi bi-tags"></i> Gestionar Categorías
          </button>
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
      <div class="filter-tab" data-category="Bebidas">Bebidas</div>
      <div class="filter-tab" data-category="Empanadas">Empanadas</div>
      <div class="filter-tab" data-category="Pizzas">Pizzas</div>
      <div class="filter-tab" data-category="Tartas">Tartas</div>
      <div class="filter-tab" data-category="Postres">Postres</div>
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
                <div style="height: 180px; background: #e9ecef; display: flex; align-items: center; justify-content: center;">
                  <i class="bi bi-image" style="font-size: 3rem; color: #6c757d;"></i>
                </div>
              <?php endif; ?>
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h5 class="card-title mb-0" style="color: #495057; font-weight: 600;"><?= esc($plato['nombre']) ?></h5>
                  <span class="badge bg-<?= $plato['disponible'] ? 'success' : 'secondary' ?>">
                    <?= $plato['disponible'] ? 'Disponible' : 'No disponible' ?>
                  </span>
                </div>

                <span class="admin-category-badge mb-2 d-inline-block"><?= esc($plato['categoria']) ?></span>
                <p class="card-text small text-muted mb-3"><?= esc($plato['descripcion']) ?></p>

                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div><strong style="color: #667eea; font-size: 1.2rem;">$<?= number_format($plato['precio'], 0, ',', '.') ?></strong></div>
                  <div class="small text-muted">
                    Stock: <strong><?= $plato['stock_ilimitado'] ? '∞' : esc($plato['stock'] ?? 0) ?></strong>
                  </div>
                </div>

                <div class="d-flex gap-2">
                  <a href="<?= site_url('admin/menu/editar/' . $plato['id']) ?>" class="btn btn-sm btn-outline-primary flex-fill">
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

<!-- Modal Gestionar Categorías -->
<div class="modal fade" id="modalCategorias" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 16px;">
      <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px 16px 0 0;">
        <h5 class="modal-title">Gestionar Categorías</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted small">Categorías actuales del menú:</p>
        <ul class="list-group mb-3">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Bebidas
            <span class="badge bg-secondary rounded-pill">Categoría predefinida</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Empanadas
            <span class="badge bg-secondary rounded-pill">Categoría predefinida</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Pizzas
            <span class="badge bg-secondary rounded-pill">Categoría predefinida</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Tartas
            <span class="badge bg-secondary rounded-pill">Categoría predefinida</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Postres
            <span class="badge bg-secondary rounded-pill">Categoría predefinida</span>
          </li>
        </ul>
        <div class="alert alert-info">
          <i class="bi bi-info-circle me-2"></i>
          <strong>Nota:</strong> Las categorías se crean automáticamente al agregar platos. Simplemente ingresa el nombre de la categoría al crear o editar un plato.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

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
