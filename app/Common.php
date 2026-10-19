<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (!defined('BASEPATH')) {
    define('BASEPATH', realpath(__DIR__ . '/../system') . DIRECTORY_SEPARATOR);
}

// Load legacy CI3 system helpers for zero-downtime functional compatibility
$helperFiles = [
    BASEPATH . 'helpers/captcha_helper.php',
    BASEPATH . 'helpers/form_helper.php',
    BASEPATH . 'helpers/string_helper.php',
    BASEPATH . 'helpers/directory_helper.php',
    BASEPATH . 'helpers/download_helper.php',
    BASEPATH . 'helpers/security_helper.php',
];
foreach ($helperFiles as $helperFile) {
    if (file_exists($helperFile)) {
        require_once $helperFile;
    }
}

// Load application domain helpers
$appHelpers = glob(APPPATH . '../application/helpers/*.php');
if ($appHelpers) {
    foreach ($appHelpers as $ah) {
        require_once $ah;
    }
}



if (!function_exists('html_escape')) {
    function html_escape($var, $double_encode = true)
    {
        if (empty($var)) {
            return $var;
        }
        if (is_array($var)) {
            foreach (array_keys($var) as $key) {
                $var[$key] = html_escape($var[$key], $double_encode);
            }
            return $var;
        }
        return htmlspecialchars($var, ENT_QUOTES, config('App')->charset ?? 'UTF-8', $double_encode);
    }
}

// In CI4, APPPATH is app/, but CI3 code often maps APPPATH . "./language/..."
// Ensure directory junction/link or fallback exists for language directories
if (!is_dir(APPPATH . 'language')) {
    @mkdir(APPPATH . 'language', 0755, true);
}
// Copy or map language folder to APPPATH . 'language' if empty
if (is_dir(APPPATH . '../application/language') && count(glob(APPPATH . 'language/*')) === 0) {
    exec('robocopy "application\\language" "app\\language" /E /NFL /NDL /NJH /NJS /nc /ns /np');
}

class DatabaseBridge
{
    protected $connection;
    protected $builder;
    protected $currentTable = '';

    public function __construct()
    {
        $this->connection = \Config\Database::connect();
    }

    public function table(string $tableName)
    {
        $this->currentTable = $tableName;
        $this->builder = $this->connection->table($tableName);
        return $this->builder;
    }

    protected function getBuilder()
    {
        if ($this->builder === null) {
            $table = !empty($this->currentTable) ? $this->currentTable : 'dual';
            $this->builder = $this->connection->table($table);
        }
        return $this->builder;
    }

    protected $pendingCalls = [];

    protected function invokeBuilderMethod(string $method, array $args)
    {
        if ($this->builder !== null) {
            call_user_func_array([$this->builder, $method], $args);
        } else {
            $this->pendingCalls[] = [$method, $args];
        }
        return $this;
    }

    public function select($select = '*', $escape = null)
    {
        return $this->invokeBuilderMethod('select', func_get_args());
    }

    public function from($from)
    {
        $this->currentTable = $from;
        if ($this->builder !== null) {
            $this->builder->setTable($from);
        } else {
            $this->builder = $this->connection->table($from);
        }

        // Apply buffered calls
        if (!empty($this->pendingCalls)) {
            $calls = $this->pendingCalls;
            $this->pendingCalls = [];
            foreach ($calls as $call) {
                [$method, $args] = $call;
                call_user_func_array([$this->builder, $method], $args);
            }
        }

        return $this;
    }

    public function where($key, $value = null, $escape = null)
    {
        return $this->invokeBuilderMethod('where', func_get_args());
    }

    public function or_where($key, $value = null, $escape = null)
    {
        return $this->invokeBuilderMethod('orWhere', func_get_args());
    }

    public function where_in($key = null, $values = null, $escape = null)
    {
        return $this->invokeBuilderMethod('whereIn', func_get_args());
    }

    public function join($table, $cond, $type = '', $escape = null)
    {
        return $this->invokeBuilderMethod('join', func_get_args());
    }

    public function order_by($orderby, $direction = '', $escape = null)
    {
        return $this->invokeBuilderMethod('orderBy', func_get_args());
    }

    public function group_by($by, $escape = null)
    {
        return $this->invokeBuilderMethod('groupBy', func_get_args());
    }

    public function limit($value, $offset = 0)
    {
        return $this->invokeBuilderMethod('limit', func_get_args());
    }

    public function group_start()
    {
        return $this->invokeBuilderMethod('groupStart', func_get_args());
    }

    public function group_end()
    {
        return $this->invokeBuilderMethod('groupEnd', func_get_args());
    }

    public function get($table = '', ?int $limit = null, int $offset = 0)
    {
        if (!empty($table)) {
            $this->from($table);
        }
        $builder = $this->getBuilder();
        $result = $builder->get($limit, $offset);
        $this->builder = null; // reset for next query
        $this->currentTable = null;
        $this->pendingCalls = [];
        return new ResultBridge($result);
    }

    public function insert($table = '', $set = null, $escape = null)
    {
        $res = $this->connection->table($table)->insert($set, $escape);
        return $res;
    }

    public function insert_id()
    {
        return $this->connection->insertID();
    }

    public function update($table = '', $set = null, $where = null, $limit = null)
    {
        return $this->connection->table($table)->update($set, $where, $limit);
    }

    public function delete($table = '', $where = '', $limit = null, $reset_data = true)
    {
        return $this->connection->table($table)->delete($where, $limit, $reset_data);
    }

    public function query($sql, $binds = false, $return_object = true)
    {
        $res = $this->connection->query($sql, $binds);
        return new ResultBridge($res);
    }

    public function trans_start()
    {
        $this->connection->transStart();
    }

    public function trans_complete()
    {
        $this->connection->transComplete();
    }

    public function trans_status()
    {
        return $this->connection->transStatus();
    }

    public function trans_rollback()
    {
        $this->connection->transRollback();
    }

    public function trans_commit()
    {
        $this->connection->transCommit();
    }

    public function escape_like_str($str)
    {
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $str[$key] = $this->escape_like_str($val);
            }
            return $str;
        }
        return addcslashes((string)$str, '%_\\\'"');
    }

    public function escape_str($str, $like = false)
    {
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $str[$key] = $this->escape_str($val, $like);
            }
            return $str;
        }
        $escaped = $this->connection->escapeString((string)$str);
        if ($like === true) {
            return addcslashes($escaped, '%_');
        }
        return $escaped;
    }

    public function escape($str)
    {
        return $this->connection->escape($str);
    }

    public function __call($method, $args)
    {
        if (method_exists($this->connection, $method)) {
            return call_user_func_array([$this->connection, $method], $args);
        }
        if (method_exists($this->getBuilder(), $method)) {
            return call_user_func_array([$this->getBuilder(), $method], $args);
        }
        return null;
    }
}

class ResultBridge
{
    protected $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function result_array()
    {
        return method_exists($this->result, 'getResultArray') ? $this->result->getResultArray() : [];
    }

    public function result()
    {
        return method_exists($this->result, 'getResult') ? $this->result->getResult() : [];
    }

    public function row_array()
    {
        return method_exists($this->result, 'getRowArray') ? $this->result->getRowArray() : [];
    }

    public function row()
    {
        return method_exists($this->result, 'getRow') ? $this->result->getRow() : null;
    }

    public function num_rows()
    {
        return method_exists($this->result, 'getNumRows') ? $this->result->getNumRows() : 0;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->result, $method], $args);
    }
}

if (!class_exists('CI_Model')) {
    #[\AllowDynamicProperties]
    class CI_Model
    {
        public $db;
        public $load;
        public $session;
        public $customlib;

        public function __construct()
        {
            $bridge = new \App\Libraries\ViewBridge();
            $this->db        = new DatabaseBridge();
            $this->load      = $bridge->load;
            $this->customlib = $bridge->customlib;
            $this->session   = new SessionBridge();
        }

        public function __get($key)
        {
            $ci = &get_instance();
            // Fallback to CI instance property
            if ($ci !== null) {
                if (isset($ci->$key)) {
                    return $ci->$key;
                }
                $prop = $ci->$key;
                if ($prop !== null) {
                    return $prop;
                }
            }

            // Case-insensitive model check
            $parts = explode('_', $key);
            $ci4Name = 'App\\Models\\' . implode('', array_map('ucfirst', $parts));
            if (class_exists($ci4Name)) {
                $this->$key = new $ci4Name();
                return $this->$key;
            }

            if (class_exists($key)) {
                $this->$key = new $key();
                return $this->$key;
            }

            return null;
        }
    }
}

class SessionBridge
{
    protected $ci4Session;

    public function __construct()
    {
        $this->ci4Session = service('session');
    }

    public function userdata($item = null)
    {
        if ($item === null) {
            return $this->ci4Session->get();
        }
        return $this->ci4Session->get($item);
    }

    public function has_userdata($item): bool
    {
        return $this->ci4Session->has($item);
    }

    public function set_userdata($data, $value = null): void
    {
        if (is_array($data)) {
            $this->ci4Session->set($data);
        } else {
            $this->ci4Session->set($data, $value);
        }
    }

    public function unset_userdata($key): void
    {
        $this->ci4Session->remove($key);
    }

    public function flashdata($key = null)
    {
        return $this->ci4Session->getFlashdata($key);
    }

    public function set_flashdata($data, $value = null): void
    {
        $this->ci4Session->setFlashdata($data, $value);
    }

    public function keep_flashdata($key): void
    {
        $this->ci4Session->keepFlashdata($key);
    }

    public function sess_destroy(): void
    {
        $this->ci4Session->destroy();
    }

    public function __get($key)
    {
        if ($key === 'userdata') {
            return $this->userdata();
        }
        if ($key === 'session_id') {
            return session_id();
        }
        if ($this->ci4Session->has($key)) {
            return $this->ci4Session->get($key);
        }
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    public function __set($key, $value)
    {
        $this->ci4Session->set($key, $value);
        $_SESSION[$key] = $value;
    }

    public function __isset($key)
    {
        if ($key === 'userdata') {
            return true;
        }
        if ($key === 'session_id') {
            return session_status() === PHP_SESSION_ACTIVE;
        }
        return $this->ci4Session->has($key) || isset($_SESSION[$key]);
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->ci4Session, $method], $args);
    }
}

class ConfigBridge
{
    protected array $config = [];

    public function __construct()
    {
        $this->config['installed'] = true;
        $this->config['migration_enabled'] = false;
    }

    public function load($file = '', $use_sections = false, $fail_gracefully = false)
    {
        $file = str_replace('.php', '', $file);
        $paths = [
            APPPATH . '../application/config/' . $file . '.php',
            APPPATH . 'Config/' . $file . '.php',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                include $path;
                if (isset($config) && is_array($config)) {
                    $this->config = array_merge($this->config, $config);
                }
                return true;
            }
        }

        return $fail_gracefully;
    }

    public function item($item, $index = '')
    {
        if (array_key_exists($item, $this->config)) {
            return $this->config[$item];
        }
        return config('App')->$item ?? null;
    }

    public function set_item($item, $value)
    {
        $this->config[$item] = $value;
        return true;
    }

    public function base_url($uri = '', $protocol = null)
    {
        $base = rtrim(config('App')->baseURL ?? 'http://localhost/lms/', '/') . '/';
        return $base . ltrim($uri, '/');
    }

    public function site_url($uri = '', $protocol = null)
    {
        $base = rtrim(config('App')->baseURL ?? 'http://localhost/lms/', '/') . '/';
        $indexPage = config('App')->indexPage ?? 'index.php';
        $prefix = !empty($indexPage) ? $base . $indexPage . '/' : $base;
        return $prefix . ltrim($uri, '/');
    }

    public function slash_item($item)
    {
        $val = $this->item($item);
        return !empty($val) ? rtrim($val, '/') . '/' : '/';
    }
}

class AgentBridge
{
    protected $agent;

    public function __construct()
    {
        $this->agent = service('request')->getUserAgent();
    }

    public function is_browser(?string $key = null): bool
    {
        return $this->agent->isBrowser($key);
    }

    public function is_robot(?string $key = null): bool
    {
        return $this->agent->isRobot($key);
    }

    public function is_mobile(?string $key = null): bool
    {
        return $this->agent->isMobile($key);
    }

    public function browser(): string
    {
        return $this->agent->getBrowser();
    }

    public function version(): string
    {
        return $this->agent->getVersion();
    }

    public function robot(): string
    {
        return $this->agent->getRobot();
    }

    public function mobile(): string
    {
        return $this->agent->getMobile();
    }

    public function platform(): string
    {
        return $this->agent->getPlatform();
    }

    public function string(): string
    {
        return $this->agent->getAgentString();
    }

    public function is_referral(): bool
    {
        return $this->agent->isReferral();
    }

    public function referrer(): string
    {
        return $this->agent->getReferrer();
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->agent, $method], $args);
    }
}

class SecurityBridge
{
    public function get_csrf_token_name(): string
    {
        return csrf_token();
    }

    public function get_csrf_hash(): string
    {
        return csrf_hash();
    }

    public function xss_clean($str, $is_image = false)
    {
        return $str;
    }

    public function sanitize_filename($str, $relative_path = false)
    {
        return sanitize_filename($str);
    }
}

if (!class_exists('CI_Controller')) {
    #[\AllowDynamicProperties]
    class CI_Controller
    {
        private static $instance;
        public $load;
        public $session;
        public $customlib;
        public $rbac;
        public $lang;
        public $db;
        public $config;
        public $input;
        public $security;
        public $agent;
        public $user_agent;
        public $form_validation;
        public $router;
        public $uri;

        public function __construct()
        {
            self::$instance = &$this;
            $bridge = new \App\Libraries\ViewBridge();
            $this->load       = $bridge->load;
            $this->customlib  = $bridge->customlib;
            $this->rbac       = $bridge->rbac;
            $this->lang       = $bridge->lang;
            $this->session    = new SessionBridge();
            $this->config     = new ConfigBridge();
            $this->db         = new DatabaseBridge();
            $this->security   = new SecurityBridge();
            $agentBridge      = new AgentBridge();
            $this->agent      = $agentBridge;
            $this->user_agent = $agentBridge;
            $this->input      = new class {
                public function post($index = null, $xss_clean = false)
                {
                    $req = service('request');
                    return $index === null ? $req->getPost() : $req->getPost($index);
                }
                public function get($index = null, $xss_clean = false)
                {
                    $req = service('request');
                    return $index === null ? $req->getGet() : $req->getGet($index);
                }
                public function get_post($index = null)
                {
                    $req = service('request');
                    return $req->getPostGet($index);
                }
                public function is_ajax_request()
                {
                    return service('request')->isAJAX();
                }
                public function ip_address()
                {
                    return service('request')->getIPAddress();
                }
                public function server($index = null)
                {
                    $req = service('request');
                    return $index === null ? $req->getServer() : $req->getServer($index);
                }
                public function method($upper = false)
                {
                    $method = service('request')->getMethod();
                    return $upper ? strtoupper($method) : strtolower($method);
                }
                public function __call($method, $args)
                {
                    return call_user_func_array([service('request'), $method], $args);
                }
            };
            $this->router    = new class {
                public function fetch_class()
                {
                    return $_SERVER['CI4_LEGACY_CONTROLLER'] ?? service('router')->controllerName();
                }
                public function fetch_method()
                {
                    return $_SERVER['CI4_LEGACY_METHOD'] ?? service('router')->methodName();
                }
            };
            $this->uri       = new class {
                public function segment($n)
                {
                    $uri = service('request')->getUri();
                    return $uri->getSegment($n);
                }
                public function total_segments()
                {
                    $uri = service('request')->getUri();
                    return $uri->getTotalSegments();
                }
                public function segment_array()
                {
                    $uri = service('request')->getUri();
                    return $uri->getSegments();
                }
                public function rsegment($n)
                {
                    return $this->segment($n);
                }
                public function uri_string()
                {
                    $uri = service('request')->getUri();
                    return $uri->getPath();
                }
                public function __call($method, $args)
                {
                    return call_user_func_array([service('request')->getUri(), $method], $args);
                }
            };
            $this->form_validation = new class {
                protected $rules = [];
                protected $errors = [];
                public function set_rules($field, $label = '', $rules = '', $errors = [])
                {
                    $this->rules[$field] = ['label' => $label, 'rules' => $rules, 'errors' => $errors];
                    return $this;
                }
                public function has_rule($field)
                {
                    return isset($this->rules[$field]);
                }
                public function set_message($rule, $message = '')
                {
                    return $this;
                }
                public function run($group = '')
                {
                    // Check if request is POST
                    if (strtolower(service('request')->getMethod()) !== 'post') {
                        return false;
                    }
                    if (empty($this->rules)) {
                        return true;
                    }
                    $ci4Rules = [];
                    foreach ($this->rules as $field => $data) {
                        $ci4Rules[$field] = [
                            'label' => $data['label'],
                            'rules' => str_replace('|xss_clean', '', str_replace('xss_clean|', '', $data['rules'])),
                        ];
                    }
                    $validation = service('validation');
                    $validation->setRules($ci4Rules);
                    $req = service('request');
                    $data = $req->getPost();
                    $success = $validation->run($data);
                    if (!$success) {
                        $this->errors = $validation->getErrors();
                    }
                    return $success;
                }
                public function error($field = '', $prefix = '', $suffix = '')
                {
                    $err = $this->errors[$field] ?? '';
                    return !empty($err) ? $prefix . $err . $suffix : '';
                }
                public function error_string($prefix = '', $suffix = '')
                {
                    return implode("\n", array_map(fn($e) => $prefix . $e . $suffix, $this->errors));
                }
                public function error_array()
                {
                    return $this->errors;
                }
                public function set_value($field = '', $default = '')
                {
                    $post = service('request')->getPost($field);
                    return $post !== null ? $post : $default;
                }
                public function set_select($field = '', $value = '', $default = false)
                {
                    $post = service('request')->getPost($field);
                    if ($post !== null) {
                        return (string)$post === (string)$value ? ' selected="selected"' : '';
                    }
                    return $default ? ' selected="selected"' : '';
                }
                public function set_checkbox($field = '', $value = '', $default = false)
                {
                    $post = service('request')->getPost($field);
                    if ($post !== null) {
                        return (string)$post === (string)$value ? ' checked="checked"' : '';
                    }
                    return $default ? ' checked="checked"' : '';
                }
                public function set_radio($field = '', $value = '', $default = false)
                {
                    return $this->set_checkbox($field, $value, $default);
                }
                public function reset_validation()
                {
                    $this->rules = [];
                    $this->errors = [];
                }
            };
        }

        public static function &get_instance()
        {
            return self::$instance;
        }

        public function __get($key)
        {
            // Case-insensitive model check
            $parts = explode('_', $key);
            $ci4Name = 'App\\Models\\' . implode('', array_map('ucfirst', $parts));
            if (class_exists($ci4Name)) {
                $this->$key = new $ci4Name();
                return $this->$key;
            }

            if (class_exists($key)) {
                $this->$key = new $key();
                return $this->$key;
            }

            // Check if legacy model file exists
            $legacyFile = APPPATH . '../application/models/' . ucfirst($key) . '.php';
            if (!file_exists($legacyFile)) {
                $legacyFile = APPPATH . '../application/models/' . $key . '.php';
            }
            if (file_exists($legacyFile)) {
                require_once $legacyFile;
                $class = basename($legacyFile, '.php');
                if (class_exists($class)) {
                    $this->$key = new $class();
                    return $this->$key;
                }
            }

            return null;
        }
    }
}

if (!function_exists('get_instance')) {
    function &get_instance()
    {
        return CI_Controller::get_instance();
    }
}

// Load application core controllers and models now that CI_Controller is defined
$coreFiles = [
    APPPATH . '../application/core/MY_Controller.php',
    APPPATH . '../application/core/MY_Model.php',
    APPPATH . '../application/core/MY_Addon_AccountsController.php',
    APPPATH . '../application/core/MY_Addon_CBSEController.php',
    APPPATH . '../application/core/MY_Addon_MBController.php',
];
foreach ($coreFiles as $coreFile) {
    if (file_exists($coreFile)) {
        require_once $coreFile;
    }
}

if (!function_exists('access_denied')) {
    function access_denied(): void
    {
        header('Location: ' . base_url('admin/unauthorized'));
        exit;
    }
}

if (!function_exists('form_error')) {
    function form_error(string $field = '', string $prefix = '', string $suffix = ''): string
    {
        $validation = \Config\Services::validation();
        if ($validation->hasError($field)) {
            return $prefix . $validation->getError($field) . $suffix;
        }
        return '';
    }
}

if (!function_exists('set_value')) {
    function set_value(string $field = '', string $default = ''): string
    {
        $request = \Config\Services::request();
        $val = $request->getPost($field);
        if ($val !== null && $val !== '') {
            return is_array($val) ? '' : (string)$val;
        }
        return $default;
    }
}

if (!function_exists('set_checkbox')) {
    function set_checkbox(string $field = '', string $value = '', bool $default = false): string
    {
        $request = \Config\Services::request();
        $post = $request->getPost($field);

        if ($post !== null) {
            if (is_array($post)) {
                return in_array($value, $post) ? 'checked="checked"' : '';
            }
            return (string)$post === (string)$value ? 'checked="checked"' : '';
        }

        return $default ? 'checked="checked"' : '';
    }
}

if (!function_exists('set_radio')) {
    function set_radio(string $field = '', string $value = '', bool $default = false): string
    {
        return set_checkbox($field, $value, $default);
    }
}

if (!function_exists('empty2null')) {
    function empty2null($v)
    {
        return empty($v) ? null : $v;
    }
}

if (!function_exists('get_purpose_pill')) {
    function get_purpose_pill($purpose_name): string
    {
        if (empty($purpose_name)) {
            return '<span class="label label-default" style="border-radius:12px; padding:4px 10px; font-weight:600; font-size:11px;">-</span>';
        }
        $colors = [
            ['bg' => '#e0f2fe', 'color' => '#0369a1', 'border' => '#bae6fd'], // Sky Blue
            ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'border' => '#e9d5ff'], // Purple
            ['bg' => '#dcfce7', 'color' => '#15803d', 'border' => '#bbf7d0'], // Emerald Green
            ['bg' => '#fef3c7', 'color' => '#b45309', 'border' => '#fde68a'], // Amber
            ['bg' => '#ffe4e6', 'color' => '#be123c', 'border' => '#fecdd3'], // Rose
            ['bg' => '#ccfbf1', 'color' => '#0f766e', 'border' => '#99f6e4'], // Teal
            ['bg' => '#fae8ff', 'color' => '#86198f', 'border' => '#f5d0fe'], // Fuchsia
            ['bg' => '#e0e7ff', 'color' => '#3730a3', 'border' => '#c7d2fe'], // Indigo
            ['bg' => '#ffedd5', 'color' => '#c2410c', 'border' => '#fed7aa'], // Warm Orange
            ['bg' => '#f1f5f9', 'color' => '#334155', 'border' => '#cbd5e1']  // Slate
        ];
        $hash = abs(crc32((string)$purpose_name));
        $style = $colors[$hash % count($colors)];
        return '<span class="label" style="background-color:' . $style['bg'] . '; color:' . $style['color'] . '; border:1px solid ' . $style['border'] . '; padding:4px 10px; border-radius:12px; font-weight:600; font-size:11px; display:inline-block;">' . htmlspecialchars((string)$purpose_name) . '</span>';
    }
}

if (!function_exists('get_call_status_pill')) {
    function get_call_status_pill($status): string
    {
        if (empty($status)) {
            return '<span class="label label-default" style="border-radius:12px; padding:4px 10px; font-weight:600; font-size:11px;">-</span>';
        }
        $status_map = [
            'Connected'          => ['bg' => '#dcfce7', 'color' => '#15803d', 'border' => '#bbf7d0'], // Emerald Green
            'Not Answered'       => ['bg' => '#ffe4e6', 'color' => '#be123c', 'border' => '#fecdd3'], // Rose Red
            'Not connected'      => ['bg' => '#f1f5f9', 'color' => '#64748b', 'border' => '#cbd5e1'], // Slate Gray
            'Busy'               => ['bg' => '#fef3c7', 'color' => '#b45309', 'border' => '#fde68a'], // Amber
            'Switched Off'       => ['bg' => '#f1f5f9', 'color' => '#475569', 'border' => '#cbd5e1'], // Slate Gray
            'Wrong Number'       => ['bg' => '#ffedd5', 'color' => '#c2410c', 'border' => '#fed7aa'], // Warm Orange
            'Callback Requested' => ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'border' => '#e9d5ff'], // Purple
        ];
        if (isset($status_map[$status])) {
            $style = $status_map[$status];
        } else {
            $colors = [
                ['bg' => '#e0f2fe', 'color' => '#0369a1', 'border' => '#bae6fd'],
                ['bg' => '#ccfbf1', 'color' => '#0f766e', 'border' => '#99f6e4'],
                ['bg' => '#e0e7ff', 'color' => '#3730a3', 'border' => '#c7d2fe']
            ];
            $hash = abs(crc32((string)$status));
            $style = $colors[$hash % count($colors)];
        }
        return '<span class="label" style="background-color:' . $style['bg'] . '; color:' . $style['color'] . '; border:1px solid ' . $style['border'] . '; padding:4px 10px; border-radius:12px; font-weight:600; font-size:11px; display:inline-block;">' . htmlspecialchars((string)$status) . '</span>';
    }
}

if (!function_exists('fix_utf8_notes')) {
    function fix_utf8_notes($str)
    {
        if (empty($str)) {
            return $str;
        }
        if (strpos($str, 'Óñ') !== false || strpos($str, 'ÓÑ') !== false || strpos($str, 'Â') !== false) {
            $conv1 = @mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
            if (!empty($conv1)) {
                $conv2 = @mb_convert_encoding($conv1, 'ISO-8859-1', 'UTF-8');
                if (!empty($conv2) && preg_match('/[\x{0900}-\x{097F}]/u', $conv2)) {
                    return $conv2;
                }
                if (preg_match('/[\x{0900}-\x{097F}]/u', $conv1)) {
                    return $conv1;
                }
            }
            $map = [
                'Óñà' => 'अ', 'Óñá' => 'आ', 'Óñç' => 'इ', 'Óñè' => 'ई', 'Óñë' => 'उ', 'Óñê' => 'ऊ', 'Óñï' => 'ऋ', 'ÓñÅ' => 'ए', 'ÓñÉ' => 'ऐ', 'Óñæ' => 'ओ', 'Óñô' => 'औ',
                'Óñò' => 'क', 'Óñó' => 'ख', 'Óñô' => 'ग', 'Óñö' => 'घ', 'Óñû' => 'च', 'Óñü' => 'छ', 'Óñ£' => 'ज', 'Óñ¥' => 'झ', 'Óñ«' => 'म',
                'Óñî' => 'ठ', 'Óñí' => 'ड', 'Óññ' => 'त', 'Óñª' => 'द', 'Óñõ' => 'ध', 'Óñ¿' => 'न',
                'Óñ¬' => 'प', 'Óñ¼' => 'ब', 'Óñ¡' => 'भ', 'Óñ»' => 'य', 'Óñ░' => 'र', 'Óñ±' => 'ल', 'ÓñÁ' => 'व', 'Óñ¾' => 'श',
                'Óñ©' => 'स', 'Óñ╣' => 'ह', 'Óñ¥' => 'ा', 'Óñ┐' => 'ि', 'ÓÑÇ' => 'ी', 'ÓÑü' => 'ु', 'ÓÑé' => 'ू', 'ÓÑç' => 'े', 'ÓÑê' => 'ै',
                'ÓÑï' => 'ो', 'ÓÑì' => '्', 'ÓÑö' => 'ौ', 'ÓÑù' => 'गे'
            ];
            return strtr($str, $map);
        }
        return $str;
    }
}

if (!function_exists('redirect')) {
    /**
     * Backward-compatible redirect() function supporting both CI3 (URIs) and CI4 (named routes / RedirectResponse).
     */
    function redirect(?string $uri = null, $method = 'auto', ?int $code = null)
    {
        $response = service('redirectresponse');

        if (empty($uri)) {
            return $response;
        }

        // 1. If it's already an absolute URL (e.g. http:// or https://)
        if (preg_match('#^https?://#i', $uri)) {
            $targetUrl = $uri;
        } else {
            // 2. Try CI4 reverse routing first in case it's a named route
            try {
                $reverse = service('routes')->reverseRoute($uri);
                if ($reverse) {
                    $targetUrl = site_url($reverse);
                } else {
                    $targetUrl = site_url($uri);
                }
            } catch (\Throwable $e) {
                // Fallback to site_url for CI3 relative paths
                $targetUrl = site_url($uri);
            }
        }

        // If called directly inside a legacy CI3 controller or library without return,
        // perform immediate header redirect as CI3 expects
        if (PHP_SAPI !== 'cli' && !headers_sent()) {
            if ($code === null) {
                $code = (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1')
                    ? ($method !== 'auto' && $method !== 'location' ? 303 : 302)
                    : 302;
            }
            header('Location: ' . $targetUrl, true, $code);
            exit;
        }

        return $response->to($targetUrl, $code);
    }
}

