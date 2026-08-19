<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Render a select2 quick-add dropdown for the Material Register.
 * The current value is always injected as an option so validation
 * re-display and edit prefill work even for freshly added items.
 */
if (!function_exists('material_select')) {
    function material_select($name, $type, $options, $current, $placeholder = '')
    {
        $current = (string) $current;
        $opts    = is_array($options) ? $options : array();
        if ($current !== '' && !in_array($current, $opts, true)) {
            $opts[] = $current;
        }
        $html = '<select class="form-control material-select2" data-type="' . html_escape($type)
            . '" data-placeholder="' . html_escape($placeholder) . '" name="' . html_escape($name) . '">';
        $html .= '<option value=""></option>';
        foreach ($opts as $o) {
            $sel = ($current !== '' && (string) $o === $current) ? ' selected' : '';
            $html .= '<option value="' . html_escape($o) . '"' . $sel . '>' . html_escape($o) . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
}

if (!function_exists('material_select_array')) {
    function material_select_array($name, $type, $options, $current, $placeholder = '', $extra_class = '')
    {
        $current = (string) $current;
        $opts    = is_array($options) ? $options : array();
        if ($current !== '' && !in_array($current, $opts, true)) {
            $opts[] = $current;
        }
        $html = '<select class="form-control material-select2 ' . html_escape($extra_class) . '" data-type="' . html_escape($type)
            . '" data-placeholder="' . html_escape($placeholder) . '" name="' . html_escape($name) . '">';
        $html .= '<option value=""></option>';
        foreach ($opts as $o) {
            $sel = ($current !== '' && (string) $o === $current) ? ' selected' : '';
            $html .= '<option value="' . html_escape($o) . '"' . $sel . '>' . html_escape($o) . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
}
