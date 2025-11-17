# Guía de Desarrollo Local - La Bartola

## Configuración

Este proyecto ahora está configurado para desarrollo local con:
- **MySQL y phpMyAdmin** corriendo en Docker
- **Aplicación PHP** corriendo localmente con `php spark serve`

## Requisitos

- PHP 8.2 o superior
- Composer
- Docker Desktop
- Extensiones PHP necesarias:
  - mysqli
  - intl
  - gd
  - zip
  - mbstring

## Inicio Rápido

### Windows

1. **Iniciar el entorno de desarrollo (RECOMENDADO):**
   ```bash
   start-dev.bat
   ```
   Esto iniciará:
   - MySQL en Docker (puerto 3306)
   - phpMyAdmin en http://localhost:8088
   - Servidor PHP OPTIMIZADO en http://localhost:8080
   - Cache automáticamente limpiado

2. **Limpiar logs y cache:**
   ```bash
   clean-logs.bat
   ```
   Ejecutar semanalmente para mantener el rendimiento

3. **Detener el entorno:**
   ```bash
   stop-dev.bat
   ```
   O presiona `Ctrl+C` para detener el servidor PHP y luego ejecuta `docker-compose down`

### Manual

1. **Iniciar solo MySQL:**
   ```bash
   docker-compose up -d mysql
   ```

2. **Iniciar servidor PHP (con optimizaciones):**
   ```bash
   php -c php.ini spark serve
   ```

   O sin optimizaciones:
   ```bash
   php spark serve
   ```

3. **Detener servicios:**
   ```bash
   docker-compose down
   ```

## URLs Disponibles

- **Aplicación:** http://localhost:8080
- **phpMyAdmin:** http://localhost:8088

## Configuración de Base de Datos

La conexión a MySQL está configurada en `.env`:
- **Host:** 127.0.0.1
- **Puerto:** 3306
- **Usuario:** root
- **Password:** root_password_2024
- **Base de datos:** labartola

## Migraciones

Ejecutar migraciones:
```bash
php spark migrate
```

Rollback:
```bash
php spark migrate:rollback
```

## Comandos Útiles

### Base de datos
```bash
# Ver estado de migraciones
php spark migrate:status

# Crear nueva migración
php spark make:migration NombreDeLaMigracion

# Ejecutar seeders
php spark db:seed NombreDelSeeder
```

### Caché
```bash
# Limpiar caché
php spark cache:clear
```

### Rutas
```bash
# Ver todas las rutas
php spark routes
```

## Estructura de Directorios

```
labartola/
├── app/                    # Código de la aplicación
│   ├── Controllers/        # Controladores
│   ├── Models/            # Modelos
│   ├── Views/             # Vistas
│   └── Database/          # Migraciones y Seeds
├── public/                # Archivos públicos
├── writable/              # Archivos de caché, logs, sesiones
├── docker/                # Configuración de Docker
├── .env                   # Variables de entorno
└── docker-compose.yml     # Configuración de Docker Compose
```

## Solución de Problemas

### Error de conexión a MySQL
1. Verifica que Docker esté corriendo: `docker ps`
2. Verifica que MySQL esté en puerto 3306: `docker-compose ps`
3. Reinicia MySQL: `docker-compose restart mysql`

### Error de permisos en writable/
```bash
# Windows (Git Bash o WSL)
chmod -R 775 writable/

# O crear los directorios manualmente si faltan
mkdir -p writable/cache writable/logs writable/session writable/uploads
```

### Puerto 8080 ya en uso
Puedes cambiar el puerto en el comando:
```bash
php spark serve --port=8000
```

## phpMyAdmin

Accede a http://localhost:8088
- **Usuario:** root
- **Password:** root_password_2024

## Notas Importantes

- El archivo `.env` está configurado para desarrollo local
- Los datos de MySQL persisten en un volumen de Docker (`db_data`)
- Para resetear la base de datos: `docker-compose down -v` (esto borrará todos los datos)
