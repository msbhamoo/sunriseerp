<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * LanguageModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Language_model.
 */
class LanguageModel extends BaseModel
{
    protected $table = 'language';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getRows($params = array())
    {
        return $this->callLegacyModel('Language_model', 'getRows', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Language_model', 'get', func_get_args());
    }

    public function getEnable_languages()
    {
        return $this->callLegacyModel('Language_model', 'getEnable_languages', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Language_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Language_model', 'add', func_get_args());
    }

    public function set_userlang($id, $data)
    {
        return $this->callLegacyModel('Language_model', 'set_userlang', func_get_args());
    }

    public function set_studentlang($id, $data)
    {
        return $this->callLegacyModel('Language_model', 'set_studentlang', func_get_args());
    }

    public function set_parentlang($id, $data)
    {
        return $this->callLegacyModel('Language_model', 'set_parentlang', func_get_args());
    }

    public function valid_check_exists($str)
    {
        return $this->callLegacyModel('Language_model', 'valid_check_exists', func_get_args());
    }

    public function check_data_exists($name, $id)
    {
        return $this->callLegacyModel('Language_model', 'check_data_exists', func_get_args());
    }

    public function update_520()
    {
        return $this->callLegacyModel('Language_model', 'update_520', func_get_args());
    }

    public function get_active_language_count()
    {
        return $this->callLegacyModel('Language_model', 'get_active_language_count', func_get_args());
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