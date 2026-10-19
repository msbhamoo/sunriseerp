<?php

namespace App\Models\Multibranch;

use App\Models\BaseModel;

/**
 * MultibranchModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Multibranch_model.
 */
class MultibranchModel extends BaseModel
{
    protected $table = 'multibranch';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getSchoolCurrentSessions()
    {
        return $this->callLegacyModel('Multibranch_model', 'getSchoolCurrentSessions', func_get_args());
    }

    public function get($id = null, $verified = false)
    {
        return $this->callLegacyModel('Multibranch_model', 'get', func_get_args());
    }

    public function getlist()
    {
        return $this->callLegacyModel('Multibranch_model', 'getlist', func_get_args());
    }

    public function verify_branch($database)
    {
        return $this->callLegacyModel('Multibranch_model', 'verify_branch', func_get_args());
    }

    public function getDisprove()
    {
        return $this->callLegacyModel('Multibranch_model', 'getDisprove', func_get_args());
    }

    public function getName($database)
    {
        return $this->callLegacyModel('Multibranch_model', 'getName', func_get_args());
    }

    public function add($data, $setting, $purchase_code, $update_data = false)
    {
        return $this->callLegacyModel('Multibranch_model', 'add', func_get_args());
    }

    public function updateSchoolBranch($update_array)
    {
        return $this->callLegacyModel('Multibranch_model', 'updateSchoolBranch', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Multibranch_model', 'remove', func_get_args());
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