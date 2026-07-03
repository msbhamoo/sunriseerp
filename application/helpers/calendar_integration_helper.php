<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('is_working_day')) {
    /**
     * Check if a given date is a working day, based on global calendar settings and annual_calendar exceptions.
     * 
     * @param string $date Date in Y-m-d format
     * @param string $module Optional module context (e.g. 'attendance', 'payroll')
     * @param array $settings Cached settings array (optional)
     * @param array $exceptions Cached exceptions array for a date range (optional)
     * @return bool True if working day, False if holiday/weekend
     */
    function is_working_day($date, $module = null, $settings = null, $exceptions = null)
    {
        $CI =& get_instance();
        
        if ($settings === null) {
            $CI->load->model('setting_model');
            $session_id = $CI->setting_model->getCurrentSession();
            $CI->db->where('session_id', $session_id);
            $settings = $CI->db->get('calendar_settings')->row_array();

            if (!$settings) {
                $settings = [
                    'default_working_days' => '1,2,3,4,5', 
                    'working_saturdays' => '1,3,5'
                ];
            }
        }

        $default_working = explode(',', $settings['default_working_days']);
        $working_saturdays = explode(',', $settings['working_saturdays']);

        $timestamp = strtotime($date);
        $day_of_week = date('N', $timestamp); // 1 (Mon) to 7 (Sun)
        $is_weekend = !in_array($day_of_week, $default_working);

        if ($day_of_week == 6 && $is_weekend) {
            $week_of_month = ceil(date('j', $timestamp) / 7);
            if (in_array($week_of_month, $working_saturdays)) {
                $is_weekend = false;
            }
        }

        if ($exceptions === null) {
            $CI->load->model('setting_model');
            $session_id = $CI->setting_model->getCurrentSession();
            $CI->db->where('from_date <=', $date);
            $CI->db->where('to_date >=', $date);
            $CI->db->where('session_id', $session_id);
            $exceptions = $CI->db->get('annual_calendar')->result_array();
        }

        $is_working = !$is_weekend;

        foreach ($exceptions as $exception) {
            // Check if this specific exception covers the date
            $ex_start = strtotime(date('Y-m-d', strtotime($exception['from_date'])));
            $ex_end = strtotime(date('Y-m-d', strtotime($exception['to_date'])));
            
            if ($timestamp >= $ex_start && $timestamp <= $ex_end) {
                if ($module != null && !empty($exception['module_impact'])) {
                    $impact = json_decode($exception['module_impact'], true);
                    if (is_array($impact) && !in_array($module, $impact)) {
                        continue; 
                    }
                }

                if (isset($exception['is_working_day']) && $exception['is_working_day'] == 1) {
                    $is_working = true;
                } else {
                    $is_working = false; 
                }
            }
        }

        return $is_working;
    }
}

if (!function_exists('get_working_days_between')) {
    /**
     * Get exact number of working days between two dates efficiently.
     */
    function get_working_days_between($start_date, $end_date, $module = null)
    {
        $CI =& get_instance();
        $CI->load->model('setting_model');
        $session_id = $CI->setting_model->getCurrentSession();

        // Fetch settings once
        $CI->db->where('session_id', $session_id);
        $settings = $CI->db->get('calendar_settings')->row_array();
        if (!$settings) {
            $settings = [
                'default_working_days' => '1,2,3,4,5',
                'working_saturdays' => '1,3,5'
            ];
        }

        // Fetch all exceptions for the range once
        $CI->db->where('from_date <=', $end_date . ' 23:59:59');
        $CI->db->where('to_date >=', $start_date . ' 00:00:00');
        $CI->db->where('session_id', $session_id);
        $exceptions = $CI->db->get('annual_calendar')->result_array();

        $start = strtotime($start_date);
        $end = strtotime($end_date);
        
        $working_days = 0;
        
        for ($i = $start; $i <= $end; $i += 86400) {
            $current_date = date('Y-m-d', $i);
            if (is_working_day($current_date, $module, $settings, $exceptions)) {
                $working_days++;
            }
        }
        
        return $working_days;
    }
}

?>
