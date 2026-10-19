<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ChatuserModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Chatuser_model.
 */
class ChatuserModel extends BaseModel
{
    protected $table = 'chatuser';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function searchForUser($keyword, $chat_user_id, $login_id, $user_type = 'staff')
    {
        return $this->callLegacyModel('Chatuser_model', 'searchForUser', func_get_args());
    }

    public function myUser($staff_id, $chat_connection_id, $user_type = 'staff')
    {
        return $this->callLegacyModel('Chatuser_model', 'myUser', func_get_args());
    }

    public function getLastMessages($chat_connection_id)
    {
        return $this->callLegacyModel('Chatuser_model', 'getLastMessages', func_get_args());
    }

    public function getChatUserDetail($chat_user_id)
    {
        return $this->callLegacyModel('Chatuser_model', 'getChatUserDetail', func_get_args());
    }

    public function myChatAndUpdate($chat_connection_id, $chat_user_id)
    {
        return $this->callLegacyModel('Chatuser_model', 'myChatAndUpdate', func_get_args());
    }

    public function getMyID($id, $user_type = 'staff')
    {
        return $this->callLegacyModel('Chatuser_model', 'getMyID', func_get_args());
    }

    public function getChatNotification($chat_user_id)
    {
        return $this->callLegacyModel('Chatuser_model', 'getChatNotification', func_get_args());
    }

    public function getChatConnectionByID($id)
    {
        return $this->callLegacyModel('Chatuser_model', 'getChatConnectionByID', func_get_args());
    }

    public function addMessage($insert)
    {
        return $this->callLegacyModel('Chatuser_model', 'addMessage', func_get_args());
    }

    public function getUpdatedchat($chat_connection_id, $last_chat_id, $chat_user_id)
    {
        return $this->callLegacyModel('Chatuser_model', 'getUpdatedchat', func_get_args());
    }

    public function addNewUser($first_entry, $insert_data, $id, $insert_message, $panel = "staff")
    {
        return $this->callLegacyModel('Chatuser_model', 'addNewUser', func_get_args());
    }

    public function addNewUserForStudent($first_entry, $insert_data, $id, $insert_message, $panel = "student")
    {
        return $this->callLegacyModel('Chatuser_model', 'addNewUserForStudent', func_get_args());
    }

    public function mynewuser($user_id, $userlist)
    {
        return $this->callLegacyModel('Chatuser_model', 'mynewuser', func_get_args());
    }

    public function delete_msg($id)
    {
        return $this->callLegacyModel('Chatuser_model', 'delete_msg', func_get_args());
    }

    public function get_active_chat_msg($chat_connection_id, $chat_user_id)
    {
        return $this->callLegacyModel('Chatuser_model', 'get_active_chat_msg', func_get_args());
    }

    public function get_chat_msg_count($staff_id)
    {
        return $this->callLegacyModel('Chatuser_model', 'get_chat_msg_count', func_get_args());
    }

    public function get_student_parent_chat_msg_count($student_id, $user_type)
    {
        return $this->callLegacyModel('Chatuser_model', 'get_student_parent_chat_msg_count', func_get_args());
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