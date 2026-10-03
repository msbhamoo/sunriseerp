<?php
/**
 * School Store & Inventory Management System (store.sunriseschool.in)
 * Database Connection & Environment Configuration
 */

// Detect environment
$is_cli = (php_sapi_name() === 'cli');
$is_localhost = $is_cli || (isset($_SERVER['HTTP_HOST']) && (
    $_SERVER['HTTP_HOST'] === 'localhost' || 
    $_SERVER['HTTP_HOST'] === '127.0.0.1' || 
    strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0
));

if ($is_localhost) {
    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'erp');
    define('DB_PORT', 3306);
    define('BASE_URL', 'http://localhost/lms/store/');
} else {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'u774654038_sunrise');
    define('DB_PASS', 'Sunrise@2026');
    define('DB_NAME', 'u774654038_sunrise');
    define('DB_PORT', 3306);
    define('BASE_URL', 'https://store.sunriseschool.in/');
}

function get_db_connection() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        if ($conn->connect_error) {
            die("Database Connection Error: " . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");
    }
    return $conn;
}
