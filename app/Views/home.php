<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="hero text-center text-light py-5" style="background-color: #000;">
  <div class="container">
    <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo La Bartola" style="height: 120px; width: auto; margin-bottom: 1.5rem;">
    <h1 class="display-4 fw-bold" style="color: #D4B68A;">La Bartola</h1>
    <p class="lead text-light mb-4">Casa de comidas y delivery en Buenos Aires</p>
    <a href="<?= base_url('menu') ?>" class="btn btn-lg" style="background-color: #D4B68A; color: #000;">Ver Menú</a>
  </div>
</section>

<section class="about py-5" style="background-color: #111;">
  <div class="container text-center text-light">
    <h2 class="mb-4" style="color: #D4B68A;">Sobre Nosotros</h2>
    <p class="mx-auto" style="max-width: 700px;">
      En <strong>La Bartola</strong> combinamos cocina casera, ingredientes frescos y un servicio de delivery pensado para vos.
      Preparaciones simples pero llenas de sabor, ideales para compartir cualquier día de la semana.
    </p>
  </div>
</section>

<section class="menu-preview py-5" style="background-color: #000;">
  <div class="container text-center text-light">
    <h2 class="mb-5" style="color: #D4B68A;">Platos Destacados</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card bg-dark border-0 text-light">
          <div class="card-body">
            <h5 class="card-title" style="color: #D4B68A;">Milanesa Napolitana</h5>
            <p class="card-text">Con papas fritas caseras.</p>
            <p class="fw-bold" style="color: #D4B68A;">$2500</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-dark border-0 text-light">
          <div class="card-body">
            <h5 class="card-title" style="color: #D4B68A;">Empanadas Caseras</h5>
            <p class="card-text">Sabores clásicos y gourmet.</p>
            <p class="fw-bold" style="color: #D4B68A;">$150 c/u</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-dark border-0 text-light">
          <div class="card-body">
            <h5 class="card-title" style="color: #D4B68A;">Ensalada La Bartola</h5>
            <p class="card-text">Fresca, nutritiva y sabrosa.</p>
            <p class="fw-bold" style="color: #D4B68A;">$1800</p>
          </div>
        </div>
      </div>
    </div>
    <a href="<?= base_url('menu') ?>" class="btn mt-4" style="background-color: #D4B68A; color: #000;">Ver Menú Completo</a>
  </div>
</section>

<section class="delivery py-5" style="background-color: #111;">
  <div class="container text-center text-light">
    <h2 class="mb-4" style="color: #D4B68A;">Delivery Rápido y Seguro</h2>
    <p>Entregamos en toda la zona de Buenos Aires con la mejor atención. Pedí online o por WhatsApp y recibí tu pedido caliente en minutos.</p>
    <a href="<?= base_url('menu') ?>" class="btn mt-3" style="background-color: #D4B68A; color: #000;">Hacer Pedido</a>
  </div>
</section>

<section class="testimonios py-5" style="background-color: #000;">
  <div class="container text-center text-light">
    <h2 class="mb-5" style="color: #D4B68A;">Opiniones de Nuestros Clientes</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <blockquote class="blockquote">
          <p>"Excelente comida y entrega rápida."</p>
          <footer class="blockquote-footer text-light">Martina R.</footer>
        </blockquote>
      </div>
      <div class="col-md-4">
        <blockquote class="blockquote">
          <p>"Todo riquísimo, las empanadas son un 10."</p>
          <footer class="blockquote-footer text-light">Lucas G.</footer>
        </blockquote>
      </div>
      <div class="col-md-4">
        <blockquote class="blockquote">
          <p>"Siempre puntuales, muy recomendable."</p>
          <footer class="blockquote-footer text-light">Sofía T.</footer>
        </blockquote>
      </div>
    </div>
  </div>
</section>

<section class="cta py-5 text-center" style="background-color: #111;">
  <div class="container">
    <h3 class="mb-3" style="color: #D4B68A;">¿Listo para disfrutar?</h3>
    <p class="mb-4">Hacé tu pedido ahora y recibilo en minutos</p>
    <a href="<?= base_url('menu') ?>" class="btn btn-lg" style="background-color: #D4B68A; color: #000;">Pedir Ahora</a>
  </div>
</section>

<?= $this->endSection() ?>