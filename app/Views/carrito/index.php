<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <h2 class="text-center mb-4 text-warning">Mi Carrito</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (empty($carrito)): ?>
        <div class="alert alert-info text-center">
            <p>Tu carrito está vacío</p>
            <a href="<?= site_url('menu') ?>" class="btn btn-warning">Ver Menú</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Plato</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    $db = \Config\Database::connect();
                    foreach ($carrito as $id => $item):
                        $subtotal = $item['precio'] * $item['cantidad'];
                        $total += $subtotal;

                        // Obtener información del stock del plato
                        $plato = $db->table('platos')->where('id', $id)->get()->getRowArray();
                        $stockMax = ($plato && $plato['stock_ilimitado'] == 0) ? $plato['stock'] : 99;
                        $stockInfo = ($plato && $plato['stock_ilimitado'] == 0) ? "Stock: {$plato['stock']}" : "Stock ilimitado";
                    ?>
                        <tr>
                            <td>
                                <?= esc($item['nombre']) ?>
                                <br><small class="text-muted"><?= $stockInfo ?></small>
                            </td>
                            <td>$<?= number_format($item['precio'], 2) ?></td>
                            <td>
                                <form action="<?= site_url('carrito/actualizar') ?>" method="post" class="d-inline">
                                    <input type="hidden" name="plato_id" value="<?= $id ?>">
                                    <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>" min="1" max="<?= $stockMax ?>" class="form-control form-control-sm d-inline" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-warning">Actualizar</button>
                                </form>
                            </td>
                            <td>$<?= number_format($subtotal, 2) ?></td>
                            <td>
                                <form action="<?= site_url('carrito/eliminar') ?>" method="post" class="d-inline">
                                    <input type="hidden" name="plato_id" value="<?= $id ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Subtotal:</th>
                        <th id="subtotal">$<?= number_format($total, 2) ?></th>
                        <th></th>
                    </tr>
                    <?php
                    $cupon_aplicado = session()->get('cupon_aplicado');
                    $descuento = 0;
                    if ($cupon_aplicado):
                        $descuento = $cupon_aplicado['descuento'];
                    ?>
                    <tr class="text-success">
                        <th colspan="3" class="text-end">
                            Descuento (<?= esc($cupon_aplicado['codigo']) ?>):
                            <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="quitarCupon()" title="Quitar cupón">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </th>
                        <th class="text-success" id="descuento">-$<?= number_format($descuento, 2) ?></th>
                        <th></th>
                    </tr>
                    <?php endif; ?>
                    <tr class="fw-bold fs-5">
                        <th colspan="3" class="text-end">Total:</th>
                        <th class="text-warning" id="total">$<?= number_format($total - $descuento, 2) ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- SECCIÓN DE CUPONES (solo si está logueado) -->
        <?php if (auth()->loggedIn()): ?>
        <div class="card bg-dark border-warning mt-4">
            <div class="card-header bg-transparent border-warning">
                <h5 class="mb-0 text-warning">
                    <i class="bi bi-tag-fill"></i> ¿Tienes un cupón de descuento?
                </h5>
            </div>
            <div class="card-body">
                <?php if ($cupon_aplicado): ?>
                    <div class="alert alert-success d-flex align-items-center justify-content-between">
                        <div>
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>Cupón aplicado:</strong> <?= esc($cupon_aplicado['codigo']) ?>
                            <br>
                            <small><?= esc($cupon_aplicado['descripcion'] ?? '') ?></small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="quitarCupon()">
                            <i class="bi bi-x"></i> Quitar
                        </button>
                    </div>
                <?php else: ?>
                    <form id="formCupon" class="row g-3" onsubmit="validarCupon(event)">
                        <div class="col-md-8">
                            <input
                                type="text"
                                class="form-control"
                                id="codigo_cupon"
                                name="codigo_cupon"
                                placeholder="Ingresa tu código de cupón"
                                style="text-transform: uppercase;"
                                required
                            >
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-check2"></i> Aplicar Cupón
                            </button>
                        </div>
                    </form>
                    <div id="mensaje_cupon" class="mt-2"></div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mt-4">
            <form action="<?= site_url('carrito/vaciar') ?>" method="post">
                <button type="submit" class="btn btn-outline-danger">Vaciar Carrito</button>
            </form>
            
            <?php if (!auth()->loggedIn()): ?>
                <!-- USUARIO NO LOGUEADO -->
                <div class="text-end">
                    <div class="alert alert-warning mb-2 d-inline-block">
                        <i class="bi bi-exclamation-triangle"></i> 
                        Debe iniciar sesión para realizar un pedido
                    </div>
                    <br>
                    <?php 
                    // Guardar URL actual para redirección después del login
                    $currentUrl = urlencode(current_url());
                    ?>
                    <a href="<?= site_url('login') ?>?redirect=<?= $currentUrl ?>" class="btn btn-warning">
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                    </a>
                    <span class="mx-2 text-muted">o</span>
                    <a href="<?= site_url('register') ?>?redirect=<?= $currentUrl ?>" class="btn btn-outline-warning">
                        <i class="bi bi-person-plus"></i> Registrarse
                    </a>
                </div>
            <?php else: ?>
                <!-- USUARIO LOGUEADO -->
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#finalizarPedidoModal">
                    <i class="bi bi-check-circle"></i> Finalizar Pedido
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- MODAL SOLO SE MUESTRA SI EL USUARIO ESTÁ LOGUEADO -->
<?php if (auth()->loggedIn()): ?>
<div class="modal fade" id="finalizarPedidoModal" tabindex="-1" aria-labelledby="finalizarPedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-beige">
            <div class="modal-header border-warning">
                <h5 class="modal-title text-warning" id="finalizarPedidoModalLabel">Finalizar Pedido</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('carrito/finalizar') ?>" method="post" id="formFinalizarPedido">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_cliente" class="form-label">A nombre de:</label>
                        <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de entrega:</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo_entrega" id="delivery" value="delivery" required>
                                <label class="form-check-label" for="delivery">Delivery</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo_entrega" id="para_llevar" value="para_llevar" required>
                                <label class="form-check-label" for="para_llevar">Para llevar</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="direccion_container" style="display: none;">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Forma de pago:</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="forma_pago" id="efectivo" value="efectivo" required>
                                <label class="form-check-label" for="efectivo">Efectivo</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="forma_pago" id="qr" value="qr" required>
                                <label class="form-check-label" for="qr">QR</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="forma_pago" id="mercado_pago" value="mercado_pago" required>
                                <label class="form-check-label" for="mercado_pago">
                                    <img src="https://http2.mlstatic.com/storage/logos-api-admin/0daa1670-5c26-11ec-ae75-df2bef173be2-xl@2x.png" height="24" style="vertical-align: middle;" alt="Mercado Pago">
                                    Mercado Pago
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info" id="mercado_pago_info" style="display: none;">
                        <i class="bi bi-info-circle"></i>
                        <strong>Mercado Pago:</strong> Serás redirigido a Mercado Pago para completar el pago de forma segura.
                        Podrás pagar con tarjeta de crédito, débito o efectivo.
                    </div>
                </div>
                <div class="modal-footer border-warning">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Confirmar Pedido</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Funciones para cupones
function validarCupon(event) {
    event.preventDefault();

    const codigo = document.getElementById('codigo_cupon').value.trim().toUpperCase();
    const mensajeDiv = document.getElementById('mensaje_cupon');

    if (!codigo) {
        mensajeDiv.innerHTML = '<div class="alert alert-warning">Por favor ingresa un código de cupón</div>';
        return;
    }

    // Mostrar loading
    mensajeDiv.innerHTML = '<div class="alert alert-info"><i class="bi bi-hourglass-split"></i> Validando cupón...</div>';

    fetch('<?= site_url('carrito/validarCupon') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ codigo: codigo })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valido) {
            // Cupón válido, aplicarlo
            aplicarCupon(codigo, data);
        } else {
            mensajeDiv.innerHTML = `<div class="alert alert-danger"><i class="bi bi-x-circle"></i> ${data.mensaje}</div>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mensajeDiv.innerHTML = '<div class="alert alert-danger">Error al validar el cupón. Inténtalo nuevamente.</div>';
    });
}

function aplicarCupon(codigo, validacionData) {
    const mensajeDiv = document.getElementById('mensaje_cupon');

    fetch('<?= site_url('carrito/aplicarCupon') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ codigo: codigo })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recargar la página para mostrar el descuento
            location.reload();
        } else {
            mensajeDiv.innerHTML = `<div class="alert alert-danger"><i class="bi bi-x-circle"></i> ${data.mensaje}</div>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mensajeDiv.innerHTML = '<div class="alert alert-danger">Error al aplicar el cupón. Inténtalo nuevamente.</div>';
    });
}

function quitarCupon() {
    if (!confirm('¿Estás seguro de que deseas quitar el cupón?')) {
        return;
    }

    fetch('<?= site_url('carrito/quitarCupon') ?>', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error al quitar el cupón');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al quitar el cupón');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const deliveryRadio = document.getElementById('delivery');
    const paraLlevarRadio = document.getElementById('para_llevar');
    const direccionContainer = document.getElementById('direccion_container');
    const direccionInput = document.getElementById('direccion');

    const efectivoRadio = document.getElementById('efectivo');
    const qrRadio = document.getElementById('qr');
    const mercadoPagoRadio = document.getElementById('mercado_pago');
    const mercadoPagoInfo = document.getElementById('mercado_pago_info');

    deliveryRadio.addEventListener('change', function() {
        if (this.checked) {
            direccionContainer.style.display = 'block';
            direccionInput.required = true;
        }
    });

    paraLlevarRadio.addEventListener('change', function() {
        if (this.checked) {
            direccionContainer.style.display = 'none';
            direccionInput.required = false;
            direccionInput.value = '';
        }
    });

    efectivoRadio.addEventListener('change', function() {
        if (this.checked) {
            mercadoPagoInfo.style.display = 'none';
        }
    });

    qrRadio.addEventListener('change', function() {
        if (this.checked) {
            mercadoPagoInfo.style.display = 'none';
        }
    });

    mercadoPagoRadio.addEventListener('change', function() {
        if (this.checked) {
            mercadoPagoInfo.style.display = 'block';
        }
    });

    document.getElementById('formFinalizarPedido').addEventListener('submit', function(e) {
        const formaPago = document.querySelector('input[name="forma_pago"]:checked').value;

        // Si selecciona Mercado Pago, cambiar action del formulario
        if (formaPago === 'mercado_pago') {
            this.action = '<?= site_url('mercadopago/crear') ?>';
        } else {
            this.action = '<?= site_url('carrito/finalizar') ?>';
        }

        // Si selecciona QR, hacer petición AJAX
        if (formaPago === 'qr') {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('<?= site_url('carrito/finalizar') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.open('<?= site_url('carrito/mostrarQR') ?>', '_blank');
                    window.location.href = '<?= site_url('pedido') ?>';
                }
            });
        }
    });
});
</script>
<?php endif; ?>

<?= $this->endSection() ?>