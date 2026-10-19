<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * DutypassModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Dutypass_model.
 */
class DutypassModel extends BaseModel
{
    protected $table = 'dutypass';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data, $staff_ids = array())
    {
        return $this->callLegacyModel('Dutypass_model', 'add', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Dutypass_model', 'get', func_get_args());
    }

    public function getDutyPassMembers($duty_pass_id)
    {
        return $this->callLegacyModel('Dutypass_model', 'getDutyPassMembers', func_get_args());
    }

    public function getByStaff($staff_id)
    {
        return $this->callLegacyModel('Dutypass_model', 'getByStaff', func_get_args());
    }

    public function getActiveDutyPassesByDate($date)
    {
        return $this->callLegacyModel('Dutypass_model', 'getActiveDutyPassesByDate', func_get_args());
    }

    public function getStaffActiveDutyPass($staff_id, $date)
    {
        return $this->callLegacyModel('Dutypass_model', 'getStaffActiveDutyPass', func_get_args());
    }

    public function generate_duty_pass_no()
    {
        return $this->callLegacyModel('Dutypass_model', 'generate_duty_pass_no', func_get_args());
    }

    public function autoMarkAttendanceForDutyPass($duty_pass_id)
    {
        return $this->callLegacyModel('Dutypass_model', 'autoMarkAttendanceForDutyPass', func_get_args());
    }

    public function delete($id)
    {
        return $this->callLegacyModel('Dutypass_model', 'delete', func_get_args());
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