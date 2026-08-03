<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Timetable extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("staff_model");
        $this->load->model("classteacher_model");
    }

    public function index()
    {

        if (!$this->rbac->hasPrivilege('class_time_table', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/timetable');
        $session            = $this->setting_model->getCurrentSession();
        $data['title']      = 'Exam Marks';
        $data['exam_id']    = "";
        $data['class_id']   = "";
        $data['section_id'] = "";

        $class             = $this->class_model->get();
        $data['classlist'] = $class;

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('group_id', $this->lang->line('subject_group'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableList', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $class_id           = $this->input->post('class_id');
            $section_id         = $this->input->post('section_id');
            $group_id           = $this->input->post('group_id');
            $data['class_id']   = $class_id;
            $data['section_id'] = $section_id;
            $result_subjects    = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);

            $getDaysnameList         = $this->customlib->getDaysname();
            $data['getDaysnameList'] = $getDaysnameList;
            $final_array             = array();
            if (!empty($result_subjects)) {
                foreach ($result_subjects as $subject_k => $subject_v) {
                    $result_array = array();
                    foreach ($getDaysnameList as $day_key => $day_value) {
                        $where_array = array(
                            'teacher_subject_id' => $subject_v['id'],
                            'day_name'           => $day_value,
                        );
                        $result = $this->timetable_model->get($where_array);
                        if (!empty($result)) {
                            $obj                      = new stdClass();
                            $obj->status              = "Yes";
                            $obj->start_time          = $result[0]['start_time'];
                            $obj->end_time            = $result[0]['end_time'];
                            $obj->room_no             = $result[0]['room_no'];
                            $result_array[$day_value] = $obj;
                        } else {
                            $obj                      = new stdClass();
                            $obj->status              = "No";
                            $obj->start_time          = "N/A";
                            $obj->end_time            = "N/A";
                            $obj->room_no             = "N/A";
                            $result_array[$day_value] = $obj;
                        }
                    }
                    $final_array[$subject_v['name']] = $result_array;
                }
            }

            $data['result_array'] = $final_array;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableList', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function mytimetable()
    {
        if (!$this->rbac->hasPrivilege('teachers_time_table', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'My Timetable';
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/timetable/mytimetable');
        $my_role  = $this->customlib->getStaffRole();
        $role     = json_decode($my_role);
        $is_admin = false;

        if ($role->id != "2") {
            $staff_list         = $this->staff_model->getEmployee('2');
            $data['staff_list'] = $staff_list;
            $is_admin           = true;
        }

        $staff_id          = $this->customlib->getStaffID();
        $data['timetable'] = array();
        $days              = $this->customlib->getDaysname();

        foreach ($days as $day_key => $day_value) {
            $data['timetable'][$day_value] = $this->subjecttimetable_model->getByStaffandDay($staff_id, $day_key);
        }

        $this->load->view('layout/header', $data);
        if ($is_admin) {
            $this->load->view('admin/timetable/admintimetable', $data);
        } else {
            $data['staff_id']=$staff_id;
            $this->load->view('admin/timetable/mytimetable', $data);
        }
        $this->load->view('layout/footer', $data);
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
            access_denied();
        }
        $data['title'] = $this->lang->line('mark_list');
        $mark          = $this->mark_model->get($id);
        $data['mark']  = $mark;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/timetable/timetableShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        $data['title'] = 'Mark List';
        $this->mark_model->remove($id);
        redirect('admin/timetable/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/timetable');

        $session            = $this->setting_model->getCurrentSession();
        $data['title']      = 'Exam Schedule';
        $data['subject_id'] = "";
        $data['class_id']   = "";
        $data['section_id'] = "";
        $exam               = $this->exam_model->get();
        $class              = $this->class_model->get('', $classteacher = 'yes');
        $data['examlist']   = $exam;
        $data['classlist']  = $class;
        $userdata           = $this->customlib->getUserData();
        $staff                   = $this->staff_model->getStaffbyrole(2);
        $data['staff']           = $staff;
        $data['subject']         = array();
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject_group'), 'trim|required|xss_clean');
        $class_id         = $this->input->post('class_id');
        $section_id       = $this->input->post('section_id');
        $subject_group_id = $this->input->post('subject_group_id');

        $data['class_id']         = $class_id;
        $data['section_id']       = $section_id;
        $data['subject_group_id'] = $subject_group_id;

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $getDaysnameList         = $this->customlib->getDaysname();
            $data['getDaysnameList'] = $getDaysnameList;
            $subject                 = $this->subjectgroup_model->getGroupsubjects($subject_group_id);
            $data['subject']         = $subject;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableCreate', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function classreport()
    {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/timetable');
        $session                 = $this->setting_model->getCurrentSession();
        $data['title']           = 'Exam Schedule';
        $data['subject_id']      = "";
        $data['class_id']        = "";
        $data['section_id']      = "";
        $exam                    = $this->exam_model->get();
        $class                   = $this->class_model->get('', $classteacher = 'yes');
        $data['examlist']        = $exam;
        $data['classlist']       = $class;
        $userdata                = $this->customlib->getUserData();
        $staff                   = $this->staff_model->getStaffbyrole(2);
        $data['staff']           = $staff;
        $data['subject']         = array();

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {
            if (isset($_POST['search'])) {

                $class_id    = $this->input->post('class_id');
                $section_id  = $this->input->post('section_id');
                $days        = $this->customlib->getDaysname();
                $days_record = array();
                foreach ($days as $day_key => $day_value) {
                    $class_id              = $this->input->post('class_id');
                    $section_id            = $this->input->post('section_id');
                    $days_record[$day_key] = $this->subjecttimetable_model->getSubjectByClassandSectionDay($class_id, $section_id, $day_key);
                }

                $data['timetable'] = $days_record;
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/timetable/classreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_edit')) {
            access_denied();
        }
        $data['title'] = $this->lang->line('edit_mark');
        $data['id']    = $id;
        $mark          = $this->mark_model->get($id);
        $data['mark']  = $mark;
        $this->form_validation->set_rules('name', $this->lang->line('mark'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'   => $id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('note'),
            );
            $this->mark_model->add($data);
            $this->session->set_flashdata('msg', '<div mark="alert alert-success text-center">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/timetable/index');
        }
    }

    public function getBydategroupclasssection()
    {
        $data                = array();
        $data['total_count'] = 1;
        $day                 = $this->input->post('day');
        $class_id            = $this->input->post('class_id');
        $section_id          = $this->input->post('section_id');
        $subject_group_id    = $this->input->post('subject_group_id');
        $subject             = $this->subjectgroup_model->getGroupsubjects($subject_group_id);

        $prev_record = $this->subjecttimetable_model->getBySubjectGroupDayClassSection($subject_group_id, $day, $class_id, $section_id);

        $staff         = $this->staff_model->getStaffbyrole(2);
        $data['staff'] = $staff;
        if (empty($prev_record)) {
            $data['prev_record'] = array();
        } else {
            $data['total_count'] = count($prev_record);
            $data['prev_record'] = $prev_record;
        }
        $data['subject']          = $subject;
        $data['day']              = $day;
        $data['class_id']         = $class_id;
        $data['section_id']       = $section_id;
        $data['subject_group_id'] = $subject_group_id;

        $data['html'] = $this->load->view('admin/timetable/addrow', $data, true);
        echo json_encode($data);
    }

    public function savegroup()
    {
        $json = array();
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject_group'), 'trim|required');
        $this->form_validation->set_rules('day', $this->lang->line('day'), 'trim|required');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required');
        $total_rows = $this->input->post('total_row');

        if (isset($total_rows) && !empty($total_rows)) {

            foreach ($this->input->post('total_row') as $key => $value) {
                if ($this->input->post('period_type_' . $value) == 'break') {
                    $this->form_validation->set_rules('time_from_' . $value, 'Time From', 'trim|required');
                    $this->form_validation->set_rules('time_to_' . $value, 'Time To', 'trim|required');
                    $this->form_validation->set_rules('break_label_' . $value, 'Break Label', 'trim|required');
                } else {
                    $this->form_validation->set_rules('subject_' . $value, 'Subject', 'trim|required');
                    $this->form_validation->set_rules('staff_' . $value, 'Staff', 'trim|required');
                    $this->form_validation->set_rules('time_from_' . $value, 'Time From', 'trim|required');
                    $this->form_validation->set_rules('time_to_' . $value, 'Time To', 'trim|required');
                }
            }
        }

        if (!$this->form_validation->run()) {
            $json = array(
                'subject_group_id' => form_error('subject_group_id', '<li>', '</li>'),
                'section_id'       => form_error('section_id', '<li>', '</li>'),
                'day'              => form_error('day', '<li>', '</li>'),
                'class_id'         => form_error('class_id', '<li>', '</li>'),
                'rows'             => form_error('rows', '<li>', '</li>'),
            );
            if (isset($total_rows) && !empty($total_rows)) {
                foreach ($this->input->post('total_row') as $key => $value) {
                    if ($this->input->post('period_type_' . $value) == 'break') {
                        $json['time_from_' . $value]   = form_error('time_from_' . $value, '<li>', '</li>');
                        $json['time_to_' . $value]     = form_error('time_to_' . $value, '<li>', '</li>');
                        $json['break_label_' . $value] = form_error('break_label_' . $value, '<li>', '</li>');
                    } else {
                        $json['subject_' . $value]   = form_error('subject_' . $value, '<li>', '</li>');
                        $json['staff_' . $value]     = form_error('staff_' . $value, '<li>', '</li>');
                        $json['time_from_' . $value] = form_error('time_from_' . $value, '<li>', '</li>');
                        $json['time_to_' . $value]   = form_error('time_to_' . $value, '<li>', '</li>');
                    }
                }
            }

            $json_array = array('status' => '0', 'error' => $json);
        } else {
            $day              = $this->input->post('day');
            $class_id         = $this->input->post('class_id');
            $section_id       = $this->input->post('section_id');
            $subject_group_id = $this->input->post('subject_group_id');
            $total_row        = $this->input->post('total_row');
            $session          = $this->setting_model->getCurrentSession();
            $insert_array     = array();
            $update_array     = array();
            $old_input        = array();
            $prev_array       = $this->input->post('prev_array');
            if (isset($prev_array)) {
                foreach ($prev_array as $prev_arr_key => $prev_arr_value) {
                    $old_input[] = $prev_arr_value;
                }
            }
            $preserve_array = array();
            if (isset($total_row)) {
                foreach ($total_row as $total_key => $total_value) {
                    $prev_id = $this->input->post('prev_id_' . $total_value);
                    $period_type = $this->input->post('period_type_' . $total_value) == 'break' ? 'break' : 'period';
                    $break_label = $period_type == 'break' ? $this->input->post('break_label_' . $total_value) : null;
                    $subject_id = $period_type == 'break' ? null : $this->input->post('subject_' . $total_value);
                    $staff_id = $period_type == 'break' ? null : $this->input->post('staff_' . $total_value);
                    $room_no = $period_type == 'break' ? null : $this->input->post('room_no_' . $total_value);

                    $arr = array(
                        'day'                      => $day,
                        'class_id'                 => $class_id,
                        'section_id'               => $section_id,
                        'subject_group_id'         => $subject_group_id,
                        'subject_group_subject_id' => $subject_id,
                        'staff_id'                 => $staff_id,
                        'time_from'                => $this->input->post('time_from_' . $total_value),
                        'time_to'                  => $this->input->post('time_to_' . $total_value),
                        'start_time'               => $this->customlib->timeFormat($this->input->post('time_from_' . $total_value), true),
                        'end_time'                 => $this->customlib->timeFormat($this->input->post('time_to_' . $total_value), true),
                        'room_no'                  => $room_no,
                        'period_type'              => $period_type,
                        'break_label'              => $break_label,
                        'session_id'               => $session,
                    );

                    if ($prev_id == 0) {
                        $insert_array[] = $arr;
                    } else {
                        $preserve_array[] = $prev_id;
                        $arr['id'] = $prev_id;
                        $update_array[] = $arr;
                    }
                }
            }

            $delete_array = array_diff($old_input, $preserve_array);

            $result       = $this->subjecttimetable_model->add($delete_array, $insert_array, $update_array);
            if ($result) {
                $json_array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            } else {
                $json_array = array('status' => '2', 'error' => '', 'message' => $this->lang->line('something_went_wrong'));
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json_array));
    }

    public function quick_generate()
    {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_edit')) {
            $json_array = array('status' => '0', 'error' => $this->lang->line('access_denied'));
            $this->output->set_content_type('application/json')->set_output(json_encode($json_array));
            return;
        }

        $class_id             = $this->input->post('class_id');
        $section_id           = $this->input->post('section_id');
        $subject_group_id     = $this->input->post('subject_group_id');
        $apply_scope          = $this->input->post('apply_scope'); // 'current' or 'all'
        
        $start_time           = $this->input->post('start_time');
        $duration             = $this->input->post('duration');
        $interval             = $this->input->post('interval');
        $total_periods        = $this->input->post('total_periods');
        
        $periods_before_break = $this->input->post('periods_before_break');
        $break_duration       = $this->input->post('break_duration');
        $break_label          = $this->input->post('break_label');
        $room_no              = $this->input->post('room_no');
        
        $session              = $this->setting_model->getCurrentSession();

        $days = $this->customlib->getDaysname();
        unset($days['Sunday']); // Remove Sunday

        $target_combos = array();
        if ($apply_scope == 'all') {
            // Get all class section subject group combinations
            $this->load->model('subjectgroup_model');
            $all_sections = $this->section_model->get();
            foreach ($all_sections as $sec_val) {
                $c_id = $sec_val['class_id'];
                $s_id = $sec_val['section_id'];
                $groups = $this->subjectgroup_model->getGroupByClassandSection($c_id, $s_id);
                if (!empty($groups)) {
                    foreach ($groups as $g_val) {
                        $target_combos[] = array(
                            'class_id' => $c_id,
                            'section_id' => $s_id,
                            'subject_group_id' => $g_val['subject_group_id']
                        );
                    }
                }
            }
        } else {
            $target_combos[] = array(
                'class_id' => $class_id,
                'section_id' => $section_id,
                'subject_group_id' => $subject_group_id
            );
        }

        if (empty($target_combos)) {
            $json_array = array('status' => '0', 'error' => "No class section combinations found.");
            $this->output->set_content_type('application/json')->set_output(json_encode($json_array));
            return;
        }

        $insert_array = array();

        foreach ($target_combos as $combo) {
            $c_id = $combo['class_id'];
            $s_id = $combo['section_id'];
            $sg_id = $combo['subject_group_id'];

            foreach ($days as $day_key => $day_value) {
                
                // Delete existing records for this day
                $this->db->where('class_id', $c_id);
                $this->db->where('section_id', $s_id);
                $this->db->where('subject_group_id', $sg_id);
                $this->db->where('day', $day_value);
                $this->db->where('session_id', $session);
                $this->db->delete('subject_timetable');
                
                // Calculate periods
                $current_start_time = $start_time;
                $period_counter = 0;
                
                for ($i = 0; $i < $total_periods; $i++) {
                    
                    // insert teaching period
                    $new_time = date('h:i A', strtotime($current_start_time . " +$duration minutes"));
                    
                    $insert_array[] = array(
                        'day'                      => $day_value,
                        'class_id'                 => $c_id,
                        'section_id'               => $s_id,
                        'subject_group_id'         => $sg_id,
                        'subject_group_subject_id' => null, // empty initially
                        'staff_id'                 => null, // empty initially
                        'time_from'                => $current_start_time,
                        'time_to'                  => $new_time,
                        'start_time'               => $this->customlib->timeFormat($current_start_time, true),
                        'end_time'                 => $this->customlib->timeFormat($new_time, true),
                        'room_no'                  => $room_no,
                        'period_type'              => 'period',
                        'break_label'              => null,
                        'session_id'               => $session,
                    );
                    
                    $current_start_time = date('h:i A', strtotime($new_time . " +$interval minutes"));
                    $period_counter++;
                    
                    if (!empty($periods_before_break) && $periods_before_break > 0 && $period_counter == $periods_before_break) {
                        // insert break
                        if (!empty($break_duration) && $break_duration > 0) {
                            $break_new_time = date('h:i A', strtotime($current_start_time . " +$break_duration minutes"));
                            $insert_array[] = array(
                                'day'                      => $day_value,
                                'class_id'                 => $c_id,
                                'section_id'               => $s_id,
                                'subject_group_id'         => $sg_id,
                                'subject_group_subject_id' => null,
                                'staff_id'                 => null,
                                'time_from'                => $current_start_time,
                                'time_to'                  => $break_new_time,
                                'start_time'               => $this->customlib->timeFormat($current_start_time, true),
                                'end_time'                 => $this->customlib->timeFormat($break_new_time, true),
                                'room_no'                  => null,
                                'period_type'              => 'break',
                                'break_label'              => !empty($break_label) ? $break_label : 'Lunch Break',
                                'session_id'               => $session,
                            );
                            $current_start_time = $break_new_time; // no interval after break usually
                        }
                        $period_counter = 0; // reset
                    }
                }
            }
        }

        if (!empty($insert_array)) {
            // Insert in chunks of 500
            $chunks = array_chunk($insert_array, 500);
            foreach ($chunks as $chunk) {
                $this->db->insert_batch('subject_timetable', $chunk);
            }
        }

        $json_array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
        $this->output->set_content_type('application/json')->set_output(json_encode($json_array));
    }

    public function shift_timetable_time()
    {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_edit')) {
            $json_array = array('status' => '0', 'error' => $this->lang->line('access_denied'));
            $this->output->set_content_type('application/json')->set_output(json_encode($json_array));
            return;
        }

        $scope         = $this->input->post('shift_scope'); // 'current' or 'all'
        $class_id      = $this->input->post('class_id');
        $section_id    = $this->input->post('section_id');
        $shift_direction = $this->input->post('shift_direction'); // 'forward' (+) or 'backward' (-)
        $shift_minutes = (int)$this->input->post('shift_minutes');
        $target_day    = $this->input->post('shift_day'); // 'all' or day name

        if ($shift_minutes <= 0) {
            $json_array = array('status' => '0', 'error' => 'Please enter a valid number of minutes (> 0).');
            $this->output->set_content_type('application/json')->set_output(json_encode($json_array));
            return;
        }

        $session = $this->setting_model->getCurrentSession();

        $this->db->where('session_id', $session);

        if ($scope == 'current') {
            if (empty($class_id) || empty($section_id)) {
                $json_array = array('status' => '0', 'error' => 'Class and Section are required for current scope.');
                $this->output->set_content_type('application/json')->set_output(json_encode($json_array));
                return;
            }
            $this->db->where('class_id', $class_id);
            $this->db->where('section_id', $section_id);
        }

        if ($target_day != 'all' && !empty($target_day)) {
            $this->db->where('day', $target_day);
        }

        $records = $this->db->get('subject_timetable')->result_array();

        if (empty($records)) {
            $json_array = array('status' => '0', 'error' => 'No timetable entries found to shift.');
            $this->output->set_content_type('application/json')->set_output(json_encode($json_array));
            return;
        }

        $sign = ($shift_direction == 'backward') ? '-' : '+';
        $update_batch = array();

        foreach ($records as $row) {
            $orig_from = $row['time_from'];
            $orig_to   = $row['time_to'];

            $new_from_ts = strtotime($orig_from . " {$sign}{$shift_minutes} minutes");
            $new_to_ts   = strtotime($orig_to . " {$sign}{$shift_minutes} minutes");

            $new_time_from  = date('h:i A', $new_from_ts);
            $new_time_to    = date('h:i A', $new_to_ts);
            $new_start_time = $this->customlib->timeFormat($new_time_from, true);
            $new_end_time   = $this->customlib->timeFormat($new_time_to, true);

            $update_batch[] = array(
                'id'         => $row['id'],
                'time_from'  => $new_time_from,
                'time_to'    => $new_time_to,
                'start_time' => $new_start_time,
                'end_time'   => $new_end_time
            );
        }

        if (!empty($update_batch)) {
            $chunks = array_chunk($update_batch, 500);
            foreach ($chunks as $chunk) {
                $this->db->update_batch('subject_timetable', $chunk, 'id');
            }
        }

        $json_array = array('status' => '1', 'error' => '', 'message' => "Timetable timings shifted successfully by {$sign}{$shift_minutes} minutes!");
        $this->output->set_content_type('application/json')->set_output(json_encode($json_array));
    }

    public function copy_timetable()
    {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_edit')) {
            $json_array = array('status' => '0', 'error' => $this->lang->line('access_denied'));
            $this->output->set_content_type('application/json')->set_output(json_encode($json_array));
            return;
        }

        $class_id             = $this->input->post('class_id');
        $section_id           = $this->input->post('section_id');
        $subject_group_id     = $this->input->post('subject_group_id');
        $copy_scope           = $this->input->post('copy_scope'); // 'current' or 'all'
        $preserve_existing    = $this->input->post('preserve_existing'); // 1 or 0
        $source_day           = $this->input->post('source_day');
        $target_day           = $this->input->post('target_day');
        
        $session              = $this->setting_model->getCurrentSession();

        // fetch source day records ordered by start_time
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->where('subject_group_id', $subject_group_id);
        $this->db->where('day', $source_day);
        $this->db->where('session_id', $session);
        $this->db->order_by('start_time', 'asc');
        $source_records = $this->db->get('subject_timetable')->result_array();

        if (empty($source_records)) {
            $json_array = array('status' => '0', 'error' => "No timetable data found for " . $source_day . " to copy.");
            $this->output->set_content_type('application/json')->set_output(json_encode($json_array));
            return;
        }

        $target_combos = array();
        if ($copy_scope == 'all') {
            $this->load->model('subjectgroup_model');
            $all_sections = $this->section_model->get();
            foreach ($all_sections as $sec_val) {
                $c_id = $sec_val['class_id'];
                $s_id = $sec_val['section_id'];
                $groups = $this->subjectgroup_model->getGroupByClassandSection($c_id, $s_id);
                if (!empty($groups)) {
                    foreach ($groups as $g_val) {
                        $target_combos[] = array(
                            'class_id' => $c_id,
                            'section_id' => $s_id,
                            'subject_group_id' => $g_val['subject_group_id']
                        );
                    }
                }
            }
        } else {
            $target_combos[] = array(
                'class_id' => $class_id,
                'section_id' => $section_id,
                'subject_group_id' => $subject_group_id
            );
        }

        $target_days = array();
        if ($target_day == 'all') {
            $days = $this->customlib->getDaysname();
            unset($days['Sunday']);
            if ($copy_scope == 'current') {
                unset($days[$source_day]);
            }
            $target_days = array_keys($days);
        } else {
            $target_days = array($target_day);
        }

        $insert_array = array();

        foreach ($target_combos as $combo) {
            $c_id = $combo['class_id'];
            $s_id = $combo['section_id'];
            $sg_id = $combo['subject_group_id'];

            foreach ($target_days as $t_day) {
                // If it's the exact same day in current class, skip to avoid self-overwrite
                if ($copy_scope == 'current' && $t_day == $source_day) {
                    continue;
                }

                // Fetch existing records for this target combo & day before deleting
                $this->db->where('class_id', $c_id);
                $this->db->where('section_id', $s_id);
                $this->db->where('subject_group_id', $sg_id);
                $this->db->where('day', $t_day);
                $this->db->where('session_id', $session);
                $this->db->order_by('start_time', 'asc');
                $existing_target_records = $this->db->get('subject_timetable')->result_array();

                // Build a queue of existing teaching period assignments (subject & teacher) for target class
                $existing_teaching_slots = array();
                if ($preserve_existing == 1 && !empty($existing_target_records)) {
                    foreach ($existing_target_records as $ex_rec) {
                        if (empty($ex_rec['period_type']) || $ex_rec['period_type'] == 'period') {
                            if (!empty($ex_rec['subject_group_subject_id']) || !empty($ex_rec['staff_id'])) {
                                $existing_teaching_slots[] = array(
                                    'subject_group_subject_id' => $ex_rec['subject_group_subject_id'],
                                    'staff_id'                 => $ex_rec['staff_id'],
                                    'room_no'                  => $ex_rec['room_no']
                                );
                            }
                        }
                    }
                }

                // Delete existing records
                $this->db->where('class_id', $c_id);
                $this->db->where('section_id', $s_id);
                $this->db->where('subject_group_id', $sg_id);
                $this->db->where('day', $t_day);
                $this->db->where('session_id', $session);
                $this->db->delete('subject_timetable');
                
                $teaching_slot_index = 0;

                foreach ($source_records as $rec) {
                    $new_rec = $rec;
                    unset($new_rec['id']);
                    $new_rec['class_id']         = $c_id;
                    $new_rec['section_id']       = $s_id;
                    $new_rec['subject_group_id'] = $sg_id;
                    $new_rec['day']              = $t_day;
                    
                    if ($c_id != $class_id || $s_id != $section_id) {
                        // Target class is different from source class
                        if ($new_rec['period_type'] == 'break') {
                            $new_rec['subject_group_subject_id'] = null;
                            $new_rec['staff_id']                 = null;
                            $new_rec['room_no']                  = null;
                        } else {
                            // Teaching period
                            if ($preserve_existing == 1 && isset($existing_teaching_slots[$teaching_slot_index])) {
                                $new_rec['subject_group_subject_id'] = $existing_teaching_slots[$teaching_slot_index]['subject_group_subject_id'];
                                $new_rec['staff_id']                 = $existing_teaching_slots[$teaching_slot_index]['staff_id'];
                                if (!empty($existing_teaching_slots[$teaching_slot_index]['room_no'])) {
                                    $new_rec['room_no'] = $existing_teaching_slots[$teaching_slot_index]['room_no'];
                                }
                                $teaching_slot_index++;
                            } else {
                                $new_rec['subject_group_subject_id'] = null;
                                $new_rec['staff_id']                 = null;
                            }
                        }
                    }

                    $insert_array[] = $new_rec;
                }
            }
        }

        if (!empty($insert_array)) {
            $chunks = array_chunk($insert_array, 500);
            foreach ($chunks as $chunk) {
                $this->db->insert_batch('subject_timetable', $chunk);
            }
        }

        $json_array = array('status' => '1', 'error' => '', 'message' => "Timetable structure synced successfully across classes!");
        $this->output->set_content_type('application/json')->set_output(json_encode($json_array));
    }

    public function getteachertimetable()
    {
        $json = array();
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('teacher', $this->lang->line('teacher'), 'trim|required');

        if (!$this->form_validation->run()) {
            $json = array(
                'teacher' => form_error('teacher'),
            );

            $json_array = array('status' => '0', 'error' => $json);
        } else {
            $staff_id          = $this->input->post('teacher');
            $data['timetable'] = array();
            $data['staff_id'] = $staff_id;

            $days              = $this->customlib->getDaysname();

            foreach ($days as $day_key => $day_value) {
                $data['timetable'][$day_value] = $this->subjecttimetable_model->getByStaffandDay($staff_id, $day_key);
            }

            $timetable_page = $this->load->view('admin/timetable/_partialgetteachertimetable', $data, true);
            $json_array = array('status' => '1', 'error' => '', 'message' => $timetable_page);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json_array));
    }

    public function printclasstimetable()
    {

        $class_id    = $this->input->post('class_id');
        $section_id  = $this->input->post('section_id');
        $days        = $this->customlib->getDaysname();
        $class_section=$this->section_model->getClassAndSectionNameByClassIDSectionID($class_id, $section_id);
        $data['class_section']=$class_section;
        $days_record = array();
        foreach ($days as $day_key => $day_value) {

            $days_record[$day_key] = $this->subjecttimetable_model->getSubjectByClassandSectionDay($class_id, $section_id, $day_key);
        }
        $data['timetable']=$days_record;
        $timetable_page = $this->load->view('admin/timetable/_printclasstimetable', $data, true);
        $json_array = array('status' => '1', 'error' => '', 'page' => $timetable_page);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json_array));
    }


    public function printteachertimetable()
    {
        $staff_id          = $this->input->post('staff_id');
        $staff = $this->staff_model->get($staff_id);
        $data['staff'] = $staff;
        $data['timetable'] = array();
        $days              = $this->customlib->getDaysname();

        foreach ($days as $day_key => $day_value) {
            $data['timetable'][$day_value] = $this->subjecttimetable_model->getByStaffandDay($staff_id, $day_key);
        }

        $timetable_page = $this->load->view('admin/timetable/_printteachertimetable', $data, true);
        $json_array = array('status' => '1', 'error' => '', 'page' => $timetable_page);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json_array));
    }

    public function check_class_dublicate_recored(){

        $time_from  =   $this->customlib->timeFormat($this->input->post("time_from"),true);
        $time_to    =   $this->customlib->timeFormat($this->input->post("time_to"),true);
        $day        =   $this->input->post("day");
        $staff_id   =   $this->input->post("staff_id");
        $result     =   $this->timetable_model->dublicate_recored($staff_id,$day,$time_from,$time_to);

        if(count($result)>0){
            $staff_name= $result[0]['staff_name'];
            $array = array('status' => 1, 'result' => $result,'error'=>"$staff_name ".$this->lang->line("is_already_allotted_to_other_class_section_or_period_for_the_same_time"));
            echo json_encode($array);
        }else{
            $array = array('status' => 0, 'result' => $result);
            echo json_encode($array);
        }
    }




}
