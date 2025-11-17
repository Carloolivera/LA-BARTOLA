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

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f5f5;
      padding-bottom: 100px;
      overflow-x: hidden;
    }

    /* Header Fijo */
    .fixed-header {
      background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
      padding: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    /* Redes Sociales - Solo Iconos */
    .social-icons {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 15px;
    }

    .social-icons a {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #D4B68A;
      color: #000;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      text-decoration: none;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .social-icons a:active {
      transform: scale(0.95);
    }

    /* Logo Circular Fijo */
    .header-brand {
      text-align: center;
      margin-bottom: 15px;
    }

    .header-logo {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #D4B68A;
      background: #000;
      padding: 5px;
    }

    .header-brand h1 {
      color: #D4B68A;
      font-size: 1.8rem;
      font-weight: 700;
      margin-top: 10px;
      margin-bottom: 0;
    }

    /* Información del Local */
    .info-section {
      background-color: rgba(212, 182, 138, 0.1);
      border-radius: 10px;
      padding: 12px;
      margin-bottom: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .info-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 0;
      color: #fff;
      font-size: 0.9rem;
      justify-content: center;
    }

    .info-item i {
      color: #D4B68A;
      font-size: 1.2rem;
      min-width: 25px;
    }

    .info-item-link {
      text-decoration: none;
      color: #fff;
      transition: all 0.2s;
      cursor: pointer;
    }

    .info-item-link:hover {
      background-color: rgba(212, 182, 138, 0.2);
      border-radius: 8px;
    }

    .info-item-link:active {
      transform: scale(0.98);
    }

    /* Frase Motivacional */
    .header-tagline {
      text-align: center;
      color: #D4B68A;
      font-family: 'Georgia', 'Times New Roman', serif;
      font-size: 0.9rem;
      margin: 15px 0 0 0;
      padding: 0 20px;
      font-weight: 400;
      line-height: 1.5;
      letter-spacing: 0.3px;
    }

    /* Buscador */
    .search-container {
      padding: 15px;
      background-color: #fff;
      border-bottom: 2px solid #e0e0e0;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .search-box {
      position: relative;
    }

    .search-box input {
      width: 100%;
      padding: 12px 45px 12px 45px;
      border: 2px solid #D4B68A;
      border-radius: 25px;
      font-size: 1rem;
      outline: none;
      transition: box-shadow 0.2s;
    }

    .search-box input:focus {
      box-shadow: 0 0 0 3px rgba(212, 182, 138, 0.2);
    }

    .search-box .search-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #D4B68A;
      font-size: 1.2rem;
    }

    .search-box .clear-icon {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
      font-size: 1.2rem;
      cursor: pointer;
      display: none;
    }

    /* Menú por Categorías */
    .menu-container {
      padding: 15px;
    }

    .category-section {
      margin-bottom: 20px;
      background-color: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .category-header {
      background: linear-gradient(135deg, #D4B68A 0%, #c9a770 100%);
      padding: 15px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      user-select: none;
    }

    .category-header h2 {
      color: #000;
      font-size: 1.3rem;
      font-weight: 600;
      margin: 0;
    }

    .category-header i {
      font-size: 1.5rem;
      color: #000;
      transition: transform 0.3s;
    }

    .category-header.collapsed i {
      transform: rotate(180deg);
    }

    .category-content {
      max-height: 2000px;
      overflow: hidden;
      transition: max-height 0.3s ease;
    }

    .category-content.collapsed {
      max-height: 0;
    }

    /* Item de Plato */
    .plato-item {
      display: flex;
      padding: 12px;
      border-bottom: 1px solid #f0f0f0;
      align-items: center;
      gap: 12px;
      transition: background-color 0.2s;
    }

    .plato-item:last-child {
      border-bottom: none;
    }

    .plato-image {
      width: 70px;
      height: 70px;
      border-radius: 10px;
      object-fit: cover;
      background-color: #e0e0e0;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #999;
      font-size: 2rem;
    }

    .plato-info {
      flex-grow: 1;
      min-width: 0;
    }

    .plato-name {
      font-weight: 600;
      font-size: 1rem;
      color: #333;
      margin-bottom: 4px;
      line-height: 1.2;
    }

    .plato-description {
      font-size: 0.85rem;
      color: #666;
      margin-bottom: 6px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .plato-price {
      font-size: 1.1rem;
      font-weight: 700;
      color: #D4B68A;
    }

    /* Controles de Cantidad */
    .quantity-controls {
      display: none;
      align-items: center;
      gap: 8px;
      flex-shrink: 0;
    }

    .quantity-controls.active {
      display: flex;
    }

    .quantity-btn {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      border: 2px solid #D4B68A;
      background-color: #fff;
      color: #D4B68A;
      font-size: 1.3rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      user-select: none;
      transition: all 0.2s;
      flex-shrink: 0;
    }

    .quantity-btn:active {
      transform: scale(0.9);
      background-color: #D4B68A;
      color: #fff;
    }

    .add-btn {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      border: 2px solid #D4B68A;
      background-color: #D4B68A;
      color: #fff;
      font-size: 1.3rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      user-select: none;
      transition: all 0.2s;
      flex-shrink: 0;
      font-weight: 700;
    }

    .add-btn:active {
      transform: scale(0.9);
    }

    .add-btn.hidden {
      display: none;
    }

    .quantity-display {
      font-size: 1.2rem;
      font-weight: 600;
      min-width: 30px;
      text-align: center;
    }

    /* Botón Flotante del Carrito */
    .cart-float {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(135deg, #D4B68A 0%, #c9a770 100%);
      color: #000;
      padding: 15px 30px;
      border-radius: 50px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 600;
      font-size: 1.1rem;
      cursor: pointer;
      user-select: none;
      transition: transform 0.2s, box-shadow 0.2s;
      z-index: 1000;
      min-width: 280px;
      justify-content: center;
    }

    .cart-float:active {
      transform: translateX(-50%) scale(0.97);
    }

    .cart-float .cart-icon {
      font-size: 1.5rem;
    }

    .cart-float .cart-total {
      font-size: 1.2rem;
      font-weight: 700;
    }

    .cart-badge {
      background-color: #000;
      color: #fff;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      font-weight: 700;
    }

    /* Animaciones */
    @keyframes slideIn {
      from {
        transform: translateY(100px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .plato-item {
      animation: slideIn 0.3s ease;
    }

    /* Estado vacío */
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #999;
    }

    .empty-state i {
      font-size: 4rem;
      margin-bottom: 15px;
    }

    /* Responsive */
    @media (min-width: 768px) {
      body {
        max-width: 600px;
        margin: 0 auto;
      }
    }
  </style>
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
    <img src="<?= base_url('assets/images/logo.png') ?>" alt="La Bartola" class="header-logo" id="adminLogo">
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
              <img src="<?= base_url('assets/images/platos/' . $plato['imagen']) ?>"
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
<div class="cart-float" onclick="goToCart()" id="cartFloat" style="display: none;">
  <i class="bi bi-cart3 cart-icon"></i>
  <span>Ver tu pedido</span>
  <div class="cart-badge" id="cartCount">0</div>
  <span class="cart-total" id="cartTotal">$0</span>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Carrito en memoria
  let cart = {};

  // Toggle de categorías
  function toggleCategory(header) {
    const content = header.nextElementSibling;
    header.classList.toggle('collapsed');
    content.classList.toggle('collapsed');
  }

  // Agregar al carrito
  function addToCart(platoId, platoNombre, platoPrecio) {
    const addBtn = document.getElementById(`add-btn-${platoId}`);
    const controls = document.getElementById(`controls-${platoId}`);

    // Ocultar botón de agregar y mostrar controles
    addBtn.classList.add('hidden');
    controls.classList.add('active');

    // Inicializar en carrito si no existe
    if (!cart[platoId]) {
      cart[platoId] = {
        nombre: platoNombre,
        precio: platoPrecio,
        cantidad: 0
      };
    }

    // Incrementar cantidad
    changeQuantity(platoId, 1);
  }

  // Cambiar cantidad
  function changeQuantity(platoId, delta) {
    if (!cart[platoId]) return;

    cart[platoId].cantidad += delta;

    if (cart[platoId].cantidad <= 0) {
      delete cart[platoId];

      // Mostrar botón de agregar y ocultar controles
      const addBtn = document.getElementById(`add-btn-${platoId}`);
      const controls = document.getElementById(`controls-${platoId}`);

      addBtn.classList.remove('hidden');
      controls.classList.remove('active');
    }

    updateCartDisplay();
  }

  // Actualizar visualización del carrito
  function updateCartDisplay() {
    const cartFloat = document.getElementById('cartFloat');
    const cartCount = document.getElementById('cartCount');
    const cartTotal = document.getElementById('cartTotal');

    let totalItems = 0;
    let totalPrice = 0;

    Object.keys(cart).forEach(platoId => {
      const item = cart[platoId];
      totalItems += item.cantidad;
      totalPrice += item.precio * item.cantidad;

      const qtyDisplay = document.getElementById(`qty-${platoId}`);
      if (qtyDisplay) {
        qtyDisplay.textContent = item.cantidad;
      }
    });

    if (totalItems > 0) {
      cartFloat.style.display = 'flex';
      cartCount.textContent = totalItems;
      cartTotal.textContent = '$' + totalPrice.toLocaleString('es-AR');
    } else {
      cartFloat.style.display = 'none';
    }
  }

  // Ir al carrito
  async function goToCart() {
    // Mostrar loading
    const cartFloat = document.getElementById('cartFloat');
    cartFloat.style.opacity = '0.5';
    cartFloat.style.pointerEvents = 'none';

    try {
      // Agregar todos los productos al carrito del servidor
      for (const platoId in cart) {
        const item = cart[platoId];

        const formData = new FormData();
        formData.append('plato_id', platoId);
        formData.append('cantidad', item.cantidad);
        formData.append('notas', '');

        const response = await fetch('<?= site_url('carrito/agregar') ?>', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();
        if (!data.success) {
          console.error(`Error al agregar ${item.nombre}:`, data.message);
        }
      }

      // Redirigir al carrito
      window.location.href = '<?= site_url('carrito') ?>';
    } catch (error) {
      console.error('Error al sincronizar carrito:', error);
      // Simplemente recargar la página para que el usuario pueda intentar nuevamente
      cartFloat.style.opacity = '1';
      cartFloat.style.pointerEvents = 'auto';
    }
  }

  // Buscador
  const searchInput = document.getElementById('searchInput');
  const clearSearch = document.getElementById('clearSearch');

  searchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();

    if (searchTerm) {
      clearSearch.style.display = 'block';
    } else {
      clearSearch.style.display = 'none';
    }

    const platoItems = document.querySelectorAll('.plato-item');
    let hasResults = false;

    // Dividir el término de búsqueda en palabras individuales
    const searchWords = searchTerm.split(/\s+/).filter(word => word.length > 0);

    platoItems.forEach(item => {
      const name = item.getAttribute('data-name');
      const desc = item.getAttribute('data-desc');
      const fullText = `${name} ${desc}`;

      // Verificar si todas las palabras de búsqueda están presentes (en cualquier orden)
      const matchesAll = searchWords.every(word => fullText.includes(word));

      if (matchesAll) {
        item.style.display = 'flex';
        hasResults = true;
      } else {
        item.style.display = 'none';
      }
    });

    // Mostrar/ocultar categorías vacías
    document.querySelectorAll('.category-section').forEach(section => {
      const visibleItems = section.querySelectorAll('.plato-item[style="display: flex;"]');
      if (visibleItems.length === 0 && searchTerm) {
        section.style.display = 'none';
      } else {
        section.style.display = 'block';
      }
    });
  });

  clearSearch.addEventListener('click', function() {
    searchInput.value = '';
    clearSearch.style.display = 'none';

    document.querySelectorAll('.plato-item').forEach(item => {
      item.style.display = 'flex';
    });

    document.querySelectorAll('.category-section').forEach(section => {
      section.style.display = 'block';
    });
  });

  // Acceso admin discreto (5 clicks en el logo redirige a caja chica)
  let adminClicks = 0;
  let adminClickTimer = null;

  document.getElementById('adminLogo').addEventListener('click', function() {
    adminClicks++;

    if (adminClicks === 5) {
      // Redirigir a caja chica (requiere login de admin)
      window.location.href = '<?= site_url('admin/caja-chica') ?>';
      adminClicks = 0;
      clearTimeout(adminClickTimer);
    } else {
      // Resetear contador después de 2 segundos sin clicks
      clearTimeout(adminClickTimer);
      adminClickTimer = setTimeout(() => {
        adminClicks = 0;
      }, 2000);
    }
  });

  // Cargar carrito de la sesión al iniciar
  document.addEventListener('DOMContentLoaded', function() {
    // Restaurar carrito desde el servidor
    const carritoServidor = <?= json_encode($carrito ?? []) ?>;

    if (Object.keys(carritoServidor).length > 0) {
      // Convertir el carrito del servidor al formato local
      Object.keys(carritoServidor).forEach(platoId => {
        const item = carritoServidor[platoId];
        cart[platoId] = {
          nombre: item.nombre,
          precio: item.precio,
          cantidad: item.cantidad
        };

        // Actualizar la UI para mostrar los controles
        const addBtn = document.getElementById(`add-btn-${platoId}`);
        const controls = document.getElementById(`controls-${platoId}`);
        const qtyDisplay = document.getElementById(`qty-${platoId}`);

        if (addBtn && controls && qtyDisplay) {
          addBtn.classList.add('hidden');
          controls.classList.add('active');
          qtyDisplay.textContent = item.cantidad;
        }
      });
    }

    updateCartDisplay();
  });
</script>

</body>
</html>
