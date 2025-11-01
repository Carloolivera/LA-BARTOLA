<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('menu', 'Menu::index');
$routes->get('contacto', 'Contacto::index');
$routes->post('contacto/enviar', 'Contacto::enviar');

$routes->group('carrito', static function ($routes) {
    $routes->get('/', 'Carrito::index');
    $routes->post('agregar', 'Carrito::agregar');
    $routes->post('actualizar', 'Carrito::actualizar');
    $routes->post('eliminar', 'Carrito::eliminar');
    $routes->post('vaciar', 'Carrito::vaciar');
    $routes->get('checkout', 'Carrito::checkout', ['filter' => 'session']);
});

$routes->group('pedido', ['filter' => 'session'], static function ($routes) {
    $routes->get('/', 'Pedido::index');
    $routes->post('crear', 'Pedido::crear');
});

$routes->group('perfil', ['filter' => 'session'], static function ($routes) {
    $routes->get('/', 'Perfil::index');
});

$routes->group('admin', ['filter' => 'group:admin'], static function ($routes) {
    // Panel Admin
    $routes->get('pedidos', 'Admin::pedidos');
    $routes->get('usuarios', 'Admin::usuarios');
    $routes->post('actualizar-estado-pedido', 'Admin::actualizarEstadoPedido');
    
    // CRUD Platos (desde menú admin)
    $routes->get('menu/crear', 'Admin::crearPlato');
    $routes->post('menu/guardar', 'Admin::guardarPlato');
    $routes->get('menu/editar/(:num)', 'Admin::editarPlato/$1');
    $routes->post('menu/actualizar/(:num)', 'Admin::actualizarPlato/$1');
    $routes->get('menu/eliminar/(:num)', 'Admin::eliminarPlato/$1');
});

service('auth')->routes($routes);

$routes->set404Override(function () {
    echo view('errors/html/error_404', ['title' => 'Página no encontrada']);
});