<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Holiday extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("holiday_model");
		$this->sch_setting_detail  = $this->setting_model->getSetting();
    }

    public function index()
    {  	
		if (!$this->rbac->hasPrivilege('annual_calendar', 'can_view')) {
            access_denied();
        }
		
        $data['title']       	        =	$this->lang->line('select_criteria');
        $data["search_holiday_type"]	=	"";

        if (isset($_POST['search_holiday_type']) && $_POST['search_holiday_type'] != '') {
            $search_holiday_type            =   $_POST['search_holiday_type'];
			$data["search_holiday_type"]	=	$_POST['search_holiday_type'];
        }         
        $this->form_validation->set_rules('search_holiday_type', $this->lang->line('type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) { 
            $holidaylist   =   $this->holiday_model->get(null,null);
        } else {
            $holidaylist   =   $this->holiday_model->get(null, $search_holiday_type);
        }

        $data["holidaylist"]  	         = $holidaylist; 
		$data['superadmin_restriction']  = $this->sch_setting_detail->superadmin_restriction;
		$getStaffRole                     = $this->customlib->getStaffRole();
        $data['staffrole']                = json_decode($getStaffRole);
		$data['login_staff_id']           = $this->customlib->getStaffID();
        $data['holiday_type']             = $this->holiday_model->get_holiday_type();

        // Get Calendar Settings
        $session_id = $this->setting_model->getCurrentSession();
        $this->db->where('session_id', $session_id);
        $settings = $this->db->get('calendar_settings')->row_array();
        if (!$settings) {
            $settings = [
                'calendar_start_date' => date('Y-04-01'),
                'calendar_end_date' => date('Y-03-31', strtotime('+1 year')),
                'default_working_days' => '1,2,3,4,5',
                'working_saturdays' => '1,3,5'
            ];
        }
        $data['calendar_settings'] = $settings;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/holiday/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add()
    {  		 
        $holiday_type=$this->input->post('holiday_type');       
		
		$this->form_validation->set_rules('from_date', $this->lang->line('from_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('to_date', $this->lang->line('to_date'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('holiday_type', $this->lang->line('type'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required|xss_clean');     
        if ($this->form_validation->run() == false) {            
            $msg = array(
                'holiday_type'   =>     form_error('holiday_type'),
                'from_date'      =>     form_error('from_date'),
                'to_date'        =>     form_error('to_date'),
                'description'    =>     form_error('description')            
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            // IF THERE IS SINGLE DATE THEN IT WILL BE SAME FOR BOTH COLUMNS - FROM_DATE AND TO_DATE //
            $from_date   =    $this->input->post('from_date');
            $to_date     =    $this->input->post('to_date');              

			if($this->input->post('front_site')){
				$front_site	=	1;
			}else{
				$front_site	=	0;
			}
            
            if($this->input->post('is_working_day')){
                $is_working_day = 1;
            }else{
                $is_working_day = 0;
            }

            $data = array(
                'id'                =>     $this->input->post('id'),
                'holiday_type'      =>     $this->input->post('holiday_type'),
                'from_date'         =>     date('Y-m-d', $this->customlib->datetostrtotime($from_date)),
                'to_date'           =>     date('Y-m-d 23:59:00', $this->customlib->datetostrtotime($to_date)),
                'description'       =>     $this->input->post('description'),
                'front_site'        =>     $front_site,
                'is_working_day'    =>     $is_working_day,
                'created_by'        =>     $this->customlib->getStaffID(),
                'holiday_color'     =>     '#008000',              
                'session_id'    	=>     $this->setting_model->getCurrentSession()                
            );

            $edit_id= $this->input->post('id');
            if($edit_id>0){
                $data['updated_at']      =   date('Y-m-d') ;   
            }else{
                $data['created_at']      =   date('Y-m-d H:i:s') ;  
            }

            $this->holiday_model->add($data);      
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
    }     
        echo json_encode($array);
    }


    public function delete_holiday()
    {
		if (!$this->rbac->hasPrivilege('annual_calendar', 'can_delete')) {
            access_denied();
        }
		
        $this->holiday_model->delete_holiday($_POST['id']);
        $array = array('status' => 1, 'success' => $this->lang->line('delete_message'));
        echo json_encode($array);
    }
	
	public function getholiday()
    {
        $id                  = $this->input->post("id");        
        $result              = $this->holiday_model->get($id);
		$result['from_date'] = date($this->customlib->getSchoolDateFormat(),strtotime($result['from_date']));
		$result['to_date']   = date($this->customlib->getSchoolDateFormat(),strtotime($result['to_date']));
        $json_array          = array('status' => '1', 'error' => '', 'result' => $result);
        echo json_encode($json_array);
    }

    public function holidaytype()
    {
        $data["title"]        = $this->lang->line("add_holiday_type");
        $holiday_type         = $this->holiday_model->get_holiday_type();
        $data["holiday_type"] = $holiday_type;
		$data['can_add_edit'] = 'can_add';
        $this->load->view("layout/header");
        $this->load->view("admin/holiday/holidaytype", $data);
        $this->load->view("layout/footer");
    }

    public function add_holiday_type()
    {
        $this->form_validation->set_rules('type', $this->lang->line('type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules(
            'type', $this->lang->line('name'), array('required',array('check_exists', array($this->holiday_model, 'valid_holiday_type')),
            )
        );	
		$data['can_add_edit'] = 'can_add';
        $id = $this->input->post("id");
        if ($this->form_validation->run()) {
            $type = $this->input->post("type");
            if (!empty($id)) {
                $data = array('type' => $type,'id' => $id);
            }else {
                $data = array('type' => $type);
            }
            $insert_id = $this->holiday_model->add_holiday_type($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect("admin/holiday/holidaytype");
        } else {
            $data["title"]        = $this->lang->line("add_holiday_type");
            $holiday_type         = $this->holiday_model->get_holiday_type();
            $data["holiday_type"] = $holiday_type;
            $this->load->view("layout/header");
            $this->load->view("admin/holiday/holidaytype", $data);
            $this->load->view("layout/footer");
        }
    }

    public function editholidaytype($id)
    {
        $data["title"]          = $this->lang->line("edit_holiday_type");
        $result                 = $this->holiday_model->get_holiday_type($id);
        $data["result"]         = $result;
        $holiday_type           = $this->holiday_model->get_holiday_type();
        $data["holiday_type"]   = $holiday_type;
		
		$data['can_add_edit'] = 'can_edit';
		
        $this->load->view("layout/header");
        $this->load->view("admin/holiday/holidaytype", $data);
        $this->load->view("layout/footer");
    }

    public function delete_holiday_type($id)
    {
        $this->holiday_model->delete_holiday_type($id);
        redirect('admin/holiday/holidaytype');
    }
    public function get_calendar_events()
    {
        $session_id = $this->setting_model->getCurrentSession();
        $this->db->where('session_id', $session_id);
        $events = $this->db->get('annual_calendar')->result_array();
        
        $calendar_events = [];
        foreach ($events as $event) {
            $title = $event['description'] ?: 'Event';
            if ($event['is_working_day'] == 1) {
                $color = '#f39c12'; // Warning color for working days override
                $title = "[Working Day] " . $title;
            } else {
                $color = $event['holiday_color'] ?: '#d9534f'; // Danger color for holidays
                $title = "[Holiday] " . $title;
            }
            
            $calendar_events[] = [
                'id' => $event['id'],
                'title' => $title,
                'start' => $event['from_date'],
                'end' => date('Y-m-d', strtotime($event['to_date'] . ' +1 day')), // FullCalendar exclusive end date
                'color' => $color,
                'allDay' => true
            ];
        }
        
        echo json_encode($calendar_events);
    }
    
    public function save_calendar_settings()
    {
        if (!$this->rbac->hasPrivilege('annual_calendar', 'can_edit')) {
            access_denied();
        }
        
        $session_id = $this->setting_model->getCurrentSession();
        
        $data = [
            'session_id' => $session_id,
            'calendar_start_date' => $this->input->post('calendar_start_date'),
            'calendar_end_date' => $this->input->post('calendar_end_date'),
            'default_working_days' => $this->input->post('default_working_days') ? implode(',', $this->input->post('default_working_days')) : '',
            'working_saturdays' => $this->input->post('working_saturdays') ? implode(',', $this->input->post('working_saturdays')) : ''
        ];
        
        $this->db->where('session_id', $session_id);
        $q = $this->db->get('calendar_settings');
        if ($q->num_rows() > 0) {
            $this->db->where('id', $q->row()->id);
            $this->db->update('calendar_settings', $data);
        } else {
            $this->db->insert('calendar_settings', $data);
        }
        
        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
        redirect('admin/holiday/index');
    }
    
    public function get_yearly_summary()
    {
        $this->load->helper('calendar_integration');
        $session_id = $this->setting_model->getCurrentSession();
        
        $this->db->where('session_id', $session_id);
        $settings = $this->db->get('calendar_settings')->row_array();
        
        if (!$settings || empty($settings['calendar_start_date']) || empty($settings['calendar_end_date'])) {
            echo "<div class='alert alert-info'>Please configure the Global Settings first (Calendar Start and End Dates).</div>";
            return;
        }
        
        $start_date = $settings['calendar_start_date'];
        $end_date = $settings['calendar_end_date'];
        
        $total_days = round((strtotime($end_date) - strtotime($start_date)) / 86400) + 1;
        $working_days = get_working_days_between($start_date, $end_date);
        $holidays = $total_days - $working_days;
        
        // Count events in the period
        $this->db->where('start_date >=', $start_date);
        $this->db->where('end_date <=', $end_date);
        $events_count = $this->db->get('events')->num_rows();
        
        $html = '<div class="row">';
        
        // Working Days Box
        $html .= '<div class="col-md-4 col-sm-6 col-xs-12">';
        $html .= '<div class="info-box">';
        $html .= '<span class="info-box-icon bg-green"><i class="fa fa-briefcase"></i></span>';
        $html .= '<div class="info-box-content">';
        $html .= '<span class="info-box-text">Total Working Days</span>';
        $html .= '<span class="info-box-number">'.$working_days.'</span>';
        $html .= '</div></div></div>';
        
        // Holidays Box
        $html .= '<div class="col-md-4 col-sm-6 col-xs-12">';
        $html .= '<div class="info-box">';
        $html .= '<span class="info-box-icon bg-red"><i class="fa fa-calendar-times-o"></i></span>';
        $html .= '<div class="info-box-content">';
        $html .= '<span class="info-box-text">Total Holidays & Weekends</span>';
        $html .= '<span class="info-box-number">'.$holidays.'</span>';
        $html .= '</div></div></div>';
        
        // Events Box
        $html .= '<div class="col-md-4 col-sm-6 col-xs-12">';
        $html .= '<div class="info-box">';
        $html .= '<span class="info-box-icon bg-aqua"><i class="fa fa-flag"></i></span>';
        $html .= '<div class="info-box-content">';
        $html .= '<span class="info-box-text">School Events</span>';
        $html .= '<span class="info-box-number">'.$events_count.'</span>';
        $html .= '</div></div></div>';
        
        $html .= '</div>'; // end row
        
        $html .= '<div class="alert alert-info">Note: The totals are calculated based on the session start date ('.date('M d, Y', strtotime($start_date)).') to end date ('.date('M d, Y', strtotime($end_date)).'), factoring in global defaults and overrides.</div>';
        
        echo $html;
    }
    
    public function get_dashboard_widget()
    {
        $this->load->helper('calendar_integration');
        $session_id = $this->setting_model->getCurrentSession();
        
        $this->db->where('session_id', $session_id);
        $settings = $this->db->get('calendar_settings')->row_array();
        
        if (!$settings || empty($settings['calendar_start_date']) || empty($settings['calendar_end_date'])) {
            echo ""; return; // Fail silently on dashboard if not configured
        }
        
        $start_date = $settings['calendar_start_date'];
        $end_date = $settings['calendar_end_date'];
        
        // YEARLY
        $total_days = round((strtotime($end_date) - strtotime($start_date)) / 86400) + 1;
        $working_days = get_working_days_between($start_date, $end_date);
        $holidays = $total_days - $working_days;
        
        // MONTHLY (Current Month)
        $m_start_date = date('Y-m-01');
        $m_end_date = date('Y-m-t');
        
        // Ensure month bounds don't exceed yearly bounds
        if (strtotime($m_start_date) < strtotime($start_date)) $m_start_date = $start_date;
        if (strtotime($m_end_date) > strtotime($end_date)) $m_end_date = $end_date;
        
        $m_total_days = round((strtotime($m_end_date) - strtotime($m_start_date)) / 86400) + 1;
        $m_working_days = get_working_days_between($m_start_date, $m_end_date);
        $m_holidays = $m_total_days - $m_working_days;
        
        $html = '<div class="col-md-12" style="margin-bottom: 24px;">';
        $html .= '<h3 style="margin-top: 0; font-size: 16px; color: #444; font-weight: 700; margin-bottom: 15px; text-transform: uppercase;">Academic Calendar <span style="font-size: 12px; font-weight: 600; color: #888;">(Session: '.date('d M Y', strtotime($start_date)).' to '.date('d M Y', strtotime($end_date)).')</span></h3>';
        
        $html .= '<div class="d2-metric-grid">';
        
        // Yearly working days box (Orange style)
        $html .= '<div class="d2-metric-box">';
        $html .= '<div class="d2-metric-icon students"><i class="fa fa-briefcase"></i></div>';
        $html .= '<div class="d2-metric-content">';
        $html .= '<div class="d2-metric-label">Yearly Working Days</div>';
        $html .= '<div class="d2-metric-value">'.$working_days.'</div>';
        $html .= '<div class="d2-metric-sub" style="color: #f97316;">Out of '.$total_days.' days</div>';
        $html .= '</div></div>';
        
        // Yearly Holidays Box (Purple style)
        $html .= '<div class="d2-metric-box">';
        $html .= '<div class="d2-metric-icon staff"><i class="fa fa-calendar-times-o"></i></div>';
        $html .= '<div class="d2-metric-content">';
        $html .= '<div class="d2-metric-label">Yearly Holidays</div>';
        $html .= '<div class="d2-metric-value">'.$holidays.'</div>';
        $html .= '<div class="d2-metric-sub" style="color: #d946ef;">Total non-working</div>';
        $html .= '</div></div>';
        
        // Monthly working days box (Blue style)
        $html .= '<div class="d2-metric-box">';
        $html .= '<div class="d2-metric-icon admissions"><i class="fa fa-calendar-check-o"></i></div>';
        $html .= '<div class="d2-metric-content">';
        $html .= '<div class="d2-metric-label">'.date('M').' Working Days</div>';
        $html .= '<div class="d2-metric-value">'.$m_working_days.'</div>';
        $html .= '<div class="d2-metric-sub" style="color: #0ea5e9;">Out of '.$m_total_days.' days</div>';
        $html .= '</div></div>';
        
        // Monthly Holidays Box (Green style)
        $html .= '<div class="d2-metric-box">';
        $html .= '<div class="d2-metric-icon attendance"><i class="fa fa-coffee"></i></div>';
        $html .= '<div class="d2-metric-content">';
        $html .= '<div class="d2-metric-label">'.date('M').' Holidays</div>';
        $html .= '<div class="d2-metric-value">'.$m_holidays.'</div>';
        $html .= '<div class="d2-metric-sub" style="color: #10b981;">Total non-working</div>';
        $html .= '</div></div>';

        $html .= '</div></div>';
        echo $html;
    }
}
?>
