<?php

class Staff_model extends MY_Model
{
    protected $superadmin_visible;
    protected $staffrole;

    public function __construct()
    {



        parent::__construct();
        $this->load->library('customlib');
        $this->superadmin_visible = $this->customlib->superadmin_visible();
        $getStaffRole     = $this->customlib->getStaffRole();
        if (isset($getStaffRole)) {
            $this->staffrole   =   json_decode($getStaffRole);
        }
    }

    public function get($id = null)
    {
        $this->db->select('staff.*,languages.language,languages.is_rtl,languages.short_code,roles.name as user_type,roles.id as role_id')
            ->from('staff')
            ->join("staff_roles", "staff_roles.staff_id = staff.id", "left")
            ->join("roles", "staff_roles.role_id = roles.id", "left")
            ->join("languages", "languages.id = staff.lang_id", "left");

        if ($this->session->has_userdata('admin')) {
            if ($this->staffrole->id != 7) {
                if ($this->superadmin_visible == 'disabled') {
                    $this->db->where("roles.id !=", 7);
                }
            }
        }

        if ($id != null) {
            $this->db->where('staff.id', $id);
        } else {
            $this->db->where('staff.is_active', 1);
            $this->db->order_by('staff.id');
        }

        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getrat()
    {
        $this->db->select('staff.id,staff.employee_id,CONCAT_WS(" ",staff.name,staff.surname,"(",staff.employee_id,")") as name,roles.name as user_type,roles.id as role_id,staff_rating.rate,staff_rating.status,staff_rating.comment,staff_rating.id as rate_id,CONCAT_WS(" ",students.firstname,students.middlename,students.lastname,"(",students.admission_no,")") as student_name')->from('staff')->join("staff_roles", "staff_roles.staff_id = staff.id", "left")->join("roles", "staff_roles.role_id = roles.id", "left")->join("staff_rating", "staff_rating.staff_id = staff.id", "inner")->join("users", "users.id=staff_rating.user_id", "left")->join("students", "students.id=users.user_id", "left");
        $this->db->where('staff.is_active', 1);
        $this->db->where_not_in('roles.id', 7);
        $this->db->order_by('staff.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStaffByName($fullname)
    {
        $this->db->select('id');
        $this->db->from('staff');
        $this->db->where('TRIM(CONCAT(name, " ", surname)) =', $fullname);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        }
        return false;
    }

    public function getTodayDayAttendance()
    {
        $date = date('Y-m-d');
        $this->db->select('staff_id');
        $this->db->from("staff_attendance");
        $this->db->where('date = ', $date);
        $this->db->where("(staff_attendance_type_id='1' OR staff_attendance_type_id='2' OR staff_attendance_type_id='4')");
        $this->db->group_by('staff_attendance.staff_id');
        $query = $this->db->get();
        $q     = $query->result_array();
        return count($q);
    }

    public function getTotalStaff()
    {
        $this->db->select('staff.id');
        $this->db->from("staff");
        $this->db->where("staff.is_active", 1);
        $query = $this->db->get();
        $q = $query->result_array();
        return count($q);
    }

    public function user_reviewlist($id)
    {
        $this->db->select('staff_rating.rate,staff_rating.comment,staff_rating.role,students.firstname as firstname,students.middlename, students.lastname as lastname,students.guardian_name')->from('staff_rating')->join("users", "users.id = staff_rating.user_id", "inner")->join("staff", "staff_rating.staff_id = staff.id", "inner")->join("students", "students.id = staff_rating.user_id", "left");
        $this->db->where('staff.is_active', 1);
        $this->db->where('staff_rating.staff_id', $id);
        $this->db->where('staff_rating.status', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAll($id = null, $is_active = null)
    {
        $this->db->select("staff.*,staff_designation.designation,department.department_name as department, roles.id as role_id, roles.name as role");
        $this->db->from('staff');
        $this->db->join('staff_designation', "staff_designation.id = staff.designation", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('roles', "roles.id = staff_roles.role_id", "left");
        $this->db->join('department', "department.id = staff.department", "left");

        if ($id != null) {
            $this->db->where('staff.id', $id);
        } else {
            if ($is_active != null) {
                $this->db->where('staff.is_active', $is_active);
            }
            $this->db->order_by('staff.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getAll_users($id = null, $is_active = null)
    {
        $this->db->select("staff.*,staff_designation.designation,department.department_name as department, roles.id as role_id, roles.name as role");
        $this->db->from('staff');
        $this->db->join('staff_designation', "staff_designation.id = staff.designation", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('roles', "roles.id = staff_roles.role_id", "left");
        $this->db->join('department', "department.id = staff.department", "left");
        if ($id != null) {
            $this->db->where('staff.id', $id);
        } else {
            if ($is_active != null) {
                $this->db->where('staff.is_active', $is_active);
            }
            $this->db->where('roles.id!=', 7);
            $this->db->order_by('staff.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getBirthDayStaff($dob, $is_active = 1, $email = false, $contact_no = false)
    {
        $this->db->select("staff.*,staff_designation.designation,department.department_name as department, roles.id as role_id, roles.name as role");
        $this->db->from('staff');
        $this->db->join('staff_designation', "staff_designation.id = staff.designation", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('roles', "roles.id = staff_roles.role_id", "left");
        $this->db->join('department', "department.id = staff.department", "left");
        $this->db->where('staff.is_active', $is_active);
        $this->db->where("DATE_FORMAT(staff.dob,'%m-%d') = DATE_FORMAT('" . $dob . "','%m-%d')");
        if ($email) {
            $this->db->where('staff.email !=', "");
        }
        if ($contact_no) {
            $this->db->where('staff.contact_no !=', "");
        }
        $this->db->order_by('staff.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On staff id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('staff', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On staff id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $id;
        }
    }

    public function update($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $data['id']);
        $query     = $this->db->update('staff', $data);
        $message   = UPDATE_RECORD_CONSTANT . " On staff id " . $data['id'];
        $action    = "Update";
        $record_id = $data['id'];
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function getByVerificationCode($ver_code)
    {
        $condition = "verification_code =" . "'" . $ver_code . "'";
        $this->db->select('*');
        $this->db->from('staff');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function batchInsert($data, $roles = array(), $leave_array = array(), $data_setting = array())
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $this->db->insert('staff', $data);
        $staff_id          = $this->db->insert_id();
        $roles['staff_id'] = $staff_id;
        $this->db->insert_batch('staff_roles', array($roles));
        if (!empty($data_setting)) {
            if ($data_setting['staffid_auto_insert']) {
                if ($data_setting['staffid_update_status'] == 0) {
                    $data_setting['staffid_update_status'] = 1;
                    $this->setting_model->add($data_setting);
                }
            }
        }

        $session = $this->setting_model->getCurrentSession();

        if (!empty($leave_array)) {
            foreach ($leave_array as $key => $value) {
                $leave_array[$key]['staff_id'] = $staff_id;
                $leave_array[$key]['session_id'] = $session;
            }
            $this->db->insert_batch('staff_leave_details', $leave_array);
        }


        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $staff_id;
        }
    }

    public function adddoc($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_documents', $data);
        } else {
            $this->db->insert('staff_documents', $data);
            return $this->db->insert_id();
        }
    }

    public function remove($id)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $sql   = "DELETE FROM custom_field_values WHERE id IN (select * from (SELECT t2.id as `id` FROM `custom_fields` INNER JOIN custom_field_values as t2 on t2.custom_field_id=custom_fields.id WHERE custom_fields.belong_to='staff' and t2.belong_table_id IN (" . $id . ")) as m2)";
        $query = $this->db->query($sql);
        $this->db->where('id', $id);
        $this->db->delete('staff');
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $staff_id;
        }
    }

    public function add_staff_leave_details($data2)
    {
        $staff_alloted_session = $this->getLeaveDetails($data2['staff_id']);
        $session = $this->setting_model->getCurrentSession();

        if (isset($data2['id'])) {
            $this->db->where('id', $data2['id']);
            $this->db->where('session_id', $session);
            $this->db->update('staff_leave_details', $data2);
        } else {
            $this->db->insert('staff_leave_details', $data2);
            return $this->db->insert_id();
        }
    }

    public function update_bulk_staff_leave_details($staff_ids, $leave_data)
    {
        if (empty($staff_ids) || empty($leave_data)) {
            return false;
        }

        $session = $this->setting_model->getCurrentSession();
        $this->db->trans_start();

        foreach ($staff_ids as $staff_id) {
            foreach ($leave_data as $leave_type_id => $alloted_leave) {
                if ($alloted_leave === '' || $alloted_leave === null) {
                    continue;
                }

                $query = $this->db->where(array(
                    'staff_id'      => $staff_id,
                    'leave_type_id' => $leave_type_id,
                    'session_id'    => $session,
                ))->get('staff_leave_details');

                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $this->db->where('id', $row->id);
                    $this->db->update('staff_leave_details', array('alloted_leave' => $alloted_leave));
                } else {
                    $this->db->insert('staff_leave_details', array(
                        'staff_id'      => $staff_id,
                        'leave_type_id' => $leave_type_id,
                        'alloted_leave' => $alloted_leave,
                        'session_id'    => $session,
                    ));
                }
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }


    public function getPayroll($id = null)
    {
        $this->db->select()->from('staff_payroll');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getLeaveType($id = null)
    {
        $this->db->select()->from('leave_types');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('is_active', 'yes');
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function valid_employee_id($str)
    {
        $name     = $this->input->post('name');
        $id       = $this->input->post('employee_id');
        $staff_id = $this->input->post('editid');

        if ((!isset($id))) {
            $id = 0;
        }
        if (!isset($staff_id)) {
            $staff_id = 0;
        }

        if ($this->check_data_exists($name, $id, $staff_id)) {
            $this->form_validation->set_message('username_check', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_data_exists($name, $id, $staff_id)
    {
        if ($staff_id != 0) {
            $data  = array('id != ' => $staff_id, 'employee_id' => $id);
            $query = $this->db->where($data)->get('staff');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('employee_id', $id);
            $query = $this->db->get('staff');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function import_check_data_exists($name, $id)
    {
        $this->db->where('employee_id', $id);
        $query = $this->db->get('staff');
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function import_check_email_exists($name, $email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('staff');
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function valid_email_id($str)
    {
        $email    = $this->input->post('email');
        $id       = $this->input->post('employee_id');
        $staff_id = $this->input->post('editid');

        if (!isset($id)) {
            $id = 0;
        }

        if (!isset($staff_id)) {
            $staff_id = 0;
        }

        if ($this->check_email_exists($email, $id, $staff_id)) {
            $this->form_validation->set_message('check_exists', $this->lang->line('email_already_exists'));
            return false;
        } else {
            return true;
        }
    }

    public function check_email_exists($email, $id, $staff_id)
    {
        if ($staff_id != 0) {
            $data  = array('id != ' => $staff_id, 'email' => $email);
            $query = $this->db->where($data)->get('staff');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('email', $email);
            $query = $this->db->get('staff');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getStaffRole($id = null)
    {
        $this->db->select('roles.id,roles.name as type')->from('roles');

        if ($id != null) {
            $this->db->where('id', $id);
        } else {

            if ($this->superadmin_visible == 'disabled' && $this->staffrole->id != 7) {
                $this->db->where("roles.id !=", 7);
            }

            $this->db->order_by('id');
        }
        $this->db->where("is_active", "yes");
        $query = $this->db->get();
        if ($id != null) {
            $result = $query->row_array();
        } else {
            $result = $query->result_array();
        }
        return $result;
    }

    public function count_leave($month, $year, $staff_id)
    {
        $query1 = $this->db->select('sum(leave_days) as tl')->where(array('month(date)' => $month, 'year(date)' => $year, 'staff_id' => $staff_id, 'status' => 'approve'))->get("staff_leave_request");
        return $query1->row_array();
    }

    public function alloted_leave($staff_id)
    {
        $query2 = $this->db->select('sum(alloted_leave) as alloted_leave')->where(array('staff_id' => $staff_id))->get("staff_leave_details");
        return $query2->result_array();
    }

    public function allotedLeaveType($id)
    {
        $session = $this->setting_model->getCurrentSession();

        $query = $this->db->select('staff_leave_details.*,leave_types.type')->where(array('staff_id' => $id, 'session_id' => $session))->join("leave_types", "staff_leave_details.leave_type_id = leave_types.id")->get("staff_leave_details");
        return $query->result_array();
    }

    public function getAllotedLeave($staff_id)
    {
        $query = $this->db->select('*')->join("leave_types", "staff_leave_details.leave_type_id = leave_types.id")->where("staff_id", $staff_id)->get("staff_leave_details");
        return $query->result_array();
    }

    public function getEmployee($role, $active = 1, $class_id = null)
    {
        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('staff', 1);

        $field_k_array = array();
        $join_array    = "";
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_k_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                $this->db->join('custom_field_values as ' . $tb_counter, 'staff.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');

                $i++;
            }
        }

        $field_var = count($field_k_array) > 0 ? "," . implode(',', $field_k_array) : "";

        if ($this->staffrole->id != 7) {
            if ($this->superadmin_visible == 'disabled') {
                $this->db->where("roles.id !=", 7);
            }
        }

        $this->db->select("staff.*,staff_designation.designation,department.department_name as department,roles.name as user_type,roles.id as role_id" . $field_var)->from('staff');
        $this->db->join('staff_designation', "staff_designation.id = staff.designation", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('roles', "roles.id = staff_roles.role_id", "left");
        $this->db->join('department', "department.id = staff.department", "left");

        if ($class_id != "") {
            $this->db->join('class_teacher', 'staff.id=class_teacher.staff_id', 'left');
            $this->db->or_where('class_teacher.class_id', $student_current_class->class_id);
        }
        $this->db->where("staff.is_active", $active);
        if ($role != "") {
            $this->db->where("roles.id", $role);
        }
        $query = $this->db->get();

        $result = $query->result_array();
        return $result;
    }

    public function getEmployeeByRoleID($role, $active = 1)
    {
        $query = $this->db->select("staff.*,staff_designation.designation,department.department_name as department, roles.id as role_id, roles.name as role")->join('staff_designation', "staff_designation.id = staff.designation", "left")->join('staff_roles', "staff_roles.staff_id = staff.id", "left")->join('roles', "roles.id = staff_roles.role_id", "left")->join('department', "department.id = staff.department", "left")->where("staff.is_active", $active)->where("roles.id", $role)->get("staff");

        return $query->result_array();
    }

    public function getStaffDesignation()
    {
        $query = $this->db->select('*')->where("is_active", "yes")->get("staff_designation");
        return $query->result_array();
    }

    public function getDepartment()
    {
        $query = $this->db->select('*')->where("is_active", "yes")->get('department');
        return $query->result_array();
    }

    public function getLeaveRecord($id)
    {
        $query = $this->db->select('leave_types.type,leave_types.id as lid,roles.id as staff_role,staff.name,staff.surname,staff.id as staff_id,roles.name as user_type,staff.employee_id,staff_leave_request.*')->join("leave_types", "leave_types.id = staff_leave_request.leave_type_id")->join("staff", "staff.id = staff_leave_request.staff_id")->join("staff_roles", "staff.id = staff_roles.staff_id")->join("roles", "staff_roles.role_id = roles.id")->where("staff_leave_request.id", $id)->get("staff_leave_request");

        $result =  $query->row();
        $applied_by  =   $this->staff_model->get($result->applied_by);

        if (!empty($applied_by['employee_id'])) {
            $result->applied_by =  $applied_by['name'] . ' ' . $applied_by['surname'] . ' (' . $applied_by['employee_id'] . ')';
        } else {
            $result->applied_by = '';
        }

        return $result;
    }

    public function getStaffId($empid)
    {
        $data  = array('employee_id' => $empid);
        $query = $this->db->select('id')->where($data)->get("staff");
        return $query->row_array();
    }

    public function getProfile($id)
    {
        $this->db->select('staff.*,staff_designation.designation as designation,staff_roles.role_id, department.department_name as department,roles.name as user_type');
        $this->db->join("staff_designation", "staff_designation.id = staff.designation", "left");
        $this->db->join("department", "department.id = staff.department", "left");
        $this->db->join("staff_roles", "staff_roles.staff_id = staff.id", "left");
        $this->db->join("roles", "staff_roles.role_id = roles.id", "left");
        $this->db->where("staff.id", $id);
        $this->db->from('staff');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_staff_name($id)
    {

        $filter_get_student_name = $this->db->select('CONCAT_WS(" ",name,surname,"(",employee_id,")") as name')->from('staff')->where('staff.id', $id)->get()->row_array();
        return $filter_get_student_name['name'];
    }

    public function searchFullText($searchterm, $active)
    {
        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('staff', 1);

        $field_k_array = array();
        $join_array    = "";
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_k_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                $join_array .= " LEFT JOIN `custom_field_values` as `" . $tb_counter . "` ON `staff`.`id` = `" . $tb_counter . "`.`belong_table_id` AND `" . $tb_counter . "`.`custom_field_id` = " . $custom_fields_value->id;
                $i++;
            }
        }

        $condition = '';
        if ($this->staffrole->id != 7) {
            if ($this->superadmin_visible == 'disabled') {
                $condition = "and roles.id != 7 ";
            }
        }

        $field_var = count($field_k_array) > 0 ? "," . implode(',', $field_k_array) : "";

        $query = "SELECT `staff`.*, `staff_designation`.`designation` as `designation`, `department`.`department_name` as `department`,roles.id as role_id,`roles`.`name` as user_type " . $field_var . "  FROM `staff` " . $join_array . " LEFT JOIN `staff_designation` ON `staff_designation`.`id` = `staff`.`designation` LEFT JOIN `staff_roles` ON `staff_roles`.`staff_id` = `staff`.`id` LEFT JOIN `roles` ON `staff_roles`.`role_id` = `roles`.`id` LEFT JOIN `department` ON `department`.`id` = `staff`.`department` WHERE  `staff`.`is_active` = '$active' and (CONCAT(`staff`.`name`,' ',`staff`.`surname`) LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!' OR `staff`.`surname` LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!' OR `staff`.`employee_id` LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!' OR `staff`.`local_address` LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!'  OR `staff`.`contact_no` LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!' OR `staff`.`email` LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!' OR `roles`.`name` LIKE '%" . $this->db->escape_like_str($searchterm) . "' ESCAPE '!') $condition ";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function searchByEmployeeId($employee_id)
    {
        $this->db->select('*');
        $this->db->from('staff');
        $this->db->like('staff.employee_id', $employee_id);
        $this->db->like('staff.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStaffDoc($id)
    {
        $this->db->select('*');
        $this->db->from('staff_documents');
        $this->db->where('staff_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function count_attendance($year, $staff_id, $att_type)
    {
        $query = $this->db->select('count(*) as attendence')->where(array('staff_id' => $staff_id, 'year(date)' => $year, 'staff_attendance_type_id' => $att_type))->get("staff_attendance");
        return $query->row()->attendence;
    }

    public function getStaffPayroll($id)
    {
        $this->db->select('*');
        $this->db->from('staff_payslip');
        $this->db->where('staff_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function doc_delete($id, $doc)
    {
        $staff = $this->get($id);
        $file_name  = "";

        if ($doc == "resume") {
            $file_name = $staff['resume'];
            $data      = array('resume' => '');
        } else if ($doc == "joining_letter") {
            $file_name = $staff['joining_letter'];
            $data      = array('joining_letter' => '');
        } else if ($doc == "resignation_letter") {
            $file_name = $staff['resignation_letter'];
            $data      = array('resignation_letter' => '');
        } else if ($doc == "other_document_file") {
            $file_name = $staff['other_document_file'];
            $data      = array('other_document_name' => '', 'other_document_file' => '');
        }

        if ($file_name != "") {
            $this->media_storage->filedelete($file_name,  "./uploads/staff_documents/" . $id);
        }

        $this->db->where('id', $id)->update("staff", $data);
    }

    public function getLeaveDetails($id)
    {
        $session = $this->setting_model->getCurrentSession(); //current session id 

        $query = $this->db->select('staff_leave_details.alloted_leave,staff_leave_details.id as altid,leave_types.type,leave_types.id')->join("leave_types", "staff_leave_details.leave_type_id = leave_types.id", "inner")->where("staff_leave_details.staff_id", $id)->where('staff_leave_details.session_id', $session)->get("staff_leave_details");
        return $query->result_array();
    }

    public function disablestaff($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        $query = $this->db->where("id", $data['id'])->update("staff", $data);
        $message   = UPDATE_RECORD_CONSTANT . " On  disable Staff id " . $data['id'];
        $action    = "Update";
        $record_id = $insert_id = $data['id'];
        $this->log($message, $record_id, $action);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function enablestaff($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        $data = array('is_active' => 1, 'disable_at' => '');
        $query = $this->db->where("id", $id)->update("staff", $data);
        $message   = UPDATE_RECORD_CONSTANT . " On  Enable Staff id " . $id;
        $action    = "Update";
        $record_id = $insert_id = $id;
        $this->log($message, $record_id, $action);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function getByEmail($email)
    {
        $this->db->select('staff.*,languages.language,languages.short_code,languages.id as language_id,languages.is_rtl,IFNULL(currencies.name,0) as currency_name,IFNULL(currencies.symbol,0) as symbol,IFNULL(currencies.base_price,0) as base_price ,IFNULL(currencies.id,0) as `currency`');
        $this->db->from('staff');
        $this->db->join('languages', 'languages.id=staff.lang_id', 'left');
        $this->db->join('currencies', 'currencies.id=staff.currency_id', 'left');
        $this->db->where('email', $email);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function checkLogin($data)
    {
        $record = $this->getByEmail($data['email']);
        if ($record) {
            $pass_verify = $this->enc_lib->passHashDyc($data['password'], $record->password);
            if ($pass_verify) {
                $roles = $this->staffroles_model->getStaffRoles($record->id);
                $record->roles = array($roles[0]->name => $roles[0]->role_id);
                return $record;
            }
        }
        return false;
    }

    public function getStaffbyrole($id)
    {
        $this->db->select('staff.*,staff_designation.designation as designation,staff_roles.role_id, department.department_name as department,roles.name as user_type');
        $this->db->join("staff_designation", "staff_designation.id = staff.designation", "left");
        $this->db->join("department", "department.id = staff.department", "left");
        $this->db->join("staff_roles", "staff_roles.staff_id = staff.id", "left");
        $this->db->join("roles", "staff_roles.role_id = roles.id", "left");
        $this->db->where("staff_roles.role_id", $id);
        $this->db->where("staff.is_active", "1");
        $this->db->from('staff');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchNameLike($searchterm)
    {
        $this->db->select('staff.*')->from('staff');
        $this->db->join("staff_roles", "staff_roles.staff_id = staff.id", "left");
        $this->db->join("roles", "staff_roles.role_id = roles.id", "left");
        $this->db->group_start();
        $this->db->like('staff.name', $searchterm);
        $this->db->group_end();
        $this->db->where("staff.is_active", "1");
        $this->db->order_by('staff.id');
        if ($this->superadmin_visible == 'disabled' && $this->staffrole->id != 7) {
            $this->db->where("roles.id !=", 7);
        }
        $this->db->limit(15);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_role($role_data)
    {
        $this->db->where("staff_id", $role_data["staff_id"])->update("staff_roles", $role_data);
    }

    public function check_staffid_exists($employee_id)
    {
        $this->db->where(array('employee_id' => $employee_id));
        $query = $this->db->get('staff');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function lastRecord()
    {
        $last_row = $this->db->select('*')->order_by('id', "desc")->limit(1)->get('staff')->row();
        return $last_row;
    }

    public function ratingapr($id, $approve)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->update("staff_rating", $approve);
        $message   = UPDATE_RECORD_CONSTANT . " On staff rating id " . $id;
        $action    = "Update";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /*Optional*/

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function rating_remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('staff_rating');
        $message   = DELETE_RECORD_CONSTANT . " On staff rating id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function get_RatedStaffByUser($user_id)
    {
        $this->db->select('staff_rating.staff_id')->from('staff_rating');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function all_rating()
    {
        $this->db->select('sum(`rate`) as rate, count(*) as total,staff_id')->from('staff_rating');
        $this->db->where('status', '1');
        $this->db->group_by('staff_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_ratingbyuser($user_id, $role)
    {
        $this->db->select('*')->from('staff_rating');
        $this->db->where('user_id', $user_id);
        $this->db->where('role', $role);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function staff_ratingById($id)
    {
        $this->db->select('sum(`rate`) as rate, count(*) as total')->from('staff_rating');
        $this->db->where('staff_id', $id);
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_StaffNameById($id)
    {
        return $this->db->select("CONCAT_WS(' ',name,surname) as name,employee_id,id")->from('staff')->where('id', $id)->get()->row_array();
    }

    public function staff_report($condition)
    {
        $rolescondition = '';

        if ($this->session->has_userdata('admin')) {
            if ($this->staffrole->id != 7) {
                if ($this->superadmin_visible == 'disabled') {
                    $rolescondition = " and roles.id != 7 ";
                }
            }
        }

        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('staff', 1);

        $field_k_array = array();
        $join_array    = "";
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_k_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                $join_array .= " LEFT JOIN `custom_field_values` as `" . $tb_counter . "` ON `staff`.`id` = `" . $tb_counter . "`.`belong_table_id` AND `" . $tb_counter . "`.`custom_field_id` = " . $custom_fields_value->id;

                $i++;
            }
        }

        $field_var = count($field_k_array) > 0 ? "," . implode(',', $field_k_array) : "";

        $query = "SELECT `staff`.*, `staff_designation`.`designation` as `designation`, `department`.`department_name` as `department`,`roles`.`name` as user_type " . $field_var . ",GROUP_CONCAT(leave_type_id,'@',alloted_leave) as leaves  FROM `staff` " . $join_array . " LEFT JOIN `staff_designation` ON `staff_designation`.`id` = `staff`.`designation` LEFT JOIN `staff_roles` ON `staff_roles`.`staff_id` = `staff`.`id` LEFT JOIN `roles` ON `staff_roles`.`role_id` = `roles`.`id` LEFT JOIN `department` ON `department`.`id` = `staff`.`department` left join staff_leave_details ON staff_leave_details.staff_id=staff.id WHERE 1  " . $condition .    $rolescondition . " group by staff.id";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function inventry_staff()
    {
        if ($this->superadmin_visible == 'disabled' && $this->staffrole->id != 7) {
            $this->db->where("staff_roles.role_id !=", 7);
        }

        $this->db->select("CONCAT_WS(' ',staff.name,staff.surname) as name,staff.employee_id,roles.id as role_id,staff.id");
        $this->db->from('staff');
        $this->db->join("staff_roles", "staff_roles.staff_id = staff.id", "left");
        $this->db->join("roles", "staff_roles.role_id = roles.id", "left");
        $this->db->where('staff.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getstaffemail($id = null)
    {
        $this->db->select('staff.*,languages.language,roles.name as user_type,roles.id as role_id')->from('staff')->join("staff_roles", "staff_roles.staff_id = staff.id", "left")->join("roles", "staff_roles.role_id = roles.id", "left")->join("languages", "languages.id = staff.lang_id", "left");
        $this->db->where('staff.id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getemployeeidbystaffid($id)
    {
        $this->db->select('staff.employee_id,email,contact_no')->from('staff')->where('staff.id', $id);
        $query = $this->db->get();
        $result =  $query->row_array();
        return $result['employee_id'];
    }

    public function get_staff_list($array = null)
    {
        $array = implode(',', $array);
        $query = $this->db->query("select * from staff where staff.id in ($array)");
        return $query->result_array();
    }

    public function getRoleStats()
    {
        $sql = "SELECT roles.name as role, COUNT(staff.id) as total 
                FROM staff 
                INNER JOIN staff_roles ON staff_roles.staff_id = staff.id 
                INNER JOIN roles ON staff_roles.role_id = roles.id 
                WHERE staff.is_active = 1 
                GROUP BY roles.name 
                ORDER BY total DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_department_by_name($name)
    {
        if (empty($name)) return null;
        $query = $this->db->select('id')->where('LOWER(department_name)', strtolower(trim($name)))->get('department');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        }
        return null;
    }

    public function get_designation_by_name($name)
    {
        if (empty($name)) return null;
        $query = $this->db->select('id')->where('LOWER(designation)', strtolower(trim($name)))->get('staff_designation');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        }
        return null;
    }

    public function get_role_by_name($name)
    {
        if (empty($name)) return null;
        $query = $this->db->select('id')->where('LOWER(name)', strtolower(trim($name)))->get('roles');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        }
        return null;
    }

    public function clean_scientific_number($val)
    {
        $val = trim($val);
        if (empty($val)) return '';
        if (preg_match('/^[0-9]+(\.[0-9]+)?[eE]\+[0-9]+$/i', $val)) {
            return sprintf("%.0f", (float)$val);
        }
        return $val;
    }


    public function parse_date_to_db($date_str)
    {
        $date_str = trim($date_str);
        if (empty($date_str) || $date_str == '0000-00-00' || $date_str == '1970-01-01') return null;

        $clean_date = str_replace(array('.', '/', ' '), '-', $date_str);
        $parts = explode('-', $clean_date);

        if (count($parts) == 3) {
            // YYYY-MM-DD
            if (strlen($parts[0]) == 4) {
                $y = $parts[0];
                $m = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                $d = str_pad($parts[2], 2, '0', STR_PAD_LEFT);
                return "{$y}-{$m}-{$d}";
            }
            // DD-MM-YYYY
            if (strlen($parts[2]) == 4) {
                $d = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                $m = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                $y = $parts[2];
                if ((int)$m > 12 && (int)$d <= 12) {
                    $temp = $d; $d = $m; $m = $temp;
                }
                return "{$y}-{$m}-{$d}";
            }
        }

        $timestamp = strtotime($clean_date);
        if ($timestamp !== false && $timestamp > 0) {
            return date('Y-m-d', $timestamp);
        }
        return null;
    }

    public function bulk_update_staff_from_csv($csv_rows)


    {
        if (empty($csv_rows)) return 0;
        $this->db->trans_start();
        $processed_count = 0;

        foreach ($csv_rows as $row) {
            $employee_id = isset($row['employee_id']) ? trim($row['employee_id']) : '';
            $email       = isset($row['email']) ? trim($row['email']) : '';

            $existing = null;
            if (!empty($employee_id)) {
                $staff_q = $this->db->where('employee_id', $employee_id)->get('staff');
                if ($staff_q->num_rows() > 0) {
                    $existing = $staff_q->row();
                }
            }
            if (empty($existing) && !empty($email)) {
                $staff_q = $this->db->where('email', $email)->get('staff');
                if ($staff_q->num_rows() > 0) {
                    $existing = $staff_q->row();
                }
            }

            $update_data = array();
            if (isset($row['name']) && $row['name'] !== '') $update_data['name'] = trim($row['name']);
            if (isset($row['surname']) && $row['surname'] !== '') $update_data['surname'] = trim($row['surname']);
            if (isset($row['father_name']) && $row['father_name'] !== '') $update_data['father_name'] = trim($row['father_name']);
            if (isset($row['mother_name']) && $row['mother_name'] !== '') $update_data['mother_name'] = trim($row['mother_name']);
            if (isset($row['email']) && $row['email'] !== '') $update_data['email'] = trim($row['email']);
            if (isset($row['contact_no']) && $row['contact_no'] !== '') $update_data['contact_no'] = $this->clean_scientific_number($row['contact_no']);
            if (isset($row['emergency_contact_no']) && $row['emergency_contact_no'] !== '') $update_data['emergency_contact_no'] = $this->clean_scientific_number($row['emergency_contact_no']);
            if (isset($row['dob']) && $row['dob'] !== '') $update_data['dob'] = $this->parse_date_to_db($row['dob']);
            if (isset($row['date_of_joining']) && $row['date_of_joining'] !== '') $update_data['date_of_joining'] = $this->parse_date_to_db($row['date_of_joining']);

            if (isset($row['qualification']) && $row['qualification'] !== '') $update_data['qualification'] = trim($row['qualification']);
            if (isset($row['basic_salary']) && $row['basic_salary'] !== '') $update_data['basic_salary'] = trim($row['basic_salary']);
            if (isset($row['contract_type']) && $row['contract_type'] !== '') $update_data['contract_type'] = trim($row['contract_type']);
            if (isset($row['account_title']) && $row['account_title'] !== '') $update_data['account_title'] = trim($row['account_title']);
            if (isset($row['bank_account_no']) && $row['bank_account_no'] !== '') $update_data['bank_account_no'] = $this->clean_scientific_number($row['bank_account_no']);
            if (isset($row['bank_name']) && $row['bank_name'] !== '') $update_data['bank_name'] = trim($row['bank_name']);
            if (isset($row['ifsc_code']) && $row['ifsc_code'] !== '') $update_data['ifsc_code'] = trim($row['ifsc_code']);
            if (isset($row['pan_no']) && $row['pan_no'] !== '') $update_data['epf_no'] = $this->clean_scientific_number($row['pan_no']);


            if (isset($row['password']) && trim($row['password']) !== '') {
                $update_data['password'] = $this->enc_lib->passHashEnc(trim($row['password']));
            }

            if (isset($row['department']) && trim($row['department']) !== '') {
                $dept_id = $this->get_department_by_name($row['department']);
                if ($dept_id !== null) {
                    $update_data['department'] = $dept_id;
                }
            }

            if (isset($row['designation']) && trim($row['designation']) !== '') {
                $desig_id = $this->get_designation_by_name($row['designation']);
                if ($desig_id !== null) {
                    $update_data['designation'] = $desig_id;
                }
            }

            if ($existing) {
                // Existing staff -> UPDATE
                $staff_id = $existing->id;
                if (!empty($update_data)) {
                    $this->db->where('id', $staff_id);
                    $this->db->update('staff', $update_data);
                }
            } else {
                // Unmatched staff -> CREATE NEW STAFF RECORD!
                $update_data['employee_id'] = !empty($employee_id) ? $employee_id : 'EMP' . rand(1000, 9999);
                if (empty($update_data['name'])) $update_data['name'] = 'Staff ' . $update_data['employee_id'];
                if (empty($update_data['email'])) $update_data['email'] = strtolower($update_data['employee_id']) . '@school.com';
                if (empty($update_data['password'])) $update_data['password'] = $this->enc_lib->passHashEnc('123456');
                $update_data['is_active'] = 1;

                $this->db->insert('staff', $update_data);
                $staff_id = $this->db->insert_id();
            }

            // Role resolution & update
            $role_id = null;
            if (isset($row['role']) && trim($row['role']) !== '') {
                $role_id = $this->get_role_by_name($row['role']);
            }
            if ($role_id === null) {
                $role_id = 2; // Default Teacher role
            }
            $role_exists = $this->db->where('staff_id', $staff_id)->get('staff_roles');
            if ($role_exists->num_rows() > 0) {
                $this->db->where('staff_id', $staff_id);
                $this->db->update('staff_roles', array('role_id' => $role_id));
            } else {
                $this->db->insert('staff_roles', array('staff_id' => $staff_id, 'role_id' => $role_id));
            }

            $processed_count++;
        }

        $this->db->trans_complete();
        return ($this->db->trans_status() === false) ? 0 : $processed_count;
    }

}

