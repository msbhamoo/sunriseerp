<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * OnlineadmissionController
 *
 * Auto-generated CI4 controller migrating CI3 Onlineadmission.
 */
class OnlineadmissionController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Onlineadmission', 'validateCanUploadFile', func_get_args());
    }

    public function admissionsetting()
    {
        return $this->dispatchLegacy('Onlineadmission', 'admissionsetting', func_get_args());
    }

    public function changeformfieldsetting()
    {
        return $this->dispatchLegacy('Onlineadmission', 'changeformfieldsetting', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Onlineadmission', 'handle_upload', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Onlineadmission', 'download', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/admin/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}