<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Barra de Redes Sociales Superior -->
<section class="social-bar py-3" style="background: linear-gradient(135deg, #D4B68A 0%, #c9a770 100%);">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8 text-center text-md-start">
        <h6 class="mb-2 mb-md-0 text-dark fw-bold">
          <i class="bi bi-heart-fill text-danger"></i> ¡Seguinos en nuestras redes sociales!
        </h6>
      </div>
      <div class="col-md-4">
        <div class="d-flex justify-content-center justify-content-md-end gap-3">
          <a href="https://instagram.com/labartolaok" target="_blank"
             class="social-icon" title="Instagram">
            <i class="bi bi-instagram"></i>
          </a>
          <a href="https://wa.me/542241517665" target="_blank"
             class="social-icon" title="WhatsApp">
            <i class="bi bi-whatsapp"></i>
          </a>
          <a href="https://facebook.com/labartolaok" target="_blank"
             class="social-icon" title="Facebook">
            <i class="bi bi-facebook"></i>
          </a>
          <a href="https://www.google.com/maps/search/?api=1&query=Newbery+356,+Buenos+Aires,+Argentina" target="_blank"
             class="social-icon" title="Ubicación">
            <i class="bi bi-geo-alt-fill"></i>
          </a>
          <a href="#" onclick="enviarUbicacion(); return false;"
             class="social-icon" title="Delivery - Enviar ubicación">
            <i class="bi bi-bicycle"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Hero Section -->
<section class="hero text-center text-light py-5" style="background: linear-gradient(180deg, #000 0%, #1a1a1a 100%);">
  <div class="container">
    <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo La Bartola"
         style="height: 120px; width: auto; margin-bottom: 1.5rem;"
         class="animate-float">
    <h1 class="display-3 fw-bold mb-3" style="color: #D4B68A;">La Bartola</h1>
    <p class="lead text-light mb-4" style="max-width: 600px; margin: 0 auto;">
      Casa de comidas caseras con delivery en Buenos Aires
    </p>
    <div class="d-flex gap-3 justify-content-center flex-wrap mb-4">
      <div class="info-badge">
        <i class="bi bi-geo-alt-fill text-warning"></i>
        <span>Newbery 356</span>
      </div>
      <div class="info-badge">
        <i class="bi bi-clock-fill text-warning"></i>
        <span>19:30hs - 23:00hs</span>
      </div>
      <div class="info-badge">
        <i class="bi bi-telephone-fill text-warning"></i>
        <span>2241 517665</span>
      </div>
    </div>
  </div>
</section>

<!-- Sección de Ofertas -->
<section class="ofertas py-5" style="background-color: #000; border-top: 3px solid #D4B68A; border-bottom: 3px solid #D4B68A;">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="display-5 fw-bold mb-2" style="color: #D4B68A;">
        <i class="bi bi-star-fill text-warning"></i> Ofertas de la Semana <i class="bi bi-star-fill text-warning"></i>
      </h2>
      <p class="text-light">¡Aprovechá nuestras promociones especiales!</p>
    </div>

    <div class="row g-4">
      <!-- Oferta 1 -->
      <div class="col-md-4">
        <div class="oferta-card">
          <div class="oferta-badge">-20%</div>
          <div class="card bg-dark border-warning h-100">
            <div class="card-body text-center">
              <i class="bi bi-basket-fill display-1 text-warning mb-3"></i>
              <h4 class="text-warning">Combo Familiar</h4>
              <p class="text-light">4 Empanadas + 2 Bebidas</p>
              <div class="price-container">
                <span class="old-price">$3000</span>
                <span class="new-price">$2400</span>
              </div>
              <a href="https://wa.me/542241517665?text=Hola! Quiero el Combo Familiar"
                 target="_blank"
                 class="btn btn-warning w-100 mt-3">
                <i class="bi bi-whatsapp"></i> Pedir Ahora
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Oferta 2 -->
      <div class="col-md-4">
        <div class="oferta-card">
          <div class="oferta-badge">2x1</div>
          <div class="card bg-dark border-warning h-100">
            <div class="card-body text-center">
              <i class="bi bi-cup-hot-fill display-1 text-warning mb-3"></i>
              <h4 class="text-warning">Miércoles de Café</h4>
              <p class="text-light">Todos los miércoles: 2x1 en cafés</p>
              <div class="price-container">
                <span class="new-price">¡Promo especial!</span>
              </div>
              <a href="https://wa.me/542241517665?text=Hola! Quiero info del 2x1 de café"
                 target="_blank"
                 class="btn btn-warning w-100 mt-3">
                <i class="bi bi-whatsapp"></i> Consultar
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Oferta 3 -->
      <div class="col-md-4">
        <div class="oferta-card">
          <div class="oferta-badge">ENVÍO GRATIS</div>
          <div class="card bg-dark border-warning h-100">
            <div class="card-body text-center">
              <i class="bi bi-truck display-1 text-warning mb-3"></i>
              <h4 class="text-warning">Pedidos +$5000</h4>
              <p class="text-light">Envío gratis en compras superiores</p>
              <div class="price-container">
                <span class="new-price">Sin cargo</span>
              </div>
              <button class="btn btn-warning w-100 mt-3" onclick="scrollToMenu()">
                <i class="bi bi-arrow-down-circle"></i> Ver Menú
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Menú Completo -->
<section class="menu py-5 text-light" style="background-color: #111;" id="menu-section">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold" style="color: #D4B68A;">Nuestro Menú</h2>
      <p class="lead">Platos caseros preparados con amor</p>
    </div>

    <div class="row g-4">
      <?php if (!empty($platos)): ?>
        <?php foreach ($platos as $p): ?>
          <?php
          // Determinar si el stock es bajo
          $stockBajo = ($p['stock_ilimitado'] == 0) && ($p['stock'] > 0) && ($p['stock'] <= 5);
          ?>

          <div class="col-md-6 col-lg-4">
            <div class="card bg-dark text-light border-warning h-100 plato-card">
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
                  <span class="badge bg-warning text-dark pulse-badge">
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

                <h5 class="card-title text-warning fw-bold"><?= esc($p['nombre']) ?></h5>
                <p class="card-text"><?= esc($p['descripcion']) ?></p>
                <p class="fw-bold fs-4 text-warning mb-3">$<?= number_format($p['precio'], 2, ',', '.') ?></p>

                <button class="btn btn-warning w-100"
                        data-bs-toggle="modal"
                        data-bs-target="#modalPedir<?= $p['id'] ?>">
                  <i class="bi bi-cart-plus"></i> Agregar al Carrito
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
      <?php else: ?>
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

<!-- Contacto e Información -->
<section class="contacto py-5" style="background-color: #000;">
  <div class="container">
    <h2 class="text-center mb-5" style="color: #D4B68A;">Contacto e Información</h2>
    <div class="row g-4 text-light">
      <!-- Card Instagram -->
      <div class="col-md-6 col-lg-3">
        <div class="card bg-dark border-warning h-100 text-center">
          <div class="card-body">
            <i class="bi bi-instagram display-4 mb-3" style="color: #D4B68A;"></i>
            <h5 class="card-title" style="color: #D4B68A;">Instagram</h5>
            <p class="card-text">
              <a href="https://instagram.com/labartolaok" target="_blank" class="text-light text-decoration-none fw-bold">
                @labartolaok
              </a>
            </p>
          </div>
        </div>
      </div>

      <!-- Card WhatsApp -->
      <div class="col-md-6 col-lg-3">
        <div class="card bg-dark border-warning h-100 text-center">
          <div class="card-body">
            <i class="bi bi-whatsapp display-4 mb-3" style="color: #D4B68A;"></i>
            <h5 class="card-title" style="color: #D4B68A;">WhatsApp</h5>
            <p class="card-text">
              <a href="https://wa.me/542241517665" target="_blank" class="text-light text-decoration-none fw-bold">
                2241 517665
              </a>
            </p>
          </div>
        </div>
      </div>

      <!-- Card Dirección -->
      <div class="col-md-6 col-lg-3">
        <div class="card bg-dark border-warning h-100 text-center">
          <div class="card-body">
            <i class="bi bi-geo-alt-fill display-4 mb-3" style="color: #D4B68A;"></i>
            <h5 class="card-title" style="color: #D4B68A;">Dirección</h5>
            <p class="card-text fw-bold text-light">
              Newbery 356<br>
              <small style="color: #999;">Buenos Aires</small>
            </p>
          </div>
        </div>
      </div>

      <!-- Card Horario -->
      <div class="col-md-6 col-lg-3">
        <div class="card bg-dark border-warning h-100 text-center">
          <div class="card-body">
            <i class="bi bi-clock-fill display-4 mb-3" style="color: #D4B68A;"></i>
            <h5 class="card-title" style="color: #D4B68A;">Horario</h5>
            <p class="card-text fw-bold text-light">
              19:30hs - 23:00hs<br>
              <small style="color: #999;">Lunes a Domingo</small>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
// Función para scroll suave al menú
function scrollToMenu() {
  document.getElementById('menu-section').scrollIntoView({ behavior: 'smooth' });
}

// Manejo de formularios de agregar al carrito
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
/* Barra de redes sociales */
.social-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 45px;
  height: 45px;
  border-radius: 50%;
  background-color: #000;
  color: #fff;
  font-size: 1.5rem;
  transition: all 0.3s ease;
  text-decoration: none;
}

.social-icon:hover {
  transform: translateY(-5px) scale(1.1);
  background-color: #fff;
  color: #000;
  box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

/* Hero badges */
.info-badge {
  background-color: rgba(212, 182, 138, 0.2);
  border: 2px solid #D4B68A;
  padding: 10px 20px;
  border-radius: 50px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
}

/* Animación del logo */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

/* Ofertas */
.oferta-card {
  position: relative;
}

.oferta-badge {
  position: absolute;
  top: -10px;
  right: 20px;
  background: linear-gradient(135deg, #ff6b6b, #ff4757);
  color: white;
  padding: 8px 16px;
  border-radius: 25px;
  font-weight: bold;
  font-size: 0.9rem;
  z-index: 10;
  box-shadow: 0 4px 15px rgba(255,75,87,0.4);
  animation: pulse 2s infinite;
}

.price-container {
  display: flex;
  flex-direction: column;
  gap: 5px;
  align-items: center;
}

.old-price {
  text-decoration: line-through;
  color: #999;
  font-size: 1.2rem;
}

.new-price {
  color: #D4B68A;
  font-size: 2rem;
  font-weight: bold;
}

/* Cartas de platos */
.plato-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.plato-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 10px 30px rgba(212, 182, 138, 0.4);
}

.pulse-badge {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
    transform: scale(1);
  }
  50% {
    opacity: 0.8;
    transform: scale(1.05);
  }
}

/* Cards generales */
.card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(212, 182, 138, 0.3);
}
</style>

<script>
function enviarUbicacion() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        const mensaje = `Hola! Quiero hacer un pedido. Mi ubicación es:`;
        const url = `https://wa.me/542241517665?text=${encodeURIComponent(mensaje)}%0A${encodeURIComponent('https://maps.google.com/?q=' + lat + ',' + lng)}`;
        window.open(url, '_blank');
      },
      function(error) {
        alert('No se pudo obtener tu ubicación. Por favor, activa el GPS o comparte tu ubicación manualmente por WhatsApp.');
        window.open('https://wa.me/542241517665?text=' + encodeURIComponent('Hola! Quiero hacer un pedido.'), '_blank');
      }
    );
  } else {
    alert('Tu navegador no soporta geolocalización. Por favor, comparte tu ubicación manualmente por WhatsApp.');
    window.open('https://wa.me/542241517665?text=' + encodeURIComponent('Hola! Quiero hacer un pedido.'), '_blank');
  }
}
</script>

<?= $this->endSection() ?>
