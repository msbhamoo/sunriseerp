<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * LessonplanController
 *
 * Auto-generated CI4 controller migrating CI3 Lessonplan.
 */
class LessonplanController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Lessonplan', 'index', func_get_args());
    }

    public function copylesson()
    {
        return $this->dispatchLegacy('Lessonplan', 'copylesson', func_get_args());
    }

    public function lesson()
    {
        return $this->dispatchLegacy('Lessonplan', 'lesson', func_get_args());
    }

    public function createlesson()
    {
        return $this->dispatchLegacy('Lessonplan', 'createlesson', func_get_args());
    }

    public function deletelesson($id)
    {
        return $this->dispatchLegacy('Lessonplan', 'deletelesson', func_get_args());
    }

    public function deletelessonbulk($id, $subject_group_subject_id)
    {
        return $this->dispatchLegacy('Lessonplan', 'deletelessonbulk', func_get_args());
    }

    public function editlesson($id, $subject_group_subject_id)
    {
        return $this->dispatchLegacy('Lessonplan', 'editlesson', func_get_args());
    }

    public function saveCopyLesson()
    {
        return $this->dispatchLegacy('Lessonplan', 'saveCopyLesson', func_get_args());
    }

    public function updatelesson()
    {
        return $this->dispatchLegacy('Lessonplan', 'updatelesson', func_get_args());
    }

    public function topic()
    {
        return $this->dispatchLegacy('Lessonplan', 'topic', func_get_args());
    }

    public function getlessonBysubjectid($sub_id)
    {
        return $this->dispatchLegacy('Lessonplan', 'getlessonBysubjectid', func_get_args());
    }

    public function getlessonBylessonid($lesson_id)
    {
        return $this->dispatchLegacy('Lessonplan', 'getlessonBylessonid', func_get_args());
    }

    public function getlessonBysubjectidedit($sub_id)
    {
        return $this->dispatchLegacy('Lessonplan', 'getlessonBysubjectidedit', func_get_args());
    }

    public function createtopic()
    {
        return $this->dispatchLegacy('Lessonplan', 'createtopic', func_get_args());
    }

    public function deletetopicbulk($id)
    {
        return $this->dispatchLegacy('Lessonplan', 'deletetopicbulk', func_get_args());
    }

    public function edittopic($id)
    {
        return $this->dispatchLegacy('Lessonplan', 'edittopic', func_get_args());
    }

    public function updatetopic()
    {
        return $this->dispatchLegacy('Lessonplan', 'updatetopic', func_get_args());
    }

    public function changeTopicStatus()
    {
        return $this->dispatchLegacy('Lessonplan', 'changeTopicStatus', func_get_args());
    }

    public function topic_completedate()
    {
        return $this->dispatchLegacy('Lessonplan', 'topic_completedate', func_get_args());
    }

    public function gettopicBylessonid($lessonid)
    {
        return $this->dispatchLegacy('Lessonplan', 'gettopicBylessonid', func_get_args());
    }

    public function get_topicbyid()
    {
        return $this->dispatchLegacy('Lessonplan', 'get_topicbyid', func_get_args());
    }

    public function gettopiclist()
    {
        return $this->dispatchLegacy('Lessonplan', 'gettopiclist', func_get_args());
    }

    public function getlessonlist()
    {
        return $this->dispatchLegacy('Lessonplan', 'getlessonlist', func_get_args());
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