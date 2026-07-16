<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> <?php echo $this->lang->line('transport'); ?>
        </h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Select Criteria</h3>
                    </div>
                    <form id="attendance_form" action="<?php echo site_url('admin/transportattendance/index'); ?>" method="post">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date"><?php echo $this->lang->line('date'); ?></label> <small class="req"> *</small>
                                        <input type="text" id="date" name="date" class="form-control date" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" autocomplete="off" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Vehicle/Bus</label> <small class="req"> *</small>
                                        <select class="form-control" name="vehicle_id">
                                            <option value="">Select</option>
                                            <?php foreach ($vehiclelist as $vehicle) { ?>
                                                <option value="<?php echo $vehicle['id'] ?>" <?php echo set_select('vehicle_id', $vehicle['id']); ?>><?php echo $vehicle['vehicle_no'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('vehicle_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Attendance Type</label> <small class="req"> *</small>
                                        <select class="form-control" name="attendance_type">
                                            <option value="">Select</option>
                                            <option value="morning" <?php echo set_select('attendance_type', 'morning'); ?>>Morning</option>
                                            <option value="evening" <?php echo set_select('attendance_type', 'evening'); ?>>Evening</option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('attendance_type'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="search" value="search" class="btn btn-primary pull-right btn-sm">Search</button>
                        </div>
                    </form>
                </div>
                
                <?php if (isset($resultlist)) { ?>
                    <div class="box box-info" id="attendance">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-users"></i> Student List</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#customRiderModal">
                                    <i class="fa fa-plus"></i> Add Custom Rider
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>
                            
                            <?php if (empty($resultlist)) { ?>
                                <div class="alert alert-info">No students found.</div>
                            <?php } else { ?>
                                <form action="<?php echo site_url('admin/transportattendance/save') ?>" method="post">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="date" value="<?php echo date('Y-m-d', strtotime($date)); ?>">
                                    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
                                    <input type="hidden" name="attendance_type" value="<?php echo $attendance_type; ?>">
                                    
                                    <div class="table-responsive p-b-20">
                                        <table class="table table-hover table-striped table-bordered example">
                                            <thead>
                                                <tr>
                                                    <th>Admission No</th>
                                                    <th>Name</th>
                                                    <th>Class (Section)</th>
                                                    <th>Status</th>
                                                    <th>Note/Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($resultlist as $student) { ?>
                                                    <tr>
                                                        <td><?php echo $student['admission_no']; ?>
                                                            <input type="hidden" name="student_session[]" value="<?php echo $student['student_session_id']; ?>">
                                                            <?php if(isset($student['status']) && $student['status'] == 'Switched Bus') { ?>
                                                                <input type="hidden" name="is_custom_rider<?php echo $student['student_session_id']; ?>" value="yes">
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $student['firstname'] . ' ' . $student['lastname']; ?>
                                                            <?php if (!empty($student['has_gatepass'])) { ?>
                                                                <span class="label label-warning pull-right" data-toggle="tooltip" title="Issued from Front Desk">Gatepass Issued</span>
                                                            <?php } ?>
                                                            <?php if (isset($student['status']) && $student['status'] == 'Switched Bus') { 
                                                                if (!empty($student['original_vehicle_no'])) {
                                                                    $rider_type = 'Custom Rider (' . $student['original_vehicle_no'] . ')';
                                                                } elseif (!empty($student['hostel_room_id']) && $student['hostel_room_id'] > 0) {
                                                                    $rider_type = 'Custom Rider (Hosteler)';
                                                                } else {
                                                                    $rider_type = 'Custom Rider (Day Scholar)';
                                                                }
                                                            ?>
                                                                <span class="label label-info pull-right"><?php echo $rider_type; ?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td><?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></td>
                                                        <td>
                                                            <?php if (isset($student['status']) && $student['status'] == 'Switched Bus') { ?>
                                                                <input type="hidden" name="attendencetype<?php echo $student['student_session_id']; ?>" value="Switched Bus">
                                                                <input type="hidden" name="is_custom_rider<?php echo $student['student_session_id']; ?>" value="yes">
                                                                <span class="label label-info">Custom Rider (Present)</span>
                                                            <?php } else { ?>
                                                                <select class="form-control" name="attendencetype<?php echo $student['student_session_id']; ?>">
                                                                    <option value="Present" <?php if($student['attendance_status'] == 'Present') echo 'selected'; ?>>Present</option>
                                                                    <option value="Absent" <?php if($student['attendance_status'] == 'Absent') echo 'selected'; ?>>Absent</option>
                                                                    <option value="Hostel" <?php if($student['attendance_status'] == 'Hostel') echo 'selected'; ?>>Hostel</option>
                                                                    <option value="Gatepass" <?php if($student['attendance_status'] == 'Gatepass') echo 'selected'; ?>>Gatepass</option>
                                                                </select>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <div style="display: flex; align-items: center; gap: 5px;">
                                                                <input type="text" name="remark<?php echo $student['student_session_id']; ?>" class="form-control" value="<?php echo $student['remark']; ?>" style="flex-grow:1;">
                                                                <?php if (isset($student['status']) && $student['status'] == 'Switched Bus') { ?>
                                                                    <button type="button" class="btn btn-danger btn-xs" title="Remove custom rider" onclick="removeCustomRider(<?php echo $student['student_session_id']; ?>, this)">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="box-footer text-right">
                                        <button type="submit" class="btn btn-primary">Save Attendance</button>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
function removeCustomRider(student_session_id, btn) {
    if (confirm('Are you sure you want to remove this custom rider?')) {
        var vehicle_id = $('select[name="vehicle_id"]').val();
        var date = $('#date').val();
        var attendance_type = $('select[name="attendance_type"]').val();
        
        $.ajax({
            url: "<?php echo site_url('admin/transportattendance/remove_custom_rider') ?>",
            type: "POST",
            data: {
                student_session_id: student_session_id,
                vehicle_id: vehicle_id,
                date: date,
                attendance_type: attendance_type
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 1) {
                    successMsg(res.msg);
                    $(btn).closest('tr').fadeOut(400, function() {
                        $(this).remove();
                    });
                } else {
                    errorMsg(res.msg);
                }
            }
        });
    }
}
</script>

<!-- Custom Rider Modal -->
<div id="customRiderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Custom Rider</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Search Student (Name or Admission No)</label>
                    <input type="text" id="search_student_text" class="form-control" placeholder="Type to search...">
                </div>
                <div id="search_results" style="max-height: 200px; overflow-y: auto;">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#search_student_text').on('keyup', function() {
            var search = $(this).val();
            if (search.length >= 3) {
                $.ajax({
                    url: '<?php echo site_url("admin/transportattendance/search_student") ?>',
                    type: 'POST',
                    data: {search: search},
                    dataType: 'json',
                    success: function(data) {
                        var html = '<ul class="list-group">';
                        $.each(data, function(index, student) {
                            html += '<li class="list-group-item d-flex justify-content-between align-items-center">';
                            html += student.firstname + ' ' + student.lastname + ' (' + student.admission_no + ') - ' + student.class + ' (' + student.section + ')';
                            html += '<button type="button" class="btn btn-xs btn-success pull-right add_custom_btn" data-studentid="'+student.id+'">Add</button>';
                            html += '</li>';
                        });
                        html += '</ul>';
                        $('#search_results').html(html);
                    }
                });
            }
        });

        $(document).on('click', '.add_custom_btn', function() {
            var student_id = $(this).data('studentid');
            var vehicle_id = $('select[name="vehicle_id"]').val();
            var date = $('#date').val();
            var attendance_type = $('select[name="attendance_type"]').val();
            
            if(!vehicle_id || !date || !attendance_type) {
                errorMsg('Please ensure you have selected a date, vehicle, and attendance type on the main page first.');
                return;
            }
            
            $.ajax({
                url: '<?php echo site_url("admin/transportattendance/add_custom_rider") ?>',
                type: 'POST',
                data: {
                    student_id: student_id,
                    vehicle_id: vehicle_id,
                    date: date,
                    attendance_type: attendance_type
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 1) {
                        successMsg(res.msg);
                        setTimeout(function(){ location.reload(); }, 1000);
                    } else {
                        errorMsg(res.msg);
                    }
                },
                error: function(xhr) {
                    errorMsg('An error occurred. Check console for details.');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
