<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\NotificationModel;
use App\Models\StaffModel;
use App\Models\StudentcallModel;
use App\Models\StudentModel;

/**
 * StudentcallController
 *
 * Migrated student call log & follow-ups controller.
 */
class StudentcallController extends BaseController
{
    protected StudentcallModel $studentcallModel;
    protected StaffModel $staffModel;
    protected ClassModel $classModel;
    protected NotificationModel $notificationModel;
    protected StudentModel $studentModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->studentcallModel = new StudentcallModel();
        $this->staffModel = new StaffModel();
        $this->classModel = new ClassModel();
        $this->notificationModel = new NotificationModel();
        $this->studentModel = new StudentModel();
    }

    private function parseInputDate(?string $date_str): ?string
    {
        if (empty($date_str)) {
            return null;
        }
        $converted = $this->customlib->dateFormatToYYYYMMDD($date_str);
        if (!empty($converted) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $converted)) {
            return $converted;
        }
        $time = strtotime($date_str);
        if ($time !== false && $time > 0) {
            return date('Y-m-d', $time);
        }
        return null;
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_view')) {
            access_denied();
        }

        $session = service('session');
        $session->set('top_menu', 'Call Log');
        $session->set('sub_menu', 'admin/studentcall');

        $data['class_list'] = $this->classModel->get();
        $data['purposes'] = $this->studentcallModel->get_purposes();
        $data['staff_list'] = $this->staffModel->get();

        // Auto-resolve zero due fee followups so they are cleared from Pending tab & counters
        $this->studentcallModel->auto_resolve_zero_due_fee_followups();

        // Defer heavy call-log fetch to AJAX (get_call_logs_ajax) for faster initial page load.
        $data['calls'] = [];
        $data['pending_calls'] = $this->studentcallModel->get_pending_followup_calls('all');

        // Fetch widget stats
        $today_stats = $this->studentcallModel->get_today_call_statistics();
        $pending_followups = $this->studentcallModel->get_total_pending_followups_count();

        $total_calls_today = 0;
        $connected_today = 0;
        $not_connected_today = 0;

        foreach ($today_stats as $stat) {
            $total_calls_today += $stat['count'];
            if ($stat['call_status'] === 'Connected') {
                $connected_today += $stat['count'];
            } elseif ($stat['call_status'] !== 'Callback Requested') {
                $not_connected_today += $stat['count'];
            }
        }

        $data['total_calls_today'] = $total_calls_today;
        $data['connected_today'] = $connected_today;
        $data['not_connected_today'] = $not_connected_today;
        $data['pending_followups'] = $pending_followups;

        $data['saved_filters'] = $session->get('studentcall_status_filters');

        return view('admin/studentcall/index', $data);
    }

    public function assigned_to_me()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_view')) {
            access_denied();
        }

        $session = service('session');
        $session->set('top_menu', 'Call Log');
        $session->set('sub_menu', 'admin/studentcall/assigned_to_me');

        $staff_id = (int)$this->customlib->getUserData()["id"];

        $data['class_list'] = $this->classModel->get();
        $data['purposes'] = $this->studentcallModel->get_purposes();
        $data['staff_list'] = $this->staffModel->get();
        $data['default_assigned_to'] = $staff_id;
        $data['is_assigned_to_me_view'] = true;

        $data['calls'] = [];
        $data['pending_calls'] = $this->studentcallModel->get_pending_followup_calls('all', $staff_id);

        $today_stats = $this->studentcallModel->get_today_call_statistics();
        $pending_followups = $this->studentcallModel->get_total_pending_followups_count($staff_id);

        $total_calls_today = 0;
        $connected_today = 0;
        $not_connected_today = 0;

        foreach ($today_stats as $stat) {
            $total_calls_today += $stat['count'];
            if ($stat['call_status'] === 'Connected') {
                $connected_today += $stat['count'];
            } elseif ($stat['call_status'] !== 'Callback Requested') {
                $not_connected_today += $stat['count'];
            }
        }

        $data['total_calls_today'] = $total_calls_today;
        $data['connected_today'] = $connected_today;
        $data['not_connected_today'] = $not_connected_today;
        $data['pending_followups'] = $pending_followups;
        $data['pending_followup_calls'] = $this->studentcallModel->get_pending_followup_calls('due_today_and_overdue', $staff_id);

        $data['saved_filters'] = $session->get('studentcall_status_filters');

        return view('admin/studentcall/index', $data);
    }

    public function search_student()
    {
        $keyword = $this->request->getPost('keyword');
        if (!empty($keyword)) {
            $students = $this->studentcallModel->search_student((string)$keyword);
            return $this->response->setJSON($students);
        }
        return $this->response->setJSON([]);
    }

    public function search_ajax()
    {
        $class_id = $this->request->getPost('class_id');
        $section_id = $this->request->getPost('section_id');
        $date_from = $this->request->getPost('date_from') ? date('Y-m-d', $this->customlib->datetostrtotime($this->request->getPost('date_from'))) : null;
        $date_to = $this->request->getPost('date_to') ? date('Y-m-d', $this->customlib->datetostrtotime($this->request->getPost('date_to'))) : null;
        $purpose_id = $this->request->getPost('purpose_id');
        $status = $this->request->getPost('status');

        $follow_up_date_from = $this->request->getPost('follow_up_date_from') ? date('Y-m-d', $this->customlib->datetostrtotime($this->request->getPost('follow_up_date_from'))) : null;
        $follow_up_date_to = $this->request->getPost('follow_up_date_to') ? date('Y-m-d', $this->customlib->datetostrtotime($this->request->getPost('follow_up_date_to'))) : null;
        $assigned_to = $this->request->getPost('assigned_to');

        $calls = $this->studentcallModel->get_calls($class_id, $section_id, $date_from, $date_to, $purpose_id, $status, $follow_up_date_from, $follow_up_date_to, $assigned_to);

        $data = [];
        if (!empty($calls)) {
            $today_date = date('Y-m-d');
            foreach ($calls as $call) {
                $followup_status = '';

                if ($call['total_followups'] == 0) {
                    $followup_status = "<span class='label label-default'>None</span>";
                } elseif ($call['pending_count'] > 0) {
                    $due_date_str = !empty($call['next_follow_up_date']) ? date('Y-m-d', strtotime($call['next_follow_up_date'])) : '';
                    $formatted_due = !empty($call['next_follow_up_date']) ? date($this->customlib->getSchoolDateFormat(), strtotime($call['next_follow_up_date'])) : '';

                    if (!empty($due_date_str) && $due_date_str < $today_date) {
                        $followup_status = "<span class='label label-danger' data-toggle='tooltip' title='Overdue since " . $formatted_due . "'><i class='fa fa-exclamation-triangle'></i> Overdue (" . $formatted_due . ")</span>";
                    } elseif (!empty($due_date_str) && $due_date_str === $today_date) {
                        $followup_status = "<span class='label label-warning' data-toggle='tooltip' title='Due Today!'><i class='fa fa-clock-o'></i> Due Today</span>";
                    } else {
                        $followup_status = "<span class='label label-info'><i class='fa fa-calendar'></i> Pending (" . $formatted_due . ")</span>";
                    }
                } else {
                    $followup_status = "<span class='label label-success'><i class='fa fa-check'></i> Resolved</span>";
                }

                $dial_phone = !empty($call['father_phone']) ? $call['father_phone'] : (!empty($call['mother_phone']) ? $call['mother_phone'] : (!empty($call['guardian_phone']) ? $call['guardian_phone'] : (!empty($call['mobileno']) ? $call['mobileno'] : $call['phone_number'])));
                $clean_dial_phone = preg_replace('/[^0-9]/', '', (string)$dial_phone);
                $normalized_date = !empty($call['date']) ? str_replace('/', '-', $call['date']) : '';
                $called_today = (!empty($normalized_date) && date('Y-m-d', strtotime($normalized_date)) === date('Y-m-d')) ? '1' : '0';
                $last_call_date = !empty($call['date']) ? date($this->customlib->getSchoolDateFormat(true, true), strtotime($normalized_date)) : '';

                // WhatsApp message template
                $wa_msg = "Hello, this is regarding student " . $call['firstname'] . " " . $call['lastname'] . " (" . $call['class'] . " - " . $call['section'] . "). Purpose: " . $call['purpose_name'] . ". Please get in touch with us.";
                $wa_link = "https://api.whatsapp.com/send?phone=" . $clean_dial_phone . "&text=" . urlencode($wa_msg);

                $action = '<div style="display:inline-flex; gap:3px; align-items:center; justify-content:flex-end; white-space:nowrap;">';
                if ($this->rbac->hasPrivilege('student_call_log', 'can_view')) {
                    $action .= '<a href="javascript:void(0)" class="btn btn-success btn-xs trigger-direct-call" data-call-id="' . $call['id'] . '" data-student-name="' . htmlspecialchars($call['firstname'] . ' ' . $call['lastname']) . '" data-phone="' . $clean_dial_phone . '" data-called-today="' . $called_today . '" data-last-call-date="' . $last_call_date . '" data-toggle="tooltip" title="Dial (' . $dial_phone . ')"><i class="fa fa-phone"></i></a>';

                    if (!empty($clean_dial_phone)) {
                        $action .= '<a href="' . $wa_link . '" target="_blank" class="btn btn-xs" style="background-color:#25D366; color:#fff; border-color:#25D366;" data-toggle="tooltip" title="WhatsApp Parent (' . $clean_dial_phone . ')"><i class="fa fa-whatsapp"></i></a>';
                    }

                    $action .= '<a href="javascript:void(0)" class="btn btn-info btn-xs btn-view-call-details" data-id="' . $call['id'] . '" data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></i></a>';

                    if ($this->rbac->hasPrivilege('student_call_log', 'can_edit') && $call['pending_count'] > 0) {
                        $action .= '<a href="javascript:void(0)" class="btn btn-primary btn-xs btn-quick-resolve" data-id="' . $call['id'] . '" data-toggle="tooltip" title="Mark Done"><i class="fa fa-check"></i></a>';
                        $action .= '<a href="javascript:void(0)" class="btn btn-warning btn-xs btn-quick-reschedule" data-id="' . $call['id'] . '" data-toggle="tooltip" title="Reschedule Date"><i class="fa fa-calendar"></i></a>';
                    }

                    $action .= '<a href="javascript:void(0)" class="btn btn-default btn-xs btn-follow-up-log" data-id="' . $call['id'] . '" onclick="follow_up(' . $call['id'] . '); return false;" data-toggle="tooltip" title="Log Follow-up"><i class="fa fa-pencil"></i></a>';
                }
                $action .= '</div>';

                // Notes cell formatting
                $clean_call_notes = fix_utf8_notes($call['notes'] ?? '');
                $notes_cell = '<div style="max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:#444; font-size:12px;" data-toggle="tooltip" data-placement="top" title="' . htmlspecialchars($clean_call_notes) . '">';
                if (!empty($clean_call_notes)) {
                    if (strpos($clean_call_notes, ' | ') !== false) {
                        $notes_cell .= '<span class="label label-info" style="font-size:10px; padding:2px 6px;">Structured Notes</span> ' . htmlspecialchars(substr($clean_call_notes, 0, 30)) . '...';
                    } else {
                        $notes_cell .= htmlspecialchars($clean_call_notes);
                    }
                } else {
                    $notes_cell .= '<span style="color:#ccc;">-</span>';
                }
                $notes_cell .= '</div>';

                $row = [
                    $call['firstname'] . ' ' . $call['lastname'] . ' (' . $call['admission_no'] . ')',
                    $call['father_name'],
                    $call['class'] . ' (' . $call['section'] . ')',
                    $call['pickup_point_name'],
                    $call['phone_number'],
                    get_purpose_pill($call['purpose_name']),
                    get_call_status_pill($call['call_status']),
                    date($this->customlib->getSchoolDateFormat(true, true), strtotime($call['date'])),
                    $notes_cell,
                    $followup_status,
                    $call['assigned_to_name'],
                    $call['staff_name'] . ' ' . $call['staff_surname'],
                    '<div class="pull-right">' . $action . '</div>'
                ];
                $data[] = $row;
            }
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }

    public function add_call()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_add')) {
            access_denied();
        }

        $rules = [
            'student_id'     => 'required',
            'call_type'      => 'required',
            'contact_person' => 'required',
            'phone_number'   => 'required',
            'purpose_id'     => 'required',
            'date'           => 'required',
        ];

        if (!$this->validate($rules)) {
            $msg = [
                'student_id'     => form_error('student_id'),
                'call_type'      => form_error('call_type'),
                'contact_person' => form_error('contact_person'),
                'phone_number'   => form_error('phone_number'),
                'purpose_id'     => form_error('purpose_id'),
                'date'           => form_error('date'),
            ];
            return $this->response->setJSON(['status' => 'fail', 'error' => $msg, 'message' => '']);
        }

        $userdata = $this->customlib->getUserData();

        $data = [
            'student_session_id' => $this->request->getPost('student_session_id'),
            'student_id'         => $this->request->getPost('student_id'),
            'call_type'          => $this->request->getPost('call_type'),
            'contact_person'     => $this->request->getPost('contact_person'),
            'phone_number'       => $this->request->getPost('phone_number'),
            'call_purpose_id'    => empty2null($this->request->getPost('purpose_id')),
            'call_status'        => $this->request->getPost('call_status'),
            'date'               => $this->customlib->dateFormatToYYYYMMDD($this->request->getPost('date')),
            'duration'           => $this->request->getPost('duration'),
            'notes'              => $this->request->getPost('notes'),
            'created_by'         => $userdata["id"]
        ];
        $call_id = $this->studentcallModel->add_call($data);

        if ($this->request->getPost('follow_up_date')) {
            $this->studentcallModel->resolve_previous_followups($call_id);
            $followup = [
                'student_call_id' => $call_id,
                'student_id'      => $this->request->getPost('student_id'),
                'due_date'        => $this->customlib->dateFormatToYYYYMMDD($this->request->getPost('follow_up_date')),
                'priority'        => $this->request->getPost('priority') ?: 'Medium',
                'assigned_to'     => empty2null($this->request->getPost('assigned_to')),
                'remarks'         => $this->request->getPost('notes'),
                'status'          => 'Pending',
                'created_by'      => $userdata["id"]
            ];
            $this->studentcallModel->add_followup($followup);

            if (!empty($followup['assigned_to'])) {
                $this->add_notification((int)$followup['assigned_to'], "New Follow-up assigned to you for student ID: " . $followup['student_id']);
            }
        } elseif (!empty($data['notes'])) {
            $followup = [
                'student_call_id' => $call_id,
                'student_id'      => $this->request->getPost('student_id'),
                'due_date'        => $data['date'],
                'priority'        => 'Medium',
                'assigned_to'     => $userdata["id"],
                'remarks'         => $data['notes'],
                'call_status'     => $data['call_status'],
                'status'          => 'Completed',
                'created_by'      => $userdata["id"]
            ];
            $this->studentcallModel->add_followup($followup);
        }

        $copy_to_siblings = $this->request->getPost('copy_to_siblings');
        if (!empty($copy_to_siblings) && is_array($copy_to_siblings)) {
            foreach ($copy_to_siblings as $sib_str) {
                $sib_parts = explode('|', $sib_str);
                if (count($sib_parts) == 2) {
                    $sib_data = $data;
                    $sib_data['student_session_id'] = $sib_parts[0];
                    $sib_data['student_id'] = $sib_parts[1];

                    $sib_call_id = $this->studentcallModel->add_call($sib_data);

                    if ($this->request->getPost('follow_up_date') && isset($followup)) {
                        $this->studentcallModel->resolve_previous_followups($sib_call_id);
                        $sib_followup = $followup;
                        $sib_followup['student_call_id'] = $sib_call_id;
                        $sib_followup['student_id'] = $sib_parts[1];
                        $this->studentcallModel->add_followup($sib_followup);

                        if (!empty($sib_followup['assigned_to'])) {
                            $this->add_notification((int)$sib_followup['assigned_to'], "New Follow-up assigned to you for student ID: " . $sib_followup['student_id']);
                        }
                    }
                }
            }
        }

        return $this->response->setJSON(['status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message')]);
    }

    public function get_siblings($student_id)
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_add')) {
            access_denied();
        }

        $student = $this->studentModel->get((int)$student_id);
        $siblings_data = [];
        if ($student && !empty($student['parent_id'])) {
            $siblings = $this->studentModel->getParentChilds($student['parent_id']);
            foreach ($siblings as $sib) {
                if ($sib->id != $student_id) {
                    $siblings_data[] = [
                        'student_id'         => $sib->id,
                        'student_session_id' => $sib->student_session_id,
                        'name'               => $sib->firstname . " " . $sib->lastname,
                        'class_section'      => $sib->class . " (" . $sib->section . ")"
                    ];
                }
            }
        }
        return $this->response->setJSON(['status' => 'success', 'siblings' => $siblings_data]);
    }

    public function get_recent_history($student_id)
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_add')) {
            access_denied();
        }
        $history = $this->studentcallModel->get_recent_history((int)$student_id, 3);
        $html = '';
        if (!empty($history)) {
            foreach ($history as $h) {
                $html .= '<div style="margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid #eee;">';
                $html .= '<div style="font-size:12px; color:#666;">' . date($this->customlib->getSchoolDateFormat(), strtotime($h['date'])) . ' - <strong>' . $h['call_status'] . '</strong></div>';
                if (!empty($h['purpose_name'])) {
                    $html .= '<div style="font-size:12px; color:#333;"><strong>Purpose:</strong> ' . $h['purpose_name'] . '</div>';
                }
                if (!empty($h['notes'])) {
                    $html .= '<div style="font-size:11px; color:#888; font-style:italic;">' . fix_utf8_notes($h['notes']) . '</div>';
                }
                $html .= '</div>';
            }
        } else {
            $html = '<div style="font-size:12px; color:#888;">No recent calls.</div>';
        }
        return $this->response->setJSON(['status' => 'success', 'html' => $html]);
    }

    public function follow_up($call_id)
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_view')) {
            access_denied();
        }
        $this->studentcallModel->auto_resolve_zero_due_fee_followups(null, (int)$call_id);
        $data['call'] = $this->studentcallModel->get_call((int)$call_id);
        $data['followups'] = $this->studentcallModel->get_followups_by_call((int)$call_id);
        $data['staff_list'] = $this->staffModel->get();

        $student = $this->studentModel->get((int)$data['call']['student_id']);
        $data['siblings'] = [];
        if ($student && !empty($student['parent_id'])) {
            $siblings = $this->studentModel->getParentChilds($student['parent_id']);
            foreach ($siblings as $sib) {
                if ($sib->id != $student['id']) {
                    $data['siblings'][] = $sib;
                }
            }
        }

        return view('admin/studentcall/follow_up_modal', $data);
    }

    public function add_follow_up_task()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_add')) {
            access_denied();
        }

        $due_date = $this->request->getPost('due_date');
        if (empty($due_date)) {
            return $this->response->setJSON(['status' => 'fail', 'error' => ['due_date' => 'Date is required'], 'message' => '']);
        }

        $userdata = $this->customlib->getUserData();
        $call_id = (int)$this->request->getPost('student_call_id');
        $this->studentcallModel->resolve_previous_followups($call_id);

        $data = [
            'student_call_id' => $call_id,
            'student_id'      => $this->request->getPost('student_id'),
            'due_date'        => $this->customlib->dateFormatToYYYYMMDD($due_date),
            'priority'        => $this->request->getPost('priority'),
            'assigned_to'     => empty2null($this->request->getPost('assigned_to')),
            'status'          => 'Pending',
            'created_by'      => $userdata["id"]
        ];
        $this->studentcallModel->add_followup($data);

        if (!empty($data['assigned_to'])) {
            $this->add_notification((int)$data['assigned_to'], "New Follow-up assigned to you for student ID: " . $data['student_id']);
        }

        return $this->response->setJSON(['status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message')]);
    }

    public function edit_follow_up_task()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_edit')) {
            access_denied();
        }

        $userdata = $this->customlib->getUserData();
        $id = (int)$this->request->getPost('followup_id');
        $remarks = $this->request->getPost('remarks');
        $call_status = $this->request->getPost('call_status');

        $data = [
            'status'      => $this->request->getPost('status'),
            'call_status' => $call_status,
            'remarks'     => $remarks
        ];
        $this->studentcallModel->update_followup($id, $data);

        $followup_db = $this->studentcallModel->get_followup($id);

        if (!empty($call_status)) {
            $this->studentcallModel->update_call((int)$followup_db['student_call_id'], ['call_status' => $call_status]);
        }

        $next_time_val = $this->request->getPost('next_follow_up_time');
        if ($this->request->getPost('next_follow_up_date')) {
            $this->studentcallModel->resolve_previous_followups((int)$followup_db['student_call_id']);

            $next_date_val = $this->customlib->dateFormatToYYYYMMDD($this->request->getPost('next_follow_up_date'));
            if (!empty($next_time_val)) {
                $next_date_val .= ' ' . date('H:i:s', strtotime((string)$next_time_val));
            }

            $next = [
                'student_call_id' => $followup_db['student_call_id'],
                'student_id'      => $followup_db['student_id'],
                'due_date'        => $next_date_val,
                'priority'        => $this->request->getPost('next_priority') ?: $followup_db['priority'],
                'assigned_to'     => $this->request->getPost('next_assigned_to') ?: $followup_db['assigned_to'],
                'status'          => 'Pending',
                'created_by'      => $userdata["id"]
            ];
            $this->studentcallModel->add_followup($next);

            if (!empty($next['assigned_to']) && $next['assigned_to'] != $userdata['id']) {
                $this->add_notification((int)$next['assigned_to'], "New Follow-up reassigned to you for student ID: " . $next['student_id']);
            }
        }

        $copy_to_siblings = $this->request->getPost('copy_to_siblings');
        if (!empty($copy_to_siblings) && is_array($copy_to_siblings)) {
            $original_call = $this->studentcallModel->get_call((int)$followup_db['student_call_id']);
            foreach ($copy_to_siblings as $sib_str) {
                $sib_parts = explode('|', $sib_str);
                if (count($sib_parts) == 2) {
                    $sib_call = [
                        'student_session_id' => $sib_parts[0],
                        'student_id'         => $sib_parts[1],
                        'call_type'          => $original_call['call_type'],
                        'contact_person'     => $original_call['contact_person'],
                        'phone_number'       => $original_call['phone_number'],
                        'call_purpose_id'    => $original_call['call_purpose_id'],
                        'call_status'        => !empty($call_status) ? $call_status : $original_call['call_status'],
                        'date'               => date('Y-m-d'),
                        'duration'           => $original_call['duration'] ?? null,
                        'notes'              => ($remarks !== null && $remarks !== '') ? $remarks : $original_call['notes'],
                        'created_by'         => $userdata["id"]
                    ];
                    $sib_call_id = $this->studentcallModel->add_call($sib_call);

                    $this->studentcallModel->resolve_previous_followups_with_details(
                        $sib_call_id,
                        $this->request->getPost('status') ?: 'Completed',
                        !empty($call_status) ? $call_status : null,
                        ($remarks !== null && $remarks !== '') ? $remarks : null
                    );

                    if ($this->request->getPost('next_follow_up_date')) {
                        $sib_due = $this->customlib->dateFormatToYYYYMMDD($this->request->getPost('next_follow_up_date'));
                        if (!empty($next_time_val)) {
                            $sib_due .= ' ' . date('H:i:s', strtotime((string)$next_time_val));
                        }
                        $sib_next = [
                            'student_call_id' => $sib_call_id,
                            'student_id'      => $sib_parts[1],
                            'due_date'        => $sib_due,
                            'priority'        => $this->request->getPost('next_priority') ?: $followup_db['priority'],
                            'assigned_to'     => $this->request->getPost('next_assigned_to') ?: $followup_db['assigned_to'],
                            'status'          => 'Pending',
                            'created_by'      => $userdata["id"]
                        ];
                        $this->studentcallModel->add_followup($sib_next);
                        if (!empty($sib_next['assigned_to']) && $sib_next['assigned_to'] != $userdata['id']) {
                            $this->add_notification((int)$sib_next['assigned_to'], "New Follow-up assigned to you for student ID: " . $sib_next['student_id']);
                        }
                    }
                }
            }
        }

        return $this->response->setJSON(['status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message')]);
    }

    public function update_followup()
    {
        return $this->edit_follow_up_task();
    }

    private function add_notification(int $staff_id, string $message): void
    {
        $staff = $this->staffModel->get($staff_id);
        if (!empty($staff)) {
            $notification = [
                'title'           => 'Follow-up Task',
                'message'         => $message,
                'publish_date'    => date('Y-m-d'),
                'date'            => date('Y-m-d'),
                'visible_student' => 'No',
                'visible_staff'   => 'Yes',
                'visible_parent'  => 'No',
                'created_id'      => $this->customlib->getUserData()["id"],
            ];
            $staff_roles = [['role_id' => $staff['role_id']]];
            $this->notificationModel->insertBatch($notification, $staff_roles);
        }
    }

    public function students_status_ajax()
    {
        $class_id = $this->request->getPost('class_id');
        $section_id = $this->request->getPost('section_id');
        $admission_type = $this->request->getPost('admission_type');
        $shrestha = $this->request->getPost('shrestha');
        $rte = $this->request->getPost('rte');
        $is_staff_kid = $this->request->getPost('is_staff_kid');
        $purpose_id = $this->request->getPost('purpose_id');
        $call_status = $this->request->getPost('call_status');

        $start = (int)($this->request->getPost('start') ?: 0);
        $length = (int)($this->request->getPost('length') ?: 10);

        $search = $this->request->getPost('search');
        $search_value = isset($search['value']) ? (string)$search['value'] : '';

        $order = $this->request->getPost('order');
        $order_col_index = isset($order[0]['column']) ? (int)$order[0]['column'] : 0;
        $order_dir = isset($order[0]['dir']) ? (string)$order[0]['dir'] : 'asc';

        $db_columns = [
            0 => 'students.firstname',
            1 => 'students.father_name',
            2 => 'classes.class',
            3 => 'pickup_point.name',
            4 => 'students.admission_type',
            5 => 'students.is_staff_kid',
            6 => 'students.mobileno',
            7 => 'sc.date',
            8 => 'sc.call_status',
        ];
        $order_by = $db_columns[$order_col_index] ?? 'students.firstname';

        // Save filters to session
        $filters = [
            'class_id'       => $class_id,
            'section_id'     => $section_id,
            'admission_type' => $admission_type,
            'shrestha'       => $shrestha,
            'rte'            => $rte,
            'is_staff_kid'   => $is_staff_kid,
            'purpose_id'     => $purpose_id,
            'call_status'    => $call_status
        ];
        service('session')->set('studentcall_status_filters', $filters);

        $students = $this->studentcallModel->get_students_call_status($class_id, $section_id, $start, $length, $search_value, $admission_type, $shrestha, $rte, $is_staff_kid, $purpose_id, $call_status, $order_by, $order_dir);
        $total_count = $this->studentcallModel->get_students_call_status_count($class_id, $section_id, $search_value, $admission_type, $shrestha, $rte, $is_staff_kid, $purpose_id, $call_status);

        $data = [];
        if (!empty($students)) {
            foreach ($students as $student) {
                $status = $student['last_call_status'] ?: 'Not connected';
                $last_call = $student['last_call_date'] ? date($this->customlib->getSchoolDateFormat(true, true), strtotime($student['last_call_date'])) : '-';

                $phone = '';
                if ($student['mobileno']) {
                    $phone = $student['mobileno'];
                } elseif ($student['father_phone']) {
                    $phone = $student['father_phone'];
                } elseif ($student['mother_phone']) {
                    $phone = $student['mother_phone'];
                } elseif ($student['guardian_phone']) {
                    $phone = $student['guardian_phone'];
                }

                $adm_type = !empty($student['admission_type']) ? $student['admission_type'] : 'New';
                $shrestha_val = !empty($student['shrestha']) ? $student['shrestha'] : 'No';
                $rte_val = !empty($student['rte']) ? $student['rte'] : 'No';
                $is_staff_kid_val = ($student['is_staff_kid'] == 1 || $student['is_staff_kid'] === '1' || strtolower($student['is_staff_kid'] ?? '') === 'yes') ? 'Yes' : 'No';
                $staff_name = $student['student_staff_name'] ?? '';

                $staff_kid_cell = '';
                if ($is_staff_kid_val === 'Yes') {
                    $staff_kid_cell = '<span class="label label-warning" style="font-size:11px; padding:3px 8px;" data-toggle="tooltip" title="Staff Kid"><i class="fa fa-user"></i> Yes' . (!empty($staff_name) ? ' (' . htmlspecialchars($staff_name) . ')' : '') . '</span>';
                } else {
                    $staff_kid_cell = '<span class="label label-default" style="font-size:11px; padding:3px 8px;">No</span>';
                }

                $action = '';
                if ($this->rbac->hasPrivilege('student_call_log', 'can_add')) {
                    $action = '<button class="btn btn-default btn-xs" onclick="openCallModalFromStatus(' . $student['student_id'] . ', ' . $student['student_session_id'] . ', \'' . htmlspecialchars(addslashes($student['firstname'] . ' ' . $student['lastname']), ENT_QUOTES) . '\')" data-toggle="tooltip" title="Log Call"><i class="fa fa-phone"></i> Log Call</button>';
                }

                $student_cell = '<div style="font-weight:600; color:#1e293b;">' . htmlspecialchars($student['firstname'] . ' ' . $student['lastname'] . ' (' . $student['admission_no'] . ')') . '</div>' .
                    '<div style="margin-top: 4px; display: flex; flex-wrap: wrap; gap: 4px; align-items: center; font-size: 10px;">' .
                    '<span style="background:#e0f2fe; color:#0369a1; padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="Admission Type">Adm: ' . htmlspecialchars($adm_type) . '</span>' .
                    '<span style="background:' . ($shrestha_val === 'Yes' ? '#dcfce7; color:#15803d;' : '#f1f5f9; color:#64748b;') . ' padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="Shrestha">Shrestha: ' . $shrestha_val . '</span>' .
                    '<span style="background:' . ($rte_val === 'Yes' ? '#dcfce7; color:#15803d;' : '#f1f5f9; color:#64748b;') . ' padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="RTE">RTE: ' . $rte_val . '</span>' .
                    '<span style="background:' . ($is_staff_kid_val === 'Yes' ? '#fef3c7; color:#b45309;' : '#f1f5f9; color:#64748b;') . ' padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="Staff Kid">Staff Kid: ' . $is_staff_kid_val . ($is_staff_kid_val === 'Yes' && !empty($staff_name) ? " (<b>" . htmlspecialchars($staff_name) . "</b>)" : "") . '</span>';

                $row_class = '';
                $fee_info = $this->studentcallModel->get_student_fee_info((int)$student['student_session_id']);
                if (!empty($fee_info)) {
                    if (!empty($fee_info['is_fully_paid'])) {
                        $row_class = 'row-pastel-green';
                        $student_cell .= '<span style="background:#16a34a; color:#ffffff; padding:1px 6px; border-radius:10px; font-weight:700;" data-toggle="tooltip" title="Fee Status"><i class="fa fa-check-circle"></i> All Fees Paid</span>';
                    } elseif (!empty($fee_info['paid_today'])) {
                        $row_class = 'row-pastel-yellow';
                        $student_cell .= '<span style="background:#9333ea; color:#ffffff; padding:1px 6px; border-radius:10px; font-weight:700;" data-toggle="tooltip" title="Fee Payment"><i class="fa fa-flash"></i> Paid Today</span>';
                    } elseif (!empty($fee_info['paid_last_7_days'])) {
                        $row_class = 'row-pastel-blue';
                        $student_cell .= '<span style="background:#2563eb; color:#ffffff; padding:1px 6px; border-radius:10px; font-weight:700;" data-toggle="tooltip" title="Fee Payment"><i class="fa fa-calendar-check-o"></i> Paid in Last 7 Days</span>';
                    }
                }
                $student_cell .= '</div>';

                $row = [
                    'DT_RowClass' => $row_class,
                    0             => $student_cell,
                    1             => $student['father_name'],
                    2             => $student['class'] . ' (' . $student['section'] . ')',
                    3             => $student['pickup_point_name'] ?: '-',
                    4             => $student['admission_type'] ?: '-',
                    5             => $staff_kid_cell,
                    6             => $phone,
                    7             => $last_call,
                    8             => get_call_status_pill($status),
                    9             => '<div class="pull-right">' . $action . '</div>'
                ];
                $data[] = $row;
            }
        }

        return $this->response->setJSON([
            "draw"            => intval($this->request->getPost('draw')),
            "recordsTotal"    => $total_count,
            "recordsFiltered" => $total_count,
            "data"            => $data
        ]);
    }

    public function quick_resolve_followup()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_edit')) {
            return $this->response->setJSON(['status' => 'fail', 'message' => 'Access Denied']);
        }

        $call_id = (int)$this->request->getPost('call_id');
        $remarks = (string)$this->request->getPost('remarks');

        if (!empty($call_id)) {
            $this->studentcallModel->resolve_previous_followups_with_details($call_id, 'Resolved', 'Connected', $remarks);
            $this->studentcallModel->update_call($call_id, [
                'call_status' => 'Connected',
                'notes'       => !empty($remarks) ? $remarks : 'Call Completed'
            ]);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Follow-up marked as resolved successfully']);
        }

        return $this->response->setJSON(['status' => 'fail', 'message' => 'Invalid Call ID']);
    }

    public function quick_update_assignee_ajax()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_edit')) {
            return $this->response->setJSON(['status' => 'fail', 'message' => 'Access Denied']);
        }

        $call_id     = (int)$this->request->getPost('call_id');
        $followup_id = (int)$this->request->getPost('followup_id');
        $assigned_to = (int)$this->request->getPost('assigned_to');

        if (empty($call_id) && empty($followup_id)) {
            return $this->response->setJSON(['status' => 'fail', 'message' => 'Invalid ID']);
        }

        if (!empty($followup_id)) {
            $this->studentcallModel->update_followup($followup_id, ['assigned_to' => $assigned_to > 0 ? $assigned_to : null]);
        } elseif (!empty($call_id)) {
            $db = \Config\Database::connect();
            $pending = $db->table('student_call_followups')
                ->where('student_call_id', $call_id)
                ->where('status', 'Pending')
                ->get()
                ->getRowArray();

            if (!empty($pending)) {
                $this->studentcallModel->update_followup((int)$pending['id'], ['assigned_to' => $assigned_to > 0 ? $assigned_to : null]);
            } else {
                $call = $this->studentcallModel->get_call($call_id);
                $userdata = $this->customlib->getUserData();
                $new_fw = [
                    'student_call_id' => $call_id,
                    'student_id'      => $call['student_id'],
                    'due_date'        => date('Y-m-d H:i:s'),
                    'priority'        => 'Medium',
                    'assigned_to'     => $assigned_to > 0 ? $assigned_to : null,
                    'status'          => 'Pending',
                    'created_by'      => $userdata['id'] ?? 0
                ];
                $this->studentcallModel->add_followup($new_fw);
            }
        }

        $staff_name = 'Unassigned';
        if ($assigned_to > 0) {
            $staff = $this->staffModel->get($assigned_to);
            if (!empty($staff)) {
                $staff_name = trim($staff['name'] . ' ' . $staff['surname']);
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Staff assigned successfully', 'assigned_to_name' => $staff_name]);
    }

    public function quick_update_followup_status_ajax()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_edit')) {
            return $this->response->setJSON(['status' => 'fail', 'message' => 'Access Denied']);
        }

        $call_id     = (int)$this->request->getPost('call_id');
        $followup_id = (int)$this->request->getPost('followup_id');
        $status      = (string)$this->request->getPost('status');
        $call_status = (string)$this->request->getPost('call_status');
        $remarks     = $this->request->getPost('remarks');
        $next_date   = (string)$this->request->getPost('next_date');
        $next_time   = (string)$this->request->getPost('next_time');

        if (empty($call_id) && empty($followup_id)) {
            return $this->response->setJSON(['status' => 'fail', 'message' => 'Invalid ID']);
        }

        if (!empty($followup_id)) {
            $update = ['status' => $status];
            if (!empty($call_status)) {
                $update['call_status'] = $call_status;
            }
            if ($remarks !== null && $remarks !== '') {
                $update['remarks'] = $remarks;
            }
            $this->studentcallModel->update_followup($followup_id, $update);
        } elseif (!empty($call_id)) {
            $this->studentcallModel->resolve_previous_followups_with_details($call_id, $status, $call_status, $remarks);
        }

        if (!empty($call_id) && !empty($call_status)) {
            $this->studentcallModel->update_call($call_id, [
                'call_status' => $call_status,
                'date'        => date('Y-m-d H:i:s')
            ]);
        }

        if (!empty($next_date) && !empty($call_id)) {
            $call = $this->studentcallModel->get_call($call_id);
            $parsed_date = $this->parseInputDate($next_date);
            if (!empty($parsed_date)) {
                $due_datetime = $parsed_date;
                if (!empty($next_time)) {
                    $due_datetime .= ' ' . date('H:i:s', strtotime($next_time));
                }
                $userdata = $this->customlib->getUserData();
                $new_fw = [
                    'student_call_id' => $call_id,
                    'student_id'      => $call['student_id'],
                    'due_date'        => $due_datetime,
                    'priority'        => 'Medium',
                    'assigned_to'     => $userdata['id'],
                    'status'          => 'Pending',
                    'created_by'      => $userdata['id']
                ];
                $this->studentcallModel->add_followup($new_fw);
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Status updated successfully']);
    }

    public function quick_reschedule_followup()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_edit')) {
            return $this->response->setJSON(['status' => 'fail', 'message' => 'Access Denied']);
        }

        $call_id = (int)$this->request->getPost('call_id');
        $new_date = (string)$this->request->getPost('next_follow_up_date');

        if (!empty($call_id) && !empty($new_date)) {
            $formatted_date = date('Y-m-d H:i:s', strtotime($this->customlib->dateFormatToYYYYMMDD($new_date)));

            $db = \Config\Database::connect();
            $db->table('student_call_followups')
                ->where('student_call_id', $call_id)
                ->where('status', 'Pending')
                ->update(['due_date' => $formatted_date]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Follow-up rescheduled successfully']);
        }

        return $this->response->setJSON(['status' => 'fail', 'message' => 'Invalid parameters']);
    }

    public function get_call_details_ajax(int $call_id)
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_view')) {
            return $this->response->setJSON(['status' => 'fail', 'message' => 'Access Denied']);
        }

        $this->studentcallModel->auto_resolve_zero_due_fee_followups(null, $call_id);
        $call = $this->studentcallModel->get_call($call_id);
        if (!empty($call)) {
            $call['notes'] = fix_utf8_notes($call['notes'] ?? '');
            $followups = $this->studentcallModel->get_followups_by_call($call_id);
            if (!empty($followups)) {
                foreach ($followups as &$fu) {
                    $fu['remarks'] = fix_utf8_notes($fu['remarks'] ?? '');
                }
            }
            $call['followups'] = $followups;

            $parsed_notes = [];
            if (!empty($call['notes'])) {
                $parts = explode(' | ', $call['notes']);
                foreach ($parts as $part) {
                    if (strpos($part, ': ') !== false) {
                        list($label, $val) = explode(': ', $part, 2);
                        $parsed_notes[] = ['label' => trim($label), 'value' => trim($val)];
                    } else {
                        $parsed_notes[] = ['label' => 'Notes', 'value' => trim($part)];
                    }
                }
            }
            $call['parsed_notes'] = $parsed_notes;

            return $this->response->setJSON(['status' => 'success', 'data' => $call]);
        }

        return $this->response->setJSON(['status' => 'fail', 'message' => 'Call log not found']);
    }

    public function get_call_logs_ajax()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_view')) {
            return $this->response->setJSON(['status' => 'fail', 'message' => 'Access Denied']);
        }

        $this->studentcallModel->auto_resolve_zero_due_fee_followups();

        $class_id = $this->request->getPost('class_id');
        $section_id = $this->request->getPost('section_id');
        $date_from = $this->parseInputDate($this->request->getPost('date_from'));
        $date_to = $this->parseInputDate($this->request->getPost('date_to'));
        $purpose_id = $this->request->getPost('purpose_id');
        $status = $this->request->getPost('status');
        $follow_up_date_from = $this->parseInputDate($this->request->getPost('follow_up_date_from'));
        $follow_up_date_to = $this->parseInputDate($this->request->getPost('follow_up_date_to'));
        $assigned_to = $this->request->getPost('assigned_to');

        $calls = $this->studentcallModel->get_calls($class_id, $section_id, $date_from, $date_to, $purpose_id, $status, $follow_up_date_from, $follow_up_date_to, $assigned_to);

        $html = '';
        if (!empty($calls)) {
            foreach ($calls as $call) {
                $normalized_date = !empty($call['date']) ? str_replace('/', '-', $call['date']) : '';
                $called_today = (!empty($normalized_date) && date('Y-m-d', strtotime($normalized_date)) === date('Y-m-d')) ? '1' : '0';
                $last_call_date = !empty($call['date']) ? date($this->customlib->getSchoolDateFormat(true, true), strtotime($normalized_date)) : '';
                $dial_phone = !empty($call['father_phone']) ? $call['father_phone'] : (!empty($call['mother_phone']) ? $call['mother_phone'] : (!empty($call['guardian_phone']) ? $call['guardian_phone'] : (!empty($call['mobileno']) ? $call['mobileno'] : $call['phone_number'])));
                $clean_dial_phone = preg_replace('/[^0-9]/', '', (string)$dial_phone);

                $adm_type = !empty($call['admission_type']) ? $call['admission_type'] : 'New';
                $shrestha = !empty($call['shrestha']) ? $call['shrestha'] : 'No';
                $rte = !empty($call['rte']) ? $call['rte'] : 'No';
                $is_staff_kid = ($call['is_staff_kid'] == 1 || $call['is_staff_kid'] === '1' || strtolower($call['is_staff_kid'] ?? '') === 'yes') ? 'Yes' : 'No';
                $staff_name = $call['student_staff_name'] ?? '';

                $student_cell = '<div style="font-weight:600; color:#1e293b;">' . htmlspecialchars($call['firstname'] . ' ' . $call['lastname'] . ' (' . $call['admission_no'] . ')') . '</div>' .
                    '<div style="margin-top: 4px; display: flex; flex-wrap: wrap; gap: 4px; align-items: center; font-size: 10px;">' .
                    '<span style="background:#e0f2fe; color:#0369a1; padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="Admission Type">Adm: ' . htmlspecialchars($adm_type) . '</span>' .
                    '<span style="background:' . ($shrestha === 'Yes' ? '#dcfce7; color:#15803d;' : '#f1f5f9; color:#64748b;') . ' padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="Shrestha">Shrestha: ' . $shrestha . '</span>' .
                    '<span style="background:' . ($rte === 'Yes' ? '#dcfce7; color:#15803d;' : '#f1f5f9; color:#64748b;') . ' padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="RTE">RTE: ' . $rte . '</span>' .
                    '<span style="background:' . ($is_staff_kid === 'Yes' ? '#fef3c7; color:#b45309;' : '#f1f5f9; color:#64748b;') . ' padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="Staff Kid">Staff Kid: ' . $is_staff_kid . ($is_staff_kid === 'Yes' && !empty($staff_name) ? " (<b>" . htmlspecialchars($staff_name) . "</b>)" : "") . '</span>';

                $row_style = '';
                $fee_info = $this->studentcallModel->get_student_fee_info((int)$call['student_session_id']);
                if (!empty($fee_info)) {
                    if (!empty($fee_info['is_fully_paid'])) {
                        $row_style = 'style="background-color: #f0fdf4;"';
                        $student_cell .= '<span style="background:#16a34a; color:#ffffff; padding:1px 6px; border-radius:10px; font-weight:700;" data-toggle="tooltip" title="Fee Status"><i class="fa fa-check-circle"></i> All Fees Paid</span>';
                    } elseif (!empty($fee_info['paid_today'])) {
                        $row_style = 'style="background-color: #faf5ff;"';
                        $student_cell .= '<span style="background:#9333ea; color:#ffffff; padding:1px 6px; border-radius:10px; font-weight:700;" data-toggle="tooltip" title="Fee Payment"><i class="fa fa-flash"></i> Paid Today</span>';
                    } elseif (!empty($fee_info['paid_last_7_days'])) {
                        $row_style = 'style="background-color: #eff6ff;"';
                        $student_cell .= '<span style="background:#2563eb; color:#ffffff; padding:1px 6px; border-radius:10px; font-weight:700;" data-toggle="tooltip" title="Fee Payment"><i class="fa fa-calendar-check-o"></i> Paid in Last 7 Days</span>';
                    }
                }
                $student_cell .= '</div>';

                $clean_call_notes = fix_utf8_notes($call['notes'] ?? '');
                $note_cell = '';
                if (!empty($clean_call_notes)) {
                    if (strpos($clean_call_notes, ' | ') !== false) {
                        $note_cell = '<span class="label label-info" style="font-size:10px; padding:2px 6px;">Structured Notes</span> ' . htmlspecialchars(substr($clean_call_notes, 0, 30)) . '...';
                    } else {
                        $note_cell = htmlspecialchars($clean_call_notes);
                    }
                } else {
                    $note_cell = '<span style="color:#ccc;">-</span>';
                }

                $followup_cell = '';
                if ($call['total_followups'] == 0) {
                    $followup_cell = "<span class='label label-default' style='cursor:pointer;' onclick='follow_up(\"" . $call['id'] . "\")'>+ Add Follow-up</span>";
                } elseif ($call['pending_count'] > 0) {
                    $today_date = date('Y-m-d');
                    $due_date_str = !empty($call['next_follow_up_date']) ? date('Y-m-d', strtotime($call['next_follow_up_date'])) : '';
                    $formatted_due = !empty($call['next_follow_up_date']) ? date($this->customlib->getSchoolDateFormat(), strtotime($call['next_follow_up_date'])) : '';

                    if (!empty($due_date_str) && $due_date_str < $today_date) {
                        $followup_cell = "<span class='label label-danger quick-change-status-btn' data-call-id='" . $call['id'] . "' data-followup-id='" . ($call['pending_followup_id'] ?? '') . "' style='cursor:pointer;' data-toggle='tooltip' title='Click to update status'><i class='fa fa-exclamation-triangle'></i> Overdue (" . $formatted_due . ") <i class='fa fa-caret-down'></i></span>";
                    } elseif (!empty($due_date_str) && $due_date_str === $today_date) {
                        $followup_cell = "<span class='label label-warning quick-change-status-btn' data-call-id='" . $call['id'] . "' data-followup-id='" . ($call['pending_followup_id'] ?? '') . "' style='cursor:pointer;' data-toggle='tooltip' title='Click to update status'><i class='fa fa-clock-o'></i> Due Today <i class='fa fa-caret-down'></i></span>";
                    } else {
                        $followup_cell = "<span class='label label-info quick-change-status-btn' data-call-id='" . $call['id'] . "' data-followup-id='" . ($call['pending_followup_id'] ?? '') . "' style='cursor:pointer;' data-toggle='tooltip' title='Click to update status'><i class='fa fa-calendar'></i> Pending (" . $formatted_due . ") <i class='fa fa-caret-down'></i></span>";
                    }
                } else {
                    $followup_cell = "<span class='label label-success quick-change-status-btn' data-call-id='" . $call['id'] . "' data-followup-id='" . ($call['pending_followup_id'] ?? '') . "' style='cursor:pointer;' data-toggle='tooltip' title='Click to change status'><i class='fa fa-check'></i> Resolved <i class='fa fa-caret-down'></i></span>";
                }

                $assigned_to_name = !empty($call['assigned_to_name']) ? htmlspecialchars($call['assigned_to_name']) : '<em>Unassigned</em>';
                $assigned_cell = "<div class='quick-change-assignee-btn' data-call-id='" . $call['id'] . "' data-followup-id='" . ($call['pending_followup_id'] ?? '') . "' data-assigned-id='" . ($call['pending_assigned_to_id'] ?? '') . "' style='cursor:pointer; display:inline-flex; align-items:center; gap:4px; padding:2px 6px; border-radius:4px; border:1px dashed #cbd5e1;' data-toggle='tooltip' title='Click to reassign staff'><i class='fa fa-user-circle text-primary'></i> " . $assigned_to_name . " <i class='fa fa-pencil text-muted' style='font-size:10px;'></i></div>";

                $can_edit = $this->rbac->hasPrivilege('student_call_log', 'can_edit');
                $action_cell = '<div style="display:inline-flex; gap:3px; align-items:center; justify-content:flex-end;">' .
                    '<a href="javascript:void(0)" class="btn btn-success btn-xs trigger-direct-call" data-call-id="' . $call['id'] . '" data-student-name="' . htmlspecialchars($call['firstname'] . ' ' . $call['lastname']) . '" data-phone="' . $clean_dial_phone . '" data-called-today="' . $called_today . '" data-last-call-date="' . $last_call_date . '" data-toggle="tooltip" title="Dial"><i class="fa fa-phone"></i></a>' .
                    '<a href="javascript:void(0)" class="btn btn-info btn-xs btn-view-call-details" data-id="' . $call['id'] . '" data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></i></a>' .
                    ($can_edit ? '<a href="javascript:void(0)" class="btn btn-primary btn-xs btn-quick-resolve" data-id="' . $call['id'] . '" data-toggle="tooltip" title="Mark Done"><i class="fa fa-check"></i></a><a href="javascript:void(0)" class="btn btn-warning btn-xs btn-quick-reschedule" data-id="' . $call['id'] . '" data-toggle="tooltip" title="Reschedule Date"><i class="fa fa-calendar"></i></a>' : '') .
                    '<a href="javascript:void(0)" class="btn btn-default btn-xs btn-follow-up-log" data-id="' . $call['id'] . '" onclick="follow_up(\'' . $call['id'] . '\'); return false;" data-toggle="tooltip" title="Log Follow-up"><i class="fa fa-pencil"></i></a>' .
                    '</div>';

                $html .= '<tr data-called-today="' . $called_today . '" ' . $row_style . '>' .
                    '<td data-label="Student">' . $student_cell . '</td>' .
                    '<td data-label="Father Name">' . htmlspecialchars($call['father_name'] ?? '') . '</td>' .
                    '<td data-label="Class">' . htmlspecialchars($call['class'] . ' (' . $call['section'] . ')') . '</td>' .
                    '<td data-label="Pickup Point">' . htmlspecialchars($call['pickup_point_name'] ?? '') . '</td>' .
                    '<td data-label="Phone">' . htmlspecialchars($call['phone_number'] ?? '') . '</td>' .
                    '<td data-label="Purpose">' . get_purpose_pill($call['purpose_name']) . '</td>' .
                    '<td data-label="Status">' . get_call_status_pill($call['call_status']) . '</td>' .
                    '<td data-label="Date">' . date($this->customlib->getSchoolDateFormat(true, true), strtotime($call['date'])) . '</td>' .
                    '<td data-label="Note">' . $note_cell . '</td>' .
                    '<td data-label="Follow-up Status">' . $followup_cell . '</td>' .
                    '<td data-label="Assigned To">' . $assigned_cell . '</td>' .
                    '<td data-label="Created By">' . htmlspecialchars(($call['staff_name'] ?? '') . ' ' . ($call['staff_surname'] ?? '')) . '</td>' .
                    '<td data-label="Action" class="pull-right">' . $action_cell . '</td>' .
                    '</tr>';
            }
        }

        return $this->response->setJSON(['status' => 'success', 'html' => $html, 'count' => count($calls)]);
    }

    public function get_pending_reminders_ajax()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_view')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized', 'calls' => []]);
        }

        $userdata = $this->customlib->getUserData();
        $staff_id = (int)($userdata["id"] ?? 0);

        $this->studentcallModel->auto_resolve_zero_due_fee_followups();
        $calls = $this->studentcallModel->get_due_reminders_for_staff($staff_id, 150);

        $formatted = [];
        foreach ($calls as $call) {
            $due_time_str = '';
            $due_date_raw = $call['due_date'];
            $due_timestamp = strtotime($due_date_raw);

            if (date('H:i:s', $due_timestamp) !== '00:00:00') {
                $due_time_str = date('h:i A', $due_timestamp);
                $formatted_due = date('M d, Y h:i A', $due_timestamp);
            } else {
                $formatted_due = date('M d, Y', $due_timestamp);
            }

            $primary_phone = !empty($call['phone_number']) ? $call['phone_number'] :
                            (!empty($call['father_phone']) ? $call['father_phone'] :
                            (!empty($call['mobileno']) ? $call['mobileno'] :
                            (!empty($call['guardian_phone']) ? $call['guardian_phone'] : $call['mother_phone'])));

            $clean_phone = preg_replace('/[^0-9+]/', '', (string)$primary_phone);
            $image = (!empty($call['image'])) ? base_url($call['image']) : base_url('uploads/student_images/no_image.png');

            $lead_owner = trim(($call['assigned_staff_name'] ?? '') . ' ' . ($call['assigned_staff_surname'] ?? ''));
            if (empty($lead_owner)) {
                $lead_owner = trim(($userdata['name'] ?? '') . ' ' . ($userdata['surname'] ?? ''));
            }

            $formatted[] = [
                'followup_id'        => $call['followup_id'],
                'student_call_id'    => $call['student_call_id'],
                'student_id'         => $call['student_id'],
                'student_session_id' => $call['student_session_id'],
                'student_name'       => trim($call['firstname'] . ' ' . ($call['middlename'] ? $call['middlename'] . ' ' : '') . $call['lastname']),
                'admission_no'       => $call['admission_no'],
                'roll_no'            => $call['roll_no'],
                'class_name'         => $call['class'] . ' (' . $call['section'] . ')',
                'father_name'        => $call['father_name'],
                'contact_person'     => $call['contact_person'] ?? 'Father',
                'phone'              => $clean_phone,
                'phone_display'      => $primary_phone,
                'image'              => $image,
                'purpose'            => $call['purpose_name'] ?: 'Follow-up Call',
                'priority'           => $call['priority'] ?: 'Medium',
                'lead_owner'         => $lead_owner,
                'lead_status'        => $call['call_status'] ?: 'Pending',
                'due_date_raw'       => $due_date_raw,
                'due_formatted'      => $formatted_due,
                'due_time_str'       => $due_time_str,
                'is_overdue'         => (strtotime(date('Y-m-d', $due_timestamp)) < strtotime(date('Y-m-d'))),
                'is_today'           => (date('Y-m-d', $due_timestamp) === date('Y-m-d')),
                'previous_remarks'   => $call['previous_remarks'] ?? ''
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'count'  => count($formatted),
            'calls'  => $formatted
        ]);
    }

    public function quick_log_reminder_ajax()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_edit')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Permission denied']);
        }

        $followup_id     = (int)$this->request->getPost('followup_id');
        $student_call_id = (int)$this->request->getPost('student_call_id');
        $call_status     = (string)$this->request->getPost('call_status');
        $status          = (string)$this->request->getPost('status');
        $remarks         = (string)$this->request->getPost('remarks');
        $next_date       = (string)$this->request->getPost('next_date');
        $next_time       = (string)$this->request->getPost('next_time');
        $next_priority   = $this->request->getPost('next_priority') ?: 'Medium';

        $userdata = $this->customlib->getUserData();

        if (empty($followup_id) || empty($student_call_id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid call identifiers']);
        }

        $update_data = [
            'status'      => !empty($status) ? $status : 'Completed',
            'call_status' => $call_status,
            'remarks'     => $remarks
        ];
        $this->studentcallModel->update_followup($followup_id, $update_data);

        $call_update = [
            'call_status' => $call_status,
            'date'        => date('Y-m-d H:i:s')
        ];
        $this->studentcallModel->update_call($student_call_id, $call_update);

        if (!empty($next_date)) {
            $parsed_date = $this->parseInputDate($next_date);
            if (!empty($parsed_date)) {
                $due_datetime = $parsed_date;
                if (!empty($next_time)) {
                    $due_datetime .= ' ' . date('H:i:s', strtotime($next_time));
                }

                $new_followup = [
                    'student_call_id' => $student_call_id,
                    'student_id'      => $this->request->getPost('student_id'),
                    'due_date'        => $due_datetime,
                    'priority'        => $next_priority,
                    'assigned_to'     => $userdata['id'],
                    'status'          => 'Pending',
                    'created_by'      => $userdata['id']
                ];
                $this->studentcallModel->add_followup($new_followup);
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Call logged successfully']);
    }

    public function quick_reschedule_reminder_ajax()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_edit')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Permission denied']);
        }

        $followup_id = (int)$this->request->getPost('followup_id');
        $new_date    = (string)$this->request->getPost('new_date');
        $new_time    = (string)$this->request->getPost('new_time');

        if (empty($followup_id) || empty($new_date)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Follow-up ID and Date are required']);
        }

        $parsed_date = $this->parseInputDate($new_date);
        if (empty($parsed_date)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid date format']);
        }

        $due_datetime = $parsed_date;
        if (!empty($new_time)) {
            $due_datetime .= ' ' . date('H:i:s', strtotime($new_time));
        }

        $this->studentcallModel->update_followup($followup_id, [
            'due_date' => $due_datetime,
            'status'   => 'Pending'
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Call rescheduled successfully']);
    }
}
