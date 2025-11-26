# 🚀 INSTRUCCIONES DE DEPLOY - COPIA LIMPIA

## ✅ PREPARADO Y LISTO

Todo está configurado correctamente con:
- ✅ Carpetas en mayúscula: `Admin/`, `Auth/`
- ✅ Controladores en mayúscula: `Carrito.php`, `Home.php`
- ✅ `.htaccess` configurados para Hostinger
- ✅ `App.php` con detección automática de entorno
- ✅ `.env.hostinger` listo para renombrar

---

## 📦 ARCHIVO PARA SUBIR

**`DEPLOY_HOSTINGER.zip`** (18MB)

Contiene:
- `app/` (con Admin/ y Auth/ en mayúscula)
- `public/` (con check-structure.php para verificar)
- `writable/`
- `.htaccess` (raíz)
- `.env.hostinger`

---

## 🔥 DEPLOY LIMPIO (10 minutos)

### **PASO 1: LIMPIAR TODO EN HOSTINGER**

1. **Login:** https://hpanel.hostinger.com/
2. **Ir a:** Administrador de Archivos
3. **Navegar a:** `public_html/`
4. **ELIMINAR TODO** excepto:
   - `.htaccess` de Hostinger (si existe)
   - Cualquier archivo que NO sea de la aplicación

O directamente **ELIMINAR TODO** y empezar de cero.

---

### **PASO 2: SUBIR ARCHIVO**

1. **Arrastrar** `DEPLOY_HOSTINGER.zip` a `public_html/`
2. **Clic derecho** en el ZIP → **Extraer**
3. **Eliminar** el archivo `DEPLOY_HOSTINGER.zip`

**Resultado esperado:**
```
public_html/
├── .htaccess
├── .env.hostinger
├── app/
│   └── Controllers/
│       ├── Admin/          ← Mayúscula
│       ├── Auth/           ← Mayúscula
│       ├── Carrito.php
│       └── Home.php
├── public/
│   ├── assets/
│   ├── index.php
│   ├── check-structure.php
│   └── .htaccess
└── writable/
```

---

### **PASO 3: CONFIGURAR .ENV**

1. **Clic derecho** en `.env.hostinger` → **Renombrar** → `.env`
2. **Verificar contenido** (debe tener):
   ```env
   CI_ENVIRONMENT = production
   app.baseURL = 'https://labartola.store/'
   database.default.database = u806811297_labartola
   database.default.username = u806811297_chlabartola
   database.default.password = laBartola.123#
   ```

---

### **PASO 4: VERIFICAR ESTRUCTURA**

**Acceder a:** `https://labartola.store/check-structure.php`

**Debe mostrar:**
```
✅ Carpeta 'Auth/' existe correctamente
✅ Archivo 'Auth/LoginController.php' existe
✅ Carpeta 'Admin/' existe correctamente
✅ Archivo 'Admin/CajaChica.php' existe
✅ Archivo 'Admin/Categorias.php' existe
✅ Archivo 'Admin/Menu.php' existe
✅ Archivo 'Admin/Pedidos.php' existe
✅ Archivo 'Carrito.php' existe

✅ ✅ ✅ TODO CORRECTO ✅ ✅ ✅
```

**Si muestra errores:** Seguir las instrucciones que aparecen en pantalla.

---

### **PASO 5: IMPORTAR BASE DE DATOS**

1. **Exportar desde local:**
   ```bash
   docker exec -it labartola-mysql mysqldump -u root -proot_password_2024 labartola > backup.sql
   ```

2. **Importar en Hostinger:**
   - hPanel → Bases de Datos → phpMyAdmin
   - Seleccionar: `u806811297_labartola`
   - Importar → Elegir `backup.sql`
   - Continuar

---

### **PASO 6: CONFIGURAR PERMISOS**

En Administrador de Archivos:

1. **Carpeta `writable/`:**
   - Clic derecho → Permisos → `755`
   - ✅ Aplicar a subdirectorios

2. **Archivo `.env`:**
   - Clic derecho → Permisos → `644`

---

### **PASO 7: PROBAR**

1. **Home:**
   ```
   https://labartola.store/
   ```
   ✅ Debe mostrar menú de platos

2. **Carrito:**
   ```
   https://labartola.store/carrito
   ```
   ✅ Debe funcionar

3. **Login (5 clicks en logo):**
   ```
   https://labartola.store/login
   ```
   ✅ Debe mostrar formulario

4. **Caja Chica:**
   - Login como admin
   - Ir a: `https://labartola.store/admin/caja-chica`
   ✅ Debe funcionar

---

### **PASO 8: LIMPIEZA FINAL**

**Eliminar archivos de verificación:**
- `public_html/public/check-structure.php`

---

## 🐛 SI ALGO FALLA

### **Error: 404 en /carrito**
- Verificar que `check-structure.php` diga "TODO CORRECTO"
- Si no, eliminar carpetas `auth/` y `admin/` (minúsculas)

### **Error: 404 en /login**
- Verificar que exista `app/Controllers/Auth/LoginController.php`
- Verificar que NO exista `app/Controllers/auth/` (minúscula)

### **Error: Página en blanco**
- Cambiar `.env` a `development` para ver errores
- Verificar permisos de `writable/` (755)

### **Error: CSS no carga**
- Verificar que `.env` tenga `app.baseURL = 'https://labartola.store/'`
- Debe terminar con `/`

---

## 📋 CHECKLIST FINAL

- [ ] Todo eliminado de `public_html/`
- [ ] `DEPLOY_HOSTINGER.zip` subido y extraído
- [ ] `.env.hostinger` renombrado a `.env`
- [ ] `check-structure.php` dice "TODO CORRECTO"
- [ ] Base de datos importada
- [ ] Permisos configurados (writable=755, .env=644)
- [ ] `/` funciona (home)
- [ ] `/carrito` funciona
- [ ] `/login` funciona (5 clicks en logo)
- [ ] `/admin/caja-chica` funciona (después de login)
- [ ] `check-structure.php` eliminado

---

## ✅ LISTO

Tu aplicación está funcionando en producción en:
**https://labartola.store/**

**Usuario admin por defecto:** (el que tengas en la BD)

---

## 📞 SOPORTE

Si necesitas ayuda:
1. Activar modo `development` en `.env`
2. Ver error completo en pantalla
3. Revisar logs en `writable/logs/`
