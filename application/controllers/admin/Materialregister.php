<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Materialregister extends Admin_Controller
{
    private $upload_path = './uploads/front_office/material/';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('materialregister_model');
        $this->load->helper('material_register');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('material_register', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'front_office');
        $this->session->set_userdata('sub_menu', 'admin/materialregister');

        $this->form_validation->set_rules('direction', $this->lang->line('direction'), 'required');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('material_name', $this->lang->line('material_name'), 'required');
        $this->form_validation->set_rules('image', $this->lang->line('attachment'), 'callback_handle_upload[image]');

        if ($this->form_validation->run() == false) {
            $this->materialregister_model->ensure_sidebar_title();
            $data['material_list']     = $this->materialregister_model->get();
            $data['stafflist']         = $this->staff_model->searchFullText("", 1);
            $data['masters']           = $this->grouped_masters();
            $data['next_gate_pass_no'] = $this->materialregister_model->generate_gate_pass_no();
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/materialregisterview', $data);
            $this->load->view('layout/footer');
        } else {
            if (!$this->rbac->hasPrivilege('material_register', 'can_add')) {
                access_denied();
            }
            $insert          = $this->build_data();
            $insert['image'] = $this->do_upload();
            $this->materialregister_model->add($insert);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/materialregister');
        }
    }

    public function get_details_json($id)
    {
        if (!$this->rbac->hasPrivilege('material_register', 'can_view')) {
            echo json_encode(array('status' => 'fail', 'message' => $this->lang->line('access_denied')));
            return;
        }
        $row = $this->materialregister_model->get($id);
        if (empty($row)) {
            echo json_encode(array('status' => 'fail', 'message' => 'Record not found'));
            return;
        }
        $row['formatted_date'] = date($this->customlib->getSchoolDateFormat(), strtotime($row['date']));
        $row['staff_full_name'] = !empty($row['staff_name']) ? ($row['staff_name'] . ' ' . $row['staff_surname'] . ($row['staff_employee_id'] ? ' (' . $row['staff_employee_id'] . ')' : '')) : '';
        echo json_encode(array('status' => 'success', 'data' => $row));
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('material_register', 'can_edit')) {
            access_denied();
        }

        $this->form_validation->set_rules('direction', $this->lang->line('direction'), 'required');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('material_name', $this->lang->line('material_name'), 'required');
        $this->form_validation->set_rules('image', $this->lang->line('attachment'), 'callback_handle_upload[image]');

        if ($this->form_validation->run() == false) {
            $data['material_data'] = $this->materialregister_model->get($id);
            if (empty($data['material_data'])) {
                show_404();
            }
            $data['stafflist'] = $this->staff_model->searchFullText("", 1);
            $data['masters']   = $this->grouped_masters();
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/materialregistereditview', $data);
            $this->load->view('layout/footer');
        } else {
            $existing        = $this->materialregister_model->get($id);
            $update          = $this->build_data();
            $new_image       = $this->do_upload();
            if (!empty($new_image)) {
                if (!empty($existing['image']) && file_exists($this->upload_path . $existing['image'])) {
                    @unlink($this->upload_path . $existing['image']);
                }
                $update['image'] = $new_image;
            }
            $this->materialregister_model->update($id, $update);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/materialregister');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('material_register', 'can_delete')) {
            access_denied();
        }
        $row = $this->materialregister_model->get($id);
        if (!empty($row['image']) && file_exists($this->upload_path . $row['image'])) {
            @unlink($this->upload_path . $row['image']);
        }
        $this->materialregister_model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/materialregister');
    }

    public function download($id)
    {
        if (!$this->rbac->hasPrivilege('material_register', 'can_view')) {
            access_denied();
        }
        $row = $this->materialregister_model->get($id);
        if (!empty($row['image']) && file_exists($this->upload_path . $row['image'])) {
            $this->load->helper('download');
            force_download($this->upload_path . $row['image'], null);
        } else {
            show_404();
        }
    }

    // AJAX: quick-add a new value to a dropdown master list.
    public function additem()
    {
        if (!$this->rbac->hasPrivilege('material_register', 'can_add')) {
            echo json_encode(array('status' => 'fail', 'message' => $this->lang->line('access_denied')));
            return;
        }
        $type = $this->input->post('type');
        $name = trim((string) $this->input->post('name'));
        if ($name === '') {
            echo json_encode(array('status' => 'fail', 'message' => $this->lang->line('required_field')));
            return;
        }
        $saved = $this->materialregister_model->add_master($type, $name);
        if ($saved === false) {
            echo json_encode(array('status' => 'fail', 'message' => $this->lang->line('error')));
            return;
        }
        echo json_encode(array('status' => 'success', 'name' => $saved));
    }

    // Master lists grouped by type for the views.
    private function grouped_masters()
    {
        $out = array(
            'item'       => array(),
            'quantity'   => array(),
            'unit'       => array(),
            'party'      => array(),
            'department' => array(),
            'vehicle'    => array(),
            'driver'     => array(),
        );
        foreach ($this->materialregister_model->get_masters() as $row) {
            if (isset($out[$row['type']])) {
                $out[$row['type']][] = $row['name'];
            }
        }
        // Include vehicles & drivers from transport table if available
        if ($this->db->table_exists('vehicles')) {
            $v_rows = $this->db->select('vehicle_no, driver_name')->from('vehicles')->get()->result_array();
            foreach ($v_rows as $vr) {
                if (!empty($vr['vehicle_no']) && !in_array($vr['vehicle_no'], $out['vehicle'], true)) {
                    $out['vehicle'][] = $vr['vehicle_no'];
                }
                if (!empty($vr['driver_name']) && !in_array($vr['driver_name'], $out['driver'], true)) {
                    $out['driver'][] = $vr['driver_name'];
                }
            }
        }
        // Include previously registered vehicle numbers & driver names
        $reg_v = $this->db->select('vehicle_no, driver_name')->from('material_register')->get()->result_array();
        foreach ($reg_v as $rv) {
            if (!empty($rv['vehicle_no']) && !in_array($rv['vehicle_no'], $out['vehicle'], true)) {
                $out['vehicle'][] = $rv['vehicle_no'];
            }
            if (!empty($rv['driver_name']) && !in_array($rv['driver_name'], $out['driver'], true)) {
                $out['driver'][] = $rv['driver_name'];
            }
        }
        return $out;
    }

    // Map posted fields to a DB row.
    private function build_data()
    {
        $direction    = $this->input->post('direction') == 'outward' ? 'outward' : 'inward';
        $gate_pass_no = trim((string) $this->input->post('gate_pass_no'));
        if ($gate_pass_no === '') {
            $gate_pass_no = $this->materialregister_model->generate_gate_pass_no();
        }
        return array(
            'direction'     => $direction,
            'date'          => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
            'material_name' => $this->input->post('material_name'),
            'quantity'      => $this->input->post('quantity'),
            'unit'          => $this->input->post('unit'),
            'carried_by'    => $this->input->post('carried_by'),
            'contact'       => $this->input->post('contact'),
            'party_name'    => $this->input->post('party_name'),
            'vehicle_no'    => $this->input->post('vehicle_no'),
            'gate_pass_no'  => $gate_pass_no,
            'driver_name'   => $this->input->post('driver_name'),
            'staff_id'      => $this->input->post('staff_id') ? $this->input->post('staff_id') : null,
            'department'    => $this->input->post('department'),
            'approved_by'   => $this->input->post('approved_by'),
            'in_time'       => $this->input->post('in_time'),
            'out_time'      => $this->input->post('out_time'),
            'remarks'       => $this->input->post('remarks'),
        );
    }

    // Validation callback: allow empty, otherwise restrict type/size.
    public function handle_upload($str, $field)
    {
        if (empty($_FILES[$field]['name'])) {
            return true;
        }
        $allowed_ext  = array('jpg', 'jpeg', 'png', 'gif', 'pdf');
        $ext          = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_ext)) {
            $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
            return false;
        }
        if ($_FILES[$field]['size'] > 5242880) { // 5 MB
            $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . ' 5 MB');
            return false;
        }
        return true;
    }

    // Move the uploaded attachment; returns stored filename or ''.
    private function do_upload($field = 'image')
    {
        if (empty($_FILES[$field]['name'])) {
            return '';
        }
        if (!is_dir($this->upload_path)) {
            @mkdir($this->upload_path, 0755, true);
        }
        $ext      = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        $filename = 'material_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
        if (move_uploaded_file($_FILES[$field]['tmp_name'], $this->upload_path . $filename)) {
            return $filename;
        }
        return '';
    }
}
