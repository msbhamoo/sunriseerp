<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feediscountrequest_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('fee_discount_requests', $data);
            return $data['id'];
        } else {
            $this->db->insert('fee_discount_requests', $data);
            return $this->db->insert_id();
        }
    }

    public function get($id)
    {
        $this->db->select('fee_discount_requests.*, staff.name as staff_name, staff.surname as staff_surname, staff.employee_id as staff_employee_id');
        $this->db->from('fee_discount_requests');
        $this->db->join('staff', 'staff.id = fee_discount_requests.requested_by', 'left');
        $this->db->where('fee_discount_requests.id', $id);
        return $this->db->get()->row_array();
    }

    public function getByStudent($student_session_id)
    {
        $this->db->select('fee_discount_requests.*, staff.name as staff_name, staff.surname as staff_surname, staff.employee_id as staff_employee_id, admin.name as admin_name, admin.surname as admin_surname');
        $this->db->from('fee_discount_requests');
        $this->db->join('staff', 'staff.id = fee_discount_requests.requested_by', 'left');
        $this->db->join('staff as admin', 'admin.id = fee_discount_requests.approved_by', 'left');
        $this->db->where('fee_discount_requests.student_session_id', $student_session_id);
        $this->db->order_by('fee_discount_requests.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getAll($status = null)
    {
        $this->db->select('fee_discount_requests.*, 
            students.firstname, students.lastname, students.admission_no, 
            classes.class, sections.section, 
            staff.name as staff_name, staff.surname as staff_surname, staff.employee_id as staff_employee_id,
            admin.name as admin_name, admin.surname as admin_surname');
        $this->db->from('fee_discount_requests');
        $this->db->join('student_session', 'student_session.id = fee_discount_requests.student_session_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'classes.id = student_session.class_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('staff', 'staff.id = fee_discount_requests.requested_by', 'left');
        $this->db->join('staff as admin', 'admin.id = fee_discount_requests.approved_by', 'left');
        
        if ($status != null && $status != 'all') {
            $this->db->where('fee_discount_requests.status', $status);
        }
        
        $this->db->order_by('fee_discount_requests.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getPendingCount()
    {
        $this->db->where('status', 'pending');
        $this->db->or_where('status', 'provisional');
        return $this->db->count_all_results('fee_discount_requests');
    }

    public function updateStatus($id, $status, $admin_id, $remark = '')
    {
        $data = array(
            'status' => $status,
            'approved_by' => $admin_id,
            'admin_remark' => $remark,
            'approved_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id);
        $this->db->update('fee_discount_requests', $data);
    }

    public function getByInvoice($invoice_id)
    {
        $this->db->select('*');
        $this->db->from('fee_discount_requests');
        $this->db->where('student_fees_deposite_id', $invoice_id);
        return $this->db->get()->result();
    }
}
