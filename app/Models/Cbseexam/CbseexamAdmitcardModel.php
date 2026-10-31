<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseexamAdmitcardModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbseexam_admitcard_model.
 */
class CbseexamAdmitcardModel extends BaseModel
{
    protected $table = 'cbseexam_admitcard';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'get', func_get_args());
    }

    public function getidcardbyid($idcard)
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'getidcardbyid', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'add', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'remove', func_get_args());
    }

    public function save_active_status($value)
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'save_active_status', func_get_args());
    }

    public function get_active_admitcard()
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'get_active_admitcard', func_get_args());
    }

    public function getexamlist($id=null)
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'getexamlist', func_get_args());
    }

    public function get_cbse_exam_list($class_id,$section_id)
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'get_cbse_exam_list', func_get_args());
    }

    public function get_cbse_exam_students_list($class_id,$section_id,$cbse_exam_id)
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'get_cbse_exam_students_list', func_get_args());
    }

    public function get_cbse_exam_students($students_array,$cbse_exam_id)
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'get_cbse_exam_students', func_get_args());
    }

    public function get_cbse_exam_timetable($cbse_exam_id, $class_id = null)
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'get_cbse_exam_timetable', func_get_args());
    }

    public function get_admitcard_generation_summary($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_admitcard_model', 'get_admitcard_generation_summary', func_get_args());
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