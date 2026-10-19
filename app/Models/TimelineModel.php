<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * TimelineModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Timeline_model.
 */
class TimelineModel extends BaseModel
{
    protected $table = 'timeline';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Timeline_model', 'add', func_get_args());
    }

    public function add_staff_timeline($data)
    {
        return $this->callLegacyModel('Timeline_model', 'add_staff_timeline', func_get_args());
    }

    public function getStudentTimeline($id, $status = '')
    {
        return $this->callLegacyModel('Timeline_model', 'getStudentTimeline', func_get_args());
    }

    public function getStaffTimeline($id, $status = '')
    {
        return $this->callLegacyModel('Timeline_model', 'getStaffTimeline', func_get_args());
    }

    public function delete_timeline($id)
    {
        return $this->callLegacyModel('Timeline_model', 'delete_timeline', func_get_args());
    }

    public function delete_staff_timeline($id)
    {
        return $this->callLegacyModel('Timeline_model', 'delete_staff_timeline', func_get_args());
    }

    public function getstudentsingletimeline($id)
    {
        return $this->callLegacyModel('Timeline_model', 'getstudentsingletimeline', func_get_args());
    }

    public function getstaffsingletimeline($id)
    {
        return $this->callLegacyModel('Timeline_model', 'getstaffsingletimeline', func_get_args());
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