<div class="content-wrapper" style="min-height: 348px;">
    <section class="content-header">
        <h1><i class="fa fa-phone"></i> <?php echo ($this->lang->line('student_call_log') ? $this->lang->line('student_call_log') : 'Student Call Log'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('student_call_log', 'can_add')) { ?>
                            <div class="box-tools pull-right">
                                <a data-toggle="modal" data-target="#addCallModal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo ($this->lang->line('add_call') ? $this->lang->line('add_call') : 'Add Call'); ?></a>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <form role="form" action="<?php echo site_url('admin/studentcall') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($class_list as $class) { ?>
                                            <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?></label>
                                    <select id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('purpose'); ?></label>
                                    <select name="purpose_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($purposes as $purpose) { ?>
                                            <option value="<?php echo $purpose['id'] ?>" <?php if (set_value('purpose_id') == $purpose['id']) echo "selected=selected" ?>><?php echo $purpose['purpose'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo ($this->lang->line('status') ? $this->lang->line('status') : 'Status'); ?></label>
                                    <select name="status" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <option value="Connected">Connected</option>
                                        <option value="Not Answered">Not Answered</option>
                                        <option value="Busy">Busy</option>
                                        <option value="Switched Off">Switched Off</option>
                                        <option value="Wrong Number">Wrong Number</option>
                                        <option value="Callback Requested">Callback Requested</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date_from'); ?></label>
                                    <input type="text" name="date_from" class="form-control date" value="<?php echo set_value('date_from') ?>">
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date_to'); ?></label>
                                    <input type="text" name="date_to" class="form-control date" value="<?php echo set_value('date_to') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="box box-primary" id="call_list">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-list"></i> <?php echo ($this->lang->line('student_call_log') ? $this->lang->line('student_call_log') : 'Student Call Log'); ?> <?php echo $this->lang->line('list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('student'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo ($this->lang->line('contact_person') ? $this->lang->line('contact_person') : 'Contact Person'); ?></th>
                                        <th><?php echo $this->lang->line('call_type'); ?></th>
                                        <th><?php echo $this->lang->line('purpose'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th>Follow-up Status</th>
                                        <th><?php echo $this->lang->line('created_by'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($calls)) {
                                        foreach ($calls as $call) { ?>
                                            <tr>
                                                <td><?php echo $call['firstname'] . " " . $call['lastname'] . " (" . $call['admission_no'] . ")"; ?></td>
                                                <td><?php echo $call['class'] . " (" . $call['section'] . ")"; ?></td>
                                                <td><?php echo $call['phone_number']; ?></td>
                                                <td><?php echo $call['contact_person']; ?></td>
                                                <td><?php echo $call['call_type']; ?></td>
                                                <td><?php echo $call['purpose_name']; ?></td>
                                                <td><?php echo $call['call_status']; ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($call['date'])); ?></td>
                                                <td>
                                                    <?php 
                                                    if ($call['total_followups'] == 0) {
                                                        echo "<span class='label label-default'>None</span>";
                                                    } else if ($call['pending_count'] > 0) {
                                                        echo "<span class='label label-warning'>Pending (" . date($this->customlib->getSchoolDateFormat(), strtotime($call['next_follow_up_date'])) . ")</span>";
                                                    } else {
                                                        echo "<span class='label label-success'>Resolved</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $call['staff_name'] . " " . $call['staff_surname']; ?></td>
                                                <td class="pull-right">
                                                    <?php if ($this->rbac->hasPrivilege('student_call_log', 'can_view')) { ?>
                                                        <a href="#" class="btn btn-default btn-xs" onclick="follow_up('<?php echo $call['id']; ?>')" data-toggle="tooltip" title="<?php echo ($this->lang->line('follow_up') ? $this->lang->line('follow_up') : 'Follow Up'); ?>">
                                                            <i class="fa fa-phone"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Call Modal -->
<div id="addCallModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo ($this->lang->line('add_call') ? $this->lang->line('add_call') : 'Add Call'); ?></h4>
            </div>
            <div class="modal-body">
                <form id="addCallForm" method="post" accept-charset="utf-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo ($this->lang->line('search_student') ? $this->lang->line('search_student') : 'Search Student'); ?></label>
                                <input type="text" id="search_student_keyword" class="form-control" placeholder="Search by name, admission no, roll no..." autocomplete="off">
                                <ul id="student_search_results" class="list-group" style="position: absolute; z-index: 1000; width: 95%; max-height: 200px; overflow-y: auto;"></ul>
                            </div>
                        </div>
                    </div>
                    
                    <div id="call_details_section" style="display:none;">
                        <input type="hidden" name="student_id" id="student_id">
                        <input type="hidden" name="student_session_id" id="student_session_id">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- LEFT COLUMN: FORM -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('student'); ?></label>
                                            <input type="text" id="selected_student_name" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('call_type'); ?> <small class="req"> *</small></label>
                                            <select name="call_type" class="form-control">
                                                <option value="Incoming">Incoming</option>
                                                <option value="Outgoing" selected>Outgoing</option>
                                            </select>
                                            <span class="text-danger" id="error_call_type"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo ($this->lang->line('contact_person') ? $this->lang->line('contact_person') : 'Contact Person'); ?> <small class="req"> *</small></label>
                                            <select name="contact_person" id="contact_person" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <option value="Father">Father</option>
                                                <option value="Mother">Mother</option>
                                                <option value="Guardian">Guardian</option>
                                                <option value="Student">Student</option>
                                            </select>
                                            <span class="text-danger" id="error_contact_person"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('phone'); ?> <small class="req"> *</small></label>
                                            <input type="text" name="phone_number" id="phone_number" class="form-control">
                                            <span class="text-danger" id="error_phone_number"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('purpose'); ?></label>
                                            <select name="purpose_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($purposes as $purpose) { ?>
                                                    <option value="<?php echo $purpose['id'] ?>"><?php echo $purpose['purpose'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('status'); ?></label>
                                            <select name="call_status" class="form-control">
                                                <option value="Connected">Connected</option>
                                                <option value="Not Answered">Not Answered</option>
                                                <option value="Busy">Busy</option>
                                                <option value="Switched Off">Switched Off</option>
                                                <option value="Wrong Number">Wrong Number</option>
                                                <option value="Callback Requested">Callback Requested</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('date'); ?> <small class="req"> *</small></label>
                                            <input type="text" name="date" class="form-control datetime" value="<?php echo date($this->customlib->getSchoolDateFormat(true, true)); ?>">
                                            <span class="text-danger" id="error_date"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Duration <small class="text-muted">(Optional)</small></label>
                                            <input type="text" name="duration" class="form-control" placeholder="e.g. 5 mins">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('note'); ?></label>
                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                </div>
                                
                                <hr>
                                <h4 class="box-title"><?php echo ($this->lang->line('follow_up') ? $this->lang->line('follow_up') : 'Create Follow Up'); ?> (Optional)</h4>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo ($this->lang->line('next_follow_up_date') ? $this->lang->line('next_follow_up_date') : 'Next Date'); ?></label>
                                            <input type="text" name="follow_up_date" class="form-control fw_date" autocomplete="off" placeholder="Select future date">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo ($this->lang->line('priority') ? $this->lang->line('priority') : 'Priority'); ?></label>
                                            <select name="priority" class="form-control">
                                                <option value="Low">Low</option>
                                                <option value="Medium" selected>Medium</option>
                                                <option value="High">High</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo ($this->lang->line('assign_to') ? $this->lang->line('assign_to') : 'Assign To'); ?></label>
                                            <select name="assigned_to" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($staff_list as $staff) { ?>
                                                    <option value="<?php echo $staff['id'] ?>"><?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ")" ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- RIGHT COLUMN: HISTORY -->
                                <div class="box box-primary" style="margin-bottom:0;">
                                    <div class="box-header with-border" style="padding: 10px;">
                                        <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-history"></i> Recent History</h3>
                                    </div>
                                    <div class="box-body" id="recent_history_container" style="max-height: 400px; overflow-y: auto; background: #f9f9f9; padding: 10px;">
                                        <div class="text-center text-muted" style="padding: 20px;">Select a student to view history</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right" style="padding-top: 15px; border-top: 1px solid #e5e5e5; margin-top: 15px;">
                            <button type="button" class="btn btn-default" id="saveAndNextBtn" style="display:none;" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving...">Save & Log Another</button>
                            <button type="submit" class="btn btn-info" id="submitBtn" style="display:none;" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>">Save & Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="followUpModal" tabindex="-1" role="dialog" aria-labelledby="followUpModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="followUpModalLabel"><?php echo ($this->lang->line('follow_up') ? $this->lang->line('follow_up') : 'Follow Up'); ?></h4>
            </div>
            <div class="modal-body" id="followUpModalBody">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
    var student_phones = {};

    $(document).ready(function () {
        $('#search_student_keyword').on('keyup', function () {
            var keyword = $(this).val();
            if (keyword.length >= 2) {
                $.ajax({
                    url: '<?php echo site_url("admin/studentcall/search_student") ?>',
                    type: 'POST',
                    data: {keyword: keyword},
                    dataType: 'json',
                    success: function (res) {
                        var html = '';
                        student_phones = {};
                        $.each(res, function (index, value) {
                            student_phones[value.student_id] = {
                                Father: value.father_phone,
                                Mother: value.mother_phone,
                                Guardian: value.guardian_phone,
                                Student: value.mobileno
                            };
                            html += '<li class="list-group-item student-item" style="cursor:pointer;" data-id="' + value.student_id + '" data-session="' + value.student_session_id + '" data-name="' + value.firstname + ' ' + value.lastname + ' (' + value.admission_no + ') - ' + value.class + ' (' + value.section + ')">' + value.firstname + ' ' + value.lastname + ' (' + value.admission_no + ') - ' + value.class + ' (' + value.section + ')</li>';
                        });
                        $('#student_search_results').html(html);
                    }
                });
            } else {
                $('#student_search_results').html('');
            }
        });

        $(document).on('click', '.student-item', function () {
            var selected_student_id = $(this).data('id');
            $('#student_id').val(selected_student_id);
            $('#student_session_id').val($(this).data('session'));
            $('#selected_student_name').val($(this).data('name'));
            $('#search_student_keyword').val('');
            $('#student_search_results').html('');
            
            $('#call_details_section').show();
            $('#submitBtn, #saveAndNextBtn').show();
            
            // Trigger contact person change to auto-fill phone
            $('#contact_person').val('');
            $('#phone_number').val('');

            // Fetch Recent History
            $('#recent_history_container').html('<div class="text-center" style="padding: 20px;"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>');
            $.ajax({
                url: "<?php echo site_url("admin/studentcall/get_recent_history/") ?>" + selected_student_id,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if(res.status == 'success') {
                        $('#recent_history_container').html(res.html);
                    }
                }
            });
        });

        $('#contact_person').on('change', function () {
            var person = $(this).val();
            var student_id = $('#student_id').val();
            if (person && student_id && student_phones[student_id]) {
                var phone = student_phones[student_id][person];
                if(phone) {
                    var x = phone.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                    var formatted = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
                    $('#phone_number').val(formatted);
                }
            }
        });

        // Professional Phone Number Auto-formatting
        $('#phone_number').on('input', function (e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });
        
        // Initialize Datepicker with minDate constraint
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        $('.fw_date').datepicker({
            format: date_format,
            autoclose: true,
            startDate: new Date()
        });

        $('#addCallForm').on('submit', function (e) {
            e.preventDefault();
            submitCallForm(false);
        });

        $('#saveAndNextBtn').on('click', function () {
            submitCallForm(true);
        });

        function submitCallForm(logAnother) {
            var $btn = logAnother ? $('#saveAndNextBtn') : $('#submitBtn');
            $btn.button('loading');
            $.ajax({
                url: '<?php echo site_url("admin/studentcall/add_call") ?>',
                type: "POST",
                data: new FormData($('#addCallForm')[0]),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.status == "fail") {
                        $.each(res.error, function(key, val) {
                            $('#error_' + key).html(val);
                        });
                        $btn.button('reset');
                    } else {
                        successMsg(res.message);
                        if (logAnother) {
                            $('#addCallForm')[0].reset();
                            $('#call_details_section').hide();
                            $('#submitBtn, #saveAndNextBtn').hide();
                            $('#student_search_results').empty();
                            $('#search_student_keyword').focus();
                            $btn.button('reset');
                        } else {
                            window.location.reload(true);
                        }
                    }
                },
                error: function() {
                    $btn.button('reset');
                }
            });
        }

        $('#class_id').change(function(){
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $('#section_id').empty();
                    $('#section_id').append('<option value=""><?php echo $this->lang->line('select'); ?></option>');
                    $.each(data, function (i, obj)
                    {
                        $('#section_id').append("<option value=" + obj.section_id + ">" + obj.section + "</option>");
                    });
                }
            });
        });
    });

    function follow_up(id) {
        $.ajax({
            url: '<?php echo site_url("admin/studentcall/follow_up/") ?>' + id,
            success: function (res) {
                $('#followUpModalBody').html(res);
                $('#followUpModal').modal('show');
            }
        });
    }
</script>
