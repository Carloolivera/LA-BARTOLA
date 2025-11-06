<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color:#000; min-height:80vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h4 text-warning">Editar Stock: <?= esc($plato['nombre']) ?></h1>
          <a href="<?= site_url('admin/stock') ?>" class="btn btn-outline-warning">
            <i class="bi bi-arrow-left"></i> Volver
          </a>
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
            <!-- Información del plato -->
            <div class="row mb-4">
              <div class="col-md-4">
                <?php if (!empty($plato['imagen'])): ?>
                  <img src="<?= base_url('assets/images/platos/' . $plato['imagen']) ?>"
                       class="img-fluid rounded"
                       style="max-width: 100%;">
                <?php else: ?>
                  <div class="bg-secondary d-flex align-items-center justify-content-center rounded"
                       style="height: 200px;">
                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                  </div>
                <?php endif; ?>
              </div>
              <div class="col-md-8">
                <h5 class="text-warning"><?= esc($plato['nombre']) ?></h5>
                <p class="mb-2"><strong>Categoría:</strong> <?= esc($plato['categoria']) ?></p>
                <p class="mb-2"><strong>Precio:</strong> $<?= number_format($plato['precio'], 2) ?></p>
                <p class="mb-0"><strong>Descripción:</strong> <?= esc($plato['descripcion']) ?></p>
              </div>
            </div>

            <hr class="border-warning">

            <!-- Formulario de edición de stock -->
            <form action="<?= site_url('admin/stock/actualizar/' . $plato['id']) ?>" method="post">
              <?= csrf_field() ?>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Stock Actual *</label>
                  <div class="input-group">
                    <button class="btn btn-outline-danger" type="button" id="btn-decrease">
                      <i class="bi bi-dash-lg"></i>
                    </button>
                    <input type="number"
                           name="stock"
                           id="stock-input"
                           class="form-control bg-dark text-light text-center"
                           value="<?= old('stock', $plato['stock']) ?>"
                           min="0"
                           required
                           style="font-size: 1.5rem; font-weight: bold;">
                    <button class="btn btn-outline-success" type="button" id="btn-increase">
                      <i class="bi bi-plus-lg"></i>
                    </button>
                  </div>
                  <small class="text-muted">Unidades disponibles del plato</small>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Estado Actual</label>
                  <div class="p-3 rounded" style="background-color: #1a1a1a;">
                    <div class="mb-2">
                      <strong>Stock:</strong>
                      <span id="current-stock-display" class="badge <?= $plato['stock'] <= 0 ? 'bg-danger' : ($plato['stock'] <= 5 ? 'bg-warning text-dark' : 'bg-info') ?> ms-2">
                        <?= $plato['stock'] ?> unidades
                      </span>
                    </div>
                    <div>
                      <strong>Disponibilidad:</strong>
                      <span class="badge <?= $plato['disponible'] ? 'bg-success' : 'bg-secondary' ?> ms-2">
                        <?= $plato['disponible'] ? 'Disponible' : 'No disponible' ?>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-check form-switch mb-3">
                <input type="checkbox"
                       name="stock_ilimitado"
                       id="stock_ilimitado"
                       class="form-check-input"
                       <?= $plato['stock_ilimitado'] ? 'checked' : '' ?>>
                <label for="stock_ilimitado" class="form-check-label">
                  <i class="bi bi-infinity"></i> Stock Ilimitado
                </label>
                <br>
                <small class="text-muted">Si está activado, el plato siempre estará disponible sin importar el stock</small>
              </div>

              <div class="form-check form-switch mb-4">
                <input type="checkbox"
                       name="disponible"
                       id="disponible"
                       class="form-check-input"
                       <?= $plato['disponible'] ? 'checked' : '' ?>>
                <label for="disponible" class="form-check-label">
                  Disponible para venta
                </label>
                <br>
                <small class="text-muted">Si está desactivado, el plato no aparecerá en el menú público</small>
              </div>

              <div class="d-flex gap-2">
                <button class="btn btn-warning flex-fill" type="submit">
                  <i class="bi bi-check-circle"></i> Guardar Cambios
                </button>
                <a href="<?= site_url('admin/stock') ?>" class="btn btn-outline-warning">
                  Cancelar
                </a>
              </div>
            </form>
          </div>
        </div>

        <!-- Botones de acciones rápidas -->
        <div class="card bg-dark text-light mt-4">
          <div class="card-body">
            <h6 class="text-warning mb-3">Acciones Rápidas</h6>
            <div class="d-grid gap-2 d-md-flex">
              <button class="btn btn-success" type="button" id="btn-add-10">
                <i class="bi bi-plus-lg"></i> Agregar 10 unidades
              </button>
              <button class="btn btn-info" type="button" id="btn-add-20">
                <i class="bi bi-plus-lg"></i> Agregar 20 unidades
              </button>
              <button class="btn btn-warning" type="button" id="btn-set-zero">
                <i class="bi bi-x-circle"></i> Poner en 0
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const stockInput = document.getElementById('stock-input');
  const btnIncrease = document.getElementById('btn-increase');
  const btnDecrease = document.getElementById('btn-decrease');
  const btnAdd10 = document.getElementById('btn-add-10');
  const btnAdd20 = document.getElementById('btn-add-20');
  const btnSetZero = document.getElementById('btn-set-zero');
  const currentStockDisplay = document.getElementById('current-stock-display');
  const stockIlimitadoCheckbox = document.getElementById('stock_ilimitado');

  function updateStockDisplay() {
    const stock = parseInt(stockInput.value) || 0;
    currentStockDisplay.textContent = stock + ' unidades';

    // Actualizar color del badge
    currentStockDisplay.className = 'badge ms-2';
    if (stock <= 0) {
      currentStockDisplay.classList.add('bg-danger');
    } else if (stock <= 5) {
      currentStockDisplay.classList.add('bg-warning', 'text-dark');
    } else {
      currentStockDisplay.classList.add('bg-info');
    }
  }

  btnIncrease.addEventListener('click', function() {
    stockInput.value = parseInt(stockInput.value) + 1;
    updateStockDisplay();
  });

  btnDecrease.addEventListener('click', function() {
    const currentValue = parseInt(stockInput.value);
    if (currentValue > 0) {
      stockInput.value = currentValue - 1;
      updateStockDisplay();
    }
  });

  btnAdd10.addEventListener('click', function() {
    stockInput.value = parseInt(stockInput.value) + 10;
    updateStockDisplay();
  });

  btnAdd20.addEventListener('click', function() {
    stockInput.value = parseInt(stockInput.value) + 20;
    updateStockDisplay();
  });

  btnSetZero.addEventListener('click', function() {
    if (confirm('¿Estás seguro de poner el stock en 0?')) {
      stockInput.value = 0;
      updateStockDisplay();
    }
  });

  stockInput.addEventListener('input', updateStockDisplay);

  // Deshabilitar input de stock si está activado stock ilimitado
  stockIlimitadoCheckbox.addEventListener('change', function() {
    if (this.checked) {
      stockInput.disabled = true;
      btnIncrease.disabled = true;
      btnDecrease.disabled = true;
      btnAdd10.disabled = true;
      btnAdd20.disabled = true;
      btnSetZero.disabled = true;
    } else {
      stockInput.disabled = false;
      btnIncrease.disabled = false;
      btnDecrease.disabled = false;
      btnAdd10.disabled = false;
      btnAdd20.disabled = false;
      btnSetZero.disabled = false;
    }
  });

  // Inicializar estado
  if (stockIlimitadoCheckbox.checked) {
    stockInput.disabled = true;
    btnIncrease.disabled = true;
    btnDecrease.disabled = true;
    btnAdd10.disabled = true;
    btnAdd20.disabled = true;
    btnSetZero.disabled = true;
  }
});
</script>

<?= $this->endSection() ?>
