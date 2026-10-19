<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('ci4-status', 'Home::index');

$routes->group('api/v2', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    // Classes & Sections
    $routes->get('classes', 'StudentController::classes');

    // Students
    $routes->get('students', 'StudentController::index');
    $routes->get('students/(:num)', 'StudentController::show/$1');
    $routes->post('students', 'StudentController::create');
    $routes->put('students/(:num)', 'StudentController::update/$1');
    $routes->delete('students/(:num)', 'StudentController::disable/$1');

    // PTM API
    $routes->get('ptm', 'PtmController::index');
    $routes->get('ptm/(:num)', 'PtmController::show/$1');
    $routes->get('ptm/(:num)/attendances', 'PtmController::attendances/$1');
    $routes->post('ptm/attendance', 'PtmController::saveAttendance');
});

// Admin Web Routes (Full Module Migration)
$routes->group('admin/ptm', ['namespace' => 'App\Controllers\Admin', 'filter' => 'admin_auth'], static function ($routes) {
    $routes->match(['GET', 'POST'], '/', 'PtmController::index');
    $routes->match(['GET', 'POST'], 'index', 'PtmController::index');
    $routes->match(['GET', 'POST'], 'edit/(:num)', 'PtmController::edit/$1');
    $routes->get('delete/(:num)', 'PtmController::delete/$1');
    $routes->get('attendance/(:num)', 'PtmController::attendance/$1');
    $routes->post('get_ptm_detail/(:num)', 'PtmController::get_ptm_detail/$1');
    $routes->post('get_sections_multi', 'PtmController::get_sections_multi');
});
