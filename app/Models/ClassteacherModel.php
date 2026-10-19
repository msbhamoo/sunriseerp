<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ClassteacherModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Classteacher_model.
 */
class ClassteacherModel extends BaseModel
{
    protected $table = 'classteacher';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getClassTeacher($id = null)
    {
        return $this->callLegacyModel('Classteacher_model', 'getClassTeacher', func_get_args());
    }

    public function addClassTeacher($data)
    {
        return $this->callLegacyModel('Classteacher_model', 'addClassTeacher', func_get_args());
    }

    public function teacherByClassSection($class_id, $section_id)
    {
        return $this->callLegacyModel('Classteacher_model', 'teacherByClassSection', func_get_args());
    }

    public function updateTeacher($previd, $class_id, $section_id)
    {
        return $this->callLegacyModel('Classteacher_model', 'updateTeacher', func_get_args());
    }

    public function getclassbyuser($id)
    {
        return $this->callLegacyModel('Classteacher_model', 'getclassbyuser', func_get_args());
    }

    public function classbysubjectteacher($id, $classes)
    {
        return $this->callLegacyModel('Classteacher_model', 'classbysubjectteacher', func_get_args());
    }

    public function delete($id = null, bool $purge = false)
    {
        return $this->callLegacyModel('Classteacher_model', 'delete', func_get_args());
    }

    public function getsubjectbyteacher($id)
    {
        return $this->callLegacyModel('Classteacher_model', 'getsubjectbyteacher', func_get_args());
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