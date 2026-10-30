<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * SeatingroomController
 *
 * Auto-generated CI4 controller migrating CI3 Seatingroom.
 */
class SeatingroomController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Seatingroom', 'index', func_get_args());
    }

    public function add_building()
    {
        return $this->dispatchLegacy('Seatingroom', 'add_building', func_get_args());
    }

    public function delete_building($id)
    {
        return $this->dispatchLegacy('Seatingroom', 'delete_building', func_get_args());
    }

    public function add_room()
    {
        return $this->dispatchLegacy('Seatingroom', 'add_room', func_get_args());
    }

    public function delete_room($id)
    {
        return $this->dispatchLegacy('Seatingroom', 'delete_room', func_get_args());
    }

    public function bulk_generate_rooms()
    {
        return $this->dispatchLegacy('Seatingroom', 'bulk_generate_rooms', func_get_args());
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