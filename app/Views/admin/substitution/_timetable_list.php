<?php if (empty($timetable)) { ?>
    <div class="alert alert-info">No timetable periods found for this staff member on the selected day.</div>
<?php } else { ?>
    <form id="substitution_form">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Subject</th>
                    <th>Class (Section)</th>
                    <th>Time</th>
                    <th>Room No</th>
                    <th>Substitute Teacher</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $fallback_p_num = 1;
                foreach ($timetable as $t) { 
                    $is_break = (isset($t->period_type) && $t->period_type == 'break');
                    $period_num_display = !empty($t->period_number) ? $t->period_number : $fallback_p_num++;
                ?>
                    <tr>
                        <td>
                            <?php if ($is_break) { ?>
                                <span class="label label-default" style="font-size: 11px;"><i class="fa fa-cutlery"></i> Break</span>
                            <?php } else { ?>
                                <span class="label label-primary" style="font-size: 11px; font-weight: 700;">Period <?php echo $period_num_display; ?></span>
                            <?php } ?>
                        </td>
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
                                
                                <?php if (!empty($t->available_staff)) { ?>
                                    <optgroup label="Available Staff (Free this period)">
                                        <?php foreach ($t->available_staff as $staff) { 
                                            $selected = (isset($sub_map[$t->id]['substitute_staff_id']) && $sub_map[$t->id]['substitute_staff_id'] == $staff['id']) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $staff['id']; ?>" <?php echo $selected; ?>>
                                                ✔ <?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ")"; ?>
                                            </option>
                                        <?php } ?>
                                    </optgroup>
                                <?php } ?>

                                <?php if (!empty($t->busy_staff)) { ?>
                                    <optgroup label="Other Active Staff (Scheduled / Busy)">
                                        <?php foreach ($t->busy_staff as $staff) { 
                                            $selected = (isset($sub_map[$t->id]['substitute_staff_id']) && $sub_map[$t->id]['substitute_staff_id'] == $staff['id']) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $staff['id']; ?>" <?php echo $selected; ?>>
                                                📅 <?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ") - " . $staff['conflict_info']; ?>
                                            </option>
                                        <?php } ?>
                                    </optgroup>
                                <?php } ?>

                                <?php if (!empty($t->absent_staff_list)) { ?>
                                    <optgroup label="Absent / On Leave Staff">
                                        <?php foreach ($t->absent_staff_list as $staff) { 
                                            $selected = (isset($sub_map[$t->id]['substitute_staff_id']) && $sub_map[$t->id]['substitute_staff_id'] == $staff['id']) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $staff['id']; ?>" <?php echo $selected; ?>>
                                                ⚠️ <?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ") - " . $staff['leave_info']; ?>
                                            </option>
                                        <?php } ?>
                                    </optgroup>
                                <?php } ?>
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
    <style type="text/css">
        .select2-container--default .select2-selection--single {
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            height: 38px !important;
            padding: 4px 8px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
            color: #1e293b !important;
            font-weight: 500 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
        .select2-dropdown {
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
            z-index: 99999 !important;
        }
        .select2-results__group {
            font-weight: 700 !important;
            color: #0f172a !important;
            background-color: #f1f5f9 !important;
            padding: 6px 10px !important;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            if (typeof $.fn.select2 !== 'undefined') {
                $('.substitute_select').select2({
                    width: '100%',
                    placeholder: 'Search & Select Substitute...',
                    dropdownParent: $('body')
                });
            }
        });
    </script>
<?php } ?>
