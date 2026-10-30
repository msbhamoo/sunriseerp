
<div class="modal fade" id="assignStudentModal" role="dialog" aria-labelledby="assignStudentModal">
    <div class="modal-dialog modal-lg" role="document" style="width: 85%;">
        <div class="modal-content" style="border:none;">
            <div class="modal-header" style="background-color: #f8fafc; color: #333; border-bottom: 1px solid #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #000 !important; opacity: 0.5 !important; font-size: 24px;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-bus"></i> Assign Student to Vehicle</h4>
            </div>
            
            <div class="modal-body" style="padding-top:10px;">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                    <li class="active"><a href="#tab_single_assign" data-toggle="tab"><i class="fa fa-user"></i> Single Student Assignment</a></li>
                    <li><a href="#tab_csv_bulk_assign" data-toggle="tab"><i class="fa fa-file-excel-o"></i> Bulk CSV Import (With Preview)</a></li>
                </ul>

                <div class="tab-content">
                    <!-- Tab 1: Single Student Assignment -->
                    <div class="tab-pane active" id="tab_single_assign">
                        <form id="assign_student_form" action="<?php echo site_url('admin/vehicle/assign_student_transport') ?>" method="post">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="vehicle_id" id="assign_vehicle_id" value="">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-warning" style="padding:10px 15px; margin-bottom:15px;">
                                        <strong>Note:</strong> Changing a student's vehicle assignment will safely remove any <b>unpaid</b> old transport fees and assign the new ones. Already paid fees will remain intact.
                                    </div>
                                </div>
                            </div>
                            
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
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('pickup_point'); ?></label> <small class="req"> *</small>
                                        <select class="form-control" name="route_pickup_point_id" id="pickup_point">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer text-right" style="padding-right:0; padding-bottom:0;">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Assign Student</button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab 2: Bulk CSV Import with Pre-Commit Preview -->
                    <div class="tab-pane" id="tab_csv_bulk_assign">
                        <div class="alert alert-info" style="padding:10px 15px; margin-bottom:15px;">
                            <strong>Instructions:</strong> Upload a CSV file containing columns <code>admission_no</code>, <code>vehicle_no</code> (or <code>route_name</code>), and <code>pickup_point_name</code>. An interactive <b>Preview Table</b> will be generated for your review before any database changes are saved.
                            <a href="<?php echo site_url('admin/vehicle/export_sample_csv'); ?>" class="btn btn-default btn-xs pull-right" style="margin-top:-2px;">
                                <i class="fa fa-download"></i> Download Sample CSV
                            </a>
                        </div>

                        <form id="csv_preview_form" method="post" enctype="multipart/form-data">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Select CSV File</label> <small class="req"> *</small>
                                        <input type="file" name="csv_file" id="csv_file_input" class="form-control" accept=".csv" required style="padding: 5px 10px; height: 38px; background: #fff; border: 1px dashed #3c8dbc; cursor: pointer;">
                                    </div>
                                </div>
                                <div class="col-md-4" style="margin-top: 24px;">
                                    <button type="submit" id="btn_upload_preview" class="btn btn-info btn-block" style="font-weight:600;">
                                        <i class="fa fa-eye"></i> Upload & Preview CSV
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Pre-Commit Interactive Preview Table Container -->
                        <div id="csv_preview_container" style="display:none; margin-top:15px;">
                            <div class="row" style="margin-bottom:10px;">
                                <div class="col-md-8">
                                    <span id="csv_valid_badge" class="label label-success" style="font-size:13px; padding:6px 10px;">0 Valid Rows</span>
                                    <span id="csv_invalid_badge" class="label label-danger" style="font-size:13px; padding:6px 10px; margin-left:5px;">0 Invalid Rows</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button type="button" id="btn_confirm_bulk_import" class="btn btn-success btn-md" style="font-weight:700;">
                                        <i class="fa fa-check-circle"></i> Proceed & Confirm Import (<span id="confirm_valid_count_btn">0</span>)
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive" style="max-height:300px; overflow-y:auto; border:1px solid #ddd;">
                                <table class="table table-hover table-striped table-bordered" style="margin-bottom:0; font-size:12px;">
                                    <thead style="background:#f1f5f9;">
                                        <tr>
                                            <th style="width:5%;">Line</th>
                                            <th style="width:12%;">Adm No</th>
                                            <th style="width:23%;">Student Name</th>
                                            <th style="width:15%;">Class (Section)</th>
                                            <th style="width:20%;">Vehicle / Route</th>
                                            <th style="width:15%;">Pickup Stop</th>
                                            <th style="width:10%;">Validation</th>
                                        </tr>
                                    </thead>
                                    <tbody id="csv_preview_tbody">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var validCsvRowsData = [];

function openAssignModal(vehicle_id) {
    $('#viewstudentsmodal').modal('hide');
    $('#assign_vehicle_id').val(vehicle_id || '');
    
    // Reset CSV Preview container
    $('#csv_file_input').val('');
    $('#csv_preview_container').hide();
    $('#csv_preview_tbody').html('');
    validCsvRowsData = [];
    
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

    // CSV Upload Preview Form Handler
    $('#csv_preview_form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var $btn = $('#btn_upload_preview');
        $btn.button('loading');

        $.ajax({
            url: '<?php echo site_url("admin/vehicle/preview_bulk_csv") ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(res) {
                if (res.status == 1) {
                    validCsvRowsData = [];
                    var tbodyHtml = '';
                    
                    $.each(res.rows, function(idx, item) {
                        if (item.is_valid) {
                            validCsvRowsData.push(item);
                            tbodyHtml += '<tr class="success">';
                            tbodyHtml += '<td>' + item.line + '</td>';
                            tbodyHtml += '<td><strong>' + item.admission_no + '</strong></td>';
                            tbodyHtml += '<td>' + item.student_name + '</td>';
                            tbodyHtml += '<td>' + item.class_section + '</td>';
                            tbodyHtml += '<td>' + item.vehicle_no + '</td>';
                            tbodyHtml += '<td>' + item.pickup_point_name + '</td>';
                            tbodyHtml += '<td><span class="label label-success"><i class="fa fa-check"></i> Ready</span></td>';
                            tbodyHtml += '</tr>';
                        } else {
                            tbodyHtml += '<tr class="danger">';
                            tbodyHtml += '<td>' + item.line + '</td>';
                            tbodyHtml += '<td><strong>' + item.admission_no + '</strong></td>';
                            tbodyHtml += '<td colspan="4" class="text-danger"><em>' + item.error_msg + '</em></td>';
                            tbodyHtml += '<td><span class="label label-danger"><i class="fa fa-times"></i> Error</span></td>';
                            tbodyHtml += '</tr>';
                        }
                    });

                    $('#csv_preview_tbody').html(tbodyHtml);
                    $('#csv_valid_badge').text(res.valid_count + ' Valid Rows Ready');
                    $('#csv_invalid_badge').text(res.invalid_count + ' Invalid Rows (Skipped)');
                    $('#confirm_valid_count_btn').text(res.valid_count);

                    if(res.valid_count > 0) {
                        $('#btn_confirm_bulk_import').prop('disabled', false);
                    } else {
                        $('#btn_confirm_bulk_import').prop('disabled', true);
                    }

                    $('#csv_preview_container').slideDown();
                    successMsg('CSV dry-run validation completed. Please review preview before confirming.');
                } else {
                    errorMsg(res.message);
                }
            },
            error: function() {
                errorMsg('An error occurred while parsing CSV.');
            },
            complete: function() {
                $btn.button('reset');
            }
        });
    });

    // Confirm Bulk Import Button Handler
    $('#btn_confirm_bulk_import').on('click', function() {
        if (validCsvRowsData.length === 0) {
            errorMsg('No valid rows available to import.');
            return;
        }

        if (!confirm('Are you sure you want to assign ' + validCsvRowsData.length + ' students to transport? Unpaid transport fees will be updated, while already paid receipts remain intact.')) {
            return;
        }

        var $btn = $(this);
        $btn.button('loading');

        $.ajax({
            url: '<?php echo site_url("admin/vehicle/confirm_bulk_csv") ?>',
            type: 'POST',
            data: { valid_rows: JSON.stringify(validCsvRowsData) },
            dataType: 'json',
            success: function(res) {
                if (res.status == 1) {
                    successMsg(res.message);
                    setTimeout(function() { location.reload(); }, 1200);
                } else {
                    errorMsg(res.message);
                }
            },
            error: function() {
                errorMsg('An error occurred during bulk assignment.');
            },
            complete: function() {
                $btn.button('reset');
            }
        });
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

// Single Student Form Submission handling
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
