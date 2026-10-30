<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * CbseexamScheduleModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbseexam_schedule_model.
 */
class CbseexamScheduleModel extends BaseModel
{
    protected $table = 'cbseexam_schedule';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Cbseexam_schedule_model', 'add', func_get_args());
    }

    public function add_type($data)
    {
        return $this->callLegacyModel('Cbseexam_schedule_model', 'add_type', func_get_args());
    }

    public function getassessmentlist()
    {
        return $this->callLegacyModel('Cbseexam_schedule_model', 'getassessmentlist', func_get_args());
    }

    public function get()
    {
        return $this->callLegacyModel('Cbseexam_schedule_model', 'get', func_get_args());
    }

    public function get_editdetails($id)
    {
        return $this->callLegacyModel('Cbseexam_schedule_model', 'get_editdetails', func_get_args());
    }

    public function get_assessmentTypebyId($id)
    {
        return $this->callLegacyModel('Cbseexam_schedule_model', 'get_assessmentTypebyId', func_get_args());
    }

    public function remove_assessment_type($id)
    {
        return $this->callLegacyModel('Cbseexam_schedule_model', 'remove_assessment_type', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cbseexam_schedule_model', 'remove', func_get_args());
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