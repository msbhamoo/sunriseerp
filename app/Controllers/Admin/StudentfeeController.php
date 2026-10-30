<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\SectionModel;
use App\Models\StudentfeemasterModel;
use App\Models\StudentModel;

/**
 * StudentfeeController
 *
 * Migrated student fees collection & search controller.
 */
class StudentfeeController extends BaseController
{
    protected ClassModel $classModel;
    protected SectionModel $sectionModel;
    protected StudentModel $studentModel;
    protected StudentfeemasterModel $studentfeemasterModel;
    protected \App\Models\SettingModel $settingModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->classModel = new ClassModel();
        $this->sectionModel = new SectionModel();
        $this->studentModel = new StudentModel();
        $this->studentfeemasterModel = new StudentfeemasterModel();
        $this->settingModel = new \App\Models\SettingModel();
    }

    public function dashboard()
    {
        if (!$this->rbac->hasPrivilege('fees_dashboard', 'can_view')) {
            access_denied();
        }

        $session = service('session');
        $session->set('top_menu', 'fees_collection');
        $session->set('sub_menu', 'studentfee/dashboard');

        $data['title'] = $this->lang->line('fees_dashboard');
        $data['sch_setting'] = (object)$this->settingModel->getSetting();

        $session_id = $this->settingModel->getCurrentSession();
        $cache_file = sys_get_temp_dir() . '/lms_fee_dashboard_stats_' . $session_id . '.json';
        $cache_ttl = 300; // 5 minutes cache
        $stats = null;

        if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_ttl)) {
            $stats = json_decode(@file_get_contents($cache_file), true);
        }

        if (!$stats || !is_array($stats)) {
            $stats = $this->studentfeemasterModel->getFeesDashboardStats();
            @file_put_contents($cache_file, json_encode($stats));
        }

        $data['stats'] = $stats;

        return view('studentfee/fees_dashboard', $data);
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }

        $session = service('session');
        $session->set('top_menu', lang('Admin.fees_collection') ?: 'Fees Collection');
        $session->set('sub_menu', 'studentfee/index');

        $data['sch_setting'] = (object)$this->settingModel->getSetting();
        $data['title']       = 'student fees';
        $data['classlist']   = $this->classModel->get();

        return view('studentfee/studentfeeSearch', $data);
    }

    public function search()
    {
        $search_type = (string)$this->request->getPost('search_type');

        if ($search_type === 'class_search') {
            $rules = ['class_id' => 'required'];
            if (!$this->validate($rules)) {
                $error = ['class_id' => form_error('class_id') ?: 'Class is required'];
                return $this->response->setJSON(['status' => 0, 'error' => $error]);
            }
        } elseif ($search_type === 'keyword_search') {
            $rules = ['search_text' => 'required'];
            if (!$this->validate($rules)) {
                $error = ['search_text' => form_error('search_text') ?: 'Search text is required'];
                return $this->response->setJSON(['status' => 0, 'error' => $error]);
            }
        }

        $params = [
            'class_id'    => $this->request->getPost('class_id'),
            'section_id'  => $this->request->getPost('section_id'),
            'search_type' => $search_type,
            'search_text' => $this->request->getPost('search_text')
        ];

        return $this->response->setJSON(['status' => 1, 'error' => '', 'params' => $params]);
    }

    public function ajaxSearch()
    {
        $class       = $this->request->getPost('class_id');
        $section     = $this->request->getPost('section_id');
        $search_text = (string)$this->request->getPost('search_text');
        $search_type = (string)$this->request->getPost('search_type');

        if ($search_type === 'class_search') {
            $studentsJson = $this->studentModel->getDatatableByClassSection($class, $section);
        } else {
            $studentsJson = $this->studentModel->getDatatableByFullTextSearch($search_text);
        }

        $students = json_decode($studentsJson);
        $sch_setting = (object)$this->settingModel->getSetting();

        $dt_data = [];
        if (!empty($students->data)) {
            foreach ($students->data as $student) {
                $row = [];
                $row[] = $student->class;
                $row[] = $student->section;
                $row[] = $student->admission_no;
                $fullName = $this->customlib->getFullName(
                    $student->firstname,
                    $student->middlename,
                    $student->lastname,
                    !empty($sch_setting->middlename),
                    !empty($sch_setting->lastname)
                );
                $row[] = "<a href='" . base_url() . "student/view/" . $student->id . "'>" . htmlspecialchars($fullName) . "</a>";

                if (!empty($sch_setting->father_name)) {
                    $row[] = htmlspecialchars($student->father_name ?? '');
                }
                $row[] = $this->customlib->dateformat($student->dob);
                $row[] = htmlspecialchars($student->mobileno ?? '');
                $row[] = "<a href='" . site_url('studentfee/addfee/' . $student->student_session_id) . "' class='btn btn-info btn-xs'>" . $this->lang->line('collect_fees') . "</a>";

                $dt_data[] = $row;
            }
        }

        return $this->response->setJSON([
            'draw'            => intval($students->draw ?? 1),
            'recordsTotal'    => intval($students->recordsTotal ?? 0),
            'recordsFiltered' => intval($students->recordsFiltered ?? 0),
            'data'            => $dt_data,
        ]);
    }
}
