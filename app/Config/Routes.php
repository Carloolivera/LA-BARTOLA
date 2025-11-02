<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('menu', 'Menu::index');

$routes->get('carrito', 'Carrito::index');
$routes->post('carrito/agregar', 'Carrito::agregar');
$routes->post('carrito/actualizar', 'Carrito::actualizar');
$routes->post('carrito/eliminar', 'Carrito::eliminar');
$routes->post('carrito/vaciar', 'Carrito::vaciar');
$routes->post('carrito/finalizar', 'Carrito::finalizar');
$routes->get('carrito/mostrarQR', 'Carrito::mostrarQR');
$routes->get('carrito/getCount', 'Carrito::getCount');

$routes->get('pedido', 'Pedido::index');
$routes->post('pedido/crear', 'Pedido::crear');

$routes->group('usuario', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Usuario::index');
    $routes->get('crear', 'Usuario::crear');
    $routes->post('guardar', 'Usuario::guardar');
    $routes->get('editar/(:num)', 'Usuario::editar/$1');
    $routes->post('actualizar/(:num)', 'Usuario::actualizar/$1');
    $routes->get('eliminar/(:num)', 'Usuario::eliminar/$1');
    $routes->post('toggleEstado/(:num)', 'Usuario::toggleEstado/$1');
});

$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('pedidos', 'Admin\Pedidos::index');
    $routes->get('pedidos/ver/(:num)', 'Admin\Pedidos::ver/$1');
    $routes->match(['get', 'post'], 'pedidos/editar/(:num)', 'Admin\Pedidos::editar/$1');
    $routes->post('pedidos/cambiarEstado/(:num)', 'Admin\Pedidos::cambiarEstado/$1');
    $routes->post('pedidos/eliminar/(:num)', 'Admin\Pedidos::eliminar/$1');
    $routes->get('stock', 'Admin::stock');
    $routes->get('usuarios', 'Admin::usuarios');
    $routes->post('actualizarEstadoPedido', 'Admin::actualizarEstadoPedido');
});

$routes->get('contacto', 'Contacto::index');
$routes->post('contacto/enviar', 'Contacto::enviar');

service('auth')->routes($routes);