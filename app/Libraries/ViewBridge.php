<?php

namespace App\Libraries;

use App\Models\SettingModel;
use App\Models\SessionModel;

/**
 * ViewBridge
 *
 * Provides compatibility objects ($this->customlib, $this->rbac, $this->load, $this->lang)
 * so existing LMS views can render seamlessly inside CodeIgniter 4.
 */
class ViewBridge
{
    public LoaderBridge $load;
    public CustomlibBridge $customlib;
    public RbacBridge $rbac;
    public LangBridge $lang;
    public SettingModel $setting_model;
    public ModuleLibBridge $module_lib;
    public $auth;

    public function __construct()
    {
        $this->load          = new LoaderBridge();
        $this->customlib     = new CustomlibBridge();
        $this->rbac          = new RbacBridge();
        $this->lang          = new LangBridge();
        $this->setting_model = new SettingModel();
        $this->module_lib    = new ModuleLibBridge();
        $this->auth          = new AuthBridge();
    }
}

class AuthBridge
{
    protected ?object $authInstance = null;

    protected function getAuth()
    {
        if ($this->authInstance === null) {
            $ci = &get_instance();
            if ($ci !== null && isset($ci->auth) && is_object($ci->auth)) {
                $this->authInstance = $ci->auth;
            } elseif (class_exists('Auth')) {
                $this->authInstance = new \Auth();
            } elseif (class_exists('App\Libraries\Auth')) {
                $this->authInstance = new \App\Libraries\Auth();
            }
        }
        return $this->authInstance;
    }

    public function addonchk($prod = null, $return_url = false)
    {
        $inst = $this->getAuth();
        if ($inst && method_exists($inst, 'addonchk')) {
            return $inst->addonchk($prod, $return_url);
        }
        return true;
    }

    public function __call($method, $args)
    {
        $inst = $this->getAuth();
        if ($inst && method_exists($inst, $method)) {
            return call_user_func_array([$inst, $method], $args);
        }
        return false;
    }
}

class LoaderBridge
{
    protected ?object $controllerInstance = null;

    public function setControllerInstance(object $ci): void
    {
        $this->controllerInstance = $ci;
    }
    public function view(string $view, array $data = [], bool $return = false)
    {
        // Support CI3 relative view paths
        $view = str_replace('.php', '', $view);
        if (!isset($data['count'])) {
            $data['count'] = 0;
        }
        if (!isset($data['search_type'])) {
            $data['search_type'] = '';
        }
        if (!isset($data['search_text'])) {
            $data['search_text'] = '';
        }
        if (!isset($data['section_list'])) {
            $data['section_list'] = [];
        }
        if (!isset($data['classlist'])) {
            $data['classlist'] = [];
        }
        $output = view($view, $data);

        if ($return) {
            return $output;
        }

        echo $output;
    }

    public function library($library = '', $params = null, $object_name = null)
    {
        $ci = &get_instance();
        if (is_array($library)) {
            foreach ($library as $l) {
                $this->library($l, $params);
            }
            return $this;
        }

        $lowerLib = strtolower($library);
        $varmap = [
            'user_agent' => 'agent',
            'unit_test'  => 'unit',
        ];

        $name = $object_name ?? ($varmap[$lowerLib] ?? $lowerLib);
        $class = ucfirst($library);
        if ($lowerLib === 'user_agent') {
            $class = 'CI_User_agent';
        }

        // Check if library file exists in app/Libraries or application/libraries or system/libraries
        $libPaths = [
            APPPATH . 'Libraries/' . ucfirst($library) . '.php',
            APPPATH . 'Libraries/' . $library . '.php',
            APPPATH . '../application/libraries/' . ucfirst($library) . '.php',
            APPPATH . '../application/libraries/' . $library . '.php',
            APPPATH . '../application/libraries/' . ucfirst(strtolower($library)) . '.php',
            APPPATH . '../application/libraries/' . strtolower($library) . '.php',
            APPPATH . '../application/libraries/' . ucfirst($library) . '/' . ucfirst($library) . '.php',
            APPPATH . '../application/libraries/' . $library . '/' . $library . '.php',
            BASEPATH . 'libraries/' . ucfirst($library) . '.php',
            BASEPATH . 'libraries/' . $library . '.php',
        ];
        foreach ($libPaths as $lp) {
            if (file_exists($lp)) {
                require_once $lp;
                break;
            }
        }

        $matchedClass = class_exists($class) ? $class : (class_exists($library) ? $library : (class_exists(strtolower($library)) ? strtolower($library) : null));
        if ($matchedClass !== null) {
            $obj = ($params === null) ? new $matchedClass() : new $matchedClass($params);
            if ($this->controllerInstance !== null) {
                $this->controllerInstance->$name = $obj;
                $this->controllerInstance->$lowerLib = $obj;
                $this->controllerInstance->$library = $obj;
            }
            if ($ci !== null) {
                $ci->$name = $obj;
                $ci->$lowerLib = $obj;
                $ci->$library = $obj;
            }
            return $obj;
        }
        return $this;
    }

    public function model($model, $name = '', $db_conn = false)
    {
        $ci = &get_instance();
        if (is_array($model)) {
            foreach ($model as $m) {
                $this->model($m);
            }
            return $this;
        }

        if (empty($name)) {
            $propName = basename(str_replace('\\', '/', $model));
        } else {
            $propName = $name;
        }
        $lowerName = strtolower($propName);

        // Compute CI4 namespace name (e.g. cbseexam/cbseexam_exam_model -> App\Models\Cbseexam\CbseexamExamModel)
        $subparts = explode('/', str_replace('\\', '/', $model));
        $ci4Parts = [];
        foreach ($subparts as $sub) {
            $subPartsUnderscore = explode('_', $sub);
            $ci4Parts[] = implode('', array_map('ucfirst', $subPartsUnderscore));
        }
        $ci4Name = 'App\\Models\\' . implode('\\', $ci4Parts);

        $instance = null;
        if (class_exists($ci4Name)) {
            $instance = new $ci4Name();
        } elseif (class_exists($model)) {
            $instance = new $model();
        } else {
            // Check legacy application/models paths
            $modelBase = basename(str_replace('\\', '/', $model));
            $modelDir = dirname(str_replace('\\', '/', $model));
            $dirPrefix = ($modelDir === '.' || empty($modelDir)) ? '' : $modelDir . '/';

            $candidates = [
                APPPATH . '../application/models/' . $dirPrefix . ucfirst($modelBase) . '.php',
                APPPATH . '../application/models/' . $dirPrefix . $modelBase . '.php',
                APPPATH . '../application/models/' . $dirPrefix . strtolower($modelBase) . '.php',
            ];

            foreach ($candidates as $candidate) {
                if (file_exists($candidate)) {
                    require_once $candidate;
                    $class = basename($candidate, '.php');
                    if (class_exists($class)) {
                        $instance = new $class();
                        break;
                    }
                    $ucClass = ucfirst($class);
                    if (class_exists($ucClass)) {
                        $instance = new $ucClass();
                        break;
                    }
                }
            }
        }

        if ($instance !== null && $ci !== null) {
            $ci->$propName = $instance;
            $ci->$lowerName = $instance;
            $ci->$model = $instance;
        }

        return $this;
    }

    public function helper($helpers = [])
    {
        if (is_array($helpers)) {
            helper($helpers);
        } else {
            helper([$helpers]);
        }
        return $this;
    }

    public function config($file = '', $use_sections = false, $fail_gracefully = false)
    {
        return true;
    }

    public function database($params = '', $return = false, $query_builder = null)
    {
        $ci = &get_instance();
        $bridge = new \DatabaseBridge();
        $ci->db = $bridge;

        if ($return) {
            return $bridge;
        }
        return $this;
    }

    public function language($files = [], $lang = '')
    {
        return $this;
    }

    public function is_loaded($class = '')
    {
        $ci = &get_instance();
        if (empty($class)) {
            return [];
        }
        $lower = strtolower($class);
        if ($ci !== null && (isset($ci->$class) || isset($ci->$lower))) {
            return $class;
        }
        return false;
    }
}

class CustomlibBridge
{
    protected ?array $admin = null;
    protected mixed $setting = null;

    public function __construct()
    {
        $session = session();
        if ($session->has('admin')) {
            $this->admin = $session->get('admin');
        }

        $settingModel = new SettingModel();
        $this->setting = $settingModel->getSetting();
    }

    public function getRTL(): string
    {
        $admin = $this->admin ?? session()->get('admin');
        if ($admin && isset($admin['is_rtl'])) {
            return $admin['is_rtl'] === 'disabled' ? '' : "dir='rtl' lang='ar'";
        }

        $student = session()->get('student');
        if ($student && isset($student['is_rtl'])) {
            return $student['is_rtl'] === 'disabled' ? '' : "dir='rtl' lang='ar'";
        }

        if (!empty($this->setting['is_rtl']) && $this->setting['is_rtl'] === 'disabled') {
            return '';
        }

        return '';
    }

    public function getAppName(): string
    {
        return $this->setting['name'] ?? 'School ERP';
    }

    public function getSchoolName(): string
    {
        return $this->setting['name'] ?? 'School ERP';
    }

    public function getBaseUrl(): string
    {
        return base_url();
    }

    public function getSchoolCurrencyFormat(): string
    {
        return $this->setting['currency_symbol'] ?? '$';
    }

    public function getCSRF(): string
    {
        if (function_exists('csrf_field')) {
            return csrf_field();
        }
        return '';
    }

    public function getCurrentThemeSetting()
    {
        $session = session();
        $admin = $session->get('admin');
        $student = $session->get('student');

        if ($admin && isset($admin['theme']) && is_array($admin['theme'])) {
            return $admin['theme'];
        }
        if ($student && isset($student['theme']) && is_array($student['theme'])) {
            return $student['theme'];
        }

        return [
            'theme_color'      => '#7367f0',
            'theme_shadow'     => '',
            'theme_background' => 'light-mode',
            'theme_content'    => 'container-fluid',
            'theme_type'       => 'default',
            'theme_navigation' => 'expanded',
            'theme_font_color' => '',
        ];
    }

    public function getLoggedInUserData(): array
    {
        return $this->admin ?? session()->get('student') ?? [];
    }

    public function getUserData(): array
    {
        $admin = $this->admin ?? session()->get('admin');
        if ($admin && !empty($admin['id'])) {
            $staffModel = new \App\Models\StaffModel();
            $staff = $staffModel->get((int)$admin['id']);
            if (!empty($staff)) {
                if (!isset($staff['role_id']) && isset($admin['role_id'])) {
                    $staff['role_id'] = $admin['role_id'];
                }
                return array_merge($admin, $staff);
            }
            return $admin;
        }

        $student = session()->get('student');
        if ($student) {
            $student['role_id'] = $student['role_id'] ?? '';
            return $student;
        }

        return $this->admin ?? [];
    }

    public function getStaffID(): ?int
    {
        return isset($this->admin['id']) ? (int)$this->admin['id'] : null;
    }

    public function getStaffRole(): string
    {
        $admin = $this->admin ?? session()->get('admin');
        if ($admin && isset($admin['roles']) && is_array($admin['roles'])) {
            $roles = $admin['roles'];
            $role_key = key($roles);
            return json_encode(['id' => $roles[$role_key], 'name' => $role_key]);
        }
        return json_encode(['id' => '7', 'name' => 'Super Admin']);
    }

    public function dateformat($date): string
    {
        if (empty($date) || $date === '0000-00-00') {
            return '';
        }
        $format = $this->setting['date_format'] ?? 'd-m-Y';
        // Convert CI date format like d-m-Y or Y-m-d to standard PHP format
        $phpFormat = str_replace(['dd', 'mm', 'yyyy'], ['d', 'm', 'Y'], strtolower($format));
        return date($phpFormat, strtotime($date));
    }

    public function dateFormatToYYYYMMDD($date): string
    {
        if (empty($date)) {
            return date('Y-m-d');
        }
        return date('Y-m-d', strtotime(str_replace('/', '-', $date)));
    }

    public function datetostrtotime($date): int
    {
        if (empty($date)) {
            return time();
        }
        return (int)strtotime(str_replace('/', '-', $date));
    }

    public function getTimeZone(): string
    {
        return $this->setting['timezone'] ?? 'Asia/Kolkata';
    }

    public function getFullName($firstname, $middlename, $lastname, $is_middlename = true, $is_lastname = true): string
    {
        $name = $firstname;
        if ($is_middlename && !empty($middlename)) {
            $name .= ' ' . $middlename;
        }
        if ($is_lastname && !empty($lastname)) {
            $name .= ' ' . $lastname;
        }
        return trim((string)$name);
    }

    public function superadmin_visible()
    {
        if ($this->admin && isset($this->admin['superadmin_restriction'])) {
            return $this->admin['superadmin_restriction'];
        }
        return 'disabled';
    }

    public function __call($method, $args)
    {
        // Check if legacy Customlib class exists and delegate
        if (class_exists('\\Customlib')) {
            static $legacyInstance = null;
            if ($legacyInstance === null) {
                $legacyInstance = new \Customlib();
            }
            if (method_exists($legacyInstance, $method)) {
                return call_user_func_array([$legacyInstance, $method], $args);
            }
        }
        return null;
    }
}


class ModuleLibBridge
{
    protected static $legacyInstance = null;

    protected function getInstance()
    {
        if (self::$legacyInstance === null) {
            $file = APPPATH . 'Libraries/Module_lib.php';
            if (file_exists($file)) {
                require_once $file;
            } else {
                $file = APPPATH . '../application/libraries/Module_lib.php';
                if (file_exists($file)) {
                    require_once $file;
                }
            }
            if (class_exists('Module_lib')) {
                self::$legacyInstance = new \Module_lib();
            }
        }
        return self::$legacyInstance;
    }

    public function hasModule($module_shortcode): bool
    {
        $inst = $this->getInstance();
        if ($inst && method_exists($inst, 'hasModule')) {
            return (bool)$inst->hasModule($module_shortcode);
        }
        return false;
    }

    public function hasActive($module = null): bool
    {
        $inst = $this->getInstance();
        if ($inst && method_exists($inst, 'hasActive')) {
            return (bool)$inst->hasActive($module);
        }
        return false;
    }

    public function __call($method, $args)
    {
        $inst = $this->getInstance();
        if ($inst && method_exists($inst, $method)) {
            return call_user_func_array([$inst, $method], $args);
        }
        return false;
    }
}

class RbacBridge
{
    public function hasPrivilege(string $category, string $permission): bool
    {
        $session = session();
        if (!$session->has('admin')) {
            return true; // Fallback during local dev/testing
        }

        $admin = $session->get('admin');
        // Super Admin has all privileges
        if (isset($admin['roles']['Super Admin']) || (isset($admin['role_id']) && (int)$admin['role_id'] === 7)) {
            return true;
        }

        // Check assigned permissions array in session
        if (isset($admin['privileges'][$category][$permission])) {
            return (bool)$admin['privileges'][$category][$permission];
        }

        return true;
    }
}

class LangBridge
{
    protected static array $lines = [];
    protected static bool $loaded = false;

    public static function loadFile(string $file): void
    {
        if (file_exists($file)) {
            if (!defined('BASEPATH')) {
                define('BASEPATH', true);
            }
            $lang = [];
            include $file;
            if (!empty($lang) && is_array($lang)) {
                self::$lines = array_merge(self::$lines, $lang);
            }
        }
    }

    public static function initLanguage(string $language = 'English'): void
    {
        if (self::$loaded && !empty(self::$lines)) {
            return;
        }
        self::$loaded = true;

        $baseApp = defined('APPPATH') ? APPPATH : dirname(__DIR__) . '/';
        $paths = [
            $baseApp . '../application/language/' . $language . '/app_files',
            $baseApp . 'Language/' . $language . '/app_files',
        ];

        foreach ($paths as $dir) {
            if (is_dir($dir)) {
                $files = glob($dir . '/*.php');
                if ($files) {
                    foreach ($files as $f) {
                        self::loadFile($f);
                    }
                }
            }
        }

        // Also check root language files
        $rootPaths = [
            $baseApp . '../application/language/' . $language,
            $baseApp . 'Language/' . $language,
        ];
        foreach ($rootPaths as $rdir) {
            if (is_dir($rdir)) {
                $rfiles = glob($rdir . '/*.php');
                if ($rfiles) {
                    foreach ($rfiles as $rf) {
                        self::loadFile($rf);
                    }
                }
            }
        }
    }

    public function line($key = null, $log_errors = true)
    {
        if (empty($key)) {
            return false;
        }

        $strKey = (string)$key;
        self::initLanguage();

        // 1. Check legacy lines array first (system_lang, accounts_lang, etc.)
        if (isset(self::$lines[$strKey])) {
            return self::$lines[$strKey];
        }

        // 2. Check CI4 translation
        $translated = lang('App.' . $strKey);
        if ($translated !== 'App.' . $strKey) {
            return $translated;
        }

        return ucwords(str_replace('_', ' ', $strKey));
    }
}
