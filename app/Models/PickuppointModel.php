<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * PickuppointModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Pickuppoint_model.
 */
class PickuppointModel extends BaseModel
{
    protected $table = 'pickuppoint';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'remove', func_get_args());
    }

    public function remove_point($id)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'remove_point', func_get_args());
    }

    public function remove_pickupfromroute($id)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'remove_pickupfromroute', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'add', func_get_args());
    }

    public function listpickup_point()
    {
        return $this->callLegacyModel('Pickuppoint_model', 'listpickup_point', func_get_args());
    }

    public function dropdownpickup_point()
    {
        return $this->callLegacyModel('Pickuppoint_model', 'dropdownpickup_point', func_get_args());
    }

    public function getpickup_pointbyid($id)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'getpickup_pointbyid', func_get_args());
    }

    public function route_pickup_point()
    {
        return $this->callLegacyModel('Pickuppoint_model', 'route_pickup_point', func_get_args());
    }

    public function get_routelist()
    {
        return $this->callLegacyModel('Pickuppoint_model', 'get_routelist', func_get_args());
    }

    public function getPickupPointByRouteID($id)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'getPickupPointByRouteID', func_get_args());
    }

    public function add_pickup_point($data)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'add_pickup_point', func_get_args());
    }

    public function reorder($id)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'reorder', func_get_args());
    }

    public function getPickupPointsByvehrouteId($vehroute_id)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'getPickupPointsByvehrouteId', func_get_args());
    }

    public function reorder_pickup_point($route_id)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'reorder_pickup_point', func_get_args());
    }

    public function getRoutePickupsByPickupId($pickup_point_id)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'getRoutePickupsByPickupId', func_get_args());
    }

    public function is_duplicate($route_id,$pickup_point_id)
    {
        return $this->callLegacyModel('Pickuppoint_model', 'is_duplicate', func_get_args());
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