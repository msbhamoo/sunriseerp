<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * AttendanceanalyticsModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Attendanceanalytics_model.
 */
class AttendanceanalyticsModel extends BaseModel
{
    protected $table = 'attendanceanalytics';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getTodayOverview($date)
    {
        return $this->callLegacyModel('Attendanceanalytics_model', 'getTodayOverview', func_get_args());
    }

    public function getClassWiseAbsenteeism($date, $session_id)
    {
        return $this->callLegacyModel('Attendanceanalytics_model', 'getClassWiseAbsenteeism', func_get_args());
    }

    public function getTrend($start_date, $end_date)
    {
        return $this->callLegacyModel('Attendanceanalytics_model', 'getTrend', func_get_args());
    }

    public function getAbsentStudentsWithFollowup($date, $session_id)
    {
        return $this->callLegacyModel('Attendanceanalytics_model', 'getAbsentStudentsWithFollowup', func_get_args());
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