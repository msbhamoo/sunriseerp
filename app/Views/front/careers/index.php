<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Careers & Current Job Openings - <?php echo htmlspecialchars(isset($school_setting['name']) ? $school_setting['name'] : 'School LMS'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
        }
        .hero-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: #ffffff;
            padding: 60px 0 50px 0;
            text-align: center;
            position: relative;
        }
        .hero-banner h1 {
            font-weight: 800;
            font-size: 36px;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }
        .hero-banner p {
            font-size: 16px;
            color: #94a3b8;
            max-width: 600px;
            margin: 0 auto 30px auto;
        }
        .search-box-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            max-width: 800px;
            margin: 0 auto;
        }
        .job-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 24px;
            margin-bottom: 20px;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .job-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }
        .job-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 8px;
        }
        .badge-desg {
            background-color: #e0f2fe;
            color: #0369a1;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 16px;
            font-size: 12px;
            display: inline-block;
        }
        .badge-type {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 16px;
            font-size: 12px;
            display: inline-block;
        }
        .job-meta-item {
            color: #64748b;
            font-size: 13px;
            margin-right: 18px;
            display: inline-block;
        }
        .job-meta-item i {
            color: #3b82f6;
            margin-right: 4px;
        }
        .btn-apply {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 20px;
            border: none;
            transition: all 0.2s;
        }
        .btn-apply:hover {
            background-color: #1d4ed8;
            color: #ffffff;
        }
        .section-header {
            font-weight: 700;
            color: #0f172a;
            font-size: 16px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 6px;
            margin-top: 18px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <!-- HERO SECTION -->
    <div class="hero-banner">
        <div class="container">
            <h1>Join Our Passionate Educator Team</h1>
            <p>Explore current career opportunities and shape the future of learning with us.</p>

            <div class="search-box-card">
                <form method="get" action="<?php echo site_url('careers'); ?>">
                    <div class="row">
                        <div class="col-md-5 col-sm-5" style="margin-bottom:8px;">
                            <input type="text" name="search" class="form-control input-lg" placeholder="Search by title, subject, or keyword..." value="<?php echo htmlspecialchars($search ?? ''); ?>" style="border-radius:8px; font-size:14px;">
                        </div>
                        <div class="col-md-4 col-sm-4" style="margin-bottom:8px;">
                            <select name="designation_id" class="form-control input-lg" style="border-radius:8px; font-size:14px;">
                                <option value="">All Designations</option>
                                <?php if (!empty($designations)) {
                                    foreach ($designations as $desg) { ?>
                                        <option value="<?php echo $desg['id']; ?>" <?php echo ($selected_desg == $desg['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($desg['designation']); ?>
                                        </option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" style="border-radius:8px; font-weight:600; background:#2563eb;">
                                <i class="fa fa-search"></i> Search Jobs
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN JOBS LIST -->
    <div class="container" style="padding-top:40px; padding-bottom:60px;">
        <div class="row">
            <div class="col-md-12">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h3 style="font-weight:700; color:#0f172a; margin:0;">
                        Open Positions (<?php echo count($jobs); ?>)
                    </h3>
                    <a href="<?php echo site_url('admin/jobposting'); ?>" class="btn btn-default btn-sm" style="border-radius:6px; font-weight:600;">
                        <i class="fa fa-lock"></i> Admin Portal
                    </a>
                </div>

                <?php if (!empty($jobs)) {
                    foreach ($jobs as $job) {
                        $desg = !empty($job['designation_title']) ? $job['designation_title'] : 'General';
                        $last_date = !empty($job['last_date']) ? date('d M Y', strtotime($job['last_date'])) : 'Open until filled';
                        $is_closed = (isset($job['is_closed']) && $job['is_closed'] == 1);
                ?>
                        <div class="job-card">
                            <div class="row" style="display:flex; align-items:center; flex-wrap:wrap;">
                                <div class="col-md-8 col-sm-8">
                                    <div style="margin-bottom:8px;">
                                        <span class="badge-desg"><i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($desg); ?></span>
                                        <span class="badge-type"><?php echo htmlspecialchars($job['employment_type']); ?></span>
                                        <?php if (!empty($job['department'])) { ?>
                                            <span class="badge-type" style="background:#e2e8f0;"><?php echo htmlspecialchars($job['department']); ?></span>
                                        <?php } ?>
                                        <?php if ($is_closed) { ?>
                                            <span class="badge-type" style="background:#fee2e2; color:#dc2626;"><i class="fa fa-lock"></i> CLOSED</span>
                                        <?php } ?>
                                    </div>
                                    <h2 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h2>
                                    
                                    <div style="margin-top:10px;">
                                        <span class="job-meta-item"><i class="fa fa-users"></i> <?php echo intval($job['vacancies']); ?> Vacancy(s)</span>
                                        <?php if (!empty($job['location'])) { ?>
                                            <span class="job-meta-item"><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                                        <?php } ?>
                                        <?php if (!empty($job['experience_required'])) { ?>
                                            <span class="job-meta-item"><i class="fa fa-briefcase"></i> <?php echo htmlspecialchars($job['experience_required']); ?></span>
                                        <?php } ?>
                                        <span class="job-meta-item"><i class="fa fa-eye text-warning"></i> <?php echo intval($job['views_count']); ?> Views</span>
                                        <span class="job-meta-item" style="color:#dc2626;"><i class="fa fa-clock-o"></i> Apply By: <?php echo $last_date; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 text-right" style="margin-top:15px;">
                                    <button type="button" class="btn btn-apply" onclick="viewPublicJobDetails(<?php echo $job['id']; ?>)">
                                        <?php echo $is_closed ? 'View Details (Closed)' : 'View Details & Apply <i class="fa fa-arrow-right"></i>'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                <?php }
                } else { ?>
                    <div class="text-center" style="background:#fff; border-radius:12px; padding:50px 20px; border:1px solid #e2e8f0;">
                        <i class="fa fa-briefcase text-muted" style="font-size:48px; margin-bottom:15px;"></i>
                        <h4 style="font-weight:600; color:#475569;">No Job Openings Found</h4>
                        <p class="text-muted">There are currently no active job listings matching your search criteria. Check back soon!</p>
                        <a href="<?php echo site_url('careers'); ?>" class="btn btn-primary" style="border-radius:6px; margin-top:10px;">View All Openings</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- PUBLIC JOB DETAIL MODAL -->
    <div class="modal fade" id="publicJobModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius:16px; overflow:hidden; border:none; box-shadow:0 20px 40px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background:linear-gradient(135deg, #0f172a, #1e3a8a); color:#fff; padding:25px;">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff; opacity:0.9;"><span aria-hidden="true">&times;</span></button>
                    <span class="badge-desg" id="pj_desg" style="margin-bottom:8px;">Designation</span>
                    <span class="badge-type" id="pj_closed_badge" style="display:none; background:#fee2e2; color:#dc2626; margin-left:6px;"><i class="fa fa-lock"></i> CLOSED</span>
                    <h2 class="modal-title" id="pj_title" style="font-weight:800; font-size:24px; color:#fff; margin-top:5px;">Job Title</h2>
                    <div style="color:#94a3b8; font-size:14px; margin-top:4px;" id="pj_sub_meta">Full Time • Location</div>
                </div>
                <div class="modal-body" style="padding:25px; max-height:75vh; overflow-y:auto; background:#ffffff;">
                    <!-- Key Highlights -->
                    <div class="row" style="background:#f8fafc; border-radius:10px; padding:15px; margin-bottom:20px; border:1px solid #e2e8f0;">
                        <div class="col-xs-6 col-sm-3 text-center" style="border-right:1px solid #e2e8f0;">
                            <span class="text-muted" style="font-size:11px; text-transform:uppercase;">Vacancies</span>
                            <div style="font-size:16px; font-weight:700; color:#0f172a;" id="pj_vacancies">1</div>
                        </div>
                        <div class="col-xs-6 col-sm-3 text-center" style="border-right:1px solid #e2e8f0;">
                            <span class="text-muted" style="font-size:11px; text-transform:uppercase;">Experience</span>
                            <div style="font-size:14px; font-weight:600; color:#0f172a;" id="pj_experience">-</div>
                        </div>
                        <div class="col-xs-6 col-sm-3 text-center" style="border-right:1px solid #e2e8f0;">
                            <span class="text-muted" style="font-size:11px; text-transform:uppercase;">Location</span>
                            <div style="font-size:14px; font-weight:600; color:#0f172a;" id="pj_location">-</div>
                        </div>
                        <div class="col-xs-6 col-sm-3 text-center">
                            <span class="text-muted" style="font-size:11px; text-transform:uppercase;">Apply By</span>
                            <div style="font-size:14px; font-weight:700; color:#dc2626;" id="pj_last_date">-</div>
                        </div>
                    </div>

                    <div id="pj_sec_role">
                        <h4 class="section-header"><i class="fa fa-user-badge text-primary"></i> Role Overview</h4>
                        <div id="pj_role_overview" style="line-height:1.6; color:#334155;">-</div>
                    </div>

                    <div>
                        <h4 class="section-header"><i class="fa fa-file-text-o text-primary"></i> Job Description & Scope</h4>
                        <div id="pj_job_description" style="line-height:1.6; color:#334155; white-space:pre-line;">-</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="section-header"><i class="fa fa-graduation-cap text-primary"></i> Educational Qualification</h4>
                            <div id="pj_educational_level" style="line-height:1.5; color:#334155;">-</div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="section-header"><i class="fa fa-certificate text-primary"></i> Certificates & Skills Required</h4>
                            <div id="pj_certificates" style="line-height:1.5; color:#334155;">-</div>
                        </div>
                    </div>

                    <div>
                        <h4 class="section-header"><i class="fa fa-gift text-primary"></i> Compensation & Benefits</h4>
                        <div id="pj_benefits" style="line-height:1.6; color:#334155; white-space:pre-line;">-</div>
                    </div>

                    <div class="well" id="apply_box_open" style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; margin-top:25px; text-align:center;">
                        <h4 style="font-weight:700; color:#1e40af; margin-top:0;">Ready to apply for this position?</h4>
                        <p style="color:#1e3a8a; font-size:14px; margin-bottom:15px;">Submit your application and upload your CV directly below.</p>
                        <button type="button" class="btn btn-success btn-lg" onclick="openApplyFormModal()" style="border-radius:8px; font-weight:700; padding:10px 30px; background:#16a34a; box-shadow:0 4px 12px rgba(22,163,74,0.3);">
                            <i class="fa fa-paper-plane"></i> Apply Online Now
                        </button>
                    </div>

                    <div class="well" id="apply_box_closed" style="display:none; background:#fef2f2; border:1px solid #fecdd3; border-radius:10px; margin-top:25px; text-align:center;">
                        <h4 style="font-weight:700; color:#991b1b; margin-top:0;"><i class="fa fa-lock"></i> Applications Closed</h4>
                        <p style="color:#9f1239; font-size:14px; margin-bottom:0;">Applications for this position are currently closed or the position has been filled. Thank you for your interest!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ONLINE APPLICATION MODAL FORM -->
    <div class="modal fade" id="applyModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content" style="border-radius:12px; overflow:hidden;">
                <div class="modal-header" style="background:#2563eb; color:#fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="font-weight:700;"><i class="fa fa-file-text"></i> Online Job Application</h4>
                    <p style="font-size:12px; margin:0; opacity:0.9;" id="ap_job_title_sub">Applying for Position</p>
                </div>
                <form id="applyForm" method="post" enctype="multipart/form-data" action="<?php echo site_url('careers/apply'); ?>">
                    <div class="modal-body" style="padding:20px 25px;">
                        <input type="hidden" name="job_id" id="ap_job_id" value="">
                        <div id="apply_error_msg"></div>

                        <div class="form-group">
                            <label for="ap_name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="ap_name" placeholder="Enter your full name" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ap_email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" id="ap_email" placeholder="name@example.com" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ap_phone">Phone / Mobile Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="phone" id="ap_phone" placeholder="Mobile phone number" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ap_exp">Years of Experience</label>
                                    <input type="text" class="form-control" name="experience_years" id="ap_exp" placeholder="e.g. 3 Years / Fresher">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ap_qual">Highest Qualification</label>
                                    <input type="text" class="form-control" name="qualification" id="ap_qual" placeholder="e.g. M.Sc, B.Ed">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ap_cover">Cover Letter / Note to HR</label>
                            <textarea class="form-control" name="cover_letter" id="ap_cover" rows="3" placeholder="Briefly describe why you are a great fit for this position..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="ap_resume"><i class="fa fa-file-pdf-o text-danger"></i> Upload Resume / CV (PDF, DOC, DOCX) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="resume_file" id="ap_resume" accept=".pdf,.doc,.docx" required>
                            <p class="help-block" style="font-size:11px; margin-bottom:0;">Allowed formats: PDF, DOC, DOCX. Max file size: 5MB.</p>
                        </div>
                    </div>
                    <div class="modal-footer" style="background:#f8fafc;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="submitAppBtn" style="font-weight:700; padding:6px 25px; background:#16a34a;">
                            <i class="fa fa-paper-plane"></i> Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script type="text/javascript">
    var currentJobData = null;

    function viewPublicJobDetails(id) {
        $.ajax({
            url: '<?php echo site_url("careers/detail/"); ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data && data.title) {
                    currentJobData = data;
                    $('#pj_title').text(data.title || 'Job Opening');
                    $('#pj_desg').text(data.designation_title || 'Designation');
                    
                    var meta = (data.employment_type || 'Full Time');
                    if (data.department) { meta += ' • ' + data.department; }
                    if (data.location) { meta += ' • ' + data.location; }
                    $('#pj_sub_meta').text(meta);

                    $('#pj_vacancies').text(data.vacancies || '1');
                    $('#pj_experience').text(data.experience_required || '-');
                    $('#pj_location').text(data.location || '-');
                    $('#pj_last_date').text(data.formatted_last_date || data.last_date || 'Open');

                    $('#pj_role_overview').text(data.role_overview || '-');
                    $('#pj_job_description').text(data.job_description || '-');
                    $('#pj_educational_level').text(data.educational_level || '-');
                    $('#pj_certificates').text(data.certificates || '-');
                    $('#pj_benefits').text(data.benefits || '-');

                    if (data.is_closed == 1) {
                        $('#pj_closed_badge').show();
                        $('#apply_box_open').hide();
                        $('#apply_box_closed').show();
                    } else {
                        $('#pj_closed_badge').hide();
                        $('#apply_box_open').show();
                        $('#apply_box_closed').hide();
                    }

                    $('#publicJobModal').modal('show');
                }
            }
        });
    }

    function openApplyFormModal() {
        if (currentJobData && currentJobData.is_closed != 1) {
            $('#ap_job_id').val(currentJobData.id);
            $('#ap_job_title_sub').text('Applying for: ' + currentJobData.title + ' (' + (currentJobData.designation_title || 'General') + ')');
            $('#apply_error_msg').html('');
            $('#applyForm')[0].reset();
            $('#ap_job_id').val(currentJobData.id);
            $('#publicJobModal').modal('hide');
            $('#applyModal').modal('show');
        }
    }

    $(document).ready(function() {
        $('#applyForm').on('submit', function(e) {
            e.preventDefault();
            $('#submitAppBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
            $('#apply_error_msg').html('');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function(res) {
                    $('#submitAppBtn').prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Submit Application');
                    if (res.status == 'fail') {
                        var err_html = '<div class="alert alert-danger"><ul>';
                        if (res.error) {
                            $.each(res.error, function(k, v) {
                                if (v != '') err_html += '<li>' + v + '</li>';
                            });
                        } else if (res.message) {
                            err_html += '<li>' + res.message + '</li>';
                        }
                        err_html += '</ul></div>';
                        $('#apply_error_msg').html(err_html);
                    } else if (res.status == 'success') {
                        $('#applyModal').modal('hide');
                        alert(res.message);
                        location.reload();
                    }
                },
                error: function() {
                    $('#submitAppBtn').prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Submit Application');
                    alert('An error occurred submitting your application. Please try again.');
                }
            });
        });
    });
    </script>
</body>
</html>
