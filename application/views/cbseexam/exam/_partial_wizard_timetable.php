<div class="table-responsive">
    <table class="table table-bordered table-hover" id="item_table" style="font-size: 13px; background: #fff; border: 1px solid #eaeaea;">
        <thead style="background: #f8f9fa;">
            <tr>
                <th>Subject <span style="color:red;">*</span></th>
                <th>Assessment Types <span style="color:red;">*</span></th>
                <th>Classes <span style="color:red;">*</span></th>
                <th>Date <span style="color:red;">*</span></th>
                <th>Start Time <span style="color:red;">*</span></th>
                <th>Duration (mins) <span style="color:red;">*</span></th>
                <th>Room No <span style="color:red;">*</span></th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody id="timetable_body">
            <?php
            // If existing timetable data is present, prepare it. Otherwise, use a default empty array with one element.
            $timetable_rows = isset($existing_timetable) && !empty($existing_timetable) ? $existing_timetable : [null];
            foreach ($timetable_rows as $tt_row): 
                $is_existing = $tt_row !== null;
                $tt_id = $is_existing ? $tt_row['id'] : '';
                $tt_subject_id = $is_existing ? $tt_row['subject_id'] : '';
                $tt_date = $is_existing ? $tt_row['date'] : '';
                $tt_time_from = $is_existing ? $tt_row['time_from'] : '';
                $tt_duration = $is_existing ? $tt_row['duration'] : '';
                $tt_room_no = $is_existing ? $tt_row['room_no'] : '';
                $tt_assessment_ids = $is_existing ? explode(',', $tt_row['assessment_ids']) : [];
                $tt_assigned_classes = $is_existing ? explode(',', $tt_row['assigned_class_ids']) : [];
            ?>
            <tr class="timetable_row">
                <td>
                    <input type="hidden" name="timetable_ids[]" class="timetable_id_hidden" value="<?php echo $tt_id; ?>">
                    <?php if ($is_existing): ?>
                        <input type="hidden" name="subjects[]" value="<?php echo $tt_subject_id; ?>">
                    <?php endif; ?>
                    <select class="d2-form-control subject_select" <?php echo !$is_existing ? 'name="subjects[]"' : 'disabled'; ?> required>
                        <option value="">Select Subject</option>
                        <?php if (!empty($batch_subjects)): ?>
                            <?php foreach ($batch_subjects as $subject_value): ?>
                                <option value="<?php echo $subject_value['id']; ?>" <?php echo $tt_subject_id == $subject_value['id'] ? 'selected' : ''; ?>>
                                    <?php echo $subject_value['name'] . ($subject_value['code'] ? ' (' . $subject_value['code'] . ')' : ''); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
                <td>
                    <?php if (!empty($assessments)): ?>
                        <?php foreach ($assessments as $assessment_value): ?>
                            <label class="d2-checkbox-inline" style="display:block; margin-bottom:5px; font-size:12px;">
                                <?php 
                                $checked = 'checked';
                                if ($is_existing && !in_array($assessment_value->id, $tt_assessment_ids)) {
                                    $checked = '';
                                }
                                ?>
                                <input type="checkbox" class="assessment_check" value="<?php echo $assessment_value->id; ?>" <?php echo $checked; ?>>
                                <?php echo $assessment_value->name . ($assessment_value->code ? ' (' . $assessment_value->code . ')' : ''); ?>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span style="color:#d8456a; font-size:11px;">Please select an assessment type in Step 1</span>
                    <?php endif; ?>
                    <input type="hidden" name="assessments[]" class="assessments_hidden" value="">
                </td>
                <td>
                    <?php if (!empty($assigned_classes_details)): ?>
                        <?php foreach ($assigned_classes_details as $cls): ?>
                            <label class="d2-checkbox-inline" style="display:block; margin-bottom:5px; font-size:12px;">
                                <?php 
                                $checked = 'checked';
                                if ($is_existing && !in_array($cls['id'], $tt_assigned_classes)) {
                                    $checked = '';
                                }
                                ?>
                                <input type="checkbox" class="class_assign_check" value="<?php echo $cls['id']; ?>" <?php echo $checked; ?>>
                                <?php echo $cls['class']; ?>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span style="color:#d8456a; font-size:11px;">Please select classes in Step 2</span>
                    <?php endif; ?>
                    <input type="hidden" name="assigned_classes[]" class="classes_hidden" value="">
                </td>
                <td>
                    <input type="date" class="d2-form-control" name="dates[]" value="<?php echo $tt_date; ?>" required>
                </td>
                <td>
                    <input type="time" class="d2-form-control" name="start_times[]" value="<?php echo $tt_time_from; ?>" required>
                </td>
                <td>
                    <input type="number" class="d2-form-control" name="durations[]" placeholder="e.g. 120" value="<?php echo $tt_duration; ?>" required>
                </td>
                <td>
                    <input type="text" class="d2-form-control" name="room_nos[]" placeholder="e.g. 101" value="<?php echo $tt_room_no; ?>" required>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <button type="button" class="btn btn-sm btn-danger remove_row"><i class="fa fa-times"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="text-right" style="margin-top: 15px;">
    <button type="button" class="d2-btn primary" id="add_row_btn"><i class="fa fa-plus"></i> Add Subject</button>
</div>

<script>
$(document).ready(function() {
    
    // Auto-update hidden assessments input before form submit (or when checkbox changes)
    function updateHiddenAssessments(row) {
        var checked = [];
        row.find('.assessment_check:checked').each(function() {
            checked.push($(this).val());
        });
        row.find('.assessments_hidden').val(checked.join(','));
    }
    
    $('#timetable_body').on('change', '.assessment_check', function() {
        updateHiddenAssessments($(this).closest('.timetable_row'));
    });
    
    function updateHiddenClasses(row) {
        var checked = [];
        row.find('.class_assign_check:checked').each(function() {
            checked.push($(this).val());
        });
        row.find('.classes_hidden').val(checked.join(','));
    }
    
    $('#timetable_body').on('change', '.class_assign_check', function() {
        updateHiddenClasses($(this).closest('.timetable_row'));
    });
    
    // Initialize ALL rows
    $('.timetable_row').each(function() {
        updateHiddenAssessments($(this));
        updateHiddenClasses($(this));
    });

    $('#add_row_btn').click(function() {
        var newRow = $('.timetable_row').first().clone();
        
        // Clear inputs
        newRow.find('input[type="date"]').val('');
        newRow.find('input[type="time"]').val('');
        newRow.find('input[type="text"], input[type="number"]').val('');
        
        // Reset hidden IDs so it's treated as a new row
        newRow.find('.timetable_id_hidden').val('');
        
        // Remove any hidden subject input (from an existing row clone)
        newRow.find('input[type="hidden"][name="subjects[]"]').remove();
        
        // Reset select, enable it, and ensure it has the correct name
        var select = newRow.find('select.subject_select');
        select.prop('disabled', false);
        select.attr('name', 'subjects[]');
        select.val('');
        
        // Ensure assessments and classes are checked by default in cloned row
        newRow.find('.assessment_check').prop('checked', true);
        newRow.find('.class_assign_check').prop('checked', true);
        
        $('#timetable_body').append(newRow);
        updateHiddenAssessments(newRow);
        updateHiddenClasses(newRow);
    });

    $('#timetable_body').on('click', '.remove_row', function() {
        if ($('.timetable_row').length > 1) {
            $(this).closest('.timetable_row').remove();
        } else {
            alert("You must have at least one subject.");
        }
    });
});
</script>
