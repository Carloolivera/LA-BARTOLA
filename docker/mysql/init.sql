-- =====================================================================
-- Script de inicialización de base de datos para La Bartola
-- =====================================================================
-- Este archivo se ejecuta automáticamente cuando se crea el contenedor
-- MySQL por primera vez.
--
-- IMPORTANTE: Las tablas se crean mediante migraciones de CodeIgniter 4.
-- Este archivo solo garantiza que la base de datos existe y está lista.
-- =====================================================================

-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS labartola CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE labartola;

-- Las tablas serán creadas por las migraciones de CodeIgniter cuando ejecutes:
-- php spark migrate
--
-- Estructura de tablas que se crearán:
-- ✓ platos (con stock y stock_ilimitado)
-- ✓ pedidos (con usuario_id, plato_id, cantidad, total, estado, notas)
-- ✓ categorias (con datos iniciales: Bebidas, Empanadas, Pizzas, Tartas, Postres)
-- ✓ caja_chica (con fecha, hora, concepto, tipo, monto, es_digital)
-- ✓ inventario_categorias (con datos iniciales)
-- ✓ inventario_items
-- ✓ inventario_movimientos
-- ✓ users (creada por CodeIgniter Shield)
-- ✓ auth_* (tablas de autenticación de Shield)

-- Verificar que la base de datos está lista
SELECT 'Base de datos labartola inicializada correctamente. Ejecuta "php spark migrate" para crear las tablas.' AS status;
