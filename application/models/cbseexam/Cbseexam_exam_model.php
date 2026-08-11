<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cbseexam_exam_model extends MY_Model
{

    protected $current_session;
    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /*
    This function is used to add and update cbse exam
    */
    public function add($data, $assessment_delete = true)
    {


        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && !empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('cbse_exams', $data);


            if ($assessment_delete) { //============if assessment value changed
                $findexam_assessments = $this->db->select('id')
                    ->from('cbse_exam_timetable')
                    ->where('cbse_exam_id', $data['id'])
                    ->get()
                    ->result();
                if (!empty($findexam_assessments)) {
                    $delete_assessment = [];
                    foreach ($findexam_assessments as $findexam_assessments_key => $findexam_assessments_value) {
                        $delete_assessment[] = $findexam_assessments_value->id;
                    }
                    $this->db->where_in('cbse_exam_timetable_id', $delete_assessment);
                    $this->db->delete('cbse_student_subject_marks');

                    //========================
                    $this->db->where_in('cbse_exam_timetable_id', $delete_assessment);
                    $this->db->delete('cbse_exam_timetable_assessment_types');

                    //============================
                }
            }




            $message = UPDATE_RECORD_CONSTANT . " On cbse exams id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
        } else {
            $this->db->insert('cbse_exams', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On cbse exams id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
        }
    }

    public function wizard_save_exam($exam_data, $classes, $sections, $subjects, $assessments, $dates, $start_times, $durations, $room_nos, $assigned_classes_list = null)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        
        // Insert exam
        $this->db->insert('cbse_exams', $exam_data);
        $exam_id = $this->db->insert_id();
        
        // Insert class sections and collect student session ids
        $all_student_session_ids = [];
        if (!empty($classes)) {
            foreach ($classes as $class_id) {
                if (isset($sections[$class_id])) {
                    foreach ($sections[$class_id] as $section_id) {
                        // Find class_section_id
                        $this->db->select('id');
                        $this->db->where('class_id', $class_id);
                        $this->db->where('section_id', $section_id);
                        $cs_row = $this->db->get('class_sections')->row();
                        
                        if ($cs_row) {
                            $this->db->insert('cbse_exam_class_sections', [
                                'cbse_exam_id' => $exam_id,
                                'class_section_id' => $cs_row->id
                            ]);
                            
                            // Get students for this class section
                            $this->db->select('id');
                            $this->db->where('session_id', $exam_data['session_id']);
                            $this->db->where('class_id', $class_id);
                            $this->db->where('section_id', $section_id);
                            $students = $this->db->get('student_session')->result();
                            
                            foreach ($students as $stu) {
                                $all_student_session_ids[] = $stu->id;
                            }
                        }
                    }
                }
            }
        }
        
        // Auto assign all students from selected class sections
        if (!empty($all_student_session_ids)) {
            foreach ($all_student_session_ids as $session_id) {
                $this->db->insert('cbse_exam_students', [
                    'cbse_exam_id' => $exam_id,
                    'student_session_id' => $session_id
                ]);
            }
        }
        
        // Insert Subjects and Timetable
        if (!empty($subjects)) {
            foreach ($subjects as $key => $subject_id) {
                if (!empty($subject_id)) {
                    $assessment_ids = explode(',', $assessments[$key]);
                    
                    $this->db->insert('cbse_exam_timetable', [
                        'cbse_exam_id' => $exam_id,
                        'subject_id' => $subject_id,
                        'date' => date('Y-m-d', strtotime($dates[$key])),
                        'time_from' => $start_times[$key],
                        'duration' => $durations[$key],
                        'room_no' => $room_nos[$key]
                    ]);
                    $timetable_id = $this->db->insert_id();
                    
                    foreach ($assessment_ids as $assess_id) {
                        if (!empty($assess_id)) {
                            $this->db->insert('cbse_exam_timetable_assessment_types', [
                                'cbse_exam_timetable_id' => $timetable_id,
                                'cbse_exam_assessment_type_id' => $assess_id
                            ]);
                        }
                    }
                    
                    if (isset($assigned_classes_list[$key]) && !empty($assigned_classes_list[$key])) {
                        $cls_ids = explode(',', $assigned_classes_list[$key]);
                        foreach ($cls_ids as $c_id) {
                            if (!empty($c_id)) {
                                $this->db->insert('cbse_exam_timetable_classes', [
                                    'cbse_exam_timetable_id' => $timetable_id,
                                    'class_id' => $c_id
                                ]);
                            }
                        }
                    }
                }
            }
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function getExamClassesSections($exam_id) {
        $this->db->select('class_section_id, class_id, section_id');
        $this->db->from('cbse_exam_class_sections');
        $this->db->join('class_sections', 'class_sections.id = cbse_exam_class_sections.class_section_id');
        $this->db->where('cbse_exam_id', $exam_id);
        $result = $this->db->get()->result_array();
        
        $assigned = [];
        foreach ($result as $row) {
            $assigned[$row['class_id']][] = $row['section_id'];
        }
        return $assigned;
    }

    public function getWizardExamTimetable($exam_id) {
        $this->db->select('cbse_exam_timetable.*, GROUP_CONCAT(DISTINCT cbse_exam_timetable_assessment_types.cbse_exam_assessment_type_id) as assessment_ids, GROUP_CONCAT(DISTINCT cbse_exam_timetable_classes.class_id) as assigned_class_ids');
        $this->db->from('cbse_exam_timetable');
        $this->db->join('cbse_exam_timetable_assessment_types', 'cbse_exam_timetable_assessment_types.cbse_exam_timetable_id = cbse_exam_timetable.id', 'left');
        $this->db->join('cbse_exam_timetable_classes', 'cbse_exam_timetable_classes.cbse_exam_timetable_id = cbse_exam_timetable.id', 'left');
        $this->db->where('cbse_exam_timetable.cbse_exam_id', $exam_id);
        $this->db->group_by('cbse_exam_timetable.id');
        return $this->db->get()->result_array();
    }

    public function wizard_update_exam($exam_id, $exam_data, $classes, $sections, $subjects, $assessments, $dates, $start_times, $durations, $room_nos, $timetable_ids, $assigned_classes_list = null)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        
        if (isset($exam_data['cbse_exam_assessment_id'])) {
            unset($exam_data['cbse_exam_assessment_id']);
        }
        $this->db->where('id', $exam_id);
        $this->db->update('cbse_exams', $exam_data);
        
        $this->db->select('cbse_exam_class_sections.id as ecs_id, class_sections.id as class_section_id, class_sections.class_id, class_sections.section_id');
        $this->db->from('cbse_exam_class_sections');
        $this->db->join('class_sections', 'class_sections.id = cbse_exam_class_sections.class_section_id');
        $this->db->where('cbse_exam_class_sections.cbse_exam_id', $exam_id);
        $existing_ecs = $this->db->get()->result();
        
        $existing_map = [];
        foreach ($existing_ecs as $ecs) {
            $existing_map[$ecs->class_id . '-' . $ecs->section_id] = $ecs;
        }
        
        $submitted_map = [];
        if (!empty($classes)) {
            foreach ($classes as $class_id) {
                if (isset($sections[$class_id])) {
                    foreach ($sections[$class_id] as $section_id) {
                        $submitted_map[$class_id . '-' . $section_id] = true;
                    }
                }
            }
        }
        
        foreach ($submitted_map as $key => $val) {
            if (!isset($existing_map[$key])) {
                list($class_id, $section_id) = explode('-', $key);
                $this->db->select('id');
                $this->db->where('class_id', $class_id);
                $this->db->where('section_id', $section_id);
                $cs_row = $this->db->get('class_sections')->row();
                
                if ($cs_row) {
                    $this->db->insert('cbse_exam_class_sections', [
                        'cbse_exam_id' => $exam_id,
                        'class_section_id' => $cs_row->id
                    ]);
                    
                    $this->db->select('id');
                    $this->db->where('session_id', isset($exam_data['session_id']) ? $exam_data['session_id'] : $this->current_session);
                    $this->db->where('class_id', $class_id);
                    $this->db->where('section_id', $section_id);
                    $students = $this->db->get('student_session')->result();
                    
                    foreach ($students as $stu) {
                        $this->db->where('cbse_exam_id', $exam_id);
                        $this->db->where('student_session_id', $stu->id);
                        if ($this->db->count_all_results('cbse_exam_students') == 0) {
                            $this->db->insert('cbse_exam_students', [
                                'cbse_exam_id' => $exam_id,
                                'student_session_id' => $stu->id
                            ]);
                        }
                    }
                }
            }
        }
        
        foreach ($existing_map as $key => $ecs) {
            if (!isset($submitted_map[$key])) {
                $this->db->where('id', $ecs->ecs_id);
                $this->db->delete('cbse_exam_class_sections');
                
                $this->db->select('id');
                $this->db->where('session_id', isset($exam_data['session_id']) ? $exam_data['session_id'] : $this->current_session);
                $this->db->where('class_id', $ecs->class_id);
                $this->db->where('section_id', $ecs->section_id);
                $students = $this->db->get('student_session')->result();
                
                $stu_ids = [];
                foreach ($students as $s) {
                    $stu_ids[] = $s->id;
                }
                
                if (!empty($stu_ids)) {
                    $this->db->where('cbse_exam_id', $exam_id);
                    $this->db->where_in('student_session_id', $stu_ids);
                    $this->db->delete('cbse_exam_students');
                }
            }
        }
        
        $this->db->select('id');
        $this->db->where('cbse_exam_id', $exam_id);
        $existing_tts_res = $this->db->get('cbse_exam_timetable')->result();
        $existing_tt_ids = [];
        foreach ($existing_tts_res as $t) {
            $existing_tt_ids[$t->id] = true;
        }
        
        $submitted_tt_ids = [];
        
        if (!empty($subjects)) {
            foreach ($subjects as $key => $subject_id) {
                if (!empty($subject_id)) {
                    $assessment_ids = explode(',', $assessments[$key]);
                    $tt_id = isset($timetable_ids[$key]) ? $timetable_ids[$key] : '';
                    
                    if (!empty($tt_id) && isset($existing_tt_ids[$tt_id])) {
                        $this->db->where('id', $tt_id);
                        $this->db->update('cbse_exam_timetable', [
                            'date' => date('Y-m-d', strtotime($dates[$key])),
                            'time_from' => $start_times[$key],
                            'duration' => $durations[$key],
                            'room_no' => $room_nos[$key]
                        ]);
                        $submitted_tt_ids[$tt_id] = true;
                        
                        $this->db->where('cbse_exam_timetable_id', $tt_id);
                        $this->db->delete('cbse_exam_timetable_assessment_types');
                        
                        foreach ($assessment_ids as $assess_id) {
                            if (!empty($assess_id)) {
                                $this->db->insert('cbse_exam_timetable_assessment_types', [
                                    'cbse_exam_timetable_id' => $tt_id,
                                    'cbse_exam_assessment_type_id' => $assess_id
                                ]);
                            }
                        }
                        
                        $this->db->where('cbse_exam_timetable_id', $tt_id);
                        $this->db->delete('cbse_exam_timetable_classes');
                        
                        if (isset($assigned_classes_list[$key]) && !empty($assigned_classes_list[$key])) {
                            $cls_ids = explode(',', $assigned_classes_list[$key]);
                            foreach ($cls_ids as $c_id) {
                                if (!empty($c_id)) {
                                    $this->db->insert('cbse_exam_timetable_classes', [
                                        'cbse_exam_timetable_id' => $tt_id,
                                        'class_id' => $c_id
                                    ]);
                                }
                            }
                        }
                    } else {
                        $this->db->insert('cbse_exam_timetable', [
                            'cbse_exam_id' => $exam_id,
                            'subject_id' => $subject_id,
                            'date' => date('Y-m-d', strtotime($dates[$key])),
                            'time_from' => $start_times[$key],
                            'duration' => $durations[$key],
                            'room_no' => $room_nos[$key]
                        ]);
                        $new_tt_id = $this->db->insert_id();
                        
                        foreach ($assessment_ids as $assess_id) {
                            if (!empty($assess_id)) {
                                $this->db->insert('cbse_exam_timetable_assessment_types', [
                                    'cbse_exam_timetable_id' => $new_tt_id,
                                    'cbse_exam_assessment_type_id' => $assess_id
                                ]);
                            }
                        }
                        
                        if (isset($assigned_classes_list[$key]) && !empty($assigned_classes_list[$key])) {
                            $cls_ids = explode(',', $assigned_classes_list[$key]);
                            foreach ($cls_ids as $c_id) {
                                if (!empty($c_id)) {
                                    $this->db->insert('cbse_exam_timetable_classes', [
                                        'cbse_exam_timetable_id' => $new_tt_id,
                                        'class_id' => $c_id
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        
        foreach ($existing_tt_ids as $tt_id => $val) {
            if (!isset($submitted_tt_ids[$tt_id])) {
                $this->db->where('id', $tt_id);
                $this->db->delete('cbse_exam_timetable');
                $this->db->where('cbse_exam_timetable_id', $tt_id);
                $this->db->delete('cbse_exam_timetable_assessment_types');
                $this->db->where('cbse_exam_timetable_id', $tt_id);
                $this->db->delete('cbse_exam_timetable_classes');
            }
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /*
    This function is used to assign exam to student
    */
    public function addexamstudent($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && !empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('cbse_exam_students', $data);
            $message = UPDATE_RECORD_CONSTANT . " On cbse exam students id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
        } else {
            $this->db->insert('cbse_exam_students', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On cbse exam students id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
        }
        return $insert_id;
    }

    /*
    This function is used to get exam list base on current session
    */
    public function getexamlist()
    {

         $userdata = $this->customlib->getUserData();
            $role_id = $userdata["role_id"];
            $carray = array();
            $class_section_id=array();
            if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
                if ($userdata["class_teacher"] == 'yes') {
                    $classlist = $this->teacher_model->get_teacherrestricted_mode($userdata["id"]);
                }
				
                foreach ($classlist as $key => $value) {
                    $class_section=$this->teacher_model->get_teacherrestricted_modesections($userdata["id"], $value['id']);
                    foreach ($class_section as $key => $value_section) {
                        $class_section_id[]=$value_section['id'];
                    }
                } 
            }
           
			$this->db->select('cbse_exams.*,cbse_category.name as category_name,GROUP_CONCAT(CONCAT(classes.class, " - ", sections.section) ORDER BY classes.class ASC, sections.section ASC SEPARATOR ", ") AS class_sections,cbse_terms.name as term_name, (select count(cbse_exam_timetable.id) from cbse_exam_timetable where cbse_exam_timetable.cbse_exam_id = cbse_exams.id)  as subjectsincluded ', false)
            ->from('cbse_exams')
            ->join('cbse_exam_class_sections', 'cbse_exam_class_sections.cbse_exam_id=cbse_exams.id', 'left')
            ->join('class_sections', 'class_sections.id=cbse_exam_class_sections.class_section_id', 'left')
            ->join('classes', 'classes.id=class_sections.class_id', 'left')
            ->join('sections', 'sections.id=class_sections.section_id', 'left')
            ->join('cbse_terms', 'cbse_terms.id=cbse_exams.cbse_term_id', 'left')
            ->join('cbse_category', 'cbse_category.id=cbse_exams.cbse_category_id', 'left')
            ->group_by('cbse_exams.id')
            ->order_by('cbse_exams.id', 'desc');
			
        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
			if(!empty($class_section_id)){
				$this->db->where_in('cbse_exam_class_sections.class_section_id', $class_section_id);
			}
		}
        $this->db->where('session_id', $this->current_session);
        return $this->db->get()->result_array();
    }

    /*
    This function is used to get published exam list
    */
    public function getPublishexams()
    {
        return $this->db->select('cbse_exams.*')->from('cbse_exams')->where('session_id', $this->current_session)->where('cbse_exams.is_publish', '1')->order_by('cbse_exams.name', 'asc')->get()->result_array();
    }

    public function get_all_session_exams()
    {
        return $this->db->select('cbse_exams.*')->from('cbse_exams')->where('session_id', $this->current_session)->order_by('cbse_exams.name', 'asc')->get()->result_array();
    }



    public function getExamResultByExamIdByTemplate($cbse_exam_id, $cbse_template_id, $class_section_id)
    {
        $sql   = "SELECT  `cbse_exams`.*,cbse_student_template_rank.rank,cbse_student_template_rank.rank_percentage,cbse_template.gradeexam_id,cbse_template.remarkexam_id,cbse_terms.name as cbse_term_name,cbse_terms.term_code as cbse_term_code,cbse_exam_timetable.subject_id,cbse_exam_students.id as cbse_exam_student_id,cbse_exam_students.total_present_days,cbse_exam_students.remark,cbse_exam_assessment_types.name as cbse_exam_assessment_type_name,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_template_term_exams.weightage,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,cbse_exam_assessment_types.maximum_marks,cbse_student_subject_marks.id as `cbse_student_subject_marks_id`,cbse_student_subject_marks.marks,cbse_student_subject_marks.is_absent,cbse_student_subject_marks.note,cbse_student_subject_marks.cbse_exam_timetable_id,students.id as `student_id`,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,  students.dob ,students.current_address, students.previous_school,students.roll_no,
        students.guardian_is,students.parent_id,students.admission_no,
        students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,subjects.name as subject_name,subjects.code as `subject_code`,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,student_session.id as `student_session_id` FROM `cbse_template` INNER JOIN cbse_template_term_exams on cbse_template_term_exams.cbse_template_id=cbse_template.id INNER JOIN `cbse_exams` on cbse_exams.id=cbse_template_term_exams.cbse_exam_id INNER JOIN cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exams.cbse_exam_assessment_id INNER JOIN cbse_terms on cbse_terms.id=cbse_exams.cbse_term_id left join cbse_student_subject_marks on cbse_student_subject_marks.cbse_exam_timetable_id =cbse_exam_timetable.id and cbse_student_subject_marks.cbse_exam_student_id= cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id  INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id INNER join students on students.id =student_session.student_id  INNER JOIN subjects on subjects.id=cbse_exam_timetable.subject_id INNER join  classes on student_session.class_id = classes.id INNER join  sections on sections.id = student_session.section_id left join cbse_student_template_rank on cbse_student_template_rank.cbse_template_id=cbse_template.id and cbse_student_template_rank.student_session_id=student_session.id INNER join class_sections on  class_sections.class_id = classes.id and class_sections.section_id = sections.id  WHERE cbse_template.id=" . $this->db->escape($cbse_template_id) . " and cbse_exams.`id` = " . $this->db->escape($cbse_exam_id) . " and cbse_exams.session_id=" . $this->current_session . " and class_sections.id=" . $this->db->escape($class_section_id);

        $query = $this->db->query($sql);
        return $query->result();
    }



    public function getExamResultByExamId($cbse_exam_id)
    {
        $sql   = "SELECT  `cbse_exams`.*,cbse_student_exam_ranks.rank,cbse_terms.name as cbse_term_name,cbse_terms.term_code as cbse_term_code,cbse_exam_timetable.subject_id,cbse_exam_students.id as cbse_exam_student_id,cbse_exam_students.total_present_days,cbse_exam_students.remark,cbse_exam_assessment_types.name as cbse_exam_assessment_type_name,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,cbse_exam_assessment_types.maximum_marks,cbse_student_subject_marks.id as `cbse_student_subject_marks_id`,cbse_student_subject_marks.marks,cbse_student_subject_marks.is_absent,cbse_student_subject_marks.note,cbse_student_subject_marks.cbse_exam_timetable_id,students.id as `student_id`,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,  students.dob ,students.current_address, students.previous_school,students.roll_no,
        students.guardian_is,students.parent_id,students.admission_no,
        students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,subjects.name as subject_name,subjects.code as `subject_code`,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,student_session.id as `student_session_id`  FROM `cbse_exams` INNER JOIN cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exams.cbse_exam_assessment_id INNER JOIN cbse_terms on cbse_terms.id=cbse_exams.cbse_term_id left join cbse_student_subject_marks on cbse_student_subject_marks.cbse_exam_timetable_id =cbse_exam_timetable.id and cbse_student_subject_marks.cbse_exam_student_id= cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id INNER join students on students.id =student_session.student_id INNER JOIN subjects on subjects.id=cbse_exam_timetable.subject_id INNER join classes on student_session.class_id = classes.id INNER join sections on sections.id = student_session.section_id left join cbse_student_exam_ranks on cbse_student_exam_ranks.student_session_id = student_session.id and cbse_student_exam_ranks.cbse_exam_id=" . $cbse_exam_id . " WHERE cbse_exams.`id` = " . $this->db->escape($cbse_exam_id) . " and cbse_exams.session_id=" . $this->current_session . " order by cbse_student_exam_ranks.rank asc";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getStudentExamResultByExamIdAndAdmissionNo($cbse_exam_id, $admission_no)
    {
        $sql   = "SELECT `cbse_exams`.*,cbse_student_exam_ranks.rank,cbse_terms.name as cbse_term_name,cbse_terms.term_code as cbse_term_code,cbse_exam_timetable.subject_id,cbse_exam_students.id as cbse_exam_student_id,cbse_exam_students.total_present_days,cbse_exam_assessment_types.name as cbse_exam_assessment_type_name,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,cbse_exam_assessment_types.maximum_marks,cbse_exam_assessment_types.maximum_marks,cbse_student_subject_marks.id as `cbse_student_subject_marks_id`,cbse_student_subject_marks.marks,cbse_student_subject_marks.is_absent,cbse_student_subject_marks.note,cbse_student_subject_marks.cbse_exam_timetable_id,students.id as `student_id`,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,  students.dob ,students.current_address, students.previous_school,students.roll_no,
           students.guardian_is,students.parent_id,students.admission_no,
           students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,subjects.name as subject_name,subjects.code as `subject_code`,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,student_session.id as `student_session_id` ,cbse_exam_students.remark FROM `cbse_exams` INNER JOIN cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exams.cbse_exam_assessment_id INNER JOIN cbse_terms on cbse_terms.id=cbse_exams.cbse_term_id left join cbse_student_subject_marks on cbse_student_subject_marks.cbse_exam_timetable_id =cbse_exam_timetable.id and cbse_student_subject_marks.cbse_exam_student_id= cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id  INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id INNER join students on students.id =student_session.student_id  INNER JOIN subjects on subjects.id=cbse_exam_timetable.subject_id INNER join  classes on student_session.class_id = classes.id INNER join  sections on sections.id = student_session.section_id left join cbse_student_exam_ranks on cbse_student_exam_ranks.student_session_id = student_session.id and cbse_student_exam_ranks.cbse_exam_id=cbse_exams.id WHERE cbse_exams.`id` = " . $this->db->escape($cbse_exam_id) . " and cbse_exams.session_id=" . $this->current_session . " and students.admission_no = " . $this->db->escape($admission_no);

        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Overlay per-subject max marks (cbse_exam_timetable_assessment_types.maximum_marks)
     * onto report/marksheet result rows. Each row carries cbse_exam_timetable_id and
     * cbse_exam_assessment_type_id; the junction is unique per pair, so this is a safe
     * 1:1 lookup that never changes row counts. Rows without an override keep the
     * assessment-type default already selected by the query.
     */
    private function _apply_max_override(&$rows)
    {
        if (empty($rows)) {
            return;
        }
        $tt_ids = array();
        foreach ($rows as $r) {
            if (!empty($r->cbse_exam_timetable_id)) {
                $tt_ids[(int)$r->cbse_exam_timetable_id] = true;
            }
        }
        if (empty($tt_ids)) {
            return;
        }
        $overrides = $this->db->select('cbse_exam_timetable_id, cbse_exam_assessment_type_id, maximum_marks')
            ->from('cbse_exam_timetable_assessment_types')
            ->where_in('cbse_exam_timetable_id', array_keys($tt_ids))
            ->where('maximum_marks IS NOT NULL', null, false)
            ->get()->result_array();
        if (empty($overrides)) {
            return;
        }
        $map = array();
        foreach ($overrides as $o) {
            $map[$o['cbse_exam_timetable_id']][$o['cbse_exam_assessment_type_id']] = $o['maximum_marks'];
        }
        foreach ($rows as $r) {
            if (isset($r->cbse_exam_timetable_id, $r->cbse_exam_assessment_type_id)
                && isset($map[$r->cbse_exam_timetable_id][$r->cbse_exam_assessment_type_id])) {
                $r->maximum_marks = $map[$r->cbse_exam_timetable_id][$r->cbse_exam_assessment_type_id];
            }
        }
    }

    public function getStudentExamResultByExamId($cbse_template_id, $cbse_exam_id, $students)
    {
        $students = implode(', ', array_map(function ($val) {
            return sprintf("'%s'", $val);
        }, $students));

        $sql   = "SELECT  `cbse_exams`.*,cbse_exam_student_subject_rank.rank as subject_rank ,cbse_student_template_rank.rank,cbse_student_template_rank.rank_percentage,cbse_template.gradeexam_id,cbse_template.remarkexam_id,cbse_terms.name as cbse_term_name,cbse_terms.term_code as cbse_term_code,cbse_exam_timetable.subject_id,cbse_exam_students.id as cbse_exam_student_id,cbse_exam_students.total_present_days,cbse_exam_students.remark,cbse_exam_assessment_types.name as cbse_exam_assessment_type_name,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_template_term_exams.weightage,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,cbse_exam_assessment_types.maximum_marks,cbse_student_subject_marks.id as `cbse_student_subject_marks_id`,cbse_student_subject_marks.marks,cbse_student_subject_marks.is_absent,cbse_student_subject_marks.note,cbse_student_subject_marks.cbse_exam_timetable_id,students.id as `student_id`,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,  students.dob ,students.current_address, students.previous_school,students.roll_no,
        students.guardian_is,students.parent_id,students.admission_no,
        students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,subjects.name as subject_name,subjects.code as `subject_code`,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,student_session.id as `student_session_id` FROM `cbse_template` INNER JOIN cbse_template_term_exams on cbse_template_term_exams.cbse_template_id=cbse_template.id INNER JOIN `cbse_exams` on cbse_exams.id=cbse_template_term_exams.cbse_exam_id INNER JOIN cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exams.cbse_exam_assessment_id INNER JOIN cbse_terms on cbse_terms.id=cbse_exams.cbse_term_id left join cbse_student_subject_marks on cbse_student_subject_marks.cbse_exam_timetable_id =cbse_exam_timetable.id and cbse_student_subject_marks.cbse_exam_student_id= cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id  INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id INNER join students on students.id =student_session.student_id  INNER JOIN subjects on subjects.id=cbse_exam_timetable.subject_id INNER join  classes on student_session.class_id = classes.id INNER join  sections on sections.id = student_session.section_id left join cbse_student_template_rank on cbse_student_template_rank.cbse_template_id=cbse_template.id and cbse_student_template_rank.student_session_id=student_session.id LEFT JOIN cbse_exam_student_subject_rank on cbse_exam_student_subject_rank.cbse_template_id=cbse_template.id and cbse_exam_student_subject_rank.student_session_id=student_session.id and cbse_exam_student_subject_rank.subject_id=subjects.id WHERE cbse_template.id=" . $this->db->escape($cbse_template_id) . " and cbse_exams.`id` = " . $this->db->escape($cbse_exam_id) . " and cbse_exams.session_id=" . $this->current_session . " and student_session.id in (" . $students . ")";

        $query = $this->db->query($sql);
        $rows = $query->result();
        $this->_apply_max_override($rows);
        return $rows;
    }

    public function getStudentResultByExamId($cbse_exam_id, $students)
    {
        $students = implode(', ', array_map(function ($val) {
            return sprintf("'%s'", $val);
        }, $students));

        $sql   = "SELECT  `cbse_exams`.*,cbse_student_exam_ranks.rank,cbse_terms.name as cbse_term_name,cbse_terms.term_code as cbse_term_code,cbse_exam_timetable.subject_id,cbse_exam_students.id as cbse_exam_student_id,cbse_exam_students.total_present_days,cbse_exam_students.remark,cbse_exam_assessment_types.name as cbse_exam_assessment_type_name,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,cbse_exam_assessment_types.maximum_marks,cbse_student_subject_marks.id as `cbse_student_subject_marks_id`,cbse_student_subject_marks.marks,cbse_student_subject_marks.is_absent,cbse_student_subject_marks.note,cbse_student_subject_marks.cbse_exam_timetable_id,students.id as `student_id`,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,  students.dob ,students.current_address, students.previous_school,students.roll_no,
        students.guardian_is,students.parent_id,students.admission_no,
        students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,subjects.name as subject_name,subjects.code as `subject_code`,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,student_session.id as `student_session_id`  FROM `cbse_exams` INNER JOIN cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exams.cbse_exam_assessment_id INNER JOIN cbse_terms on cbse_terms.id=cbse_exams.cbse_term_id left join cbse_student_subject_marks on cbse_student_subject_marks.cbse_exam_timetable_id =cbse_exam_timetable.id and cbse_student_subject_marks.cbse_exam_student_id= cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id INNER join students on students.id =student_session.student_id INNER JOIN subjects on subjects.id=cbse_exam_timetable.subject_id INNER join classes on student_session.class_id = classes.id INNER join sections on sections.id = student_session.section_id left join cbse_student_exam_ranks on cbse_student_exam_ranks.student_session_id = student_session.id and cbse_student_exam_ranks.cbse_exam_id=" . $cbse_exam_id . " WHERE cbse_exams.`id` = " . $this->db->escape($cbse_exam_id) . " and cbse_exams.session_id=" . $this->current_session . " and student_session.id in (" . $students . ")";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function getStudentResultByTemplateId($cbse_template_id, $class_section_id)
    {
        $sql   = "SELECT  `cbse_exams`.*,cbse_student_template_rank.rank,cbse_template.gradeexam_id,cbse_template.remarkexam_id,cbse_terms.name as cbse_term_name,cbse_terms.term_code as cbse_term_code,cbse_exam_timetable.subject_id,cbse_exam_students.id as cbse_exam_student_id,cbse_exam_students.total_present_days,cbse_exam_students.remark,cbse_exam_assessment_types.name as cbse_exam_assessment_type_name,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_template_term_exams.weightage,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,cbse_exam_assessment_types.maximum_marks,cbse_student_subject_marks.id as `cbse_student_subject_marks_id`,cbse_student_subject_marks.marks,cbse_student_subject_marks.is_absent,cbse_student_subject_marks.note,cbse_student_subject_marks.cbse_exam_timetable_id,cbse_exam_timetable_assessment_types.id as cbse_exam_timetable_assessment_type_id,students.id as `student_id`,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,  students.dob ,students.current_address, students.previous_school,students.roll_no,
            students.guardian_is,students.parent_id,students.admission_no,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,subjects.name as subject_name,subjects.code as `subject_code`,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,student_session.id as `student_session_id` FROM `cbse_template` INNER JOIN cbse_template_term_exams on cbse_template_term_exams.cbse_template_id=cbse_template.id INNER JOIN `cbse_exams` on cbse_exams.id=cbse_template_term_exams.cbse_exam_id INNER JOIN cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exams.cbse_exam_assessment_id INNER JOIN cbse_terms on cbse_terms.id=cbse_exams.cbse_term_id left join cbse_student_subject_marks on cbse_student_subject_marks.cbse_exam_timetable_id =cbse_exam_timetable.id and cbse_student_subject_marks.cbse_exam_student_id= cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id  INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id INNER join students on students.id =student_session.student_id  INNER JOIN subjects on subjects.id=cbse_exam_timetable.subject_id INNER join  classes on student_session.class_id = classes.id INNER join  sections on sections.id = student_session.section_id INNER join class_sections on  class_sections.class_id = classes.id and class_sections.section_id = sections.id left join cbse_student_template_rank on cbse_student_template_rank.cbse_template_id=cbse_template.id and cbse_student_template_rank.student_session_id=student_session.id LEFT join cbse_exam_timetable_assessment_types on cbse_exam_timetable_assessment_types.cbse_exam_timetable_id= cbse_exam_timetable.id and cbse_exam_timetable_assessment_types.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id WHERE cbse_template.id=" . $this->db->escape($cbse_template_id) . " and class_sections.id=" . $this->db->escape($class_section_id);

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getStudentExamResultByTemplateId($cbse_template_id, $students)
    {
        $students = implode(', ', array_map(function ($val) {
            return sprintf("'%s'", $val);
        }, $students));
        $sql   = "SELECT  `cbse_exams`.*,cbse_exam_student_subject_rank.rank as subject_rank ,cbse_student_template_rank.rank,cbse_student_template_rank.rank_percentage,cbse_template.gradeexam_id,cbse_template.remarkexam_id,cbse_terms.name as cbse_term_name,cbse_terms.term_code as cbse_term_code,cbse_exam_timetable.subject_id,cbse_exam_students.id as cbse_exam_student_id,cbse_exam_students.total_present_days,cbse_exam_students.remark,cbse_exam_assessment_types.name as cbse_exam_assessment_type_name,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_template_term_exams.weightage,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,cbse_exam_assessment_types.maximum_marks,cbse_student_subject_marks.id as `cbse_student_subject_marks_id`,cbse_student_subject_marks.marks,cbse_student_subject_marks.is_absent,cbse_student_subject_marks.note,cbse_student_subject_marks.cbse_exam_timetable_id,students.id as `student_id`,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,  students.dob ,students.current_address, students.previous_school,students.roll_no,
            students.guardian_is,students.parent_id,students.admission_no,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,subjects.name as subject_name,subjects.code as `subject_code`,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,student_session.id as `student_session_id`,cbse_exam_timetable_assessment_types.id as cbse_exam_timetable_assessment_type_id FROM `cbse_template` INNER JOIN cbse_template_term_exams on cbse_template_term_exams.cbse_template_id=cbse_template.id INNER JOIN `cbse_exams` on cbse_exams.id=cbse_template_term_exams.cbse_exam_id INNER JOIN cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exams.cbse_exam_assessment_id INNER JOIN cbse_terms on cbse_terms.id=cbse_exams.cbse_term_id left join cbse_student_subject_marks on cbse_student_subject_marks.cbse_exam_timetable_id =cbse_exam_timetable.id and cbse_student_subject_marks.cbse_exam_student_id= cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id  INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id INNER join students on students.id =student_session.student_id  INNER JOIN subjects on subjects.id=cbse_exam_timetable.subject_id INNER join  classes on student_session.class_id = classes.id INNER join  sections on sections.id = student_session.section_id left join cbse_student_template_rank on cbse_student_template_rank.cbse_template_id=cbse_template.id and cbse_student_template_rank.student_session_id=student_session.id LEFT JOIN cbse_exam_student_subject_rank on cbse_exam_student_subject_rank.cbse_template_id=cbse_template.id and cbse_exam_student_subject_rank.student_session_id=student_session.id and cbse_exam_student_subject_rank.subject_id=subjects.id LEFT join cbse_exam_timetable_assessment_types on cbse_exam_timetable_assessment_types.cbse_exam_timetable_id= cbse_exam_timetable.id and cbse_exam_timetable_assessment_types.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id WHERE cbse_template.id=" . $this->db->escape($cbse_template_id) . " and student_session.id in (" . $students . ") order by id asc";

            

        $query = $this->db->query($sql);
        $rows = $query->result();
        $this->_apply_max_override($rows);
        return $rows;
    }

    public function getResultTermwiseByTemplateIdWithSelectedTerm($cbse_template_id, $class_section_id)
    {
        $sql   = "SELECT  `cbse_exams`.*,cbse_template.gradeexam_id,cbse_student_template_rank.rank,cbse_template.remarkexam_id,cbse_template_terms.weightage as `cbse_template_terms_weightage`,cbse_terms.name as cbse_term_name,cbse_terms.term_code as cbse_term_code,cbse_exam_timetable.subject_id,cbse_exam_students.id as cbse_exam_student_id,cbse_exam_students.total_present_days,cbse_exam_students.remark,cbse_exam_assessment_types.name as cbse_exam_assessment_type_name,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,cbse_exam_assessment_types.maximum_marks,cbse_exam_assessment_types.maximum_marks,cbse_student_subject_marks.id as `cbse_student_subject_marks_id`,cbse_student_subject_marks.marks,cbse_student_subject_marks.is_absent,cbse_student_subject_marks.note,cbse_student_subject_marks.cbse_exam_timetable_id,students.id as `student_id`,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,  students.dob ,students.current_address, students.previous_school,students.roll_no,
            students.guardian_is,students.parent_id,students.admission_no,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,subjects.name as subject_name,subjects.code as `subject_code`,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,student_session.id as `student_session_id`,cbse_template_terms.weightage,cbse_exam_timetable_assessment_types.id as cbse_exam_timetable_assessment_type_id FROM `cbse_template` INNER JOIN cbse_template_terms on cbse_template_terms.cbse_template_id=cbse_template.id INNER JOIN cbse_template_term_exams on cbse_template_term_exams.cbse_template_term_id=cbse_template_terms.id INNER JOIN `cbse_exams` on cbse_exams.id=cbse_template_term_exams.cbse_exam_id INNER JOIN cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exams.cbse_exam_assessment_id INNER JOIN cbse_terms on cbse_terms.id=cbse_exams.cbse_term_id left join cbse_student_subject_marks on cbse_student_subject_marks.cbse_exam_timetable_id =cbse_exam_timetable.id and cbse_student_subject_marks.cbse_exam_student_id= cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id  INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id INNER join students on students.id =student_session.student_id  INNER JOIN subjects on subjects.id=cbse_exam_timetable.subject_id INNER join  classes on student_session.class_id = classes.id INNER join  sections on sections.id = student_session.section_id  INNER join class_sections on  class_sections.class_id = classes.id and class_sections.section_id = sections.id left join cbse_student_template_rank on cbse_student_template_rank.cbse_template_id=cbse_template.id and cbse_student_template_rank.student_session_id=student_session.id LEFT join cbse_exam_timetable_assessment_types on cbse_exam_timetable_assessment_types.cbse_exam_timetable_id= cbse_exam_timetable.id and cbse_exam_timetable_assessment_types.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id WHERE cbse_template.id=" . $this->db->escape($cbse_template_id) . " and class_sections.id=" . $this->db->escape($class_section_id) . " order by cbse_student_template_rank.rank asc";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getResultTermwiseByTemplateId($cbse_template_id, $class_section_id)
    {
        $sql   = "SELECT  `cbse_exams`.*,cbse_student_template_rank.rank,cbse_template.gradeexam_id,cbse_template.remarkexam_id,cbse_template_terms.weightage as `cbse_template_terms_weightage`,cbse_terms.name as cbse_term_name,cbse_terms.term_code as cbse_term_code,cbse_exam_timetable.subject_id,cbse_exam_students.id as cbse_exam_student_id,cbse_exam_students.total_present_days,cbse_exam_students.remark,cbse_exam_assessment_types.name as cbse_exam_assessment_type_name,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,cbse_exam_assessment_types.maximum_marks,cbse_exam_assessment_types.maximum_marks,cbse_student_subject_marks.id as `cbse_student_subject_marks_id`,cbse_student_subject_marks.marks,cbse_student_subject_marks.is_absent,cbse_student_subject_marks.note,cbse_student_subject_marks.cbse_exam_timetable_id,students.id as `student_id`,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast,  students.dob ,students.current_address, students.previous_school,students.roll_no,
            students.guardian_is,students.parent_id,students.admission_no,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,subjects.name as subject_name,subjects.code as `subject_code`,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,student_session.id as `student_session_id`,cbse_template_terms.weightage,cbse_exam_timetable_assessment_types.id as cbse_exam_timetable_assessment_type_id FROM `cbse_template` INNER JOIN cbse_template_terms on cbse_template_terms.cbse_template_id=cbse_template.id INNER JOIN cbse_template_term_exams on cbse_template_term_exams.cbse_template_term_id=cbse_template_terms.id INNER JOIN `cbse_exams` on cbse_exams.id=cbse_template_term_exams.cbse_exam_id INNER JOIN cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exams.cbse_exam_assessment_id INNER JOIN cbse_terms on cbse_terms.id=cbse_exams.cbse_term_id left join cbse_student_subject_marks on cbse_student_subject_marks.cbse_exam_timetable_id =cbse_exam_timetable.id and cbse_student_subject_marks.cbse_exam_student_id= cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id  INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id INNER join students on students.id =student_session.student_id  INNER JOIN subjects on subjects.id=cbse_exam_timetable.subject_id INNER join  classes on student_session.class_id = classes.id INNER join  sections on sections.id = student_session.section_id INNER join class_sections on  class_sections.class_id = classes.id and class_sections.section_id = sections.id left join cbse_student_template_rank on cbse_student_template_rank.cbse_template_id=cbse_template.id and cbse_student_template_rank.student_session_id=student_session.id LEFT join cbse_exam_timetable_assessment_types on cbse_exam_timetable_assessment_types.cbse_exam_timetable_id= cbse_exam_timetable.id and cbse_exam_timetable_assessment_types.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id WHERE cbse_template.id=" . $this->db->escape($cbse_template_id) . " and class_sections.id=" . $this->db->escape($class_section_id);

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getStudentExamResultTermwiseByTemplateId($cbse_template_id, $students)
    {
        $students = implode(', ', array_map(function ($val) {
            return sprintf("'%s'", $val);
        }, $students));

        $sql   = "SELECT  `cbse_exams`.*,cbse_student_template_rank.rank,cbse_student_template_rank.rank_percentage,cbse_template.id as `cbse_template_id`,cbse_template.gradeexam_id,cbse_template.remarkexam_id,cbse_template_terms.weightage as `cbse_template_terms_weightage`,cbse_terms.name as cbse_term_name,cbse_terms.term_code as cbse_term_code,cbse_exam_timetable.subject_id,cbse_exam_students.id as cbse_exam_student_id,cbse_exam_students.total_present_days,cbse_exam_students.remark,cbse_exam_assessment_types.name as cbse_exam_assessment_type_name,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,cbse_exam_assessment_types.maximum_marks,cbse_exam_assessment_types.maximum_marks,cbse_student_subject_marks.id as `cbse_student_subject_marks_id`,cbse_student_subject_marks.marks,cbse_student_subject_marks.is_absent,cbse_student_subject_marks.note,cbse_student_subject_marks.cbse_exam_timetable_id,students.id as `student_id`,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note as student_note, students.religion, students.cast,  students.dob ,students.current_address, students.previous_school,students.roll_no,
            students.guardian_is,students.parent_id,students.admission_no,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,subjects.name as subject_name,subjects.code as `subject_code`,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,student_session.id as `student_session_id`,cbse_template_terms.weightage,cbse_exam_student_subject_rank.rank as subject_rank,cbse_exam_timetable_assessment_types.id as cbse_exam_timetable_assessment_type_id FROM `cbse_template` 
            INNER JOIN cbse_template_terms on cbse_template_terms.cbse_template_id=cbse_template.id 
            INNER JOIN cbse_template_term_exams on cbse_template_term_exams.cbse_template_term_id=cbse_template_terms.id 
            INNER JOIN `cbse_exams` on cbse_exams.id=cbse_template_term_exams.cbse_exam_id 
            INNER JOIN cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id 
            INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id 
            INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exams.cbse_exam_assessment_id 
            INNER JOIN cbse_terms on cbse_terms.id=cbse_exams.cbse_term_id 
            left join cbse_student_subject_marks on cbse_student_subject_marks.cbse_exam_timetable_id =cbse_exam_timetable.id and cbse_student_subject_marks.cbse_exam_student_id= cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id  
            INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id 
            INNER join students on students.id =student_session.student_id  
            INNER JOIN subjects on subjects.id=cbse_exam_timetable.subject_id 
            INNER join  classes on student_session.class_id = classes.id
            INNER join  sections on sections.id = student_session.section_id 
            left join cbse_student_template_rank on cbse_student_template_rank.cbse_template_id=cbse_template.id and cbse_student_template_rank.student_session_id=student_session.id 
            LEFT JOIN cbse_exam_student_subject_rank on cbse_exam_student_subject_rank.cbse_template_id=cbse_template.id and cbse_exam_student_subject_rank.student_session_id=student_session.id and cbse_exam_student_subject_rank.subject_id=subjects.id 
            LEFT join cbse_exam_timetable_assessment_types on cbse_exam_timetable_assessment_types.cbse_exam_timetable_id= cbse_exam_timetable.id and cbse_exam_timetable_assessment_types.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id 
            WHERE cbse_template.id=" . $this->db->escape($cbse_template_id) . " and student_session.id in (" . $students . ") order by cbse_student_template_rank.rank asc";

        $query = $this->db->query($sql);
        $rows = $query->result();
        $this->_apply_max_override($rows);
        return $rows;
    }

    public function getTemplateAssessment($cbse_template_id)
    {
        $sql = "SELECT cbse_template_term_exams.*,cbse_exams.cbse_exam_assessment_id,cbse_exam_assessments.name as `cbse_exam_assessment_name`,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_exams.name as `exam_name`,cbse_exam_assessment_types.name,cbse_exam_assessment_types.code,cbse_exam_assessment_types.maximum_marks ,cbse_exam_assessment_types.pass_percentage,cbse_template_terms.cbse_term_id,cbse_template_terms.weightage as `cbse_template_term_weightage`   FROM `cbse_template` INNER JOIN cbse_template_terms on cbse_template_terms.cbse_template_id=cbse_template.id INNER JOIN cbse_template_term_exams on cbse_template_term_exams.cbse_template_term_id=cbse_template_terms.id INNER JOIN `cbse_exams` on cbse_exams.id=cbse_template_term_exams.cbse_exam_id INNER JOIN cbse_exam_assessments on cbse_exam_assessments.id=cbse_exams.cbse_exam_assessment_id INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exam_assessments.id WHERE cbse_template.id=" . $this->db->escape($cbse_template_id);

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getTemplateSingleExam($cbse_template_id)
    {
        $sql = "SELECT cbse_template_term_exams.*  FROM `cbse_template_term_exams`  WHERE cbse_template_term_exams.cbse_template_id=" . $this->db->escape($cbse_template_id);
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function searchTermStudentsByClass($cbse_observation_term_id, $class_id, $section_id)
    {
        $section_condition = "";
        if ($section_id != "") {
            $section_condition = "  and student_session.section_id=" . $this->db->escape($section_id);
        }

        $sql = "SELECT cbse_exams.*,cbse_exam_students.student_session_id,student_session.class_id,student_session.section_id, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id` as `student_id`, `students`.`admission_no`, `students`.`roll_no`, `students`.`admission_date`, `students`.`firstname`,`students`.`middlename`, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, '') as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender`,cbse_observation_term_student_subparameter.id as cbse_observation_term_student_subparameter_id,cbse_observation_subparameter.id as cbse_observation_subparameter_id,cbse_observation_terms.id as cbse_observation_term_id,cbse_observation_parameters.name as cbse_observation_parameter_name,cbse_observation_subparameter.cbse_exam_observation_id,cbse_observation_subparameter.cbse_observation_parameter_id,cbse_observation_term_student_subparameter.obtain_marks FROM `cbse_exams` INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id INNER join `students` ON `student_session`.`student_id` = `students`.`id`  INNER JOIN cbse_observation_terms on cbse_observation_terms.cbse_term_id=cbse_exams.cbse_term_id and cbse_observation_terms.id= " . $this->db->escape($cbse_observation_term_id) . " JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN cbse_observation_subparameter on cbse_observation_subparameter.cbse_exam_observation_id =cbse_observation_terms.cbse_exam_observation_id LEFT JOIN cbse_observation_term_student_subparameter on cbse_observation_term_student_subparameter.cbse_ovservation_term_id=cbse_observation_terms.id and cbse_observation_term_student_subparameter.cbse_observation_subparameter_id = cbse_observation_subparameter.id and  cbse_observation_term_student_subparameter.student_session_id=cbse_exam_students.student_session_id inner join cbse_observation_parameters on cbse_observation_parameters.id =cbse_observation_subparameter.cbse_observation_parameter_id where cbse_exams.session_id=" . $this->current_session . " and student_session.class_id=" . $this->db->escape($class_id) . $section_condition . " GROUP by cbse_exam_students.student_session_id order by cbse_observation_subparameter.id desc";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function searchStudentOverservationMarks($cbse_observation_term_id, $class_id, $section_id)
    {
        $section_condition = "";
        if ($section_id != "") {
            $section_condition = "  and student_session.section_id=" . $this->db->escape($section_id);
        }

        $sql = "SELECT cbse_exams.*,cbse_exam_students.student_session_id,student_session.class_id,student_session.section_id, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id` as `student_id`, `students`.`admission_no`, `students`.`roll_no`, `students`.`admission_date`, `students`.`firstname`,`students`.`middlename`, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, '') as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender`,cbse_observation_term_student_subparameter.id as cbse_observation_term_student_subparameter_id,cbse_observation_subparameter.id as cbse_observation_subparameter_id,cbse_observation_terms.id as cbse_observation_term_id,cbse_observation_parameters.name as cbse_observation_parameter_name,cbse_observation_subparameter.cbse_exam_observation_id,cbse_observation_subparameter.cbse_observation_parameter_id,cbse_observation_term_student_subparameter.obtain_marks FROM `cbse_exams` INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id INNER join `students` ON `student_session`.`student_id` = `students`.`id`  INNER JOIN cbse_observation_terms on cbse_observation_terms.cbse_term_id=cbse_exams.cbse_term_id and cbse_observation_terms.id= " . $this->db->escape($cbse_observation_term_id) . " JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN cbse_observation_subparameter on cbse_observation_subparameter.cbse_exam_observation_id =cbse_observation_terms.cbse_exam_observation_id LEFT JOIN cbse_observation_term_student_subparameter on cbse_observation_term_student_subparameter.cbse_ovservation_term_id=cbse_observation_terms.id and cbse_observation_term_student_subparameter.cbse_observation_subparameter_id = cbse_observation_subparameter.id and  cbse_observation_term_student_subparameter.student_session_id=cbse_exam_students.student_session_id inner join cbse_observation_parameters on cbse_observation_parameters.id =cbse_observation_subparameter.cbse_observation_parameter_id where cbse_exams.session_id=" . $this->current_session . " and student_session.class_id=" . $this->db->escape($class_id) . $section_condition . " order by cbse_observation_subparameter.id desc";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getTemplateAssessmentWithoutTerm($cbse_template_id)
    {
        $sql = "SELECT cbse_template_term_exams.*,cbse_exams.cbse_exam_assessment_id,cbse_exam_assessments.name as `cbse_exam_assessment_name`,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_exams.name as `exam_name`,cbse_exam_assessment_types.name,cbse_exam_assessment_types.code,cbse_exam_assessment_types.maximum_marks ,cbse_exam_assessment_types.pass_percentage  FROM `cbse_template`  INNER JOIN cbse_template_term_exams on cbse_template_term_exams.cbse_template_id=cbse_template.id INNER JOIN `cbse_exams` on cbse_exams.id=cbse_template_term_exams.cbse_exam_id INNER JOIN cbse_exam_assessments on cbse_exam_assessments.id=cbse_exams.cbse_exam_assessment_id INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exam_assessments.id WHERE cbse_template.id=" . $this->db->escape($cbse_template_id);
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getexams()
    {
        return $this->db->select('*')->get('cbse_exams')->result_array();
    }

    public function get()
    {
        return $this->db->select('cbse_exams.cbse_term_id,cbse_terms.name as term_name')->join('cbse_terms', 'cbse_terms.id=cbse_exams.cbse_term_id')->group_by('cbse_term_id')->get('cbse_exams')->result_array();
    }

    public function get_editdetails($id)
    {
        $result['list'] = $this->db->select('*')->from('cbse_exams')->where('cbse_term_id', $id)->get()->result_array();
        return $result;
    }

    public function get_exambyId($id)
    {
        $result = $this->db->select('*')->from('cbse_exams')->where('id', $id)->get()->row_array();
        return $result;
    }

    public function getExamWithGrade($id)
    {
        $result = $this->db->select('*')->from('cbse_exams')->where('id', $id)->get()->row();
        $result->grades = $this->db->select('*')->from('cbse_exam_grades_range')->where('cbse_exam_grade_id', $result->cbse_exam_grade_id)->get()->result();
        return $result;
    }

    public function remove_exam($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('cbse_exams');
        $message = DELETE_RECORD_CONSTANT . " On cbse exams id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $id;
        }
    }

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('cbse_term_id', $id);
        $this->db->delete('cbse_exams');
        $message = DELETE_RECORD_CONSTANT . " On cbse exams id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $id;
        }
    }

    public function add_exam_class_section($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert('cbse_exam_class_sections', $data);
        $insert_id = $this->db->insert_id();
        $message = INSERT_RECORD_CONSTANT . " On cbse exam class sections id " . $insert_id;
        $action = "Insert";
        $record_id = $insert_id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function searchExamStudents($exam_class_section, $exam_id)
    {
        $userdata = $this->customlib->getUserData();
            $role_id = $userdata["role_id"];
            $carray = array();
            $class_section_id=array();
            if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
                if ($userdata["class_teacher"] == 'yes') {
                    $classlist = $this->teacher_model->get_teacherrestricted_mode($userdata["id"]);
                }
                foreach ($classlist as $key => $value) {
                    $class_section=$this->teacher_model->get_teacherrestricted_modesections($userdata["id"], $value['id']);
                    $class_section_id[]=$class_section[0]['id'];
                }
            }

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,IFNULL(cbse_exam_students.id, 0) as exam_student_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('class_sections', 'class_sections.class_id=student_session.class_id and class_sections.section_id=student_session.section_id');
        $this->db->join('cbse_exam_students', 'cbse_exam_students.cbse_exam_id="' . $exam_id . '" and cbse_exam_students.student_session_id=student_session.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
         if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
        if(!empty($class_section_id)){
             $this->db->where_in('class_sections.id', $class_section_id);
        }
    }
        $this->db->where('students.is_active', 'yes');
        $this->db->where_in('class_sections.id', $exam_class_section);
        $this->db->order_by('students.admission_no');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getExamStudents($exam_id)
    {
        $this->db->select('cbse_student_exam_ranks.rank,classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');

        $this->db->join('class_sections', 'class_sections.class_id=student_session.class_id and class_sections.section_id=student_session.section_id');
        $this->db->join('cbse_exam_students', 'cbse_exam_students.student_session_id= student_session.id and cbse_exam_students.cbse_exam_id=' . $exam_id);
        $this->db->join('cbse_student_exam_ranks', 'cbse_student_exam_ranks.student_session_id = student_session.id and cbse_student_exam_ranks.cbse_exam_id=' . $exam_id, 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $query = $this->db->get();
        return $query->result();
    }

    public function getExamByGrade($cbse_term_id)
    {
        $exams = $this->db->select('*')->from('cbse_exams')->where('cbse_term_id', $cbse_term_id)->get()->result();
        return $exams;
    }

    public function getExamClasses($exam_id)
    {
        $this->db->select('classes.id as class_id, classes.class');
        $this->db->from('cbse_exam_class_sections');
        $this->db->join('class_sections', 'class_sections.id = cbse_exam_class_sections.class_section_id');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->where('cbse_exam_id', $exam_id);
        $this->db->group_by('classes.id');
        return $this->db->get()->result_array();
    }

    public function getClassByExam($exam_id)
    {
        $this->db->select('cbse_exam_class_sections.*,class_sections.class_id,classes.class');
        $this->db->from('cbse_exam_class_sections');
        $this->db->join('class_sections', 'class_sections.id = cbse_exam_class_sections.class_section_id');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->where('cbse_exam_id', $exam_id);
        $this->db->group_by('classes.id');
        $exams = $this->db->get();
        $result = $exams->result();

        return $result;
    }

    public function getExamSectionByClass($exam_id, $class_id)
    {
        $this->db->select('cbse_exam_class_sections.*,class_sections.class_id,classes.class,sections.id as `section_id`,sections.section');
        $this->db->from('cbse_exam_class_sections');
        $this->db->join('class_sections', 'class_sections.id = cbse_exam_class_sections.class_section_id');
        $this->db->join('classes', 'classes.id = class_sections.class_id and classes.id = ' . $class_id);
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->where('cbse_exam_id', $exam_id);
        $exams = $this->db->get();
        $result = $exams->result();
        return $result;
    }

    public function get_class_sectionbyexamid($id)
    {
        $class_sections = $this->db->select('*')->from('cbse_exam_class_sections')->where('cbse_exam_id', $id)->get()->result_array();
        foreach ($class_sections as $key => $value) {
            $class_section[] = $value['class_section_id'];
        }
        return $class_section;
    }

    public function add_student($insert_array, $exam_id, $all_students)
    {
        $delete_array = array();
        $new_inserted_array = array('0');
        $this->db->trans_begin();
        if (!empty($insert_array)) {
            foreach ($insert_array as $insert_key => $insert_value) {
                $this->insert($insert_value);
                $new_inserted_array[] = $insert_value['student_session_id'];
            }
        }

        if (!empty($new_inserted_array)) {
            $this->db->where('cbse_exam_id', $exam_id);
            $this->db->where_not_in('student_session_id', $new_inserted_array);
            $this->db->delete('cbse_exam_students');
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function insert($insert_value)
    {
        $this->db->where('cbse_exam_id', $insert_value['cbse_exam_id']);
        $this->db->where('student_session_id', $insert_value['student_session_id']);
        $q = $this->db->get('cbse_exam_students');

        if ($q->num_rows() == 0) {
            $this->db->insert('cbse_exam_students', $insert_value);
        }
        return true;
    }

    public function getexamdetails($exam_id)
    {
        return $this->db->select('cbse_exams.*, GROUP_CONCAT(CONCAT(classes.class, " - ", sections.section) ORDER BY classes.class ASC, sections.section ASC SEPARATOR ", ") AS class_sections, cbse_terms.name as term_name', false)->from('cbse_exams')->join('cbse_exam_class_sections', 'cbse_exam_class_sections.cbse_exam_id=cbse_exams.id')->join('class_sections', 'class_sections.id=cbse_exam_class_sections.class_section_id')->join('classes', 'classes.id=class_sections.class_id')->join('sections', 'sections.id=class_sections.section_id')->join('cbse_terms', 'cbse_terms.id=cbse_exams.cbse_term_id', 'left')->where('cbse_exams.id', $exam_id)->group_by('cbse_exams.id')->get()->row_array();
    }

    public function add_examsubject($insert_array, $update_array, $not_be_del, $exam_id, $timetable_prev_rows, $assessment_array, $prev_assessment_array)
    {
        $this->db->trans_begin();
        // ====================
        $n_array = [];

        foreach ($assessment_array as $as_key => $as_value) {
            foreach ($as_value as $tkey => $tvalue) {
                $n_array[] = $tvalue;
            }
        }

        $delete_assessments = (array_diff($prev_assessment_array, $n_array));

        if (!empty($delete_assessments)) {
            foreach ($delete_assessments as $d_a_key => $d_a_value) {
                $a = explode("_", $d_a_value);
                $this->db->where('cbse_exam_timetable_id', $a[0]);
                $this->db->where('cbse_exam_assessment_type_id', $a[1]);
                $this->db->delete('cbse_exam_timetable_assessment_types');
            }
        }

        if (!empty($insert_array)) {
            foreach ($insert_array as $insert_key => $insert_value) {
                $insert_assessments = $insert_array[$insert_key]['assessment'];
                unset($insert_array[$insert_key]['assessment']);
                unset($insert_array[$insert_key]['classes']);
                $this->db->insert('cbse_exam_timetable', $insert_array[$insert_key]);
                $inserted_id = $this->db->insert_id();
                $not_be_del[] = $inserted_id;

                foreach ($insert_value['assessment'] as $a_key => $a_value) {

                    $insert_as = explode("_", $a_value);
                    $n = [
                        'cbse_exam_timetable_id' => $inserted_id,
                        'cbse_exam_assessment_type_id' => $insert_as[1]
                    ];

                    $this->db->insert('cbse_exam_timetable_assessment_types', $n);
                }

                if (isset($insert_value['classes']) && !empty($insert_value['classes'])) {
                    $class_mappings = [];
                    foreach ($insert_value['classes'] as $cls_id) {
                        $class_mappings[] = [
                            'cbse_exam_timetable_id' => $inserted_id,
                            'class_id' => $cls_id
                        ];
                    }
                    if (!empty($class_mappings)) {
                        $this->db->insert_batch('cbse_exam_timetable_classes', $class_mappings);
                    }
                }
            }
        }

        if (!empty($update_array)) {
            foreach ($update_array as $up_key => $up_value) {

                foreach ($up_value['assessment'] as $a_key => $a_value) {

                    $insert_as = explode("_", $a_value);
                    $n = [
                        'cbse_exam_timetable_id' => $insert_as[0],
                        'cbse_exam_assessment_type_id' => $insert_as[1]
                    ];
                    $if_exists = $this->find_assessment_exists($prev_assessment_array, $a_value);

                    if (!$if_exists) {
                        $this->db->insert('cbse_exam_timetable_assessment_types', $n);
                    }
                }
                unset($update_array[$up_key]['assessment']);

                if (isset($up_value['classes'])) {
                    $this->db->where('cbse_exam_timetable_id', $up_value['id']);
                    $this->db->delete('cbse_exam_timetable_classes');

                    if (!empty($up_value['classes'])) {
                        $class_mappings = [];
                        foreach ($up_value['classes'] as $cls_id) {
                            $class_mappings[] = [
                                'cbse_exam_timetable_id' => $up_value['id'],
                                'class_id' => $cls_id
                            ];
                        }
                        if (!empty($class_mappings)) {
                            $this->db->insert_batch('cbse_exam_timetable_classes', $class_mappings);
                        }
                    }
                }
                unset($update_array[$up_key]['classes']);
            }

            $this->db->update_batch('cbse_exam_timetable', $update_array, 'id');
        }

        if (!empty($not_be_del)) {
            $this->db->where('cbse_exam_id', $exam_id);
            $this->db->where_not_in('id', $not_be_del);
            $this->db->delete('cbse_exam_timetable');
        }
        //====================

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function find_assessment_exists($assessment_array, $find_value)
    {
        if (in_array($find_value, $assessment_array)) {
            return true;
        }
        return false;
    }

    public function getexamsubjects($exam_id)
    {
		$subject_condition = 0;
        $userdata = $this->customlib->getUserData();
		if(isset($userdata)){
			$role_id = $userdata["role_id"];
			if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
				if ($userdata["class_teacher"] == 'yes') {
					$my_classes = $this->teacher_model->my_classes($userdata['id']);        
					if (!empty($my_classes)) {
						$subject_condition = 0;
					} else {
						$subject_condition = 1;                    
					}
					$my_subjects = $this->get_examsubjects($userdata['id']);
				}
			}
		}
		
		if (!empty($my_subjects)) {

			foreach($my_subjects as $key=>$value){
				$my_subjects[]=$value;
			}				
				return $this->db->select('cbse_exam_timetable.*,subjects.name as subject_name,subjects.code as subject_code')
				->from('cbse_exam_timetable')
				->join('subjects', 'subjects.id=cbse_exam_timetable.subject_id')
				->where_in('subjects.id', $my_subjects)
				->where('cbse_exam_id', $exam_id)->get()->result();
				
        }elseif($subject_condition == 1 && empty($my_subjects)){
               
             return array();
			 
        }else{
				
                return $this->db->select('cbse_exam_timetable.*,subjects.name as subject_name,subjects.code as subject_code')
				->from('cbse_exam_timetable')
				->join('subjects', 'subjects.id=cbse_exam_timetable.subject_id')			 
				->where('cbse_exam_id', $exam_id)->get()->result();
				
				 
           
        }		
        
    }

    public function getexamSubjectswithAssessment($exam_id, $assessments, $class_ids = null)
    {
        $assessment_sql_additional = [];
        $assessment_variables = [];
        if (!empty($assessments)) {
            foreach ($assessments as $assessment_key => $assessment_value) {

                $assessment_sql_additional[] = "LEFT join cbse_exam_timetable_assessment_types as assess_type_" . $assessment_value->id . " on assess_type_" . $assessment_value->id . ".cbse_exam_timetable_id=cbse_exam_timetable.id and assess_type_" . $assessment_value->id . ".cbse_exam_assessment_type_id=" . $assessment_value->id;
                $assessment_variables[] = "assess_type_" . $assessment_value->id . ".id as assess_type_" . $assessment_value->id . " , assess_type_" . $assessment_value->id . ".cbse_exam_timetable_id as cbse_exam_timetable_id_" . $assessment_value->id . " , assess_type_" . $assessment_value->id . ".cbse_exam_assessment_type_id as cbse_exam_assessment_type_id_" . $assessment_value->id;
            }
        }
        $additional_sql = implode(" ", $assessment_sql_additional);
        $additional_assessment_variables = implode(" ,", $assessment_variables);

        $class_filter = "";
        $class_join = "";
        if (!empty($class_ids) && is_array($class_ids)) {
            $class_ids_str = implode(',', array_map('intval', $class_ids));
            $class_join = " INNER JOIN cbse_exam_timetable_classes ON cbse_exam_timetable_classes.cbse_exam_timetable_id = cbse_exam_timetable.id ";
            $class_filter = " AND cbse_exam_timetable_classes.class_id IN (" . $class_ids_str . ") ";
        }

        $sql = "SELECT DISTINCT cbse_exam_timetable.*,`subjects`.`name` as `subject_name`, `subjects`.`code` as `subject_code`,cbse_exams.cbse_exam_assessment_id " . (!empty($additional_assessment_variables) ? ", " . $additional_assessment_variables : "") . " from cbse_exam_timetable INNER join cbse_exams on cbse_exams.id=cbse_exam_timetable.cbse_exam_id  
        JOIN `subjects` ON `subjects`.`id`=`cbse_exam_timetable`.`subject_id` " . $class_join . $additional_sql . " WHERE cbse_exam_timetable.cbse_exam_id=" . $exam_id . $class_filter . " order by cbse_exam_timetable.date asc, cbse_exam_timetable.time_from asc, subjects.name asc";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getExamTimetable()
    {

        $userdata = $this->customlib->getUserData();
            $role_id = $userdata["role_id"];
            $carray = array();
            $class_section_id=array();
            if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
                if ($userdata["class_teacher"] == 'yes') {
                    $classlist = $this->teacher_model->get_teacherrestricted_mode($userdata["id"]);
                }
                foreach ($classlist as $key => $value) {
                    $class_section=$this->teacher_model->get_teacherrestricted_modesections($userdata["id"], $value['id']);
                    $class_section_id[]=$class_section[0]['id'];
                }
            }
        $this->db->select('cbse_exams.*')
            ->from('cbse_exams')->join('cbse_exam_class_sections', 'cbse_exam_class_sections.cbse_exam_id=cbse_exams.id');
             if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
        if(!empty($class_section_id)){
             $this->db->where_in('cbse_exam_class_sections.class_section_id', $class_section_id);
        }
    }
           $exams =  $this->db->where('cbse_exams.is_active', 1)
            ->where('cbse_exams.session_id', $this->current_session)
            ->order_by('cbse_exams.id', 'desc')
			->group_by('cbse_exams.id')
            ->get()->result();

        if (!empty($exams)) {
            foreach ($exams as $exam_key => $exam_value) {

                $exams[$exam_key]->{"time_table"} = $this->db->select('cbse_exam_timetable.*,subjects.name as subject_name,subjects.code as subject_code')
                    ->from('cbse_exam_timetable')->join('subjects', 'subjects.id=cbse_exam_timetable.subject_id')

                    ->where('cbse_exam_timetable.cbse_exam_id', $exam_value->id)

                    ->get()
                    ->result();
            }
        }

        return $exams;
    }

    public function getExamTimetableMatrix($exam_id, $class_id = null)
    {
        $this->db->select('cbse_exam_timetable.*, subjects.name as subject_name, subjects.code as subject_code, cbse_exam_timetable_classes.class_id, classes.class as class_name');
        $this->db->from('cbse_exam_timetable');
        $this->db->join('subjects', 'subjects.id = cbse_exam_timetable.subject_id');
        $this->db->join('cbse_exam_timetable_classes', 'cbse_exam_timetable_classes.cbse_exam_timetable_id = cbse_exam_timetable.id');
        $this->db->join('classes', 'classes.id = cbse_exam_timetable_classes.class_id');
        $this->db->where('cbse_exam_timetable.cbse_exam_id', $exam_id);
        if ($class_id) {
            $this->db->where('cbse_exam_timetable_classes.class_id', $class_id);
        }
        $this->db->order_by('cbse_exam_timetable.date', 'ASC');
        $this->db->order_by('classes.class', 'ASC');
        $results = $this->db->get()->result_array();

        $dates = [];
        $matrix = [];
        
        foreach ($results as $row) {
            $date = $row['date'];
            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }
            $matrix[$row['class_name']][$date][] = $row;
        }

        // Ensure dates are sorted chronologically
        usort($dates, function($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        return ['dates' => $dates, 'classes' => $matrix];
    }

    public function getStudentExamTimetable($student_session_id)
    {
        $student_exam = $this->db->select('cbse_exam_students.*,cbse_exams.name,cbse_exams.exam_code')
            ->from('cbse_exam_students')->join('cbse_exams', 'cbse_exams.id=cbse_exam_students.cbse_exam_id')
            ->where('student_session_id', $student_session_id)
            ->where('cbse_exams.session_id', $this->current_session)
            ->where('cbse_exams.is_active', 1)
            ->order_by('cbse_exams.id', 'desc')
            ->get()->result();
        if (!empty($student_exam)) {
            foreach ($student_exam as $exam_key => $exam_value) {

                $student_exam[$exam_key]->{"time_table"} = $this->db->select('cbse_exam_timetable.*,subjects.name as subject_name,subjects.code as subject_code')
                    ->from('cbse_exam_timetable')->join('subjects', 'subjects.id=cbse_exam_timetable.subject_id')
                    ->where('cbse_exam_id', $exam_value->cbse_exam_id)
                    ->get()
                    ->result();
            }
        }

        return $student_exam;
    }

    public function getStudentexamSubjectsResult($exam_id, $cbse_exam_student_id)
    {
        return $this->db->select('cbse_exam_timetable.*, `subjects`.`name` as `subject_name`, `cbse_student_subject_result`.`id` as `cbse_student_subject_result_id`, `cbse_exams`.`name` as `exam_name`, `cbse_exam_assessments`.`name` as `cbse_exam_assessments_name`, `cbse_exam_assessment_types`.`name` as `cbse_exam_assessment_type_name`,cbse_student_subject_marks.id as cbse_student_subject_mark_id,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,IFNULL( `cbse_student_subject_marks`.`mark`,0) as mark,cbse_exam_assessment_types.pass_percentage,cbse_exam_assessment_types.maximum_marks,cbse_exam_assessment_types.id as cbse_exam_assessment_type_id')
            ->from('cbse_exam_timetable')
            ->join('cbse_exams', 'cbse_exams.id=cbse_exam_timetable.cbse_exam_id')
            ->join('cbse_exam_assessments', 'cbse_exam_assessments.id=cbse_exams.cbse_exam_assessment_id')
            ->join('cbse_exam_assessment_types', 'cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exam_assessments.id')
            ->join('subjects', 'subjects.id=cbse_exam_timetable.subject_id')
            ->join('cbse_student_subject_result', 'cbse_student_subject_result.cbse_exam_timetable_id=cbse_exam_timetable.id and `cbse_student_subject_result`.`cbse_exam_student_id` =' . $cbse_exam_student_id, 'LEFT')
            ->join('cbse_student_subject_marks', 'cbse_student_subject_marks.cbse_student_subject_result_id=cbse_student_subject_result.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id', 'LEFT')
            ->where('cbse_exam_id', $exam_id)
            ->get()
            ->result();
    }

    public function getStudentExamByStudentSession($student_session_id)
    {
        return $this->db->select('cbse_exam_students.*,cbse_exams.cbse_exam_assessment_id,cbse_exams.cbse_term_id,cbse_exams.name,cbse_exams.use_exam_roll_no,cbse_exams.is_active,cbse_exams.is_publish,cbse_exams.cbse_term_id,cbse_exams.cbse_exam_grade_id,cbse_exams.total_working_days')
            ->from('cbse_exam_students')
            ->join('student_session', 'student_session.id=cbse_exam_students.student_session_id')
            ->join('students', 'students.id=student_session.student_id')
            ->join('cbse_exams', 'cbse_exam_students.cbse_exam_id=cbse_exams.id')
            ->where('cbse_exam_students.student_session_id', $student_session_id)
            ->where('cbse_exams.is_publish', '1')
            ->order_by('cbse_exams.created_at', 'desc')
            ->get()->result();
    }

    public function get_examstudents($exam_id)
    {
         $userdata = $this->customlib->getUserData();
            $role_id = $userdata["role_id"];
            $carray = array();
            $class_section_id=array();
            if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
                if ($userdata["class_teacher"] == 'yes') {
                    $classlist = $this->teacher_model->get_teacherrestricted_mode($userdata["id"]);
                }
                foreach ($classlist as $key => $value) {
                    $class_section=$this->teacher_model->get_teacherrestricted_modesections($userdata["id"], $value['id']);
                    $class_section_id[]=$class_section[0]['id'];
                }
            }
         $this->db->select('students.*,cbse_exam_students.cbse_exam_id,cbse_exam_students.id as exam_student_id,  cbse_exams.total_working_days,cbse_exam_students.total_present_days,cbse_exam_students.roll_no as `exam_roll_no`,classes.class as class_name,sections.section as section_name,student_session.class_id as class_id,student_session.section_id as section_id,cbse_exam_students.student_session_id')
            ->from('cbse_exam_students')
            ->join('cbse_exams', 'cbse_exams.id=cbse_exam_students.cbse_exam_id')
            ->join('cbse_exam_class_sections', 'cbse_exam_class_sections.cbse_exam_id=cbse_exams.id')
            ->join('staff', 'staff.id=cbse_exam_students.staff_id', 'left')
            ->join('student_session', 'student_session.id=cbse_exam_students.student_session_id')
            ->join('students', 'students.id=student_session.student_id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->where('cbse_exam_students.cbse_exam_id', $exam_id);
             if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
        if(!empty($class_section_id)){
             $this->db->where_in('cbse_exam_class_sections.class_section_id', $class_section_id);
        }
    }
           return $this->db->group_by('cbse_exam_students.id')->get()->result_array();
    }

    public function get_markexamstudents($timetable_id, $class_id = null, $section_id = null)
    {
        $result = array();
        
        $this->db->select('students.*,cbse_exam_students.id as exam_student_id,cbse_exam_timetable.id as cbse_exam_timetable_id,cbse_exam_students.roll_no as `exam_roll_no`,classes.class as class_name,sections.section as section_name')
            ->from('cbse_exam_students')
            ->join('student_session', 'student_session.id=cbse_exam_students.student_session_id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('students', 'students.id=student_session.student_id')
            ->join('cbse_exam_timetable', 'cbse_exam_timetable.cbse_exam_id=cbse_exam_students.cbse_exam_id')
            ->join('cbse_exam_timetable_classes', 'cbse_exam_timetable_classes.cbse_exam_timetable_id=cbse_exam_timetable.id AND cbse_exam_timetable_classes.class_id=classes.id')
            ->where('cbse_exam_timetable.id', $timetable_id);

        if ($class_id) {
            $this->db->where('classes.id', $class_id);
        }
        if ($section_id) {
            $this->db->where('sections.id', $section_id);
        }

        $student_data = $this->db->get()->result_array();

        foreach ($student_data as $key => $value) {
            $cbse_student_subject_marks = $this->db->select('cbse_student_subject_marks.*')
                ->from('cbse_student_subject_marks')
                ->where(array('cbse_student_subject_marks.cbse_exam_timetable_id' => $value['cbse_exam_timetable_id'], ' `cbse_student_subject_marks`.`cbse_exam_student_id` ' => $value['exam_student_id']))
                ->get()
                ->result_array();
            $student_subject_marks = array();

            foreach ($cbse_student_subject_marks as $mkey => $mvalue) {
                $student_subject_marks[$mvalue['cbse_exam_assessment_type_id']] = $mvalue;
            }
            $result[$value['id']] = $value;
            $result[$value['id']]['marks'] = $student_subject_marks;
        }
        return $result;
    }

    public function get_markexamstudents_multi($timetable_id, $class_ids = array(), $section_ids = array())
    {
        $result = array();
        
        $this->db->select('students.*,cbse_exam_students.id as exam_student_id,cbse_exam_timetable.id as cbse_exam_timetable_id,cbse_exam_students.roll_no as `exam_roll_no`,classes.class as class_name,sections.section as section_name')
            ->from('cbse_exam_students')
            ->join('student_session', 'student_session.id=cbse_exam_students.student_session_id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('students', 'students.id=student_session.student_id')
            ->join('cbse_exam_timetable', 'cbse_exam_timetable.cbse_exam_id=cbse_exam_students.cbse_exam_id')
            ->join('cbse_exam_timetable_classes', 'cbse_exam_timetable_classes.cbse_exam_timetable_id=cbse_exam_timetable.id AND cbse_exam_timetable_classes.class_id=classes.id')
            ->where('cbse_exam_timetable.id', $timetable_id);

        if (!empty($class_ids)) {
            $this->db->where_in('classes.id', $class_ids);
        }
        if (!empty($section_ids)) {
            $this->db->where_in('sections.id', $section_ids);
        }

        $student_data = $this->db->get()->result_array();

        foreach ($student_data as $key => $value) {
            $cbse_student_subject_marks = $this->db->select('cbse_student_subject_marks.*')
                ->from('cbse_student_subject_marks')
                ->where(array('cbse_student_subject_marks.cbse_exam_timetable_id' => $value['cbse_exam_timetable_id'], ' `cbse_student_subject_marks`.`cbse_exam_student_id` ' => $value['exam_student_id']))
                ->get()
                ->result_array();
            $student_subject_marks = array();

            foreach ($cbse_student_subject_marks as $mkey => $mvalue) {
                $student_subject_marks[$mvalue['cbse_exam_assessment_type_id']] = $mvalue;
            }
            $result[$value['id']] = $value;
            $result[$value['id']]['marks'] = $student_subject_marks;
        }
        return $result;
    }

    public function get_exam_subject_assessment_types($exam_assessment_id, $timetable_id)
    {
        // maximum_marks: use the per-subject override on the timetable link when
        // set, otherwise fall back to the assessment-type default. This is the
        // single source of truth for a subject's max marks (entry + marksheet).
        $this->db->select('cbse_exam_assessment_types.id, cbse_exam_assessment_types.cbse_exam_assessment_id, cbse_exam_assessment_types.name, cbse_exam_assessment_types.code, cbse_exam_assessment_types.pass_percentage, cbse_exam_assessment_types.description, COALESCE(cbse_exam_timetable_assessment_types.maximum_marks, cbse_exam_assessment_types.maximum_marks) as maximum_marks, cbse_exam_timetable_assessment_types.id as cbse_exam_timetable_assessment_type_id');
        $this->db->from('cbse_exam_assessment_types');
        $this->db->join('cbse_exam_timetable_assessment_types', 'cbse_exam_timetable_assessment_types.cbse_exam_timetable_id=' . $timetable_id . ' and cbse_exam_timetable_assessment_types.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id');
        $this->db->where('cbse_exam_assessment_types.cbse_exam_assessment_id', $exam_assessment_id);
        $result = $this->db->get();
        return $result->result();
    }

    public function get_exam_assessment_types($exam_assessment_id)
    {
        return $this->db->select('*')->from('cbse_exam_assessment_types')->where('cbse_exam_assessment_id', $exam_assessment_id)->get()->result();
    }

    public function addresult_data($result_data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($result_data['id']) && !empty($result_data['id'])) {
            $this->db->where('id', $result_data['id']);
            $this->db->update('cbse_student_subject_result', $result_data);
            $message = UPDATE_RECORD_CONSTANT . " On cbse student subject result id " . $result_data['id'];
            $action = "Update";
            $record_id = $result_data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('cbse_student_subject_result', $result_data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On cbse student subject result id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

    public function getresult_data($subject_id, $student_id)
    {
        $this->db->select('cbse_student_subject_result.id');
        $this->db->from('cbse_student_subject_result');
        $this->db->where('cbse_student_subject_result.subject_id', $subject_id);
        $this->db->where('cbse_student_subject_result.cbse_exam_student_id', $student_id);
        $result = $this->db->get();
        return $result->row_array();
    }

    public function addresultmark_data($result_mark, $cbse_exam_timetable_id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        if (isset($result_mark['id']) && !empty($result_mark['id'])) {
            $this->db->where('id', $result_mark['id']);
            $this->db->update('cbse_student_subject_marks', $result_mark);
            $message = UPDATE_RECORD_CONSTANT . " On cbse student subject marks id " . $result_mark['id'];
            $action = "Update";
            $record_id = $result_mark['id'];
            $this->log($message, $record_id, $action);
        } else {
            $insert_ids = [];
            foreach ($result_mark as $mark_key => $mark_value) {
                $this->db->insert('cbse_student_subject_marks', $mark_value);
                $insert_id = $this->db->insert_id();
                $insert_ids[] = $insert_id;
            }
            
            $submitted_student_ids = array_unique(array_column($result_mark, 'cbse_exam_student_id'));
            
            if (!empty($insert_ids) && !empty($submitted_student_ids)) {
                $this->db->where('cbse_exam_timetable_id', $cbse_exam_timetable_id);
                $this->db->where_in('cbse_exam_student_id', $submitted_student_ids);
                $this->db->where_not_in('id', $insert_ids);
                $this->db->delete('cbse_student_subject_marks');
            }
        }

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function add_exam_student_attendance($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well

        $this->db->insert('cbse_exam_student_attendance', $data);
        $insert_id = $this->db->insert_id();
        $message = INSERT_RECORD_CONSTANT . " On cbse exam student attendance id " . $insert_id;
        $action = "Insert";
        $record_id = $insert_id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function add_exam_attendance($data)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('cbse_exam_attendance', $data);
            $insert_id = $data['id'];
        } else {
            $this->db->insert('cbse_exam_attendance', $data);
            $insert_id = $this->db->insert_id();
        }

        return $insert_id;
    }

    public function delete_exam_student_attendance($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('cbse_exam_attendance_id', $data['cbse_exam_attendance_id']);
        $this->db->delete('cbse_exam_student_attendance');
        $message   = DELETE_RECORD_CONSTANT . " On cbse exam student attendance where cbse exam attendance id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getexamattendance($exam_id)
    {
        $this->db->select('id, cbse_exam_id');
        $this->db->from('cbse_exam_attendance');
        $this->db->where('cbse_exam_attendance.cbse_exam_id', $exam_id);
        $result = $this->db->get();
        return $result->row_array();
    }

    public function get_teacher_remark($exam_id)
    {
        $sql = "SELECT  `students`.*, `a`.`id` as `exam_student_id`, `a`.`remark`, `classes`.`class` as `class_name`, `sections`.`section` as `section_name` ,(SELECT sum(cbse_student_subject_marks.marks) FROM `cbse_exams` INNER join cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id  INNER join cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id INNER join cbse_exam_assessments on cbse_exam_assessments.id=cbse_exams.cbse_exam_assessment_id INNER join cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exam_assessments.id left join `cbse_student_subject_marks` on  cbse_student_subject_marks.`cbse_exam_timetable_id` = cbse_exam_timetable.id  AND cbse_student_subject_marks.`cbse_exam_student_id` = cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id where cbse_exams.id=" . $this->db->escape($exam_id) . " and cbse_exams.session_id=" . $this->current_session . " and student_session_id=a.student_session_id  ORDER BY cbse_exam_timetable.subject_id asc, cbse_exam_assessment_types.id desc)  as gain_total_marks, (SELECT sum(cbse_exam_assessment_types.maximum_marks) FROM `cbse_exams` INNER join cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id  INNER join cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id INNER join cbse_exam_assessments on cbse_exam_assessments.id=cbse_exams.cbse_exam_assessment_id INNER join cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exam_assessments.id left join `cbse_student_subject_marks` on  cbse_student_subject_marks.`cbse_exam_timetable_id` = cbse_exam_timetable.id  AND cbse_student_subject_marks.`cbse_exam_student_id` = cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id where cbse_exams.id=" . $this->db->escape($exam_id) . " and cbse_exams.session_id=" . $this->current_session . " and student_session_id=a.student_session_id  ORDER BY cbse_exam_timetable.subject_id asc, cbse_exam_assessment_types.id desc)  as total_marks FROM `cbse_exams` INNER join cbse_exam_students a on a.cbse_exam_id=cbse_exams.id inner JOIN `student_session` ON `student_session`.`id`=a.`student_session_id` JOIN `students` ON `students`.`id`=`student_session`.`student_id` JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` where cbse_exams.id=" . $this->db->escape($exam_id) . " and cbse_exams.session_id=" . $this->current_session . " order by students.firstname asc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getremarkbyexamid($exam_id)
    {
        $this->db->select('id, cbse_exam_student_id');
        $this->db->from('cbse_teacher_remarks');
        $this->db->where('cbse_teacher_remarks.cbse_exam_student_id', $exam_id);
        $result = $this->db->get();
        return $result->row_array();
    }

    public function addteacherremark($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && !empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('cbse_teacher_remarks', $data);
            $message = UPDATE_RECORD_CONSTANT . " On cbse teacher remarks id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('cbse_teacher_remarks', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On cbse teacher remarks id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function get_classsectionbyId($exam_id)
    {
        $this->db->select('cbse_exam_class_sections.class_section_id, classes.id as class_id');
        $this->db->from('cbse_exam_class_sections');
        $this->db->join('class_sections', 'class_sections.id=cbse_exam_class_sections.class_section_id');
        $this->db->join('classes', 'classes.id=class_sections.class_id');
        $this->db->where('cbse_exam_class_sections.cbse_exam_id', $exam_id);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function removeclasssection($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('cbse_exam_id', $id);
        $this->db->delete('cbse_exam_class_sections');
        $message = DELETE_RECORD_CONSTANT . " On cbse exam class sections id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

	public function getSubject($id = null) {

        $subject_condition = 0;
        $userdata = $this->customlib->getUserData();
        $my_subjects=array();
        $role_id = $userdata["role_id"];


        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {



                $my_classes = $this->teacher_model->my_classes($userdata['id']);

		
                if (!empty($my_classes)) {
                    $subject_condition = 0;
                } else {
                    $subject_condition = 1;
                    
                }
		$my_subjects = $this->get_examsubjects($userdata['id']);
            }
        }
        
       
           
            if (!empty($my_subjects)) {

foreach($my_subjects as $key=>$value){
$my_subjects[]=$value;
}

                 $this->db->select()->from('subjects');
                $this->db->where_in('subjects.id', $my_subjects);
                $this->db->order_by('id');
                 $query = $this->db->get();
                return $query->result_array(); 
            }elseif($subject_condition == 1 && empty($my_subjects)){
               
             return array();
            }else{
                 $this->db->select()->from('subjects');
                 $this->db->order_by('id');
                 $query = $this->db->get();
                 return $query->result_array(); 
            }
            
        
        
       
    }
  public function get_examsubjects($staff_id)
    {
	
        $subject_id = array();
	$class_teacher_sections      = $this->db->query("SELECT class_id, section_id FROM `class_teacher` where staff_id=".$staff_id." and session_id=" . $this->current_session);
        $class_teacher_subjectquerydata = $class_teacher_sections->result_array();
	
	 foreach ($class_teacher_subjectquerydata as $cskey => $csvalue) {
	$sclass_id=$csvalue['class_id'];
	$ssection_id=$csvalue['section_id'];
	$class_teacher_subject     = $this->db->query("select sgs.subject_id  from class_sections cs inner join subject_group_class_sections sgcs on sgcs.class_section_id=cs.id inner join subject_group_subjects sgs on sgs.subject_group_id=sgcs.subject_group_id where cs.class_id='".$sclass_id."' and cs.section_id='".$ssection_id."' and sgs.session_id='" . $this->current_session . "' ");
        $class_teacher_subjectquerydata = $class_teacher_subject->result_array();
         foreach ($class_teacher_subjectquerydata as $ctskey => $ctsvalue) {
            $subject_id[$ctsvalue['subject_id']] = $ctsvalue['subject_id'];
        }  

        }



        $query      = $this->db->query("select sgs.subject_id  from subject_timetable st inner join subject_group_subjects sgs on st.subject_group_subject_id=sgs.id where st.staff_id='" . $staff_id . "' and st.session_id='" . $this->current_session . "' ");
        $querydata = $query->result_array();
        foreach ($querydata as $key => $value) {
            $subject_id[$value['subject_id']] = $value['subject_id'];
        }

        return $subject_id;
    }

//========================
    public function get_cbse_exam_sections_name($exam_id)
    {
        $this->db->select('group_concat(cbse_exam_class_sections.class_section_id) as sections, classes.id as class_id');
        $this->db->from('cbse_exam_class_sections');
        $this->db->join('class_sections', 'class_sections.id=cbse_exam_class_sections.class_section_id');
        $this->db->join('classes', 'classes.id=class_sections.class_id');
        $this->db->where('cbse_exam_class_sections.cbse_exam_id', $exam_id);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getexamsubjectnote($subject_note_exam_id,$student_session_id,$subject_id)
    {
       
        $this->db->select('`cbse_student_subject_marks`.`note`');
        $this->db->from('cbse_exams');
        $this->db->join('cbse_exam_students', 'cbse_exams.id=cbse_exam_students.cbse_exam_id','left');
        $this->db->join('cbse_student_subject_marks', 'cbse_exam_students.id=cbse_student_subject_marks.cbse_exam_student_id','left');

        $this->db->join('cbse_exam_timetable_assessment_types','cbse_exam_timetable_assessment_types.id=cbse_student_subject_marks.cbse_exam_timetable_assessment_type_id','left');


        $this->db->join('cbse_exam_timetable','cbse_exam_timetable.id=cbse_exam_timetable_assessment_types.cbse_exam_timetable_id','left');



        $this->db->where("`cbse_exam_students`.`cbse_exam_id`",$subject_note_exam_id);
        $this->db->where("cbse_exam_timetable.subject_id",$subject_id);
        $this->db->where("`cbse_exam_students`.`student_session_id` in  ($student_session_id)");
        $result = $this->db->get();
        return $result->result_array();
    }
//========================
	
	public function getStudentAttendenceRange($date)
    {
        $sql = "SELECT attendence_type.type,attendence_type.key_value,student_attendences.* FROM `student_attendences` INNER JOIN attendence_type ON attendence_type.id=student_attendences.attendence_type_id where student_attendences.date >= " . $this->db->escape($date['start']) . " and student_attendences.date <= " . $this->db->escape($date['end'])." group by student_attendences.date";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //===================================new function created============================//
    public function getStudentExamResultByExamId_download($cbse_template_id, $cbse_exam_id, $students)
    {
        
        $sql   = "SELECT `cbse_exams`.*,cbse_exam_student_subject_rank.rank as subject_rank ,cbse_student_template_rank.rank,cbse_student_template_rank.rank_percentage,cbse_terms.name as cbse_term_name,cbse_terms.term_code as cbse_term_code,cbse_exam_timetable.subject_id,cbse_exam_students.id as cbse_exam_student_id,cbse_exam_students.total_present_days,cbse_exam_students.remark,cbse_exam_assessment_types.name as cbse_exam_assessment_type_name,cbse_exam_assessment_types.id as `cbse_exam_assessment_type_id`,cbse_exam_assessment_types.code as cbse_exam_assessment_type_code,cbse_exam_assessment_types.maximum_marks,cbse_student_subject_marks.id as `cbse_student_subject_marks_id`,cbse_student_subject_marks.marks,cbse_student_subject_marks.is_absent,cbse_student_subject_marks.note,cbse_student_subject_marks.cbse_exam_timetable_id,students.id as `student_id`,students.firstname, students.middlename, students.lastname,students.image, students.mobileno, students.email ,students.state , students.city , students.pincode , students.note, students.religion, students.cast, students.dob ,students.current_address, students.previous_school,students.roll_no, students.guardian_is,students.parent_id,students.admission_no, students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,subjects.name as subject_name,subjects.code as `subject_code`,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,student_session.id as `student_session_id`,
            (select cbse_template.content_footer from cbse_template where cbse_template.id=$cbse_template_id ) as content_footer,
            (select cbse_template.gradeexam_id from cbse_template where cbse_template.id=$cbse_template_id ) as gradeexam_id,
            (select cbse_template.remarkexam_id from cbse_template where cbse_template.id=$cbse_template_id ) as remarkexam_id,
            (select cbse_template.subjectnoteexam_id from cbse_template where cbse_template.id=$cbse_template_id ) as subjectnoteexam_id,
            (select cbse_template_term_exams.weightage from cbse_template_term_exams where cbse_template_term_exams.`cbse_exam_id` = $cbse_exam_id and cbse_template_term_exams.cbse_template_id =$cbse_template_id) as weightage,cbse_student_exam_ranks.rank
            FROM `cbse_exams` 
            INNER JOIN cbse_exam_timetable on cbse_exam_timetable.cbse_exam_id=cbse_exams.id 
            INNER JOIN cbse_exam_students on cbse_exam_students.cbse_exam_id=cbse_exams.id 
            INNER JOIN cbse_exam_assessment_types on cbse_exam_assessment_types.cbse_exam_assessment_id=cbse_exams.cbse_exam_assessment_id
            INNER JOIN cbse_terms on cbse_terms.id=cbse_exams.cbse_term_id 
            left join cbse_student_subject_marks on cbse_student_subject_marks.cbse_exam_timetable_id =cbse_exam_timetable.id and cbse_student_subject_marks.cbse_exam_student_id= cbse_exam_students.id and cbse_student_subject_marks.cbse_exam_assessment_type_id=cbse_exam_assessment_types.id 
            INNER JOIN student_session on student_session.id=cbse_exam_students.student_session_id 
            INNER join students on students.id =student_session.student_id
            INNER JOIN subjects on subjects.id=cbse_exam_timetable.subject_id
            INNER join classes on student_session.class_id = classes.id 
            INNER join sections on sections.id = student_session.section_id 
            left join cbse_student_template_rank on cbse_student_template_rank.cbse_template_id=$cbse_template_id and cbse_student_template_rank.student_session_id=student_session.id 
            left JOIN cbse_exam_student_subject_rank on cbse_exam_student_subject_rank.cbse_template_id=$cbse_template_id and cbse_exam_student_subject_rank.student_session_id=student_session.id and cbse_exam_student_subject_rank.subject_id=subjects.id 
            left join cbse_student_exam_ranks on cbse_student_exam_ranks.cbse_exam_id=cbse_exams.id and cbse_student_exam_ranks.student_session_id 
            in  ($students)
            WHERE  cbse_exams.`id` = " . $this->db->escape($cbse_exam_id) . " and cbse_exams.session_id=" . $this->current_session . " and student_session.id in (" . $students . ")";
            $query = $this->db->query($sql);
            return $query->result();
    }

    public function get_cbse_reportcard_data($exam_id, $class_ids = array(), $section_ids = array())
    {
        $exam = $this->get_exambyId($exam_id);
        if (!$exam) {
            return false;
        }

        // Grade bands for this exam (percentage based).
        $grades = array();
        if (!empty($exam['cbse_exam_grade_id'])) {
            $grades = $this->db->select('name, minimum_percentage, maximum_percentage')
                ->from('cbse_exam_grades_range')
                ->where('cbse_exam_grade_id', $exam['cbse_exam_grade_id'])
                ->order_by('minimum_percentage', 'desc')
                ->get()->result_array();
        }

        // Students (with class/section) + their marks keyed [student][tt][at].
        $sd = $this->get_bulk_exam_students_with_marks($exam_id, $class_ids, $section_ids);
        $students = $sd['students'];
        $existing = $sd['existing_marks'];

        // Per-class subject structure with override-aware max marks.
        $where_class = '';
        if (!empty($class_ids) && is_array($class_ids)) {
            $where_class = ' AND tc.class_id IN (' . implode(',', array_map('intval', $class_ids)) . ')';
        }
        $sql = "SELECT tc.class_id, t.id AS tt_id, t.subject_id, s.name AS subject_name, s.code AS subject_code,
                       at.id AS at_id, at.name AS at_name,
                       COALESCE(tat.maximum_marks, at.maximum_marks) AS max_marks
                FROM cbse_exam_timetable t
                JOIN cbse_exam_timetable_classes tc ON tc.cbse_exam_timetable_id = t.id
                JOIN subjects s ON s.id = t.subject_id
                JOIN cbse_exam_timetable_assessment_types tat ON tat.cbse_exam_timetable_id = t.id
                JOIN cbse_exam_assessment_types at ON at.id = tat.cbse_exam_assessment_type_id
                WHERE t.cbse_exam_id = " . $this->db->escape($exam_id) . $where_class . "
                ORDER BY tc.class_id, s.name, at.id";
        $rows = $this->db->query($sql)->result_array();

        // class_id => subject_id => { name, code, tt, max, at_ids[] }
        $class_subjects = array();
        foreach ($rows as $r) {
            $cid = $r['class_id'];
            $sid = $r['subject_id'];
            if (!isset($class_subjects[$cid][$sid])) {
                $class_subjects[$cid][$sid] = array(
                    'subject_name' => $r['subject_name'],
                    'subject_code' => $r['subject_code'],
                    'tt_id' => $r['tt_id'],
                    'max' => 0,
                    'at_ids' => array()
                );
            }
            $class_subjects[$cid][$sid]['max'] += (float)$r['max_marks'];
            $class_subjects[$cid][$sid]['at_ids'][] = $r['at_id'];
        }

        $report = array();
        foreach ($students as $std) {
            $cid = isset($std['class_id']) ? $std['class_id'] : 0;
            $sid_key = $std['exam_student_id'];
            $subjects_out = array();
            $grand_obt = 0;
            $grand_max = 0;

            if (isset($class_subjects[$cid])) {
                foreach ($class_subjects[$cid] as $subject_id => $sub) {
                    $tt = $sub['tt_id'];
                    $obtained = 0;
                    $absent = false;
                    $has_mark = false;
                    foreach ($sub['at_ids'] as $at_id) {
                        if (isset($existing[$sid_key][$tt][$at_id])) {
                            $m = $existing[$sid_key][$tt][$at_id];
                            $has_mark = true;
                            if ($m['is_absent'] == 1) {
                                $absent = true;
                            } else {
                                $obtained += (float)$m['marks'];
                            }
                        }
                    }
                    $max = $sub['max'];
                    $pct = ($max > 0) ? ($obtained / $max) * 100 : 0;
                    $subjects_out[] = array(
                        'subject_name' => $sub['subject_name'],
                        'subject_code' => $sub['subject_code'],
                        'max' => $max,
                        'obtained' => $absent ? null : ($has_mark ? $obtained : null),
                        'absent' => $absent,
                        'grade' => ($has_mark && !$absent) ? $this->_grade_for_percent($pct, $grades) : '',
                        'percent' => $pct
                    );
                    if ($has_mark && !$absent) {
                        $grand_obt += $obtained;
                    }
                    $grand_max += $max;
                }
            }

            $overall_pct = ($grand_max > 0) ? ($grand_obt / $grand_max) * 100 : 0;
            $report[] = array(
                'student' => $std,
                'subjects' => $subjects_out,
                'grand_obtained' => $grand_obt,
                'grand_max' => $grand_max,
                'overall_percent' => $overall_pct,
                'overall_grade' => $this->_grade_for_percent($overall_pct, $grades),
                'result' => ($overall_pct >= 33) ? 'PASS' : 'FAIL',
                'rank' => 0
            );
        }

        // Class rank by overall percentage (standard competition ranking).
        // Only rank students who have at least one mark (grand_max > 0).
        $order = array();
        foreach ($report as $idx => $rc) {
            if ($rc['grand_max'] > 0) $order[$idx] = $rc['overall_percent'];
        }
        arsort($order);
        $rank = 0; $seen = 0; $prev = null;
        foreach ($order as $idx => $pct) {
            $seen++;
            if ($prev === null || $pct < $prev) { $rank = $seen; $prev = $pct; }
            $report[$idx]['rank'] = $rank;
        }

        return array('exam' => $exam, 'grades' => $grades, 'report' => $report);
    }

    private function _grade_for_percent($pct, $grades)
    {
        foreach ($grades as $g) {
            if ($pct >= (float)$g['minimum_percentage'] && $pct <= (float)$g['maximum_percentage']) {
                return $g['name'];
            }
        }
        return '';
    }

    public function get_subject_max_marks($exam_id, $class_id)
    {
        $sql = "SELECT t.id AS tt_id, t.subject_id, s.name AS subject_name, s.code AS subject_code
                FROM cbse_exam_timetable t
                JOIN subjects s ON s.id = t.subject_id
                JOIN cbse_exam_timetable_classes tc ON tc.cbse_exam_timetable_id = t.id AND tc.class_id = " . (int)$class_id . "
                WHERE t.cbse_exam_id = " . $this->db->escape($exam_id) . "
                ORDER BY s.name";
        $subjects = $this->db->query($sql)->result_array();

        foreach ($subjects as &$sub) {
            $sub['assessment_types'] = $this->db->select('at.id AS at_id, at.name, at.code, at.maximum_marks AS default_max, tat.id AS tat_id, tat.maximum_marks AS override_max')
                ->from('cbse_exam_timetable_assessment_types tat')
                ->join('cbse_exam_assessment_types at', 'at.id = tat.cbse_exam_assessment_type_id')
                ->where('tat.cbse_exam_timetable_id', $sub['tt_id'])
                ->order_by('at.id')
                ->get()->result_array();
        }
        unset($sub);
        return $subjects;
    }

    public function save_subject_max_marks($rows)
    {
        $this->db->trans_start();
        foreach ($rows as $tat_id => $max) {
            $val = ($max === '' || $max === null) ? null : (float)$max;
            $this->db->where('id', (int)$tat_id)
                ->update('cbse_exam_timetable_assessment_types', array('maximum_marks' => $val));
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_bulk_exam_classes($exam_id)
    {
        $sql = "SELECT DISTINCT c.id, c.class
                FROM cbse_exam_class_sections ecs
                JOIN class_sections cs ON cs.id = ecs.class_section_id
                JOIN classes c ON c.id = cs.class_id
                WHERE ecs.cbse_exam_id = " . $this->db->escape($exam_id) . "
                ORDER BY c.id";
        return $this->db->query($sql)->result_array();
    }

    public function get_bulk_exam_structure($exam_id)
    {
        $exam = $this->get_exambyId($exam_id);
        if (!$exam) {
            return false;
        }

        $sql = "SELECT t.id as timetable_id, t.subject_id, s.name as subject_name, s.code as subject_code, tc.class_id
                FROM cbse_exam_timetable t
                JOIN subjects s ON s.id = t.subject_id
                LEFT JOIN cbse_exam_timetable_classes tc ON tc.cbse_exam_timetable_id = t.id
                WHERE t.cbse_exam_id = " . $this->db->escape($exam_id) . "
                ORDER BY s.name, t.id";
        $raw_items = $this->db->query($sql)->result_array();

        $sql_tat = "SELECT tat.id, tat.cbse_exam_timetable_id, tat.cbse_exam_assessment_type_id
                    FROM cbse_exam_timetable_assessment_types tat
                    JOIN cbse_exam_timetable t ON t.id = tat.cbse_exam_timetable_id
                    WHERE t.cbse_exam_id = " . $this->db->escape($exam_id);
        $raw_tats = $this->db->query($sql_tat)->result_array();
        $tat_map = array();
        foreach ($raw_tats as $tat) {
            $tat_map[$tat['cbse_exam_timetable_id']][$tat['cbse_exam_assessment_type_id']] = $tat['id'];
        }

        $unique_subjects = array();
        $subject_class_map = array();

        foreach ($raw_items as $item) {
            $sub_id = $item['subject_id'];
            $tt_id = $item['timetable_id'];
            $cls_id = $item['class_id'];

            if (!isset($unique_subjects[$sub_id])) {
                $unique_subjects[$sub_id] = array(
                    'subject_id' => $sub_id,
                    'subject_name' => $item['subject_name'],
                    'subject_code' => $item['subject_code'],
                    'id' => $tt_id,
                    'assessment_types' => $this->get_exam_subject_assessment_types($exam['cbse_exam_assessment_id'], $tt_id)
                );

                if (empty($unique_subjects[$sub_id]['assessment_types'])) {
                    $this->db->select('cbse_exam_assessment_types.id, cbse_exam_assessment_types.cbse_exam_assessment_id, cbse_exam_assessment_types.name, cbse_exam_assessment_types.code, cbse_exam_assessment_types.pass_percentage, cbse_exam_assessment_types.description, COALESCE(cbse_exam_timetable_assessment_types.maximum_marks, cbse_exam_assessment_types.maximum_marks) as maximum_marks, cbse_exam_timetable_assessment_types.id as cbse_exam_timetable_assessment_type_id');
                    $this->db->from('cbse_exam_timetable_assessment_types');
                    $this->db->join('cbse_exam_assessment_types', 'cbse_exam_assessment_types.id = cbse_exam_timetable_assessment_types.cbse_exam_assessment_type_id');
                    $this->db->where('cbse_exam_timetable_assessment_types.cbse_exam_timetable_id', $tt_id);
                    $unique_subjects[$sub_id]['assessment_types'] = $this->db->get()->result();
                }
            }

            if ($cls_id) {
                $subject_class_map[$sub_id][$cls_id] = $tt_id;
            }
        }

        return array(
            'exam' => $exam,
            'subjects' => array_values($unique_subjects),
            'subject_class_map' => $subject_class_map,
            'tat_map' => $tat_map
        );
    }

    public function get_bulk_exam_students_with_marks($exam_id, $class_ids = null, $section_ids = null)
    {
        $all_students = $this->get_examstudents($exam_id);
        $filtered_students = array();

        if (!empty($class_ids) || !empty($section_ids)) {
            foreach ($all_students as $std) {
                $pass_class = empty($class_ids) || (is_array($class_ids) && in_array($std['class_id'], $class_ids));
                $pass_section = empty($section_ids) || (is_array($section_ids) && in_array($std['section_id'], $section_ids));
                if ($pass_class && $pass_section) {
                    $filtered_students[] = $std;
                }
            }
        } else {
            $filtered_students = $all_students;
        }

        $sql = "SELECT m.*, t.subject_id 
                FROM cbse_student_subject_marks m 
                JOIN cbse_exam_timetable t ON t.id = m.cbse_exam_timetable_id 
                WHERE t.cbse_exam_id = " . $this->db->escape($exam_id);
        $existing_marks_raw = $this->db->query($sql)->result_array();

        $existing_marks = array();
        foreach ($existing_marks_raw as $m) {
            $s_id = $m['cbse_exam_student_id'];
            $tt_id = $m['cbse_exam_timetable_id'];
            $at_id = $m['cbse_exam_assessment_type_id'];
            $existing_marks[$s_id][$tt_id][$at_id] = $m;
        }

        return array(
            'students' => $filtered_students,
            'existing_marks' => $existing_marks
        );
    }

    public function save_bulk_exam_marks($exam_id, $bulk_marks_data)
    {
        $this->db->trans_start();

        foreach ($bulk_marks_data as $item) {
            $this->db->where('cbse_exam_student_id', $item['cbse_exam_student_id']);
            $this->db->where('cbse_exam_timetable_id', $item['cbse_exam_timetable_id']);
            $this->db->where('cbse_exam_assessment_type_id', $item['cbse_exam_assessment_type_id']);
            $this->db->delete('cbse_student_subject_marks');

            $this->db->insert('cbse_student_subject_marks', array(
                'cbse_exam_student_id' => $item['cbse_exam_student_id'],
                'cbse_exam_timetable_id' => $item['cbse_exam_timetable_id'],
                'cbse_exam_assessment_type_id' => $item['cbse_exam_assessment_type_id'],
                'cbse_exam_timetable_assessment_type_id' => $item['cbse_exam_timetable_assessment_type_id'],
                'marks' => $item['marks'],
                'is_absent' => $item['is_absent'],
                'note' => isset($item['note']) ? $item['note'] : ''
            ));
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

}

