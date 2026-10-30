<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * TransfercertificateModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Transfercertificate_model.
 */
class TransfercertificateModel extends BaseModel
{
    protected $table = 'transfercertificate';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function addformfields($record,$iscustomfield)
    {
        return $this->callLegacyModel('Transfercertificate_model', 'addformfields', func_get_args());
    }

    public function editguardianfield($status)
    {
        return $this->callLegacyModel('Transfercertificate_model', 'editguardianfield', func_get_args());
    }

    public function getcustomfields()
    {
        return $this->callLegacyModel('Transfercertificate_model', 'getcustomfields', func_get_args());
    }

    public function getdefaultfields()
    {
        return $this->callLegacyModel('Transfercertificate_model', 'getdefaultfields', func_get_args());
    }

    public function getallfields()
    {
        return $this->callLegacyModel('Transfercertificate_model', 'getallfields', func_get_args());
    }

    public function getfieldstatus($fieldname)
    {
        return $this->callLegacyModel('Transfercertificate_model', 'getfieldstatus', func_get_args());
    }

    public function updateQueue($queueData)
    {
        return $this->callLegacyModel('Transfercertificate_model', 'updateQueue', func_get_args());
    }

    public function getfieldsposition($fieldname)
    {
        return $this->callLegacyModel('Transfercertificate_model', 'getfieldsposition', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Transfercertificate_model', 'get', func_get_args());
    }

    public function get_settings()
    {
        return $this->callLegacyModel('Transfercertificate_model', 'get_settings', func_get_args());
    }

    public function get_transfer_certificate_no()
    {
        return $this->callLegacyModel('Transfercertificate_model', 'get_transfer_certificate_no', func_get_args());
    }

    public function check_is_tc_exist($tc_no_start)
    {
        return $this->callLegacyModel('Transfercertificate_model', 'check_is_tc_exist', func_get_args());
    }

    public function update_setting($data)
    {
        return $this->callLegacyModel('Transfercertificate_model', 'update_setting', func_get_args());
    }

    public function save_tc_details($data)
    {
        return $this->callLegacyModel('Transfercertificate_model', 'save_tc_details', func_get_args());
    }

    public function getByStudentSession($student_session_id)
    {
        return $this->callLegacyModel('Transfercertificate_model', 'getByStudentSession', func_get_args());
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