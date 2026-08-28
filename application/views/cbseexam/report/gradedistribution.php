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
.report-btn-excel:hover { background: #d1fae5 !important; border-color: #6ee7b7 !important; }
.report-btn-print { background: #eff6ff !important; border-color: #bfdbfe !important; color: #1d4ed8 !important; }
.report-btn-print:hover { background: #dbeafe !important; border-color: #93c5fd !important; }

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
.status-stat-icon.icon-teal { background: #f0fdfa; color: #114B5F; border: 1px solid #99f6e4; }
.status-stat-icon.icon-green { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
.status-stat-icon.icon-amber { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
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
.report-quick-search {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12.5px;
    height: 34px;
    width: 220px;
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
    padding: 10px 12px;
}
.custom-table tbody td {
    padding: 10px 12px;
    font-size: 12.5px;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
    vertical-align: middle;
}
.custom-table tbody tr:hover td { background: #f8fafc; }

.grade-badge {
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    display: inline-block;
}
.grade-a { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.grade-b { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
.grade-c { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
.grade-d { background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }
.grade-e { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
</style>

<div class="content-wrapper" style="padding: 15px 20px;">
    <div class="report-page-header">
        <h1 class="report-page-title">
            <i class="fa fa-bar-chart"></i> Grade & Pass/Fail Analysis Report
        </h1>
    </div>

    <section class="content" style="padding: 0;">
        <?php $this->load->view('cbseexam/report/_cbsereport'); ?>
        <div class="row">
            <div class="col-md-12">
                <!-- Search Box -->
                <div class="report-card">
                    <div class="report-card-header">
                        <h3 class="report-card-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="report-card-body">
                        <form role="form" action="<?php echo site_url('cbseexam/report/gradedistribution') ?>" method="post" class="row">
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
                if (isset($dist_report) && !empty($dist_report['exam'])) {
                    $summary = $dist_report['summary'];
                    $grades = $dist_report['grades'];
                    $classes_dist = $dist_report['classes'];
                    $subjects_dist = $dist_report['subjects'];
                    $exam_detail = $dist_report['exam'];
                ?>
                    <!-- Summary Stats -->
                    <div class="status-stats-grid">
                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-teal">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Students Appeared</div>
                                <div class="status-stat-value"><?php echo $summary['total_students']; ?></div>
                                <div class="status-stat-subtext">Avg Score: <?php echo $summary['overall_average_percent']; ?>%</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-green">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Overall Pass Rate</div>
                                <div class="status-stat-value text-success"><?php echo $summary['overall_pass_percent']; ?>%</div>
                                <div class="status-stat-subtext"><?php echo $summary['total_passed']; ?> Passed Students</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-amber">
                                <i class="fa fa-times-circle"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Failed Students</div>
                                <div class="status-stat-value text-danger"><?php echo $summary['total_failed']; ?></div>
                                <div class="status-stat-subtext">&lt; 33% Minimum Passing</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-purple">
                                <i class="fa fa-star"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Distinctions</div>
                                <div class="status-stat-value"><?php echo $summary['total_distinctions']; ?></div>
                                <div class="status-stat-subtext">&ge; 75% Marks Scored</div>
                            </div>
                        </div>
                    </div>

                    <!-- Class-Wise Grade Matrix -->
                    <div class="report-card">
                        <div class="report-card-header">
                            <div class="report-card-title">
                                <i class="fa fa-table"></i> <?php echo $exam_detail['name']; ?> - Class Performance & Grade Matrix
                            </div>
                            <div class="report-header-actions">
                                <input type="text" id="class_dist_search" class="report-quick-search" placeholder="Search class...">
                                <button type="button" class="report-btn report-btn-print" onclick="printDiv('div_print_class_matrix')"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                                <button type="button" class="report-btn report-btn-excel" onclick="fnExcelReportClassMatrix()"><i class="fa fa-file-excel-o"></i> <?php echo $this->lang->line('download_excel'); ?></button>
                            </div>
                        </div>

                        <div class="report-card-body" id="div_print_class_matrix" style="padding: 20px;">
                            <div class="table-responsive">
                                <table class="table custom-table" id="class_matrix_table">
                                    <thead>
                                        <tr>
                                            <th>Class (Section)</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Passed</th>
                                            <th class="text-center">Failed</th>
                                            <th class="text-center">Pass %</th>
                                            <th class="text-center">Avg %</th>
                                            <th class="text-center">Highest %</th>
                                            <?php foreach ($grades as $g) { ?>
                                                <th class="text-center"><?php echo $g['name']; ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($classes_dist)) {
                                            foreach ($classes_dist as $cd) {
                                        ?>
                                                <tr class="class-matrix-row" data-cname="<?php echo strtolower($cd['class_name']); ?>">
                                                    <td><strong><?php echo $cd['class_name']; ?></strong></td>
                                                    <td class="text-center"><?php echo $cd['total_students']; ?></td>
                                                    <td class="text-center text-success font-weight-bold"><?php echo $cd['passed']; ?></td>
                                                    <td class="text-center text-danger"><?php echo $cd['failed']; ?></td>
                                                    <td class="text-center">
                                                        <strong style="color: <?php echo ($cd['pass_percent'] >= 80) ? '#059669' : (($cd['pass_percent'] >= 50) ? '#d97706' : '#dc2626'); ?>;">
                                                            <?php echo $cd['pass_percent']; ?>%
                                                        </strong>
                                                    </td>
                                                    <td class="text-center"><?php echo $cd['average_percent']; ?>%</td>
                                                    <td class="text-center text-primary"><?php echo $cd['highest_percent']; ?>%</td>
                                                    <?php foreach ($grades as $g) { 
                                                        $gname = $g['name'];
                                                        $gcount = isset($cd['grade_counts'][$gname]) ? $cd['grade_counts'][$gname] : 0;
                                                    ?>
                                                        <td class="text-center">
                                                            <?php if ($gcount > 0) { ?>
                                                                <span class="grade-badge <?php echo ($gname == 'A1' || $gname == 'A2') ? 'grade-a' : (($gname == 'B1' || $gname == 'B2') ? 'grade-b' : (($gname == 'C1' || $gname == 'C2') ? 'grade-c' : 'grade-e')); ?>">
                                                                    <?php echo $gcount; ?>
                                                                </span>
                                                            <?php } else { echo "<span style='color:#cbd5e1;'>-</span>"; } ?>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                            <tr><td colspan="15" class="text-center text-muted">No student results found for this exam.</td></tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Subject-Wise Analytics -->
                    <div class="report-card">
                        <div class="report-card-header">
                            <div class="report-card-title">
                                <i class="fa fa-book"></i> Subject-Wise Performance & Pass Rates
                            </div>
                        </div>
                        <div class="report-card-body" style="padding: 20px;">
                            <div class="table-responsive">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th class="text-center">Appeared</th>
                                            <th class="text-center">Passed</th>
                                            <th class="text-center">Failed</th>
                                            <th class="text-center">Pass %</th>
                                            <th class="text-center">Subject Average</th>
                                            <th class="text-center">Highest Marks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($subjects_dist)) {
                                            foreach ($subjects_dist as $sd) {
                                        ?>
                                                <tr>
                                                    <td><strong><?php echo $sd['subject_name']; ?></strong> <?php if (!empty($sd['subject_code'])) { echo "<span style='color:#64748b; font-size:11px;'>(" . $sd['subject_code'] . ")</span>"; } ?></td>
                                                    <td class="text-center"><?php echo $sd['appeared']; ?></td>
                                                    <td class="text-center text-success"><?php echo $sd['passed']; ?></td>
                                                    <td class="text-center text-danger"><?php echo $sd['failed']; ?></td>
                                                    <td class="text-center">
                                                        <strong style="color: <?php echo ($sd['pass_percent'] >= 80) ? '#059669' : (($sd['pass_percent'] >= 50) ? '#d97706' : '#dc2626'); ?>;">
                                                            <?php echo $sd['pass_percent']; ?>%
                                                        </strong>
                                                    </td>
                                                    <td class="text-center"><?php echo $sd['avg_percent']; ?>%</td>
                                                    <td class="text-center text-primary font-weight-bold"><?php echo $sd['highest_marks']; ?> / <?php echo $sd['max_marks']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                            <tr><td colspan="7" class="text-center text-muted">No subject data found.</td></tr>
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

        $('#class_dist_search').on('keyup', function() {
            var val = $(this).val().toLowerCase().trim();
            $('.class-matrix-row').each(function() {
                var cname = $(this).data('cname') || '';
                if (val === '' || cname.indexOf(val) > -1) $(this).show(); else $(this).hide();
            });
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
        var head = "<html><head>" + $("head").html() + "<style>.action-link-btn, .report-quick-search { display: none !important; }</style></head>";
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

    function fnExcelReportClassMatrix() {
        var tab_text = "<table border='1px'><tr bgcolor='#f8fafc'>";
        var tab = document.getElementById('class_matrix_table');
        for (var j = 0; j < tab.rows.length; j++) { tab_text += tab.rows[j].innerHTML + "</tr>"; }
        tab_text += "</table>";
        var sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        return sa;
    }
</script>
