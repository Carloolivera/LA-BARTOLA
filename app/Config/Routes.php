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
    $routes->post('validarCupon', 'Carrito::validarCupon');
    $routes->post('aplicarCupon', 'Carrito::aplicarCupon');
    $routes->post('quitarCupon', 'Carrito::quitarCupon');
});

// ---------------- PEDIDOS ----------------
$routes->group('pedido', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Pedido::index');
    $routes->post('crear', 'Pedido::crear');
});

// ---------------- NOTIFICACIONES ----------------
$routes->group('notificaciones', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Notificaciones::index');
    $routes->get('obtener', 'Notificaciones::obtener');
    $routes->get('stream', 'Notificaciones::stream');
    $routes->post('marcarLeida/(:num)', 'Notificaciones::marcarLeida/$1');
    $routes->post('marcarTodasLeidas', 'Notificaciones::marcarTodasLeidas');
    $routes->post('eliminar/(:num)', 'Notificaciones::eliminar/$1');
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
    $routes->match(['get', 'post'], 'inventario/crear', 'Admin\Inventario::crear');
    $routes->match(['get', 'post'], 'inventario/editar/(:num)', 'Admin\Inventario::editar/$1');
    $routes->get('inventario/eliminar/(:num)', 'Admin\Inventario::eliminar/$1');
    $routes->post('inventario/movimiento/(:num)', 'Admin\Inventario::movimiento/$1');

    // CAJA CHICA
    $routes->get('caja-chica', 'Admin\CajaChica::index');
    $routes->get('caja-chica/ver/(:segment)', 'Admin\CajaChica::ver/$1');
    $routes->post('caja-chica/agregar', 'Admin\CajaChica::agregar');
    $routes->match(['get', 'post'], 'caja-chica/editar/(:num)', 'Admin\CajaChica::editar/$1');
    $routes->get('caja-chica/eliminar/(:num)', 'Admin\CajaChica::eliminar/$1');
    $routes->get('caja-chica/archivo', 'Admin\CajaChica::archivo');
    $routes->get('caja-chica/imprimir/(:segment)', 'Admin\CajaChica::imprimir/$1');

    // CUPONES
    $routes->get('cupones', 'Admin\Cupones::index');
    $routes->get('cupones/crear', 'Admin\Cupones::crear');
    $routes->post('cupones/guardar', 'Admin\Cupones::guardar');
    $routes->get('cupones/editar/(:num)', 'Admin\Cupones::editar/$1');
    $routes->post('cupones/actualizar/(:num)', 'Admin\Cupones::actualizar/$1');
    $routes->post('cupones/eliminar/(:num)', 'Admin\Cupones::eliminar/$1');
    $routes->post('cupones/toggleEstado/(:num)', 'Admin\Cupones::toggleEstado/$1');

    // ANALYTICS
    $routes->get('analytics', 'Admin\Analytics::index');
    $routes->get('analytics/exportar', 'Admin\Analytics::exportar');

    // CAJA
    $routes->get('caja', 'Admin\Caja::index');
    $routes->post('caja/abrir', 'Admin\Caja::abrir');
    $routes->post('caja/cerrar/(:num)', 'Admin\Caja::cerrar/$1');
    $routes->post('caja/registrarIngreso', 'Admin\Caja::registrarIngreso');
    $routes->post('caja/registrarEgreso', 'Admin\Caja::registrarEgreso');
    $routes->get('caja/historial', 'Admin\Caja::historial');
    $routes->get('caja/ver/(:num)', 'Admin\Caja::ver/$1');

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

// ---------------- OAUTH GOOGLE ----------------
$routes->get('oauth/google', 'OAuth::googleRedirect');
$routes->get('oauth/google/callback', 'OAuth::googleCallback');

// ---------------- MERCADO PAGO ----------------
$routes->post('mercadopago/crear', 'MercadoPago::crearPreferencia');
$routes->get('mercadopago/success', 'MercadoPago::success');
$routes->get('mercadopago/failure', 'MercadoPago::failure');
$routes->get('mercadopago/pending', 'MercadoPago::pending');
$routes->post('mercadopago/webhook', 'MercadoPago::webhook');

// ---------------- AUTH SHIELD ----------------
service('auth')->routes($routes, ['except' => ['login', 'register']]);

// ---------------- LOGIN / REGISTER ----------------
$routes->get('login', '\App\Controllers\Auth\LoginController::loginView');
$routes->post('login', '\App\Controllers\Auth\LoginController::loginAction');
$routes->get('register', '\App\Controllers\Auth\RegisterController::registerView');
$routes->post('register', '\App\Controllers\Auth\RegisterController::registerAction');
