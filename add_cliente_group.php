<?php

// Configurar el entorno de CodeIgniter
chdir(__DIR__);
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap CodeIgniter
$app = require_once __DIR__ . '/app/Config/Paths.php';
$app = new \CodeIgniter\Boot($app);
$app = $app->bootWeb();

$db = \Config\Database::connect();

// Verificar si el grupo 'cliente' ya existe
$existing = $db->table('auth_groups')->where('group', 'cliente')->get()->getRow();

if (!$existing) {
    // Insertar el grupo 'cliente'
    $db->table('auth_groups')->insert([
        'group'       => 'cliente',
        'title'       => 'Cliente',
        'description' => 'Clientes del restaurante que pueden realizar pedidos.',
    ]);
    echo "✓ Grupo 'cliente' creado correctamente\n";
} else {
    echo "✓ El grupo 'cliente' ya existe\n";
}

// Registrar la migración como ejecutada
$migrationName = '2025-11-09-164351';
$migrationExists = $db->table('migrations')->where('version', $migrationName)->get()->getRow();

if (!$migrationExists) {
    $db->table('migrations')->insert([
        'version' => $migrationName,
        'class'   => 'App\\Database\\Migrations\\AddClienteGroup',
        'group'   => 'default',
        'namespace' => 'App',
        'time'    => time(),
        'batch'   => 1,
    ]);
    echo "✓ Migración registrada correctamente\n";
}
