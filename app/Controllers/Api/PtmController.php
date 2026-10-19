<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PtmModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * PtmController (API v2)
 *
 * Modern RESTful API endpoint for Parent-Teacher Meetings powered by CodeIgniter 4.
 */
class PtmController extends BaseController
{
    protected PtmModel $ptmModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->ptmModel = new PtmModel();
    }

    /**
     * GET /api/v2/ptm
     * List all PTM schedules with targets.
     */
    public function index(): ResponseInterface
    {
        $ptms = $this->ptmModel->get();

        foreach ($ptms as &$ptm) {
            $ptm['targets'] = $this->ptmModel->getTargets((int)$ptm['id']);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'count'   => count($ptms),
            'data'    => $ptms,
        ]);
    }

    /**
     * GET /api/v2/ptm/(:num)
     * Get details for a single PTM.
     */
    public function show(int $id): ResponseInterface
    {
        $ptm = $this->ptmModel->get($id);

        if (empty($ptm)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'PTM record not found.',
            ]);
        }

        $ptm['targets'] = $this->ptmModel->getTargets($id);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $ptm,
        ]);
    }

    /**
     * GET /api/v2/ptm/(:num)/attendances
     * Get attendance records for a PTM.
     */
    public function attendances(int $id): ResponseInterface
    {
        $ptm = $this->ptmModel->get($id);

        if (empty($ptm)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'PTM record not found.',
            ]);
        }

        $attendances = $this->ptmModel->getStudentAttendances($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'ptm_id'  => $id,
            'count'   => count($attendances),
            'data'    => $attendances,
        ]);
    }

    /**
     * POST /api/v2/ptm/attendance
     * Save/update student attendance for a PTM.
     */
    public function saveAttendance(): ResponseInterface
    {
        $rules = [
            'ptm_id'             => 'required|is_natural_no_zero',
            'student_session_id' => 'required|is_natural_no_zero',
            'status'             => 'required|in_list[present,absent]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'ptm_id'               => (int)$this->request->getPost('ptm_id'),
            'student_session_id'   => (int)$this->request->getPost('student_session_id'),
            'status'               => $this->request->getPost('status'),
            'remarks'              => $this->request->getPost('remarks'),
            'followup_required'    => $this->request->getPost('followup_required') ? 1 : 0,
            'followup_assigned_to' => $this->request->getPost('followup_assigned_to') ?: null,
            'followup_date'        => $this->request->getPost('followup_date') ?: null,
        ];

        $recordId = $this->ptmModel->saveAttendance($data);

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Attendance record saved successfully.',
            'record_id' => $recordId,
        ]);
    }
}
