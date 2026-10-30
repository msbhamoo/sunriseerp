<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-user-plus"></i> Scholar Register Detail</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url() . $student['image'] ?>" alt="User profile picture">
                        <h3 class="profile-username text-center"><?php echo $student['firstname'] . " " . $student['lastname']; ?></h3>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Admission No</b> <a class="pull-right text-aqua"><?php echo $student['admission_no']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Class</b> <a class="pull-right text-aqua"><?php echo $student['class']; ?> (<?php echo $student['section']; ?>)</a>
                            </li>
                            <li class="list-group-item">
                                <b>Father Name</b> <a class="pull-right text-aqua"><?php echo $student['father_name']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Mother Name</b> <a class="pull-right text-aqua"><?php echo $student['mother_name']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b> <a class="pull-right text-aqua"><?php echo ($student['is_active'] == 'yes') ? 'Active' : 'Inactive'; ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                

            </div>
            
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#academic_register" data-toggle="tab">Academic Register</a></li>
                        <li><a href="#certificate_history" data-toggle="tab">Certificate History</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="academic_register">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Session</th>
                                            <th>Class (Section)</th>
                                            <th>Working Days</th>
                                            <th>Present Days</th>
                                            <th>Attnd %</th>
                                            <th>Result</th>
                                            <th>Conduct</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($academic_history)) { 
                                            foreach($academic_history as $hist) { 
                                                $h = $hist['history'];
                                        ?>
                                            <tr>
                                                <td><?php echo $hist['session']; ?></td>
                                                <td><?php echo $hist['class']; ?> (<?php echo $hist['section']; ?>)</td>
                                                <td><?php echo $h ? $h['working_days'] : ''; ?></td>
                                                <td><?php echo $h ? $h['present_days'] : ''; ?></td>
                                                <td><?php echo $h ? $h['attendance_percentage'] : ''; ?></td>
                                                <td><?php echo $h ? $h['result'] : ''; ?></td>
                                                <td><?php echo $h ? $h['conduct'] : ''; ?></td>
                                                <td><?php echo $h ? $h['remarks'] : ''; ?></td>
                                                <td>
                                                    <button class="btn btn-xs btn-default edit-hist" data-id="<?php echo $hist['id']; ?>" data-sess="<?php echo $hist['session_id']; ?>" data-cls="<?php echo $hist['class_id']; ?>" data-sec="<?php echo $hist['section_id']; ?>" data-wd="<?php echo $h ? $h['working_days'] : ''; ?>" data-pd="<?php echo $h ? $h['present_days'] : ''; ?>" data-ap="<?php echo $h ? $h['attendance_percentage'] : ''; ?>" data-res="<?php echo $h ? $h['result'] : ''; ?>" data-con="<?php echo $h ? $h['conduct'] : ''; ?>" data-rem="<?php echo $h ? $h['remarks'] : ''; ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="certificate_history">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Certificate Type</th>
                                        <th>Certificate No</th>
                                        <th>Issue Date</th>
                                        <th>Status</th>
                                        <th>Generated By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($certificates)) {
                                        foreach($certificates as $cert) { ?>
                                        <tr>
                                            <td><?php echo $cert['certificate_name']; ?></td>
                                            <td><?php echo $cert['certificate_number']; ?></td>
                                            <td><?php echo $cert['issue_date']; ?></td>
                                            <td><?php echo $cert['status']; ?></td>
                                            <td><?php echo $cert['generated_by_name']; ?></td>
                                            <td>
                                                <a href="<?php echo base_url('admin/certificateregister/download/'.$cert['id']); ?>" class="btn btn-xs btn-primary"><i class="fa fa-download"></i></a>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='6'>No certificates found.</td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="editHistoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="form_edit_history" action="<?php echo base_url('admin/scholarregister/update_history'); ?>" method="post">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Academic History</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="student_session_id" id="eh_student_session_id">
        <input type="hidden" name="session_id" id="eh_session_id">
        <input type="hidden" name="class_id" id="eh_class_id">
        <input type="hidden" name="section_id" id="eh_section_id">
        
        <div class="form-group">
            <label>Working Days</label>
            <input type="number" class="form-control" name="working_days" id="eh_working_days">
        </div>
        <div class="form-group">
            <label>Present Days</label>
            <input type="number" class="form-control" name="present_days" id="eh_present_days">
        </div>
        <div class="form-group">
            <label>Attendance %</label>
            <input type="number" step="0.01" class="form-control" name="attendance_percentage" id="eh_attendance_percentage">
        </div>
        <div class="form-group">
            <label>Result</label>
            <input type="text" class="form-control" name="result" id="eh_result">
        </div>
        <div class="form-group">
            <label>Conduct</label>
            <input type="text" class="form-control" name="conduct" id="eh_conduct">
        </div>
        <div class="form-group">
            <label>Remarks</label>
            <textarea class="form-control" name="remarks" id="eh_remarks"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
    $('.edit-hist').click(function(){
        $('#eh_student_session_id').val($(this).data('id'));
        $('#eh_session_id').val($(this).data('sess'));
        $('#eh_class_id').val($(this).data('cls'));
        $('#eh_section_id').val($(this).data('sec'));
        $('#eh_working_days').val($(this).data('wd'));
        $('#eh_present_days').val($(this).data('pd'));
        $('#eh_attendance_percentage').val($(this).data('ap'));
        $('#eh_result').val($(this).data('res'));
        $('#eh_conduct').val($(this).data('con'));
        $('#eh_remarks').val($(this).data('rem'));
        $('#editHistoryModal').modal('show');
    });

    $('#form_edit_history').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if(data.status == 1) {
                    location.reload();
                } else {
                    alert(data.msg);
                }
            }
        });
    });
</script>
