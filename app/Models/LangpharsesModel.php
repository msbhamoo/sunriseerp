<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * LangpharsesModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Langpharses_model.
 */
class LangpharsesModel extends BaseModel
{
    protected $table = 'langpharses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id)
    {
        return $this->callLegacyModel('Langpharses_model', 'get', func_get_args());
    }

    public function getByLangAfter($id, $after)
    {
        return $this->callLegacyModel('Langpharses_model', 'getByLangAfter', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Langpharses_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Langpharses_model', 'add', func_get_args());
    }

    public function deletepharses($lang_id)
    {
        return $this->callLegacyModel('Langpharses_model', 'deletepharses', func_get_args());
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