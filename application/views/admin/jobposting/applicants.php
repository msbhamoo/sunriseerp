<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> <?php echo $title; ?>
        </h1>
    </section>

    <section class="content">
        <!-- STATS & FILTER CARDS -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="border-radius:8px; border-top:3px solid #2563eb;">
                    <div class="box-header with-border" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; padding:15px 20px;">
                        <h3 class="box-title" style="font-weight:700; color:#0f172a;">
                            <i class="fa fa-filter"></i> Candidate Pipeline Overview
                        </h3>
                        <a href="<?php echo site_url('admin/jobposting'); ?>" class="btn btn-default btn-sm" style="border-radius:6px; font-weight:600;">
                            <i class="fa fa-arrow-left"></i> Back to Job Postings
                        </a>
                    </div>
                    <div class="box-body" style="padding:20px;">
                        <!-- Job Filter Form -->
                        <form method="get" action="<?php echo site_url('admin/jobposting/applicants'); ?>" class="form-inline" style="margin-bottom:20px;">
                            <div class="form-group" style="margin-right:15px;">
                                <label for="job_filter" style="margin-right:8px; font-weight:600;">Filter by Job Position:</label>
                                <select name="job_id" id="job_filter" class="form-control" onchange="this.form.submit()" style="min-width:250px; border-radius:6px;">
                                    <option value="">-- All Job Openings --</option>
                                    <?php if (!empty($job_list)) {
                                        foreach ($job_list as $j) { ?>
                                            <option value="<?php echo $j['id']; ?>" <?php echo ($selected_job == $j['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($j['title']); ?> (<?php echo htmlspecialchars($j['designation_title'] ? $j['designation_title'] : 'General'); ?>)
                                            </option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <?php if (!empty($selected_stage)) { ?>
                                <input type="hidden" name="stage" value="<?php echo htmlspecialchars($selected_stage); ?>">
                            <?php } ?>
                            <?php if (!empty($selected_job) || !empty($selected_stage)) { ?>
                                <a href="<?php echo site_url('admin/jobposting/applicants'); ?>" class="btn btn-default btn-sm" style="border-radius:6px;">Reset Filters</a>
                            <?php } ?>
                        </form>

                        <!-- Stage Tabs / Quick Filter Pills -->
                        <?php
                        $stages_list = array(
                            'All'                 => array('label' => 'All Candidates', 'color' => '#334155', 'bg' => '#f1f5f9', 'cnt' => $counts['total']),
                            'Submitted'           => array('label' => 'Submitted', 'color' => '#0284c7', 'bg' => '#e0f2fe', 'cnt' => $counts['Submitted']),
                            'Screening'           => array('label' => 'Screening', 'color' => '#7c3aed', 'bg' => '#f3e8ff', 'cnt' => $counts['Screening']),
                            'Shortlisted'         => array('label' => 'Shortlisted', 'color' => '#059669', 'bg' => '#d1fae5', 'cnt' => $counts['Shortlisted']),
                            'Interview Scheduled' => array('label' => 'Interview Scheduled', 'color' => '#d97706', 'bg' => '#fef3c7', 'cnt' => $counts['Interview Scheduled']),
                            'Offered'             => array('label' => 'Offered', 'color' => '#0891b2', 'bg' => '#cffaff', 'cnt' => $counts['Offered']),
                            'Hired'               => array('label' => 'Hired', 'color' => '#16a34a', 'bg' => '#dcfce7', 'cnt' => $counts['Hired']),
                            'Rejected'            => array('label' => 'Rejected', 'color' => '#dc2626', 'bg' => '#fee2e2', 'cnt' => $counts['Rejected']),
                        );
                        ?>
                        <div style="display:flex; flex-wrap:wrap; gap:8px;">
                            <?php foreach ($stages_list as $stg_key => $stg_val) {
                                $is_sel = ($stg_key == 'All' && empty($selected_stage)) || ($selected_stage == $stg_key);
                                $url = site_url('admin/jobposting/applicants' . ($selected_job ? '/' . $selected_job : '')) . ($stg_key != 'All' ? '?stage=' . urlencode($stg_key) : '');
                            ?>
                                <a href="<?php echo $url; ?>" class="btn btn-sm" style="border-radius:20px; font-weight:600; padding:6px 14px; text-decoration:none; background-color:<?php echo $stg_val['bg']; ?>; color:<?php echo $stg_val['color']; ?>; border:1px solid <?php echo $is_sel ? $stg_val['color'] : 'transparent'; ?>; box-shadow:<?php echo $is_sel ? '0 0 0 2px ' . $stg_val['color'] : 'none'; ?>;">
                                    <?php echo $stg_val['label']; ?> <span class="badge" style="background-color:<?php echo $stg_val['color']; ?>; color:#fff;"><?php echo $stg_val['cnt']; ?></span>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- APPLICANTS TABLE -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="border-radius:8px;">
                    <div class="box-body table-responsive" style="padding:15px;">
                        <?php if ($this->session->flashdata('msg')) {
                            echo $this->session->flashdata('msg');
                        } ?>
                        <div id="applicant_ajax_msg"></div>

                        <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                            <thead>
                                <tr style="background:#f8fafc; color:#475569;">
                                    <th># Ref No</th>
                                    <th>Candidate Name</th>
                                    <th>Applied Job & Designation</th>
                                    <th>Contact Details</th>
                                    <th>Experience & Qualification</th>
                                    <th>Applied Date</th>
                                    <th class="text-center">Pipeline Stage</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($applicants)) {
                                    foreach ($applicants as $app) {
                                        $applied_date = date($this->customlib->getSchoolDateFormat(), strtotime($app['created_at']));
                                        $stage_color_map = array(
                                            'Submitted'           => '#0284c7',
                                            'Screening'           => '#7c3aed',
                                            'Shortlisted'         => '#059669',
                                            'Interview Scheduled' => '#d97706',
                                            'Offered'             => '#0891b2',
                                            'Hired'               => '#16a34a',
                                            'Rejected'            => '#dc2626',
                                        );
                                        $curr_color = isset($stage_color_map[$app['stage']]) ? $stage_color_map[$app['stage']] : '#334155';
                                ?>
                                        <tr>
                                            <td>
                                                <strong style="color:#2563eb; font-size:12px;"><?php echo htmlspecialchars($app['application_no']); ?></strong>
                                            </td>
                                            <td>
                                                <strong style="color:#0f172a; font-size:14px;"><?php echo htmlspecialchars($app['name']); ?></strong>
                                                <?php if (!empty($app['admin_notes'])) { ?>
                                                    <br><small class="text-warning" title="HR Notes"><i class="fa fa-commenting-o"></i> <?php echo htmlspecialchars(substr($app['admin_notes'], 0, 40)); ?>...</small>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <strong style="color:#1e293b;"><?php echo htmlspecialchars($app['job_title']); ?></strong>
                                                <br><small class="text-muted"><i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($app['designation_title'] ? $app['designation_title'] : 'General'); ?></small>
                                            </td>
                                            <td>
                                                <i class="fa fa-envelope text-muted"></i> <a href="mailto:<?php echo htmlspecialchars($app['email']); ?>"><?php echo htmlspecialchars($app['email']); ?></a><br>
                                                <i class="fa fa-phone text-muted"></i> <a href="tel:<?php echo htmlspecialchars($app['phone']); ?>"><?php echo htmlspecialchars($app['phone']); ?></a>
                                            </td>
                                            <td>
                                                <span class="label label-default" style="font-weight:600;"><i class="fa fa-briefcase"></i> <?php echo htmlspecialchars($app['experience_years'] ? $app['experience_years'] : '-'); ?></span><br>
                                                <small class="text-muted"><i class="fa fa-graduation-cap"></i> <?php echo htmlspecialchars($app['qualification'] ? $app['qualification'] : '-'); ?></small>
                                            </td>
                                            <td><span class="text-muted"><i class="fa fa-clock-o"></i> <?php echo $applied_date; ?></span></td>
                                            <td class="text-center">
                                                <?php if ($this->rbac->hasPrivilege('job_posting', 'can_edit')) { ?>
                                                    <select onchange="updateStage(<?php echo $app['id']; ?>, this.value)" class="form-control input-sm" style="font-weight:700; border-radius:12px; color:<?php echo $curr_color; ?>; border:1px solid <?php echo $curr_color; ?>; background:#ffffff;">
                                                        <option value="Submitted" <?php echo ($app['stage'] == 'Submitted') ? 'selected' : ''; ?>>Submitted</option>
                                                        <option value="Screening" <?php echo ($app['stage'] == 'Screening') ? 'selected' : ''; ?>>Screening</option>
                                                        <option value="Shortlisted" <?php echo ($app['stage'] == 'Shortlisted') ? 'selected' : ''; ?>>Shortlisted</option>
                                                        <option value="Interview Scheduled" <?php echo ($app['stage'] == 'Interview Scheduled') ? 'selected' : ''; ?>>Interview Scheduled</option>
                                                        <option value="Offered" <?php echo ($app['stage'] == 'Offered') ? 'selected' : ''; ?>>Offered</option>
                                                        <option value="Hired" <?php echo ($app['stage'] == 'Hired') ? 'selected' : ''; ?>>Hired</option>
                                                        <option value="Rejected" <?php echo ($app['stage'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                                                    </select>
                                                <?php } else { ?>
                                                    <span class="label" style="background:<?php echo $curr_color; ?>; font-weight:600; padding:5px 10px; border-radius:12px; font-size:11px; color:#fff;">
                                                        <?php echo htmlspecialchars($app['stage']); ?>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-right" style="white-space:nowrap;">
                                                <?php if (!empty($app['resume_file'])) { ?>
                                                    <a href="<?php echo site_url('admin/jobposting/download_resume/' . $app['id']); ?>" class="btn btn-primary btn-xs" title="Download Resume / CV" style="border-radius:4px; margin-right:2px;">
                                                        <i class="fa fa-download"></i> Resume
                                                    </a>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-default btn-xs disabled" title="No Resume Uploaded">No File</button>
                                                <?php } ?>

                                                <button type="button" class="btn btn-default btn-xs" onclick="openNotesModal(<?php echo $app['id']; ?>, '<?php echo htmlspecialchars(addslashes($app['name'])); ?>', '<?php echo htmlspecialchars(addslashes($app['admin_notes'] ?? '')); ?>')" title="HR Internal Notes" style="border-radius:4px; margin-right:2px;">
                                                    <i class="fa fa-commenting text-warning"></i> Notes
                                                </button>

                                                <?php if ($this->rbac->hasPrivilege('job_posting', 'can_delete')) { ?>
                                                    <a href="<?php echo site_url('admin/jobposting/delete_applicant/' . $app['id']); ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure you want to delete this applicant?');" title="Delete Applicant" style="border-radius:4px;">
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

<!-- ================= HR NOTES MODAL ================= -->
<div class="modal fade" id="hrNotesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:10px; overflow:hidden;">
            <div class="modal-header" style="background:#3c8dbc; color:#fff;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-weight:600;"><i class="fa fa-commenting"></i> HR Notes: <span id="note_candidate_name">Candidate</span></h4>
            </div>
            <div class="modal-body" style="padding:20px;">
                <input type="hidden" id="note_app_id" value="">
                <div class="form-group">
                    <label for="note_text">Internal Interview & Evaluation Remarks:</label>
                    <textarea class="form-control" id="note_text" rows="5" placeholder="Enter HR comments, interview scores, background check status, salary expectations, etc."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveNotes()" style="font-weight:600;">Save HR Remarks</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function updateStage(id, newStage) {
    $.ajax({
        url: '<?php echo site_url("admin/jobposting/update_applicant_stage"); ?>',
        type: 'POST',
        data: { id: id, stage: newStage, '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' },
        dataType: 'json',
        success: function(res) {
            if (res.status == 'success') {
                $('#applicant_ajax_msg').html('<div class="alert alert-success alert-dismissible" style="border-radius:6px;"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fa fa-check-circle"></i> ' + res.message + '</div>');
                setTimeout(function(){ $('#applicant_ajax_msg').fadeOut('slow'); }, 3000);
            } else {
                alert(res.message || 'Failed to update stage');
            }
        },
        error: function() {
            alert('Error updating applicant stage');
        }
    });
}

function openNotesModal(id, candidateName, currentNotes) {
    $('#note_app_id').val(id);
    $('#note_candidate_name').text(candidateName);
    $('#note_text').val(currentNotes);
    $('#hrNotesModal').modal('show');
}

function saveNotes() {
    var id = $('#note_app_id').val();
    var notes = $('#note_text').val();

    $.ajax({
        url: '<?php echo site_url("admin/jobposting/update_applicant_notes"); ?>',
        type: 'POST',
        data: { id: id, notes: notes, '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' },
        dataType: 'json',
        success: function(res) {
            if (res.status == 'success') {
                $('#hrNotesModal').modal('hide');
                location.reload();
            } else {
                alert(res.message || 'Failed to save notes');
            }
        },
        error: function() {
            alert('Error saving HR notes');
        }
    });
}
</script>
