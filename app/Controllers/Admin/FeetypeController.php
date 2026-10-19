<?php

namespace App\Controllers\Admin;

use App\Models\FeetypeModel;

/**
 * FeetypeController
 *
 * Full MVC Web Controller for managing Fee Types.
 * Replaces legacy CI3 application/controllers/admin/Feetype.php.
 */
class FeetypeController extends AdminController
{
    protected FeetypeModel $feetypeModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->feetypeModel = new FeetypeModel();
    }

    /**
     * Fee Type List & Add screen
     */
    public function index()
    {
        $session = session();
        $session->set('top_menu', 'Fees Collection');
        $session->set('sub_menu', 'feetype/index');

        $data = [
            'title'       => 'Fee Type List',
            'feetypeList' => $this->feetypeModel->get(),
        ];

        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|trim',
                'code' => 'required|trim',
            ];

            if ($this->validate($rules)) {
                $name = (string)$this->request->getPost('name');
                $code = (string)$this->request->getPost('code');

                if ($this->feetypeModel->check_data_exists($name)) {
                    $session->setFlashdata('msg', '<div class="alert alert-danger text-left">Record already exists</div>');
                } else {
                    $postData = [
                        'type'        => $name,
                        'code'        => $code,
                        'description' => (string)$this->request->getPost('description'),
                    ];
                    $this->feetypeModel->add($postData);
                    $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Saved Successfully</div>');
                    return redirect()->to(base_url('admin/feetype/index'));
                }
            }
        }

        return view('layout/header', $data)
            . view('admin/feetype/feetypeList', $data)
            . view('layout/footer', $data);
    }

    /**
     * Edit Fee Type screen
     */
    public function edit(int $id)
    {
        $session = session();
        $session->set('top_menu', 'Fees Collection');
        $session->set('sub_menu', 'feetype/index');

        $data = [
            'title'       => 'Edit Fee Type',
            'id'          => $id,
            'feetype'     => $this->feetypeModel->get($id),
            'feetypeList' => $this->feetypeModel->get(),
        ];

        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|trim',
                'code' => 'required|trim',
            ];

            if ($this->validate($rules)) {
                $name = (string)$this->request->getPost('name');
                $code = (string)$this->request->getPost('code');

                if ($this->feetypeModel->check_data_exists($name, $id)) {
                    $session->setFlashdata('msg', '<div class="alert alert-danger text-left">Record already exists</div>');
                } else {
                    $postData = [
                        'id'          => $id,
                        'type'        => $name,
                        'code'        => $code,
                        'description' => (string)$this->request->getPost('description'),
                    ];
                    $this->feetypeModel->add($postData);
                    $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Updated Successfully</div>');
                    return redirect()->to(base_url('admin/feetype/index'));
                }
            }
        }

        return view('layout/header', $data)
            . view('admin/feetype/feetypeEdit', $data)
            . view('layout/footer', $data);
    }

    /**
     * Delete Fee Type
     */
    public function delete(int $id)
    {
        $this->feetypeModel->remove($id);

        $session = session();
        $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Deleted Successfully</div>');
        return redirect()->to(base_url('admin/feetype/index'));
    }
}
