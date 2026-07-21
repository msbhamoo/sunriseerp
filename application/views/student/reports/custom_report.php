<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Custom Report</h3>
                    </div>
                    <form id="form1" action="<?php echo site_url('studentreport/custom_report') ?>" method="post">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Select Class <small class="req"> *</small></label>
                                        <select id="class_id" name="class_id[]" class="form-control select2" multiple="multiple" data-placeholder="Select Classes (Leave empty for All)">
                                            <option value="all">All Classes</option>
                                            <?php foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Select Section </label>
                                        <select id="section_id" name="section_id[]" class="form-control select2" multiple="multiple" data-placeholder="Select Sections (Leave empty for All)">
                                            <option value="all">All Sections</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Select Admission Type</label>
                                        <select id="admission_type" name="admission_type[]" class="form-control select2" multiple="multiple" data-placeholder="Select Admission (Leave empty for All)">
                                            <option value="all">All Admission</option>
                                            <option value="New">New</option>
                                            <option value="Old">Old</option>
                                            <option value="Added">Added</option>
                                            <option value="Promotion">Promotion</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Select RTE</label>
                                        <select id="rte" name="rte[]" class="form-control select2" multiple="multiple" data-placeholder="Select RTE (Leave empty for All)">
                                            <option value="all">All</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Select Sort By</label>
                                        <select id="sort_by" name="sort_by" class="form-control">
                                            <option value="">Select</option>
                                            <option value="firstname">Student Name</option>
                                            <option value="admission_no">Admission No</option>
                                            <option value="roll_no">Roll No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="select_all"> <strong>Select All Columns</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                $standard_columns = [
                                    'class' => 'Class',
                                    'section' => 'Section',
                                    'admission_no' => 'Admission No',
                                    'roll_no' => 'Roll No',
                                    'student_name' => 'Student Name',
                                    'mobileno' => 'Mobile No',
                                    'email' => 'Email',
                                    'state' => 'State',
                                    'city' => 'City',
                                    'pincode' => 'Pincode',
                                    'religion' => 'Religion',
                                    'cast' => 'Cast',
                                    'dob' => 'Date of Birth',
                                    'gender' => 'Gender',
                                    'current_address' => 'Current Address',
                                    'permanent_address' => 'Permanent Address',
                                    'blood_group' => 'Blood Group',
                                    'adhar_no' => 'Aadhar No',
                                    'samagra_id' => 'Samagra ID',
                                    'bank_account_no' => 'Bank Account No',
                                    'bank_name' => 'Bank Name',
                                    'ifsc_code' => 'IFSC Code',
                                    'guardian_name' => 'Guardian Name',
                                    'father_name' => 'Father Name',
                                    'mother_name' => 'Mother Name',
                                    'guardian_relation' => 'Guardian Relation',
                                    'guardian_phone' => 'Guardian Phone',
                                    'guardian_address' => 'Guardian Address',
                                    'apaar_id' => 'APAAR ID',
                                    'pen' => 'PEN',
                                    'aadhaar_id' => 'Aadhaar ID',
                                    'rte' => 'RTE',
                                    'admission_type' => 'Admission Type',
                                    'shrestha' => 'Shrestha',
                                    'is_active' => 'Is Active',
                                    'admission_date' => 'Admission Date',
                                    'hostel_name' => 'Hostel',
                                    'room_no' => 'Room No',
                                    'pickup_point_name' => 'Pick Point',
                                ];
                                
                                foreach ($standard_columns as $col_key => $col_name) {
                                    ?>
                                    <div class="col-md-3 col-sm-4">
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="columns[]" value="<?php echo $col_key; ?>" class="column-checkbox"> <?php echo $col_name; ?></label>
                                        </div>
                                    </div>
                                    <?php
                                }

                                // Fee totals
                                if ($this->rbac->hasPrivilege('collect_fees', 'can_view')) {
                                    $fee_columns = [
                                        'total_fee' => 'Total Fee',
                                        'total_paid' => 'Total Paid',
                                        'total_balance' => 'Total Balance',
                                    ];
                                    foreach ($fee_columns as $col_key => $col_name) {
                                        ?>
                                        <div class="col-md-3 col-sm-4">
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="columns[]" value="<?php echo $col_key; ?>" class="column-checkbox"> <?php echo $col_name; ?></label>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }

                                // Custom fields
                                if (!empty($custom_fields)) {
                                    foreach ($custom_fields as $cf) {
                                        ?>
                                        <div class="col-md-3 col-sm-4">
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="columns[]" value="cf_<?php echo $cf['id']; ?>" class="column-checkbox"> <?php echo $cf['name']; ?></label>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>

                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Generate Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/select2/select2.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();

        $('#select_all').on('change', function() {
            $('.column-checkbox').prop('checked', $(this).prop('checked'));
        });

        $('#class_id').change(function () {
            var class_ids = $(this).val();
            $('#section_id').html('');
            if (class_ids != null && class_ids.length > 0) {
                $.each(class_ids, function(index, class_id) {
                    var div_data = '<option value="">Select Section</option>';
                    $.ajax({
                        type: "GET",
                        url: baseurl + "sections/getByClass",
                        data: {'class_id': class_id},
                        dataType: "json",
                        success: function (data) {
                            $.each(data, function (i, obj) {
                                $('#section_id').append("<option value=" + obj.section_id + ">" + obj.section + " (Class: " + class_id + ")</option>");
                            });
                            $('.select2').select2();
                        }
                    });
                });
            }
        });
    });
</script>
