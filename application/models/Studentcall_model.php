<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Studentcall_model extends MY_Model
{
    protected $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function resolve_previous_followups($call_id)
    {
        $this->resolve_previous_followups_with_details($call_id, 'Resolved');
    }

    public function resolve_previous_followups_with_details($call_id, $status = 'Resolved', $call_status = null, $remarks = null)
    {
        if (!empty($call_id)) {
            $data = array('status' => !empty($status) ? $status : 'Resolved');
            if (!empty($call_status)) {
                $data['call_status'] = $call_status;
            }
            if ($remarks !== null && $remarks !== '') {
                $data['remarks'] = $remarks;
            }
            $this->db->where('student_call_id', $call_id);
            $this->db->where('status', 'Pending');
            $this->db->update('student_call_followups', $data);
        }
    }

    public function auto_resolve_zero_due_fee_followups($student_session_id = null, $call_id = null)
    {
        $this->db->select('f.id as followup_id, f.student_call_id, sc.student_session_id, sc.student_id, sc.call_purpose_id, p.purpose as purpose_name');
        $this->db->from('student_call_followups f');
        $this->db->join('student_calls sc', 'sc.id = f.student_call_id');
        $this->db->join('student_session ss', 'ss.id = sc.student_session_id');
        $this->db->join('student_call_purpose p', 'p.id = sc.call_purpose_id', 'left');
        $this->db->where('f.status', 'Pending');
        $this->db->where('ss.session_id', $this->current_session);
        
        if (!empty($student_session_id)) {
            $this->db->where('sc.student_session_id', $student_session_id);
        }
        if (!empty($call_id)) {
            $this->db->where('sc.id', $call_id);
        }
        
        // Match purpose that relates to Fee or general/empty
        $this->db->group_start();
        $this->db->like('LOWER(p.purpose)', 'fee');
        $this->db->or_where('sc.call_purpose_id IS NULL', null, false);
        $this->db->or_where('sc.call_purpose_id', 0);
        $this->db->or_where('sc.call_purpose_id', '');
        $this->db->group_end();
        
        $pending = $this->db->get()->result_array();
        if (!empty($pending)) {
            foreach ($pending as $p_item) {
                $fee_info = $this->get_student_fee_info($p_item['student_session_id']);
                if (!empty($fee_info) && $fee_info['total_amount'] > 0 && $fee_info['due_amount'] <= 0) {
                    // All fees paid! Auto resolve this follow-up
                    $this->db->where('id', $p_item['followup_id']);
                    $this->db->update('student_call_followups', [
                        'status' => 'Resolved',
                        'call_status' => 'Connected',
                        'remarks' => 'Auto-Resolved: Fee balance is ₹0.00 (All fees fully paid)'
                    ]);
                    
                    $this->db->where('id', $p_item['student_call_id']);
                    $this->db->update('student_calls', [
                        'call_status' => 'Connected'
                    ]);
                }
            }
        }
    }

    public function merge_existing_duplicate_calls()
    {
        $sql = "SELECT student_id, call_purpose_id, COUNT(*) as cnt 
                FROM student_calls 
                GROUP BY student_id, call_purpose_id 
                HAVING cnt > 1";
        $query = $this->db->query($sql);
        if ($query) {
            $duplicates = $query->result_array();
            foreach ($duplicates as $dup) {
                $this->db->select('id');
                $this->db->from('student_calls');
                $this->db->where('student_id', $dup['student_id']);
                if (is_null($dup['call_purpose_id']) || $dup['call_purpose_id'] === '' || $dup['call_purpose_id'] == 0) {
                    $this->db->where('(call_purpose_id IS NULL OR call_purpose_id = 0 OR call_purpose_id = \'\')', NULL, FALSE);
                } else {
                    $this->db->where('call_purpose_id', $dup['call_purpose_id']);
                }
                $this->db->order_by('id', 'desc');
                $q = $this->db->get();
                $rows = $q->result_array();

                if (count($rows) > 1) {
                    $keep_id = $rows[0]['id'];
                    $remove_ids = array();
                    for ($i = 1; $i < count($rows); $i++) {
                        $remove_ids[] = $rows[$i]['id'];
                    }

                    if (!empty($remove_ids)) {
                        $this->db->where_in('student_call_id', $remove_ids);
                        $this->db->update('student_call_followups', array('student_call_id' => $keep_id));

                        $this->db->where_in('id', $remove_ids);
                        $this->db->delete('student_calls');
                    }
                }
            }
        }
    }

    public function get_purposes()
    {
        $this->db->select('*');
        $this->db->from('student_call_purpose');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_purpose($data)
    {
        $this->db->insert('student_call_purpose', $data);
        return $this->db->insert_id();
    }

    public function update_purpose($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('student_call_purpose', $data);
    }

    public function delete_purpose($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('student_call_purpose');
    }

    public function get_purpose($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('student_call_purpose');
        return $query->row_array();
    }

    public function add_call($data)
    {
        if (!empty($data['student_id'])) {
            $this->db->where('student_id', $data['student_id']);
            if (!empty($data['call_purpose_id'])) {
                $this->db->where('call_purpose_id', $data['call_purpose_id']);
            } else {
                $this->db->group_start();
                $this->db->where('call_purpose_id IS NULL', null, false);
                $this->db->or_where('call_purpose_id', 0);
                $this->db->or_where('call_purpose_id', '');
                $this->db->group_end();
            }
            $this->db->order_by('id', 'desc');
            $query = $this->db->get('student_calls');
            $existing = $query->row_array();

            if ($existing) {
                $this->db->where('id', $existing['id']);
                $this->db->update('student_calls', $data);
                return $existing['id'];
            }
        }

        $this->db->insert('student_calls', $data);
        return $this->db->insert_id();
    }

    public function update_call($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('student_calls', $data);
    }

    public function add_followup($data)
    {
        $this->db->insert('student_call_followups', $data);
        return $this->db->insert_id();
    }

    public function update_followup($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('student_call_followups', $data);
    }

    public function search_student($query)
    {
        $this->db->select('students.id as student_id, student_session.id as student_session_id, students.admission_no, students.roll_no, students.firstname, students.middlename, students.lastname, students.father_name, students.mother_name, students.image, students.mobileno, students.father_phone, students.mother_phone, students.guardian_phone, students.admission_type, students.shrestha, students.rte, students.is_staff_kid, students.staff_id, staff.name as staff_name, classes.class, sections.section');
        $this->db->from('student_session');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'student_session.section_id = sections.id');
        $this->db->join('staff', 'staff.id = students.staff_id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        
        $this->db->group_start();
        $this->db->like('students.firstname', $query);
        $this->db->or_like('students.lastname', $query);
        $this->db->or_like('students.admission_no', $query);
        $this->db->or_like('students.roll_no', $query);
        $this->db->group_end();
        
        $this->db->limit(20);
        $q = $this->db->get();
        return $q->result_array();
    }

    public function get_calls($class_id = null, $section_id = null, $date_from = null, $date_to = null, $purpose_id = null, $status = null, $follow_up_date_from = null, $follow_up_date_to = null, $assigned_to = null)
    {
        $this->db->select('student_calls.*, students.firstname, students.lastname, students.admission_no, students.father_name, students.father_phone, students.mother_phone, students.guardian_phone, students.mobileno, students.admission_type, students.shrestha, students.rte, students.is_staff_kid, students.staff_id, student_staff.name as student_staff_name, classes.class, sections.section, student_call_purpose.purpose as purpose_name, staff.name as staff_name, staff.surname as staff_surname, pickup_point.name as pickup_point_name, (SELECT due_date FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1) as next_follow_up_date, (SELECT count(id) FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending") as pending_count, (SELECT count(id) FROM student_call_followups WHERE student_call_id = student_calls.id) as total_followups, (SELECT CONCAT(staff.name, " ", staff.surname) FROM student_call_followups JOIN staff ON staff.id = student_call_followups.assigned_to WHERE student_call_id = student_calls.id AND student_call_followups.status = "Pending" ORDER BY student_call_followups.id DESC LIMIT 1) as assigned_to_name');
        $this->db->from('student_calls');
        $this->db->join('student_session', 'student_session.id = student_calls.student_session_id');
        $this->db->join('students', 'students.id = student_calls.student_id');
        $this->db->join('staff as student_staff', 'student_staff.id = students.staff_id', 'left');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'student_session.section_id = sections.id');
        $this->db->join('route_pickup_point', 'student_session.route_pickup_point_id = route_pickup_point.id', 'left');
        $this->db->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left');
        $this->db->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left');
        $this->db->join('staff', 'staff.id = student_calls.created_by', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');

        if (!empty($class_id)) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }
        if (!empty($purpose_id)) {
            $this->db->where('student_calls.call_purpose_id', $purpose_id);
        }
        if (!empty($status)) {
            $this->db->where('student_calls.call_status', $status);
        }
        if (!empty($date_from) && !empty($date_to)) {
            $this->db->where('DATE(student_calls.date) >=', $date_from);
            $this->db->where('DATE(student_calls.date) <=', $date_to);
        } elseif (!empty($date_from)) {
            $this->db->where('DATE(student_calls.date) >=', $date_from);
        } elseif (!empty($date_to)) {
            $this->db->where('DATE(student_calls.date) <=', $date_to);
        }
        
        if (!empty($assigned_to)) {
            $this->db->where('EXISTS (SELECT 1 FROM student_call_followups WHERE student_call_followups.student_call_id = student_calls.id AND student_call_followups.assigned_to = '.(int)$assigned_to.')', NULL, FALSE);
        }
        if (!empty($follow_up_date_from) && !empty($follow_up_date_to)) {
            $this->db->where('DATE((SELECT due_date FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1)) >= '.$this->db->escape($follow_up_date_from), NULL, FALSE);
            $this->db->where('DATE((SELECT due_date FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1)) <= '.$this->db->escape($follow_up_date_to), NULL, FALSE);
        } elseif (!empty($follow_up_date_from)) {
            $this->db->where('DATE((SELECT due_date FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1)) >= '.$this->db->escape($follow_up_date_from), NULL, FALSE);
        } elseif (!empty($follow_up_date_to)) {
            $this->db->where('DATE((SELECT due_date FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1)) <= '.$this->db->escape($follow_up_date_to), NULL, FALSE);
        }

        $this->db->order_by('student_calls.id', 'desc');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_pending_followup_calls($filter = 'due_today_and_overdue', $assigned_to = null)
    {
        $this->db->select('student_calls.*, students.firstname, students.lastname, students.admission_no, students.father_name, students.father_phone, students.mother_phone, students.guardian_phone, students.mobileno, classes.class, sections.section, student_call_purpose.purpose as purpose_name, staff.name as staff_name, staff.surname as staff_surname, pickup_point.name as pickup_point_name, f.id as followup_id, f.due_date as next_follow_up_date, f.priority, f.assigned_to, f.remarks as followup_remarks, (SELECT CONCAT(s2.name, " ", s2.surname) FROM staff s2 WHERE s2.id = f.assigned_to) as assigned_to_name');
        $this->db->from('student_call_followups f');
        $this->db->join('student_calls', 'student_calls.id = f.student_call_id');
        $this->db->join('student_session', 'student_session.id = student_calls.student_session_id');
        $this->db->join('students', 'students.id = student_calls.student_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'student_session.section_id = sections.id');
        $this->db->join('route_pickup_point', 'student_session.route_pickup_point_id = route_pickup_point.id', 'left');
        $this->db->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left');
        $this->db->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left');
        $this->db->join('staff', 'staff.id = student_calls.created_by', 'left');
        $this->db->where('f.status', 'Pending');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');

        if ($filter == 'due_today_and_overdue') {
            $this->db->where('f.due_date <=', date('Y-m-d 23:59:59'));
        }

        if (!empty($assigned_to)) {
            $this->db->where('f.assigned_to', (int)$assigned_to);
        }

        $this->db->order_by('f.due_date', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_call($id)
    {
        $this->db->select('student_calls.*, students.firstname, students.lastname, students.admission_no, students.father_name, classes.class, sections.section, student_call_purpose.purpose as purpose_name');
        $this->db->from('student_calls');
        $this->db->join('student_session', 'student_session.id = student_calls.student_session_id');
        $this->db->join('students', 'students.id = student_calls.student_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'student_session.section_id = sections.id');
        $this->db->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left');
        $this->db->where('student_calls.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_followups_by_call($call_id)
    {
        $this->db->select('student_call_followups.*, staff.name as assigned_name, staff.surname as assigned_surname');
        $this->db->from('student_call_followups');
        $this->db->join('staff', 'staff.id = student_call_followups.assigned_to', 'left');
        $this->db->where('student_call_followups.student_call_id', $call_id);
        $this->db->order_by('student_call_followups.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_followups_by_student($student_id)
    {
        $this->db->select('student_call_followups.*, student_calls.date as call_date, student_calls.notes as call_notes, student_call_purpose.purpose as purpose_name, staff.name as assigned_name, staff.surname as assigned_surname');
        $this->db->from('student_call_followups');
        $this->db->join('student_calls', 'student_calls.id = student_call_followups.student_call_id');
        $this->db->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left');
        $this->db->join('staff', 'staff.id = student_call_followups.assigned_to', 'left');
        $this->db->where('student_call_followups.student_id', $student_id);
        $this->db->order_by('student_call_followups.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_pending_followups_by_staff($staff_id)
    {
        $this->db->select('student_call_followups.*, student_calls.phone_number, student_calls.contact_person, students.firstname, students.lastname, students.admission_no');
        $this->db->from('student_call_followups');
        $this->db->join('student_calls', 'student_calls.id = student_call_followups.student_call_id');
        $this->db->join('students', 'students.id = student_calls.student_id');
        $this->db->where('student_call_followups.assigned_to', $staff_id);
        $this->db->where('student_call_followups.status', 'Pending');
        $this->db->order_by('student_call_followups.due_date', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_total_pending_followups_count($staff_id = null)
    {
        $this->db->select('count(DISTINCT student_call_id) as total');
        $this->db->from('student_call_followups');
        $this->db->join('student_calls', 'student_calls.id = student_call_followups.student_call_id');
        $this->db->join('student_session', 'student_session.id = student_calls.student_session_id');
        $this->db->join('students', 'students.id = student_calls.student_id');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('student_call_followups.status', 'Pending');
        if ($staff_id) {
            $this->db->where('student_call_followups.assigned_to', $staff_id);
        }
        $query = $this->db->get();
        $res = $query->row_array();
        return isset($res['total']) ? intval($res['total']) : 0;
    }

    public function get_all_pending_followups_for_reminder()
    {
        $this->db->select('student_call_followups.*, student_calls.student_id, student_calls.phone_number, students.firstname, students.lastname, students.admission_no');
        $this->db->from('student_call_followups');
        $this->db->join('student_calls', 'student_calls.id = student_call_followups.student_call_id');
        $this->db->join('students', 'students.id = student_calls.student_id');
        $this->db->where('student_call_followups.status', 'Pending');
        $this->db->where('student_call_followups.assigned_to IS NOT NULL', null, false);
        $this->db->where('student_call_followups.assigned_to !=', 0);
        $this->db->where('student_call_followups.due_date <=', date('Y-m-d'));
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_call_statistics($staff_id = null)
    {
        $this->db->select('call_status, count(*) as count');
        $this->db->from('student_calls');
        if ($staff_id) {
            $this->db->where('created_by', $staff_id);
        }
        $this->db->group_by('call_status');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_followup($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('student_call_followups');
        return $query->row_array();
    }

    public function get_recent_history($student_id, $limit = 3)
    {
        $this->db->select('student_calls.date, student_calls.call_status, student_calls.notes, student_call_purpose.purpose as purpose_name');
        $this->db->from('student_calls');
        $this->db->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left');
        $this->db->where('student_calls.student_id', $student_id);
        $this->db->order_by('student_calls.date', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_calls_by_student($student_id, $student_session_id = null)
    {
        $this->db->select('student_calls.*, student_call_purpose.purpose as purpose_name, staff.name as staff_name, staff.surname as staff_surname');
        $this->db->from('student_calls');
        $this->db->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left');
        $this->db->join('staff', 'staff.id = student_calls.created_by', 'left');
        
        $this->db->group_start();
        if (!empty($student_id)) {
            $this->db->where('student_calls.student_id', $student_id);
        }
        if (!empty($student_session_id)) {
            $this->db->or_where('student_calls.student_session_id', $student_session_id);
        }
        $this->db->group_end();

        $this->db->order_by('student_calls.date', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_today_call_statistics($staff_id = null)
    {
        $today_start = date('Y-m-d 00:00:00');
        $today_end = date('Y-m-d 23:59:59');
        $this->db->select('call_status, count(*) as count');
        $this->db->from('student_calls');
        $this->db->where('date >=', $today_start);
        $this->db->where('date <=', $today_end);
        if ($staff_id) {
            $this->db->where('created_by', $staff_id);
        }
        $this->db->group_by('call_status');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_students_call_status($class_id = null, $section_id = null, $start = 0, $length = 10, $search_value = '', $admission_type = '', $shrestha = '', $rte = '', $is_staff_kid = '', $purpose_id = '', $call_status = '', $order_by = 'students.firstname', $order_dir = 'asc')
    {
        $this->db->select('students.id as student_id, student_session.id as student_session_id, students.firstname, students.lastname, students.admission_no, students.father_name, students.mobileno, students.father_phone, students.mother_phone, students.guardian_phone, students.admission_type, students.shrestha, students.rte, students.is_staff_kid, students.staff_id, student_staff.name as student_staff_name, classes.class, sections.section, sc.date as last_call_date, sc.call_status as last_call_status, pickup_point.name as pickup_point_name');
        $this->db->from('student_session');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('staff as student_staff', 'student_staff.id = students.staff_id', 'left');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'student_session.section_id = sections.id');
        $this->db->join('route_pickup_point', 'student_session.route_pickup_point_id = route_pickup_point.id', 'left');
        $this->db->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left');
        
        // Join with subquery to get the most recent call status (filtered by purpose_id if specified)
        $purpose_sub = !empty($purpose_id) ? " WHERE call_purpose_id = " . (int)$purpose_id : "";
        $this->db->join('(SELECT student_session_id, MAX(id) as max_id FROM student_calls ' . $purpose_sub . ' GROUP BY student_session_id) as latest_call', 'latest_call.student_session_id = student_session.id', 'left', false);
        $this->db->join('student_calls sc', 'sc.id = latest_call.max_id', 'left', false);

        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');

        if (!empty($class_id)) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }

        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('students.firstname', $search_value);
            $this->db->or_like('students.lastname', $search_value);
            $this->db->or_like('students.admission_no', $search_value);
            $this->db->group_end();
        }

        if (!empty($admission_type)) {
            $this->db->where("TRIM(students.admission_type)", trim($admission_type));
        }
        if ($shrestha !== '' && $shrestha !== null) {
            if ($shrestha === 'Yes' || $shrestha === '1' || $shrestha === 1) {
                $this->db->where_in('students.shrestha', ['Yes', 1, '1']);
            } elseif ($shrestha === 'No' || $shrestha === '0' || $shrestha === 0) {
                $this->db->group_start();
                $this->db->where_in('students.shrestha', ['No', 0, '0']);
                $this->db->or_where('students.shrestha IS NULL', null, false);
                $this->db->or_where('students.shrestha', '');
                $this->db->group_end();
            } else {
                $this->db->where('students.shrestha', $shrestha);
            }
        }
        if ($rte !== '' && $rte !== null) {
            if ($rte === 'Yes' || $rte === '1' || $rte === 1) {
                $this->db->where_in('students.rte', ['Yes', 1, '1']);
            } elseif ($rte === 'No' || $rte === '0' || $rte === 0) {
                $this->db->group_start();
                $this->db->where_in('students.rte', ['No', 0, '0']);
                $this->db->or_where('students.rte IS NULL', null, false);
                $this->db->or_where('students.rte', '');
                $this->db->group_end();
            } else {
                $this->db->where('students.rte', $rte);
            }
        }
        if ($is_staff_kid === '1' || $is_staff_kid === 1) {
            $this->db->where("students.is_staff_kid", '1');
        } elseif ($is_staff_kid === '0' || $is_staff_kid === 0) {
            $this->db->group_start();
            $this->db->where("students.is_staff_kid", '0');
            $this->db->or_where('students.is_staff_kid IS NULL', null, false);
            $this->db->group_end();
        }

        if (!empty($call_status)) {
            if ($call_status == 'No Call Log' || $call_status == 'Not Called') {
                $this->db->where('sc.id IS NULL', null, false);
            } else if ($call_status == 'Not Connected') {
                $this->db->group_start();
                $this->db->where('sc.id IS NULL', null, false);
                $this->db->or_where('sc.call_status !=', 'Connected');
                $this->db->group_end();
            } else {
                $this->db->where('sc.call_status', $call_status);
            }
        }

        if ($order_by && $order_dir) {
            $this->db->order_by($order_by, $order_dir);
        } else {
            $this->db->order_by('students.firstname', 'asc');
        }
        
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_students_call_status_count($class_id = null, $section_id = null, $search_value = '', $admission_type = '', $shrestha = '', $rte = '', $is_staff_kid = '', $purpose_id = '', $call_status = '')
    {
        $this->db->select('students.id');
        $this->db->from('student_session');
        $this->db->join('students', 'students.id = student_session.student_id');
        
        $purpose_sub = !empty($purpose_id) ? " WHERE call_purpose_id = " . (int)$purpose_id : "";
        $this->db->join('(SELECT student_session_id, MAX(id) as max_id FROM student_calls ' . $purpose_sub . ' GROUP BY student_session_id) as latest_call', 'latest_call.student_session_id = student_session.id', 'left', false);
        $this->db->join('student_calls sc', 'sc.id = latest_call.max_id', 'left', false);

        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');

        if (!empty($class_id)) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }

        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('students.firstname', $search_value);
            $this->db->or_like('students.lastname', $search_value);
            $this->db->or_like('students.admission_no', $search_value);
            $this->db->group_end();
        }

        if (!empty($admission_type)) {
            $this->db->where("TRIM(students.admission_type)", trim($admission_type));
        }
        if ($shrestha !== '' && $shrestha !== null) {
            if ($shrestha === 'Yes' || $shrestha === '1' || $shrestha === 1) {
                $this->db->where_in('students.shrestha', ['Yes', 1, '1']);
            } elseif ($shrestha === 'No' || $shrestha === '0' || $shrestha === 0) {
                $this->db->group_start();
                $this->db->where_in('students.shrestha', ['No', 0, '0']);
                $this->db->or_where('students.shrestha IS NULL', null, false);
                $this->db->or_where('students.shrestha', '');
                $this->db->group_end();
            } else {
                $this->db->where('students.shrestha', $shrestha);
            }
        }
        if ($rte !== '' && $rte !== null) {
            if ($rte === 'Yes' || $rte === '1' || $rte === 1) {
                $this->db->where_in('students.rte', ['Yes', 1, '1']);
            } elseif ($rte === 'No' || $rte === '0' || $rte === 0) {
                $this->db->group_start();
                $this->db->where_in('students.rte', ['No', 0, '0']);
                $this->db->or_where('students.rte IS NULL', null, false);
                $this->db->or_where('students.rte', '');
                $this->db->group_end();
            } else {
                $this->db->where('students.rte', $rte);
            }
        }
        if ($is_staff_kid === '1' || $is_staff_kid === 1) {
            $this->db->where("students.is_staff_kid", '1');
        } elseif ($is_staff_kid === '0' || $is_staff_kid === 0) {
            $this->db->group_start();
            $this->db->where("students.is_staff_kid", '0');
            $this->db->or_where('students.is_staff_kid IS NULL', null, false);
            $this->db->group_end();
        }

        if (!empty($call_status)) {
            if ($call_status == 'No Call Log' || $call_status == 'Not Called') {
                $this->db->where('sc.id IS NULL', null, false);
            } else if ($call_status == 'Not Connected') {
                $this->db->group_start();
                $this->db->where('sc.id IS NULL', null, false);
                $this->db->or_where('sc.call_status !=', 'Connected');
                $this->db->group_end();
            } else {
                $this->db->where('sc.call_status', $call_status);
            }
        }
        
        $query = $this->db->get();
        return $query->num_rows();
    }

    private static $fee_info_cache = [];

    public function get_student_fee_info($student_session_id)
    {
        if (empty($student_session_id)) {
            return null;
        }

        if (isset(self::$fee_info_cache[$student_session_id])) {
            return self::$fee_info_cache[$student_session_id];
        }

        $this->load->model('studentfeemaster_model');
        $academic_fees = $this->studentfeemaster_model->getStudentFees($student_session_id);

        $total_amount = 0;
        $collected_amount = 0;
        $latest_payment_date = null;

        if (!empty($academic_fees)) {
            foreach ($academic_fees as $fee_master) {
                if (!empty($fee_master->fees)) {
                    foreach ($fee_master->fees as $fee) {
                        $fee_amount = (isset($fee->amount)) ? (float)$fee->amount : 0;
                        $total_amount += $fee_amount;

                        $amount_detail = json_decode($fee->amount_detail, true);
                        if (!empty($amount_detail)) {
                            foreach ($amount_detail as $detail) {
                                $amt = isset($detail['amount']) ? (float)$detail['amount'] : 0;
                                $disc = isset($detail['amount_discount']) ? (float)$detail['amount_discount'] : 0;
                                $collected_amount += ($amt + $disc);

                                $pdate = !empty($detail['date']) ? $detail['date'] : '';
                                if (!empty($pdate)) {
                                    if (empty($latest_payment_date) || strtotime($pdate) > strtotime($latest_payment_date)) {
                                        $latest_payment_date = $pdate;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $student = $this->db->get_where('student_session', ['id' => $student_session_id])->row_array();
        if (!empty($student)) {
            $st_data = $this->db->get_where('students', ['id' => $student['student_id']])->row_array();
            if (!empty($st_data['route_pickup_point_id'])) {
                $transport_fees = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $st_data['route_pickup_point_id']);
                if (!empty($transport_fees)) {
                    foreach ($transport_fees as $tfee) {
                        $total_amount += (float)$tfee->fees;
                        $amount_detail = json_decode($tfee->amount_detail, true);
                        if (!empty($amount_detail)) {
                            foreach ($amount_detail as $detail) {
                                $amt = isset($detail['amount']) ? (float)$detail['amount'] : 0;
                                $disc = isset($detail['amount_discount']) ? (float)$detail['amount_discount'] : 0;
                                $collected_amount += ($amt + $disc);

                                $pdate = !empty($detail['date']) ? $detail['date'] : '';
                                if (!empty($pdate)) {
                                    if (empty($latest_payment_date) || strtotime($pdate) > strtotime($latest_payment_date)) {
                                        $latest_payment_date = $pdate;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $due_amount = max(0, $total_amount - $collected_amount);
        $is_fully_paid = ($total_amount > 0 && $due_amount <= 0);

        $today = date('Y-m-d');
        $seven_days_ago = date('Y-m-d', strtotime('-7 days'));

        $paid_today = false;
        $paid_last_7_days = false;

        if ($latest_payment_date) {
            $p_date_formatted = date('Y-m-d', strtotime($latest_payment_date));
            if ($p_date_formatted === $today) {
                $paid_today = true;
            } elseif ($p_date_formatted >= $seven_days_ago && $p_date_formatted < $today) {
                $paid_last_7_days = true;
            }
        }

        $res = [
            'total_amount' => $total_amount,
            'collected_amount' => $collected_amount,
            'due_amount' => $due_amount,
            'is_fully_paid' => $is_fully_paid,
            'latest_payment_date' => $latest_payment_date,
            'paid_today' => $paid_today,
            'paid_last_7_days' => $paid_last_7_days
        ];

        self::$fee_info_cache[$student_session_id] = $res;
        return $res;
    }
}
