<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * OnlineexamquestionModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Onlineexamquestion_model.
 */
class OnlineexamquestionModel extends BaseModel
{
    protected $table = 'onlineexamquestion';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getByExamID($exam_id, $limit, $start, $where_search)
    {
        return $this->callLegacyModel('Onlineexamquestion_model', 'getByExamID', func_get_args());
    }

    public function getExamQuestions($exam_id)
    {
        return $this->callLegacyModel('Onlineexamquestion_model', 'getExamQuestions', func_get_args());
    }

    public function getExamQuestionSubjects($exam_id)
    {
        return $this->callLegacyModel('Onlineexamquestion_model', 'getExamQuestionSubjects', func_get_args());
    }

    public function getCountByExamID($exam_id, $where_search)
    {
        return $this->callLegacyModel('Onlineexamquestion_model', 'getCountByExamID', func_get_args());
    }

    public function getByExamNoLimit($exam_id, $question_type)
    {
        return $this->callLegacyModel('Onlineexamquestion_model', 'getByExamNoLimit', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Onlineexamquestion_model', 'remove', func_get_args());
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