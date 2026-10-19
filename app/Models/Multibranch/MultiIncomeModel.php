<?php

namespace App\Models\Multibranch;

use App\Models\BaseModel;

/**
 * MultiIncomeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Multi_income_model.
 */
class MultiIncomeModel extends BaseModel
{
    protected $table = 'multi_income';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get()
    {
        return $this->callLegacyModel('Multi_income_model', 'get', func_get_args());
    }

    public function getincomelist()
    {
        return $this->callLegacyModel('Multi_income_model', 'getincomelist', func_get_args());
    }

    public function search($start_date = null, $end_date = null)
    {
        return $this->callLegacyModel('Multi_income_model', 'search', func_get_args());
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
        return 'Multibranch';
    }
}