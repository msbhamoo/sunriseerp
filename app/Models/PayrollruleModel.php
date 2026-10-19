<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * PayrollruleModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Payrollrule_model.
 */
class PayrollruleModel extends BaseModel
{
    protected $table = 'payrollrule';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getRuleGroups($active_only = false)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getRuleGroups', func_get_args());
    }

    public function saveRuleGroup($data)
    {
        return $this->callLegacyModel('Payrollrule_model', 'saveRuleGroup', func_get_args());
    }

    public function deleteRuleGroup($id)
    {
        return $this->callLegacyModel('Payrollrule_model', 'deleteRuleGroup', func_get_args());
    }

    public function getRules($group_id = null, $active_only = false)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getRules', func_get_args());
    }

    public function getRule($id)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getRule', func_get_args());
    }

    public function saveRule($data, $conditions = array(), $actions = array())
    {
        return $this->callLegacyModel('Payrollrule_model', 'saveRule', func_get_args());
    }

    public function toggleRule($id, $status)
    {
        return $this->callLegacyModel('Payrollrule_model', 'toggleRule', func_get_args());
    }

    public function deleteRule($id)
    {
        return $this->callLegacyModel('Payrollrule_model', 'deleteRule', func_get_args());
    }

    public function getConditions($rule_id)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getConditions', func_get_args());
    }

    public function getActions($rule_id)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getActions', func_get_args());
    }

    public function createVersion($rule_id, $reason)
    {
        return $this->callLegacyModel('Payrollrule_model', 'createVersion', func_get_args());
    }

    public function getVersionHistory($rule_id)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getVersionHistory', func_get_args());
    }

    public function createEngineRun($data)
    {
        return $this->callLegacyModel('Payrollrule_model', 'createEngineRun', func_get_args());
    }

    public function updateEngineRun($id, $data)
    {
        return $this->callLegacyModel('Payrollrule_model', 'updateEngineRun', func_get_args());
    }

    public function getEngineRuns()
    {
        return $this->callLegacyModel('Payrollrule_model', 'getEngineRuns', func_get_args());
    }

    public function getRunTraces($run_id)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getRunTraces', func_get_args());
    }

    public function getSimulationResults($run_id)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getSimulationResults', func_get_args());
    }

    public function clearSimulation($run_id)
    {
        return $this->callLegacyModel('Payrollrule_model', 'clearSimulation', func_get_args());
    }

    public function getStaffList($role_id = null)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getStaffList', func_get_args());
    }

    public function getStaffAttendanceSummary($month, $year)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getStaffAttendanceSummary', func_get_args());
    }

    public function getStaffLeaveSummary($month, $year)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getStaffLeaveSummary', func_get_args());
    }

    public function getHolidayCount($month, $year)
    {
        return $this->callLegacyModel('Payrollrule_model', 'getHolidayCount', func_get_args());
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