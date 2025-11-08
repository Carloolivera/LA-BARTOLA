
## Información General
**Nombre del Proyecto:** La Bartola  
**Tipo:** Casa de Comidas & Delivery  
**Framework:** CodeIgniter 4  
**PHP Version:** ^8.1  
**Ubicación:** Buenos Aires, Argentina

## Tecnologías Principales
- **Backend:** CodeIgniter 4 Framework
- **Autenticación:** CodeIgniter Shield ^1.2
- **Base de Datos:** MySQL (MySQLi Driver)
- **Frontend:** Bootstrap 5.3.3
- **Fuentes:** Google Fonts (Poppins)
- **Testing:** PHPUnit ^10.5.16

## Estructura del Proyecto

### Controladores (`app/Controllers/`)
1. **Home.php** - Página principal/landing page
2. **menu.php** - Gestión y visualización del menú de platos
3. **pedido.php** - Sistema de pedidos (requiere autenticación)
4. **contacto.php** - Formulario de contacto
5. **perfil.php** - Perfil de usuario (requiere autenticación)

### Modelos (`app/Models/`)
1. **PlatoModel.php**
   - Tabla: `platos`
   - Campos: id, nombre, descripcion, precio, imagen, activo

2. **PedidoModel.php**
   - Tabla: `pedidos`
   - Campos: id, usuario_id, plato_id, cantidad, estado

### Rutas Principales (`app/Config/Routes.php`)
- **Públicas:**
  - `/` - Home
  - `/menu` - Menú de platos
  - `/contacto` - Formulario de contacto
  - `POST /contacto/enviar` - Envío de mensaje

- **Protegidas (requieren login):**
  - `/pedido` - Ver/crear pedidos
  - `POST /pedido/crear` - Crear nuevo pedido
  - `/perfil` - Perfil de usuario

- **Autenticación (Shield):**
  - `/login` - Iniciar sesión
  - `/register` - Registro de usuarios
  - `/logout` - Cerrar sesión

### Vistas (`app/Views/`)
- **layouts/main.php** - Layout principal con navbar y footer
- **home.php** - Landing page con secciones: Hero, Sobre Nosotros, Platos Destacados, Delivery, Testimonios
- **menu/** - Vistas del menú
- **pedido/** - Vistas de pedidos
- **contacto/** - Vista de contacto
- **perfil/** - Vista de perfil de usuario

## Diseño Visual
**Paleta de Colores:**
- Fondo principal: Negro (#000)
- Fondo secundario: Gris oscuro (#111)
- Texto principal: Beige (#f5f5dc)
- Acento/Destacado: Dorado (#d4af37 / #D4B68A)

**Características de Diseño:**
- Navbar sticky con fondo negro y borde dorado
- Footer con información de copyright y redes sociales
- Cards oscuras para platos destacados
- Botones con estilo dorado sobre negro

## Funcionalidades Implementadas

### Sistema de Autenticación
- Integración con CodeIgniter Shield
- Login/Registro de usuarios
- Protección de rutas mediante filtro 'session'
- Verificación de usuario logueado en vistas

### Gestión de Menú
- Visualización de platos desde base de datos
- Modelo PlatoModel para gestión de platos

### Sistema de Pedidos
- Creación de pedidos vinculados a usuarios
- Estados de pedido (pendiente, etc.)
- Relación usuario-plato-cantidad

### Contacto
- Formulario de contacto básico
- Preparado para integración con servicio de correo

## Estado Actual del Proyecto

### Completado ✓
- Estructura base de CodeIgniter 4
- Sistema de autenticación con Shield
- Diseño visual completo (layout, estilos)
- Controladores principales implementados
- Modelos de datos (Plato, Pedido)
- Rutas configuradas con protección
- Landing page completa con secciones
- Navbar responsive con autenticación condicional

### Pendiente / Por Implementar
- Migraciones de base de datos (directorio vacío)
- Seeds para datos iniciales
- Implementación completa de vistas de menú
- Implementación completa de vistas de pedidos
- Implementación completa de vistas de perfil
- Integración de servicio de correo en contacto
- Assets estáticos (imágenes de platos)
- Validaciones de formularios
- Gestión de estados de pedidos
- Panel administrativo
- Sistema de carrito de compras
- Integración con pasarela de pagos
- Sistema de notificaciones

## Configuración

### Base de Datos
- Driver: MySQLi
- Host: localhost
- Puerto: 3306
- Charset: utf8mb4
- Collation: utf8mb4_general_ci
- **Nota:** Credenciales vacías en Database.php (requiere configuración en .env)

### Entorno
- Servidor web debe apuntar a carpeta `public/`
- Archivo `.env` debe configurarse desde plantilla `env`
- Preload habilitado para optimización

## Notas de Desarrollo
- No hay migraciones creadas aún (tablas deben crearse manualmente o mediante migraciones futuras)
- El proyecto usa PSR-4 autoloading
- Testing configurado con PHPUnit
- Composer para gestión de dependencias
- Estructura MVC estándar de CodeIgniter 4