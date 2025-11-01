<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5 text-center bg-black text-light">
  <div class="container">
    <h1 class="fw-bold mb-4 text-warning">Mi Perfil</h1>
    <p class="text-beige">Información de tu cuenta en La Bartola</p>
  </div>
</section>

<section class="py-5 bg-dark text-light">
  <div class="container col-md-6">
    <div class="card bg-black text-light border-warning p-4">
      <p><strong class="text-warning">Nombre:</strong> <?= esc($usuario->username) ?></p>
      <p><strong class="text-warning">Email:</strong> <?= esc($usuario->email) ?></p>
      <a href="<?= site_url('logout') ?>" class="btn btn-outline-warning w-100">Cerrar sesión</a>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
