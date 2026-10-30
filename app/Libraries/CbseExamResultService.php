<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CbseExamResultService
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * Builds a structured multi-dimensional array of student results (Term -> Exam -> Subject -> Assessment)
     * This replicates the logic previously found in Result::test_multi, Report::getSinglTerm, etc.
     * 
     * @param array $cbse_exam_result Flat resultset from the model
     * @param string $remarkexam_id The exam ID used for remarks
     * @return array
     */
    public function buildStudentResultTermWise($cbse_exam_result, $remarkexam_id = "", $group_by_field = "student_session_id")
    {
        $students = [];

        foreach ($cbse_exam_result as $student_key => $student_value) {

            // To support both student_session_id and student_id grouping depending on the query
            $student_group_key = isset($student_value->$group_by_field) ? $student_value->$group_by_field : $student_value->student_id;

            if (array_key_exists($student_group_key, $students)) {

                if (!array_key_exists($student_value->cbse_term_id, $students[$student_group_key]['terms'])) {

                    $new_cbse_term_id = [
                        'cbse_term_id'           => $student_value->cbse_term_id,
                        'cbse_term_name'         => $student_value->cbse_term_name,
                        'cbse_term_code'         => $student_value->cbse_term_code,
                        'cbse_term_weight'       => isset($student_value->cbse_template_terms_weightage) ? $student_value->cbse_template_terms_weightage : (isset($student_value->weightage) ? $student_value->weightage : 0),
                        'term_total_assessments' => 1,
                        'exams'                  => [
                            $student_value->id => [
                                'name'               => $student_value->name,
                                'total_assessments'  => 1,
                                'total_present_days' => isset($student_value->total_present_days) ? $student_value->total_present_days : 0,
                                'total_working_days' => isset($student_value->total_working_days) ? $student_value->total_working_days : 0,
                                'subjects'           => [
                                    $student_value->subject_id => [
                                        'subject_id'       => $student_value->subject_id,
                                        'subject_name'     => $student_value->subject_name,
                                        'subject_code'     => $student_value->subject_code,
                                        'exam_assessments' => [
                                            $student_value->cbse_exam_assessment_type_id => [
                                                'cbse_exam_assessment_type_name' => $student_value->cbse_exam_assessment_type_name,
                                                'cbse_exam_assessment_type_id'   => $student_value->cbse_exam_assessment_type_id,
                                                'cbse_exam_assessment_type_code' => $student_value->cbse_exam_assessment_type_code,
                                                'maximum_marks'                  => $student_value->maximum_marks,
                                                'cbse_student_subject_marks_id'  => $student_value->cbse_student_subject_marks_id,
                                                'marks'                          => $student_value->marks,
                                                'note'                           => $student_value->note,
                                                'is_absent'                      => isset($student_value->is_absent) ? $student_value->is_absent : 0,
                                                'cbse_exam_timetable_assessment_type_id' => isset($student_value->cbse_exam_timetable_assessment_type_id) ? $student_value->cbse_exam_timetable_assessment_type_id : null
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ];

                    $students[$student_group_key]['terms'][$student_value->cbse_term_id] = $new_cbse_term_id;

                    if ($student_value->remarkexam_id == $remarkexam_id) {
                        $students[$student_group_key]['remark'] = $student_value->remark;
                    }
                } elseif (!array_key_exists($student_value->id, $students[$student_group_key]['terms'][$student_value->cbse_term_id]['exams'])) {

                    $new_exam = [
                        'name'               => $student_value->name,
                        'total_assessments'  => 1,
                        'total_present_days' => isset($student_value->total_present_days) ? $student_value->total_present_days : 0,
                        'total_working_days' => isset($student_value->total_working_days) ? $student_value->total_working_days : 0,
                        'subjects'           => [
                            $student_value->subject_id => [
                                'subject_id'       => $student_value->subject_id,
                                'subject_name'     => $student_value->subject_name,
                                'subject_code'     => $student_value->subject_code,
                                'exam_assessments' => [
                                    $student_value->cbse_exam_assessment_type_id => [
                                        'cbse_exam_assessment_type_name' => $student_value->cbse_exam_assessment_type_name,
                                        'cbse_exam_assessment_type_id'   => $student_value->cbse_exam_assessment_type_id,
                                        'cbse_exam_assessment_type_code' => $student_value->cbse_exam_assessment_type_code,
                                        'maximum_marks'                  => $student_value->maximum_marks,
                                        'cbse_student_subject_marks_id'  => $student_value->cbse_student_subject_marks_id,
                                        'marks'                          => $student_value->marks,
                                        'note'                           => $student_value->note,
                                        'is_absent'                      => isset($student_value->is_absent) ? $student_value->is_absent : 0,
                                        'cbse_exam_timetable_assessment_type_id' => isset($student_value->cbse_exam_timetable_assessment_type_id) ? $student_value->cbse_exam_timetable_assessment_type_id : null
                                    ],
                                ],
                            ],
                        ],
                    ];

                    $students[$student_group_key]['terms'][$student_value->cbse_term_id]['exams'][$student_value->id] = $new_exam;
                    $students[$student_group_key]['terms'][$student_value->cbse_term_id]['term_total_assessments'] += 1;

                    if ($student_value->remarkexam_id == $remarkexam_id) {
                        $students[$student_group_key]['remark'] = $student_value->remark;
                    }
                } elseif (!array_key_exists($student_value->subject_id, $students[$student_group_key]['terms'][$student_value->cbse_term_id]['exams'][$student_value->id]['subjects'])) {

                    $new_subject = [
                        'subject_id'       => $student_value->subject_id,
                        'subject_name'     => $student_value->subject_name,
                        'subject_code'     => $student_value->subject_code,
                        'exam_assessments' => [
                            $student_value->cbse_exam_assessment_type_id => [
                                'cbse_exam_assessment_type_name' => $student_value->cbse_exam_assessment_type_name,
                                'cbse_exam_assessment_type_id'   => $student_value->cbse_exam_assessment_type_id,
                                'cbse_exam_assessment_type_code' => $student_value->cbse_exam_assessment_type_code,
                                'maximum_marks'                  => $student_value->maximum_marks,
                                'cbse_student_subject_marks_id'  => $student_value->cbse_student_subject_marks_id,
                                'marks'                          => $student_value->marks,
                                'note'                           => $student_value->note,
                                'is_absent'                      => isset($student_value->is_absent) ? $student_value->is_absent : 0,
                                'cbse_exam_timetable_assessment_type_id' => isset($student_value->cbse_exam_timetable_assessment_type_id) ? $student_value->cbse_exam_timetable_assessment_type_id : null
                            ],
                        ],
                    ];

                    $students[$student_group_key]['terms'][$student_value->cbse_term_id]['exams'][$student_value->id]['subjects'][$student_value->subject_id] = $new_subject;

                    $students[$student_group_key]['terms'][$student_value->cbse_term_id]['term_total_assessments'] += 1;

                    if ($student_value->remarkexam_id == $remarkexam_id) {
                        $students[$student_group_key]['remark'] = $student_value->remark;
                    }
                } elseif (!array_key_exists($student_value->cbse_exam_assessment_type_id, $students[$student_group_key]['terms'][$student_value->cbse_term_id]['exams'][$student_value->id]['subjects'][$student_value->subject_id]['exam_assessments'])) {

                    $new_assesment = [
                        'cbse_exam_assessment_type_name' => $student_value->cbse_exam_assessment_type_name,
                        'cbse_exam_assessment_type_id'   => $student_value->cbse_exam_assessment_type_id,
                        'cbse_exam_assessment_type_code' => $student_value->cbse_exam_assessment_type_code,
                        'maximum_marks'                  => $student_value->maximum_marks,
                        'cbse_student_subject_marks_id'  => $student_value->cbse_student_subject_marks_id,
                        'marks'                          => $student_value->marks,
                        'note'                           => $student_value->note,
                        'is_absent'                      => isset($student_value->is_absent) ? $student_value->is_absent : 0,
                        'cbse_exam_timetable_assessment_type_id' => isset($student_value->cbse_exam_timetable_assessment_type_id) ? $student_value->cbse_exam_timetable_assessment_type_id : null
                    ];

                    $students[$student_group_key]['terms'][$student_value->cbse_term_id]['exams'][$student_value->id]['subjects'][$student_value->subject_id]['exam_assessments'][$student_value->cbse_exam_assessment_type_id] = $new_assesment;
                    $students[$student_group_key]['terms'][$student_value->cbse_term_id]['term_total_assessments'] += 1;
                    $students[$student_group_key]['terms'][$student_value->cbse_term_id]['exams'][$student_value->id]['total_assessments'] = count($students[$student_group_key]['terms'][$student_value->cbse_term_id]['exams'][$student_value->id]['subjects'][$student_value->subject_id]['exam_assessments']);
                    if ($student_value->remarkexam_id == $remarkexam_id) {
                        $students[$student_group_key]['remark'] = $student_value->remark;
                    }
                }
            } else {
                $students[$student_group_key] = [
                    'student_id'         => $student_value->student_id,
                    'student_session_id' => isset($student_value->student_session_id) ? $student_value->student_session_id : $student_value->student_id,
                    'firstname'          => isset($student_value->firstname) ? $student_value->firstname : '',
                    'middlename'         => isset($student_value->middlename) ? $student_value->middlename : '',
                    'lastname'           => isset($student_value->lastname) ? $student_value->lastname : '',
                    'mobileno'           => isset($student_value->mobileno) ? $student_value->mobileno : '',
                    'email'              => isset($student_value->email) ? $student_value->email : '',
                    'religion'           => isset($student_value->religion) ? $student_value->religion : '',
                    'guardian_name'      => isset($student_value->guardian_name) ? $student_value->guardian_name : '',
                    'guardian_phone'     => isset($student_value->guardian_phone) ? $student_value->guardian_phone : '',
                    'dob'                => isset($student_value->dob) ? $student_value->dob : '',
                    'admission_no'       => isset($student_value->admission_no) ? $student_value->admission_no : '',
                    'father_name'        => isset($student_value->father_name) ? $student_value->father_name : '',
                    'mother_name'        => isset($student_value->mother_name) ? $student_value->mother_name : '',
                    'class_id'           => isset($student_value->class_id) ? $student_value->class_id : '',
                    'class'              => isset($student_value->class) ? $student_value->class : '',
                    'section_id'         => isset($student_value->section_id) ? $student_value->section_id : '',
                    'section'            => isset($student_value->section) ? $student_value->section : '',
                    'roll_no'            => isset($student_value->roll_no) ? $student_value->roll_no : '',
                    'student_image'      => isset($student_value->image) ? $student_value->image : '',
                    'gender'             => isset($student_value->gender) ? $student_value->gender : '',
                    'rank'               => isset($student_value->rank) ? $student_value->rank : '',
                    'subject_rank'       => isset($student_value->subject_rank) ? [
                        $student_value->subject_id => $student_value->subject_rank
                    ] : [],
                    'terms'              => [
                        $student_value->cbse_term_id => [

                            'cbse_term_id'           => $student_value->cbse_term_id,
                            'cbse_term_name'         => $student_value->cbse_term_name,
                            'cbse_term_code'         => $student_value->cbse_term_code,
                            'cbse_term_weight'       => isset($student_value->cbse_template_terms_weightage) ? $student_value->cbse_template_terms_weightage : (isset($student_value->weightage) ? $student_value->weightage : 0),
                            'term_total_assessments' => 1,

                            'exams'                  => [
                                $student_value->id => [
                                    'name'               => $student_value->name,
                                    'total_assessments'  => 1,
                                    'total_present_days' => isset($student_value->total_present_days) ? $student_value->total_present_days : 0,
                                    'total_working_days' => isset($student_value->total_working_days) ? $student_value->total_working_days : 0,
                                    'subjects'           => [
                                        $student_value->subject_id => [
                                            'subject_id'       => $student_value->subject_id,
                                            'subject_name'     => $student_value->subject_name,
                                            'subject_code'     => $student_value->subject_code,
                                            'exam_assessments' => [
                                                $student_value->cbse_exam_assessment_type_id => [
                                                    'cbse_exam_assessment_type_name' => $student_value->cbse_exam_assessment_type_name,
                                                    'cbse_exam_assessment_type_id'   => $student_value->cbse_exam_assessment_type_id,
                                                    'cbse_exam_assessment_type_code' => $student_value->cbse_exam_assessment_type_code,
                                                    'maximum_marks'                  => $student_value->maximum_marks,
                                                    'cbse_student_subject_marks_id'  => $student_value->cbse_student_subject_marks_id,
                                                    'marks'                          => $student_value->marks,
                                                    'note'                           => $student_value->note,
                                                    'is_absent'                      => isset($student_value->is_absent) ? $student_value->is_absent : 0,
                                                    'cbse_exam_timetable_assessment_type_id' => isset($student_value->cbse_exam_timetable_assessment_type_id) ? $student_value->cbse_exam_timetable_assessment_type_id : null
                                                ],

                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ];
                
                // Set subject rank properly for the initial setup
                if(isset($student_value->subject_rank)) {
                    $students[$student_group_key]['subject_rank'][$student_value->subject_id] = $student_value->subject_rank;
                }

                if ($student_value->remarkexam_id == $remarkexam_id) {
                    $students[$student_group_key]['remark'] = $student_value->remark;
                }
            }
        }

        return $students;
    }
}
