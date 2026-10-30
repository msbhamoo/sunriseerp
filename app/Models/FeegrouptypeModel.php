<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * FeegrouptypeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Feegrouptype_model.
 */
class FeegrouptypeModel extends BaseModel
{
    protected $table = 'feegrouptype';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Feegrouptype_model', 'get', func_get_args());
    }

    public function getAll()
    {
        return $this->callLegacyModel('Feegrouptype_model', 'getAll', func_get_args());
    }

    public function getSingleFeeGroup($id)
    {
        return $this->callLegacyModel('Feegrouptype_model', 'getSingleFeeGroup', func_get_args());
    }

    public function getFeeGroupByIDAndStudentSessionID($id,$student_session_id)
    {
        return $this->callLegacyModel('Feegrouptype_model', 'getFeeGroupByIDAndStudentSessionID', func_get_args());
    }

    public function getFeeGroupByID($id)
    {
        return $this->callLegacyModel('Feegrouptype_model', 'getFeeGroupByID', func_get_args());
    }

    public function getfeeTypeByGroup($id = null)
    {
        return $this->callLegacyModel('Feegrouptype_model', 'getfeeTypeByGroup', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Feegrouptype_model', 'remove', func_get_args());
    }

    public function add1($data)
    {
        return $this->callLegacyModel('Feegrouptype_model', 'add1', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Feegrouptype_model', 'add', func_get_args());
    }

    public function getFeeTypeDueDateReminder($date)
    {
        return $this->callLegacyModel('Feegrouptype_model', 'getFeeTypeDueDateReminder', func_get_args());
    }

    public function getFeeTypeStudents($fee_session_group_id, $fee_groups_feetype_id)
    {
        return $this->callLegacyModel('Feegrouptype_model', 'getFeeTypeStudents', func_get_args());
    }

    public function remove_comulative_by_fee_groups_feetype_id($id)
    {
        return $this->callLegacyModel('Feegrouptype_model', 'remove_comulative_by_fee_groups_feetype_id', func_get_args());
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