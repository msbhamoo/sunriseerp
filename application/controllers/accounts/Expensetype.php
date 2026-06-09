<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Expensetype extends MY_Addon_AccountsController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('acc_expense_type', 'can_view')) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/expensetype');
        
        $data['title'] = $this->lang->line('expense_type_list');
        
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('type', $this->lang->line('type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('accounts/expense_type/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (!$this->rbac->hasPrivilege('acc_expense_type', 'can_add')) {
                access_denied();
            }
            $data_insert = array(
                'name' => $this->input->post('name'),
                'type' => $this->input->post('type'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            );
            $this->accexpensetype_model->add($data_insert);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('accounts/expensetype/index');
        }
    }

    public function getlist()
    {
        if (!$this->rbac->hasPrivilege('acc_expense_type', 'can_view')) {
            access_denied();
        }
        $result = $this->accexpensetype_model->getDatatableExpenseTypes();
        $result = json_decode($result);
        $dt_data = array();

        if (!empty($result->data)) {
            foreach ($result->data as $value) {
                $row = array();
                $action = "<div class='d-flex gap-0-5 text-right float-right'>";
                
                if ($this->rbac->hasPrivilege('acc_expense_type', 'can_edit')) {
                    $action .= "<a href='" . site_url('accounts/expensetype/edit/' . $value->id) . "' class='btn btn-primary btn-xs' data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('acc_expense_type', 'can_delete')) {
                    $action .= "<a href='" . site_url('accounts/expensetype/delete/' . $value->id) . "' class='btn btn-primary btn-xs' data-toggle='tooltip' title='" . $this->lang->line('delete') . "' onclick='return confirm(\"" . $this->lang->line('delete_confirm') . "\");'><i class='fa fa-remove'></i></a>";
                }
                $action .= "</div>";

                $row[] = $value->name;
                $row[] = ucfirst($value->type);
                $row[] = ($value->is_active) ? 'Yes' : 'No';
                $row[] = $action;
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw" => intval($result->draw),
            "recordsTotal" => intval($result->recordsTotal),
            "recordsFiltered" => intval($result->recordsFiltered),
            "data" => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('acc_expense_type', 'can_edit')) {
            access_denied();
        }

        $data['title'] = $this->lang->line('edit_expense_type');
        $data['id'] = $id;
        $data['expense_type'] = $this->accexpensetype_model->get($id);

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('type', $this->lang->line('type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('accounts/expense_type/edit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data_update = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'type' => $this->input->post('type'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            );
            $this->accexpensetype_model->add($data_update);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('accounts/expensetype/index');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('acc_expense_type', 'can_delete')) {
            access_denied();
        }
        $this->accexpensetype_model->delete($id);
        redirect('accounts/expensetype/index');
    }
}
