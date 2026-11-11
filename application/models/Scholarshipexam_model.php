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
        if (!$this->db->table_exists('scholarship_study_materials')) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `scholarship_study_materials` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `scholarship_exam_id` INT NOT NULL,
                  `class_id` INT NOT NULL,
                  `material_type` ENUM('sample_paper', 'syllabus', 'notes', 'other') DEFAULT 'sample_paper',
                  `title` VARCHAR(255) NOT NULL,
                  `description` TEXT,
                  `file_name` VARCHAR(255) NOT NULL,
                  `file_path` VARCHAR(500) NOT NULL,
                  `file_size` VARCHAR(50) DEFAULT NULL,
                  `uploaded_by` INT DEFAULT NULL,
                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                  `status` TINYINT(1) DEFAULT 1,
                  INDEX (`scholarship_exam_id`),
                  INDEX (`class_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        }

        if ($this->db->table_exists('scholarship_exam_candidates')) {
            if (!$this->db->field_exists('password_pin', 'scholarship_exam_candidates')) {
                $this->db->query("ALTER TABLE `scholarship_exam_candidates` ADD `password_pin` VARCHAR(10) DEFAULT NULL;");
            }
        }

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
            if (!$this->db->field_exists('section_id', 'scholarship_exam_schedules')) {
                $this->db->query("ALTER TABLE `scholarship_exam_schedules` ADD `section_id` INT DEFAULT NULL;");
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
            $extra_cols = array(
                'external_class_id' => 'INT DEFAULT NULL',
                'external_father_name' => 'VARCHAR(255) DEFAULT NULL',
                'external_mother_name' => 'VARCHAR(255) DEFAULT NULL',
                'external_dob' => 'DATE DEFAULT NULL',
                'external_gender' => 'VARCHAR(20) DEFAULT NULL',
                'external_category' => 'VARCHAR(50) DEFAULT NULL',
                'external_village' => 'VARCHAR(150) DEFAULT NULL',
                'external_city' => 'VARCHAR(150) DEFAULT NULL',
                'external_address' => 'TEXT',
            );
            foreach ($extra_cols as $col => $def) {
                // Live check (not field_exists): the CI4 field-name cache is stale after an ALTER in the same request
                $has_col = $this->db->query("SHOW COLUMNS FROM `scholarship_exam_candidates` LIKE '$col'")->row_array();
                if (!$has_col) {
                    $this->db->query("ALTER TABLE `scholarship_exam_candidates` ADD `$col` $def;");
                }
            }
        }

        if (!$this->db->table_exists('scholarship_settings')) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `scholarship_settings` (
                  `setting_key` VARCHAR(100) NOT NULL PRIMARY KEY,
                  `setting_value` TEXT,
                  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        }

        if (!$this->db->table_exists('scholarship_qr_scans')) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `scholarship_qr_scans` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `visitor_id` VARCHAR(32) NOT NULL,
                  `scanned_at` DATETIME NOT NULL,
                  `ip` VARCHAR(45) DEFAULT NULL,
                  `user_agent` VARCHAR(500) DEFAULT NULL,
                  `device` VARCHAR(20) DEFAULT NULL,
                  `os` VARCHAR(30) DEFAULT NULL,
                  `browser` VARCHAR(30) DEFAULT NULL,
                  `is_bot` TINYINT(1) DEFAULT 0,
                  `landing_url` VARCHAR(500) DEFAULT NULL,
                  `registered` TINYINT(1) DEFAULT 0,
                  `candidate_id` INT DEFAULT NULL,
                  `exam_id` INT DEFAULT NULL,
                  `registered_at` DATETIME DEFAULT NULL,
                  KEY `idx_scanned_at` (`scanned_at`),
                  KEY `idx_visitor` (`visitor_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
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

        if (!$this->db->table_exists('scholarship_leads')) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `scholarship_leads` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `exam_id` INT DEFAULT NULL,
                  `mobile` VARCHAR(20) NOT NULL,
                  `candidate_name` VARCHAR(255) DEFAULT NULL,
                  `class_id` INT DEFAULT NULL,
                  `source` VARCHAR(50) DEFAULT 'web_form',
                  `status` VARCHAR(20) DEFAULT 'draft',
                  `ip_address` VARCHAR(50) DEFAULT NULL,
                  `user_agent` TEXT DEFAULT NULL,
                  `registered_at` DATETIME DEFAULT NULL,
                  `created_at` DATETIME NOT NULL,
                  `updated_at` DATETIME NOT NULL,
                  KEY `idx_lead_mobile` (`mobile`),
                  KEY `idx_lead_exam` (`exam_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ");
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
            ),
            array(
                'menu' => 'Sample Papers & Study Material',
                'url' => 'admin/scholarshipexam/materials',
                'activate_controller' => 'scholarshipexam',
                'activate_methods' => 'materials',
                'level' => 9,
                'key' => 'scholarshipexam_materials'
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

        // Seed Default "SUNRISE SCHOLARSHIP CUM ADMISSION TEST" if no exam exists yet
        $existing_ssat = $this->db->get_where('scholarship_exams', array('exam_code' => 'SSAT-2026'))->row();
        if (!$existing_ssat) {
            $default_exam = array(
                'title' => 'SUNRISE SCHOLARSHIP CUM ADMISSION TEST',
                'exam_code' => 'SSAT-2026',
                'exam_category' => 'Scholarship & Admission',
                'exam_mode' => 'offline',
                'roll_no_prefix' => 'SSAT-',
                'is_paid' => 0,
                'registration_fee' => 0.00,
                'description' => "Sunrise Scholarship Cum Admission Test for Class 5th to 11th.\nEligibility: All students from class 5th to 11th from any school other than Sunrise.\nMedium: Hindi & English (bilingual)\nType of Exam: MCQ based on OMR Sheet.\nAwards:\n(a) Upto 100% Scholarship in school fees.\n(b) Thousands of Cash Awards.\n(c) Gifts, Memento and Certificates of participation.\nMode of Registration: Online via portal, WhatsApp to 9783200821, or Offline at School Reception.",
                'exam_center' => 'Sunrise International Public School Campus',
                'instructions' => 'OMR Based MCQ Test. Bring Admit Card and Blue/Black Ballpoint pen. Reporting time 30 minutes prior to exam.',
                'status' => 1,
                'registration_status' => 1
            );
            $this->db->insert('scholarship_exams', $default_exam);
            $new_exam_id = $this->db->insert_id();

            // Match available classes for 5th, 6th-10th, 11th
            $all_cls = $this->db->get('classes')->result_array();
            $cls_by_name = array();
            foreach ($all_cls as $ac) {
                $cname = strtolower(trim($ac['class']));
                $cls_by_name[$cname] = $ac['id'];
                // Handle numbers (e.g., '5' or '5th')
                $num = preg_replace('/[^0-9]/', '', $cname);
                if ($num) {
                    $cls_by_name[$num] = $ac['id'];
                }
            }

            $get_ids = function($names) use ($cls_by_name, $all_cls) {
                $ids = array();
                foreach ($names as $n) {
                    if (isset($cls_by_name[$n])) {
                        $ids[] = $cls_by_name[$n];
                    }
                }
                if (empty($ids) && !empty($all_cls)) {
                    $ids[] = $all_cls[0]['id'];
                }
                return implode(',', array_unique($ids));
            };

            $c_5th = $get_ids(array('5', '5th', 'class 5', 'class 5th'));
            $c_6to10 = $get_ids(array('6', '6th', '7', '7th', '8', '8th', '9', '9th', '10', '10th'));
            $c_11th_sci = $get_ids(array('11', '11th', 'class 11'));
            $c_11th_arts = $get_ids(array('11', '11th', 'class 11'));

            $sample_schedules = array(
                array(
                    'scholarship_exam_id' => $new_exam_id,
                    'phase_name' => '5th Class - Hindi, English, Maths, EVS',
                    'class_id' => !empty($c_5th) ? intval(explode(',', $c_5th)[0]) : 1,
                    'class_ids' => $c_5th,
                    'registration_start_date' => '2026-10-15',
                    'registration_close_date' => '2026-11-10',
                    'admit_card_release_date' => '2026-11-12',
                    'exam_date' => '2026-11-15 10:00:00',
                    'duration_minutes' => 90,
                    'total_marks' => 50.00,
                    'passing_marks' => 20.00,
                    'result_date' => '2026-11-21',
                    'award_ceremony_date' => '2026-12-05'
                ),
                array(
                    'scholarship_exam_id' => $new_exam_id,
                    'phase_name' => '6th to 10th - Hindi, Eng, Sci, Maths, SST, Reasoning',
                    'class_id' => !empty($c_6to10) ? intval(explode(',', $c_6to10)[0]) : 1,
                    'class_ids' => $c_6to10,
                    'registration_start_date' => '2026-10-15',
                    'registration_close_date' => '2026-11-10',
                    'admit_card_release_date' => '2026-11-12',
                    'exam_date' => '2026-11-15 10:00:00',
                    'duration_minutes' => 90,
                    'total_marks' => 90.00,
                    'passing_marks' => 36.00,
                    'result_date' => '2026-11-21',
                    'award_ceremony_date' => '2026-12-05'
                ),
                array(
                    'scholarship_exam_id' => $new_exam_id,
                    'phase_name' => '11th Science - English, Maths/Bio, Physics, Chemistry',
                    'class_id' => !empty($c_11th_sci) ? intval(explode(',', $c_11th_sci)[0]) : 1,
                    'class_ids' => $c_11th_sci,
                    'registration_start_date' => '2026-10-15',
                    'registration_close_date' => '2026-11-10',
                    'admit_card_release_date' => '2026-11-12',
                    'exam_date' => '2026-11-15 10:00:00',
                    'duration_minutes' => 90,
                    'total_marks' => 120.00,
                    'passing_marks' => 48.00,
                    'result_date' => '2026-11-21',
                    'award_ceremony_date' => '2026-12-05'
                ),
                array(
                    'scholarship_exam_id' => $new_exam_id,
                    'phase_name' => '11th Arts - English, Pol Sci, History, Geography',
                    'class_id' => !empty($c_11th_arts) ? intval(explode(',', $c_11th_arts)[0]) : 1,
                    'class_ids' => $c_11th_arts,
                    'registration_start_date' => '2026-10-15',
                    'registration_close_date' => '2026-11-10',
                    'admit_card_release_date' => '2026-11-12',
                    'exam_date' => '2026-11-15 10:00:00',
                    'duration_minutes' => 90,
                    'total_marks' => 120.00,
                    'passing_marks' => 48.00,
                    'result_date' => '2026-11-21',
                    'award_ceremony_date' => '2026-12-05'
                )
            );

            foreach ($sample_schedules as $ss) {
                $this->db->insert('scholarship_exam_schedules', $ss);
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
        $has_section = $this->db->field_exists('section_id', 'scholarship_exam_schedules');
        if ($has_section) {
            $this->db->select('scholarship_exam_schedules.*, classes.class, sections.section');
        } else {
            $this->db->select('scholarship_exam_schedules.*, classes.class');
        }
        $this->db->from('scholarship_exam_schedules');
        $this->db->join('classes', 'classes.id = scholarship_exam_schedules.class_id', 'left');
        if ($has_section) {
            $this->db->join('sections', 'sections.id = scholarship_exam_schedules.section_id', 'left');
        }
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

    /** Candidates who registered through the public form (not added by admin). */
    public function getOnlineRegistrationCount($exam_id)
    {
        $this->db->where('scholarship_exam_id', $exam_id);
        $this->db->where('is_external', 1);
        return $this->db->count_all_results('scholarship_exam_candidates');
    }

    /** Latest public sign-ups, with the name partly hidden, for the "just registered" popup. */
    public function getRecentOnlineRegistrations($exam_id, $limit = 8)
    {
        $rows = $this->db->query(
            "SELECT c.external_candidate_name AS name, c.external_city AS city, c.external_village AS village, c.registration_date, cl.class AS cls
             FROM scholarship_exam_candidates c
             LEFT JOIN classes cl ON cl.id = c.external_class_id
             WHERE c.scholarship_exam_id = ? AND c.is_external = 1 AND c.registration_date >= (NOW() - INTERVAL 1 DAY)
             ORDER BY c.id DESC LIMIT " . (int) $limit,
            array($exam_id)
        )->result_array();

        $out = array();
        foreach ($rows as $r) {
            $parts = preg_split('/\s+/u', trim($r['name']));
            $first = mb_substr($parts[0], 0, 2, 'UTF-8') . str_repeat('*', max(2, mb_strlen($parts[0], 'UTF-8') - 2));
            $last = count($parts) > 1 ? ' ' . mb_strtoupper(mb_substr(end($parts), 0, 1, 'UTF-8'), 'UTF-8') . '.' : '';
            $place = trim($r['city'] ?: $r['village']);
            $mins = max(0, (int) floor((time() - strtotime($r['registration_date'])) / 60));
            $out[] = array(
                'name' => $first . $last,
                'cls' => $r['cls'] ? 'Class ' . $r['cls'] : '',
                'place' => $place,
                'ago' => $mins < 1 ? 'just now' : ($mins < 60 ? $mins . ' min ago' : floor($mins / 60) . ' hr ago'),
            );
        }
        return $out;
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
            students.image, COALESCE(students.gender, scholarship_exam_candidates.external_gender) as gender, COALESCE(students.dob, scholarship_exam_candidates.external_dob) as dob, 
            COALESCE(students.guardian_name, NULLIF(scholarship_exam_candidates.external_father_name, ""), "Guardian") as guardian_name, 
            COALESCE(students.guardian_phone, scholarship_exam_candidates.external_mobile) as guardian_phone, 
            COALESCE(students.admission_no, CONCAT("EXT-", scholarship_exam_candidates.id)) as admission_no, 
            COALESCE(classes.class, CONCAT(IF(ext_classes.class IS NULL, "", CONCAT("Class ", ext_classes.class, " - ")), "External (", IFNULL(scholarship_exam_candidates.external_school_name, "Public"), ")")) as class, 
            scholarship_exam_candidates.external_category, scholarship_exam_candidates.external_city, scholarship_exam_candidates.external_village, scholarship_exam_candidates.external_address, 
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
        $this->db->join('classes ext_classes', 'ext_classes.id = scholarship_exam_candidates.external_class_id', 'left');
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
            students.image, COALESCE(students.gender, scholarship_exam_candidates.external_gender) as gender, COALESCE(students.dob, scholarship_exam_candidates.external_dob) as dob, 
            COALESCE(students.father_name, scholarship_exam_candidates.external_father_name, "Guardian") as father_name, 
            COALESCE(students.mother_name, scholarship_exam_candidates.external_mother_name, "") as mother_name, 
            COALESCE(students.admission_no, CONCAT("EXT-", scholarship_exam_candidates.id)) as admission_no, 
            COALESCE(classes.class, ext_classes.class, "N/A") as raw_class_name,
            COALESCE(classes.class, CONCAT(IF(ext_classes.class IS NULL, "", CONCAT("Class ", ext_classes.class, " - ")), "External (", IFNULL(scholarship_exam_candidates.external_school_name, "Public"), ")")) as class, 
            COALESCE(sections.section, "A") as section, 
            scholarship_exam_schedules.phase_name, scholarship_exam_schedules.exam_date, scholarship_exam_schedules.duration_minutes, scholarship_exam_schedules.admit_card_release_date');
        $this->db->from('scholarship_exam_candidates');
        $this->db->join('scholarship_exams', 'scholarship_exams.id = scholarship_exam_candidates.scholarship_exam_id', 'inner');
        $this->db->join('students', 'students.id = scholarship_exam_candidates.student_id', 'left');
        $this->db->join('student_session', 'student_session.id = scholarship_exam_candidates.student_session_id', 'left');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
        $this->db->join('classes ext_classes', 'ext_classes.id = scholarship_exam_candidates.external_class_id', 'left');
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

    public function getSetting($key, $default = null)
    {
        $row = $this->db->get_where('scholarship_settings', array('setting_key' => $key))->row_array();
        return $row ? $row['setting_value'] : $default;
    }

    public function saveSetting($key, $value)
    {
        $this->db->query(
            'INSERT INTO scholarship_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)',
            array($key, $value)
        );
    }

    /**
     * Where the permanent short link (/ssat, encoded in the printed QR) sends visitors.
     * Empty = the first open exam's public form.
     */
    public function getPublicLinkTarget()
    {
        return trim((string) $this->getSetting('public_link_target', ''));
    }

    /** Rough device / OS / browser from the user agent (exact phone model is not available). */
    public function parseUserAgent($ua)
    {
        $is_bot = (int) (bool) preg_match('/bot|crawl|spider|preview|facebookexternalhit|whatsapp|telegram|slack|curl|wget|python/i', $ua);
        if (preg_match('/ipad|tablet|android(?!.*mobile)/i', $ua)) {
            $device = 'Tablet';
        } elseif (preg_match('/mobi|iphone|ipod|android/i', $ua)) {
            $device = 'Mobile';
        } else {
            $device = $ua === '' ? 'Unknown' : 'Desktop';
        }
        $os = 'Other';
        foreach (array('Android' => '/android/i', 'iOS' => '/iphone|ipad|ipod/i', 'Windows' => '/windows/i', 'macOS' => '/mac os x/i', 'Linux' => '/linux/i') as $name => $re) {
            if (preg_match($re, $ua)) {
                $os = $name;
                break;
            }
        }
        $browser = 'Other';
        foreach (array('Edge' => '/edg\//i', 'Samsung Internet' => '/samsungbrowser/i', 'Opera' => '/opr\/|opera/i', 'Chrome' => '/chrome|crios/i', 'Firefox' => '/firefox|fxios/i', 'Safari' => '/safari/i') as $name => $re) {
            if (preg_match($re, $ua)) {
                $browser = $name;
                break;
            }
        }
        return array('device' => $device, 'os' => $os, 'browser' => $browser, 'is_bot' => $is_bot);
    }

    public function logQrScan($visitor_id, $ip, $ua, $landing_url)
    {
        $ua = substr((string) $ua, 0, 500);
        $row = array_merge(array(
            'visitor_id' => $visitor_id,
            'scanned_at' => date('Y-m-d H:i:s'),
            'ip' => $ip,
            'user_agent' => $ua,
            'landing_url' => substr($landing_url, 0, 500),
        ), $this->parseUserAgent($ua));
        $this->db->insert('scholarship_qr_scans', $row);
        return $this->db->insert_id();
    }

    /** Credits a new registration to the scan that brought this visitor in (latest unconverted scan). */
    public function markQrScanRegistered($scan_id, $visitor_id, $candidate_id, $exam_id)
    {
        $scan = $this->db->query(
            'SELECT id FROM scholarship_qr_scans WHERE registered = 0 AND (id = ? OR visitor_id = ?) ORDER BY id DESC LIMIT 1',
            array((int) $scan_id, (string) $visitor_id)
        )->row_array();
        if (!$scan) {
            return;
        }
        $this->db->where('id', $scan['id'])->update('scholarship_qr_scans', array(
            'registered' => 1,
            'candidate_id' => $candidate_id,
            'exam_id' => $exam_id,
            'registered_at' => date('Y-m-d H:i:s'),
        ));
    }

    /** Scan analytics for the admin page; bots / link-preview fetches are excluded. */
    public function getQrScanStats($from, $to)
    {
        $range = array($from . ' 00:00:00', $to . ' 23:59:59');
        $where = 'is_bot = 0 AND scanned_at BETWEEN ? AND ?';

        $totals = $this->db->query(
            "SELECT COUNT(*) AS scans, COUNT(DISTINCT visitor_id) AS visitors, COALESCE(SUM(registered), 0) AS registrations FROM scholarship_qr_scans WHERE $where",
            $range
        )->row_array();

        $daily = $this->db->query(
            "SELECT DATE(scanned_at) AS day, COUNT(*) AS scans, COUNT(DISTINCT visitor_id) AS visitors, SUM(registered) AS registrations
             FROM scholarship_qr_scans WHERE $where GROUP BY DATE(scanned_at) ORDER BY day",
            $range
        )->result_array();

        $breakdown = array();
        foreach (array('device', 'os', 'browser') as $col) {
            $breakdown[$col] = $this->db->query(
                "SELECT $col AS label, COUNT(*) AS scans, SUM(registered) AS registrations
                 FROM scholarship_qr_scans WHERE $where GROUP BY $col ORDER BY scans DESC",
                $range
            )->result_array();
        }

        $recent = $this->db->query(
            "SELECT s.*, c.external_candidate_name FROM scholarship_qr_scans s
             LEFT JOIN scholarship_exam_candidates c ON c.id = s.candidate_id
             WHERE s.is_bot = 0 AND s.scanned_at BETWEEN ? AND ? ORDER BY s.id DESC LIMIT 100",
            $range
        )->result_array();

        $bots = $this->db->query(
            'SELECT COUNT(*) AS n FROM scholarship_qr_scans WHERE is_bot = 1 AND scanned_at BETWEEN ? AND ?',
            $range
        )->row_array();

        return array('totals' => $totals, 'daily' => $daily, 'breakdown' => $breakdown, 'recent' => $recent, 'bots' => (int) $bots['n']);
    }

    public function findExternalDuplicate($exam_id, $mobile)
    {
        return $this->db->get_where('scholarship_exam_candidates', array(
            'scholarship_exam_id' => $exam_id,
            'is_external' => 1,
            'external_mobile' => $mobile,
        ))->row_array();
    }

    public function getCandidatesByMobile($exam_id = null, $mobile = null)
    {
        if ($mobile === null && $exam_id !== null) {
            $mobile = $exam_id;
            $exam_id = null;
        }
        $mobile = trim((string)$mobile);
        $this->db->select('scholarship_exam_candidates.*, classes.class as class_name, scholarship_exams.title as exam_title');
        $this->db->from('scholarship_exam_candidates');
        $this->db->join('classes', 'classes.id = scholarship_exam_candidates.external_class_id', 'left');
        $this->db->join('scholarship_exams', 'scholarship_exams.id = scholarship_exam_candidates.scholarship_exam_id', 'left');
        if (!empty($exam_id)) {
            $this->db->where('scholarship_exam_candidates.scholarship_exam_id', $exam_id);
        }
        $this->db->where('scholarship_exam_candidates.external_mobile', $mobile);
        $this->db->order_by('scholarship_exam_candidates.id', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Inserts an external candidate with a unique roll number. roll_no has a UNIQUE index,
     * so a concurrent submission that grabs the same number is retried with the next one.
     */
    public function insertExternalCandidate($exam, array $data)
    {
        $prefix = $exam['roll_no_prefix'] ?: 'EXT-';
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $row = $this->db->query(
                'SELECT MAX(CAST(SUBSTRING(roll_no, ?) AS UNSIGNED)) AS n FROM scholarship_exam_candidates WHERE roll_no LIKE ?',
                array(strlen($prefix) + 1, $this->db->escape_like_str($prefix) . '%')
            )->row();
            $next = max(1000, (int) ($row->n ?? 0)) + 1 + $attempt;
            $roll_no = $prefix . $next;

            $data['roll_no'] = $roll_no;
            $data['admit_card_no'] = 'ADM-EXT-' . sprintf('%05d', $next) . '-' . $exam['id'];

            $this->db->db_debug = false;
            $ok = $this->db->insert('scholarship_exam_candidates', $data);
            $this->db->db_debug = true;
            if ($ok) {
                return array('id' => $this->db->insert_id(), 'roll_no' => $roll_no, 'admit_card_no' => $data['admit_card_no']);
            }
        }
        return false;
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

    /* ================= Study Materials & Sample Papers ================= */

    public function getMaterials($exam_id = null, $class_id = null, $material_type = null)
    {
        $this->checkAndSetupTables();
        $this->db->select('scholarship_study_materials.*, scholarship_exams.title as exam_title, scholarship_exams.exam_code, classes.class as class_name');
        $this->db->from('scholarship_study_materials');
        $this->db->join('scholarship_exams', 'scholarship_exams.id = scholarship_study_materials.scholarship_exam_id', 'left');
        $this->db->join('classes', 'classes.id = scholarship_study_materials.class_id', 'left');

        if ($exam_id) {
            $this->db->where('scholarship_study_materials.scholarship_exam_id', $exam_id);
        }
        if ($class_id) {
            $this->db->where('scholarship_study_materials.class_id', $class_id);
        }
        if ($material_type) {
            $this->db->where('scholarship_study_materials.material_type', $material_type);
        }

        $this->db->order_by('scholarship_study_materials.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getMaterialById($id)
    {
        $this->checkAndSetupTables();
        return $this->db->get_where('scholarship_study_materials', array('id' => $id))->row_array();
    }

    public function addMaterial($data)
    {
        $this->checkAndSetupTables();
        $this->db->insert('scholarship_study_materials', $data);
        return $this->db->insert_id();
    }

    public function deleteMaterial($id)
    {
        $this->checkAndSetupTables();
        $material = $this->getMaterialById($id);
        if ($material) {
            if (!empty($material['file_path']) && file_exists(FCPATH . $material['file_path'])) {
                @unlink(FCPATH . $material['file_path']);
            }
            $this->db->where('id', $id)->delete('scholarship_study_materials');
            return true;
        }
        return false;
    }

    public function authenticateCandidate($login_identifier, $pin, $candidate_id = null)
    {
        $login_identifier = trim((string)$login_identifier);
        $pin = trim((string)$pin);

        $this->db->select('scholarship_exam_candidates.*, scholarship_exams.title as exam_title, scholarship_exams.exam_code, scholarship_exams.exam_center, scholarship_exams.instructions, scholarship_exams.admit_card_status, scholarship_exams.result_status_published');
        $this->db->from('scholarship_exam_candidates');
        $this->db->join('scholarship_exams', 'scholarship_exams.id = scholarship_exam_candidates.scholarship_exam_id', 'inner');
        if (!empty($candidate_id)) {
            $this->db->where('scholarship_exam_candidates.id', $candidate_id);
        } else {
            $this->db->group_start();
            $this->db->where('scholarship_exam_candidates.roll_no', $login_identifier);
            $this->db->or_where('scholarship_exam_candidates.external_mobile', $login_identifier);
            $this->db->group_end();
        }
        $this->db->where('scholarship_exam_candidates.password_pin', $pin);
        $candidate = $this->db->get()->row_array();

        return $candidate;
    }

    public function updateCandidateProfile($candidate_id, $data)
    {
        $this->db->where('id', $candidate_id);
        return $this->db->update('scholarship_exam_candidates', $data);
    }

    public function saveLead($mobile, $exam_id = null, $data = array())
    {
        $mobile = preg_replace('/\D/', '', (string)$mobile);
        if (strlen($mobile) > 10 && substr($mobile, 0, 2) === '91') {
            $mobile = substr($mobile, 2);
        } elseif (strlen($mobile) > 10) {
            $mobile = substr($mobile, -10);
        }
        if (strlen($mobile) != 10) {
            return false;
        }

        $this->checkAndSetupTables();

        $existing = $this->db->get_where('scholarship_leads', array('mobile' => $mobile))->row_array();
        
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : (isset($this->input) && method_exists($this->input, 'ip_address') ? $this->input->ip_address() : '127.0.0.1');
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 500) : '';
        $now = date('Y-m-d H:i:s');

        if ($existing) {
            $update_data = array(
                'updated_at' => $now,
                'ip_address' => $ip,
                'user_agent' => $ua
            );
            if (!empty($exam_id)) $update_data['exam_id'] = $exam_id;
            if (!empty($data['candidate_name'])) $update_data['candidate_name'] = $data['candidate_name'];
            if (!empty($data['class_id'])) $update_data['class_id'] = $data['class_id'];
            if (!empty($data['status'])) $update_data['status'] = $data['status'];
            if (!empty($data['registered_at'])) $update_data['registered_at'] = $data['registered_at'];

            $this->db->where('id', $existing['id']);
            $this->db->update('scholarship_leads', $update_data);
            return $existing['id'];
        } else {
            $insert_data = array(
                'exam_id' => $exam_id ?: null,
                'mobile' => $mobile,
                'candidate_name' => !empty($data['candidate_name']) ? $data['candidate_name'] : null,
                'class_id' => !empty($data['class_id']) ? $data['class_id'] : null,
                'source' => !empty($data['source']) ? $data['source'] : 'web_form',
                'status' => !empty($data['status']) ? $data['status'] : 'draft',
                'ip_address' => $ip,
                'user_agent' => $ua,
                'created_at' => $now,
                'updated_at' => $now
            );
            $this->db->insert('scholarship_leads', $insert_data);
            return $this->db->insert_id();
        }
    }

    public function markLeadRegistered($mobile, $exam_id = null)
    {
        $mobile = preg_replace('/\D/', '', (string)$mobile);
        if (strlen($mobile) > 10) {
            $mobile = substr($mobile, -10);
        }
        if (empty($mobile)) return false;

        $this->checkAndSetupTables();

        $this->db->where('mobile', $mobile);
        $this->db->update('scholarship_leads', array(
            'status' => 'registered',
            'registered_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }

    public function getSocialProofCount($exam_id = null)
    {
        $this->checkAndSetupTables();

        $this->db->from('scholarship_exam_candidates');
        if (!empty($exam_id)) {
            $this->db->where('scholarship_exam_id', $exam_id);
        }
        $actual_count = $this->db->count_all_results();

        // 500+ baseline social proof that increases by 2x for every registration!
        $social_count = 500 + ($actual_count * 2);
        if ($social_count < 524) {
            $social_count = 524 + ($actual_count * 2);
        }

        return array(
            'actual_count' => $actual_count,
            'social_proof_count' => $social_count
        );
    }
}
