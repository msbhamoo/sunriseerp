<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * LessonplanModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Lessonplan_model.
 */
class LessonplanModel extends BaseModel
{
    protected $table = 'lessonplan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add_copy_lesson($data_to_be_insert)
    {
        return $this->callLegacyModel('Lessonplan_model', 'add_copy_lesson', func_get_args());
    }

    public function add_lesson($data)
    {
        return $this->callLegacyModel('Lessonplan_model', 'add_lesson', func_get_args());
    }

    public function getlessonBysubjectid($sub_id, $getlessonBysubjectid)
    {
        return $this->callLegacyModel('Lessonplan_model', 'getlessonBysubjectid', func_get_args());
    }

    public function getlessonBylessonid($lesson_id)
    {
        return $this->callLegacyModel('Lessonplan_model', 'getlessonBylessonid', func_get_args());
    }

    public function getlessonBysubjectidedit($sub_id, $subject_group_class_sections_id)
    {
        return $this->callLegacyModel('Lessonplan_model', 'getlessonBysubjectidedit', func_get_args());
    }

    public function get_subjectNameBySubjectGroupSubjectId($subject_group_subject_id)
    {
        return $this->callLegacyModel('Lessonplan_model', 'get_subjectNameBySubjectGroupSubjectId', func_get_args());
    }

    public function getSyllabusById($id)
    {
        return $this->callLegacyModel('Lessonplan_model', 'getSyllabusById', func_get_args());
    }

    public function add_topic($data)
    {
        return $this->callLegacyModel('Lessonplan_model', 'add_topic', func_get_args());
    }

    public function gettopicBylessonid($lessonid, $session)
    {
        return $this->callLegacyModel('Lessonplan_model', 'gettopicBylessonid', func_get_args());
    }

    public function gettopicByID($id)
    {
        return $this->callLegacyModel('Lessonplan_model', 'gettopicByID', func_get_args());
    }

    public function gettopic($session, $id = null)
    {
        return $this->callLegacyModel('Lessonplan_model', 'gettopic', func_get_args());
    }

    public function deletetopic($id, $session)
    {
        return $this->callLegacyModel('Lessonplan_model', 'deletetopic', func_get_args());
    }

    public function deletetopicbulk($id, $session)
    {
        return $this->callLegacyModel('Lessonplan_model', 'deletetopicbulk', func_get_args());
    }

    public function changeTopicStatus($data)
    {
        return $this->callLegacyModel('Lessonplan_model', 'changeTopicStatus', func_get_args());
    }

    public function add_syllabus($data)
    {
        return $this->callLegacyModel('Lessonplan_model', 'add_syllabus', func_get_args());
    }

    public function update_syllabus($data)
    {
        return $this->callLegacyModel('Lessonplan_model', 'update_syllabus', func_get_args());
    }

    public function get($session, $id = null, $subject_group_subject_id = null)
    {
        return $this->callLegacyModel('Lessonplan_model', 'get', func_get_args());
    }

    public function getsubject_group_class_sectionsId($class_id, $section_id, $subject_group_id,$session_id=NULL)
    {
        return $this->callLegacyModel('Lessonplan_model', 'getsubject_group_class_sectionsId', func_get_args());
    }

    public function getlesson($subject_group_subjectid, $subject_group_class_sections_id, $session)
    {
        return $this->callLegacyModel('Lessonplan_model', 'getlesson', func_get_args());
    }

    public function deletelesson($id, $session)
    {
        return $this->callLegacyModel('Lessonplan_model', 'deletelesson', func_get_args());
    }

    public function deletelessonbulk($id, $session, $subject_group_subject_id)
    {
        return $this->callLegacyModel('Lessonplan_model', 'deletelessonbulk', func_get_args());
    }

    public function get_subjectstatus($id, $subject_group_class_section_id)
    {
        return $this->callLegacyModel('Lessonplan_model', 'get_subjectstatus', func_get_args());
    }

    public function get_subject_syllabus($subject_group_subject_id, $subject_id, $time_from, $time_to, $new_date, $staddID, $session)
    {
        return $this->callLegacyModel('Lessonplan_model', 'get_subject_syllabus', func_get_args());
    }

    public function ifclassteacher($class_id, $section_id, $staff_id, $subject_group_id, $subject_group_subject_id)
    {
        return $this->callLegacyModel('Lessonplan_model', 'ifclassteacher', func_get_args());
    }

    public function gettopiclist($session)
    {
        return $this->callLegacyModel('Lessonplan_model', 'gettopiclist', func_get_args());
    }

    public function getlessonlist($session, $id = null)
    {
        return $this->callLegacyModel('Lessonplan_model', 'getlessonlist', func_get_args());
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