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
                    foreach ($carrito as $id => $item): 
                        $subtotal = $item['precio'] * $item['cantidad'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?= esc($item['nombre']) ?></td>
                            <td>$<?= number_format($item['precio'], 2) ?></td>
                            <td>
                                <form action="<?= site_url('carrito/actualizar') ?>" method="post" class="d-inline">
                                    <input type="hidden" name="plato_id" value="<?= $id ?>">
                                    <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>" min="1" class="form-control form-control-sm d-inline" style="width: 70px;">
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
                        <th colspan="3" class="text-end">Total:</th>
                        <th>$<?= number_format($total, 2) ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

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
                                <label class="form-check-label" for="mercado_pago">Mercado Pago</label>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info" id="mercado_pago_info" style="display: none;">
                        <h6 class="text-dark">Datos para transferencia:</h6>
                        <p class="mb-1"><strong>CBU:</strong> <span id="cbu_value">AQUI_VA_TU_CBU</span></p>
                        <p class="mb-0"><strong>ALIAS:</strong> <span id="alias_value">AQUI_VA_TU_ALIAS</span></p>
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