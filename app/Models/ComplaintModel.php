<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ComplaintModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Complaint_Model.
 */
class ComplaintModel extends BaseModel
{
    protected $table = 'complaint_model';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Complaint_Model', 'add', func_get_args());
    }

    public function image_add($complaint_id, $image)
    {
        return $this->callLegacyModel('Complaint_Model', 'image_add', func_get_args());
    }

    public function complaint_list($id = null)
    {
        return $this->callLegacyModel('Complaint_Model', 'complaint_list', func_get_args());
    }

    public function image_delete($id, $img_name)
    {
        return $this->callLegacyModel('Complaint_Model', 'image_delete', func_get_args());
    }

    public function compalaint_update($id, $data)
    {
        return $this->callLegacyModel('Complaint_Model', 'compalaint_update', func_get_args());
    }

    public function delete($id)
    {
        return $this->callLegacyModel('Complaint_Model', 'delete', func_get_args());
    }

    public function getComplaintType()
    {
        return $this->callLegacyModel('Complaint_Model', 'getComplaintType', func_get_args());
    }

    public function getComplaintSource()
    {
        return $this->callLegacyModel('Complaint_Model', 'getComplaintSource', func_get_args());
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