<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Mi Carrito - La Bartola</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f5f5;
      padding-bottom: 80px;
      overflow-x: hidden;
    }

    /* Header */
    .cart-header {
      background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
      padding: 20px;
      text-align: center;
    }

    .header-logo {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #D4B68A;
      background: #000;
      padding: 5px;
      margin-bottom: 10px;
    }

    .header-title {
      color: #D4B68A;
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0;
      cursor: pointer;
      transition: all 0.2s;
      text-decoration: none;
      display: inline-block;
    }

    .header-title:hover {
      color: #e5c79b;
      transform: scale(1.02);
    }

    .header-title:active {
      transform: scale(0.98);
    }

    /* Items del Carrito */
    .cart-items {
      padding: 15px;
      padding-bottom: 200px; /* Espacio para los botones fijos */
    }

    .cart-item {
      background: #fff;
      border-radius: 12px;
      padding: 15px;
      margin-bottom: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      position: relative;
    }

    .item-header {
      display: flex;
      justify-content: space-between;
      align-items: start;
      margin-bottom: 12px;
    }

    .item-name {
      font-weight: 600;
      font-size: 1.05rem;
      color: #333;
      flex: 1;
      padding-right: 10px;
    }

    .item-price {
      font-size: 1.1rem;
      font-weight: 700;
      color: #D4B68A;
    }

    .item-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .quantity-controls-cart {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .qty-btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 2px solid #D4B68A;
      background-color: #fff;
      color: #D4B68A;
      font-size: 1.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      user-select: none;
      transition: all 0.2s;
      font-weight: 700;
    }

    .qty-btn:active {
      transform: scale(0.9);
      background-color: #D4B68A;
      color: #fff;
    }

    .qty-display-cart {
      font-size: 1.2rem;
      font-weight: 600;
      min-width: 35px;
      text-align: center;
    }

    .item-subtotal {
      font-size: 1rem;
      font-weight: 600;
      color: #666;
    }

    .delete-btn {
      background: none;
      border: none;
      color: #dc3545;
      font-size: 1.3rem;
      cursor: pointer;
      padding: 5px;
      margin-left: 10px;
    }

    /* Resumen Total */
    .cart-summary {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: #fff;
      border-top: 3px solid #D4B68A;
      padding: 15px;
      box-shadow: 0 -4px 15px rgba(0,0,0,0.1);
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }

    .total-label {
      font-size: 1.2rem;
      font-weight: 600;
      color: #333;
    }

    .total-value {
      font-size: 1.4rem;
      font-weight: 700;
      color: #D4B68A;
    }

    .btn-update {
      background: #fff;
      color: #D4B68A;
      border: 2px solid #D4B68A;
      padding: 10px 20px;
      border-radius: 25px;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 8px;
      justify-content: center;
    }

    .btn-update:active {
      transform: scale(0.95);
      background-color: #D4B68A;
      color: #fff;
    }

    .btn-update i {
      font-size: 1.1rem;
    }

    .total-update-row {
      display: grid;
      grid-template-columns: 1fr auto;
      grid-template-rows: auto auto;
      gap: 12px;
      margin-bottom: 15px;
    }

    .total-info {
      grid-column: 1 / 2;
      grid-row: 1 / 2;
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .btn-update {
      grid-column: 2 / 3;
      grid-row: 1 / 2;
      align-self: center;
    }

    .btn-confirm-grid {
      grid-column: 1 / 3;
      grid-row: 2 / 3;
    }

    .btn-back-grid {
      grid-column: 1 / 3;
      grid-row: 3 / 4;
    }

    .btn-confirm, .btn-back {
      width: 100%;
      padding: 15px;
      border: none;
      border-radius: 50px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s;
      margin-bottom: 8px;
    }

    .btn-confirm {
      background: linear-gradient(135deg, #D4B68A 0%, #c9a770 100%);
      color: #000;
    }

    .btn-back {
      background: #fff;
      color: #D4B68A;
      border: 2px solid #D4B68A;
    }

    .btn-confirm:active, .btn-back:active {
      transform: scale(0.98);
    }

    /* Modal de confirmación de eliminación */
    .delete-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.5);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }

    .delete-modal.active {
      display: flex;
    }

    .delete-modal-content {
      background: #fff;
      border-radius: 16px;
      padding: 25px;
      max-width: 320px;
      width: 90%;
      text-align: center;
    }

    .delete-modal-icon {
      font-size: 3rem;
      color: #dc3545;
      margin-bottom: 15px;
    }

    .delete-modal-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 10px;
      color: #333;
    }

    .delete-modal-text {
      color: #666;
      margin-bottom: 20px;
    }

    .delete-modal-buttons {
      display: flex;
      gap: 10px;
    }

    .delete-modal-btn {
      flex: 1;
      padding: 12px;
      border: none;
      border-radius: 25px;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .delete-modal-btn:active {
      transform: scale(0.95);
    }

    .delete-modal-btn.cancel {
      background: #e0e0e0;
      color: #666;
    }

    .delete-modal-btn.confirm {
      background: #dc3545;
      color: #fff;
    }

    /* Modal de formulario de pedido */
    .order-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.5);
      z-index: 9998;
      overflow-y: auto;
      padding: 20px;
    }

    .order-modal.active {
      display: block;
    }

    .order-modal-content {
      background: #fff;
      border-radius: 16px;
      padding: 25px;
      max-width: 500px;
      margin: 20px auto;
    }

    .order-modal-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .order-modal-logo {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #D4B68A;
      margin-bottom: 10px;
    }

    .order-modal-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: #D4B68A;
      margin: 0;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-label {
      display: block;
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
      font-size: 0.95rem;
    }

    .form-input {
      width: 100%;
      padding: 12px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      font-size: 1rem;
      transition: border-color 0.2s;
    }

    .form-input:focus {
      outline: none;
      border-color: #D4B68A;
    }

    textarea.form-input {
      resize: vertical;
      min-height: 80px;
    }

    .order-summary-box {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
    }

    .order-summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
      font-size: 0.95rem;
    }

    .order-summary-total {
      display: flex;
      justify-content: space-between;
      font-weight: 700;
      font-size: 1.2rem;
      color: #D4B68A;
      padding-top: 10px;
      border-top: 2px solid #e0e0e0;
      margin-top: 10px;
    }

    .btn-whatsapp {
      width: 100%;
      padding: 15px;
      background: #25D366;
      color: #fff;
      border: none;
      border-radius: 50px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 8px;
    }

    .btn-whatsapp:active {
      transform: scale(0.98);
    }

    /* Estado Vacío */
    .empty-cart {
      text-align: center;
      padding: 60px 20px;
    }

    .empty-cart i {
      font-size: 5rem;
      color: #ddd;
      margin-bottom: 20px;
    }

    .empty-cart h3 {
      color: #666;
      margin-bottom: 10px;
    }

    .empty-cart p {
      color: #999;
      margin-bottom: 25px;
    }

    /* Responsive */
    @media (min-width: 768px) {
      body {
        max-width: 600px;
        margin: 0 auto;
      }
    }

    /* Alerts */
    .alert-mobile {
      margin: 15px;
      border-radius: 12px;
      padding: 12px;
      font-size: 0.95rem;
    }

    /* Loading */
    .loading {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.5);
      z-index: 10000;
      align-items: center;
      justify-content: center;
    }

    .loading.active {
      display: flex;
    }

    .spinner {
      width: 50px;
      height: 50px;
      border: 5px solid #f3f3f3;
      border-top: 5px solid #D4B68A;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Sistema de Notificaciones */
    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      left: 20px;
      max-width: 400px;
      margin: 0 auto;
      padding: 15px 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      z-index: 10001;
      display: flex;
      align-items: center;
      gap: 12px;
      transform: translateY(-100px);
      opacity: 0;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .notification.show {
      transform: translateY(0);
      opacity: 1;
    }

    .notification.success {
      background: #d4edda;
      color: #155724;
      border-left: 4px solid #28a745;
    }

    .notification.error {
      background: #f8d7da;
      color: #721c24;
      border-left: 4px solid #dc3545;
    }

    .notification.warning {
      background: #fff3cd;
      color: #856404;
      border-left: 4px solid #ffc107;
    }

    .notification.info {
      background: #d1ecf1;
      color: #0c5460;
      border-left: 4px solid #17a2b8;
    }

    .notification i {
      font-size: 1.3rem;
    }
  </style>
</head>
<body>

<!-- Loading Spinner -->
<div class="loading" id="loading">
  <div class="spinner"></div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="delete-modal" id="deleteModal">
  <div class="delete-modal-content">
    <div class="delete-modal-icon">
      <i class="bi bi-trash"></i>
    </div>
    <div class="delete-modal-title">¿Eliminar producto?</div>
    <div class="delete-modal-text" id="deleteModalText">¿Estás seguro de eliminar este producto?</div>
    <div class="delete-modal-buttons">
      <button class="delete-modal-btn cancel" onclick="closeDeleteModal()">Cancelar</button>
      <button class="delete-modal-btn confirm" onclick="confirmDelete()">Eliminar</button>
    </div>
  </div>
</div>

<!-- Modal de formulario de pedido -->
<div class="order-modal" id="orderModal">
  <div class="order-modal-content">
    <div class="order-modal-header">
      <img src="<?= base_url('assets/images/logo.png') ?>" alt="La Bartola" class="order-modal-logo">
      <h2 class="order-modal-title">Confirmar Pedido</h2>
    </div>

    <form id="orderForm" onsubmit="return false;">
      <div class="form-group">
        <label class="form-label">Nombre *</label>
        <input type="text" class="form-input" id="nombre" placeholder="Tu nombre" required>
      </div>

      <div class="form-group">
        <label class="form-label">Domicilio *</label>
        <input type="text" class="form-input" id="domicilio" placeholder="Tu dirección completa" required>
      </div>

      <div class="form-group">
        <label class="form-label">Entre Calles</label>
        <input type="text" class="form-input" id="entreCalles" placeholder="Entre qué calles (opcional)">
      </div>

      <div class="form-group">
        <label class="form-label">Comentarios Adicionales</label>
        <textarea class="form-input" id="comentarios" placeholder="Timbre, piso, apartamento, etc. (opcional)"></textarea>
      </div>

      <div class="order-summary-box">
        <div class="order-summary-item">
          <span>Subtotal:</span>
          <span id="modalSubtotal">$0</span>
        </div>
        <div class="order-summary-total">
          <span>Total:</span>
          <span id="modalTotal">$0</span>
        </div>
      </div>

      <button type="button" class="btn-whatsapp" onclick="enviarPorWhatsApp()">
        <i class="bi bi-whatsapp" style="font-size: 1.5rem;"></i>
        Enviar por WhatsApp
      </button>

      <button type="button" class="btn-back" onclick="closeOrderModal()">
        Cancelar
      </button>
    </form>
  </div>
</div>

<!-- Header -->
<header class="cart-header">
  <img src="<?= base_url('assets/images/logo.png') ?>" alt="La Bartola" class="header-logo">
  <a href="<?= site_url('/') ?>" class="header-title">La Bartola</a>
</header>

<!-- Alerts -->
<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-mobile">
    <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger alert-mobile">
    <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
  </div>
<?php endif; ?>

<!-- Carrito Vacío -->
<?php if (empty($carrito)): ?>
  <div class="empty-cart">
    <i class="bi bi-cart-x"></i>
    <h3>Tu carrito está vacío</h3>
    <p>Agrega productos desde el menú</p>
    <button onclick="window.location.href='<?= site_url('/') ?>'" class="btn-confirm">
      Ver Menú
    </button>
  </div>

<!-- Carrito con Productos -->
<?php else: ?>
  <div class="cart-items">
    <?php
    $total = 0;
    $db = \Config\Database::connect();
    foreach ($carrito as $id => $item):
      $subtotal = $item['precio'] * $item['cantidad'];
      $total += $subtotal;

      // Obtener información del stock del plato
      $plato = $db->table('platos')->where('id', $id)->get()->getRowArray();
      $stockMax = ($plato && $plato['stock_ilimitado'] == 0) ? $plato['stock'] : 99;
    ?>

      <div class="cart-item" id="item-<?= $id ?>">
        <div class="item-header">
          <div class="item-name"><?= esc($item['nombre']) ?></div>
          <div class="item-price">$<?= number_format($item['precio'], 0, ',', '.') ?></div>
        </div>

        <div class="item-controls">
          <div class="quantity-controls-cart">
            <div class="qty-btn" onclick="updateQuantity(<?= $id ?>, -1, <?= $stockMax ?>)">-</div>
            <div class="qty-display-cart" id="qty-<?= $id ?>"><?= $item['cantidad'] ?></div>
            <div class="qty-btn" onclick="updateQuantity(<?= $id ?>, 1, <?= $stockMax ?>)">+</div>
          </div>

          <div class="d-flex align-items-center">
            <div class="item-subtotal" id="subtotal-<?= $id ?>">
              $<?= number_format($subtotal, 0, ',', '.') ?>
            </div>
            <button class="delete-btn" onclick="showDeleteModal(<?= $id ?>, '<?= addslashes(esc($item['nombre'])) ?>')">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>

    <?php endforeach; ?>
  </div>

  <!-- Resumen y Botones -->
  <div class="cart-summary">
    <div class="total-update-row">
      <div class="total-info">
        <div class="total-label">Total:</div>
        <div class="total-value" id="totalGeneral">$<?= number_format($total, 0, ',', '.') ?></div>
      </div>

      <button onclick="actualizarCarrito()" class="btn-update">
        <i class="bi bi-arrow-clockwise"></i>
        Actualizar
      </button>

      <button onclick="showOrderModal()" class="btn-confirm btn-confirm-grid">
        <i class="bi bi-check-circle"></i> Confirmar tu Pedido
      </button>

      <button onclick="window.location.href='<?= site_url('/') ?>'" class="btn-back btn-back-grid">
        <i class="bi bi-arrow-left"></i> Volver al Menú
      </button>
    </div>
  </div>

<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Variables globales
  let deleteTargetId = null;
  const WHATSAPP_NUMBER = '542241517665'; // Número de La Bartola

  // Datos del carrito para WhatsApp
  const carritoItems = <?= json_encode($carrito ?? []) ?>;
  let carritoTotal = <?= $total ?? 0 ?>;

  // Objeto para rastrear cambios pendientes
  const cambiosPendientes = {};

  // Actualizar cantidad en la UI
  function updateQuantity(platoId, delta, maxStock) {
    // Leer la cantidad actual del DOM
    const qtyDisplay = document.getElementById(`qty-${platoId}`);
    if (!qtyDisplay) return;

    const currentQty = parseInt(qtyDisplay.textContent) || 0;
    const newQty = currentQty + delta;

    if (newQty <= 0) {
      showDeleteModal(platoId, 'este producto');
      return;
    }

    if (maxStock > 0 && newQty > maxStock) {
      showNotification('Stock máximo disponible: ' + maxStock, 'warning');
      return;
    }

    // Actualizar la UI inmediatamente
    qtyDisplay.textContent = newQty;

    // Guardar cambio pendiente
    cambiosPendientes[platoId] = newQty;

    // Actualizar el subtotal inmediatamente (solo visualmente, no el total)
    const item = carritoItems[platoId];
    if (item) {
      item.cantidad = newQty;
      const subtotal = item.precio * newQty;
      const subtotalDisplay = document.getElementById(`subtotal-${platoId}`);
      if (subtotalDisplay) {
        subtotalDisplay.textContent = '$' + subtotal.toLocaleString('es-AR');
      }
    }
  }

  // Función para actualizar el carrito en el servidor
  async function actualizarCarrito() {
    if (Object.keys(cambiosPendientes).length === 0) {
      showNotification('No hay cambios para actualizar', 'info');
      return;
    }

    showLoading();

    try {
      // Enviar todos los cambios pendientes al servidor
      for (const platoId in cambiosPendientes) {
        const cantidad = cambiosPendientes[platoId];
        const formData = new FormData();
        formData.append('plato_id', platoId);
        formData.append('cantidad', cantidad);

        const response = await fetch('<?= site_url('carrito/actualizar') ?>', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();
        if (!data.success) {
          hideLoading();
          showNotification(data.message || 'Error al actualizar cantidad', 'error');
          setTimeout(() => location.reload(), 2000);
          return;
        }
      }

      // Limpiar cambios pendientes
      Object.keys(cambiosPendientes).forEach(key => delete cambiosPendientes[key]);

      // Actualizar el total general después de sincronizar
      let nuevoTotal = 0;
      Object.keys(carritoItems).forEach(id => {
        nuevoTotal += carritoItems[id].precio * carritoItems[id].cantidad;
      });
      carritoTotal = nuevoTotal;
      document.getElementById('totalGeneral').textContent = '$' + nuevoTotal.toLocaleString('es-AR');
      document.getElementById('modalSubtotal').textContent = '$' + nuevoTotal.toLocaleString('es-AR');
      document.getElementById('modalTotal').textContent = '$' + nuevoTotal.toLocaleString('es-AR');

      hideLoading();
      showNotification('Carrito actualizado correctamente', 'success');
    } catch (error) {
      hideLoading();
      console.error('Error:', error);
      showNotification('Error al actualizar el carrito', 'error');
      setTimeout(() => location.reload(), 2000);
    }
  }

  // Mostrar modal de eliminación
  function showDeleteModal(platoId, platoNombre) {
    deleteTargetId = platoId;
    document.getElementById('deleteModalText').textContent = `¿Eliminar "${platoNombre}"?`;
    document.getElementById('deleteModal').classList.add('active');
  }

  // Cerrar modal de eliminación
  function closeDeleteModal() {
    deleteTargetId = null;
    document.getElementById('deleteModal').classList.remove('active');
  }

  // Confirmar eliminación
  function confirmDelete() {
    if (!deleteTargetId) return;

    closeDeleteModal();
    showLoading();

    // Eliminar de cambios pendientes si existe
    if (cambiosPendientes[deleteTargetId]) {
      delete cambiosPendientes[deleteTargetId];
    }

    // Eliminar del carrito en memoria
    if (carritoItems[deleteTargetId]) {
      delete carritoItems[deleteTargetId];
    }

    const formData = new FormData();
    formData.append('plato_id', deleteTargetId);

    fetch('<?= site_url('carrito/eliminar') ?>', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      hideLoading();
      if (data.success) {
        showNotification('Producto eliminado correctamente', 'success');
        setTimeout(() => location.reload(), 1000);
      } else {
        showNotification(data.message || 'Error al eliminar producto', 'error');
        setTimeout(() => location.reload(), 2000);
      }
    })
    .catch(error => {
      hideLoading();
      console.error('Error:', error);
      showNotification('Error al eliminar producto', 'error');
      setTimeout(() => location.reload(), 2000);
    });
  }

  // Mostrar modal de pedido
  function showOrderModal() {
    document.getElementById('modalSubtotal').textContent = '$' + carritoTotal.toLocaleString('es-AR');
    document.getElementById('modalTotal').textContent = '$' + carritoTotal.toLocaleString('es-AR');
    document.getElementById('orderModal').classList.add('active');
  }

  // Cerrar modal de pedido
  function closeOrderModal() {
    document.getElementById('orderModal').classList.remove('active');
  }

  // Enviar por WhatsApp
  async function enviarPorWhatsApp() {
    const nombre = document.getElementById('nombre').value.trim();
    const domicilio = document.getElementById('domicilio').value.trim();
    const entreCalles = document.getElementById('entreCalles').value.trim();
    const comentarios = document.getElementById('comentarios').value.trim();

    if (!nombre || !domicilio) {
      alert('Por favor completa tu nombre y domicilio');
      return;
    }

    showLoading();

    try {
      // Primero, guardar el pedido en la base de datos
      const formData = new FormData();
      formData.append('nombre_cliente', nombre);
      formData.append('tipo_entrega', 'delivery');
      formData.append('direccion', domicilio);
      formData.append('forma_pago', 'efectivo');

      // Agregar comentarios adicionales
      let notasCompletas = '';
      if (entreCalles) {
        notasCompletas += `Entre calles: ${entreCalles}. `;
      }
      if (comentarios) {
        notasCompletas += comentarios;
      }
      formData.append('notas', notasCompletas);

      const response = await fetch('<?= site_url('carrito/finalizar') ?>', {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      if (!data.success) {
        hideLoading();
        alert(data.message || 'Error al guardar el pedido');
        return;
      }

      hideLoading();

      // Construir el mensaje para WhatsApp
      let mensaje = `*🍽️ NUEVO PEDIDO - LA BARTOLA*\n\n`;
      mensaje += `👤 *Nombre:* ${nombre}\n`;
      mensaje += `📍 *Domicilio:* ${domicilio}\n`;
      if (entreCalles) {
        mensaje += `🗺️ *Entre calles:* ${entreCalles}\n`;
      }
      if (comentarios) {
        mensaje += `💬 *Comentarios:* ${comentarios}\n`;
      }
      mensaje += `\n*📋 DETALLE DEL PEDIDO:*\n`;
      mensaje += `━━━━━━━━━━━━━━━━\n`;

      // Agregar items del carrito
      Object.keys(carritoItems).forEach(platoId => {
        const item = carritoItems[platoId];
        const subtotal = item.precio * item.cantidad;
        mensaje += `\n🔸 *${item.nombre}*\n`;
        mensaje += `   Cantidad: ${item.cantidad}\n`;
        mensaje += `   Precio: $${item.precio.toLocaleString('es-AR')}\n`;
        mensaje += `   Subtotal: $${subtotal.toLocaleString('es-AR')}\n`;
      });

      mensaje += `\n━━━━━━━━━━━━━━━━\n`;
      mensaje += `*💰 TOTAL: $${carritoTotal.toLocaleString('es-AR')}*`;

      // Codificar el mensaje para URL
      const mensajeCodificado = encodeURIComponent(mensaje);

      // Crear la URL de WhatsApp
      const urlWhatsApp = `https://wa.me/${WHATSAPP_NUMBER}?text=${mensajeCodificado}`;

      // Abrir WhatsApp
      window.open(urlWhatsApp, '_blank');

      // Mostrar mensaje de éxito y redirigir
      alert('¡Pedido confirmado! Se abrió WhatsApp para enviar el mensaje.');

      setTimeout(() => {
        closeOrderModal();
        // Redirigir al historial de pedidos
        window.location.href = '<?= site_url('pedido') ?>';
      }, 1000);

    } catch (error) {
      hideLoading();
      console.error('Error:', error);
      alert('Error al procesar el pedido. Por favor intenta nuevamente.');
    }
  }

  function showLoading() {
    document.getElementById('loading').classList.add('active');
  }

  function hideLoading() {
    document.getElementById('loading').classList.remove('active');
  }

  // Sistema de notificaciones profesional
  let notificationTimeout = null;
  function showNotification(message, type = 'info') {
    // Remover notificación anterior si existe
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
      existingNotification.remove();
    }

    // Limpiar timeout anterior
    if (notificationTimeout) {
      clearTimeout(notificationTimeout);
    }

    // Crear nueva notificación
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;

    // Iconos según el tipo
    const icons = {
      success: 'bi-check-circle-fill',
      error: 'bi-exclamation-circle-fill',
      warning: 'bi-exclamation-triangle-fill',
      info: 'bi-info-circle-fill'
    };

    notification.innerHTML = `
      <i class="bi ${icons[type] || icons.info}"></i>
      <span>${message}</span>
    `;

    document.body.appendChild(notification);

    // Animar entrada
    setTimeout(() => notification.classList.add('show'), 10);

    // Auto-ocultar después de 3 segundos
    notificationTimeout = setTimeout(() => {
      notification.classList.remove('show');
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }
</script>

</body>
</html>
