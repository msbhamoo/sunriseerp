<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * CalendarModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Calendar_model.
 */
class CalendarModel extends BaseModel
{
    protected $table = 'calendar';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function saveEvent($data)
    {
        return $this->callLegacyModel('Calendar_model', 'saveEvent', func_get_args());
    }

    public function getEventsById($id = null)
    {
        return $this->callLegacyModel('Calendar_model', 'getEventsById', func_get_args());
    }

    public function getEvents()
    {
        return $this->callLegacyModel('Calendar_model', 'getEvents', func_get_args());
    }

    public function getStudentEvents($id = null)
    {
        return $this->callLegacyModel('Calendar_model', 'getStudentEvents', func_get_args());
    }

    public function deleteEvent($id)
    {
        return $this->callLegacyModel('Calendar_model', 'deleteEvent', func_get_args());
    }

    public function getTask($id, $role_id, $limit = null, $offset = null)
    {
        return $this->callLegacyModel('Calendar_model', 'getTask', func_get_args());
    }

    public function countEventByUser($user_id)
    {
        return $this->callLegacyModel('Calendar_model', 'countEventByUser', func_get_args());
    }

    public function countrows($id, $role_id)
    {
        return $this->callLegacyModel('Calendar_model', 'countrows', func_get_args());
    }

    public function countincompleteTask($id, $role_id = null)
    {
        return $this->callLegacyModel('Calendar_model', 'countincompleteTask', func_get_args());
    }

    public function getincompleteTask($id, $role_id = null)
    {
        return $this->callLegacyModel('Calendar_model', 'getincompleteTask', func_get_args());
    }

    public function geteventreminder($reminder_date)
    {
        return $this->callLegacyModel('Calendar_model', 'geteventreminder', func_get_args());
    }

    public function getstaffandstudentemail()
    {
        return $this->callLegacyModel('Calendar_model', 'getstaffandstudentemail', func_get_args());
    }

    public function getUpcomingEvents($limit = 5)
    {
        return $this->callLegacyModel('Calendar_model', 'getUpcomingEvents', func_get_args());
    }

    public function getUpcomingBirthdays($limit = 5)
    {
        return $this->callLegacyModel('Calendar_model', 'getUpcomingBirthdays', func_get_args());
    }

    public function getUpcomingExams($limit = 5)
    {
        return $this->callLegacyModel('Calendar_model', 'getUpcomingExams', func_get_args());
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