<?php

namespace App\Controllers\Admin;

use App\Models\ClassModel;
use App\Models\ClasssectionModel;
use App\Models\SectionModel;
use App\Models\StudentModel;

/**
 * ClassesController
 *
 * Full MVC Web Controller for managing classes and class-section associations.
 * Replaces legacy CI3 application/controllers/Classes.php.
 */
class ClassesController extends AdminController
{
    protected ClassModel $classModel;
    protected SectionModel $sectionModel;
    protected ClasssectionModel $classsectionModel;
    protected StudentModel $studentModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->classModel        = new ClassModel();
        $this->sectionModel      = new SectionModel();
        $this->classsectionModel = new ClasssectionModel();
        $this->studentModel      = new StudentModel();
    }

    /**
     * Class list and create screen
     */
    public function index()
    {
        $session = session();
        $session->set('top_menu', 'Academics');
        $session->set('sub_menu', 'classes/index');

        $data = [
            'title'      => 'Add Class',
            'title_list' => 'Class List',
        ];

        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $rules = [
                'class'      => 'required|trim',
                'sections.*' => 'trim',
            ];

            if ($this->validate($rules)) {
                $className = (string)$this->request->getPost('class');
                $sections  = (array)$this->request->getPost('sections');

                // Check uniqueness
                $existing = $this->classModel->check_data_exists($className);
                if ($existing) {
                    $session->setFlashdata('msg', '<div class="alert alert-danger text-left">Record already exists</div>');
                } else {
                    $this->classsectionModel->add(['class' => $className], $sections);
                    $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Saved Successfully</div>');
                    return redirect()->to(base_url('classes'));
                }
            }
        }

        $data['vehiclelist']  = $this->sectionModel->getAll();
        $data['vehroutelist'] = $this->classsectionModel->getByID();

        return view('layout/header', $data)
            . view('class/classList', $data)
            . view('layout/footer', $data);
    }

    /**
     * Edit class screen
     */
    public function edit(int $id)
    {
        $session = session();
        $session->set('top_menu', 'Academics');
        $session->set('sub_menu', 'classes/index');

        $data = [
            'title'      => 'Edit Class',
            'id'         => $id,
            'title_list' => 'Class List',
        ];

        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $rules = [
                'class'      => 'required|trim',
                'sections.*' => 'trim',
            ];

            if ($this->validate($rules)) {
                $className     = (string)$this->request->getPost('class');
                $sections      = (array)($this->request->getPost('sections') ?? []);
                $prevSections  = (array)($this->request->getPost('prev_sections') ?? []);
                $classId       = (int)($this->request->getPost('pre_class_id') ?: $id);

                // Check uniqueness (ignoring current class)
                $existing = $this->classModel->check_data_exists($className);
                if ($existing && (int)$existing->id !== $classId) {
                    $session->setFlashdata('msg', '<div class="alert alert-danger text-left">Record already exists</div>');
                } else {
                    $addResult    = array_diff($sections, $prevSections);
                    $deleteResult = array_diff($prevSections, $sections);

                    if (!empty($addResult)) {
                        $this->classsectionModel->add(['id' => $classId, 'class' => $className], array_values($addResult));
                    } else {
                        $this->classsectionModel->updateClass(['id' => $classId, 'class' => $className]);
                    }

                    if (!empty($deleteResult)) {
                        $this->classsectionModel->remove($classId, array_values($deleteResult));
                    }

                    $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Updated Successfully</div>');
                    return redirect()->to(base_url('classes/index'));
                }
            }
        }

        $vehroute = $this->classsectionModel->getByID($id);
        $data['vehroute']     = !empty($vehroute) ? $vehroute[0] : null;
        $data['vehiclelist']  = $this->sectionModel->getAll();
        $data['vehroutelist'] = $this->classsectionModel->getByID();

        return view('layout/header', $data)
            . view('class/classEdit', $data)
            . view('layout/footer', $data);
    }

    /**
     * Delete class
     */
    public function delete(int $id)
    {
        $this->classModel->remove($id);

        $session = session();
        $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Deleted Successfully</div>');
        return redirect()->to(base_url('classes'));
    }

    /**
     * Get sections for a class (AJAX endpoint)
     */
    public function get_section(int $id)
    {
        $sections = $this->classModel->get_section($id);
        return $this->response->setJSON(['sections' => $sections]);
    }
}
