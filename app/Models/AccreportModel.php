<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * AccreportModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Accreport_model.
 */
class AccreportModel extends BaseModel
{
    protected $table = 'accreport';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getDaybookData($from_date, $to_date)
    {
        return $this->callLegacyModel('Accreport_model', 'getDaybookData', func_get_args());
    }

    public function getCashbookData($from_date, $to_date)
    {
        return $this->callLegacyModel('Accreport_model', 'getCashbookData', func_get_args());
    }

    public function getBankbookData($from_date, $to_date, $ledger_id = null)
    {
        return $this->callLegacyModel('Accreport_model', 'getBankbookData', func_get_args());
    }

    public function getLedgerStatement($ledger_id, $from_date, $to_date)
    {
        return $this->callLegacyModel('Accreport_model', 'getLedgerStatement', func_get_args());
    }

    public function getOutstandingReport($type)
    {
        return $this->callLegacyModel('Accreport_model', 'getOutstandingReport', func_get_args());
    }

    public function getExpIncomeTypeReport($from_date, $to_date, $type)
    {
        return $this->callLegacyModel('Accreport_model', 'getExpIncomeTypeReport', func_get_args());
    }

    public function getTrialBalance($to_date = null)
    {
        return $this->callLegacyModel('Accreport_model', 'getTrialBalance', func_get_args());
    }

    public function getProfitLoss($from_date, $to_date)
    {
        return $this->callLegacyModel('Accreport_model', 'getProfitLoss', func_get_args());
    }

    public function getBalanceSheet($to_date)
    {
        return $this->callLegacyModel('Accreport_model', 'getBalanceSheet', func_get_args());
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