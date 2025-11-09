<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>La Bartola | Casa de Comidas & Delivery</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #000;
      color: #f5f5dc;
    }
    .navbar {
      background-color: #000;
      border-bottom: 1px solid #d4af37;
    }
    .navbar-brand {
      color: #f5f5dc !important;
      font-weight: 600;
      letter-spacing: 1px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .navbar-brand img {
      height: 40px;
      width: auto;
      object-fit: contain;
    }
    .nav-link {
      color: #f5f5dc !important;
      transition: color 0.2s;
    }
    .nav-link:hover {
      color: #d4af37 !important;
    }
    .nav-link.active {
      color: #d4af37 !important;
      font-weight: 600;
    }
    footer {
      background-color: #000;
      border-top: 1px solid #d4af37;
      color: #f5f5dc;
      padding: 2rem 0;
    }
    .text-beige { color: #f5f5dc; }
    .text-warning { color: #d4af37 !important; }
    .btn-warning {
      background-color: #d4af37;
      color: #000;
      border: none;
    }
    .btn-outline-warning {
      color: #d4af37;
      border-color: #d4af37;
    }
    .btn-outline-warning:hover {
      background-color: #d4af37;
      color: #000;
    }
    .badge-cart {
      background-color: #d4af37;
      color: #000;
      font-size: 0.7rem;
      position: relative;
      top: -8px;
      left: -5px;
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="<?= site_url('/') ?>">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo La Bartola">
        La Bartola
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <?php if (auth()->loggedIn() && auth()->user()->inGroup('admin')) : ?>
            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/menu') ?>">Gestión Menú</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/pedidos') ?>">Pedidos</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/inventario') ?>">📦 Inventario</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/caja-chica') ?>">💰 Caja Chica</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= site_url('usuario') ?>">Usuarios</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= site_url('logout') ?>">Logout</a></li>
          <?php elseif (auth()->loggedIn() && auth()->user()->inGroup('vendedor')) : ?>
            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/menu') ?>">Gestión Menú</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/pedidos') ?>">Pedidos</a></li>
            <li class="nav-item">
              <a class="nav-link" href="<?= site_url('carrito') ?>">
                <i class="bi bi-cart3"></i> Carrito
                <span class="badge badge-cart" id="cart-count">0</span>
              </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="<?= site_url('logout') ?>">Logout</a></li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= site_url('carrito') ?>">
                <i class="bi bi-cart3"></i> Carrito
                <span class="badge badge-cart" id="cart-count">0</span>
              </a>
            </li>
            <?php if (auth()->loggedIn()) : ?>
              <li class="nav-item"><a class="nav-link" href="<?= site_url('pedido') ?>">Mis Pedidos</a></li>
              <li class="nav-item"><a class="nav-link" href="<?= site_url('logout') ?>">Logout</a></li>
            <?php else : ?>
              <li class="nav-item"><a class="nav-link" href="<?= site_url('login') ?>">Login</a></li>
              <li class="nav-item"><a class="nav-link" href="<?= site_url('register') ?>">Registrarse</a></li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <main>
    <?= $this->renderSection('content') ?>
  </main>

  <footer class="text-center mt-5">
    <div class="container">
      <p class="mb-1">© <?= date('Y') ?> La Bartola | Casa de Comidas y Delivery</p>
      <p class="small text-beige">Buenos Aires, Argentina</p>
      <div class="mt-2">
        <a href="#" class="text-warning me-3"><i class="bi bi-instagram"></i></a>
        <a href="#" class="text-warning"><i class="bi bi-facebook"></i></a>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      actualizarContadorCarrito();
    });
    
    function actualizarContadorCarrito() {
      fetch('<?= site_url('carrito/getCount') ?>')
        .then(response => response.json())
        .then(data => {
          const cartCount = document.getElementById('cart-count');
          if (cartCount) {
            cartCount.textContent = data.cart_count;
          }
        })
        .catch(error => console.error('Error:', error));
    }
  </script>
</body>
</html>