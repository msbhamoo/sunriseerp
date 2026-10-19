<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * VisitorsModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Visitors_model.
 */
class VisitorsModel extends BaseModel
{
    protected $table = 'visitors';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Visitors_model', 'add', func_get_args());
    }

    public function getPurpose()
    {
        return $this->callLegacyModel('Visitors_model', 'getPurpose', func_get_args());
    }

    public function visitors_list($id = null)
    {
        return $this->callLegacyModel('Visitors_model', 'visitors_list', func_get_args());
    }

    public function delete($id = null, bool $purge = false)
    {
        return $this->callLegacyModel('Visitors_model', 'delete', func_get_args());
    }

    public function update($id = null, $row = null): bool
    {
        return $this->callLegacyModel('Visitors_model', 'update', func_get_args());
    }

    public function image_add($visitor_id, $image)
    {
        return $this->callLegacyModel('Visitors_model', 'image_add', func_get_args());
    }

    public function image_update($visitor_id, $image)
    {
        return $this->callLegacyModel('Visitors_model', 'image_update', func_get_args());
    }

    public function image_delete($id, $img_name)
    {
        return $this->callLegacyModel('Visitors_model', 'image_delete', func_get_args());
    }

    public function getstudent($class_id, $section_id)
    {
        return $this->callLegacyModel('Visitors_model', 'getstudent', func_get_args());
    }

    public function visitorbystudentid($student_session_id)
    {
        return $this->callLegacyModel('Visitors_model', 'visitorbystudentid', func_get_args());
    }

    public function visitorbystaffid($staff_id)
    {
        return $this->callLegacyModel('Visitors_model', 'visitorbystaffid', func_get_args());
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