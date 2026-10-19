<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * SidebarmenuModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Sidebarmenu_model.
 */
class SidebarmenuModel extends BaseModel
{
    protected $table = 'sidebarmenu';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Sidebarmenu_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Sidebarmenu_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Sidebarmenu_model', 'add', func_get_args());
    }

    public function addSubMenu($data)
    {
        return $this->callLegacyModel('Sidebarmenu_model', 'addSubMenu', func_get_args());
    }

    public function getMenuwithSubmenus($sidebar_display = -1)
    {
        return $this->callLegacyModel('Sidebarmenu_model', 'getMenuwithSubmenus', func_get_args());
    }

    public function getSubmenusByMenuId($menu_id,$sidebar_display= 0)
    {
        return $this->callLegacyModel('Sidebarmenu_model', 'getSubmenusByMenuId', func_get_args());
    }

    public function getSubmenuById($id)
    {
        return $this->callLegacyModel('Sidebarmenu_model', 'getSubmenuById', func_get_args());
    }

    public function update_menu_order($data, $id_not_to_be_reset)
    {
        return $this->callLegacyModel('Sidebarmenu_model', 'update_menu_order', func_get_args());
    }

    public function update_submenu_order($data)
    {
        return $this->callLegacyModel('Sidebarmenu_model', 'update_submenu_order', func_get_args());
    }

    public function update_submenu_by_key($data)
    {
        return $this->callLegacyModel('Sidebarmenu_model', 'update_submenu_by_key', func_get_args());
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