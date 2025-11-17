# Problema de Carga Infinita - Sistema de Notificaciones

## Problema Identificado

El sistema de notificaciones SSE (Server-Sent Events) estaba causando que las páginas se queden cargando infinitamente.

### Causa Raíz:
- El método `stream()` en `Notificaciones.php` (línea 61) tiene un `while(true)`
- Este bucle infinito mantiene una conexión abierta permanentemente
- En desarrollo local con `php spark serve`, esto puede bloquear otras peticiones
- El servidor integrado de PHP es de un solo hilo y puede quedar bloqueado

## Solución Temporal (APLICADA)

✅ **Deshabilitado el sistema de notificaciones en `main.php`:**
```javascript
// iniciarNotificaciones(); // DESHABILITADO - Causa carga infinita
```

Ahora la aplicación debería funcionar normalmente sin notificaciones en tiempo real.

## Solución Permanente (Para implementar después)

Hay 3 opciones para arreglar las notificaciones:

### Opción 1: Polling en lugar de SSE (Más simple)
Cambiar de SSE a polling cada X segundos:

```javascript
// En lugar de EventSource, usar setInterval
setInterval(function() {
  fetch('<?= site_url('notificaciones/obtener') ?>')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        actualizarNotificaciones(data.notificaciones);
      }
    });
}, 30000); // Cada 30 segundos
```

**Ventajas:**
- Simple de implementar
- No bloquea el servidor
- Compatible con cualquier servidor

**Desventajas:**
- No es en tiempo real (hay delay de hasta 30s)
- Más peticiones al servidor

### Opción 2: SSE con timeout (Medio)
Modificar el método `stream()` para que cierre la conexión después de un tiempo:

```php
public function stream()
{
    // ... código existente ...

    $start_time = time();
    $max_duration = 30; // Cerrar después de 30 segundos

    while (true) {
        if (connection_aborted() || (time() - $start_time) > $max_duration) {
            break; // Cerrar conexión
        }

        // ... resto del código ...

        sleep(5); // Esperar 5 segundos entre checks
    }
}
```

**Ventajas:**
- Más eficiente que polling
- Cierra conexiones automáticamente

**Desventajas:**
- Requiere reconexión del cliente
- Puede seguir bloqueando en servidores de un hilo

### Opción 3: WebSockets con Ratchet (Avanzado)
Implementar WebSockets reales con una librería como Ratchet.

**Ventajas:**
- Notificaciones en tiempo real verdaderas
- No bloquea peticiones HTTP
- Escalable

**Desventajas:**
- Requiere servidor WebSocket separado
- Más complejo de configurar
- Necesita puerto adicional abierto

## Recomendación

Para desarrollo local con `php spark serve`:
- **Usar Opción 1 (Polling)** - Es la más simple y confiable

Para producción con Apache/Nginx:
- **Usar Opción 2 (SSE con timeout)** - Mejor balance entre simplicidad y rendimiento

## Para Reactivar Notificaciones

1. Elegir una de las opciones anteriores
2. Implementar los cambios
3. En `main.php` descomentar:
   ```javascript
   iniciarNotificaciones();
   ```

## Testing

Para probar que las notificaciones funcionen sin bloquear:
1. Abrir 2 pestañas del navegador
2. En ambas navegar por diferentes páginas simultáneamente
3. Si ambas funcionan sin bloquearse, el problema está resuelto

---

**Estado actual:** ✅ Notificaciones deshabilitadas, aplicación funcionando
**Próximo paso:** Implementar Opción 1 (Polling) cuando sea necesario
