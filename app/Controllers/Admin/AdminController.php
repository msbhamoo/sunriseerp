<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * AdminController
 *
 * Auto-generated CI4 controller migrating CI3 Admin.
 */
class AdminController extends BaseController
{
    public function unauthorized()
    {
        return $this->dispatchLegacy('Admin', 'unauthorized', func_get_args());
    }

    public function updateAddonVerify()
    {
        return $this->dispatchLegacy('Admin', 'updateAddonVerify', func_get_args());
    }

    public function dashboard()
    {
        return $this->dispatchLegacy('Admin', 'dashboard', func_get_args());
    }

    public function getUserImage()
    {
        return $this->dispatchLegacy('Admin', 'getUserImage', func_get_args());
    }

    public function getSession()
    {
        return $this->dispatchLegacy('Admin', 'getSession', func_get_args());
    }

    public function updateSession()
    {
        return $this->dispatchLegacy('Admin', 'updateSession', func_get_args());
    }

    public function updatePurchaseCode()
    {
        return $this->dispatchLegacy('Admin', 'updatePurchaseCode', func_get_args());
    }

    public function backup()
    {
        return $this->dispatchLegacy('Admin', 'backup', func_get_args());
    }

    public function changepass()
    {
        return $this->dispatchLegacy('Admin', 'changepass', func_get_args());
    }

    public function pdf_report()
    {
        return $this->dispatchLegacy('Admin', 'pdf_report', func_get_args());
    }

    public function downloadbackup($file)
    {
        return $this->dispatchLegacy('Admin', 'downloadbackup', func_get_args());
    }

    public function dropbackup($file)
    {
        return $this->dispatchLegacy('Admin', 'dropbackup', func_get_args());
    }

    public function search()
    {
        return $this->dispatchLegacy('Admin', 'search', func_get_args());
    }

    public function getCollectionbymonth()
    {
        return $this->dispatchLegacy('Admin', 'getCollectionbymonth', func_get_args());
    }

    public function getCollectionbyday($date)
    {
        return $this->dispatchLegacy('Admin', 'getCollectionbyday', func_get_args());
    }

    public function getExpensebyday($date)
    {
        return $this->dispatchLegacy('Admin', 'getExpensebyday', func_get_args());
    }

    public function getExpensebymonth()
    {
        return $this->dispatchLegacy('Admin', 'getExpensebymonth', func_get_args());
    }

    public function whatever($feecollection_array, $start_month_date, $end_month_date)
    {
        return $this->dispatchLegacy('Admin', 'whatever', func_get_args());
    }

    public function startmonthandend()
    {
        return $this->dispatchLegacy('Admin', 'startmonthandend', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Admin', 'handle_upload', func_get_args());
    }

    public function generate_key($length = 12)
    {
        return $this->dispatchLegacy('Admin', 'generate_key', func_get_args());
    }

    public function addCronsecretkey($id)
    {
        return $this->dispatchLegacy('Admin', 'addCronsecretkey', func_get_args());
    }

    public function updateandappCode()
    {
        return $this->dispatchLegacy('Admin', 'updateandappCode', func_get_args());
    }

    public function filetype()
    {
        return $this->dispatchLegacy('Admin', 'filetype', func_get_args());
    }

    public function addfiletype()
    {
        return $this->dispatchLegacy('Admin', 'addfiletype', func_get_args());
    }

    public function validate_extension($extension)
    {
        return $this->dispatchLegacy('Admin', 'validate_extension', func_get_args());
    }

    public function validate_mime($mime)
    {
        return $this->dispatchLegacy('Admin', 'validate_mime', func_get_args());
    }

    public function updateaddon()
    {
        return $this->dispatchLegacy('Admin', 'updateaddon', func_get_args());
    }

    public function searchvalidation()
    {
        return $this->dispatchLegacy('Admin', 'searchvalidation', func_get_args());
    }

    public function search_text()
    {
        return $this->dispatchLegacy('Admin', 'search_text', func_get_args());
    }

    public function dtstudentlist()
    {
        return $this->dispatchLegacy('Admin', 'dtstudentlist', func_get_args());
    }

    public function ajax_search()
    {
        return $this->dispatchLegacy('Admin', 'ajax_search', func_get_args());
    }

    public function switch_dashboard($version = '2.0')
    {
        return $this->dispatchLegacy('Admin', 'switch_dashboard', func_get_args());
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