<?php

namespace App\Controllers\.;

use App\Controllers\BaseController;

/**
 * SchsettingsController
 *
 * Auto-generated CI4 controller migrating CI3 Schsettings.
 */
class SchsettingsController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Schsettings', 'validateCanUploadFile', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Schsettings', 'index', func_get_args());
    }

    public function ajax_editlogo()
    {
        return $this->dispatchLegacy('Schsettings', 'ajax_editlogo', func_get_args());
    }

    public function ajax_editadmin_smalllogo()
    {
        return $this->dispatchLegacy('Schsettings', 'ajax_editadmin_smalllogo', func_get_args());
    }

    public function ajax_editadmin_adminlogo()
    {
        return $this->dispatchLegacy('Schsettings', 'ajax_editadmin_adminlogo', func_get_args());
    }

    public function editLogo($id)
    {
        return $this->dispatchLegacy('Schsettings', 'editLogo', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Schsettings', 'handle_upload', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Schsettings', 'view', func_get_args());
    }

    public function getSchsetting()
    {
        return $this->dispatchLegacy('Schsettings', 'getSchsetting', func_get_args());
    }

    public function generalsetting()
    {
        return $this->dispatchLegacy('Schsettings', 'generalsetting', func_get_args());
    }

    public function ajax_applogo()
    {
        return $this->dispatchLegacy('Schsettings', 'ajax_applogo', func_get_args());
    }

    public function check_admission_digit()
    {
        return $this->dispatchLegacy('Schsettings', 'check_admission_digit', func_get_args());
    }

    public function check_staff_id_digit()
    {
        return $this->dispatchLegacy('Schsettings', 'check_staff_id_digit', func_get_args());
    }

    public function logo()
    {
        return $this->dispatchLegacy('Schsettings', 'logo', func_get_args());
    }

    public function signature()
    {
        return $this->dispatchLegacy('Schsettings', 'signature', func_get_args());
    }

    public function ajax_edit_signature_staff()
    {
        return $this->dispatchLegacy('Schsettings', 'ajax_edit_signature_staff', func_get_args());
    }

    public function ajax_remove_signature()
    {
        return $this->dispatchLegacy('Schsettings', 'ajax_remove_signature', func_get_args());
    }

    public function ajax_edit_signature()
    {
        return $this->dispatchLegacy('Schsettings', 'ajax_edit_signature', func_get_args());
    }

    public function miscellaneous()
    {
        return $this->dispatchLegacy('Schsettings', 'miscellaneous', func_get_args());
    }

    public function savemiscellaneous()
    {
        return $this->dispatchLegacy('Schsettings', 'savemiscellaneous', func_get_args());
    }

    public function backendtheme()
    {
        return $this->dispatchLegacy('Schsettings', 'backendtheme', func_get_args());
    }

    public function savebackendtheme()
    {
        return $this->dispatchLegacy('Schsettings', 'savebackendtheme', func_get_args());
    }

    public function mobileapp()
    {
        return $this->dispatchLegacy('Schsettings', 'mobileapp', func_get_args());
    }

    public function savemobileapp()
    {
        return $this->dispatchLegacy('Schsettings', 'savemobileapp', func_get_args());
    }

    public function studentguardianpanel()
    {
        return $this->dispatchLegacy('Schsettings', 'studentguardianpanel', func_get_args());
    }

    public function studentguardian()
    {
        return $this->dispatchLegacy('Schsettings', 'studentguardian', func_get_args());
    }

    public function fees()
    {
        return $this->dispatchLegacy('Schsettings', 'fees', func_get_args());
    }

    public function savefees()
    {
        return $this->dispatchLegacy('Schsettings', 'savefees', func_get_args());
    }

    public function idautogeneration()
    {
        return $this->dispatchLegacy('Schsettings', 'idautogeneration', func_get_args());
    }

    public function saveidautogeneration()
    {
        return $this->dispatchLegacy('Schsettings', 'saveidautogeneration', func_get_args());
    }

    public function attendancetype()
    {
        return $this->dispatchLegacy('Schsettings', 'attendancetype', func_get_args());
    }

    public function maintenance()
    {
        return $this->dispatchLegacy('Schsettings', 'maintenance', func_get_args());
    }

    public function saveattendancetype()
    {
        return $this->dispatchLegacy('Schsettings', 'saveattendancetype', func_get_args());
    }

    public function save_maintenance()
    {
        return $this->dispatchLegacy('Schsettings', 'save_maintenance', func_get_args());
    }

    public function login_page_background()
    {
        return $this->dispatchLegacy('Schsettings', 'login_page_background', func_get_args());
    }

    public function add_admin_login_background()
    {
        return $this->dispatchLegacy('Schsettings', 'add_admin_login_background', func_get_args());
    }

    public function savestaffsetting()
    {
        return $this->dispatchLegacy('Schsettings', 'savestaffsetting', func_get_args());
    }

    public function savelatepolicy()
    {
        return $this->dispatchLegacy('Schsettings', 'savelatepolicy', func_get_args());
    }

    public function staffshiftstatus()
    {
        return $this->dispatchLegacy('Schsettings', 'staffshiftstatus', func_get_args());
    }

    public function deletestaffshift()
    {
        return $this->dispatchLegacy('Schsettings', 'deletestaffshift', func_get_args());
    }

    public function whatsappsettings()
    {
        return $this->dispatchLegacy('Schsettings', 'whatsappsettings', func_get_args());
    }

    public function savewhatsappsettings()
    {
        return $this->dispatchLegacy('Schsettings', 'savewhatsappsettings', func_get_args());
    }

    public function chatsetting()
    {
        return $this->dispatchLegacy('Schsettings', 'chatsetting', func_get_args());
    }

    public function savechatsetting()
    {
        return $this->dispatchLegacy('Schsettings', 'savechatsetting', func_get_args());
    }

    public function googledrivesetting()
    {
        return $this->dispatchLegacy('Schsettings', 'googledrivesetting', func_get_args());
    }

    public function savegoogledrive()
    {
        return $this->dispatchLegacy('Schsettings', 'savegoogledrive', func_get_args());
    }

    public function cbse_disclosure()
    {
        return $this->dispatchLegacy('Schsettings', 'cbse_disclosure', func_get_args());
    }

    public function ajax_save_cbse_disclosure()
    {
        return $this->dispatchLegacy('Schsettings', 'ajax_save_cbse_disclosure', func_get_args());
    }

    public function ajax_add_cbse_custom_document()
    {
        return $this->dispatchLegacy('Schsettings', 'ajax_add_cbse_custom_document', func_get_args());
    }

    public function ajax_delete_cbse_custom_document()
    {
        return $this->dispatchLegacy('Schsettings', 'ajax_delete_cbse_custom_document', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/./' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}