<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * SubstitutionModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Substitution_model.
 */
class SubstitutionModel extends BaseModel
{
    protected $table = 'substitution';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get_approved_leave($staff_id, $date)
    {
        return $this->callLegacyModel('Substitution_model', 'get_approved_leave', func_get_args());
    }

    public function get_absent_staff_by_date($date)
    {
        return $this->callLegacyModel('Substitution_model', 'get_absent_staff_by_date', func_get_args());
    }

    public function insert_substitutions($data)
    {
        return $this->callLegacyModel('Substitution_model', 'insert_substitutions', func_get_args());
    }

    public function check_conflict($substitute_staff_id, $day, $time_from, $time_to, $date)
    {
        return $this->callLegacyModel('Substitution_model', 'check_conflict', func_get_args());
    }

    public function get_substitution_history($date = null, $staff_id = null)
    {
        return $this->callLegacyModel('Substitution_model', 'get_substitution_history', func_get_args());
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