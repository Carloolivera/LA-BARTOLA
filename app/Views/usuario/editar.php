<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5 text-center text-light bg-black">
  <div class="container">
    <h1 class="fw-bold mb-4 text-warning">
      <i class="bi bi-pencil"></i> Editar Usuario
    </h1>
  </div>
</section>

<section class="py-5 bg-dark text-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card bg

-black text-light border-warning">
          <div class="card-body">
            <?php if (session('errors')): ?>
              <div class="alert alert-danger">
                <ul class="mb-0">
                  <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form action="<?= site_url('usuario/actualizar/' . $usuario['id']) ?>" method="post">
              <?= csrf_field() ?>

              <div class="mb-3">
                <label class="form-label">
                  <i class="bi bi-person"></i> Nombre de Usuario
                </label>
                <input type="text" 
                       class="form-control bg-dark text-light border-warning" 
                       name="username" 
                       value="<?= old('username', $usuario['username']) ?>"
                       required>
              </div>

              <div

 class="mb-3">
                <label class="form-label">
                  <i class="bi bi-lock"></i> Nueva Contraseña
                </label>
                <input type="password" 
                       class="form-control bg-dark text-light border-warning" 
                       name="password">
                <small class="text-muted">Dejar en blanco para mantener la actual</small>
              </div>

              <div class="mb-3">
                <label class="form-label">
                  <i class="bi bi-shield"></i> Grupo
                </label>
                <select class="form-select bg-dark text-light border-warning" 
                        name="grupo" 
                        required>
                  <option value="admin" <?= old('grupo', $usuario['group']) == 'admin' ? 'selected' : '' ?>>Admin</option>
                  <option value="vendedor" <?= old('grupo', $usuario['group']) == 'vendedor' ? 'selected' : '' ?>>Vendedor</option>
                  <option value="

cliente" <?= old('grupo', $usuario['group']) == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">
                  <i class="bi bi-toggle-on"></i> Estado
                </label>
                <select class="form-select bg-dark text-light border-warning" 
                        name="active" 
                        required>
                  <option value="1" <?= old('active', $usuario['active']) == 1 ? 'selected' : '' ?>>Activo</option>
                  <option value="0" <?= old('active', $usuario['active']) == 0 ? 'selected' : '' ?>>Inactivo</option>
                </select>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning">
                  <i class="bi bi-save"></i> Actualizar Usuario
                </button>
                <a href="<?= site_url('usuario') ?>" class="btn btn-outline-

warning">
                  <i class="bi bi-arrow-left"></i> Cancelar
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>