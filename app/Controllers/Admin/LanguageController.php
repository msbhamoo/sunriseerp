<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * LanguageController
 *
 * Auto-generated CI4 controller migrating CI3 Language.
 */
class LanguageController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Language', 'index', func_get_args());
    }

    public function onloadlanguage()
    {
        return $this->dispatchLegacy('Language', 'onloadlanguage', func_get_args());
    }

    public function user_language($lang_id)
    {
        return $this->dispatchLegacy('Language', 'user_language', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Language', 'view', func_get_args());
    }

    public function editlanguage()
    {
        return $this->dispatchLegacy('Language', 'editlanguage', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Language', 'delete', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Language', 'create', func_get_args());
    }

    public function addPharses($id)
    {
        return $this->dispatchLegacy('Language', 'addPharses', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Language', 'edit', func_get_args());
    }

    public function migratelang()
    {
        return $this->dispatchLegacy('Language', 'migratelang', func_get_args());
    }

    public function copydirr($fromDir, $toDir, $chmod = 0757, $verbose = false)
    {
        return $this->dispatchLegacy('Language', 'copydirr', func_get_args());
    }

    public function select_language($language_id)
    {
        return $this->dispatchLegacy('Language', 'select_language', func_get_args());
    }

    public function defoult_language($language_id)
    {
        return $this->dispatchLegacy('Language', 'defoult_language', func_get_args());
    }

    public function unselect_language($language_id)
    {
        return $this->dispatchLegacy('Language', 'unselect_language', func_get_args());
    }

    public function rtl()
    {
        return $this->dispatchLegacy('Language', 'rtl', func_get_args());
    }

    public function write_lang_file($language, $writedata)
    {
        return $this->dispatchLegacy('Language', 'write_lang_file', func_get_args());
    }

    public function create_language_file()
    {
        return $this->dispatchLegacy('Language', 'create_language_file', func_get_args());
    }

    public function update_520()
    {
        return $this->dispatchLegacy('Language', 'update_520', func_get_args());
    }

    public function writeTranslateText($language, $writedata)
    {
        return $this->dispatchLegacy('Language', 'writeTranslateText', func_get_args());
    }

    public function updateTranslateText($language, $writedata)
    {
        return $this->dispatchLegacy('Language', 'updateTranslateText', func_get_args());
    }

    public function editcountrycode()
    {
        return $this->dispatchLegacy('Language', 'editcountrycode', func_get_args());
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