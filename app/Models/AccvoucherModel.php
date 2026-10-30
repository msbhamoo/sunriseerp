<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * AccvoucherModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Accvoucher_model.
 */
class AccvoucherModel extends BaseModel
{
    protected $table = 'accvoucher';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function generateVoucherNo($type)
    {
        return $this->callLegacyModel('Accvoucher_model', 'generateVoucherNo', func_get_args());
    }

    public function peekVoucherNo($type)
    {
        return $this->callLegacyModel('Accvoucher_model', 'peekVoucherNo', func_get_args());
    }

    public function addVoucher($data, $items)
    {
        return $this->callLegacyModel('Accvoucher_model', 'addVoucher', func_get_args());
    }

    public function getVoucher($id)
    {
        return $this->callLegacyModel('Accvoucher_model', 'getVoucher', func_get_args());
    }

    public function deleteVoucher($id)
    {
        return $this->callLegacyModel('Accvoucher_model', 'deleteVoucher', func_get_args());
    }

    public function reverseAutoSyncVoucher($id)
    {
        return $this->callLegacyModel('Accvoucher_model', 'reverseAutoSyncVoucher', func_get_args());
    }

    public function getDatatableVouchers($type)
    {
        return $this->callLegacyModel('Accvoucher_model', 'getDatatableVouchers', func_get_args());
    }

    public function getVoucherAuditLog($voucher_id)
    {
        return $this->callLegacyModel('Accvoucher_model', 'getVoucherAuditLog', func_get_args());
    }

    public function getPendingSyncItems($limit = 50)
    {
        return $this->callLegacyModel('Accvoucher_model', 'getPendingSyncItems', func_get_args());
    }

    public function findFalsePositiveReversals()
    {
        return $this->callLegacyModel('Accvoucher_model', 'findFalsePositiveReversals', func_get_args());
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