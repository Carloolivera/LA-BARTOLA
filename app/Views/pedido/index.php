<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color: #000; min-height: 80vh;">
  <div class="container">
    <h1 class="mb-4" style="color: #D4B68A;">Mis Pedidos</h1>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="row g-4">
      <?php if (!empty($pedidos)): ?>
        <?php foreach ($pedidos as $pedido): ?>
          <div class="col-md-6 col-lg-4">
            <div class="card bg-dark text-light h-100">
              <?php if (!empty($pedido['imagen'])): ?>
                <img src="<?= base_url('assets/images/platos/' . $pedido['imagen']) ?>" 
                     class="card-img-top" 
                     alt="<?= esc($pedido['plato_nombre']) ?>"
                     style="height: 200px; object-fit: cover;">
              <?php endif; ?>
              
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h5 class="card-title" style="color: #D4B68A;"><?= esc($pedido['plato_nombre']) ?></h5>
                  <?php
                    $badgeClass = match($pedido['estado']) {
                      'pendiente' => 'warning',
                      'en_proceso' => 'info',
                      'completado' => 'success',
                      'cancelado' => 'danger',
                      default => 'secondary'
                    };
                  ?>
                  <span class="badge bg-<?= $badgeClass ?>"><?= ucfirst(str_replace('_', ' ', $pedido['estado'])) ?></span>
                </div>
                
                <p class="mb-2"><strong>Cantidad:</strong> <?= $pedido['cantidad'] ?></p>
                <p class="mb-2"><strong>Total:</strong> $<?= number_format($pedido['total'], 2, ',', '.') ?></p>
                
                <?php if (!empty($pedido['notas'])): ?>
                  <p class="small text-muted mb-2">
                    <strong>Notas:</strong> <?= esc($pedido['notas']) ?>
                  </p>
                <?php endif; ?>
                
                <p class="small text-muted mb-0">
                  <i class="bi bi-calendar"></i> <?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?>
                </p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12">
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>
            Aún no has realizado ningún pedido.
            <br>
            <a href="<?= site_url('menu') ?>" class="btn btn-warning mt-3">Ver Menú</a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?= $this->endSection() ?>