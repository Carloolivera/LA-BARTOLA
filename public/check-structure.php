<?php
/**
 * Verificar estructura de controladores
 * Acceder desde: https://labartola.store/check-structure.php
 * ELIMINAR DESPUÉS DE VERIFICAR
 */

echo "<h1>🔍 Verificación de Estructura de Controladores</h1>";
echo "<pre>";

$controllersPath = __DIR__ . '/../app/Controllers/';

echo "\n📁 ESTRUCTURA ACTUAL:\n";
echo "======================\n\n";

// Función para listar directorios
function listDirectory($path, $indent = '') {
    if (!is_dir($path)) {
        echo "❌ No existe: $path\n";
        return;
    }

    $items = scandir($path);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $fullPath = $path . '/' . $item;
        $isDir = is_dir($fullPath);

        if ($isDir) {
            echo $indent . "📂 " . $item . "/\n";
            listDirectory($fullPath, $indent . "  ");
        } else {
            echo $indent . "📄 " . $item . "\n";
        }
    }
}

echo "Ruta base: $controllersPath\n\n";
listDirectory($controllersPath);

echo "\n\n🔍 VERIFICACIÓN DE PROBLEMAS:\n";
echo "=============================\n\n";

$problems = [];

// Verificar si existe auth/ (minúscula) - DEBE NO EXISTIR
if (is_dir($controllersPath . 'auth')) {
    $problems[] = "❌ ERROR: Existe carpeta 'auth/' (minúscula) - DEBE ELIMINARSE";
}

// Verificar si existe admin/ (minúscula) - DEBE NO EXISTIR
if (is_dir($controllersPath . 'admin')) {
    $problems[] = "❌ ERROR: Existe carpeta 'admin/' (minúscula) - DEBE ELIMINARSE";
}

// Verificar si existe Auth/ (mayúscula) - DEBE EXISTIR
if (!is_dir($controllersPath . 'Auth')) {
    $problems[] = "❌ ERROR: NO existe carpeta 'Auth/' (mayúscula) - DEBE EXISTIR";
} else {
    echo "✅ Carpeta 'Auth/' existe correctamente\n";

    // Verificar LoginController.php
    if (!file_exists($controllersPath . 'Auth/LoginController.php')) {
        $problems[] = "❌ ERROR: NO existe 'Auth/LoginController.php'";
    } else {
        echo "✅ Archivo 'Auth/LoginController.php' existe\n";
    }
}

// Verificar si existe Admin/ (mayúscula) - DEBE EXISTIR
if (!is_dir($controllersPath . 'Admin')) {
    $problems[] = "❌ ERROR: NO existe carpeta 'Admin/' (mayúscula) - DEBE EXISTIR";
} else {
    echo "✅ Carpeta 'Admin/' existe correctamente\n";

    // Verificar archivos en Admin
    $adminFiles = ['CajaChica.php', 'Categorias.php', 'Menu.php', 'Pedidos.php'];
    foreach ($adminFiles as $file) {
        if (!file_exists($controllersPath . 'Admin/' . $file)) {
            $problems[] = "❌ ERROR: NO existe 'Admin/$file'";
        } else {
            echo "✅ Archivo 'Admin/$file' existe\n";
        }
    }
}

// Verificar Carrito.php (mayúscula) - DEBE EXISTIR
if (!file_exists($controllersPath . 'Carrito.php')) {
    $problems[] = "❌ ERROR: NO existe 'Carrito.php'";
} else {
    echo "✅ Archivo 'Carrito.php' existe\n";
}

// Verificar carrito.php (minúscula) - DEBE NO EXISTIR
if (file_exists($controllersPath . 'carrito.php')) {
    $problems[] = "❌ ERROR: Existe 'carrito.php' (minúscula) - DEBE ELIMINARSE";
}

echo "\n\n📋 RESUMEN:\n";
echo "===========\n\n";

if (empty($problems)) {
    echo "✅ ✅ ✅ TODO CORRECTO ✅ ✅ ✅\n";
    echo "\nLa estructura de controladores está correcta.\n";
    echo "Si sigues teniendo errores 404, el problema es otro.\n";
} else {
    echo "❌ PROBLEMAS ENCONTRADOS:\n\n";
    foreach ($problems as $problem) {
        echo $problem . "\n";
    }

    echo "\n\n🔧 SOLUCIÓN:\n";
    echo "============\n\n";

    if (is_dir($controllersPath . 'auth') || is_dir($controllersPath . 'admin')) {
        echo "En Administrador de Archivos de Hostinger:\n\n";

        if (is_dir($controllersPath . 'auth')) {
            echo "1. Ir a: public_html/app/Controllers/\n";
            echo "2. ELIMINAR carpeta: auth/ (minúscula)\n\n";
        }

        if (is_dir($controllersPath . 'admin')) {
            echo "3. ELIMINAR carpeta: admin/ (minúscula)\n\n";
        }

        echo "Luego refrescar esta página para verificar.\n";
    }
}

echo "\n\n⚠️ IMPORTANTE: ELIMINAR ESTE ARCHIVO DESPUÉS DE VERIFICAR\n";
echo "</pre>";
