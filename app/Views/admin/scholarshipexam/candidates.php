<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-users"></i> Candidate Registration & Roll Number Assignment</h1>
    </section>

    <section class="content">
        <?php echo $this->session->flashdata('msg'); ?>

        <div class="box box-primary" style="border-radius: 10px;">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Select Exam & Registered Roster</h3>
            </div>
            <div class="box-body">
                <form action="<?php echo site_url('admin/scholarshipexam/candidates'); ?>" method="GET" class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Select Scholarship Exam *</label>
                            <select name="exam_id" class="form-control" onchange="this.form.submit()">
                                <?php if (!empty($exams)) {
                                    foreach ($exams as $e) { ?>
                                        <option value="<?php echo $e['id']; ?>" <?php echo ($selected_exam_id == $e['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($e['title']); ?> (<?php echo htmlspecialchars($e['exam_code']); ?>)
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Candidate Type Filter</label>
                            <select name="candidate_type" class="form-control" onchange="this.form.submit()">
                                <option value="">All Candidates (Internal & External)</option>
                                <option value="internal" <?php echo (isset($selected_candidate_type) && $selected_candidate_type == 'internal') ? 'selected' : ''; ?>>Internal Students Only</option>
                                <option value="external" <?php echo (isset($selected_candidate_type) && $selected_candidate_type == 'external') ? 'selected' : ''; ?>>External Candidates Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Filter Class (Internal Enrollment)</label>
                            <select name="class_id" class="form-control" onchange="this.form.submit()">
                                <option value="">All Classes</option>
                                <?php if (!empty($classList)) {
                                    foreach ($classList as $c) { ?>
                                        <option value="<?php echo $c['id']; ?>" <?php echo ($selected_class_id == $c['id']) ? 'selected' : ''; ?>>
                                            Class <?php echo htmlspecialchars($c['class']); ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" style="margin-top: 24px;">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($selected_exam_id && $selected_class_id && !empty($eligible_students)) { ?>
            <div class="box box-warning" style="border-radius: 10px;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user-plus"></i> Eligible Students for Candidate Registration</h3>
                </div>
                <form action="<?php echo site_url('admin/scholarshipexam/register_candidates'); ?>" method="POST">
                    <input type="hidden" name="exam_id" value="<?php echo $selected_exam_id; ?>">
                    <input type="hidden" name="schedule_id" value="<?php echo isset($schedules[0]['id']) ? $schedules[0]['id'] : ''; ?>">

                    <div class="box-body table-responsive">
                        <div class="row" style="margin-bottom: 12px;">
                            <div class="col-md-4">
                                <label>Roll Number Prefix Series</label>
                                <input type="text" name="roll_prefix" class="form-control input-sm" value="OLY26-" placeholder="e.g. OLY26-">
                            </div>
                            <div class="col-md-8 text-right" style="margin-top: 22px;">
                                <button type="button" class="btn btn-xs btn-default" onclick="selectAllStudents(true)">Select All</button>
                                <button type="button" class="btn btn-xs btn-default" onclick="selectAllStudents(false)">Deselect All</button>
                            </div>
                        </div>

                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="40">Select</th>
                                    <th>Admission No</th>
                                    <th>Student Name</th>
                                    <th>Class & Section</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($eligible_students as $st) { ?>
                                    <tr>
                                        <td>
                                            <?php if ($st['is_registered'] == 0) { ?>
                                                <input type="checkbox" name="student_session_ids[]" value="<?php echo $st['student_session_id']; ?>" class="std-check">
                                            <?php } else { ?>
                                                <i class="fa fa-check-circle text-success"></i>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($st['admission_no']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($st['firstname'] . ' ' . $st['lastname']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($st['class'] . ' - ' . $st['section']); ?></td>
                                        <td>
                                            <?php if ($st['is_registered'] == 1) { ?>
                                                <span class="label label-success">Registered (Roll: <?php echo htmlspecialchars($st['candidate_details']['roll_no']); ?>)</span>
                                            <?php } else { ?>
                                                <span class="label label-default">Not Enrolled</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-success" style="border-radius: 6px;"><i class="fa fa-user-plus"></i> Register Selected Candidates & Auto-Generate Roll Series</button>
                    </div>
                </form>
            </div>
        <?php } ?>

        <div class="box box-info" style="border-radius: 10px;">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-id-card-o"></i> Registered Candidates Roster, Profile & PIN Management</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th>Roll Number</th>
                            <th>Password PIN</th>
                            <th>Student Name & Type</th>
                            <th>Class / School</th>
                            <th>Mobile / Contact</th>
                            <th>Registration Date</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($registered_candidates)) {
                            foreach ($registered_candidates as $cand) { ?>
                                <tr>
                                    <td><strong class="text-primary"><?php echo htmlspecialchars($cand['roll_no']); ?></strong></td>
                                    <td>
                                        <span class="label label-warning" style="font-size: 13px; font-weight: bold; letter-spacing: 2px;">
                                            <?php echo !empty($cand['password_pin']) ? htmlspecialchars($cand['password_pin']) : 'N/A'; ?>
                                        </span>
                                        <button type="button" class="btn btn-xs btn-default" title="Reset PIN" onclick="openResetPinModal(<?php echo $cand['id']; ?>, '<?php echo htmlspecialchars($cand['roll_no']); ?>', '<?php echo htmlspecialchars($cand['firstname']); ?>')" style="margin-left: 4px; border-radius: 3px;">
                                            <i class="fa fa-key text-warning"></i> Reset
                                        </button>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($cand['firstname'] . ' ' . $cand['lastname']); ?></strong>
                                        <?php if (isset($cand['is_external']) && $cand['is_external'] == 1) { ?>
                                            <span class="label label-warning" style="font-size: 10px; margin-left: 4px;">EXTERNAL</span>
                                        <?php } else { ?>
                                            <span class="label label-info" style="font-size: 10px; margin-left: 4px;">INTERNAL</span>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($cand['class']); ?></td>
                                    <td><small><i class="fa fa-phone"></i> <?php echo htmlspecialchars(!empty($cand['guardian_phone']) ? $cand['guardian_phone'] : (!empty($cand['external_mobile']) ? $cand['external_mobile'] : '-')); ?></small></td>
                                    <td><small><?php echo date('d M Y', strtotime($cand['registration_date'])); ?></small></td>
                                    <td>
                                        <?php echo ($cand['payment_status'] == 'paid') ? '<span class="label label-success">Paid</span>' : '<span class="label label-default">Free</span>'; ?>
                                    </td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-xs btn-primary" title="View & Edit Candidate Profile" onclick="openProfileModal(<?php echo $cand['id']; ?>)" style="border-radius: 4px;">
                                            <i class="fa fa-user"></i> Profile
                                        </button>
                                        <a href="<?php echo site_url('admin/scholarshipexam/admitcard/' . $cand['id']); ?>" target="_blank" class="btn btn-xs btn-info" style="border-radius: 4px;" title="Print Admit Card">
                                            <i class="fa fa-print"></i> Admit Card
                                        </a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">No candidate records found for selected filters.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal 1: Reset 4-Digit Password PIN -->
<div class="modal fade" id="resetPinModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: #f8fafc; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-key text-warning"></i> Reset Password PIN</h4>
            </div>
            <form action="<?php echo site_url('admin/scholarshipexam/reset_candidate_pin'); ?>" method="POST">
                <input type="hidden" name="candidate_id" id="pin_candidate_id">
                <input type="hidden" name="exam_id" value="<?php echo htmlspecialchars($selected_exam_id); ?>">
                <div class="modal-body">
                    <p style="font-size: 13px;">Candidate: <strong id="pin_candidate_name"></strong> (<span id="pin_candidate_roll"></span>)</p>
                    <div class="form-group">
                        <label>New 4-Digit PIN <span class="text-danger">*</span></label>
                        <input type="text" name="new_pin" id="new_pin_input" class="form-control text-center" maxlength="4" pattern="\d{4}" placeholder="e.g. 1234" style="font-size: 20px; font-weight: bold; letter-spacing: 6px;" required>
                        <p class="help-block"><small>Enter exactly 4 digits. Candidate will use this to sign in to their portal.</small></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning"><i class="fa fa-check"></i> Save New PIN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: View & Update Candidate Profile -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: #0f1d38; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9;">&times;</button>
                <h4 class="modal-title"><i class="fa fa-user-circle"></i> Candidate Profile Details & Update</h4>
            </div>
            <form action="<?php echo site_url('admin/scholarshipexam/update_candidate_profile'); ?>" method="POST">
                <input type="hidden" name="candidate_id" id="prof_candidate_id">
                <input type="hidden" name="exam_id" value="<?php echo htmlspecialchars($selected_exam_id); ?>">
                <div class="modal-body">
                    <!-- Top Summary Card -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 18px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                        <div>
                            <span class="text-muted" style="font-size: 12px;">Roll Number:</span>
                            <strong id="prof_roll_no" style="font-size: 16px; color: #1e40af; margin-left: 5px;"></strong>
                        </div>
                        <div>
                            <span class="text-muted" style="font-size: 12px;">Admit Card:</span>
                            <code id="prof_admit_card_no"></code>
                        </div>
                        <div>
                            <span class="text-muted" style="font-size: 12px;">Exam:</span>
                            <span id="prof_exam_title" style="font-weight: 600;"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Candidate Name <span class="text-danger">*</span></label>
                                <input type="text" name="candidate_name" id="prof_candidate_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Registered Class <span class="text-danger">*</span></label>
                                <select name="class_id" id="prof_class_id" class="form-control" required>
                                    <option value="">-- Select Class --</option>
                                    <?php foreach ($classList as $cls) { ?>
                                        <option value="<?php echo $cls['id']; ?>">Class <?php echo htmlspecialchars($cls['class']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Father's Name</label>
                                <input type="text" name="father_name" id="prof_father_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mother's Name</label>
                                <input type="text" name="mother_name" id="prof_mother_name" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" name="mobile" id="prof_mobile" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" id="prof_email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Candidate 4-Digit PIN</label>
                                <input type="text" name="password_pin" id="prof_pin" class="form-control text-center" maxlength="4" pattern="\d{4}" style="font-weight: bold; letter-spacing: 3px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>School Name</label>
                                <input type="text" name="school_name" id="prof_school_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" name="dob" id="prof_dob" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" id="prof_gender" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category" id="prof_category" class="form-control">
                                    <option value="General">General</option>
                                    <option value="OBC">OBC</option>
                                    <option value="SC">SC</option>
                                    <option value="ST">ST</option>
                                    <option value="SBC">SBC</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>City / District</label>
                                <input type="text" name="city" id="prof_city" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Village / Town</label>
                                <input type="text" name="village" id="prof_village" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Residential Address</label>
                        <textarea name="address" id="prof_address" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment Status</label>
                                <select name="payment_status" id="prof_payment_status" class="form-control">
                                    <option value="free">Free</option>
                                    <option value="paid">Paid</option>
                                    <option value="unpaid">Unpaid</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Profile Details</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function selectAllStudents(flag) {
    $('.std-check').prop('checked', flag);
}

function openResetPinModal(candidateId, rollNo, name) {
    $('#pin_candidate_id').val(candidateId);
    $('#pin_candidate_roll').text(rollNo);
    $('#pin_candidate_name').text(name);
    $('#new_pin_input').val('');
    $('#resetPinModal').modal('show');
}

function openProfileModal(candidateId) {
    $.ajax({
        url: '<?php echo site_url("admin/scholarshipexam/get_candidate_details/"); ?>' + candidateId,
        type: 'GET',
        dataType: 'json',
        success: function(c) {
            if (c) {
                $('#prof_candidate_id').val(c.id);
                $('#prof_roll_no').text(c.roll_no || '');
                $('#prof_admit_card_no').text(c.admit_card_no || '');
                $('#prof_exam_title').text(c.exam_title || '');

                $('#prof_candidate_name').val(c.external_candidate_name || c.firstname || '');
                $('#prof_father_name').val(c.external_father_name || c.father_name || '');
                $('#prof_mother_name').val(c.external_mother_name || c.mother_name || '');
                $('#prof_mobile').val(c.external_mobile || c.guardian_phone || '');
                $('#prof_email').val(c.external_email || '');
                $('#prof_pin').val(c.password_pin || '');
                $('#prof_school_name').val(c.external_school_name || '');
                $('#prof_dob').val(c.external_dob || c.dob || '');
                $('#prof_gender').val(c.external_gender || c.gender || '');
                $('#prof_category').val(c.external_category || 'General');
                $('#prof_city').val(c.external_city || '');
                $('#prof_village').val(c.external_village || '');
                $('#prof_address').val(c.external_address || '');
                $('#prof_payment_status').val(c.payment_status || 'free');

                if (c.external_class_id) {
                    $('#prof_class_id').val(c.external_class_id);
                }

                $('#profileModal').modal('show');
            }
        },
        error: function() {
            alert('Could not fetch candidate details. Please try again.');
        }
    });
}
</script>
