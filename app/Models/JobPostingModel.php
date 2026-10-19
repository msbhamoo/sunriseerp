<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * JobPostingModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Job_posting_model.
 */
class JobPostingModel extends BaseModel
{
    protected $table = 'job_posting';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Job_posting_model', 'get', func_get_args());
    }

    public function incrementViews($id)
    {
        return $this->callLegacyModel('Job_posting_model', 'incrementViews', func_get_args());
    }

    public function getActiveForWebsite($limit = null, $search = null, $designation_id = null)
    {
        return $this->callLegacyModel('Job_posting_model', 'getActiveForWebsite', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Job_posting_model', 'add', func_get_args());
    }

    public function delete($id)
    {
        return $this->callLegacyModel('Job_posting_model', 'delete', func_get_args());
    }

    public function changeStatus($id, $status)
    {
        return $this->callLegacyModel('Job_posting_model', 'changeStatus', func_get_args());
    }

    public function toggleCloseStatus($id, $is_closed)
    {
        return $this->callLegacyModel('Job_posting_model', 'toggleCloseStatus', func_get_args());
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