<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Vehicle_model extends MY_Model
{

    protected $current_session;
    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null)
    {
        $this->db->select()->from('vehicles');
        if ($id != null) {
            $this->db->where('vehicles.id', $id);
        } else {
            $this->db->order_by('vehicles.id','desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result_array();
        }
    }

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('vehicles');
        
        $this->db->where('vehicle_id', $id);
        $this->db->delete('vehicle_routes');
        
        $message   = DELETE_RECORD_CONSTANT . " On vehicles id " . $id;
        $action    = "Delete";
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

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('vehicles', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  vehicles id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
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
        } else {
            $this->db->insert('vehicles', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On vehicles id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }

    public function vehicleListByarray($array)
    {
        $this->db->select('*');
        $this->db->from('vehicles');
        $this->db->where_in('vehicles.id', $array);
        $query = $this->db->get();
        return $query->result();
    }

    public function getVehicleRoutes($vehicle_id)
    {
        $this->db->select('vehicle_routes.id as vehroute_id, vehicle_routes.route_id, transport_route.route_title, pickup_point.name as pickup_point_name, route_pickup_point.pickup_time');
        $this->db->from('vehicle_routes');
        $this->db->join('transport_route', 'transport_route.id = vehicle_routes.route_id');
        $this->db->join('route_pickup_point', 'route_pickup_point.transport_route_id = transport_route.id', 'left');
        $this->db->join('pickup_point', 'pickup_point.id = route_pickup_point.pickup_point_id', 'left');
        $this->db->where('vehicle_routes.vehicle_id', $vehicle_id);
        $this->db->order_by('transport_route.route_title, route_pickup_point.order_number', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getVehicleOccupancy($session_id = null)
    {
        if ($session_id == null) {
            $session_id = $this->current_session;
        }
        $this->db->select('vehicle_routes.vehicle_id, COUNT(student_session.id) as occupied_seats');
        $this->db->from('student_session');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_session.session_id', $session_id);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_by('vehicle_routes.vehicle_id');
        
        $result = $this->db->get()->result_array();
        
        $occupancy = array();
        foreach ($result as $row) {
            $occupancy[$row['vehicle_id']] = $row['occupied_seats'];
        }
        return $occupancy;
    }

    public function getVehicleStudents($vehicle_id, $session_id = null)
    {
        if ($session_id == null) {
            $session_id = $this->current_session;
        }
        $this->db->select('students.admission_no, students.firstname, students.lastname, classes.class, sections.section, transport_route.route_title, pickup_point.name as pickup_point_name');
        $this->db->from('student_session');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'classes.id = student_session.class_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id');
        $this->db->join('transport_route', 'transport_route.id = vehicle_routes.route_id', 'left');
        $this->db->join('route_pickup_point', 'route_pickup_point.id = student_session.route_pickup_point_id', 'left');
        $this->db->join('pickup_point', 'pickup_point.id = route_pickup_point.pickup_point_id', 'left');
        $this->db->where('vehicle_routes.vehicle_id', $vehicle_id);
        $this->db->where('student_session.session_id', $session_id);
        $this->db->where('students.is_active', 'yes');
        $this->db->order_by('transport_route.route_title', 'ASC');
        $this->db->order_by('students.firstname', 'ASC');
        return $this->db->get()->result_array();
    }

}
