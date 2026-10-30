<?php

namespace App\Controllers\Admin;

use App\Models\ClassModel;
use App\Models\FeegroupModel;

/**
 * FeegroupController
 *
 * Full MVC Web Controller for managing Fee Groups.
 * Replaces legacy CI3 application/controllers/admin/Feegroup.php.
 */
class FeegroupController extends AdminController
{
    protected FeegroupModel $feegroupModel;
    protected ClassModel $classModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->feegroupModel = new FeegroupModel();
        $this->classModel    = new ClassModel();
    }

    /**
     * Fee Group List & Create screen
     */
    public function index()
    {
        $session = session();
        $session->set('top_menu', 'Fees Collection');
        $session->set('sub_menu', 'admin/feegroup');

        $data = [
            'title'        => 'Fee Group List',
            'feegroupList' => $this->feegroupModel->get(),
            'classlist'    => $this->classModel->get(),
        ];

        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|trim',
            ];

            if ($this->validate($rules)) {
                $name = (string)$this->request->getPost('name');

                if ($this->feegroupModel->check_data_exists($name)) {
                    $session->setFlashdata('msg', '<div class="alert alert-danger text-left">Record already exists</div>');
                } else {
                    $postData = [
                        'name'        => $name,
                        'description' => (string)$this->request->getPost('description'),
                        'class_id'    => $this->request->getPost('class_id'),
                    ];
                    $this->feegroupModel->add($postData);
                    $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Saved Successfully</div>');
                    return redirect()->to(base_url('admin/feegroup/index'));
                }
            }
        }

        return view('layout/header', $data)
            . view('admin/feegroup/feegroupList', $data)
            . view('layout/footer', $data);
    }

    /**
     * Edit Fee Group screen
     */
    public function edit(int $id)
    {
        $session = session();
        $session->set('top_menu', 'Fees Collection');
        $session->set('sub_menu', 'admin/feegroup');

        $data = [
            'title'        => 'Edit Fee Group',
            'id'           => $id,
            'feegroup'     => $this->feegroupModel->get($id),
            'feegroupList' => $this->feegroupModel->get(),
            'classlist'    => $this->classModel->get(),
        ];

        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|trim',
            ];

            if ($this->validate($rules)) {
                $name = (string)$this->request->getPost('name');

                if ($this->feegroupModel->check_data_exists($name, $id)) {
                    $session->setFlashdata('msg', '<div class="alert alert-danger text-left">Record already exists</div>');
                } else {
                    $postData = [
                        'id'          => $id,
                        'name'        => $name,
                        'description' => (string)$this->request->getPost('description'),
                        'class_id'    => $this->request->getPost('class_id'),
                    ];
                    $this->feegroupModel->add($postData);
                    $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Updated Successfully</div>');
                    return redirect()->to(base_url('admin/feegroup/index'));
                }
            }
        }

        return view('layout/header', $data)
            . view('admin/feegroup/feegroupEdit', $data)
            . view('layout/footer', $data);
    }

    /**
     * Delete Fee Group
     */
    public function delete(int $id)
    {
        $this->feegroupModel->remove($id);

        $session = session();
        $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Deleted Successfully</div>');
        return redirect()->to(base_url('admin/feegroup/index'));
    }
}
