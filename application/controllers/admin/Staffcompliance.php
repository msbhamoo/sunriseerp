<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Staffcompliance extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->load->model('staff_model');
        $this->load->model('staffcompliance_model');
        $this->load->model('setting_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('staff_compliance', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffcompliance');

        $data['title']       = 'Staff Compliance Checklist';
        $data['sch_setting'] = $this->setting_model->getSetting();
        $data['classlist']   = $this->staff_model->getStaffRole();

        $user_type_id = $this->input->post('user_id');
        $data['user_type_id'] = $user_type_id;

        $date_input = $this->input->post('date');
        if (empty($date_input)) {
            $formatted_date = date($this->customlib->getSchoolDateFormat());
            $db_date        = date('Y-m-d');
        } else {
            $formatted_date = $date_input;
            $db_date        = date('Y-m-d', $this->customlib->datetostrtotime($date_input));
        }

        $data['date'] = $formatted_date;

        // If form is submitted for saving compliance
        $search = $this->input->post('search');
        if ($search === 'savecompliance') {
            if (!$this->rbac->hasPrivilege('staff_compliance', 'can_add') && !$this->rbac->hasPrivilege('staff_compliance', 'can_edit')) {
                access_denied();
            }

            $staff_ids = $this->input->post('staff_session');
            $compliance_rows = [];

            if (!empty($staff_ids)) {
                foreach ($staff_ids as $sid) {
                    $u_val  = $this->input->post('uniform_status_' . $sid);
                    $id_val = $this->input->post('id_card_status_' . $sid);
                    $lp_val = $this->input->post('lesson_plan_status_' . $sid);
                    $ph_val = $this->input->post('phone_handover_status_' . $sid);
                    $rem    = $this->input->post('remark_' . $sid);

                    $compliance_rows[] = [
                        'staff_id'              => $sid,
                        'date'                  => $db_date,
                        'uniform_status'        => ($u_val === 'yes') ? 'yes' : 'no',
                        'id_card_status'        => ($id_val === 'yes') ? 'yes' : 'no',
                        'lesson_plan_status'    => ($lp_val === 'yes') ? 'yes' : 'no',
                        'phone_handover_status' => ($ph_val === 'yes') ? 'yes' : 'no',
                        'remark'                => trim((string)$rem),
                    ];
                }

                $this->staffcompliance_model->saveCompliance($compliance_rows);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            }

            // Redirect preserving query context or role/date
            redirect('admin/staffcompliance');
            return;
        }

        // Search or display results
        if (isset($user_type_id)) {
            $data['resultlist'] = $this->staffcompliance_model->getStaffComplianceList($user_type_id, $db_date);
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffcompliance/index', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * AJAX endpoint to save an individual staff's compliance toggle instantly
     */
    public function ajax_save_single()
    {
        if (!$this->rbac->hasPrivilege('staff_compliance', 'can_edit') && !$this->rbac->hasPrivilege('staff_compliance', 'can_add')) {
            json_output(403, ['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $staff_id = $this->input->post('staff_id');
        $field    = $this->input->post('field');
        $value    = $this->input->post('value');
        $date_str = $this->input->post('date');

        $allowed_fields = ['uniform_status', 'id_card_status', 'lesson_plan_status', 'phone_handover_status'];
        if (!in_array($field, $allowed_fields) || empty($staff_id)) {
            json_output(400, ['status' => 'error', 'message' => 'Invalid parameters']);
            return;
        }

        $db_date = !empty($date_str) ? date('Y-m-d', $this->customlib->datetostrtotime($date_str)) : date('Y-m-d');
        $status_val = ($value === 'yes' || $value === '1' || $value === true) ? 'yes' : 'no';

        $data = [
            [
                'staff_id' => $staff_id,
                'date'     => $db_date,
                $field     => $status_val,
            ]
        ];

        $this->staffcompliance_model->saveCompliance($data);
        json_output(200, ['status' => 'success', 'message' => 'Updated', 'field' => $field, 'value' => $status_val]);
    }

    /**
     * AJAX endpoint to render monthly compliance sheet partial
     */
    public function monthsheet()
    {
        if (!$this->rbac->hasPrivilege('staff_compliance', 'can_view')) {
            access_denied();
        }

        $role  = $this->input->post('role');
        $month = (int)$this->input->post('month');
        $year  = (int)$this->input->post('year');

        if (empty($month)) {
            $month = (int)date('n');
        }
        if (empty($year)) {
            $year = (int)date('Y');
        }

        $data['role']          = $role;
        $data['month']         = $month;
        $data['year']          = $year;
        $data['days_in_month'] = (int)date('t', mktime(0, 0, 0, $month, 1, $year));

        // Get off days (Sundays + annual_calendar holidays)
        $m_start = sprintf('%04d-%02d-01', $year, $month);
        $m_end   = sprintf('%04d-%02d-%02d', $year, $month, $data['days_in_month']);

        $holiday_dates = [];
        for ($t = strtotime($m_start); $t <= strtotime($m_end); $t += 86400) {
            if ((int)date('w', $t) === 0) {
                $holiday_dates[date('Y-m-d', $t)] = true;
            }
        }
        if ($this->db->table_exists('annual_calendar')) {
            $c_rows = $this->db->query("SELECT from_date, to_date, is_working_day FROM annual_calendar
                WHERE is_active = 1
                AND DATE(from_date) <= " . $this->db->escape($m_end) . "
                AND DATE(to_date) >= " . $this->db->escape($m_start) . "
                ORDER BY is_working_day ASC")->result();
            foreach ($c_rows as $cr) {
                $cd  = max(strtotime($m_start), strtotime(date('Y-m-d', strtotime($cr->from_date))));
                $cto = min(strtotime($m_end), strtotime(date('Y-m-d', strtotime($cr->to_date))));
                for (; $cd <= $cto; $cd += 86400) {
                    $c_str = date('Y-m-d', $cd);
                    if ((int)$cr->is_working_day === 1) {
                        unset($holiday_dates[$c_str]);
                    } else {
                        $holiday_dates[$c_str] = true;
                    }
                }
            }
        }

        $data['holiday_dates'] = $holiday_dates;
        $data['sheet']         = $this->staffcompliance_model->getMonthlyComplianceSheet($role, $year, $month);

        $this->load->view('admin/staffcompliance/_monthly_sheet', $data);
    }
}
