<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Caja - <?= date('d/m/Y', strtotime($fecha)) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }
        h2 {
            text-align: center;
            font-size: 14px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px 8px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: left;
        }
        td.text-right {
            text-align: right;
        }
        td.text-center {
            text-align: center;
        }
        .totales {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .resumen {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .resumen-item {
            border: 1px solid #000;
            padding: 10px;
            width: 48%;
        }
        .resumen-label {
            font-size: 11px;
            margin-bottom: 5px;
        }
        .resumen-valor {
            font-size: 16px;
            font-weight: bold;
        }
        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" style="margin-bottom: 20px; padding: 10px 20px; cursor: pointer;">
        🖨️ Imprimir
    </button>

    <h1>REPORTE DE CAJA DEL DIA: <?= date('d/m/Y', strtotime($fecha)) ?></h1>

    <table>
        <thead>
            <tr>
                <th>FECHA</th>
                <th>HORA</th>
                <th>CONCEPTO</th>
                <th class="text-right">ENTRADA</th>
                <th class="text-right">SALIDA</th>
                <th class="text-right">SALDO</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $saldoAcumulado = 0;
            if (empty($movimientos)):
            ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">
                        No hay movimientos para esta fecha
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($movimientos as $mov): ?>
                    <?php
                    if ($mov['tipo'] === 'entrada') {
                        $saldoAcumulado += $mov['monto'];
                    } else {
                        $saldoAcumulado -= $mov['monto'];
                    }
                    ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($mov['fecha'])) ?></td>
                        <td><?= date('H:i', strtotime($mov['hora'])) ?></td>
                        <td><?= esc($mov['concepto']) ?></td>
                        <td class="text-right">
                            <?= $mov['tipo'] === 'entrada' ? number_format($mov['monto'], 2) : '0.00' ?>
                        </td>
                        <td class="text-right">
                            <?= $mov['tipo'] === 'salida' ? number_format($mov['monto'], 2) : '0.00' ?>
                        </td>
                        <td class="text-right"><?= number_format($saldoAcumulado, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr class="totales">
                <td colspan="3" class="text-right">Total Entradas (*):</td>
                <td class="text-right"><?= number_format($entradas, 2) ?></td>
                <td colspan="2"></td>
            </tr>
            <tr class="totales">
                <td colspan="3" class="text-right">Total Salidas:</td>
                <td colspan="2" class="text-right"><?= number_format($salidas, 2) ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="resumen">
        <div class="resumen-item">
            <div class="resumen-label">(*) Total Efectivo:</div>
            <div class="resumen-valor"><?= number_format($efectivo, 2) ?></div>
        </div>
        <div class="resumen-item">
            <div class="resumen-label">Total Dinero Digital:</div>
            <div class="resumen-valor"><?= number_format($digital, 2) ?></div>
        </div>
    </div>

    <div class="resumen-item" style="width: 100%; margin-top: 10px; background-color: #e3f2fd;">
        <div class="resumen-label">Saldo Actual:</div>
        <div class="resumen-valor" style="font-size: 20px; color: #1976d2;">
            <?= number_format($saldo, 2) ?>
        </div>
    </div>

    <script>
        // Auto-imprimir al cargar la página
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
