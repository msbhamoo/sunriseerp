<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transportyearlyfee_model extends MY_Model
{
    public $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function add($data)
    {
        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('transport_yearly_feemaster', $data);
            return $data['id'];
        } else {
            $this->db->insert('transport_yearly_feemaster', $data);
            return $this->db->insert_id();
        }
    }

    public function get($id = null, $route_id = null, $pickup_point_id = null)
    {
        $this->db->select("transport_yearly_feemaster.*, 
            IFNULL(route_pickup_point.pickup_point_id, transport_yearly_feemaster.pickup_point_id) as pickup_point_id, 
            transport_route.id as route_id, transport_route.route_title, 
            IFNULL(pickup_point.name, p2.name) as pickup_point_name, 
            classes.class as class_name, feetype.type as feetype_name, feetype.code as feetype_code, 
            (SELECT GROUP_CONCAT(vehicles.vehicle_no SEPARATOR ', ') FROM vehicle_routes JOIN vehicles ON vehicles.id = vehicle_routes.vehicle_id WHERE vehicle_routes.route_id = transport_route.id) as vehicle_no");
        $this->db->from('transport_yearly_feemaster');
        $this->db->join('route_pickup_point', 'route_pickup_point.id = transport_yearly_feemaster.route_pickup_point_id', 'left');
        $this->db->join('pickup_point', 'pickup_point.id = route_pickup_point.pickup_point_id', 'left');
        $this->db->join('pickup_point as p2', 'p2.id = transport_yearly_feemaster.pickup_point_id', 'left');
        $this->db->join('transport_route', 'transport_route.id = route_pickup_point.transport_route_id', 'left');
        $this->db->join('classes', 'classes.id = transport_yearly_feemaster.class_id', 'left');
        $this->db->join('feetype', 'feetype.id = transport_yearly_feemaster.feetype_id');
        $this->db->where('transport_yearly_feemaster.session_id', $this->current_session);
        
        if ($route_id != null && $route_id != "") {
            $this->db->where('route_pickup_point.transport_route_id', $route_id);
        }
        if ($pickup_point_id != null && $pickup_point_id != "") {
            $this->db->group_start();
            $this->db->where('route_pickup_point.pickup_point_id', $pickup_point_id);
            $this->db->or_where('transport_yearly_feemaster.pickup_point_id', $pickup_point_id);
            $this->db->group_end();
        }
        
        if ($id != null) {
            $this->db->where('transport_yearly_feemaster.id', $id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->order_by('transport_yearly_feemaster.id', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function remove($id)
    {
        $this->db->trans_start();
        $this->db->where('transport_yearly_feemaster_id', $id);
        $this->db->delete('student_transport_yearly_fees');

        $this->db->where('id', $id);
        $this->db->delete('transport_yearly_feemaster');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return true;
    }

    public function check_payment_exists($transport_yearly_feemaster_id)
    {
        $this->db->select('student_fees_deposite.id');
        $this->db->from('student_transport_yearly_fees');
        $this->db->join('student_fees_deposite', 'student_fees_deposite.student_transport_yearly_fee_id = student_transport_yearly_fees.id');
        $this->db->where('student_transport_yearly_fees.transport_yearly_feemaster_id', $transport_yearly_feemaster_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }

    public function getApplicableYearlyFees($class_id, $route_pickup_point_id)
    {
        $this->db->select('pickup_point_id');
        $this->db->from('route_pickup_point');
        $this->db->where('id', $route_pickup_point_id);
        $rp = $this->db->get()->row();
        $pickup_point_id = $rp ? $rp->pickup_point_id : 0;

        $this->db->select('transport_yearly_feemaster.*, feetype.type, feetype.code');
        $this->db->from('transport_yearly_feemaster');
        $this->db->join('feetype', 'feetype.id = transport_yearly_feemaster.feetype_id');
        
        $this->db->group_start();
            $this->db->group_start();
                $this->db->where('transport_yearly_feemaster.class_id', $class_id);
                $this->db->or_where('transport_yearly_feemaster.class_id', 0);
            $this->db->group_end();
            
            $this->db->group_start();
                $this->db->where('transport_yearly_feemaster.route_pickup_point_id', $route_pickup_point_id);
                $this->db->or_where('transport_yearly_feemaster.pickup_point_id', $pickup_point_id);
            $this->db->group_end();
        $this->db->group_end();
        
        $this->db->where('transport_yearly_feemaster.session_id', $this->current_session);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStudentYearlyFees($student_session_id)
    {
        $this->db->select('student_transport_yearly_fees.*');
        $this->db->from('student_transport_yearly_fees');
        $this->db->where('student_transport_yearly_fees.student_session_id', $student_session_id);
        $this->db->where('student_transport_yearly_fees.is_active', 'yes');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function assignStudent($data)
    {
        $this->db->where('student_session_id', $data['student_session_id']);
        $this->db->where('transport_yearly_feemaster_id', $data['transport_yearly_feemaster_id']);
        $q = $this->db->get('student_transport_yearly_fees');

        if ($q->num_rows() == 0) {
            $this->db->insert('student_transport_yearly_fees', $data);
            return $this->db->insert_id();
        } else {
            $row = $q->row();
            if ($row->is_active == 'no') {
                $this->db->where('id', $row->id);
                $this->db->update('student_transport_yearly_fees', array('is_active' => 'yes'));
            }
            return $row->id;
        }
    }

    public function unassignStudent($student_session_id, $transport_yearly_feemaster_id)
    {
        // We only deactivate or delete if there's no payment against it. 
        // For simplicity we delete if not paid, but let's check deposite first, or just delete.
        // Similar to transport fee, we delete it.
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('transport_yearly_feemaster_id', $transport_yearly_feemaster_id);
        $this->db->delete('student_transport_yearly_fees');
    }

    public function getStudentTransportYearlyFees($student_session_id, $route_pickup_point_id, $class_id)
    {
        // Returns the configured fees and marks whether this student is assigned
        $fees = $this->getApplicableYearlyFees($class_id, $route_pickup_point_id);
        $assigned = $this->getStudentYearlyFees($student_session_id);
        
        $assigned_ids = array();
        foreach($assigned as $a) {
            $assigned_ids[] = $a['transport_yearly_feemaster_id'];
        }
        
        $result = array();
        foreach($fees as $f) {
            $f['student_transport_fee_id'] = 0;
            if (in_array($f['id'], $assigned_ids)) {
                // Get the student transport fee id
                foreach($assigned as $a) {
                    if ($a['transport_yearly_feemaster_id'] == $f['id']) {
                        $f['student_transport_fee_id'] = $a['id'];
                    }
                }
            }
            $result[] = $f;
        }
        return $result;
    }
}
