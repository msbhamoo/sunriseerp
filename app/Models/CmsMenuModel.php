<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * CmsMenuModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cms_menu_model.
 */
class CmsMenuModel extends BaseModel
{
    protected $table = 'cms_menu';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Cms_menu_model', 'get', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Cms_menu_model', 'add', func_get_args());
    }

    public function getBySlug($slug = null)
    {
        return $this->callLegacyModel('Cms_menu_model', 'getBySlug', func_get_args());
    }

    public function findMenuExists($menu)
    {
        return $this->callLegacyModel('Cms_menu_model', 'findMenuExists', func_get_args());
    }

    public function valid_check_exists($str)
    {
        return $this->callLegacyModel('Cms_menu_model', 'valid_check_exists', func_get_args());
    }

    public function check_data_exists($menu, $id)
    {
        return $this->callLegacyModel('Cms_menu_model', 'check_data_exists', func_get_args());
    }

    public function removeBySlug($slug)
    {
        return $this->callLegacyModel('Cms_menu_model', 'removeBySlug', func_get_args());
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