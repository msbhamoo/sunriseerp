<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * PickuppointController
 *
 * Auto-generated CI4 controller migrating CI3 Pickuppoint.
 */
class PickuppointController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Pickuppoint', 'index', func_get_args());
    }

    public function getpickpointlist()
    {
        return $this->dispatchLegacy('Pickuppoint', 'getpickpointlist', func_get_args());
    }

    public function add_point()
    {
        return $this->dispatchLegacy('Pickuppoint', 'add_point', func_get_args());
    }

    public function add_student_fees()
    {
        return $this->dispatchLegacy('Pickuppoint', 'add_student_fees', func_get_args());
    }

    public function student_fees()
    {
        return $this->dispatchLegacy('Pickuppoint', 'student_fees', func_get_args());
    }

    public function reorder()
    {
        return $this->dispatchLegacy('Pickuppoint', 'reorder', func_get_args());
    }

    public function reorder_pointid()
    {
        return $this->dispatchLegacy('Pickuppoint', 'reorder_pointid', func_get_args());
    }

    public function check_dublicate_pickuppoint($route_id,$pickup_point_id)
    {
        return $this->dispatchLegacy('Pickuppoint', 'check_dublicate_pickuppoint', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Pickuppoint', 'create', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Pickuppoint', 'delete', func_get_args());
    }

    public function delete_point($id)
    {
        return $this->dispatchLegacy('Pickuppoint', 'delete_point', func_get_args());
    }

    public function assign()
    {
        return $this->dispatchLegacy('Pickuppoint', 'assign', func_get_args());
    }

    public function pointmap()
    {
        return $this->dispatchLegacy('Pickuppoint', 'pointmap', func_get_args());
    }

    public function student_transport_months()
    {
        return $this->dispatchLegacy('Pickuppoint', 'student_transport_months', func_get_args());
    }

    public function addmore_point()
    {
        return $this->dispatchLegacy('Pickuppoint', 'addmore_point', func_get_args());
    }

    public function addmore_point_bulk()
    {
        return $this->dispatchLegacy('Pickuppoint', 'addmore_point_bulk', func_get_args());
    }

    public function get_pointdata()
    {
        return $this->dispatchLegacy('Pickuppoint', 'get_pointdata', func_get_args());
    }

    public function get_assigndetails()
    {
        return $this->dispatchLegacy('Pickuppoint', 'get_assigndetails', func_get_args());
    }

    public function get_pickupdropdownlist()
    {
        return $this->dispatchLegacy('Pickuppoint', 'get_pickupdropdownlist', func_get_args());
    }

    public function getpickuppointsbyroute()
    {
        return $this->dispatchLegacy('Pickuppoint', 'getpickuppointsbyroute', func_get_args());
    }

    public function get_pickuppointfees($id)
    {
        return $this->dispatchLegacy('Pickuppoint', 'get_pickuppointfees', func_get_args());
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