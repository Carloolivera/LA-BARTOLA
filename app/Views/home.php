<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>La Bartola - Delivery</title>

  <!-- Preconnect para acelerar carga de recursos externos -->
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- CSS crítico inline primero, luego externos con preload -->
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">

  <!-- Fallback para navegadores sin JS -->
  <noscript>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  </noscript>

  <!-- Estilos externos -->
  <link rel="stylesheet" href="<?= base_url('assets/css/home.css') ?>">
</head>
<body>

<!-- Header Fijo -->
<header class="fixed-header">
  <!-- Redes Sociales - Solo Iconos -->
  <div class="social-icons">
    <a href="https://instagram.com/labartolaok" target="_blank" aria-label="Instagram">
      <i class="bi bi-instagram"></i>
    </a>
    <a href="https://wa.me/542241517665" target="_blank" aria-label="WhatsApp">
      <i class="bi bi-whatsapp"></i>
    </a>
    <a href="https://facebook.com/labartolaok" target="_blank" aria-label="Facebook">
      <i class="bi bi-facebook"></i>
    </a>
  </div>

  <!-- Logo Circular y Título -->
  <div class="header-brand">
    <img src="<?= base_url('assets/images/logo.png') ?>" alt="La Bartola" class="header-logo" id="adminLogo" data-caja-chica-url="<?= site_url('admin/caja-chica') ?>">
    <h1>La Bartola</h1>
  </div>

  <!-- Información del Local -->
  <div class="info-section">
    <a href="https://www.google.com/maps/search/?api=1&query=Jorge+Newbery+356,+Chascomus,+Argentina" target="_blank" class="info-item info-item-link">
      <i class="bi bi-geo-alt-fill"></i>
      <span>Jorge Newbery 356, Chascomús</span>
    </a>
    <div class="info-item">
      <i class="bi bi-clock-fill"></i>
      <span>19:30 - 23:00 hs</span>
    </div>
    <div class="info-item">
      <i class="bi bi-bicycle"></i>
      <span>19:30 - 23:00 hs (Delivery)</span>
    </div>
    <div class="info-item">
      <i class="bi bi-credit-card-fill"></i>
      <span>Transferencia y Efectivo</span>
    </div>
  </div>

  <!-- Frase Motivacional -->
  <p class="header-tagline">"Todos los mejores platos que te puedas imaginar, adentro de una empanada"</p>
</header>

<!-- Buscador -->
<div class="search-container">
  <div class="search-box">
    <i class="bi bi-search search-icon"></i>
    <input type="text" id="searchInput" placeholder="Ingresá lo que estás buscando...">
    <i class="bi bi-x-circle-fill clear-icon" id="clearSearch"></i>
  </div>
</div>

<!-- Menú por Categorías -->
<div class="menu-container">
  <?php
  // Organizar platos por categoría
  $categorias = [
    'Bebidas' => [],
    'Empanadas' => [],
    'Pizzas' => [],
    'Tartas' => [],
    'Postres' => []
  ];

  if (!empty($platos)) {
    foreach ($platos as $plato) {
      $cat = $plato['categoria'] ?? 'Otros';
      if (isset($categorias[$cat])) {
        $categorias[$cat][] = $plato;
      }
    }
  }

  foreach ($categorias as $nombreCategoria => $platosCategoria):
    if (empty($platosCategoria)) continue;
  ?>

  <div class="category-section">
    <div class="category-header" onclick="toggleCategory(this)">
      <h2><?= esc($nombreCategoria) ?></h2>
      <i class="bi bi-chevron-up"></i>
    </div>

    <div class="category-content">
      <?php foreach ($platosCategoria as $plato): ?>
        <div class="plato-item" data-name="<?= strtolower(esc($plato['nombre'])) ?>" data-desc="<?= strtolower(esc($plato['descripcion'])) ?>">
          <div class="plato-image">
            <?php if (!empty($plato['imagen'])): ?>
              <?php
              // Detectar si es URL externa o archivo local
              $imagenUrl = (strpos($plato['imagen'], 'http') === 0)
                ? $plato['imagen']
                : base_url('assets/images/platos/' . $plato['imagen']);
              ?>
              <img src="<?= $imagenUrl ?>"
                   alt="<?= esc($plato['nombre']) ?>"
                   loading="lazy"
                   style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
            <?php else: ?>
              <i class="bi bi-image"></i>
            <?php endif; ?>
          </div>

          <div class="plato-info">
            <div class="plato-name"><?= esc($plato['nombre']) ?></div>
            <div class="plato-description"><?= esc($plato['descripcion']) ?></div>
            <div class="plato-price">$<?= number_format($plato['precio'], 0, ',', '.') ?></div>
          </div>

          <div class="add-btn" id="add-btn-<?= $plato['id'] ?>" onclick="addToCart(<?= $plato['id'] ?>, '<?= addslashes(esc($plato['nombre'])) ?>', <?= $plato['precio'] ?>)">
            +
          </div>

          <div class="quantity-controls" id="controls-<?= $plato['id'] ?>" data-plato-id="<?= $plato['id'] ?>">
            <div class="quantity-btn" onclick="changeQuantity(<?= $plato['id'] ?>, -1)">-</div>
            <div class="quantity-display" id="qty-<?= $plato['id'] ?>">0</div>
            <div class="quantity-btn" onclick="changeQuantity(<?= $plato['id'] ?>, 1)">+</div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <?php endforeach; ?>

  <?php if (empty($platos)): ?>
    <div class="empty-state">
      <i class="bi bi-inbox"></i>
      <p>No hay platos disponibles en este momento</p>
    </div>
  <?php endif; ?>
</div>

<!-- Botón Flotante del Carrito -->
<div class="cart-float" onclick="goToCart()" id="cartFloat" style="display: none;" data-carrito-url="<?= site_url('carrito') ?>" data-agregar-url="<?= site_url('carrito/agregar') ?>">
  <i class="bi bi-cart3 cart-icon"></i>
  <span>Ver tu pedido</span>
  <div class="cart-badge" id="cartCount">0</div>
  <span class="cart-total" id="cartTotal">$0</span>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/home.js') ?>"></script>
<script>
  // Inicializar carrito con datos del servidor
  const carritoServidor = <?= json_encode($carrito ?? []) ?>;
  initCarrito(carritoServidor);
</script>

</body>
</html>
