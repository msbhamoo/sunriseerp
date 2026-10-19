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
            $this->session   = service('session');
            $this->input     = service('request');
        }

        public static function &get_instance()
        {
            return self::$instance;
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

