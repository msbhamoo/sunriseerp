<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ScholarregisterModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Scholarregister_model.
 */
class ScholarregisterModel extends BaseModel
{
    protected $table = 'scholarregister';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get_student_scholar_register($student_id)
    {
        return $this->callLegacyModel('Scholarregister_model', 'get_student_scholar_register', func_get_args());
    }

    public function get_student_scholar_register_history($student_session_id)
    {
        return $this->callLegacyModel('Scholarregister_model', 'get_student_scholar_register_history', func_get_args());
    }

    public function get_history_by_student($student_id)
    {
        return $this->callLegacyModel('Scholarregister_model', 'get_history_by_student', func_get_args());
    }

    public function add_or_update_scholar_register($data)
    {
        return $this->callLegacyModel('Scholarregister_model', 'add_or_update_scholar_register', func_get_args());
    }

    public function add_or_update_scholar_register_history($data)
    {
        return $this->callLegacyModel('Scholarregister_model', 'add_or_update_scholar_register_history', func_get_args());
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