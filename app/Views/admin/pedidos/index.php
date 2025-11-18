<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
/* Estilos móvil-first */
.pedido-card {
    background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    border: 2px solid #D4B68A;
    border-radius: 15px;
    margin-bottom: 15px;
    padding: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

.pedido-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #D4B68A;
}

.pedido-cliente {
    font-size: 1.1rem;
    font-weight: 700;
    color: #D4B68A;
}

.pedido-fecha {
    font-size: 0.85rem;
    color: #ccc;
}

.pedido-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #333;
}

.item-nombre {
    flex: 1;
    font-weight: 600;
    color: #fff;
}

.item-cantidad {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 10px;
}

.qty-btn-pedido {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #D4B68A;
    color: #000;
    border: none;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.1rem;
}

.qty-btn-pedido:active {
    transform: scale(0.9);
}

.item-subtotal {
    font-weight: 700;
    color: #4CAF50;
    min-width: 80px;
    text-align: right;
}

.pedido-footer {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 2px solid #D4B68A;
}

.pedido-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.pedido-total-label {
    color: #D4B68A;
}

.pedido-total-value {
    color: #4CAF50;
}

.pedido-info-row {
    display: flex;
    align-items: center;
    margin: 8px 0;
    font-size: 0.9rem;
}

.pedido-info-row i {
    color: #D4B68A;
    margin-right: 8px;
    width: 20px;
}

.pedido-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: 12px;
}

.btn-pedido {
    padding: 10px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-pedido:active {
    transform: scale(0.95);
}

.btn-estado {
    background: linear-gradient(135deg, #2196F3, #1976D2);
    color: #fff;
}

.btn-imprimir {
    background: linear-gradient(135deg, #4CAF50, #388E3C);
    color: #fff;
}

.btn-editar {
    background: linear-gradient(135deg, #FF9800, #F57C00);
    color: #fff;
}

.btn-eliminar {
    background: linear-gradient(135deg, #F44336, #D32F2F);
    color: #fff;
}

.badge-estado {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.badge-pendiente { background: #FFC107; color: #000; }
.badge-en_proceso { background: #2196F3; color: #fff; }
.badge-completado { background: #4CAF50; color: #fff; }
.badge-cancelado { background: #F44336; color: #fff; }

.filtros-container {
    display: flex;
    overflow-x: auto;
    gap: 8px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
}

.filtros-container::-webkit-scrollbar {
    height: 4px;
}

.filtros-container::-webkit-scrollbar-thumb {
    background: #D4B68A;
    border-radius: 2px;
}

.filtro-btn {
    min-width: fit-content;
    padding: 8px 12px;
    border: 2px solid #D4B68A;
    background: transparent;
    color: #D4B68A;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
    font-size: 0.85rem;
    flex-shrink: 0;
}

.filtro-btn.active {
    background: #D4B68A;
    color: #000;
}

.filtro-btn:active {
    transform: scale(0.95);
}

/* Modal de edición */
.modal-editar {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.8);
    z-index: 9999;
    overflow-y: auto;
    padding: 20px;
}

.modal-editar.active {
    display: flex;
    align-items: flex-start;
    justify-content: center;
}

.modal-content-editar {
    background: #1a1a1a;
    border: 2px solid #D4B68A;
    border-radius: 15px;
    padding: 20px;
    max-width: 500px;
    width: 100%;
    margin: 20px auto;
}

.modal-header-editar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #D4B68A;
}

.modal-title-editar {
    font-size: 1.3rem;
    font-weight: 700;
    color: #D4B68A;
}

.btn-close-modal {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #F44336;
    color: #fff;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-group-editar {
    margin-bottom: 15px;
}

.form-label-editar {
    display: block;
    color: #D4B68A;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-control-editar {
    width: 100%;
    padding: 10px;
    background: #2a2a2a;
    border: 1px solid #D4B68A;
    border-radius: 8px;
    color: #fff;
    font-size: 1rem;
}

.form-control-editar:focus {
    outline: none;
    border-color: #FFD700;
}

.btn-guardar-editar {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #4CAF50, #388E3C);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    margin-top: 10px;
}

.btn-guardar-editar:active {
    transform: scale(0.98);
}

@media (min-width: 768px) {
    .pedido-actions {
        grid-template-columns: repeat(4, 1fr);
    }
}
</style>

<section class="py-4" style="background-color: #000; min-height: 100vh;">
<div class="container-fluid" style="max-width: 1200px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: #D4B68A;">
            <i class="bi bi-receipt"></i> Pedidos
        </h2>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="filtros-container">
        <button class="filtro-btn active" data-filter="todos">
            Todos (<?= count($pedidos) ?>)
        </button>
        <button class="filtro-btn" data-filter="pendiente">
            🟡 Pendientes
        </button>
        <button class="filtro-btn" data-filter="en_proceso">
            🔵 En Proceso
        </button>
        <button class="filtro-btn" data-filter="completado">
            🟢 Completados
        </button>
        <button class="filtro-btn" data-filter="cancelado">
            🔴 Cancelados
        </button>
    </div>

    <!-- Lista de Pedidos -->
    <div id="pedidosContainer">
        <?php if (empty($pedidos)): ?>
            <div class="text-center text-light py-5" style="opacity: 0.7;">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-3">No hay pedidos registrados</p>
            </div>
        <?php else: ?>
            <?php
            // Agrupar pedidos por cliente y fecha
            $pedidosAgrupados = [];
            foreach ($pedidos as $pedido) {
                $key = $pedido['info_pedido']['nombre_cliente'] . '_' . date('Y-m-d H:i', strtotime($pedido['created_at'] ?? 'now'));
                if (!isset($pedidosAgrupados[$key])) {
                    $pedidosAgrupados[$key] = [
                        'cliente' => $pedido['info_pedido']['nombre_cliente'],
                        'fecha' => $pedido['created_at'] ?? 'now',
                        'tipo_entrega' => $pedido['info_pedido']['tipo_entrega'],
                        'direccion' => $pedido['info_pedido']['direccion'] ?? '',
                        'forma_pago' => $pedido['info_pedido']['forma_pago'],
                        'estado' => $pedido['estado'],
                        'items' => [],
                        'primer_id' => $pedido['id']
                    ];
                }
                $pedidosAgrupados[$key]['items'][] = [
                    'id' => $pedido['id'],
                    'plato_id' => $pedido['plato_id'],
                    'plato_nombre' => $pedido['plato_nombre'],
                    'cantidad' => $pedido['cantidad'],
                    'precio' => $pedido['precio'],
                    'total' => $pedido['total']
                ];
            }
            ?>

            <?php foreach ($pedidosAgrupados as $key => $grupo): ?>
                <?php
                $totalPedido = array_sum(array_column($grupo['items'], 'total'));
                $estadoClass = 'badge-' . $grupo['estado'];
                $estadoTexto = match($grupo['estado']) {
                    'pendiente' => '🟡 Pendiente',
                    'en_proceso' => '🔵 En Proceso',
                    'completado' => '🟢 Completado',
                    'cancelado' => '🔴 Cancelado',
                    default => $grupo['estado']
                };
                ?>
                <div class="pedido-card" data-estado="<?= $grupo['estado'] ?>" data-pedido-key="<?= $key ?>">
                    <!-- Header -->
                    <div class="pedido-header">
                        <div>
                            <div class="pedido-cliente"><?= esc($grupo['cliente']) ?></div>
                            <div class="pedido-fecha">
                                <?= date('d/m/Y - H:i', strtotime($grupo['fecha'])) ?>
                            </div>
                        </div>
                        <span class="badge-estado <?= $estadoClass ?>"><?= $estadoTexto ?></span>
                    </div>

                    <!-- Items -->
                    <div class="pedido-items">
                        <?php foreach ($grupo['items'] as $item): ?>
                            <div class="pedido-item" data-item-id="<?= $item['id'] ?>" data-plato-id="<?= $item['plato_id'] ?>">
                                <div class="item-nombre"><?= esc($item['plato_nombre']) ?></div>
                                <div class="item-cantidad">
                                    <button class="qty-btn-pedido" onclick="cambiarCantidad(<?= $item['id'] ?>, -1, event)">−</button>
                                    <span class="qty-display" id="qty-<?= $item['id'] ?>" data-precio="<?= $item['precio'] ?>"><?= $item['cantidad'] ?></span>
                                    <button class="qty-btn-pedido" onclick="cambiarCantidad(<?= $item['id'] ?>, 1, event)">+</button>
                                </div>
                                <div class="item-subtotal" id="subtotal-<?= $item['id'] ?>">
                                    $<?= number_format($item['total'], 0, ',', '.') ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Footer -->
                    <div class="pedido-footer">
                        <div class="pedido-info-row">
                            <i class="bi bi-<?= $grupo['tipo_entrega'] === 'delivery' ? 'truck' : 'bag' ?>"></i>
                            <span><?= ucfirst($grupo['tipo_entrega']) ?></span>
                        </div>
                        <?php if (!empty($grupo['direccion'])): ?>
                            <div class="pedido-info-row">
                                <i class="bi bi-geo-alt"></i>
                                <span><?= esc($grupo['direccion']) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="pedido-info-row">
                            <i class="bi bi-<?= match($grupo['forma_pago']) {
                                'efectivo' => 'cash',
                                'qr' => 'qr-code',
                                'tarjeta' => 'credit-card',
                                default => 'currency-exchange'
                            } ?>"></i>
                            <span><?= ucfirst($grupo['forma_pago']) ?></span>
                        </div>

                        <div class="pedido-total">
                            <span class="pedido-total-label">TOTAL:</span>
                            <span class="pedido-total-value" id="total-<?= $key ?>">$<?= number_format($totalPedido, 0, ',', '.') ?></span>
                        </div>

                        <div class="pedido-actions">
                            <button class="btn-pedido btn-estado" onclick="cambiarEstado('<?= $key ?>', '<?= $grupo['primer_id'] ?>', event)">
                                <i class="bi bi-arrow-repeat"></i> Estado
                            </button>
                            <button class="btn-pedido btn-imprimir" onclick="imprimir(<?= $grupo['primer_id'] ?>, event)">
                                <i class="bi bi-printer"></i> Imprimir
                            </button>
                            <button class="btn-pedido btn-editar" onclick="editarPedido('<?= $key ?>', event)">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                            <button class="btn-pedido btn-eliminar" onclick="eliminarPedido(<?= $grupo['primer_id'] ?>, event)">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de edición -->
<div class="modal-editar" id="modalEditar">
    <div class="modal-content-editar">
        <div class="modal-header-editar">
            <div class="modal-title-editar">Editar Pedido</div>
            <button class="btn-close-modal" onclick="cerrarModalEditar()">×</button>
        </div>
        <div id="modalEditarBody">
            <!-- Contenido dinámico -->
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal-editar" id="modalConfirmar">
    <div class="modal-content-editar" style="max-width: 400px;">
        <div class="modal-header-editar">
            <div class="modal-title-editar" id="modalConfirmarTitulo">Confirmar acción</div>
            <button class="btn-close-modal" onclick="cerrarModalConfirmar()">×</button>
        </div>
        <div style="padding: 20px;">
            <p id="modalConfirmarMensaje" style="color: #fff; margin-bottom: 20px; font-size: 1rem;"></p>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <button class="btn-guardar-editar" style="background: #666;" onclick="cerrarModalConfirmar()">
                    Cancelar
                </button>
                <button class="btn-guardar-editar" style="background: linear-gradient(135deg, #F44336, #D32F2F);" id="modalConfirmarBoton">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Modo debug: cambiar a false en producción para mejorar velocidad
const DEBUG_MODE = false;

// Función helper para logs condicionales
function debugLog(...args) {
    if (DEBUG_MODE) console.log(...args);
}

// Variable global para el callback del modal de confirmación
let modalConfirmarCallback = null;

// Filtrar pedidos
document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const filtro = this.dataset.filter;

        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        document.querySelectorAll('.pedido-card').forEach(card => {
            if (filtro === 'todos' || card.dataset.estado === filtro) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Cambiar cantidad de item
// Debounce timers para actualización de cantidad
let updateTimers = {};

function cambiarCantidad(itemId, delta, event) {
    // Evitar que el evento se propague
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }

    const qtyDisplay = document.getElementById(`qty-${itemId}`);
    const subtotalDisplay = document.getElementById(`subtotal-${itemId}`);

    if (!qtyDisplay) {
        if (DEBUG_MODE) console.error('No se encontró el elemento qty-' + itemId);
        mostrarNotificacion('Error: elemento no encontrado', 'error');
        return;
    }

    let cantidad = parseInt(qtyDisplay.textContent);
    cantidad += delta;

    if (cantidad < 1) {
        mostrarModalConfirmar(
            '¿Eliminar plato?',
            '¿Deseas eliminar este plato del pedido?',
            () => eliminarItem(itemId)
        );
        return;
    }

    // Actualizar visualmente de inmediato
    qtyDisplay.textContent = cantidad;

    // Calcular y actualizar subtotal visualmente
    const precioUnitario = parseFloat(qtyDisplay.dataset.precio || 0);
    const nuevoSubtotal = precioUnitario * cantidad;
    if (subtotalDisplay) {
        subtotalDisplay.textContent = `$${nuevoSubtotal.toLocaleString('es-AR')}`;
    }

    // Actualizar total visualmente
    actualizarTotal(itemId);

    // Cancelar timer anterior si existe
    if (updateTimers[itemId]) {
        clearTimeout(updateTimers[itemId]);
    }

    // Deshabilitar botones temporalmente para evitar clicks rápidos
    const btnMenos = event.target.closest('.pedido-item').querySelector('.qty-btn-pedido[onclick*="-1"]');
    const btnMas = event.target.closest('.pedido-item').querySelector('.qty-btn-pedido[onclick*="1,"]');
    if (btnMenos) btnMenos.style.opacity = '0.5';
    if (btnMas) btnMas.style.opacity = '0.5';

    // Programar actualización en servidor con delay de 500ms
    updateTimers[itemId] = setTimeout(() => {
        actualizarCantidadEnServidor(itemId, cantidad, qtyDisplay, subtotalDisplay, btnMenos, btnMas);
    }, 500);
}

// Función separada para actualizar en el servidor
function actualizarCantidadEnServidor(itemId, cantidad, qtyDisplay, subtotalDisplay, btnMenos, btnMas) {
    fetch('<?= site_url("admin/pedidos/actualizarItem") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}&cantidad=${cantidad}`
    })
    .then(response => response.json())
    .then(data => {
        // Rehabilitar botones
        if (btnMenos) btnMenos.style.opacity = '1';
        if (btnMas) btnMas.style.opacity = '1';

        if (data.success) {
            // Confirmar valores del servidor
            qtyDisplay.textContent = data.cantidad;
            if (subtotalDisplay) {
                subtotalDisplay.textContent = `$${Number(data.subtotal).toLocaleString('es-AR')}`;
            }
            actualizarTotal(itemId);
            mostrarNotificacion('Cantidad actualizada', 'success');
        } else {
            mostrarNotificacion(data.message || 'Error al actualizar', 'error');
            // Revertir cambios visuales si falla
            location.reload();
        }
    })
    .catch(error => {
        // Rehabilitar botones
        if (btnMenos) btnMenos.style.opacity = '1';
        if (btnMas) btnMas.style.opacity = '1';

        console.error('Error:', error);
        mostrarNotificacion('Error al actualizar cantidad', 'error');
        // Revertir cambios visuales
        location.reload();
    });
}

// Actualizar total del pedido
function actualizarTotal(itemId) {
    const item = document.querySelector(`[data-item-id="${itemId}"]`);
    const card = item.closest('.pedido-card');
    const items = card.querySelectorAll('.pedido-item');

    let total = 0;
    items.forEach(i => {
        const subtotalText = i.querySelector('.item-subtotal').textContent;
        const subtotal = parseFloat(subtotalText.replace('$', '').replace('.', '').replace(',', '.'));
        total += subtotal;
    });

    const totalDisplay = card.querySelector('.pedido-total-value');
    totalDisplay.textContent = `$${total.toLocaleString('es-AR')}`;
}

// Cambiar estado
function cambiarEstado(key, pedidoId, event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }

    debugLog('Cambiar estado - Key:', key, 'PedidoID:', pedidoId);

    const estados = [
        { value: 'pendiente', label: '🟡 Pendiente' },
        { value: 'en_proceso', label: '🔵 En Proceso' },
        { value: 'completado', label: '🟢 Completado' },
        { value: 'cancelado', label: '🔴 Cancelado' }
    ];

    let html = '<select class="form-control-editar" id="nuevoEstado">';
    estados.forEach(e => {
        html += `<option value="${e.value}">${e.label}</option>`;
    });
    html += '</select>';
    html += '<button class="btn-guardar-editar" onclick="guardarEstado(' + pedidoId + ', \'' + key + '\')">Guardar</button>';

    document.getElementById('modalEditarBody').innerHTML = html;
    document.getElementById('modalEditar').classList.add('active');
}

function guardarEstado(pedidoId, key) {
    const nuevoEstado = document.getElementById('nuevoEstado').value;

    fetch('<?= site_url("admin/pedidos/cambiarEstado") ?>/' + pedidoId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `estado=${nuevoEstado}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cerrar modal y recargar inmediatamente
            cerrarModalEditar();
            location.reload();
        } else {
            mostrarNotificacion(data.message || 'Error al cambiar estado', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al cambiar estado', 'error');
    });
}

// Imprimir
function imprimir(pedidoId, event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }
    debugLog('Imprimir pedido:', pedidoId);
    const url = '<?= site_url("admin/pedidos/imprimir") ?>/' + pedidoId;
    debugLog('URL:', url);
    window.open(url, '_blank');
}

// Editar pedido
function editarPedido(key, event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }
    debugLog('Editar pedido - Key:', key);
    const card = document.querySelector(`[data-pedido-key="${key}"]`);
    const items = card.querySelectorAll('.pedido-item');

    let html = `
        <div class="form-group-editar">
            <label class="form-label-editar">Platos del Pedido:</label>
            <div style="max-height: 300px; overflow-y: auto; margin-bottom: 15px;">
    `;

    items.forEach(item => {
        const itemId = item.dataset.itemId;
        const platoId = item.dataset.platoId;
        const nombre = item.querySelector('.item-nombre').textContent;
        const cantidad = item.querySelector('.qty-display').textContent;

        html += `
            <div class="item-editar" style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #2a2a2a; margin-bottom: 8px; border-radius: 8px;">
                <div style="flex: 1;">
                    <div style="color: #fff; font-weight: 600;">${nombre}</div>
                    <div style="color: #ccc; font-size: 0.85rem;">ID: ${itemId}</div>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <button onclick="editarCantidadModal(${itemId}, -1)" style="width: 30px; height: 30px; border-radius: 50%; background: #F44336; color: #fff; border: none; cursor: pointer;">−</button>
                    <span id="modal-qty-${itemId}" style="color: #D4B68A; font-weight: 700; min-width: 30px; text-align: center;">${cantidad}</span>
                    <button onclick="editarCantidadModal(${itemId}, 1)" style="width: 30px; height: 30px; border-radius: 50%; background: #4CAF50; color: #fff; border: none; cursor: pointer;">+</button>
                    <button onclick="eliminarItemModal(${itemId})" style="width: 30px; height: 30px; border-radius: 50%; background: #FF5722; color: #fff; border: none; cursor: pointer; margin-left: 5px;"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
    });

    html += `
            </div>
        </div>
        <div class="form-group-editar">
            <label class="form-label-editar">Agregar Plato:</label>
            <button class="btn-guardar-editar" style="background: linear-gradient(135deg, #2196F3, #1976D2); margin-top: 0;" onclick="mostrarSelectorPlatos('${key}')">
                <i class="bi bi-plus-circle"></i> Agregar Plato
            </button>
        </div>
    `;

    document.getElementById('modalEditarBody').innerHTML = html;
    document.getElementById('modalEditar').classList.add('active');
}

function editarCantidadModal(itemId, delta) {
    const qtyDisplay = document.getElementById(`modal-qty-${itemId}`);
    let cantidad = parseInt(qtyDisplay.textContent);
    cantidad += delta;

    if (cantidad < 1) {
        mostrarModalConfirmar(
            '¿Eliminar plato?',
            '¿Deseas eliminar este plato del pedido?',
            () => eliminarItemModal(itemId)
        );
        return;
    }

    qtyDisplay.textContent = cantidad;

    // Actualizar en el servidor
    cambiarCantidad(itemId, delta);
}

function eliminarItemModal(itemId) {
    fetch('<?= site_url("admin/pedidos/actualizarItem") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}&cantidad=0`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarNotificacion('Plato eliminado', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarNotificacion(data.message || 'Error al eliminar', 'error');
        }
    });
}

function mostrarSelectorPlatos(key) {
    // Obtener lista de platos disponibles desde el backend
    fetch('<?= site_url("admin/menu/obtenerPlatos") ?>')
        .then(response => response.json())
        .then(data => {
            if (!data.success || !data.platos || data.platos.length === 0) {
                mostrarNotificacion('No hay platos disponibles', 'error');
                return;
            }

            // Crear el select con los platos disponibles
            let platosOptions = '<option value="">-- Selecciona un plato --</option>';
            data.platos.forEach(plato => {
                const stockInfo = plato.stock_ilimitado == 1 ? '∞' : `Stock: ${plato.stock}`;
                platosOptions += `<option value="${plato.id}" data-precio="${plato.precio}">
                    ${plato.nombre} - $${Number(plato.precio).toLocaleString('es-AR')} (${stockInfo})
                </option>`;
            });

            let html = `
                <div class="form-group-editar">
                    <label class="form-label-editar">Seleccionar Plato:</label>
                    <select class="form-control-editar" id="nuevoPlatoId">
                        ${platosOptions}
                    </select>
                </div>
                <div class="form-group-editar">
                    <label class="form-label-editar">Cantidad:</label>
                    <input type="number" class="form-control-editar" id="nuevoPlatoCantidad" value="1" min="1">
                </div>
                <button class="btn-guardar-editar" onclick="agregarPlatoAPedido('${key}')">
                    <i class="bi bi-plus-circle"></i> Agregar
                </button>
                <button class="btn-guardar-editar" style="background: #666; margin-top: 10px;" onclick="editarPedido('${key}')">
                    <i class="bi bi-arrow-left"></i> Volver
                </button>
            `;

            document.getElementById('modalEditarBody').innerHTML = html;
        })
        .catch(error => {
            console.error('Error al cargar platos:', error);
            mostrarNotificacion('Error al cargar los platos disponibles', 'error');
        });
}

function agregarPlatoAPedido(key) {
    const platoId = document.getElementById('nuevoPlatoId').value;
    const cantidad = document.getElementById('nuevoPlatoCantidad').value;

    if (!platoId || platoId === '') {
        mostrarNotificacion('Selecciona un plato', 'error');
        return;
    }

    if (!cantidad || cantidad < 1) {
        mostrarNotificacion('Ingresa una cantidad válida', 'error');
        return;
    }

    // Crear nuevo pedido con este plato
    fetch('<?= site_url("admin/pedidos/agregarPlato") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `pedido_key=${encodeURIComponent(key)}&plato_id=${platoId}&cantidad=${cantidad}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarNotificacion('Plato agregado correctamente', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarNotificacion(data.message || 'Error al agregar plato', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al agregar plato', 'error');
    });
}

function guardarEdicion(key) {
    // Por ahora solo cierra el modal
    // Puedes implementar la lógica de guardado aquí
    cerrarModalEditar();
    mostrarNotificacion('Cambios guardados', 'success');
}

// Eliminar item
function eliminarItem(itemId) {
    fetch('<?= site_url("admin/pedidos/eliminar") ?>/' + itemId, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Eliminar pedido
function eliminarPedido(pedidoId, event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }
    debugLog('Eliminar pedido:', pedidoId);

    // Mostrar modal de confirmación personalizado
    mostrarModalConfirmar(
        '¿Eliminar pedido?',
        '¿Estás seguro de que deseas eliminar todo el pedido? Esta acción no se puede deshacer.',
        () => {
            // Callback cuando se confirma
            fetch('<?= site_url("admin/pedidos/eliminar") ?>/' + pedidoId, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion('Pedido eliminado correctamente', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    mostrarNotificacion(data.message || 'Error al eliminar pedido', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al eliminar pedido', 'error');
            });
        }
    );
}

function cerrarModalEditar() {
    document.getElementById('modalEditar').classList.remove('active');
}

// Funciones del modal de confirmación
function mostrarModalConfirmar(titulo, mensaje, callback) {
    document.getElementById('modalConfirmarTitulo').textContent = titulo;
    document.getElementById('modalConfirmarMensaje').textContent = mensaje;

    // Guardar el callback
    modalConfirmarCallback = callback;

    // Asignar el evento al botón de confirmar
    const botonConfirmar = document.getElementById('modalConfirmarBoton');
    botonConfirmar.onclick = function() {
        cerrarModalConfirmar();
        if (modalConfirmarCallback) {
            modalConfirmarCallback();
            modalConfirmarCallback = null;
        }
    };

    // Mostrar el modal
    document.getElementById('modalConfirmar').classList.add('active');
}

function cerrarModalConfirmar() {
    document.getElementById('modalConfirmar').classList.remove('active');
    modalConfirmarCallback = null;
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

</section>
<?= $this->endSection() ?>
