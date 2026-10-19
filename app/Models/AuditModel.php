<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * AuditModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Audit_model.
 */
class AuditModel extends BaseModel
{
    protected $table = 'audit';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($limit = null, $offset = null)
    {
        return $this->callLegacyModel('Audit_model', 'get', func_get_args());
    }

    public function getAllRecord()
    {
        return $this->callLegacyModel('Audit_model', 'getAllRecord', func_get_args());
    }

    public function count()
    {
        return $this->callLegacyModel('Audit_model', 'count', func_get_args());
    }

    public function audittrail_delete()
    {
        return $this->callLegacyModel('Audit_model', 'audittrail_delete', func_get_args());
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