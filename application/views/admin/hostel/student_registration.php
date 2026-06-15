<style type="text/css">
    .hostel-ui-wrapper {
        font-family: 'Inter', sans-serif;
    }
    .room-card {
        border: 1px solid #eaeaea;
        border-radius: 8px;
        margin-bottom: 20px;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .room-header {
        background: #fdfaf6;
        padding: 15px 20px;
        border-bottom: 1px solid #f7eedf;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .room-header h4 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #d68940;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .room-header .bed-count {
        font-size: 12px;
        color: #8a8a8a;
        font-weight: 600;
    }
    .bed-grid {
        display: flex;
        flex-wrap: wrap;
        padding: 20px;
        gap: 15px;
        background-color: #fff;
    }
    .bed-item {
        flex: 1 1 calc(25% - 15px);
        min-width: 150px;
        border: 1px solid #f0f0f0;
        border-radius: 6px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    .bed-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .bed-available {
        background-color: #fffcf5;
        border-color: #fbedcf;
    }
    .bed-available:hover {
        background-color: #fdfaf6;
        border-color: #d09435;
    }
    .bed-occupied {
        background-color: #f6fffa;
        border-color: #dcf2e6;
    }
    .bed-occupied:hover {
        background-color: #eafbf2;
        border-color: #3b9b65;
    }
    .bed-icon {
        font-size: 28px;
        margin-bottom: 10px;
    }
    .bed-available .bed-icon {
        color: #d09435;
    }
    .bed-occupied .bed-icon {
        color: #3b9b65;
    }
    .bed-title {
        font-weight: 700;
        margin-bottom: 5px;
        color: #2c2c2c;
        font-size: 14px;
    }
    .bed-status {
        font-size: 12px;
        color: #8a8a8a;
    }
    .bed-occupied .bed-status {
        color: #3b9b65;
        font-weight: 600;
    }
    
    /* Search Results styling */
    #search_results {
        position: absolute;
        width: 100%;
        background: #fff;
        border: 1px solid #ccc;
        border-top: none;
        z-index: 1000;
        max-height: 250px;
        overflow-y: auto;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        display: none;
    }
    .search-item {
        padding: 10px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    .search-item:hover {
        background: #f8f9fa;
    }
    .search-item img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }
    .search-item-info {
        flex: 1;
    }
    .search-item-name {
        font-weight: bold;
        color: #333;
    }
    .search-item-desc {
        font-size: 12px;
        color: #777;
    }
    .already-assigned-badge {
        font-size: 10px;
        background: #dc3545;
        color: white;
        padding: 2px 5px;
        border-radius: 3px;
        margin-top: 3px;
        display: inline-block;
    }
    
    .student-details-card {
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        margin-top: 15px;
        display: none;
    }
    .student-details-card table {
        width: 100%;
        margin-bottom: 0;
    }
    .student-details-card th {
        width: 120px;
        color: #555;
    }
</style>

<div class="content-wrapper hostel-ui-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-building-o"></i> <?php echo $this->lang->line('hostel'); ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active"><a href="#tab_layout" data-toggle="tab">Bed Layout</a></li>
                        <li><a href="#tab_students" data-toggle="tab">Enrolled Students</a></li>
                        <li class="pull-left header"><i class="fa fa-building-o"></i> Student Registration</li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_layout">
                        <?php if (empty($hostelroomlist)) { ?>
                            <div class="alert alert-info">No hostel rooms found.</div>
                        <?php } else { 
                            $grouped_hostels = [];
                            foreach ($hostelroomlist as $room) {
                                $grouped_hostels[$room['hostel_name']][] = $room;
                            }
                            $first_tab = true;
                        ?>
                            <div class="nav-tabs-custom" style="box-shadow: none; border-radius: 0; margin-bottom: 0;">
                                <ul class="nav nav-tabs" style="border-bottom: 1px solid #eaeaea;">
                                    <?php foreach ($grouped_hostels as $hostel_name => $rooms) { ?>
                                        <li class="<?php echo $first_tab ? 'active' : ''; ?>">
                                            <a href="#hostel_<?php echo md5($hostel_name); ?>" data-toggle="tab">
                                                <i class="fa fa-building"></i> <?php echo $hostel_name; ?>
                                            </a>
                                        </li>
                                    <?php $first_tab = false; } ?>
                                </ul>
                                <div class="tab-content">
                                    <?php 
                                    $first_tab = true;
                                    foreach ($grouped_hostels as $hostel_name => $rooms) { 
                                    ?>
                                        <div class="tab-pane <?php echo $first_tab ? 'active' : ''; ?>" id="hostel_<?php echo md5($hostel_name); ?>">
                                            <?php foreach ($rooms as $room) { ?>
                                                <div class="room-card">
                                                    <div class="room-header">
                                                        <h4>Room: <?php echo $room['room_no']; ?> <small>(<?php echo $room['room_type']; ?>)</small></h4>
                                                        <span class="bed-count">BEDS: <?php echo $room['no_of_bed']; ?></span>
                                                    </div>
                                                    <div class="bed-grid">
                                                        <?php 
                                                        for ($i = 1; $i <= $room['no_of_bed']; $i++) { 
                                                            $is_occupied = isset($room['students'][$i]);
                                                            if ($is_occupied) {
                                                                $student = $room['students'][$i];
                                                            }
                                                        ?>
                                                            <div class="bed-item <?php echo $is_occupied ? 'bed-occupied' : 'bed-available'; ?>" 
                                                                 data-room-id="<?php echo $room['id']; ?>" 
                                                                 data-room-no="<?php echo $room['room_no']; ?>" 
                                                                 data-hostel-name="<?php echo $room['hostel_name']; ?>" 
                                                                 data-bed-no="<?php echo $i; ?>"
                                                                 <?php if ($is_occupied) { ?>
                                                                 data-student-id="<?php echo $student['student_id']; ?>"
                                                                 data-student-name="<?php echo $student['firstname'] . ' ' . $student['lastname']; ?>"
                                                                 data-student-class="<?php echo $student['class_name'] . ' (' . $student['section_name'] . ')'; ?>"
                                                                 data-assign-date="<?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['hostel_assign_date'])); ?>"
                                                                 <?php } ?>
                                                                 onclick="<?php echo $is_occupied ? 'openUnassignModal(this)' : 'openAssignModal(this)'; ?>">
                                                                
                                                                <div class="bed-icon"><i class="fa fa-bed"></i></div>
                                                                <div class="bed-title">Bed-<?php echo $i; ?></div>
                                                                <div class="bed-status">
                                                                    <?php if ($is_occupied) { ?>
                                                                        <?php echo $student['firstname'] . ' ' . $student['lastname']; ?>
                                                                    <?php } else { ?>
                                                                        Available
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php 
                                        $first_tab = false;
                                    } ?>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        
                        <div class="tab-pane" id="tab_students">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Father Name</th>
                                            <th>Admission No</th>
                                            <th>Class</th>
                                            <th>Hostel</th>
                                            <th>Room No</th>
                                            <th>Bed No</th>
                                            <th>Assign Date</th>
                                            <th class="text-right noExport">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($hostelroomlist)) {
                                            foreach ($hostelroomlist as $room) {
                                                if (!empty($room['students'])) {
                                                    foreach ($room['students'] as $student) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <img src="<?php echo $student['image'] ? base_url() . $student['image'] : base_url() . 'uploads/student_images/no_image.png'; ?>" class="img-circle" style="width:20px; height:20px; object-fit: cover; margin-right: 5px;" />
                                                                <a href="<?php echo base_url(); ?>student/view/<?php echo $student['student_id']; ?>">
                                                                    <?php echo $student['firstname'] . ' ' . $student['lastname']; ?>
                                                                </a>
                                                            </td>
                                                            <td><?php echo $student['father_name']; ?></td>
                                                            <td><?php echo $student['admission_no']; ?></td>
                                                            <td><?php echo $student['class_name'] . ' (' . $student['section_name'] . ')'; ?></td>
                                                            <td><?php echo $room['hostel_name']; ?></td>
                                                            <td><?php echo $room['room_no']; ?></td>
                                                            <td><?php echo $student['hostel_bed_no']; ?></td>
                                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['hostel_assign_date'])); ?></td>
                                                            <td class="text-right">
                                                                <button type="button" class="btn btn-default btn-xs" 
                                                                    data-student-id="<?php echo $student['student_id']; ?>"
                                                                    data-student-name="<?php echo $student['firstname'] . ' ' . $student['lastname']; ?>"
                                                                    data-student-class="<?php echo $student['class_name'] . ' (' . $student['section_name'] . ')'; ?>"
                                                                    data-assign-date="<?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['hostel_assign_date'])); ?>"
                                                                    onclick="openUnassignModal(this)"
                                                                    data-toggle="tooltip" title="Unassign Bed">
                                                                    <i class="fa fa-remove"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </section>
</div>

<!-- Assign Room Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="assignModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="assign_form" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="assignModalLabel">Assign Room</h4>
                </div>
                <div class="modal-body">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <p><b>Hostel:</b> <span id="modal_hostel_name"></span></p>
                        </div>
                        <div class="col-md-4">
                            <p><b>Room:</b> <span id="modal_room_no"></span></p>
                        </div>
                        <div class="col-md-4">
                            <p><b>Bed:</b> <span id="modal_bed_no"></span></p>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p><b>Room Assets:</b> <span id="modal_room_assets"></span></p>
                        </div>
                    </div>
                    <hr/>

                    <input type="hidden" name="hostel_room_id" id="assign_room_id">
                    <input type="hidden" name="hostel_bed_no" id="assign_bed_no">
                    <input type="hidden" name="student_id" id="assign_student_id">
                    <input type="hidden" name="fees_loaded" id="fees_loaded" value="0">

                    <div class="form-group" style="position: relative;">
                        <label>Search Student <small class="req"> *</small></label>
                        <div class="input-group">
                            <input type="text" id="search_student_input" class="form-control" placeholder="Search by Name, Admission No..." autocomplete="off">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        </div>
                        <div id="search_results"></div>
                    </div>

                    <div class="student-details-card" id="student_details_card">
                        <table class="table table-condensed table-striped">
                            <tr>
                                <th>Name</th>
                                <td id="dtl_name"></td>
                            </tr>
                            <tr>
                                <th>Admission No</th>
                                <td id="dtl_adm_no"></td>
                            </tr>
                            <tr>
                                <th>Class</th>
                                <td id="dtl_class"></td>
                            </tr>
                            <tr>
                                <th>Guardian</th>
                                <td id="dtl_guardian"></td>
                            </tr>
                            <tr>
                                <th>Mobile</th>
                                <td id="dtl_mobile"></td>
                            </tr>
                            <tr>
                                <th>Hostel Fees</th>
                                <td id="dtl_fees"></td>
                            </tr>
                        </table>
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label>Assign Date <small class="req"> *</small></label>
                        <input type="text" name="assign_date" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn_assign_save" disabled>Save Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Manage Assigned Bed Modal -->
<div class="modal fade" id="unassignModal" tabindex="-1" role="dialog" aria-labelledby="unassignModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="unassignModalLabel">Manage Assigned Bed</h4>
            </div>
            <div class="modal-body">
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_unassign" data-toggle="tab">Unassign</a></li>
                        <li><a href="#tab_transfer" data-toggle="tab">Transfer Room</a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- Unassign Tab -->
                        <div class="tab-pane active" id="tab_unassign">
                            <form id="unassign_form" method="post">
                                <input type="hidden" name="student_id" id="unassign_student_id">
                                <input type="hidden" name="from_room_id" id="unassign_from_room_id">
                                <input type="hidden" name="from_bed_no" id="unassign_from_bed_no">
                                
                                <div class="text-center">
                                    <i class="fa fa-user-circle fa-4x text-primary" style="margin-bottom:15px;"></i>
                                    <h4 id="unassign_student_name" style="margin-top:0;"></h4>
                                    <p class="text-muted" id="unassign_student_class"></p>
                                    <p><b>Assign Date:</b> <span id="unassign_date"></span></p>
                                </div>
                                
                                <p class="text-center text-danger" style="margin-top: 20px;">
                                    Do you want to remove this student from the bed?
                                </p>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-danger" id="btn_unassign_save">Remove Student</button>
                                </div>
                            </form>
                        </div>

                        <!-- Transfer Tab -->
                        <div class="tab-pane" id="tab_transfer">
                            <form id="transfer_form" method="post">
                                <input type="hidden" name="student_id" id="transfer_student_id">
                                <input type="hidden" name="from_room_id" id="transfer_from_room_id">
                                <input type="hidden" name="from_bed_no" id="transfer_from_bed_no">
                                
                                <div class="form-group">
                                    <label>New Hostel Room <small class="req"> *</small></label>
                                    <select class="form-control" name="to_room_id" id="transfer_to_room_id" required>
                                        <option value="">Select Room</option>
                                        <?php 
                                        if(!empty($hostelroomlist)) {
                                            foreach($hostelroomlist as $room) {
                                                echo '<option value="'.$room['id'].'" data-beds="'.$room['no_of_bed'].'">'.$room['hostel_name'].' - Room '.$room['room_no'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>New Bed No <small class="req"> *</small></label>
                                    <select class="form-control" name="to_bed_no" id="transfer_to_bed_no" required>
                                        <option value="">Select Bed</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Transfer Date <small class="req"> *</small></label>
                                    <input type="text" name="transfer_date" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Reason</label>
                                    <textarea name="reason" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary" id="btn_transfer_save">Transfer Student</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bed History Logs -->
                <div style="margin-top: 20px;">
                    <h4 class="box-title" style="border-bottom: 1px solid #eee; padding-bottom: 5px;">Bed History Logs</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="bed_history_table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- populated via ajax -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';

    function openAssignModal(elem) {
        var $el = $(elem);
        $('#modal_hostel_name').text($el.data('hostel-name'));
        $('#modal_room_no').text($el.data('room-no'));
        $('#modal_bed_no').text($el.data('bed-no'));
        
        $('#assign_room_id').val($el.data('room-id'));
        $('#assign_bed_no').val($el.data('bed-no'));
        
        // Reset form
        $('#search_student_input').val('');
        $('#assign_student_id').val('');
        $('#student_details_card').hide();
        $('#btn_assign_save').prop('disabled', true);
        
        $('#modal_room_assets').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $.ajax({
            url: base_url + 'admin/hostelroom/get_room_assets_list',
            type: 'POST',
            data: {room_id: $el.data('room-id')},
            success: function(data) {
                $('#modal_room_assets').html(data);
            }
        });

        $('#assignModal').modal('show');
    }

    function openUnassignModal(elem) {
        var $el = $(elem);
        var student_id = $el.data('student-id');
        var room_id = $el.data('room-id');
        var bed_no = $el.data('bed-no');

        // Unassign Form
        $('#unassign_student_id').val(student_id);
        $('#unassign_from_room_id').val(room_id);
        $('#unassign_from_bed_no').val(bed_no);
        
        // Transfer Form
        $('#transfer_student_id').val(student_id);
        $('#transfer_from_room_id').val(room_id);
        $('#transfer_from_bed_no').val(bed_no);
        
        $('#unassign_student_name').text($el.data('student-name'));
        $('#unassign_student_class').text($el.data('student-class'));
        $('#unassign_date').text($el.data('assign-date'));

        // Load bed history via ajax
        $('#bed_history_table tbody').html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
        $.ajax({
            url: base_url + 'admin/hostelregistration/get_bed_history',
            type: 'POST',
            data: {room_id: room_id, bed_no: bed_no},
            dataType: 'json',
            success: function(res) {
                var html = '';
                if(res.length > 0) {
                    $.each(res, function(i, log) {
                        html += '<tr>';
                        html += '<td>' + log.student_name + '</td>';
                        html += '<td>' + log.date + '</td>';
                        html += '<td>' + log.action + '</td>';
                        html += '<td>' + log.details + '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center text-muted">No history found for this bed.</td></tr>';
                }
                $('#bed_history_table tbody').html(html);
            }
        });

        $('#unassignModal').modal('show');
    }

    $(document).ready(function() {
        
        var searchTimer;
        $('#search_student_input').on('keyup', function() {
            var search_text = $(this).val();
            clearTimeout(searchTimer);
            if(search_text.length >= 2) {
                searchTimer = setTimeout(function() {
                    $.ajax({
                        url: base_url + 'admin/hostelregistration/search_student',
                        type: 'POST',
                        data: { search_text: search_text },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 0) {
                                alert("Error: " + response.msg);
                                return;
                            }
                            var html = '';
                            if(response.data.length > 0) {
                                $.each(response.data, function(index, student) {
                                    var img = student.image ? base_url + student.image : base_url + 'uploads/student_images/no_image.png';
                                    var already_assigned = student.hostel_room_id > 0 ? '<br><span class="already-assigned-badge">Already in ' + student.hostel_name + ' Room ' + student.room_no + '</span>' : '';
                                    
                                    html += '<div class="search-item" onclick=\'selectStudent(' + JSON.stringify(student) + ')\'>';
                                    html += '<img src="' + img + '" />';
                                    html += '<div class="search-item-info">';
                                    html += '<div class="search-item-name">' + student.firstname + ' ' + (student.lastname ? student.lastname : '') + ' (' + student.admission_no + ')</div>';
                                    html += '<div class="search-item-desc">Class: ' + student.class_name + ' (' + student.section_name + ')' + already_assigned + '</div>';
                                    html += '</div></div>';
                                });
                            } else {
                                html = '<div style="padding: 10px;">No active students found.</div>';
                            }
                            $('#search_results').html(html).show();
                        }
                    });
                }, 300);
            } else {
                $('#search_results').hide();
            }
        });

        // Hide search results when clicking outside
        $(document).click(function(e) {
            if(!$(e.target).closest('.form-group').length) {
                if($('#search_results').is(":visible")) {
                    $('#search_results').hide();
                }
            }
        });

        $('#assign_form').on('submit', function(e) {
            e.preventDefault();
            var btn = $('#btn_assign_save');
            btn.button('loading');
            $.ajax({
                url: base_url + 'admin/hostelregistration/assign_bed',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == 1) {
                        successMsg(response.msg);
                        setTimeout(function(){ window.location.reload(); }, 1000);
                    } else {
                        errorMsg(response.msg);
                        btn.button('reset');
                    }
                },
                error: function() {
                    errorMsg('Server error. Please try again.');
                    btn.button('reset');
                }
            });
        });

        $('#unassign_form').on('submit', function(e) {
            e.preventDefault();
            var btn = $('#btn_unassign_save');
            btn.button('loading');
            $.ajax({
                url: base_url + 'admin/hostelregistration/unassign_bed',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == 1) {
                        successMsg(response.msg);
                        setTimeout(function(){ window.location.reload(); }, 1000);
                    } else {
                        errorMsg(response.msg);
                        btn.button('reset');
                    }
                },
                error: function() {
                    errorMsg('Server error. Please try again.');
                    btn.button('reset');
                }
            });
        });

        // Dynamic Bed Dropdown for Transfer
        $('#transfer_to_room_id').on('change', function() {
            var room_id = $(this).val();
            var no_of_beds = $(this).find(':selected').data('beds');
            var $bedSelect = $('#transfer_to_bed_no');
            $bedSelect.empty().append('<option value="">Select Bed</option>');
            if(room_id && no_of_beds) {
                for(var i=1; i<=no_of_beds; i++) {
                    $bedSelect.append('<option value="'+i+'">Bed-'+i+'</option>');
                }
            }
        });

        // Transfer Form Submit
        $('#transfer_form').on('submit', function(e) {
            e.preventDefault();
            var btn = $('#btn_transfer_save');
            btn.button('loading');
            $.ajax({
                url: base_url + 'admin/hostelregistration/transfer_bed',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == 1) {
                        successMsg(response.msg);
                        setTimeout(function(){ window.location.reload(); }, 1000);
                    } else {
                        errorMsg(response.msg);
                        btn.button('reset');
                    }
                },
                error: function() {
                    errorMsg('Server error. Please try again.');
                    btn.button('reset');
                }
            });
        });
    });

    function selectStudent(student) {
        $('#search_student_input').val(student.firstname + ' ' + (student.lastname ? student.lastname : '') + ' (' + student.admission_no + ')');
        $('#assign_student_id').val(student.id);
        
        $('#dtl_name').text(student.firstname + ' ' + (student.lastname ? student.lastname : ''));
        $('#dtl_adm_no').text(student.admission_no);
        $('#dtl_class').text(student.class_name + ' (' + student.section_name + ')');
        $('#dtl_guardian').text(student.guardian_name);
        $('#dtl_mobile').text(student.mobileno);
        
        $('#dtl_fees').html('<i class="fa fa-spinner fa-spin"></i> Checking...');
        $('#fees_loaded').val('0');

        $.ajax({
            url: base_url + 'admin/hostelregistration/get_applicable_fees',
            type: 'POST',
            data: { student_id: student.id },
            dataType: 'json',
            success: function(response) {
                $('#fees_loaded').val('1');
                if (response.status == 1 && response.data.length > 0) {
                    var fees_html = '';
                    $.each(response.data, function(index, fee) {
                        fees_html += '<div class="checkbox" style="margin-top:0;">';
                        fees_html += '<label>';
                        fees_html += '<input type="checkbox" name="assign_fee_groups[]" value="' + fee.id + '" checked> ';
                        fees_html += '<span class="label label-info" style="font-size: 13px;">' + fee.name + '</span> ';
                        fees_html += '<strong>(₹' + parseFloat(fee.total_amount).toFixed(2) + ')</strong>';
                        fees_html += '</label>';
                        fees_html += '</div>';
                    });
                    $('#dtl_fees').html(fees_html);
                } else {
                    $('#dtl_fees').html('<span class="text-muted">No hostel fees applicable</span>');
                }
            }
        });

        $('#student_details_card').show();
        $('#search_results').hide();
        $('#btn_assign_save').prop('disabled', false);
    }
</script>
