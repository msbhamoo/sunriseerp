<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cbse_seating_room_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get_buildings($id = null)
    {
        $this->db->select()->from('cbse_seating_buildings');
        $this->db->where('session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('id', $id);
            return $this->db->get()->row_array();
        }
        $this->db->order_by('name');
        return $this->db->get()->result_array();
    }

    public function add_building($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('cbse_seating_buildings', $data);
            $message = UPDATE_RECORD_CONSTANT . " On cbse_seating_buildings id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
        } else {
            $data['session_id'] = $this->current_session;
            $this->db->insert('cbse_seating_buildings', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On cbse_seating_buildings id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
        }

        $this->log($message, $record_id, $action);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function delete_building($id)
    {
        $this->db->trans_start();
        $this->db->where('id', $id)->delete('cbse_seating_buildings');
        $message = DELETE_RECORD_CONSTANT . " On cbse_seating_buildings id " . $id;
        $this->log($message, $id, "Delete");
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_rooms($id = null, $building_id = null)
    {
        $this->db->select('cbse_seating_rooms.*, cbse_seating_buildings.name as building_name, cbse_seating_buildings.code as building_code')
            ->from('cbse_seating_rooms')
            ->join('cbse_seating_buildings', 'cbse_seating_buildings.id = cbse_seating_rooms.building_id')
            ->where('cbse_seating_rooms.session_id', $this->current_session);

        if ($building_id != null) {
            $this->db->where('cbse_seating_rooms.building_id', $building_id);
        }

        if ($id != null) {
            $this->db->where('cbse_seating_rooms.id', $id);
            return $this->db->get()->row_array();
        }

        $this->db->order_by('cbse_seating_buildings.name', 'ASC');
        $this->db->order_by('cbse_seating_rooms.room_number', 'ASC');
        return $this->db->get()->result_array();
    }

    public function add_room($data)
    {
        $this->db->trans_start();
        
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('cbse_seating_rooms', $data);
            $message = UPDATE_RECORD_CONSTANT . " On cbse_seating_rooms id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
        } else {
            $data['session_id'] = $this->current_session;
            $this->db->insert('cbse_seating_rooms', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On cbse_seating_rooms id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
        }

        $this->log($message, $record_id, $action);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function delete_room($id)
    {
        $this->db->trans_start();
        $this->db->where('id', $id)->delete('cbse_seating_rooms');
        $message = DELETE_RECORD_CONSTANT . " On cbse_seating_rooms id " . $id;
        $this->log($message, $id, "Delete");
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    public function bulk_generate_rooms($building_id, $prefix, $start, $count, $capacity, $type)
    {
        $this->db->trans_start();
        $success_count = 0;
        
        for ($i = 0; $i < $count; $i++) {
            $room_num = $start + $i;
            $room_name = $prefix . $room_num;
            
            // Check if exists to avoid unique constraint error
            $exists = $this->db->where('building_id', $building_id)
                               ->where('room_number', $room_name)
                               ->where('session_id', $this->current_session)
                               ->get('cbse_seating_rooms')
                               ->num_rows();
                               
            if ($exists == 0) {
                $data = [
                    'building_id' => $building_id,
                    'room_number' => $room_name,
                    'seating_capacity' => $capacity,
                    'room_type' => $type,
                    'is_active' => 1,
                    'session_id' => $this->current_session
                ];
                $this->db->insert('cbse_seating_rooms', $data);
                $success_count++;
            }
        }
        
        $this->log("Bulk generated $success_count rooms in building $building_id", $building_id, "Insert");
        $this->db->trans_complete();
        return $success_count;
    }
}
