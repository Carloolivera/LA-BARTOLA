<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>La Bartola | Casa de Comidas & Delivery</title>

  <!-- Preconexión a CDNs para reducir latencia -->
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Estilos externos -->
  <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
</head>

<body data-cart-count-url="<?= site_url('carrito/getCount') ?>">

  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="<?= site_url('/') ?>" id="logo-link" data-caja-chica-url="<?= site_url('admin/caja-chica') ?>">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo La Bartola" loading="lazy" width="40" height="40">
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
            <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/caja-chica') ?>">Caja Chica</a></li>
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
              <li class="nav-item position-relative">
                <a class="nav-link notification-bell" id="notificationBell" onclick="toggleNotifications()">
                  <i class="bi bi-bell-fill"></i>
                  <span class="notification-badge d-none" id="notificationCount">0</span>
                </a>
                <div class="notification-dropdown d-none" id="notificationDropdown">
                  <div class="notification-header">
                    <h6 class="mb-0 text-warning">Notificaciones</h6>
                    <button class="btn btn-sm btn-link text-beige p-0" onclick="marcarTodasLeídas()">
                      Marcar todas como leídas
                    </button>
                  </div>
                  <div id="notificationList">
                    <div class="text-center text-muted p-3">
                      <i class="bi bi-inbox"></i> No hay notificaciones
                    </div>
                  </div>
                </div>
              </li>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
  <script src="<?= base_url('assets/js/main.js') ?>" defer></script>
</body>
</html>