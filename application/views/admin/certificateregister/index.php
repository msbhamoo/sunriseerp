<style>
    .tc-report-table th {
        background-color: <?php echo $theme_color; ?> !important;
        color: white !important;
        text-transform: uppercase;
        font-size: 11px;
        white-space: nowrap;
    }
    .tc-report-table td {
        vertical-align: middle !important;
        font-size: 13px;
    }
    .student-img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-certificate"></i> Certificate Register</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Generated Certificates</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('certificate', 'can_add')) { ?>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createCertModal"><i class="fa fa-plus"></i> Generate Certificate</button>
                                <a href="<?php echo base_url('admin/certificateregister/settings'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-wpforms"></i> Certificate Types & Formats</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php echo $this->session->flashdata('msg'); ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example tc-report-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>PHOTO</th>
                                        <th>SR NO.</th>
                                        <th>STUDENT NAME</th>
                                        <th>FATHER NAME</th>
                                        <th>CLASS (SECTION)</th>
                                        <th>CONTACT</th>
                                        <th>LATEST CERTIFICATE DATE</th>
                                        <th>CERTIFICATES GENERATED</th>
                                        <th>CERTIFICATE</th>
                                        <th>REVERT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($grouped_certs)) {
                                        $count = 1;
                                        foreach ($grouped_certs as $cert) { 
                                            $img_url = empty($cert['image']) ? base_url('uploads/student_images/no_image.png') : base_url($cert['image']);
                                            
                                            // Get latest issue date
                                            $latest_date = '';
                                            $cert_abbrevs = [];
                                            foreach($cert['all_certs'] as $c) {
                                                if (empty($latest_date) || strtotime($c['issue_date']) > strtotime($latest_date)) {
                                                    $latest_date = $c['issue_date'];
                                                }
                                                // Create abbreviation (e.g. Transfer Certificate -> TC)
                                                $name_parts = explode(' ', $c['certificate_name']);
                                                $abbrev = '';
                                                foreach($name_parts as $p) {
                                                    if (!empty($p) && preg_match('/^[A-Za-z]/', $p)) {
                                                        $abbrev .= strtoupper(substr($p, 0, 1));
                                                    }
                                                }
                                                // If name contains (TC) etc, prefer that
                                                if (preg_match('/\((.*?)\)/', $c['certificate_name'], $matches)) {
                                                    $abbrev = strtoupper($matches[1]);
                                                }
                                                $cert_abbrevs[] = $abbrev;
                                            }
                                        ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td><img src="<?php echo $img_url; ?>" class="student-img"></td>
                                                <td><?php echo $cert['admission_no']; ?></td>
                                                <td><?php echo strtoupper(($cert['firstname'] ?? '') . ' ' . ($cert['lastname'] ?? '')); ?></td>
                                                <td><?php echo strtoupper($cert['father_name'] ?? ''); ?></td>
                                                <td><?php echo $cert['class'] . ' (' . $cert['section'] . ')'; ?></td>
                                                <td><?php echo $cert['mobileno']; ?></td>
                                                <td><?php echo date('m/d/Y', strtotime($latest_date)); ?></td>
                                                <td><?php echo implode(', ', $cert_abbrevs); ?></td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <?php foreach ($cert['all_certs'] as $c) { ?>
                                                                <li><a href="<?php echo base_url('admin/certificateregister/download/' . $c['id']); ?>" target="_blank"><i class="fa fa-certificate"></i> <?php echo $c['certificate_name']; ?></a></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($this->rbac->hasPrivilege('certificate', 'can_delete')) { ?>
                                                        <a href="<?php echo base_url('admin/certificateregister/revert/' . $cert['student_id'] . '?all=1'); ?>" onclick="return confirm('Are you sure you want to revert? All certificates for this student will be deleted and the student will be re-activated.');" class="text-danger" style="font-size: 16px;"><i class="fa fa-undo"></i></a>
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

<div class="modal fade" id="createCertModal" tabindex="-1" role="dialog" aria-labelledby="createCertModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 10px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" style="color: <?php echo $theme_color; ?>;"><i class="fa fa-plus-circle"></i> Generate Certificate</h4>      
      </div>
      <div class="modal-body">
        
        <!-- Search Area -->
        <div class="form-group" style="position: relative;">
            <label>Search Student <small class="text-danger"> *</small></label>
            <input type="text" id="search_student" class="form-control" autocomplete="off" placeholder="Search by name or admission number...">
            <div id="search_results" style="position: absolute; width: 100%; z-index: 999; background: #fff; border: 1px solid #ccc; display: none; max-height: 250px; overflow-y: auto; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
                <table class="table table-hover table-bordered mb-0">
                    <thead style="background: #f4f4f4;">
                        <tr><th>SR NO</th><th>NAME</th><th>FATHER'S NAME</th><th>MOTHER'S NAME</th><th>CONTACT</th></tr>
                    </thead>
                    <tbody id="search_results_body"></tbody>
                </table>
            </div>
        </div>

        <div id="student_details_container" style="display:none;">
            <!-- Student Details -->
            <div class="row" style="background-color: #f9f9f9; padding: 15px; margin: 0; border-radius: 5px; margin-bottom: 20px; border: 1px solid #ddd;">
                <div class="col-md-2 text-center">
                    <img id="sel_img" src="" style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid <?php echo $theme_color; ?>; object-fit:cover;">
                </div>
                <div class="col-md-10">
                    <h4 id="sel_name" style="margin-top: 5px; color: <?php echo $theme_color; ?>; font-weight: bold;"></h4>
                    <div class="row">
                        <div class="col-md-4"><strong>SR No:</strong> <span id="sel_admisno"></span></div>
                        <div class="col-md-4"><strong>Class & Sec:</strong> <span id="sel_class"></span> (<span id="sel_sec"></span>)</div>
                        <div class="col-md-4"><strong>Contact:</strong> <span id="sel_contact"></span></div>
                    </div>
                </div>
            </div>

            <!-- Fee Summary Table -->
            <h4 style="border-bottom: 2px solid <?php echo $theme_color; ?>; padding-bottom: 5px; color: <?php echo $theme_color; ?>;">Fees Summary</h4>
            <table class="table table-bordered table-striped" style="margin-bottom: 20px;">
                <thead style="background-color: <?php echo $theme_color; ?>; color: white;">
                    <tr>
                        <th>Fee Head</th>
                        <th>Total Fees</th>
                        <th>Collected</th>
                        <th>Due</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Academic Fees</strong></td>
                        <td id="fee_ac_total">0.00</td>
                        <td id="fee_ac_coll">0.00</td>
                        <td id="fee_ac_due" style="font-weight:bold;">0.00</td>
                    </tr>
                    <tr>
                        <td><strong>Transport Fees</strong></td>
                        <td id="fee_tr_total">0.00</td>
                        <td id="fee_tr_coll">0.00</td>
                        <td id="fee_tr_due" style="font-weight:bold;">0.00</td>
                    </tr>
                    <tr>
                        <td><strong>Hostel Fees</strong></td>
                        <td id="fee_ho_total">0.00</td>
                        <td id="fee_ho_coll">0.00</td>
                        <td id="fee_ho_due" style="font-weight:bold;">0.00</td>
                    </tr>
                </tbody>
            </table>

            <!-- Certificate Generation Form -->
            <form id="form_generate_cert">
                <input type="hidden" id="gen_student_id" name="student_id">
                <input type="hidden" id="gen_student_session_id" name="student_session_id">
                
                <div id="scholar_register_container" style="background-color: #f4f6f9; padding: 15px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 5px;">
                    <h4 style="margin-top: 0; border-bottom: 2px solid <?php echo $theme_color; ?>; padding-bottom: 5px; color: <?php echo $theme_color; ?>;">Current Session Scholar Register Data</h4>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Working Days</label>
                                <input type="number" id="sr_working_days" name="sr_working_days" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Present Days</label>
                                <input type="number" id="sr_present_days" name="sr_present_days" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Attnd %</label>
                                <input type="number" step="0.01" id="sr_attendance" name="sr_attendance" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Result</label>
                                <input type="text" id="sr_result" name="sr_result" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Conduct</label>
                                <input type="text" id="sr_conduct" name="sr_conduct" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Remarks</label>
                                <input type="text" id="sr_remarks" name="sr_remarks" class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Certificate Type <small class="text-danger"> *</small></label>
                            <select id="gen_cert_type" name="certificate_type_id" class="form-control" required>
                                <option value="">Select</option>
                                <?php foreach($certificate_types as $type) { ?>
                                    <option value="<?php echo $type['id']; ?>"><?php echo $type['certificate_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Certificate Number <small class="text-danger"> *</small></label>
                            <input type="text" id="gen_tc_number" name="tc_number" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Issue Date <small class="text-danger"> *</small></label>
                            <input type="text" id="gen_issue_date" name="issue_date" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Reason</label>
                            <select id="gen_reason" name="reason" class="form-control">
                                <option value="">Select Reason (Optional)</option>
                                <?php foreach($disable_reasons as $reason) { ?>
                                    <option value="<?php echo $reason['reason']; ?>"><?php echo $reason['reason']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-success" id="btn_submit_cert"><i class="fa fa-save"></i> Generate</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.date').datepicker({
        format: '<?php echo $this->customlib->getSchoolDateFormat(true, true); ?>',
        autoclose: true
    });

    $('#gen_cert_type option').each(function() {
        $(this).data('original-text', $(this).text());
    });

    var searchTimer;
    $('#search_student').on('keyup', function() {
        var term = $(this).val();
        clearTimeout(searchTimer);
        if (term.length >= 2) {
            searchTimer = setTimeout(function() {
                $.ajax({
                    url: base_url + 'admin/certificateregister/search_student_ajax',
                    type: 'POST',
                    data: {searchterm: term},
                    dataType: 'json',
                    success: function(res) {
                        if (res.length > 0) {
                            var html = '';
                            $.each(res, function(i, row) {
                                var existing = (row.existing_certs && row.existing_certs.length > 0) ? row.existing_certs.join(',') : '';
                                html += '<tr style="cursor:pointer;" class="sel-student-row" data-id="'+row.student_id+'" data-session_id="'+row.student_session_id+'" data-adm="'+row.admission_no+'" data-fname="'+row.firstname+'" data-lname="'+(row.lastname?row.lastname:'')+'" data-father="'+row.father_name+'" data-mother="'+row.mother_name+'" data-class="'+row.class_name+'" data-sec="'+row.section_name+'" data-contact="'+row.mobileno+'" data-img="'+row.image+'" data-existing="'+existing+'">';
                                html += '<td>'+row.admission_no+'</td>';
                                html += '<td>'+row.firstname+' '+(row.lastname?row.lastname:'')+'</td>';
                                html += '<td>'+row.father_name+'</td>';
                                html += '<td>'+row.mother_name+'</td>';
                                html += '<td>'+row.mobileno+'</td>';
                                html += '</tr>';
                            });
                            $('#search_results_body').html(html);
                            $('#search_results').show();
                        } else {
                            $('#search_results_body').html('<tr><td colspan="5" class="text-center text-danger">No active students found</td></tr>');
                            $('#search_results').show();
                        }
                    }
                });
            }, 300);
        } else {
            $('#search_results').hide();
        }
    });

    $(document).on('click', '.sel-student-row', function() {
        $('#search_results').hide();
        $('#search_student').val($(this).data('fname') + ' ' + $(this).data('lname'));
        
        var std_id = $(this).data('id');
        var std_session_id = $(this).data('session_id');
        var existing_str = $(this).data('existing');
        var existing_certs = [];
        if (existing_str) {
            existing_certs = existing_str.toString().split(',').map(Number);
        }
        
        // Disable existing certificates in the dropdown
        $('#gen_cert_type option').each(function() {
            if (!$(this).val()) return; // skip 'Select'
            var type_id = parseInt($(this).val());
            if (existing_certs.includes(type_id)) {
                $(this).prop('disabled', true);
                $(this).text($(this).data('original-text') + ' (Already Issued)');
            } else {
                $(this).prop('disabled', false);
                $(this).text($(this).data('original-text'));
            }
        });
        $('#gen_cert_type').val('');
        
        var img = $(this).data('img');
        if (!img) img = 'uploads/student_images/no_image.png';
        
        $('#sel_img').attr('src', base_url + img);
        $('#sel_name').text($(this).data('fname') + ' ' + $(this).data('lname'));
        $('#sel_admisno').text($(this).data('adm'));
        $('#sel_class').text($(this).data('class'));
        $('#sel_sec').text($(this).data('sec'));
        $('#sel_contact').text($(this).data('contact'));
        
        $('#gen_student_id').val(std_id);
        $('#gen_student_session_id').val(std_session_id);

        $('#student_details_container').show();
        
        // Fetch fee summary and scholar history
        $.ajax({
            url: base_url + 'admin/certificateregister/get_student_fee_summary_ajax',
            type: 'POST',
            data: {student_session_id: std_session_id, student_id: std_id},
            dataType: 'json',
            success: function(res) {
                $('#fee_ac_total').text(res.academic.total.toFixed(2));
                $('#fee_ac_coll').text(res.academic.collected.toFixed(2));
                $('#fee_ac_due').text(res.academic.due.toFixed(2));
                if(res.academic.due > 0) $('#fee_ac_due').css('color', 'red'); else $('#fee_ac_due').css('color', 'green');
                
                $('#fee_tr_total').text(res.transport.total.toFixed(2));
                $('#fee_tr_coll').text(res.transport.collected.toFixed(2));
                $('#fee_tr_due').text(res.transport.due.toFixed(2));
                if(res.transport.due > 0) $('#fee_tr_due').css('color', 'red'); else $('#fee_tr_due').css('color', 'green');
                
                $('#fee_ho_total').text(res.hostel.total.toFixed(2));
                $('#fee_ho_coll').text(res.hostel.collected.toFixed(2));
                $('#fee_ho_due').text(res.hostel.due.toFixed(2));
                if(res.hostel.due > 0) $('#fee_ho_due').css('color', 'red'); else $('#fee_ho_due').css('color', 'green');
                
                // Populate Scholar Register history
                if (res.history) {
                    $('#sr_working_days').val(res.history.working_days);
                    $('#sr_present_days').val(res.history.present_days);
                    $('#sr_attendance').val(res.history.attendance_percentage);
                    $('#sr_result').val(res.history.result);
                    $('#sr_conduct').val(res.history.conduct);
                    $('#sr_remarks').val(res.history.remarks);
                } else {
                    $('#sr_working_days, #sr_present_days, #sr_attendance, #sr_result, #sr_conduct, #sr_remarks').val('');
                }
            }
        });
    });

    $(document).click(function(e) {
        if (!$(e.target).closest('.form-group').length) {
            $('#search_results').hide();
        }
    });

    $('#gen_cert_type').change(function() {
        var type_id = $(this).val();
        if(type_id) {
            $.ajax({
                url: base_url + 'admin/certificateregister/get_cert_number_ajax',
                type: 'POST',
                data: {certificate_type_id: type_id},
                dataType: 'json',
                success: function(res) {
                    if(res.status == 'success') {
                        $('#gen_tc_number').val(res.cert_no);
                    } else {
                        $('#gen_tc_number').val('');
                    }
                }
            });
        } else {
            $('#gen_tc_number').val('');
        }
    });

    $('#form_generate_cert').submit(function(e) {
        e.preventDefault();
        $('#btn_submit_cert').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        var form_data = $(this).serialize();
        var selected_text = $('#gen_cert_type option:selected').text().toLowerCase();
        var is_tc = (selected_text.indexOf('transfer') !== -1 || selected_text.indexOf('tc') !== -1);
        
        // Step 1: Save Scholar History
        $.ajax({
            url: base_url + 'admin/certificateregister/save_scholar_history_ajax',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            success: function(res) {
                if (is_tc) {
                    var std_id = $('#gen_student_id').val();
                    var type_id = $('#gen_cert_type').val();
                    var reason = $('#gen_reason').val();
                    window.location.href = base_url + 'admin/certificateregister/generate/' + std_id + '?type=' + type_id + '&reason=' + encodeURIComponent(reason);
                    return;
                }
                
                // Step 2: Generate Certificate (Non-TC)
                $('#btn_submit_cert').html('<i class="fa fa-spinner fa-spin"></i> Generating...');
                $.ajax({
                    url: base_url + 'admin/certificateregister/generate_cert_ajax',
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function(res2) {
                        if(res2.status == 'success') {
                            alert(res2.message);
                            window.location.reload();
                        } else {
                            alert(res2.message);
                            $('#btn_submit_cert').prop('disabled', false).html('<i class="fa fa-save"></i> Generate');
                        }
                    },
                    error: function() {
                        alert("An error occurred during generation!");
                        $('#btn_submit_cert').prop('disabled', false).html('<i class="fa fa-save"></i> Generate');
                    }
                });
            },
            error: function() {
                alert("An error occurred while saving Scholar Register data.");
                $('#btn_submit_cert').prop('disabled', false).html('<i class="fa fa-save"></i> Generate');
            }
        });
    });
});
</script>
