<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * RouteModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Route_model.
 */
class RouteModel extends BaseModel
{
    protected $table = 'route';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Route_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Route_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Route_model', 'add', func_get_args());
    }

    public function listroute($id = null)
    {
        return $this->callLegacyModel('Route_model', 'listroute', func_get_args());
    }

    public function listpickup_point()
    {
        return $this->callLegacyModel('Route_model', 'listpickup_point', func_get_args());
    }

    public function listvehicles()
    {
        return $this->callLegacyModel('Route_model', 'listvehicles', func_get_args());
    }

    public function searchTransportDetails($section_id, $class_id, $transport_route_id, $pickup_point_id, $vehicle_id)
    {
        return $this->callLegacyModel('Route_model', 'searchTransportDetails', func_get_args());
    }

    public function getClass($student_id)
    {
        return $this->callLegacyModel('Route_model', 'getClass', func_get_args());
    }

    public function getSection($student_id, $class_id)
    {
        return $this->callLegacyModel('Route_model', 'getSection', func_get_args());
    }

    /**
     * Compatibility Bridge: Delegates execution to the legacy model instance
     * maintaining full compatibility with query builder and CI3 environment.
     */
    protected function callLegacyModel(string $modelClass, string $method, array $args)
    {
        static $instances = [];

        if (!isset($instances[$modelClass])) {
            require_once APPPATH . '../application/core/MY_Model.php';
            $file = APPPATH . '../application/models/' . ($this->subPath() ? $this->subPath() . '/' : '') . $modelClass . '.php';
            if (file_exists($file)) {
                require_once $file;
                $instances[$modelClass] = new $modelClass();
            } else {
                return null;
            }
        }

        return call_user_func_array([$instances[$modelClass], $method], $args);
    }

    protected function subPath(): string
    {
        return '';
    }
}