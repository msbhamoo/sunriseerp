<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StaffidcardModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Staffidcard_model.
 */
class StaffidcardModel extends BaseModel
{
    protected $table = 'staffidcard';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function staffidcardlist()
    {
        return $this->callLegacyModel('Staffidcard_model', 'staffidcardlist', func_get_args());
    }

    public function addstaffidcard($data)
    {
        return $this->callLegacyModel('Staffidcard_model', 'addstaffidcard', func_get_args());
    }

    public function idcardbyid($id)
    {
        return $this->callLegacyModel('Staffidcard_model', 'idcardbyid', func_get_args());
    }

    public function get($id)
    {
        return $this->callLegacyModel('Staffidcard_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Staffidcard_model', 'remove', func_get_args());
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