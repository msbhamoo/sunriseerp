<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Scholarshipexam_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($this->db)) {
            $this->load->database();
        }
        $this->checkAndSetupTables();
    }

    /**
     * Self-installer method: Ensures database tables, permission group,
     * and sidebar navigation items exist seamlessly without manual DB scripts.
     */
    public function checkAndSetupTables()
    {
        if (!$this->db->table_exists('scholarship_exams')) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `scholarship_exams` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `title` VARCHAR(255) NOT NULL,
                  `exam_code` VARCHAR(100) NOT NULL,
                  `exam_category` VARCHAR(100) DEFAULT 'Scholarship/Olympiad',
                  `exam_mode` ENUM('offline', 'online') DEFAULT 'offline',
                  `roll_no_prefix` VARCHAR(50) DEFAULT 'OLY-',
                  `is_paid` TINYINT(1) DEFAULT 0,
                  `registration_fee` DECIMAL(10,2) DEFAULT 0.00,
                  `description` TEXT,
                  `exam_center` VARCHAR(255) DEFAULT 'Main School Campus',
                  `instructions` TEXT,
                  `status` TINYINT(1) DEFAULT 1,
                  `created_by` INT(11) DEFAULT NULL,
                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        }

        if ($this->db->table_exists('scholarship_exams')) {
            if (!$this->db->field_exists('registration_status', 'scholarship_exams')) {
                $this->db->query("ALTER TABLE `scholarship_exams` ADD `registration_status` TINYINT(1) DEFAULT 1;");
            }
            if (!$this->db->field_exists('admit_card_status', 'scholarship_exams')) {
                $this->db->query("ALTER TABLE `scholarship_exams` ADD `admit_card_status` TINYINT(1) DEFAULT 0;");
            }
            if (!$this->db->field_exists('result_status_published', 'scholarship_exams')) {
                $this->db->query("ALTER TABLE `scholarship_exams` ADD `result_status_published` TINYINT(1) DEFAULT 0;");
            }
            if (!$this->db->field_exists('registration_stopped_at', 'scholarship_exams')) {
                $this->db->query("ALTER TABLE `scholarship_exams` ADD `registration_stopped_at` DATETIME DEFAULT NULL;");
            }
            if (!$this->db->field_exists('admit_card_released_at', 'scholarship_exams')) {
                $this->db->query("ALTER TABLE `scholarship_exams` ADD `admit_card_released_at` DATETIME DEFAULT NULL;");
            }
            if (!$this->db->field_exists('result_released_at', 'scholarship_exams')) {
                $this->db->query("ALTER TABLE `scholarship_exams` ADD `result_released_at` DATETIME DEFAULT NULL;");
            }
        }

        if ($this->db->table_exists('scholarship_exam_schedules')) {
            if (!$this->db->field_exists('class_ids', 'scholarship_exam_schedules')) {
                $this->db->query("ALTER TABLE `scholarship_exam_schedules` ADD `class_ids` VARCHAR(255) DEFAULT NULL;");
            }
        } else {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `scholarship_exam_schedules` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `scholarship_exam_id` INT NOT NULL,
                  `phase_name` VARCHAR(100) DEFAULT 'Phase 1',
                  `class_id` INT NOT NULL,
                  `class_ids` VARCHAR(255) DEFAULT NULL,
                  `section_id` INT DEFAULT NULL,
                  `registration_start_date` DATE,
                  `registration_close_date` DATE,
                  `admit_card_release_date` DATE,
                  `exam_date` DATETIME,
                  `duration_minutes` INT DEFAULT 60,
                  `total_marks` DECIMAL(8,2) DEFAULT 100.00,
                  `passing_marks` DECIMAL(8,2) DEFAULT 40.00,
                  `result_date` DATE,
                  `award_ceremony_date` DATE,
                  INDEX (`scholarship_exam_id`),
                  INDEX (`class_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        }

        if (!$this->db->table_exists('scholarship_exam_candidates')) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `scholarship_exam_candidates` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `scholarship_exam_id` INT NOT NULL,
                  `schedule_id` INT DEFAULT NULL,
                  `student_id` INT NOT NULL,
                  `student_session_id` INT NOT NULL,
                  `roll_no` VARCHAR(100) NOT NULL UNIQUE,
                  `admit_card_no` VARCHAR(100) NOT NULL,
                  `registration_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
                  `payment_status` ENUM('free', 'unpaid', 'paid') DEFAULT 'free',
                  `attendance_status` ENUM('pending', 'present', 'absent') DEFAULT 'pending',
                  `marks_obtained` DECIMAL(8,2) DEFAULT NULL,
                  `percentile` DECIMAL(5,2) DEFAULT NULL,
                  `rank` INT DEFAULT NULL,
                  `result_status` ENUM('pending', 'passed', 'failed', 'merit_holder') DEFAULT 'pending',
                  `remarks` TEXT,
                  INDEX (`scholarship_exam_id`),
                  INDEX (`student_session_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        }

        if ($this->db->table_exists('scholarship_exam_candidates')) {
            if (!$this->db->field_exists('is_external', 'scholarship_exam_candidates')) {
                $this->db->query("ALTER TABLE `scholarship_exam_candidates` ADD `is_external` TINYINT(1) DEFAULT 0;");
            }
            if (!$this->db->field_exists('external_candidate_name', 'scholarship_exam_candidates')) {
                $this->db->query("ALTER TABLE `scholarship_exam_candidates` ADD `external_candidate_name` VARCHAR(255) DEFAULT NULL;");
            }
            if (!$this->db->field_exists('external_school_name', 'scholarship_exam_candidates')) {
                $this->db->query("ALTER TABLE `scholarship_exam_candidates` ADD `external_school_name` VARCHAR(255) DEFAULT NULL;");
            }
            if (!$this->db->field_exists('external_mobile', 'scholarship_exam_candidates')) {
                $this->db->query("ALTER TABLE `scholarship_exam_candidates` ADD `external_mobile` VARCHAR(50) DEFAULT NULL;");
            }
            if (!$this->db->field_exists('external_email', 'scholarship_exam_candidates')) {
                $this->db->query("ALTER TABLE `scholarship_exam_candidates` ADD `external_email` VARCHAR(100) DEFAULT NULL;");
            }
        }

        if (!$this->db->table_exists('scholarship_exam_questions')) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `scholarship_exam_questions` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `scholarship_exam_id` INT NOT NULL,
                  `question_id` INT NOT NULL,
                  `marks` DECIMAL(8,2) DEFAULT 1.00,
                  `neg_marks` DECIMAL(8,2) DEFAULT 0.00,
                  `display_order` INT DEFAULT 0,
                  INDEX (`scholarship_exam_id`),
                  INDEX (`question_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        }

        if (!$this->db->table_exists('scholarship_exam_field_settings')) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `scholarship_exam_field_settings` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `field_name` VARCHAR(100) NOT NULL UNIQUE,
                  `field_label` VARCHAR(150) NOT NULL,
                  `is_visible` TINYINT(1) DEFAULT 1,
                  `is_required` TINYINT(1) DEFAULT 0,
                  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");

            $default_fields = array(
                array('field_name' => 'father_name', 'field_label' => "Father's Name", 'is_visible' => 1, 'is_required' => 1),
                array('field_name' => 'mother_name', 'field_label' => "Mother's Name", 'is_visible' => 0, 'is_required' => 0),
                array('field_name' => 'guardian_name', 'field_label' => "Guardian Name", 'is_visible' => 0, 'is_required' => 0),
                array('field_name' => 'dob', 'field_label' => "Date of Birth", 'is_visible' => 1, 'is_required' => 0),
                array('field_name' => 'gender', 'field_label' => "Gender", 'is_visible' => 1, 'is_required' => 0),
                array('field_name' => 'category', 'field_label' => "Student Category", 'is_visible' => 1, 'is_required' => 0),
                array('field_name' => 'religion', 'field_label' => "Religion", 'is_visible' => 0, 'is_required' => 0),
                array('field_name' => 'caste', 'field_label' => "Caste", 'is_visible' => 0, 'is_required' => 0),
                array('field_name' => 'school_name', 'field_label' => "Current / Previous School", 'is_visible' => 1, 'is_required' => 1),
                array('field_name' => 'state', 'field_label' => "State", 'is_visible' => 0, 'is_required' => 0),
                array('field_name' => 'city', 'field_label' => "City", 'is_visible' => 0, 'is_required' => 0),
                array('field_name' => 'village', 'field_label' => "Village / Town", 'is_visible' => 0, 'is_required' => 0),
                array('field_name' => 'address', 'field_label' => "Address", 'is_visible' => 0, 'is_required' => 0),
                array('field_name' => 'national_id', 'field_label' => "Aadhaar / National ID No", 'is_visible' => 0, 'is_required' => 0),
                array('field_name' => 'photo', 'field_label' => "Student Photo Upload", 'is_visible' => 0, 'is_required' => 0)
            );

            foreach ($default_fields as $df) {
                $this->db->insert('scholarship_exam_field_settings', $df);
            }
        }

        // Setup Permission Group & Dynamic Sidebar Menu Items if missing
        $perm = $this->db->get_where('permission_group', array('short_code' => 'scholarship_exam'))->row();
        if (!$perm) {
            $this->db->insert('permission_group', array(
                'name' => 'Scholarship & Olympiad Exams',
                'short_code' => 'scholarship_exam',
                'is_active' => 1,
                'system' => 0
            ));
            $permission_group_id = $this->db->insert_id();
        } else {
            $permission_group_id = $perm->id;
        }

        // Setup Permission Category & Role Permissions
        if ($this->db->table_exists('permission_category')) {
            $perm_cat = $this->db->get_where('permission_category', array('short_code' => 'scholarship_exam'))->row();
            if (!$perm_cat) {
                $this->db->insert('permission_category', array(
                    'perm_group_id' => $permission_group_id,
                    'name' => 'Scholarship & Olympiad Exams',
                    'short_code' => 'scholarship_exam',
                    'enable_view' => 1,
                    'enable_add' => 1,
                    'enable_edit' => 1,
                    'enable_delete' => 1
                ));
                $perm_cat_id = $this->db->insert_id();
            } else {
                $perm_cat_id = $perm_cat->id;
            }

            if ($this->db->table_exists('roles_permissions') && $this->db->table_exists('roles')) {
                $roles = $this->db->get('roles')->result_array();
                foreach ($roles as $r) {
                    $rp = $this->db->get_where('roles_permissions', array('role_id' => $r['id'], 'perm_cat_id' => $perm_cat_id))->row();
                    if (!$rp) {
                        $this->db->insert('roles_permissions', array(
                            'role_id' => $r['id'],
                            'perm_cat_id' => $perm_cat_id,
                            'can_view' => 1,
                            'can_add' => 1,
                            'can_edit' => 1,
                            'can_delete' => 1
                        ));
                    }
                }
            }
        }

        // Setup Main Sidebar Menu
        $main_menu = $this->db->get_where('sidebar_menus', array('lang_key' => 'scholarship_exam'))->row();
        if (!$main_menu) {
            $max_level_query = $this->db->select_max('level')->get('sidebar_menus')->row();
            $next_level = ($max_level_query && isset($max_level_query->level)) ? $max_level_query->level + 1 : 15;

            $this->db->insert('sidebar_menus', array(
                'permission_group_id' => $permission_group_id,
                'icon' => 'icon-award',
                'menu' => 'Scholarship & Olympiad Exams',
                'lang_key' => 'scholarship_exam',
                'activate_menu' => 'scholarshipexam',
                'sidebar_display' => 1,
                'access_permissions' => "('scholarship_exam', 'can_view')",
                'level' => $next_level,
                'is_active' => 1
            ));
            $main_menu_id = $this->db->insert_id();
        } else {
            $main_menu_id = $main_menu->id;
        }

        // Setup Submenus
        $submenus = array(
            array(
                'menu' => 'Overview Dashboard',
                'url' => 'admin/scholarshipexam/index',
                'activate_controller' => 'scholarshipexam',
                'activate_methods' => 'index',
                'level' => 1,
                'key' => 'scholarshipexam_index'
            ),
            array(
                'menu' => 'Exams & Schedules',
                'url' => 'admin/scholarshipexam/exams',
                'activate_controller' => 'scholarshipexam',
                'activate_methods' => 'exams',
                'level' => 2,
                'key' => 'scholarshipexam_exams'
            ),
            array(
                'menu' => 'Question Bank & Paper Creation',
                'url' => 'admin/scholarshipexam/questions',
                'activate_controller' => 'scholarshipexam',
                'activate_methods' => 'questions',
                'level' => 3,
                'key' => 'scholarshipexam_questions'
            ),
            array(
                'menu' => 'Candidates & Admit Cards',
                'url' => 'admin/scholarshipexam/candidates',
                'activate_controller' => 'scholarshipexam',
                'activate_methods' => 'candidates',
                'level' => 4,
                'key' => 'scholarshipexam_candidates'
            ),
            array(
                'menu' => 'Marks & Rank Generator',
                'url' => 'admin/scholarshipexam/marks',
                'activate_controller' => 'scholarshipexam',
                'activate_methods' => 'marks',
                'level' => 5,
                'key' => 'scholarshipexam_marks'
            ),
            array(
                'menu' => 'Merit Certificates',
                'url' => 'admin/scholarshipexam/certificates',
                'activate_controller' => 'scholarshipexam',
                'activate_methods' => 'certificates',
                'level' => 6,
                'key' => 'scholarshipexam_certificates'
            ),
            array(
                'menu' => 'Reports & Analytics',
                'url' => 'admin/scholarshipexam/report',
                'activate_controller' => 'scholarshipexam',
                'activate_methods' => 'report',
                'level' => 7,
                'key' => 'scholarshipexam_report'
            ),
            array(
                'menu' => 'Module Settings',
                'url' => 'admin/scholarshipexam/setting',
                'activate_controller' => 'scholarshipexam',
                'activate_methods' => 'setting',
                'level' => 8,
                'key' => 'scholarshipexam_setting'
            )
        );

        foreach ($submenus as $sub) {
            $check = $this->db->get_where('sidebar_sub_menus', array('sidebar_menu_id' => $main_menu_id, 'key' => $sub['key']))->row();
            if (!$check) {
                $this->db->insert('sidebar_sub_menus', array(
                    'sidebar_menu_id' => $main_menu_id,
                    'permission_group_id' => $permission_group_id,
                    'menu' => $sub['menu'],
                    'activate_controller' => $sub['activate_controller'],
                    'activate_methods' => $sub['activate_methods'],
                    'url' => $sub['url'],
                    'access_permissions' => "('scholarship_exam', 'can_view')",
                    'level' => $sub['level'],
                    'is_active' => 1,
                    'key' => $sub['key']
                ));
            }
        }
    }

    public function getExams($id = null)
    {
        $this->db->select('scholarship_exams.*');
        $this->db->from('scholarship_exams');
        if ($id != null) {
            $this->db->where('scholarship_exams.id', $id);
            $query = $this->db->get();
            $result = $query->row_array();
            if ($result) {
                $result['schedules'] = $this->getSchedulesByExam($id);
                $result['questions'] = $this->getQuestionsForExam($id);
            }
            return $result;
        } else {
            $this->db->order_by('scholarship_exams.id', 'DESC');
            $query = $this->db->get();
            $exams = $query->result_array();
            foreach ($exams as &$e) {
                $e['schedules'] = $this->getSchedulesByExam($e['id']);
                $e['candidate_count'] = $this->getCandidateCount($e['id']);
                $e['question_count'] = $this->getQuestionCount($e['id']);
            }
            return $exams;
        }
    }

    public function getExamByCodeOrId($code_or_id)
    {
        $this->db->select('scholarship_exams.*');
        $this->db->from('scholarship_exams');
        if (is_numeric($code_or_id)) {
            $this->db->where('scholarship_exams.id', $code_or_id);
        } else {
            $this->db->where('scholarship_exams.exam_code', urldecode($code_or_id));
        }
        $query = $this->db->get();
        $result = $query->row_array();
        if ($result) {
            $result['schedules'] = $this->getSchedulesByExam($result['id']);
            $result['questions'] = $this->getQuestionsForExam($result['id']);
        }
        return $result;
    }

    public function getSchedulesByExam($exam_id)
    {
        $this->db->select('scholarship_exam_schedules.*, classes.class, sections.section');
        $this->db->from('scholarship_exam_schedules');
        $this->db->join('classes', 'classes.id = scholarship_exam_schedules.class_id', 'left');
        $this->db->join('sections', 'sections.id = scholarship_exam_schedules.section_id', 'left');
        $this->db->where('scholarship_exam_id', $exam_id);
        $this->db->order_by('scholarship_exam_schedules.id', 'ASC');
        $raw_schedules = $this->db->get()->result_array();

        $all_classes = $this->db->get('classes')->result_array();
        $class_map = array();
        foreach ($all_classes as $c) {
            $class_map[$c['id']] = $c['class'];
        }

        $grouped_schedules = array();

        foreach ($raw_schedules as $s) {
            $pname = trim($s['phase_name']) ?: 'Phase 1';
            $cids = !empty($s['class_ids']) ? explode(',', $s['class_ids']) : array($s['class_id']);
            
            if (!isset($grouped_schedules[$pname])) {
                $grouped_schedules[$pname] = $s;
                $grouped_schedules[$pname]['all_class_ids'] = array();
            }

            foreach ($cids as $cid) {
                $cid = trim($cid);
                if (!empty($cid) && !in_array($cid, $grouped_schedules[$pname]['all_class_ids'])) {
                    $grouped_schedules[$pname]['all_class_ids'][] = $cid;
                }
            }
        }

        $final_schedules = array();
        foreach ($grouped_schedules as $pname => $s) {
            $c_names = array();
            foreach ($s['all_class_ids'] as $cid) {
                if (isset($class_map[$cid])) {
                    $c_names[] = $class_map[$cid];
                }
            }
            $s['class_names_str'] = !empty($c_names) ? implode(', ', $c_names) : ($s['class'] ?: 'All Classes');
            $s['class_id_array'] = $s['all_class_ids'];
            $final_schedules[] = $s;
        }

        return $final_schedules;
    }

    public function getCandidateCount($exam_id)
    {
        $this->db->where('scholarship_exam_id', $exam_id);
        return $this->db->count_all_results('scholarship_exam_candidates');
    }

    public function getQuestionCount($exam_id)
    {
        $this->db->where('scholarship_exam_id', $exam_id);
        return $this->db->count_all_results('scholarship_exam_questions');
    }

    public function addExam($data, $schedules = array())
    {
        $this->db->trans_start();

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('scholarship_exams', $data);
            $exam_id = $data['id'];
            $this->db->where('scholarship_exam_id', $exam_id)->delete('scholarship_exam_schedules');
        } else {
            $this->db->insert('scholarship_exams', $data);
            $exam_id = $this->db->insert_id();
        }

        if (!empty($schedules)) {
            foreach ($schedules as $sched) {
                $sched['scholarship_exam_id'] = $exam_id;
                $this->db->insert('scholarship_exam_schedules', $sched);
            }
        }

        $this->db->trans_complete();
        return ($this->db->trans_status() === false) ? false : $exam_id;
    }

    public function deleteExam($id)
    {
        $this->db->trans_start();
        $this->db->where('id', $id)->delete('scholarship_exams');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function getEligibleStudentsForExam($exam_id, $class_id, $section_id = null)
    {
        $this->db->select('student_session.id as student_session_id, student_session.student_id, students.firstname, students.lastname, students.admission_no, students.roll_no as std_roll_no, classes.class, sections.section');
        $this->db->from('student_session');
        $this->db->join('students', 'students.id = student_session.student_id', 'inner');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'inner');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'inner');
        $this->db->where('student_session.class_id', $class_id);
        if ($section_id) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->where('students.is_active', 'yes');
        $this->db->order_by('students.firstname', 'ASC');
        $students = $this->db->get()->result_array();

        foreach ($students as &$s) {
            $cand = $this->db->get_where('scholarship_exam_candidates', array(
                'scholarship_exam_id' => $exam_id,
                'student_session_id' => $s['student_session_id']
            ))->row_array();
            $s['is_registered'] = $cand ? 1 : 0;
            $s['candidate_details'] = $cand;
        }

        return $students;
    }

    public function registerCandidatesBatch($exam_id, $schedule_id, $student_session_ids, $prefix = 'OLY-')
    {
        $this->db->trans_start();

        $this->db->select('roll_no');
        $this->db->from('scholarship_exam_candidates');
        $this->db->where('scholarship_exam_id', $exam_id);
        $this->db->like('roll_no', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $last_cand = $this->db->get()->row();

        $start_counter = 1001;
        if ($last_cand && !empty($last_cand->roll_no)) {
            $num_str = preg_replace('/[^0-9]/', '', str_replace($prefix, '', $last_cand->roll_no));
            if (is_numeric($num_str) && intval($num_str) >= 1000) {
                $start_counter = intval($num_str) + 1;
            }
        }

        $exam = $this->getExams($exam_id);
        $payment_status = ($exam && $exam['is_paid'] == 1) ? 'unpaid' : 'free';

        foreach ($student_session_ids as $ss_id) {
            $check = $this->db->get_where('scholarship_exam_candidates', array(
                'scholarship_exam_id' => $exam_id,
                'student_session_id' => $ss_id
            ))->row();

            if (!$check) {
                $student_sess = $this->db->get_where('student_session', array('id' => $ss_id))->row();
                if ($student_sess) {
                    $roll_no = $prefix . $start_counter;
                    $admit_card_no = 'ADM-' . str_replace('-', '', $prefix) . '-' . sprintf('%04d', rand(1000, 9999));

                    $this->db->insert('scholarship_exam_candidates', array(
                        'scholarship_exam_id' => $exam_id,
                        'schedule_id' => $schedule_id,
                        'student_id' => $student_sess->student_id,
                        'student_session_id' => $ss_id,
                        'roll_no' => $roll_no,
                        'admit_card_no' => $admit_card_no,
                        'payment_status' => $payment_status,
                        'attendance_status' => 'pending',
                        'result_status' => 'pending'
                    ));
                    $start_counter++;
                }
            }
        }

        $this->db->trans_complete();
        return ($this->db->trans_status() === false) ? false : true;
    }

    public function getCandidatesList($exam_id, $class_id = null, $section_id = null, $candidate_type = null)
    {
        $this->db->select('scholarship_exam_candidates.*, 
            COALESCE(NULLIF(students.firstname, ""), scholarship_exam_candidates.external_candidate_name, "External Candidate") as firstname, 
            COALESCE(students.lastname, "") as lastname, 
            students.image, students.gender, students.dob, 
            COALESCE(students.guardian_name, "Guardian") as guardian_name, 
            COALESCE(students.guardian_phone, scholarship_exam_candidates.external_mobile) as guardian_phone, 
            COALESCE(students.admission_no, CONCAT("EXT-", scholarship_exam_candidates.id)) as admission_no, 
            COALESCE(classes.class, CONCAT("External (", IFNULL(scholarship_exam_candidates.external_school_name, "Public"), ")")) as class, 
            COALESCE(sections.section, "A") as section, 
            scholarship_exam_schedules.total_marks, 
            scholarship_exam_schedules.passing_marks, 
            scholarship_exam_schedules.exam_date, 
            scholarship_exam_schedules.phase_name');
        $this->db->from('scholarship_exam_candidates');
        $this->db->join('students', 'students.id = scholarship_exam_candidates.student_id', 'left');
        $this->db->join('student_session', 'student_session.id = scholarship_exam_candidates.student_session_id', 'left');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('scholarship_exam_schedules', 'scholarship_exam_schedules.id = scholarship_exam_candidates.schedule_id', 'left');
        $this->db->where('scholarship_exam_candidates.scholarship_exam_id', $exam_id);

        if ($class_id) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id) {
            $this->db->where('student_session.section_id', $section_id);
        }

        if ($candidate_type == 'internal') {
            $this->db->where('scholarship_exam_candidates.is_external', 0);
        } elseif ($candidate_type == 'external') {
            $this->db->where('scholarship_exam_candidates.is_external', 1);
        }

        $this->db->order_by('scholarship_exam_candidates.id', 'ASC');
        return $this->db->get()->result_array();
    }

    /* ================= Question Bank & Paper Creation Methods ================= */

    public function getQuestionsForExam($exam_id)
    {
        $this->db->select('scholarship_exam_questions.*, questions.question, questions.opt_a, questions.opt_b, questions.opt_c, questions.opt_d, questions.correct, questions.question_type, questions.level, subjects.name as subject_name');
        $this->db->from('scholarship_exam_questions');
        $this->db->join('questions', 'questions.id = scholarship_exam_questions.question_id', 'inner');
        $this->db->join('subjects', 'subjects.id = questions.subject_id', 'left');
        $this->db->where('scholarship_exam_questions.scholarship_exam_id', $exam_id);
        $this->db->order_by('scholarship_exam_questions.display_order', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getAvailableQuestions($subject_id = null, $class_ids = null, $keyword = null)
    {
        $this->db->select('questions.*, subjects.name as subject_name, classes.class as class_name');
        $this->db->from('questions');
        $this->db->join('subjects', 'subjects.id = questions.subject_id', 'left');
        $this->db->join('classes', 'classes.id = questions.class_id', 'left');

        if ($subject_id) {
            $this->db->where('questions.subject_id', $subject_id);
        }

        if (!empty($class_ids)) {
            if (is_array($class_ids)) {
                $this->db->group_start();
                $this->db->where_in('questions.class_id', $class_ids);
                $this->db->or_where('questions.class_id', null);
                $this->db->or_where('questions.class_id', 0);
                $this->db->group_end();
            } else {
                $this->db->group_start();
                $this->db->where('questions.class_id', $class_ids);
                $this->db->or_where('questions.class_id', null);
                $this->db->or_where('questions.class_id', 0);
                $this->db->group_end();
            }
        }

        if ($keyword) {
            $this->db->like('questions.question', $keyword);
        }

        $this->db->order_by('questions.id', 'DESC');
        $this->db->limit(100);
        return $this->db->get()->result_array();
    }

    public function addQuestionToExam($exam_id, $question_id, $marks = 1.00, $neg_marks = 0.00)
    {
        $check = $this->db->get_where('scholarship_exam_questions', array(
            'scholarship_exam_id' => $exam_id,
            'question_id' => $question_id
        ))->row();

        if (!$check) {
            $this->db->insert('scholarship_exam_questions', array(
                'scholarship_exam_id' => $exam_id,
                'question_id' => $question_id,
                'marks' => $marks,
                'neg_marks' => $neg_marks
            ));
            return true;
        }
        return false;
    }

    public function removeQuestionFromExam($exam_id, $question_id)
    {
        $this->db->where('scholarship_exam_id', $exam_id);
        $this->db->where('question_id', $question_id);
        $this->db->delete('scholarship_exam_questions');
        return true;
    }

    public function saveMarksBatch($exam_id, $marks_data)
    {
        $this->db->trans_start();

        foreach ($marks_data as $cand_id => $m) {
            $attendance = isset($m['attendance_status']) ? $m['attendance_status'] : 'present';
            $marks = (isset($m['marks_obtained']) && $m['marks_obtained'] !== '') ? floatval($m['marks_obtained']) : null;
            $remarks = isset($m['remarks']) ? $m['remarks'] : '';

            $this->db->where('id', $cand_id);
            $this->db->update('scholarship_exam_candidates', array(
                'attendance_status' => $attendance,
                'marks_obtained' => $marks,
                'remarks' => $remarks
            ));
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() !== false) {
            $this->calculateRanks($exam_id);
            return true;
        }
        return false;
    }

    public function calculateRanks($exam_id)
    {
        $candidates = $this->db->select('scholarship_exam_candidates.*, scholarship_exam_schedules.total_marks, scholarship_exam_schedules.passing_marks')
            ->from('scholarship_exam_candidates')
            ->join('scholarship_exam_schedules', 'scholarship_exam_schedules.id = scholarship_exam_candidates.schedule_id', 'left')
            ->where('scholarship_exam_candidates.scholarship_exam_id', $exam_id)
            ->where('scholarship_exam_candidates.attendance_status', 'present')
            ->where('scholarship_exam_candidates.marks_obtained IS NOT NULL', null, false)
            ->order_by('scholarship_exam_candidates.marks_obtained', 'DESC')
            ->get()->result_array();

        if (empty($candidates)) {
            return;
        }

        $current_rank = 1;
        $prev_marks = null;
        $rank_counter = 0;

        foreach ($candidates as $c) {
            $rank_counter++;
            if ($prev_marks !== null && $c['marks_obtained'] < $prev_marks) {
                $current_rank = $rank_counter;
            }
            $prev_marks = $c['marks_obtained'];

            $total = ($c['total_marks'] > 0) ? $c['total_marks'] : 100;
            $percentile = round(($c['marks_obtained'] / $total) * 100, 2);
            $pass_mark = ($c['passing_marks'] > 0) ? $c['passing_marks'] : 40;

            if ($current_rank <= 3 && $c['marks_obtained'] >= $pass_mark) {
                $status = 'merit_holder';
            } elseif ($c['marks_obtained'] >= $pass_mark) {
                $status = 'passed';
            } else {
                $status = 'failed';
            }

            $this->db->where('id', $c['id']);
            $this->db->update('scholarship_exam_candidates', array(
                'rank' => $current_rank,
                'percentile' => $percentile,
                'result_status' => $status
            ));
        }
    }

    public function getCandidateAdmitCardData($candidate_id)
    {
        $this->db->select('scholarship_exam_candidates.*, scholarship_exams.title as exam_title, scholarship_exams.exam_code, scholarship_exams.exam_mode, scholarship_exams.exam_center, scholarship_exams.instructions, 
            COALESCE(NULLIF(students.firstname, ""), scholarship_exam_candidates.external_candidate_name, "External Candidate") as firstname, 
            COALESCE(students.lastname, "") as lastname, 
            students.image, students.gender, students.dob, 
            COALESCE(students.father_name, "Guardian") as father_name, 
            COALESCE(students.mother_name, "") as mother_name, 
            COALESCE(students.admission_no, CONCAT("EXT-", scholarship_exam_candidates.id)) as admission_no, 
            COALESCE(classes.class, CONCAT("External (", IFNULL(scholarship_exam_candidates.external_school_name, "Public"), ")")) as class, 
            COALESCE(sections.section, "A") as section, 
            scholarship_exam_schedules.phase_name, scholarship_exam_schedules.exam_date, scholarship_exam_schedules.duration_minutes, scholarship_exam_schedules.admit_card_release_date');
        $this->db->from('scholarship_exam_candidates');
        $this->db->join('scholarship_exams', 'scholarship_exams.id = scholarship_exam_candidates.scholarship_exam_id', 'inner');
        $this->db->join('students', 'students.id = scholarship_exam_candidates.student_id', 'left');
        $this->db->join('student_session', 'student_session.id = scholarship_exam_candidates.student_session_id', 'left');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('scholarship_exam_schedules', 'scholarship_exam_schedules.id = scholarship_exam_candidates.schedule_id', 'left');
        $this->db->where('scholarship_exam_candidates.id', $candidate_id);
        return $this->db->get()->row_array();
    }

    public function getDashboardStats()
    {
        $total_exams = $this->db->count_all('scholarship_exams');
        $total_candidates = $this->db->count_all('scholarship_exam_candidates');
        
        $this->db->where('result_status', 'merit_holder');
        $merit_holders = $this->db->count_all_results('scholarship_exam_candidates');

        $this->db->where('status', 1);
        $active_exams = $this->db->count_all_results('scholarship_exams');

        return array(
            'total_exams' => $total_exams,
            'active_exams' => $active_exams,
            'total_candidates' => $total_candidates,
            'merit_holders' => $merit_holders
        );
    }

    public function getAllClasses()
    {
        $this->db->select('classes.*');
        $this->db->from('classes');
        $this->db->order_by('classes.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getSchoolSetting()
    {
        return $this->db->get('sch_settings')->result_array();
    }

    public function getActiveExam()
    {
        $this->db->select('scholarship_exams.*');
        $this->db->from('scholarship_exams');
        $this->db->where('status', 1);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $exam = $query->row_array();
        if ($exam) {
            $exam['schedules'] = $this->getSchedulesByExam($exam['id']);
            $exam['questions'] = $this->getQuestionsForExam($exam['id']);
            $exam['candidate_count'] = $this->getCandidateCount($exam['id']);
            $exam['question_count'] = $this->getQuestionCount($exam['id']);
            $exam['field_settings'] = $this->getFieldSettings();
        }
        return $exam;
    }

    public function toggleExamStatus($id)
    {
        $exam = $this->db->get_where('scholarship_exams', array('id' => $id))->row();
        if ($exam) {
            $new_status = ($exam->status == 1) ? 0 : 1;
            if ($new_status == 1) {
                // Ensure only ONE scholarship exam is active at a time
                $this->db->update('scholarship_exams', array('status' => 0));
            }
            $this->db->where('id', $id)->update('scholarship_exams', array('status' => $new_status));
            return $new_status;
        }
        return false;
    }

    public function toggleRegistrationStatus($id)
    {
        $exam = $this->db->get_where('scholarship_exams', array('id' => $id))->row();
        if ($exam) {
            $new_status = (isset($exam->registration_status) && $exam->registration_status == 1) ? 0 : 1;
            $stopped_at = ($new_status == 0) ? date('Y-m-d H:i:s') : null;
            $this->db->where('id', $id)->update('scholarship_exams', array(
                'registration_status' => $new_status,
                'registration_stopped_at' => $stopped_at
            ));
            return $new_status;
        }
        return false;
    }

    public function toggleAdmitCardStatus($id)
    {
        $exam = $this->db->get_where('scholarship_exams', array('id' => $id))->row();
        if ($exam) {
            $new_status = (isset($exam->admit_card_status) && $exam->admit_card_status == 1) ? 0 : 1;
            $released_at = ($new_status == 1) ? date('Y-m-d H:i:s') : null;
            $this->db->where('id', $id)->update('scholarship_exams', array(
                'admit_card_status' => $new_status,
                'admit_card_released_at' => $released_at
            ));
            return $new_status;
        }
        return false;
    }

    public function toggleResultStatus($id)
    {
        $exam = $this->db->get_where('scholarship_exams', array('id' => $id))->row();
        if ($exam) {
            $new_status = (isset($exam->result_status_published) && $exam->result_status_published == 1) ? 0 : 1;
            $released_at = ($new_status == 1) ? date('Y-m-d H:i:s') : null;
            $this->db->where('id', $id)->update('scholarship_exams', array(
                'result_status_published' => $new_status,
                'result_released_at' => $released_at
            ));
            return $new_status;
        }
        return false;
    }

    public function getFieldSettings()
    {
        $this->db->select('*');
        $this->db->from('scholarship_exam_field_settings');
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function saveFieldSettings($post_data)
    {
        $all_fields = $this->getFieldSettings();
        foreach ($all_fields as $f) {
            $fname = $f['field_name'];
            $is_visible = (isset($post_data[$fname]['is_visible']) && $post_data[$fname]['is_visible'] == 1) ? 1 : 0;
            $is_required = (isset($post_data[$fname]['is_required']) && $post_data[$fname]['is_required'] == 1) ? 1 : 0;

            $this->db->where('field_name', $fname)->update('scholarship_exam_field_settings', array(
                'is_visible' => $is_visible,
                'is_required' => $is_required
            ));
        }
        return true;
    }
}
