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
    <div class="row g-4">
      <?php if (!empty($platos)) : ?>
        <?php foreach ($platos as $p) : ?>
          <?php 
          // Determinar si el stock es bajo
          $stockBajo = ($p['stock_ilimitado'] == 0) && ($p['stock'] > 0) && ($p['stock'] <= 5);
          ?>
          
          <div class="col-md-6 col-lg-4">
            <div class="card bg-black text-light border-warning h-100">
              <?php if (!empty($p['imagen'])): ?>
                <img src="<?= base_url('assets/images/platos/' . $p['imagen']) ?>" 
                     class="card-img-top" 
                     alt="<?= esc($p['nombre']) ?>"
                     style="height: 250px; object-fit: cover;">
              <?php else: ?>
                <div class="bg-secondary d-flex align-items-center justify-content-center" 
                     style="height: 250px;">
                  <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                </div>
              <?php endif; ?>
              
              <!-- Badge de stock bajo -->
              <?php if ($stockBajo): ?>
                <div class="position-absolute top-0 end-0 m-2">
                  <span class="badge bg-warning text-dark">
                    <i class="bi bi-exclamation-triangle"></i> ¡ÚLTIMAS <?= $p['stock'] ?>!
                  </span>
                </div>
              <?php endif; ?>
              
              <div class="card-body">
                <?php if (!empty($p['categoria'])): ?>
                  <p class="text-muted small mb-2">
                    <i class="bi bi-tag"></i> <?= esc($p['categoria']) ?>
                  </p>
                <?php endif; ?>
                
                <h5 class="card-title text-warning"><?= esc($p['nombre']) ?></h5>
                <p class="card-text"><?= esc($p['descripcion']) ?></p>
                <p class="fw-bold fs-5 text-warning mb-3">$<?= number_format($p['precio'], 2, ',', '.') ?></p>
                
                <button class="btn btn-warning w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalPedir<?= $p['id'] ?>">
                  <i class="bi bi-cart-plus"></i> Pedir
                </button>
              </div>
            </div>
          </div>

          <!-- Modal del plato -->
          <div class="modal fade" id="modalPedir<?= $p['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content bg-dark text-light">
                <div class="modal-header border-warning">
                  <h5 class="modal-title text-warning"><?= esc($p['nombre']) ?></h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formAgregarCarrito<?= $p['id'] ?>" class="form-agregar-carrito">
                  <?= csrf_field() ?>
                  <input type="hidden" name="plato_id" value="<?= $p['id'] ?>">
                  
                  <div class="modal-body">
                    <div class="text-center mb-3">
                      <?php if (!empty($p['imagen'])): ?>
                        <img src="<?= base_url('assets/images/platos/' . $p['imagen']) ?>" 
                             alt="<?= esc($p['nombre']) ?>"
                             style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                      <?php endif; ?>
                    </div>
                    
                    <p><?= esc($p['descripcion']) ?></p>
                    <p class="fw-bold fs-4 text-warning">Precio: $<?= number_format($p['precio'], 2, ',', '.') ?></p>
                    
                    <div class="mb-3">
                      <label class="form-label">Cantidad</label>
                      <input type="number" 
                             class="form-control bg-dark text-light border-warning" 
                             name="cantidad" 
                             value="1" 
                             min="1" 
                             max="<?= $p['stock_ilimitado'] == 1 ? 99 : $p['stock'] ?>"
                             required>
                      <?php if ($p['stock_ilimitado'] == 0 && $p['stock'] > 0): ?>
                        <small class="text-muted">
                          <i class="bi bi-box-seam"></i> Stock disponible: <?= $p['stock'] ?> unidad<?= $p['stock'] != 1 ? 'es' : '' ?>
                        </small>
                      <?php elseif ($p['stock_ilimitado'] == 1): ?>
                        <small class="text-success">
                          <i class="bi bi-infinity"></i> Stock ilimitado
                        </small>
                      <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                      <label class="form-label">Notas (opcional)</label>
                      <textarea class="form-control bg-dark text-light border-warning" 
                                name="notas" 
                                rows="2" 
                                placeholder="Ej: Sin cebolla, extra salsa, etc."></textarea>
                    </div>
                  </div>
                  
                  <div class="modal-footer border-warning">
                    <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                      <i class="bi bi-cart-plus"></i> Agregar al Carrito
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      <?php else : ?>
        <div class="col-12">
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>
            No hay platos disponibles actualmente.
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const forms = document.querySelectorAll('.form-agregar-carrito');
  
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const modal = bootstrap.Modal.getInstance(this.closest('.modal'));
      
      fetch('<?= site_url('carrito/agregar') ?>', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Actualizar contador del carrito
          const cartCount = document.getElementById('cart-count');
          if (cartCount) {
            cartCount.textContent = data.cart_count;
          }
          
          // Cerrar modal
          if (modal) {
            modal.hide();
          }
          
          // Mostrar alerta de éxito
          const alert = document.createElement('div');
          alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
          alert.style.zIndex = '9999';
          alert.style.minWidth = '300px';
          alert.innerHTML = `
            <i class="bi bi-check-circle me-2"></i>${data.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          `;
          document.body.appendChild(alert);
          
          setTimeout(() => alert.remove(), 3000);
          
          // Resetear formulario
          this.reset();
        } else {
          // Mostrar error
          const alert = document.createElement('div');
          alert.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
          alert.style.zIndex = '9999';
          alert.style.minWidth = '300px';
          alert.innerHTML = `
            <i class="bi bi-exclamation-triangle me-2"></i>${data.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          `;
          document.body.appendChild(alert);
          
          setTimeout(() => alert.remove(), 3000);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        alert.style.zIndex = '9999';
        alert.innerHTML = `
          <i class="bi bi-x-circle me-2"></i>Error al agregar al carrito
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        setTimeout(() => alert.remove(), 3000);
      });
    });
  });
});
</script>

<style>
.card {
  transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
}

.position-absolute.top-0.end-0 {
  z-index: 10;
}

.badge {
  font-size: 0.75rem;
  padding: 0.5rem 0.75rem;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}
</style>

<?= $this->endSection() ?>