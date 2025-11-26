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
  }

  @media (max-width: 576px) {
    .admin-btn-primary,
    .admin-btn-secondary {
      width: 100%;
      margin-bottom: 0.5rem;
      text-align: center;
    }
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
      <div class="d-flex justify-content-between align-items-start align-items-md-center flex-wrap gap-3">
        <div class="mb-2 mb-md-0">
          <h1 class="h3 mb-1" style="color: #D4B68A; font-weight: 700;">Gestión del Menú</h1>
          <p class="text-light mb-0">Administra los platos y categorías de tu restaurante</p>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
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
                  <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmarEliminarPlato(<?= $plato['id'] ?>, '<?= addslashes(esc($plato['nombre'])) ?>')">
                    <i class="bi bi-trash"></i> Eliminar
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

<!-- Modal de confirmación para eliminar plato -->
<div class="modal fade" id="modalEliminarPlato" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background: #1a1a1a; border: 2px solid #D4B68A;">
      <div class="modal-header" style="border-bottom: 1px solid #D4B68A;">
        <h5 class="modal-title" style="color: #D4B68A;">
          <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="color: #fff;">
        <p>¿Estás seguro de que deseas eliminar el plato <strong id="nombrePlatoEliminar" style="color: #D4B68A;"></strong>?</p>
        <p class="text-danger mb-0"><small>Esta acción no se puede deshacer.</small></p>
      </div>
      <div class="modal-footer" style="border-top: 1px solid #D4B68A;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">
          <i class="bi bi-trash"></i> Eliminar Plato
        </a>
      </div>
    </div>
  </div>
</div>

<script>
  // Guardar posición del scroll antes de eliminar
  let scrollPosition = 0;

  // Función para confirmar eliminación
  function confirmarEliminarPlato(id, nombre) {
    // Guardar posición actual del scroll
    scrollPosition = window.scrollY || window.pageYOffset;

    // Actualizar el nombre del plato en el modal
    document.getElementById('nombrePlatoEliminar').textContent = nombre;

    // Actualizar el enlace de eliminación
    const btnEliminar = document.getElementById('btnConfirmarEliminar');
    btnEliminar.href = '<?= site_url("admin/menu/eliminar/") ?>/' + id;

    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('modalEliminarPlato'));
    modal.show();
  }

  // Restaurar posición del scroll después de cerrar el modal
  document.getElementById('modalEliminarPlato').addEventListener('hidden.bs.modal', function () {
    if (scrollPosition > 0) {
      window.scrollTo({
        top: scrollPosition,
        behavior: 'instant'
      });
    }
  });

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

  // Restaurar scroll después de redirigir
  window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const scroll = urlParams.get('scroll');
    if (scroll) {
      window.scrollTo({
        top: parseInt(scroll),
        behavior: 'instant'
      });
      // Limpiar el parámetro de la URL
      window.history.replaceState({}, document.title, window.location.pathname);
    }
  });
</script>

<!-- Footer Admin -->
<footer class="text-center text-light py-4 mt-5" style="background-color: #1a1a1a; border-top: 2px solid #D4B68A;">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
            <a href="https://docs.google.com/spreadsheets" target="_blank" class="text-decoration-none" title="Google Sheets">
                <i class="bi bi-file-earmark-excel" style="font-size: 1.8rem; color: #1D6F42;"></i>
            </a>
            <a href="https://docs.google.com/document" target="_blank" class="text-decoration-none" title="Google Docs">
                <i class="bi bi-file-earmark-word" style="font-size: 1.8rem; color: #2B579A;"></i>
            </a>
            <a href="https://hpanel.hostinger.com" target="_blank" class="text-decoration-none" title="Hostinger Panel">
                <i class="bi bi-hdd-rack" style="font-size: 1.8rem; color: #673DE6;"></i>
            </a>
            <a href="https://mail.google.com" target="_blank" class="text-decoration-none" title="Gmail">
                <i class="bi bi-envelope" style="font-size: 1.8rem; color: #D93025;"></i>
            </a>
            <a href="https://www.instagram.com/aido_agenciaweb/" target="_blank" class="text-decoration-none" title="Soporte Técnico">
                <i class="bi bi-life-preserver" style="font-size: 1.8rem; color: #D4B68A;"></i>
            </a>
        </div>
        <p class="mb-0 mt-3 small" style="color: #999;">© 2025 La Bartola | Panel Administrativo</p>
    </div>
</footer>

<?= $this->endSection() ?>
