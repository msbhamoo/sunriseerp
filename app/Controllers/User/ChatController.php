<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * ChatController
 *
 * Auto-generated CI4 controller migrating CI3 Chat.
 */
class ChatController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Chat', 'index', func_get_args());
    }

    public function myuser()
    {
        return $this->dispatchLegacy('Chat', 'myuser', func_get_args());
    }

    public function getChatRecord()
    {
        return $this->dispatchLegacy('Chat', 'getChatRecord', func_get_args());
    }

    public function newMessage()
    {
        return $this->dispatchLegacy('Chat', 'newMessage', func_get_args());
    }

    public function chatUpdate()
    {
        return $this->dispatchLegacy('Chat', 'chatUpdate', func_get_args());
    }

    public function mychatnotification()
    {
        return $this->dispatchLegacy('Chat', 'mychatnotification', func_get_args());
    }

    public function searchuser()
    {
        return $this->dispatchLegacy('Chat', 'searchuser', func_get_args());
    }

    public function mynewuser()
    {
        return $this->dispatchLegacy('Chat', 'mynewuser', func_get_args());
    }

    public function adduser()
    {
        return $this->dispatchLegacy('Chat', 'adduser', func_get_args());
    }

    public function get_student_parent_chat_msg_count()
    {
        return $this->dispatchLegacy('Chat', 'get_student_parent_chat_msg_count', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/user/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}