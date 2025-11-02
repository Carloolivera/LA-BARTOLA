<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #<?= $pedido['id'] ?> - La Bartola</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 10px;
            margin: 2px 0;
        }
        
        .info-section {
            margin: 10px 0;
            padding: 5px 0;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        
        .info-label {
            font-weight: bold;
        }
        
        .items {
            margin: 15px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 10px 0;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        
        .total-section {
            margin: 15px 0;
            padding: 10px 0;
            border-top: 2px solid #000;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 10px;
        }
        
        @media print {
            body {
                width: 80mm;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
            🖨️ Imprimir Ticket
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; margin-left: 10px;">
            ❌ Cerrar
        </button>
    </div>

    <div class="header">
        <h1>LA BARTOLA</h1>
        <p>Casa de Comidas</p>
        <p>Tel: (XXX) XXX-XXXX</p>
        <p>Dirección: Tu dirección aquí</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Pedido #:</span>
            <span><?= $pedido['id'] ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha:</span>
            <span><?= date('d/m/Y H:i', strtotime($pedido['created_at'] ?? 'now')) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Cliente:</span>
            <span><?= esc($pedido['username']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">A nombre de:</span>
            <span><?= esc($pedido['info_pedido']['nombre_cliente']) ?></span>
        </div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Tipo:</span>
            <span><?= esc(ucfirst($pedido['info_pedido']['tipo_entrega'])) ?></span>
        </div>
        <?php if (!empty($pedido['info_pedido']['direccion'])): ?>
        <div class="info-row">
            <span class="info-label">Dirección:</span>
            <span><?= esc($pedido['info_pedido']['direccion']) ?></span>
        </div>
        <?php endif; ?>
        <div class="info-row">
            <span class="info-label">Pago:</span>
            <span><?= esc(ucfirst($pedido['info_pedido']['forma_pago'])) ?></span>
        </div>
    </div>

    <div class="items">
        <div class="item-row">
            <span><strong>Producto</strong></span>
            <span><strong>Subtotal</strong></span>
        </div>
        <div class="item-row">
            <span><?= esc($pedido['plato_nombre']) ?> x<?= $pedido['cantidad'] ?></span>
            <span>$<?= number_format($pedido['total'], 2) ?></span>
        </div>
    </div>

    <div class="total-section">
        <div class="total-row">
            <span>TOTAL:</span>
            <span>$<?= number_format($pedido['total'], 2) ?></span>
        </div>
    </div>

    <div class="footer">
        <p>¡Gracias por su compra!</p>
        <p>Estado: <?= esc(ucfirst($pedido['estado'])) ?></p>
        <p>═══════════════════════</p>
        <p>www.labartola.com</p>
    </div>

    <script>
        // Auto-imprimir al cargar (opcional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>