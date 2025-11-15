<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
  .admin-form-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 80vh;
    padding: 2rem 0;
  }

  .admin-form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 2rem;
  }

  .admin-form-header {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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
    background: white;
    border: 2px solid #667eea;
    color: #667eea;
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
  }

  .admin-btn-secondary:hover {
    background: #667eea;
    color: white;
  }

  .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
  }
</style>

<section class="admin-form-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="admin-form-header">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 class="h3 mb-1" style="color: #495057; font-weight: 700;">Crear Nuevo Plato</h1>
              <p class="text-muted mb-0">Completa el formulario para agregar un nuevo plato al menú</p>
            </div>
            <a href="<?= site_url('admin/menu') ?>" class="admin-btn-secondary">
              <i class="bi bi-arrow-left"></i> Volver
            </a>
          </div>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Errores encontrados:</strong>
            <ul class="mb-0 mt-2">
              <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
              <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <div class="admin-form-card">
          <form action="<?= site_url('admin/menu/guardar') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label class="form-label">Nombre *</label>
              <input type="text" name="nombre" class="form-control" value="<?= old('nombre') ?>" placeholder="Ej: Pizza Napolitana" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Categoría</label>
              <input type="text" name="categoria" class="form-control" value="<?= old('categoria') ?>" placeholder="Ej: Pizzas, Empanadas, Bebidas, etc.">
              <small class="text-muted">Si no existe, se creará automáticamente</small>
            </div>

            <div class="mb-3">
              <label class="form-label">Descripción</label>
              <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe los ingredientes y características del plato..."><?= old('descripcion') ?></textarea>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Precio *</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" step="0.01" name="precio" class="form-control" value="<?= old('precio') ?>" placeholder="0.00" required>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Stock *</label>
                <input type="number" name="stock" class="form-control" value="<?= old('stock', 0) ?>" min="0" placeholder="0" required>
                <small class="text-muted">Stock disponible en unidades</small>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Imagen *</label>
              <input type="file" name="imagen" class="form-control" accept="image/*" required>
              <small class="text-muted">Formatos aceptados: JPG, PNG, GIF. Sin límite de tamaño</small>
            </div>

            <div class="form-check form-switch mb-4">
              <input type="checkbox" name="disponible" id="disponible" class="form-check-input" checked>
              <label for="disponible" class="form-check-label">Plato disponible para la venta</label>
            </div>

            <div class="d-flex gap-2">
              <button class="admin-btn-primary flex-fill" type="submit">
                <i class="bi bi-check-circle"></i> Guardar Plato
              </button>
              <a href="<?= site_url('admin/menu') ?>" class="admin-btn-secondary">
                <i class="bi bi-x-circle"></i> Cancelar
              </a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
