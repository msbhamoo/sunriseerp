<style>
    /* Premium Grid UI Styles - Dashboard2 Theme */
    .premium-grid-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.02);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        margin-top: 15px;
    }
    
    .premium-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    
    .premium-table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        border-top: none;
        padding: 12px 16px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .premium-table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .premium-table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .premium-table td {
        padding: 10px 16px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
        font-size: 13px;
        color: #0f172a;
    }
    
    /* Excel-like Input Cells */
    .excel-cell-wrapper {
        position: relative;
    }
    
    .excel-cell, .noteinput {
        width: 100%;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 6px 10px;
        border-radius: 6px;
        transition: all 0.2s ease;
        box-shadow: none;
        font-weight: 500;
        font-size: 13px;
        color: #0f172a;
    }
    
    .excel-cell:not([readonly]):hover, .noteinput:not([readonly]):hover {
        border-color: #cbd5e1;
        background: #ffffff;
    }
    
    .excel-cell:not([readonly]):focus, .noteinput:not([readonly]):focus {
        border-color: #0284c7;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        outline: none;
        position: relative;
        z-index: 5;
    }
    
    .excel-cell[readonly] {
        background-color: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
        border-color: #e2e8f0 !important;
        box-shadow: none !important;
    }
    
    .mark-invalid {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
        color: #dc2626 !important;
    }

    /* Live grade badge shown next to each entered mark */
    .excel-cell-wrapper { display: flex; align-items: center; gap: 6px; }
    .excel-cell-wrapper .excel-cell,
    .excel-cell-wrapper .noteinput { flex: 1; }
    .mark-grade-badge {
        display: none;
        flex: none;
        min-width: 34px;
        text-align: center;
        font-size: 11px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        letter-spacing: 0.3px;
    }
    .mark-grade-badge.grade-fail {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }
    
    /* Checkbox Styling */
    .custom-checkbox-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        margin-bottom: 5px;
        user-select: none;
    }
    
    .custom-checkbox-label input[type="checkbox"] {
        margin: 0;
        accent-color: #0284c7;
        width: 14px;
        height: 14px;
        cursor: pointer;
    }
    
    /* Header Action Bar - Compact Single Line Toolbar */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
        padding: 10px 16px;
        border-radius: 10px;
        margin-bottom: 15px;
        border: 1px solid #e2e8f0;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .action-bar .dropify-wrapper {
        height: 36px !important;
        min-height: 36px !important;
        border-radius: 8px !important;
        border: 1px dashed #cbd5e1 !important;
        background: #ffffff !important;
        padding: 0 10px !important;
        transition: all 0.2s ease !important;
    }
    
    .action-bar .dropify-wrapper:hover {
        border-color: #0284c7 !important;
    }
    
    .action-bar .dropify-wrapper .dropify-message p {
        font-size: 11px !important;
        margin: 0 !important;
        color: #64748b !important;
        font-weight: 600 !important;
        line-height: 36px !important;
    }
    
    .action-bar .dropify-wrapper .dropify-message span.file-icon {
        font-size: 14px !important;
    }

    .btn-premium {
        border-radius: 8px;
        font-weight: 700;
        padding: 0 16px;
        height: 36px;
        transition: all 0.2s;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
    }
    
    .btn-premium:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .student-avatar-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #e0f2fe;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #0284c7;
        font-weight: 800;
        font-size: 13px;
        margin-right: 10px;
    }
    
    .student-name-col {
        display: flex;
        align-items: center;
        font-weight: 700;
        color: #0f172a;
    }
    
    .badge-class {
        background: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }
    
    /* Empty State */
    .premium-empty-state {
        padding: 50px 20px;
        text-align: center;
        background: #ffffff;
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
        color: #64748b;
    }
</style>

<div id="header_action_template" style="display:none;">
    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">   
        <form method="POST" enctype="multipart/form-data" id="fileUploadForm" style="display: flex; gap: 8px; align-items: center; margin: 0;">
            <div style="width: 220px; position: relative;">
                <input id="my-file-selector" name="file" data-height="34" class="dropify" type="file" data-allowed-file-extensions="xls xlsx csv" placeholder="Select Excel File">
            </div>
            <button type="submit" class="btn btn-sc-primary btn-premium" id="btnSubmit" style="height: 34px; white-space: nowrap; padding: 0 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa fa-upload"></i> <?php echo $this->lang->line('submit') ?>
            </button>
        </form>
        <a class="btn btn-premium" href="<?php echo site_url('cbseexam/exam/exportformat') ?>" target="_blank" style="height: 34px; display: inline-flex; align-items: center; gap: 6px; background: #ffffff; border: 1px solid #cbd5e1; color: #0284c7; border-radius: 8px; font-weight: 700; padding: 0 14px; font-size: 12px;">
            <i class="fa fa-download"></i> <?php echo $this->lang->line('export_sample'); ?>
        </a>
    </div>
</div>  

<form method="post" action="<?php echo site_url('cbseexam/exam/entrymarks') ?>" id="assign_form1111">
    <input type="hidden" name="cbse_exam_timetable_id" value="<?php echo $timetable_id; ?>">
    
    <?php if (isset($resultlist) && !empty($resultlist)) { ?>
        <div class="premium-grid-container">
            <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
                <table class="table premium-table">
                    <thead>
                        <tr>
                            <th class="sortable" style="cursor:pointer; user-select:none;"><?php echo $this->lang->line('admission_no'); ?> <i class="fa fa-sort text-muted pull-right"></i></th>
                            <th class="sortable" style="cursor:pointer; user-select:none;"><?php echo $this->lang->line('roll_no'); ?> <i class="fa fa-sort text-muted pull-right"></i></th>
                            <th class="sortable" style="cursor:pointer; user-select:none;"><?php echo $this->lang->line('student_name'); ?> <i class="fa fa-sort text-muted pull-right"></i></th>
                            <th class="sortable" style="cursor:pointer; user-select:none;"><?php echo $this->lang->line('class'); ?> <i class="fa fa-sort text-muted pull-right"></i></th>
                            <th class="sortable" style="cursor:pointer; user-select:none;"><?php echo $this->lang->line('gender'); ?> <i class="fa fa-sort text-muted pull-right"></i></th>
                            
                            <?php foreach ($exam_assessment_types as $key => $value) {
                                if (!is_null($value->cbse_exam_timetable_assessment_type_id)) {
                                    $value = (array)$value;
                                    $code = $value['code'] ? " (".$value['code'].")" : '';
                            ?>
                                    <th class="sortable" style="min-width: 150px; cursor:pointer; user-select:none;">
                                        <?php echo $value['name'] . $code; ?> <i class="fa fa-sort text-muted pull-right"></i>
                                        <div style="font-size: 10px; color: #888; font-weight: normal; margin-top: 2px;">
                                            Max: <?php echo $value['maximum_marks']; ?>
                                        </div>
                                    </th>
                            <?php } } ?>
                            
                            <th class="sortable" style="min-width: 100px; cursor:pointer; user-select:none;">Percentage <i class="fa fa-sort text-muted pull-right"></i></th>
                            <th class="sortable" style="min-width: 150px; cursor:pointer; user-select:none;"><?php echo $this->lang->line('note') ?> <i class="fa fa-sort text-muted pull-right"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultlist as $student) { ?>
                            <tr class="excel-row" data-adm_no="<?php echo htmlspecialchars($student['admission_no'], ENT_QUOTES); ?>">
                                <input type="hidden" name="exam_student_id[]" value="<?php echo $student['exam_student_id']; ?>">

                                <td><span style="color: #6c757d; font-family: monospace;"><?php echo $student['admission_no']; ?></span></td>
                                <td>
                                    <span style="color: #6c757d; font-family: monospace;">
                                        <?php echo ($exam['use_exam_roll_no'] != 0) ? $student['exam_roll_no'] : (($student['roll_no'] != 0) ? $student['roll_no'] : '-'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="student-name-col">
                                        <div class="student-avatar-placeholder">
                                            <?php echo strtoupper(substr($student['firstname'], 0, 1)); ?>
                                        </div>
                                        <?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                    </div>
                                </td>
                                <td><span class="badge-class"><?php echo ($student['class_name'] . " (" . $student['section_name'] . ")"); ?></span></td>
                                <td>
                                    <?php if($student['gender']){ 
                                        $gender = strtolower($student['gender']);
                                        $icon = $gender == 'male' ? '<i class="fa fa-mars text-primary"></i>' : ($gender == 'female' ? '<i class="fa fa-venus text-danger"></i>' : '');
                                        echo $icon . ' ' . ucfirst($this->lang->line($gender)); 
                                    } ?> 
                                </td>
                                
                                <?php
                                $note="";
                                foreach ($exam_assessment_types as $key => $value) {
                                    $remark_row_id=$value->id;
                                    if(array_key_exists($remark_row_id,$student['marks'])){
                                        $note = ($student['marks'][$remark_row_id]['note']) ? $student['marks'][$remark_row_id]['note'] : "";
                                    }
                                   
                                    if (!is_null($value->cbse_exam_timetable_assessment_type_id)) {
                                        $value = (array)$value;
                                        $absent_status = (!empty($student['marks'][$value['id']]) && $student['marks'][$value['id']]['is_absent']) ? 1 : 0;
                                ?>
                                        <td>
                                            <label class="custom-checkbox-label">
                                                <input type="checkbox" name="absent[<?php echo $student['exam_student_id']; ?>][<?php echo $value['id'] ?>]" value="1" <?php echo ($absent_status) ? "checked='checked'" : ""; ?> class="check_absent attendance_chk"> 
                                                <span><?php echo $this->lang->line('absent'); ?></span>
                                            </label>
                                            
                                            <input type="hidden" value="<?php echo $value['cbse_exam_timetable_assessment_type_id'];?>" name="mark[<?php echo $student['exam_student_id']; ?>][<?php echo $value['id'] ?>][cbse_exam_timetable_assessment_type]">
                                            
                                            <div class="excel-cell-wrapper">
                                                <input type="number" max="<?php echo $value['maximum_marks']; ?>" min="0" data-marks="<?php echo $value['maximum_marks']; ?>" class="marksssss_<?php echo $key+1 ;?> mark excel-cell" name="mark[<?php echo $student['exam_student_id']; ?>][<?php echo $value['id'] ?>][marks]" value="<?php if (!empty($student['marks'][$value['id']]['marks'])) { echo $student['marks'][$value['id']]['marks'];} ?>" step="any" placeholder="Enter marks..." <?php echo ($absent_status) ? "readonly='readonly'" : ""; ?>>
                                                <span class="mark-grade-badge"></span>
                                            </div>
                                        </td>
                                <?php
                                    }
                                }
                                ?>
                                <td class="percentage-col" style="vertical-align: middle; font-weight: 600; color: #0288d1; font-size: 14px; text-align: center;">
                                    <span class="row-percentage">0.00%</span>
                                </td>
                                <td>
                                    <div class="excel-cell-wrapper">
                                        <input type="text" class="note noteinput" name="exam_student_note[<?php echo $student['exam_student_id']; ?>]" value="<?php echo htmlspecialchars($note, ENT_QUOTES); ?>" placeholder="Add note...">
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <?php if ($this->rbac->hasPrivilege('cbse_exam_marks', 'can_edit')) { ?>
                <div style="background: #f8f9fa; padding: 15px 20px; border-top: 1px solid #eaeaea; text-align: right;">
                    <button type="submit" class="btn btn-success btn-premium" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving...">
                        <i class="fa fa-check"></i> <?php echo $this->lang->line('save_marks'); ?>
                    </button>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="premium-empty-state">
            <i class="fa fa-folder-open-o" style="font-size: 48px; color: #dce1e7; margin-bottom: 15px;"></i>
            <h4><?php echo $this->lang->line('no_record_found'); ?></h4>
            <p>No students found for this examination.</p>
        </div>
    <?php } ?>
</form>

<script>
$(document).ready(function() {
    if ($.fn.dropify) {
        $('.dropify').dropify();
    }

    // ----- Live grade badges (CBSE grade bands for this exam) -----
    var gradeRanges = <?php echo json_encode(isset($grade_ranges) ? $grade_ranges : array()); ?>;

    function gradeForPercent(pct) {
        for (var i = 0; i < gradeRanges.length; i++) {
            var g = gradeRanges[i];
            if (pct >= parseFloat(g.minimum_percentage) && pct <= parseFloat(g.maximum_percentage)) {
                return g.name;
            }
        }
        return '';
    }

    function updateMarkGrade($cell) {
        var $badge = $cell.closest('.excel-cell-wrapper').find('.mark-grade-badge');
        if (!gradeRanges.length) { $badge.hide(); return; }
        var maxMarks = parseFloat($cell.data('marks'));
        var val = parseFloat($cell.val());
        if ($cell.prop('readonly') || isNaN(val) || isNaN(maxMarks) || maxMarks <= 0 || $cell.val() === '') {
            $badge.hide().text('');
            return;
        }
        var pct = (val / maxMarks) * 100;
        var name = gradeForPercent(pct);
        if (!name) { $badge.hide().text(''); return; }
        // Below the CBSE 33% pass line is styled as a fail band.
        $badge.text(name).toggleClass('grade-fail', pct < 33).show();
    }

    // Keyboard navigation
    $('.excel-cell').on('keydown', function(e) {
        var $this = $(this);
        var $row = $this.closest('.excel-row');
        var $td = $this.closest('td');
        
        // Find which column index this is among the excel-cells in this row
        var cellsInRow = $row.find('.excel-cell');
        var index = cellsInRow.index(this);
        
        var $nextCell = null;

        switch(e.which) {
            case 37: // Left
                if (index > 0) {
                    $nextCell = cellsInRow.eq(index - 1);
                }
                break;
            case 38: // Up
                var $prevRow = $row.prev('.excel-row');
                if ($prevRow.length) {
                    var prevCells = $prevRow.find('.excel-cell');
                    if (prevCells.length > index) {
                        $nextCell = prevCells.eq(index);
                    }
                }
                break;
            case 39: // Right
                if (index < cellsInRow.length - 1) {
                    $nextCell = cellsInRow.eq(index + 1);
                }
                break;
            case 40: // Down
            case 13: // Enter
                e.preventDefault();
                var $nextRow = $row.next('.excel-row');
                if ($nextRow.length) {
                    var nextCells = $nextRow.find('.excel-cell');
                    if (nextCells.length > index) {
                        $nextCell = nextCells.eq(index);
                    }
                }
                break;
        }

        if ($nextCell && $nextCell.length && !$nextCell.prop('readonly')) {
            $nextCell.focus();
        }
    });

    // Select all text on focus for quick overwrite
    $('.excel-cell').on('focus', function() {
        if (!$(this).prop('readonly')) {
            $(this).select();
        }
    });

    // Validation for maximum marks and trigger calculation
    $('.excel-cell').on('input blur', function() {
        var $this = $(this);
        var maxMarks = parseFloat($this.data('marks'));
        var val = parseFloat($this.val());
        
        if (!isNaN(val) && val > maxMarks) {
            $this.addClass('mark-invalid');
            errorMsg("Max marks allowed is " + maxMarks);
        } else if (val < 0) {
            $this.addClass('mark-invalid');
            errorMsg("Marks cannot be negative");
        } else {
            $this.removeClass('mark-invalid');
        }

        updateMarkGrade($this);
        calculateRowPercentage($this.closest('.excel-row'));
    });

    // Handle Absent Checkbox toggle
    $('.check_absent').on('change', function() {
        var $this = $(this);
        var $cellWrapper = $this.closest('td').find('.excel-cell');
        if ($this.is(':checked')) {
            $cellWrapper.prop('readonly', true);
            $cellWrapper.val('');
            $cellWrapper.removeClass('mark-invalid');
        } else {
            $cellWrapper.prop('readonly', false);
        }

        updateMarkGrade($cellWrapper);
        calculateRowPercentage($this.closest('.excel-row'));
    });

    // Calculate percentage function
    function calculateRowPercentage($row) {
        var totalObtained = 0;
        var totalMax = 0;
        
        $row.find('.excel-cell').each(function() {
            var $cell = $(this);
            var maxMarks = parseFloat($cell.data('marks'));
            var val = parseFloat($cell.val());
            
            if (!isNaN(maxMarks)) {
                totalMax += maxMarks;
            }
            if (!isNaN(val) && !$cell.prop('readonly') && !$cell.hasClass('mark-invalid')) {
                totalObtained += val;
            }
        });
        
        var percentage = 0;
        if (totalMax > 0) {
            percentage = (totalObtained / totalMax) * 100;
        }
        
        $row.find('.row-percentage').text(percentage.toFixed(2) + '%');
    }

    // Initial calculation on load
    $('.excel-row').each(function() {
        calculateRowPercentage($(this));
    });
    $('.excel-cell').each(function() {
        updateMarkGrade($(this));
    });

    // Auto-save logic (if button clicked) - intercepts the regular form submission
    $('#assign_form1111').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('#load');
        
        $btn.button('loading');
        
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 1) {
                    successMsg(response.message || 'Marks saved successfully');
                } else if (response.status === 0) {
                    errorMsg(response.message || 'Failed to save marks');
                }
                $btn.button('reset');
            },
            error: function() {
                errorMsg('An error occurred while saving.');
                $btn.button('reset');
            }
        });
    });

    // Column Sorting Handler for Premium Table
    $(document).on('click', '.premium-table thead th.sortable', function() {
        var $th = $(this);
        var colIndex = $th.index();
        var $table = $th.closest('table');
        var $tbody = $table.find('tbody');
        var rows = $tbody.find('tr.excel-row').get();
        
        var dir = $th.hasClass('asc') ? 'desc' : 'asc';
        
        $table.find('thead th').removeClass('asc desc').find('i.fa-sort-asc, i.fa-sort-desc').removeClass('fa-sort-asc fa-sort-desc').addClass('fa-sort');
        $th.removeClass('asc desc').addClass(dir);
        $th.find('i').removeClass('fa-sort fa-sort-asc fa-sort-desc').addClass(dir === 'asc' ? 'fa-sort-asc' : 'fa-sort-desc');

        rows.sort(function(a, b) {
            var aVal = getCellValue(a, colIndex);
            var bVal = getCellValue(b, colIndex);

            var aNum = parseFloat(aVal);
            var bNum = parseFloat(bVal);

            if (!isNaN(aNum) && !isNaN(bNum)) {
                return dir === 'asc' ? aNum - bNum : bNum - aNum;
            }

            return dir === 'asc' ? aVal.toString().localeCompare(bVal.toString()) : bVal.toString().localeCompare(aVal.toString());
        });

        $.each(rows, function(index, row) {
            $tbody.append(row);
        });
    });

    function getCellValue(row, index) {
        var $td = $(row).children('td').eq(index);
        
        // Priority 1: Check if td has input field
        var $input = $td.find('input[type="number"], input[type="text"]');
        if ($input.length && $input.val() !== '') {
            return $input.val().trim();
        }
        
        // Priority 2: Check for row-percentage
        var $perc = $td.find('.row-percentage');
        if ($perc.length) {
            return parseFloat($perc.text().replace('%', '')) || 0;
        }
        
        // Priority 3: Direct text value
        return $td.text().trim();
    }
});
</script>