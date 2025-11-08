# La Bartola - Guía para Agentes IA

## Descripción del Proyecto

**La Bartola** es una aplicación web de casa de comidas con sistema de delivery desarrollada en **CodeIgniter 4** con **PHP 8.2**. El proyecto está completamente dockerizado y funciona con MySQL 8.0, Apache y phpMyAdmin.

---

## Stack Tecnológico

### Backend
- **PHP**: 8.2
- **Framework**: CodeIgniter 4
- **Autenticación**: CodeIgniter Shield v1.2
- **ORM/Database**: CodeIgniter QueryBuilder + MySQLi
- **Dependencias principales**:
  - `codeigniter4/shield`: Sistema de autenticación
  - `codeigniter4/settings`: Gestión de configuraciones
  - `league/oauth2-google`: Login con Google OAuth

### Frontend
- **Bootstrap**: 5.3.3
- **Font**: Poppins (Google Fonts)
- **Icons**: Bootstrap Icons 1.11.3
- **JavaScript**: Vanilla JS + Fetch API

### Base de Datos
- **MySQL**: 8.0
- **Charset**: utf8mb4_unicode_ci
- **Engine**: InnoDB

### Infraestructura
- **Docker Compose**: Orchestración de servicios
- **Servicios**:
  - `labartola_mysql` - MySQL 8.0 (puerto 3307 → 3306)
  - `labartola_web` - PHP 8.2 + Apache (puerto 8080)
  - `labartola_phpmyadmin` - phpMyAdmin (puerto 8088)

---

## Estructura del Proyecto

```
labartola/
├── app/
│   ├── Config/
│   │   ├── Routes.php          # Rutas de la aplicación
│   │   └── ...
│   ├── Controllers/
│   │   ├── Home.php
│   │   ├── Auth.php
│   │   ├── carrito.php
│   │   ├── pedido.php
│   │   ├── Notificaciones.php
│   │   ├── MercadoPago.php
│   │   └── admin/
│   │       └── Cupones.php
│   ├── Models/
│   │   ├── UserModel.php
│   │   ├── PlatoModel.php
│   │   ├── PedidoModel.php
│   │   ├── CuponModel.php
│   │   └── NotificacionModel.php
│   ├── Views/
│   │   ├── layouts/
│   │   │   └── main.php        # Layout principal
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   └── register.php
│   │   ├── carrito/
│   │   │   └── index.php
│   │   └── ...
│   └── Database/
│       └── Migrations/
│           └── 2025-01-07-000001_CreateCuponesTable.php
├── docker/
│   ├── apache/
│   │   └── 000-default.conf   # Configuración Apache
│   └── mysql/
│       └── init.sql            # Script inicialización DB
├── public/
│   ├── index.php               # Entry point
│   └── assets/
│       └── images/
├── writable/
│   ├── logs/                   # Logs de CodeIgniter
│   ├── cache/
│   ├── session/
│   └── debugbar/
├── .env                        # Variables de entorno
├── docker-compose.yml          # Configuración Docker
├── Dockerfile                  # Imagen PHP + Apache
├── DOCKER_SETUP.md            # Documentación Docker
└── README_MERCADOPAGO.md      # Guía Mercado Pago
```

---

## Base de Datos

### Esquema Completo

#### Tabla: `users`
Sistema de usuarios con soporte para Google OAuth.

```sql
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('cliente','admin') NOT NULL DEFAULT 'cliente',
  `google_id` varchar(255) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `google_id` (`google_id`)
);
```

**Roles**:
- `cliente`: Usuario normal
- `admin`: Administrador del sistema

#### Tabla: `platos`
Catálogo de productos/comidas.

```sql
CREATE TABLE `platos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `imagen` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `stock_ilimitado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);
```

#### Tabla: `pedidos`
Órdenes de compra con geolocalización.

```sql
CREATE TABLE `pedidos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) unsigned DEFAULT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `telefono_cliente` varchar(20) NOT NULL,
  `direccion_entrega` text NOT NULL,
  `notas` text DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','en_preparacion','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `forma_pago` varchar(50) NOT NULL,
  `referencia_pago` varchar(255) DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
);
```

**Estados de pedido**:
- `pendiente`: Recién creado
- `en_preparacion`: En cocina
- `enviado`: En camino
- `entregado`: Completado
- `cancelado`: Cancelado

#### Tabla: `pedido_items`
Detalle de items por pedido.

```sql
CREATE TABLE `pedido_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) unsigned NOT NULL,
  `plato_id` int(11) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `notas` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`plato_id`) REFERENCES `platos` (`id`) ON DELETE CASCADE
);
```

#### Tabla: `cupones`
Sistema de cupones de descuento.

```sql
CREATE TABLE `cupones` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `tipo` enum('porcentaje','monto_fijo') NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `monto_minimo` decimal(10,2) DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `usos_maximos` int(11) DEFAULT NULL,
  `usos_por_usuario` int(11) DEFAULT 1,
  `usos_actuales` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `descripcion` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
);
```

**Tipos de cupón**:
- `porcentaje`: Descuento en % (ej: 15% off)
- `monto_fijo`: Descuento fijo (ej: $500 off)

#### Tabla: `cupones_usos`
Tracking de uso de cupones por usuario.

```sql
CREATE TABLE `cupones_usos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cupon_id` int(11) unsigned NOT NULL,
  `usuario_id` int(11) unsigned NOT NULL,
  `pedido_id` int(11) unsigned NOT NULL,
  `descuento_aplicado` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cupon_id`) REFERENCES `cupones` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE
);
```

#### Tabla: `notificaciones`
Sistema de notificaciones en tiempo real.

```sql
CREATE TABLE `notificaciones` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) unsigned DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `url` varchar(255) DEFAULT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

#### Tabla: `caja_turnos`
Sistema de caja registradora por turnos.

```sql
CREATE TABLE `caja_turnos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) unsigned NOT NULL COMMENT 'Usuario que abrió la caja',
  `fecha_apertura` datetime NOT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `monto_inicial` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_final` decimal(10,2) DEFAULT NULL,
  `monto_esperado` decimal(10,2) DEFAULT NULL COMMENT 'Monto según movimientos',
  `diferencia` decimal(10,2) DEFAULT NULL COMMENT 'Diferencia entre esperado y real',
  `estado` enum('abierta','cerrada') NOT NULL DEFAULT 'abierta',
  `notas_apertura` text DEFAULT NULL,
  `notas_cierre` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

#### Tabla: `caja_movimientos`
Movimientos de caja (ingresos, egresos, ventas).

```sql
CREATE TABLE `caja_movimientos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `caja_turno_id` int(11) unsigned NOT NULL,
  `tipo` enum('ingreso','egreso','venta') NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL COMMENT 'efectivo, qr, mercado_pago, tarjeta',
  `pedido_id` int(11) unsigned DEFAULT NULL,
  `usuario_id` int(11) unsigned NOT NULL,
  `notas` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`caja_turno_id`) REFERENCES `caja_turnos` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE SET NULL
);
```

**Tipos de movimiento**:
- `ingreso`: Entrada manual de dinero
- `egreso`: Salida manual de dinero
- `venta`: Ingreso por pedido

#### Tabla: `settings`
Configuraciones del sistema (CodeIgniter Settings library).

```sql
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(31) NOT NULL DEFAULT 'string',
  `context` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
);
```

**IMPORTANTE**: La columna `context` es requerida por CodeIgniter Settings y debe estar presente.

#### Tabla: `migrations`
Control de migraciones de CodeIgniter.

```sql
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);
```

---

## Configuración Docker

### Credenciales de Base de Datos

**Desde el host (tu máquina)**:
- Host: `localhost`
- Puerto: `3307`
- Usuario: `root`
- Password: `root_password_2024`
- Database: `labartola`

**Desde dentro de Docker (en .env)**:
- Host: `mysql` (nombre del servicio)
- Puerto: `3306`
- Usuario: `root`
- Password: `root_password_2024`
- Database: `labartola`

### Comandos Docker Útiles

```bash
# Levantar contenedores
docker-compose up -d

# Levantar y rebuildar
docker-compose up -d --build

# Ver logs
docker-compose logs -f
docker logs labartola_web
docker logs labartola_mysql

# Detener contenedores
docker-compose down

# Detener y eliminar volúmenes (CUIDADO: borra la DB)
docker-compose down -v

# Entrar al contenedor web
docker exec -it labartola_web bash

# Ejecutar comandos MySQL
docker exec labartola_mysql mysql -uroot -proot_password_2024 labartola -e "SHOW TABLES;"

# Ver contenedores activos
docker-compose ps
docker ps

# Reiniciar servicios
docker-compose restart web
docker-compose restart mysql
```

---

## Variables de Entorno (.env)

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

# Database (configurado para Docker)
database.default.hostname = mysql
database.default.database = labartola
database.default.username = root
database.default.password = root_password_2024
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306

# Google OAuth
GOOGLE_CLIENT_ID = 'TU_GOOGLE_CLIENT_ID_AQUI'
GOOGLE_CLIENT_SECRET = 'TU_GOOGLE_CLIENT_SECRET_AQUI'

# Mercado Pago
MERCADOPAGO_ACCESS_TOKEN = 'TU_ACCESS_TOKEN_AQUI'
MERCADOPAGO_PUBLIC_KEY = 'TU_PUBLIC_KEY_AQUI'
MERCADOPAGO_MODO = 'sandbox'  # sandbox o production
```

---

## Funcionalidades Implementadas

### 1. Sistema de Autenticación
- Login/Register tradicional
- Login con Google OAuth
- Gestión de sesiones con CodeIgniter Shield
- Roles: `cliente` y `admin`

### 2. Catálogo de Productos
- CRUD de platos/comidas
- Control de stock (normal e ilimitado)
- Disponibilidad en tiempo real
- Categorías
- Imágenes de productos

### 3. Carrito de Compras
- Agregar/quitar productos
- Modificar cantidades
- Persistencia en sesión
- Cálculo automático de totales

### 4. Sistema de Pedidos
- Creación de pedidos
- Tracking de estados
- Geolocalización (latitud/longitud)
- Historial de pedidos por usuario
- Notas especiales por item

### 5. Sistema de Cupones
- Cupones de descuento (% o monto fijo)
- Validación de vigencia
- Límite de usos totales
- Límite de usos por usuario
- Monto mínimo de compra
- Analytics de uso

### 6. Notificaciones en Tiempo Real
- Sistema de notificaciones por usuario
- Estados: leída/no leída
- Tipos personalizables
- URLs de acción
- Iconos personalizados

### 7. Sistema de Caja
- Apertura/cierre de turnos
- Registro de movimientos (ingresos, egresos, ventas)
- Métodos de pago múltiples
- Cálculo de diferencias
- Auditoría por usuario

### 8. Integración Mercado Pago (Pendiente)
- Pagos online
- Webhooks de notificación
- Modo sandbox/production

---

## Rutas Principales

### Públicas
- `GET /` - Homepage
- `GET /login` - Login
- `GET /register` - Registro
- `POST /auth/login` - Procesar login
- `POST /auth/register` - Procesar registro
- `GET /auth/google` - Login con Google

### Carrito
- `GET /carrito` - Ver carrito
- `POST /carrito/agregar` - Agregar producto
- `POST /carrito/actualizar` - Actualizar cantidades
- `POST /carrito/eliminar` - Eliminar producto

### Pedidos
- `POST /pedido/crear` - Crear pedido
- `GET /pedido/mis-pedidos` - Historial del usuario
- `GET /pedido/{id}` - Detalle de pedido

### Notificaciones
- `GET /notificaciones/stream` - SSE para tiempo real
- `POST /notificaciones/marcar-leida/{id}` - Marcar como leída

### Admin
- `GET /admin/cupones` - Gestión de cupones
- `POST /admin/cupones/crear` - Crear cupón
- `PUT /admin/cupones/{id}` - Editar cupón
- `DELETE /admin/cupones/{id}` - Eliminar cupón

---

## Problemas Conocidos y Soluciones

### 1. Error: "Unknown column 'context' in 'where clause'"
**Causa**: La tabla `settings` no tiene la columna `context` requerida por CodeIgniter Settings.

**Solución**: Ya está resuelto. La tabla en `docker/mysql/init.sql` tiene la estructura correcta.

### 2. Error: "Table doesn't exist"
**Causa**: El volumen de Docker tiene datos antiguos.

**Solución**:
```bash
docker-compose down -v
docker-compose up -d --build
```

### 3. Permisos en carpeta `writable`
**Causa**: Apache no tiene permisos de escritura.

**Solución**:
```bash
docker exec -it labartola_web bash
chown -R www-data:www-data /var/www/html/writable
chmod -R 775 /var/www/html/writable
```

### 4. Puerto 8080 ocupado
**Solución**: Cambiar el puerto en `docker-compose.yml`:
```yaml
ports:
  - "8081:80"  # Cambiar 8080 por 8081
```

---

## Logs y Debug

### Ver logs de CodeIgniter
```bash
# Desde el host
tail -f writable/logs/log-$(date +%Y-%m-%d).log

# Buscar errores
grep -i "error\|critical" writable/logs/log-$(date +%Y-%m-%d).log
```

### Ver logs de Apache
```bash
docker exec -it labartola_web tail -f /var/log/apache2/error.log
docker exec -it labartola_web tail -f /var/log/apache2/access.log
```

### Debug Bar de CodeIgniter
Ya está habilitado en desarrollo. Se muestra en la parte inferior de cada página.

---

## Guía Rápida para Nuevos Agentes

### Inicio Rápido

1. **Levantar el proyecto**:
```bash
cd C:\Dev\labartola
docker-compose up -d
```

2. **Acceder a la aplicación**: http://localhost:8080

3. **Acceder a phpMyAdmin**: http://localhost:8088

4. **Ver logs en tiempo real**:
```bash
docker-compose logs -f
```

### Comandos CodeIgniter desde Docker

```bash
# Entrar al contenedor
docker exec -it labartola_web bash

# Dentro del contenedor:
php spark migrate              # Correr migraciones
php spark db:seed UserSeeder   # Correr seeders
php spark list                 # Ver comandos disponibles
php spark routes               # Ver todas las rutas
```

### Estructura de Carpetas Importante

- `app/Controllers/` - Lógica de negocio
- `app/Models/` - Modelos de datos
- `app/Views/` - Vistas (templates)
- `app/Database/Migrations/` - Migraciones
- `public/assets/` - Assets estáticos
- `writable/logs/` - Logs de la aplicación
- `docker/` - Configuración Docker

### Convenciones de Código

- **Nombres de clases**: PascalCase (ej: `UserModel`, `PedidoController`)
- **Nombres de métodos**: camelCase (ej: `getUserById`, `crearPedido`)
- **Nombres de tablas**: snake_case plural (ej: `users`, `pedidos`, `pedido_items`)
- **Nombres de variables**: snake_case (ej: `$usuario_id`, `$total_pedido`)

### Git Workflow

Branch actual: `carlolivera`
Branch principal: `main`

```bash
# Ver estado
git status

# Crear commit
git add .
git commit -m "Descripción del cambio"

# Push
git push origin carlolivera

# Ver historial
git log --oneline -10
```

---

## Notas Importantes

1. **NUNCA hacer push --force** al branch main sin autorización
2. **SIEMPRE probar en local** antes de hacer commit
3. **Los archivos .env NO se commitean** (ya está en .gitignore)
4. **La carpeta writable/ está en .gitignore** excepto su estructura
5. **Usar docker-compose down -v con cuidado** (borra toda la base de datos)
6. **El puerto 3306 interno de MySQL se mapea al 3307 externo** para evitar conflictos

---

## Estado Actual del Proyecto (Última Actualización)

✅ **Funcionando correctamente**:
- Docker Compose con 3 servicios activos
- Base de datos MySQL con todas las tablas creadas
- Aplicación web accesible en http://localhost:8080
- phpMyAdmin accesible en http://localhost:8088
- Sistema de autenticación con Shield
- Login con Google OAuth configurado
- Sistema de cupones implementado
- Sistema de notificaciones implementado
- Sistema de caja implementado

⚠️ **Pendiente de configuración**:
- Mercado Pago (credenciales de producción)

🐛 **Bugs conocidos**: Ninguno reportado actualmente

---

## Changelog Reciente

### 2025-11-08
- ✅ Resuelto error "Unknown column 'context' in 'where clause'"
- ✅ Actualizada tabla `settings` con estructura correcta para CodeIgniter Settings
- ✅ Actualizado `docker/mysql/init.sql` con esquema completo
- ✅ Verificada aplicación funcionando correctamente en Docker

### 2025-11-07
- ✅ Configuración completa de Docker
- ✅ Creación de Dockerfile para PHP 8.2 + Apache
- ✅ Configuración de docker-compose.yml
- ✅ Script de inicialización de base de datos
- ✅ Documentación en DOCKER_SETUP.md

---

**Última actualización**: 8 de noviembre de 2025
**Versión**: 1.0
**Autor**: Claude Code Assistant
