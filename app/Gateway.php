<?php

namespace App;

use CodeIgniter\Boot;
use Config\Paths;

/**
 * Migration Gateway Router
 *
 * Directs traffic between CodeIgniter 4 (migrated routes) and
 * CodeIgniter 3 (legacy routes) at the front controller level.
 */
class Gateway
{
    /**
     * Registered CI4 route patterns.
     * Use exact path (e.g., 'ci4-status') or prefix wildcard (e.g., 'api/v2/*').
     *
     * @var array<string>
     */
    protected static array $ci4Routes = [
        'ci4-status',
        'ci4-status/*',
        'api/v2/*',
        'admin/ptm',
        'admin/ptm/*',
    ];

    /**
     * Checks if the incoming request path matches any registered CI4 route.
     */
    public static function shouldRouteToCI4(): bool
    {
        $path = static::getRequestPath();

        foreach (static::$ci4Routes as $pattern) {
            if ($pattern === $path) {
                return true;
            }

            if (str_ends_with($pattern, '/*')) {
                $prefix = rtrim(substr($pattern, 0, -2), '/');
                if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Extracts and normalizes the request path relative to the app base.
     */
    public static function getRequestPath(): string
    {
        // First check query string for index.php?/path/here format
        if (!empty($_SERVER['QUERY_STRING']) && str_starts_with($_SERVER['QUERY_STRING'], '/')) {
            $qs = $_SERVER['QUERY_STRING'];
            if (($pos = strpos($qs, '&')) !== false) {
                $qs = substr($qs, 0, $pos);
            }
            return trim($qs, '/');
        }

        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Strip query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

        // Strip baseDir from URI if present (e.g. /lms)
        if ($baseDir !== '' && str_starts_with($uri, $baseDir)) {
            $uri = substr($uri, strlen($baseDir));
        }

        // Strip index.php if present
        if (str_starts_with($uri, '/index.php')) {
            $uri = substr($uri, strlen('/index.php'));
        }

        return trim($uri, '/');
    }

    /**
     * Boots CodeIgniter 4 and executes the request.
     */
    public static function dispatch(): void
    {
        // Load composer autoloader
        require_once __DIR__ . '/../vendor/autoload.php';

        // Define front controller path
        if (!defined('FCPATH')) {
            define('FCPATH', realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR);
        }

        require_once __DIR__ . '/Config/Paths.php';
        $paths = new Paths();

        require_once $paths->systemDirectory . '/Boot.php';

        exit(Boot::bootWeb($paths));
    }
}
