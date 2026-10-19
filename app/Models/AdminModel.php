<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * AdminModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Admin_model.
 */
class AdminModel extends BaseModel
{
    protected $table = 'admin';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Admin_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Admin_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Admin_model', 'add', func_get_args());
    }

    public function checkLogin($data)
    {
        return $this->callLegacyModel('Admin_model', 'checkLogin', func_get_args());
    }

    public function read_user_information($email)
    {
        return $this->callLegacyModel('Admin_model', 'read_user_information', func_get_args());
    }

    public function readByEmail($email)
    {
        return $this->callLegacyModel('Admin_model', 'readByEmail', func_get_args());
    }

    public function updateVerCode($data)
    {
        return $this->callLegacyModel('Admin_model', 'updateVerCode', func_get_args());
    }

    public function getAdminByCode($ver_code)
    {
        return $this->callLegacyModel('Admin_model', 'getAdminByCode', func_get_args());
    }

    public function change_password($data)
    {
        return $this->callLegacyModel('Admin_model', 'change_password', func_get_args());
    }

    public function checkOldPass($data)
    {
        return $this->callLegacyModel('Admin_model', 'checkOldPass', func_get_args());
    }

    public function saveNewPass($data)
    {
        return $this->callLegacyModel('Admin_model', 'saveNewPass', func_get_args());
    }

    public function saveForgotPass($data)
    {
        return $this->callLegacyModel('Admin_model', 'saveForgotPass', func_get_args());
    }

    public function addReceipt($data)
    {
        return $this->callLegacyModel('Admin_model', 'addReceipt', func_get_args());
    }

    public function getMonthlyCollection()
    {
        return $this->callLegacyModel('Admin_model', 'getMonthlyCollection', func_get_args());
    }

    public function getMonthlyExpense()
    {
        return $this->callLegacyModel('Admin_model', 'getMonthlyExpense', func_get_args());
    }

    public function getCollectionbyDay($date)
    {
        return $this->callLegacyModel('Admin_model', 'getCollectionbyDay', func_get_args());
    }

    public function getExpensebyDay($date)
    {
        return $this->callLegacyModel('Admin_model', 'getExpensebyDay', func_get_args());
    }

    public function getAllEnquiryCount($start_date, $end_date)
    {
        return $this->callLegacyModel('Admin_model', 'getAllEnquiryCount', func_get_args());
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