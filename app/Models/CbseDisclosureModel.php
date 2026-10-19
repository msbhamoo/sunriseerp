<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * CbseDisclosureModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbse_disclosure_model.
 */
class CbseDisclosureModel extends BaseModel
{
    protected $table = 'cbse_disclosure';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function ensure_table_exists()
    {
        return $this->callLegacyModel('Cbse_disclosure_model', 'ensure_table_exists', func_get_args());
    }

    public function get_all_disclosures()
    {
        return $this->callLegacyModel('Cbse_disclosure_model', 'get_all_disclosures', func_get_args());
    }

    public function save_field($section, $field_key, $field_value, $file_path = null)
    {
        return $this->callLegacyModel('Cbse_disclosure_model', 'save_field', func_get_args());
    }

    public function add_custom_document($section, $title, $file_path)
    {
        return $this->callLegacyModel('Cbse_disclosure_model', 'add_custom_document', func_get_args());
    }

    public function get_custom_document($id)
    {
        return $this->callLegacyModel('Cbse_disclosure_model', 'get_custom_document', func_get_args());
    }

    public function delete_custom_document($id)
    {
        return $this->callLegacyModel('Cbse_disclosure_model', 'delete_custom_document', func_get_args());
    }

    public function get_section_fields($section)
    {
        return $this->callLegacyModel('Cbse_disclosure_model', 'get_section_fields', func_get_args());
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