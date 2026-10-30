<?php

namespace App\Controllers\Admin;

use App\Models\FeegroupModel;
use App\Models\FeesessiongroupModel;
use App\Models\FeetypeModel;

/**
 * FeemasterController
 *
 * Full MVC Web Controller for managing Fee Masters.
 * Replaces legacy CI3 application/controllers/admin/Feemaster.php.
 */
class FeemasterController extends AdminController
{
    protected FeegroupModel $feegroupModel;
    protected FeetypeModel $feetypeModel;
    protected FeesessiongroupModel $feesessiongroupModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->feegroupModel        = new FeegroupModel();
        $this->feetypeModel         = new FeetypeModel();
        $this->feesessiongroupModel = new FeesessiongroupModel();
    }

    /**
     * Fee Master List & Configuration Screen
     */
    public function index()
    {
        $session = session();
        $session->set('top_menu', 'Fees Collection');
        $session->set('sub_menu', 'admin/feemaster');

        $data = [
            'title'         => 'Fees Master List',
            'feegroupList'  => $this->feegroupModel->get(),
            'feetypeList'   => $this->feetypeModel->get(),
            'feemasterList' => $this->feesessiongroupModel->getFeesByGroup(null, 0),
        ];

        return view('layout/header', $data)
            . view('admin/feemaster/feemasterList', $data)
            . view('layout/footer', $data);
    }

    /**
     * Delete Fee Master Session Group
     */
    public function delete(int $id)
    {
        $this->feesessiongroupModel->remove($id);

        $session = session();
        $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Deleted Successfully</div>');
        return redirect()->to(base_url('admin/feemaster'));
    }

    /**
     * Delete Fee Groups Fee Type item
     */
    public function deletegrp(int $id)
    {
        $this->feesessiongroupModel->removeGroupFeetype($id);

        $session = session();
        $session->setFlashdata('msg', '<div class="alert alert-success text-left">Record Deleted Successfully</div>');
        return redirect()->to(base_url('admin/feemaster'));
    }
}
