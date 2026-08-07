<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Certificateregister extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("certificateregister_model");
        $this->load->model("certificatetypes_model");
        $this->load->model("student_model");
        $this->load->model("class_model");
        $this->load->model("disable_reason_model");
        $this->load->model("studentfeemaster_model");
        $this->load->library('m_pdf');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index() {
        if (!$this->rbac->hasPrivilege('certificate', 'can_view')) {
            access_denied();
        }
        
        // Cleanup duplicates in student_certificate_types
        $this->db->query("DELETE t1 FROM student_certificate_types t1
            INNER JOIN student_certificate_types t2 
            WHERE t1.id > t2.id AND t1.certificate_name = t2.certificate_name");

        $data['title'] = 'Certificate Register';
        $data['disable_reasons'] = $this->disable_reason_model->get();
        $data['certificate_types'] = $this->certificatetypes_model->get();
        $data['theme_color'] = !empty($this->sch_setting_detail->theme_color) ? $this->sch_setting_detail->theme_color : '#3bb56e';
        
        $this->db->group_start();
        $this->db->like('certificate_name', 'Transfer');
        $this->db->or_like('certificate_name', 'TC');
        $this->db->group_end();
        $tc_type = $this->db->get('student_certificate_types')->row_array();
        if ($tc_type) {
            $next_num = max($tc_type['start_number'], $tc_type['current_number'] + 1);
            $data['default_tc_number'] = $tc_type['series_prefix'] . sprintf('%03d', $next_num);
        } else {
            $data['default_tc_number'] = '';
        }
        
        $certificates = $this->certificateregister_model->get();
        
        $grouped_certs = [];
        foreach ($certificates as $cert) {
            $student_id = $cert['student_id'];
            if (!isset($grouped_certs[$student_id])) {
                $grouped_certs[$student_id] = $cert;
                $grouped_certs[$student_id]['all_certs'] = [];
            }
            $grouped_certs[$student_id]['all_certs'][] = [
                'id' => $cert['id'],
                'certificate_name' => $cert['certificate_name'],
                'certificate_number' => $cert['certificate_number']
            ];
        }
        $data['grouped_certs'] = $grouped_certs;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/certificateregister/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function generate($student_id = null) {
        if (!$this->rbac->hasPrivilege('certificate', 'can_add')) {
            access_denied();
        }

        $data['title'] = 'Generate Certificate';
        $data['certificate_types'] = $this->certificatetypes_model->get();

        if ($student_id == null) {
            $data['classlist'] = $this->class_model->get();
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificateregister/generate_step1', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data['student'] = $this->student_model->get($student_id);
            if (empty($data['student'])) show_404();

            $data['student_id'] = $student_id;
            
            if ($this->input->post('generate')) {
                $type_id = $this->input->post('certificate_type_id');
                $type = $this->certificatetypes_model->get($type_id);

                // Check if student already has this certificate type
                $this->db->where('student_id', $student_id);
                $this->db->where('student_certificate_type_id', $type_id);
                $existing_cert = $this->db->get('student_certificate_register')->row_array();

                if (!empty($existing_cert)) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">This student already has a ' . $type['certificate_name'] . ' issued (No: ' . $existing_cert['certificate_number'] . '). Please view or download it from the Certificate Register.</div>');
                    redirect('admin/certificateregister/generate/'.$student_id);
                } else {
                    // Get next number
                    $next_num = max($type['start_number'], $type['current_number'] + 1);
                    $cert_no = $type['series_prefix'] . sprintf('%03d', $next_num);
                    
                    $insert_data = [
                        'student_id' => $student_id,
                        'student_certificate_type_id' => $type_id,
                        'certificate_number' => $cert_no,
                        'issue_date' => date('Y-m-d'),
                        'status' => 'Issued',
                        'remark' => $this->input->post('remark'),
                        'custom_data' => json_encode($this->input->post('custom_data') ?? []),
                        'generated_by' => $this->customlib->getStaffID()
                    ];
                    
                    $cert_id = $this->certificateregister_model->add($insert_data);
                    $this->certificatetypes_model->update_current_number($type_id, $next_num);
                    
                    // Disable student and mark as alumni if it is a Transfer Certificate
                    if (strpos(strtolower($type['certificate_name']), 'transfer') !== false || strpos(strtolower($type['certificate_name']), 'tc') !== false) {
                        $this->db->where('id', $student_id);
                        $this->db->update('students', ['is_active' => 'no', 'disable_at' => date('Y-m-d')]);

                        $this->db->where('student_id', $student_id);
                        $this->db->update('student_session', ['is_alumni' => 1]);
                    }

                    $this->session->set_flashdata('msg', '<div class="alert alert-success">Certificate Generated Successfully. <a href="'.base_url('admin/certificateregister/download/'.$cert_id).'" target="_blank">Download PDF</a></div>');
                    redirect('admin/scholarregister/view/'.$student_id);
                }
            }

            // Fetch custom fields to prepopulate TC extra inputs if any exist
            $data['student_custom_fields'] = [];
            $cf_query = $this->db->query("SELECT cf.name, cfv.field_value FROM custom_fields cf LEFT JOIN custom_field_values cfv ON cf.id = cfv.custom_field_id AND cfv.belong_table_id = ? WHERE cf.belong_to = 'students'", [$student_id]);
            if ($cf_query->num_rows() > 0) {
                foreach ($cf_query->result_array() as $row) {
                    $data['student_custom_fields'][strtolower(trim($row['name']))] = $row['field_value'];
                }
            }

            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificateregister/generate_step2', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function settings() {
        if (!$this->rbac->hasPrivilege('certificate', 'can_add')) { // Should use specific setting privilege
            access_denied();
        }
        
        if ($this->input->post('save')) {
            $fields_config = json_encode([
                'show_religion' => $this->input->post('show_religion') ? 1 : 0,
                'show_category' => $this->input->post('show_category') ? 1 : 0,
                'show_handicapped' => $this->input->post('show_handicapped') ? 1 : 0,
                'show_father_contact' => $this->input->post('show_father_contact') ? 1 : 0,
                'show_reason' => $this->input->post('show_reason') ? 1 : 0,
            ]);

            $data = [
                'certificate_name' => $this->input->post('certificate_name'),
                'series_prefix' => $this->input->post('series_prefix'),
                'start_number' => $this->input->post('start_number'),
                'fields_config' => $fields_config
            ];
            $id = $this->input->post('id');
            if ($id) {
                $this->certificatetypes_model->update($id, $data);
            } else {
                $this->certificatetypes_model->add($data);
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Settings Saved</div>');
            redirect('admin/certificateregister/settings');
        }

        $data['types'] = $this->certificatetypes_model->get();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/certificateregister/settings', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download($cert_id) {
        $cert = $this->certificateregister_model->get($cert_id);
        if (empty($cert)) show_404();

        $student_id = $cert['student_id'];
        $data['student_data'] = $this->student_model->get($student_id);
        $data['cert'] = $cert;
        $data['custom_data'] = json_decode($cert['custom_data'] ?? '{}', true) ?? [];
        $data['sch_setting_detail'] = $this->sch_setting_detail;
        $data['general_purpose_header'] = $this->setting_model->get_general_purpose_header();

        $this->load->model('scholarregister_model');
        $data['academic_history'] = $this->scholarregister_model->get_history_by_student($student_id);

        $html = $this->load->view('admin/certificateregister/print_certificate', $data, true);

        $mpdf = $this->m_pdf->load();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showWatermarkImage = true;
        $mpdf->WriteHTML($html);
        $mpdf->Output($cert['certificate_number'] . '.pdf', 'I');
    }

    public function revert($id) {
        if (!$this->rbac->hasPrivilege('certificate', 'can_delete')) {
            access_denied();
        }

        if ($this->input->get('all')) {
            $student_id = $id;
            // Re-activate student
            $this->db->where('id', $student_id);
            $this->db->update('students', ['is_active' => 'yes', 'disable_at' => null]);
            
            // Delete ALL certificate records for this student
            $this->db->where('student_id', $student_id);
            $this->db->delete('student_certificate_register');

            $this->session->set_flashdata('msg', '<div class="alert alert-success">All certificates deleted and student reverted successfully.</div>');
        } else {
            $cert_id = $id;
            $cert = $this->certificateregister_model->get($cert_id);
            if (!empty($cert)) {
                // Re-activate student
                $this->db->where('id', $cert['student_id']);
                $this->db->update('students', ['is_active' => 'yes', 'disable_at' => null]);
                
                // Delete certificate record
                $this->db->where('id', $cert_id);
                $this->db->delete('student_certificate_register');

                $this->session->set_flashdata('msg', '<div class="alert alert-success">Certificate deleted and student reverted successfully.</div>');
            }
        }
        redirect('admin/certificateregister');
    }

    public function search_student_ajax() {
        $searchterm = $this->input->post('searchterm');
        $current_session = $this->setting_model->getCurrentSession();
        
        $this->db->select('students.id as student_id, students.admission_no, students.firstname, students.lastname, students.father_name, students.guardian_name as mother_name, students.mobileno, students.image, classes.class as class_name, sections.section as section_name, student_session.id as student_session_id');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'classes.id = student_session.class_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.session_id', $current_session);
        
        $this->db->group_start();
        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->or_like('students.admission_no', $searchterm);
        $this->db->group_end();
        
        $this->db->limit(15);
        $result = $this->db->get()->result_array();
        
        foreach ($result as &$row) {
            $this->db->select('student_certificate_type_id');
            $this->db->where('student_id', $row['student_id']);
            $certs = $this->db->get('student_certificate_register')->result_array();
            $row['existing_certs'] = array_column($certs, 'student_certificate_type_id');
        }
        
        echo json_encode($result);
    }

    public function get_student_fee_summary_ajax() {
        $student_session_id = $this->input->post('student_session_id');
        if (empty($student_session_id)) {
            $student_session_id = $this->input->get('student_session_id');
        }
        
        $has_fee_privilege = $this->rbac->hasPrivilege('collect_fees', 'can_view');
        
        $academic_total = 0;
        $academic_collected = 0;
        $hostel_total = 0;
        $hostel_collected = 0;
        $transport_total = 0;
        $transport_collected = 0;
        $academic_due = 0;
        $hostel_due = 0;
        $transport_due = 0;

        $academic_last_collected_amount = 0;
        $academic_last_collected_date = '-';
        $academic_last_date_raw = '';

        $hostel_last_collected_amount = 0;
        $hostel_last_collected_date = '-';
        $hostel_last_date_raw = '';

        $transport_last_collected_amount = 0;
        $transport_last_collected_date = '-';
        $transport_last_date_raw = '';

        if ($has_fee_privilege) {
            $academic_fees = $this->studentfeemaster_model->getStudentFees($student_session_id);

            $mapped_hostel_fees = $this->db->get('hostel_fee_groups')->result_array();
            $hostel_fee_group_ids = array_column($mapped_hostel_fees, 'fee_groups_id');

            if (!empty($academic_fees)) {
                foreach ($academic_fees as $fee_master) {
                    if (!empty($fee_master->fees)) {
                        foreach ($fee_master->fees as $fee) {
                            $fee_amount = (isset($fee->amount)) ? $fee->amount : 0;
                            $collected = 0;
                            $amount_detail = json_decode($fee->amount_detail, true);
                            if (!empty($amount_detail)) {
                                foreach ($amount_detail as $detail) {
                                    $amt = isset($detail['amount']) ? (float)$detail['amount'] : 0;
                                    $disc = isset($detail['amount_discount']) ? (float)$detail['amount_discount'] : 0;
                                    $collected += $amt + $disc;

                                    $pdate = !empty($detail['date']) ? $detail['date'] : '';
                                    if (!empty($pdate)) {
                                        $is_hostel = (isset($fee->fee_groups_id) && in_array($fee->fee_groups_id, $hostel_fee_group_ids));
                                        if ($is_hostel) {
                                            if (empty($hostel_last_date_raw) || strtotime($pdate) > strtotime($hostel_last_date_raw)) {
                                                $hostel_last_date_raw = $pdate;
                                                $hostel_last_collected_date = date($this->customlib->getSchoolDateFormat(), strtotime($pdate));
                                                $hostel_last_collected_amount = $amt;
                                            }
                                        } else {
                                            if (empty($academic_last_date_raw) || strtotime($pdate) > strtotime($academic_last_date_raw)) {
                                                $academic_last_date_raw = $pdate;
                                                $academic_last_collected_date = date($this->customlib->getSchoolDateFormat(), strtotime($pdate));
                                                $academic_last_collected_amount = $amt;
                                            }
                                        }
                                    }
                                }
                            }
                            
                            if (isset($fee->fee_groups_id) && in_array($fee->fee_groups_id, $hostel_fee_group_ids)) {
                                $hostel_total += $fee_amount;
                                $hostel_collected += $collected;
                            } else {
                                $academic_total += $fee_amount;
                                $academic_collected += $collected;
                            }
                        }
                    }
                }
            }
            
            $academic_due = $academic_total - $academic_collected;
            $hostel_due = $hostel_total - $hostel_collected;
            
            $student = $this->student_model->getByStudentSession($student_session_id);
            $transport_fees = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $student['route_pickup_point_id']);
            
            if (!empty($transport_fees)) {
                foreach ($transport_fees as $tfee) {
                    $transport_total += $tfee->fees;
                    $amount_detail = json_decode($tfee->amount_detail, true);
                    if (!empty($amount_detail)) {
                        foreach ($amount_detail as $detail) {
                            $amt = isset($detail['amount']) ? (float)$detail['amount'] : 0;
                            $disc = isset($detail['amount_discount']) ? (float)$detail['amount_discount'] : 0;
                            $transport_collected += $amt + $disc;

                            $pdate = !empty($detail['date']) ? $detail['date'] : '';
                            if (!empty($pdate)) {
                                if (empty($transport_last_date_raw) || strtotime($pdate) > strtotime($transport_last_date_raw)) {
                                    $transport_last_date_raw = $pdate;
                                    $transport_last_collected_date = date($this->customlib->getSchoolDateFormat(), strtotime($pdate));
                                    $transport_last_collected_amount = $amt;
                                }
                            }
                        }
                    }
                }
            }
            $transport_due = $transport_total - $transport_collected;
        }
        
        $this->db->where('student_session_id', $student_session_id);
        $history = $this->db->get('student_scholar_register_history')->row_array();
        
        // Calculate real-time attendance
        $this->db->select('count(*) as total_days, sum(case when attendence_type_id in (1, 3, 6) then 1 else 0 end) as present_days');
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where_in('attendence_type_id', [1, 2, 3, 4, 6]); // Present, Late with excuse, Late, Absent, Half Day
        $att = $this->db->get('student_attendences')->row_array();
        
        $live_attendance = [
            'working_days' => $att['total_days'] ?? 0,
            'present_days' => $att['present_days'] ?? 0,
            'attendance_percentage' => ($att['total_days'] > 0) ? round(($att['present_days'] / $att['total_days']) * 100, 2) : 0
        ];
        
        if (empty($history)) {
            $history = $live_attendance;
        } else {
            // Prefer history if set manually, otherwise fallback to live calculated
            $history['working_days'] = !empty($history['working_days']) ? $history['working_days'] : $live_attendance['working_days'];
            $history['present_days'] = !empty($history['present_days']) ? $history['present_days'] : $live_attendance['present_days'];
            $history['attendance_percentage'] = !empty($history['attendance_percentage']) ? $history['attendance_percentage'] : $live_attendance['attendance_percentage'];
        }
        
        $data = [
            'academic' => [
                'total' => $academic_total,
                'collected' => $academic_collected,
                'due' => $academic_due,
                'last_collected' => $academic_last_collected_amount,
                'last_collected_date' => $academic_last_collected_date,
                'last_collected_date_raw' => !empty($academic_last_date_raw) ? date('Y-m-d', strtotime($academic_last_date_raw)) : ''
            ],
            'transport' => [
                'total' => $transport_total,
                'collected' => $transport_collected,
                'due' => $transport_due,
                'last_collected' => $transport_last_collected_amount,
                'last_collected_date' => $transport_last_collected_date,
                'last_collected_date_raw' => !empty($transport_last_date_raw) ? date('Y-m-d', strtotime($transport_last_date_raw)) : ''
            ],
            'hostel' => [
                'total' => $hostel_total,
                'collected' => $hostel_collected,
                'due' => $hostel_due,
                'last_collected' => $hostel_last_collected_amount,
                'last_collected_date' => $hostel_last_collected_date,
                'last_collected_date_raw' => !empty($hostel_last_date_raw) ? date('Y-m-d', strtotime($hostel_last_date_raw)) : ''
            ],
            'history' => $history
        ];

        echo json_encode($data);
    }

    public function get_cert_number_ajax() {
        $type_id = $this->input->post('certificate_type_id');
        $type = $this->db->get_where('student_certificate_types', ['id' => $type_id])->row_array();
        if ($type) {
            $next_num = max($type['start_number'], $type['current_number'] + 1);
            $cert_no = $type['series_prefix'] . sprintf('%03d', $next_num);
            echo json_encode(['status' => 'success', 'cert_no' => $cert_no]);
        } else {
            echo json_encode(['status' => 'fail', 'cert_no' => '']);
        }
    }

    public function save_scholar_history_ajax() {
        if (!$this->rbac->hasPrivilege('certificate', 'can_add')) {
            echo json_encode(['status' => 'fail']);
            return;
        }
        
        $this->load->model('scholarregister_model');
        $student_session_id = $this->input->post('student_session_id');
        
        $working_days = $this->input->post('sr_working_days');
        $present_days = $this->input->post('sr_present_days');
        $attendance = $this->input->post('sr_attendance');

        $data = [
            'student_session_id' => $student_session_id,
            'working_days' => ($working_days !== '') ? $working_days : null,
            'present_days' => ($present_days !== '') ? $present_days : null,
            'attendance_percentage' => ($attendance !== '') ? $attendance : null,
            'result' => $this->input->post('sr_result'),
            'conduct' => $this->input->post('sr_conduct'),
            'remarks' => $this->input->post('sr_remarks'),
        ];
        
        // Ensure student_scholar_register_history exists for this session
        $this->db->where('student_session_id', $student_session_id);
        $existing = $this->db->get('student_scholar_register_history')->row_array();
        if (!empty($existing)) {
            $this->db->where('id', $existing['id']);
            $this->db->update('student_scholar_register_history', $data);
        } else {
            // Need session_id, class_id, section_id
            $ss = $this->db->get_where('student_session', ['id' => $student_session_id])->row_array();
            if ($ss) {
                $data['session_id'] = $ss['session_id'];
                $data['class_id'] = $ss['class_id'];
                $data['section_id'] = $ss['section_id'];
                $this->db->insert('student_scholar_register_history', $data);
            }
        }
        echo json_encode(['status' => 'success']);
    }

    public function generate_cert_ajax() {
        if (!$this->rbac->hasPrivilege('certificate', 'can_add')) {
            echo json_encode(['status' => 'fail', 'message' => 'Access Denied']);
            return;
        }

        $student_id = $this->input->post('student_id');
        $type_id = $this->input->post('certificate_type_id');
        $tc_number = $this->input->post('tc_number');
        $issue_date = $this->input->post('issue_date');
        $reason = $this->input->post('reason');
        
        $type = $this->db->get_where('student_certificate_types', ['id' => $type_id])->row_array();
        
        if (empty($type)) {
            echo json_encode(['status' => 'fail', 'message' => 'Invalid Certificate Type.']);
            return;
        }
        
        $this->db->where('student_id', $student_id);
        $this->db->where('student_certificate_type_id', $type_id);
        $existing_cert = $this->db->get('student_certificate_register')->row_array();

        if (!empty($existing_cert)) {
            echo json_encode(['status' => 'fail', 'message' => 'This student already has a '.$type['certificate_name'].' issued.']);
            return;
        }

        $insert_data = [
            'student_id' => $student_id,
            'student_certificate_type_id' => $type_id,
            'certificate_number' => $tc_number,
            'issue_date' => date('Y-m-d', $this->customlib->datetostrtotime($issue_date)),
            'status' => 'Issued',
            'remark' => $reason,
            'generated_by' => $this->customlib->getStaffID()
        ];
        
        $cert_id = $this->certificateregister_model->add($insert_data);
        
        $next_num = max($type['start_number'], $type['current_number'] + 1);
        $this->certificatetypes_model->update_current_number($type_id, $next_num);
        
        if (strpos(strtolower($type['certificate_name']), 'transfer') !== false || strpos(strtolower($type['certificate_name']), 'tc') !== false) {
            $this->db->where('id', $student_id);
            $this->db->update('students', ['is_active' => 'no', 'disable_at' => date('Y-m-d')]);
        }

        echo json_encode(['status' => 'success', 'cert_id' => $cert_id, 'message' => $type['certificate_name'].' Generated Successfully!']);
    }
}
