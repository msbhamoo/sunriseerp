<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * AiquestiongeneratorController
 *
 * Auto-generated CI4 controller migrating CI3 Aiquestiongenerator.
 */
class AiquestiongeneratorController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Aiquestiongenerator', 'index', func_get_args());
    }

    public function generate_paper_ajax()
    {
        return $this->dispatchLegacy('Aiquestiongenerator', 'generate_paper_ajax', func_get_args());
    }

    public function regenerate_single_question_ajax()
    {
        return $this->dispatchLegacy('Aiquestiongenerator', 'regenerate_single_question_ajax', func_get_args());
    }

    public function save_edited_paper_ajax()
    {
        return $this->dispatchLegacy('Aiquestiongenerator', 'save_edited_paper_ajax', func_get_args());
    }

    public function get_saved_paper_ajax()
    {
        return $this->dispatchLegacy('Aiquestiongenerator', 'get_saved_paper_ajax', func_get_args());
    }

    public function delete_saved_paper_ajax()
    {
        return $this->dispatchLegacy('Aiquestiongenerator', 'delete_saved_paper_ajax', func_get_args());
    }

    public function get_saved_papers_list_ajax()
    {
        return $this->dispatchLegacy('Aiquestiongenerator', 'get_saved_papers_list_ajax', func_get_args());
    }

    public function save_to_question_bank_ajax()
    {
        return $this->dispatchLegacy('Aiquestiongenerator', 'save_to_question_bank_ajax', func_get_args());
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