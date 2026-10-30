<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * AccpurchaseModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Accpurchase_model.
 */
class AccpurchaseModel extends BaseModel
{
    protected $table = 'accpurchase';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function addPurchase($data, $items)
    {
        return $this->callLegacyModel('Accpurchase_model', 'addPurchase', func_get_args());
    }

    public function getPurchase($id)
    {
        return $this->callLegacyModel('Accpurchase_model', 'getPurchase', func_get_args());
    }

    public function deletePurchase($id)
    {
        return $this->callLegacyModel('Accpurchase_model', 'deletePurchase', func_get_args());
    }

    public function getDatatablePurchases()
    {
        return $this->callLegacyModel('Accpurchase_model', 'getDatatablePurchases', func_get_args());
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