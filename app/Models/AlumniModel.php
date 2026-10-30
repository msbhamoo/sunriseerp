<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * AlumniModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Alumni_model.
 */
class AlumniModel extends BaseModel
{
    protected $table = 'alumni';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get_alumnidetail($student_id)
    {
        return $this->callLegacyModel('Alumni_model', 'get_alumnidetail', func_get_args());
    }

    public function get()
    {
        return $this->callLegacyModel('Alumni_model', 'get', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Alumni_model', 'add', func_get_args());
    }

    public function add_event($data)
    {
        return $this->callLegacyModel('Alumni_model', 'add_event', func_get_args());
    }

    public function getevents()
    {
        return $this->callLegacyModel('Alumni_model', 'getevents', func_get_args());
    }

    public function get_eventbydate($date)
    {
        return $this->callLegacyModel('Alumni_model', 'get_eventbydate', func_get_args());
    }

    public function get_eventbyid($id)
    {
        return $this->callLegacyModel('Alumni_model', 'get_eventbyid', func_get_args());
    }

    public function get_eventbydaterange($start, $end)
    {
        return $this->callLegacyModel('Alumni_model', 'get_eventbydaterange', func_get_args());
    }

    public function delete_event($id)
    {
        return $this->callLegacyModel('Alumni_model', 'delete_event', func_get_args());
    }

    public function get_education($student_id)
    {
        return $this->callLegacyModel('Alumni_model', 'get_education', func_get_args());
    }

    public function get_work_experience($student_id)
    {
        return $this->callLegacyModel('Alumni_model', 'get_work_experience', func_get_args());
    }

    public function save_education($student_id, $education_batch)
    {
        return $this->callLegacyModel('Alumni_model', 'save_education', func_get_args());
    }

    public function save_work_experience($student_id, $work_batch)
    {
        return $this->callLegacyModel('Alumni_model', 'save_work_experience', func_get_args());
    }

    public function deletestudent($id)
    {
        return $this->callLegacyModel('Alumni_model', 'deletestudent', func_get_args());
    }

    public function get_story($student_id)
    {
        return $this->callLegacyModel('Alumni_model', 'get_story', func_get_args());
    }

    public function save_story($data)
    {
        return $this->callLegacyModel('Alumni_model', 'save_story', func_get_args());
    }

    public function alumniMail($class_id = null, $session = null, $section = null)
    {
        return $this->callLegacyModel('Alumni_model', 'alumniMail', func_get_args());
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