<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * CmsProgramModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cms_program_model.
 */
class CmsProgramModel extends BaseModel
{
    protected $table = 'cms_program';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Cms_program_model', 'get', func_get_args());
    }

    public function getByCategory($category = null, $params = array(), $is_front = 0)
    {
        return $this->callLegacyModel('Cms_program_model', 'getByCategory', func_get_args());
    }

    public function updateFeaturedImage($id, $record_id)
    {
        return $this->callLegacyModel('Cms_program_model', 'updateFeaturedImage', func_get_args());
    }

    public function getBySlug($slug = null)
    {
        return $this->callLegacyModel('Cms_program_model', 'getBySlug', func_get_args());
    }

    public function front_cms_program_photos($program_id)
    {
        return $this->callLegacyModel('Cms_program_model', 'front_cms_program_photos', func_get_args());
    }

    public function remove($slug)
    {
        return $this->callLegacyModel('Cms_program_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Cms_program_model', 'add', func_get_args());
    }

    public function inst_batch($data, $contents)
    {
        return $this->callLegacyModel('Cms_program_model', 'inst_batch', func_get_args());
    }

    public function update_batch($data, $contents, $remove_content)
    {
        return $this->callLegacyModel('Cms_program_model', 'update_batch', func_get_args());
    }

    public function addImage($data)
    {
        return $this->callLegacyModel('Cms_program_model', 'addImage', func_get_args());
    }

    public function removeImage($id)
    {
        return $this->callLegacyModel('Cms_program_model', 'removeImage', func_get_args());
    }

    public function removeBySlug($slug, $type)
    {
        return $this->callLegacyModel('Cms_program_model', 'removeBySlug', func_get_args());
    }

    public function banner($banner_content, $data)
    {
        return $this->callLegacyModel('Cms_program_model', 'banner', func_get_args());
    }

    public function bannerDelete($banner_content, $media_gallery_id)
    {
        return $this->callLegacyModel('Cms_program_model', 'bannerDelete', func_get_args());
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