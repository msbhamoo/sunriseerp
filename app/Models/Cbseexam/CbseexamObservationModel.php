<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseexamObservationModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbseexam_observation_model.
 */
class CbseexamObservationModel extends BaseModel
{
    protected $table = 'cbseexam_observation';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function observation_exists($str)
    {
        return $this->callLegacyModel('Cbseexam_observation_model', 'observation_exists', func_get_args());
    }

    public function check_data_exists($data)
    {
        return $this->callLegacyModel('Cbseexam_observation_model', 'check_data_exists', func_get_args());
    }

    public function add($cbse_exam_observation, $cbse_observation_subparameter, $cbse_observation_subparameter_update, $delete_observation_subparameter)
    {
        return $this->callLegacyModel('Cbseexam_observation_model', 'add', func_get_args());
    }

    public function getobservationlist()
    {
        return $this->callLegacyModel('Cbseexam_observation_model', 'getobservationlist', func_get_args());
    }

    public function getObservationSubparameter($cbse_exam_observation_id)
    {
        return $this->callLegacyModel('Cbseexam_observation_model', 'getObservationSubparameter', func_get_args());
    }

    public function get()
    {
        return $this->callLegacyModel('Cbseexam_observation_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cbseexam_observation_model', 'remove', func_get_args());
    }


    public function get_editdetails($id)
    {
        return $this->callLegacyModel('Cbseexam_observation_model', 'get_editdetails', func_get_args());
    }


    public function get_observation_subparameterbyId($id)
    {
        return $this->callLegacyModel('Cbseexam_observation_model', 'get_observation_subparameterbyId', func_get_args());
    }


    public function get_observation_student($observation_id)
    {
        return $this->callLegacyModel('Cbseexam_observation_model', 'get_observation_student', func_get_args());
    }

    public function observationsubparameter($observation_id)
    {
        return $this->callLegacyModel('Cbseexam_observation_model', 'observationsubparameter', func_get_args());
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