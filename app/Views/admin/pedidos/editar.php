<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color: #000; min-height: 80vh;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 style="color: #D4B68A;">Editar Pedido #<?= $pedido['id'] ?></h1>
      <a href="<?= base_url('admin/pedidos') ?>" class="btn btn-outline-warning">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
    </div>

    <?php if (session()->has('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Items del Pedido -->
    <div class="card bg-dark text-light mb-4">
      <div class="card-header" style="background-color: #1a1a1a; border-bottom: 2px solid #D4B68A;">
        <h5 class="mb-0" style="color: #D4B68A;">
          <i class="bi bi-basket"></i> Items del Pedido
        </h5>
      </div>
      <div class="card-body">
        <?php if (!empty($items)): ?>
          <div class="table-responsive">
            <table class="table table-dark table-striped">
              <thead>
                <tr style="color: #D4B68A;">
                  <th>Plato</th>
                  <th>Cantidad</th>
                  <th>Precio Unit.</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $total = 0;
                foreach ($items as $item):
                  $total += $item['total'];
                ?>
                  <tr>
                    <td><?= esc($item['plato_nombre']) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>$<?= number_format($item['precio'], 0, ',', '.') ?></td>
                    <td>$<?= number_format($item['total'], 0, ',', '.') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr style="border-top: 2px solid #D4B68A; font-weight: bold; color: #4CAF50;">
                  <td colspan="3" class="text-end">TOTAL:</td>
                  <td>$<?= number_format($total, 0, ',', '.') ?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        <?php else: ?>
          <p class="text-muted">No hay items en este pedido</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Agregar Plato -->
    <div class="card bg-dark text-light mb-4">
      <div class="card-header" style="background-color: #1a1a1a; border-bottom: 2px solid #D4B68A;">
        <h5 class="mb-0" style="color: #D4B68A;">
          <i class="bi bi-plus-circle"></i> Agregar Plato al Pedido
        </h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <div class="mb-3">
              <label for="plato_agregar" class="form-label">Seleccionar Plato</label>
              <select id="plato_agregar" class="form-select bg-dark text-light" style="border-color: #d4af37;">
                <option value="">-- Selecciona un plato --</option>
                <?php foreach ($platos_menu as $plato): ?>
                  <?php
                    $stockInfo = $plato['stock_ilimitado'] == 1 ? '∞' : 'Stock: ' . $plato['stock'];
                  ?>
                  <option value="<?= $plato['id'] ?>" data-precio="<?= $plato['precio'] ?>">
                    [<?= esc($plato['categoria']) ?>] <?= esc($plato['nombre']) ?> - $<?= number_format($plato['precio'], 0, ',', '.') ?> (<?= $stockInfo ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="mb-3">
              <label for="cantidad_agregar" class="form-label">Cantidad</label>
              <input type="number" id="cantidad_agregar" class="form-control bg-dark text-light" style="border-color: #d4af37;" value="1" min="1">
            </div>
          </div>
          <div class="col-md-2">
            <div class="mb-3">
              <label class="form-label">&nbsp;</label>
              <button type="button" class="btn btn-success w-100" onclick="agregarPlatoAlPedido()">
                <i class="bi bi-plus"></i> Agregar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Detalles del Pedido -->
    <div class="card bg-dark text-light">
      <div class="card-header" style="background-color: #1a1a1a; border-bottom: 2px solid #D4B68A;">
        <h5 class="mb-0" style="color: #D4B68A;">
          <i class="bi bi-pencil-square"></i> Detalles del Pedido
        </h5>
      </div>
      <div class="card-body">
        <form action="<?= base_url('admin/pedidos/editar/' . $pedido['id']) ?>" method="post">
          <?= csrf_field() ?>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select bg-dark text-light" style="border-color: #d4af37;" required>
                  <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendientes</option>
                  <option value="completado" <?= $pedido['estado'] == 'completado' ? 'selected' : '' ?>>Completados</option>
                  <option value="cancelado" <?= $pedido['estado'] == 'cancelado' ? 'selected' : '' ?>>Cancelados</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="tipo_entrega" class="form-label">Tipo de Entrega</label>
                <select name="tipo_entrega" id="tipo_entrega" class="form-select bg-dark text-light" style="border-color: #d4af37;" required>
                  <option value="delivery" <?= $pedido['tipo_entrega'] == 'delivery' ? 'selected' : '' ?>>Delivery</option>
                  <option value="retiro" <?= $pedido['tipo_entrega'] == 'retiro' ? 'selected' : '' ?>>Retiro en Local</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" name="direccion" id="direccion" class="form-control bg-dark text-light" style="border-color: #d4af37;" value="<?= esc($pedido['direccion'] ?? '') ?>">
                <small class="text-muted">Dejar vacío si es retiro en local</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="forma_pago" class="form-label">Forma de Pago</label>
                <select name="forma_pago" id="forma_pago" class="form-select bg-dark text-light" style="border-color: #d4af37;" required>
                  <option value="efectivo" <?= $pedido['forma_pago'] == 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
                  <option value="qr" <?= $pedido['forma_pago'] == 'qr' ? 'selected' : '' ?>>QR</option>
                  <option value="mercado_pago" <?= $pedido['forma_pago'] == 'mercado_pago' ? 'selected' : '' ?>>Mercado Pago</option>
                  <option value="transferencia" <?= $pedido['forma_pago'] == 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
                  <option value="tarjeta" <?= $pedido['forma_pago'] == 'tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="mb-3">
                <label for="notas" class="form-label">Notas</label>
                <textarea name="notas" id="notas" class="form-control bg-dark text-light" style="border-color: #d4af37;" rows="5"><?= esc($pedido['notas']) ?></textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-warning">
                <i class="bi bi-save"></i> Guardar Cambios
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
  function agregarPlatoAlPedido() {
    const platoSelect = document.getElementById('plato_agregar');
    const cantidadInput = document.getElementById('cantidad_agregar');

    const platoId = platoSelect.value;
    const cantidad = parseInt(cantidadInput.value);

    if (!platoId || platoId === '') {
      mostrarNotificacion('Selecciona un plato', 'error');
      return;
    }

    if (!cantidad || cantidad < 1) {
      mostrarNotificacion('Ingresa una cantidad válida', 'error');
      return;
    }

    // Obtener el nombre del cliente del pedido
    const pedidoKey = '<?= $pedido['info_pedido']['nombre_cliente'] . '_' . date('Y-m-d H:i', strtotime($pedido['created_at'])) ?>';

    // Deshabilitar botón mientras se procesa
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Agregando...';

    fetch('<?= site_url("admin/pedidos/agregarPlato") ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `pedido_key=${encodeURIComponent(pedidoKey)}&plato_id=${platoId}&cantidad=${cantidad}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        mostrarNotificacion('Plato agregado correctamente', 'success');
        setTimeout(() => location.reload(), 1000);
      } else {
        mostrarNotificacion(data.message || 'Error al agregar plato', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-plus"></i> Agregar';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      mostrarNotificacion('Error al agregar plato', 'error');
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-plus"></i> Agregar';
    });
  }

  function mostrarNotificacion(mensaje, tipo) {
    const notif = document.createElement('div');
    notif.className = `alert alert-${tipo === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
    notif.style.zIndex = '10000';
    notif.textContent = mensaje;
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 3000);
  }
  </script>

  <!-- Footer Admin -->
  <footer class="text-center text-light py-4 mt-5" style="background-color: #1a1a1a; border-top: 2px solid #D4B68A;">
    <div class="container">
      <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
        <a href="https://docs.google.com/spreadsheets" target="_blank" class="text-decoration-none" title="Google Sheets">
          <i class="bi bi-file-earmark-excel" style="font-size: 1.8rem; color: #1D6F42;"></i>
        </a>
        <a href="https://docs.google.com/document" target="_blank" class="text-decoration-none" title="Google Docs">
          <i class="bi bi-file-earmark-word" style="font-size: 1.8rem; color: #2B579A;"></i>
        </a>
        <a href="https://hpanel.hostinger.com" target="_blank" class="text-decoration-none" title="Hostinger Panel">
          <i class="bi bi-hdd-rack" style="font-size: 1.8rem; color: #673DE6;"></i>
        </a>
        <a href="https://mail.google.com" target="_blank" class="text-decoration-none" title="Gmail">
          <i class="bi bi-envelope" style="font-size: 1.8rem; color: #D93025;"></i>
        </a>
        <a href="https://www.instagram.com/aido_agenciaweb/" target="_blank" class="text-decoration-none" title="Soporte Técnico">
          <i class="bi bi-life-preserver" style="font-size: 1.8rem; color: #D4B68A;"></i>
        </a>
      </div>
      <p class="mb-0 mt-3 small" style="color: #999;">© 2025 La Bartola | Panel Administrativo</p>
    </div>
  </footer>
</section>

<?= $this->endSection() ?>
