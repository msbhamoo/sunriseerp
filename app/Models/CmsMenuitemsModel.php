<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * CmsMenuitemsModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cms_menuitems_model.
 */
class CmsMenuitemsModel extends BaseModel
{
    protected $table = 'cms_menuitems';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Cms_menuitems_model', 'get', func_get_args());
    }

    public function getBySlug($slug = null)
    {
        return $this->callLegacyModel('Cms_menuitems_model', 'getBySlug', func_get_args());
    }

    public function removeBySlug($slug)
    {
        return $this->callLegacyModel('Cms_menuitems_model', 'removeBySlug', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cms_menuitems_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Cms_menuitems_model', 'add', func_get_args());
    }

    public function getMenus($menu_id, $parent = 0, $spacing = '', $user_tree_array = '')
    {
        return $this->callLegacyModel('Cms_menuitems_model', 'getMenus', func_get_args());
    }

    public function dyn_menu($parent_array, $sub_array, $qs_val = 'menu', $main_id = 'nav', $sub_id = 'subnav', $extra_style = 'foldout')
    {
        return $this->callLegacyModel('Cms_menuitems_model', 'dyn_menu', func_get_args());
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