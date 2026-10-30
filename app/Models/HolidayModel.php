<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * HolidayModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Holiday_model.
 */
class HolidayModel extends BaseModel
{
    protected $table = 'holiday';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Holiday_model', 'add', func_get_args());
    }

    public function get($id = null,$holidaylist = null,$front_site = null)
    {
        return $this->callLegacyModel('Holiday_model', 'get', func_get_args());
    }

    public function delete_holiday($id)
    {
        return $this->callLegacyModel('Holiday_model', 'delete_holiday', func_get_args());
    }

    public function add_holiday_type($data)
    {
        return $this->callLegacyModel('Holiday_model', 'add_holiday_type', func_get_args());
    }

    public function get_holiday_type($id=null)
    {
        return $this->callLegacyModel('Holiday_model', 'get_holiday_type', func_get_args());
    }

    public function delete_holiday_type($id)
    {
        return $this->callLegacyModel('Holiday_model', 'delete_holiday_type', func_get_args());
    }

    public function valid_holiday_type($str)
    {
        return $this->callLegacyModel('Holiday_model', 'valid_holiday_type', func_get_args());
    }

    public function check_data_exists($name, $id)
    {
        return $this->callLegacyModel('Holiday_model', 'check_data_exists', func_get_args());
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