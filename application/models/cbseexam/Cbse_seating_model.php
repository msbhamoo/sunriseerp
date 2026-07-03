<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cbse_seating_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get_allocations($exam_id = null)
    {
        $this->db->select('cbse_seating_allocations.*, cbse_exams.name as exam_name')
            ->from('cbse_seating_allocations')
            ->join('cbse_exams', 'cbse_exams.id = cbse_seating_allocations.cbse_exam_id')
            ->where('cbse_seating_allocations.session_id', $this->current_session);
            
        if ($exam_id != null) {
            $this->db->where('cbse_seating_allocations.cbse_exam_id', $exam_id);
        }
        
        $this->db->order_by('cbse_seating_allocations.exam_date', 'DESC');
        return $this->db->get()->result_array();
    }
    
    public function get_allocation_by_id($id)
    {
        $this->db->select('cbse_seating_allocations.*, cbse_exams.name as exam_name')
            ->from('cbse_seating_allocations')
            ->join('cbse_exams', 'cbse_exams.id = cbse_seating_allocations.cbse_exam_id')
            ->where('cbse_seating_allocations.id', $id);
        return $this->db->get()->row_array();
    }

    public function autoAllocateStudents($exam_id, $exam_date, $strategy, $seat_format, $selected_rooms)
    {
        $this->db->trans_start();

        // 1. Fetch all students for this exam
        $students = $this->db->select('cbse_exam_students.*, student_session.class_id, classes.class as class_name, sections.section as section_name')
            ->from('cbse_exam_students')
            ->join('student_session', 'student_session.id = cbse_exam_students.student_session_id')
            ->join('classes', 'classes.id = student_session.class_id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->where('cbse_exam_students.cbse_exam_id', $exam_id)
            ->order_by('classes.id', 'ASC')
            ->order_by('sections.id', 'ASC')
            ->order_by('cbse_exam_students.roll_no', 'ASC')
            ->get()->result_array();

        if (empty($students)) {
            return false; // No students to allocate
        }

        // 2. Fetch selected rooms
        $rooms = $this->db->where_in('id', $selected_rooms)
            ->where('is_active', 1)
            ->order_by('room_number', 'ASC')
            ->get('cbse_seating_rooms')->result_array();

        if (empty($rooms)) {
            return ['status' => false, 'msg' => 'No valid rooms selected.'];
        }

        // Validate total capacity
        $total_capacity = 0;
        foreach ($rooms as $room) {
            $total_capacity += $room['seating_capacity'];
        }

        if (count($students) > $total_capacity) {
            return [
                'status' => false, 
                'msg' => 'Insufficient room capacity! You are trying to seat ' . count($students) . ' students, but the selected rooms only have capacity for ' . $total_capacity . ' students. Please select more rooms or adjust capacities.'
            ];
        }

        // 3. Create Allocation Record
        $alloc_data = [
            'cbse_exam_id' => $exam_id,
            'exam_date' => $exam_date,
            'allocation_strategy' => $strategy,
            'seat_number_format' => $seat_format,
            'status' => 'draft',
            'session_id' => $this->current_session
        ];
        
        // Remove existing allocation for this exam and date if exists (it cascades to children)
        $this->db->where('cbse_exam_id', $exam_id)
            ->where('exam_date', $exam_date)
            ->where('session_id', $this->current_session)
            ->delete('cbse_seating_allocations');
            
        $this->db->insert('cbse_seating_allocations', $alloc_data);
        $allocation_id = $this->db->insert_id();

        // 4. Group students by class
        $class_groups = [];
        foreach ($students as $student) {
            $class_groups[$student['class_id']][] = $student;
        }

        // 5. Allocation Engine
        $room_idx = 0;
        $current_room_seats_used = 0;
        
        $total_allocated = 0;
        $room_assignments = [];
        
        if ($strategy == 'interleaved') {
            // Pick one from each class in round-robin fashion
            $class_keys = array_keys($class_groups);
            $class_idx = 0;
            
            while ($total_allocated < count($students)) {
                if ($room_idx >= count($rooms)) break; // Out of rooms
                
                $current_room = $rooms[$room_idx];
                
                // Get next student
                $student_to_seat = null;
                $attempts = 0;
                while ($student_to_seat == null && $attempts < count($class_keys)) {
                    $key = $class_keys[$class_idx];
                    if (!empty($class_groups[$key])) {
                        $student_to_seat = array_shift($class_groups[$key]);
                    }
                    $class_idx = ($class_idx + 1) % count($class_keys);
                    $attempts++;
                }
                
                if ($student_to_seat == null) break; // All classes empty
                
                // Create Room Assignment if not exists
                if (!isset($room_assignments[$current_room['id']])) {
                    $this->db->insert('cbse_seating_room_assignments', [
                        'allocation_id' => $allocation_id,
                        'room_id' => $current_room['id']
                    ]);
                    $room_assignments[$current_room['id']] = $this->db->insert_id();
                }
                
                $seat_num = $current_room_seats_used + 1;
                $formatted_seat = ($seat_format == 'room_prefixed') ? $current_room['room_number'] . '-' . $seat_num : (string)$seat_num;
                
                // Seat the student
                $this->db->insert('cbse_seating_student_seats', [
                    'allocation_id' => $allocation_id,
                    'room_assignment_id' => $room_assignments[$current_room['id']],
                    'student_session_id' => $student_to_seat['student_session_id'],
                    'seat_number' => $seat_num,
                    'formatted_seat_number' => $formatted_seat
                ]);
                
                $current_room_seats_used++;
                $total_allocated++;
                
                if ($current_room_seats_used >= $current_room['seating_capacity']) {
                    // Update room seats used
                    $this->db->where('id', $room_assignments[$current_room['id']])
                        ->update('cbse_seating_room_assignments', ['seats_used' => $current_room_seats_used]);
                    
                    $room_idx++;
                    $current_room_seats_used = 0;
                }
            }
        } else {
            // Grouped Strategy (Fill room sequentially by class)
            foreach ($students as $student_to_seat) {
                if ($room_idx >= count($rooms)) break; // Out of rooms
                
                $current_room = $rooms[$room_idx];
                
                // Create Room Assignment if not exists
                if (!isset($room_assignments[$current_room['id']])) {
                    $this->db->insert('cbse_seating_room_assignments', [
                        'allocation_id' => $allocation_id,
                        'room_id' => $current_room['id']
                    ]);
                    $room_assignments[$current_room['id']] = $this->db->insert_id();
                }
                
                $seat_num = $current_room_seats_used + 1;
                $formatted_seat = ($seat_format == 'room_prefixed') ? $current_room['room_number'] . '-' . $seat_num : (string)$seat_num;
                
                // Seat the student
                $this->db->insert('cbse_seating_student_seats', [
                    'allocation_id' => $allocation_id,
                    'room_assignment_id' => $room_assignments[$current_room['id']],
                    'student_session_id' => $student_to_seat['student_session_id'],
                    'seat_number' => $seat_num,
                    'formatted_seat_number' => $formatted_seat
                ]);
                
                $current_room_seats_used++;
                $total_allocated++;
                
                if ($current_room_seats_used >= $current_room['seating_capacity']) {
                    // Update room seats used
                    $this->db->where('id', $room_assignments[$current_room['id']])
                        ->update('cbse_seating_room_assignments', ['seats_used' => $current_room_seats_used]);
                    
                    $room_idx++;
                    $current_room_seats_used = 0;
                }
            }
        }
        
        // Update final room seats if partially filled
        if ($current_room_seats_used > 0 && isset($rooms[$room_idx])) {
            $this->db->where('id', $room_assignments[$rooms[$room_idx]['id']])
                ->update('cbse_seating_room_assignments', ['seats_used' => $current_room_seats_used]);
        }
        
        // Update Allocation summary
        $this->db->where('id', $allocation_id)->update('cbse_seating_allocations', [
            'total_students_allocated' => $total_allocated,
            'total_rooms_used' => count($room_assignments)
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    public function delete_allocation($id)
    {
        $this->db->trans_start();
        $this->db->where('id', $id)->delete('cbse_seating_allocations');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function update_allocation_status($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update('cbse_seating_allocations', array('status' => $status));
    }

    public function swap_students($seat1_id, $seat2_id)
    {
        $seat1 = $this->db->where('id', $seat1_id)->get('cbse_seating_student_seats')->row_array();
        $seat2 = $this->db->where('id', $seat2_id)->get('cbse_seating_student_seats')->row_array();

        if ($seat1 && $seat2) {
            $this->db->trans_start();
            
            // Swap to seat2
            $this->db->where('id', $seat1_id)->update('cbse_seating_student_seats', [
                'room_assignment_id' => $seat2['room_assignment_id'],
                'seat_number' => $seat2['seat_number'],
                'formatted_seat_number' => $seat2['formatted_seat_number']
            ]);
            
            // Swap to seat1
            $this->db->where('id', $seat2_id)->update('cbse_seating_student_seats', [
                'room_assignment_id' => $seat1['room_assignment_id'],
                'seat_number' => $seat1['seat_number'],
                'formatted_seat_number' => $seat1['formatted_seat_number']
            ]);
            
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
        return false;
    }

    public function move_students($seat_ids_array, $target_room_assignment_id)
    {
        $this->db->trans_start();
        
        $target_room = $this->db->select('cbse_seating_room_assignments.*, cbse_seating_rooms.room_number, cbse_seating_rooms.seating_capacity, cbse_seating_allocations.seat_number_format')
            ->from('cbse_seating_room_assignments')
            ->join('cbse_seating_rooms', 'cbse_seating_rooms.id = cbse_seating_room_assignments.room_id')
            ->join('cbse_seating_allocations', 'cbse_seating_allocations.id = cbse_seating_room_assignments.allocation_id')
            ->where('cbse_seating_room_assignments.id', $target_room_assignment_id)
            ->get()->row_array();

        if (!$target_room) return false;

        $current_seats_used = $target_room['seats_used'];
        $capacity = $target_room['seating_capacity'];
        $seat_format = $target_room['seat_number_format'];
        $room_number = $target_room['room_number'];

        foreach ($seat_ids_array as $seat_id) {
            if ($current_seats_used >= $capacity) {
                break; // Room is full
            }
            
            $current_seats_used++;
            $formatted_seat = ($seat_format == 'room_prefixed') ? $room_number . '-' . $current_seats_used : (string)$current_seats_used;
            
            $this->db->where('id', $seat_id)->update('cbse_seating_student_seats', [
                'room_assignment_id' => $target_room_assignment_id,
                'seat_number' => $current_seats_used,
                'formatted_seat_number' => $formatted_seat
            ]);
        }

        // Update target room seats used
        $this->db->where('id', $target_room_assignment_id)->update('cbse_seating_room_assignments', ['seats_used' => $current_seats_used]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
