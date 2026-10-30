<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * LibrarymemberModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Librarymember_model.
 */
class LibrarymemberModel extends BaseModel
{
    protected $table = 'librarymember';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get()
    {
        return $this->callLegacyModel('Librarymember_model', 'get', func_get_args());
    }

    public function checkIsMember($member_type, $id)
    {
        return $this->callLegacyModel('Librarymember_model', 'checkIsMember', func_get_args());
    }

    public function getByMemberID($id = null)
    {
        return $this->callLegacyModel('Librarymember_model', 'getByMemberID', func_get_args());
    }

    public function getTeacherData($id)
    {
        return $this->callLegacyModel('Librarymember_model', 'getTeacherData', func_get_args());
    }

    public function getStudentData($id)
    {
        return $this->callLegacyModel('Librarymember_model', 'getStudentData', func_get_args());
    }

    public function surrender($id)
    {
        return $this->callLegacyModel('Librarymember_model', 'surrender', func_get_args());
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