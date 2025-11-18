# 🚀 Deploy en Hostinger - Resumen Rápido

## 📚 Documentación Disponible

Este proyecto incluye **3 guías completas** para deployment:

| Archivo | Descripción | Cuándo usar |
|---------|-------------|-------------|
| **[DEPLOY_HOSTINGER.md](DEPLOY_HOSTINGER.md)** | Guía completa paso a paso | Primera vez deployando |
| **[CHECKLIST_DEPLOY.md](CHECKLIST_DEPLOY.md)** | Checklist verificable | Durante el deploy |
| **[COMANDOS_HOSTINGER.sh](COMANDOS_HOSTINGER.sh)** | Script con comandos rápidos | Mantenimiento diario |

---

## ⚡ Quick Start (Resumen de 5 minutos)

### 1️⃣ **Subir código a GitHub**

```bash
# Si aún no lo hiciste
git remote add origin https://github.com/TU_USUARIO/labartola.git
git push -u origin main
```

### 2️⃣ **En Hostinger - Crear Base de Datos**

1. Panel → Bases de Datos MySQL
2. Crear nueva: `u123456789_labartola`
3. Usuario: `u123456789_user`
4. **Anotar contraseña**

### 3️⃣ **SSH a Hostinger**

```bash
ssh u123456789@tu-dominio.com
```

### 4️⃣ **Clonar e Instalar**

```bash
# Clonar
git clone https://github.com/TU_USUARIO/labartola.git ~/labartola
cd ~/labartola

# Instalar
composer install --no-dev --optimize-autoloader

# Configurar
cp .env.production.example .env
nano .env  # Editar credenciales
```

### 5️⃣ **Generar Clave**

```bash
php spark key:generate --show
# Copiar resultado a .env (encryption.key)
```

### 6️⃣ **Migraciones y Usuario**

```bash
php spark migrate --all
php spark shield:user create
php spark shield:user addgroup admin TU_EMAIL
```

### 7️⃣ **Configurar public_html**

```bash
cd ~
rm -rf public_html/*
ln -s ~/labartola/public/* ~/public_html/
ln -s ~/labartola/public/.htaccess ~/public_html/.htaccess
```

### 8️⃣ **Permisos**

```bash
chmod -R 775 ~/labartola/writable/
```

### 9️⃣ **Activar SSL**

1. Panel → SSL
2. Activar "SSL Gratuito"
3. Esperar 5-10 min

### 🎉 **LISTO!**

Visita: `https://tu-dominio.com`

---

## 🔄 Actualizar en el Futuro

```bash
ssh u123456789@tu-dominio.com
cd ~/labartola
git pull origin main
composer install --no-dev
php spark migrate
php spark cache:clear
```

O usa el script interactivo:

```bash
bash COMANDOS_HOSTINGER.sh
# Opción 2: Actualizar código
```

---

## 📋 Archivos de Configuración

### Para Producción (Hostinger):
- `.env.production.example` → Copiar a `.env` y editar

### Para Desarrollo (Local):
- `.env.example` → Ya configurado para Docker

---

## ❗ Problemas Comunes

| Problema | Solución Rápida |
|----------|----------------|
| Error 500 | `tail -f ~/labartola/writable/logs/log-*.log` |
| CSS no carga | `ln -sf ~/labartola/public/assets ~/public_html/assets` |
| BD no conecta | Verificar credenciales en `.env` |
| Página blanca | Cambiar a `CI_ENVIRONMENT = development` temporalmente |

**Ver más:** [DEPLOY_HOSTINGER.md - Sección Troubleshooting](DEPLOY_HOSTINGER.md#-troubleshooting)

---

## 📞 Necesitas Ayuda?

1. **Guía completa:** [DEPLOY_HOSTINGER.md](DEPLOY_HOSTINGER.md)
2. **Checklist:** [CHECKLIST_DEPLOY.md](CHECKLIST_DEPLOY.md)
3. **Comandos:** [COMANDOS_HOSTINGER.sh](COMANDOS_HOSTINGER.sh)
4. **Soporte Hostinger:** https://www.hostinger.com/support

---

## 🎯 Requerimientos Mínimos

- ✅ **Hosting:** Plan Business o superior (con SSH)
- ✅ **PHP:** 8.1 o superior
- ✅ **MySQL:** 5.7 o superior
- ✅ **Espacio:** Mínimo 500MB
- ✅ **Composer:** Instalado en el servidor

---

## 🔒 Seguridad en Producción

Antes de ir live, verifica:

- [x] SSL activado (HTTPS)
- [x] `.env` con `CI_ENVIRONMENT = production`
- [x] Contraseñas seguras
- [x] Headers de seguridad activos
- [x] Backups configurados

---

**Última actualización:** 2025-11-18
**Versión:** 1.0
**Estado:** ✅ Listo para deployment

**¡Éxito con tu deploy! 🚀**
