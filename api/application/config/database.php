<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$query_builder = TRUE;

$is_localhost = isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1' || strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0);

if ($is_localhost) {
    $db['default'] = array(
        'dsn'          => '',
        'hostname'     => '127.0.0.1:3307',
        'username'     => 'root',
        'password'     => '',
        'database'     => 'schoolsms',
        'dbdriver'     => 'mysqli',
        'dbprefix'     => '',
        'pconnect'     => FALSE,
        'db_debug'     => (ENVIRONMENT !== 'production'),
        'cache_on'     => FALSE,
        'cachedir'     => '',
        'char_set'     => 'utf8mb4',
        'dbcollat'     => 'utf8mb4_unicode_ci',
        'swap_pre'     => '',
        'encrypt'      => FALSE,
        'compress'     => FALSE,
        'stricton'     => FALSE,
        'failover'     => array(),
        'save_queries' => TRUE,
        'multi_branch' => FALSE,
    );
} else {
    $db['default'] = array(
        'dsn'          => '',
        'hostname'     => 'localhost',
        'username'     => 'u774654038_sunrise',
        'password'     => 'Sunrise@2026',
        'database'     => 'u774654038_sunrise',
        'dbdriver'     => 'mysqli',
        'dbprefix'     => '',
        'pconnect'     => FALSE,
        'db_debug'     => (ENVIRONMENT !== 'production'),
        'cache_on'     => FALSE,
        'cachedir'     => '',
        'char_set'     => 'utf8mb4',
        'dbcollat'     => 'utf8mb4_unicode_ci',
        'swap_pre'     => '',
        'encrypt'      => FALSE,
        'compress'     => FALSE,
        'stricton'     => FALSE,
        'failover'     => array(),
        'save_queries' => TRUE,
        'multi_branch' => FALSE,
    );
}

$active_group = 'default';

