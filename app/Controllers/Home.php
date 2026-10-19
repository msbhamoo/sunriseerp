<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    public function index(): ResponseInterface
    {
        $db = \Config\Database::connect();
        $dbVersion = 'Unknown';
        $dbStatus = 'disconnected';
        try {
            $dbVersion = $db->getVersion();
            $dbStatus = 'connected';
        } catch (\Throwable $e) {
            $dbStatus = 'error: ' . $e->getMessage();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'framework' => 'CodeIgniter 4',
            'ci_version' => \CodeIgniter\CodeIgniter::CI_VERSION,
            'php_version' => PHP_VERSION,
            'database' => [
                'status' => $dbStatus,
                'version' => $dbVersion,
                'database' => $db->getDatabase(),
            ],
            'session_driver' => config('Session')->driver,
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
    }
}
