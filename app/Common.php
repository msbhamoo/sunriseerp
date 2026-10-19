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
