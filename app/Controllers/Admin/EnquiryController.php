<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * EnquiryController
 *
 * Auto-generated CI4 controller migrating CI3 Enquiry.
 */
class EnquiryController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Enquiry', 'index', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Enquiry', 'add', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Enquiry', 'delete', func_get_args());
    }

    public function follow_up($enquiry_id, $status, $created_by)
    {
        return $this->dispatchLegacy('Enquiry', 'follow_up', func_get_args());
    }

    public function follow_up_insert()
    {
        return $this->dispatchLegacy('Enquiry', 'follow_up_insert', func_get_args());
    }

    public function follow_up_list($id)
    {
        return $this->dispatchLegacy('Enquiry', 'follow_up_list', func_get_args());
    }

    public function details($id, $status)
    {
        return $this->dispatchLegacy('Enquiry', 'details', func_get_args());
    }

    public function editpost($id)
    {
        return $this->dispatchLegacy('Enquiry', 'editpost', func_get_args());
    }

    public function follow_up_delete($follow_up_id, $enquiry_id)
    {
        return $this->dispatchLegacy('Enquiry', 'follow_up_delete', func_get_args());
    }

    public function check_default($post_string)
    {
        return $this->dispatchLegacy('Enquiry', 'check_default', func_get_args());
    }

    public function change_status()
    {
        return $this->dispatchLegacy('Enquiry', 'change_status', func_get_args());
    }

    public function check_number()
    {
        return $this->dispatchLegacy('Enquiry', 'check_number', func_get_args());
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