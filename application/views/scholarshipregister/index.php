<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($selected_exam['title']) ? htmlspecialchars($selected_exam['title']) . ' - Registration' : 'Online Scholarship Registration'; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('backend/font-awesome/css/font-awesome.min.css'); ?>">
    <style>
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; padding: 40px 15px; }
        .reg-card { max-width: 760px; margin: 0 auto; background: #ffffff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 35px; border-top: 6px solid #3b82f6; }
        .reg-header { text-align: center; margin-bottom: 25px; }
        .reg-header h2 { font-weight: bold; color: #1e3a8a; margin: 0; }
        .reg-header p { color: #64748b; font-size: 14px; margin-top: 5px; }
        .exam-info-banner { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 15px 20px; margin-bottom: 25px; }
        .exam-info-title { font-size: 18px; font-weight: bold; color: #1e40af; margin-bottom: 4px; }
        .exam-info-meta { font-size: 13px; color: #3b82f6; }
    </style>
    <script>
    function toggleSchoolInput(val) {
        var box = document.getElementById('other_school_box');
        var inp = document.getElementById('other_school_name');
        if (box && inp) {
            if (val === 'external') {
                box.style.display = 'block';
                inp.required = true;
                inp.focus();
            } else {
                box.style.display = 'none';
                inp.required = false;
                inp.value = '';
            }
        }
    }
    </script>
</head>
<body>

<?php
$fields_map = array();
if (!empty($field_settings)) {
    foreach ($field_settings as $fs) {
        $fields_map[$fs['field_name']] = $fs;
    }
}
function isFieldVisible($key, $map) {
    return (isset($map[$key]) && $map[$key]['is_visible'] == 1);
}
function isFieldRequired($key, $map) {
    return (isset($map[$key]) && $map[$key]['is_required'] == 1);
}
?>

<div class="reg-card">
    <div class="reg-header">
        <h2><?php echo htmlspecialchars($sch_setting[0]['name']); ?></h2>
        <p>Online Candidate Registration Portal</p>
    </div>

    <?php if (!empty($selected_exam)) { ?>
        <div class="exam-info-banner">
            <div class="exam-info-title"><i class="fa fa-trophy"></i> <?php echo htmlspecialchars($selected_exam['title']); ?></div>
            <div class="exam-info-meta">
                Code: <strong><?php echo htmlspecialchars($selected_exam['exam_code']); ?></strong> | 
                Category: <strong><?php echo htmlspecialchars($selected_exam['exam_category']); ?></strong> | 
                Mode: <strong><?php echo strtoupper($selected_exam['exam_mode']); ?></strong> | 
                Fee: <strong><?php echo ($selected_exam['is_paid'] == 1) ? '$' . number_format($selected_exam['registration_fee'], 2) : 'FREE'; ?></strong>
            </div>
            <?php if (!empty($selected_exam['description'])) { ?>
                <div style="font-size: 12px; color: #475569; margin-top: 8px; border-top: 1px dashed #bfdbfe; padding-top: 6px;">
                    <?php echo htmlspecialchars($selected_exam['description']); ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if (isset($registration_closed) && $registration_closed) { ?>
        <div class="alert alert-danger text-center" style="border-radius: 8px; padding: 25px; margin-bottom: 25px;">
            <h3 style="margin-top: 0; font-weight: bold; color: #991b1b;"><i class="fa fa-ban"></i> Registrations Closed</h3>
            <p style="font-size: 15px; color: #7f1d1d; margin-top: 8px;">
                Online candidate registration for <strong><?php echo htmlspecialchars($selected_exam['title']); ?></strong> has been closed by the administration 
                <?php echo (!empty($selected_exam['registration_stopped_at'])) ? 'on <strong>' . date('d M Y h:i A', strtotime($selected_exam['registration_stopped_at'])) . '</strong>' : ''; ?>.
            </p>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 20px;">
            <h4 style="font-weight: bold; color: #1e40af; margin-top: 0;"><i class="fa fa-search"></i> Check Admit Card & Result Status</h4>
            <p class="text-muted" style="font-size: 13px;">If you have already registered, enter your Roll Number or Mobile Number below to check your Admit Card or Result status.</p>
            <form action="<?php echo site_url('scholarshipregister/check_status'); ?>" method="POST" class="row">
                <div class="col-sm-8">
                    <input type="text" name="search_term" class="form-control" placeholder="Enter Roll Number (e.g. SCHOLAR-1001) or Mobile" required>
                </div>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary btn-block" style="border-radius: 6px;"><i class="fa fa-search"></i> Check Status</button>
                </div>
            </form>
        </div>
    <?php } else { ?>

        <form action="<?php echo site_url('scholarshipregister/submit'); ?>" method="POST" enctype="multipart/form-data">
            <?php if (!empty($selected_exam) && isset($is_direct_link) && $is_direct_link) { ?>
                <input type="hidden" name="exam_id" value="<?php echo $selected_exam['id']; ?>">
            <?php } else { ?>
                <div class="form-group">
                    <label>Select Competition / Exam *</label>
                    <select name="exam_id" class="form-control" onchange="location.href='<?php echo site_url('scholarshipregister/apply/'); ?>' + this.value" required>
                        <?php if (!empty($exams)) {
                            foreach ($exams as $e) { ?>
                                <option value="<?php echo $e['id']; ?>" <?php echo ($selected_exam_id == $e['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($e['title']); ?> (<?php echo htmlspecialchars($e['exam_code']); ?>) - <?php echo ($e['is_paid'] == 1) ? '$' . number_format($e['registration_fee'], 2) : 'FREE'; ?>
                                </option>
                            <?php }
                        } ?>
                    </select>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Candidate Full Name *</label>
                        <input type="text" name="candidate_name" class="form-control" placeholder="Enter full name" required>
                    </div>
                </div>
                <?php if (isFieldVisible('school_name', $fields_map)) { ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Current School <?php echo isFieldRequired('school_name', $fields_map) ? '*' : ''; ?></label>
                            <div style="margin-top: 4px; margin-bottom: 6px;">
                                <label style="cursor: pointer; font-weight: bold; color: #1e3a8a; margin-right: 15px;">
                                    <input type="radio" name="school_type" value="internal" checked onchange="toggleSchoolInput('internal')" onclick="toggleSchoolInput('internal')"> <?php echo htmlspecialchars($sch_setting[0]['name']); ?>
                                </label>
                                <label style="cursor: pointer; font-weight: bold; color: #b45309;">
                                    <input type="radio" name="school_type" value="external" onchange="toggleSchoolInput('external')" onclick="toggleSchoolInput('external')"> Other School
                                </label>
                            </div>
                            <div id="other_school_box" style="display: none; margin-top: 6px;">
                                <input type="text" name="other_school_name" id="other_school_name" class="form-control" placeholder="Type your school name">
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mobile Number *</label>
                        <input type="text" name="mobile" class="form-control" placeholder="10-digit mobile number" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="candidate@example.com">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Class / Grade *</label>
                        <select name="class_id" class="form-control" required>
                            <?php foreach ($classList as $c) { ?>
                                <option value="<?php echo $c['id']; ?>">Class <?php echo htmlspecialchars($c['class']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <?php if (isFieldVisible('father_name', $fields_map)) { ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Father's Name <?php echo isFieldRequired('father_name', $fields_map) ? '*' : ''; ?></label>
                            <input type="text" name="father_name" class="form-control" placeholder="Father's full name" <?php echo isFieldRequired('father_name', $fields_map) ? 'required' : ''; ?>>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="row">
                <?php if (isFieldVisible('mother_name', $fields_map)) { ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mother's Name <?php echo isFieldRequired('mother_name', $fields_map) ? '*' : ''; ?></label>
                            <input type="text" name="mother_name" class="form-control" placeholder="Mother's full name" <?php echo isFieldRequired('mother_name', $fields_map) ? 'required' : ''; ?>>
                        </div>
                    </div>
                <?php } ?>

                <?php if (isFieldVisible('guardian_name', $fields_map)) { ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Guardian Name <?php echo isFieldRequired('guardian_name', $fields_map) ? '*' : ''; ?></label>
                            <input type="text" name="guardian_name" class="form-control" placeholder="Guardian's name" <?php echo isFieldRequired('guardian_name', $fields_map) ? 'required' : ''; ?>>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="row">
                <?php if (isFieldVisible('dob', $fields_map)) { ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date of Birth <?php echo isFieldRequired('dob', $fields_map) ? '*' : ''; ?></label>
                            <input type="date" name="dob" class="form-control" <?php echo isFieldRequired('dob', $fields_map) ? 'required' : ''; ?>>
                        </div>
                    </div>
                <?php } ?>

                <?php if (isFieldVisible('gender', $fields_map)) { ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Gender <?php echo isFieldRequired('gender', $fields_map) ? '*' : ''; ?></label>
                            <select name="gender" class="form-control" <?php echo isFieldRequired('gender', $fields_map) ? 'required' : ''; ?>>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="row">
                <?php if (isFieldVisible('category', $fields_map)) { ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Student Category <?php echo isFieldRequired('category', $fields_map) ? '*' : ''; ?></label>
                            <select name="category" class="form-control" <?php echo isFieldRequired('category', $fields_map) ? 'required' : ''; ?>>
                                <option value="General">General</option>
                                <option value="OBC">OBC</option>
                                <option value="SC">SC</option>
                                <option value="ST">ST</option>
                                <option value="SBC">SBC</option>
                            </select>
                        </div>
                    </div>
                <?php } ?>

                <?php if (isFieldVisible('religion', $fields_map)) { ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Religion <?php echo isFieldRequired('religion', $fields_map) ? '*' : ''; ?></label>
                            <input type="text" name="religion" class="form-control" placeholder="e.g. Hindu / Muslim / Christian" <?php echo isFieldRequired('religion', $fields_map) ? 'required' : ''; ?>>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="row">
                <?php if (isFieldVisible('caste', $fields_map)) { ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Caste <?php echo isFieldRequired('caste', $fields_map) ? '*' : ''; ?></label>
                            <input type="text" name="caste" class="form-control" placeholder="Enter Caste" <?php echo isFieldRequired('caste', $fields_map) ? 'required' : ''; ?>>
                        </div>
                    </div>
                <?php } ?>

                <?php if (isFieldVisible('national_id', $fields_map)) { ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Aadhaar / National ID No <?php echo isFieldRequired('national_id', $fields_map) ? '*' : ''; ?></label>
                            <input type="text" name="national_id" class="form-control" placeholder="12-digit ID Number" <?php echo isFieldRequired('national_id', $fields_map) ? 'required' : ''; ?>>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="row">
                <?php if (isFieldVisible('state', $fields_map)) { ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>State <?php echo isFieldRequired('state', $fields_map) ? '*' : ''; ?></label>
                            <input type="text" name="state" class="form-control" placeholder="Enter State" <?php echo isFieldRequired('state', $fields_map) ? 'required' : ''; ?>>
                        </div>
                    </div>
                <?php } ?>

                <?php if (isFieldVisible('city', $fields_map)) { ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City <?php echo isFieldRequired('city', $fields_map) ? '*' : ''; ?></label>
                            <input type="text" name="city" class="form-control" placeholder="Enter City" <?php echo isFieldRequired('city', $fields_map) ? 'required' : ''; ?>>
                        </div>
                    </div>
                <?php } ?>

                <?php if (isFieldVisible('village', $fields_map)) { ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Village / Town <?php echo isFieldRequired('village', $fields_map) ? '*' : ''; ?></label>
                            <input type="text" name="village" class="form-control" placeholder="Enter Village / Town" <?php echo isFieldRequired('village', $fields_map) ? 'required' : ''; ?>>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <?php if (isFieldVisible('address', $fields_map)) { ?>
                <div class="form-group">
                    <label>Address <?php echo isFieldRequired('address', $fields_map) ? '*' : ''; ?></label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Full residential address" <?php echo isFieldRequired('address', $fields_map) ? 'required' : ''; ?>></textarea>
                </div>
            <?php } ?>

            <hr style="margin: 25px 0;">

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg btn-block" style="border-radius: 8px; font-weight: bold;"><i class="fa fa-check-circle"></i> Register Online</button>
            </div>
        </form>
    <?php } ?>
</div>

</body>
</html>
