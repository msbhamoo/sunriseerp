<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StudentEditFieldModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Student_edit_field_model.
 */
class StudentEditFieldModel extends BaseModel
{
    protected $table = 'student_edit_field';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($record)
    {
        return $this->callLegacyModel('Student_edit_field_model', 'add', func_get_args());
    }

    public function get()
    {
        return $this->callLegacyModel('Student_edit_field_model', 'get', func_get_args());
    }

    public function checkprofilesettingfieldexist($fieldname)
    {
        return $this->callLegacyModel('Student_edit_field_model', 'checkprofilesettingfieldexist', func_get_args());
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