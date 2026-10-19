<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * AddonsModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Addons_model.
 */
class AddonsModel extends BaseModel
{
    protected $table = 'addons';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getByProductID($product_id)
    {
        return $this->callLegacyModel('Addons_model', 'getByProductID', func_get_args());
    }

    public function getAddonWithVersion($product_id)
    {
        return $this->callLegacyModel('Addons_model', 'getAddonWithVersion', func_get_args());
    }

    public function getAddonVersions($addon_id, $current_version)
    {
        return $this->callLegacyModel('Addons_model', 'getAddonVersions', func_get_args());
    }

    public function getAddonByFileName($filename)
    {
        return $this->callLegacyModel('Addons_model', 'getAddonByFileName', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Addons_model', 'get', func_get_args());
    }

    public function getlimitwithsearch($limit = null, $start = null, $where_condition = array())
    {
        return $this->callLegacyModel('Addons_model', 'getlimitwithsearch', func_get_args());
    }

    public function countwithsearch($where_condition = array())
    {
        return $this->callLegacyModel('Addons_model', 'countwithsearch', func_get_args());
    }

    public function addAddonSetupDetails($addon_Array = [])
    {
        return $this->callLegacyModel('Addons_model', 'addAddonSetupDetails', func_get_args());
    }

    public function registerCustomAddon($data)
    {
        return $this->callLegacyModel('Addons_model', 'registerCustomAddon', func_get_args());
    }

    public function deleteCustomAddon($product_id)
    {
        return $this->callLegacyModel('Addons_model', 'deleteCustomAddon', func_get_args());
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