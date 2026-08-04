<style type="text/css">
    @media (max-width: 767px) {
        .mobile-card-table thead {
            display: none !important;
        }
        .mobile-card-table, 
        .mobile-card-table tbody, 
        .mobile-card-table tr, 
        .mobile-card-table td {
            display: block !important;
            width: 100% !important;
        }
        .mobile-card-table tr {
            background: #fff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            margin-bottom: 16px !important;
            padding: 14px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
        }
        .mobile-card-table td {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 8px 0 !important;
            border: none !important;
            border-bottom: 1px dashed #edf2f7 !important;
            text-align: right !important;
            font-size: 13px !important;
        }
        .mobile-card-table td:last-child {
            border-bottom: none !important;
            padding-top: 12px !important;
            display: flex !important;
            gap: 8px !important;
        }
        .mobile-card-table td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #64748b;
            text-align: left;
            padding-right: 10px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .mobile-card-table td[data-label="Action"] .btn {
            flex: 1;
            padding: 8px !important;
            font-size: 12px !important;
            border-radius: 6px !important;
        }
        .mobile-card-table td .form-control {
            max-width: 60% !important;
            display: inline-block !important;
            height: 34px !important;
            font-size: 12px !important;
        }
        .view-snapshot {
            margin-left: 6px;
            padding: 2px 6px !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-users"></i> <?php echo $this->lang->line('ptm_attendance'); ?> : <?php echo $ptm['title']; ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example mobile-card-table">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <th><?php echo $this->lang->line('student_name'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('attendee'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student) { 
                                        $att = isset($attendances[$student['student_session_id']]) ? $attendances[$student['student_session_id']] : [];
                                    ?>
                                        <tr>
                                            <td data-label="<?php echo $this->lang->line('admission_no'); ?>"><?php echo $student['admission_no']; ?></td>
                                            <td data-label="<?php echo $this->lang->line('student_name'); ?>">
                                                <span><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></span>
                                                <a href="#" class="btn btn-xs btn-info view-snapshot" data-student="<?php echo $student['id']; ?>" data-session="<?php echo $student['student_session_id']; ?>"><i class="fa fa-line-chart"></i> <?php echo $this->lang->line('snapshot'); ?></a>
                                            </td>
                                            <td data-label="<?php echo $this->lang->line('class'); ?>"><?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></td>
                                            <td data-label="<?php echo $this->lang->line('status'); ?>">
                                                <select class="form-control" id="status_<?php echo $student['student_session_id']; ?>">
                                                    <option value="absent" <?php echo (isset($att['status']) && $att['status']=='absent') ? 'selected' : ''; ?>>Absent</option>
                                                    <option value="present" <?php echo (isset($att['status']) && $att['status']=='present') ? 'selected' : ''; ?>>Present</option>
                                                </select>
                                            </td>
                                            <td data-label="<?php echo $this->lang->line('attendee'); ?>">
                                                <select class="form-control" id="attendee_<?php echo $student['student_session_id']; ?>">
                                                    <option value="">-</option>
                                                    <?php $atts = ['father', 'mother', 'both', 'guardian', 'other']; 
                                                    foreach($atts as $a) { ?>
                                                        <option value="<?php echo $a; ?>" <?php echo (isset($att['attendee_type']) && $att['attendee_type']==$a) ? 'selected' : ''; ?>><?php echo ucfirst($a); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td data-label="Action">
                                                <button type="button" class="btn btn-primary btn-sm add-remarks" data-session="<?php echo $student['student_session_id']; ?>"><i class="fa fa-commenting-o"></i> <?php echo $this->lang->line('remarks'); ?></button>
                                                
                                                <!-- Hidden fields for remarks data -->
                                                <input type="hidden" id="arrival_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['arrival_time']) ? $att['arrival_time'] : ''; ?>">
                                                <input type="hidden" id="departure_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['departure_time']) ? $att['departure_time'] : ''; ?>">
                                                <input type="hidden" id="points_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['discussion_points']) ? htmlspecialchars($att['discussion_points']) : ''; ?>">
                                                <input type="hidden" id="parent_rmk_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['parent_remarks']) ? htmlspecialchars($att['parent_remarks']) : ''; ?>">
                                                <input type="hidden" id="teacher_rmk_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['teacher_remarks']) ? htmlspecialchars($att['teacher_remarks']) : ''; ?>">
                                                <input type="hidden" id="c_academics_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['concerns_academics']) ? htmlspecialchars($att['concerns_academics']) : ''; ?>">
                                                <input type="hidden" id="c_attendance_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['concerns_attendance']) ? htmlspecialchars($att['concerns_attendance']) : ''; ?>">
                                                <input type="hidden" id="c_behavior_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['concerns_behavior']) ? htmlspecialchars($att['concerns_behavior']) : ''; ?>">
                                                <input type="hidden" id="c_discipline_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['concerns_discipline']) ? htmlspecialchars($att['concerns_discipline']) : ''; ?>">
                                                <input type="hidden" id="action_items_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['action_items']) ? htmlspecialchars($att['action_items']) : ''; ?>">
                                                <input type="hidden" id="followup_req_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['followup_required']) ? $att['followup_required'] : '0'; ?>">
                                                <input type="hidden" id="followup_ass_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['followup_assigned_to']) ? $att['followup_assigned_to'] : ''; ?>">
                                                <input type="hidden" id="followup_date_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['followup_date']) ? $att['followup_date'] : ''; ?>">
                                                
                                                <button type="button" class="btn btn-success btn-sm save-attendance" data-session="<?php echo $student['student_session_id']; ?>"><i class="fa fa-save"></i> Save</button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal for Remarks -->
<div id="remarksModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('discussions_and_remarks'); ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal_session_id">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('arrival_time'); ?></label>
                            <input type="time" class="form-control" id="m_arrival">
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('discussion_points'); ?></label>
                            <textarea class="form-control" id="m_points"></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('parent_remarks'); ?></label>
                            <textarea class="form-control" id="m_parent_rmk"></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('concerns_academics'); ?></label>
                            <textarea class="form-control" id="m_c_academics"></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('concerns_behavior'); ?></label>
                            <textarea class="form-control" id="m_c_behavior"></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('action_items'); ?></label>
                            <textarea class="form-control" id="m_action_items"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('departure_time'); ?></label>
                            <input type="time" class="form-control" id="m_departure">
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('teacher_remarks'); ?></label>
                            <textarea class="form-control" id="m_teacher_rmk"></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('concerns_attendance'); ?></label>
                            <textarea class="form-control" id="m_c_attendance"></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('concerns_discipline'); ?></label>
                            <textarea class="form-control" id="m_c_discipline"></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('follow_up_required'); ?></label>
                            <select class="form-control" id="m_followup_req">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('assign_to'); ?></label>
                            <select class="form-control" id="m_followup_ass">
                                <option value="">Select</option>
                                <?php foreach($staffs as $s) { ?>
                                    <option value="<?php echo $s['id']; ?>"><?php echo $s['name'] . ' ' . $s['surname']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('follow_up_date'); ?></label>
                            <input type="date" class="form-control" id="m_followup_date">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveModalData()"><?php echo $this->lang->line('save_remarks'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Right Sidebar for Snapshot -->
<style>
.ptm-sidebar {
    height: 100%;
    width: 0;
    position: fixed;
    z-index: 9999;
    top: 0;
    right: 0;
    background-color: #fff;
    overflow-x: hidden;
    transition: 0.5s;
    box-shadow: -2px 0 5px rgba(0,0,0,0.2);
}
.ptm-sidebar-header {
    padding: 15px;
    background: #f4f4f4;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
}
.ptm-sidebar-content {
    padding: 15px;
}
</style>
<div id="ptmSnapshotSidebar" class="ptm-sidebar">
    <div class="ptm-sidebar-header">
        <h4><i class="fa fa-line-chart"></i> <?php echo $this->lang->line('performance_snapshot'); ?></h4>
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    </div>
    <div class="ptm-sidebar-content" id="snapshot_content">
        <!-- Content loaded via ajax -->
    </div>
</div>

<script>
    var current_ptm_id = '<?php echo $ptm['id']; ?>';

    $('.add-remarks').click(function(){
        var sess_id = $(this).data('session');
        $('#modal_session_id').val(sess_id);
        $('#m_arrival').val($('#arrival_' + sess_id).val());
        $('#m_departure').val($('#departure_' + sess_id).val());
        $('#m_points').val($('#points_' + sess_id).val());
        $('#m_parent_rmk').val($('#parent_rmk_' + sess_id).val());
        $('#m_teacher_rmk').val($('#teacher_rmk_' + sess_id).val());
        $('#m_c_academics').val($('#c_academics_' + sess_id).val());
        $('#m_c_attendance').val($('#c_attendance_' + sess_id).val());
        $('#m_c_behavior').val($('#c_behavior_' + sess_id).val());
        $('#m_c_discipline').val($('#c_discipline_' + sess_id).val());
        $('#m_action_items').val($('#action_items_' + sess_id).val());
        $('#m_followup_req').val($('#followup_req_' + sess_id).val());
        $('#m_followup_ass').val($('#followup_ass_' + sess_id).val());
        $('#m_followup_date').val($('#followup_date_' + sess_id).val());
        
        $('#remarksModal').modal('show');
    });

    function saveModalData() {
        var sess_id = $('#modal_session_id').val();
        $('#arrival_' + sess_id).val($('#m_arrival').val());
        $('#departure_' + sess_id).val($('#m_departure').val());
        $('#points_' + sess_id).val($('#m_points').val());
        $('#parent_rmk_' + sess_id).val($('#m_parent_rmk').val());
        $('#teacher_rmk_' + sess_id).val($('#m_teacher_rmk').val());
        $('#c_academics_' + sess_id).val($('#m_c_academics').val());
        $('#c_attendance_' + sess_id).val($('#m_c_attendance').val());
        $('#c_behavior_' + sess_id).val($('#m_c_behavior').val());
        $('#c_discipline_' + sess_id).val($('#m_c_discipline').val());
        $('#action_items_' + sess_id).val($('#m_action_items').val());
        $('#followup_req_' + sess_id).val($('#m_followup_req').val());
        $('#followup_ass_' + sess_id).val($('#m_followup_ass').val());
        $('#followup_date_' + sess_id).val($('#m_followup_date').val());
        
        $('#remarksModal').modal('hide');
    }

    $('.save-attendance').click(function(){
        var sess_id = $(this).data('session');
        var btn = $(this);
        btn.button('loading');
        
        $.ajax({
            url: "<?php echo site_url('admin/ptm/save_attendance') ?>",
            type: "POST",
            data: {
                ptm_id: current_ptm_id,
                student_session_id: sess_id,
                status: $('#status_' + sess_id).val(),
                attendee_type: $('#attendee_' + sess_id).val(),
                arrival_time: $('#arrival_' + sess_id).val(),
                departure_time: $('#departure_' + sess_id).val(),
                discussion_points: $('#points_' + sess_id).val(),
                parent_remarks: $('#parent_rmk_' + sess_id).val(),
                teacher_remarks: $('#teacher_rmk_' + sess_id).val(),
                concerns_academics: $('#c_academics_' + sess_id).val(),
                concerns_attendance: $('#c_attendance_' + sess_id).val(),
                concerns_behavior: $('#c_behavior_' + sess_id).val(),
                concerns_discipline: $('#c_discipline_' + sess_id).val(),
                action_items: $('#action_items_' + sess_id).val(),
                followup_required: $('#followup_req_' + sess_id).val(),
                followup_assigned_to: $('#followup_ass_' + sess_id).val(),
                followup_date: $('#followup_date_' + sess_id).val()
            },
            dataType: 'json',
            success: function (res) {
                if (res.status == 'success') {
                    successMsg(res.message);
                } else {
                    errorMsg(res.message);
                }
                btn.button('reset');
            },
            error: function () {
                btn.button('reset');
                errorMsg("Error saving data");
            }
        });
    });

    $('.view-snapshot').click(function(e){
        e.preventDefault();
        var student_id = $(this).data('student');
        var sess_id = $(this).data('session');
        $('#snapshot_content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
        openNav();
        
        $.ajax({
            url: "<?php echo site_url('admin/ptm/get_student_snapshot') ?>",
            type: "POST",
            data: {student_id: student_id, student_session_id: sess_id, ptm_id: current_ptm_id},
            dataType: 'json',
            success: function (res) {
                $('#snapshot_content').html(res.page);
            }
        });
    });

    function openNav() {
        document.getElementById("ptmSnapshotSidebar").style.width = "800px";
    }

    function closeNav() {
        document.getElementById("ptmSnapshotSidebar").style.width = "0";
    }
</script>
