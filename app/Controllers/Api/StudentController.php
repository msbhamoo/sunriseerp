<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\StudentModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * StudentController (API v2)
 *
 * RESTful API endpoints for Student Management powered by CodeIgniter 4.
 */
class StudentController extends BaseController
{
    protected StudentModel $studentModel;
    protected ClassModel $classModel;
    protected SessionModel $sessionModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->studentModel = new StudentModel();
        $this->classModel   = new ClassModel();
        $this->sessionModel = new SessionModel();
    }

    /**
     * GET /api/v2/students
     * List and search students with filters and pagination.
     */
    public function index(): ResponseInterface
    {
        $limit  = max(1, min(100, (int)($this->request->getGet('limit') ?: 25)));
        $page   = max(1, (int)($this->request->getGet('page') ?: 1));
        $offset = ($page - 1) * $limit;

        $criteria = [
            'keyword'    => $this->request->getGet('keyword'),
            'class_id'   => $this->request->getGet('class_id'),
            'section_id' => $this->request->getGet('section_id'),
            'is_active'  => $this->request->getGet('is_active') ?: 'yes',
        ];

        $totalRecords = $this->studentModel->countFiltered($criteria);
        $students     = $this->studentModel->search($criteria, $limit, $offset);

        return $this->response->setJSON([
            'status'     => 'success',
            'pagination' => [
                'current_page' => $page,
                'per_page'     => $limit,
                'total'        => $totalRecords,
                'total_pages'  => (int)ceil($totalRecords / $limit),
            ],
            'data'       => $students,
        ]);
    }

    /**
     * GET /api/v2/students/(:num)
     * Get single student profile.
     */
    public function show(int $id): ResponseInterface
    {
        $student = $this->studentModel->get($id);

        if (empty($student)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Student record not found.',
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $student,
        ]);
    }

    /**
     * GET /api/v2/classes
     * List classes with their associated sections.
     */
    public function classes(): ResponseInterface
    {
        $classes = $this->classModel->getClassesWithSections();

        return $this->response->setJSON([
            'status' => 'success',
            'count'  => count($classes),
            'data'   => $classes,
        ]);
    }

    /**
     * POST /api/v2/students
     * Create a new student admission.
     */
    public function create(): ResponseInterface
    {
        $rules = [
            'admission_no' => 'required|is_unique[students.admission_no]',
            'firstname'    => 'required|min_length[2]|max_length[100]',
            'gender'       => 'required|in_list[Male,Female,Other]',
            'class_id'     => 'required|is_natural_no_zero',
            'section_id'   => 'required|is_natural_no_zero',
            'dob'          => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $studentData = [
            'admission_no'       => trim($this->request->getPost('admission_no')),
            'roll_no'            => $this->request->getPost('roll_no') ?: null,
            'admission_date'     => $this->request->getPost('admission_date') ?: date('Y-m-d'),
            'firstname'          => trim($this->request->getPost('firstname')),
            'middlename'         => $this->request->getPost('middlename') ?: '',
            'lastname'           => $this->request->getPost('lastname') ?: '',
            'gender'             => $this->request->getPost('gender'),
            'dob'                => $this->request->getPost('dob') ?: null,
            'mobileno'           => $this->request->getPost('mobileno') ?: '',
            'email'              => $this->request->getPost('email') ?: '',
            'current_address'    => $this->request->getPost('current_address') ?: '',
            'permanent_address'  => $this->request->getPost('permanent_address') ?: '',
            'father_name'        => $this->request->getPost('father_name') ?: '',
            'father_phone'       => $this->request->getPost('father_phone') ?: '',
            'mother_name'        => $this->request->getPost('mother_name') ?: '',
            'mother_phone'       => $this->request->getPost('mother_phone') ?: '',
            'guardian_name'      => $this->request->getPost('guardian_name') ?: '',
            'guardian_phone'     => $this->request->getPost('guardian_phone') ?: '',
            'guardian_relation'  => $this->request->getPost('guardian_relation') ?: '',
            'is_active'          => 'yes',
        ];

        $sessionData = [
            'class_id'   => (int)$this->request->getPost('class_id'),
            'section_id' => (int)$this->request->getPost('section_id'),
        ];

        $studentId = $this->studentModel->saveStudent($studentData, $sessionData);

        if (!$studentId) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Failed to create student record.',
            ]);
        }

        return $this->response->setStatusCode(201)->setJSON([
            'status'     => 'success',
            'message'    => 'Student admitted successfully.',
            'student_id' => $studentId,
        ]);
    }

    /**
     * PUT /api/v2/students/(:num)
     * Update an existing student record.
     */
    public function update(int $id): ResponseInterface
    {
        $existing = $this->studentModel->get($id);
        if (empty($existing)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Student not found.',
            ]);
        }

        $input = $this->request->getJSON(true) ?: $this->request->getRawInput();

        $allowedStudentFields = [
            'roll_no', 'firstname', 'middlename', 'lastname', 'gender', 'dob',
            'mobileno', 'email', 'current_address', 'permanent_address',
            'father_name', 'father_phone', 'father_occupation',
            'mother_name', 'mother_phone', 'mother_occupation',
            'guardian_name', 'guardian_phone', 'guardian_relation',
            'blood_group', 'adhar_no', 'samagra_id', 'note',
        ];

        $studentData = ['id' => $id];
        foreach ($allowedStudentFields as $field) {
            if (isset($input[$field])) {
                $studentData[$field] = $input[$field];
            }
        }

        $sessionData = [];
        if (!empty($input['class_id']) && !empty($input['section_id'])) {
            $sessionData = [
                'class_id'   => (int)$input['class_id'],
                'section_id' => (int)$input['section_id'],
            ];
        }

        $success = $this->studentModel->saveStudent($studentData, $sessionData);

        return $this->response->setJSON([
            'status'  => $success ? 'success' : 'error',
            'message' => $success ? 'Student updated successfully.' : 'Update failed.',
        ]);
    }

    /**
     * DELETE /api/v2/students/(:num)
     * Soft-disable student with reason and note.
     */
    public function disable(int $id): ResponseInterface
    {
        $existing = $this->studentModel->get($id);
        if (empty($existing)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Student not found.',
            ]);
        }

        $reason = (string)($this->request->getVar('reason') ?: 'Disabled via API');
        $note   = (string)($this->request->getVar('note') ?: '');

        $success = $this->studentModel->disableStudent($id, $reason, $note);

        return $this->response->setJSON([
            'status'  => $success ? 'success' : 'error',
            'message' => $success ? 'Student disabled successfully.' : 'Operation failed.',
        ]);
    }
}
