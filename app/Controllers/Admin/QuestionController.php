<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * QuestionController
 *
 * Auto-generated CI4 controller migrating CI3 Question.
 */
class QuestionController extends BaseController
{
    public function read($id)
    {
        return $this->dispatchLegacy('Question', 'read', func_get_args());
    }

    public function index($offset = 0)
    {
        return $this->dispatchLegacy('Question', 'index', func_get_args());
    }

    public function getQuestionByID()
    {
        return $this->dispatchLegacy('Question', 'getQuestionByID', func_get_args());
    }

    public function exportformat()
    {
        return $this->dispatchLegacy('Question', 'exportformat', func_get_args());
    }

    public function bulkdelete()
    {
        return $this->dispatchLegacy('Question', 'bulkdelete', func_get_args());
    }

    public function uploadfile()
    {
        return $this->dispatchLegacy('Question', 'uploadfile', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Question', 'handle_upload', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Question', 'add', func_get_args());
    }

    public function getRecord($id)
    {
        return $this->dispatchLegacy('Question', 'getRecord', func_get_args());
    }

    public function addform()
    {
        return $this->dispatchLegacy('Question', 'addform', func_get_args());
    }

    public function editform()
    {
        return $this->dispatchLegacy('Question', 'editform', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Question', 'delete', func_get_args());
    }

    public function getimages()
    {
        return $this->dispatchLegacy('Question', 'getimages', func_get_args());
    }

    public function getpagination($total_data, $limit, $page)
    {
        return $this->dispatchLegacy('Question', 'getpagination', func_get_args());
    }

    public function getDatatable()
    {
        return $this->dispatchLegacy('Question', 'getDatatable', func_get_args());
    }

    public function questionsearchvalidation()
    {
        return $this->dispatchLegacy('Question', 'questionsearchvalidation', func_get_args());
    }

    public function ai_generate_questions_ajax()
    {
        return $this->dispatchLegacy('Question', 'ai_generate_questions_ajax', func_get_args());
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