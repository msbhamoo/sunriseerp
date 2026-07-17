
<div class="modal fade" id="assignStudentModal" role="dialog" aria-labelledby="assignStudentModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modern-card" style="border:none;">
            <div class="modern-header" style="background-color: #f8fafc;">
                <h4 class="modern-title"><i class="fa fa-user-plus"></i> Assign Student to Vehicle</h4>
                <button type="button" class="close" data-dismiss="modal" style="opacity:0.6;">&times;</button>
            </div>
            <form id="assign_student_form" action="<?php echo site_url('admin/vehicle/assign_student_transport') ?>" method="post">
                <div class="modal-body modern-body">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <input type="hidden" name="vehicle_id" id="assign_vehicle_id" value="">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Search Student</label> <small class="req"> *</small>
                                <input type="text" id="search_student_text_vehicle" class="form-control" placeholder="Type name or admission number to search...">
                                <div id="search_results_vehicle" style="max-height: 200px; overflow-y: auto; position: absolute; z-index: 999; background: #fff; width: 95%; border: 1px solid #ccc; display: none;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id="selected_student_details" style="display:none; margin-bottom:15px;">
                        <div class="col-md-12">
                            <div class="alert alert-info" style="margin-bottom:0;">
                                <strong>Selected Student:</strong> <span id="selected_student_name"></span> (<span id="selected_student_admission_no"></span>)<br>
                                <strong>Class:</strong> <span id="selected_student_class"></span>
                                <input type="hidden" name="student_id" id="assign_student_id" value="">
                                <input type="hidden" name="student_session_id" id="assign_student_session_id" value="">
                                <input type="hidden" name="class_id" id="assign_class_id" value="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('route_list'); ?></label> <small class="req"> *</small>
                                <select class="form-control" onchange="get_pickup_point(this.value,'')" name="vehroute_id" id="vehroute_id">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    if (isset($vehroutelist)) {
                                        foreach ($vehroutelist as $vehroute) {
                                            $vehicles = $vehroute->vehicles;
                                            if (!empty($vehicles)) {
                                                foreach ($vehicles as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $value->vec_route_id ?>" data-vehicle-id="<?php echo $value->id; ?>">
                                                        <?php echo $value->vehicle_no . " (" . $vehroute->route_title . ")" ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('vehroute_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('pickup_point'); ?></label> <small class="req"> *</small>
                                <select class="form-control" name="route_pickup_point_id" id="pickup_point">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="box-footer text-right">
                    <button type="submit" class="btn btn-primary">Assign Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
function openAssignModal(vehicle_id) {
    $('#viewstudentsmodal').modal('hide');
    $('#assign_vehicle_id').val(vehicle_id || '');
    
    // Auto select the route that has this vehicle
    if (vehicle_id) {
        setTimeout(function() {
            $('#vehroute_id option').each(function() {
                if($(this).data('vehicle-id') == vehicle_id) {
                    $(this).prop('selected', true);
                    get_pickup_point($(this).val(), '');
                }
            });
        }, 500);
    } else {
        // Reset the form if opened without a specific vehicle
        $('#vehroute_id').val('');
        $('#pickup_point').html('<option value="">Select</option>');
        $('#search_student_text_vehicle').val('');
        $('#selected_student_details').hide();
        $('#assign_student_id').val('');
        $('#assign_student_session_id').val('');
        $('#assign_class_id').val('');
    }

    $('#assignStudentModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

$(document).ready(function() {

    // Search student logic
    $('#search_student_text_vehicle').on('keyup', function() {
        var search = $(this).val();
        if (search.length >= 3) {
            $.ajax({
                url: '<?php echo site_url("admin/transportattendance/search_student") ?>',
                type: 'POST',
                data: {search: search},
                dataType: 'json',
                success: function(data) {
                    var html = '<ul class="list-group" style="margin-bottom:0;">';
                    $.each(data, function(index, student) {
                        html += '<li class="list-group-item d-flex justify-content-between align-items-center" style="cursor:pointer;" onclick="selectStudentForAssign('+student.id+', '+student.student_session_id+', \''+student.firstname+' '+student.lastname+'\', \''+student.admission_no+'\', \''+student.class+' ('+student.section+')\', '+student.class_id+')">';
                        html += student.firstname + ' ' + student.lastname + ' (' + student.admission_no + ') - ' + student.class + ' (' + student.section + ')';
                        html += '</li>';
                    });
                    html += '</ul>';
                    $('#search_results_vehicle').html(html).show();
                }
            });
        } else {
            $('#search_results_vehicle').hide();
        }
    });
});

function selectStudentForAssign(student_id, student_session_id, name, admission_no, class_name, class_id) {
    $('#assign_student_id').val(student_id);
    $('#assign_student_session_id').val(student_session_id);
    $('#assign_class_id').val(class_id);
    $('#selected_student_name').text(name);
    $('#selected_student_admission_no').text(admission_no);
    $('#selected_student_class').text(class_name);
    
    $('#search_results_vehicle').hide();
    $('#search_student_text_vehicle').val('');
    $('#selected_student_details').show();
    
    var current_vehroute = $('#vehroute_id').val();
    if (current_vehroute != "") {
        get_pickup_point(current_vehroute, $('#pickup_point').val());
    }
}

function get_pickup_point(vehroute_id, pickuppoint_id) {
    if (vehroute_id != "") {
        var class_id = $('#assign_class_id').val();
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            url: '<?php echo site_url("admin/pickuppoint/get_pickupdropdownlist") ?>',
            type: "POST",
            data: {vehroute_id: vehroute_id, class_id: class_id},
            dataType: 'json',
            beforeSend: function() {
                $('#pickup_point').html('');
            },
            success: function(res) {
                $.each(res, function(i, obj) {
                    var sel = "";
                    if (pickuppoint_id == obj.route_pickup_point_id) {
                        sel = "selected";
                    }
                    var fee_text = obj.fees ? " (<?php echo $this->customlib->getSchoolCurrencyFormat(); ?>" + obj.fees + ")" : "";
                    div_data += "<option value=" + obj.route_pickup_point_id + " " + sel + ">" + obj.name + fee_text + "</option>";
                });
                $('#pickup_point').append(div_data);
            }
        });
    }
}

// Form Submission handling
$('#assign_student_form').on('submit', function(e) {
    e.preventDefault();
    if(!$('#assign_student_session_id').val()) {
        errorMsg('Please search and select a student first.');
        return false;
    }
    var $this = $(this).find("button[type=submit]:focus");
    $this.button('loading');
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(res) {
            if (res.status == 0) {
                var message = "";
                $.each(res.error, function(index, value) {
                    message += value;
                });
                errorMsg(message);
            } else {
                successMsg(res.message);
                window.location.reload(true);
            }
        },
        error: function() {
            errorMsg('An error occurred, please try again.');
        },
        complete: function() {
            $this.button('reset');
        }
    });
});
</script>
