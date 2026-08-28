<?php $this->load->view('layout/cbseexam_css.php'); ?>

<style type="text/css">
.report-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 18px;
}
.report-page-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: -0.3px;
}
.report-page-title i { color: #114B5F; }
.report-header-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
.report-btn {
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
    white-space: nowrap;
}
.report-btn:hover { background: #f8fafc; color: #0f172a; border-color: #94a3b8; }
.report-btn-primary { background: #114B5F !important; border-color: #114B5F !important; color: #ffffff !important; box-shadow: 0 2px 6px rgba(17, 75, 95, 0.2) !important; }
.report-btn-primary:hover { background: #0c3847 !important; border-color: #0c3847 !important; }
.report-btn-excel { background: #ecfdf5 !important; border-color: #a7f3d0 !important; color: #059669 !important; }
.report-btn-print { background: #eff6ff !important; border-color: #bfdbfe !important; color: #1d4ed8 !important; }

/* Stats Cards */
.status-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}
.status-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
}
.status-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.status-stat-icon.icon-gold { background: #fefce8; color: #ca8a04; border: 1px solid #fef08a; }
.status-stat-icon.icon-teal { background: #f0fdfa; color: #114B5F; border: 1px solid #99f6e4; }
.status-stat-icon.icon-purple { background: #fdf4ff; color: #9333ea; border: 1px solid #f0abfc; }
.status-stat-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 3px;
}
.status-stat-value { font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.1; }
.status-stat-subtext { font-size: 11.5px; color: #64748b; margin-top: 2px; font-weight: 500; }

.report-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    margin-bottom: 20px;
    overflow: hidden;
}
.report-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    flex-wrap: wrap;
    gap: 10px;
}
.report-card-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.report-card-title i { color: #114B5F; }
.report-card-body { padding: 18px 20px; }
.report-form-label {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
    display: block;
}
.report-select {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    height: 38px !important;
    font-size: 13px !important;
    color: #0f172a !important;
    width: 100%;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
}
.custom-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
    padding: 10px 14px;
}
.custom-table tbody td {
    padding: 11px 14px;
    font-size: 12.5px;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
    vertical-align: middle;
}
.custom-table tbody tr:hover td { background: #f8fafc; }

/* Rank Badges */
.rank-badge {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 12px;
}
.rank-1 { background: #fef08a; color: #854d0e; border: 2px solid #eab308; box-shadow: 0 2px 4px rgba(234, 179, 8, 0.3); }
.rank-2 { background: #f1f5f9; color: #475569; border: 2px solid #94a3b8; }
.rank-3 { background: #ffedd5; color: #9a3412; border: 2px solid #f97316; }
.rank-other { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

.podium-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.podium-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 18px;
    text-align: center;
    position: relative;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}
.podium-card.podium-first { border-color: #facc15; background: linear-gradient(180deg, #fefce8 0%, #ffffff 100%); }
.podium-card.podium-second { border-color: #cbd5e1; }
.podium-card.podium-third { border-color: #fdba74; }
.podium-icon { font-size: 28px; margin-bottom: 8px; }
.podium-name { font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 2px; }
.podium-class { font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 8px; }
.podium-score { font-size: 24px; font-weight: 800; color: #114B5F; }
</style>

<div class="content-wrapper" style="padding: 15px 20px;">
    <div class="report-page-header">
        <h1 class="report-page-title">
            <i class="fa fa-trophy"></i> CBSE Exam Top Rankers & Leaderboard
        </h1>
    </div>

    <section class="content" style="padding: 0;">
        <?php $this->load->view('cbseexam/report/_cbsereport'); ?>
        <div class="row">
            <div class="col-md-12">
                <!-- Criteria Form -->
                <div class="report-card">
                    <div class="report-card-header">
                        <h3 class="report-card-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="report-card-body">
                        <form role="form" action="<?php echo site_url('cbseexam/report/toprankers') ?>" method="post" class="row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group mb0">
                                    <label class="report-form-label"><?php echo $this->lang->line('exam'); ?> <small class="text-danger">*</small></label>
                                    <select id="exam_id" name="exam_id" class="form-control report-select select2">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($exams as $exam_key => $exam_value) {
                                        ?>
                                            <option value="<?php echo $exam_value['id'] ?>" <?php
                                                if (set_value('exam_id', isset($exam_id) ? $exam_id : '') == $exam_value['id']) {
                                                    echo "selected=selected";
                                                }
                                            ?>><?php echo $exam_value['name'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('exam_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group mb0">
                                    <label class="report-form-label"><?php echo $this->lang->line('class'); ?></label>
                                    <select id="class_id" name="class_id" class="form-control report-select">
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                        <?php
                                        if (isset($classlist) && !empty($classlist)) {
                                            foreach ($classlist as $class) {
                                        ?>
                                                <option value="<?php echo $class['id'] ?>" <?php
                                                    if (set_value('class_id', isset($class_id) ? $class_id : '') == $class['id']) {
                                                        echo "selected=selected";
                                                    }
                                                ?>><?php echo $class['class'] ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group mb0">
                                    <label class="report-form-label"><?php echo $this->lang->line('section'); ?></label>
                                    <select id="section_id" name="section_id" class="form-control report-select">
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12" style="margin-top: 16px;">
                                <div class="form-group mb0">
                                    <button type="submit" name="search" value="search_filter" class="report-btn report-btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php
                if (isset($rankers_report) && !empty($rankers_report['exam'])) {
                    $summary = $rankers_report['summary'];
                    $school_toppers = $rankers_report['school_toppers'];
                    $class_toppers = $rankers_report['class_toppers'];
                    $subject_toppers = $rankers_report['subject_toppers'];
                    $exam_detail = $rankers_report['exam'];
                ?>
                    <!-- Top 3 Podium Cards -->
                    <?php if (count($school_toppers) >= 3) { ?>
                        <div class="podium-grid">
                            <!-- 2nd Rank -->
                            <div class="podium-card podium-second">
                                <div class="podium-icon">🥈</div>
                                <div class="podium-name"><?php echo $school_toppers[1]['student_name']; ?></div>
                                <div class="podium-class"><?php echo $school_toppers[1]['class_name']; ?> | Roll: <?php echo $school_toppers[1]['roll_no']; ?></div>
                                <div class="podium-score"><?php echo $school_toppers[1]['overall_percent']; ?>%</div>
                                <span class="badge badge-info" style="margin-top: 4px;">2nd Rank</span>
                            </div>

                            <!-- 1st Rank (Champion) -->
                            <div class="podium-card podium-first">
                                <div class="podium-icon">👑 🥇</div>
                                <div class="podium-name" style="font-size: 17px; color: #854d0e;"><?php echo $school_toppers[0]['student_name']; ?></div>
                                <div class="podium-class"><?php echo $school_toppers[0]['class_name']; ?> | Roll: <?php echo $school_toppers[0]['roll_no']; ?></div>
                                <div class="podium-score" style="font-size: 28px; color: #854d0e;"><?php echo $school_toppers[0]['overall_percent']; ?>%</div>
                                <span class="badge badge-warning" style="margin-top: 4px; font-weight: 800;">1st Rank (School Topper)</span>
                            </div>

                            <!-- 3rd Rank -->
                            <div class="podium-card podium-third">
                                <div class="podium-icon">🥉</div>
                                <div class="podium-name"><?php echo $school_toppers[2]['student_name']; ?></div>
                                <div class="podium-class"><?php echo $school_toppers[2]['class_name']; ?> | Roll: <?php echo $school_toppers[2]['roll_no']; ?></div>
                                <div class="podium-score"><?php echo $school_toppers[2]['overall_percent']; ?>%</div>
                                <span class="badge badge-default" style="margin-top: 4px;">3rd Rank</span>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Overall Merit List / Leaderboard -->
                    <div class="report-card">
                        <div class="report-card-header">
                            <div class="report-card-title">
                                <i class="fa fa-trophy"></i> <?php echo $exam_detail['name']; ?> - Merit List & Top Rankers
                            </div>
                            <div class="report-header-actions">
                                <button type="button" class="report-btn report-btn-print" onclick="printDiv('div_print_leaderboard')"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                                <button type="button" class="report-btn report-btn-excel" onclick="fnExcelReportLeaderboard()"><i class="fa fa-file-excel-o"></i> <?php echo $this->lang->line('download_excel'); ?></button>
                            </div>
                        </div>

                        <div class="report-card-body" id="div_print_leaderboard" style="padding: 20px;">
                            <div class="table-responsive">
                                <table class="table custom-table" id="leaderboard_table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 8%;">Rank</th>
                                            <th>Student Name</th>
                                            <th>Admission No</th>
                                            <th>Roll No</th>
                                            <th>Class (Section)</th>
                                            <th>Father Name</th>
                                            <th class="text-center">Marks Obtained</th>
                                            <th class="text-center">Percentage</th>
                                            <th class="text-center">Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($school_toppers)) {
                                            foreach ($school_toppers as $st) {
                                                $rk = $st['rank'];
                                                $rk_class = ($rk == 1) ? 'rank-1' : (($rk == 2) ? 'rank-2' : (($rk == 3) ? 'rank-3' : 'rank-other'));
                                        ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <span class="rank-badge <?php echo $rk_class; ?>"><?php echo $rk; ?></span>
                                                    </td>
                                                    <td><strong><?php echo $st['student_name']; ?></strong></td>
                                                    <td><?php echo $st['admission_no']; ?></td>
                                                    <td><?php echo $st['roll_no']; ?></td>
                                                    <td><?php echo $st['class_name']; ?></td>
                                                    <td><?php echo $st['father_name']; ?></td>
                                                    <td class="text-center"><strong><?php echo $st['grand_obtained']; ?></strong> / <?php echo $st['grand_max']; ?></td>
                                                    <td class="text-center"><strong style="color: #114B5F; font-size: 13.5px;"><?php echo $st['overall_percent']; ?>%</strong></td>
                                                    <td class="text-center"><span class="badge badge-success"><?php echo $st['overall_grade']; ?></span></td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                            <tr><td colspan="9" class="text-center text-muted">No ranker data available.</td></tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Subject Toppers Grid -->
                    <div class="report-card">
                        <div class="report-card-header">
                            <div class="report-card-title">
                                <i class="fa fa-star text-warning"></i> Subject-Wise Highest Scorers
                            </div>
                        </div>
                        <div class="report-card-body" style="padding: 20px;">
                            <div class="table-responsive">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Highest Scorer</th>
                                            <th>Class (Section)</th>
                                            <th class="text-center">Marks Obtained</th>
                                            <th class="text-center">Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($subject_toppers)) {
                                            foreach ($subject_toppers as $s_top) {
                                        ?>
                                                <tr>
                                                    <td><strong><?php echo $s_top['subject_name']; ?></strong> <?php if (!empty($s_top['subject_code'])) { echo "<span style='color:#64748b; font-size:11px;'>(" . $s_top['subject_code'] . ")</span>"; } ?></td>
                                                    <td><strong style="color: #0f172a;"><?php echo $s_top['student_name']; ?></strong></td>
                                                    <td><?php echo $s_top['class_name']; ?></td>
                                                    <td class="text-center"><strong class="text-success"><?php echo $s_top['obtained']; ?></strong> / <?php echo $s_top['max_marks']; ?></td>
                                                    <td class="text-center"><span class="badge badge-primary"><?php echo $s_top['percent']; ?>%</span></td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                            <tr><td colspan="5" class="text-center text-muted">No subject toppers found.</td></tr>
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

<script type="text/javascript">
    $(document).ready(function() {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', isset($section_id) ? $section_id : ''); ?>';
        getSectionByClass(class_id, section_id);

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, 0);
        });
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('all'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj) {
                        var sel = (section_id == obj.section_id || section_id == obj.id) ? "selected" : "";
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        } else {
            $('#section_id').html('<option value=""><?php echo $this->lang->line('all'); ?></option>');
        }
    }

    function printDiv(tagid) {
        let hashid = "#" + tagid;
        var divToPrint = $(hashid).html();
        var head = "<html><head>" + $("head").html() + "<style>.action-link-btn { display: none !important; }</style></head>";
        var allcontent = head + "<body onload='window.print()'>" + divToPrint + "</body></html>";
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        frameDoc.document.write(allcontent);
        frameDoc.document.close();
        setTimeout(function() { window.frames["frame1"].focus(); window.frames["frame1"].print(); frame1.remove(); }, 500);
    }

    function fnExcelReportLeaderboard() {
        var tab_text = "<table border='1px'><tr bgcolor='#f8fafc'>";
        var tab = document.getElementById('leaderboard_table');
        for (var j = 0; j < tab.rows.length; j++) { tab_text += tab.rows[j].innerHTML + "</tr>"; }
        tab_text += "</table>";
        var sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        return sa;
    }
</script>
