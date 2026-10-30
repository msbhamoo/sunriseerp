<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * AiexamgeneratorController
 *
 * Auto-generated CI4 controller migrating CI3 Aiexamgenerator.
 */
class AiexamgeneratorController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Aiexamgenerator', 'index', func_get_args());
    }

    public function generate_paper_ajax()
    {
        return $this->dispatchLegacy('Aiexamgenerator', 'generate_paper_ajax', func_get_args());
    }

    public function regenerate_single_question_ajax()
    {
        return $this->dispatchLegacy('Aiexamgenerator', 'regenerate_single_question_ajax', func_get_args());
    }

    public function save_edited_paper_ajax()
    {
        return $this->dispatchLegacy('Aiexamgenerator', 'save_edited_paper_ajax', func_get_args());
    }

    public function get_saved_paper_ajax()
    {
        return $this->dispatchLegacy('Aiexamgenerator', 'get_saved_paper_ajax', func_get_args());
    }

    public function delete_saved_paper_ajax()
    {
        return $this->dispatchLegacy('Aiexamgenerator', 'delete_saved_paper_ajax', func_get_args());
    }

    public function get_saved_papers_list_ajax()
    {
        return $this->dispatchLegacy('Aiexamgenerator', 'get_saved_papers_list_ajax', func_get_args());
    }

    public function save_to_question_bank_ajax()
    {
        return $this->dispatchLegacy('Aiexamgenerator', 'save_to_question_bank_ajax', func_get_args());
    }

    public function get_or_fetch_chapters_ajax()
    {
        return $this->dispatchLegacy('Aiexamgenerator', 'get_or_fetch_chapters_ajax', func_get_args());
    }

    public function get_subjects_by_class_ajax()
    {
        return $this->dispatchLegacy('Aiexamgenerator', 'get_subjects_by_class_ajax', func_get_args());
    }

    public function get_sync_pairs_ajax()
    {
        return $this->dispatchLegacy('Aiexamgenerator', 'get_sync_pairs_ajax', func_get_args());
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