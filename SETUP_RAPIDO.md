# 🚀 Setup Rápido - La Bartola

## Para una nueva instalación desde cero

### 1. Prerequisitos
```bash
# Instalar Docker Desktop (recomendado)
# O tener instalado: PHP 8.2+, Composer, MySQL 8.0+
```

### 2. Clonar y configurar
```bash
git clone https://github.com/Carloolivera/labartola.git
cd labartola
cp .env.example .env
```

### 3. Levantar con Docker
```bash
docker-compose up -d
# Espera 30 segundos a que MySQL esté listo
```

### 4. Ejecutar migraciones
```bash
docker-compose exec web php spark migrate --all
docker-compose exec web php spark shield:setup
```

### 5. Crear usuario admin
```bash
docker-compose exec web php spark shield:user create
# Email: admin@labartola.com
# Username: admin
# Password: admin123

docker-compose exec web php spark shield:group add admin admin
```

### 6. Listo!
- App: http://localhost:8080
- phpMyAdmin: http://localhost:8088 (root / root_password_2024)

---

## Troubleshooting

### Error: "Base de datos no encontrada"
```bash
docker-compose exec mysql mysql -u root -proot_password_2024 -e "CREATE DATABASE IF NOT EXISTS labartola CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Error: "Migraciones fallan"
```bash
# Resetear migraciones
docker-compose exec web php spark migrate:rollback
docker-compose exec web php spark migrate --all
```

### Ver logs
```bash
docker-compose logs -f web
docker-compose logs -f mysql
```

### Reiniciar desde cero
```bash
docker-compose down
docker volume rm labartola_db_data
docker-compose up -d
# Ejecutar pasos 4-5 nuevamente
```

---

## Datos de prueba (Menu scrapeado de agilpedido.com)

El proyecto incluye datos del menú de La Bartola extraídos de agilpedido.com:

### Categorías disponibles
- Empanadas Gourmet
- Empanadas Clásicas
- Bebidas
- Pizzas

### Ejemplo de platos scrapeados
```
Empanadas Gourmet:
- Atún ($190): Atún natural grillado con cebolla, salsa de tomate y queso azul
- Burguer Cheese Bacon ($180): Lomo y panceta con cheddar
- Honey and Beer Bondi ($200): Bondiola con salsa miel-cerveza
- Pollo al Champignon ($190): Pollo con salsa cremosa de hongos

Bebidas:
- Imperial IPA ($200)
- Coca-Cola 2.25L ($430)
- Malbec Bravio ($450)

Pizzas:
- Muzzarella grande ($900)
- Fugazzetta grande ($1,100)
- Jamón y Morrones ($1,200)
```

**Nota:** Estos datos se pueden cargar manualmente desde el admin o crear un seeder automático.

---

## Comandos útiles

```bash
# Entrar al contenedor para ejecutar comandos
docker-compose exec web bash

# Ejecutar migraciones
docker-compose exec web php spark migrate

# Crear nuevo usuario
docker-compose exec web php spark shield:user create

# Ver lista de rutas
docker-compose exec web php spark routes

# Ver estado de migraciones
docker-compose exec web php spark migrate:status

# Acceder a MySQL directamente
docker-compose exec mysql mysql -u root -proot_password_2024 labartola
```
