<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('menu', 'Menu::index');

// ---------------- CARRITO (público) ----------------
$routes->get('carrito', 'Carrito::index');
$routes->post('carrito/agregar', 'Carrito::agregar');
$routes->post('carrito/actualizar', 'Carrito::actualizar');
$routes->post('carrito/eliminar', 'Carrito::eliminar');
$routes->post('carrito/vaciar', 'Carrito::vaciar');
$routes->get('carrito/getCount', 'Carrito::getCount');

// ---------------- CARRITO (con login) ----------------
$routes->group('carrito', ['filter' => 'auth'], function($routes) {
    $routes->post('finalizar', 'Carrito::finalizar');
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
    $routes->match(['get', 'post'], 'pedidos/editar/(:num)', 'Admin\Pedidos::editar/$1');
    $routes->post('pedidos/cambiarEstado/(:num)', 'Admin\Pedidos::cambiarEstado/$1');
    $routes->post('pedidos/eliminar/(:num)', 'Admin\Pedidos::eliminar/$1');
    $routes->get('pedidos/imprimir/(:num)', 'Admin\Pedidos::imprimirTicket/$1');

    // STOCK
    $routes->get('stock', 'Admin\Stock::index');
    $routes->get('stock/editar/(:num)', 'Admin\Stock::editar/$1');
    $routes->post('stock/actualizar/(:num)', 'Admin\Stock::actualizar/$1');
    $routes->post('stock/ajusteRapido', 'Admin\Stock::ajusteRapido');

    // OTROS
    $routes->get('usuarios', 'Admin::usuarios');
    $routes->post('actualizarEstadoPedido', 'Admin::actualizarEstadoPedido');
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



// ---------------- CONTACTO ----------------
$routes->get('contacto', 'Contacto::index');
$routes->post('contacto/enviar', 'Contacto::enviar');

// ---------------- AUTH GOOGLE ----------------
$routes->get('auth/google', 'Auth\GoogleAuth::redirect');
$routes->get('auth/google/callback', 'Auth\GoogleAuth::callback');

// ---------------- AUTH SHIELD ----------------
service('auth')->routes($routes, ['except' => ['login', 'register']]);

// ---------------- LOGIN / REGISTER ----------------
$routes->get('login', '\App\Controllers\Auth\LoginController::loginView');
$routes->post('login', '\App\Controllers\Auth\LoginController::loginAction');
$routes->get('register', '\App\Controllers\Auth\RegisterController::registerView');
$routes->post('register', '\App\Controllers\Auth\RegisterController::registerAction');
