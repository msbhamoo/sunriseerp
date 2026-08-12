<style type="text/css">
    .radio {
        padding-left: 20px;
    }

    .radio label {
        display: inline-block;
        vertical-align: middle;
        position: relative;
        padding-left: 5px;
    }

    .radio label::before {
        content: "";
        display: inline-block;
        position: absolute;
        width: 17px;
        height: 17px;
        left: 0;
        margin-left: -20px;
        border: 1px solid var(--bs-primary);
        border-radius: 50%;
        background-color: var(--bs-input-bg);
        -webkit-transition: border 0.15s ease-in-out;
        -o-transition: border 0.15s ease-in-out;
        transition: border 0.15s ease-in-out;
    }

    .radio label::after {
        display: inline-block;
        position: absolute;
        content: " ";
        width: 11px;
        height: 11px;
        left: 3px;
        top: 3px;
        margin-left: -20px;
        border-radius: 50%;
        background-color: #555555;
        -webkit-transform: scale(0, 0);
        -ms-transform: scale(0, 0);
        -o-transform: scale(0, 0);
        transform: scale(0, 0);
        -webkit-transition: -webkit-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -moz-transition: -moz-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -o-transition: -o-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        transition: transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
    }

    .radio input[type="radio"] {
        opacity: 0;
        z-index: 1;
    }

    .radio input[type="radio"]:focus+label::before {
        outline: thin dotted;
        outline: 5px auto -webkit-focus-ring-color;
        outline-offset: -2px;
    }

    .radio input[type="radio"]:checked+label::after {
        -webkit-transform: scale(1, 1);
        -ms-transform: scale(1, 1);
        -o-transform: scale(1, 1);
        transform: scale(1, 1);
    }

    .radio input[type="radio"]:disabled+label {
        opacity: 0.65;
    }

    .radio input[type="radio"]:disabled+label::before {
        cursor: not-allowed;
    }

    .radio.radio-inline {
        margin-top: 0;
        margin-right: 10px;
        margin-left: 0;
    }

    .radio-primary input[type="radio"]+label::after {
        background-color: var(--bs-primary);
    }

    .radio-primary input[type="radio"]:checked+label::before {
        border-color: var(--bs-primary);
    }

    .radio-primary input[type="radio"]:checked+label::after {
        background-color: var(--bs-primary);
    }

    .radio-danger input[type="radio"]+label::after {
        background-color: #d9534f;
    }

    .radio-danger input[type="radio"]:checked+label::before {
        border-color: #d9534f;
    }

    .radio-danger input[type="radio"]:checked+label::after {
        background-color: #d9534f;
    }

    .radio-info input[type="radio"]+label::after {
        background-color: var(--bs-primary);
    }

    .radio-info input[type="radio"]:checked+label::before {
        border-color: var(--bs-primary);
    }

    .radio-info input[type="radio"]:checked+label::after {
        background-color: var(--bs-primary);
    }

    @media (max-width:767px) {
        .radio.radio-inline {
            display: block;
            margin-left: 0;
        }
    }

    /* ===== Compliance Compact Badge Checkboxes ===== */
    .cmp-group { display: flex; flex-wrap: wrap; gap: 4px 6px; align-items: center; }
    .cmp-pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; cursor: pointer; user-select: none; border: 1px solid #d1d5db; background: #f8fafc; color: #64748b; transition: all 0.15s ease; margin: 0; }
    .cmp-pill input { display: none; }
    .cmp-pill.active { background: #e6f4ea; color: #1e7e34; border-color: #a8dab5; }
    .cmp-pill:not(.active) { background: #fef2f2; color: #dc3545; border-color: #f5c6cb; }
    .cmp-pill i { font-size: 10px; }

    /* ===== Page polish ===== */
    #staffatt-legend { display:flex; flex-wrap:wrap; gap:14px; align-items:center; font-size:12px; color:#555; padding:8px 4px 0; }
    #staffatt-legend .lg-item { display:inline-flex; align-items:center; gap:6px; }
    #staffatt-legend .lg-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
    .staffatt-table thead th { background:#f7f9fb; color:#41505f; font-size:12px; text-transform:uppercase; letter-spacing:.3px; border-bottom:2px solid #e4e9ef !important; vertical-align:middle; white-space:nowrap; }
    .staffatt-table tbody td { vertical-align:middle !important; }
    .staffatt-table tbody tr:hover td { background:#f3f8ff !important; }
    .staffatt-table .radio-inline { margin-right:8px; }
    .staffatt-hint { color:#8a93a2; font-size:12px; margin-top:4px; }
    @media (max-width:767px){ .uniform-switch { justify-content:flex-start; } }

</style>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/staffattendance/scan'); ?>" class="btn btn-success btn-sm"><i class="fa fa-qrcode"></i> Mark My Attendance</a>
                            <a href="<?php echo site_url('admin/staffattendance/qrdisplay'); ?>" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-desktop"></i> Display QR</a>
                            <a href="<?php echo site_url('admin/staffattendance/qrsettings'); ?>" class="btn btn-default btn-sm"><i class="fa fa-cog"></i> QR Settings</a>
                        </div>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/staffattendance/index') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php
                            if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                            }
                            ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('role'); ?></label>

                                        <select autofocus="" id="class_id" name="user_id" class="form-control">
                                            <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $key => $class) {
                                            ?>
                                                <option value="<?php echo $class["type"] ?>" <?php
                                                                                                if ($class["type"] == $user_type_id) {
                                                                                                    echo "selected =selected";
                                                                                                }
                                                                                                ?>><?php print_r($class["type"]) ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">
                                            <?php echo $this->lang->line('attendance_date'); ?></label>
                                        <input name="date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search" class="btn btn-primary pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (isset($resultlist)) {
                    ?>
                        <div class="box-header ptbnull"></div>
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('staff_list'); ?></h3>
                            <div class="box-tools pull-right">
                            </div>                            <div id="staffatt-legend">
                                <span class="lg-item"><span class="lg-dot" style="background:#00a65a;"></span> <?php echo $this->lang->line('yes'); ?> / Compliant</span>
                                <span class="lg-item"><span class="lg-dot" style="background:#d9534f;"></span> <?php echo $this->lang->line('no'); ?> / Non-Compliant</span>
                                <span class="lg-item"><i class="fa fa-info-circle text-muted"></i> Compliance items (Uniform, ID Card, Lesson Plan, Phone Handover) are for administrative tracking.</span>
                            </div>
                        </div>
                        <div class="box-body">
                            <?php
                            if (!empty($resultlist)) {
                                $checked = "";

                                // Attendance types that should NOT have entry/exit time (leave-like).
                                // Kept data-driven so the new "Unplanned Leave" type is handled without
                                // hardcoding its id, while Absent/Holiday behave exactly as before.
                                $leave_like_ids = [];
                                foreach ($attendencetypeslist as $__t) {
                                    if (in_array($__t['long_lang_name'], ['absent', 'holiday', 'unplanned_leave'])) {
                                        $leave_like_ids[] = (int)$__t['id'];
                                    }
                                }
                                ?>
                                <form action="<?php echo site_url('admin/staffattendance/index') ?>" id="save_attendance" method="post">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="mailbox-controls">
                                    <div class="row">
                                                <div class="col-md-9">
                                                
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('set_attendance_for_all_staff_as'); ?> &nbsp;</label>
                                                        <?php
                                                        foreach ($attendencetypeslist as $key => $type) {
                                                            $att_type = str_replace(" ", "_", strtolower($type['type']));

                                                        ?>
                                                            <div class="radio radio-info radio-inline">
                                                                <input type="radio" data-record_id="<?php echo $type['id'] ?>" name="attendencetype" class="default_radio" value="radio_<?php echo $type['id'] ?>" id="attendencetype<?php echo $type['id'] ?>"   onclick="getatten(<?php echo $type['id'] ?>)">
                                                                <label for="attendencetype<?php echo $type['id'] ?>">
                                                                    <?php echo  $this->lang->line($att_type); ?> 
                                                                </label>

                                                            </div>
                                                        <?php

                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="form-group" style="display:flex; flex-wrap:wrap; gap:15px; align-items:center; background:#f9fafb; padding:8px 12px; border-radius:6px; border:1px solid #eef2f6;">
                                                        <span style="font-weight:600; color:#334155;"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('compliance_checklist'); ?> (Set All):</span>
                                                        
                                                        <span style="font-size:12px;"><strong><?php echo $this->lang->line('in_uniform'); ?>:</strong>
                                                            <button type="button" class="btn btn-xs btn-success set-all-cmp" data-target="uniform-check" data-val="true"><i class="fa fa-check"></i> <?php echo $this->lang->line('yes'); ?></button>
                                                            <button type="button" class="btn btn-xs btn-danger set-all-cmp" data-target="uniform-check" data-val="false"><i class="fa fa-times"></i> <?php echo $this->lang->line('no'); ?></button>
                                                        </span>

                                                        <span style="font-size:12px;"><strong><?php echo $this->lang->line('id_card'); ?>:</strong>
                                                            <button type="button" class="btn btn-xs btn-success set-all-cmp" data-target="idcard-check" data-val="true"><i class="fa fa-check"></i> <?php echo $this->lang->line('yes'); ?></button>
                                                            <button type="button" class="btn btn-xs btn-danger set-all-cmp" data-target="idcard-check" data-val="false"><i class="fa fa-times"></i> <?php echo $this->lang->line('no'); ?></button>
                                                        </span>

                                                        <span style="font-size:12px;"><strong><?php echo $this->lang->line('lesson_plan'); ?>:</strong>
                                                            <button type="button" class="btn btn-xs btn-success set-all-cmp" data-target="lessonplan-check" data-val="true"><i class="fa fa-check"></i> <?php echo $this->lang->line('yes'); ?></button>
                                                            <button type="button" class="btn btn-xs btn-danger set-all-cmp" data-target="lessonplan-check" data-val="false"><i class="fa fa-times"></i> <?php echo $this->lang->line('no'); ?></button>
                                                        </span>

                                                        <span style="font-size:12px;"><strong><?php echo $this->lang->line('phone_handover'); ?>:</strong>
                                                            <button type="button" class="btn btn-xs btn-success set-all-cmp" data-target="phone-check" data-val="true"><i class="fa fa-check"></i> <?php echo $this->lang->line('yes'); ?></button>
                                                            <button type="button" class="btn btn-xs btn-danger set-all-cmp" data-target="phone-check" data-val="false"><i class="fa fa-times"></i> <?php echo $this->lang->line('no'); ?></button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="pull-right">
 														<?php if (($this->rbac->hasPrivilege('staff_attendance', 'can_add')) || ($this->rbac->hasPrivilege('staff_attendance', 'can_edit'))) { ?>
                                                        <button type="submit" name="search" value="saveattendence" id="saveattendence" class="btn btn-primary pull-right checkbox-toggle"><i class="fa fa-save"></i> <?php echo $this->lang->line('save_attendance'); ?> </button>
 														<?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <input type="hidden" name="is_first_time_attendance" value="<?php echo $is_first_time_attendance;?>">
                                    <input type="hidden" name="user_id" value="<?php echo $user_type_id; ?>">
                                    <input type="hidden" name="section_id" value="">
                                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped example staffatt-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php echo $this->lang->line('staff_id'); ?></th>
                                                    <th><?php echo $this->lang->line('name'); ?></th>
                                                    <th><?php echo $this->lang->line('role'); ?></th>
                                                    <th><?php echo $this->lang->line('attendance'); ?></th>
                                                    <th class="text-center white-space-nowrap" style="min-width:280px;"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('compliance_checklist'); ?></th>
                                                    <?php  if ($sch_setting->biometric) {  ?>
                                                        <th width="10%"><?php echo $this->lang->line('date'); ?></th>
                                                    <?php  }  ?>
                                                    <th width="8%" ><?php echo $this->lang->line('source'); ?></th>
                                                    <th class="white-space-nowrap"><?php echo $this->lang->line('entry_time'); ?></th>
                                                    <th class="white-space-nowrap"><?php echo $this->lang->line('exit_time'); ?></th>
                                                    <th class="text-right"><?php echo $this->lang->line('note'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $row_count = 1;
                                                foreach ($resultlist as $key => $value) {

                                                    $attendendence_id = $value["id"];
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="staff_role[]" id="staff_role_<?php echo $value['role_id']; ?>" value="<?php echo $value['role_id']; ?>">
                                                            <input type="hidden" name="student_session[]" value="<?php echo $value['staff_id']; ?>">
                                                            <input type="hidden" value="<?php echo $attendendence_id ?>" name="attendendence_id<?php echo $value["staff_id"]; ?>">
                                                            <?php echo $row_count; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value['employee_id']; ?>
                                                        </td>                                                      
                                                        <td>
                                                            <?php echo $value['name'] . " " . $value['surname']; ?>
                                                        </td>
                                                        <td><?php echo $value['user_type']; ?></td>
                                                        <td>
                                                            <?php
                                                            $c     = 1;
                                                            $count = 0;
                                                            foreach ($attendencetypeslist as $key => $type) {  

                                                                    $att_type = str_replace(" ", "_", strtolower($type['type']));
                                                                    if ($value["date"] != "xxx") {
                                                            ?>
                                                                        <div class="radio radio-info radio-inline">
                                                                            <input onclick="disable_enable(this.value,<?php echo $value["staff_id"] ?>)"  <?php if ($value['staff_attendance_type_id'] == $type['id']) {
                                                                                        echo "checked";
                                                                                    }
                                                                                    ?> type="radio" id="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>" value="<?php echo $type['id'] ?>" name="attendencetype<?php echo $value['staff_id']; ?>" class="radio_<?php echo $type['id'] ?>">
                                                                            <label for="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>">
                                                                                <?php echo $this->lang->line(($type['long_lang_name'])); ?>
                                                                            </label>
                                                                        </div>
                                                                    <?php
                                                                    } else {
                                                                    ?>

                                                                        <?php
                                                                        if ($sch_setting->biometric) {
                                                                        ?>
                                                                            <div class="radio radio-info radio-inline">
                                                                                <input <?php if ($att_type == "absent") {
                                                                                            echo "checked";
                                                                                        }
                                                                                        ?> type="radio" id="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>" value="<?php echo $type['id'] ?>" name="attendencetype<?php echo $value['staff_id']; ?>" class="radio_<?php echo $type['id'] ?>">
                                                                                <label for="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>">
                                                                                    <?php echo $this->lang->line(($type['long_lang_name'])); ?>
                                                                                </label>
                                                                            </div>
                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <div class="radio radio-info radio-inline">
                                                                                <input  onclick="disable_enable(this.value,<?php echo $value["staff_id"] ?>)"  <?php if (($c == 1) && ($resultlist[0]['staff_attendance_type_id'] != 5)) {
                                                                                            echo "checked";
                                                                                        }
                                                                                        ?> type="radio" id="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>" value="<?php echo $type['id'] ?>" name="attendencetype<?php echo $value['staff_id']; ?>" class="radio_<?php echo $type['id'] ?>">
                                                                                <label for="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>">
                                                                                    <?php
                                                                                     echo $this->lang->line(($type['long_lang_name'])); ?>
                                                                                </label>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?> <?php
                                                                        }
                                                                        $c++;
                                                                        $count++;
                                                                    
                                                                }
                                                                            ?>
                                                        </td>
                                                        <?php
                                                            // Compliance toggles — default 'yes' (checked) unless explicit 'no'
                                                            $u_val  = isset($value['uniform_status']) ? $value['uniform_status'] : null;
                                                            $id_val = isset($value['id_card_status']) ? $value['id_card_status'] : null;
                                                            $lp_val = isset($value['lesson_plan_status']) ? $value['lesson_plan_status'] : null;
                                                            $ph_val = isset($value['phone_handover_status']) ? $value['phone_handover_status'] : null;
                                                        ?>
                                                        <td>
                                                            <div class="cmp-group">
                                                                <!-- Uniform -->
                                                                <label class="cmp-pill <?php echo ($u_val === 'no') ? '' : 'active'; ?>" title="<?php echo $this->lang->line('in_uniform'); ?>">
                                                                    <input type="checkbox" class="cmp-check uniform-check" name="uniform_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($u_val === 'no') ? '' : 'checked'; ?>>
                                                                    <i class="fa <?php echo ($u_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                    <span><?php echo $this->lang->line('in_uniform'); ?></span>
                                                                </label>

                                                                <!-- ID Card -->
                                                                <label class="cmp-pill <?php echo ($id_val === 'no') ? '' : 'active'; ?>" title="<?php echo $this->lang->line('id_card'); ?>">
                                                                    <input type="checkbox" class="cmp-check idcard-check" name="id_card_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($id_val === 'no') ? '' : 'checked'; ?>>
                                                                    <i class="fa <?php echo ($id_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                    <span><?php echo $this->lang->line('id_card'); ?></span>
                                                                </label>

                                                                <!-- Lesson Plan / Diary -->
                                                                <label class="cmp-pill <?php echo ($lp_val === 'no') ? '' : 'active'; ?>" title="<?php echo $this->lang->line('lesson_plan'); ?>">
                                                                    <input type="checkbox" class="cmp-check lessonplan-check" name="lesson_plan_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($lp_val === 'no') ? '' : 'checked'; ?>>
                                                                    <i class="fa <?php echo ($lp_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                    <span><?php echo $this->lang->line('lesson_plan'); ?></span>
                                                                </label>

                                                                <!-- Phone Handover -->
                                                                <label class="cmp-pill <?php echo ($ph_val === 'no') ? '' : 'active'; ?>" title="<?php echo $this->lang->line('phone_handover'); ?>">
                                                                    <input type="checkbox" class="cmp-check phone-check" name="phone_handover_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($ph_val === 'no') ? '' : 'checked'; ?>>
                                                                    <i class="fa <?php echo ($ph_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                    <span><?php echo $this->lang->line('phone_handover'); ?></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <?php
                                                        if ($sch_setting->biometric) {
                                                        ?>
                                                            <td>
                                                            <?php
                                                                if ($value['biometric_attendence'] || $value['qrcode_attendance']) {

                                                                    echo $this->customlib->dateyyyymmddToDateTimeformat($value['attendence_dt']);
                                                                }
                                                                ?>
                                                            </td>
                                                        <?php
                                                        }
                                                        ?>
                                                        <td>
                                                            <?php

                                                            if (IsNullOrEmptyString($value['biometric_attendence']) && IsNullOrEmptyString($value['qrcode_attendance'])) {
                                                                echo $this->lang->line('n_a');
                                                            } elseif (($value['biometric_attendence'] == 0) && ($value['qrcode_attendance']  == 0)) {
                                                                echo $this->lang->line('manual');
                                                            } elseif ($value['biometric_attendence']) {
                                                                echo $this->lang->line('biometric');
                                                            } elseif ($value['qrcode_attendance']) {
                                                                echo $this->lang->line('qrcode')." / ".$this->lang->line('barcode');
                                                            }

                                                            ?>
                                                        </td>

                                                        <?php
                                                        if(in_array((int)$value['staff_attendance_type_id'], $leave_like_ids)){
                                                            $disable_input_attr="disabled";
                                                        }else{
                                                            $disable_input_attr="";
                                                        }  ?>

                                                    <td class="relative">
                                                        <input <?php echo $disable_input_attr;?> type="text" value="<?php if($value["in_time"]!="00:00:00"){ echo $value["in_time"]; }else{ echo "";} ?>"  name="in_time_<?php echo $value["staff_id"] ?>" id="in_time_<?php echo $value["staff_id"] ?>" class="form-control datetime in_time time in_time_<?php echo $value['role_id']; ?>">
                                                    </td>                                                        
                                                    <td class="relative">
                                                        <input  <?php echo $disable_input_attr;?>  type="text" value="<?php if($value["out_time"]!="00:00:00"){ echo $value["out_time"]; }else{ echo "";} ?>"  name="out_time_<?php echo $value["staff_id"] ?>"  id="out_time_<?php echo $value["staff_id"] ?>" class="form-control datetime out_time time out_time_<?php echo $value['role_id']; ?>">
                                                    </td>  
                                                        <?php if ($value["date"] == 'xxx') { ?>
                                                            <td class="text-right"><input type="text"  class="form-control"  name="remark<?php echo $value["staff_id"] ?>"></td>
                                                        <?php } else { ?>
                                                            <td class="text-right"><input type="text"  class="form-control" name="remark<?php echo $value["staff_id"] ?>" value="<?php echo $value["remark"]; ?>"></td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php
                                                    $row_count++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            <?php
                            } else {
                            ?>
                                <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                            <?php
                            }
                            ?>
                        </div>
                </div>
            <?php
                    }
            ?>
    </section>
</div>

<script type="text/javascript">
    $(document).on('submit', '#save_attendance', function(e) {
        $('#load').button('loading');
    });

    $(document).ready(function() {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            ordering: true,
            paging: false,
            retrieve: true,
            destroy: true,
            info: false
        });
        var table = $('.example').DataTable();
        table.buttons('.export').remove();
    });
</script>
<script type="text/javascript">
  
    $(document).ready(function() {
        $('.default_radio').click(function() {
            let radio_default=($(this).val());
            var returnVal = confirm("<?php echo $this->lang->line('are_you_sure'); ?>");
            if(returnVal){
                
                $("input[type=radio][class='"+radio_default+"']").prop("checked", returnVal);
                
                let attendance_type_id = ($(this).data('record_id'));
                if(window.leaveTypeIds && window.leaveTypeIds.indexOf(parseInt(attendance_type_id))!==-1){
                    //absent, holiday or unplanned leave -> no entry/exit time
                    $('.in_time').attr("disabled",true);
                    $('.out_time').attr("disabled",true);
                }else{
                    $('.in_time').attr("disabled",false);
                    $('.out_time').attr("disabled",false);
                }

            }else{
                return false;
            }
    });
    });
</script>

<script type="text/javascript">
    $(function() {
        $('.button-checkbox').each(function() {
            var $widget = $(this),
                $button = $widget.find('button'),
                $checkbox = $widget.find('input:checkbox'),
                color = $button.data('color'),
                settings = {
                    on: {
                        icon: 'glyphicon glyphicon-check'
                    },
                    off: {
                        icon: 'glyphicon glyphicon-unchecked'
                    }
                };
            $button.on('click', function() {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
                $checkbox.triggerHandler('change');
                updateDisplay();
            });
            $checkbox.on('change', function() {
                updateDisplay();
            });

            function updateDisplay() {
                var isChecked = $checkbox.is(':checked');
                $button.data('state', (isChecked) ? "on" : "off");
                $button.find('.state-icon')
                    .removeClass()
                    .addClass('state-icon ' + settings[$button.data('state')].icon);
                if (isChecked) {
                    $button
                        .removeClass('btn-success')
                        .addClass('btn-' + color + ' active');
                } else {
                    $button
                        .removeClass('btn-' + color + ' active')
                        .addClass('btn-primary');
                }
            }

            function init() {
                updateDisplay();
                if ($button.find('.state-icon').length == 0) {
                    $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                }
            }
            init();
        });
    });
</script>

<script type="text/javascript">
//**** staff attendance ****//
 $(function() {
     $('.time').datetimepicker({
            format: 'LT'
     });
 });
 
  $(function()
        {
            $('.time').datetimepicker().on('dp.show',function()
            {
                $(this).closest('.table-responsive').removeClass('table-responsive').addClass('temp');
            }).on('dp.hide',function()
            {
                $(this).closest('.temp').addClass('table-responsive').removeClass('temp')
            });
        });

function tConvert(time) {
if (time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/)) {
    const [timeWithoutPeriod, period] = time.split(" ");
    let [hours, minutes, seconds] = timeWithoutPeriod.split(":");
    let AM_PM = null;
    AM_PM = hours < 12 ? 'AM' : 'PM'; // Set AM/PM
    hours = hours % 12 || 12; // Adjust hours
    return `${hours}:${minutes} ${AM_PM}`;
} else {
    return time;
}
}

var attendance_setting = <?php echo json_encode($staff_settings) ?>;

var leaveTypeIds = <?php echo json_encode(isset($leave_like_ids) ? $leave_like_ids : [3,5]); ?>;
window.leaveTypeIds = leaveTypeIds;

function getatten(atten_type){
    // Leave-like types (absent / holiday / unplanned leave) have no entry/exit time.
    if(leaveTypeIds.indexOf(parseInt(atten_type))!==-1){
      $('.in_time').val('');
      $('.out_time').val('');
      return false;
    }else{
        var role_id = $("input[name='staff_role[]']").map(function(){return $(this).val();}).get();
        let nm = (attendance_setting);     
        for(var i=0;i<role_id.length;i++){
        var returnValue = false;
        $.each(nm, function(key, value) {
            if (value.staff_attendence_type_id == atten_type  &&  value.role_id==role_id[i]) {                
                returnValue = [tConvert(value.entry_time_from), tConvert(value.entry_time_to)];
                $('.in_time_'+role_id[i]).val(returnValue[0]);
                $('.out_time_'+role_id[i]).val(returnValue[1]);                
            }else{
                            
            }
        }); 
    }
    }
}

let disable_enable=(type,staff_id)=>{
    if(leaveTypeIds.indexOf(parseInt(type))!==-1){
        $("#in_time_"+staff_id).val("");
        $("#out_time_"+staff_id).val("");
        $("#in_time_"+staff_id).attr("disabled",true);
        $("#out_time_"+staff_id).attr("disabled",true);
    }else{
        $("#in_time_"+staff_id).val("");
        $("#out_time_"+staff_id).val("");
        $("#in_time_"+staff_id).attr("disabled",false);
        $("#out_time_"+staff_id).attr("disabled",false);
    }
}

// ===== Compliance Checklist Pill handlers =====
$(document).ready(function() {
    function syncCmpPill($chk) {
        var isChecked = $chk.is(':checked');
        var $pill = $chk.closest('.cmp-pill');
        var $icon = $pill.find('i');
        if (isChecked) {
            $pill.addClass('active');
            $icon.removeClass('fa-times').addClass('fa-check');
        } else {
            $pill.removeClass('active');
            $icon.removeClass('fa-check').addClass('fa-times');
        }
    }

    $(document).on('change', '.cmp-check', function() {
        syncCmpPill($(this));
    });

    $('.set-all-cmp').on('click', function() {
        var targetClass = $(this).data('target');
        var state = $(this).data('val') === true || $(this).data('val') === 'true';
        $('.' + targetClass).prop('checked', state).each(function() {
            syncCmpPill($(this));
        });
    });
});

</script>