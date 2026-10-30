<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StaffattendanceauditModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Staffattendanceaudit_model.
 */
class StaffattendanceauditModel extends BaseModel
{
    protected $table = 'staffattendanceaudit';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function ensureSchema()
    {
        return $this->callLegacyModel('Staffattendanceaudit_model', 'ensureSchema', func_get_args());
    }

    public function installTriggers()
    {
        return $this->callLegacyModel('Staffattendanceaudit_model', 'installTriggers', func_get_args());
    }

    public function triggersInstalled()
    {
        return $this->callLegacyModel('Staffattendanceaudit_model', 'triggersInstalled', func_get_args());
    }

    public function setContext()
    {
        return $this->callLegacyModel('Staffattendanceaudit_model', 'setContext', func_get_args());
    }

    public function logShift($action, $shift_id, $old = null, $new = null)
    {
        return $this->callLegacyModel('Staffattendanceaudit_model', 'logShift', func_get_args());
    }

    public function logEvent($action, $label, $old = null, $new = null)
    {
        return $this->callLegacyModel('Staffattendanceaudit_model', 'logEvent', func_get_args());
    }

    public function search($f, $limit = 100, $offset = 0)
    {
        return $this->callLegacyModel('Staffattendanceaudit_model', 'search', func_get_args());
    }

    public function getSources()
    {
        return $this->callLegacyModel('Staffattendanceaudit_model', 'getSources', func_get_args());
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