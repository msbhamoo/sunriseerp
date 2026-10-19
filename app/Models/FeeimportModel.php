<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * FeeimportModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Feeimport_model.
 */
class FeeimportModel extends BaseModel
{
    protected $table = 'feeimport';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function createBatch($data)
    {
        return $this->callLegacyModel('Feeimport_model', 'createBatch', func_get_args());
    }

    public function deleteBatch($batch_id)
    {
        return $this->callLegacyModel('Feeimport_model', 'deleteBatch', func_get_args());
    }

    public function getBatchHistory()
    {
        return $this->callLegacyModel('Feeimport_model', 'getBatchHistory', func_get_args());
    }

    public function getBatch($batch_id)
    {
        return $this->callLegacyModel('Feeimport_model', 'getBatch', func_get_args());
    }

    public function getBatchDetail($batch_id)
    {
        return $this->callLegacyModel('Feeimport_model', 'getBatchDetail', func_get_args());
    }

    public function parseAndValidateCSV($file_path, $batch_id, $fallback_route_id = null)
    {
        return $this->callLegacyModel('Feeimport_model', 'parseAndValidateCSV', func_get_args());
    }

    public function executeBatch($batch_id)
    {
        return $this->callLegacyModel('Feeimport_model', 'executeBatch', func_get_args());
    }

    public function revertBatch($batch_id)
    {
        return $this->callLegacyModel('Feeimport_model', 'revertBatch', func_get_args());
    }

    public function fixBatchDates($batch_id)
    {
        return $this->callLegacyModel('Feeimport_model', 'fixBatchDates', func_get_args());
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