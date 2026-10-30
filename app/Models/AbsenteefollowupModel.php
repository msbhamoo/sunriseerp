<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * AbsenteefollowupModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Absenteefollowup_model.
 */
class AbsenteefollowupModel extends BaseModel
{
    protected $table = 'absenteefollowup';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getAbsentLateStudents($date, $class_id = null, $section_id = null)
    {
        return $this->callLegacyModel('Absenteefollowup_model', 'getAbsentLateStudents', func_get_args());
    }

    public function getConsecutiveAbsenceCount($student_session_id, $date)
    {
        return $this->callLegacyModel('Absenteefollowup_model', 'getConsecutiveAbsenceCount', func_get_args());
    }

    public function saveLog($data)
    {
        return $this->callLegacyModel('Absenteefollowup_model', 'saveLog', func_get_args());
    }

    public function getLogs($student_session_id, $date)
    {
        return $this->callLegacyModel('Absenteefollowup_model', 'getLogs', func_get_args());
    }

    public function getAllLogsByStudent($student_session_id)
    {
        return $this->callLegacyModel('Absenteefollowup_model', 'getAllLogsByStudent', func_get_args());
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