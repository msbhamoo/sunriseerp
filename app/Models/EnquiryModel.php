<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * EnquiryModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Enquiry_model.
 */
class EnquiryModel extends BaseModel
{
    protected $table = 'enquiry';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getclasses($id = null)
    {
        return $this->callLegacyModel('Enquiry_model', 'getclasses', func_get_args());
    }

    public function get_enquiry_type()
    {
        return $this->callLegacyModel('Enquiry_model', 'get_enquiry_type', func_get_args());
    }

    public function getComplaintSource()
    {
        return $this->callLegacyModel('Enquiry_model', 'getComplaintSource', func_get_args());
    }

    public function getComplaintType()
    {
        return $this->callLegacyModel('Enquiry_model', 'getComplaintType', func_get_args());
    }

    public function get_reference()
    {
        return $this->callLegacyModel('Enquiry_model', 'get_reference', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Enquiry_model', 'add', func_get_args());
    }

    public function getenquiry_list($id = null, $status = 'active')
    {
        return $this->callLegacyModel('Enquiry_model', 'getenquiry_list', func_get_args());
    }

    public function getFollowByEnquiry($id)
    {
        return $this->callLegacyModel('Enquiry_model', 'getFollowByEnquiry', func_get_args());
    }

    public function getfollow_up_list($enquiry_id, $follow_up = null)
    {
        return $this->callLegacyModel('Enquiry_model', 'getfollow_up_list', func_get_args());
    }

    public function add_follow_up($data)
    {
        return $this->callLegacyModel('Enquiry_model', 'add_follow_up', func_get_args());
    }

    public function follow_up_update($enquiry_id, $follow_up_id, $data)
    {
        return $this->callLegacyModel('Enquiry_model', 'follow_up_update', func_get_args());
    }

    public function enquiry_update($id, $data)
    {
        return $this->callLegacyModel('Enquiry_model', 'enquiry_update', func_get_args());
    }

    public function enquiry_delete($id)
    {
        return $this->callLegacyModel('Enquiry_model', 'enquiry_delete', func_get_args());
    }

    public function delete_follow_up($id)
    {
        return $this->callLegacyModel('Enquiry_model', 'delete_follow_up', func_get_args());
    }

    public function next_follow_up_date($enquiry_id)
    {
        return $this->callLegacyModel('Enquiry_model', 'next_follow_up_date', func_get_args());
    }

    public function changeStatus($data)
    {
        return $this->callLegacyModel('Enquiry_model', 'changeStatus', func_get_args());
    }

    public function searchEnquiry($class, $source, $date_from, $date_to, $status = 'active')
    {
        return $this->callLegacyModel('Enquiry_model', 'searchEnquiry', func_get_args());
    }

    public function check_number($phone_number)
    {
        return $this->callLegacyModel('Enquiry_model', 'check_number', func_get_args());
    }

    public function getPendingAdmissions()
    {
        return $this->callLegacyModel('Enquiry_model', 'getPendingAdmissions', func_get_args());
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