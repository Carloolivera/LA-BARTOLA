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


// ---------------- ADMIN ----------------
$routes->group('admin', ['filter' => 'group:admin'], function($routes) {
    // PEDIDOS
    $routes->get('pedidos', 'Admin\Pedidos::index');
    $routes->get('pedidos/ver/(:num)', 'Admin\Pedidos::ver/$1');
    $routes->match(['GET', 'POST'], 'pedidos/editar/(:num)', 'Admin\Pedidos::editar/$1');
    $routes->post('pedidos/cambiarEstado/(:num)', 'Admin\Pedidos::cambiarEstado/$1');
    $routes->post('pedidos/actualizarItem', 'Admin\Pedidos::actualizarItem');
    $routes->post('pedidos/agregarPlato', 'Admin\Pedidos::agregarPlato');
    $routes->post('pedidos/eliminar/(:num)', 'Admin\Pedidos::eliminar/$1');
    $routes->get('pedidos/imprimir/(:num)', 'Admin\Pedidos::imprimirTicket/$1');

    // CAJA CHICA
    $routes->get('caja-chica', 'Admin\CajaChica::index');
    $routes->get('caja-chica/ver/(:segment)', 'Admin\CajaChica::ver/$1');
    $routes->post('caja-chica/agregar', 'Admin\CajaChica::agregar');
    $routes->match(['GET', 'POST'], 'caja-chica/editar/(:num)', 'Admin\CajaChica::editar/$1');
    $routes->get('caja-chica/eliminar/(:num)', 'Admin\CajaChica::eliminar/$1');
    $routes->get('caja-chica/archivo', 'Admin\CajaChica::archivo');
    $routes->get('caja-chica/imprimir/(:segment)', 'Admin\CajaChica::imprimir/$1');
});

// CRUD DE MENÚ (ADMIN o VENDEDOR)
$routes->group('admin/menu', ['filter' => 'adminOrVendedor'], function($routes) {
    $routes->get('/', 'Admin\Menu::index');
    $routes->get('crear', 'Admin\Menu::crear');
    $routes->post('guardar', 'Admin\Menu::guardar');
    $routes->get('editar/(:num)', 'Admin\Menu::editar/$1');
    $routes->post('actualizar/(:num)', 'Admin\Menu::actualizar/$1');
    $routes->get('eliminar/(:num)', 'Admin\Menu::eliminar/$1');
    $routes->get('obtenerPlatos', 'Admin\Menu::obtenerPlatos');
});

// CRUD DE CATEGORÍAS (ADMIN o VENDEDOR)
$routes->group('admin/categorias', ['filter' => 'adminOrVendedor'], function($routes) {
    $routes->get('/', 'Admin\Categorias::index');
    $routes->post('crear', 'Admin\Categorias::crear');
    $routes->post('actualizar/(:num)', 'Admin\Categorias::actualizar/$1');
    $routes->post('eliminar/(:num)', 'Admin\Categorias::eliminar/$1');
    $routes->get('obtenerTodas', 'Admin\Categorias::obtenerTodas');
});



// ---------------- AUTH SHIELD ----------------
service('auth')->routes($routes, ['except' => ['login', 'register']]);

// ---------------- LOGIN ADMIN ----------------
$routes->get('login', '\App\Controllers\Auth\LoginController::loginView');
$routes->post('login', '\App\Controllers\Auth\LoginController::loginAction');
