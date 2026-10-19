<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseObservationTermStudentSubparameterModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbse_observation_term_student_subparameter_model.
 */
class CbseObservationTermStudentSubparameterModel extends BaseModel
{
    protected $table = 'cbse_observation_term_student_subparameter';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($insert_array, $update_array)
    {
        return $this->callLegacyModel('Cbse_observation_term_student_subparameter_model', 'add', func_get_args());
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
        return 'Cbseexam';
    }
}