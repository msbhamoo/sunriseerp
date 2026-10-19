<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-eye"></i> Preview Bulk Staff Updates</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/staff/bulk_update'); ?>" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Re-upload CSV
                            </a>
                        </div>
                    </div>
                    <form action="<?php echo site_url('admin/staff/process_bulk_update'); ?>" method="post">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                            <?php } ?>

                            <div class="alert alert-info">
                                <strong><i class="fa fa-info-circle"></i> Instructions:</strong>
                                <ul style="margin-bottom: 0; padding-left: 20px;">
                                    <li><strong class="text-success">Matched Rows (Checked by default):</strong> Will update existing staff details in the database.</li>
                                    <li><strong class="text-warning">Not Found Rows (Unchecked by default):</strong> Unmatched rows are skipped. <strong>Check the box</strong> for any unmatched row if you want to <strong>create a new staff record</strong>.</li>
                                </ul>
                            </div>

                            <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="40"><input type="checkbox" id="select_all_rows" checked></th>
                                            <th>Match Status</th>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Father Name</th>
                                            <th>Mother Name</th>
                                            <th>Email</th>
                                            <th>Contact No</th>
                                            <th>DOB</th>
                                            <th>Joining Date</th>
                                            <th>Qualification</th>
                                            <th>Basic Salary</th>
                                            <th>Bank Acc No</th>
                                            <th>Bank Name</th>
                                            <th>IFSC Code</th>
                                            <th>Department</th>
                                            <th>Designation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($preview_rows)) {
                                            foreach ($preview_rows as $idx => $row) {
                                                $is_matched = isset($row['is_matched']) && $row['is_matched'] == 1;
                                                $staff_db = isset($row['staff_db']) ? $row['staff_db'] : null;
                                                $emp_id = isset($row['employee_id']) ? $row['employee_id'] : '';
                                                $name = isset($row['name']) ? $row['name'] : (isset($staff_db['name']) ? $staff_db['name'] : '');
                                                $surname = isset($row['surname']) ? $row['surname'] : (isset($staff_db['surname']) ? $staff_db['surname'] : '');
                                                $father_name = isset($row['father_name']) ? $row['father_name'] : (isset($staff_db['father_name']) ? $staff_db['father_name'] : '');
                                                $mother_name = isset($row['mother_name']) ? $row['mother_name'] : (isset($staff_db['mother_name']) ? $staff_db['mother_name'] : '');
                                                $email = isset($row['email']) ? $row['email'] : '';
                                                $contact = isset($row['contact_no']) ? $row['contact_no'] : '';
                                                $dob = isset($row['dob']) && !empty($row['dob']) ? $row['dob'] : (isset($staff_db['dob']) && !empty($staff_db['dob']) && $staff_db['dob'] != '0000-00-00' ? date('d.m.Y', strtotime($staff_db['dob'])) : '');
                                                $joining = isset($row['date_of_joining']) && !empty($row['date_of_joining']) ? $row['date_of_joining'] : (isset($staff_db['date_of_joining']) && !empty($staff_db['date_of_joining']) && $staff_db['date_of_joining'] != '0000-00-00' ? date('d.m.Y', strtotime($staff_db['date_of_joining'])) : '');

                                                $qual = isset($row['qualification']) ? $row['qualification'] : '';
                                                $salary = isset($row['basic_salary']) ? $row['basic_salary'] : '';
                                                $bank_acc = isset($row['bank_account_no']) ? $row['bank_account_no'] : '';
                                                $bank_name = isset($row['bank_name']) ? $row['bank_name'] : '';
                                                $ifsc = isset($row['ifsc_code']) ? $row['ifsc_code'] : '';
                                                $dept = isset($row['department']) ? $row['department'] : '';
                                                $desig = isset($row['designation']) ? $row['designation'] : '';
                                                ?>
                                                <tr class="<?php echo $is_matched ? 'success' : 'warning'; ?>">
                                                    <td>
                                                        <input type="checkbox" name="selected_rows[]" value="<?php echo $idx; ?>" class="row_checkbox" <?php echo $is_matched ? 'checked' : ''; ?>>
                                                    </td>
                                                    <td>
                                                        <?php if ($is_matched) { ?>
                                                            <span class="label label-success"><i class="fa fa-check"></i> Matched (Will Update)</span>
                                                        <?php } else { ?>
                                                            <span class="label label-warning"><i class="fa fa-plus"></i> Not Found (Check to Create)</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td><strong><?php echo htmlspecialchars($emp_id); ?></strong></td>
                                                    <td><?php echo htmlspecialchars($name . ' ' . $surname); ?></td>
                                                    <td><?php echo htmlspecialchars($father_name); ?></td>
                                                    <td><?php echo htmlspecialchars($mother_name); ?></td>
                                                    <td><?php echo htmlspecialchars($email); ?></td>
                                                    <td><?php echo htmlspecialchars($contact); ?></td>
                                                    <td><?php echo htmlspecialchars($dob); ?></td>
                                                    <td><?php echo htmlspecialchars($joining); ?></td>
                                                    <td><?php echo htmlspecialchars($qual); ?></td>
                                                    <td><?php echo htmlspecialchars($salary); ?></td>
                                                    <td><?php echo htmlspecialchars($bank_acc); ?></td>
                                                    <td><?php echo htmlspecialchars($bank_name); ?></td>
                                                    <td><?php echo htmlspecialchars($ifsc); ?></td>
                                                    <td><?php echo htmlspecialchars($dept); ?></td>
                                                    <td><?php echo htmlspecialchars($desig); ?></td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td colspan="17" class="text-center">No rows parsed from CSV.</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success pull-right" onclick="return confirm('Are you sure you want to process these selected bulk updates/creations?');">
                                <i class="fa fa-check-circle"></i> Confirm & Process Selected Rows
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#select_all_rows').on('change', function () {
            $('.row_checkbox').prop('checked', $(this).prop('checked'));
        });
    });
</script>
