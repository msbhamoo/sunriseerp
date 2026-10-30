<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StaffattendancecorrectionModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Staffattendancecorrection_model.
 */
class StaffattendancecorrectionModel extends BaseModel
{
    protected $table = 'staffattendancecorrection';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function minDate()
    {
        return $this->callLegacyModel('Staffattendancecorrection_model', 'minDate', func_get_args());
    }

    public function create($staff_id, $date, $type, $in, $out, $reason)
    {
        return $this->callLegacyModel('Staffattendancecorrection_model', 'create', func_get_args());
    }

    public function cancel($id, $staff_id)
    {
        return $this->callLegacyModel('Staffattendancecorrection_model', 'cancel', func_get_args());
    }

    public function myRequests($staff_id, $limit = 20)
    {
        return $this->callLegacyModel('Staffattendancecorrection_model', 'myRequests', func_get_args());
    }

    public function pendingCount()
    {
        return $this->callLegacyModel('Staffattendancecorrection_model', 'pendingCount', func_get_args());
    }

    public function listForReview($status = 'pending', $limit = 200)
    {
        return $this->callLegacyModel('Staffattendancecorrection_model', 'listForReview', func_get_args());
    }

    public function reject($id, $reviewer_id, $remark)
    {
        return $this->callLegacyModel('Staffattendancecorrection_model', 'reject', func_get_args());
    }

    public function approve($id, $reviewer_id, $in, $out, $remark)
    {
        return $this->callLegacyModel('Staffattendancecorrection_model', 'approve', func_get_args());
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