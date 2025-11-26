# 🐛 Problema: Error 404 en rutas de CodeIgniter en Hostinger

## 📋 Resumen
- ✅ **Home funciona:** `https://labartola.store/`
- ❌ **Carrito da 404:** `https://labartola.store/carrito`
- ❌ **Otras rutas dan 404:** `/login`, `/admin/caja-chica`

## 🔍 Diagnóstico

### Configuración actual:
- **Servidor:** Hostinger
- **Document Root:** `/home/u806811297/domains/labartola.store/public_html`
- **Framework:** CodeIgniter 4
- **Estructura:** `public_html/` (raíz) → `public_html/public/` (web root real)

### Estado:
- ✅ DotEnv se carga correctamente
- ❌ Variables de entorno **NO** se leen (env() retorna vacío)
- ⚠️ `baseURL` usa fallback: `http://localhost:8080/` en lugar de `https://labartola.store/`

## 🔧 Archivos Modificados

### 1. `app/Config/App.php`
```php
public string $baseURL;

public function __construct()
{
    parent::__construct();
    $this->baseURL = env('app.baseURL', 'http://localhost:8080/');
}

public string $indexPage = '';
```

### 2. `.htaccess` (raíz - `public_html/`)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Si el archivo existe en public/, servirlo directamente
    RewriteCond %{DOCUMENT_ROOT}/public%{REQUEST_URI} -f
    RewriteRule ^(.*)$ public/$1 [L]

    # Si el directorio existe en public/, servirlo directamente
    RewriteCond %{DOCUMENT_ROOT}/public%{REQUEST_URI} -d
    RewriteRule ^(.*)$ public/$1 [L]

    # Para todo lo demás, pasar a index.php de CodeIgniter
    RewriteRule ^(.*)$ public/index.php/$1 [L]
</IfModule>
```

### 3. `public/.htaccess`
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

## ❓ Problema Principal

**Las variables de entorno NO se están leyendo** a pesar de que DotEnv carga sin errores.

### Posibles causas:
1. El `.env` en Hostinger tiene formato incorrecto
2. Permisos del `.env` incorrectos
3. CodeIgniter no está parseando correctamente las variables
4. El `env()` helper no funciona fuera del contexto completo de CodeIgniter

## 🎯 Solución Propuesta

### Cambiar a `development` para ver errores:
```env
CI_ENVIRONMENT = development
```

### Hardcodear baseURL temporalmente:
```php
// En app/Config/App.php
public function __construct()
{
    parent::__construct();

    // TEMPORAL: Hardcodear hasta que env() funcione
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'labartola.store') {
        $this->baseURL = 'https://labartola.store/';
    } else {
        $this->baseURL = env('app.baseURL', 'http://localhost:8080/');
    }
}
```

## 📝 Próximos Pasos

1. ✅ Cambiar `.env` a `development`
2. ⏳ Subir `.env` actualizado a Hostinger
3. ⏳ Acceder a `/carrito` y ver el error completo
4. ⏳ Basado en el error, ajustar configuración

## 📂 Archivos a Subir

```
public_html/
├── .env                    ← Renombrar .env.hostinger y cambiar a development
├── .htaccess              ← Ya actualizado
├── app/Config/App.php     ← Ya actualizado
└── public/
    └── .htaccess          ← Ya actualizado
```
