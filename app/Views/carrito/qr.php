<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código QR - La Bartola</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #000;
        }
        img {
            max-width: 90%;
            max-height: 90vh;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <img src="<?= base_url('assets/images/qr-pago.png') ?>" alt="Código QR para pago">
</body>
</html>