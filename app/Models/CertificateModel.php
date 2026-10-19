<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * CertificateModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Certificate_model.
 */
class CertificateModel extends BaseModel
{
    protected $table = 'certificate';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function addcertificate($data)
    {
        return $this->callLegacyModel('Certificate_model', 'addcertificate', func_get_args());
    }

    public function certificateList()
    {
        return $this->callLegacyModel('Certificate_model', 'certificateList', func_get_args());
    }

    public function get($id)
    {
        return $this->callLegacyModel('Certificate_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Certificate_model', 'remove', func_get_args());
    }

    public function getstudentcertificate()
    {
        return $this->callLegacyModel('Certificate_model', 'getstudentcertificate', func_get_args());
    }

    public function certifiatebyid($id)
    {
        return $this->callLegacyModel('Certificate_model', 'certifiatebyid', func_get_args());
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