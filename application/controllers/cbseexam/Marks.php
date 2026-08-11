<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Marks extends MY_Addon_CBSEController
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->model('cbseexam/cbseexam_admitcard_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_marks', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'cbse_exam');
        $this->session->set_userdata('sub_menu', 'cbse_exam/marks');
        
        $data['title'] = 'Marks Register';
        $data['classes'] = $this->class_model->get();
        $data['all_exams'] = $this->cbseexam_exam_model->get_all_session_exams();

        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/marks/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function get_sections_multi()
    {
        $class_ids = $this->input->post('class_ids'); // array
        $sections = array();
        if (!empty($class_ids) && is_array($class_ids)) {
            foreach ($class_ids as $class_id) {
                $data = $this->section_model->getClassBySection($class_id);
                foreach ($data as $sec) {
                    $sections[$sec['section_id']] = $sec; // Keep unique by section_id
                }
            }
        }
        echo json_encode(array_values($sections));
    }

    public function get_exams_multi()
    {
        $class_ids = $this->input->post('class_ids');
        $section_ids = $this->input->post('section_ids');
        $exams = array();
        
        if (!empty($class_ids) && is_array($class_ids) && !empty($section_ids) && is_array($section_ids)) {
            foreach ($class_ids as $class_id) {
                foreach ($section_ids as $section_id) {
                    $data = $this->cbseexam_admitcard_model->get_cbse_exam_list($class_id, $section_id);
                    foreach ($data as $ex) {
                        $exams[$ex->id] = $ex; // Keep unique by exam id
                    }
                }
            }
        }
        echo json_encode(array_values($exams));
    }

    public function subjectstudent_multi()
    {
        $data['timetable_id'] = $this->input->post('timetable_id');
        $data['subject_id'] = $this->input->post('subject_id');
        $data['exam_id'] = $this->input->post('exam_id');
        $class_ids = $this->input->post('class_ids');
        $section_ids = $this->input->post('section_ids');
        
        if (!is_array($class_ids)) $class_ids = array();
        if (!is_array($section_ids)) $section_ids = array();
        
        $examdetails = $this->cbseexam_exam_model->get_exambyId($data['exam_id']);
        $data['exam'] = $examdetails;
        
        $resultlist = $this->cbseexam_exam_model->get_markexamstudents_multi($data['timetable_id'], $class_ids, $section_ids);
        $data['resultlist'] = $resultlist;
        
        $data['exam_assessment_types'] = $this->cbseexam_exam_model->get_exam_subject_assessment_types($examdetails['cbse_exam_assessment_id'], $data['timetable_id']);
        $subject_detail = $this->batchsubject_model->getExamSubject($data['subject_id']);
        $data['subject_detail'] = $subject_detail;
        $data['sch_setting'] = $this->sch_setting_detail;

        // Grade bands for this exam so marks can be auto-graded live during entry.
        $data['grade_ranges'] = array();
        if (!empty($examdetails['cbse_exam_grade_id'])) {
            $data['grade_ranges'] = $this->db->select('name, minimum_percentage, maximum_percentage')
                ->from('cbse_exam_grades_range')
                ->where('cbse_exam_grade_id', $examdetails['cbse_exam_grade_id'])
                ->order_by('minimum_percentage', 'desc')
                ->get()->result_array();
        }
        
        $student_exam_page = $this->load->view('cbseexam/exam/_partialstudentmarkEntry', $data, true);
        $array = array('status' => '1', 'error' => '', 'page' => $student_exam_page);
        echo json_encode($array);
    }

    public function cbse_reportcard($exam_id = null, $class_id = null, $section_id = null)
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_marks', 'can_view')) {
            access_denied();
        }
        // Accept path segments (preferred - avoids query-string rewrite issues)
        // and fall back to GET params.
        if (!$exam_id) $exam_id = $this->input->get('exam_id');
        if (!$class_id) $class_id = $this->input->get('class_id');
        if (!$section_id) $section_id = $this->input->get('section_id');

        if (!$exam_id || !$class_id) {
            show_error("Please select an exam and class to generate report cards.");
        }

        $class_ids = is_array($class_id) ? $class_id : array($class_id);
        $section_ids = $section_id ? (is_array($section_id) ? $section_id : array($section_id)) : array();

        $data['card'] = $this->cbseexam_exam_model->get_cbse_reportcard_data($exam_id, $class_ids, $section_ids);
        if (!$data['card']) {
            show_error("Invalid exam selected.");
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['school'] = $this->setting_model->getSetting();

        $this->load->view('cbseexam/marks/reportcard', $data);
    }

    public function subject_max_marks()
    {
        $exam_id = $this->input->post('exam_id');
        $class_id = $this->input->post('class_id');
        if (!$exam_id || !$class_id) {
            echo json_encode(array('status' => 0, 'error' => 'Please select an exam and a class.'));
            return;
        }
        $subjects = $this->cbseexam_exam_model->get_subject_max_marks($exam_id, $class_id);
        echo json_encode(array('status' => 1, 'subjects' => $subjects));
    }

    public function save_subject_max_marks()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_marks', 'can_edit')) {
            access_denied();
        }
        $marks = $this->input->post('marks'); // array: tat_id => max value ('' clears the override)
        if (!is_array($marks) || empty($marks)) {
            echo json_encode(array('status' => 0, 'error' => 'No max marks submitted.'));
            return;
        }
        // Reject values that are present but not a valid non-negative number.
        foreach ($marks as $tat_id => $val) {
            if ($val !== '' && (!is_numeric($val) || (float)$val < 0)) {
                echo json_encode(array('status' => 0, 'error' => 'Max marks must be a positive number.'));
                return;
            }
        }
        $res = $this->cbseexam_exam_model->save_subject_max_marks($marks);
        echo json_encode($res
            ? array('status' => 1, 'message' => 'Subject max marks updated.')
            : array('status' => 0, 'error' => 'Could not save max marks.'));
    }

    public function get_bulk_exam_classes()
    {
        $exam_id = $this->input->post('exam_id');
        if (!$exam_id) {
            echo json_encode(array());
            return;
        }
        echo json_encode($this->cbseexam_exam_model->get_bulk_exam_classes($exam_id));
    }

    public function export_bulk_template($exam_id = null, $class_id = null)
    {
        if (!$exam_id) {
            $exam_id = $this->input->get('exam_id');
        }
        if ($class_id === null) {
            $class_id = $this->input->get('class_id');
        }
        if (!$exam_id) {
            show_error("Exam ID is required");
        }
        if (!$class_id) {
            show_error("Please select a class before downloading the sample template.");
        }
        $class_id = (int)$class_id;

        $structure = $this->cbseexam_exam_model->get_bulk_exam_structure($exam_id);
        if (!$structure) {
            show_error("Invalid Exam ID");
        }

        $exam = $structure['exam'];
        $subjects = $structure['subjects'];
        $subject_class_map = $structure['subject_class_map'];
        // Only subjects actually offered to the selected class.
        $subjects = array_values(array_filter($subjects, function ($sub) use ($subject_class_map, $class_id) {
            return isset($subject_class_map[$sub['subject_id']][$class_id]);
        }));
        // Only students of the selected class (all its sections).
        $students_data = $this->cbseexam_exam_model->get_bulk_exam_students_with_marks($exam_id, array($class_id));
        $students = $students_data['students'];
        $existing_marks = $students_data['existing_marks'];

        $class_label = '';
        foreach ($students as $std) {
            if (!empty($std['class_name'])) { $class_label = $std['class_name']; break; }
        }
        $filename = "CBSE_Marks_Template_" . preg_replace('/[^A-Za-z0-9_\-]/', '_', $exam['name'])
            . ($class_label ? "_" . preg_replace('/[^A-Za-z0-9_\-]/', '_', $class_label) : "") . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

        // Clean Header (Minimal format: S.N, SrNo, Roll No, Student Name, followed by unique subjects)
        $header = array('S.N', 'SrNo', 'Roll No', 'Student Name');
        
        $col_map = array();
        foreach ($subjects as $sub) {
            if (!empty($sub['assessment_types'])) {
                foreach ($sub['assessment_types'] as $at) {
                    $at_id = $at->id;
                    $max_m = $at->maximum_marks;
                    $title = $sub['subject_name'];
                    if (count($sub['assessment_types']) > 1 || !empty($at->name)) {
                        $title .= " - " . $at->name;
                    }
                    // Embed a machine-readable [ID:tt_at] tag so the parser can
                    // map the column back to the exact subject/assessment type
                    // regardless of how the visible subject name is formatted.
                    $col_name = $title . " (" . $max_m . ") [ID:" . $sub['id'] . "_" . $at_id . "]";
                    $header[] = $col_name;
                    $col_map[] = array(
                        'subject_id' => $sub['subject_id'],
                        'default_tt_id' => $sub['id'],
                        'at_id' => $at_id,
                        'tat_id' => $at->cbse_exam_timetable_assessment_type_id,
                        'max_marks' => $max_m
                    );
                }
            } else {
                $max_m = 100;
                $col_name = $sub['subject_name'] . " (" . $max_m . ") [ID:" . $sub['id'] . "_0]";
                $header[] = $col_name;
                $col_map[] = array(
                    'subject_id' => $sub['subject_id'],
                    'default_tt_id' => $sub['id'],
                    'at_id' => 0,
                    'tat_id' => 0,
                    'max_marks' => $max_m
                );
            }
        }

        fputcsv($output, $header);

        $sn = 1;
        foreach ($students as $std) {
            $row = array(
                $sn++,
                $std['admission_no'],
                $std['exam_roll_no'] ? $std['exam_roll_no'] : $std['roll_no'],
                trim($std['firstname'] . ' ' . $std['lastname'])
            );

            $s_id = $std['exam_student_id'];
            $cls_id = isset($std['class_id']) ? $std['class_id'] : 0;

            foreach ($col_map as $c) {
                $sub_id = $c['subject_id'];
                $tt_id = (isset($subject_class_map[$sub_id][$cls_id])) ? $subject_class_map[$sub_id][$cls_id] : $c['default_tt_id'];
                $at_id = $c['at_id'];
                $mark_val = '';

                if (isset($existing_marks[$s_id][$tt_id][$at_id])) {
                    $m = $existing_marks[$s_id][$tt_id][$at_id];
                    if ($m['is_absent'] == 1) {
                        $mark_val = 'A';
                    } else {
                        $mark_val = $m['marks'];
                    }
                }
                $row[] = $mark_val;
            }

            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    private function _normalize_sub_key($str)
    {
        $str = strtolower(trim($str));
        $str = preg_replace('/\(\d+\)/', '', $str);
        $str = preg_replace('/\[.*?\]/', '', $str);
        $str = trim(preg_replace('/[^a-z0-9]/', '', $str));

        if (in_array($str, array('math', 'maths', 'mathematics'))) return 'math';
        if (in_array($str, array('sci', 'science', 'generalscience'))) return 'science';
        if (in_array($str, array('comp', 'computer', 'computerscience'))) return 'comp';
        if (in_array($str, array('sst', 'socialscience', 'socialstudies', 'social'))) return 'sst';
        if (in_array($str, array('eng', 'english'))) return 'english';
        if (in_array($str, array('hin', 'hindi'))) return 'hindi';
        return $str;
    }

    private function _raw_sub_key($str)
    {
        $str = strtolower(trim($str));
        $str = preg_replace('/\(\d+\)/', '', $str);
        $str = preg_replace('/\[.*?\]/', '', $str);
        return trim(preg_replace('/[^a-z0-9]/', '', $str));
    }

    public function parse_bulk_marks()
    {
        $exam_id = $this->input->post('exam_id');
        if (!$exam_id || !isset($_FILES['file']['tmp_name']) || empty($_FILES['file']['tmp_name'])) {
            echo json_encode(array('status' => 0, 'error' => 'Please select an exam and upload a valid CSV file.'));
            return;
        }

        $structure = $this->cbseexam_exam_model->get_bulk_exam_structure($exam_id);
        if (!$structure) {
            echo json_encode(array('status' => 0, 'error' => 'Invalid CBSE Exam selected.'));
            return;
        }

        $subject_class_map = $structure['subject_class_map'];
        $students_data = $this->cbseexam_exam_model->get_bulk_exam_students_with_marks($exam_id);
        $students = $students_data['students'];

        $student_map = array();
        foreach ($students as $std) {
            $raw_adm = trim($std['admission_no']);
            $clean_adm = preg_replace('/[^0-9A-Za-z_-]/', '', $raw_adm);
            $student_map[$raw_adm] = $std;
            $student_map[$clean_adm] = $std;
            $student_map[ltrim($clean_adm, '0')] = $std;
        }

        $filePath = $_FILES['file']['tmp_name'];
        $file = fopen($filePath, 'r');
        if (!$file) {
            echo json_encode(array('status' => 0, 'error' => 'Could not read uploaded file.'));
            return;
        }

        $bom = fread($file, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($file);
        }

        $header = fgetcsv($file, 10000, ",");
        if (!$header) {
            fclose($file);
            echo json_encode(array('status' => 0, 'error' => 'Uploaded CSV file is empty.'));
            return;
        }

        $adm_col_idx = -1;
        foreach ($header as $idx => $col_name) {
            $clean_col = strtolower(trim(preg_replace('/[^A-Za-z0-9]/', '', $col_name)));
            if (in_array($clean_col, array('srno', 'admissionno', 'admno', 'admission_no', 'sr_no'))) {
                $adm_col_idx = $idx;
                break;
            }
        }

        if ($adm_col_idx === -1) {
            $adm_col_idx = 1;
        }

        $subject_col_map = array();
        foreach ($header as $idx => $col_name) {
            $norm_col = $this->_normalize_sub_key($col_name);
            if (in_array($norm_col, array('srno', 'sn', 'rollno', 'studentname', 'class', 'section', 'gender', 'dob'))) {
                continue;
            }

            if (preg_match('/\[ID:(\d+)_(\d+)\]/', $col_name, $matches)) {
                $tt_id = (int)$matches[1];
                $at_id = (int)$matches[2];
                foreach ($structure['subjects'] as $sub) {
                    if ($sub['id'] == $tt_id) {
                        $at = !empty($sub['assessment_types']) ? $sub['assessment_types'][0] : null;
                        $subject_col_map[$idx] = array(
                            'subject_id' => $sub['subject_id'],
                            'default_tt_id' => $tt_id,
                            'at_id' => $at ? $at->id : $at_id,
                            'tat_id' => $at ? $at->cbse_exam_timetable_assessment_type_id : 0,
                            'max_marks' => $at ? $at->maximum_marks : 100,
                            'subject_name' => $sub['subject_name']
                        );
                        break;
                    }
                }
            } else {
                $matched = false;
                // 1st Pass: Exact normalized match
                foreach ($structure['subjects'] as $sub) {
                    $norm_sub = $this->_normalize_sub_key($sub['subject_name']);
                    $norm_code = $this->_normalize_sub_key($sub['subject_code']);

                    if ($norm_sub !== '' && ($norm_col === $norm_sub || $norm_col === $norm_code)) {
                        $at = !empty($sub['assessment_types']) ? $sub['assessment_types'][0] : null;
                        $subject_col_map[$idx] = array(
                            'subject_id' => $sub['subject_id'],
                            'default_tt_id' => $sub['id'],
                            'at_id' => $at ? $at->id : 0,
                            'tat_id' => $at ? $at->cbse_exam_timetable_assessment_type_id : 0,
                            'max_marks' => $at ? $at->maximum_marks : 100,
                            'subject_name' => $sub['subject_name']
                        );
                        $matched = true;
                        break;
                    }
                }

                // 2nd Pass: Word boundary match only if exact match fails
                if (!$matched && strlen($norm_col) >= 4) {
                    foreach ($structure['subjects'] as $sub) {
                        $norm_sub = $this->_normalize_sub_key($sub['subject_name']);
                        if ($norm_sub !== '' && preg_match('/\b' . preg_quote($norm_col, '/') . '\b/', $norm_sub)) {
                            $at = !empty($sub['assessment_types']) ? $sub['assessment_types'][0] : null;
                            $subject_col_map[$idx] = array(
                                'subject_id' => $sub['subject_id'],
                                'default_tt_id' => $sub['id'],
                                'at_id' => $at ? $at->id : 0,
                                'tat_id' => $at ? $at->cbse_exam_timetable_assessment_type_id : 0,
                                'max_marks' => $at ? $at->maximum_marks : 100,
                                'subject_name' => $sub['subject_name']
                            );
                            $matched = true;
                            break;
                        }
                    }
                }

                // 3rd Pass: Raw prefix match (handles template headers like
                // "ENGLISH - SBBT - II (100)" where a suffix is appended to the
                // subject name). Longest matching subject name/code wins so
                // "POLITICAL SCIENCE" is not swallowed by "SCIENCE", etc.
                if (!$matched) {
                    $raw_col = $this->_raw_sub_key($col_name);
                    $best = null;
                    $best_len = 0;
                    if ($raw_col !== '' && !in_array($raw_col, array('sn', 'srno', 'rollno', 'studentname', 'admissionno'))) {
                        foreach ($structure['subjects'] as $sub) {
                            foreach (array($sub['subject_name'], $sub['subject_code']) as $cand) {
                                $raw_sub = $this->_raw_sub_key($cand);
                                if ($raw_sub !== '' && strlen($raw_sub) > $best_len && strpos($raw_col, $raw_sub) === 0) {
                                    $best = $sub;
                                    $best_len = strlen($raw_sub);
                                }
                            }
                        }
                    }
                    if ($best) {
                        $at = !empty($best['assessment_types']) ? $best['assessment_types'][0] : null;
                        $subject_col_map[$idx] = array(
                            'subject_id' => $best['subject_id'],
                            'default_tt_id' => $best['id'],
                            'at_id' => $at ? $at->id : 0,
                            'tat_id' => $at ? $at->cbse_exam_timetable_assessment_type_id : 0,
                            'max_marks' => $at ? $at->maximum_marks : 100,
                            'subject_name' => $best['subject_name']
                        );
                        $matched = true;
                    }
                }
            }
        }

        $parsed_rows = array();
        $total_students_found = 0;
        $errors_found = 0;

        while (($row = fgetcsv($file, 10000, ",")) !== false) {
            if (empty($row) || (count($row) == 1 && trim($row[0]) == '')) {
                continue;
            }

            $adm_no_raw = isset($row[$adm_col_idx]) ? trim($row[$adm_col_idx]) : '';
            if ($adm_no_raw === '' || strtolower($adm_no_raw) === 'srno' || strtolower($adm_no_raw) === 'admission no' || strtolower($adm_no_raw) === 's.n') {
                continue;
            }

            $adm_no_clean = preg_replace('/[^0-9A-Za-z_-]/', '', $adm_no_raw);
            $adm_no_ltrim = ltrim($adm_no_clean, '0');

            $student_info = null;
            if (isset($student_map[$adm_no_raw])) {
                $student_info = $student_map[$adm_no_raw];
            } elseif (isset($student_map[$adm_no_clean])) {
                $student_info = $student_map[$adm_no_clean];
            } elseif (isset($student_map[$adm_no_ltrim])) {
                $student_info = $student_map[$adm_no_ltrim];
            }

            $row_status = 'valid';
            $row_errors = array();

            if (!$student_info) {
                $master_student = $this->db->select('students.*, classes.class as class_name, sections.section as section_name, student_session.class_id, student_session.id as student_session_id')
                    ->from('students')
                    ->join('student_session', 'student_session.student_id = students.id and student_session.session_id = ' . $this->current_session, 'inner')
                    ->join('classes', 'classes.id = student_session.class_id', 'left')
                    ->join('sections', 'sections.id = student_session.section_id', 'left')
                    ->group_start()
                        ->where('students.admission_no', $adm_no_raw)
                        ->or_where('students.admission_no', $adm_no_clean)
                        ->or_where('students.admission_no', $adm_no_ltrim)
                    ->group_end()
                    ->get()->row_array();

                if ($master_student) {
                    $es_row = $this->db->select('id')->from('cbse_exam_students')
                        ->where('cbse_exam_id', $exam_id)
                        ->where('student_session_id', $master_student['student_session_id'])
                        ->get()->row_array();

                    if ($es_row) {
                        $exam_student_id = $es_row['id'];
                    } else {
                        $this->db->insert('cbse_exam_students', array(
                            'cbse_exam_id' => $exam_id,
                            'student_session_id' => $master_student['student_session_id'],
                            'roll_no' => $master_student['roll_no'] ? $master_student['roll_no'] : 0,
                            'total_present_days' => 0
                        ));
                        $exam_student_id = $this->db->insert_id();
                    }

                    $student_info = array(
                        'exam_student_id' => $exam_student_id,
                        'firstname' => $master_student['firstname'],
                        'lastname' => $master_student['lastname'],
                        'class_name' => $master_student['class_name'] ? $master_student['class_name'] : 'N/A',
                        'section_name' => $master_student['section_name'] ? $master_student['section_name'] : 'N/A',
                        'class_id' => $master_student['class_id'],
                        'roll_no' => $master_student['roll_no'],
                        'exam_roll_no' => $master_student['roll_no']
                    );
                    $total_students_found++;
                } else {
                    $row_status = 'invalid_student';
                    $row_errors[] = "Admission No / SrNo '$adm_no_raw' not found in database.";
                    $errors_found++;
                }
            } else {
                $total_students_found++;
            }

            $marks_entry = array();
            foreach ($subject_col_map as $col_idx => $meta) {
                $raw_val = isset($row[$col_idx]) ? trim($row[$col_idx]) : '';
                $is_absent = 0;
                $numeric_mark = 0;
                $cell_status = 'ok';

                $sub_id = $meta['subject_id'];
                $cls_id = (isset($student_info['class_id'])) ? $student_info['class_id'] : 0;

                // Is this subject actually offered to THIS student's class?
                // (Each subject has a per-class timetable; if the student's class
                // has no timetable row for it, the subject is not applicable and
                // must NOT be uploaded to another class's timetable.)
                $is_applicable = ($student_info && isset($subject_class_map[$sub_id][$cls_id]));

                if (!$is_applicable) {
                    // Not offered to this class - flag it and do not import.
                    $cell_status = 'not_applicable';
                    $marks_entry[] = array(
                        'subject_id' => $sub_id,
                        'tt_id' => 0,
                        'at_id' => $meta['at_id'],
                        'tat_id' => 0,
                        'subject_name' => $meta['subject_name'],
                        'max_marks' => $meta['max_marks'],
                        'raw_value' => $raw_val,
                        'numeric_mark' => 0,
                        'is_absent' => 0,
                        'applicable' => 0,
                        'cell_status' => $cell_status
                    );
                    continue;
                }

                if (strtoupper($raw_val) === 'A' || strtoupper($raw_val) === 'ABS') {
                    $is_absent = 1;
                    $numeric_mark = 0;
                    $cell_status = 'absent';
                } elseif ($raw_val !== '' && is_numeric($raw_val)) {
                    $val_float = (float)$raw_val;
                    if ($val_float < 0 || $val_float > $meta['max_marks']) {
                        $cell_status = 'out_of_range';
                        $row_status = 'error';
                        $row_errors[] = $meta['subject_name'] . " mark ($val_float) exceeds max mark (" . $meta['max_marks'] . ").";
                        $errors_found++;
                    }
                    $numeric_mark = $val_float;
                } elseif ($raw_val !== '') {
                    $cell_status = 'invalid_format';
                    $row_status = 'error';
                    $row_errors[] = $meta['subject_name'] . " invalid mark '$raw_val'.";
                    $errors_found++;
                }

                $tt_id = $subject_class_map[$sub_id][$cls_id];
                $at_id = $meta['at_id'];

                $tat_id = 0;
                if (isset($structure['tat_map'][$tt_id][$at_id])) {
                    $tat_id = $structure['tat_map'][$tt_id][$at_id];
                } elseif (isset($meta['tat_id'])) {
                    $tat_id = $meta['tat_id'];
                }

                $marks_entry[] = array(
                    'subject_id' => $sub_id,
                    'tt_id' => $tt_id,
                    'at_id' => $at_id,
                    'tat_id' => $tat_id,
                    'subject_name' => $meta['subject_name'],
                    'max_marks' => $meta['max_marks'],
                    'raw_value' => $raw_val,
                    'numeric_mark' => $numeric_mark,
                    'is_absent' => $is_absent,
                    'applicable' => 1,
                    'cell_status' => $cell_status
                );
            }

            $parsed_rows[] = array(
                'admission_no' => $adm_no_raw,
                'student' => $student_info,
                'status' => $row_status,
                'errors' => $row_errors,
                'marks' => $marks_entry
            );
        }

        fclose($file);

        echo json_encode(array(
            'status' => 1,
            'exam' => $structure['exam'],
            'columns' => array_values($subject_col_map),
            'parsed_rows' => $parsed_rows,
            'total_students_found' => $total_students_found,
            'errors_found' => $errors_found
        ));
    }

    public function import_bulk_marks()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_marks', 'can_edit')) {
            access_denied();
        }

        $exam_id = $this->input->post('exam_id');
        $rows_json = $this->input->post('rows_data');
        
        if (!$exam_id || empty($rows_json)) {
            echo json_encode(array('status' => 0, 'error' => 'No mark data submitted.'));
            return;
        }

        $rows = json_decode($rows_json, true);
        if (!is_array($rows) || empty($rows)) {
            echo json_encode(array('status' => 0, 'error' => 'Invalid data payload.'));
            return;
        }

        $bulk_marks_data = array();
        $student_count = 0;

        foreach ($rows as $r) {
            if ($r['status'] === 'invalid_student' || !isset($r['student']['exam_student_id']) || empty($r['student']['exam_student_id'])) {
                continue;
            }

            $exam_student_id = $r['student']['exam_student_id'];
            $student_count++;

            foreach ($r['marks'] as $m) {
                // Subject not offered to this student's class - never import.
                if (isset($m['applicable']) && !$m['applicable']) {
                    continue;
                }
                $raw = trim((string)$m['raw_value']);
                // A blank cell means "no mark entered" - skip it so we never
                // overwrite an existing mark with 0. A real "0" typed in the
                // file is non-empty and still saves below.
                if ($raw === '' && $m['is_absent'] == 0) {
                    continue;
                }

                $bulk_marks_data[] = array(
                    'cbse_exam_student_id' => $exam_student_id,
                    'cbse_exam_timetable_id' => $m['tt_id'],
                    'cbse_exam_assessment_type_id' => $m['at_id'],
                    'cbse_exam_timetable_assessment_type_id' => $m['tat_id'],
                    'marks' => $m['is_absent'] == 1 ? 0 : (float)$m['numeric_mark'],
                    'is_absent' => $m['is_absent'],
                    'note' => ''
                );
            }
        }

        if (empty($bulk_marks_data)) {
            echo json_encode(array('status' => 0, 'error' => 'No valid student marks found to import.'));
            return;
        }

        $res = $this->cbseexam_exam_model->save_bulk_exam_marks($exam_id, $bulk_marks_data);

        if ($res) {
            echo json_encode(array(
                'status' => 1,
                'message' => "Successfully imported marks for $student_count students."
            ));
        } else {
            echo json_encode(array('status' => 0, 'error' => 'Database update failed. Transaction rolled back.'));
        }
    }
}

