<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * ObservationController
 *
 * Auto-generated CI4 controller migrating CI3 Observation.
 */
class ObservationController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Observation', 'index', func_get_args());
    }

    public function studentovservation($id)
    {
        return $this->dispatchLegacy('Observation', 'studentovservation', func_get_args());
    }

    public function add_observation_term_marks()
    {
        return $this->dispatchLegacy('Observation', 'add_observation_term_marks', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Observation', 'add', func_get_args());
    }

    public function get_editdetails()
    {
        return $this->dispatchLegacy('Observation', 'get_editdetails', func_get_args());
    }

    public function addobservationparam()
    {
        return $this->dispatchLegacy('Observation', 'addobservationparam', func_get_args());
    }

    public function observationform()
    {
        return $this->dispatchLegacy('Observation', 'observationform', func_get_args());
    }

    public function add_subparameter()
    {
        return $this->dispatchLegacy('Observation', 'add_subparameter', func_get_args());
    }

    public function observationtermform()
    {
        return $this->dispatchLegacy('Observation', 'observationtermform', func_get_args());
    }

    public function assign()
    {
        return $this->dispatchLegacy('Observation', 'assign', func_get_args());
    }

    public function termstudent()
    {
        return $this->dispatchLegacy('Observation', 'termstudent', func_get_args());
    }

    public function getlist()
    {
        return $this->dispatchLegacy('Observation', 'getlist', func_get_args());
    }

    public function get_assignclasssections()
    {
        return $this->dispatchLegacy('Observation', 'get_assignclasssections', func_get_args());
    }

    public function getClassByExam()
    {
        return $this->dispatchLegacy('Observation', 'getClassByExam', func_get_args());
    }

    public function getExamSectionByClass()
    {
        return $this->dispatchLegacy('Observation', 'getExamSectionByClass', func_get_args());
    }

    public function exam_observationstudent()
    {
        return $this->dispatchLegacy('Observation', 'exam_observationstudent', func_get_args());
    }

    public function assignObservationTerm()
    {
        return $this->dispatchLegacy('Observation', 'assignObservationTerm', func_get_args());
    }

    public function add_observation_marks()
    {
        return $this->dispatchLegacy('Observation', 'add_observation_marks', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Observation', 'delete', func_get_args());
    }

    public function deleteassignterm($id)
    {
        return $this->dispatchLegacy('Observation', 'deleteassignterm', func_get_args());
    }

    public function removeassignclass_sections()
    {
        return $this->dispatchLegacy('Observation', 'removeassignclass_sections', func_get_args());
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