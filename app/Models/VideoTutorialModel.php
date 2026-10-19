<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * VideoTutorialModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Video_tutorial_model.
 */
class VideoTutorialModel extends BaseModel
{
    protected $table = 'video_tutorial';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Video_tutorial_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Video_tutorial_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Video_tutorial_model', 'add', func_get_args());
    }

    public function count_all($keyword = null, $class_id = null, $class_section_id = null)
    {
        return $this->callLegacyModel('Video_tutorial_model', 'count_all', func_get_args());
    }

    public function fetch_details($limit, $start, $keyword = null, $class_id = null, $class_section_id = null)
    {
        return $this->callLegacyModel('Video_tutorial_model', 'fetch_details', func_get_args());
    }

    public function getvideotutorial($limit, $start, $class_id, $section_id)
    {
        return $this->callLegacyModel('Video_tutorial_model', 'getvideotutorial', func_get_args());
    }

    public function addsections($data)
    {
        return $this->callLegacyModel('Video_tutorial_model', 'addsections', func_get_args());
    }

    public function selectedsection($video_tutorial_id)
    {
        return $this->callLegacyModel('Video_tutorial_model', 'selectedsection', func_get_args());
    }

    public function getclassid($video_tutorial_id)
    {
        return $this->callLegacyModel('Video_tutorial_model', 'getclassid', func_get_args());
    }

    public function delete($id = null, bool $purge = false)
    {
        return $this->callLegacyModel('Video_tutorial_model', 'delete', func_get_args());
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