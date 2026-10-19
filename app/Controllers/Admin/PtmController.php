<?php

namespace App\Controllers\Admin;

use App\Models\ClassModel;
use App\Models\PtmModel;
use App\Models\SectionModel;
use App\Models\StudentModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * PtmController (Web Interface)
 *
 * Full MVC Web Controller for Parent-Teacher Meetings powered by CodeIgniter 4.
 */
class PtmController extends AdminController
{
    protected PtmModel $ptmModel;
    protected ClassModel $classModel;
    protected SectionModel $sectionModel;
    protected StudentModel $studentModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->ptmModel     = new PtmModel();
        $this->classModel   = new ClassModel();
        $this->sectionModel = new SectionModel();
        $this->studentModel = new StudentModel();
    }

    /**
     * Main PTM List and Add Form Screen.
     */
    public function index()
    {
        $session = session();
        $session->set('top_menu', 'Academics');
        $session->set('sub_menu', 'admin/ptm');

        // Handle POST submission
        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $rules = [
                'title'     => 'required|trim',
                'ptm_date'  => 'required|trim',
                'time_from' => 'required|trim',
                'time_to'   => 'required|trim',
                'venue'     => 'required|trim',
            ];

            if ($this->validate($rules)) {
                $targetType = $this->request->getPost('target_type') ?: 'whole_school';
                $dataInsert = [
                    'title'       => $this->request->getPost('title'),
                    'ptm_date'    => $this->normalizeDate((string)$this->request->getPost('ptm_date')),
                    'time_from'   => $this->request->getPost('time_from'),
                    'time_to'     => $this->request->getPost('time_to'),
                    'venue'       => $this->request->getPost('venue'),
                    'description' => $this->request->getPost('description'),
                    'target_type' => $targetType,
                    'created_by'  => $this->staffId ?: 1,
                ];

                $insertId = $this->ptmModel->savePtm($dataInsert);

                if ($insertId && $targetType !== 'whole_school') {
                    $targets = (array)$this->request->getPost('class_section_id');
                    $this->saveTargets((int)$insertId, $targets);
                }

                $session->setFlashdata('msg', '<div class="alert alert-success text-left">PTM created successfully</div>');
                return redirect()->to(base_url('admin/ptm'));
            }
        }

        // Prepare View Data
        $ptms = $this->ptmModel->get();
        foreach ($ptms as &$ptm) {
            $ptm['targets'] = $this->ptmModel->getTargets((int)$ptm['id']);
        }

        $classes = $this->classModel->getClassesWithSections();

        $data = [
            'title'     => 'PTM List',
            'ptm_list'  => $ptms,
            'classlist' => $classes,
        ];

        return view('layout/header', $data)
             . view('admin/ptm/index', $data)
             . view('layout/footer', $data);
    }

    /**
     * Edit PTM Form Screen.
     */
    public function edit(int $id)
    {
        $session = session();
        $session->set('top_menu', 'Academics');
        $session->set('sub_menu', 'admin/ptm');

        $ptm = $this->ptmModel->get($id);
        if (empty($ptm)) {
            return redirect()->to(base_url('admin/ptm'));
        }

        // Handle POST update
        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $rules = [
                'title'     => 'required|trim',
                'ptm_date'  => 'required|trim',
                'time_from' => 'required|trim',
                'time_to'   => 'required|trim',
                'venue'     => 'required|trim',
            ];

            if ($this->validate($rules)) {
                $targetType = $this->request->getPost('target_type') ?: 'whole_school';
                $dataUpdate = [
                    'id'          => $id,
                    'title'       => $this->request->getPost('title'),
                    'ptm_date'    => $this->normalizeDate((string)$this->request->getPost('ptm_date')),
                    'time_from'   => $this->request->getPost('time_from'),
                    'time_to'     => $this->request->getPost('time_to'),
                    'venue'       => $this->request->getPost('venue'),
                    'description' => $this->request->getPost('description'),
                    'target_type' => $targetType,
                ];

                $this->ptmModel->savePtm($dataUpdate);

                if ($targetType !== 'whole_school') {
                    $targets = (array)$this->request->getPost('class_section_id');
                    $this->saveTargets($id, $targets);
                } else {
                    $this->saveTargets($id, []);
                }

                $session->setFlashdata('msg', '<div class="alert alert-success text-left">PTM updated successfully</div>');
                return redirect()->to(base_url('admin/ptm'));
            }
        }

        $ptm['targets'] = $this->ptmModel->getTargets($id);

        $ptms = $this->ptmModel->get();
        foreach ($ptms as &$p) {
            $p['targets'] = $this->ptmModel->getTargets((int)$p['id']);
        }

        $classes = $this->classModel->getClassesWithSections();

        $data = [
            'id'        => $id,
            'title'     => 'Edit PTM',
            'ptm'       => $ptm,
            'ptm_list'  => $ptms,
            'classlist' => $classes,
        ];

        return view('layout/header', $data)
             . view('admin/ptm/index', $data)
             . view('layout/footer', $data);
    }

    /**
     * Delete PTM.
     */
    public function delete(int $id)
    {
        $this->ptmModel->deletePtm($id);
        session()->setFlashdata('msg', '<div class="alert alert-success text-left">PTM deleted successfully</div>');
        return redirect()->to(base_url('admin/ptm'));
    }

    /**
     * PTM Attendance Screen.
     */
    public function attendance(int $ptmId)
    {
        $session = session();
        $session->set('top_menu', 'Academics');
        $session->set('sub_menu', 'admin/ptm');

        $ptm = $this->ptmModel->get($ptmId);
        if (empty($ptm)) {
            return redirect()->to(base_url('admin/ptm'));
        }

        $targets = $this->ptmModel->getTargets($ptmId);
        $classes = $this->classModel->getAll();

        $selectedClassIds = (array)($this->request->getVar('class_id') ?: []);
        $selectedSectionIds = (array)($this->request->getVar('section_id') ?: []);
        $selectedClassIds = array_values(array_filter($selectedClassIds));
        $selectedSectionIds = array_values(array_filter($selectedSectionIds));

        // Get sections for dropdown
        $sectionsList = [];
        if (!empty($selectedClassIds)) {
            foreach ($selectedClassIds as $cid) {
                $secs = $this->sectionModel->getByClassId((int)$cid);
                foreach ($secs as $s) {
                    $sectionsList[$s['section_id']] = $s;
                }
            }
        }

        // Fetch students
        $students = [];
        if (!empty($selectedClassIds)) {
            foreach ($selectedClassIds as $cid) {
                if (!empty($selectedSectionIds)) {
                    foreach ($selectedSectionIds as $sid) {
                        $clsStudents = $this->studentModel->getByClassSection((int)$cid, (int)$sid);
                        $students = array_merge($students, $clsStudents);
                    }
                } else {
                    $clsStudents = $this->studentModel->search(['class_id' => (int)$cid]);
                    $students = array_merge($students, $clsStudents);
                }
            }
        }

        // Deduplicate
        $uniqueStudents = [];
        foreach ($students as $stu) {
            $sessId = $stu['student_session_id'] ?? $stu['id'];
            if ($sessId && !isset($uniqueStudents[$sessId])) {
                $uniqueStudents[$sessId] = $stu;
            }
        }

        $attendances = $this->ptmModel->getStudentAttendances($ptmId);

        $db = \Config\Database::connect();
        $staffs = $db->table('staff')->where('is_active', 1)->get()->getResultArray();

        $data = [
            'ptm'                  => $ptm,
            'targets'              => $targets,
            'classlist'            => $classes,
            'selected_class_ids'   => $selectedClassIds,
            'selected_section_ids' => $selectedSectionIds,
            'sections_list'        => array_values($sectionsList),
            'students'             => array_values($uniqueStudents),
            'attendances'          => $attendances,
            'staffs'               => $staffs,
            'is_searched'          => !empty($selectedClassIds),
        ];

        return view('layout/header', $data)
             . view('admin/ptm/attendance', $data)
             . view('layout/footer', $data);
    }

    /**
     * AJAX: Get PTM details.
     */
    public function get_ptm_detail(int $id): ResponseInterface
    {
        $ptm = $this->ptmModel->get($id);
        if (!empty($ptm)) {
            $ptm['ptm_date_formatted'] = date('d-m-Y', strtotime($ptm['ptm_date']));
            $ptm['targets'] = $this->ptmModel->getTargets($id);
            return $this->respondJson(['status' => 1, 'data' => $ptm]);
        }

        return $this->respondJson(['status' => 0, 'message' => 'PTM not found']);
    }

    /**
     * AJAX: Multi-class section loader.
     */
    public function get_sections_multi(): ResponseInterface
    {
        $classIds = (array)$this->request->getPost('class_ids');
        $sections = [];

        if (!empty($classIds)) {
            foreach ($classIds as $classId) {
                $secs = $this->sectionModel->getByClassId((int)$classId);
                foreach ($secs as $sec) {
                    $sections[$sec['section_id']] = $sec;
                }
            }
        }

        return $this->respondJson(array_values($sections));
    }

    /**
     * Helper: save class-section targets.
     */
    protected function saveTargets(int $ptmId, array $targets): void
    {
        $db = \Config\Database::connect();
        $db->table('ptm_targets')->where('ptm_id', $ptmId)->delete();

        if (!empty($targets)) {
            $insertData = [];
            foreach ($targets as $target) {
                $parts = explode('-', $target);
                if (count($parts) === 2) {
                    $insertData[] = [
                        'ptm_id'     => $ptmId,
                        'class_id'   => (int)$parts[0],
                        'section_id' => (int)$parts[1],
                    ];
                }
            }

            if (!empty($insertData)) {
                $db->table('ptm_targets')->insertBatch($insertData);
            }
        }
    }

    /**
     * Normalize date string to YYYY-MM-DD.
     */
    protected function normalizeDate(string $date): string
    {
        $date = trim($date);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }
        return date('Y-m-d', strtotime(str_replace('/', '-', $date)));
    }
}
