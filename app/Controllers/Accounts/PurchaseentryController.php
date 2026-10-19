<?php

namespace App\Controllers\Accounts;

use App\Controllers\BaseController;

/**
 * PurchaseentryController
 *
 * Auto-generated CI4 controller migrating CI3 Purchaseentry.
 */
class PurchaseentryController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Purchaseentry', 'index', func_get_args());
    }

    public function getlist()
    {
        return $this->dispatchLegacy('Purchaseentry', 'getlist', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Purchaseentry', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Purchaseentry', 'delete', func_get_args());
    }

    public function addsupplier()
    {
        return $this->dispatchLegacy('Purchaseentry', 'addsupplier', func_get_args());
    }

    public function approve_purchase()
    {
        return $this->dispatchLegacy('Purchaseentry', 'approve_purchase', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/accounts/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}