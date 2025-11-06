<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color:#000; min-height:80vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h4 text-warning">Editar plato</h1>
          <a href="<?= site_url('admin/menu') ?>" class="btn btn-outline-warning">Volver</a>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger">
            <ul class="mb-0">
              <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <div class="card bg-dark text-light">
          <div class="card-body">
            <form action="<?= site_url('admin/menu/actualizar/' . $plato['id']) ?>" method="post" enctype="multipart/form-data">
              <?= csrf_field() ?>

              <div class="mb-3">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control bg-dark text-light" value="<?= old('nombre', $plato['nombre']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Categoría</label>
                <input type="text" name="categoria" class="form-control bg-dark text-light" value="<?= old('categoria', $plato['categoria']) ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control bg-dark text-light" rows="3"><?= old('descripcion', $plato['descripcion']) ?></textarea>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Precio *</label>
                  <input type="number" step="0.01" name="precio" class="form-control bg-dark text-light" value="<?= old('precio', $plato['precio']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Stock *</label>
                  <input type="number" name="stock" class="form-control bg-dark text-light" value="<?= old('stock', $plato['stock']) ?>" min="0" required>
                </div>
              </div>

              <?php if (! empty($plato['imagen'])): ?>
                <div class="mb-3">
                  <label class="form-label">Imagen actual</label>
                  <div><img src="<?= base_url('assets/images/platos/' . $plato['imagen']) ?>" style="max-width:300px;"></div>
                </div>
              <?php endif; ?>

              <div class="mb-3">
                <label class="form-label">Cambiar imagen (opcional)</label>
                <input type="file" name="imagen" class="form-control bg-dark text-light" accept="image/*">
                <small class="text-muted">Dejar vacío para no cambiar</small>
              </div>

              <div class="form-check form-switch mb-3">
                <input type="checkbox" name="disponible" id="disponible" class="form-check-input" <?= $plato['disponible'] ? 'checked' : '' ?>>
                <label for="disponible" class="form-check-label">Disponible</label>
              </div>

              <div class="d-flex gap-2">
                <button class="btn btn-warning flex-fill" type="submit">Actualizar</button>
                <a href="<?= site_url('admin/menu') ?>" class="btn btn-outline-warning">Cancelar</a>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
