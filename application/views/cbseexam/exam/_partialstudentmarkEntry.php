<style>
    /* Premium Grid UI Styles */
    .premium-grid-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #eaeaea;
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
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e9ecef;
        border-top: none;
        padding: 12px 15px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .premium-table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .premium-table tbody tr:hover {
        background-color: #f1f8ff;
    }
    
    .premium-table td {
        padding: 8px 15px;
        vertical-align: middle;
        border-top: 1px solid #f1f3f5;
        font-size: 13px;
        color: #333;
    }
    
    /* Excel-like Input Cells */
    .excel-cell-wrapper {
        position: relative;
    }
    
    .excel-cell, .noteinput {
        width: 100%;
        border: 1px solid transparent;
        background: transparent;
        padding: 8px 12px;
        border-radius: 4px;
        transition: all 0.2s ease;
        box-shadow: none;
        font-weight: 500;
    }
    
    .excel-cell:not([readonly]):hover, .noteinput:not([readonly]):hover {
        border-color: #dce1e7;
        background: #fdfdfd;
    }
    
    .excel-cell:not([readonly]):focus, .noteinput:not([readonly]):focus {
        border-color: #007bff;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        outline: none;
        position: relative;
        z-index: 5;
    }
    
    .excel-cell[readonly] {
        background-color: #f8f9fa;
        color: #adb5bd;
        cursor: not-allowed;
        border-color: transparent !important;
        box-shadow: none !important;
    }
    
    .mark-invalid {
        border-color: #dc3545 !important;
        background-color: #ffe6e6 !important;
    }
    
    /* Checkbox Styling */
    .custom-checkbox-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #666;
        cursor: pointer;
        margin-bottom: 5px;
        user-select: none;
    }
    
    .custom-checkbox-label input[type="checkbox"] {
        margin: 0;
        accent-color: #007bff;
        width: 14px;
        height: 14px;
        cursor: pointer;
    }
    
    /* Header Action Bar */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #eaeaea;
    }
    
    .action-bar .dropify-wrapper {
        height: 40px !important;
        min-height: 40px !important;
        border-radius: 4px;
        border-color: #dce1e7;
    }
    
    .btn-premium {
        border-radius: 4px;
        font-weight: 600;
        padding: 8px 16px;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .btn-premium:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .student-avatar-placeholder {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e9ecef;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-weight: 600;
        font-size: 12px;
        margin-right: 10px;
    }
    
    .student-name-col {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: #2b3445;
    }
    
    .badge-class {
        background: #e1f5fe;
        color: #0288d1;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    /* Empty State */
    .premium-empty-state {
        padding: 40px;
        text-align: center;
        background: #fff;
        border-radius: 8px;
        border: 1px dashed #dce1e7;
        color: #6c757d;
    }
</style>

<div class="action-bar">   
    <div style="flex: 1; max-width: 500px;"> 
        <form method="POST" enctype="multipart/form-data" id="fileUploadForm" style="display: flex; gap: 10px; align-items: center;">
            <div style="flex: 1;">
                <input id="my-file-selector" data-height="40" class="dropify" type="file">
            </div>
            <button type="submit" class="btn btn-primary btn-premium" id="btnSubmit">
                <i class="fa fa-upload"></i> <?php echo $this->lang->line('submit') ?>
            </button>
        </form>
    </div>  

    <div> 
        <a class="btn btn-outline-primary btn-premium" href="<?php echo site_url('cbseexam/exam/exportformat') ?>" target="_blank" style="background: #fff; border: 1px solid #007bff; color: #007bff;">
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
                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                            <th><?php echo $this->lang->line('roll_no'); ?></th>
                            <th><?php echo $this->lang->line('student_name'); ?></th>
                            <th><?php echo $this->lang->line('class'); ?></th>
                            <th><?php echo $this->lang->line('gender'); ?></th>
                            
                            <?php foreach ($exam_assessment_types as $key => $value) {
                                if (!is_null($value->cbse_exam_timetable_assessment_type_id)) {
                                    $value = (array)$value;
                                    $code = $value['code'] ? " (".$value['code'].")" : '';
                            ?>
                                    <th style="min-width: 150px;">
                                        <?php echo $value['name'] . $code; ?>
                                        <div style="font-size: 10px; color: #888; font-weight: normal; margin-top: 2px;">
                                            Max: <?php echo $value['maximum_marks']; ?>
                                        </div>
                                    </th>
                            <?php } } ?>
                            
                            <th style="min-width: 100px;">Percentage</th>
                            <th style="min-width: 150px;"><?php echo $this->lang->line('note') ?></th>
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
});
</script>