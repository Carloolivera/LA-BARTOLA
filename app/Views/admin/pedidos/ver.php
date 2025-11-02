<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color: #000; min-height: 80vh;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 style="color: #D4B68A;">Detalle del Pedido #<?= $pedido['id'] ?></h1>
      <a href="/admin/pedidos" class="btn btn-outline-warning">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
    </div>

    <div class="card bg-dark text-light">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h5 style="color: #D4B68A;">Información del Cliente</h5>
            <p><strong>Nombre:</strong> <?= $pedido['username'] ?? 'Invitado' ?></p>
            <p><strong>Email:</strong> <?= $pedi

do['email'] ?? 'N/A' ?></p>
          </div>
          <div class="col-md-6">
            <h5 style="color: #D4B68A;">Información del Pedido</h5>
            <p><strong>Estado:</strong> 
              <span class="badge bg-<?= $pedido['estado'] == 'entregado' ? 'success' : ($pedido['estado'] == 'cancelado' ? 'danger' : 'warning') ?>">
                <?= ucfirst(str_replace('_', ' ', $pedido['estado'])) ?>
              </span>
            </p>
            <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></p>
          </div>
        </div>
        
        <hr style="border-color: #d4af37;">
        
        <div class="row">
          <div class="col-md-12">
            <h5 style="color: #D4B68A;">Detalle del Plato</h5>
            <div class="d-flex align-items-center mb-3">
              <?php if (!empty($pedido['imagen'])): ?>
                <img src="<?= base_url('assets/images/platos/' . $pedido['imagen']) ?>" 
                     alt="<?= $pedido['plato_

nombre'] ?>" 
                     style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #d4af37;" 
                     class="me-3">
              <?php endif; ?>
              <div>
                <p class="mb-1"><strong><?= $pedido['plato_nombre'] ?></strong></p>
                <p class="mb-1">Cantidad: <?= $pedido['cantidad'] ?></p>
                <p class="mb-1">Precio unitario: $<?= number_format($pedido['plato_precio'], 2) ?></p>
                <p class="mb-0"><strong style="color: #D4B68A;">Total: $<?= number_format($pedido['total'], 2) ?></strong></p>
              </div>
            </div>
          </div>
        </div>
        
        <?php if (!empty($pedido['notas'])): ?>
        <hr style="border-color: #d4af37;">
        <div class="row">
          <div class="col-md-12">
            <h5 style="color: #D4B68A;">Notas del Pedido</h5>
            <p style="white-space: pre-line;"><?= $pedido['notas'] ?></p>
          </div>
        </div>


        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>