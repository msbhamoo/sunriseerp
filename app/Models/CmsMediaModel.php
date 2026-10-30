<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * CmsMediaModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cms_media_model.
 */
class CmsMediaModel extends BaseModel
{
    protected $table = 'cms_media';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function bulk_add($data = null)
    {
        return $this->callLegacyModel('Cms_media_model', 'bulk_add', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Cms_media_model', 'add', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Cms_media_model', 'get', func_get_args());
    }

    public function getSlug($slug = null)
    {
        return $this->callLegacyModel('Cms_media_model', 'getSlug', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cms_media_model', 'remove', func_get_args());
    }

    public function count_all($st = null, $media_type = null)
    {
        return $this->callLegacyModel('Cms_media_model', 'count_all', func_get_args());
    }

    public function fetch_details($limit, $start, $st = 'img', $media_type = null)
    {
        return $this->callLegacyModel('Cms_media_model', 'fetch_details', func_get_args());
    }

    public function getlimitwithsearch($where_condition = array(),$limit = null, $start = null)
    {
        return $this->callLegacyModel('Cms_media_model', 'getlimitwithsearch', func_get_args());
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