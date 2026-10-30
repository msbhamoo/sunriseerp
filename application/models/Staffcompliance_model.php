<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Staffcompliance_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get staff list with their attendance and compliance checklist for a specific role and date
     *
     * @param string $user_type Role name or 'select' for all
     * @param string $date Date in Y-m-d format
     * @return array
     */
    public function getStaffComplianceList($user_type, $date)
    {
        $condition = '';
        if ($this->session->has_userdata('admin')) {
            $getStaffRole       = $this->customlib->getStaffRole();
            $staffrole          = json_decode($getStaffRole);
            $superadmin_visible = $this->customlib->superadmin_visible();
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {
                $condition = " AND roles.id != 7";
            }
        }

        $compliance_cols = '';
        foreach (['uniform_status', 'id_card_status', 'lesson_plan_status', 'phone_handover_status'] as $ccol) {
            if ($this->db->field_exists($ccol, 'staff_attendance')) {
                $compliance_cols .= ", staff_attendance.$ccol";
            } else {
                $compliance_cols .= ", NULL as $ccol";
            }
        }

        $role_filter = "";
        if ($user_type != "select" && !empty($user_type)) {
            $role_filter = " AND roles.name = " . $this->db->escape($user_type);
        }

        $sql = "SELECT 
                    staff.id as staff_id,
                    staff.name,
                    staff.surname,
                    staff.employee_id,
                    staff.contact_no,
                    staff.email,
                    roles.name as role_name,
                    roles.id as role_id,
                    IFNULL(staff_attendance.id, 0) as attendance_id,
                    IFNULL(staff_attendance.date, 'xxx') as attendance_date,
                    staff_attendance.staff_attendance_type_id,
                    staff_attendance_type.type as att_type,
                    staff_attendance_type.key_value as att_key,
                    staff_attendance.in_time,
                    staff_attendance.out_time,
                    staff_attendance.remark
                    $compliance_cols
                FROM staff
                LEFT JOIN staff_roles ON staff_roles.staff_id = staff.id
                LEFT JOIN roles ON roles.id = staff_roles.role_id
                LEFT JOIN staff_attendance ON (staff.id = staff_attendance.staff_id AND staff_attendance.date = " . $this->db->escape($date) . ")
                LEFT JOIN staff_attendance_type ON staff_attendance_type.id = staff_attendance.staff_attendance_type_id
                WHERE staff.is_active = 1 $role_filter $condition
                ORDER BY roles.name ASC, staff.name ASC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * Save/update compliance checklist statuses for staff on a specific date.
     * Preserves attendance type, times, and other fields if attendance row already exists.
     * If row doesn't exist, creates one with default 'unmarked' or present.
     *
     * @param array $compliance_data Array of compliance items: [staff_id, date, uniform_status, id_card_status, lesson_plan_status, phone_handover_status, remark]
     * @return bool
     */
    public function saveCompliance($compliance_data)
    {
        if (empty($compliance_data)) {
            return false;
        }

        $this->db->trans_start();
        $this->db->trans_strict(false);

        foreach ($compliance_data as $row) {
            $staff_id = $row['staff_id'];
            $date     = $row['date'];

            $this->db->where('staff_id', $staff_id);
            $this->db->where('date', $date);
            $query = $this->db->get('staff_attendance');

            $data_to_save = [
                'uniform_status'        => isset($row['uniform_status']) ? $row['uniform_status'] : 'no',
                'id_card_status'        => isset($row['id_card_status']) ? $row['id_card_status'] : 'no',
                'lesson_plan_status'    => isset($row['lesson_plan_status']) ? $row['lesson_plan_status'] : 'no',
                'phone_handover_status' => isset($row['phone_handover_status']) ? $row['phone_handover_status'] : 'no',
                'updated_at'            => date('Y-m-d H:i:s'),
            ];

            if (!empty($row['remark'])) {
                $data_to_save['remark'] = $row['remark'];
            }

            if ($query->num_rows() > 0) {
                // Update existing record
                $existing = $query->row();
                $this->db->where('id', $existing->id);
                $this->db->update('staff_attendance', $data_to_save);
            } else {
                // Insert new placeholder record
                $data_to_save['staff_id']                 = $staff_id;
                $data_to_save['date']                     = $date;
                $data_to_save['staff_attendance_type_id'] = 1; // Default to Present or Unmarked
                $data_to_save['is_active']                 = 1;
                $data_to_save['created_at']               = date('Y-m-d H:i:s');
                if (!isset($data_to_save['remark'])) {
                    $data_to_save['remark'] = '';
                }
                $this->db->insert('staff_attendance', $data_to_save);
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Get monthly compliance grid data for all days in a month.
     *
     * @param string $role_name
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getMonthlyComplianceSheet($role_name, $year, $month)
    {
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end   = date('Y-m-t', strtotime($start));

        $role_cond = '';
        if (!empty($role_name) && $role_name !== 'select') {
            $role_cond = " AND roles.name = " . $this->db->escape($role_name);
        }

        $condition = '';
        if ($this->session->has_userdata('admin')) {
            $getStaffRole       = $this->customlib->getStaffRole();
            $staffrole          = json_decode($getStaffRole);
            $superadmin_visible = $this->customlib->superadmin_visible();
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {
                $condition = " AND roles.id != 7";
            }
        }

        $sql = "SELECT staff.id AS staff_id, staff.name, staff.surname, staff.employee_id,
                       roles.id AS role_id, roles.name AS role_name,
                       sa.date,
                       sa.uniform_status,
                       sa.id_card_status,
                       sa.lesson_plan_status,
                       sa.phone_handover_status,
                       sa.remark,
                       sat.type AS att_type,
                       COALESCE(NULLIF(sat.long_lang_name, ''), LOWER(TRIM(sat.key_value))) AS att_status
                FROM staff
                LEFT JOIN staff_roles ON staff_roles.staff_id = staff.id
                LEFT JOIN roles ON roles.id = staff_roles.role_id
                LEFT JOIN staff_attendance sa ON sa.staff_id = staff.id AND sa.date BETWEEN " . $this->db->escape($start) . " AND " . $this->db->escape($end) . "
                LEFT JOIN staff_attendance_type sat ON sat.id = sa.staff_attendance_type_id
                WHERE staff.is_active = 1" . $role_cond . $condition . "
                ORDER BY roles.name ASC, staff.name ASC, staff.surname ASC";

        $rows = $this->db->query($sql)->result();

        $staff = [];
        $map   = []; // [staff_id][Y-m-d] => array of compliance statuses

        foreach ($rows as $r) {
            if (!isset($staff[$r->staff_id])) {
                $staff[$r->staff_id] = (object)[
                    'staff_id'    => (int)$r->staff_id,
                    'name'        => $r->name,
                    'surname'     => $r->surname,
                    'employee_id' => $r->employee_id,
                    'role_name'   => $r->role_name,
                ];
                $map[$r->staff_id] = [];
            }

            if (!empty($r->date)) {
                $is_fully_compliant = (
                    $r->uniform_status === 'yes' &&
                    $r->id_card_status === 'yes' &&
                    $r->lesson_plan_status === 'yes' &&
                    $r->phone_handover_status === 'yes'
                );

                $map[$r->staff_id][$r->date] = [
                    'uniform'        => $r->uniform_status,
                    'id_card'        => $r->id_card_status,
                    'lesson_plan'    => $r->lesson_plan_status,
                    'phone_handover' => $r->phone_handover_status,
                    'is_compliant'   => $is_fully_compliant,
                    'att_status'     => $r->att_status,
                    'remark'         => $r->remark,
                ];
            }
        }

        return [
            'staff' => array_values($staff),
            'map'   => $map,
        ];
    }
}
