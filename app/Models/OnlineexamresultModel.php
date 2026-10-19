<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * OnlineexamresultModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Onlineexamresult_model.
 */
class OnlineexamresultModel extends BaseModel
{
    protected $table = 'onlineexamresult';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getDescriptionRecord($per_page, $start, $where,$exam_id)
    {
        return $this->callLegacyModel('Onlineexamresult_model', 'getDescriptionRecord', func_get_args());
    }

    public function add($data_insert)
    {
        return $this->callLegacyModel('Onlineexamresult_model', 'add', func_get_args());
    }

    public function update($id = null, $row = null): bool
    {
        return $this->callLegacyModel('Onlineexamresult_model', 'update', func_get_args());
    }

    public function getResultByStudent($onlineexam_student_id, $exam_id)
    {
        return $this->callLegacyModel('Onlineexamresult_model', 'getResultByStudent', func_get_args());
    }

    public function getStudentByExam($exam_id, $class_id, $section_id)
    {
        return $this->callLegacyModel('Onlineexamresult_model', 'getStudentByExam', func_get_args());
    }

    public function checkResultPrepare($onlineexam_student_id)
    {
        return $this->callLegacyModel('Onlineexamresult_model', 'checkResultPrepare', func_get_args());
    }

    public function onlineexamrank($onlineexam_student_id, $exam_id)
    {
        return $this->callLegacyModel('Onlineexamresult_model', 'onlineexamrank', func_get_args());
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