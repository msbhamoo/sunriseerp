<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getMysqlVersion() {
        $mysqlVersion = $this->db->query('SELECT VERSION() as version')->row();
        return $mysqlVersion;
    }

    public function getSqlMode() {

        $sqlMode = $this->db->query('SELECT @@sql_mode as mode')->row();
        return $sqlMode;
    }
 
    public function get($id = null) {

        $this->db->select('sch_settings.*,languages.language,languages.short_code as `language_code`,sessions.session,languages.is_rtl,currencies.symbol as currency_symbol,currencies.base_price,currencies.short_name as currency,currencies.id as currency_id');
     
        $this->db->from('sch_settings');
        $this->db->join('sessions', 'sessions.id = sch_settings.session_id');
        $this->db->join('languages', 'languages.id = sch_settings.lang_id');
        $this->db->join('currencies', 'currencies.id = sch_settings.currency');
        if ($id != null) {
            $this->db->where('sch_settings.id', $id);
        } else {
            $this->db->order_by('sch_settings.id');
        }
        $query = $this->db->get();

        if ($id != null) {
            return $query->row_array();
        } else {
            $session_array = $this->session->has_userdata('session_array');
            $result = $query->result_array();

            $result[0]['current_session'] = array(
                'session_id' => $result[0]['session_id'],
                'session' => $result[0]['session']
            );

            if ($session_array) {
                $session_array = $this->session->userdata('session_array');
                $result[0]['session_id'] = $session_array['session_id'];
                $result[0]['session'] = $session_array['session'];
            }

            return $result;
        }
    }

    public function get_studentlang($id) {
        $data = $this->db->select('users.lang_id')->from('users')->where('user_id', $id)->get()->row_array();
        return $data;
    } 
    
    public function get_guestlang($id) {
        $data = $this->db->select('guest.lang_id')->from('guest')->where('id', $id)->get()->row_array();
        return $data;
    }

    public function get_parentlang($id) {
        $data = $this->db->select('users.lang_id')->from('users')->where('id', $id)->get()->row_array();
        return $data;
    }

    public function get_stafflang($id) {
        $data = $this->db->select('staff.lang_id')->from('staff')->where('id', $id)->get()->row_array();
        return $data;
    }

    public function getSchoolDetail($id = null) {

        $this->db->select('sch_settings.*,languages.language,sessions.session,currencies.symbol as currency_symbol,currencies.base_price,currencies.short_name as currency');
        $this->db->from('sch_settings');
        $this->db->join('sessions', 'sessions.id = sch_settings.session_id');
        $this->db->join('languages', 'languages.id = sch_settings.lang_id');
        $this->db->join('currencies', 'currencies.id = sch_settings.currency');
        $this->db->order_by('sch_settings.id');
        $query = $this->db->get();
        return $query->row();
    }

    public function getSetting() {  

        $this->db->select('sch_settings.*,languages.language,sessions.session,languages.short_code as `language_code`,sch_settings.languages as activelanguage,currencies.symbol as currency_symbol,currencies.base_price,currencies.short_name as currency,currencies.id as currency_id');

        $this->db->from('sch_settings');
        $this->db->join('sessions', 'sessions.id = sch_settings.session_id');
        $this->db->join('languages', 'languages.id = sch_settings.lang_id');
		$this->db->join('currencies', 'currencies.id = sch_settings.currency');
        $this->db->order_by('sch_settings.id');
        $query = $this->db->get();    
        
        $result = $query->row();
        
        $json_languages = json_decode($result->activelanguage);        
        foreach ($json_languages as $key => $value) {       
       
            $langresult  =        $this->language_model->get($value);							
            $language[$key] =  $langresult;
           					
		}
        
        $result->activelanguage2    =   $language;        
        return 	$result;
    }

    public function remove($id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('sch_settings');
        $message = DELETE_RECORD_CONSTANT . " On settings id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function add($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('sch_settings', $data);
            $message = UPDATE_RECORD_CONSTANT . " On settings id " . $data['id'];
            $action = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('sch_settings', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On settings id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);             
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }
 
    public function getCurrentSession() {
        $session_result = $this->get();
        return $session_result[0]['session_id'];
    }

    public function getOnlineAdmissionStatus() {
        $setting_result = $this->get();
        if ($setting_result[0]['online_admission']) {
            return true;
        }
        return false;
    }

    public function getexamresultstatus() {
        $setting_result = $this->get();
        if ($setting_result[0]['exam_result']) {
            return true;
        }
        return false;
    }

    public function getCurrentSessionName() {
        $session_result = $this->get();
        return $session_result[0]['session'];
    }

    public function getCurrentSchoolName() {
        $session_result = $this->get();
        return $session_result[0]['name'];
    }

    public function getStartMonth() {
        $session_result = $this->get();
        return $session_result[0]['start_month'];
    }

    public function getCurrentSessiondata() {
        $session_result = $this->get();
        return $session_result[0];
    }

    public function getCurrency() {
        $session_result = $this->get();
        return $session_result[0]['currency'];
    }

    public function getCurrencySymbol() {
        $session_result = $this->get();
        return $session_result[0]['currency_symbol'];
    }

    public function getDateYmd() {
        return date('Y-m-d');
    }

    public function getDateDmy() {
        return date('d-m-Y');
    }

    public function add_cronsecretkey($data, $id) {
        $this->db->where("id", $id)->update("sch_settings", $data);
    }

    public function getLanguage() {
        $query = $this->db->select('languages.language,languages.short_code')->where('id', $this->session->userdata['admin']['language']['lang_id'])->get('languages');
        return $query->row_array();
    }
    
    public function getuserLanguage() {
        $query = $this->db->select('languages.language,languages.short_code')->where('id', $this->session->userdata['student']['language']['lang_id'])->get('languages');
        return $query->row_array();
    }

    public function getAdminlogo() {
        $query = $this->db->select('admin_logo')->get('sch_settings');
        $logo = $query->row_array();
        return $logo['admin_logo'];
    }

    public function getAdminsmalllogo() {
        $query = $this->db->select('admin_small_logo')->get('sch_settings');
        $logo = $query->row_array();
        return  $logo['admin_small_logo'];
    }

    public function get_appname() {
        $query = $this->db->select('name')->get('sch_settings');
        $name = $query->row_array();
        echo $name['name'];
    }

    public function check_haederimage($type) {
        $check = $this->db->select('*')->from('print_headerfooter')->where('print_type', $type)->get()->row_array();
        if (empty($check['header_image'])) {
            return 0;
        } else {
            return 1;
        }
    }

    public function add_printheader($data) {
        $this->db->where('print_type', $data['print_type']);
        $this->db->update('print_headerfooter', $data);       
    }

    public function get_printheader() {
        return $this->db->select('*')->from('print_headerfooter')->get()->result_array();
    }

    public function get_receiptheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'student_receipt')->get()->row_array();
        return $image['header_image'];
    }

    public function unlink_receiptheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'student_receipt')->get()->row_array();
        return $image['header_image'];
    }

    public function get_receiptfooter() {
        $image = $this->db->select('footer_content')->from('print_headerfooter')->where('print_type', 'student_receipt')->get()->row_array();
        return $image['footer_content'];
    }

    public function get_payslipheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'staff_payslip')->get()->row_array();
        return $image['header_image'];
    }

    public function unlink_payslipheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'staff_payslip')->get()->row_array();
        return $image['header_image'];
    }

    public function get_payslipfooter() {
        $image = $this->db->select('footer_content')->from('print_headerfooter')->where('print_type', 'staff_payslip')->get()->row_array();
        return $image['footer_content'];
    }

    public function unlink_onlinereceiptheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'online_admission_receipt')->get()->row_array();
        return $image['header_image'];
    }

    public function get_onlineadmissionheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'online_admission_receipt')->get()->row_array();
        echo $image['header_image'];
    }

    public function get_onlineadmissionfooter() {
        $image = $this->db->select('footer_content')->from('print_headerfooter')->where('print_type', 'online_admission_receipt')->get()->row_array();
        echo $image['footer_content'];
    }
    
    public function get_onlineexamheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'online_exam')->get()->row_array();
        echo $image['header_image'];
    }
    
    public function get_onlineexamfooter() {
        $query = $this->db->select('footer_content,header_image')->from('print_headerfooter')->where('print_type', 'online_exam')->get()->row_array();        
        return $query ;
    }

    public function get_general_purpose_header() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'general_purpose')->get()->row_array();
        return $image['header_image'];
    }

    public function get_general_purpose_footer() {
        $image = $this->db->select('footer_content')->from('print_headerfooter')->where('print_type', 'general_purpose')->get()->row_array();
        return $image['footer_content'];
    }

    public function get_email_header() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'email')->get()->row_array();
        return $image['header_image'];
    }

    public function get_email_footer() {
        $image = $this->db->select('footer_content')->from('print_headerfooter')->where('print_type', 'email')->get()->row_array();
        return $image['footer_content'];
    }

}
