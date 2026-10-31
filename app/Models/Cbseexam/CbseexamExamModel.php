<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseexamExamModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbseexam_exam_model.
 */
class CbseexamExamModel extends BaseModel
{
    protected $table = 'cbseexam_exam';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data, $assessment_delete = true)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'add', func_get_args());
    }

    public function wizard_save_exam($exam_data, $classes, $sections, $subjects, $assessments, $dates, $start_times, $durations, $room_nos, $assigned_classes_list = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'wizard_save_exam', func_get_args());
    }

    public function getExamClassesSections($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getExamClassesSections', func_get_args());
    }

    public function getWizardExamTimetable($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getWizardExamTimetable', func_get_args());
    }

    public function wizard_update_exam($exam_id, $exam_data, $classes, $sections, $subjects, $assessments, $dates, $start_times, $durations, $room_nos, $timetable_ids, $assigned_classes_list = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'wizard_update_exam', func_get_args());
    }

    public function addexamstudent($data)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'addexamstudent', func_get_args());
    }

    public function getexamlist()
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getexamlist', func_get_args());
    }

    public function getPublishexams()
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getPublishexams', func_get_args());
    }

    public function get_all_session_exams()
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_all_session_exams', func_get_args());
    }

    public function getExamResultByExamIdByTemplate($cbse_exam_id, $cbse_template_id, $class_section_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getExamResultByExamIdByTemplate', func_get_args());
    }

    public function getExamResultByExamId($cbse_exam_id, $class_id = null, $section_id = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getExamResultByExamId', func_get_args());
    }

    public function getStudentExamResultByExamIdAndAdmissionNo($cbse_exam_id, $admission_no)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentExamResultByExamIdAndAdmissionNo', func_get_args());
    }

    public function getStudentExamResultByExamId($cbse_template_id, $cbse_exam_id, $students)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentExamResultByExamId', func_get_args());
    }

    public function getStudentResultByExamId($cbse_exam_id, $students)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentResultByExamId', func_get_args());
    }

    public function getStudentResultByTemplateId($cbse_template_id, $class_section_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentResultByTemplateId', func_get_args());
    }

    public function getStudentExamResultByTemplateId($cbse_template_id, $students = [])
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentExamResultByTemplateId', func_get_args());
    }

    public function getResultTermwiseByTemplateIdWithSelectedTerm($cbse_template_id, $class_section_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getResultTermwiseByTemplateIdWithSelectedTerm', func_get_args());
    }

    public function getResultTermwiseByTemplateId($cbse_template_id, $class_section_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getResultTermwiseByTemplateId', func_get_args());
    }

    public function getStudentExamResultTermwiseByTemplateId($cbse_template_id, $students = [])
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentExamResultTermwiseByTemplateId', func_get_args());
    }

    public function getTemplateAssessment($cbse_template_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getTemplateAssessment', func_get_args());
    }

    public function getTemplateSingleExam($cbse_template_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getTemplateSingleExam', func_get_args());
    }

    public function searchTermStudentsByClass($cbse_observation_term_id, $class_id, $section_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'searchTermStudentsByClass', func_get_args());
    }

    public function searchStudentOverservationMarks($cbse_observation_term_id, $class_id, $section_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'searchStudentOverservationMarks', func_get_args());
    }

    public function getTemplateAssessmentWithoutTerm($cbse_template_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getTemplateAssessmentWithoutTerm', func_get_args());
    }

    public function getexams()
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getexams', func_get_args());
    }

    public function get()
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get', func_get_args());
    }

    public function get_editdetails($id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_editdetails', func_get_args());
    }

    public function get_exambyId($id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_exambyId', func_get_args());
    }

    public function getExamWithGrade($id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getExamWithGrade', func_get_args());
    }

    public function remove_exam($id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'remove_exam', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'remove', func_get_args());
    }

    public function add_exam_class_section($data)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'add_exam_class_section', func_get_args());
    }

    public function searchExamStudents($exam_class_section, $exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'searchExamStudents', func_get_args());
    }

    public function getExamStudents($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getExamStudents', func_get_args());
    }

    public function getExamByGrade($cbse_term_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getExamByGrade', func_get_args());
    }

    public function getExamClasses($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getExamClasses', func_get_args());
    }

    public function getClassByExam($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getClassByExam', func_get_args());
    }

    public function getExamSectionByClass($exam_id, $class_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getExamSectionByClass', func_get_args());
    }

    public function get_class_sectionbyexamid($id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_class_sectionbyexamid', func_get_args());
    }

    public function add_student($insert_array, $exam_id, $all_students)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'add_student', func_get_args());
    }

    public function insert($data = null, bool $returnID = true)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'insert', func_get_args());
    }

    public function getexamdetails($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getexamdetails', func_get_args());
    }

    public function add_examsubject($insert_array, $update_array, $not_be_del, $exam_id, $timetable_prev_rows, $assessment_array, $prev_assessment_array)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'add_examsubject', func_get_args());
    }

    public function find_assessment_exists($assessment_array, $find_value)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'find_assessment_exists', func_get_args());
    }

    public function getexamsubjects($exam_id, $class_id = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getexamsubjects', func_get_args());
    }

    public function getexamSubjectswithAssessment($exam_id, $assessments, $class_ids = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getexamSubjectswithAssessment', func_get_args());
    }

    public function getExamTimetable()
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getExamTimetable', func_get_args());
    }

    public function getExamTimetableMatrix($exam_id, $class_id = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getExamTimetableMatrix', func_get_args());
    }

    public function getStudentExamTimetable($student_session_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentExamTimetable', func_get_args());
    }

    public function getStudentexamSubjectsResult($exam_id, $cbse_exam_student_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentexamSubjectsResult', func_get_args());
    }

    public function getStudentExamByStudentSession($student_session_id, $publish_only = true)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentExamByStudentSession', func_get_args());
    }

    public function get_examstudents($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_examstudents', func_get_args());
    }

    public function get_markexamstudents($timetable_id, $class_id = null, $section_id = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_markexamstudents', func_get_args());
    }

    public function get_markexamstudents_multi($timetable_id, $class_ids = array(), $section_ids = array())
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_markexamstudents_multi', func_get_args());
    }

    public function get_exam_subject_assessment_types($exam_assessment_id, $timetable_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_exam_subject_assessment_types', func_get_args());
    }

    public function get_exam_assessment_types($exam_assessment_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_exam_assessment_types', func_get_args());
    }

    public function addresult_data($result_data)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'addresult_data', func_get_args());
    }

    public function getresult_data($subject_id, $student_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getresult_data', func_get_args());
    }

    public function addresultmark_data($result_mark, $cbse_exam_timetable_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'addresultmark_data', func_get_args());
    }

    public function add_exam_student_attendance($data)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'add_exam_student_attendance', func_get_args());
    }

    public function add_exam_attendance($data)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'add_exam_attendance', func_get_args());
    }

    public function delete_exam_student_attendance($data)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'delete_exam_student_attendance', func_get_args());
    }

    public function getexamattendance($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getexamattendance', func_get_args());
    }

    public function get_teacher_remark($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_teacher_remark', func_get_args());
    }

    public function getremarkbyexamid($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getremarkbyexamid', func_get_args());
    }

    public function addteacherremark($data)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'addteacherremark', func_get_args());
    }

    public function get_classsectionbyId($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_classsectionbyId', func_get_args());
    }

    public function removeclasssection($id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'removeclasssection', func_get_args());
    }

    public function getSubject($id = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getSubject', func_get_args());
    }

    public function get_examsubjects($staff_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_examsubjects', func_get_args());
    }

    public function get_cbse_exam_sections_name($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_cbse_exam_sections_name', func_get_args());
    }

    public function getexamsubjectnote($subject_note_exam_id,$student_session_id,$subject_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getexamsubjectnote', func_get_args());
    }

    public function getStudentAttendenceRange($date)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentAttendenceRange', func_get_args());
    }

    public function getStudentExamResultByExamId_download($cbse_template_id, $cbse_exam_id, $students)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentExamResultByExamId_download', func_get_args());
    }

    public function get_cbse_reportcard_data($exam_id, $class_ids = array(), $section_ids = array())
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_cbse_reportcard_data', func_get_args());
    }

    public function get_subject_max_marks($exam_id, $class_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_subject_max_marks', func_get_args());
    }

    public function save_subject_max_marks($rows)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'save_subject_max_marks', func_get_args());
    }

    public function get_bulk_exam_classes($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_bulk_exam_classes', func_get_args());
    }

    public function get_bulk_exam_structure($exam_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_bulk_exam_structure', func_get_args());
    }

    public function get_bulk_exam_students_with_marks($exam_id, $class_ids = null, $section_ids = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_bulk_exam_students_with_marks', func_get_args());
    }

    public function save_bulk_exam_marks($exam_id, $bulk_marks_data)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'save_bulk_exam_marks', func_get_args());
    }

    public function get_exam_marks_status($exam_id, $class_id = null, $section_id = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_exam_marks_status', func_get_args());
    }

    public function get_exam_teacher_compliance($exam_id, $filter_staff_id = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_exam_teacher_compliance', func_get_args());
    }

    public function get_exam_grade_distribution($exam_id, $class_id = null, $section_id = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_exam_grade_distribution', func_get_args());
    }

    public function get_exam_top_rankers($exam_id, $class_id = null, $section_id = null, $limit = 10)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_exam_top_rankers', func_get_args());
    }

    public function get_exam_roll_number_report($exam_id, $class_id = null, $section_id = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_exam_roll_number_report', func_get_args());
    }

    public function getStudentExamMarksSummary($student_session_id)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'getStudentExamMarksSummary', func_get_args());
    }


    public function get_observation_parameter($exam_id = null)
    {
        return $this->callLegacyModel('Cbseexam_exam_model', 'get_observation_parameter', func_get_args());
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
        return 'cbseexam';
    }
}