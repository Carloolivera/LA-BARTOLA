<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color: #000; min-height: 80vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 style="color: #D4B68A;">Crear Nuevo Plato</h1>
          <a href="<?= site_url('menu') ?>" class="btn btn-outline-warning">
            <i class="bi bi-arrow-left"></i> Volver
          </a>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger alert-dismissible fade show">
            <strong>Errores:</strong>
            <ul class="mb-0">
              <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
              <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <div class="card bg-dark text-light">
          <div class="card-body p-4">
            <form action="<?= site_url('admin/menu/guardar') ?>" method="post" enctype="multipart/form-data">
              <?= csrf_field() ?>

              <div class="mb-3">
                <label class="form-label">Nombre del Plato <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control bg-dark text-light" 
                       name="nombre" 
                       value="<?= old('nombre') ?>"
                       required 
                       style="border-color: #d4af37;">
              </div>

              <div class="mb-3">
                <label class="form-label">Categoría <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control bg-dark text-light" 
                       name="categoria" 
                       value="<?= old('categoria') ?>"
                       placeholder="Ej: Entradas, Principales, Postres, Bebidas"
                       required 
                       style="border-color: #d4af37;">
                <small class="text-muted">Escribe la categoría del plato</small>
              </div>

              <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control bg-dark text-light" 
                          name="descripcion" 
                          rows="3" 
                          style="border-color: #d4af37;"><?= old('descripcion') ?></textarea>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Precio <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text bg-dark text-light" style="border-color: #d4af37;">$</span>
                    <input type="number" 
                           step="0.01" 
                           class="form-control bg-dark text-light" 
                           name="precio" 
                           value="<?= old('precio') ?>"
                           required 
                           style="border-color: #d4af37;">
                  </div>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Stock <span class="text-danger">*</span></label>
                  <input type="number" 
                         class="form-control bg-dark text-light" 
                         name="stock" 
                         value="<?= old('stock', 0) ?>"
                         min="0"
                         required 
                         style="border-color: #d4af37;">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Imagen del Plato <span class="text-danger">*</span></label>
                <input type="file" 
                       class="form-control bg-dark text-light" 
                       name="imagen" 
                       accept="image/*"
                       required 
                       style="border-color: #d4af37;"
                       onchange="previewImage(event)">
                <small class="text-muted">Formatos: JPG, PNG, WEBP. Máximo 2MB</small>
              </div>

              <div class="mb-3" id="imagePreview" style="display: none;">
                <label class="form-label">Vista previa:</label>
                <div>
                  <img id="preview" src="" alt="Preview" style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                </div>
              </div>

              <div class="mb-4">
                <div class="form-check form-switch">
                  <input class="form-check-input" 
                         type="checkbox" 
                         name="disponible" 
                         id="disponible" 
                         checked
                         style="cursor: pointer;">
                  <label class="form-check-label" for="disponible" style="cursor: pointer;">
                    Marcar como disponible
                  </label>
                </div>
              </div>

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning flex-fill">
                  <i class="bi bi-check-circle"></i> Guardar Plato
                </button>
                <a href="<?= site_url('menu') ?>" class="btn btn-outline-warning">
                  Cancelar
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
function previewImage(event) {
  const preview = document.getElementById('preview');
  const previewContainer = document.getElementById('imagePreview');
  const file = event.target.files[0];
  
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      previewContainer.style.display = 'block';
    }
    reader.readAsDataURL(file);
  }
}
</script>

<?= $this->endSection() ?>