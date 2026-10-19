<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * BookissueModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Bookissue_model.
 */
class BookissueModel extends BaseModel
{
    protected $table = 'bookissue';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Bookissue_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Bookissue_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Bookissue_model', 'add', func_get_args());
    }

    public function getMemberBooks($member_id = null)
    {
        return $this->callLegacyModel('Bookissue_model', 'getMemberBooks', func_get_args());
    }

    public function getissueMemberBooks($member_id = null)
    {
        return $this->callLegacyModel('Bookissue_model', 'getissueMemberBooks', func_get_args());
    }

    public function getissuereturnMemberBooks($member_id = null, $start_date = null, $end_date = null)
    {
        return $this->callLegacyModel('Bookissue_model', 'getissuereturnMemberBooks', func_get_args());
    }

    public function update($data)
    {
        return $this->callLegacyModel('Bookissue_model', 'update', func_get_args());
    }

    public function book_issuedByMemberID($member_id)
    {
        return $this->callLegacyModel('Bookissue_model', 'book_issuedByMemberID', func_get_args());
    }

    public function getAvailQuantity($id = null)
    {
        return $this->callLegacyModel('Bookissue_model', 'getAvailQuantity', func_get_args());
    }

    public function valid_check_exists($str)
    {
        return $this->callLegacyModel('Bookissue_model', 'valid_check_exists', func_get_args());
    }

    public function checkBookIssuedOrNot($id, $member_id)
    {
        return $this->callLegacyModel('Bookissue_model', 'checkBookIssuedOrNot', func_get_args());
    }

    public function checkAvailQuantity($id = null)
    {
        return $this->callLegacyModel('Bookissue_model', 'checkAvailQuantity', func_get_args());
    }

    public function studentBookIssue_report($start_date, $end_date)
    {
        return $this->callLegacyModel('Bookissue_model', 'studentBookIssue_report', func_get_args());
    }

    public function bookduereport($start_date, $end_date)
    {
        return $this->callLegacyModel('Bookissue_model', 'bookduereport', func_get_args());
    }

    public function dueforreturn($start_date, $end_date)
    {
        return $this->callLegacyModel('Bookissue_model', 'dueforreturn', func_get_args());
    }

    public function forreturn($start_date, $end_date)
    {
        return $this->callLegacyModel('Bookissue_model', 'forreturn', func_get_args());
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