<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-certificate"></i> Generate Certificate - Step 2</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-4">
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
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Select Certificate Type</h3>
                    </div>
                    <form action="<?php echo base_url('admin/certificateregister/generate/' . $student_id); ?>" method="post">
                        <div class="box-body">
                            <?php if($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                            } ?>
                            <div class="form-group">
                                <label>Certificate Type <small class="req"> *</small></label>
                                <select name="certificate_type_id" id="cert_type_select" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php 
                                    $get_type = $this->input->get('type');
                                    foreach ($certificate_types as $type) { 
                                        $selected = ($get_type == $type['id']) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $type['id']; ?>" data-name="<?php echo strtolower($type['certificate_name']); ?>" <?php echo $selected; ?>><?php echo $type['certificate_name']; ?> (Next No: <?php echo $type['series_prefix'] . sprintf('%03d', max($type['start_number'], $type['current_number'] + 1)); ?>)</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Remark / Reason for Leaving</label>
                                <textarea name="remark" class="form-control" rows="2" placeholder="e.g. PROMOTED, TO STUDY ELSEWHERE"><?php echo $this->input->get('reason'); ?></textarea>
                            </div>
                            
                            <hr>
                            <div id="tc_extra_panel" style="display: none;">
                                <h4 class="text-primary">Transfer Certificate (TC) Extra Details</h4>
                                <p class="text-muted"><small>Only fill these if generating a Transfer Certificate. Leave blank to print dotted lines.</small></p>
                            
                            <?php 
                            function get_cf($cfs, $keys) {
                                foreach($keys as $k) { if(isset($cfs[$k]) && !empty($cfs[$k])) return $cfs[$k]; }
                                return '';
                            }
                            ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Apaar ID</label>
                                        <input type="text" name="custom_data[apaar_id]" class="form-control" value="<?php echo get_cf($student_custom_fields, ['apaar id', 'apaar_id', 'apaar']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>PEN No.</label>
                                        <input type="text" name="custom_data[pen_no]" class="form-control" value="<?php echo get_cf($student_custom_fields, ['pen no', 'pen_no', 'pen']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Promoted to Class</label>
                                        <input type="text" name="custom_data[promoted_to]" class="form-control" value="<?php echo get_cf($student_custom_fields, ['promoted to', 'promoted_to_class']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Whether failed. If so once/twice</label>
                                        <input type="text" name="custom_data[whether_failed]" class="form-control" value="<?php echo get_cf($student_custom_fields, ['whether failed', 'failed']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last class Pass/Fail</label>
                                        <input type="text" name="custom_data[pass_fail]" class="form-control" value="<?php echo get_cf($student_custom_fields, ['pass/fail', 'pass fail']); ?>" placeholder="Pass/Fail">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fee concession availed</label>
                                        <input type="text" name="custom_data[fee_concession]" class="form-control" value="<?php echo get_cf($student_custom_fields, ['fee concession', 'concession']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Subjects studied</label>
                                        <textarea name="custom_data[subjects_studied]" class="form-control" rows="2"><?php echo get_cf($student_custom_fields, ['subjects studied', 'subjects']); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>NCC cadet/ Boy Scout/ Girl Guide</label>
                                        <input type="text" name="custom_data[ncc_cadet]" class="form-control" value="<?php echo get_cf($student_custom_fields, ['ncc cadet', 'ncc']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Games played/ Extracurricular</label>
                                        <input type="text" name="custom_data[games_played]" class="form-control" value="<?php echo get_cf($student_custom_fields, ['games played', 'extracurricular', 'games']); ?>">
                                    </div>
                                </div>
                            </div>
                            </div> <!-- End of tc_extra_panel -->
                            
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="generate" value="generate" class="btn btn-info pull-right">Generate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
$(document).ready(function() {
    function toggle_tc_panel() {
        var selected_option = $('#cert_type_select').find('option:selected');
        var type_name = selected_option.data('name');
        if (type_name && (type_name.indexOf('transfer') !== -1 || type_name.indexOf('tc') !== -1)) {
            $('#tc_extra_panel').slideDown();
        } else {
            $('#tc_extra_panel').slideUp();
        }
    }

    $('#cert_type_select').change(function() {
        toggle_tc_panel();
    });

    // Run on initial load
    toggle_tc_panel();
});
</script>
