<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * GeneratecertificateModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Generatecertificate_model.
 */
class GeneratecertificateModel extends BaseModel
{
    protected $table = 'generatecertificate';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getcertificatebyid($certificate)
    {
        return $this->callLegacyModel('Generatecertificate_model', 'getcertificatebyid', func_get_args());
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