    <style type="text/css">
        /* Modern Attendance Setting Enhancements */
        .sa-setting-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
            margin-bottom: 24px;
            overflow: hidden;
            transition: all 0.2s ease;
        }
        .sa-setting-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.07);
        }
        .sa-setting-header {
            background: #f8fafc;
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .sa-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 700;
            font-size: 14.5px;
            padding: 5px 14px;
            border-radius: 20px;
            border: 1px solid #dbeafe;
        }
        .sa-setting-body {
            padding: 20px;
        }
        .sa-group-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px 18px 14px 18px;
            margin-bottom: 16px;
            height: calc(100% - 16px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .sa-group-title {
            font-size: 12.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .sa-group-in {
            color: #059669;
            border-left: 3.5px solid #10b981;
            padding-left: 10px;
        }
        .sa-group-out {
            color: #2563eb;
            border-left: 3.5px solid #3b82f6;
            padding-left: 10px;
        }
        .sa-grid-3 {
            display: flex;
            flex-wrap: nowrap;
            gap: 12px;
        }
        @media (max-width: 991px) {
            .sa-grid-3 {
                flex-direction: column;
                gap: 12px;
            }
        }
        .sa-field-item {
            flex: 1 1 0;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }
        .sa-field-label {
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sa-field-subtext {
            font-size: 10.5px;
            color: #64748b;
            font-weight: 500;
            margin-top: 5px;
            line-height: 1.3;
        }
        .sa-time-wrap {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .sa-time-control {
            width: 100% !important;
            height: 38px !important;
            border-radius: 8px !important;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            color: #0f172a !important;
            background-color: #ffffff !important;
            border: 1.5px solid #cbd5e1 !important;
            padding: 6px 34px 6px 10px !important;
            letter-spacing: 0.5px !important;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .sa-time-control:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
            outline: none;
        }
        .sa-time-icon {
            position: absolute;
            right: 10px;
            pointer-events: none;
            font-size: 14px;
            line-height: 1;
        }
        .sa-copy-banner {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 12px 18px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .sa-quick-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 12px;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }
    </style>

    <div class="content-wrapper">  
        <section class="content">
            <div class="row">
            
                <?php $this->load->view('setting/_settingmenu'); ?>
                
                <!-- left column -->
                <div class="col-lg-9 col-md-8 col-sm-8">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('attendance_type'); ?></h3>
                            <div class="box-tools pull-right">
                            </div><!-- /.box-tools -->
                        </div><!-- /.box-header -->
                        <div>
                            <form role="form" id="attendancetype_form" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                                <div class="box-body">                       
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-4"><?php echo $this->lang->line('attendance'); ?></label>
                                                <div class="col-sm-8">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="attendence_type" value="0" <?php
                                                        if (!$result->attendence_type) {
                                                            echo "checked";
                                                        }
                                                        ?> ><?php echo $this->lang->line('day_wise'); ?>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="attendence_type" value="1" <?php
                                                        if ($result->attendence_type) {
                                                            echo "checked";
                                                        }
                                                        ?>><?php echo $this->lang->line('period_wise'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-8"> <?php echo $this->lang->line('qrcode') .' / '. $this->lang->line('barcode') .' / '. $this->lang->line('biometric_attendance'); ?></label>
                                                <div class="col-sm-4">
                                                    <div class="material-switch">
                                                        <input id="biometric" name="biometric" type="checkbox" class=""
                                                            value="1" <?php echo set_checkbox('biometric', '1', ($result->biometric==1)); ?> />
                                                        <label for="biometric" class="label-info-success"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-3"> <?php echo $this->lang->line('devices_separate_by_comma'); ?> </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="name" name="biometric_device" value="<?php echo $result->biometric_device; ?>">
                                                        <span class="text-danger"><?php echo form_error('biometric_device'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-3"> <?php echo $this->lang->line('low_attendance_limit'); ?> <i class="fa fa-question-circle cursor-pointer text-sky-blue" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('below_it_attendance_will_be_mark_as_low_attendance');?>"></i></label>
                                                    <div class="col-sm-3">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="low_attendance_limit" id="low_attendance_limit" value="<?php echo $result->low_attendance_limit; ?>">
                                                                <div class="input-group-addon">
                                                                    <span class="">%</span>
                                                                </div>
                                                                
                                                        </div>         
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                               
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <?php
                                    if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                        ?>
                                        <button type="button" class="btn btn-primary submit_schsetting pull-right edit_attendancetype" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="box box-primary hide" id="save_class_time_hide_show">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><?php echo $this->lang->line('class_attendance_time_for_auto_attendance_submission'); ?> (<?php echo $this->lang->line('day_wise_with_cron_setting'); ?>)</h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <?php  $count=1;
                        if(!empty($class_list)){ ?>
                        <form method="POST" action="<?php echo site_url('admin/stuattendence/saveclasstime');?>" id="form_timetable">               
                        <div class="box-body">
                            <div class="mailbox-messages">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="checkbox mb0 mt0">
                                        <label for="copy_other">
                                            <input class="copy_other" id="copy_other" value="1" type="checkbox" > <?php echo $this->lang->line('copy_first_detail_for_all'); ?>
                                        </label></div>
                                    </div>
                                </div>
                            <?php 
        foreach ($class_list as $class_key => $class_value) {
            ?>
    <hr class="hrexam">
            <div class="row block_row">     
                            
                                    <div class="col-sm-4 col-lg-4 col-md-4">         
                                        <h4 class="transport_fee_line"><?php echo $class_value['class']; ?></h4>
                                    </div>
                                    <div class="col-sm-8 col-lg-8 col-md-8">                                    
                                        <div class="row">  

                                            <div class="col-sm-12 col-lg-12 col-md-12">
                                            <?php 
                                            if(!empty($class_value['sections'])){
    foreach ($class_value['sections'] as $section_key => $section_value) {   
    ?>
    <div class="row">    
        <div class="form-group col-md-6">
        <label class="control-label col-sm-3" for="time"><?php echo $section_value->section ?></label>
        <div class="col-sm-9">
            <div class="input-group">
                                            <input type="text" class="form-control datetimepicker" name="class_section_id[<?php echo $section_value->id;?>]" value
        ="<?php echo ($section_value->time !=0) ? $section_value->time :"" ?>" id="time" placeholder="Enter time">

                                            <div class="input-group-addon">
                                                <span class="fa fa-clock-o"></span>
                                            </div>
                                        </div>
            <input type="hidden" name="row[]" value="<?php echo $count; ?>">
            <input type="hidden" name="prev_record_id[<?php echo $section_value->id;?>]" value="<?php echo $section_value->class_section_times_id; ?>">  
        </div>
    </div>
    </div>
    <?php
    $count++;
    }

    }else{
        ?>
    <div class="alert alert-info">
    <?php echo $this->lang->line('no_section_found'); ?>
    </div>
        <?php
    }
                                            ?>
                                            </div>              
                                        </div>                                              
                                    </div>         
                                </div>
                                
                                <?php
        }

                            ?>
                            
                            </div><!-- /.mail-box-messages -->
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                                                    
                            <button type="submit" class="btn btn-primary pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"> <?php echo $this->lang->line('save') ?></button>
                                    
                            </div>
                        </form>
                        <?php } ?>
                    </div>
            
                    
                        <div class="nav-tabs-custom theme-shadow">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="<?php if($classid==0){ echo "active";}else{ echo ""; } ?>"><a href="#staff" data-toggle="tab" aria-expanded="true"><i class="fa fa-users"></i> <?php echo $this->lang->line('staff'); ?></a></li>
                                <li class="<?php if($classid>0 || $classid==""){ echo "active";} ?>"><a href="#student" data-toggle="tab" aria-expanded="true"><i class="fa fa-graduation-cap"></i> <?php echo $this->lang->line('student'); ?></a></li>
                            </ul>
                            <div class="tab-content pb0">
                            <div class="tab-pane <?php if($classid==0){ echo "active";}else{ echo ""; } ?>" id="staff">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><i class="fa fa-clock-o text-primary"></i> <?php echo $this->lang->line('staff_attendance_setting'); ?></h3>
                                    </div>
                                    <div class="box-body" style="background:#f8fafc; padding:20px;">
                                    <?php
                                        if (!empty($list_attendance)){ ?>

                                        <!-- Late-coming policy -->
                                        <?php $lp = $late_policy; ?>
                                        <form id="late_policy_form" class="sa-late-policy" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px; margin-bottom:16px;">
                                            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; margin-bottom:10px;">
                                                <span style="font-weight:700; font-size:13px; color:#334155;"><i class="fa fa-hourglass-end text-warning"></i> Late-coming policy</span>
                                                <label style="margin:0; display:inline-flex; align-items:center; gap:6px; font-weight:600; cursor:pointer;">
                                                    <input type="checkbox" name="late_enabled" value="1" id="lp_enabled" <?php echo !empty($lp['late_enabled']) ? 'checked' : ''; ?> style="margin:0; width:16px; height:16px;"> Apply policy
                                                </label>
                                            </div>
                                            <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
                                                <div style="flex:1 1 150px;">
                                                    <label style="font-size:12px; font-weight:600;">Free lates per month</label>
                                                    <input type="number" name="late_free" class="form-control input-sm lp-field" min="0" max="31" value="<?php echo (int) $lp['late_free']; ?>">
                                                </div>
                                                <div style="flex:1 1 150px;">
                                                    <label style="font-size:12px; font-weight:600;">Then every N lates</label>
                                                    <input type="number" name="late_every" class="form-control input-sm lp-field" min="1" max="31" value="<?php echo (int) $lp['late_every']; ?>">
                                                </div>
                                                <div style="flex:1 1 150px;">
                                                    <label style="font-size:12px; font-weight:600;">Deduct</label>
                                                    <select name="late_penalty" class="form-control input-sm lp-field">
                                                        <option value="0.5" <?php echo (float) $lp['late_penalty'] != 1.0 ? 'selected' : ''; ?>>Half day</option>
                                                        <option value="1" <?php echo (float) $lp['late_penalty'] == 1.0 ? 'selected' : ''; ?>>Full day</option>
                                                    </select>
                                                </div>
                                                <div style="flex:0 0 auto;">
                                                    <button type="submit" class="btn btn-primary btn-sm" style="height:30px;"><i class="fa fa-save"></i> Save policy</button>
                                                </div>
                                            </div>
                                            <div id="lp_preview" style="font-size:11.5px; color:#64748b; margin-top:8px;"></div>
                                        </form>

                                        <!-- Shifts list -->
                                        <?php
                                        $es = $editing_shift; $es_temp = ($es['shift_type'] === 'temporary'); $today_ymd = date('Y-m-d');
                                        $fmt_d = function ($d) { return date('d M Y', strtotime($d)); };
                                        // Which shift applies today: an active temporary shift covering today wins over the regular one.
                                        $today_temp = null; $today_reg = null;
                                        foreach ((array) $staff_shifts as $sh) {
                                            $covers = false;
                                            if (!empty($sh['shift_dates'])) {
                                                $covers = in_array($today_ymd, $sh['shift_dates'], true);
                                            } else {
                                                $covers = (empty($sh['effective_from']) || $sh['effective_from'] <= $today_ymd) && (empty($sh['effective_to']) || $sh['effective_to'] >= $today_ymd);
                                            }
                                            if (empty($sh['is_active']) || !$covers) { continue; }
                                            if ($sh['shift_type'] === 'temporary') { $today_temp = $sh; } elseif (!$today_reg) { $today_reg = $sh; }
                                        }
                                        ?>
                                        <style>
                                            .sh-panel { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:16px; margin-bottom:16px; }
                                            .sh-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:12px; }
                                            .sh-head h4 { margin:0; font-size:15px; font-weight:800; color:#0f172a; }
                                            .sh-today { background:#f0fdf4; border:1px solid #bbf7d0; color:#14532d; border-radius:8px; padding:9px 12px; font-size:12.5px; margin-bottom:12px; }
                                            .sh-today.temp { background:#f5f3ff; border-color:#ddd6fe; color:#4c1d95; }
                                            .sh-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:12px; }
                                            .sh-card { border:1px solid #e2e8f0; border-radius:10px; padding:12px 14px; display:flex; flex-direction:column; gap:8px; background:#fff; }
                                            .sh-card.editing { border-color:#2563eb; box-shadow:0 0 0 2px #dbeafe; }
                                            .sh-card.temp { border-left:4px solid #8b5cf6; }
                                            .sh-card.reg { border-left:4px solid #2563eb; }
                                            .sh-card .nm { font-size:14px; font-weight:800; color:#0f172a; display:flex; align-items:center; justify-content:space-between; gap:8px; }
                                            .sh-tag { font-size:10.5px; font-weight:700; padding:2px 8px; border-radius:20px; white-space:nowrap; }
                                            .sh-tag.reg { background:#dbeafe; color:#1d4ed8; }
                                            .sh-tag.temp { background:#ede9fe; color:#6d28d9; }
                                            .sh-meta { font-size:12px; color:#475569; line-height:1.5; }
                                            .sh-status { font-size:11.5px; font-weight:700; padding:3px 8px; border-radius:6px; align-self:flex-start; }
                                            .sh-status.on { background:#dcfce7; color:#15803d; }
                                            .sh-status.base { background:#fef9c3; color:#854d0e; }
                                            .sh-status.sched { background:#e0f2fe; color:#0369a1; }
                                            .sh-status.off { background:#f1f5f9; color:#64748b; }
                                            .sh-actions { display:flex; flex-wrap:wrap; gap:6px; margin-top:auto; }
                                            .sh-actions .btn { border-radius:6px; }
                                            .sh-form { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:16px; margin-bottom:14px; }
                                            .sh-form-title { font-size:15px; font-weight:800; color:#0f172a; margin:0 0 12px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; }
                                            .sh-fields { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px 16px; }
                                            .sh-fields label.lbl { display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px; }
                                            .sh-fields .form-control { height:36px; border-radius:6px; }
                                            .sh-type { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin:14px 0 4px; }
                                            .sh-type label { border:1.5px solid #e2e8f0; border-radius:10px; padding:10px 12px; cursor:pointer; margin:0; font-weight:400; display:flex; gap:10px; align-items:flex-start; }
                                            .sh-type input { margin-top:3px; }
                                            .sh-type b { display:block; font-size:13px; color:#0f172a; }
                                            .sh-type span { font-size:11.5px; color:#64748b; line-height:1.4; }
                                            .sh-type label.sel-reg { border-color:#2563eb; background:#eff6ff; }
                                            .sh-type label.sel-temp { border-color:#8b5cf6; background:#f5f3ff; }
                                            .sh-help { font-size:12px; color:#475569; margin-top:10px; }
                                            .sh-timings-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; margin:4px 2px 10px; }
                                            .sh-timings-head h4 { margin:0; font-size:14px; font-weight:800; color:#0f172a; }

                                            /* Slide-in Right Side Drawer for Shift Add / Edit */
                                            .shift-drawer-overlay {
                                                position: fixed;
                                                top: 0; left: 0; right: 0; bottom: 0;
                                                background: rgba(15, 23, 42, 0.55);
                                                backdrop-filter: blur(3px);
                                                z-index: 1050;
                                                opacity: 0;
                                                visibility: hidden;
                                                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                            }
                                            .shift-drawer-overlay.is-active {
                                                opacity: 1;
                                                visibility: visible;
                                            }
                                            .shift-drawer-panel {
                                                position: fixed;
                                                top: 0;
                                                right: -850px;
                                                width: 800px;
                                                max-width: 95vw;
                                                height: 100vh;
                                                max-height: 100vh;
                                                background: #ffffff;
                                                z-index: 1055;
                                                box-shadow: -10px 0 25px -5px rgba(0, 0, 0, 0.15);
                                                display: flex;
                                                flex-direction: column;
                                                overflow: hidden;
                                                transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
                                            }
                                            .shift-drawer-panel.is-open {
                                                right: 0;
                                            }
                                            .shift-drawer-header {
                                                background: #ffffff;
                                                color: #0f172a;
                                                padding: 16px 20px;
                                                border-bottom: 1px solid #e2e8f0;
                                                display: flex;
                                                align-items: center;
                                                justify-content: space-between;
                                                flex-shrink: 0;
                                            }
                                            .shift-drawer-title {
                                                margin: 0;
                                                font-size: 16px;
                                                font-weight: 800;
                                                color: #0f172a;
                                                display: flex;
                                                align-items: center;
                                                gap: 8px;
                                            }
                                            .shift-drawer-close {
                                                background: transparent;
                                                border: none;
                                                color: #64748b;
                                                font-size: 24px;
                                                cursor: pointer;
                                                line-height: 1;
                                                opacity: 0.8;
                                                transition: all 0.15s ease;
                                            }
                                            .shift-drawer-close:hover {
                                                color: #0f172a;
                                                opacity: 1;
                                            }
                                            .shift-drawer-body {
                                                padding: 20px 24px 30px 24px;
                                                overflow-y: auto !important;
                                                overflow-x: hidden;
                                                flex: 1 1 auto;
                                                background: #f8fafc;
                                                -webkit-overflow-scrolling: touch;
                                            }
                                            .shift-drawer-footer {
                                                padding: 14px 24px;
                                                background: #ffffff;
                                                border-top: 1px solid #e2e8f0;
                                                display: flex;
                                                justify-content: space-between;
                                                align-items: center;
                                                flex-shrink: 0;
                                                z-index: 10;
                                            }

                                            /* Calendar popup must sit above the drawer (z-index 1055) */
                                            .datepicker.dropdown-menu, .datepicker-dropdown { z-index: 3000 !important; }

                                            @media (max-width: 600px) { .sh-type { grid-template-columns:1fr; } }
                                        </style>

                                        <div class="sh-panel">
                                            <div class="sh-head">
                                                <h4><i class="fa fa-clock-o text-primary"></i> Shifts</h4>
                                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                                    <a href="<?php echo site_url('schsettings/attendancetype?new=regular'); ?>" class="btn btn-sm btn-primary btn-open-shift-drawer" data-type="regular" style="border-radius:6px;"><i class="fa fa-plus"></i> Regular shift</a>
                                                    <a href="<?php echo site_url('schsettings/attendancetype?new=temporary'); ?>" class="btn btn-sm btn-open-shift-drawer" data-type="temporary" style="background:#8b5cf6; color:#fff; border-radius:6px;"><i class="fa fa-bolt"></i> Temporary shift</a>
                                                </div>
                                            </div>

                                            <?php if ($today_temp) { ?>
                                                <div class="sh-today temp"><i class="fa fa-info-circle"></i> Today staff follow <b><?php echo html_escape($today_temp['name']); ?></b> (temporary, until <?php echo $fmt_d($today_temp['effective_to']); ?>).
                                                    <?php if ($today_reg) { ?>After that, <b><?php echo html_escape($today_reg['name']); ?></b> applies again.<?php } ?></div>
                                            <?php } elseif ($today_reg) { ?>
                                                <div class="sh-today"><i class="fa fa-check-circle"></i> Today staff follow <b><?php echo html_escape($today_reg['name']); ?></b>.</div>
                                            <?php } else { ?>
                                                <div class="sh-today temp" style="background:#fef2f2; border-color:#fecaca; color:#991b1b;"><i class="fa fa-exclamation-triangle"></i> No shift applies today. Activate a regular shift so attendance can be graded.</div>
                                            <?php } ?>

                                            <?php if (empty($staff_shifts)) { ?>
                                                <div class="text-muted" style="font-size:12.5px;">No shifts yet. Fill in the timings below and save to create the first regular shift.</div>
                                            <?php } else { ?>
                                            <div class="sh-grid">
                                                <?php foreach ($staff_shifts as $sh) {
                                                    $sh_temp   = $sh['shift_type'] === 'temporary';
                                                    $sh_dates  = !empty($sh['shift_dates']) ? $sh['shift_dates'] : array();
                                                    if ($sh_temp && !empty($sh_dates)) {
                                                        $sh_covers = in_array($today_ymd, $sh_dates, true);
                                                        $last_d = end($sh_dates);
                                                        $sh_ended = ($last_d < $today_ymd);
                                                    } else {
                                                        $sh_covers = (empty($sh['effective_from']) || $sh['effective_from'] <= $today_ymd) && (empty($sh['effective_to']) || $sh['effective_to'] >= $today_ymd);
                                                        $sh_ended  = !empty($sh['effective_to']) && $sh['effective_to'] < $today_ymd;
                                                    }
                                                    $sh_live   = !empty($sh['is_active']) && $sh_covers;
                                                    $is_editing = (string) $es['id'] === (string) $sh['id'];
                                                    if ($sh_live && !$sh_temp && $today_temp) { $st = array('base', 'Replaced today by ' . $today_temp['name']); }
                                                    elseif ($sh_live) { $st = array('on', 'Applies today'); }
                                                    elseif (!empty($sh['is_active']) && $sh_ended) { $st = array('off', 'Ended'); }
                                                    elseif (!empty($sh['is_active'])) {
                                                        $start_txt = !empty($sh_dates) ? reset($sh_dates) : $sh['effective_from'];
                                                        $st = array('sched', $start_txt ? 'Starts ' . $fmt_d($start_txt) : 'Active');
                                                    }
                                                    else { $st = array('off', 'Inactive'); }

                                                    if ($sh_temp && !empty($sh_dates)) {
                                                        $date_count = count($sh_dates);
                                                        $formatted_dates = array_map($fmt_d, array_slice($sh_dates, 0, 3));
                                                        $range = $date_count . ' date' . ($date_count > 1 ? 's' : '') . ': ' . implode(', ', $formatted_dates);
                                                        if ($date_count > 3) {
                                                            $range .= ' +' . ($date_count - 3) . ' more';
                                                        }
                                                    } else {
                                                        $range = (!empty($sh['effective_from']) ? $fmt_d($sh['effective_from']) : 'No start date') . ' → ' . (!empty($sh['effective_to']) ? $fmt_d($sh['effective_to']) : 'no end date');
                                                        if ($sh_temp && $sh['effective_from'] === $sh['effective_to']) { $range = $fmt_d($sh['effective_from']) . ' (1 day)'; }
                                                    }
                                                ?>
                                                    <div class="sh-card <?php echo $sh_temp ? 'temp' : 'reg'; ?> <?php echo $is_editing ? 'editing' : ''; ?>">
                                                        <div class="nm">
                                                            <span><?php echo html_escape($sh['name']); ?></span>
                                                            <span class="sh-tag <?php echo $sh_temp ? 'temp' : 'reg'; ?>"><?php echo $sh_temp ? 'Temporary' : 'Regular'; ?></span>
                                                        </div>
                                                        <span class="sh-status <?php echo $st[0]; ?>"><?php echo html_escape($st[1]); ?></span>
                                                        <div class="sh-meta">
                                                            <i class="fa fa-calendar"></i> <?php echo $range; ?><br>
                                                            <i class="fa fa-users"></i> <?php echo (int) $sh['total_roles']; ?> roles &nbsp;·&nbsp; used by <?php echo number_format((int) $sh['usage_count']); ?> attendance records
                                                        </div>
                                                        <div class="sh-actions">
                                                            <a href="<?php echo site_url('schsettings/attendancetype?shift=' . $sh['id']); ?>" class="btn btn-default btn-xs btn-edit-shift"><i class="fa fa-pencil text-primary"></i> Edit</a>
                                                            <?php if (empty($sh['is_active'])) { ?>
                                                                <button type="button" class="btn btn-success btn-xs sa-shift-status" data-id="<?php echo $sh['id']; ?>" data-active="1"><i class="fa fa-check"></i> Activate</button>
                                                                <?php if ((int) $sh['usage_count'] === 0) { ?>
                                                                    <button type="button" class="btn btn-default btn-xs sa-shift-delete" data-id="<?php echo $sh['id']; ?>" title="Delete"><i class="fa fa-trash text-danger"></i> Delete</button>
                                                                <?php } ?>
                                                            <?php } elseif ($sh_temp || !$sh_live) { ?>
                                                                <button type="button" class="btn btn-default btn-xs sa-shift-status" data-id="<?php echo $sh['id']; ?>" data-active="0"><i class="fa fa-pause text-warning"></i> Deactivate</button>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
                                        </div>

                                        <!-- Right Sidebar Drawer Overlay -->
                                        <div id="shift_drawer_overlay" class="shift-drawer-overlay"></div>

                                        <!-- Right Sidebar Drawer Panel -->
                                        <div id="shift_drawer_panel" class="shift-drawer-panel">
                                            <div class="shift-drawer-header">
                                                <h4 class="shift-drawer-title" id="shift_drawer_title">
                                                    <?php if (!empty($es['id'])) { ?>
                                                        <i class="fa fa-pencil text-primary"></i> Edit shift: <span style="color:#2563eb;"><?php echo html_escape($es['name']); ?></span>
                                                    <?php } else { ?>
                                                        <i class="fa fa-plus-circle text-success"></i> New shift
                                                    <?php } ?>
                                                </h4>
                                                <button type="button" class="shift-drawer-close" id="btn_close_shift_drawer" title="Close">&times;</button>
                                            </div>

                                            <form method="POST" action="<?php echo site_url('schsettings/savestaffsetting'); ?>" class="update" id="staff_attendance_form" style="display:flex; flex-direction:column; flex:1; min-height:0; margin:0;">
                                                <div class="shift-drawer-body">
                                                    <div class="sh-form" id="sa_banner_container" style="border:none; box-shadow:none; padding:0; background:transparent;">
                                                        <input type="hidden" name="shift_id" value="<?php echo html_escape($es['id']); ?>">

                                                        <div class="sh-type" id="staff_shift_type">
                                                            <label class="<?php echo !$es_temp ? 'sel-reg' : ''; ?>">
                                                                <input type="radio" name="shift_type" value="regular" <?php echo !$es_temp ? 'checked' : ''; ?>>
                                                                <div><b>Regular shift</b><span>Normal timings for a season (e.g. Summer, Winter). Runs from the start date until the next regular shift begins.</span></div>
                                                            </label>
                                                            <label class="<?php echo $es_temp ? 'sel-temp' : ''; ?>">
                                                                <input type="radio" name="shift_type" value="temporary" <?php echo $es_temp ? 'checked' : ''; ?>>
                                                                <div><b>Temporary shift</b><span>Special timings for a few days (PTM, exams, events). Replaces the regular shift only on these dates; attendance on those dates is re-checked.</span></div>
                                                            </label>
                                                        </div>

                                                        <div class="sh-fields" style="margin-top:12px;">
                                                            <div>
                                                                <label class="lbl" for="staff_shift_name">Shift name</label>
                                                                <input type="text" id="staff_shift_name" name="shift_name" class="form-control"
                                                                       value="<?php echo html_escape($es['name']); ?>" placeholder="e.g. Winter Timings" required>
                                                            </div>
                                                            <div id="sh_reg_dates" style="display:contents;">
                                                                <div>
                                                                    <label class="lbl" for="staff_effective_from">Start date</label>
                                                                    <input type="text" id="staff_effective_from" name="effective_from" class="form-control date"
                                                                           value="<?php echo !empty($es['effective_from']) ? date($this->customlib->getSchoolDateFormat(), strtotime($es['effective_from'])) : ''; ?>"
                                                                           placeholder="No start date">
                                                                </div>
                                                                <div>
                                                                    <label class="lbl" for="staff_effective_to">End date <span class="sh-end-hint" style="font-weight:400; color:#94a3b8;">(optional)</span></label>
                                                                    <input type="text" id="staff_effective_to" name="effective_to" class="form-control date"
                                                                           value="<?php echo !empty($es['effective_to']) ? date($this->customlib->getSchoolDateFormat(), strtotime($es['effective_to'])) : ''; ?>"
                                                                           placeholder="No end date">
                                                                </div>
                                                            </div>
                                                            <div style="display:flex; align-items:flex-end;">
                                                                <?php if (empty($es['is_active'])) { ?>
                                                                    <label style="cursor:pointer; display:inline-flex; align-items:center; gap:8px; margin:0 0 8px; font-weight:700; color:#0f172a;">
                                                                        <input type="checkbox" name="activate" value="1" checked style="margin:0; width:17px; height:17px;"> Activate when saved
                                                                    </label>
                                                                <?php } else { ?>
                                                                    <span class="sh-status on" style="margin-bottom:8px;"><i class="fa fa-check"></i> Active</span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>

                                                        <!-- Temporary Shift Multi-Date Box -->
                                                        <?php
                                                        $existing_shift_dates = !empty($es['shift_dates']) ? (array) $es['shift_dates'] : array();
                                                        if (empty($existing_shift_dates) && $es_temp && !empty($es['effective_from'])) {
                                                            $cur = $es['effective_from'];
                                                            $to_dt = !empty($es['effective_to']) ? $es['effective_to'] : $es['effective_from'];
                                                            while ($cur <= $to_dt) {
                                                                $existing_shift_dates[] = $cur;
                                                                $cur = date('Y-m-d', strtotime($cur . ' +1 day'));
                                                            }
                                                        }
                                                        $existing_shift_dates = array_values(array_unique(array_filter($existing_shift_dates)));
                                                        sort($existing_shift_dates);
                                                        ?>
                                                        <div id="sh_temp_dates_box" style="<?php echo $es_temp ? '' : 'display:none;'; ?> margin-top:14px; background:#faf5ff; border:1px solid #e9d5ff; border-radius:10px; padding:14px;">
                                                            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; margin-bottom:10px;">
                                                                <div style="font-weight:700; font-size:13px; color:#581c87;">
                                                                    <i class="fa fa-calendar-check-o" style="color:#8b5cf6;"></i> Dates Assigned to this Shift
                                                                    <span class="badge" id="temp_dates_count" style="background:#8b5cf6; margin-left:6px;"><?php echo count($existing_shift_dates); ?></span>
                                                                </div>
                                                                <div style="font-size:11.5px; color:#7e22ce;">
                                                                    <i class="fa fa-shield"></i> Recorded attendance for past dates is locked and preserved.
                                                                </div>
                                                            </div>

                                                            <!-- List of existing dates badges -->
                                                            <div id="temp_dates_container" style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:12px; min-height:30px;">
                                                                <?php if (empty($existing_shift_dates)) { ?>
                                                                    <span class="text-muted" id="temp_no_dates_msg" style="font-size:12px; font-style:italic;">No dates added yet. Add a single date or range below.</span>
                                                                <?php } else { ?>
                                                                    <?php foreach ($existing_shift_dates as $d_val) {
                                                                        $d_display = date($this->customlib->getSchoolDateFormat(), strtotime($d_val));
                                                                        $is_past = ($d_val < $today_ymd);
                                                                        $is_td   = ($d_val === $today_ymd);
                                                                    ?>
                                                                        <span class="temp-date-badge" data-date="<?php echo $d_val; ?>" style="background:#fff; border:1px solid <?php echo $is_td ? '#8b5cf6' : ($is_past ? '#cbd5e1' : '#c084fc'); ?>; color:<?php echo $is_td ? '#581c87' : ($is_past ? '#475569' : '#6b21a8'); ?>; font-weight:<?php echo $is_td ? '700' : '600'; ?>; padding:3px 10px; border-radius:15px; font-size:11.5px; display:inline-flex; align-items:center; gap:5px; box-shadow:0 1px 2px rgba(0,0,0,0.04);">
                                                                            <i class="fa <?php echo $is_td ? 'fa-clock-o' : ($is_past ? 'fa-history text-muted' : 'fa-calendar text-primary'); ?>"></i>
                                                                            <?php echo html_escape($d_display); ?>
                                                                            <?php if ($is_td) { ?><span style="font-size:9.5px; background:#ede9fe; color:#6d28d9; padding:1px 4px; border-radius:8px;">Today</span><?php } ?>
                                                                            <?php if ($is_past) { ?><span style="font-size:9.5px; background:#f1f5f9; color:#64748b; padding:1px 4px; border-radius:8px;">History</span><?php } ?>
                                                                            <input type="hidden" name="shift_dates[]" value="<?php echo html_escape($d_display); ?>">
                                                                            <?php if (!$is_past) { ?><a href="#" class="temp-date-remove" title="Remove" style="color:#b91c1c;"><i class="fa fa-times"></i></a><?php } ?>
                                                                        </span>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </div>

                                                            <!-- Controls to add new date or date range -->
                                                            <div style="background:#fff; border:1px dashed #d8b4fe; border-radius:8px; padding:10px 12px;">
                                                                <div style="font-size:12px; font-weight:700; color:#4c1d95; margin-bottom:8px;">
                                                                    <i class="fa fa-plus-circle text-primary"></i> Add Date(s) to this Shift:
                                                                </div>
                                                                <div style="display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end;">
                                                                    <div style="min-width:140px; flex:1;">
                                                                        <label style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:2px; display:block;">Date / From Date</label>
                                                                        <input type="text" id="add_shift_date_from" name="add_date_from" class="form-control date" placeholder="Pick Date" style="height:32px; font-size:12px; border-radius:6px;">
                                                                    </div>
                                                                    <div style="min-width:140px; flex:1;">
                                                                        <label style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:2px; display:block;">To Date <span style="font-weight:400; color:#94a3b8;">(leave empty for 1 day)</span></label>
                                                                        <input type="text" id="add_shift_date_to" name="add_date_to" class="form-control date" placeholder="Optional Range End" style="height:32px; font-size:12px; border-radius:6px;">
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" id="btn_add_temp_date" class="btn btn-sm" style="background:#8b5cf6; color:#fff; border-radius:6px; height:32px; font-weight:600;">
                                                                            <i class="fa fa-plus"></i> Add to Shift
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div style="font-size:11px; color:#6b7280; margin-top:6px;">
                                                                    <i class="fa fa-info-circle"></i> Pick a single date (e.g. 2 Nov), or both From & To for a range (e.g. 2 Nov to 5 Nov). You can click <b>Add to Shift</b> multiple times for all different dates (e.g. multiple Sundays or PTMs).
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="sh-help" id="sa_info_text"></div>
                                                    </div>

                                                    <div class="sh-timings-head">
                                                        <h4><i class="fa fa-sliders text-primary"></i> Timings by role</h4>
                                                        <label for="copy_staff_attendance" style="cursor:pointer; display:inline-flex; align-items:center; gap:8px; margin:0; font-weight:600; font-size:12.5px;">
                                                            <input class="copy_staff_attendance" id="copy_staff_attendance" value="1" type="checkbox" style="margin:0; width:16px; height:16px;">
                                                            <?php echo $this->lang->line('copy_first_detail_for_all'); ?>
                                                        </label>
                                                    </div>
                                                <?php $row = 1; ?>
                                                <?php foreach ($list_attendance as $list_key => $list_value){ ?>
                                                        <div class="col-md-12 staff_attendance_row">
                                                            <div class="sa-setting-card">
                                                                <div class="sa-setting-header">
                                                                    <div class="sa-role-badge">
                                                                        <i class="fa fa-id-badge"></i> <?php echo html_escape($list_value['role']); ?>
                                                                    </div>
                                                                    <span style="font-size:11.5px; color:#64748b; font-weight:600;"><i class="fa fa-calendar-check-o text-success"></i> Configured Schedule</span>
                                                                </div>
                                                                <div class="sa-setting-body">
                                                                    <?php
                                                                    $prefill = array('p' => '', 'l' => '', 'f' => '', 'sh' => '');
                                                                    $early_half_val = isset($list_value['early_half']) ? $list_value['early_half'] : '';
                                                                    $early_grace_val = isset($list_value['early_grace']) ? $list_value['early_grace'] : '';
                                                                    $day_start_val = isset($list_value['day_start']) ? $list_value['day_start'] : '';
                                                                    $carriers = '';
                                                                    if (!empty($attendance_type)) {
                                                                        foreach ($attendance_type as $att_type_value) {
                                                                            $rv = get_input_value($list_value['schedule'], $att_type_value->id, $att_type_value->key_value);
                                                                            $k = strtolower(strip_tags($att_type_value->key_value));
                                                                            if (array_key_exists($k, $prefill)) { $prefill[$k] = $rv['entry_time_to']; }
                                                                            if (empty($early_half_val) && !empty($rv['early_half_time'])) { $early_half_val = $rv['early_half_time']; }
                                                                            if (empty($early_grace_val) && !empty($rv['early_grace_time'])) { $early_grace_val = $rv['early_grace_time']; }
                                                                            $carriers .= '<input type="hidden" name="row[]" value="' . $row . '">';
                                                                            $carriers .= '<input type="hidden" name="attendance_type_id_' . $row . '" value="' . $att_type_value->id . '">';
                                                                            $carriers .= '<input type="hidden" name="role_id_' . $row . '" value="' . $list_value['role_id'] . '">';
                                                                            $carriers .= '<input type="hidden" class="hb_from hbk_' . $k . '" name="entry_time_from_' . $row . '" value="' . $rv['entry_time_from'] . '">';
                                                                            $carriers .= '<input type="hidden" class="hb_to hbk_' . $k . '" name="entry_time_to_' . $row . '" value="' . $rv['entry_time_to'] . '">';
                                                                            $carriers .= '<input type="hidden" class="hb_total hbk_' . $k . '" name="total_institute_hour_' . $row . '" value="' . $rv['total_institute_hour'] . '">';
                                                                            $carriers .= '<input type="hidden" class="hb_early_half" name="early_half_time_' . $row . '" value="' . $early_half_val . '">';
                                                                            $carriers .= '<input type="hidden" class="hb_early_grace" name="early_grace_time_' . $row . '" value="' . $early_grace_val . '">';
                                                                            $carriers .= '<input type="hidden" class="hb_day_start" name="day_start_time_' . $row . '" value="' . $day_start_val . '">';
                                                                            $row++;
                                                                        }
                                                                    }
                                                                    echo $carriers;
                                                                    $dayend_prefill = ($prefill['sh'] !== '') ? $prefill['sh'] : $prefill['f'];
                                                                    ?>
                                                                    <div class="row">
                                                                        <!-- Check-In Timing Rules -->
                                                                        <div class="col-md-6">
                                                                            <div class="sa-group-box">
                                                                                <div class="sa-group-title sa-group-in"><i class="fa fa-sign-in"></i> Morning Check-In Cutoffs</div>
                                                                                <div class="sa-field-item" style="margin-bottom:8px;">
                                                                                    <label class="sa-field-label" title="Official start of the working day">Day Start Time</label>
                                                                                    <div class="sa-time-wrap" style="max-width:180px;">
                                                                                        <input type="text" class="form-control time sa-time-control cut_daystart" value="<?php echo $day_start_val; ?>" placeholder="08:00:00">
                                                                                        <span class="fa fa-clock-o sa-time-icon" style="color:#0f766e;"></span>
                                                                                    </div>
                                                                                    <div class="sa-field-subtext">Required hours = Day End − Day Start: <strong class="sa-req-hours">—</strong></div>
                                                                                </div>
                                                                                <div class="sa-grid-3">
                                                                                    <div class="sa-field-item">
                                                                                        <label class="sa-field-label" title="Present Up To">Present Up To</label>
                                                                                        <div class="sa-time-wrap">
                                                                                            <input type="text" class="form-control time sa-time-control cut_present" value="<?php echo $prefill['p']; ?>" placeholder="08:30:00">
                                                                                            <span class="fa fa-clock-o sa-time-icon" style="color:#10b981;"></span>
                                                                                        </div>
                                                                                        <div class="sa-field-subtext">Up to this is Present</div>
                                                                                    </div>
                                                                                    <div class="sa-field-item">
                                                                                        <label class="sa-field-label" title="Late Up To">Late Up To</label>
                                                                                        <div class="sa-time-wrap">
                                                                                            <input type="text" class="form-control time sa-time-control cut_late" value="<?php echo $prefill['l']; ?>" placeholder="09:30:00">
                                                                                            <span class="fa fa-clock-o sa-time-icon" style="color:#f59e0b;"></span>
                                                                                        </div>
                                                                                        <div class="sa-field-subtext">After present up to late</div>
                                                                                    </div>
                                                                                    <div class="sa-field-item">
                                                                                        <label class="sa-field-label" title="Half Day Up To">Half Day Up To</label>
                                                                                        <div class="sa-time-wrap">
                                                                                            <input type="text" class="form-control time sa-time-control cut_halfday" value="<?php echo $prefill['f']; ?>" placeholder="10:30:00">
                                                                                            <span class="fa fa-clock-o sa-time-icon" style="color:#06b6d4;"></span>
                                                                                        </div>
                                                                                        <div class="sa-field-subtext">After late up to half day</div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Check-Out Timing Rules -->
                                                                        <div class="col-md-6">
                                                                            <div class="sa-group-box">
                                                                                <div class="sa-group-title sa-group-out"><i class="fa fa-sign-out"></i> Afternoon Check-Out / Shift Rules</div>
                                                                                <div class="sa-grid-3">
                                                                                    <div class="sa-field-item">
                                                                                        <label class="sa-field-label" title="Early End Half Up To">Early End Half Up To</label>
                                                                                        <div class="sa-time-wrap">
                                                                                            <input type="text" class="form-control time sa-time-control cut_early_half" value="<?php echo $early_half_val; ?>" placeholder="13:00:00" title="Leaving before this time is marked Half Day">
                                                                                            <span class="fa fa-clock-o sa-time-icon" style="color:#ef4444;"></span>
                                                                                        </div>
                                                                                        <div class="sa-field-subtext">Out before this = Half Day</div>
                                                                                    </div>
                                                                                    <div class="sa-field-item">
                                                                                        <label class="sa-field-label" title="Early End Grace">Early End Grace</label>
                                                                                        <div class="sa-time-wrap">
                                                                                            <input type="text" class="form-control time sa-time-control cut_early_grace" value="<?php echo $early_grace_val; ?>" placeholder="14:45:00" title="Leaving before this time counts as Shortfall / Early Out">
                                                                                            <span class="fa fa-clock-o sa-time-icon" style="color:#f59e0b;"></span>
                                                                                        </div>
                                                                                        <div class="sa-field-subtext">Out before this = Early Out</div>
                                                                                    </div>
                                                                                    <div class="sa-field-item">
                                                                                        <label class="sa-field-label" title="Day End Time">Day End Time</label>
                                                                                        <div class="sa-time-wrap">
                                                                                            <input type="text" class="form-control time sa-time-control cut_dayend" value="<?php echo $dayend_prefill; ?>" placeholder="15:00:00">
                                                                                            <span class="fa fa-clock-o sa-time-icon" style="color:#3b82f6;"></span>
                                                                                        </div>
                                                                                        <div class="sa-field-subtext">Official Shift End</div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="sa-quick-info">
                                                                        <i class="fa fa-info-circle text-primary"></i>
                                                                        <span>Check-in after <strong>Present</strong> is Late; after <strong>Late</strong> is Half Day. Leaving before <strong>Early End Half</strong> is automatically marked <strong>Half Day</strong>; total working hours are calculated against <strong>Day End</strong>.</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php } ?>
                                                </div>
                                                <div class="shift-drawer-footer">
                                                    <button type="button" class="btn btn-default" id="btn_cancel_shift_drawer" style="border-radius:6px; font-weight:600; padding:8px 18px;">
                                                        <i class="fa fa-times"></i> Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-primary" style="padding:8px 26px; font-weight:700; border-radius:8px; box-shadow:0 2px 6px rgba(37,99,235,0.3);" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $this->lang->line('update'); ?>">
                                                        <i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane <?php if($classid>0 || $classid==""){ echo "active";} ?>" id="student">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <form method="post" action="<?php echo base_url('schsettings/attendancetype');?>"  >
                                        <div class="row">
                                            <div class="col-lg-8 col-md-8 col-sm-12">
                                                <h3 class="box-title ptt10"><?php echo $this->lang->line('student_attendance_setting'); ?></h3>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                            <select autofocus="" id="class_id" name="class_id" class="form-control" onchange="this.form.submit()">
                                            <option value=""><?php echo $this->lang->line('all_classes'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {  ?>
                                            <option value="<?php echo $class['id'] ?>" <?php echo set_select('class_id', $class['id']); ?>><?php echo $class['class'] ?></option>
                                            <?php } ?>
                                            </select>
                                            </div>
                                        </div>
                                    </form>

                                    </div>
                                    <div class="box-body">
                            <?php
                            if (!empty($student_list_attendance)) {
                            ?>
                                <form method="POST" action="<?php echo site_url('admin/stuattendence/savestudentsetting'); ?>" class="student_update" id="student_attendance_form">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="checkbox mb0 mt0">
                                        <label for="copy_student_attendance">
                                            <input class="copy_student_attendance" id="copy_student_attendance" value="1" type="checkbox" > <?php echo $this->lang->line('copy_first_detail_for_all'); ?>
                                        </label></div>
                                    </div>
                                    <?php
                                    foreach ($student_list_attendance as $list_key => $list_value) {
                                    ?>
                                        <div class="col-md-12 student_attendance_row">
                                                <div class="panel panel-info">
                                                    <div class="panel-footer panel-fo border-0">
                                                                <div class="row d-flex align-items-center justify-content-between">
                                                                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                                                            <strong>                                                                
                                                                                <?php echo $this->lang->line('class'); ?>: <?php  echo $list_value['class'];  ?>
                                                                            </strong>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                                
                                                    <div class="panel-body panelheight">													
                                                        <div class="append_row paddA10">
                                                            <?php
                                                            $row = 1;
                                                            if (!empty($list_value['sections'])) {
                                                                $count = 1;
                                                                foreach ($list_value['sections'] as $student_session_key => $student_session_value) { ?>
                                                                    <div class="row">
                                                                        <div class="col-md-12">																	
                                                                            <h4><center><?php echo $this->lang->line('section'); ?>: <?php echo $student_session_value['section']; ?></center></h4>
                                                                            
                                                                                <div class="row">													
                                                                                    <div class="col-sm-3 col-lg-3 col-md-3">
                                                                                        <label for="email"><?php echo $this->lang->line('attendance_type'); ?></label>
                                                                                    </div>
                                                                                    <div class="col-sm-9 col-lg-9 col-md-9">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-4 col-lg-4 col-md-4">
                                                                                                <label for="email"><?php echo $this->lang->line('entry_from'); ?> (hh:mm:ss)</label>
                                                                                            </div>
                                                                                            <div class="col-sm-4 col-lg-4 col-md-4">
                                                                                                <label for="email"><?php echo $this->lang->line('entry_upto'); ?> (hh:mm:ss)</label>
                                                                                            </div>
                                                                                            <div class="col-sm-4 col-lg-4 col-md-4">
                                                                                                <label for="email"><?php echo $this->lang->line('total_hour'); ?></label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>														
                                                                                </div>
                                                                                
                                                            
                                                                            <?php
                                                                            if (!empty($student_attendance_type)) {
                                                                                foreach ($student_attendance_type as $att_type_key => $att_type_value) {
                                                                                    $return_value = get_student_input_value($student_session_value['student_schedule'], $att_type_value->id, $att_type_value->key_value);?>
                                                                                    <input type="hidden" name="row[]" value="<?php echo $row; ?>">
                                                                                    <input type="hidden" name="attendance_type_id_<?php echo $row; ?>" value="<?php echo $att_type_value->id; ?>">
                                                                                    <input type="hidden" name="class_section_id_<?php echo $row; ?>" value="<?php echo $student_session_value['class_section_id']; ?>">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-3 col-lg-3 col-md-3">
                                                                                            <?php echo $this->lang->line($att_type_value->long_lang_name)." (" .$att_type_value->key_value . ")"; ?>
                                                                                        </div>
                                                                                        <div class="col-sm-9 col-lg-9 col-md-9">
                                                                                            <div class="row">
                                                                                                <div class="col-sm-4 col-lg-4 col-md-4">
                                                                                                    <div class="form-group">                                                                       
                                                                                                        <div class="input-group">
                                                                                                            <input type="text" name="entry_time_from_<?php echo $row; ?>" class="form-control entry_time_from time valid" id="entry_time_from" value="<?php echo $return_value['entry_time_from']?>">
                                                                                                            <div class="input-group-addon">
                                                                                                                <span class="fa fa-clock-o"></span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-sm-4 col-lg-4 col-md-4">
                                                                                                    <div class="form-group">
                                                                                                        
                                                                                                        <div class="input-group">
                                                                                                            <input type="text" name="entry_time_to_<?php echo $row; ?>" class="form-control entry_time_to time valid" id="time_to" value="<?php echo $return_value['entry_time_to']?>">
                                                                                                            <div class="input-group-addon">
                                                                                                                <span class="fa fa-clock-o"></span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-sm-4 col-lg-4 col-md-4">
                                                                                                    <div class="form-group">
                                                                                                        <div class="input-group">
                                                                                                            <input type="text" name="total_institute_hour_<?php echo $row; ?>" class="form-control total_institute_hour time_hour valid" id="total_institute_hour" value="<?php echo $return_value['total_institute_hour']?>">
                                                                                                            <div class="input-group-addon">
                                                                                                                <span class="fa fa-clock-o"></span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    


                                                                            <?php

                                                                                    $row++; 
                                                                                } 
                                                                            }
                                                                            ?>



                                                                        </div>
                                                                    </div>
                                                            <?php
                                                                    $count++;
                                                                }
                                                            }
                                                            ?>

                                                        </div>
                                                    </div> 
                                                </div>

                                        </div>

                                    <?php
                                    }

                                    ?>
                                </div>
                                <?php if ($this->rbac->hasPrivilege('multi_class_student', 'can_edit')) { ?>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('update'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                </div>
                                <?php } ?>
                                </form>
                            <?php
                            }
                            ?>
                        </div>
                                    
                                </div>
                            </div>

                            </div>
                        </div>
                    
                </div>
                <!-- staff attandance settings -->


            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <!-- new END -->
    </div><!-- /.content-wrapper -->



    <?php
    function get_input_value($array, $find_time, $key_value = ''){
        if (!empty($array)) {
            foreach ($array as $array_key => $array_value) {
                if ($array_value->staff_attendence_type_id == $find_time) {
                    return [
                        'entry_time_from' => $array_value->entry_time_from,
                        'entry_time_to' => $array_value->entry_time_to,
                        'total_institute_hour' => $array_value->total_institute_hour,
                        'early_half_time' => (isset($array_value->early_half_time) && !empty($array_value->early_half_time) && $array_value->early_half_time !== '00:00:00') ? $array_value->early_half_time : '',
                        'early_grace_time' => (isset($array_value->early_grace_time) && !empty($array_value->early_grace_time) && $array_value->early_grace_time !== '00:00:00') ? $array_value->early_grace_time : '',
                    ];
                }
            }
        }

        $default_from = '';
        $default_to = '';
        $default_total = '';
        
        if (strtolower($key_value) == 'p') {
            $default_from = '06:00:00';
            $default_to = '08:30:00';
            $default_total = '08:30:00';
        } elseif (strtolower($key_value) == 'l') {
            $default_from = '08:30:01';
            $default_to = '11:00:00';
            $default_total = '07:30:00';
        } elseif (strtolower($key_value) == 'f' || strtolower($key_value) == 'sh') {
            $default_from = '11:00:01';
            $default_to = '16:00:00';
            $default_total = '04:15:00';
        }

        return [
            'entry_time_from' => $default_from,
            'entry_time_to' => $default_to,
            'total_institute_hour' => $default_total,
            'early_half_time' => '',
            'early_grace_time' => '',
        ];
    } ?>

    <?php

    function get_student_input_value($array, $find_time, $key_value = '')
    {
        if (!empty($array)) {
            foreach ($array as $array_key => $array_value) {
                if ($array_value->attendence_type_id == $find_time) {
                    return [
                        'entry_time_from' => $array_value->entry_time_from,
                        'entry_time_to' => $array_value->entry_time_to,
                        'total_institute_hour' =>$array_value->total_institute_hour,
                        
                    ];
                }
            }
        }

        $default_from = '';
        $default_to = '';
        $default_total = '';
        
        if (strtolower($key_value) == 'p') {
            $default_from = '06:00:00';
            $default_to = '08:30:00';
            $default_total = '07:00:00';
        } elseif (strtolower($key_value) == 'l') {
            $default_from = '08:30:01';
            $default_to = '11:00:00';
            $default_total = '06:00:00';
        } elseif (strtolower($key_value) == 'f' || strtolower($key_value) == 'sh') {
            $default_from = '11:00:01';
            $default_to = '14:30:00';
            $default_total = '03:30:00';
        }

        return [
            'entry_time_from' => $default_from,
            'entry_time_to' => $default_to,
            'total_institute_hour' => $default_total

        ];
    }
    ?>


    <script type="text/javascript">
        $('#biometric').change(function() {
            if ($(this).is(':checked')) {
                $('#save_class_time_hide_show').removeClass('hide'); 
            } else {
                $('#save_class_time_hide_show').addClass('hide');   
            }
        }); 
        
        window.onload = function(){  
            var biometric = '<?php echo $result->biometric; ?>';  
            if(biometric == '1'){
                $('#save_class_time_hide_show').removeClass('hide'); 
            }else if(biometric == '0'){
                $('#save_class_time_hide_show').addClass('hide');   
            }
        }  
    </script> 

    <script type="text/javascript">
        var base_url = '<?php echo base_url(); ?>';
    
        $(".edit_attendancetype").on('click', function (e) {
            var $this = $(this);
            $this.button('loading');
            $.ajax({
                url: '<?php echo site_url("schsettings/saveattendancetype") ?>',
                type: 'POST',
                data: $('#attendancetype_form').serialize(),
                dataType: 'json',

                success: function (data) {

                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        location.reload();
                    }

                    $this.button('reset');
                }
            });
        });

    </script>

    <script type="text/javascript">
        $('.datetimepicker').datetimepicker({
        format: 'hh:mm A',
    });

    $(document).on('submit','#form_timetable',function(e){

        // this is the id of the form


        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var actionUrl = form.attr('action');
        var submit_button = form.find(':submit');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(), // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
                        beforeSend: function () {

                            submit_button.button('loading');
                        },
                        success: function (data)
                        {

                            var message = "";
                            if (!data.status) {

                                $.each(data.error, function (index, value) {

                                    message += value;
                                });

                                errorMsg(message);

                            } else {
                                successMsg(data.message);
                            
                            }
                        },
                        error: function (xhr) { // if error occured
                            submit_button.button('reset');
                            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                        },
                        complete: function () {
                            submit_button.button('reset');
                        }
                    });    
                });


        $(document).on('change','.copy_other',function(){
            if(this.checked) {           
                var first_due= $('form#form_timetable').find('input.datetimepicker').filter(':visible:first').val();          
                $('form#form_timetable').find('.datetimepicker').val(first_due);  
                
            }
        });
    </script>



    <script type="text/javascript">
        //****staff attendance settings****//
        $(function() {
            $('.time').datetimepicker({
                format: 'HH:mm:ss'
            });
        });
        $(function() {
            $('.time_hour').datetimepicker({
                format: 'HH:mm:ss'
            });
        });

        // ---- Simplified staff schedule: derive the 4 attendance bands from a few
        // single-time cut-offs (Present up to / Late up to / Half Day up to / Day
        // End). Populates the hidden carrier inputs the backend still expects, so
        // there is NO server-side change. Scoped to staff blocks; Student tab is
        // untouched.
        function _timeToSeconds(str) {
            if (!str) return null;
            var p = ('' + str).split(':');
            if (p.length < 2) return null;
            var h = parseInt(p[0], 10), m = parseInt(p[1], 10), s = parseInt(p[2] || '0', 10);
            if (isNaN(h) || isNaN(m) || isNaN(s)) return null;
            return h * 3600 + m * 60 + s;
        }
        function _secondsToHms(sec) {
            if (sec < 0) sec = 0;
            if (sec > 86399) sec = 86399;
            var h = Math.floor(sec / 3600), m = Math.floor((sec % 3600) / 60), s = sec % 60;
            function z(n){ return (n < 10 ? '0' : '') + n; }
            return z(h) + ':' + z(m) + ':' + z(s);
        }
        function _setStaffBand($block, key, from, to, total) {
            $block.find('.hb_from.hbk_' + key).val(from);
            $block.find('.hb_to.hbk_' + key).val(to);
            $block.find('.hb_total.hbk_' + key).val(total);
        }
        function genStaffBands($block) {
            var pS = _timeToSeconds($block.find('.cut_present').val());
            var lS = _timeToSeconds($block.find('.cut_late').val());
            var fS = _timeToSeconds($block.find('.cut_halfday').val());
            var dS = _timeToSeconds($block.find('.cut_dayend').val());
            var ehVal = $block.find('.cut_early_half').val() || '';
            var egVal = $block.find('.cut_early_grace').val() || '';

            var dsVal = $block.find('.cut_daystart').val() || '';
            var sS = _timeToSeconds(dsVal);

            $block.find('.hb_early_half').val(ehVal);
            $block.find('.hb_early_grace').val(egVal);
            $block.find('.hb_day_start').val(sS === null ? '' : _secondsToHms(sS));

            if (pS === null) pS = _timeToSeconds('08:30:00');
            if (lS === null) lS = _timeToSeconds('09:30:00');
            if (fS === null) fS = _timeToSeconds('10:30:00');
            if (dS === null) dS = _timeToSeconds('15:00:00');

            // Required hours = Day End - Day Start; without a Day Start, the older Day End - Present cut-off.
            var total = _secondsToHms(Math.max(0, dS - (sS !== null ? sS : pS)));
            if (total === '00:00:00') { total = '07:30:00'; }
            $block.find('.sa-req-hours').text(total.substr(0, 5) + (sS === null ? ' (set Day Start)' : ''));

            _setStaffBand($block, 'p', '00:00:00', _secondsToHms(pS), total);
            _setStaffBand($block, 'l', _secondsToHms(pS + 1), _secondsToHms(lS), total);
            _setStaffBand($block, 'f', _secondsToHms(lS + 1), _secondsToHms(fS), total);
            _setStaffBand($block, 'sh', _secondsToHms(fS + 1), _secondsToHms(dS), total);
        }
        $(document).on('change dp.change blur', '#staff_attendance_form .cut_daystart, #staff_attendance_form .cut_present, #staff_attendance_form .cut_late, #staff_attendance_form .cut_halfday, #staff_attendance_form .cut_early_half, #staff_attendance_form .cut_early_grace, #staff_attendance_form .cut_dayend', function () {
            var $block = $(this).closest('.staff_attendance_row');
            if ($block.length) { genStaffBands($block); }
        });
        $(function () {
            $('#staff_attendance_form .staff_attendance_row').each(function () { genStaffBands($(this)); });
        });

        $(document).on('submit', '.update', function(e) {
            var submit_btn = $(this).find("button[type=submit]");
            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url = form.attr('action');
            // Regenerate hidden attendance bands from the simplified cut-offs.
            $('#staff_attendance_form .staff_attendance_row').each(function () { genStaffBands($(this)); });

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(), // serializes the form's elements.
                dataType: "json",
                beforeSend: function() {
                    submit_btn.button('loading');
                },
                success: function(data) {
                    if (data.status == 1) {
                        successMsg(data.message);
                        if (data.shift_id) {
                            setTimeout(function() { window.location.href = '<?php echo site_url('schsettings/attendancetype'); ?>?shift=' + data.shift_id; }, 700);
                        }
                    } else {
                        var message = "";
                        $.each(data.error, function(index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    }
                    submit_btn.button('reset');
                },
                error: function(xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function() {
                    submit_btn.button('reset');
                }
            });
        });

        $(document).on('submit', '.student_update', function(e) {
            var submit_btn = $(this).find("button[type=submit]");
            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(), // serializes the form's elements.
                dataType: "json",
                beforeSend: function() {
                    submit_btn.button('loading');
                },
                success: function(data) {
                    if (data.status == 1) {
                        successMsg(data.message);
                    } else {
                        var message = "";
                        $.each(data.error, function(index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    }
                    submit_btn.button('reset');
                },
                error: function(xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function() {
                    submit_btn.button('reset');
                }
            });
        });

        $(document).on('change', '.copy_staff_attendance', function() {
            if (this.checked) {
                var $blocks = $('#staff_attendance_form .staff_attendance_row');
                if (!$blocks.length) return;
                var $first = $blocks.eq(0);
                var p = $first.find('.cut_present').val();
                var l = $first.find('.cut_late').val();
                var f = $first.find('.cut_halfday').val();
                var eh = $first.find('.cut_early_half').val();
                var eg = $first.find('.cut_early_grace').val();
                var d = $first.find('.cut_dayend').val();
                var st = $first.find('.cut_daystart').val();
                $blocks.each(function () {
                    $(this).find('.cut_daystart').val(st);
                    $(this).find('.cut_present').val(p);
                    $(this).find('.cut_late').val(l);
                    $(this).find('.cut_halfday').val(f);
                    $(this).find('.cut_early_half').val(eh);
                    $(this).find('.cut_early_grace').val(eg);
                    $(this).find('.cut_dayend').val(d);
                    genStaffBands($(this));
                });
            }
        });

        $(document).on('change', '.copy_student_attendance', function() {
            if (this.checked) {
                var N = <?php echo count($student_attendance_type); ?>;
                var from_times = $('#student_attendance_form').find('.entry_time_from').slice(0, N).map(function() { return $(this).val(); }).get();
                var to_times = $('#student_attendance_form').find('.entry_time_to').slice(0, N).map(function() { return $(this).val(); }).get();
                var total_hours = $('#student_attendance_form').find('.total_institute_hour').slice(0, N).map(function() { return $(this).val(); }).get();

                $('#student_attendance_form').find('.entry_time_from').each(function(i) { $(this).val(from_times[i % N]); });
                $('#student_attendance_form').find('.entry_time_to').each(function(i) { $(this).val(to_times[i % N]); });
                $('#student_attendance_form').find('.total_institute_hour').each(function(i) { $(this).val(total_hours[i % N]); });
            }
        });

        function staffShiftTypeUi() {
            var isTemp = $('#staff_shift_type input:checked').val() === 'temporary';
            var $labels = $('#staff_shift_type label').removeClass('sel-reg sel-temp');
            $('#staff_shift_type input:checked').closest('label').addClass(isTemp ? 'sel-temp' : 'sel-reg');
            if (isTemp) {
                $('#sh_reg_dates').hide();
                $('#sh_temp_dates_box').show();
            } else {
                $('#sh_reg_dates').show();
                $('#sh_temp_dates_box').hide();
            }
            $('#staff_effective_to').attr('placeholder', isTemp ? 'Same as start date' : 'No end date');
            $('#staff_effective_from').attr('placeholder', isTemp ? 'Required' : 'No start date');
            $('.sh-end-hint').text(isTemp ? '(leave empty for a 1-day shift)' : '(optional)');
            $('#sa_info_text').html(isTemp
                ? '<i class="fa fa-info-circle" style="color:#8b5cf6;"></i> On these dates staff follow the timings below instead of the regular shift. Attendance already recorded on these dates is re-checked when you save. Hand-entered attendance is not changed.'
                : '<i class="fa fa-info-circle text-primary"></i> Set a start date to switch seasons: the current regular shift ends automatically the day before. Leave the end date empty unless the season has a fixed end.');
        }
        $(document).on('change', '#staff_shift_type input', staffShiftTypeUi);
        $(staffShiftTypeUi);

        // ---- Temporary shift dates (add single dates / ranges, remove upcoming ones) ----
        var SH_FMT = '<?php echo $this->customlib->getSchoolDateFormat(); ?>';
        var SH_MON = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        function shPad(n) { return (n < 10 ? '0' : '') + n; }
        function shParse(str) {
            // Parse a date typed in the school's date format into a local Date (or null).
            var re = '', keys = [];
            for (var i = 0; i < SH_FMT.length; i++) {
                var c = SH_FMT.charAt(i);
                if ('dmjnYyMF'.indexOf(c) > -1) {
                    keys.push(c);
                    re += (c === 'Y') ? '(\\d{4})' : ((c === 'M' || c === 'F') ? '([A-Za-z]+)' : '(\\d{1,2})');
                } else {
                    re += c.replace(/[^A-Za-z0-9\s]/g, '\\$&');
                }
            }
            var m = new RegExp('^\\s*' + re + '\\s*$').exec(str);
            if (!m) return null;
            var d = 0, mo = -1, y = 0;
            for (var k = 0; k < keys.length; k++) {
                var v = m[k + 1], key = keys[k];
                if (key === 'd' || key === 'j') d = parseInt(v, 10);
                else if (key === 'm' || key === 'n') mo = parseInt(v, 10) - 1;
                else if (key === 'M' || key === 'F') mo = SH_MON.indexOf(v.charAt(0).toUpperCase() + v.substr(1, 2).toLowerCase());
                else if (key === 'Y') y = parseInt(v, 10);
                else if (key === 'y') y = 2000 + parseInt(v, 10);
            }
            var dt = new Date(y, mo, d);
            return (mo > -1 && dt.getFullYear() === y && dt.getMonth() === mo && dt.getDate() === d) ? dt : null;
        }
        function shFormat(dt) {
            var out = '';
            for (var i = 0; i < SH_FMT.length; i++) {
                var c = SH_FMT.charAt(i);
                if (c === 'd') out += shPad(dt.getDate());
                else if (c === 'j') out += dt.getDate();
                else if (c === 'm') out += shPad(dt.getMonth() + 1);
                else if (c === 'n') out += (dt.getMonth() + 1);
                else if (c === 'M' || c === 'F') out += SH_MON[dt.getMonth()];
                else if (c === 'Y') out += dt.getFullYear();
                else if (c === 'y') out += String(dt.getFullYear()).slice(-2);
                else out += c;
            }
            return out;
        }
        function shRefreshCount() {
            var total = $('#temp_dates_container input[name="shift_dates[]"]').length;
            $('#temp_dates_count').text(total);
            $('#temp_no_dates_msg').toggle(total === 0);
        }

        $(document).on('click', '#btn_add_temp_date', function() {
            var fromVal = $.trim($('#add_shift_date_from').val());
            var toVal = $.trim($('#add_shift_date_to').val());
            if (!fromVal) { errorMsg('Please pick a date first.'); return; }
            var d1 = shParse(fromVal), d2 = toVal ? shParse(toVal) : null;
            if (!d1 || (toVal && !d2)) { errorMsg('Invalid date. Please use the date picker.'); return; }
            if (d2 && d2 < d1) { var t = d1; d1 = d2; d2 = t; }
            if (!d2) { d2 = d1; }
            if ((d2 - d1) / 86400000 > 366) { errorMsg('A range can cover at most 366 days.'); return; }

            var existing = {};
            $('#temp_dates_container input[name="shift_dates[]"]').each(function() { existing[$.trim($(this).val())] = true; });
            var added = 0;
            for (var cur = new Date(d1.getTime()); cur <= d2; cur = new Date(cur.getFullYear(), cur.getMonth(), cur.getDate() + 1)) {
                var txt = shFormat(cur);
                if (existing[txt]) continue;
                existing[txt] = true;
                var $b = $('<span class="temp-date-badge" style="background:#fff; border:1px solid #c084fc; color:#6b21a8; font-weight:600; padding:3px 10px; border-radius:15px; font-size:11.5px; display:inline-flex; align-items:center; gap:5px;"></span>');
                $b.append('<i class="fa fa-calendar text-primary"></i> ').append($('<span class="badge-txt"></span>').text(txt + ' '));
                $b.append($('<input type="hidden" name="shift_dates[]">').val(txt));
                $b.append('<span style="font-size:9.5px; background:#dcfce7; color:#15803d; padding:1px 4px; border-radius:8px;">New</span>');
                $b.append('<a href="#" class="temp-date-remove" title="Remove" style="color:#b91c1c; margin-left:2px;"><i class="fa fa-times"></i></a>');
                $('#temp_dates_container').append($b);
                added++;
            }
            shRefreshCount();
            $('#add_shift_date_from, #add_shift_date_to').val('');
            if (added > 0) { successMsg(added + ' date(s) added. Click Save to apply.'); } else { errorMsg('Date(s) already in the list.'); }
        });

        $(document).on('click', '.temp-date-remove', function(e) {
            e.preventDefault();
            $(this).closest('.temp-date-badge').remove();
            shRefreshCount();
        });

        // Late-coming policy: live example + save
        function latePolicyPreview() {
            var f = $('#late_policy_form');
            if (!f.length) return;
            var on = $('#lp_enabled').is(':checked'), free = parseInt(f.find('[name=late_free]').val() || 0, 10),
                every = Math.max(1, parseInt(f.find('[name=late_every]').val() || 1, 10)), pen = parseFloat(f.find('[name=late_penalty]').val());
            if (!on) { $('#lp_preview').html('Off: late arrivals are only counted, nothing is deducted.'); return; }
            var ex = [3, 5, 6, 9].map(function (n) { var d = Math.floor(Math.max(0, n - free) / every) * pen; return n + ' lates → ' + d + (d === 1 ? ' day' : ' days'); }).join(' &nbsp;·&nbsp; ');
            $('#lp_preview').html((free ? free + ' free per month, then ' : '') + 'every ' + every + ' late(s) = ' + (pen === 1 ? '1 day' : 'half day') + ' deducted. Example: ' + ex +
                '. Daily records stay "Late"; the deduction shows in the Monthly Sheet and register.');
        }
        $(document).on('change input', '#late_policy_form input, #late_policy_form select', latePolicyPreview);
        latePolicyPreview();
        $(document).on('submit', '#late_policy_form', function (e) {
            e.preventDefault();
            var $b = $(this).find('button[type=submit]').prop('disabled', true);
            $.post('<?php echo site_url('schsettings/savelatepolicy'); ?>', $(this).serialize(), function (res) {
                if (res.status == 1) { successMsg(res.message); } else { errorMsg(res.message); }
            }, 'json').fail(function () { errorMsg('Could not save. Please try again.'); }).always(function () { $b.prop('disabled', false); });
        });


        // Drawer Open / Close logic
        function openShiftDrawer() {
            $('#shift_drawer_overlay').addClass('is-active');
            $('#shift_drawer_panel').addClass('is-open');
            $('body').css('overflow', 'hidden');
        }

        function closeShiftDrawer() {
            $('#shift_drawer_panel').removeClass('is-open');
            $('#shift_drawer_overlay').removeClass('is-active');
            $('body').css('overflow', '');
        }

        $(document).on('click', '#btn_close_shift_drawer, #btn_cancel_shift_drawer, #shift_drawer_overlay', function(e) {
            e.preventDefault();
            closeShiftDrawer();
        });

        $(document).on('keydown', function(e) {
            if (e.keyCode === 27 && $('#shift_drawer_panel').hasClass('is-open')) {
                closeShiftDrawer();
            }
        });

        <?php if (isset($_GET['shift']) || isset($_GET['new'])) { ?>
        $(document).ready(function() {
            openShiftDrawer();
        });
        <?php } ?>
        function staffShiftAction(url, data) {
            $.post(url, data, function(res) {
                if (res.status == 1) {
                    successMsg(res.message);
                    setTimeout(function() { window.location.reload(); }, 600);
                } else {
                    errorMsg(res.message);
                }
            }, 'json').fail(function() {
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            });
        }

        $(document).on('click', '.sa-shift-status', function() {
            var active = $(this).data('active');
            if (active == 1 && !confirm('Activate this shift? For a regular shift, the currently active regular shift will be replaced.')) return;
            staffShiftAction('<?php echo site_url('schsettings/staffshiftstatus'); ?>', { shift_id: $(this).data('id'), active: active });
        });

        $(document).on('click', '.sa-shift-delete', function() {
            if (!confirm('Delete this shift? This cannot be undone.')) return;
            staffShiftAction('<?php echo site_url('schsettings/deletestaffshift'); ?>', { shift_id: $(this).data('id') });
        });
    </script>
