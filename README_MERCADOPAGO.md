# Integración de Mercado Pago - La Bartola

Esta guía te ayudará a integrar Mercado Pago como método de pago en tu aplicación.

## 📋 Requisitos Previos

1. ✅ Cuenta de Mercado Pago (Crear en [https://www.mercadopago.com.ar](https://www.mercadopago.com.ar))
2. ✅ Credenciales de API (Access Token y Public Key)

## 🔑 Paso 1: Obtener Credenciales

### 1.1 Acceder al Panel de Desarrolladores

1. Inicia sesión en tu cuenta de Mercado Pago
2. Ve a [https://www.mercadopago.com.ar/developers/panel](https://www.mercadopago.com.ar/developers/panel)
3. Haz clic en **"Tus integraciones"**

### 1.2 Crear una Aplicación

1. Haz clic en **"Crear aplicación"**
2. Completa los datos:
   - **Nombre de la aplicación**: `La Bartola`
   - **Tipo de producto**: Pagos online
   - **Modelo de integración**: Checkout Pro o Checkout API
3. Haz clic en **"Crear aplicación"**

### 1.3 Copiar Credenciales

En el panel de tu aplicación encontrarás:

- **Public Key**: `APP_USR-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx`
- **Access Token**: `APP_USR-xxxxxxxx-xxxxxxxxxxxxxxxxxxxx`

**⚠️ IMPORTANTE:**
- Usa las credenciales de **PRUEBA** para desarrollo
- Usa las credenciales de **PRODUCCIÓN** para tu sitio en vivo

## ⚙️ Paso 2: Configurar la Aplicación

### 2.1 Instalar SDK de Mercado Pago

Ejecuta en la terminal:

```bash
composer require mercadopago/dx-php
```

### 2.2 Actualizar el Archivo .env

Abre el archivo `.env` y agrega las siguientes líneas:

```ini
#--------------------------------------------------------------------
# MERCADO PAGO
#--------------------------------------------------------------------
MERCADOPAGO_ACCESS_TOKEN = 'TU_ACCESS_TOKEN_AQUI'
MERCADOPAGO_PUBLIC_KEY = 'TU_PUBLIC_KEY_AQUI'
MERCADOPAGO_MODO = 'sandbox'  # sandbox para pruebas, production para producción
```

Reemplaza con tus credenciales reales:

```ini
#--------------------------------------------------------------------
# MERCADO PAGO
#--------------------------------------------------------------------
MERCADOPAGO_ACCESS_TOKEN = 'APP_USR-1234567890123456-123456-abcdef1234567890abcdef1234567890-1234567890'
MERCADOPAGO_PUBLIC_KEY = 'APP_USR-12345678-1234-1234-1234-123456789012'
MERCADOPAGO_MODO = 'sandbox'
```

### 2.3 Reiniciar el Servidor

Si el servidor está corriendo, reinícialo para cargar las nuevas variables:

```bash
php spark serve
```

O si usas Docker:

```bash
docker-compose restart
```

## 💳 Paso 3: Usar Mercado Pago en tu Aplicación

### 3.1 Flujo de Pago

Cuando un usuario finaliza el carrito y selecciona "Mercado Pago" como forma de pago:

1. La aplicación crea una preferencia de pago en Mercado Pago
2. El usuario es redirigido al checkout de Mercado Pago
3. El usuario completa el pago
4. Mercado Pago redirige al usuario de vuelta a tu sitio
5. La aplicación recibe la confirmación del pago (webhook)
6. Se marca el pedido como "pagado"

### 3.2 Tarjetas de Prueba

Para probar en modo sandbox, usa estas tarjetas:

**Visa (Aprobada):**
- Número: `4509 9535 6623 3704`
- CVV: `123`
- Vencimiento: Cualquier fecha futura
- Nombre: `APRO`

**Mastercard (Rechazada):**
- Número: `5031 7557 3453 0604`
- CVV: `123`
- Vencimiento: Cualquier fecha futura
- Nombre: `OTHE`

**Mastercard (Pendiente):**
- Número: `5031 4332 1540 6351`
- CVV: `123`
- Vencimiento: Cualquier fecha futura
- Nombre: `CONT`

Más tarjetas de prueba: [https://www.mercadopago.com.ar/developers/es/docs/checkout-pro/additional-content/test-cards](https://www.mercadopago.com.ar/developers/es/docs/checkout-pro/additional-content/test-cards)

## 🔔 Paso 4: Configurar Webhooks (Notificaciones IPN)

Los webhooks permiten que Mercado Pago notifique a tu aplicación cuando cambia el estado de un pago.

### 4.1 URL de Webhook

Tu URL de webhook será: `https://tudominio.com/mercadopago/webhook`

Para desarrollo local, puedes usar **ngrok** para exponer tu servidor:

```bash
ngrok http 8080
```

Esto te dará una URL como: `https://abc123.ngrok.io`

### 4.2 Configurar en Mercado Pago

1. Ve al panel de tu aplicación en Mercado Pago
2. Ve a **"Webhooks"**
3. Haz clic en **"Configurar notificaciones"**
4. Configura:
   - **URL de notificación**: `https://tudominio.com/mercadopago/webhook` (o tu URL de ngrok)
   - **Eventos**: Selecciona "Pagos"
5. Guarda los cambios

## 🧪 Paso 5: Probar la Integración

### 5.1 Realizar un Pedido de Prueba

1. Agrega productos al carrito
2. Ve al carrito y haz clic en "Finalizar Pedido"
3. Selecciona "Mercado Pago" como forma de pago
4. Completa el formulario de entrega
5. Serás redirigido a Mercado Pago
6. Usa una tarjeta de prueba para pagar
7. Serás redirigido de vuelta a La Bartola
8. El pedido debería aparecer como "pagado" en el panel de admin

### 5.2 Verificar en el Panel de Mercado Pago

1. Ve a [https://www.mercadopago.com.ar/activities](https://www.mercadopago.com.ar/activities)
2. Verás el pago de prueba que realizaste
3. Verifica que el estado sea correcto

## 🚀 Paso 6: Pasar a Producción

Cuando estés listo para aceptar pagos reales:

### 6.1 Actualizar Credenciales

1. Ve al panel de Mercado Pago
2. Cambia a las credenciales de **PRODUCCIÓN**
3. Actualiza el archivo `.env`:

```ini
MERCADOPAGO_ACCESS_TOKEN = 'TU_ACCESS_TOKEN_PRODUCCION'
MERCADOPAGO_PUBLIC_KEY = 'TU_PUBLIC_KEY_PRODUCCION'
MERCADOPAGO_MODO = 'production'
```

### 6.2 Actualizar Webhook

Actualiza la URL de webhook con tu dominio de producción:

```
https://www.tudominio.com/mercadopago/webhook
```

### 6.3 Verificar Certificación

Mercado Pago puede requerir que tu aplicación pase un proceso de certificación antes de poder procesar pagos reales. Verifica los requisitos en:

[https://www.mercadopago.com.ar/developers/es/docs/checkout-pro/integration-test](https://www.mercadopago.com.ar/developers/es/docs/checkout-pro/integration-test)

## 🔒 Seguridad

### Buenas Prácticas

1. **NUNCA** expongas tu Access Token públicamente
2. **NUNCA** subas el archivo `.env` a Git (ya está en `.gitignore`)
3. Valida SIEMPRE las notificaciones de webhook con la firma de Mercado Pago
4. Usa HTTPS en producción (obligatorio por Mercado Pago)
5. Implementa límites de tasa para prevenir abuso

### Validar Webhooks

Cuando recibes una notificación de webhook, siempre verifica:

1. Que provenga de Mercado Pago (valida la IP)
2. Que la firma x-signature sea válida
3. Que el ID de pago exista en Mercado Pago (consulta la API)

## 🐛 Solución de Problemas

### Error: "Invalid credentials"

**Causa:** Access Token incorrecto o no configurado.

**Solución:**
1. Verifica que hayas copiado correctamente el Access Token
2. Asegúrate de estar usando las credenciales correctas (prueba/producción)
3. Verifica que el `.env` esté cargado correctamente

### Error: "Callback URL not set"

**Causa:** No se configuró la URL de retorno.

**Solución:**
Verifica que las URLs de `back_urls` estén configuradas en la preferencia.

### No se reciben notificaciones de webhook

**Causa:** URL de webhook incorrecta o no accesible.

**Solución:**
1. Verifica que la URL sea accesible públicamente
2. Usa ngrok para desarrollo local
3. Verifica los logs de Mercado Pago para ver errores

### Pago aprobado pero pedido no se actualiza

**Causa:** Webhook no está funcionando correctamente.

**Solución:**
1. Verifica los logs en `writable/logs/`
2. Asegúrate de que el controlador de webhook esté funcionando
3. Verifica que el pedido se esté actualizando en la base de datos

## 📚 Recursos Adicionales

- [Documentación oficial de Mercado Pago](https://www.mercadopago.com.ar/developers/es/docs)
- [SDK PHP de Mercado Pago](https://github.com/mercadopago/sdk-php)
- [Checkout Pro - Guía de integración](https://www.mercadopago.com.ar/developers/es/docs/checkout-pro/landing)
- [Testing con tarjetas de prueba](https://www.mercadopago.com.ar/developers/es/docs/checkout-pro/additional-content/test-cards)

## ❓ Preguntas Frecuentes

**¿Es gratis usar Mercado Pago?**
No, Mercado Pago cobra una comisión por cada transacción. Consulta las tarifas en: [https://www.mercadopago.com.ar/costs-section/](https://www.mercadopago.com.ar/costs-section/)

**¿Cuánto tiempo tardan en acreditarse los pagos?**
Los pagos tardan entre 1 y 14 días en acreditarse, dependiendo del método de pago. Consulta los tiempos en el panel de Mercado Pago.

**¿Puedo ofrecer cuotas sin interés?**
Sí, puedes configurar cuotas promocionales. Consulta la documentación de Mercado Pago.

**¿Funciona en localhost?**
Sí, pero necesitas usar ngrok u otra herramienta para exponer tu servidor local y recibir webhooks.

---

**¡Listo!** Ahora tu aplicación puede recibir pagos con Mercado Pago. 💰
