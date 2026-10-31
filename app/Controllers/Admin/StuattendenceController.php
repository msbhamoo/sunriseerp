<?php

namespace App\Controllers\Admin;

use App\Models\AttendencetypeModel;
use App\Models\ClassModel;
use App\Models\SectionModel;
use App\Models\SessionModel;
use App\Models\SettingModel;
use App\Models\StuattendenceModel;

/**
 * StuattendenceController
 *
 * Full MVC Web Controller for managing daily student attendance.
 * Replaces legacy CI3 application/controllers/admin/Stuattendence.php.
 */
class StuattendenceController extends AdminController
{
    protected ClassModel $classModel;
    protected SectionModel $sectionModel;
    protected StuattendenceModel $stuattendenceModel;
    protected AttendencetypeModel $attendencetypeModel;
    protected SettingModel $settingModel;
    protected SessionModel $sessionModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->classModel          = new ClassModel();
        $this->sectionModel        = new SectionModel();
        $this->stuattendenceModel  = new StuattendenceModel();
        $this->attendencetypeModel = new AttendencetypeModel();
        $this->settingModel        = new SettingModel();
        $this->sessionModel        = new SessionModel();
    }

    /**
     * Daily Student Attendance Form & Search Screen
     */
    public function index()
    {
        $session = session();
        $session->set('top_menu', 'Attendance');
        $session->set('sub_menu', 'stuattendence/index');

        $data = [
            'title'               => 'Student Attendance',
            'classlist'           => $this->classModel->get(),
            'attendencetypeslist' => $this->attendencetypeModel->get(),
            'sch_setting'         => $this->settingModel->getSetting(),
            'class_id'            => '',
            'section_id'          => '',
            'date'                => '',
            'resultlist'          => [],
            'is_first_time_attendance' => true,
        ];

        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $classId   = (int)$this->request->getPost('class_id');
            $sectionId = (int)$this->request->getPost('section_id');
            $dateRaw   = (string)$this->request->getPost('date');
            $search    = (string)$this->request->getPost('search');

            $data['class_id']   = $classId;
            $data['section_id'] = $sectionId;
            $data['date']       = $dateRaw;

            $formattedDate = !empty($dateRaw) ? date('Y-m-d', strtotime(str_replace('/', '-', $dateRaw))) : date('Y-m-d');

            if ($search === 'saveattendence') {
                $sessionAry = (array)($this->request->getPost('student_session') ?? []);
                $insertBatch = [];
                $updateBatch = [];

                foreach ($sessionAry as $studentSessionId) {
                    $attendanceId   = (int)$this->request->getPost('attendendence_id' . $studentSessionId);
                    $attendanceType = (int)$this->request->getPost('attendencetype' . $studentSessionId);
                    $remark         = (string)$this->request->getPost('remark' . $studentSessionId);

                    $inTimeRaw  = (string)$this->request->getPost('in_time_' . $studentSessionId);
                    $outTimeRaw = (string)$this->request->getPost('out_time_' . $studentSessionId);

                    $inTime  = (!empty($inTimeRaw) && $attendanceType !== 4 && $attendanceType !== 5) ? date('H:i:s', strtotime($inTimeRaw)) : null;
                    $outTime = (!empty($outTimeRaw) && $attendanceType !== 4 && $attendanceType !== 5) ? date('H:i:s', strtotime($outTimeRaw)) : null;

                    $record = [
                        'student_session_id' => (int)$studentSessionId,
                        'attendence_type_id' => $attendanceType,
                        'remark'             => $remark,
                        'in_time'            => $inTime,
                        'out_time'           => $outTime,
                        'date'               => $formattedDate,
                    ];

                    if ($attendanceId > 0) {
                        $record['id'] = $attendanceId;
                        $updateBatch[] = $record;
                    } else {
                        $insertBatch[] = $record;
                    }
                }

                $this->stuattendenceModel->add($insertBatch, $updateBatch);
                $session->setFlashdata('msg', '<div class="alert alert-success text-left">Attendance Saved Successfully</div>');
                return redirect()->to(base_url('admin/stuattendence/index'));
            }

            // Search branch
            if (!empty($classId) && !empty($sectionId)) {
                $data['resultlist'] = $this->stuattendenceModel->searchAttendenceClassSection($classId, $sectionId, $formattedDate);
                foreach ($data['resultlist'] as $row) {
                    if (!empty($row['attendence_type_id'])) {
                        $data['is_first_time_attendance'] = false;
                        break;
                    }
                }
            }
        }

        return view('layout/header', $data)
            . view('admin/stuattendence/attendenceList', $data)
            . view('layout/footer', $data);
    }
}
