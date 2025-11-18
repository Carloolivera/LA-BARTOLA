# Mejoras de Seguridad y Optimización - La Bartola

Documento generado: 2025-11-18

## 1. ARCHIVOS ELIMINADOS (Limpieza)

Se eliminaron los siguientes archivos no utilizados o duplicados:

- `add_cliente.sql` - Migración manual obsoleta (ya existe migración oficial)
- `add_cliente_group.php` - Script auxiliar obsoleto
- `labartola (1).sql` - Dump de BD duplicado
- `builds` - Script sin extensión de fecha antigua
- `agents.md` - Documentación desactualizada
- `docker/mysql/insert_platos_ejemplo.sql` - Script de ejemplo no necesario
- `docker/mysql/update_platos_imagenes.sql` - Script de ejemplo no necesario

## 2. OPTIMIZACIÓN DE VELOCIDAD

### 2.1 Externalización de CSS y JavaScript

**Archivos creados:**
- `/public/assets/css/home.css` - Estilos de la página principal (antes inline)
- `/public/assets/css/main.css` - Estilos del layout principal (antes inline)
- `/public/assets/js/home.js` - JavaScript de la página principal (antes inline)
- `/public/assets/js/main.js` - JavaScript del layout principal (antes inline)

**Beneficios:**
- ✅ Mejor caché del navegador (CSS/JS se cachean por 1 mes)
- ✅ Reducción del tamaño del HTML
- ✅ Mejor compresión GZIP
- ✅ Tiempo de carga inicial más rápido
- ✅ Código más mantenible y organizado

### 2.2 Configuración de Caché en .htaccess

**Optimizaciones implementadas:**

```apache
# Imágenes: 1 año de caché
ExpiresByType image/jpeg "access plus 1 year"
ExpiresByType image/png "access plus 1 year"

# CSS y JavaScript: 1 mes de caché
ExpiresByType text/css "access plus 1 month"
ExpiresByType application/javascript "access plus 1 month"

# HTML: sin caché (siempre actualizado)
ExpiresByType text/html "access plus 0 seconds"
```

### 2.3 Compresión GZIP

Activada compresión para:
- HTML, CSS, JavaScript
- JSON, XML
- Fuentes web
- SVG

**Reducción estimada:** 60-80% en tamaño de archivos de texto

## 3. MEJORAS DE SEGURIDAD

### 3.1 Headers de Seguridad (`.htaccess`)

```apache
# XSS Protection
Header set X-XSS-Protection "1; mode=block"

# Prevent MIME sniffing
Header set X-Content-Type-Options "nosniff"

# Clickjacking Protection
Header set X-Frame-Options "SAMEORIGIN"

# Referrer Policy
Header set Referrer-Policy "strict-origin-when-cross-origin"

# Permissions Policy
Header set Permissions-Policy "geolocation=(), microphone=(), camera=()"

# Remove Server Info
Header unset Server
Header unset X-Powered-By
```

**Protecciones activadas:**

| Header | Protección contra |
|--------|-------------------|
| `X-XSS-Protection` | Ataques XSS (Cross-Site Scripting) |
| `X-Content-Type-Options` | MIME type sniffing |
| `X-Frame-Options` | Clickjacking |
| `Referrer-Policy` | Fuga de información de referencia |
| `Permissions-Policy` | Acceso no autorizado a sensores |

### 3.2 Filtros de Seguridad de CodeIgniter

**Archivo:** `app/Config/Filters.php`

**Cambios realizados:**

```php
public array $globals = [
    'before' => [
        'invalidchars',  // ✅ ACTIVADO: Bloquea caracteres inválidos
    ],
    'after' => [
        'toolbar',
        'secureheaders', // ✅ ACTIVADO: Headers de seguridad adicionales
    ],
];
```

### 3.3 CSRF Protection Mejorado

**Archivo:** `app/Config/Security.php`

```php
public bool $tokenRandomize = true; // ✅ CAMBIADO de false a true
```

**Mejora:** Los tokens CSRF ahora se regeneran con cada request, haciendo más difícil los ataques CSRF.

### 3.4 Protección de Archivos Sensibles

```apache
# Bloquear acceso a archivos de configuración y backups
<FilesMatch "(^#.*#|\.(bak|conf|dist|fla|in[ci]|log|orig|psd|sh|sql|sw[op])|~)$">
    Require all denied
</FilesMatch>

# Bloquear ejecución de scripts en carpeta de uploads
<FilesMatch "\.(php|php3|php4|php5|phtml|pl|py|jsp|asp|sh|cgi)$">
    Redirect 404 /
</FilesMatch>
```

### 3.5 Seguridad Existente en el Código

El proyecto ya implementa buenas prácticas:

✅ **Escape de salida:** Uso consistente de `esc()` en vistas
✅ **Query Builder:** Se usa el Query Builder de CodeIgniter (protege contra SQL Injection)
✅ **Validación de entrada:** Validación en controladores (`$this->validate()`)
✅ **Autenticación robusta:** CodeIgniter Shield con grupos de usuarios
✅ **Control de acceso:** Filtros por roles (admin, vendedor, cliente)
✅ **Sanitización:** `esc()` en formularios de finalización de pedido

## 4. MEDIDAS DE SEGURIDAD ADICIONALES RECOMENDADAS

### 4.1 Para Producción

1. **HTTPS obligatorio:**
   - Activar filtro `forcehttps` en `Filters.php`
   - Obtener certificado SSL (Let's Encrypt gratuito)

2. **Variables de entorno:**
   - ✅ `.env` ya está en `.gitignore`
   - Verificar que no haya credenciales hardcodeadas

3. **Logs de seguridad:**
   - Revisar regularmente `writable/logs/`
   - Configurar rotación de logs

4. **Copias de seguridad:**
   - Automatizar backups de BD
   - Excluir `public/assets/images/platos/` del repositorio (ya configurado)

### 4.2 Monitoreo

- Implementar rate limiting para endpoints públicos
- Considerar WAF (Web Application Firewall) como Cloudflare
- Monitorear intentos de acceso a rutas admin

## 5. PERFORMANCE ESPERADO

### Antes de las optimizaciones:
- CSS inline: ~15KB no cacheado por petición
- JS inline: ~8KB no cacheado por petición
- Sin compresión GZIP
- Sin headers de caché

### Después de las optimizaciones:
- CSS externo: ~15KB cacheado 1 mes (solo 1 petición)
- JS externo: ~8KB cacheado 1 mes (solo 1 petición)
- Compresión GZIP: ~70% reducción en archivos de texto
- Imágenes: cacheadas 1 año
- **Mejora estimada en carga:** 40-60% más rápido en visitas repetidas

## 6. CHECKLIST DE VERIFICACIÓN

Antes de ir a producción, verificar:

- [ ] Cambiar `ENVIRONMENT` a `production` en `.env`
- [ ] Activar `forcehttps` en `Filters.php` si hay SSL
- [ ] Desactivar `toolbar` en producción
- [ ] Revisar que no haya `display_errors = On` en PHP
- [ ] Configurar backups automáticos de BD
- [ ] Probar formularios con CSRF habilitado
- [ ] Verificar que los headers de seguridad se envíen correctamente
- [ ] Probar caché de navegador con DevTools

## 7. RECURSOS Y REFERENCIAS

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [CodeIgniter 4 Security](https://codeigniter.com/user_guide/libraries/security.html)
- [MDN Security Headers](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers)
- [Google PageSpeed Insights](https://pagespeed.web.dev/)

---

**Última actualización:** 2025-11-18
**Responsable:** Claude Code
**Estado:** ✅ Implementado
