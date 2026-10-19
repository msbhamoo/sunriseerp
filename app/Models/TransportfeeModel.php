<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * TransportfeeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Transportfee_model.
 */
class TransportfeeModel extends BaseModel
{
    protected $table = 'transportfee';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($insert_data, $update_data)
    {
        return $this->callLegacyModel('Transportfee_model', 'add', func_get_args());
    }

    public function getSessionFees($session_id)
    {
        return $this->callLegacyModel('Transportfee_model', 'getSessionFees', func_get_args());
    }

    public function transportfesstype($session_id,$month)
    {
        return $this->callLegacyModel('Transportfee_model', 'transportfesstype', func_get_args());
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