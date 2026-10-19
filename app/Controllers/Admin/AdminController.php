<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AdminController
 *
 * Base controller for all admin-facing modules migrated to CodeIgniter 4.
 */
class AdminController extends BaseController
{
    protected ?array $adminUser = null;
    protected ?int $staffId = null;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $session = session();
        if ($session->has('admin')) {
            $this->adminUser = $session->get('admin');
            $this->staffId   = isset($this->adminUser['id']) ? (int)$this->adminUser['id'] : null;
        }
    }

    /**
     * Get current admin session data.
     */
    protected function getAdminUser(): ?array
    {
        return $this->adminUser;
    }

    /**
     * Get current staff ID.
     */
    protected function getStaffId(): ?int
    {
        return $this->staffId;
    }

    /**
     * Helper for standard JSON responses.
     */
    protected function respondJson(array $data, int $statusCode = 200): ResponseInterface
    {
        return $this->response->setStatusCode($statusCode)->setJSON($data);
    }
}
