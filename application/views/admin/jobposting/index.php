<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-briefcase"></i> <?php echo $title; ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <?php
            $total_jobs = count($job_postings);
            $active_jobs = 0;
            $closed_jobs = 0;
            $total_vacancies = 0;
            $total_views = 0;
            $total_applications = 0;

            foreach ($job_postings as $job) {
                if ($job['is_active'] == 1) {
                    $active_jobs++;
                }
                if (isset($job['is_closed']) && $job['is_closed'] == 1) {
                    $closed_jobs++;
                }
                $total_vacancies += intval($job['vacancies']);
                $total_views += intval($job['views_count']);
                $total_applications += intval($job['applications_count']);
            }
            ?>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua" style="border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    <div class="inner">
                        <h3><?php echo $total_jobs; ?></h3>
                        <p>Total Job Postings</p>
                    </div>
                    <div class="icon"><i class="fa fa-briefcase"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green" style="border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    <div class="inner">
                        <h3><?php echo $active_jobs; ?></h3>
                        <p>Website Listed (ON)</p>
                    </div>
                    <div class="icon"><i class="fa fa-globe"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-purple" style="border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    <div class="inner">
                        <h3><?php echo $total_applications; ?></h3>
                        <p>Total Applications</p>
                    </div>
                    <div class="icon"><i class="fa fa-users"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red" style="border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    <div class="inner">
                        <h3><?php echo $closed_jobs; ?></h3>
                        <p>Closed Postings</p>
                    </div>
                    <div class="icon"><i class="fa fa-lock"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="border-radius:8px; border-top:3px solid #3c8dbc;">
                    <div class="box-header with-border" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; padding:12px 18px;">
                        <h3 class="box-title" style="font-weight:600; color:#333;"><i class="fa fa-list"></i> Job Openings Directory</h3>
                        <div class="box-tools pull-right" style="display:flex; gap:8px;">
                            <a href="<?php echo site_url('admin/jobposting/applicants'); ?>" class="btn btn-info btn-sm" style="border-radius:20px; font-weight:600; box-shadow:0 2px 6px rgba(0,192,239,0.3);">
                                <i class="fa fa-users"></i> View All Applicants (<?php echo $total_applications; ?>)
                            </a>
                            <?php if ($this->rbac->hasPrivilege('job_posting', 'can_add')) { ?>
                                <button type="button" class="btn btn-primary btn-sm" onclick="openJobModal()" style="border-radius:20px; padding:6px 16px; font-weight:600; box-shadow:0 2px 6px rgba(60,141,188,0.3);">
                                    <i class="fa fa-plus"></i> Add New Job Posting
                                </button>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="box-body table-responsive" style="padding:15px;">
                        <?php if ($this->session->flashdata('msg')) {
                            echo $this->session->flashdata('msg');
                        } ?>
                        <div id="ajax_msg_box"></div>

                        <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                            <thead>
                                <tr style="background:#f8fafc; color:#475569;">
                                    <th>#</th>
                                    <th>Job Title</th>
                                    <th>Designation (Master)</th>
                                    <th>Type & Openings</th>
                                    <th class="text-center">Views</th>
                                    <th class="text-center">Applicants</th>
                                    <th class="text-center">Open / Closed</th>
                                    <th class="text-center">Website Listing</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($job_postings)) {
                                    $count = 1;
                                    foreach ($job_postings as $job) {
                                        $designation_name = !empty($job['designation_title']) ? $job['designation_title'] : 'Unassigned';
                                        $last_date = !empty($job['last_date']) ? date($this->customlib->getSchoolDateFormat(), strtotime($job['last_date'])) : '-';
                                        $is_active = ($job['is_active'] == 1);
                                        $is_closed = (isset($job['is_closed']) && $job['is_closed'] == 1);
                                        $app_cnt = intval($job['applications_count']);
                                        $view_cnt = intval($job['views_count']);
                                ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td>
                                                <strong style="color:#1e293b; font-size:14px;"><?php echo htmlspecialchars($job['title']); ?></strong>
                                                <?php if (!empty($job['location'])) { ?>
                                                    <br><small class="text-muted"><i class="fa fa-map-marker text-danger"></i> <?php echo htmlspecialchars($job['location']); ?></small>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <span class="label" style="background-color:#e0f2fe; color:#0369a1; border:1px solid #bae6fd; border-radius:12px; padding:4px 10px; font-weight:600;">
                                                    <i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($designation_name); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-blue" style="font-weight:500;"><?php echo htmlspecialchars($job['employment_type']); ?></span>
                                                <span class="label label-warning" style="border-radius:10px; font-size:11px;"><?php echo intval($job['vacancies']); ?> Positions</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-yellow" style="font-size:12px; padding:4px 8px;"><i class="fa fa-eye"></i> <?php echo $view_cnt; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?php echo site_url('admin/jobposting/applicants/' . $job['id']); ?>" class="btn btn-default btn-xs" style="border-radius:12px; font-weight:700; color:#2563eb; padding:3px 10px; border:1px solid #bfdbfe; background:#eff6ff;">
                                                    <i class="fa fa-users"></i> <?php echo $app_cnt; ?> Applicants
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($this->rbac->hasPrivilege('job_posting', 'can_edit')) { ?>
                                                    <button type="button" class="btn btn-xs <?php echo $is_closed ? 'btn-danger' : 'btn-success'; ?>" onclick="toggleCloseStatus(<?php echo $job['id']; ?>, <?php echo $is_closed ? 0 : 1; ?>)" style="border-radius:12px; font-weight:700; padding:3px 10px;" title="Click to <?php echo $is_closed ? 'Reopen' : 'Mark as Closed'; ?>">
                                                        <i class="fa <?php echo $is_closed ? 'fa-lock' : 'fa-unlock-alt'; ?>"></i> <?php echo $is_closed ? 'CLOSED' : 'OPEN'; ?>
                                                    </button>
                                                <?php } else { ?>
                                                    <span class="label <?php echo $is_closed ? 'label-danger' : 'label-success'; ?>">
                                                        <?php echo $is_closed ? 'CLOSED' : 'OPEN'; ?>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($this->rbac->hasPrivilege('job_posting', 'can_edit')) { ?>
                                                    <label class="switch" style="position:relative; display:inline-block; width:44px; height:22px; margin:0;">
                                                        <input type="checkbox" onchange="toggleWebsiteStatus(<?php echo $job['id']; ?>, this)" <?php echo $is_active ? 'checked' : ''; ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <div id="status_label_<?php echo $job['id']; ?>" style="font-size:11px; font-weight:600; color:<?php echo $is_active ? '#15803d' : '#94a3b8'; ?>;">
                                                        <?php echo $is_active ? 'Listed (ON)' : 'Hidden (OFF)'; ?>
                                                    </div>
                                                <?php } else { ?>
                                                    <span class="label <?php echo $is_active ? 'label-success' : 'label-default'; ?>">
                                                        <?php echo $is_active ? 'Listed (ON)' : 'Hidden (OFF)'; ?>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-right" style="white-space:nowrap;">
                                                <button type="button" class="btn btn-default btn-xs" onclick="previewJob(<?php echo $job['id']; ?>)" title="Preview Job Details" style="border-radius:4px; margin-right:2px;">
                                                    <i class="fa fa-eye text-info"></i> Preview
                                                </button>
                                                <?php if ($this->rbac->hasPrivilege('job_posting', 'can_edit')) { ?>
                                                    <button type="button" class="btn btn-default btn-xs" onclick="editJob(<?php echo $job['id']; ?>)" title="Edit Posting" style="border-radius:4px; margin-right:2px;">
                                                        <i class="fa fa-pencil text-warning"></i>
                                                    </button>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('job_posting', 'can_delete')) { ?>
                                                    <a href="<?php echo site_url('admin/jobposting/delete/' . $job['id']); ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure you want to delete this job posting?');" title="Delete Posting" style="border-radius:4px;">
                                                        <i class="fa fa-remove text-danger"></i>
                                                    </a>
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
    </section>
</div>

<!-- ================= CREATE / EDIT JOB MODAL ================= -->
<div class="modal fade" id="jobPostingModal" tabindex="-1" role="dialog" aria-labelledby="jobPostingModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius:10px; overflow:hidden;">
            <div class="modal-header" style="background:#3c8dbc; color:#fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:0.9;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="jobPostingModalLabel" style="font-weight:600;"><i class="fa fa-briefcase"></i> <span id="modal_action_title">Add Job Posting</span></h4>
            </div>
            <form id="jobPostingForm" method="post" action="<?php echo site_url('admin/jobposting/save'); ?>">
                <div class="modal-body" style="padding:20px 25px; max-height:75vh; overflow-y:auto;">
                    <input type="hidden" name="job_id" id="job_id" value="">
                    
                    <div id="form_error_message"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Job Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="e.g. Senior Mathematics Teacher" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="designation_id">Designation (From Master) <span class="text-danger">*</span></label>
                                <select class="form-control" name="designation_id" id="designation_id" required>
                                    <option value="">-- Select Designation --</option>
                                    <?php if (!empty($designations)) {
                                        foreach ($designations as $desg) { ?>
                                            <option value="<?php echo $desg['id']; ?>"><?php echo htmlspecialchars($desg['designation']); ?></option>
                                    <?php }
                                    } ?>
                                </select>
                                <span class="help-block" style="font-size:11px; margin-bottom:0;">Data sourced from Designation master (<a href="<?php echo site_url('admin/designation/designation'); ?>" target="_blank">Manage Designations</a>)</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="department">Department</label>
                                <select class="form-control" name="department" id="department">
                                    <option value="">-- Select Department --</option>
                                    <?php if (!empty($departments)) {
                                        foreach ($departments as $dept) { ?>
                                            <option value="<?php echo htmlspecialchars($dept['department_name']); ?>"><?php echo htmlspecialchars($dept['department_name']); ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="employment_type">Employment Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="employment_type" id="employment_type" required>
                                    <option value="Full Time">Full Time</option>
                                    <option value="Part Time">Part Time</option>
                                    <option value="Contract">Contractual</option>
                                    <option value="Temporary">Temporary</option>
                                    <option value="Internship">Internship</option>
                                    <option value="Guest Faculty">Guest Faculty</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vacancies">Open Vacancies <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="vacancies" id="vacancies" min="1" value="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="experience_required">Experience Required</label>
                                <input type="text" class="form-control" name="experience_required" id="experience_required" placeholder="e.g. 2 - 5 Years / Fresher">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="location">Job Location / Campus</label>
                                <input type="text" class="form-control" name="location" id="location" placeholder="e.g. Main Campus / Senior Wing">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="last_date">Last Date to Apply <span class="text-danger">*</span></label>
                                <input type="text" class="form-control date" name="last_date" id="last_date" placeholder="DD-MM-YYYY" required readonly style="background:#fff;">
                            </div>
                        </div>
                    </div>

                    <hr style="margin:15px 0;">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="educational_level"><i class="fa fa-graduation-cap"></i> Educational Qualification Level</label>
                                <textarea class="form-control" name="educational_level" id="educational_level" rows="3" placeholder="e.g. M.Sc / M.A in subject with B.Ed from a recognized university"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="certificates"><i class="fa fa-certificate"></i> Required Certificates & Skills</label>
                                <textarea class="form-control" name="certificates" id="certificates" rows="3" placeholder="e.g. CTET / STET Qualified, Excellent English Communication, Computer Proficiency"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role_overview"><i class="fa fa-id-badge"></i> Role Overview & Responsibilities</label>
                        <textarea class="form-control" name="role_overview" id="role_overview" rows="3" placeholder="Summary of the role duties and candidate expectations"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="job_description"><i class="fa fa-file-text-o"></i> Detailed Job Description (JD) <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="job_description" id="job_description" rows="5" placeholder="Full job description, specific duties, subject syllabus scope, class levels to teach, etc." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="benefits"><i class="fa fa-gift"></i> Compensation, Perks & Benefits</label>
                        <textarea class="form-control" name="benefits" id="benefits" rows="3" placeholder="e.g. Competitive pay scale as per 7th CPC, Provident Fund, Medical Allowance, Staff Quarters, Free Transport"></textarea>
                    </div>

                    <div class="well well-sm" style="background:#f8fafc; border-radius:6px; margin-bottom:0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkbox" style="margin:5px 0;">
                                    <label style="font-weight:600; color:#0f172a;">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" checked> 
                                        <i class="fa fa-globe text-primary"></i> Turn ON Website Listing
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox" style="margin:5px 0;">
                                    <label style="font-weight:600; color:#dc2626;">
                                        <input type="checkbox" name="is_closed" id="is_closed" value="1"> 
                                        <i class="fa fa-lock text-danger"></i> Mark Job Posting as CLOSED
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="background:#f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" class="btn btn-default" onclick="previewFormJob()" style="border-radius:6px;">
                        <i class="fa fa-eye text-info"></i> Preview Form Inputs
                    </button>
                    <div>
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px;">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveJobBtn" style="border-radius:6px; font-weight:600; padding:6px 20px;">
                            <i class="fa fa-save"></i> Save Job Posting
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= LIVE PREVIEW MODAL ================= -->
<div class="modal fade" id="previewJobModal" tabindex="-1" role="dialog" aria-labelledby="previewJobModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden; border:none; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background:linear-gradient(135deg, #1e3a8a, #3b82f6); color:#fff; padding:20px 25px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:0.9;"><span aria-hidden="true">&times;</span></button>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="label" style="background:rgba(255,255,255,0.2); font-size:12px; padding:4px 10px; border-radius:12px;" id="pv_emp_type">Full Time</span>
                    <span class="label" style="background:#e0f2fe; color:#0369a1; font-size:12px; padding:4px 10px; border-radius:12px; font-weight:600;" id="pv_designation">Designation</span>
                    <span class="label" style="background:#dcfce7; color:#15803d; font-size:11px; padding:4px 10px; border-radius:12px;" id="pv_status_badge">Website Listed</span>
                    <span class="label" style="background:#fee2e2; color:#dc2626; font-size:11px; padding:4px 10px; border-radius:12px;" id="pv_closed_badge">OPEN</span>
                </div>
                <h2 class="modal-title" id="pv_title" style="font-weight:700; margin-top:10px; color:#fff; font-size:22px;">Job Title</h2>
                <div style="font-size:13px; opacity:0.9; margin-top:4px;" id="pv_dept_loc">Department • Location</div>
            </div>

            <div class="modal-body" style="padding:25px; max-height:75vh; overflow-y:auto; background:#fafafa;">
                <!-- Key Details Bar -->
                <div class="row" style="background:#fff; border-radius:8px; padding:15px; margin-bottom:20px; border:1px solid #e2e8f0; box-shadow:0 2px 4px rgba(0,0,0,0.02);">
                    <div class="col-xs-6 col-sm-3 text-center" style="border-right:1px solid #f1f5f9;">
                        <span class="text-muted" style="font-size:11px; text-transform:uppercase;">Vacancies</span>
                        <div style="font-size:16px; font-weight:700; color:#1e293b;" id="pv_vacancies">1</div>
                    </div>
                    <div class="col-xs-6 col-sm-3 text-center" style="border-right:1px solid #f1f5f9;">
                        <span class="text-muted" style="font-size:11px; text-transform:uppercase;">Experience</span>
                        <div style="font-size:14px; font-weight:600; color:#1e293b;" id="pv_experience">-</div>
                    </div>
                    <div class="col-xs-6 col-sm-3 text-center" style="border-right:1px solid #f1f5f9;">
                        <span class="text-muted" style="font-size:11px; text-transform:uppercase;">Location</span>
                        <div style="font-size:14px; font-weight:600; color:#1e293b;" id="pv_location">-</div>
                    </div>
                    <div class="col-xs-6 col-sm-3 text-center">
                        <span class="text-muted" style="font-size:11px; text-transform:uppercase;">Last Date to Apply</span>
                        <div style="font-size:14px; font-weight:700; color:#dc2626;" id="pv_last_date">-</div>
                    </div>
                </div>

                <!-- Sections -->
                <div id="pv_role_sec" class="pv-section" style="margin-bottom:20px; background:#fff; padding:18px; border-radius:8px; border:1px solid #e2e8f0;">
                    <h4 style="font-weight:600; color:#1e3a8a; border-bottom:2px solid #e0f2fe; padding-bottom:6px; margin-top:0;">
                        <i class="fa fa-user-badge"></i> Role Overview & Responsibilities
                    </h4>
                    <div id="pv_role_overview" style="color:#334155; line-height:1.6; whitespace:pre-line;">-</div>
                </div>

                <div class="pv-section" style="margin-bottom:20px; background:#fff; padding:18px; border-radius:8px; border:1px solid #e2e8f0;">
                    <h4 style="font-weight:600; color:#1e3a8a; border-bottom:2px solid #e0f2fe; padding-bottom:6px; margin-top:0;">
                        <i class="fa fa-file-text-o"></i> Job Description (JD)
                    </h4>
                    <div id="pv_job_description" style="color:#334155; line-height:1.6; whitespace:pre-line;">-</div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="pv-section" style="margin-bottom:20px; background:#fff; padding:18px; border-radius:8px; border:1px solid #e2e8f0; height:100%;">
                            <h4 style="font-weight:600; color:#1e3a8a; border-bottom:2px solid #e0f2fe; padding-bottom:6px; margin-top:0;">
                                <i class="fa fa-graduation-cap"></i> Educational Qualification
                            </h4>
                            <div id="pv_educational_level" style="color:#334155; line-height:1.5;">-</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pv-section" style="margin-bottom:20px; background:#fff; padding:18px; border-radius:8px; border:1px solid #e2e8f0; height:100%;">
                            <h4 style="font-weight:600; color:#1e3a8a; border-bottom:2px solid #e0f2fe; padding-bottom:6px; margin-top:0;">
                                <i class="fa fa-certificate"></i> Certificates & Required Skills
                            </h4>
                            <div id="pv_certificates" style="color:#334155; line-height:1.5;">-</div>
                        </div>
                    </div>
                </div>

                <div class="pv-section" style="margin-top:10px; background:#fff; padding:18px; border-radius:8px; border:1px solid #e2e8f0;">
                    <h4 style="font-weight:600; color:#1e3a8a; border-bottom:2px solid #e0f2fe; padding-bottom:6px; margin-top:0;">
                        <i class="fa fa-gift"></i> Compensation, Perks & Benefits
                    </h4>
                    <div id="pv_benefits" style="color:#334155; line-height:1.6; whitespace:pre-line;">-</div>
                </div>
            </div>

            <div class="modal-footer" style="background:#fff; border-top:1px solid #e2e8f0;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px; font-weight:600;">Close Preview</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Toggle Switch */
.switch input { display:none; }
.slider {
    position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1; transition: .3s; border-radius: 22px;
}
.slider:before {
    position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px;
    background-color: white; transition: .3s; border-radius: 50%;
}
input:checked + .slider { background-color: #10b981; }
input:checked + .slider:before { transform: translateX(22px); }
</style>

<script type="text/javascript">
function openJobModal() {
    $('#jobPostingForm')[0].reset();
    $('#job_id').val('');
    $('#modal_action_title').text('Add Job Posting');
    $('#form_error_message').html('');
    $('#is_active').prop('checked', true);
    $('#is_closed').prop('checked', false);
    $('#jobPostingModal').modal('show');
}

function editJob(id) {
    $('#jobPostingForm')[0].reset();
    $('#job_id').val(id);
    $('#modal_action_title').text('Edit Job Posting');
    $('#form_error_message').html('');

    $.ajax({
        url: '<?php echo site_url("admin/jobposting/get_details/"); ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data) {
                $('#title').val(data.title);
                $('#designation_id').val(data.designation_id);
                $('#department').val(data.department);
                $('#employment_type').val(data.employment_type);
                $('#vacancies').val(data.vacancies);
                $('#location').val(data.location);
                $('#experience_required').val(data.experience_required);
                if (data.formatted_last_date) {
                    $('#last_date').val(data.formatted_last_date);
                } else if (data.last_date) {
                    $('#last_date').val(data.last_date);
                }
                $('#educational_level').val(data.educational_level);
                $('#certificates').val(data.certificates);
                $('#role_overview').val(data.role_overview);
                $('#job_description').val(data.job_description);
                $('#benefits').val(data.benefits);
                $('#is_active').prop('checked', (data.is_active == 1));
                $('#is_closed').prop('checked', (data.is_closed == 1));

                $('#jobPostingModal').modal('show');
            }
        }
    });
}

function previewJob(id) {
    $.ajax({
        url: '<?php echo site_url("admin/jobposting/get_details/"); ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data) {
                populatePreview(data);
                $('#previewJobModal').modal('show');
            }
        }
    });
}

function previewFormJob() {
    var desg_text = $('#designation_id option:selected').text();
    if ($('#designation_id').val() == '') { desg_text = 'Not Selected'; }

    var data = {
        title: $('#title').val() || 'Untitled Job Position',
        designation_title: desg_text,
        department: $('#department').val() || 'General',
        employment_type: $('#employment_type').val() || 'Full Time',
        vacancies: $('#vacancies').val() || 1,
        location: $('#location').val() || 'Main Campus',
        experience_required: $('#experience_required').val() || 'Not specified',
        formatted_last_date: $('#last_date').val() || 'Open until filled',
        educational_level: $('#educational_level').val() || 'As per designation standard',
        certificates: $('#certificates').val() || 'Standard qualifications apply',
        role_overview: $('#role_overview').val() || 'N/A',
        job_description: $('#job_description').val() || 'N/A',
        benefits: $('#benefits').val() || 'Competitive package',
        is_active: $('#is_active').is(':checked') ? 1 : 0,
        is_closed: $('#is_closed').is(':checked') ? 1 : 0
    };
    populatePreview(data);
    $('#previewJobModal').modal('show');
}

function populatePreview(data) {
    $('#pv_title').text(data.title || '-');
    $('#pv_designation').text(data.designation_title || 'Designation');
    $('#pv_emp_type').text(data.employment_type || 'Full Time');

    var dept_loc = (data.department ? data.department : 'General Department');
    if (data.location) { dept_loc += ' • ' + data.location; }
    $('#pv_dept_loc').text(dept_loc);

    $('#pv_vacancies').text(data.vacancies || '1');
    $('#pv_experience').text(data.experience_required || '-');
    $('#pv_location').text(data.location || '-');
    $('#pv_last_date').text(data.formatted_last_date || data.last_date || '-');

    $('#pv_role_overview').text(data.role_overview || '-');
    $('#pv_job_description').text(data.job_description || '-');
    $('#pv_educational_level').text(data.educational_level || '-');
    $('#pv_certificates').text(data.certificates || '-');
    $('#pv_benefits').text(data.benefits || '-');

    if (data.is_active == 1) {
        $('#pv_status_badge').text('Website Listed (ON)').css({'background':'#dcfce7', 'color':'#15803d'});
    } else {
        $('#pv_status_badge').text('Website Hidden (OFF)').css({'background':'#f1f5f9', 'color':'#64748b'});
    }

    if (data.is_closed == 1) {
        $('#pv_closed_badge').text('CLOSED (Applications Closed)').css({'background':'#fee2e2', 'color':'#dc2626'});
    } else {
        $('#pv_closed_badge').text('OPEN').css({'background':'#dcfce7', 'color':'#15803d'});
    }
}

function toggleWebsiteStatus(id, elem) {
    var status = $(elem).is(':checked') ? 1 : 0;
    $.ajax({
        url: '<?php echo site_url("admin/jobposting/change_status"); ?>',
        type: 'POST',
        data: { id: id, status: status, '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' },
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                var label_text = (status == 1) ? 'Listed (ON)' : 'Hidden (OFF)';
                var label_color = (status == 1) ? '#15803d' : '#94a3b8';
                $('#status_label_' + id).text(label_text).css('color', label_color);
                
                $('#ajax_msg_box').html('<div class="alert alert-success alert-dismissible" style="border-radius:6px;"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fa fa-check-circle"></i> ' + response.message + '</div>');
                setTimeout(function(){ $('#ajax_msg_box').fadeOut('slow'); }, 3000);
            } else {
                alert(response.message || 'Failed to update status');
                $(elem).prop('checked', !status);
            }
        },
        error: function() {
            alert('Error updating status');
            $(elem).prop('checked', !status);
        }
    });
}

function toggleCloseStatus(id, newClosedState) {
    $.ajax({
        url: '<?php echo site_url("admin/jobposting/toggle_close"); ?>',
        type: 'POST',
        data: { id: id, is_closed: newClosedState, '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' },
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                $('#ajax_msg_box').html('<div class="alert alert-success alert-dismissible" style="border-radius:6px;"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fa fa-check-circle"></i> ' + response.message + '</div>');
                setTimeout(function(){ location.reload(); }, 1000);
            } else {
                alert(response.message || 'Failed to update status');
            }
        },
        error: function() {
            alert('Error updating close status');
        }
    });
}

$(document).ready(function() {
    $('#jobPostingForm').on('submit', function(e) {
        e.preventDefault();
        $('#saveJobBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('#form_error_message').html('');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function(data) {
                $('#saveJobBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save Job Posting');
                if (data.status == 'fail') {
                    var error_html = '<div class="alert alert-danger"><ul>';
                    $.each(data.error, function(key, val) {
                        if (val != '') {
                            error_html += '<li>' + val + '</li>';
                        }
                    });
                    error_html += '</ul></div>';
                    $('#form_error_message').html(error_html);
                } else if (data.status == 'success') {
                    $('#jobPostingModal').modal('hide');
                    location.reload();
                }
            },
            error: function() {
                $('#saveJobBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save Job Posting');
                alert('An error occurred while saving.');
            }
        });
    });
});
</script>
