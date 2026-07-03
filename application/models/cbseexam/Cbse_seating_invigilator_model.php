<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cbse_seating_invigilator_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_allocation_rooms($allocation_id)
    {
        $this->db->select('cbse_seating_room_assignments.*, cbse_seating_rooms.room_number, cbse_seating_buildings.name as building_name')
            ->from('cbse_seating_room_assignments')
            ->join('cbse_seating_rooms', 'cbse_seating_rooms.id = cbse_seating_room_assignments.room_id')
            ->join('cbse_seating_buildings', 'cbse_seating_buildings.id = cbse_seating_rooms.building_id')
            ->where('cbse_seating_room_assignments.allocation_id', $allocation_id)
            ->order_by('cbse_seating_buildings.name', 'ASC')
            ->order_by('cbse_seating_rooms.room_number', 'ASC');
            
        $rooms = $this->db->get()->result_array();
        
        // Fetch existing invigilators for each room
        foreach ($rooms as $key => $room) {
            $rooms[$key]['invigilators'] = $this->db->select('cbse_seating_invigilators.*, staff.name as staff_name, staff.surname as staff_surname, staff.employee_id')
                ->from('cbse_seating_invigilators')
                ->join('staff', 'staff.id = cbse_seating_invigilators.staff_id')
                ->where('cbse_seating_invigilators.room_assignment_id', $room['id'])
                ->get()->result_array();
        }
        
        return $rooms;
    }

    public function assign_invigilators($allocation_id, $data)
    {
        $this->db->trans_start();
        
        // Remove old assignments for this allocation
        $this->db->where('allocation_id', $allocation_id)->delete('cbse_seating_invigilators');
        
        // Insert new
        if (!empty($data)) {
            $this->db->insert_batch('cbse_seating_invigilators', $data);
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
