<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f4f6f9;
        font-family: 'Inter', sans-serif;
    }
    .d2-card {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #eaeaea;
    }
    .d2-title {
        font-size: 14px;
        font-weight: 600;
        color: #8a8a8a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        border-bottom: 1px solid #f4f4f4;
        padding-bottom: 10px;
    }
    .d2-pill {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }
    .pill-absent { background: #fff5f8; color: #d8456a; border: 1px solid #fbe0e8; }
    .pill-late { background: #fffcf5; color: #d09435; border: 1px solid #fbedcf; }
    .pill-halfday { background: #fdfaff; color: #9d50ce; border: 1px solid #f4e8fb; }
    .pill-danger { background: #d8456a; color: #fff; }
    .pill-warning { background: #fbedcf; color: #d09435; }
    .pill-info { background: #e0f2fe; color: #0284c7; }
    
    .btn-sleek {
        border-radius: 4px;
        box-shadow: none;
        border: none;
        padding: 6px 12px;
        font-weight: 600;
    }
    .action-btn {
        width: 28px;
        height: 28px;
        line-height: 28px;
        text-align: center;
        padding: 0;
        border-radius: 50%;
        display: inline-block;
        margin: 0 2px;
        color: #fff;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }
    .action-btn:hover { transform: translateY(-2px); box-shadow: 0 3px 6px rgba(0,0,0,0.15); color: #fff; }
    .btn-call { background: #3b82f6; }
    .btn-sms { background: #8b5cf6; }
    .btn-email { background: #10b981; }
    .btn-edit { background: #f59e0b; }
    .btn-history { background: #64748b; }
    
    .table>thead>tr>th {
        border-bottom: 2px solid #eaeaea;
        color: #666;
        font-size: 12px;
        text-transform: uppercase;
    }
    .table>tbody>tr>td {
        vertical-align: middle;
        border-top: 1px solid #f4f4f4;
    }
</style>

<div class="content-wrapper dashboard2-wrapper">
    <section class="content-header">
        <div class="row" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
            <div class="col-md-6">
                <h1 style="margin:0; font-size: 24px; font-weight:700;">Absentee Follow-up</h1>
                <small style="color:#888;">Attendance / Absentee Follow-up</small>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="d2-card">
                    <div class="d2-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></div>
                    <form id='form1' action="<?php echo site_url('admin/absenteefollowup') ?>" method="post" accept-charset="utf-8">
                        <div style="padding: 10px 0;">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                        <select id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label>
                                        <input id="date" name="date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date', $date); ?>" readonly="readonly"/>
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="padding-top: 10px; text-align: right;">
                            <button type="submit" name="search" value="search" class="btn btn-primary btn-sleek"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                        </div>
                    </form>
                </div>
                
                <?php if (isset($resultlist)) { ?>
                <div class="d2-card">
                    <div class="d2-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('absentee_followup'); ?></div>
                    <div style="padding-top: 10px;">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover example" style="border: none;">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('student_name'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('roll_no'); ?></th>
                                        <th><?php echo $this->lang->line('father_name'); ?></th>
                                        <th><?php echo $this->lang->line('contact_no'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('consecutive_days'); ?></th>
                                        <th><?php echo $this->lang->line('followup_status'); ?></th>
                                        <th><?php echo $this->lang->line('remark'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($resultlist)) {
                                            foreach ($resultlist as $student) {
                                                $consecutive = $student['consecutive_days'];
                                                $badge_class = 'pill-info';
                                                $badge_text = 'Day ' . $consecutive;
                                                if ($consecutive >= 3) {
                                                    $badge_class = 'pill-danger';
                                                    $badge_text = 'Day 3+ (' . $this->lang->line('habitual_absent') . ')';
                                                } elseif ($consecutive == 2) {
                                                    $badge_class = 'pill-warning';
                                                }
                                                
                                                $status_badge = $student['attendence_type_id'] == 4 ? 'pill-absent' : ($student['attendence_type_id'] == 3 ? 'pill-late' : 'pill-halfday');
                                                ?>
                                                <tr>
                                                    <td style="font-weight: 600; color: #333;"><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                                                    <td><?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></td>
                                                    <td><?php echo $student['roll_no']; ?></td>
                                                    <td><?php echo $student['father_name']; ?></td>
                                                    <td><?php echo $student['guardian_phone'] ? $student['guardian_phone'] : $student['mobileno']; ?></td>
                                                    <td><span class="d2-pill <?php echo $status_badge; ?>"><?php echo $student['att_type']; ?></span></td>
                                                    <td><span class="d2-pill <?php echo $badge_class; ?>"><?php echo $badge_text; ?></span></td>
                                                    <td id="td_status_<?php echo $student['student_session_id']; ?>"><span style="font-weight: 600; color: #555;"><?php echo $student['followup_status']; ?></span></td>
                                                    <td id="td_remark_<?php echo $student['student_session_id']; ?>" style="color: #888; font-size: 12px;"><?php echo $student['remark']; ?></td>
                                                    <td class="text-right" style="white-space: nowrap;">
                                                        <?php $contact = $student['guardian_phone'] ? $student['guardian_phone'] : $student['mobileno']; ?>
                                                        <?php if ($contact) { ?>
                                                            <a href="tel:<?php echo $contact; ?>" class="action-btn btn-call" data-toggle="tooltip" title="<?php echo $this->lang->line('call'); ?>" onclick="logAction(<?php echo $student['student_session_id']; ?>, 'Call')"><i class="fa fa-phone"></i></a>
                                                        <?php } ?>
                                                        <button class="action-btn btn-sms" data-toggle="tooltip" title="SMS" onclick="logAction(<?php echo $student['student_session_id']; ?>, 'SMS')"><i class="fa fa-comment-o"></i></button>
                                                        <button class="action-btn btn-email" data-toggle="tooltip" title="Email" onclick="logAction(<?php echo $student['student_session_id']; ?>, 'Email')"><i class="fa fa-envelope-o"></i></button>
                                                        <button class="action-btn btn-edit" data-toggle="tooltip" title="<?php echo $this->lang->line('status_update'); ?>" onclick="openStatusModal(<?php echo $student['student_session_id']; ?>)"><i class="fa fa-pencil"></i></button>
                                                        <button class="action-btn btn-history" data-toggle="tooltip" title="<?php echo $this->lang->line('history'); ?>" onclick="openHistoryModal(<?php echo $student['student_session_id']; ?>)"><i class="fa fa-history"></i></button>
                                                    </td>
                                                </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('status_update'); ?></h4>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <input type="hidden" name="student_session_id" id="status_student_session_id" value="">
                    <input type="hidden" name="date" id="status_date" value="<?php echo set_value('date', $date); ?>">
                    <input type="hidden" name="action" value="Status Update">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('followup_status'); ?> <small class="req"> *</small></label>
                        <select name="followup_status" id="followup_status" class="form-control" required>
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <option value="Not Contacted"><?php echo $this->lang->line('not_contacted'); ?></option>
                            <option value="Called - Answered"><?php echo $this->lang->line('called_answered'); ?></option>
                            <option value="Not Reachable"><?php echo $this->lang->line('not_reachable'); ?></option>
                            <option value="Switched Off"><?php echo $this->lang->line('switched_off'); ?></option>
                            <option value="Wrong Number"><?php echo $this->lang->line('wrong_number'); ?></option>
                            <option value="SMS Sent"><?php echo $this->lang->line('sms_sent'); ?></option>
                            <option value="Email Sent"><?php echo $this->lang->line('email_sent'); ?></option>
                            <option value="Informed"><?php echo $this->lang->line('informed'); ?></option>
                            <option value="Pending"><?php echo $this->lang->line('pending'); ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('remark'); ?></label>
                        <textarea name="remark" id="remark" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary" id="btn_status_save"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="historyModalLabel"><?php echo $this->lang->line('history'); ?></h4>
            </div>
            <div class="modal-body" id="history_body">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        if (class_id != '') {
            getSectionByClass(class_id, section_id);
        }

        $('#class_id').change(function () {
            var class_id = $('#class_id').val();
            getSectionByClass(class_id, 0);
        });

        $('#statusForm').on('submit', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $btn = $("#btn_status_save");
            $btn.button('loading');
            $.ajax({
                url: "<?php echo site_url('admin/absenteefollowup/save_followup') ?>",
                type: "POST",
                data: $this.serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status == "fail") {
                        errorMsg(res.error);
                    } else {
                        successMsg(res.message);
                        $('#statusModal').modal('hide');
                        // Update grid visually
                        var sid = $('#status_student_session_id').val();
                        $('#td_status_' + sid).text($('#followup_status').val());
                        $('#td_remark_' + sid).text($('#remark').val());
                    }
                    $btn.button('reset');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $btn.button('reset');
                }
            });
        });
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected=selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    function openStatusModal(student_session_id) {
        $('#status_student_session_id').val(student_session_id);
        $('#followup_status').val('');
        $('#remark').val('');
        $('#statusModal').modal('show');
    }

    function logAction(student_session_id, action) {
        $.ajax({
            url: "<?php echo site_url('admin/absenteefollowup/save_followup') ?>",
            type: "POST",
            data: {
                student_session_id: student_session_id,
                date: $('#date').val(),
                action: action,
                followup_status: action + ' Sent/Initiated',
                remark: 'Action triggered via quick button'
            },
            dataType: 'json',
            success: function (res) {
                if (res.status == "success") {
                    successMsg(action + ' logged successfully.');
                    $('#td_status_' + student_session_id).text(action + ' Sent/Initiated');
                    $('#td_remark_' + student_session_id).text('Action triggered via quick button');
                }
            }
        });
    }

    function openHistoryModal(student_session_id) {
        $('#history_body').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i></div>');
        $('#historyModal').modal('show');
        $.ajax({
            url: "<?php echo site_url('admin/absenteefollowup/get_history') ?>",
            type: "POST",
            data: {
                student_session_id: student_session_id,
                date: $('#date').val()
            },
            dataType: 'json',
            success: function (res) {
                if (res.status == "success") {
                    $('#history_body').html(res.html);
                }
            }
        });
    }
</script>
