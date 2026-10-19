<?php

namespace App\Models;

/**
 * StaffModel
 *
 * Model for school staff members (`staff`).
 */
class StaffModel extends BaseModel
{
    protected $table = 'staff';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'employee_id', 'lang_id', 'department', 'designation', 'qualification',
        'work_exp', 'name', 'surname', 'father_name', 'mother_name', 'contact_no',
        'emergency_contact_no', 'email', 'dob', 'marital_status', 'date_of_joining',
        'date_of_leaving', 'local_address', 'permanent_address', 'note', 'image',
        'password', 'gender', 'account_title', 'bank_account_no', 'bank_name',
        'ifsc_code', 'bank_branch', 'pancard', 'sanction_order_no', 'is_active',
        'verification_code', 'zoom_api_key', 'zoom_api_secret', 'created_at', 'updated_at'
    ];

    public function get(?int $id = null): array
    {
        $builder = $this->db->table('staff')
            ->select('staff.*, languages.language, languages.is_rtl, languages.short_code, roles.name as user_type, roles.id as role_id')
            ->join('staff_roles', 'staff_roles.staff_id = staff.id', 'left')
            ->join('roles', 'staff_roles.role_id = roles.id', 'left')
            ->join('languages', 'languages.id = staff.lang_id', 'left');

        if ($id !== null) {
            $builder->where('staff.id', $id);
            $row = $builder->get()->getRowArray();
            return $row ?: [];
        }

        return $builder->where('staff.is_active', 1)
            ->orderBy('staff.id', 'ASC')
            ->get()
            ->getResultArray();
    }
}
