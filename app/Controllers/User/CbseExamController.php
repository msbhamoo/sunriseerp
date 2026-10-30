<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * CbseExamController
 *
 * CI4 entry point for the legacy student CBSE exam pages
 * (application/controllers/user/cbse/Exam.php): user/cbse/exam/result|timetable.
 */
class CbseExamController extends BaseController
{
    public function result()
    {
        return $this->dispatchLegacy('result', func_get_args());
    }

    public function timetable()
    {
        return $this->dispatchLegacy('timetable', func_get_args());
    }

    protected function dispatchLegacy(string $method, array $params)
    {
        $_SERVER['CI4_LEGACY_CONTROLLER'] = 'Exam';
        $_SERVER['CI4_LEGACY_METHOD']     = $method;
        $_SERVER['CI4_LEGACY_PARAMS']     = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        require_once APPPATH . '../application/controllers/user/cbse/Exam.php';

        $instance = new \Exam();
        return call_user_func_array([$instance, $method], $params);
    }
}
