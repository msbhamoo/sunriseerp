<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * QuestionModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Question_model.
 */
class QuestionModel extends BaseModel
{
    protected $table = 'question';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Question_model', 'add', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Question_model', 'get', func_get_args());
    }

    public function getall($limit = null, $offset = null)
    {
        return $this->callLegacyModel('Question_model', 'getall', func_get_args());
    }

    public function getAllRecord($class_id=null,$section_id=null,$subject=null,$question_type=null,$question_level=null,$created_by=null)
    {
        return $this->callLegacyModel('Question_model', 'getAllRecord', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Question_model', 'remove', func_get_args());
    }

    public function image_add($id, $image)
    {
        return $this->callLegacyModel('Question_model', 'image_add', func_get_args());
    }

    public function bulkdelete($question_array)
    {
        return $this->callLegacyModel('Question_model', 'bulkdelete', func_get_args());
    }

    public function add_option($data)
    {
        return $this->callLegacyModel('Question_model', 'add_option', func_get_args());
    }

    public function add_question_answers($data)
    {
        return $this->callLegacyModel('Question_model', 'add_question_answers', func_get_args());
    }

    public function add_question_bulk($data)
    {
        return $this->callLegacyModel('Question_model', 'add_question_bulk', func_get_args());
    }

    public function get_result($id)
    {
        return $this->callLegacyModel('Question_model', 'get_result', func_get_args());
    }

    public function get_option($id)
    {
        return $this->callLegacyModel('Question_model', 'get_option', func_get_args());
    }

    public function get_answer($id)
    {
        return $this->callLegacyModel('Question_model', 'get_answer', func_get_args());
    }

    public function count()
    {
        return $this->callLegacyModel('Question_model', 'count', func_get_args());
    }

    public function getquestioncreatedstaff()
    {
        return $this->callLegacyModel('Question_model', 'getquestioncreatedstaff', func_get_args());
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