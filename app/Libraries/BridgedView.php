<?php

namespace App\Libraries;

use CodeIgniter\View\View;

/**
 * BridgedView
 *
 * Custom CI4 View renderer that seamlessly responds to legacy view calls
 * like `$this->customlib->...`, `$this->load->view(...)`, `$this->rbac->...`.
 */
class BridgedView extends View
{
    protected ?ViewBridge $bridge = null;

    protected function getBridge(): ViewBridge
    {
        if ($this->bridge === null) {
            $this->bridge = new ViewBridge();
        }
        return $this->bridge;
    }

    public function __get($key)
    {
        $bridge = $this->getBridge();

        switch ($key) {
            case 'customlib':
                return $bridge->customlib;
            case 'load':
                return $bridge->load;
            case 'rbac':
                return $bridge->rbac;
            case 'lang':
                return $bridge->lang;
            case 'setting_model':
                return $bridge->setting_model;
            case 'session_model':
                return new \App\Models\SessionModel();
            case 'class_model':
                return new \App\Models\ClassModel();
            case 'section_model':
                return new \App\Models\SectionModel();
            case 'attendencetype_model':
                return new \App\Models\AttendencetypeModel();
            case 'stuattendence_model':
                return new \App\Models\StuattendenceModel();
            case 'feegroup_model':
                return new \App\Models\FeegroupModel();
            case 'feetype_model':
                return new \App\Models\FeetypeModel();
            case 'feesessiongroup_model':
                return new \App\Models\FeesessiongroupModel();
            case 'studentcall_model':
                return new \App\Models\StudentcallModel();
            case 'staff_model':
                return new \App\Models\StaffModel();
            case 'notification_model':
                return new \App\Models\NotificationModel();
            case 'student_model':
                return new \App\Models\StudentModel();
            case 'studentfeemaster_model':
                return new \App\Models\StudentfeemasterModel();
            case 'session':
                return new \SessionBridge();
            case 'security':
                return new \SecurityBridge();
            case 'router':
                return service('router');
            default:
                $ci = &get_instance();
                if ($ci !== null && isset($ci->$key)) {
                    return $ci->$key;
                }
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }
                // Try case-insensitive matching in CI instance
                if ($ci !== null) {
                    $lower = strtolower($key);
                    foreach (get_object_vars($ci) as $prop => $val) {
                        if (strtolower($prop) === $lower) {
                            return $val;
                        }
                    }
                }
                return null;
        }
    }
}
