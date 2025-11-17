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
    .notification-bell {
      position: relative;
      cursor: pointer;
    }
    .notification-badge {
      position: absolute;
      top: -5px;
      right: -8px;
      background-color: #dc3545;
      color: white;
      border-radius: 50%;
      padding: 2px 6px;
      font-size: 0.65rem;
      font-weight: bold;
    }
    .notification-dropdown {
      position: absolute;
      right: 0;
      top: 100%;
      margin-top: 10px;
      background-color: #1a1a1a;
      border: 1px solid #d4af37;
      border-radius: 8px;
      width: 360px;
      max-height: 500px;
      overflow-y: auto;
      z-index: 1000;
      box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }
    .notification-item {
      padding: 12px 15px;
      border-bottom: 1px solid #333;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    .notification-item:hover {
      background-color: #2a2a2a;
    }
    .notification-item.unread {
      background-color: rgba(212, 175, 55, 0.05);
    }
    .notification-header {
      padding: 12px 15px;
      border-bottom: 1px solid #d4af37;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .notification-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #d4af37;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #000;
      margin-right: 12px;
      flex-shrink: 0;
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="<?= site_url('/') ?>" id="logo-link">
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
              <li class="nav-item position-relative">
                <a class="nav-link notification-bell" id="notificationBell" onclick="toggleNotifications()">
                  <i class="bi bi-bell-fill"></i>
                  <span class="notification-badge d-none" id="notificationCount">0</span>
                </a>
                <div class="notification-dropdown d-none" id="notificationDropdown">
                  <div class="notification-header">
                    <h6 class="mb-0 text-warning">Notificaciones</h6>
                    <button class="btn btn-sm btn-link text-beige p-0" onclick="marcarTodasLeidas()">
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      actualizarContadorCarrito();
      <?php if (auth()->loggedIn()): ?>
      // iniciarNotificaciones(); // Temporalmente deshabilitado
      <?php endif; ?>
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

    <?php if (auth()->loggedIn()): ?>
    /* Sistema de Notificaciones - TEMPORALMENTE DESHABILITADO
    // Sistema de Notificaciones en Tiempo Real
    let notificationEventSource = null;

    function iniciarNotificaciones() {
      // Cargar notificaciones iniciales
      cargarNotificaciones();

      // Iniciar conexión SSE para actualizaciones en tiempo real
      if (typeof(EventSource) !== "undefined") {
        notificationEventSource = new EventSource('<?= site_url('notificaciones/stream') ?>');

        notificationEventSource.onmessage = function(event) {
          const data = JSON.parse(event.data);

          // Actualizar contador
          actualizarContadorNotificaciones(data.no_leidas);

          // Recargar lista de notificaciones
          cargarNotificaciones();

          // Mostrar notificación del navegador si está permitido
          if (data.notificaciones.length > 0 && Notification.permission === "granted") {
            const ultimaNotif = data.notificaciones[0];
            new Notification(ultimaNotif.titulo, {
              body: ultimaNotif.mensaje,
              icon: '<?= base_url('assets/images/logo.png') ?>',
              tag: 'notif-' + ultimaNotif.id
            });
          }
        };

        notificationEventSource.onerror = function() {
          console.error('Error en la conexión SSE. Reintentando en 5 segundos...');
          notificationEventSource.close();
          setTimeout(iniciarNotificaciones, 5000);
        };
      }

      // Solicitar permisos para notificaciones del navegador
      if ("Notification" in window && Notification.permission === "default") {
        Notification.requestPermission();
      }
    }

    function cargarNotificaciones() {
      fetch('<?= site_url('notificaciones/obtener') ?>')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            actualizarContadorNotificaciones(data.no_leidas);
            mostrarNotificaciones(data.notificaciones);
          }
        })
        .catch(error => console.error('Error:', error));
    }

    function actualizarContadorNotificaciones(count) {
      const badge = document.getElementById('notificationCount');
      if (badge) {
        if (count > 0) {
          badge.textContent = count > 99 ? '99+' : count;
          badge.classList.remove('d-none');
        } else {
          badge.classList.add('d-none');
        }
      }
    }

    function mostrarNotificaciones(notificaciones) {
      const list = document.getElementById('notificationList');
      if (!list) return;

      if (notificaciones.length === 0) {
        list.innerHTML = '<div class="text-center text-muted p-3"><i class="bi bi-inbox"></i> No hay notificaciones</div>';
        return;
      }

      let html = '';
      notificaciones.forEach(notif => {
        const fecha = new Date(notif.created_at);
        const tiempoRelativo = calcularTiempoRelativo(fecha);
        const unreadClass = notif.leida == 0 ? 'unread' : '';
        const icono = notif.icono || 'bi-info-circle';

        html += `
          <div class="notification-item ${unreadClass}" onclick="clickNotificacion(${notif.id}, '${notif.url || ''}')">
            <div class="d-flex">
              <div class="notification-icon">
                <i class="bi ${icono}"></i>
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-1 text-beige">${notif.titulo}</h6>
                <p class="mb-1 small text-muted">${notif.mensaje}</p>
                <small class="text-warning">${tiempoRelativo}</small>
              </div>
            </div>
          </div>
        `;
      });

      list.innerHTML = html;
    }

    function toggleNotifications() {
      const dropdown = document.getElementById('notificationDropdown');
      dropdown.classList.toggle('d-none');
    }

    function clickNotificacion(id, url) {
      // Marcar como leída
      fetch('<?= site_url('notificaciones/marcarLeida') ?>/' + id, {
        method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          cargarNotificaciones();
        }
      });

      // Redirigir si tiene URL
      if (url) {
        window.location.href = url;
      }
    }

    function marcarTodasLeidas() {
      fetch('<?= site_url('notificaciones/marcarTodasLeidas') ?>', {
        method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          cargarNotificaciones();
        }
      });
    }

    function calcularTiempoRelativo(fecha) {
      const ahora = new Date();
      const diff = Math.floor((ahora - fecha) / 1000);

      if (diff < 60) return 'Hace un momento';
      if (diff < 3600) return `Hace ${Math.floor(diff / 60)} min`;
      if (diff < 86400) return `Hace ${Math.floor(diff / 3600)} h`;
      if (diff < 604800) return `Hace ${Math.floor(diff / 86400)} días`;
      return fecha.toLocaleDateString();
    }

    // Cerrar dropdown al hacer click fuera
    document.addEventListener('click', function(event) {
      const bell = document.getElementById('notificationBell');
      const dropdown = document.getElementById('notificationDropdown');

      if (bell && dropdown && !bell.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.add('d-none');
      }
    });

    // Cerrar conexión SSE al salir de la página
    window.addEventListener('beforeunload', function() {
      if (notificationEventSource) {
        notificationEventSource.close();
      }
    });
    FIN DEL BLOQUE COMENTADO */
    <?php endif; ?>

    // Script para 5 clicks en el logo redirige a caja chica
    (function() {
      let clickCount = 0;
      let clickTimer = null;
      const logoLink = document.getElementById('logo-link');

      if (logoLink) {
        logoLink.addEventListener('click', function(e) {
          e.preventDefault();
          clickCount++;

          if (clickCount >= 5) {
            window.location.href = '<?= site_url('admin/caja-chica') ?>';
            clickCount = 0;
            clearTimeout(clickTimer);
            return false;
          }

          clearTimeout(clickTimer);
          clickTimer = setTimeout(function() {
            if (clickCount < 5) {
              window.location.href = logoLink.href;
            }
            clickCount = 0;
          }, 800);
        });
      }
    })();
  </script>
</body>
</html>