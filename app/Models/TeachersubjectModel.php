<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * TeachersubjectModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Teachersubject_model.
 */
class TeachersubjectModel extends BaseModel
{
    protected $table = 'teachersubject';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Teachersubject_model', 'get', func_get_args());
    }

    public function teachersjubject($id = null)
    {
        return $this->callLegacyModel('Teachersubject_model', 'teachersjubject', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Teachersubject_model', 'remove', func_get_args());
    }

    public function deleteBatch($ids, $class_section_id)
    {
        return $this->callLegacyModel('Teachersubject_model', 'deleteBatch', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Teachersubject_model', 'add', func_get_args());
    }

    public function getDetailByclassAndSection($class_section_id)
    {
        return $this->callLegacyModel('Teachersubject_model', 'getDetailByclassAndSection', func_get_args());
    }

    public function getDetailbyClsandSection($class_id, $section_id, $exam_id)
    {
        return $this->callLegacyModel('Teachersubject_model', 'getDetailbyClsandSection', func_get_args());
    }

    public function getSubjectByClsandSection($class_id, $section_id, $classteacher = 'yes')
    {
        return $this->callLegacyModel('Teachersubject_model', 'getSubjectByClsandSection', func_get_args());
    }

    public function getTeacherClassSubjects($teacher_id)
    {
        return $this->callLegacyModel('Teachersubject_model', 'getTeacherClassSubjects', func_get_args());
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