# Configuración de Docker para La Bartola

Este proyecto ahora está completamente dockerizado con PHP 8.2, Apache, MySQL 8.0 y phpMyAdmin.

## Requisitos Previos

- Docker Desktop instalado y corriendo
- Git Bash o terminal compatible

## Estructura de Docker

```
labartola/
├── docker-compose.yml          # Configuración de servicios
├── Dockerfile                  # Imagen de PHP + Apache
├── docker/
│   ├── apache/
│   │   └── 000-default.conf   # Configuración de Apache
│   └── mysql/
│       └── init.sql            # Script de inicialización de BD
└── .dockerignore              # Archivos excluidos del build
```

## Servicios Configurados

### 1. MySQL (labartola_mysql)
- Puerto: `3307` (host) → `3306` (contenedor)
- Usuario: `root`
- Password: `root_password_2024`
- Base de datos: `labartola`
- Volumen persistente: `db_data`

### 2. Web (labartola_web)
- Puerto: `8080` (host) → `80` (contenedor)
- PHP: 8.2 con Apache
- Extensiones: mysqli, pdo, pdo_mysql, zip, gd, intl
- mod_rewrite habilitado

### 3. phpMyAdmin (labartola_phpmyadmin)
- Puerto: `8088`
- Acceso: http://localhost:8088

## Comandos de Docker

### 1. Bajar contenedores actuales (si existen)
```bash
cd C:\Dev\labartola
docker-compose down
```

### 2. Eliminar volúmenes viejos (CUIDADO: esto borra la base de datos)
```bash
docker volume rm labartola_db_data
# o si el volumen se llama diferente:
docker volume ls
docker volume rm <nombre_del_volumen>
```

### 3. Construir y levantar los contenedores
```bash
docker-compose up -d --build
```

### 4. Ver logs en tiempo real
```bash
# Todos los servicios
docker-compose logs -f

# Solo web
docker-compose logs -f web

# Solo MySQL
docker-compose logs -f mysql
```

### 5. Verificar que los contenedores están corriendo
```bash
docker-compose ps
```

### 6. Acceder al contenedor web
```bash
docker exec -it labartola_web bash
```

### 7. Ejecutar comandos de CodeIgniter dentro del contenedor
```bash
# Entrar al contenedor
docker exec -it labartola_web bash

# Dentro del contenedor:
php spark migrate              # Correr migraciones (si es necesario)
php spark db:seed UserSeeder   # Seeders
php spark list                 # Ver comandos disponibles
```

### 8. Reiniciar servicios
```bash
# Reiniciar todo
docker-compose restart

# Reiniciar solo web
docker-compose restart web
```

### 9. Detener contenedores (sin borrar datos)
```bash
docker-compose stop
```

### 10. Detener y eliminar contenedores
```bash
docker-compose down
```

## Acceso a la Aplicación

Una vez levantados los contenedores:

- **Aplicación Web**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8088
- **MySQL** (desde host): `localhost:3307`

## Configuración de Base de Datos

El archivo `docker/mysql/init.sql` se ejecuta automáticamente cuando creas el contenedor de MySQL por primera vez. Este archivo crea todas las tablas necesarias:

- users
- platos
- pedidos
- pedido_items
- cupones
- cupones_usos
- notificaciones
- caja_turnos
- caja_movimientos
- migrations

## Solución de Problemas

### El puerto 8080 está ocupado
```bash
# Matar procesos en Windows
powershell "Get-Process -Id (Get-NetTCPConnection -LocalPort 8080).OwningProcess | Stop-Process -Force"

# O cambiar el puerto en docker-compose.yml
ports:
  - "8081:80"  # Cambia 8080 por 8081
```

### La base de datos no se inicializa
```bash
# Eliminar volúmenes y recrear
docker-compose down -v
docker-compose up -d --build
```

### Permisos en carpeta writable
```bash
# Dentro del contenedor
docker exec -it labartola_web bash
chown -R www-data:www-data /var/www/html/writable
chmod -R 775 /var/www/html/writable
```

### Ver errores de Apache
```bash
docker exec -it labartola_web tail -f /var/log/apache2/error.log
```

## Desarrollo

El directorio del proyecto está montado como volumen en el contenedor, por lo que:

✅ Los cambios en el código se reflejan inmediatamente (no necesitas rebuildar)
✅ La carpeta `writable` tiene permisos adecuados
✅ Apache está configurado con mod_rewrite para las rutas de CodeIgniter

## Backup de Base de Datos

```bash
# Exportar
docker exec labartola_mysql mysqldump -uroot -proot_password_2024 labartola > backup.sql

# Importar
docker exec -i labartola_mysql mysql -uroot -proot_password_2024 labartola < backup.sql
```

## Notas Importantes

1. **Primera vez**: El contenedor MySQL tarda unos segundos en inicializarse
2. **Healthcheck**: El servicio web espera a que MySQL esté listo antes de iniciar
3. **Persistencia**: Los datos de MySQL se guardan en el volumen `db_data`
4. **Red**: Todos los contenedores están en la red `labartola_network`
