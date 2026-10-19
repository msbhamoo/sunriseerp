<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ClassSectionTimeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Class_section_time_model.
 */
class ClassSectionTimeModel extends BaseModel
{
    protected $table = 'class_section_time';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($insert_data, $update_data)
    {
        return $this->callLegacyModel('Class_section_time_model', 'add', func_get_args());
    }

    public function getAttendanceNotSubmittedByTime($date, $time)
    {
        return $this->callLegacyModel('Class_section_time_model', 'getAttendanceNotSubmittedByTime', func_get_args());
    }

    public function allClassSections()
    {
        return $this->callLegacyModel('Class_section_time_model', 'allClassSections', func_get_args());
    }

    public function getTime($class_id)
    {
        return $this->callLegacyModel('Class_section_time_model', 'getTime', func_get_args());
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