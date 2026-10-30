<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ExamsubjectModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Examsubject_model.
 */
class ExamsubjectModel extends BaseModel
{
    protected $table = 'examsubject';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($insert_array, $update_array, $not_be_del, $exam_id)
    {
        return $this->callLegacyModel('Examsubject_model', 'add', func_get_args());
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