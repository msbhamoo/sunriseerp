<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseexamGradeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbseexam_grade_model.
 */
class CbseexamGradeModel extends BaseModel
{
    protected $table = 'cbseexam_grade';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Cbseexam_grade_model', 'add', func_get_args());
    }

    public function add_graderange($insert_grade, $cbse_exam_grades_range, $cbse_exam_grades_range_update, $delete_grade_range)
    {
        return $this->callLegacyModel('Cbseexam_grade_model', 'add_graderange', func_get_args());
    }

    public function getgradelist()
    {
        return $this->callLegacyModel('Cbseexam_grade_model', 'getgradelist', func_get_args());
    }

    public function get()
    {
        return $this->callLegacyModel('Cbseexam_grade_model', 'get', func_get_args());
    }

    public function getWithRange($id)
    {
        return $this->callLegacyModel('Cbseexam_grade_model', 'getWithRange', func_get_args());
    }

    public function getGraderangebyGradeID($cbse_exam_grade_id)
    {
        return $this->callLegacyModel('Cbseexam_grade_model', 'getGraderangebyGradeID', func_get_args());
    }

    public function get_graderangebyId($id)
    {
        return $this->callLegacyModel('Cbseexam_grade_model', 'get_graderangebyId', func_get_args());
    }

    public function getExamGrades($cbse_exam_id)
    {
        return $this->callLegacyModel('Cbseexam_grade_model', 'getExamGrades', func_get_args());
    }

    public function remove_graderange($id)
    {
        return $this->callLegacyModel('Cbseexam_grade_model', 'remove_graderange', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cbseexam_grade_model', 'remove', func_get_args());
    }

    public function get_editdetails($id)
    {
        return $this->callLegacyModel('Cbseexam_grade_model', 'get_editdetails', func_get_args());
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