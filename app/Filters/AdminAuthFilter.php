<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AdminAuthFilter
 *
 * Verifies admin session authentication across CI3/CI4 shared sessions.
 */
class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->has('admin')) {
            if ($request->isAJAX() || str_contains($request->getHeaderLine('Accept'), 'application/json')) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Unauthorized. Please log in.'
                    ]);
            }

            return redirect()->to(base_url('site/login'));
        }

        // Apply staff audit tracking to MySQL session if DB is connected
        try {
            $admin = $session->get('admin');
            $staffId = $admin['id'] ?? null;
            if (!empty($staffId)) {
                $db = \Config\Database::connect();
                $db->query("SET @current_audit_user_id = " . (int)$staffId);
            }
        } catch (\Throwable $e) {
            // Non-blocking for audit tracking
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing required
    }
}
