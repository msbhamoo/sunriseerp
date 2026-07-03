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
                        <h3 class="box-title"><i class="fa fa-search"></i> Custom Report Results</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('studentreport/custom_report'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back to Filters</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover custom-report-table">
                                <thead>
                                    <tr>
                                        <?php
                                        // Standard mappings
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
                                            'admission_type' => 'Admission Type',
                                            'shrestha' => 'Shrestha',
                                            'is_active' => 'Is Active',
                                            'admission_date' => 'Admission Date',
                                            'hostel_name' => 'Hostel',
                                            'room_no' => 'Room No',
                                            'pickup_point_name' => 'Pick Point',
                                            'total_fee' => 'Total Fee',
                                            'total_paid' => 'Total Paid',
                                            'total_balance' => 'Total Balance',
                                        ];

                                        // Custom fields mapping
                                        $cf_mappings = [];
                                        if (!empty($custom_fields)) {
                                            foreach ($custom_fields as $cf) {
                                                $cf_mappings['cf_' . $cf['id']] = $cf['name'];
                                            }
                                        }

                                        if (!empty($selected_columns)) {
                                            foreach ($selected_columns as $col) {
                                                $col_name = isset($standard_columns[$col]) ? $standard_columns[$col] : (isset($cf_mappings[$col]) ? $cf_mappings[$col] : $col);
                                                echo "<th>" . $col_name . "</th>";
                                            }
                                        } else {
                                            echo "<th>No columns selected</th>";
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($results) && !empty($selected_columns)) {
                                        foreach ($results as $student) {
                                            echo "<tr>";
                                            foreach ($selected_columns as $col) {
                                                if (strpos($col, 'cf_') === 0) {
                                                    // It's a custom field
                                                    $cf_id = str_replace('cf_', '', $col);
                                                    $val = '';
                                                    if (!empty($student['custom_fields'])) {
                                                        foreach ($student['custom_fields'] as $scf) {
                                                            if ($scf->custom_field_id == $cf_id) {
                                                                $val = $scf->field_value;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    echo "<td>" . $val . "</td>";
                                                } else {
                                                    if ($col == 'student_name') {
                                                        $val = trim((isset($student['firstname']) ? $student['firstname'] : '') . ' ' . (isset($student['lastname']) ? $student['lastname'] : ''));
                                                    } else {
                                                        $val = isset($student[$col]) ? $student[$col] : '';
                                                    }
                                                    if ($col == 'dob' || $col == 'admission_date') {
                                                        if (!empty($val) && $val != '0000-00-00') {
                                                            $val = date($this->customlib->getSchoolDateFormat(), strtotime($val));
                                                        } else {
                                                            $val = "";
                                                        }
                                                    }
                                                    echo "<td>" . $val . "</td>";
                                                }
                                            }
                                            echo "</tr>";
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
<script type="text/javascript">
    $(document).ready(function() {
        if ($('.custom-report-table').length > 0 && $.fn.DataTable) {
            $('.custom-report-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',
                    'print'
                ]
            });
        }
    });
</script>
