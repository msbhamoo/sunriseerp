<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ContentModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Content_model.
 */
class ContentModel extends BaseModel
{
    protected $table = 'content';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Content_model', 'get', func_get_args());
    }

    public function getContentByRole($id = null, $role = null)
    {
        return $this->callLegacyModel('Content_model', 'getContentByRole', func_get_args());
    }

    public function getListByCategory($category)
    {
        return $this->callLegacyModel('Content_model', 'getListByCategory', func_get_args());
    }

    public function getListByCategoryforUser($class_id, $section_id, $category = '')
    {
        return $this->callLegacyModel('Content_model', 'getListByCategoryforUser', func_get_args());
    }

    public function getListByforUser($class_id, $section_id)
    {
        return $this->callLegacyModel('Content_model', 'getListByforUser', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Content_model', 'remove', func_get_args());
    }

    public function search_by_content_type($text)
    {
        return $this->callLegacyModel('Content_model', 'search_by_content_type', func_get_args());
    }

    public function add($data, $content_role = array())
    {
        return $this->callLegacyModel('Content_model', 'add', func_get_args());
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