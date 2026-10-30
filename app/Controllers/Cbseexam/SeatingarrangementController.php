<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * SeatingarrangementController
 *
 * Auto-generated CI4 controller migrating CI3 Seatingarrangement.
 */
class SeatingarrangementController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Seatingarrangement', 'index', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Seatingarrangement', 'create', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Seatingarrangement', 'delete', func_get_args());
    }

    public function change_status($id, $status)
    {
        return $this->dispatchLegacy('Seatingarrangement', 'change_status', func_get_args());
    }

    public function assign_invigilators($allocation_id)
    {
        return $this->dispatchLegacy('Seatingarrangement', 'assign_invigilators', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/cbseexam/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}