<?php 
$logged_in_staff_id = $this->customlib->getStaffID();
$is_superadmin = ($this->customlib->getStaffRole() == '{"id":"7","name":"Super Admin"}') || ($this->rbac->hasPrivilege('superadmin', 'can_view'));
$can_add = $this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_add');
$can_edit = $this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_edit');
$can_delete = $this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_delete');

$school_setting = $this->setting_model->get();
$default_venue = isset($school_setting[0]['name']) ? $school_setting[0]['name'] : '';
?>

<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f8fafc;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Modern Card Box */
    .sc-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 18px rgba(0,0,0,0.02);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .sc-card-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sc-card-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Buttons */
    .btn-sc-primary {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 9px;
        font-weight: 700;
        font-size: 13px;
        padding: 9px 20px;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.28);
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-sc-primary:hover, .btn-sc-primary:focus {
        background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.38);
    }

    .btn-action-pill {
        border-radius: 8px !important;
        padding: 6px 11px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-action-pill:hover {
        transform: translateY(-1px);
    }

    /* Slide-over Right Drawer */
    .drawer-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(3px);
        z-index: 10000;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .drawer-backdrop.active {
        display: block;
        opacity: 1;
    }

    .ptm-drawer {
        position: fixed;
        top: 0;
        right: -620px;
        width: 600px;
        max-width: 100%;
        height: 100%;
        background: #ffffff;
        z-index: 10001;
        box-shadow: -10px 0 35px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .ptm-drawer.active {
        right: 0;
    }

    .drawer-header {
        padding: 20px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .drawer-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .drawer-close {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .drawer-close:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fecaca;
    }

    .drawer-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
    }

    .drawer-footer {
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }

    /* Form UI inside Drawer */
    .form-label-custom {
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
    }
    .form-control-custom {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 9px 13px;
        height: 40px;
        background-color: #ffffff;
        font-size: 13px;
        color: #0f172a;
        width: 100%;
        transition: all 0.2s ease;
    }
    .form-control-custom:focus {
        border-color: #0284c7;
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12);
    }

    .target-segment-container {
        display: flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 10px;
        gap: 4px;
        margin-bottom: 14px;
    }
    .target-segment-btn {
        flex: 1;
        padding: 8px 12px;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .target-segment-btn.active {
        background: #ffffff;
        color: #0284c7;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .class-selection-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
        background: #f8fafc;
        max-height: 220px;
        overflow-y: auto;
    }
    .class-row-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 8px;
    }
    .class-row-header {
        font-weight: 700;
        font-size: 12px;
        color: #1e293b;
        margin-bottom: 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notify-pill-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .notify-pill-label {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .notify-pill-label:hover {
        background: #f1f5f9;
    }
    .notify-pill-label input[type="checkbox"] {
        margin: 0;
        cursor: pointer;
    }

    /* Modern Table Elements */
    .table-modern thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: none !important;
        border-bottom: 2px solid #e2e8f0 !important;
        padding: 12px 16px !important;
    }
    .table-modern tbody td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        font-size: 13px !important;
        border-top: 1px solid #f1f5f9 !important;
        color: #1e293b;
    }
    .table-modern tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .badge-whole-school {
        background-color: #e0f2fe;
        color: #0369a1;
        font-weight: 700;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
    }
    .badge-classes {
        background-color: #fef3c7;
        color: #92400e;
        font-weight: 600;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
    }

    @media (max-width: 767px) {
        .ptm-drawer {
            width: 100% !important;
        }
        .mobile-card-table thead {
            display: none !important;
        }
        .mobile-card-table, 
        .mobile-card-table tbody, 
        .mobile-card-table tr, 
        .mobile-card-table td {
            display: block !important;
            width: 100% !important;
        }
        .mobile-card-table tr {
            background: #fff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            margin-bottom: 15px !important;
            padding: 12px 15px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
        }
        .mobile-card-table td {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 8px 0 !important;
            border: none !important;
            border-bottom: 1px dashed #edf2f7 !important;
            text-align: right !important;
            font-size: 13px !important;
        }
        .mobile-card-table td:last-child {
            border-bottom: none !important;
            padding-top: 12px !important;
            justify-content: flex-end !important;
        }
        .mobile-card-table td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #64748b;
            text-align: left;
            padding-right: 10px;
            font-size: 12px;
            text-transform: uppercase;
        }
    }
</style>

<div class="content-wrapper dashboard2-wrapper" style="min-height: 946px;">
    <!-- Top Header -->
    <section class="content-header" style="padding: 18px 20px 5px 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div>
                <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="fa fa-calendar-check-o" style="color: #0284c7;"></i> <?php echo $this->lang->line('parent_teacher_meeting'); ?>
                </h1>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <a href="<?php echo base_url('admin/ptmreports'); ?>" class="btn btn-default" style="border-radius: 9px; font-weight: 700; font-size: 13px; padding: 9px 16px; border: 1px solid #cbd5e1; color: #334155; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa fa-line-chart" style="color: #0284c7;"></i> <?php echo $this->lang->line('reports'); ?>
                </a>
                <button type="button" class="btn btn-sc-primary btn-open-add-drawer">
                    <i class="fa fa-plus-circle"></i> Add PTM
                </button>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content" style="padding: 18px 20px;">
        <?php if ($this->session->flashdata('msg')) { ?>
            <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
        <?php } ?>

        <!-- Scheduled Meetings Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="sc-card">
                    <div class="sc-card-header">
                        <h3 class="sc-card-title">
                            <i class="fa fa-list-ul" style="color: #0284c7;"></i> Scheduled Meetings
                        </h3>
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <span class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 13px; font-weight: 700; padding: 7px 14px; border-radius: 8px;">
                                Total: <?php echo !empty($ptm_list) ? count($ptm_list) : 0; ?> PTMs
                            </span>
                            <a href="<?php echo base_url('admin/ptmreports'); ?>" class="btn btn-default" style="border-radius: 9px; font-weight: 700; font-size: 12px; padding: 7px 14px; border: 1px solid #cbd5e1; color: #334155; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa fa-line-chart" style="color: #0284c7;"></i> <?php echo $this->lang->line('reports'); ?>
                            </a>
                            <button type="button" class="btn btn-sc-primary btn-open-add-drawer" style="padding: 7px 16px; font-size: 12px;">
                                <i class="fa fa-plus-circle"></i> Add PTM
                            </button>
                        </div>
                    </div>
                    <div class="box-body" style="padding: 20px;">
                        <div class="table-responsive overflow-visible">
                            <table class="table table-modern table-striped table-bordered table-hover example mobile-card-table">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('time'); ?></th>
                                        <th><?php echo $this->lang->line('venue'); ?></th>
                                        <th><?php echo $this->lang->line('target') ? $this->lang->line('target') : 'Target Audience'; ?></th>
                                        <th class="text-right" style="width: 220px;"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($ptm_list)) {
                                        foreach ($ptm_list as $ptm_item) { 
                                            $can_manage_this = ($is_superadmin || $ptm_item['created_by'] == $logged_in_staff_id);
                                        ?>
                                        <tr>
                                            <td data-label="<?php echo $this->lang->line('title'); ?>">
                                                <strong style="color: #0f172a; font-size: 14px;"><?php echo htmlspecialchars($ptm_item['title']); ?></strong>
                                                <?php if (!empty($ptm_item['description'])) { ?>
                                                    <div style="font-size: 12px; color: #64748b; margin-top: 2px; max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                        <?php echo htmlspecialchars($ptm_item['description']); ?>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                            <td data-label="<?php echo $this->lang->line('date'); ?>">
                                                <span style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600;">
                                                    <i class="fa fa-calendar-o" style="color: #0284c7;"></i>
                                                    <?php echo $this->customlib->dateformat($ptm_item['ptm_date']); ?>
                                                </span>
                                            </td>
                                            <td data-label="<?php echo $this->lang->line('time'); ?>">
                                                <span style="display: inline-flex; align-items: center; gap: 6px; color: #475569;">
                                                    <i class="fa fa-clock-o" style="color: #f59e0b;"></i>
                                                    <?php echo $ptm_item['time_from'] . ' - ' . $ptm_item['time_to']; ?>
                                                </span>
                                            </td>
                                            <td data-label="<?php echo $this->lang->line('venue'); ?>">
                                                <span style="display: inline-flex; align-items: center; gap: 6px;">
                                                    <i class="fa fa-map-marker" style="color: #ef4444;"></i>
                                                    <?php echo htmlspecialchars($ptm_item['venue']); ?>
                                                </span>
                                            </td>
                                            <td data-label="Target">
                                                <?php if ($ptm_item['target_type'] == 'whole_school') { ?>
                                                    <span class="badge-whole-school"><i class="fa fa-globe"></i> Whole School</span>
                                                <?php } else { 
                                                    $t_arr = [];
                                                    if (!empty($ptm_item['targets'])) {
                                                        foreach ($ptm_item['targets'] as $t) {
                                                            $t_arr[] = $t['class'] . " (" . $t['section'] . ")";
                                                        }
                                                    }
                                                    ?>
                                                    <span class="badge-classes"><i class="fa fa-users"></i> <?php echo !empty($t_arr) ? implode(", ", $t_arr) : 'Specific Classes'; ?></span>
                                                <?php } ?>
                                            </td>
                                            <td data-label="Action" class="text-right">
                                                <div style="display: inline-flex; gap: 6px; align-items: center;">
                                                    <?php if ($this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_view')) { ?>
                                                        <a href="<?php echo base_url('admin/ptm/attendance/' . $ptm_item['id']); ?>" class="btn btn-primary btn-action-pill" title="<?php echo $this->lang->line('mark_attendance'); ?>">
                                                            <i class="fa fa-check-square-o"></i> Attendance
                                                        </a>
                                                    <?php } ?>

                                                    <?php if ($can_edit && $can_manage_this) { ?>
                                                        <button type="button" class="btn btn-default btn-action-pill btn-edit-drawer" data-id="<?php echo $ptm_item['id']; ?>" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil text-primary"></i>
                                                        </button>
                                                    <?php } ?>

                                                    <?php if ($can_delete && $can_manage_this) { ?>
                                                        <a href="<?php echo base_url('admin/ptm/delete/' . $ptm_item['id']); ?>" class="btn btn-default btn-action-pill" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm'); ?>');">
                                                            <i class="fa fa-trash text-danger"></i>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td colspan="6" class="text-center" style="padding: 30px; color: #94a3b8;">
                                                <i class="fa fa-calendar-o" style="font-size: 28px; margin-bottom: 8px;"></i>
                                                <div style="font-size: 14px; font-weight: 600;">No Parent-Teacher Meetings scheduled yet.</div>
                                                <div style="font-size: 12px; margin-top: 4px;">Click the <strong>+ Add PTM</strong> button above to create one.</div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Drawer Backdrop -->
<div class="drawer-backdrop" id="drawerBackdrop" onclick="closePtmDrawer()"></div>

<!-- Slide-Over Right Sidebar Drawer for Add / Edit PTM -->
<div class="ptm-drawer" id="ptmFormDrawer">
    <div class="drawer-header">
        <h3 id="drawerHeading"><i class="fa fa-calendar-plus-o" style="color: #0284c7;"></i> Schedule PTM</h3>
        <div class="drawer-close" onclick="closePtmDrawer()">&times;</div>
    </div>
    
    <form id="ptmDrawerForm" action="<?php echo site_url('admin/ptm/index'); ?>" method="post" accept-charset="utf-8" style="display: flex; flex-direction: column; height: 100%; margin: 0;">
        <div class="drawer-body">
            <input type="hidden" name="id" id="drawer_ptm_id" value="">
            <?php echo $this->customlib->getCSRF(); ?>
            
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label-custom" for="drawer_title"><?php echo $this->lang->line('title'); ?> <span class="text-danger">*</span></label>
                <input id="drawer_title" name="title" placeholder="e.g. Mid-Term Parent Teacher Meeting" type="text" class="form-control-custom" required />
            </div>

            <div class="row" style="margin: 0 -8px 16px -8px;">
                <div class="col-xs-12 col-md-4" style="padding: 0 8px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label-custom" for="drawer_ptm_date"><?php echo $this->lang->line('date'); ?> <span class="text-danger">*</span></label>
                        <input id="drawer_ptm_date" name="ptm_date" type="text" class="form-control-custom date" placeholder="Select Date" readonly="readonly" required />
                    </div>
                </div>
                <div class="col-xs-6 col-md-4" style="padding: 0 8px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label-custom" for="drawer_time_from"><?php echo $this->lang->line('time_from'); ?> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input id="drawer_time_from" name="time_from" type="text" class="form-control-custom timepicker" placeholder="09:00 AM" required />
                            <div class="input-group-addon" style="border-radius: 0 8px 8px 0; border: 1px solid #cbd5e1; border-left: none; background: #f8fafc; cursor: pointer;"><i class="fa fa-clock-o text-muted"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-4" style="padding: 0 8px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label-custom" for="drawer_time_to"><?php echo $this->lang->line('time_to'); ?> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input id="drawer_time_to" name="time_to" type="text" class="form-control-custom timepicker" placeholder="01:00 PM" required />
                            <div class="input-group-addon" style="border-radius: 0 8px 8px 0; border: 1px solid #cbd5e1; border-left: none; background: #f8fafc; cursor: pointer;"><i class="fa fa-clock-o text-muted"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label-custom" for="drawer_venue"><?php echo $this->lang->line('venue'); ?> <span class="text-danger">*</span></label>
                <input id="drawer_venue" name="venue" type="text" class="form-control-custom" value="<?php echo htmlspecialchars($default_venue); ?>" required />
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label-custom"><?php echo $this->lang->line('target_audience'); ?></label>
                <div class="target-segment-container">
                    <div class="target-segment-btn active" id="seg_whole_school" onclick="setTargetType('whole_school')">
                        <i class="fa fa-globe"></i> <?php echo $this->lang->line('whole_school'); ?>
                    </div>
                    <div class="target-segment-btn" id="seg_class" onclick="setTargetType('class')">
                        <i class="fa fa-users"></i> <?php echo $this->lang->line('specific_classes'); ?>
                    </div>
                </div>
                <input type="hidden" name="target_type" id="drawer_target_type" value="whole_school">
            </div>

            <div class="form-group" id="drawer_class_section_div" style="display: none; margin-bottom: 16px;">
                <label class="form-label-custom"><?php echo $this->lang->line('class'); ?> & <?php echo $this->lang->line('section'); ?> Selection</label>
                <div class="class-selection-card">
                    <?php if (!empty($classlist)) {
                        foreach ($classlist as $class_value) { ?>
                            <div class="class-row-box">
                                <div class="class-row-header">
                                    <span><i class="fa fa-graduation-cap text-primary"></i> <?php echo htmlspecialchars($class_value['class']); ?></span>
                                    <a href="javascript:void(0)" class="text-primary" style="font-size: 11px; font-weight: 600;" onclick="toggleAllClassSections(this)">Select All</a>
                                </div>
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <?php if (!empty($class_value['sections'])) {
                                        foreach ($class_value['sections'] as $sec) { ?>
                                            <label class="checkbox-inline" style="margin: 0; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px 8px; font-size: 12px; font-weight: 500; cursor: pointer;">
                                                <input type="checkbox" class="class-sec-checkbox" name="class_section_id[]" value="<?php echo $class_value['id'] . '-' . $sec['section_id']; ?>">
                                                Section <?php echo htmlspecialchars($sec['section']); ?>
                                            </label>
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label-custom"><?php echo $this->lang->line('description'); ?></label>
                <textarea name="description" id="drawer_description" class="form-control-custom" rows="3" style="height: auto;" placeholder="Meeting objectives, agenda, or guidelines for parents..."></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 10px;">
                <label class="form-label-custom"><?php echo $this->lang->line('send_notification'); ?></label>
                <div class="notify-pill-group">
                    <label class="notify-pill-label"><input type="checkbox" name="sms" value="1"> <i class="fa fa-commenting-o text-info"></i> SMS</label>
                    <label class="notify-pill-label"><input type="checkbox" name="mail" value="1"> <i class="fa fa-envelope-o text-warning"></i> Email</label>
                    <label class="notify-pill-label"><input type="checkbox" name="mobile_app" value="1"> <i class="fa fa-mobile text-success" style="font-size: 16px;"></i> Mobile App</label>
                    <label class="notify-pill-label"><input type="checkbox" name="whatsapp" value="1"> <i class="fa fa-whatsapp text-success"></i> WhatsApp</label>
                </div>
            </div>
        </div>

        <div class="drawer-footer">
            <button type="button" class="btn btn-default" style="border-radius: 8px; font-weight: 600; padding: 8px 18px;" onclick="closePtmDrawer()"><?php echo $this->lang->line('cancel'); ?></button>
            <button type="submit" class="btn btn-sc-primary" id="btn_save_drawer">
                <i class="fa fa-check"></i> <span id="btn_save_text"><?php echo $this->lang->line('save'); ?></span>
            </button>
        </div>
    </form>
</div>

<script type="text/javascript">
    var default_venue_name = "<?php echo addslashes($default_venue); ?>";

    $(document).ready(function () {
        // Initialize timepicker
        $('.timepicker').datetimepicker({
            format: 'LT'
        });

        // Make timepicker icon clickable
        $('.input-group-addon').click(function(){
            var tp = $(this).siblings('.timepicker');
            if (tp.length > 0 && tp.data("DateTimePicker")) {
                tp.data("DateTimePicker").show();
            }
        });

        // Open Add Drawer
        $('.btn-open-add-drawer, #btn_open_add_drawer').click(function () {
            openAddPtmDrawer();
        });

        // Open Edit Drawer via AJAX
        $('.btn-edit-drawer').click(function () {
            var ptmId = $(this).data('id');
            openEditPtmDrawer(ptmId);
        });

        // Validate time before form submit
        $('#ptmDrawerForm').submit(function (e) {
            var timeFrom = $('#drawer_time_from').val();
            var timeTo = $('#drawer_time_to').val();
            
            if (timeFrom && timeTo) {
                var d1 = new Date("01/01/2000 " + timeFrom);
                var d2 = new Date("01/01/2000 " + timeTo);
                
                if (d1 >= d2) {
                    e.preventDefault();
                    errorMsg("Start time must be earlier than end time.");
                    return false;
                }
            }

            if ($('#drawer_target_type').val() === 'class') {
                if ($('.class-sec-checkbox:checked').length === 0) {
                    e.preventDefault();
                    errorMsg("Please select at least one class and section.");
                    return false;
                }
            }
        });
    });

    function openAddPtmDrawer() {
        $('#drawerHeading').html('<i class="fa fa-calendar-plus-o" style="color: #0284c7;"></i> Add PTM');
        $('#ptmDrawerForm').attr('action', '<?php echo site_url('admin/ptm/index'); ?>');
        $('#drawer_ptm_id').val('');
        $('#drawer_title').val('');
        $('#drawer_ptm_date').val('');
        $('#drawer_time_from').val('');
        $('#drawer_time_to').val('');
        $('#drawer_venue').val(default_venue_name);
        $('#drawer_description').val('');
        $('.class-sec-checkbox').prop('checked', false);
        $('input[name="sms"], input[name="mail"], input[name="mobile_app"], input[name="whatsapp"]').prop('checked', false);
        setTargetType('whole_school');
        $('#btn_save_text').text('Schedule PTM');

        $('#drawerBackdrop').addClass('active');
        $('#ptmFormDrawer').addClass('active');
    }

    function openEditPtmDrawer(ptmId) {
        $('#drawerHeading').html('<i class="fa fa-spinner fa-spin" style="color: #0284c7;"></i> Loading PTM...');
        $('#drawerBackdrop').addClass('active');
        $('#ptmFormDrawer').addClass('active');

        $.ajax({
            url: "<?php echo site_url('admin/ptm/get_ptm_detail/'); ?>" + ptmId,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.status === 1) {
                    var p = res.data;
                    $('#drawerHeading').html('<i class="fa fa-pencil-square-o" style="color: #0284c7;"></i> Edit PTM');
                    $('#ptmDrawerForm').attr('action', '<?php echo site_url('admin/ptm/edit/'); ?>' + p.id);
                    $('#drawer_ptm_id').val(p.id);
                    $('#drawer_title').val(p.title);
                    $('#drawer_ptm_date').val(p.ptm_date_formatted);
                    $('#drawer_time_from').val(p.time_from);
                    $('#drawer_time_to').val(p.time_to);
                    $('#drawer_venue').val(p.venue);
                    $('#drawer_description').val(p.description);

                    // Reset checkboxes
                    $('.class-sec-checkbox').prop('checked', false);

                    if (p.target_type === 'class' && p.targets && p.targets.length > 0) {
                        setTargetType('class');
                        $.each(p.targets, function (idx, item) {
                            var val = item.class_id + '-' + item.section_id;
                            $('.class-sec-checkbox[value="' + val + '"]').prop('checked', true);
                        });
                    } else {
                        setTargetType('whole_school');
                    }

                    $('#btn_save_text').text('Update PTM');
                } else {
                    errorMsg(res.message || "Failed to load PTM details.");
                    closePtmDrawer();
                }
            },
            error: function () {
                errorMsg("Error connecting to server.");
                closePtmDrawer();
            }
        });
    }

    function closePtmDrawer() {
        $('#drawerBackdrop').removeClass('active');
        $('#ptmFormDrawer').removeClass('active');
    }

    function setTargetType(type) {
        $('#drawer_target_type').val(type);
        if (type === 'whole_school') {
            $('#seg_whole_school').addClass('active');
            $('#seg_class').removeClass('active');
            $('#drawer_class_section_div').slideUp(200);
        } else {
            $('#seg_class').addClass('active');
            $('#seg_whole_school').removeClass('active');
            $('#drawer_class_section_div').slideDown(200);
        }
    }

    function toggleAllClassSections(btn) {
        var $box = $(btn).closest('.class-row-box');
        var $checkboxes = $box.find('.class-sec-checkbox');
        var allChecked = $checkboxes.length === $checkboxes.filter(':checked').length;
        
        $checkboxes.prop('checked', !allChecked);
        $(btn).text(allChecked ? 'Select All' : 'Deselect All');
    }
</script>
