<?php if (empty($timetable)) { ?>
    <div class="alert alert-info">No timetable periods found for this staff member on the selected day.</div>
<?php } else { ?>
    <form id="substitution_form">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Class (Section)</th>
                    <th>Time</th>
                    <th>Room No</th>
                    <th>Substitute Teacher</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($timetable as $t) { ?>
                    <tr>
                        <td>
                            <select name="substitute_subjects[<?php echo $t->id; ?>]" class="form-control" style="width: 100%;">
                                <?php 
                                $original_subject_found = false;
                                if (!empty($t->available_subjects)) {
                                    foreach ($t->available_subjects as $sub) {
                                        $is_original = ($sub->subject_id == $t->subject_id);
                                        if ($is_original) $original_subject_found = true;
                                        
                                        $selected = '';
                                        if (!empty($t->substitute_subject_id) && $t->substitute_subject_id == $sub->subject_id) {
                                            $selected = 'selected';
                                        } else if (empty($t->substitute_subject_id) && $is_original) {
                                            $selected = 'selected';
                                        }
                                ?>
                                        <option value="<?php echo $sub->subject_id; ?>" <?php echo $selected; ?>><?php echo $sub->subject_name . " (" . $sub->subject_code . ")"; ?></option>
                                <?php 
                                    }
                                } 
                                if (!$original_subject_found) {
                                    // Fallback if original subject is somehow not in the group
                                    $selected = empty($t->substitute_subject_id) ? 'selected' : '';
                                    echo '<option value="'.$t->subject_id.'" '.$selected.'>'.$t->subject_name.' ('.$t->subject_code.')</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td><?php echo $t->class . " (" . $t->section . ")"; ?></td>
                        <td><?php echo $t->time_from . " - " . $t->time_to; ?></td>
                        <td><?php echo $t->room_no; ?></td>
                        <td>
                            <select name="substitutions[<?php echo $t->id; ?>]" id="substitute_<?php echo $t->id; ?>" 
                                class="form-control substitute_select" 
                                data-timetable_id="<?php echo $t->id; ?>" 
                                data-day="<?php echo $t->day; ?>" 
                                data-time_from="<?php echo $t->time_from; ?>" 
                                data-time_to="<?php echo $t->time_to; ?>">
                                <option value="">Select Substitute...</option>
                                <?php foreach ($staff_list as $staff) { 
                                    if ($staff['id'] != $absent_staff_id && $staff['is_active'] == 1) { 
                                        $selected = (isset($sub_map[$t->id]) && $sub_map[$t->id] == $staff['id']) ? 'selected' : '';
                                ?>
                                        <option value="<?php echo $staff['id']; ?>" <?php echo $selected; ?>><?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ")"; ?></option>
                                <?php } 
                                } ?>
                            </select>
                            <input type="hidden" name="overrides[<?php echo $t->id; ?>]" id="override_<?php echo $t->id; ?>" value="">
                            <div id="conflict_<?php echo $t->id; ?>"></div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="text-right mt-10">
            <button type="button" id="save_substitution_btn" class="btn btn-success"><i class="fa fa-save"></i> Save Substitutions</button>
        </div>
    </form>
<?php } ?>
