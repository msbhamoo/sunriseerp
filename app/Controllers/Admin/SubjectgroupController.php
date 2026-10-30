<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * SubjectgroupController
 *
 * Auto-generated CI4 controller migrating CI3 Subjectgroup.
 */
class SubjectgroupController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Subjectgroup', 'index', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Subjectgroup', 'delete', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Subjectgroup', 'edit', func_get_args());
    }

    public function addsubjectgroup()
    {
        return $this->dispatchLegacy('Subjectgroup', 'addsubjectgroup', func_get_args());
    }

    public function getGroupByClassandSection()
    {
        return $this->dispatchLegacy('Subjectgroup', 'getGroupByClassandSection', func_get_args());
    }

    public function getSubjectByClassandSectionDate()
    {
        return $this->dispatchLegacy('Subjectgroup', 'getSubjectByClassandSectionDate', func_get_args());
    }

    public function getAllSubjectByClassandSection()
    {
        return $this->dispatchLegacy('Subjectgroup', 'getAllSubjectByClassandSection', func_get_args());
    }

    public function getSubjectByClassandSection()
    {
        return $this->dispatchLegacy('Subjectgroup', 'getSubjectByClassandSection', func_get_args());
    }

    public function getGroupsubjects()
    {
        return $this->dispatchLegacy('Subjectgroup', 'getGroupsubjects', func_get_args());
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