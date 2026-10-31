<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseObservationTermModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbse_observation_term_model.
 */
class CbseObservationTermModel extends BaseModel
{
    protected $table = 'cbse_observation_term';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Cbse_observation_term_model', 'add', func_get_args());
    }

    public function getlist()
    {
        return $this->callLegacyModel('Cbse_observation_term_model', 'getlist', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Cbse_observation_term_model', 'get', func_get_args());
    }

    public function observation_term_exists($str)
    {
        return $this->callLegacyModel('Cbse_observation_term_model', 'observation_term_exists', func_get_args());
    }

    public function check_data_exists($cbse_exam_observation_id,$cbse_term_id)
    {
        return $this->callLegacyModel('Cbse_observation_term_model', 'check_data_exists', func_get_args());
    }

    public function getStudentSubparameter($exam_id,$cbse_observation_term_id,$class_id,$section_id)
    {
        return $this->callLegacyModel('Cbse_observation_term_model', 'getStudentSubparameter', func_get_args());
    }

    public function getObservationByTemplate($cbse_template_id)
    {
        return $this->callLegacyModel('Cbse_observation_term_model', 'getObservationByTemplate', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cbse_observation_term_model', 'remove', func_get_args());
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
        return 'cbseexam';
    }
}