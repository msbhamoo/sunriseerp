<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * ResultController
 *
 * Auto-generated CI4 controller migrating CI3 Result.
 */
class ResultController extends BaseController
{
    public function t()
    {
        return $this->dispatchLegacy('Result', 't', func_get_args());
    }

    public function marksheet()
    {
        return $this->dispatchLegacy('Result', 'marksheet', func_get_args());
    }

    public function test_multi($cbse_template_id, $students)
    {
        return $this->dispatchLegacy('Result', 'test_multi', func_get_args());
    }

    public function test_multi1()
    {
        return $this->dispatchLegacy('Result', 'test_multi1', func_get_args());
    }

    public function test_multi_exam($cbse_template_id, $students)
    {
        return $this->dispatchLegacy('Result', 'test_multi_exam', func_get_args());
    }

    public function test_multi_exam1()
    {
        return $this->dispatchLegacy('Result', 'test_multi_exam1', func_get_args());
    }

    public function multi_exam_without_term($cbse_template_id, $students)
    {
        return $this->dispatchLegacy('Result', 'multi_exam_without_term', func_get_args());
    }

    public function multi_exam_without_term1()
    {
        return $this->dispatchLegacy('Result', 'multi_exam_without_term1', func_get_args());
    }

    public function test($cbse_template_id, $cbse_exam_id, $students)
    {
        return $this->dispatchLegacy('Result', 'test', func_get_args());
    }

    public function test1()
    {
        return $this->dispatchLegacy('Result', 'test1', func_get_args());
    }

    public function printmarksheet()
    {
        return $this->dispatchLegacy('Result', 'printmarksheet', func_get_args());
    }

    public function demo()
    {
        return $this->dispatchLegacy('Result', 'demo', func_get_args());
    }

    public function examtermwise()
    {
        return $this->dispatchLegacy('Result', 'examtermwise', func_get_args());
    }

    public function termwiseresult()
    {
        return $this->dispatchLegacy('Result', 'termwiseresult', func_get_args());
    }

    public function examwiseresult()
    {
        return $this->dispatchLegacy('Result', 'examwiseresult', func_get_args());
    }

    public function getExamAssesmentByTerm($array, $find_cbse_term_id)
    {
        return $this->dispatchLegacy('Result', 'getExamAssesmentByTerm', func_get_args());
    }

    public function getExamAssesment($array, $find_cbse_term_id)
    {
        return $this->dispatchLegacy('Result', 'getExamAssesment', func_get_args());
    }

    public function midterm_sbbt($cbse_template_id, $students)
    {
        return $this->dispatchLegacy('Result', 'midterm_sbbt', func_get_args());
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