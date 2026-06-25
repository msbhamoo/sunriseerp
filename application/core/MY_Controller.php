<?php

define('THEMES_DIR', 'themes');
define('BASE_URI', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

class MY_Controller extends CI_Controller
{
    public $exceptions;

    protected $langs = array();

    protected $lazy_models = array(
        'session_model' => true,
        'staff_model' => true,
        'section_model' => true,
        'setting_model' => true,
        'class_model' => true,
        'category_model' => true,
        'religion_model' => true,
        'cast_model' => true,
        'classsection_model' => true,
        'feecategory_model' => true,
        'student_model' => true,
        'feemaster_model' => true,
        'feecategory_model' => true,
        'feetype_model' => true,
        'studentfee_model' => true,
        'stuattendence_model' => true,
        'attendencetype_model' => true,
        'studentsession_model' => true,
        'language_model' => true,
        'admin_model' => true,
        'smsconfig_model' => true,
        'langpharses_model' => true,
        'subject_model' => true,
        'teacher_model' => true,
        'teachersubject_model' => true,
        'exam_model' => true,
        'mark_model' => true,
        'examschedule_model' => true,
        'examresult_model' => true,
        'expense_model' => true,
        'expensehead_model' => true,
        'studenttransportfee_model' => true,
        'book_model' => true,
        'grade_model' => true,
        'timetable_model' => true,
        'hostel_model' => true,
        'route_model' => true,
        'content_model' => true,
        'user_model' => true,
        'notification_model' => true,
        'paymentsetting_model' => true,
        'payroll_model' => true,
        'roomtype_model' => true,
        'department_model' => true,
        'designation_model' => true,
        'hostelroom_model' => true,
        'vehicle_model' => true,
        'vehroute_model' => true,
        'librarian_model' => true,
        'accountant_model' => true,
        'homework_model' => true,
        'librarymanagement_model' => true,
        'librarymember_model' => true,
        'bookissue_model' => true,
        'feegroup_model' => true,
        'feegrouptype_model' => true,
        'feesessiongroup_model' => true,
        'studentfeemaster_model' => true,
        'feediscount_model' => true,
        'emailconfig_model' => true,
        'income_model' => true,
        'incomehead_model' => true,
        'itemcategory_model' => true,
        'schoolhouse_model' => true,
        'item_model' => true,
        'messages_model' => true,
        'itemstore_model' => true,
        'itemsupplier_model' => true,
        'notificationsetting_model' => true,
        'itemstock_model' => true,
        'itemissue_model' => true,
        'userlog_model' => true,
        'cms_program_model' => true,
        'cms_menu_model' => true,
        'cms_media_model' => true,
        'cms_page_model' => true,
        'cms_menuitems_model' => true,
        'cms_page_content_model' => true,
        'role_model' => true,
        'calendar_model' => true,
        'userpermission_model' => true,
        'staffroles_model' => true,
        'staffattendancemodel' => true,
        'rolepermission_model' => true,
        'Certificate_model' => true,
        'classteacher_model' => true,
        'Generatecertificate_model' => true,
        'Student_id_card_model' => true,
        'timeline_model' => true,
        'Generateidcard_model' => true,
        'Module_model' => true,
        'subjectgroup_model' => true,
        'studentsubjectgroup_model' => true,
        'subjecttimetable_model' => true,
        'studentsubjectattendence_model' => true,
        'audit_model' => true,
        'Chat_model' => true,
        'apply_leave_model' => true,
        'disable_reason_model' => true,
        'question_model' => true,
        'leavetypes_model' => true,
        'alumni_model' => true,
        'lessonplan_model' => true,
        'syllabus_model' => true,
        'Staffidcard_model' => true,
        'Generatestaffidcard_model' => true,
        'visitors_model' => true,
        'video_tutorial_model' => true,
        'customfield_model' => true,
        'onlinestudent_model' => true,
        'houselist_model' => true,
        'onlineexam_model' => true,
        'onlineexamquestion_model' => true,
        'onlineexamresult_model' => true,
        'examstudent_model' => true,
        'admitcard_model' => true,
        'marksheet_model' => true,
        'chatuser_model' => true,
        'examgroupstudent_model' => true,
        'examgroup_model' => true,
        'batchsubject_model' => true,
        'filetype_model' => true,
        'currency_model' => true,
        'examsubject_model' => true,
        'feereminder_model' => true
    );

    public function __construct()
    {

        parent::__construct();

        $this->load->library('Db_manager');
        $this->config->load('license');
        $this->load->helper(array('language', 'directory', 'customfield', 'custom', 'mime'));
        $this->load->library(array('Role', 'Smsgateway', 'QDMailer', 'Adler32', 'Aes'));
        $this->load->library(array('auth', 'module_lib', 'pushnotification', 'jsonlib', 'datatables'));
        $this->load->library(array('version_control'));

        if ($this->session->has_userdata('admin')) {

            $admin    = $this->session->userdata('admin');
            $language = ($admin['language']['language']);
        } else if ($this->session->has_userdata('student')) {

            $student = $this->session->userdata('student');

            $language = ($student['language']['language']);
        } else {
            $this->school_details = $this->setting_model->getSchoolDetail();
            $language             = ($this->school_details->language);
        }

        $this->config->set_item('language', $language);
        $lang_array = array('form_validation_lang');
        $map        = directory_map(APPPATH . "./language/" . $language . "/app_files");
        foreach ($map as $lang_key => $lang_value) {
            $lang_array[] = 'app_files/' . str_replace(".php", "", $lang_value);
        }

        $this->load->language($lang_array, $language);
        
        // Audit Log User Tracking
        if (isset($this->customlib) && isset($this->db)) {
            $audit_user_id = $this->customlib->getStaffID();
            if (!empty($audit_user_id)) {
                $this->db->query("SET @current_audit_user_id = " . (int)$audit_user_id);
            }
        }
    }

    public function __get($key)
    {
        if (isset($this->lazy_models[$key])) {
            $this->load->model($key);
            return $this->$key;
        }

        // Case-insensitive fallback
        $lower_key = strtolower($key);
        foreach ($this->lazy_models as $model_name => $val) {
            if (strtolower($model_name) === $lower_key) {
                $this->load->model($model_name);
                // Return using original $key to ensure compatibility
                // but CodeIgniter's loader sets the property matching the casing of the string passed
                // wait, if we load 'Module_model', it sets $this->Module_model.
                // we should assign it to $this->$key as well, so future accesses are O(1).
                if (!isset($this->$key) && isset($this->$model_name)) {
                    $this->$key = $this->$model_name;
                }
                return $this->$key;
            }
        }

        return null;
    }

}

class Admin_Controller extends MY_Controller
{

    protected $aaaa = false;

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged_in();
        $this->check_license();
        $this->load->library('rbac');
        $this->config->load('app-config');
        $this->config->load('ci-blog');
        $this->config->load('custom_filed-config');
    }

    public function check_license()
    {

        $license = $this->config->item('SSLK');

        if (!empty($license)) {

            $regex = "/^[A-Z0-9]{6}-[A-Z0-9]{6}-[A-Z0-9]{6}-/";

            if (preg_match($regex, $license)) {
                $valid_string = $this->aes->validchk('encrypt', base_url());

                if (strpos($license, $valid_string) !== false) {

                    true; //valid
                } else {
                    $this->update_ss_routine();
                }
            } else {

                $this->update_ss_routine();
            }
        }
    }

    public function update_ss_routine()
    {

        $license       = $this->config->item('SSLK');
        $fname         = APPPATH . 'config/license.php';
        $update_handle = fopen($fname, "r");
        $content       = fread($update_handle, filesize($fname));
        $file_contents = str_replace('$config[\'SSLK\'] = \'' . $license . '\'', '$config[\'SSLK\'] = \'\'', $content);
        $update_handle = fopen($fname, 'w') or die("can't open file");
        if (fwrite($update_handle, $file_contents)) {

        }
        fclose($update_handle);

        $this->config->set_item('SSLK', '');
    }

}

class Student_Controller extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->school_details = $this->setting_model->getSchoolDetail();
        if ($this->school_details->maintenance_mode) {
            echo $this->load->view('maintenance', '', true);
            exit;
        }

        $this->load->library('studentmodule_lib');
        $this->load->library('cart');
        $this->config->load('app-config');
        $this->auth->is_logged_in_user('student');
        $is_lock_panel = check_lock_enabled();
		
		check_login_token();
		
        if ($is_lock_panel) {

            $active_class  = $this->router->fetch_class();
            $active_method = $this->router->fetch_method();
            if (($active_class == "user" && (
                $active_method == "fees"
                || $active_method == "getcollectfee"
                || $active_method == "geBalanceFee"
                || $active_method == "change_currency"
                || $active_method == "user_language"
                || $active_method == "addstudentfee"
                || $active_method == "printFeesByName"

            ))

                || ($active_class == "offlinepayment" && (
                    $active_method == "index"

                ))
            ) {

            } else {
                redirect('user/user/fees');
            }

        }

    }

}

class Studentgateway_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('studentmodule_lib');
        $this->config->load('app-config');
        $this->auth->is_logged_in_user('student');

    }

}

class Public_Controller extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
         $active_class  = $this->router->fetch_class();
         $active_method = $this->router->fetch_method();
       
        if (($active_class == "site" || $active_class =="gauthenticate") && ($active_method == "userlogin")) {

            $this->school_details = $this->setting_model->getSchoolDetail();
            if ($this->school_details->maintenance_mode) {
                echo $this->load->view('maintenance', '', true);
                exit;
            }

        }

    }

}

class OnlineAdmission_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('custom');
    }

}

class Parent_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged_in_user('parent');
        $this->config->load('app-config');
        $this->load->library('parentmodule_lib');
    }

}

class Front_Controller extends CI_Controller
{

    protected $data           = array();
    protected $school_details = array();
    protected $parent_menu    = '';
    protected $page_title     = '';
    protected $theme_path     = '';
    protected $front_setting  = '';

    public function __construct()
    {

        parent::__construct();
        $this->check_installation();
        $this->load->database();
                $this->load->library(array('Smsgateway', 'QDMailer'));
        $this->load->model(array('setting_model', 'language_model', 'Module_model', 'cms_program_model', 'cms_menu_model', 'cms_menuitems_model', 'cms_page_model', 'cms_page_content_model', 'class_model', 'category_model', 'religion_model', 'cast_model', 'notificationsetting_model'));

        if ($this->config->item('installed') == true) {

            $this->db->reconnect();
        }
        $this->load->helper('language');
        $this->school_details = $this->setting_model->getSchoolDetail();
        $this->customlib->initFrontSession();

        if ($this->school_details->maintenance_mode) {
            echo $this->load->view('maintenance', '', true);
            exit;
        }

        $this->load->model('frontcms_setting_model');

        $this->front_setting = $this->frontcms_setting_model->get();

        if (!$this->front_setting) {
            redirect('site/userlogin');
        } else {
            $front_cms_class  = $this->router->fetch_class();
            $front_cms_method = $this->router->fetch_method();
            if ($this->front_setting->is_active_front_cms) {
                $this->config->set_item('front_layout', true);
            }
            if (!$this->front_setting->is_active_front_cms) {
                $this->config->set_item('front_layout', false);
            }

            if (!$this->front_setting->is_active_front_cms && !$this->school_details->online_admission) {
                redirect('site/userlogin');
            }

            if ($this->school_details->online_admission) {
                if (!$this->front_setting->is_active_front_cms &&
                    !($front_cms_class == "welcome" && $front_cms_method == "admission") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "editonlineadmission") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "online_admission_review") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "getSections") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "submitadmission") &&
                    !($front_cms_class == "checkout" && $front_cms_method == "index") &&
                    !($front_cms_class == "checkout" && $front_cms_method == "successinvoice") &&
                    !($front_cms_class == "checkout" && $front_cms_method == "paymentfailed") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "checkadmissionstatus")&&
                    !($front_cms_class == "welcome" && $front_cms_method == "download")
                ) {
                    redirect('site/userlogin');
                }
            }

        }

        $this->theme_path = $this->front_setting->theme;
//================
        $language = ($this->school_details->language);
        $this->config->set_item('language', $language);
        $this->load->helper(array('directory', 'custom'));
        $lang_array = array('form_validation_lang');
        $map        = directory_map(APPPATH . "./language/" . $language . "/app_files");
        foreach ($map as $lang_key => $lang_value) {
            $lang_array[] = 'app_files/' . str_replace(".php", "", $lang_value);
        }

        $this->load->language($lang_array, $language);
//===============

        $this->load->config('ci-blog');
    }

    protected function load_theme($content = null, $layout = true)
    {

        $this->data['main_menus']     = '';
        $this->data['school_setting'] = $this->school_details;
        $this->data['front_setting']  = $this->front_setting;
        $menu_list                    = $this->cms_menu_model->getBySlug('main-menu');

        $footer_menu_list = $this->cms_menu_model->getBySlug('bottom-menu');
        if (count($menu_list) > 0) {
            $this->data['main_menus'] = $this->cms_menuitems_model->getMenus($menu_list['id']);
        }

        if (count($footer_menu_list) > 0) {
            $this->data['footer_menus'] = $this->cms_menuitems_model->getMenus($footer_menu_list['id']);
        }
        $this->data['layout_type'] = $layout;
        $this->data['header']      = $this->load->view('themes/' . $this->theme_path . '/header', $this->data, true);

        $this->data['slider'] = $this->load->view('themes/' . $this->theme_path . '/home_slider', $this->data, true);

        $this->data['footer'] = $this->load->view('themes/' . $this->theme_path . '/footer', $this->data, true);

        $this->base_assets_url = 'backend/' . THEMES_DIR . '/' . $this->theme_path . '/';

        $this->data['base_assets_url'] = base_url() . $this->base_assets_url; 
        $is_captcha                    = $this->captchalib->is_captcha('admission');
        $this->data["is_captcha"]      = $is_captcha;

        if ($layout == true) {
            $this->data['content'] = (is_null($content)) ? '' : $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/' . $content, $this->data, true);
            $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/layout', $this->data);
        } else {
            $this->data['content'] = (is_null($content)) ? '' : $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/' . $content, $this->data, true);
            $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/base_layout', $this->data);
        }
    }

    protected function load_theme_form($content = null, $layout = true)
    {

        $this->data['main_menus']     = '';
        $this->data['school_setting'] = $this->school_details;
        $this->data['front_setting']  = $this->front_setting;
        $menu_list                    = $this->cms_menu_model->getBySlug('main-menu');
        $footer_menu_list             = $this->cms_menu_model->getBySlug('bottom-menu');
        if (count($menu_list > 0)) {
            $this->data['main_menus'] = $this->cms_menuitems_model->getMenus($menu_list['id']);
        }

        if (count($footer_menu_list > 0)) {
            $this->data['footer_menus'] = $this->cms_menuitems_model->getMenus($footer_menu_list['id']);
        }
        $this->data['header'] = $this->load->view('themes/' . $this->theme_path . '/header', $this->data, true);

        $this->data['slider'] = $this->load->view('themes/' . $this->theme_path . '/home_slider', $this->data, true);

        $this->data['footer'] = $this->load->view('themes/' . $this->theme_path . '/footer', $this->data, true);

        $this->base_assets_url = 'backend/' . THEMES_DIR . '/' . $this->theme_path . '/';

        $this->data['base_assets_url'] = BASE_URI . $this->base_assets_url;

        $this->data['content'] = (is_null($content)) ? '' : $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/' . $content, $this->data, true);
        $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/layout', $this->data);

    }

    private function check_installation()
    {

        if ($this->uri->segment(1) !== 'install') {
            $this->load->config('migration');
            if ($this->config->item('installed') == false && $this->config->item('migration_enabled') == false) {
                redirect(base_url() . 'install/start');
            } else {
                if (is_dir(APPPATH . 'controllers/install')) {
                    echo '<h3>Delete the install folder from application/controllers/install</h3>';
                    die;
                }
            }
        }
    }

}
