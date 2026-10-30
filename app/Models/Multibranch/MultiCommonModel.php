<?php

namespace App\Models\Multibranch;

use App\Models\BaseModel;

/**
 * MultiCommonModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Multi_common_model.
 */
class MultiCommonModel extends BaseModel
{
    protected $table = 'multi_common';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getStudentCount($school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getStudentCount', func_get_args());
    }

    public function getCurrentSessionStudentFees($school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getCurrentSessionStudentFees', func_get_args());
    }

    public function getStaff($school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getStaff', func_get_args());
    }

    public function getStaffAttendance($date, $school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getStaffAttendance', func_get_args());
    }

    public function getOfflineStudentAdmissions($school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getOfflineStudentAdmissions', func_get_args());
    }

    public function getOnlineStudentAdmissions($school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getOnlineStudentAdmissions', func_get_args());
    }

    public function getBooks($school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getBooks', func_get_args());
    }

    public function getLibararyMembers($school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getLibararyMembers', func_get_args());
    }

    public function getLibararyBookIssued($school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getLibararyBookIssued', func_get_args());
    }

    public function getAlumniStudents($school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getAlumniStudents', func_get_args());
    }

    public function getUserLog($school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getUserLog', func_get_args());
    }

    public function getStudentTransportFees($school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getStudentTransportFees', func_get_args());
    }

    public function getStaffPayslipCount($month, $year, $school_array = [])
    {
        return $this->callLegacyModel('Multi_common_model', 'getStaffPayslipCount', func_get_args());
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
        return 'Multibranch';
    }
}