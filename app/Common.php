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
            $this->builder = $this->connection->table($this->currentTable ?: 'dual');
        }
        return $this->builder;
    }

    public function select($select = '*', $escape = null)
    {
        return $this->getBuilder()->select($select, $escape);
    }

    public function from($from)
    {
        $this->currentTable = $from;
        $this->builder = $this->connection->table($from);
        return $this->builder;
    }

    public function where($key, $value = null, $escape = null)
    {
        return $this->getBuilder()->where($key, $value, $escape);
    }

    public function or_where($key, $value = null, $escape = null)
    {
        return $this->getBuilder()->orWhere($key, $value, $escape);
    }

    public function where_in($key = null, $values = null, $escape = null)
    {
        return $this->getBuilder()->whereIn($key, $values, $escape);
    }

    public function join($table, $cond, $type = '', $escape = null)
    {
        return $this->getBuilder()->join($table, $cond, $type, $escape);
    }

    public function order_by($orderby, $direction = '', $escape = null)
    {
        return $this->getBuilder()->orderBy($orderby, $direction, $escape);
    }

    public function group_by($by, $escape = null)
    {
        return $this->getBuilder()->groupBy($by, $escape);
    }

    public function limit($value, $offset = 0)
    {
        return $this->getBuilder()->limit($value, $offset);
    }

    public function group_start()
    {
        return $this->getBuilder()->groupStart();
    }

    public function group_end()
    {
        return $this->getBuilder()->groupEnd();
    }

    public function get($table = '', $limit = null, $offset = null)
    {
        if (!empty($table)) {
            $this->from($table);
        }
        $builder = $this->getBuilder();
        $result = $builder->get($limit, $offset);
        $this->builder = null; // reset for next query
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

    public function __call($method, $args)
    {
        if (method_exists($this->connection, $method)) {
            return call_user_func_array([$this->connection, $method], $args);
        }
        return call_user_func_array([$this->getBuilder(), $method], $args);
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
            if (isset($ci->$key)) {
                return $ci->$key;
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

    public function sess_destroy(): void
    {
        $this->ci4Session->destroy();
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->ci4Session, $method], $args);
    }
}

class ConfigBridge
{
    public function load($file = '', $use_sections = false, $fail_gracefully = false)
    {
        return true;
    }

    public function item($item, $index = '')
    {
        return config('App')->$item ?? null;
    }

    public function set_item($item, $value)
    {
        return true;
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
        public $form_validation;

        public function __construct()
        {
            self::$instance = &$this;
            $bridge = new \App\Libraries\ViewBridge();
            $this->load      = $bridge->load;
            $this->customlib = $bridge->customlib;
            $this->rbac      = $bridge->rbac;
            $this->lang      = $bridge->lang;
            $this->session   = new SessionBridge();
            $this->config    = new ConfigBridge();
            $this->db        = \Config\Database::connect();
            $this->input     = service('request');
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

