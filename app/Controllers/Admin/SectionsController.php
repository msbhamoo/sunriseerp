<?php

namespace App\Controllers\Admin;

use App\Models\ClasssectionModel;
use App\Models\SectionModel;
use App\Models\StudentModel;

/**
 * SectionsController
 *
 * Full MVC Web Controller for managing sections.
 * Replaces legacy CI3 application/controllers/Sections.php.
 */
class SectionsController extends AdminController
{
    protected SectionModel $sectionModel;
    protected StudentModel $studentModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->sectionModel = new SectionModel();
        $this->studentModel = new StudentModel();
    }

    /**
     * Section list and create screen
     */
    public function index()
    {
        $session = session();
        $session->set('top_menu', 'Academics');
        $session->set('sub_menu', 'sections/index');

        $data = [
            'title'       => 'Section List',
            'sectionlist' => $this->sectionModel->getAll(),
        ];

        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $rules = [
                'section' => 'required|trim',
            ];

            if ($this->validate($rules)) {
                $sectionName = (string)$this->request->getPost('section');
                $this->sectionModel->add(['section' => $sectionName]);

                $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Saved Successfully</div>');
                return redirect()->to(base_url('sections/index'));
            }
        }

        return view('layout/header', $data)
            . view('section/sectionList', $data)
            . view('layout/footer', $data);
    }

    /**
     * View section
     */
    public function view(int $id)
    {
        $session = session();
        $session->set('top_menu', 'Academics');
        $session->set('sub_menu', 'sections/index');

        $data = [
            'title'   => 'Section List',
            'section' => $this->sectionModel->get($id),
        ];

        return view('layout/header', $data)
            . view('section/sectionShow', $data)
            . view('layout/footer', $data);
    }

    /**
     * Edit section screen
     */
    public function edit(int $id)
    {
        $session = session();
        $session->set('top_menu', 'Academics');
        $session->set('sub_menu', 'sections/index');

        $data = [
            'title'       => 'Edit Section',
            'id'          => $id,
            'section'     => $this->sectionModel->get($id),
            'sectionlist' => $this->sectionModel->getAll(),
        ];

        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $rules = [
                'section' => 'required|trim',
            ];

            if ($this->validate($rules)) {
                $sectionName = (string)$this->request->getPost('section');
                $this->sectionModel->add(['id' => $id, 'section' => $sectionName]);

                $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Updated Successfully</div>');
                return redirect()->to(base_url('sections/index'));
            }
        }

        return view('layout/header', $data)
            . view('section/sectionEdit', $data)
            . view('layout/footer', $data);
    }

    /**
     * Delete section
     */
    public function delete(int $id)
    {
        $this->sectionModel->remove($id);

        $session = session();
        $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Deleted Successfully</div>');
        return redirect()->to(base_url('sections/index'));
    }

    /**
     * Get sections by class (AJAX endpoint)
     */
    public function getByClass()
    {
        $classId = (int)$this->request->getGet('class_id');
        $data    = $this->sectionModel->getClassBySection($classId);
        return $this->response->setJSON($data);
    }

    /**
     * Get class teacher section (AJAX endpoint)
     */
    public function getClassTeacherSection()
    {
        $classId = (int)$this->request->getGet('class_id');
        $data    = $this->sectionModel->getClassBySection($classId);
        return $this->response->setJSON($data);
    }
}
