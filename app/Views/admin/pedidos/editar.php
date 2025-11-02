<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color: #000; min-height: 80vh;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 style="color: #D4B68A;">Editar Pedido #<?= $pedido['id'] ?></h1>
      <a href="/admin/pedidos" class="btn btn-outline-warning">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
    </div>

    <div class="card bg-dark text-light">
      <div class="card-body">
        <form action="/admin/pedidos/editar/<?= $pedido['id'] ?>" method="post">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select bg-dark text-light" style

="border-color: #d4af37;" required>
                  <?php foreach ($estados as $estado): ?>
                    <option value="<?= $estado ?>" <?= $pedido['estado'] == $estado ? 'selected' : '' ?>>
                      <?= ucfirst(str_replace('_', ' ', $estado)) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="mb-3">
                <label for="notas" class="form-label">Notas</label>
                <textarea name="notas" id="notas" class="form-control bg-dark text-light" style="border-color: #d4af37;" rows="5"><?= $pedido['notas'] ?></textarea>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-warning">
                <i class="bi bi-save"></i> Guar

dar Cambios
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>