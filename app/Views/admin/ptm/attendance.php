<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f8fafc;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Modern Card Box Enhancements */
    .sc-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 18px rgba(0,0,0,0.02);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .sc-card-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .sc-card-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sc-card label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
    }

    .sc-card .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 8px 12px;
        height: 38px;
        background-color: #f8fafc;
        font-size: 13px;
        color: #0f172a;
        transition: all 0.2s ease;
    }

    .sc-card .form-control:focus {
        border-color: #0284c7;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }

    .select2-container--default .select2-selection--multiple {
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #f8fafc !important;
        min-height: 38px !important;
    }

    /* Buttons */
    .btn-sc-primary {
        background-color: #0284c7;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        padding: 8px 20px;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .btn-sc-primary:hover, .btn-sc-primary:focus {
        background-color: #0369a1;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
    }

    /* Status Toggle Pill Styling */
    .status-badge-select {
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        padding: 6px 10px;
        cursor: pointer;
        border: 1px solid #cbd5e1;
    }

    /* Mobile Responsive Card Transformations */
    @media (max-width: 767px) {
        .content-header {
            padding: 12px 14px 4px 14px !important;
        }
        .content {
            padding: 10px 12px !important;
        }
        .header-title-box {
            font-size: 16px !important;
            flex-direction: column;
            align-items: flex-start !important;
            gap: 4px !important;
        }
        .header-actions-box {
            width: 100%;
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-top: 8px;
        }
        .header-actions-box .btn {
            flex: 1;
            text-align: center;
            justify-content: center;
            padding: 8px 10px !important;
            font-size: 12px !important;
        }
        .sc-card {
            border-radius: 10px !important;
            margin-bottom: 14px !important;
        }
        .sc-card-header {
            padding: 12px 14px !important;
        }
        .sc-card-title {
            font-size: 15px !important;
        }

        /* Filter Form Stack on Mobile */
        .filter-submit-btn-wrap {
            margin-top: 10px !important;
        }

        /* Transform table into mobile-friendly touch cards */
        .mobile-card-table, 
        .mobile-card-table tbody, 
        .mobile-card-table tr, 
        .mobile-card-table td {
            display: block !important;
            width: 100% !important;
        }
        .mobile-card-table thead {
            display: none !important;
        }
        .mobile-card-table tr {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            margin-bottom: 14px !important;
            padding: 14px !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03) !important;
            position: relative;
        }
        .mobile-card-table td {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 9px 0 !important;
            border: none !important;
            border-bottom: 1px dashed #f1f5f9 !important;
            text-align: right !important;
            font-size: 13px !important;
        }
        .mobile-card-table td[data-label="Student"] {
            border-bottom: 1px solid #e2e8f0 !important;
            padding-bottom: 10px !important;
            margin-bottom: 4px !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            text-align: left !important;
        }
        .mobile-card-table td[data-label="Student"]::before {
            display: none !important;
        }
        .mobile-student-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        .mobile-student-name {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
        }
        .mobile-adm-badge {
            font-size: 11px;
            background: #f1f5f9;
            color: #475569;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 700;
        }
        .mobile-card-table td[data-label="Admission No"] {
            display: none !important; /* already in student header */
        }
        .mobile-card-table td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #64748b;
            text-align: left;
            padding-right: 12px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }
        .mobile-card-table td .form-control {
            max-width: 58% !important;
            display: inline-block !important;
            height: 38px !important;
            font-size: 13px !important;
            background: #ffffff !important;
        }
        .mobile-card-table td:last-child,
        .mobile-card-table td[data-label="Action"] {
            border-bottom: none !important;
            padding-top: 12px !important;
            padding-bottom: 0 !important;
            display: flex !important;
            gap: 10px !important;
            justify-content: space-between !important;
        }
        .mobile-card-table td[data-label="Action"]::before {
            display: none !important;
        }
        .mobile-card-table td[data-label="Action"] .btn {
            flex: 1;
            padding: 10px 8px !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            border-radius: 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
        }
        .view-snapshot {
            padding: 3px 8px !important;
            font-size: 11px !important;
            border-radius: 6px !important;
            margin-top: 4px;
        }
    }
</style>
<div class="content-wrapper dashboard2-wrapper" style="min-height: 946px;">
    <section class="content-header" style="padding: 18px 20px 5px 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <h1 class="header-title-box" style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa fa-users" style="color: #0284c7;"></i> <?php echo $this->lang->line('ptm_attendance'); ?>: <span style="color:#0284c7;"><?php echo htmlspecialchars($ptm['title']); ?></span>
            </h1>
            <div class="header-actions-box" style="display: flex; align-items: center; gap: 10px;">
                <a href="<?php echo base_url('admin/ptmreports'); ?>" class="btn btn-default" style="border-radius: 9px; font-weight: 700; font-size: 13px; padding: 8px 16px; border: 1px solid #cbd5e1; color: #334155; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa fa-line-chart" style="color: #0284c7;"></i> <?php echo $this->lang->line('reports'); ?>
                </a>
                <a href="<?php echo site_url('admin/ptm'); ?>" class="btn btn-default" style="border-radius: 9px; font-weight: 700; font-size: 13px; padding: 8px 16px; border: 1px solid #cbd5e1; color: #334155; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                </a>
            </div>
        </div>
    </section>
    <section class="content" style="padding: 15px 20px;">
        <div class="row">
            <div class="col-md-12">
                <!-- Select Criteria Box -->
                <div class="sc-card">
                    <div class="sc-card-header">
                        <h3 class="sc-card-title"><i class="fa fa-search" style="color:#0284c7;"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body" style="padding: 18px 20px;">
                        <form role="form" id="filter_form" method="GET" action="<?php echo site_url('admin/ptm/attendance/' . $ptm['id']); ?>" class="row">
                            <div class="col-md-5 col-sm-6">
                                <div class="form-group" style="margin-bottom: 12px;">
                                    <label><?php echo $this->lang->line('class'); ?> <small class="req" style="color:#ef4444;"> *</small></label>
                                    <select id="class_id" name="class_id[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                        <?php if (!empty($classlist)) {
                                            foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id']; ?>" <?php echo (!empty($selected_class_ids) && in_array($class['id'], $selected_class_ids)) ? 'selected' : ''; ?>><?php echo $class['class']; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-6">
                                <div class="form-group" style="margin-bottom: 12px;">
                                    <label><?php echo $this->lang->line('section'); ?> <small class="req" style="color:#ef4444;"> *</small></label>
                                    <select id="section_id" name="section_id[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                        <?php if (!empty($sections_list)) {
                                            foreach ($sections_list as $sec) { ?>
                                                <option value="<?php echo $sec['section_id']; ?>" <?php echo (!empty($selected_section_ids) && in_array($sec['section_id'], $selected_section_ids)) ? 'selected' : ''; ?>><?php echo $sec['section']; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 filter-submit-btn-wrap" style="margin-top: 24px; display: flex; gap: 8px;">
                                <button type="submit" class="btn btn-sc-primary" id="btn_search" style="flex: 1; height: 38px;"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                <?php if (!empty($selected_class_ids) || !empty($selected_section_ids)) { ?>
                                    <a href="<?php echo site_url('admin/ptm/attendance/' . $ptm['id']); ?>" class="btn btn-default" style="border-radius: 8px; padding: 8px 12px; height: 38px; display: inline-flex; align-items: center; justify-content: center;" title="Reset Filters"><i class="fa fa-refresh"></i></a>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if (!empty($is_searched)) { ?>
                <!-- Students Attendance List -->
                <div class="sc-card">
                    <div class="sc-card-header">
                        <h3 class="sc-card-title"><i class="fa fa-list" style="color:#0284c7;"></i> <?php echo $this->lang->line('students'); ?></h3>
                        <span class="badge bg-blue" style="font-size: 13px; font-weight: 600; padding: 6px 12px; border-radius: 6px; background-color: #0284c7;">Total: <?php echo count($students); ?> Students</span>
                    </div>
                    <div class="box-body" style="padding: 18px 20px;">
                        <div class="table-responsive" style="border: none;">
                            <table class="table table-striped table-bordered table-hover example mobile-card-table" style="margin-bottom: 0;">
                                <thead>
                                    <tr style="background: #f8fafc;">
                                        <th style="width: 100px;"><?php echo $this->lang->line('admission_no'); ?></th>
                                        <th><?php echo $this->lang->line('student_name'); ?></th>
                                        <th style="width: 140px;"><?php echo $this->lang->line('class'); ?></th>
                                        <th style="width: 130px;"><?php echo $this->lang->line('status'); ?></th>
                                        <th style="width: 130px;"><?php echo $this->lang->line('attendee'); ?></th>
                                        <th style="width: 180px; text-align: center;"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($students)) {
                                        foreach ($students as $student) { 
                                            $att = isset($attendances[$student['student_session_id']]) ? $attendances[$student['student_session_id']] : [];
                                            $has_remarks = !empty($att['parent_remarks']) || !empty($att['teacher_remarks']) || !empty($att['discussion_points']) || !empty($att['action_items']) || !empty($att['followup_required']);
                                        ?>
                                            <tr>
                                                <td data-label="Admission No"><?php echo $student['admission_no']; ?></td>
                                                <td data-label="Student">
                                                    <div class="mobile-student-header">
                                                        <div>
                                                            <span class="mobile-student-name"><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></span>
                                                            <span class="mobile-adm-badge hidden-md hidden-lg">Adm: <?php echo $student['admission_no']; ?></span>
                                                        </div>
                                                        <a href="#" class="btn btn-xs btn-info view-snapshot" data-student="<?php echo $student['id']; ?>" data-session="<?php echo $student['student_session_id']; ?>" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-line-chart"></i> <?php echo $this->lang->line('snapshot'); ?></a>
                                                    </div>
                                                </td>
                                                <td data-label="<?php echo $this->lang->line('class'); ?>"><span class="badge" style="background:#f1f5f9; color:#334155; font-size:12px; font-weight:600;"><?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></span></td>
                                                <td data-label="<?php echo $this->lang->line('status'); ?>">
                                                    <select class="form-control" id="status_<?php echo $student['student_session_id']; ?>" style="font-weight: 700; color: <?php echo (isset($att['status']) && $att['status']=='present') ? '#16a34a' : '#dc2626'; ?>;">
                                                        <option value="absent" <?php echo (isset($att['status']) && $att['status']=='absent') ? 'selected' : ''; ?>>Absent</option>
                                                        <option value="present" <?php echo (isset($att['status']) && $att['status']=='present') ? 'selected' : ''; ?>>Present</option>
                                                    </select>
                                                </td>
                                                <td data-label="<?php echo $this->lang->line('attendee'); ?>">
                                                    <select class="form-control" id="attendee_<?php echo $student['student_session_id']; ?>">
                                                        <option value="">-</option>
                                                        <?php $atts = ['father', 'mother', 'both', 'guardian', 'other']; 
                                                        foreach($atts as $a) { ?>
                                                            <option value="<?php echo $a; ?>" <?php echo (isset($att['attendee_type']) && $att['attendee_type']==$a) ? 'selected' : ''; ?>><?php echo ucfirst($a); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td data-label="Action">
                                                    <button type="button" class="btn btn-primary btn-sm add-remarks" data-session="<?php echo $student['student_session_id']; ?>" style="background: <?php echo $has_remarks ? '#0284c7' : '#e2e8f0'; ?>; color: <?php echo $has_remarks ? '#ffffff' : '#334155'; ?>; border: none;">
                                                        <i class="fa fa-commenting-o"></i> <?php echo $this->lang->line('remarks'); ?> <?php if ($has_remarks) { ?><i class="fa fa-check" style="font-size:10px;"></i><?php } ?>
                                                    </button>
                                                    
                                                    <!-- Hidden fields for remarks data -->
                                                    <input type="hidden" id="arrival_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['arrival_time']) ? $att['arrival_time'] : ''; ?>">
                                                    <input type="hidden" id="departure_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['departure_time']) ? $att['departure_time'] : ''; ?>">
                                                    <input type="hidden" id="points_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['discussion_points']) ? htmlspecialchars($att['discussion_points']) : ''; ?>">
                                                    <input type="hidden" id="parent_rmk_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['parent_remarks']) ? htmlspecialchars($att['parent_remarks']) : ''; ?>">
                                                    <input type="hidden" id="teacher_rmk_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['teacher_remarks']) ? htmlspecialchars($att['teacher_remarks']) : ''; ?>">
                                                    <input type="hidden" id="c_academics_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['concerns_academics']) ? htmlspecialchars($att['concerns_academics']) : ''; ?>">
                                                    <input type="hidden" id="c_attendance_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['concerns_attendance']) ? htmlspecialchars($att['concerns_attendance']) : ''; ?>">
                                                    <input type="hidden" id="c_behavior_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['concerns_behavior']) ? htmlspecialchars($att['concerns_behavior']) : ''; ?>">
                                                    <input type="hidden" id="c_discipline_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['concerns_discipline']) ? htmlspecialchars($att['concerns_discipline']) : ''; ?>">
                                                    <input type="hidden" id="action_items_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['action_items']) ? htmlspecialchars($att['action_items']) : ''; ?>">
                                                    <input type="hidden" id="followup_req_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['followup_required']) ? $att['followup_required'] : '0'; ?>">
                                                    <input type="hidden" id="followup_ass_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['followup_assigned_to']) ? $att['followup_assigned_to'] : ''; ?>">
                                                    <input type="hidden" id="followup_date_<?php echo $student['student_session_id']; ?>" value="<?php echo isset($att['followup_date']) ? $att['followup_date'] : ''; ?>">
                                                    
                                                    <button type="button" class="btn btn-success btn-sm save-attendance" data-session="<?php echo $student['student_session_id']; ?>" style="background: #16a34a; border: none; font-weight: 700;"><i class="fa fa-save"></i> Save</button>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="6" class="text-center" style="padding: 24px; color: #94a3b8;">
                                                <i class="fa fa-info-circle" style="font-size: 24px; color: #cbd5e1; margin-bottom: 8px;"></i><br>
                                                No students found for the selected criteria.
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<!-- Modal for Remarks (Mobile Responsive) -->
<div id="remarksModal" class="modal fade" role="dialog" tabindex="-1" style="z-index: 10500;">
    <div class="modal-dialog modal-lg" style="margin: 15px auto; max-width: 95%;">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden;">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between;">
                <h4 class="modal-title" style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-comments" style="color: #0284c7;"></i> <?php echo $this->lang->line('discussions_and_remarks'); ?>
                </h4>
                <button type="button" class="close" data-dismiss="modal" style="font-size: 24px; color: #64748b; opacity: 1; margin: 0; padding: 0;">&times;</button>
            </div>
            <div class="modal-body" style="padding: 16px; max-height: 75vh; overflow-y: auto;">
                <input type="hidden" id="modal_session_id">
                
                <div class="row">
                    <!-- Column 1 / Top on Mobile -->
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('arrival_time'); ?></label>
                            <input type="time" class="form-control" id="m_arrival" style="border-radius: 8px; height: 38px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('discussion_points'); ?></label>
                            <textarea class="form-control" id="m_points" rows="3" style="border-radius: 8px;" placeholder="Key discussion points during PTM..."></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('parent_remarks'); ?></label>
                            <textarea class="form-control" id="m_parent_rmk" rows="2" style="border-radius: 8px;" placeholder="Feedback or remarks from parents..."></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('concerns_academics'); ?></label>
                            <textarea class="form-control" id="m_c_academics" rows="2" style="border-radius: 8px;" placeholder="Academic concerns if any..."></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('concerns_behavior'); ?></label>
                            <textarea class="form-control" id="m_c_behavior" rows="2" style="border-radius: 8px;" placeholder="Behavior notes..."></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('action_items'); ?></label>
                            <textarea class="form-control" id="m_action_items" rows="2" style="border-radius: 8px;" placeholder="Action items agreed upon..."></textarea>
                        </div>
                    </div>

                    <!-- Column 2 / Bottom on Mobile -->
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('departure_time'); ?></label>
                            <input type="time" class="form-control" id="m_departure" style="border-radius: 8px; height: 38px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('teacher_remarks'); ?></label>
                            <textarea class="form-control" id="m_teacher_rmk" rows="2" style="border-radius: 8px;" placeholder="Teacher observation notes..."></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('concerns_attendance'); ?></label>
                            <textarea class="form-control" id="m_c_attendance" rows="2" style="border-radius: 8px;" placeholder="Attendance irregularity notes..."></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('concerns_discipline'); ?></label>
                            <textarea class="form-control" id="m_c_discipline" rows="2" style="border-radius: 8px;" placeholder="Discipline notes..."></textarea>
                        </div>

                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; margin-top: 6px;">
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label style="font-size: 11px; font-weight: 700; color: #d97706; text-transform: uppercase;"><i class="fa fa-flag"></i> <?php echo $this->lang->line('follow_up_required'); ?></label>
                                <select class="form-control" id="m_followup_req" style="border-radius: 8px; height: 38px;">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('assign_to'); ?></label>
                                <select class="form-control" id="m_followup_ass" style="border-radius: 8px; height: 38px;">
                                    <option value="">Select Staff</option>
                                    <?php foreach($staffs as $s) { ?>
                                        <option value="<?php echo $s['id']; ?>"><?php echo $s['name'] . ' ' . $s['surname']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('follow_up_date'); ?></label>
                                <input type="date" class="form-control" id="m_followup_date" style="border-radius: 8px; height: 38px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 16px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 600; padding: 8px 16px;"><?php echo $this->lang->line('close'); ?></button>
                <button type="button" class="btn btn-primary" onclick="saveModalData()" style="border-radius: 8px; font-weight: 700; background: #0284c7; border: none; padding: 8px 20px;"><i class="fa fa-save"></i> <?php echo $this->lang->line('save_remarks'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Right Sidebar for Student Snapshot -->
<style>
.ptm-sidebar {
    height: 100%;
    width: 0;
    position: fixed;
    z-index: 10600;
    top: 0;
    right: 0;
    background-color: #ffffff;
    overflow-x: hidden;
    transition: 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: -4px 0 25px rgba(0,0,0,0.15);
    max-width: 100vw;
}
.ptm-sidebar-header {
    padding: 16px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.ptm-sidebar-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}
.ptm-sidebar-header .closebtn {
    font-size: 26px;
    color: #64748b;
    cursor: pointer;
    text-decoration: none;
    line-height: 1;
}
.ptm-sidebar-content {
    padding: 16px;
    overflow-y: auto;
    height: calc(100% - 60px);
}
</style>
<div id="ptmSnapshotSidebar" class="ptm-sidebar">
    <div class="ptm-sidebar-header">
        <h4><i class="fa fa-line-chart" style="color: #0284c7;"></i> <?php echo $this->lang->line('performance_snapshot'); ?></h4>
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    </div>
    <div class="ptm-sidebar-content" id="snapshot_content">
        <!-- Content loaded via ajax -->
    </div>
</div>

<script>
    var current_ptm_id = '<?php echo $ptm['id']; ?>';

    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2({
                placeholder: "Select...",
                allowClear: true
            });
        }

        $('#class_id').change(function () {
            var class_ids = $(this).val();
            $('#section_id').html('');
            
            if (class_ids && class_ids.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('admin/ptm/get_sections_multi'); ?>",
                    data: {'class_ids': class_ids},
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function (i, obj) {
                            $('#section_id').append($('<option>').text(obj.section).attr('value', obj.section_id));
                        });
                        $('#section_id').trigger('change');
                    }
                });
            }
        });
    });

    $(document).on('click', '.add-remarks', function(){
        var sess_id = $(this).data('session');
        $('#modal_session_id').val(sess_id);
        $('#m_arrival').val($('#arrival_' + sess_id).val());
        $('#m_departure').val($('#departure_' + sess_id).val());
        $('#m_points').val($('#points_' + sess_id).val());
        $('#m_parent_rmk').val($('#parent_rmk_' + sess_id).val());
        $('#m_teacher_rmk').val($('#teacher_rmk_' + sess_id).val());
        $('#m_c_academics').val($('#c_academics_' + sess_id).val());
        $('#m_c_attendance').val($('#c_attendance_' + sess_id).val());
        $('#m_c_behavior').val($('#c_behavior_' + sess_id).val());
        $('#m_c_discipline').val($('#c_discipline_' + sess_id).val());
        $('#m_action_items').val($('#action_items_' + sess_id).val());
        $('#m_followup_req').val($('#followup_req_' + sess_id).val() || '0');
        $('#m_followup_ass').val($('#followup_ass_' + sess_id).val());
        $('#m_followup_date').val($('#followup_date_' + sess_id).val());
        
        $('#remarksModal').modal('show');
    });

    function saveModalData() {
        var sess_id = $('#modal_session_id').val();
        $('#arrival_' + sess_id).val($('#m_arrival').val());
        $('#departure_' + sess_id).val($('#m_departure').val());
        $('#points_' + sess_id).val($('#m_points').val());
        $('#parent_rmk_' + sess_id).val($('#m_parent_rmk').val());
        $('#teacher_rmk_' + sess_id).val($('#m_teacher_rmk').val());
        $('#c_academics_' + sess_id).val($('#m_c_academics').val());
        $('#c_attendance_' + sess_id).val($('#m_c_attendance').val());
        $('#c_behavior_' + sess_id).val($('#m_c_behavior').val());
        $('#c_discipline_' + sess_id).val($('#m_c_discipline').val());
        $('#action_items_' + sess_id).val($('#m_action_items').val());
        $('#followup_req_' + sess_id).val($('#m_followup_req').val());
        $('#followup_ass_' + sess_id).val($('#m_followup_ass').val());
        $('#followup_date_' + sess_id).val($('#m_followup_date').val());
        
        $('#remarksModal').modal('hide');
    }

    $(document).on('click', '.save-attendance', function(){
        var sess_id = $(this).data('session');
        var btn = $(this);
        var original_text = btn.html();
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: "<?php echo site_url('admin/ptm/save_attendance') ?>",
            type: "POST",
            data: {
                ptm_id: current_ptm_id,
                student_session_id: sess_id,
                status: $('#status_' + sess_id).val(),
                attendee_type: $('#attendee_' + sess_id).val(),
                arrival_time: $('#arrival_' + sess_id).val(),
                departure_time: $('#departure_' + sess_id).val(),
                discussion_points: $('#points_' + sess_id).val(),
                parent_remarks: $('#parent_rmk_' + sess_id).val(),
                teacher_remarks: $('#teacher_rmk_' + sess_id).val(),
                concerns_academics: $('#c_academics_' + sess_id).val(),
                concerns_attendance: $('#c_attendance_' + sess_id).val(),
                concerns_behavior: $('#c_behavior_' + sess_id).val(),
                concerns_discipline: $('#c_discipline_' + sess_id).val(),
                action_items: $('#action_items_' + sess_id).val(),
                followup_required: $('#followup_req_' + sess_id).val(),
                followup_assigned_to: $('#followup_ass_' + sess_id).val(),
                followup_date: $('#followup_date_' + sess_id).val()
            },
            dataType: 'json',
            success: function (res) {
                if (res.status == 'success') {
                    if (typeof successMsg === 'function') {
                        successMsg(res.message);
                    } else {
                        alert(res.message);
                    }
                    btn.html('<i class="fa fa-check"></i> Saved').removeClass('btn-success').addClass('btn-default');
                    setTimeout(function(){
                        btn.html(original_text).removeClass('btn-default').addClass('btn-success');
                    }, 1800);
                } else {
                    if (typeof errorMsg === 'function') {
                        errorMsg(res.message);
                    } else {
                        alert(res.message);
                    }
                    btn.html(original_text);
                }
                btn.prop('disabled', false);
            },
            error: function () {
                btn.prop('disabled', false).html(original_text);
                if (typeof errorMsg === 'function') {
                    errorMsg("Error saving attendance data");
                } else {
                    alert("Error saving attendance data");
                }
            }
        });
    });

    $(document).on('click', '.view-snapshot', function(e){
        e.preventDefault();
        var student_id = $(this).data('student');
        var sess_id = $(this).data('session');
        $('#snapshot_content').html('<div class="text-center" style="padding: 40px;"><i class="fa fa-spinner fa-spin fa-2x" style="color: #0284c7;"></i><p style="margin-top:10px; color:#64748b;">Loading performance snapshot...</p></div>');
        openNav();
        
        $.ajax({
            url: "<?php echo site_url('admin/ptm/get_student_snapshot') ?>",
            type: "POST",
            data: {student_id: student_id, student_session_id: sess_id, ptm_id: current_ptm_id},
            dataType: 'json',
            success: function (res) {
                $('#snapshot_content').html(res.page);
            },
            error: function() {
                $('#snapshot_content').html('<div class="text-center text-danger" style="padding: 30px;"><i class="fa fa-exclamation-triangle fa-2x"></i><p>Unable to load snapshot data.</p></div>');
            }
        });
    });

    function openNav() {
        if ($(window).width() < 768) {
            document.getElementById("ptmSnapshotSidebar").style.width = "100%";
        } else {
            document.getElementById("ptmSnapshotSidebar").style.width = "750px";
        }
    }

    function closeNav() {
        document.getElementById("ptmSnapshotSidebar").style.width = "0";
    }
</script>
