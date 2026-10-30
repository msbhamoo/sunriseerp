<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * UploadcontentModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Uploadcontent_model.
 */
class UploadcontentModel extends BaseModel
{
    protected $table = 'uploadcontent';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Uploadcontent_model', 'get', func_get_args());
    }

    public function total_record($staff_id)
    {
        return $this->callLegacyModel('Uploadcontent_model', 'total_record', func_get_args());
    }

    public function getByIdArray($array)
    {
        return $this->callLegacyModel('Uploadcontent_model', 'getByIdArray', func_get_args());
    }

    public function remove_array($array)
    {
        return $this->callLegacyModel('Uploadcontent_model', 'remove_array', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Uploadcontent_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Uploadcontent_model', 'add', func_get_args());
    }

    public function getlimitwithsearch($staff_id, $limit = null, $start = null, $where_condition = array())
    {
        return $this->callLegacyModel('Uploadcontent_model', 'getlimitwithsearch', func_get_args());
    }

    public function countwithsearch($where_condition = array())
    {
        return $this->callLegacyModel('Uploadcontent_model', 'countwithsearch', func_get_args());
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