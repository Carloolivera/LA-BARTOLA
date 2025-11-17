<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

// ---------------- CARRITO (público) ----------------
$routes->get('carrito', 'Carrito::index');
$routes->post('carrito/agregar', 'Carrito::agregar');
$routes->post('carrito/actualizar', 'Carrito::actualizar');
$routes->post('carrito/eliminar', 'Carrito::eliminar');
$routes->post('carrito/vaciar', 'Carrito::vaciar');
$routes->get('carrito/getCount', 'Carrito::getCount');

// ---------------- CARRITO (finalizar pedido SIN requerir login) ----------------
$routes->post('carrito/finalizar', 'Carrito::finalizar');

// ---------------- CARRITO (con login) ----------------
$routes->group('carrito', ['filter' => 'auth'], function($routes) {
    $routes->get('mostrarQR', 'Carrito::mostrarQR');
});

// ---------------- PEDIDOS ----------------
$routes->group('pedido', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Pedido::index');
    $routes->post('crear', 'Pedido::crear');
});


// ---------------- USUARIOS ----------------
$routes->group('usuario', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Usuario::index');
    $routes->get('crear', 'Usuario::crear');
    $routes->post('guardar', 'Usuario::guardar');
    $routes->get('editar/(:num)', 'Usuario::editar/$1');
    $routes->post('actualizar/(:num)', 'Usuario::actualizar/$1');
    $routes->get('eliminar/(:num)', 'Usuario::eliminar/$1');
    $routes->post('toggleEstado/(:num)', 'Usuario::toggleEstado/$1');
});

// ---------------- ADMIN ----------------
$routes->group('admin', ['filter' => 'group:admin'], function($routes) {
    // PEDIDOS
    $routes->get('pedidos', 'Admin\Pedidos::index');
    $routes->get('pedidos/ver/(:num)', 'Admin\Pedidos::ver/$1');
    $routes->match(['GET', 'POST'], 'pedidos/editar/(:num)', 'Admin\Pedidos::editar/$1');
    $routes->post('pedidos/cambiarEstado/(:num)', 'Admin\Pedidos::cambiarEstado/$1');
    $routes->post('pedidos/eliminar/(:num)', 'Admin\Pedidos::eliminar/$1');
    $routes->get('pedidos/imprimir/(:num)', 'Admin\Pedidos::imprimirTicket/$1');

    // INVENTARIO
    $routes->get('inventario', 'Admin\Inventario::index');
    $routes->match(['GET', 'POST'], 'inventario/crear', 'Admin\Inventario::crear');
    $routes->match(['GET', 'POST'], 'inventario/editar/(:num)', 'Admin\Inventario::editar/$1');
    $routes->get('inventario/eliminar/(:num)', 'Admin\Inventario::eliminar/$1');
    $routes->post('inventario/movimiento/(:num)', 'Admin\Inventario::movimiento/$1');

    // CAJA CHICA
    $routes->get('caja-chica', 'Admin\CajaChica::index');
    $routes->get('caja-chica/ver/(:segment)', 'Admin\CajaChica::ver/$1');
    $routes->post('caja-chica/agregar', 'Admin\CajaChica::agregar');
    $routes->match(['GET', 'POST'], 'caja-chica/editar/(:num)', 'Admin\CajaChica::editar/$1');
    $routes->get('caja-chica/eliminar/(:num)', 'Admin\CajaChica::eliminar/$1');
    $routes->get('caja-chica/archivo', 'Admin\CajaChica::archivo');
    $routes->get('caja-chica/imprimir/(:segment)', 'Admin\CajaChica::imprimir/$1');


    // GESTIÓN DE USUARIOS (desde admin)
    $routes->get('usuarios', 'Usuario::index');
});

// CRUD DE MENÚ (ADMIN o VENDEDOR)
$routes->group('admin/menu', ['filter' => 'adminOrVendedor'], function($routes) {
    $routes->get('/', 'Admin\Menu::index');
    $routes->get('crear', 'Admin\Menu::crear');
    $routes->post('guardar', 'Admin\Menu::guardar');
    $routes->get('editar/(:num)', 'Admin\Menu::editar/$1');
    $routes->post('actualizar/(:num)', 'Admin\Menu::actualizar/$1');
    $routes->get('eliminar/(:num)', 'Admin\Menu::eliminar/$1');
});

// CRUD DE CATEGORÍAS (ADMIN o VENDEDOR)
$routes->group('admin/categorias', ['filter' => 'adminOrVendedor'], function($routes) {
    $routes->get('/', 'Admin\Categorias::index');
    $routes->post('crear', 'Admin\Categorias::crear');
    $routes->post('actualizar/(:num)', 'Admin\Categorias::actualizar/$1');
    $routes->post('eliminar/(:num)', 'Admin\Categorias::eliminar/$1');
    $routes->get('obtenerTodas', 'Admin\Categorias::obtenerTodas');
});



// ---------------- CONTACTO ----------------
$routes->get('contacto', 'Contacto::index');
$routes->post('contacto/enviar', 'Contacto::enviar');

// ---------------- OAUTH GOOGLE ----------------
$routes->get('oauth/google', 'OAuth::googleRedirect');
$routes->get('oauth/google/callback', 'OAuth::googleCallback');


// ---------------- AUTH SHIELD ----------------
service('auth')->routes($routes, ['except' => ['login', 'register']]);

// ---------------- LOGIN / REGISTER ----------------
$routes->get('login', '\App\Controllers\Auth\LoginController::loginView');
$routes->post('login', '\App\Controllers\Auth\LoginController::loginAction');
$routes->get('register', '\App\Controllers\Auth\RegisterController::registerView');
$routes->post('register', '\App\Controllers\Auth\RegisterController::registerAction');
