<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Scholarregister extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("scholarregister_model");
        $this->load->model("certificateregister_model");
        $this->load->model("student_model");
        $this->load->model("class_model");
    }

    public function index() {
        if (!$this->rbac->hasPrivilege('scholar_register', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Scholar Register';
        $data['classlist'] = $this->class_model->get();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/scholarregister/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function dt_student_list() {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        
        $condition = "";
        if (!empty($class_id)) {
            $condition .= " AND classes.id = " . $this->db->escape($class_id);
        }
        if (!empty($section_id)) {
            $condition .= " AND sections.id = " . $this->db->escape($section_id);
        }

        $mdata = $this->db->query("SELECT students.id as std_id, students.admission_no, students.firstname, students.lastname, students.image, classes.class, sections.section, student_session.id as student_session_id, students.father_name, students.is_active FROM students JOIN student_session ON student_session.student_id = students.id JOIN classes ON student_session.class_id = classes.id JOIN sections ON sections.id = student_session.section_id WHERE student_session.session_id = " . $this->setting_model->getCurrentSession() . $condition . " ORDER BY students.id DESC LIMIT 500")->result();

        $data = array();
        if (!empty($mdata)) {
            foreach ($mdata as $student) {
                $row = array();
                $name = $student->firstname . " " . $student->lastname;
                $img_url = empty($student->image) ? base_url('uploads/student_images/no_image.png') : base_url($student->image);
                $row[] = "<img src='" . $img_url . "' class='img-circle' width='30' height='30'>";
                $row[] = $student->admission_no;
                $row[] = "<a href='" . base_url() . "admin/scholarregister/view/" . $student->std_id . "'>" . $name . "</a>";
                $row[] = $student->father_name;
                $row[] = $student->class . " (" . $student->section . ")";
                $row[] = ($student->is_active == 'yes') ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>';
                
                $action = "<a href='" . base_url() . "admin/scholarregister/view/" . $student->std_id . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='View Register'><i class='fa fa-eye'></i></a>";
                
                $row[] = $action;
                $data[] = $row;
            }
        }
        
        $json_data = array(
            "draw"            => intval($_POST['draw'] ?? 1),
            "recordsTotal"    => count($data),
            "recordsFiltered" => count($data),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function view($student_id) {
        if (!$this->rbac->hasPrivilege('scholar_register', 'can_view')) {
            access_denied();
        }
        
        $data['student'] = $this->student_model->get($student_id);
        if(empty($data['student'])) {
            show_404();
        }

        $data['scholar_register'] = $this->scholarregister_model->get_student_scholar_register($student_id);
        
        // Fetch all historical sessions for this student
        $sessions = $this->db->query("SELECT student_session.*, sessions.session, classes.class, sections.section FROM student_session JOIN sessions ON sessions.id = student_session.session_id JOIN classes ON classes.id = student_session.class_id JOIN sections ON sections.id = student_session.section_id WHERE student_session.student_id = " . $this->db->escape($student_id) . " ORDER BY sessions.session ASC")->result_array();
        
        $history = [];
        foreach($sessions as $sess) {
            $reg_hist = $this->scholarregister_model->get_student_scholar_register_history($sess['id']);
            if (!empty($reg_hist)) {
                $sess['history'] = $reg_hist[0];
            } else {
                $sess['history'] = null;
            }
            $history[] = $sess;
        }
        $data['academic_history'] = $history;
        
        // Certificates
        $data['certificates'] = $this->certificateregister_model->getByStudent($student_id);
        
        $this->load->view('layout/header', $data);
        $this->load->view('admin/scholarregister/view', $data);
        $this->load->view('layout/footer', $data);
    }

    public function update_history() {
        if (!$this->rbac->hasPrivilege('scholar_register', 'can_edit')) {
            access_denied();
        }
        $student_session_id = $this->input->post('student_session_id');
        $data = array(
            'student_session_id' => $student_session_id,
            'session_id' => $this->input->post('session_id'),
            'class_id' => $this->input->post('class_id'),
            'section_id' => $this->input->post('section_id'),
            'working_days' => $this->input->post('working_days'),
            'present_days' => $this->input->post('present_days'),
            'attendance_percentage' => $this->input->post('attendance_percentage'),
            'result' => $this->input->post('result'),
            'conduct' => $this->input->post('conduct'),
            'remarks' => $this->input->post('remarks')
        );
        $this->scholarregister_model->add_or_update_scholar_register_history($data);
        
        echo json_encode(array('status' => 1, 'msg' => 'Updated Successfully'));
    }
}
