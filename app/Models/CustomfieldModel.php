<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * CustomfieldModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Customfield_model.
 */
class CustomfieldModel extends BaseModel
{
    protected $table = 'customfield';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Customfield_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Customfield_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Customfield_model', 'add', func_get_args());
    }

    public function updateorder($data)
    {
        return $this->callLegacyModel('Customfield_model', 'updateorder', func_get_args());
    }

    public function getByBelong($belong_to)
    {
        return $this->callLegacyModel('Customfield_model', 'getByBelong', func_get_args());
    }

    public function insertRecord($custom_value_array, $insert_id)
    {
        return $this->callLegacyModel('Customfield_model', 'insertRecord', func_get_args());
    }

    public function updateRecord($custom_value_array, $id, $belong_to)
    {
        return $this->callLegacyModel('Customfield_model', 'updateRecord', func_get_args());
    }

    public function get_custom_fields($belongs_to, $display_table = null)
    {
        return $this->callLegacyModel('Customfield_model', 'get_custom_fields', func_get_args());
    }

    public function onlineadmissioninsertRecord($custom_value_array, $insert_id)
    {
        return $this->callLegacyModel('Customfield_model', 'onlineadmissioninsertRecord', func_get_args());
    }

    public function onlineadmissionupdateRecord($custom_value_array, $id, $belong_to)
    {
        return $this->callLegacyModel('Customfield_model', 'onlineadmissionupdateRecord', func_get_args());
    }

    public function updateRecordTransferCertificate($custom_value_array, $id, $belong_to)
    {
        return $this->callLegacyModel('Customfield_model', 'updateRecordTransferCertificate', func_get_args());
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