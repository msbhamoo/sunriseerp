<div class="table-responsive">
    <table class="table table-bordered table-hover" id="item_table" style="font-size: 13px; background: #fff; border: 1px solid #eaeaea;">
        <thead style="background: #f8f9fa;">
            <tr>
                <th>Subject <span style="color:red;">*</span></th>
                <th>Assessment Types <span style="color:red;">*</span></th>
                <th>Date <span style="color:red;">*</span></th>
                <th>Start Time <span style="color:red;">*</span></th>
                <th>Duration (mins) <span style="color:red;">*</span></th>
                <th>Room No <span style="color:red;">*</span></th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody id="timetable_body">
            <!-- Initial Row -->
            <tr class="timetable_row">
                <td>
                    <select class="d2-form-control subject_select" name="subjects[]" required>
                        <option value="">Select Subject</option>
                        <?php if (!empty($batch_subjects)): ?>
                            <?php foreach ($batch_subjects as $subject_value): ?>
                                <option value="<?php echo $subject_value['id']; ?>">
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
                                <input type="checkbox" class="assessment_check" value="<?php echo $assessment_value->id; ?>" checked>
                                <?php echo $assessment_value->name . ($assessment_value->code ? ' (' . $assessment_value->code . ')' : ''); ?>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span style="color:#d8456a; font-size:11px;">Please select an assessment type in Step 1</span>
                    <?php endif; ?>
                    <input type="hidden" name="assessments[]" class="assessments_hidden" value="">
                </td>
                <td>
                    <input type="date" class="d2-form-control" name="dates[]" required>
                </td>
                <td>
                    <input type="time" class="d2-form-control" name="start_times[]" required>
                </td>
                <td>
                    <input type="number" class="d2-form-control" name="durations[]" placeholder="e.g. 120" required>
                </td>
                <td>
                    <input type="text" class="d2-form-control" name="room_nos[]" placeholder="e.g. 101" required>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <button type="button" class="btn btn-sm btn-danger remove_row"><i class="fa fa-times"></i></button>
                </td>
            </tr>
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
    
    // Initialize the first row
    updateHiddenAssessments($('.timetable_row').first());

    $('#add_row_btn').click(function() {
        var newRow = $('.timetable_row').first().clone();
        
        // Clear inputs
        newRow.find('input[type="date"]').val('');
        newRow.find('input[type="time"]').val('');
        newRow.find('input[type="text"], input[type="number"]').val('');
        newRow.find('select').val('');
        
        // Ensure assessments are checked by default in cloned row
        newRow.find('.assessment_check').prop('checked', true);
        
        $('#timetable_body').append(newRow);
        updateHiddenAssessments(newRow);
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
