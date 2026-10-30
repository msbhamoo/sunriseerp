<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StudentAttendaceSettingModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy StudentAttendaceSetting_model.
 */
class StudentAttendaceSettingModel extends BaseModel
{
    protected $table = 'studentattendacesetting';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getClassWiseAttendanceSetting($class_id=null)
    {
        return $this->callLegacyModel('StudentAttendaceSetting_model', 'getClassWiseAttendanceSetting', func_get_args());
    }

    public function add($insert_array = [], $class_section_array = [])
    {
        return $this->callLegacyModel('StudentAttendaceSetting_model', 'add', func_get_args());
    }

    public function getClassWiseAttendanceSettingByClassAndSection($class_id, $section_id)
    {
        return $this->callLegacyModel('StudentAttendaceSetting_model', 'getClassWiseAttendanceSettingByClassAndSection', func_get_args());
    }

    public function getAttendanceTypeByClassAndSectionTime($class_section_id, $time)
    {
        return $this->callLegacyModel('StudentAttendaceSetting_model', 'getAttendanceTypeByClassAndSectionTime', func_get_args());
    }

    public function getAttendanceTypeByClassAndSectionTime2($class_section_id, $time)
    {
        return $this->callLegacyModel('StudentAttendaceSetting_model', 'getAttendanceTypeByClassAndSectionTime2', func_get_args());
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