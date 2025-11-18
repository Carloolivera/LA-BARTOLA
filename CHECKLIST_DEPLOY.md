# ✅ Checklist de Deploy - Hostinger

## 📋 ANTES DE SUBIR A HOSTINGER

### 1. Verificaciones Locales
- [ ] Todo funciona correctamente en local
- [ ] No hay errores en logs (`writable/logs/`)
- [ ] Velocidad < 2 segundos
- [ ] Commits actualizados en Git

### 2. Preparar Repositorio
- [ ] Subir código a GitHub/GitLab/Bitbucket
- [ ] Verificar que `.env` NO está en el repositorio
- [ ] Verificar que `vendor/` NO está en el repositorio
- [ ] README.md actualizado

---

## 🌐 EN HOSTINGER

### 3. Configuración Inicial
- [ ] Plan con SSH activado
- [ ] PHP 8.1 o superior configurado
- [ ] Base de datos MySQL creada
- [ ] Usuario de BD creado
- [ ] Anotar credenciales de BD

### 4. Acceso SSH
- [ ] SSH habilitado en hPanel
- [ ] Conexión SSH exitosa
- [ ] Composer disponible/instalado

### 5. Instalación
- [ ] Repositorio clonado
- [ ] `composer install` ejecutado
- [ ] Archivo `.env` creado y configurado
- [ ] Clave de encriptación generada
- [ ] Migraciones ejecutadas (`php spark migrate --all`)
- [ ] Usuario admin creado

### 6. Configuración Web
- [ ] `public_html/` configurado (symlink o copia)
- [ ] `.htaccess` en su lugar
- [ ] Archivos estáticos accesibles
- [ ] Permisos correctos (775 para writable)

### 7. SSL y Seguridad
- [ ] SSL activado (Let's Encrypt)
- [ ] HTTPS funcionando
- [ ] Force HTTPS activado en `.env`
- [ ] Headers de seguridad verificados

### 8. Verificaciones Finales
- [ ] Sitio carga correctamente
- [ ] Login funciona
- [ ] Imágenes se ven
- [ ] Admin panel accesible
- [ ] No hay errores 500/404
- [ ] Logs sin errores críticos

---

## 🔄 DESPUÉS DEL DEPLOY

### 9. Testing en Producción
- [ ] Probar flujo completo de pedido
- [ ] Verificar notificaciones (si aplica)
- [ ] Probar login con Google OAuth (si aplica)
- [ ] Verificar velocidad de carga
- [ ] Probar en móvil

### 10. Monitoreo
- [ ] Configurar monitoreo de uptime
- [ ] Revisar logs diariamente
- [ ] Configurar backups automáticos

---

## 📝 DATOS IMPORTANTES A ANOTAR

```
HOSTINGER:
├── Usuario SSH: ___________________
├── Host SSH: ______________________
├── Directorio proyecto: ___________
│
DATABASE:
├── Nombre BD: _____________________
├── Usuario BD: ____________________
├── Contraseña: ____________________
├── Host: localhost
│
DOMINIO:
├── URL: https://___________________
│
ADMIN:
├── Email: _________________________
├── Contraseña: ____________________
│
GIT:
└── Repositorio: ___________________
```

---

## ❗ EN CASO DE PROBLEMAS

### Error 500
1. Verificar logs: `tail -f ~/labartola/writable/logs/log-*.log`
2. Verificar permisos: `chmod -R 775 ~/labartola/writable/`
3. Verificar `.env` configurado correctamente

### Base de datos no conecta
1. Verificar credenciales en `.env`
2. Probar conexión: `mysql -u USUARIO -p BASE_DATOS`
3. Verificar que el usuario tenga permisos

### CSS/JS no cargan
1. Verificar symlink: `ls -la ~/public_html/assets`
2. Verificar `.htaccess` presente
3. Re-enlazar si es necesario

### Página en blanco
1. Activar display_errors temporalmente
2. Cambiar `.env` a: `CI_ENVIRONMENT = development`
3. Ver el error completo
4. Corregir y volver a production

---

## 📞 SOPORTE

- **Hostinger:** https://www.hostinger.com/support
- **CodeIgniter:** https://codeigniter.com/user_guide/
- **GitHub del proyecto:** [TU_REPO_AQUI]

---

**Última actualización:** 2025-11-18
