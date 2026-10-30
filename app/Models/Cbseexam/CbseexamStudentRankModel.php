<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseexamStudentRankModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbseexam_student_rank_model.
 */
class CbseexamStudentRankModel extends BaseModel
{
    protected $table = 'cbseexam_student_rank';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add_rank($student_details, $template_id,$subject_rank)
    {
        return $this->callLegacyModel('Cbseexam_student_rank_model', 'add_rank', func_get_args());
    }

    public function add_exam_rank($student_details,$exam_id)
    {
        return $this->callLegacyModel('Cbseexam_student_rank_model', 'add_exam_rank', func_get_args());
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