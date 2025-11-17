# Sistema de Notificaciones Toast - La Bartola

## 🎨 Sistema Unificado Implementado

Se ha implementado un sistema de notificaciones toast minimalista y consistente en toda la aplicación.

### Ubicación del Sistema
- **CSS y JavaScript**: [app/Views/layouts/main.php](app/Views/layouts/main.php)
- **Container**: Esquina superior derecha (fijo)
- **Auto-dismiss**: 4 segundos

### Tipos de Notificaciones

#### 1. **Success** (Verde)
```javascript
showToast('Operación exitosa', 'success');
```
- Fondo: Degradado verde (#28a745 → #20c997)
- Icono: check-circle-fill
- Uso: Guardado exitoso, eliminación, actualización

#### 2. **Error** (Rojo)
```javascript
showToast('Error al procesar', 'error');
```
- Fondo: Degradado rojo (#dc3545 → #c82333)
- Icono: x-circle-fill
- Uso: Errores, validación fallida

#### 3. **Warning** (Amarillo)
```javascript
showToast('Advertencia importante', 'warning');
```
- Fondo: Degradado amarillo (#ffc107 → #e0a800)
- Icono: exclamation-triangle-fill
- Color texto: Negro (mejor contraste)
- Uso: Advertencias, validaciones no críticas

#### 4. **Info** (Azul)
```javascript
showToast('Información útil', 'info');
```
- Fondo: Degradado azul (#17a2b8 → #117a8b)
- Icono: info-circle-fill
- Uso: Información general, ayuda

### Uso en Controladores

Los mensajes flash de sesión se muestran automáticamente como toast:

```php
// En cualquier controlador
return redirect()->to('/admin/inventario')
    ->with('success', 'Item agregado correctamente');

return redirect()->back()
    ->with('error', 'Error al procesar la solicitud');

return redirect()->to('/admin/pedidos')
    ->with('warning', 'Stock bajo detectado');

return redirect()->back()
    ->with('info', 'Procesando en segundo plano');
```

### Uso en JavaScript (AJAX)

```javascript
fetch('/admin/inventario/movimiento/' + itemId, {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        showToast(data.message, 'success');
        // Otras acciones...
    } else {
        showToast('Error: ' + data.message, 'error');
    }
})
.catch(error => {
    console.error('Error:', error);
    showToast('Error al procesar la solicitud', 'error');
});
```

### Características

✅ **Animaciones suaves**
- Entrada: Desliza desde la derecha
- Salida: Desliza hacia la derecha
- Duración: 300ms

✅ **Apilamiento automático**
- Múltiples toasts se apilan verticalmente
- Margen de 12px entre toasts

✅ **Cierre manual**
- Botón X en cada toast
- Hover aumenta opacidad

✅ **Responsive**
- Se adapta a pantallas móviles
- Z-index alto (9999) para estar siempre visible

### Migración de Alertas Antiguas

❌ **NO usar más:**
```html
<!-- Alertas Bootstrap tradicionales -->
<div class="alert alert-success">...</div>

<!-- JavaScript alerts -->
<script>
alert('Mensaje');
</script>
```

✅ **Usar ahora:**
```php
// En controladores
->with('success', 'Mensaje')

// En vistas JavaScript
showToast('Mensaje', 'success');
```

### CRUDs Actualizados

- ✅ Inventario
- ⏳ Pedidos
- ⏳ Caja Chica
- ⏳ Menú
- ⏳ Cupones
- ⏳ Caja
- ⏳ Analytics

### Mejoras Adicionales

#### Contraste de Colores
Se revisaron y mejoraron los contrastes en:
- Badges sobre fondos oscuros
- Texto en tablas
- Botones y enlaces
- Estados activo/inactivo

#### Accesibilidad
- Aria-label en botón de cerrar
- Iconos descriptivos
- Colores con buen contraste WCAG AA

---

**Última actualización:** 2025-11-10
**Versión:** 1.0
