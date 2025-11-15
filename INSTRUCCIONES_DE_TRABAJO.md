# Instrucciones de Trabajo - La Bartola

## Estado Actual del Proyecto

✅ **Todo está funcionando correctamente**
- Branch activo: `carlolivera`
- Docker funcionando en: http://localhost:8080
- phpMyAdmin disponible en: http://localhost:8088
- Base de datos MySQL en puerto: 3307

---

## Cómo Trabajar

### Opción 1: Trabajar con Docker (RECOMENDADO)

#### Iniciar el proyecto
```bash
docker-compose up -d
```

#### Verificar que todo está corriendo
```bash
docker-compose ps
```

Deberías ver 3 contenedores:
- `labartola_mysql` (healthy)
- `labartola_web`
- `labartola_phpmyadmin`

#### Acceder a la aplicación
- Aplicación web: http://localhost:8080
- phpMyAdmin: http://localhost:8088
  - Usuario: `root`
  - Password: `root_password_2024`

#### Ver logs si algo falla
```bash
docker-compose logs -f web
docker-compose logs -f mysql
```

#### Detener el proyecto
```bash
docker-compose down
```

#### Ejecutar comandos dentro del contenedor web
```bash
docker exec -it labartola_web bash
```

---

### Opción 2: Trabajar con PHP Local (sin Docker)

Si prefieres usar tu PHP local instalado en Windows:

#### 1. Renombrar archivos .env
```bash
# Renombrar el .env actual (configurado para Docker)
mv .env .env.docker

# Usar la configuración para host local
mv .env.local .env
```

#### 2. Asegurarte que MySQL esté corriendo en Docker
```bash
docker-compose up -d mysql phpmyadmin
```

#### 3. Trabajar normalmente con PHP local
```bash
php spark serve
php spark migrate
# etc...
```

#### 4. Cuando termines, volver a la configuración Docker
```bash
mv .env .env.local
mv .env.docker .env
```

---

## Comandos Útiles

### Migraciones de Base de Datos
```bash
# Ver estado de migraciones
php spark migrate:status

# Ejecutar migraciones pendientes
php spark migrate

# Revertir última migración
php spark migrate:rollback
```

### Limpiar cache
```bash
php spark cache:clear
```

### Ver rutas disponibles
```bash
php spark routes
```

---

## Archivos de Configuración Importantes

### `.env` (Actual)
Configurado para trabajar desde Docker:
- `database.default.hostname = mysql`
- `database.default.port = 3306`

### `.env.local` (Para PHP local)
Configurado para trabajar desde el host:
- `database.default.hostname = 127.0.0.1`
- `database.default.port = 3307`

### `docker-compose.yml`
Configuración de contenedores Docker:
- MySQL: puerto 3307 (externo) → 3306 (interno)
- Web: puerto 8080 (externo) → 80 (interno)
- phpMyAdmin: puerto 8088 (externo) → 80 (interno)

---

## Estructura del Proyecto

```
labartola/
├── app/
│   ├── Controllers/         # Controladores
│   │   ├── admin/          # Panel administrativo
│   │   │   ├── CajaChica.php
│   │   │   ├── Inventario.php
│   │   │   ├── Caja.php
│   │   │   ├── Cupones.php
│   │   │   └── Analytics.php
│   │   ├── carrito.php
│   │   └── pedido.php
│   ├── Models/             # Modelos de datos
│   ├── Views/              # Vistas (HTML/PHP)
│   └── Config/             # Configuraciones
├── public/                 # Archivos públicos (CSS, JS, imágenes)
├── writable/               # Logs, cache, sesiones
├── vendor/                 # Dependencias de Composer
├── .env                    # Variables de entorno (DOCKER)
├── .env.local              # Variables de entorno (PHP LOCAL)
└── docker-compose.yml      # Configuración Docker
```

---

## Solución de Problemas

### Error: "Unable to connect to the database"

**Si estás usando Docker:**
```bash
# Verifica que MySQL esté corriendo
docker-compose ps

# Verifica que el .env tenga hostname = mysql
cat .env | grep hostname
```

**Si estás usando PHP local:**
```bash
# Verifica que estés usando .env.local
cat .env | grep hostname
# Debería mostrar: database.default.hostname = 127.0.0.1

# Verifica que MySQL en Docker esté corriendo
docker-compose ps mysql
```

### Error: "Cache unable to write"
```bash
# En Windows, dar permisos al directorio writable
icacls writable /grant Everyone:F /T
```

### Docker no levanta
```bash
# Ver logs detallados
docker-compose logs

# Reiniciar todo desde cero
docker-compose down
docker-compose up -d --build
```

---

## Git - Estado Actual

- **Branch actual:** `carlolivera`
- **Rebase completado:** ✅
- **Conflictos resueltos:** ✅
- **Todo commiteado:** ✅

### Para hacer push
```bash
git status
git push origin carlolivera
```

### Para crear un pull request
```bash
gh pr create --title "Tu título" --body "Descripción"
```

---

## Próximos Pasos

1. ✅ Proyecto funcionando con Docker
2. ✅ Base de datos configurada
3. ⏳ Continuar desarrollo de features
4. ⏳ Testing
5. ⏳ Deploy a producción

---

## Contacto y Ayuda

Si algo no funciona:
1. Verifica los logs: `docker-compose logs -f`
2. Verifica el estado: `docker-compose ps`
3. Reinicia los contenedores: `docker-compose restart`
4. Si nada funciona: `docker-compose down && docker-compose up -d --build`

**Última actualización:** 2025-11-15
