<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('ci4-status', 'Home::index');

$routes->group('api/v2', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->get('ptm', 'PtmController::index');
    $routes->get('ptm/(:num)', 'PtmController::show/$1');
    $routes->get('ptm/(:num)/attendances', 'PtmController::attendances/$1');
    $routes->post('ptm/attendance', 'PtmController::saveAttendance');
});
