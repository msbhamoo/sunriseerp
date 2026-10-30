<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseexamObservationParameterModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbseexam_observation_parameter_model.
 */
class CbseexamObservationParameterModel extends BaseModel
{
    protected $table = 'cbseexam_observation_parameter';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Cbseexam_observation_parameter_model', 'get', func_get_args());
    }

    public function getObservationParamsByObservationTerm($cbse_observation_term_id)
    {
        return $this->callLegacyModel('Cbseexam_observation_parameter_model', 'getObservationParamsByObservationTerm', func_get_args());
    }

    public function getWithParameters($id)
    {
        return $this->callLegacyModel('Cbseexam_observation_parameter_model', 'getWithParameters', func_get_args());
    }

    public function parameter_exists($str)
    {
        return $this->callLegacyModel('Cbseexam_observation_parameter_model', 'parameter_exists', func_get_args());
    }

    public function check_data_exists($data)
    {
        return $this->callLegacyModel('Cbseexam_observation_parameter_model', 'check_data_exists', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Cbseexam_observation_parameter_model', 'add', func_get_args());
    }

    public function getStudentTermObservation($cbse_template_id, $students = [])
    {
        return $this->callLegacyModel('Cbseexam_observation_parameter_model', 'getStudentTermObservation', func_get_args());
    }

    public function getTermObservationParams($cbse_template_id)
    {
        return $this->callLegacyModel('Cbseexam_observation_parameter_model', 'getTermObservationParams', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cbseexam_observation_parameter_model', 'remove', func_get_args());
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