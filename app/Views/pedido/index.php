<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5 text-center bg-black text-light">
  <div class="container">
    <h1 class="fw-bold mb-4 text-warning">Realizá tu Pedido</h1>
    <p class="text-beige">Seleccioná tus platos favoritos y confirmá tu orden.</p>
  </div>
</section>

<section class="py-5 bg-dark text-light">
  <div class="container">
    <form method="post" action="<?= site_url('pedido/crear') ?>">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label text-beige">Plato</label>
        <select class="form-select bg-black text-light border-warning" name="plato_id" required>
          <?php foreach ($platos as $p): ?>
            <option value="<?= $p['id'] ?>"><?= esc($p['nombre']) ?> - $<?= number_format($p['precio'], 2, ',', '.') ?></option>
          <?php endforeach ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label text-beige">Cantidad</label>
        <input type="number" name="cantidad" class="form-control bg-black text-light border-warning" min="1" value="1" required>
      </div>

      <button type="submit" class="btn btn-warning text-black fw-bold">Confirmar Pedido</button>
    </form>
  </div>
</section>

<?= $this->endSection() ?>
