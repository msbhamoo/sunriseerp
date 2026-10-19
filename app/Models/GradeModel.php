<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * GradeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Grade_model.
 */
class GradeModel extends BaseModel
{
    protected $table = 'grade';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getGradeDetails()
    {
        return $this->callLegacyModel('Grade_model', 'getGradeDetails', func_get_args());
    }

    public function getfeeTypeByGroup($exm_type_key)
    {
        return $this->callLegacyModel('Grade_model', 'getfeeTypeByGroup', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Grade_model', 'get', func_get_args());
    }

    public function getByExamType($exam_type = null)
    {
        return $this->callLegacyModel('Grade_model', 'getByExamType', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Grade_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Grade_model', 'add', func_get_args());
    }

    public function check_Exits_group($data)
    {
        return $this->callLegacyModel('Grade_model', 'check_Exits_group', func_get_args());
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