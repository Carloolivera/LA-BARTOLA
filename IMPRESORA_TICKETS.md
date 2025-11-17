# Guía de Integración de Impresora de Tickets

## Resumen
Este documento explica cómo conectar una impresora térmica de tickets (58mm o 80mm) a tu sistema La Bartola.

## Tipos de Impresoras Compatibles

### 1. **Impresoras Térmicas USB** (Más Común)
- Marcas: Epson TM-T20, Star TSP100, Bixolon SRP-275
- Conexión: USB directa a la computadora/tablet
- Precio: $150-300 USD

### 2. **Impresoras Bluetooth**
- Ideal para: Tablets y teléfonos móviles
- Marcas: Zebra iMZ220, Epson TM-P20
- Precio: $200-400 USD

### 3. **Impresoras de Red (Ethernet/WiFi)**
- Ideal para: Cocina separada del mostrador
- Marcas: Epson TM-T88VI, Star TSP143IIILAN
- Precio: $250-450 USD

## Métodos de Integración

### Opción 1: Imprimir desde Navegador (Más Simple) ⭐ RECOMENDADO

Ya está implementado en tu sistema:

```php
// Ruta: /admin/pedidos/imprimir/{id}
// Ya genera HTML optimizado para impresora térmica
```

**Pasos:**
1. Instala la impresora en Windows/Linux/Mac
2. Configura el ancho de papel (58mm o 80mm) en el driver
3. En el navegador, al imprimir se abre la ventana de diálogo
4. Selecciona tu impresora térmica
5. Ajusta márgenes a 0 en configuración de impresión

**Ventajas:**
- ✅ Ya funciona sin código adicional
- ✅ Compatible con cualquier impresora
- ✅ Fácil configuración

**Desventajas:**
- ❌ Requiere un click para seleccionar impresora

### Opción 2: Impresión Automática con ESC/POS

Para impresión directa sin diálogo:

#### a) Usando PHP con extensión ESC/POS

```bash
# Instalar librería
composer require mike42/escpos-php
```

```php
// Ejemplo de implementación
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

public function imprimirDirecto($pedidoId) {
    $pedido = $this->obtenerPedido($pedidoId);

    // Conectar a impresora (nombre en Windows)
    $connector = new WindowsPrintConnector("TM-T20");
    $printer = new Printer($connector);

    // Imprimir
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("LA BARTOLA\n");
    $printer->text("=================\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Cliente: " . $pedido['cliente'] . "\n");
    $printer->text("Fecha: " . date('d/m/Y H:i') . "\n\n");

    foreach ($pedido['items'] as $item) {
        $printer->text($item['nombre'] . "\n");
        $printer->text(" " . $item['cantidad'] . " x $" . $item['precio'] . "\n");
    }

    $printer->text("\n=================\n");
    $printer->text("TOTAL: $" . $pedido['total'] . "\n\n\n");
    $printer->cut();
    $printer->close();
}
```

#### b) Usando JavaScript con plugin

Para navegador con QZ Tray (software gratuito):

```html
<script src="https://cdn.jsdelivr.net/npm/qz-tray@2.x/qz-tray.js"></script>
<script>
async function imprimirTicket(pedidoId) {
    // Conectar a QZ Tray
    await qz.websocket.connect();

    // Seleccionar impresora
    const printers = await qz.printers.find();
    const config = qz.configs.create(printers[0]);

    // Datos del ticket en formato ESC/POS
    const data = [
        '\x1B\x40', // Inicializar
        '\x1B\x61\x01', // Centrar
        'LA BARTOLA\n',
        '=================\n',
        '\x1B\x61\x00', // Izquierda
        `Cliente: ${cliente}\n`,
        `Fecha: ${fecha}\n\n`,
        // ... items ...
        '=================\n',
        `TOTAL: $${total}\n\n\n`,
        '\x1D\x56\x00' // Cortar papel
    ];

    await qz.print(config, data);
    await qz.websocket.disconnect();
}
</script>
```

### Opción 3: Impresión desde App Android/iOS

Si usas tablet/celular:

1. **Android:** Usa intents nativos
2. **iOS:** Usa AirPrint
3. **App PWA:** Convierte tu sitio en app con capacidades de impresión

## Configuración Recomendada para Tu Negocio

### Setup Básico (Presupuesto Bajo)
1. Impresora térmica USB genérica ($100 USD en MercadoLibre)
2. Usar método de impresión desde navegador
3. Conectar a una PC/tablet con Chrome

### Setup Profesional (Recomendado)
1. Impresora Epson TM-T20II ($200 USD)
2. Instalar librería ESC/POS en servidor
3. Agregar botón "Imprimir Automático" que imprime sin diálogo

### Setup Multi-Cocina
1. Impresora de red WiFi en cocina
2. Impresora USB en mostrador
3. Configurar para que pedidos de delivery vayan a cocina automáticamente

## Próximos Pasos

1. **Comprar impresora** → Recomiendo Epson TM-T20II o compatible
2. **Instalar driver** → Viene en CD o descargar de sitio del fabricante
3. **Probar impresión** → Desde tu panel admin, botón "Imprimir"
4. **Optimizar** → Si quieres automático, agregar librería ESC/POS

## Funcionalidades Implementadas

Tu sistema ya cuenta con:

1. **Vista de impresión optimizada** → `/admin/pedidos/imprimir/{id}` genera HTML listo para impresoras térmicas
2. **Edición de pedidos en tiempo real** → El admin puede modificar cantidades desde la interfaz móvil
3. **Gestión de stock automática** → Se descuenta al completar pedidos
4. **Agrupación de pedidos** → Los items del mismo cliente se muestran juntos para fácil preparación

El método `actualizarItem()` en `app/Controllers/Admin/Pedidos.php` ya está implementado y permite:
- Cambiar cantidades de items individuales
- Verificar stock disponible antes de modificar
- Eliminar items si la cantidad llega a 0
- Recalcular totales automáticamente

## Recursos

- [Librería PHP ESC/POS](https://github.com/mike42/escpos-php)
- [QZ Tray para JS](https://qz.io/)
- [Configurar Epson TM-T20](https://epson.com/Support/Printers/)

¿Necesitas ayuda con alguna configuración específica?
