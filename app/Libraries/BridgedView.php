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
            case 'session':
                return service('session');
            case 'router':
                return service('router');
            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }
                return null;
        }
    }
}
