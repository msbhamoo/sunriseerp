<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * ChatController
 *
 * Auto-generated CI4 controller migrating CI3 Chat.
 */
class ChatController extends BaseController
{
    public function unauthorized()
    {
        return $this->dispatchLegacy('Chat', 'unauthorized', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Chat', 'index', func_get_args());
    }

    public function dashbord()
    {
        return $this->dispatchLegacy('Chat', 'dashbord', func_get_args());
    }

    public function load_page()
    {
        return $this->dispatchLegacy('Chat', 'load_page', func_get_args());
    }

    public function chatdemo()
    {
        return $this->dispatchLegacy('Chat', 'chatdemo', func_get_args());
    }

    public function reply()
    {
        return $this->dispatchLegacy('Chat', 'reply', func_get_args());
    }

    public function load_message()
    {
        return $this->dispatchLegacy('Chat', 'load_message', func_get_args());
    }

    public function user_list()
    {
        return $this->dispatchLegacy('Chat', 'user_list', func_get_args());
    }

    public function delete_message($id, $sender_id)
    {
        return $this->dispatchLegacy('Chat', 'delete_message', func_get_args());
    }

    public function chat_seen()
    {
        return $this->dispatchLegacy('Chat', 'chat_seen', func_get_args());
    }

    public function searchuser()
    {
        return $this->dispatchLegacy('Chat', 'searchuser', func_get_args());
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

    public function adduser()
    {
        return $this->dispatchLegacy('Chat', 'adduser', func_get_args());
    }

    public function myuser()
    {
        return $this->dispatchLegacy('Chat', 'myuser', func_get_args());
    }

    public function get_chat_msg_count()
    {
        return $this->dispatchLegacy('Chat', 'get_chat_msg_count', func_get_args());
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
        $target = APPPATH . '../application/controllers/admin/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}