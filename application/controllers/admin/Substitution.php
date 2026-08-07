<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Substitution extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('substitution_model');
        $this->load->model('staff_model');
        $this->load->model('subjecttimetable_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('substitution_planning', 'can_view')) {
            // Using a generic check or letting it pass if role not defined yet
            // access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'substitution/index');
        $data['title'] = 'Substitute Planning';
        $data['staff_list'] = $this->staff_model->get();

        $this->load->view('layout/header');
        $this->load->view('admin/substitution/index', $data);
        $this->load->view('layout/footer');
    }

    public function check_leave_status()
    {
        $staff_id = $this->input->post('staff_id');
        $date = $this->input->post('date');
        $formatted_date = date('Y-m-d', $this->customlib->datetostrtotime($date));

        $leave = $this->substitution_model->get_approved_leave($staff_id, $formatted_date);
        
        if ($leave) {
            echo json_encode(['status' => 1, 'msg' => 'Approved Leave Found', 'leave' => $leave]);
        } else {
            echo json_encode(['status' => 0, 'msg' => 'Unplanned Absence']);
        }
    }

    public function get_absent_staff()
    {
        $date = $this->input->post('date');
        $formatted_date = date('Y-m-d', $this->customlib->datetostrtotime($date));
        $res = $this->substitution_model->get_absent_staff_by_date($formatted_date);
        echo json_encode($res);
    }

    public function get_staff_timetable()
    {
        ob_start();
        $staff_id = $this->input->post('staff_id');
        $date = $this->input->post('date');
        $timestamp = $this->customlib->datetostrtotime($date);
        $day_of_week = date('l', $timestamp);

        $timetable = $this->subjecttimetable_model->getByStaffandDay($staff_id, $day_of_week);
        if (!$timetable) {
            $timetable = [];
        }

        // Fetch already substituted periods for this specific date and absent teacher
        $current_session = $this->setting_model->getCurrentSession();
        $this->db->where('date', date('Y-m-d', $timestamp));
        $this->db->where('absent_staff_id', $staff_id);
        $this->db->where('session_id', $current_session);
        $existing_subs = $this->db->get('staff_substitutions')->result_array();
        $sub_map = [];
        foreach($existing_subs as $es) {
            $sub_map[$es['subject_timetable_id']] = [
                'substitute_staff_id' => $es['substitute_staff_id'],
                'substitute_subject_id' => $es['substitute_subject_id']
            ];
        }

        // Fetch all staff members
        $all_staff = $this->staff_model->get();

        foreach ($timetable as &$t) {
            $t->substitute_staff_id = $sub_map[$t->id]['substitute_staff_id'] ?? '';
            $t->substitute_subject_id = $sub_map[$t->id]['substitute_subject_id'] ?? '';
            $t->available_subjects = $this->subjectgroup_model->getAllsubjectByClassSection($t->class_id, $t->section_id);

            // Compute exact period index based on the master class-section day timetable
            $class_day_tt = $this->subjecttimetable_model->getSubjectByClassandSectionDay($t->class_id, $t->section_id, $day_of_week);
            $p_index = 0;
            $found_period = null;
            if (!empty($class_day_tt)) {
                foreach ($class_day_tt as $c_slot) {
                    if (!isset($c_slot->period_type) || $c_slot->period_type != 'break') {
                        $p_index++;
                    }
                    if ($c_slot->id == $t->id) {
                        $found_period = $p_index;
                        break;
                    }
                }
            }
            $t->period_number = $found_period;

            // Get list of absent staff for this date to flag them
            $formatted_date_str = date('Y-m-d', $timestamp);
            $absent_data = $this->substitution_model->get_absent_staff_by_date($formatted_date_str);
            $absent_staff_ids = [];
            $absent_details_map = [];
            if (!empty($absent_data['absent_staff'])) {
                foreach ($absent_data['absent_staff'] as $as_item) {
                    $absent_staff_ids[] = $as_item['id'];
                    $l_label = !empty($as_item['leave_type']) ? $as_item['leave_type'] : 'Absent / On Leave';
                    $absent_details_map[$as_item['id']] = $l_label;
                }
            }

            // Partition staff into Available (free/not assigned), Absent/On Leave, and Busy
            $available_staff = [];
            $absent_staff_list = [];
            $busy_staff = [];

            foreach ($all_staff as $staff_member) {
                if ($staff_member['id'] == $staff_id || $staff_member['is_active'] != 1) {
                    continue;
                }

                // Check if staff is absent / on leave today
                if (in_array($staff_member['id'], $absent_staff_ids)) {
                    $staff_member['leave_info'] = $absent_details_map[$staff_member['id']] ?? 'Absent';
                    $absent_staff_list[] = $staff_member;
                    continue;
                }
                
                $conflict = $this->substitution_model->check_conflict(
                    $staff_member['id'], 
                    $day_of_week, 
                    $t->time_from, 
                    $t->time_to, 
                    $formatted_date_str
                );

                if ($conflict) {
                    $c_label = ($conflict['conflict_type'] == 'substitution') ? 'Substituted in ' : 'Class ';
                    $staff_member['conflict_info'] = isset($conflict['class']) && isset($conflict['section']) 
                        ? $c_label . $conflict['class'] . ' (' . $conflict['section'] . ')'
                        : 'Scheduled';
                    $busy_staff[] = $staff_member;
                } else {
                    $available_staff[] = $staff_member;
                }
            }

            $t->available_staff = $available_staff;
            $t->absent_staff_list = $absent_staff_list;
            $t->busy_staff = $busy_staff;
        }

        $data['timetable'] = $timetable;
        $data['sub_map'] = $sub_map;
        $data['staff_list'] = $all_staff;
        $data['absent_staff_id'] = $staff_id;

        $page = $this->load->view('admin/substitution/_timetable_list', $data, true);
        file_put_contents('c:/wamp64/www/lms/ajax_log.txt', "Staff: $staff_id, Day: $day_of_week\nPage:\n$page\n");
        $errors = ob_get_clean();
        if(!empty($errors)) {
            file_put_contents('c:/wamp64/www/lms/ajax_errors.txt', $errors);
        }
        echo json_encode(['status' => 1, 'page' => $page]);
    }

    public function validate_conflict()
    {
        $substitute_staff_id = $this->input->post('substitute_staff_id');
        $day = $this->input->post('day');
        $time_from = $this->input->post('time_from');
        $time_to = $this->input->post('time_to');
        $date = $this->input->post('date');
        $formatted_date = date('Y-m-d', $this->customlib->datetostrtotime($date));

        $conflict = $this->substitution_model->check_conflict($substitute_staff_id, $day, $time_from, $time_to, $formatted_date);
        
        if ($conflict) {
            echo json_encode(['status' => 0, 'conflict' => $conflict]);
        } else {
            echo json_encode(['status' => 1]);
        }
    }

    public function save_substitution()
    {
        $date = $this->input->post('date');
        $formatted_date = date('Y-m-d', $this->customlib->datetostrtotime($date));
        $absent_staff_id = $this->input->post('absent_staff_id');
        $is_unplanned = $this->input->post('is_unplanned');
        $subs = $this->input->post('substitutions'); // Array of timetable_id => substitute_staff_id
        $sub_subjects = $this->input->post('substitute_subjects'); // Array of timetable_id => substitute_subject_id
        $overrides = $this->input->post('overrides'); // Array of timetable_id => conflict_timetable_id

        $data_batch = [];
        $admin_id = $this->customlib->getStaffID();
        $session_id = $this->setting_model->getCurrentSession();

        if (!empty($subs)) {
            // Remove existing substitutions for this teacher on this day
            $this->db->where('date', $formatted_date);
            $this->db->where('absent_staff_id', $absent_staff_id);
            $this->db->where('session_id', $session_id);
            $this->db->delete('staff_substitutions');

            foreach ($subs as $timetable_id => $sub_staff_id) {
                if ($sub_staff_id != '') {
                    $override_id = isset($overrides[$timetable_id]) ? $overrides[$timetable_id] : null;
                    $sub_subject_id = (isset($sub_subjects[$timetable_id]) && $sub_subjects[$timetable_id] != '') ? $sub_subjects[$timetable_id] : null;
                    $data_batch[] = [
                        'date' => $formatted_date,
                        'absent_staff_id' => $absent_staff_id,
                        'substitute_staff_id' => $sub_staff_id,
                        'substitute_subject_id' => $sub_subject_id,
                        'subject_timetable_id' => $timetable_id,
                        'is_unplanned' => $is_unplanned,
                        'override_conflict_timetable_id' => $override_id,
                        'session_id' => $session_id,
                        'created_by' => $admin_id,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }

            if (!empty($data_batch)) {
                $this->substitution_model->insert_substitutions($data_batch);
            }
        }

        echo json_encode(['status' => 1, 'msg' => 'Substitutions saved successfully']);
    }

    public function history()
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'substitution/history');
        $data['title'] = 'Substitution History';
        
        $date = $this->input->post('date');
        if (empty($date)) {
            $date = date($this->customlib->getSchoolDateFormat());
        }
        $staff_id = $this->input->post('staff_id');
        $data['search_date'] = $date;
        $data['search_staff_id'] = $staff_id;
        $formatted_date = null;
        if (!empty($date)) {
            $formatted_date = date('Y-m-d', $this->customlib->datetostrtotime($date));
        }

        $history = $this->substitution_model->get_substitution_history($formatted_date, $staff_id);
        
        // Calculate exact Period Number for each history log entry
        if (!empty($history)) {
            foreach ($history as &$h_item) {
                $day_str = !empty($h_item['day']) ? $h_item['day'] : date('l', strtotime($h_item['date']));
                $class_day_tt = $this->subjecttimetable_model->getSubjectByClassandSectionDay($h_item['class_id'], $h_item['section_id'], $day_str);
                $p_index = 0;
                $found_period = null;
                if (!empty($class_day_tt)) {
                    foreach ($class_day_tt as $c_slot) {
                        if (!isset($c_slot->period_type) || $c_slot->period_type != 'break') {
                            $p_index++;
                        }
                        if ($c_slot->id == $h_item['subject_timetable_id']) {
                            $found_period = $p_index;
                            break;
                        }
                    }
                }
                $h_item['period_number'] = $found_period;
            }
        }

        $data['history'] = $history;
        $data['staff_list'] = $this->staff_model->get();

        $this->load->view('layout/header');
        $this->load->view('admin/substitution/history', $data);
        $this->load->view('layout/footer');
    }

    public function todays_schedule()
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'substitution/todays_schedule');
        $data['title'] = 'Today\'s Schedule';
        
        $today = date('Y-m-d');
        $day_of_week = date('l');
        $session_id = $this->setting_model->getCurrentSession();

        // Get all classes and sections
        $this->load->model('class_model');
        $classes = $this->class_model->get();
        
        $schedule = [];
        foreach($classes as $class) {
            $sections = $this->section_model->getClassBySection($class['id']);
            foreach($sections as $section) {
                // Get timetable
                $timetable = $this->subjecttimetable_model->getSubjectByClassandSectionDay($class['id'], $section['section_id'], $day_of_week);
                
                // Get substitutions for today for this class/section
                if (!empty($timetable)) {
                    foreach($timetable as &$t) {
                        // Check if this period has a substitution today
                        $this->db->where('date', $today);
                        $this->db->where('subject_timetable_id', $t->id);
                        $this->db->where('session_id', $session_id);
                        $sub = $this->db->get('staff_substitutions')->row_array();
                        
                        if ($sub) {
                            $t->is_substituted = true;
                            $sub_staff = $this->staff_model->get($sub['substitute_staff_id']);
                            $t->name = $sub_staff['name'];
                            $t->surname = $sub_staff['surname'];
                            $t->employee_id = $sub_staff['employee_id'];
                        } else {
                            $t->is_substituted = false;
                        }

                        // Also check if the original teacher is substituting somewhere else, meaning they are absent here? 
                        // Wait, if they are absent here but NO substitute is assigned, it's just absent.
                        // We can mark "Absent" if they are in `staff_substitutions` as `absent_staff_id` for today but this specific period has no sub.
                        $this->db->where('date', $today);
                        $this->db->where('absent_staff_id', $t->staff_id);
                        $this->db->where('session_id', $session_id);
                        $absent = $this->db->get('staff_substitutions')->row_array();
                        if ($absent && !$sub) {
                            $t->is_absent_uncovered = true;
                        } else {
                            $t->is_absent_uncovered = false;
                        }
                    }
                }

                $schedule[$class['class'] . ' (' . $section['section'] . ')'] = $timetable;
            }
        }

        $data['schedule'] = $schedule;
        $data['day'] = $day_of_week;

        $this->load->view('layout/header');
        $this->load->view('admin/substitution/todays_schedule', $data);
        $this->load->view('layout/footer');
    }
}
?>
