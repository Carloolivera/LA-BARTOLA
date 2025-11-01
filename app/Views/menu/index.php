<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5 text-center text-light bg-black">
  <div class="container">
    <h1 class="fw-bold mb-4 text-warning">Menú</h1>
    <p class="text-beige">Descubrí nuestros platos caseros listos para llevar o disfrutar en casa.</p>
  </div>
</section>

<section class="py-5 bg-dark text-light">
  <div class="container">
    <div class="row">
      <?php if (!empty($platos)) : ?>
        <?php foreach ($platos as $p) : ?>
          <div class="col-md-4 mb-4">
            <div class="card bg-black text-light border-0 shadow-sm">
              <img src="<?= base_url('uploads/' . $p['imagen']) ?>" class="card-img-top" alt="<?= esc($p['nombre']) ?>">
              <div class="card-body">
                <h5 class="card-title text-warning"><?= esc($p['nombre']) ?></h5>
                <p class="card-text"><?= esc($p['descripcion']) ?></p>
                <p class="fw-bold">$<?= number_format($p['precio'], 2, ',', '.') ?></p>
                <a href="<?= site_url('pedido') ?>" class="btn btn-outline-warning btn-sm">Pedir</a>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      <?php else : ?>
        <p class="text-center text-beige">No hay platos cargados actualmente.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
