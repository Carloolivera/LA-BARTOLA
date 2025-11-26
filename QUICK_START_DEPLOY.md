# ⚡ DEPLOY RÁPIDO EN HOSTINGER

## 📋 DATOS DE CONEXIÓN

- **URL**: https://labartola.store/
- **BD**: u806811297_labartola
- **Usuario BD**: u806811297_chlabartola
- **Password BD**: laBartola.123#

---

## 🚀 PASOS RÁPIDOS (15 minutos)

### 1️⃣ COMPRIMIR PROYECTO (2 min)

```powershell
# Desde C:\Dev\labartola
# Opción 1: Comprimir TODO
Compress-Archive -Path * -DestinationPath labartola-full.zip

# Opción 2: Sin vendor (más liviano, pero necesitarás composer después)
Compress-Archive -Path app,public,system,writable,composer.json,spark,.env.hostinger,.htaccess,DEPLOY_HOSTINGER.md -DestinationPath labartola.zip
```

**📦 Archivos a incluir**:
- ✅ app/
- ✅ public/
- ✅ system/
- ✅ writable/
- ✅ vendor/ (si comprimiste full)
- ✅ .htaccess (raíz)
- ✅ .env.hostinger
- ✅ composer.json
- ✅ spark

**❌ NO incluir**:
- .git/
- .env (local)
- docker-compose.yml
- node_modules/

---

### 2️⃣ SUBIR A HOSTINGER (3 min)

1. **Login en Hostinger**: https://hpanel.hostinger.com/
2. **Ir a**: Administrador de Archivos
3. **Navegar a**: `public_html/`
4. **Subir**: `labartola.zip` (arrastrar y soltar)
5. **Clic derecho** en `labartola.zip` → **Extraer**
6. **Eliminar**: `labartola.zip`

**✅ Resultado**: Debes tener en `public_html/`:
```
public_html/
├── app/
├── public/
├── system/
├── vendor/
├── writable/
├── .htaccess
├── .env.hostinger
├── composer.json
└── spark
```

---

### 3️⃣ CONFIGURAR .ENV (2 min)

1. **En Administrador de Archivos**, navegar a `public_html/`
2. **Clic derecho** en `.env.hostinger` → **Renombrar** → `.env`
3. **Clic derecho** en `.env` → **Editar**
4. **Verificar** que tenga:

```env
CI_ENVIRONMENT = production
app.baseURL = 'https://labartola.store/'

database.default.hostname = localhost
database.default.database = u806811297_labartola
database.default.username = u806811297_chlabartola
database.default.password = laBartola.123#
```

5. **Guardar y cerrar**

---

### 4️⃣ IMPORTAR BASE DE DATOS (3 min)

#### A. Exportar desde local

```bash
# Desde tu PC local
docker exec -it labartola-mysql mysqldump -u root -proot_password_2024 labartola > labartola_backup.sql
```

#### B. Importar en Hostinger

1. **hPanel** → **Bases de Datos** → **phpMyAdmin**
2. **Seleccionar**: `u806811297_labartola`
3. **Importar** → **Elegir archivo** → `labartola_backup.sql`
4. **Continuar**
5. **Esperar** a que termine (✅ Importación finalizada)

---

### 5️⃣ INSTALAR COMPOSER (2 min)

#### Opción A: Si subiste vendor/
✅ Ya está listo, saltar este paso

#### Opción B: Si NO subiste vendor/

**SSH** (si lo tienes habilitado):
```bash
ssh u806811297@labartola.store
cd public_html/
composer install --no-dev --optimize-autoloader
```

**Sin SSH**:
- Comprimir `vendor/` de tu local
- Subir a Hostinger
- Extraer en `public_html/vendor/`

---

### 6️⃣ CONFIGURAR PERMISOS (1 min)

**En Administrador de Archivos**:

1. **Clic derecho** en carpeta `writable/` → **Permisos**
2. **Cambiar a**: `755`
3. **✅ Aplicar a subdirectorios**
4. **Guardar**

**Si tienes SSH**:
```bash
chmod -R 755 writable/
chmod -R 755 public/assets/images/
chmod 644 .env
```

---

### 7️⃣ CONFIGURAR PHP (2 min)

1. **hPanel** → **Avanzado** → **Configuración de PHP**
2. **Seleccionar PHP**: `8.1` o superior
3. **Guardar**

**Verificar extensiones activas** (deben estar ✅):
- mysqli
- gd
- mbstring
- xml
- curl
- intl

---

## ✅ VERIFICAR QUE FUNCIONA

### Test 1: Página principal
```
https://labartola.store/
```
**Debe mostrar**: Menú de platos

### Test 2: Login
```
https://labartola.store/login
```
**Debe mostrar**: Formulario de login

### Test 3: Admin
```
https://labartola.store/admin
```
**Debe**: Redirigir a login

---

## 🐛 PROBLEMAS COMUNES

### ❌ Error 500

**Solución 1**: Permisos
```bash
chmod -R 755 writable/
```

**Solución 2**: Verificar .env
- Abrir `.env` y verificar credenciales

### ❌ Página en blanco

**Causa**: Document Root incorrecto

**Solución**: El `.htaccess` en la raíz ya redirige a `/public`

**Alternativa**:
1. **hPanel** → **Sitios Web** → **labartola.store**
2. **Buscar**: "Document Root" o "Carpeta raíz"
3. **Cambiar a**: `public_html/public`
4. **Guardar**

### ❌ CSS/JS no cargan

**Verificar** `app.baseURL` en `.env`:
```env
app.baseURL = 'https://labartola.store/'
# IMPORTANTE: Debe terminar con /
```

### ❌ Error de conexión a BD

**Test de conexión**:
Crear `test_db.php` en `public/`:

```php
<?php
$conn = new mysqli('localhost', 'u806811297_chlabartola', 'laBartola.123#', 'u806811297_labartola');
if ($conn->connect_error) {
    die("❌ Error: " . $conn->connect_error);
}
echo "✅ Conexión exitosa!";
$conn->close();
```

Abrir: https://labartola.store/test_db.php

**¡Eliminar después de probar!**

---

## 🔒 SEGURIDAD POST-DEPLOY

### 1. Verificar .env no es accesible
```
https://labartola.store/.env
```
**Debe dar**: 403 Forbidden

### 2. Activar HTTPS
1. **hPanel** → **SSL/TLS**
2. **Activar**: "Force HTTPS"

### 3. Cambiar contraseña admin
1. Login en `/admin`
2. Cambiar contraseña por defecto

---

## 📊 CHECKLIST FINAL

- [ ] ✅ Archivos subidos a public_html/
- [ ] ✅ .env creado (de .env.hostinger)
- [ ] ✅ Base de datos importada
- [ ] ✅ vendor/ instalado
- [ ] ✅ Permisos writable/ = 755
- [ ] ✅ PHP 8.1+ configurado
- [ ] ✅ https://labartola.store/ funciona
- [ ] ✅ /login funciona
- [ ] ✅ /admin funciona
- [ ] ✅ CSS y JS cargan
- [ ] ✅ .env no es accesible
- [ ] ✅ HTTPS forzado

---

## 🎉 ¡LISTO!

Tu app está en vivo en:
**https://labartola.store/**

**Documentación completa**: Ver [DEPLOY_HOSTINGER.md](DEPLOY_HOSTINGER.md)

**Soporte Hostinger**: Chat 24/7 en https://www.hostinger.com/
