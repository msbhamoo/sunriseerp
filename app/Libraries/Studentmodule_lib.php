<?php

class Studentmodule_lib
{

    private $allModules = array();
    private $modules    = array();
    public $student     = array();
    public $CI;

    public function __construct()
    {
        $this->CI      = &get_instance();
        $this->modules = array();
        self::loadModule(); //Initiate the userroles
        $this->CI->load->library('session');
    }

    public function loadModule()
    {
        if ($this->CI->session->has_userdata('student')) {
            $this->student = $this->CI->session->userdata('student');

            if (!empty($this->student) && is_array($this->student) && !empty($this->student['role'])) {
                $role_name        = $this->student['role'];
                $this->allModules = $this->CI->Module_model->get_userpermission($role_name);
                if (!empty($this->allModules)) {
                    foreach ($this->allModules as $mod_key => $mod_value) {
                        if (array_key_exists($role_name, (array)$mod_value)) {
                            if ($mod_value->{$role_name} == 1) {
                                $this->modules[$mod_value->short_code] = true;
                            } else {
                                $this->modules[$mod_value->short_code] = false;
                            }
                        } else {
                            $this->modules[$mod_value->short_code] = false;
                        }
                    }
                }
            }
        }
    }

    public function hasActive($module = null)
    {
        if (!empty($this->modules[$module])) {
            return true;
        }

        return false;
    }

}
