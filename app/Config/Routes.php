<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('menu', 'Menu::index');

// RUTAS DEL CARRITO - Agregar y ver carrito SIN login
$routes->get('carrito', 'Carrito::index');
$routes->post('carrito/agregar', 'Carrito::agregar');
$routes->post('carrito/actualizar', 'Carrito::actualizar');
$routes->post('carrito/eliminar', 'Carrito::eliminar');
$routes->post('carrito/vaciar', 'Carrito::vaciar');
$routes->get('carrito/getCount', 'Carrito::getCount');

// RUTAS DEL CARRITO QUE REQUIEREN LOGIN
$routes->group('carrito', ['filter' => 'auth'], function($routes) {
    $routes->post('finalizar', 'Carrito::finalizar');
    $routes->get('mostrarQR', 'Carrito::mostrarQR');
});

// RUTAS DE PEDIDOS (requieren login)
$routes->group('pedido', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Pedido::index');
    $routes->post('crear', 'Pedido::crear');
});

// RUTAS DE USUARIOS (requieren login)
$routes->group('usuario', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Usuario::index');
    $routes->get('crear', 'Usuario::crear');
    $routes->post('guardar', 'Usuario::guardar');
    $routes->get('editar/(:num)', 'Usuario::editar/$1');
    $routes->post('actualizar/(:num)', 'Usuario::actualizar/$1');
    $routes->get('eliminar/(:num)', 'Usuario::eliminar/$1');
    $routes->post('toggleEstado/(:num)', 'Usuario::toggleEstado/$1');
});

// RUTAS DE ADMIN (requieren login)
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('pedidos', 'Admin\Pedidos::index');
    $routes->get('pedidos/ver/(:num)', 'Admin\Pedidos::ver/$1');
    $routes->match(['get', 'post'], 'pedidos/editar/(:num)', 'Admin\Pedidos::editar/$1');
    $routes->post('pedidos/cambiarEstado/(:num)', 'Admin\Pedidos::cambiarEstado/$1');
    $routes->post('pedidos/eliminar/(:num)', 'Admin\Pedidos::eliminar/$1');
    $routes->get('pedidos/imprimir/(:num)', 'Admin\Pedidos::imprimirTicket/$1');
    $routes->get('stock', 'Admin::stock');
    $routes->get('usuarios', 'Admin::usuarios');
    $routes->post('actualizarEstadoPedido', 'Admin::actualizarEstadoPedido');
});

// RUTAS DE CONTACTO
$routes->get('contacto', 'Contacto::index');
$routes->post('contacto/enviar', 'Contacto::enviar');

// RUTAS DE AUTENTICACION CON GOOGLE
$routes->get('auth/google', 'Auth\GoogleAuth::redirect');
$routes->get('auth/google/callback', 'Auth\GoogleAuth::callback');

// RUTAS DE AUTENTICACION DE SHIELD (PERSONALIZADAS)
service('auth')->routes($routes, ['except' => ['login', 'register']]);

// Login y Registro personalizados con redirección
$routes->get('login', '\App\Controllers\Auth\LoginController::loginView');
$routes->post('login', '\App\Controllers\Auth\LoginController::loginAction');
$routes->get('register', '\App\Controllers\Auth\RegisterController::registerView');
$routes->post('register', '\App\Controllers\Auth\RegisterController::registerAction');