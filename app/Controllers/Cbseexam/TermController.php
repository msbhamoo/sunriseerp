<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * TermController
 *
 * Auto-generated CI4 controller migrating CI3 Term.
 */
class TermController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Term', 'index', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Term', 'add', func_get_args());
    }

    public function check_duplicate_code()
    {
        return $this->dispatchLegacy('Term', 'check_duplicate_code', func_get_args());
    }

    public function getdata()
    {
        return $this->dispatchLegacy('Term', 'getdata', func_get_args());
    }

    public function get_ClassSectionByTermId($termid)
    {
        return $this->dispatchLegacy('Term', 'get_ClassSectionByTermId', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Term', 'delete', func_get_args());
    }

    public function gettermlist()
    {
        return $this->dispatchLegacy('Term', 'gettermlist', func_get_args());
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