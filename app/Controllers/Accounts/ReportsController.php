<?php

namespace App\Controllers\Accounts;

use App\Controllers\BaseController;

/**
 * ReportsController
 *
 * Auto-generated CI4 controller migrating CI3 Reports.
 */
class ReportsController extends BaseController
{
    public function daybook()
    {
        return $this->dispatchLegacy('Reports', 'daybook', func_get_args());
    }

    public function cashbook()
    {
        return $this->dispatchLegacy('Reports', 'cashbook', func_get_args());
    }

    public function bankbook()
    {
        return $this->dispatchLegacy('Reports', 'bankbook', func_get_args());
    }

    public function statement()
    {
        return $this->dispatchLegacy('Reports', 'statement', func_get_args());
    }

    public function export_statement($format = 'print')
    {
        return $this->dispatchLegacy('Reports', 'export_statement', func_get_args());
    }

    public function outstanding()
    {
        return $this->dispatchLegacy('Reports', 'outstanding', func_get_args());
    }

    public function expincome_type()
    {
        return $this->dispatchLegacy('Reports', 'expincome_type', func_get_args());
    }

    public function trialbalance()
    {
        return $this->dispatchLegacy('Reports', 'trialbalance', func_get_args());
    }

    public function profitloss()
    {
        return $this->dispatchLegacy('Reports', 'profitloss', func_get_args());
    }

    public function balancesheet()
    {
        return $this->dispatchLegacy('Reports', 'balancesheet', func_get_args());
    }

    public function false_reversals()
    {
        return $this->dispatchLegacy('Reports', 'false_reversals', func_get_args());
    }


    public function _remap($method, ...$params)
    {
        if (method_exists($this, $method)) {
            return $this->$method(...$params);
        }
        return $this->dispatchLegacy('Reports', $method, $params);
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