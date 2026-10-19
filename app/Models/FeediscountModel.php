<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * FeediscountModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Feediscount_model.
 */
class FeediscountModel extends BaseModel
{
    protected $table = 'feediscount';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null, $order = "desc")
    {
        return $this->callLegacyModel('Feediscount_model', 'get', func_get_args());
    }

    public function getbyasc($id = null)
    {
        return $this->callLegacyModel('Feediscount_model', 'getbyasc', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Feediscount_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Feediscount_model', 'add', func_get_args());
    }

    public function updateStudentDiscount($data)
    {
        return $this->callLegacyModel('Feediscount_model', 'updateStudentDiscount', func_get_args());
    }

    public function allotdiscount($data)
    {
        return $this->callLegacyModel('Feediscount_model', 'allotdiscount', func_get_args());
    }

    public function searchAssignFeeByClassSection($class_id = null, $section_id = null, $fees_discount_id = null, $category = null, $gender = null, $rte = null)
    {
        return $this->callLegacyModel('Feediscount_model', 'searchAssignFeeByClassSection', func_get_args());
    }

    public function deletedisstd($fees_discount_id, $array)
    {
        return $this->callLegacyModel('Feediscount_model', 'deletedisstd', func_get_args());
    }

    public function delete_assigned_discount($student_id)
    {
        return $this->callLegacyModel('Feediscount_model', 'delete_assigned_discount', func_get_args());
    }

    public function getStudentFeesDiscount($student_session_id = null)
    {
        return $this->callLegacyModel('Feediscount_model', 'getStudentFeesDiscount', func_get_args());
    }

    public function getDiscountNotApplied($student_session_id = null)
    {
        return $this->callLegacyModel('Feediscount_model', 'getDiscountNotApplied', func_get_args());
    }

    public function createDynamicDiscount($request)
    {
        return $this->callLegacyModel('Feediscount_model', 'createDynamicDiscount', func_get_args());
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