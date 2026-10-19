<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ChatModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Chat_model.
 */
class ChatModel extends BaseModel
{
    protected $table = 'chat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function seen($sender_id, $receiver_id, $sender_type, $receiver_type, $data)
    {
        return $this->callLegacyModel('Chat_model', 'seen', func_get_args());
    }

    public function get_chat($sender_id, $receiver_id)
    {
        return $this->callLegacyModel('Chat_model', 'get_chat', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Chat_model', 'add', func_get_args());
    }

    public function conversation_staff($sender_id)
    {
        return $this->callLegacyModel('Chat_model', 'conversation_staff', func_get_args());
    }

    public function conversation_student($sender_id)
    {
        return $this->callLegacyModel('Chat_model', 'conversation_student', func_get_args());
    }

    public function conversation_parent($sender_id)
    {
        return $this->callLegacyModel('Chat_model', 'conversation_parent', func_get_args());
    }

    public function conversation_seen($sender_id)
    {
        return $this->callLegacyModel('Chat_model', 'conversation_seen', func_get_args());
    }

    public function conversation($sender_id)
    {
        return $this->callLegacyModel('Chat_model', 'conversation', func_get_args());
    }

    public function conversation_forstudent($sender_id)
    {
        return $this->callLegacyModel('Chat_model', 'conversation_forstudent', func_get_args());
    }

    public function conversation_forparent($parent_id)
    {
        return $this->callLegacyModel('Chat_model', 'conversation_forparent', func_get_args());
    }

    public function get_staff($staff)
    {
        return $this->callLegacyModel('Chat_model', 'get_staff', func_get_args());
    }

    public function get_student($student)
    {
        return $this->callLegacyModel('Chat_model', 'get_student', func_get_args());
    }

    public function get_parent($name)
    {
        return $this->callLegacyModel('Chat_model', 'get_parent', func_get_args());
    }

    public function receiver_name($receiver_id, $type)
    {
        return $this->callLegacyModel('Chat_model', 'receiver_name', func_get_args());
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