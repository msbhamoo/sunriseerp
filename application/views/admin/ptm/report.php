<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f8fafc;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Modern Card Box */
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
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sc-card-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Buttons */
    .btn-sc-primary {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 9px;
        font-weight: 700;
        font-size: 13px;
        padding: 9px 20px;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.28);
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-sc-primary:hover, .btn-sc-primary:focus {
        background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.38);
    }

    /* Report Navigation Links (matching user's screenshot style) */
    .report-card-container {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.02);
        margin-bottom: 22px;
        overflow: hidden;
    }
    .report-card-header {
        padding: 16px 22px 10px 22px;
    }
    .report-card-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }
    .report-links-grid {
        display: flex;
        flex-wrap: wrap;
        padding: 6px 14px 16px 14px;
    }
    .report-link-item {
        padding: 8px 10px;
    }
    .report-link-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 16px;
        border-radius: 9px;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.2s ease;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        cursor: pointer;
    }
    .report-link-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0284c7;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }
    .report-link-btn.active {
        background: #e0f2fe;
        color: #0369a1;
        font-weight: 700;
        border-color: #bae6fd;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.15);
    }
    .report-link-btn i {
        font-size: 15px;
    }

    /* Form UI inside Filters */
    .form-label-custom {
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
    }
    .form-control-custom {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 9px 13px;
        height: 40px;
        background-color: #ffffff;
        font-size: 13px;
        color: #0f172a;
        width: 100%;
        transition: all 0.2s ease;
    }
    .form-control-custom:focus {
        border-color: #0284c7;
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12);
    }

    .select2-container--default .select2-selection--multiple {
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        min-height: 40px !important;
    }

    /* Performance Snapshot Sidebar */
    .ptm-sidebar {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 10002;
        top: 0;
        right: 0;
        background-color: #fff;
        overflow-x: hidden;
        transition: 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: -5px 0 25px rgba(0,0,0,0.15);
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
    }
    .ptm-sidebar-content {
        padding: 16px;
        overflow-y: auto;
        height: calc(100% - 60px);
    }

    @media (max-width: 767px) {
        .content-header {
            padding: 12px 14px 4px 14px !important;
        }
        .content {
            padding: 10px 12px !important;
        }
        .report-links-grid {
            padding: 4px 8px 12px 8px !important;
        }
        .report-link-item {
            padding: 4px 6px !important;
            width: 100% !important;
        }
        .report-link-btn {
            padding: 12px 14px !important;
            font-size: 14px !important;
        }
        .btn-sc-primary {
            width: 100% !important;
            justify-content: center !important;
            height: 42px !important;
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
    }
</style>

<div class="content-wrapper dashboard2-wrapper" style="min-height: 946px;">
    <!-- Top Header -->
    <section class="content-header" style="padding: 18px 20px 5px 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div>
                <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="fa fa-pie-chart" style="color: #0284c7;"></i> <?php echo $this->lang->line('ptm_reports'); ?>
                </h1>
            </div>
            <div>
                <a href="<?php echo site_url('admin/ptm'); ?>" class="btn btn-default" style="border-radius: 9px; font-weight: 700; font-size: 13px; padding: 8px 16px; border: 1px solid #cbd5e1; color: #334155; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa fa-calendar-check-o" style="color: #0284c7;"></i> PTM Meetings
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content" style="padding: 15px 20px;">
        <!-- 1. Reports Section (matching user's reference screenshot) -->
        <div class="report-card-container" id="ptm_reports_section">
            <div class="report-card-header">
                <h4><?php echo $this->lang->line('reports'); ?></h4>
            </div>
            <div class="report-links-grid row">
                <div class="col-lg-4 col-md-4 col-sm-6 report-link-item">
                    <a href="javascript:void(0)" class="report-link-btn" data-type="ptm_attendance" data-title="PTM Attendance & Participation Report">
                        <i class="fa fa-file-text-o text-primary"></i> PTM Attendance Report
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 report-link-item">
                    <a href="javascript:void(0)" class="report-link-btn" data-type="followup_report" data-title="Assigned PTM Follow-ups & Action Items">
                        <i class="fa fa-check-square-o text-success"></i> Assigned Follow-ups Report
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 report-link-item">
                    <a href="javascript:void(0)" class="report-link-btn" data-type="remarks_report" data-title="Discussions, Feedback & Remarks Summary">
                        <i class="fa fa-comments-o text-info"></i> Discussion & Remarks Summary
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 report-link-item">
                    <a href="javascript:void(0)" class="report-link-btn" data-type="analytics_report" data-title="Class-wise Turnout & Turnaround Analytics">
                        <i class="fa fa-bar-chart text-warning"></i> Class-wise Turnout Analysis
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 report-link-item">
                    <a href="javascript:void(0)" class="report-link-btn" data-type="absentee_report" data-title="Absentee & Non-Attendee Follow-up List">
                        <i class="fa fa-user-times text-danger"></i> Absentee & Follow-up List
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Select Criteria Card for Reports (opens dynamically below on click) -->
        <div class="sc-card" id="report_filter_card" style="display: none;">
            <div class="sc-card-header">
                <h3 class="sc-card-title">
                    <i class="fa fa-search" style="color:#0284c7;"></i> <span id="report_filter_heading">Select Criteria</span>
                </h3>
                <button type="button" class="btn btn-default btn-sm" style="border-radius: 8px;" onclick="closeReportFilter()"><i class="fa fa-times"></i> Close</button>
            </div>
            <div class="box-body" style="padding: 20px;">
                <form id="ajax_report_form" class="row">
                    <input type="hidden" id="rep_type_input" value="ptm_attendance">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label-custom">PTM Meeting <small class="req text-danger">*</small></label>
                            <select id="rep_ptm_id" class="form-control-custom" required>
                                <option value="">Select PTM Meeting</option>
                                <?php if (!empty($ptm_list)) {
                                    foreach ($ptm_list as $p_opt) { ?>
                                        <option value="<?php echo $p_opt['id']; ?>"><?php echo htmlspecialchars($p_opt['title']); ?> (<?php echo date('d-M-Y', strtotime($p_opt['ptm_date'])); ?>)</option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label-custom"><?php echo $this->lang->line('class'); ?></label>
                            <select id="rep_class_id" name="class_id[]" class="form-control select2" multiple="multiple">
                                <?php if (!empty($classlist)) {
                                    foreach ($classlist as $cls) { ?>
                                        <option value="<?php echo $cls['id']; ?>"><?php echo $cls['class']; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label-custom"><?php echo $this->lang->line('section'); ?></label>
                            <select id="rep_section_id" name="section_id[]" class="form-control select2" multiple="multiple">
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 text-right" style="margin-top: 10px;">
                        <button type="submit" class="btn btn-sc-primary" id="btn_fetch_report">
                            <i class="fa fa-search"></i> Generate Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Report Results Display Area (Loaded below dynamically) -->
        <div id="report_results_container" style="display: none; margin-bottom: 24px;"></div>
    </section>
</div>

<!-- Right Sidebar for Performance Snapshot -->
<div id="ptmSnapshotSidebar" class="ptm-sidebar">
    <div class="ptm-sidebar-header">
        <h4><i class="fa fa-line-chart" style="color:#0284c7;"></i> Performance Snapshot</h4>
        <div class="drawer-close" onclick="closeSnapshotNav()" style="cursor:pointer;font-size:20px;">&times;</div>
    </div>
    <div class="ptm-sidebar-content" id="snapshot_content"></div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();

        // Reports click handler - opens filter card below
        $('.report-link-btn').click(function(e) {
            e.preventDefault();
            $('.report-link-btn').removeClass('active');
            $(this).addClass('active');

            var repType = $(this).data('type');
            var repTitle = $(this).data('title');

            $('#rep_type_input').val(repType);
            $('#report_filter_heading').text(repTitle + ' : Select Criteria');
            
            $('#report_filter_card').slideDown(250);
            $('html, body').animate({
                scrollTop: $("#report_filter_card").offset().top - 80
            }, 300);
        });

        // Auto-select first report on page load
        $('.report-link-btn:first').trigger('click');

        // Class change in report filter to load sections
        $('#rep_class_id').change(function () {
            var class_ids = $(this).val();
            $('#rep_section_id').html('');
            
            if (class_ids && class_ids.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('admin/ptmreports/get_sections_multi'); ?>",
                    data: {'class_ids': class_ids},
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function (i, obj) {
                            $('#rep_section_id').append($('<option>').text(obj.section).attr('value', obj.section_id));
                        });
                        $('#rep_section_id').trigger('change');
                    }
                });
            }
        });

        // Generate Report AJAX Submit
        $('#ajax_report_form').submit(function(e) {
            e.preventDefault();
            var ptm_id = $('#rep_ptm_id').val();
            if (!ptm_id) {
                errorMsg("Please select a PTM Meeting first.");
                return false;
            }

            var $btn = $('#btn_fetch_report');
            $btn.button('loading');
            $('#report_results_container').show().html('<div class="text-center" style="padding: 40px;"><i class="fa fa-spinner fa-spin fa-3x" style="color:#0284c7;"></i><p style="margin-top:10px;font-weight:600;color:#64748b;">Generating report...</p></div>');

            $.ajax({
                url: "<?php echo site_url('admin/ptmreports/get_report_data'); ?>",
                type: "POST",
                data: {
                    report_type: $('#rep_type_input').val(),
                    ptm_id: ptm_id,
                    class_ids: $('#rep_class_id').val(),
                    section_ids: $('#rep_section_id').val()
                },
                dataType: "json",
                success: function(res) {
                    $btn.button('reset');
                    if (res.status === 1) {
                        $('#report_results_container').html(res.html);
                        $('html, body').animate({
                            scrollTop: $("#report_results_container").offset().top - 80
                        }, 300);
                    } else {
                        $('#report_results_container').html('<div class="alert alert-danger">' + res.message + '</div>');
                    }
                },
                error: function() {
                    $btn.button('reset');
                    $('#report_results_container').html('<div class="alert alert-danger">Error connecting to server. Please try again.</div>');
                }
            });
        });

        // View snapshot click event inside dynamic report results
        $(document).on('click', '.view-snapshot', function(e){
            e.preventDefault();
            var student_id = $(this).data('student');
            var sess_id = $(this).data('session');
            var ptm_id = $('#rep_ptm_id').val() || 0;
            
            $('#snapshot_content').html('<div class="text-center" style="padding:40px;"><i class="fa fa-spinner fa-spin fa-2x" style="color:#0284c7;"></i></div>');
            openSnapshotNav();
            
            $.ajax({
                url: "<?php echo site_url('admin/ptm/get_student_snapshot') ?>",
                type: "POST",
                data: {student_id: student_id, student_session_id: sess_id, ptm_id: ptm_id},
                dataType: 'json',
                success: function (res) {
                    $('#snapshot_content').html(res.page);
                }
            });
        });
    });

    function closeReportFilter() {
        $('#report_filter_card, #report_results_container').slideUp(200);
        $('.report-link-btn').removeClass('active');
    }

    function openSnapshotNav() {
        if ($(window).width() < 768) {
            document.getElementById("ptmSnapshotSidebar").style.width = "100%";
        } else {
            document.getElementById("ptmSnapshotSidebar").style.width = "750px";
        }
    }

    function closeSnapshotNav() {
        document.getElementById("ptmSnapshotSidebar").style.width = "0";
    }
</script>
