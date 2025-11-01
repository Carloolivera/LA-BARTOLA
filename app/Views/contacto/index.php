<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5 text-center bg-black text-light">
  <div class="container">
    <h1 class="fw-bold mb-4 text-warning">Contacto</h1>
    <p class="text-beige">Consultas, pedidos especiales o reservas. Te respondemos a la brevedad.</p>
  </div>
</section>

<section class="py-5 bg-dark text-light">
  <div class="container col-md-6">
    <form method="post" action="<?= site_url('contacto/enviar') ?>">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label text-beige">Nombre</label>
        <input type="text" name="nombre" class="form-control bg-black text-light border-warning" required>
      </div>

      <div class="mb-3">
        <label class="form-label text-beige">Email</label>
        <input type="email" name="email" class="form-control bg-black text-light border-warning" required>
      </div>

      <div class="mb-3">
        <label class="form-label text-beige">Mensaje</label>
        <textarea name="mensaje" rows="4" class="form-control bg-black text-light border-warning" required></textarea>
      </div>

      <button type="submit" class="btn btn-warning text-black fw-bold w-100">Enviar</button>
    </form>
  </div>
</section>

<?= $this->endSection() ?>
