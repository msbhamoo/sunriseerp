<?php

namespace App\Models;

/**
 * StudentModel
 *
 * Model for student management (`students` and `student_session`).
 */
class StudentModel extends BaseModel
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'admission_no', 'roll_no', 'admission_date', 'firstname', 'middlename', 'lastname',
        'rte', 'image', 'mobileno', 'email', 'state', 'city', 'pincode', 'note', 'religion',
        'cast', 'dob', 'current_address', 'permanent_address', 'category_id',
        'adhar_no', 'samagra_id', 'bank_account_no', 'bank_name', 'ifsc_code', 'guardian_name',
        'guardian_relation', 'guardian_phone', 'guardian_address', 'guardian_email', 'guardian_is',
        'father_name', 'father_phone', 'father_occupation', 'mother_name', 'mother_phone', 'mother_occupation',
        'guardian_occupation', 'gender', 'blood_group', 'school_house_id', 'height', 'weight',
        'measurement_date', 'is_active', 'dis_reason', 'dis_note', 'disable_at',
        'app_key', 'parent_app_key', 'created_at', 'updated_at'
    ];

    /**
     * Build base student query joined with session, class, section, and user tables.
     */
    protected function getBaseQuery(?int $sessionId = null)
    {
        if ($sessionId === null) {
            $sessionModel = new SessionModel();
            $sessionId = $sessionModel->getCurrentSessionId();
        }

        return $this->db->table('students')
            ->select('students.*, student_session.id as student_session_id, student_session.session_id, student_session.class_id, student_session.section_id, classes.class, sections.section, categories.category, users.username, users.is_active as user_active')
            ->join('student_session', 'student_session.student_id = students.id AND student_session.session_id = ' . (int)$sessionId)
            ->join('classes', 'classes.id = student_session.class_id', 'left')
            ->join('sections', 'sections.id = student_session.section_id', 'left')
            ->join('categories', 'categories.id = students.category_id', 'left')
            ->join('users', 'users.user_id = students.id AND users.role = "student"', 'left');
    }

    /**
     * Get a single student by ID or list all active students.
     */
    public function get(?int $id = null, ?int $sessionId = null): array
    {
        $builder = $this->getBaseQuery($sessionId);

        if ($id !== null) {
            $builder->where('students.id', $id);
            $result = $builder->get()->getRowArray();
            return $result ?: [];
        }

        $builder->where('students.is_active', 'yes');
        $builder->orderBy('students.id', 'DESC');
        return $builder->get()->getResultArray();
    }

    /**
     * Get active students for a specific class and section.
     */
    public function getByClassSection(int $classId, int $sectionId, ?int $sessionId = null): array
    {
        return $this->getBaseQuery($sessionId)
            ->where('student_session.class_id', $classId)
            ->where('student_session.section_id', $sectionId)
            ->where('students.is_active', 'yes')
            ->orderBy('students.firstname', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Search student by admission number.
     */
    public function findByAdmissionNo(string $admissionNo, ?int $sessionId = null): ?array
    {
        $result = $this->getBaseQuery($sessionId)
            ->where('students.admission_no', trim($admissionNo))
            ->get()
            ->getRowArray();

        return $result ?: null;
    }

    /**
     * Search students with filters and pagination.
     */
    public function search(array $criteria = [], int $limit = 50, int $offset = 0, ?int $sessionId = null): array
    {
        $builder = $this->getBaseQuery($sessionId);

        if (!empty($criteria['class_id'])) {
            $builder->where('student_session.class_id', (int)$criteria['class_id']);
        }

        if (!empty($criteria['section_id'])) {
            $builder->where('student_session.section_id', (int)$criteria['section_id']);
        }

        if (isset($criteria['is_active'])) {
            $builder->where('students.is_active', $criteria['is_active']);
        } else {
            $builder->where('students.is_active', 'yes');
        }

        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            $builder->groupStart()
                ->like('students.firstname', $keyword)
                ->orLike('students.lastname', $keyword)
                ->orLike('students.admission_no', $keyword)
                ->orLike('students.roll_no', $keyword)
                ->orLike('students.father_name', $keyword)
                ->orLike('students.guardian_name', $keyword)
                ->orLike('students.guardian_phone', $keyword)
                ->orLike('students.mobileno', $keyword)
                ->groupEnd();
        }

        $builder->orderBy('students.id', 'DESC');
        $builder->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    /**
     * Count total matching students for pagination.
     */
    public function countFiltered(array $criteria = [], ?int $sessionId = null): int
    {
        $builder = $this->getBaseQuery($sessionId);

        if (!empty($criteria['class_id'])) {
            $builder->where('student_session.class_id', (int)$criteria['class_id']);
        }

        if (!empty($criteria['section_id'])) {
            $builder->where('student_session.section_id', (int)$criteria['section_id']);
        }

        if (isset($criteria['is_active'])) {
            $builder->where('students.is_active', $criteria['is_active']);
        } else {
            $builder->where('students.is_active', 'yes');
        }

        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            $builder->groupStart()
                ->like('students.firstname', $keyword)
                ->orLike('students.lastname', $keyword)
                ->orLike('students.admission_no', $keyword)
                ->orLike('students.roll_no', $keyword)
                ->orLike('students.father_name', $keyword)
                ->orLike('students.guardian_name', $keyword)
                ->orLike('students.guardian_phone', $keyword)
                ->orLike('students.mobileno', $keyword)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }

    /**
     * Save student (insert or update) with academic session record.
     */
    public function saveStudent(array $studentData, array $sessionData = []): int|bool
    {
        $this->db->transStart();

        if (!empty($studentData['id'])) {
            $studentId = (int)$studentData['id'];
            $studentData['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('students')->where('id', $studentId)->update($studentData);
            $action = 'Update';
        } else {
            $studentData['created_at'] = date('Y-m-d H:i:s');
            $this->db->table('students')->insert($studentData);
            $studentId = (int)$this->db->insertID();
            $action = 'Insert';
        }

        // Manage student_session record
        if (!empty($sessionData['class_id']) && !empty($sessionData['section_id'])) {
            $sessionModel = new SessionModel();
            $sessionId = $sessionData['session_id'] ?? $sessionModel->getCurrentSessionId();

            $existingSession = $this->db->table('student_session')
                ->where('student_id', $studentId)
                ->where('session_id', $sessionId)
                ->get()
                ->getRowArray();

            $sessionPayload = [
                'session_id' => $sessionId,
                'student_id' => $studentId,
                'class_id'   => (int)$sessionData['class_id'],
                'section_id' => (int)$sessionData['section_id'],
            ];

            if (!empty($existingSession)) {
                $this->db->table('student_session')->where('id', $existingSession['id'])->update($sessionPayload);
            } else {
                $this->db->table('student_session')->insert($sessionPayload);
            }
        }

        $this->logAudit("Student {$action} on ID {$studentId}", $studentId, $action);

        $this->db->transComplete();

        return $this->db->transStatus() !== false ? $studentId : false;
    }

    /**
     * Disable student record with reason.
     */
    public function disableStudent(int $id, string $reason = '', string $note = ''): bool
    {
        $this->db->transStart();

        $data = [
            'is_active'  => 'no',
            'dis_reason' => $reason,
            'dis_note'   => $note,
            'disable_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('students')->where('id', $id)->update($data);
        $this->logAudit("Student Disabled on ID {$id}", $id, 'Disable');

        $this->db->transComplete();

        return $this->db->transStatus() !== false;
    }
}
