<?php

namespace App\Models;

/**
 * StudentcallModel
 *
 * Model for student call logs and follow-up activities (`student_calls`, `student_call_followups`, `student_call_purpose`).
 */
class StudentcallModel extends BaseModel
{
    protected $table = 'student_calls';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'student_id', 'student_session_id', 'call_type', 'contact_person', 'phone_number',
        'call_purpose_id', 'call_status', 'date', 'duration', 'follow_up_date',
        'notes', 'created_by', 'created_at', 'updated_at'
    ];

    private static array $fee_info_cache = [];

    public function get_purposes(): array
    {
        return $this->db->table('student_call_purpose')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function add_purpose(array $data): int
    {
        $this->db->table('student_call_purpose')->insert($data);
        return (int)$this->db->insertID();
    }

    public function update_purpose(int $id, array $data): void
    {
        $this->db->table('student_call_purpose')->where('id', $id)->update($data);
    }

    public function delete_purpose(int $id): void
    {
        $this->db->table('student_call_purpose')->where('id', $id)->delete();
    }

    public function get_purpose(int $id): ?array
    {
        return $this->db->table('student_call_purpose')->where('id', $id)->get()->getRowArray();
    }

    public function resolve_previous_followups(int $call_id): void
    {
        $this->resolve_previous_followups_with_details($call_id, 'Resolved');
    }

    public function resolve_previous_followups_with_details(int $call_id, string $status = 'Resolved', ?string $call_status = null, ?string $remarks = null): void
    {
        if (!empty($call_id)) {
            $data = ['status' => !empty($status) ? $status : 'Resolved'];
            if (!empty($call_status)) {
                $data['call_status'] = $call_status;
            }
            if ($remarks !== null && $remarks !== '') {
                $data['remarks'] = $remarks;
            }
            $this->db->table('student_call_followups')
                ->where('student_call_id', $call_id)
                ->where('status', 'Pending')
                ->update($data);
        }
    }

    public function auto_resolve_zero_due_fee_followups(?int $student_session_id = null, ?int $call_id = null): void
    {
        $sessionModel = new SessionModel();
        $currentSession = $sessionModel->getCurrentSessionId();

        $builder = $this->db->table('student_call_followups f')
            ->select('f.id as followup_id, f.student_call_id, sc.student_session_id, sc.student_id, sc.call_purpose_id, p.purpose as purpose_name')
            ->join('student_calls sc', 'sc.id = f.student_call_id')
            ->join('student_session ss', 'ss.id = sc.student_session_id')
            ->join('student_call_purpose p', 'p.id = sc.call_purpose_id', 'left')
            ->where('f.status', 'Pending')
            ->where('ss.session_id', $currentSession);

        if (!empty($student_session_id)) {
            $builder->where('sc.student_session_id', $student_session_id);
        }
        if (!empty($call_id)) {
            $builder->where('sc.id', $call_id);
        }

        $builder->groupStart()
            ->like('LOWER(p.purpose)', 'fee')
            ->orWhere('sc.call_purpose_id IS NULL', null, false)
            ->orWhere('sc.call_purpose_id', 0)
            ->orWhere('sc.call_purpose_id', '')
            ->groupEnd();

        $pending = $builder->get()->getResultArray();
        if (!empty($pending)) {
            foreach ($pending as $p_item) {
                $fee_info = $this->get_student_fee_info((int)$p_item['student_session_id']);
                if (!empty($fee_info) && $fee_info['total_amount'] > 0 && $fee_info['due_amount'] <= 0) {
                    $this->db->table('student_call_followups')->where('id', $p_item['followup_id'])->update([
                        'status'      => 'Resolved',
                        'call_status' => 'Connected',
                        'remarks'     => 'Auto-Resolved: Fee balance is ₹0.00 (All fees fully paid)'
                    ]);

                    $this->db->table('student_calls')->where('id', $p_item['student_call_id'])->update([
                        'call_status' => 'Connected'
                    ]);
                }
            }
        }
    }

    public function add_call(array $data): int
    {
        if (!empty($data['student_id'])) {
            $builder = $this->db->table('student_calls')->where('student_id', $data['student_id']);
            if (!empty($data['call_purpose_id'])) {
                $builder->where('call_purpose_id', $data['call_purpose_id']);
            } else {
                $builder->groupStart()
                    ->where('call_purpose_id IS NULL', null, false)
                    ->orWhere('call_purpose_id', 0)
                    ->orWhere('call_purpose_id', '')
                    ->groupEnd();
            }
            $existing = $builder->orderBy('id', 'desc')->get()->getRowArray();
            if ($existing) {
                $this->db->table('student_calls')->where('id', $existing['id'])->update($data);
                return (int)$existing['id'];
            }
        }

        $this->db->table('student_calls')->insert($data);
        return (int)$this->db->insertID();
    }

    public function update_call(int $id, array $data): void
    {
        $this->db->table('student_calls')->where('id', $id)->update($data);
    }

    public function add_followup(array $data): int
    {
        $this->db->table('student_call_followups')->insert($data);
        return (int)$this->db->insertID();
    }

    public function update_followup(int $id, array $data): void
    {
        $this->db->table('student_call_followups')->where('id', $id)->update($data);
    }

    public function search_student(string $query): array
    {
        $sessionModel = new SessionModel();
        $currentSession = $sessionModel->getCurrentSessionId();

        return $this->db->table('student_session')
            ->select('students.id as student_id, student_session.id as student_session_id, students.admission_no, students.roll_no, students.firstname, students.middlename, students.lastname, students.father_name, students.mother_name, students.image, students.mobileno, students.father_phone, students.mother_phone, students.guardian_phone, students.admission_type, students.shrestha, students.rte, students.is_staff_kid, students.staff_id, staff.name as staff_name, classes.class, sections.section')
            ->join('students', 'students.id = student_session.student_id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'student_session.section_id = sections.id')
            ->join('staff', 'staff.id = students.staff_id', 'left')
            ->where('student_session.session_id', $currentSession)
            ->where('students.is_active', 'yes')
            ->groupStart()
                ->like('students.firstname', $query)
                ->orLike('students.lastname', $query)
                ->orLike('students.admission_no', $query)
                ->orLike('students.roll_no', $query)
            ->groupEnd()
            ->limit(20)
            ->get()
            ->getResultArray();
    }

    public function get_calls(
        $class_id = null, $section_id = null, $date_from = null, $date_to = null,
        $purpose_id = null, $status = null, $follow_up_date_from = null,
        $follow_up_date_to = null, $assigned_to = null
    ): array {
        $sessionModel = new SessionModel();
        $currentSession = $sessionModel->getCurrentSessionId();

        $builder = $this->db->table('student_calls')
            ->select('student_calls.*, students.firstname, students.lastname, students.admission_no, students.father_name, students.father_phone, students.mother_phone, students.guardian_phone, students.mobileno, students.admission_type, students.shrestha, students.rte, students.is_staff_kid, students.staff_id, student_staff.name as student_staff_name, classes.class, sections.section, student_call_purpose.purpose as purpose_name, staff.name as staff_name, staff.surname as staff_surname, pickup_point.name as pickup_point_name, (SELECT id FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1) as pending_followup_id, (SELECT due_date FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1) as next_follow_up_date, (SELECT count(id) FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending") as pending_count, (SELECT count(id) FROM student_call_followups WHERE student_call_id = student_calls.id) as total_followups, (SELECT assigned_to FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1) as pending_assigned_to_id, (SELECT CONCAT(staff.name, " ", staff.surname) FROM student_call_followups JOIN staff ON staff.id = student_call_followups.assigned_to WHERE student_call_id = student_calls.id AND student_call_followups.status = "Pending" ORDER BY student_call_followups.id DESC LIMIT 1) as assigned_to_name')
            ->join('student_session', 'student_session.id = student_calls.student_session_id')
            ->join('students', 'students.id = student_calls.student_id')
            ->join('staff as student_staff', 'student_staff.id = students.staff_id', 'left')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'student_session.section_id = sections.id')
            ->join('route_pickup_point', 'student_session.route_pickup_point_id = route_pickup_point.id', 'left')
            ->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left')
            ->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left')
            ->join('staff', 'staff.id = student_calls.created_by', 'left')
            ->where('student_session.session_id', $currentSession)
            ->where('students.is_active', 'yes');

        if (!empty($class_id)) {
            $builder->where('student_session.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $builder->where('student_session.section_id', $section_id);
        }
        if (!empty($purpose_id)) {
            $builder->where('student_calls.call_purpose_id', $purpose_id);
        }
        if (!empty($status)) {
            $builder->where('student_calls.call_status', $status);
        }
        if (!empty($date_from) && !empty($date_to)) {
            $builder->where('DATE(student_calls.date) >=', $date_from);
            $builder->where('DATE(student_calls.date) <=', $date_to);
        } elseif (!empty($date_from)) {
            $builder->where('DATE(student_calls.date) >=', $date_from);
        } elseif (!empty($date_to)) {
            $builder->where('DATE(student_calls.date) <=', $date_to);
        }

        if (!empty($assigned_to)) {
            $builder->where('EXISTS (SELECT 1 FROM student_call_followups WHERE student_call_followups.student_call_id = student_calls.id AND student_call_followups.assigned_to = ' . (int)$assigned_to . ')', null, false);
        }
        if (!empty($follow_up_date_from) && !empty($follow_up_date_to)) {
            $builder->where('DATE((SELECT due_date FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1)) >= ' . $this->db->escape($follow_up_date_from), null, false);
            $builder->where('DATE((SELECT due_date FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1)) <= ' . $this->db->escape($follow_up_date_to), null, false);
        } elseif (!empty($follow_up_date_from)) {
            $builder->where('DATE((SELECT due_date FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1)) >= ' . $this->db->escape($follow_up_date_from), null, false);
        } elseif (!empty($follow_up_date_to)) {
            $builder->where('DATE((SELECT due_date FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id DESC LIMIT 1)) <= ' . $this->db->escape($follow_up_date_to), null, false);
        }

        return $builder->orderBy('student_calls.id', 'desc')->get()->getResultArray();
    }

    public function get_pending_followup_calls(string $filter = 'due_today_and_overdue', $assigned_to = null): array
    {
        $sessionModel = new SessionModel();
        $currentSession = $sessionModel->getCurrentSessionId();

        $builder = $this->db->table('student_call_followups f')
            ->select('student_calls.*, students.firstname, students.lastname, students.admission_no, students.father_name, students.father_phone, students.mother_phone, students.guardian_phone, students.mobileno, classes.class, sections.section, student_call_purpose.purpose as purpose_name, staff.name as staff_name, staff.surname as staff_surname, pickup_point.name as pickup_point_name, f.id as followup_id, f.due_date as next_follow_up_date, f.priority, f.assigned_to, f.remarks as followup_remarks, (SELECT CONCAT(s2.name, " ", s2.surname) FROM staff s2 WHERE s2.id = f.assigned_to) as assigned_to_name')
            ->join('student_calls', 'student_calls.id = f.student_call_id')
            ->join('student_session', 'student_session.id = student_calls.student_session_id')
            ->join('students', 'students.id = student_calls.student_id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'student_session.section_id = sections.id')
            ->join('route_pickup_point', 'student_session.route_pickup_point_id = route_pickup_point.id', 'left')
            ->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left')
            ->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left')
            ->join('staff', 'staff.id = student_calls.created_by', 'left')
            ->where('f.status', 'Pending')
            ->where('student_session.session_id', $currentSession)
            ->where('students.is_active', 'yes');

        if ($filter === 'due_today_and_overdue') {
            $builder->where('f.due_date <=', date('Y-m-d 23:59:59'));
        }

        if (!empty($assigned_to)) {
            $builder->where('f.assigned_to', (int)$assigned_to);
        }

        return $builder->orderBy('f.due_date', 'asc')->get()->getResultArray();
    }

    public function get_call(int $id): ?array
    {
        return $this->db->table('student_calls')
            ->select('student_calls.*, students.firstname, students.lastname, students.admission_no, students.father_name, classes.class, sections.section, student_call_purpose.purpose as purpose_name')
            ->join('student_session', 'student_session.id = student_calls.student_session_id')
            ->join('students', 'students.id = student_calls.student_id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'student_session.section_id = sections.id')
            ->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left')
            ->where('student_calls.id', $id)
            ->get()
            ->getRowArray();
    }

    public function get_followups_by_call(int $call_id): array
    {
        return $this->db->table('student_call_followups')
            ->select('student_call_followups.*, staff.name as assigned_name, staff.surname as assigned_surname')
            ->join('staff', 'staff.id = student_call_followups.assigned_to', 'left')
            ->where('student_call_followups.student_call_id', $call_id)
            ->orderBy('student_call_followups.id', 'asc')
            ->get()
            ->getResultArray();
    }

    public function get_followup(int $id): ?array
    {
        return $this->db->table('student_call_followups')
            ->where('id', $id)
            ->get()
            ->getRowArray();
    }

    public function get_recent_history(int $student_id, int $limit = 3): array
    {
        return $this->db->table('student_calls')
            ->select('student_calls.date, student_calls.call_status, student_calls.notes, student_call_purpose.purpose as purpose_name')
            ->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left')
            ->where('student_calls.student_id', $student_id)
            ->orderBy('student_calls.date', 'desc')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function get_today_call_statistics(?int $staffId = null): array
    {
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd   = date('Y-m-d 23:59:59');

        $builder = $this->db->table('student_calls')
            ->select('call_status, count(*) as count')
            ->where('date >=', $todayStart)
            ->where('date <=', $todayEnd);

        if ($staffId !== null) {
            $builder->where('created_by', $staffId);
        }

        return $builder->groupBy('call_status')->get()->getResultArray();
    }

    public function get_total_pending_followups_count(?int $staffId = null): int
    {
        $sessionModel = new SessionModel();
        $currentSession = $sessionModel->getCurrentSessionId();

        $builder = $this->db->table('student_call_followups')
            ->select('count(DISTINCT student_call_id) as total')
            ->join('student_calls', 'student_calls.id = student_call_followups.student_call_id')
            ->join('student_session', 'student_session.id = student_calls.student_session_id')
            ->join('students', 'students.id = student_calls.student_id')
            ->where('student_session.session_id', $currentSession)
            ->where('students.is_active', 'yes')
            ->where('student_call_followups.status', 'Pending');

        if ($staffId !== null) {
            $builder->where('student_call_followups.assigned_to', $staffId);
        }

        $row = $builder->get()->getRowArray();
        return isset($row['total']) ? (int)$row['total'] : 0;
    }

    public function get_students_call_status(
        $class_id = null, $section_id = null, int $start = 0, int $length = 10,
        string $search_value = '', $admission_type = '', $shrestha = '', $rte = '',
        $is_staff_kid = '', $purpose_id = '', $call_status = '',
        string $order_by = 'students.firstname', string $order_dir = 'asc'
    ): array {
        $sessionModel = new SessionModel();
        $currentSession = $sessionModel->getCurrentSessionId();

        $builder = $this->db->table('student_session')
            ->select('students.id as student_id, student_session.id as student_session_id, students.firstname, students.lastname, students.admission_no, students.father_name, students.mobileno, students.father_phone, students.mother_phone, students.guardian_phone, students.admission_type, students.shrestha, students.rte, students.is_staff_kid, students.staff_id, student_staff.name as student_staff_name, classes.class, sections.section, sc.date as last_call_date, sc.call_status as last_call_status, pickup_point.name as pickup_point_name')
            ->join('students', 'students.id = student_session.student_id')
            ->join('staff as student_staff', 'student_staff.id = students.staff_id', 'left')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'student_session.section_id = sections.id')
            ->join('route_pickup_point', 'student_session.route_pickup_point_id = route_pickup_point.id', 'left')
            ->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left');

        $purpose_sub = !empty($purpose_id) ? " WHERE call_purpose_id = " . (int)$purpose_id : "";
        $builder->join('(SELECT student_session_id, MAX(id) as max_id FROM student_calls ' . $purpose_sub . ' GROUP BY student_session_id) as latest_call', 'latest_call.student_session_id = student_session.id', 'left', false);
        $builder->join('student_calls sc', 'sc.id = latest_call.max_id', 'left', false);

        $builder->where('student_session.session_id', $currentSession)
            ->where('students.is_active', 'yes');

        if (!empty($class_id)) {
            $builder->where('student_session.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $builder->where('student_session.section_id', $section_id);
        }

        if (!empty($search_value)) {
            $builder->groupStart()
                ->like('students.firstname', $search_value)
                ->orLike('students.lastname', $search_value)
                ->orLike('students.admission_no', $search_value)
                ->groupEnd();
        }

        if (!empty($admission_type)) {
            $builder->where("TRIM(students.admission_type)", trim((string)$admission_type));
        }
        if ($shrestha !== '' && $shrestha !== null) {
            if ($shrestha === 'Yes' || $shrestha === '1' || $shrestha === 1) {
                $builder->whereIn('students.shrestha', ['Yes', 1, '1']);
            } elseif ($shrestha === 'No' || $shrestha === '0' || $shrestha === 0) {
                $builder->groupStart()
                    ->whereIn('students.shrestha', ['No', 0, '0'])
                    ->orWhere('students.shrestha IS NULL', null, false)
                    ->orWhere('students.shrestha', '')
                    ->groupEnd();
            } else {
                $builder->where('students.shrestha', $shrestha);
            }
        }
        if ($rte !== '' && $rte !== null) {
            if ($rte === 'Yes' || $rte === '1' || $rte === 1) {
                $builder->whereIn('students.rte', ['Yes', 1, '1']);
            } elseif ($rte === 'No' || $rte === '0' || $rte === 0) {
                $builder->groupStart()
                    ->whereIn('students.rte', ['No', 0, '0'])
                    ->orWhere('students.rte IS NULL', null, false)
                    ->orWhere('students.rte', '')
                    ->groupEnd();
            } else {
                $builder->where('students.rte', $rte);
            }
        }
        if ($is_staff_kid === '1' || $is_staff_kid === 1) {
            $builder->where("students.is_staff_kid", '1');
        } elseif ($is_staff_kid === '0' || $is_staff_kid === 0) {
            $builder->groupStart()
                ->where("students.is_staff_kid", '0')
                ->orWhere('students.is_staff_kid IS NULL', null, false)
                ->groupEnd();
        }

        if (!empty($call_status)) {
            if ($call_status === 'No Call Log' || $call_status === 'Not Called') {
                $builder->where('sc.id IS NULL', null, false);
            } elseif ($call_status === 'Not Connected') {
                $builder->groupStart()
                    ->where('sc.id IS NULL', null, false)
                    ->orWhere('sc.call_status !=', 'Connected')
                    ->groupEnd();
            } else {
                $builder->where('sc.call_status', $call_status);
            }
        }

        if ($order_by && $order_dir) {
            $builder->orderBy($order_by, $order_dir);
        } else {
            $builder->orderBy('students.firstname', 'asc');
        }

        if ($length != -1) {
            $builder->limit($length, $start);
        }

        return $builder->get()->getResultArray();
    }

    public function get_students_call_status_count(
        $class_id = null, $section_id = null, string $search_value = '',
        $admission_type = '', $shrestha = '', $rte = '', $is_staff_kid = '',
        $purpose_id = '', $call_status = ''
    ): int {
        $sessionModel = new SessionModel();
        $currentSession = $sessionModel->getCurrentSessionId();

        $builder = $this->db->table('student_session')
            ->select('students.id')
            ->join('students', 'students.id = student_session.student_id');

        $purpose_sub = !empty($purpose_id) ? " WHERE call_purpose_id = " . (int)$purpose_id : "";
        $builder->join('(SELECT student_session_id, MAX(id) as max_id FROM student_calls ' . $purpose_sub . ' GROUP BY student_session_id) as latest_call', 'latest_call.student_session_id = student_session.id', 'left', false);
        $builder->join('student_calls sc', 'sc.id = latest_call.max_id', 'left', false);

        $builder->where('student_session.session_id', $currentSession)
            ->where('students.is_active', 'yes');

        if (!empty($class_id)) {
            $builder->where('student_session.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $builder->where('student_session.section_id', $section_id);
        }

        if (!empty($search_value)) {
            $builder->groupStart()
                ->like('students.firstname', $search_value)
                ->orLike('students.lastname', $search_value)
                ->orLike('students.admission_no', $search_value)
                ->groupEnd();
        }

        if (!empty($admission_type)) {
            $builder->where("TRIM(students.admission_type)", trim((string)$admission_type));
        }
        if ($shrestha !== '' && $shrestha !== null) {
            if ($shrestha === 'Yes' || $shrestha === '1' || $shrestha === 1) {
                $builder->whereIn('students.shrestha', ['Yes', 1, '1']);
            } elseif ($shrestha === 'No' || $shrestha === '0' || $shrestha === 0) {
                $builder->groupStart()
                    ->whereIn('students.shrestha', ['No', 0, '0'])
                    ->orWhere('students.shrestha IS NULL', null, false)
                    ->orWhere('students.shrestha', '')
                    ->groupEnd();
            } else {
                $builder->where('students.shrestha', $shrestha);
            }
        }
        if ($rte !== '' && $rte !== null) {
            if ($rte === 'Yes' || $rte === '1' || $rte === 1) {
                $builder->whereIn('students.rte', ['Yes', 1, '1']);
            } elseif ($rte === 'No' || $rte === '0' || $rte === 0) {
                $builder->groupStart()
                    ->whereIn('students.rte', ['No', 0, '0'])
                    ->orWhere('students.rte IS NULL', null, false)
                    ->orWhere('students.rte', '')
                    ->groupEnd();
            } else {
                $builder->where('students.rte', $rte);
            }
        }
        if ($is_staff_kid === '1' || $is_staff_kid === 1) {
            $builder->where("students.is_staff_kid", '1');
        } elseif ($is_staff_kid === '0' || $is_staff_kid === 0) {
            $builder->groupStart()
                ->where("students.is_staff_kid", '0')
                ->orWhere('students.is_staff_kid IS NULL', null, false)
                ->groupEnd();
        }

        if (!empty($call_status)) {
            if ($call_status === 'No Call Log' || $call_status === 'Not Called') {
                $builder->where('sc.id IS NULL', null, false);
            } elseif ($call_status === 'Not Connected') {
                $builder->groupStart()
                    ->where('sc.id IS NULL', null, false)
                    ->orWhere('sc.call_status !=', 'Connected')
                    ->groupEnd();
            } else {
                $builder->where('sc.call_status', $call_status);
            }
        }

        return $builder->countAllResults();
    }

    public function get_student_fee_info(?int $student_session_id): ?array
    {
        if (empty($student_session_id)) {
            return null;
        }

        if (isset(self::$fee_info_cache[$student_session_id])) {
            return self::$fee_info_cache[$student_session_id];
        }

        // Calculate academic & transport fees directly via DB
        $fees = $this->db->table('student_fees_master')
            ->select('student_fees_master.id, fee_groups_feetype.amount, student_fees_deposite.amount_detail')
            ->join('fee_session_groups', 'fee_session_groups.id = student_fees_master.fee_session_group_id')
            ->join('fee_groups_feetype', 'fee_groups_feetype.fee_session_group_id = fee_session_groups.id')
            ->join('student_fees_deposite', 'student_fees_deposite.student_fees_master_id = student_fees_master.id AND student_fees_deposite.fee_groups_feetype_id = fee_groups_feetype.id', 'left')
            ->where('student_fees_master.student_session_id', $student_session_id)
            ->get()
            ->getResultArray();

        $total_amount = 0;
        $collected_amount = 0;
        $latest_payment_date = null;

        if (!empty($fees)) {
            foreach ($fees as $fee) {
                $total_amount += (float)($fee['amount'] ?? 0);
                if (!empty($fee['amount_detail'])) {
                    $details = json_decode($fee['amount_detail'], true);
                    if (!empty($details)) {
                        foreach ($details as $detail) {
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
            'total_amount'        => $total_amount,
            'collected_amount'    => $collected_amount,
            'due_amount'          => $due_amount,
            'is_fully_paid'       => $is_fully_paid,
            'latest_payment_date' => $latest_payment_date,
            'paid_today'          => $paid_today,
            'paid_last_7_days'    => $paid_last_7_days
        ];

        self::$fee_info_cache[$student_session_id] = $res;
        return $res;
    }

    public function get_due_reminders_for_staff(int $staff_id, int $limit = 100): array
    {
        $today_end = date('Y-m-d 23:59:59');

        $builder = $this->db->table('student_call_followups f')
            ->select('f.id as followup_id, f.student_call_id, f.due_date, f.priority, f.remarks as previous_remarks, f.created_at as followup_created_at, sc.student_id, sc.student_session_id, sc.contact_person, sc.call_type, sc.phone_number, sc.call_status, sc.notes as initial_notes, s.firstname, s.middlename, s.lastname, s.admission_no, s.roll_no, s.father_name, s.father_phone, s.mother_name, s.mother_phone, s.guardian_name, s.guardian_phone, s.mobileno, s.image, c.class, sec.section, p.purpose as purpose_name, st.name as assigned_staff_name, st.surname as assigned_staff_surname')
            ->join('student_calls sc', 'sc.id = f.student_call_id')
            ->join('students s', 's.id = sc.student_id')
            ->join('student_session ss', 'ss.id = sc.student_session_id', 'left')
            ->join('classes c', 'c.id = ss.class_id', 'left')
            ->join('sections sec', 'sec.id = ss.section_id', 'left')
            ->join('student_call_purpose p', 'p.id = sc.call_purpose_id', 'left')
            ->join('staff st', 'st.id = f.assigned_to', 'left')
            ->where('f.status', 'Pending')
            ->where('s.is_active', 'yes')
            ->where('f.due_date <=', $today_end);

        if (!empty($staff_id)) {
            $builder->groupStart()
                ->where('f.assigned_to', (int)$staff_id)
                ->orWhere('f.assigned_to IS NULL', null, false)
                ->orWhere('f.assigned_to', 0)
                ->orWhere('f.created_by', (int)$staff_id)
                ->groupEnd();
        }

        $builder->orderBy("CASE WHEN f.priority = 'High' THEN 1 WHEN f.priority = 'Medium' THEN 2 ELSE 3 END", "ASC", false);
        $builder->orderBy('f.due_date', 'ASC');
        $builder->limit($limit);

        return $builder->get()->getResultArray();
    }
}
