# 🍴 La Bartola - Sistema de Gestión de Casa de Comidas con Delivery

![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.5-EE4623?style=flat-square&logo=codeigniter)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

Sistema web completo para gestión de restaurante/casa de comidas con funcionalidades de e-commerce, gestión de stock, pedidos online y sistema de delivery integrado con WhatsApp y geolocalización.

---

## 📋 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Roles y Permisos](#-roles-y-permisos)
- [Funcionalidades por Usuario](#-funcionalidades-por-usuario)
  - [Cliente](#-cliente-usuario-público-y-registrado)
  - [Vendedor](#-vendedor)
  - [Administrador](#-administrador)
- [Tecnologías](#️-tecnologías)
- [Instalación](#-instalación)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Base de Datos](#-base-de-datos)
- [Características Técnicas](#-características-técnicas)
- [Documentación Adicional](#-documentación-adicional)

---

## ✨ Características Principales

### 🛒 E-Commerce Completo
- Catálogo de productos con imágenes
- Carrito de compras con sesión persistente
- Sistema de pedidos con estados (Pendiente → En Proceso → Completado/Cancelado)
- Validación de stock en tiempo real
- Ofertas y promociones destacadas

### 📦 Gestión de Inventario Inteligente
- Stock ilimitado o limitado por plato
- Ajustes rápidos de stock (+1, +5, -1)
- Auto-deshabilitación cuando stock llega a 0
- Badges visuales de stock crítico (≤5 unidades)
- Descuento automático de stock al completar pedidos
- Devolución de stock al cancelar pedidos

### 📱 Integración con WhatsApp
- **Geolocalización GPS**: Los clientes pueden enviar su ubicación exacta por WhatsApp con un click
- **Sin APIs de pago**: Implementación 100% gratuita usando Geolocation API (HTML5)
- Botones directos para consultar ofertas
- Enlaces al número de WhatsApp del negocio

### 👥 Sistema de Usuarios Robusto
- Autenticación con CodeIgniter Shield
- 3 roles: Cliente, Vendedor, Administrador
- Login con email y password
- Gestión completa de usuarios desde admin

### 🎨 Diseño Moderno y Responsive
- Bootstrap 5.3
- Paleta de colores personalizada (Negro + Beige/Dorado)
- Animaciones CSS3 (float, pulse, hover effects)
- Iconos de Bootstrap Icons
- Mobile-first design

---

## 🔐 Roles y Permisos

| Funcionalidad | Cliente | Vendedor | Admin |
|--------------|---------|----------|-------|
| Ver menú público | ✅ | ✅ | ✅ |
| Agregar al carrito | ✅ | ✅ | ✅ |
| Realizar pedidos | ✅ | ✅ | ✅ |
| Ver mis pedidos | ✅ | ✅ | ✅ |
| Gestionar menú (CRUD platos) | ❌ | ✅ | ✅ |
| Ver todos los pedidos | ❌ | ✅ (solo lectura) | ✅ |
| Cambiar estado de pedidos | ❌ | ❌ | ✅ |
| Gestionar stock | ❌ | ❌ | ✅ |
| Gestionar usuarios | ❌ | ❌ | ✅ |

---

## 🎯 Funcionalidades por Usuario

### 👤 Cliente (Usuario Público y Registrado)

#### 🏠 Home Page
- **Barra de redes sociales superior**:
  - Instagram: [@labartolaok](https://instagram.com/labartolaok)
  - WhatsApp directo: [2241 517665](https://wa.me/542241517665)
  - Facebook
  - 📍 **Ubicación del local**: Link a Google Maps (Newbery 356, Buenos Aires)
  - 🚲 **Envío de ubicación**: Botón que solicita GPS y envía ubicación por WhatsApp

- **Hero Section**:
  - Logo animado con efecto flotación
  - Información del negocio (dirección, horario, teléfono)

- **Ofertas de la Semana**:
  - 🎁 Combo Familiar (-20%): 4 Empanadas + 2 Bebidas
  - ☕ Miércoles de Café (2x1)
  - 🚚 Envío Gratis en pedidos >$5000
  - Botones de WhatsApp directos para consultar

- **Menú Completo Integrado**:
  - Solo muestra platos disponibles Y con stock
  - Badges de "ÚLTIMAS X!" si stock ≤ 5
  - Categorías visuales con iconos
  - Precio formateado
  - Botón "Agregar al Carrito" con modal

#### 🛒 Carrito de Compras (`/carrito`)

**Sin necesidad de login:**
- Ver items agregados
- Actualizar cantidades (con validación de stock)
- Eliminar items individuales
- Vaciar carrito completo
- Ver total en tiempo real

**Validaciones automáticas:**
- ❌ Impide agregar más cantidad que el stock disponible
- ❌ Bloquea productos agotados
- ⚠️ Alerta si solo quedan pocas unidades
- ✅ Respeta productos con stock ilimitado

**Requiere login para:**
- Finalizar pedido

#### 📝 Finalizar Pedido

**Formulario completo:**
1. **A nombre de**: ¿Quién recibe el pedido?
2. **Tipo de entrega**:
   - 🚲 Delivery (muestra campo de dirección)
   - 🛍️ Para llevar (sin dirección)
3. **Dirección**: Si es delivery (validación condicional)
4. **Forma de pago**:
   - 💵 Efectivo
   - 📱 QR (abre imagen QR en nueva ventana)
   - 💳 Mercado Pago (muestra CBU y ALIAS)

**Proceso al confirmar:**
1. Guarda pedido en BD con estado "Pendiente"
2. Descuenta stock de cada plato
3. Marca como "No disponible" si stock llega a 0
4. Limpia el carrito
5. Muestra confirmación con datos de contacto

#### 📋 Mis Pedidos (`/pedido`)

**Vista completa de pedidos:**
- Lista todos los pedidos del usuario
- Información por pedido:
  - Imagen del plato
  - Nombre y cantidad
  - Total pagado
  - **Estado con badge de color**:
    - 🟡 Pendiente
    - 🔵 En Proceso
    - 🟢 Completado
    - 🔴 Cancelado
  - Notas del pedido (nombre, dirección, pago)
  - Fecha y hora

---

### 🏪 Vendedor

El vendedor tiene acceso a funcionalidades de gestión operativa sin acceso a configuraciones críticas.

#### 📖 Gestión de Menú (`/admin/menu`)

**Ver todos los platos:**
- Lista completa (disponibles y no disponibles)
- Cards con imagen, nombre, categoría, precio, stock
- Badges de disponibilidad (verde/gris)

**Crear plato nuevo** (`/admin/menu/crear`):

Formulario con validaciones:
- **Nombre**: 3-255 caracteres (obligatorio)
- **Categoría**: Entrada, Principal, Postre, Bebida, etc.
- **Descripción**: Texto libre
- **Precio**: Numérico (obligatorio)
- **Stock**: Entero ≥ 0 (obligatorio)
- **Stock ilimitado**: Checkbox
- **Disponible**: Checkbox
- **Imagen**: Archivo de imagen (obligatorio, sin límite de tamaño)

**Proceso de subida de imagen:**
```
1. Valida que sea imagen (jpg, jpeg, png, gif, webp)
2. Genera nombre único: [16_caracteres_hex]_[timestamp]_[random].ext
3. Guarda en: public/assets/images/platos/
4. Almacena nombre en BD
```

**Editar plato** (`/admin/menu/editar/:id`):
- Formulario pre-llenado
- Imagen opcional (puede mantener existente)
- Si sube nueva imagen: elimina la anterior

**Eliminar plato** (`/admin/menu/eliminar/:id`):
- Confirmación JavaScript
- Elimina imagen física del servidor
- Elimina registro de BD

#### 👀 Ver Pedidos (`/admin/pedidos`)

- **Solo lectura**: puede ver todos los pedidos
- No puede cambiar estados (solo admin)
- Puede filtrar por estado
- Puede ver detalles completos
- Puede imprimir tickets

---

### 👨‍💼 Administrador

El admin tiene acceso total al sistema.

#### 📦 Gestión de Stock (`/admin/stock`) - EXCLUSIVO ADMIN

**Vista de inventario:**
- Tabla ordenada por stock (críticos primero)
- Información por plato:
  - Imagen (60x60px)
  - Nombre y categoría
  - Precio
  - **Stock con badge de color**:
    - 🔴 0 unidades (Sin stock)
    - 🟡 1-5 unidades (Stock bajo)
    - 🔵 6+ unidades (Stock normal)
    - 🟢 ∞ (Stock ilimitado)
  - Estado de disponibilidad

**Ajustes rápidos (AJAX sin recargar):**
- Botón `-1`: Resta 1 unidad
- Botón `+1`: Suma 1 unidad
- Botón `+5`: Suma 5 unidades
- Auto-actualiza badges y colores
- Auto-gestiona disponibilidad

**Edición detallada** (`/admin/stock/editar/:id`):
- Campo de stock numérico
- Checkbox "Stock ilimitado"
- Checkbox "Disponible"
- **Lógica automática**:
  - Si stock > 0 → Marca como disponible
  - Si stock = 0 → Marca como no disponible
  - Si stock ilimitado → Siempre disponible

#### 📊 Gestión de Pedidos (`/admin/pedidos`)

**Vista completa de pedidos:**

Tabla con extracción inteligente de información:
- **ID del pedido**
- **Cliente**: username + email
- **A nombre de**: Extraído de notas con regex
- **Plato**: Nombre con imagen
- **Cantidad**: Badge azul
- **Total**: Verde, formateado
- **Tipo de entrega**:
  - 🚚 Delivery (con dirección)
  - 🛍️ Para llevar
- **Forma de pago**:
  - 💵 Efectivo
  - 📱 QR
  - 💳 Mercado Pago
- **Estado**: Dropdown editable
- **Fecha y hora**

**Filtros dinámicos:**
- Todos (con contador)
- Pendientes
- En Proceso
- Completados
- Cancelados

**Cambiar estado** (AJAX):

Endpoint: `POST /admin/pedidos/cambiarEstado/:id`

**Lógica de stock automática:**

1. **Al marcar como "Completado"** (si antes NO era completado):
   ```
   - Descuenta stock del plato
   - Registra en log
   - Respeta stock ilimitado (no descuenta)
   ```

2. **Al marcar como "Cancelado"** (si antes era "Completado"):
   ```
   - DEVUELVE stock al plato
   - Registra en log
   - Marca como disponible si aplica
   ```

**Otros botones:**
- 👁️ **Ver detalles**: Información completa del pedido
- 🖨️ **Imprimir ticket**: Formato para impresora térmica
- 🗑️ **Eliminar**: Elimina pedido (no afecta stock)

#### 👥 Gestión de Usuarios (`/usuario`) - EXCLUSIVO ADMIN

**Listar usuarios:**
- Tabla con: ID, Username, Email, Grupo, Estado, Fecha
- Filtros y búsqueda
- Acciones: Editar, Eliminar, Toggle Estado

**Crear usuario nuevo** (`/usuario/crear`):

Formulario con CodeIgniter Shield:
- **Username**: 3-30 caracteres, único
- **Email**: Válido, único
- **Password**: Mínimo 8 caracteres
- **Grupo**: admin / vendedor / cliente

**Editar usuario** (`/usuario/editar/:id`):
- Cambiar username
- Cambiar email
- Cambiar password (opcional)
- Cambiar grupo
- Activar/desactivar

**Validaciones de seguridad:**
- ❌ No puede eliminar su propio usuario
- ❌ No puede desactivar su propio usuario
- ✅ Eliminación en cascada (auth_identities, auth_groups_users)

**Toggle estado** (AJAX):
- Activa/desactiva usuarios con un click
- Sin recargar página

---

## 🛠️ Tecnologías

### Backend
- **PHP**: 8.1+
- **CodeIgniter**: 4.5.0
- **CodeIgniter Shield**: Sistema de autenticación oficial
- **MySQL**: 8.0+ o MariaDB

### Frontend
- **Bootstrap**: 5.3.0
- **Bootstrap Icons**: 1.11.3
- **JavaScript**: ES6+ (Vanilla JS, sin jQuery)
- **CSS3**: Animaciones y efectos modernos
- **Google Fonts**: Poppins

### APIs y Servicios (100% Gratuitos)
- **Geolocation API**: HTML5 nativo
- **WhatsApp URL Scheme**: Público
- **Google Maps URLs**: Gratuito para compartir ubicación

---

## 📥 Instalación

### Opción A: Con Docker (Recomendado)

Docker simplifica la instalación y garantiza un ambiente consistente.

#### Requisitos Previos
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado
- Git

#### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/Carloolivera/labartola.git
cd labartola
```

2. **Configurar variables de entorno**
```bash
cp .env.example .env
```

El archivo `.env.example` ya viene configurado para Docker. Si necesitas cambiar algo, edita `.env`:
```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

# Database (configurado para Docker)
database.default.hostname = mysql
database.default.database = labartola
database.default.username = root
database.default.password = root_password_2024
database.default.DBDriver = MySQLi
database.default.port = 3306
```

3. **Levantar los contenedores**
```bash
docker-compose up -d
```

Esto iniciará 3 servicios:
- **Web (CodeIgniter)**: http://localhost:8080
- **MySQL 8.0**: Puerto 3307 (host) / 3306 (contenedor)
- **phpMyAdmin**: http://localhost:8088

4. **Esperar a que MySQL esté listo**
```bash
# Verificar que los contenedores estén corriendo
docker-compose ps

# Ver logs de MySQL para confirmar que está listo
docker-compose logs mysql
```

5. **Ejecutar migraciones dentro del contenedor**
```bash
# Entrar al contenedor web
docker-compose exec web bash

# Ejecutar migraciones
php spark migrate --all
php spark shield:setup

# Salir del contenedor
exit
```

6. **Crear usuario admin inicial**
```bash
# Entrar al contenedor nuevamente
docker-compose exec web bash

# Crear usuario
php spark shield:user create
# Email: admin@labartola.com
# Username: admin
# Password: [tu_password_seguro]

# Asignar grupo admin
php spark shield:group add admin admin

# Salir
exit
```

7. **Acceder al sistema**
```
🌐 Aplicación: http://localhost:8080
🗄️ phpMyAdmin: http://localhost:8088
   - Usuario: root
   - Contraseña: root_password_2024
```

#### Comandos útiles Docker

```bash
# Ver logs en tiempo real
docker-compose logs -f web

# Detener contenedores
docker-compose down

# Reiniciar servicios
docker-compose restart

# Reconstruir contenedores (si cambias Dockerfile)
docker-compose build
docker-compose up -d

# Ejecutar comandos PHP dentro del contenedor
docker-compose exec web php spark [comando]

# Acceder a MySQL directamente
docker-compose exec mysql mysql -u root -proot_password_2024 labartola
```

---

### Opción B: Instalación Manual (Sin Docker)

Si prefieres no usar Docker, puedes instalar manualmente.

#### Requisitos Previos
```bash
- PHP >= 8.2
- Composer
- MySQL 8.0+ o MariaDB
- Apache/Nginx
- Extensiones PHP: intl, mbstring, gd, mysqli, json, zip, opcache
```

#### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/Carloolivera/labartola.git
cd labartola
```

2. **Instalar dependencias**
```bash
composer install
```

3. **Configurar variables de entorno**
```bash
cp .env.example .env
```

Editar `.env` para instalación local:
```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

# Database (localhost)
database.default.hostname = 127.0.0.1
database.default.database = labartola
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

4. **Crear base de datos**
```sql
CREATE DATABASE labartola CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

5. **Ejecutar migraciones**
```bash
php spark migrate --all
php spark shield:setup
```

6. **Crear usuario admin inicial**
```bash
php spark shield:user create
# Email: admin@labartola.com
# Username: admin
# Password: [tu_password_seguro]

php spark shield:group add admin admin
```

7. **Crear directorios necesarios**
```bash
mkdir -p public/assets/images/platos
chmod -R 755 public/assets/images
chmod -R 777 writable
```

8. **Iniciar servidor**
```bash
php spark serve
```

9. **Acceder al sistema**
```
http://localhost:8080
```

---

## 📂 Estructura del Proyecto

```
labartola/
├── app/
│   ├── Config/
│   │   └── Routes.php              # Definición de rutas
│   ├── Controllers/
│   │   ├── admin/
│   │   │   ├── menu.php            # CRUD de platos
│   │   │   ├── Stock.php           # Gestión de stock
│   │   │   └── pedidos.php         # Gestión de pedidos
│   │   ├── Home.php                # Página principal
│   │   ├── menu.php                # Menú público
│   │   ├── carrito.php             # Carrito de compras
│   │   ├── pedido.php              # Pedidos del cliente
│   │   └── Usuario.php             # Gestión de usuarios
│   ├── Filters/
│   │   └── AdminOrVendedorFilter.php
│   ├── Models/
│   │   ├── PlatoModel.php
│   │   └── PedidoModel.php
│   └── Views/
│       ├── layouts/
│       │   └── main.php            # Layout principal
│       ├── home.php                # Vista principal
│       ├── menu/
│       │   └── index.php
│       ├── carrito/
│       │   ├── index.php
│       │   └── qr.php
│       ├── pedido/
│       │   └── index.php
│       ├── admin/
│       │   ├── menu/               # CRUD platos
│       │   │   ├── index.php
│       │   │   ├── crear.php
│       │   │   └── editar.php
│       │   ├── stock/              # Gestión stock
│       │   │   ├── index.php
│       │   │   └── editar.php
│       │   └── pedidos/            # Gestión pedidos
│       │       ├── index.php
│       │       ├── ver.php
│       │       └── ticket.php
│       └── usuario/                # Gestión usuarios
│           ├── index.php
│           ├── crear.php
│           └── editar.php
├── public/
│   ├── assets/
│   │   └── images/
│   │       ├── platos/             # Imágenes de platos
│   │       ├── logo.png
│   │       └── qr-pago.png
│   └── .htaccess
├── writable/
│   ├── logs/
│   └── session/
├── README.md
├── README_UBICACION_WHATSAPP.md    # Doc de geolocalización
└── composer.json
```

---

## 🗄️ Base de Datos

### Tablas Principales

#### `users` (CodeIgniter Shield)
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) UNIQUE NOT NULL,
    active TINYINT(1) DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME
);
```

#### `auth_identities` (CodeIgniter Shield)
```sql
CREATE TABLE auth_identities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50), -- 'email_password', 'google', etc.
    secret VARCHAR(255), -- email o provider ID
    name VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### `auth_groups_users` (CodeIgniter Shield)
```sql
CREATE TABLE auth_groups_users (
    user_id INT NOT NULL,
    group VARCHAR(50) NOT NULL, -- 'admin', 'vendedor', 'cliente'
    PRIMARY KEY (user_id, group),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### `platos`
```sql
CREATE TABLE platos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(100),
    disponible TINYINT(1) DEFAULT 1,
    imagen VARCHAR(255),
    stock INT DEFAULT 0,
    stock_ilimitado TINYINT(1) DEFAULT 0,
    created_at DATETIME,
    updated_at DATETIME
);
```

#### `pedidos`
```sql
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    plato_id INT NOT NULL,
    cantidad INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'en_proceso', 'completado', 'cancelado') DEFAULT 'pendiente',
    notas TEXT, -- Formato estructurado con info de entrega
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (usuario_id) REFERENCES users(id),
    FOREIGN KEY (plato_id) REFERENCES platos(id)
);
```

### Diagrama Relacional

```
users (1) ←→ (N) auth_identities
users (1) ←→ (N) auth_groups_users
users (1) ←→ (N) pedidos
platos (1) ←→ (N) pedidos
```

---

## 🔧 Características Técnicas

### Validación de Stock Multicapa

El sistema implementa **5 capas de validación** para garantizar integridad:

1. **Al agregar al carrito**:
   ```php
   - Verifica disponibilidad
   - Verifica stock > 0 (si no es ilimitado)
   - Calcula cantidad total (carrito + nueva)
   - Impide superar stock disponible
   ```

2. **Al actualizar cantidad en carrito**:
   ```php
   - Re-consulta stock actual de BD
   - Valida nueva cantidad vs stock
   ```

3. **Al finalizar pedido (cliente)**:
   ```php
   - Descuenta stock por cada item
   - Marca como NO DISPONIBLE si llega a 0
   ```

4. **Al cambiar estado a "Completado" (admin)**:
   ```php
   - Descuenta stock NUEVAMENTE (doble validación)
   - Registra en log
   ```

5. **Al cancelar pedido "Completado" (admin)**:
   ```php
   - DEVUELVE stock al plato
   - Marca como DISPONIBLE si aplica
   ```

### Sistema de Geolocalización GPS

**Implementación sin costo:**

```javascript
function enviarUbicacion() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        // Éxito: obtiene coordenadas
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        // Construye URL de WhatsApp
        const mensaje = `Hola! Quiero hacer un pedido. Mi ubicación es:`;
        const mapsUrl = `https://maps.google.com/?q=${lat},${lng}`;
        const url = `https://wa.me/542241517665?text=${encodeURIComponent(mensaje)}%0A${encodeURIComponent(mapsUrl)}`;

        window.open(url, '_blank');
      },
      function(error) {
        // Fallback si usuario rechaza
        alert('No se pudo obtener ubicación');
        window.open('https://wa.me/542241517665', '_blank');
      }
    );
  }
}
```

**Ventajas:**
- 🆓 Costo: $0
- 🔒 Privacidad: ubicación va directo a WhatsApp, no se almacena
- 📱 Compatible: todos los navegadores modernos
- 🎯 Precisión: 5-50 metros con GPS

Ver documentación completa: [README_UBICACION_WHATSAPP.md](README_UBICACION_WHATSAPP.md)

### Seguridad

**Implementaciones de seguridad:**

1. **CSRF Protection**: Tokens en todos los formularios
2. **XSS Prevention**: Escapado de salida con `esc()`
3. **SQL Injection**: Query Builder con prepared statements
4. **Autenticación**: CodeIgniter Shield con bcrypt
5. **Autorización**: Filtros de grupo en rutas
6. **Validación de archivos**: Solo imágenes, nombres aleatorios
7. **Validación de datos**: Server-side en todos los endpoints

### Optimizaciones

- **AJAX sin recargar**: Carrito, stock, estados de pedidos
- **Carga condicional**: Solo muestra platos disponibles
- **Imágenes optimizadas**: Nombres únicos, previene overwrite
- **Sesión persistente**: Carrito sobrevive cierre de navegador
- **Badges dinámicos**: Actualización en tiempo real

---

## 📚 Documentación Adicional

- [README_UBICACION_WHATSAPP.md](README_UBICACION_WHATSAPP.md): Documentación completa del sistema de geolocalización GPS y envío por WhatsApp
- [CodeIgniter 4 Docs](https://codeigniter.com/user_guide/)
- [CodeIgniter Shield Docs](https://shield.codeigniter.com/)
- [Bootstrap 5 Docs](https://getbootstrap.com/docs/5.3/)

---

## 🚀 Resumen de Endpoints

### Públicos (sin login)
```
GET  /                          → Home con menú
GET  /menu                      → Vista de menú
GET  /carrito                   → Ver carrito
POST /carrito/agregar           → Agregar item (AJAX)
POST /carrito/actualizar        → Actualizar cantidad
POST /carrito/eliminar          → Eliminar item
POST /carrito/vaciar            → Vaciar carrito
GET  /carrito/getCount          → Contador (AJAX)
```

### Con Login (auth)
```
POST /carrito/finalizar         → Finalizar pedido
GET  /carrito/mostrarQR         → Mostrar QR de pago
GET  /pedido                    → Ver mis pedidos
```

### Admin o Vendedor
```
GET  /admin/menu                → Listar platos
GET  /admin/menu/crear          → Crear plato
POST /admin/menu/guardar        → Guardar plato
GET  /admin/menu/editar/:id     → Editar plato
POST /admin/menu/actualizar/:id → Actualizar plato
GET  /admin/menu/eliminar/:id   → Eliminar plato
```

### Solo Admin
```
# Stock
GET  /admin/stock               → Listar stock
POST /admin/stock/ajusteRapido  → Ajuste rápido (AJAX)
GET  /admin/stock/editar/:id    → Editar stock
POST /admin/stock/actualizar/:id → Actualizar stock

# Pedidos
GET  /admin/pedidos             → Listar pedidos
POST /admin/pedidos/cambiarEstado/:id → Cambiar estado (AJAX)
GET  /admin/pedidos/ver/:id     → Ver detalle
GET  /admin/pedidos/imprimir/:id → Imprimir ticket
POST /admin/pedidos/eliminar/:id → Eliminar

# Usuarios
GET  /usuario                   → Listar usuarios
GET  /usuario/crear             → Crear usuario
POST /usuario/guardar           → Guardar usuario
GET  /usuario/editar/:id        → Editar usuario
POST /usuario/actualizar/:id    → Actualizar usuario
GET  /usuario/eliminar/:id      → Eliminar usuario
POST /usuario/toggleEstado/:id  → Toggle estado (AJAX)
```

---

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

---

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver archivo `LICENSE` para más detalles.

---

## 👨‍💻 Autor

**La Bartola Team**
- Instagram: [@labartolaok](https://instagram.com/labartolaok)
- WhatsApp: [+54 9 2241 517665](https://wa.me/542241517665)
- Ubicación: Newbery 356, Buenos Aires, Argentina

---

## 🙏 Agradecimientos

- CodeIgniter Team por el excelente framework
- Bootstrap Team por los componentes UI
- Comunidad de PHP por las mejores prácticas

---

## 📞 Soporte

Para reportar bugs o solicitar features:
- Abrir un [Issue](https://github.com/Carloolivera/labartola/issues)
- Contactar por WhatsApp: [2241 517665](https://wa.me/542241517665)

---

**Hecho con ❤️ para La Bartola**
