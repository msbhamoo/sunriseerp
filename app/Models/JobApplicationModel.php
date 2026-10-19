<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * JobApplicationModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Job_application_model.
 */
class JobApplicationModel extends BaseModel
{
    protected $table = 'job_application';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Job_application_model', 'add', func_get_args());
    }

    public function getByJobId($job_id = null, $stage = null)
    {
        return $this->callLegacyModel('Job_application_model', 'getByJobId', func_get_args());
    }

    public function get($id)
    {
        return $this->callLegacyModel('Job_application_model', 'get', func_get_args());
    }

    public function updateStage($id, $stage)
    {
        return $this->callLegacyModel('Job_application_model', 'updateStage', func_get_args());
    }

    public function updateNotes($id, $notes)
    {
        return $this->callLegacyModel('Job_application_model', 'updateNotes', func_get_args());
    }

    public function delete($id = null, bool $purge = false)
    {
        return $this->callLegacyModel('Job_application_model', 'delete', func_get_args());
    }

    public function getSummaryCounts($job_id = null)
    {
        return $this->callLegacyModel('Job_application_model', 'getSummaryCounts', func_get_args());
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