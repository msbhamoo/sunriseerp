<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * AccledgerModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Accledger_model.
 */
class AccledgerModel extends BaseModel
{
    protected $table = 'accledger';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getLedgerGroups()
    {
        return $this->callLegacyModel('Accledger_model', 'getLedgerGroups', func_get_args());
    }

    public function getBanks()
    {
        return $this->callLegacyModel('Accledger_model', 'getBanks', func_get_args());
    }

    public function addLedger($data)
    {
        return $this->callLegacyModel('Accledger_model', 'addLedger', func_get_args());
    }

    public function getLedgers($id = null)
    {
        return $this->callLegacyModel('Accledger_model', 'getLedgers', func_get_args());
    }

    public function getLedgersBySystemGroup($system_name)
    {
        return $this->callLegacyModel('Accledger_model', 'getLedgersBySystemGroup', func_get_args());
    }

    public function getLedgersBySystemGroups($system_names)
    {
        return $this->callLegacyModel('Accledger_model', 'getLedgersBySystemGroups', func_get_args());
    }

    public function getLedgersByGroupIds($group_ids)
    {
        return $this->callLegacyModel('Accledger_model', 'getLedgersByGroupIds', func_get_args());
    }

    public function deleteLedger($id)
    {
        return $this->callLegacyModel('Accledger_model', 'deleteLedger', func_get_args());
    }

    public function getDatatableLedgers()
    {
        return $this->callLegacyModel('Accledger_model', 'getDatatableLedgers', func_get_args());
    }

    public function seedDefaultData()
    {
        return $this->callLegacyModel('Accledger_model', 'seedDefaultData', func_get_args());
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