<?php

namespace App\Models\Multibranch;

use App\Models\BaseModel;

/**
 * MultiStudentFeeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Multi_student_fee_model.
 */
class MultiStudentFeeModel extends BaseModel
{
    protected $table = 'multi_student_fee';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getexpenselist()
    {
        return $this->callLegacyModel('Multi_student_fee_model', 'getexpenselist', func_get_args());
    }

    public function getCurrentSessionStudentFees()
    {
        return $this->callLegacyModel('Multi_student_fee_model', 'getCurrentSessionStudentFees', func_get_args());
    }

    public function getFeesDepositeByIdArray($id_array = array())
    {
        return $this->callLegacyModel('Multi_student_fee_model', 'getFeesDepositeByIdArray', func_get_args());
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