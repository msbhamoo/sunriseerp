<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MY_Addon_CBSEController extends Admin_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->helper("cbse");
        $this->load->config('cbse_config');
        $this->load->library('cbse_mail_sms');
        $this->load->model(array("cbseexam/cbseexam_assessment_model", "cbseexam/cbseexam_exam_model", "cbseexam/cbseexam_grade_model", "cbseexam/cbseexam_term_model", "cbseexam/cbseexam_result_model", "cbseexam/cbseexam_template_model", "cbseexam/cbseexam_student_rank_model", "cbseexam/cbseexam_observation_model", "cbseexam/cbse_observation_term_model", "cbseexam/cbse_observation_term_student_subparameter_model", "cbseexam/cbseexam_observation_parameter_model", "section_model"));

        if ($this->uri->segment(1) == "cbseexam" && ($this->router->fetch_class() != "setting" xor $this->router->fetch_method() != "index")) {

            $this->auth->addonchk('sscbse', site_url('cbseexam/setting/index'));
        }
        if ($this->uri->segment(1) == "cbseexam" && $this->router->fetch_class() != "setting") {

            $this->auth->addonchk('sscbse', site_url('cbseexam/setting/index'));
        } elseif ($this->uri->segment(1) != "cbseexam") {

            redirect('admin/unauthorized');
        }

        $this->ensure_cbse_sidebar_submenus();
        $this->auto_seed_cbse_observations();
        $this->ensure_cbse_template_db_columns();
        $this->auto_seed_default_portrait_template();
    }

    private function ensure_cbse_sidebar_submenus()
    {
        $cbse_menu = $this->db->get_where('sidebar_menus', array('lang_key' => 'cbse_exam'))->row();
        if ($cbse_menu) {
            $submenus = array(
                array(
                    'menu' => 'Assign Observations',
                    'lang_key' => 'assign_observation',
                    'url' => 'cbseexam/observation/assign',
                    'activate_controller' => 'observation',
                    'activate_methods' => 'assign',
                    'access_permissions' => "('cbse_exam_assign_observation', 'can_view')",
                    'level' => 5
                ),
                array(
                    'menu' => 'Observations',
                    'lang_key' => 'observation',
                    'url' => 'cbseexam/observation/index',
                    'activate_controller' => 'observation',
                    'activate_methods' => 'index',
                    'access_permissions' => "('cbse_exam_observation', 'can_view')",
                    'level' => 6
                ),
                array(
                    'menu' => 'Observation Parameters',
                    'lang_key' => 'observation_parameter',
                    'url' => 'cbseexam/observationparameter',
                    'activate_controller' => 'observationparameter',
                    'activate_methods' => 'index',
                    'access_permissions' => "('cbse_exam_observation_parameter', 'can_view')",
                    'level' => 7
                )
            );

            foreach ($submenus as $sub) {
                $check_url = $this->db->get_where('sidebar_sub_menus', array('sidebar_menu_id' => $cbse_menu->id, 'url' => $sub['url']))->row();
                if (!$check_url) {
                    $this->db->insert('sidebar_sub_menus', array(
                        'sidebar_menu_id' => $cbse_menu->id,
                        'permission_group_id' => $cbse_menu->permission_group_id,
                        'menu' => $sub['menu'],
                        'lang_key' => $sub['lang_key'],
                        'activate_controller' => $sub['activate_controller'],
                        'activate_methods' => $sub['activate_methods'],
                        'url' => $sub['url'],
                        'access_permissions' => $sub['access_permissions'],
                        'level' => $sub['level'],
                        'is_active' => 1
                    ));
                } else {
                    if ($check_url->is_active == 0) {
                        $this->db->where('id', $check_url->id)->update('sidebar_sub_menus', array('is_active' => 1));
                    }
                }
            }
        }
    }

    private function auto_seed_cbse_observations()
    {
        if (!$this->db->table_exists('cbse_observation_parameters')) {
            return;
        }

        $count = $this->db->count_all_results('cbse_observation_parameters');
        if ($count == 0) {
            $default_parameters = array(
                'Regularity & Punctuality',
                'Sincerity & Discipline',
                'Teamwork & Leadership Skills',
                'Visual & Performing Arts',
                'Health, Physical Fitness & Sportsmanship',
                'Environmental Awareness & Cleanliness',
                'Social Values & Emotional Control',
                'Communication & Expressive Skills'
            );

            $param_ids = array();
            foreach ($default_parameters as $param_name) {
                $this->db->insert('cbse_observation_parameters', array('name' => $param_name));
                $param_ids[$param_name] = $this->db->insert_id();
            }

            if ($this->db->table_exists('cbse_exam_observations') && $this->db->count_all_results('cbse_exam_observations') == 0) {
                $rubrics = array(
                    array(
                        'name' => 'Co-Scholastic & Life Skills Evaluation',
                        'description' => 'Standard CBSE Co-Scholastic evaluation parameters including Life Skills, Values, and Discipline.',
                        'params' => array(
                            'Regularity & Punctuality' => 10.00,
                            'Sincerity & Discipline' => 10.00,
                            'Teamwork & Leadership Skills' => 10.00,
                            'Social Values & Emotional Control' => 10.00,
                            'Communication & Expressive Skills' => 10.00
                        )
                    ),
                    array(
                        'name' => 'Work & Health Education Rubric',
                        'description' => 'Evaluation rubric for Work Education, Arts, Physical Fitness, and Hygiene.',
                        'params' => array(
                            'Visual & Performing Arts' => 10.00,
                            'Health, Physical Fitness & Sportsmanship' => 10.00,
                            'Environmental Awareness & Cleanliness' => 10.00
                        )
                    )
                );

                foreach ($rubrics as $rubric) {
                    $this->db->insert('cbse_exam_observations', array(
                        'name' => $rubric['name'],
                        'description' => $rubric['description']
                    ));
                    $obs_id = $this->db->insert_id();

                    if ($this->db->table_exists('cbse_observation_subparameter')) {
                        foreach ($rubric['params'] as $pname => $max_marks) {
                            if (isset($param_ids[$pname])) {
                                $this->db->insert('cbse_observation_subparameter', array(
                                    'cbse_exam_observation_id' => $obs_id,
                                    'cbse_observation_parameter_id' => $param_ids[$pname],
                                    'maximum_marks' => $max_marks
                                ));
                            }
                        }
                    }
                }
            }
        }
    }

    private function ensure_cbse_template_db_columns()
    {
        if ($this->db->table_exists('cbse_template_term_exams')) {
            if (!$this->db->field_exists('aggregation_type', 'cbse_template_term_exams')) {
                $this->db->query("ALTER TABLE `cbse_template_term_exams` ADD COLUMN `aggregation_type` varchar(20) NOT NULL DEFAULT 'all' AFTER `weightage`");
            }
        }
    }

    private function auto_seed_default_portrait_template()
    {
        if (!$this->db->table_exists('cbse_template')) {
            return;
        }

        $current_session = $this->setting_model->getCurrentSession();
        $check = $this->db->where('name', 'Standard CBSE Portrait Report Card')->where('session_id', $current_session)->get('cbse_template')->row();
        if (!$check) {
            $data = array(
                'name' => 'Standard CBSE Portrait Report Card',
                'description' => 'Default pre-configured single-page portrait CBSE Marksheet Template.',
                'created_by' => 1,
                'school_name' => 'Sunrise Public School',
                'exam_center' => 'Main Campus',
                'date' => date('Y-m-d'),
                'is_name' => 1,
                'is_father_name' => 1,
                'is_mother_name' => 1,
                'is_admission_no' => 1,
                'is_roll_no' => 1,
                'is_photo' => 1,
                'is_class' => 1,
                'is_division' => 1,
                'is_section' => 1,
                'is_dob' => 1,
                'is_remark' => 1,
                'session_id' => $current_session,
                'content' => 'Annual Academic Progress Report Card as per official CBSE Class IX/X guidelines.',
                'content_footer' => 'This is a computer-generated mark sheet.',
                'exam_session' => 1,
                'orientation' => 'P',
                'marksheet_type' => 'all_term',
                'is_weightage' => '1',
            );
            $this->db->insert('cbse_template', $data);
            $template_id = $this->db->insert_id();

            if ($template_id && $this->db->table_exists('cbse_template_class_sections') && $this->db->table_exists('class_sections')) {
                $class_sections = $this->db->get('class_sections')->result();
                foreach ($class_sections as $cs) {
                    $this->db->insert('cbse_template_class_sections', array(
                        'cbse_template_id' => $template_id,
                        'class_section_id' => $cs->id
                    ));
                }
            }
        }
    }
}
