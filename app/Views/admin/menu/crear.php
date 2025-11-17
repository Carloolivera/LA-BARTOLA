<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
  .admin-form-section {
    background-color: #000;
    min-height: 80vh;
    padding: 2rem 0;
  }

  .admin-form-card {
    background-color: #1a1a1a;
    border: 1px solid #D4B68A;
    border-radius: 12px;
    padding: 2rem;
  }

  .form-control:focus, .form-select:focus {
    border-color: #D4B68A;
    box-shadow: 0 0 0 0.2rem rgba(212, 182, 138, 0.25);
    background-color: #2a2a2a;
    color: #f5f5dc;
  }

  .form-control, .form-select {
    background-color: #2a2a2a;
    border-color: #D4B68A;
    color: #f5f5dc;
  }

  .form-label {
    font-weight: 500;
    color: #D4B68A;
    margin-bottom: 0.5rem;
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

  .admin-btn-secondary:hover {
    background: #D4B68A;
    color: #000;
  }
</style>

<section class="admin-form-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 class="h3 mb-1" style="color: #D4B68A; font-weight: 700;">Crear Nuevo Plato</h1>
              <p class="text-light mb-0">Completa el formulario para agregar un nuevo plato al menú</p>
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
              <label class="form-label">Categoría *</label>
              <select name="categoria" class="form-select" required>
                <option value="">Selecciona una categoría</option>
                <?php if (!empty($categorias)): ?>
                  <?php foreach ($categorias as $cat): ?>
                    <option value="<?= esc($cat['nombre']) ?>" <?= old('categoria') === $cat['nombre'] ? 'selected' : '' ?>>
                      <?= esc($cat['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <small class="text-light">
                <a href="<?= site_url('admin/categorias') ?>" target="_blank" style="color: #D4B68A;">Gestionar categorías</a>
              </small>
            </div>

            <div class="mb-3">
              <label class="form-label">Descripción</label>
              <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe los ingredientes y características del plato..."><?= old('descripcion') ?></textarea>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Precio *</label>
                <div class="input-group">
                  <span class="input-group-text" style="background-color: #D4B68A; color: #000; border-color: #D4B68A;">$</span>
                  <input type="number" step="0.01" name="precio" class="form-control" value="<?= old('precio') ?>" placeholder="0.00" required>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Stock *</label>
                <input type="number" name="stock" class="form-control" value="<?= old('stock', 0) ?>" min="0" placeholder="0" required>
                <small class="text-light">Stock disponible en unidades</small>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Imagen *</label>
              <input type="file" name="imagen" class="form-control" accept="image/*" required>
              <small class="text-light">Formatos aceptados: JPG, PNG, GIF. Sin límite de tamaño</small>
            </div>

            <div class="form-check form-switch mb-4">
              <input type="checkbox" name="disponible" id="disponible" class="form-check-input" checked>
              <label for="disponible" class="form-check-label text-light">Plato disponible para la venta</label>
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
