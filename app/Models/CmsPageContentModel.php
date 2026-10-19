<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * CmsPageContentModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cms_page_content_model.
 */
class CmsPageContentModel extends BaseModel
{
    protected $table = 'cms_page_content';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Cms_page_content_model', 'get', func_get_args());
    }

    public function getContentByPage($page_id = null)
    {
        return $this->callLegacyModel('Cms_page_content_model', 'getContentByPage', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cms_page_content_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Cms_page_content_model', 'add', func_get_args());
    }

    public function batch_insert($data)
    {
        return $this->callLegacyModel('Cms_page_content_model', 'batch_insert', func_get_args());
    }

    public function insertOrUpdate($data)
    {
        return $this->callLegacyModel('Cms_page_content_model', 'insertOrUpdate', func_get_args());
    }

    public function deleteByPageID($page_id)
    {
        return $this->callLegacyModel('Cms_page_content_model', 'deleteByPageID', func_get_args());
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