<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * VehrouteModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Vehroute_model.
 */
class VehrouteModel extends BaseModel
{
    protected $table = 'vehroute';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Vehroute_model', 'get', func_get_args());
    }

    public function get_pickup_pointbyvehrouteid($reh_route_id)
    {
        return $this->callLegacyModel('Vehroute_model', 'get_pickup_pointbyvehrouteid', func_get_args());
    }

    public function getVechileByRoute($route_id)
    {
        return $this->callLegacyModel('Vehroute_model', 'getVechileByRoute', func_get_args());
    }

    public function getVechileDetailByVecRouteID($id)
    {
        return $this->callLegacyModel('Vehroute_model', 'getVechileDetailByVecRouteID', func_get_args());
    }

    public function getRouteVehiclesList($id = null)
    {
        return $this->callLegacyModel('Vehroute_model', 'getRouteVehiclesList', func_get_args());
    }

    public function listroute()
    {
        return $this->callLegacyModel('Vehroute_model', 'listroute', func_get_args());
    }

    public function remove($route_id, $array)
    {
        return $this->callLegacyModel('Vehroute_model', 'remove', func_get_args());
    }

    public function removeByroute($route_id)
    {
        return $this->callLegacyModel('Vehroute_model', 'removeByroute', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Vehroute_model', 'add', func_get_args());
    }

    public function route_exists($str)
    {
        return $this->callLegacyModel('Vehroute_model', 'route_exists', func_get_args());
    }

    public function check_data_exists($route_id)
    {
        return $this->callLegacyModel('Vehroute_model', 'check_data_exists', func_get_args());
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