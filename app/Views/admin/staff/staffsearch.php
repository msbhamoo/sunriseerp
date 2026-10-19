<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<script type="text/javascript">
function openStaffDrawer() {
    var b = document.getElementById('staffDrawerBackdrop');
    var d = document.getElementById('staffRightDrawer');
    if (b) {
        b.style.setProperty('display', 'block', 'important');
        b.style.setProperty('opacity', '1', 'important');
        b.style.setProperty('visibility', 'visible', 'important');
        b.style.setProperty('pointer-events', 'auto', 'important');
    }
    if (d) {
        d.style.setProperty('display', 'flex', 'important');
        d.style.setProperty('right', '0px', 'important');
        d.style.setProperty('visibility', 'visible', 'important');
    }
    document.body.style.overflow = 'hidden';
}

function closeStaffDrawer() {
    var b = document.getElementById('staffDrawerBackdrop');
    var d = document.getElementById('staffRightDrawer');
    if (b) {
        b.style.setProperty('opacity', '0', 'important');
        b.style.setProperty('visibility', 'hidden', 'important');
        b.style.setProperty('pointer-events', 'none', 'important');
        setTimeout(function() { b.style.setProperty('display', 'none', 'important'); }, 300);
    }
    if (d) {
        d.style.setProperty('right', '-750px', 'important');
    }
    document.body.style.overflow = '';
}
window.openStaffDrawer = openStaffDrawer;
window.closeStaffDrawer = closeStaffDrawer;
</script>

<style type="text/css">
.staff-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 18px;
}
.staff-page-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: -0.3px;
}
.staff-page-title i {
    color: #114B5F;
}
.staff-header-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}
.staff-btn {
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
.staff-btn:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #94a3b8;
    text-decoration: none;
}
.staff-btn-primary {
    background: #114B5F !important;
    border-color: #114B5F !important;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(17, 75, 95, 0.2) !important;
}
.staff-btn-primary:hover {
    background: #0c3847 !important;
    border-color: #0c3847 !important;
    color: #ffffff !important;
}
.staff-btn-info {
    background: #f0fdfa !important;
    border-color: #99f6e4 !important;
    color: #0f766e !important;
}
.staff-btn-info:hover {
    background: #ccfbf1 !important;
    border-color: #5eead4 !important;
    color: #115e59 !important;
}

/* Top Stat Grid */
.staff-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}
.staff-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    transition: all 0.15s ease;
}
.staff-stat-card:hover {
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
}
.staff-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.staff-stat-icon.icon-blue {
    background: #f0fdfa;
    color: #114B5F;
    border: 1px solid #99f6e4;
}
.staff-stat-icon.icon-green {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}
.staff-stat-icon.icon-purple {
    background: #fdf4ff;
    color: #9333ea;
    border: 1px solid #f0abfc;
}
.staff-stat-icon.icon-amber {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fde68a;
}
.staff-stat-label {
    font-size: 10.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.staff-stat-value {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
}

/* Filter Card */
.staff-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    margin-bottom: 20px;
    overflow: hidden;
}
.staff-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.staff-card-title {
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
.staff-card-title i {
    color: #114B5F;
}
.staff-card-body {
    padding: 18px 20px;
}
.staff-form-label {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
    display: block;
}
.staff-input, .staff-select {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    height: 38px !important;
    font-size: 13px !important;
    color: #0f172a !important;
    box-shadow: none !important;
    transition: all 0.15s ease !important;
}
.staff-input:focus, .staff-select:focus {
    border-color: #114B5F !important;
    box-shadow: 0 0 0 3px rgba(17, 75, 95, 0.12) !important;
}

/* Tabs */
.staff-tabs-nav {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #e2e8f0;
    padding: 8px 16px 0;
    background: #f8fafc;
    list-style: none;
    margin: 0;
    gap: 6px;
}
.staff-tabs-nav > li > a {
    border: 1px solid transparent !important;
    border-radius: 8px 8px 0 0 !important;
    padding: 9px 16px !important;
    font-size: 12.5px !important;
    font-weight: 600 !important;
    color: #64748b !important;
    background: transparent !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}
.staff-tabs-nav > li > a:hover {
    color: #114B5F !important;
    background: #ffffff !important;
    border-color: #e2e8f0 #e2e8f0 transparent !important;
}
.staff-tabs-nav > li.active > a {
    color: #114B5F !important;
    background: #ffffff !important;
    border-color: #e2e8f0 #e2e8f0 transparent !important;
    font-weight: 700 !important;
}
.staff-count-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* =========================================
   Modern Clean Right Slide-In Drawer UX/UI
   ========================================= */
.staff-drawer-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(15, 23, 42, 0.4) !important;
    backdrop-filter: blur(3px) !important;
    z-index: 99998 !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
    transition: opacity 0.25s ease, visibility 0.25s ease !important;
}
.staff-drawer-backdrop.active {
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important;
    display: block !important;
}
.staff-right-drawer {
    position: fixed !important;
    top: 0 !important;
    right: -620px !important;
    width: 580px !important;
    max-width: 96vw !important;
    height: 100vh !important;
    background: #ffffff !important;
    z-index: 99999 !important;
    box-shadow: -10px 0 40px rgba(15, 23, 42, 0.15) !important;
    display: flex !important;
    flex-direction: column !important;
    transition: right 0.32s cubic-bezier(0.16, 1, 0.3, 1) !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}
.staff-right-drawer.active {
    right: 0 !important;
}

/* Drawer Header */
.staff-drawer-header {
    padding: 20px 24px 18px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    flex-shrink: 0;
}
.staff-drawer-header h3 {
    margin: 0;
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: -0.3px;
}
.staff-drawer-header h3 i {
    color: #114B5F;
    font-size: 18px;
}
.staff-drawer-close {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    cursor: pointer;
    transition: all 0.15s ease;
    font-size: 14px;
}
.staff-drawer-close:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #cbd5e1;
}

/* Drawer Body */
.staff-drawer-body {
    padding: 22px 24px;
    overflow-y: auto;
    flex: 1;
    background: #ffffff;
}

/* Modern Input & Select fields inside drawer */
.drawer-input-group {
    margin-bottom: 16px;
}
.drawer-label {
    font-size: 12.5px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 7px;
    display: flex;
    align-items: center;
    gap: 3px;
}
.drawer-label .text-danger {
    color: #ef4444 !important;
    font-weight: bold;
}
.drawer-ctrl {
    width: 100% !important;
    background: #ffffff !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 10px !important;
    padding: 9px 14px !important;
    height: 42px !important;
    font-size: 13.5px !important;
    color: #0f172a !important;
    font-weight: 500 !important;
    outline: none !important;
    box-shadow: none !important;
    transition: all 0.15s ease !important;
}
.drawer-ctrl::placeholder {
    color: #94a3b8 !important;
    font-weight: 400 !important;
}
.drawer-ctrl:focus {
    border-color: #114B5F !important;
    box-shadow: 0 0 0 3.5px rgba(17, 75, 95, 0.1) !important;
    background: #ffffff !important;
}
textarea.drawer-ctrl {
    height: auto !important;
    min-height: 72px !important;
}

/* Ensure Datepicker Popup Appears Above Right Drawer */
.datepicker.datepicker-dropdown,
.bootstrap-datetimepicker-widget {
    z-index: 100005 !important;
}

/* Section Box Card */
.drawer-card-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 18px;
    margin-bottom: 18px;
}
.drawer-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}
.drawer-card-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.drawer-card-title i {
    color: #114B5F;
}

/* Drawer Footer */
.staff-drawer-footer {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    flex-shrink: 0;
}
.drawer-btn {
    padding: 9px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    border: 1px solid transparent;
    transition: all 0.15s ease;
    text-decoration: none;
}
.drawer-btn-cancel {
    background: #ffffff;
    border-color: #e2e8f0;
    color: #475569;
}
.drawer-btn-cancel:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #cbd5e1;
}
.drawer-btn-primary {
    background: #114B5F;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(17, 75, 95, 0.2);
}
.drawer-btn-primary:hover {
    background: #0d3b4b;
    color: #ffffff;
}
.drawer-btn-info {
    background: #0284c7;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(2, 132, 199, 0.2);
}
.drawer-btn-info:hover {
    background: #0369a1;
    color: #ffffff;
}
</style>

<div class="content-wrapper" style="padding: 15px 20px;">
    <!-- Page Header -->
    <div class="staff-page-header">
        <h1 class="staff-page-title">
            <i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
        </h1>
        <div class="staff-header-actions">
            <?php if ($this->rbac->hasPrivilege('staff', 'can_edit')) { ?>
                <a href="<?php echo base_url(); ?>admin/staff/bulk_update" class="staff-btn staff-btn-info">
                    <i class="fa fa-upload"></i> Bulk Update Staff
                </a>
            <?php } ?>
            <?php if ($this->rbac->hasPrivilege('staff', 'can_add')) { ?>
                <button type="button" onclick="openStaffDrawer()" class="staff-btn staff-btn-primary btn-open-staff-drawer">
                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_staff'); ?>
                </button>
            <?php } ?>
        </div>
    </div>

    <?php
    $total_staff = count($resultlist);
    $teachers_count = 0;
    $admin_count = 0;
    $other_count = 0;
    foreach ($resultlist as $s_item) {
        $r_type = strtolower((string)$s_item['user_type']);
        if (strpos($r_type, 'teacher') !== false || strpos($r_type, 'faculty') !== false) {
            $teachers_count++;
        } elseif (strpos($r_type, 'admin') !== false || strpos($r_type, 'principal') !== false) {
            $admin_count++;
        } else {
            $other_count++;
        }
    }
    ?>

    <!-- Top Statistics Tiles -->
    <div class="staff-stats-grid">
        <div class="staff-stat-card">
            <div class="staff-stat-icon icon-blue">
                <i class="fa fa-users"></i>
            </div>
            <div>
                <div class="staff-stat-label">Total Staff</div>
                <div class="staff-stat-value"><?php echo $total_staff; ?></div>
            </div>
        </div>
        <div class="staff-stat-card">
            <div class="staff-stat-icon icon-green">
                <i class="fa fa-graduation-cap"></i>
            </div>
            <div>
                <div class="staff-stat-label">Teaching Staff</div>
                <div class="staff-stat-value"><?php echo $teachers_count; ?></div>
            </div>
        </div>
        <div class="staff-stat-card">
            <div class="staff-stat-icon icon-purple">
                <i class="fa fa-user-secret"></i>
            </div>
            <div>
                <div class="staff-stat-label">Admin & Leaders</div>
                <div class="staff-stat-value"><?php echo $admin_count; ?></div>
            </div>
        </div>
        <div class="staff-stat-card">
            <div class="staff-stat-icon icon-amber">
                <i class="fa fa-briefcase"></i>
            </div>
            <div>
                <div class="staff-stat-label">Support / Other</div>
                <div class="staff-stat-value"><?php echo $other_count; ?></div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content" style="padding: 0;">
        <div class="row">
            <div class="col-md-12">
                <!-- Search & Filter Card -->
                <div class="staff-card">
                    <div class="staff-card-header">
                        <h3 class="staff-card-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('staff', 'can_add')) { ?>
                            <button type="button" onclick="openStaffDrawer()" class="staff-btn staff-btn-primary btn-open-staff-drawer" style="padding: 5px 12px; font-size: 11.5px;">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_staff'); ?>
                            </button>
                        <?php } ?>
                    </div>

                    <div class="staff-card-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                        <?php } ?>
                        
                        <form id="ajax_search_form" onsubmit="return false;">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb0">
                                        <label class="staff-form-label"><?php echo $this->lang->line("role"); ?></label>
                                        <select id="role_select" name="role_id" class="form-control staff-select">
                                            <option value=""><?php echo $this->lang->line("all") ? $this->lang->line("all") : "All Roles"; ?></option>
                                            <?php if (!empty($role)) { foreach ($role as $key => $role_value) { ?>
                                                <option value="<?php echo $role_value['id'] ?>"><?php echo $role_value['type'] ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-6">
                                    <div class="form-group mb0">
                                        <label class="staff-form-label"><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                        <div class="input-group">
                                            <input type="text" id="search_text_input" name="search_text" class="form-control staff-input" placeholder="<?php echo $this->lang->line('search_by_staff'); ?>" autocomplete="off">
                                            <span class="input-group-btn">
                                                <button type="button" id="btn_clear_search" class="btn btn-default staff-input" style="border-radius: 0 8px 8px 0 !important; height: 38px;" title="Clear Search"><i class="fa fa-times text-muted"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Results Container -->
                <div class="staff-card">
                    <div class="nav-tabs-custom border0" style="margin-bottom: 0;">
                        <ul class="staff-tabs-nav nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-th-large"></i> <?php echo $this->lang->line('card_view'); ?></a></li>
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-list"></i> <?php echo $this->lang->line('list_view'); ?></a></li>
                            <li class="pull-right" style="margin-left: auto; display: flex; align-items: center; gap: 8px; padding-bottom: 6px;">
                                <span id="search_loading_spinner" style="display: none; color: #114B5F; font-size: 12px; font-weight: 600;">
                                    <i class="fa fa-circle-o-notch fa-spin"></i> Searching...
                                </span>
                                <span id="results_counter_badge" class="staff-count-badge">
                                    <i class="fa fa-users"></i> <?php echo count($resultlist); ?> Staff Found
                                </span>
                            </li>
                        </ul>

                        <div class="tab-content" style="padding: 20px;">
                            <!-- Card View Tab -->
                            <div class="tab-pane active" id="tab_1">
                                <div id="card_view_container">
                                    <?php $this->load->view('admin/staff/_staff_card_view', array('resultlist' => $resultlist)); ?>
                                </div>
                            </div>

                            <!-- List View Tab -->
                            <div class="tab-pane" id="tab_2">
                                <div id="list_view_container">
                                    <?php $this->load->view('admin/staff/_staff_list_view', array('resultlist' => $resultlist, 'fields' => $fields)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ========================================== -->
<!-- RIGHT SIDEBAR DRAWER: ADD NEW STAFF        -->
<!-- ========================================== -->
<div class="staff-drawer-backdrop" id="staffDrawerBackdrop"></div>
<div class="staff-right-drawer" id="staffRightDrawer">
    <div class="staff-drawer-header">
        <h3><i class="fa fa-plus-circle" style="color: #114B5F;"></i> <?php echo $this->lang->line('add_staff'); ?></h3>
        <button type="button" class="staff-drawer-close" id="btnCloseStaffDrawer" title="Close Drawer">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <form id="drawer_add_staff_form" action="<?php echo site_url('admin/staff/create') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data" style="display: flex; flex-direction: column; height: calc(100vh - 65px); margin: 0;">
        <?php echo $this->customlib->getCSRF(); ?>
        
        <div class="staff-drawer-body">
            
            <!-- Basic Information Card Box -->
            <div class="drawer-card-box">
                <div class="drawer-card-header">
                    <h4 class="drawer-card-title"><i class="fa fa-user-circle"></i> <?php echo $this->lang->line('basic_information'); ?></h4>
                </div>

                <div class="row">
                    <?php if (isset($staffid_auto_insert) && !$staffid_auto_insert) { ?>
                        <div class="col-sm-6">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('staff_id'); ?> <span class="text-danger">*</span></label>
                                <input id="drawer_employee_id" name="employee_id" type="text" class="drawer-ctrl" placeholder="e.g. STF-1001" value="<?php echo set_value('employee_id') ?>" required />
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-sm-6">
                        <div class="drawer-input-group">
                            <label class="drawer-label"><?php echo $this->lang->line('role'); ?> <span class="text-danger">*</span></label>
                            <select id="drawer_role" name="role" class="drawer-ctrl" required>
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php if (!empty($roles)) { foreach ($roles as $key => $role_item) { ?>
                                    <option value="<?php echo $role_item['id'] ?>"><?php echo $role_item["name"] ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>

                    <?php if (!empty($sch_setting->staff_designation)) { ?>
                        <div class="col-sm-6">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('designation'); ?></label>
                                <select id="drawer_designation" name="designation" class="drawer-ctrl">
                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                    <?php if (!empty($designation)) { foreach ($designation as $key => $value) { ?>
                                        <option value="<?php echo $value["id"] ?>"><?php echo $value["designation"] ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!empty($sch_setting->staff_department)) { ?>
                        <div class="col-sm-6">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('department'); ?></label>
                                <select id="drawer_department" name="department" class="drawer-ctrl">
                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                    <?php if (!empty($department)) { foreach ($department as $key => $value) { ?>
                                        <option value="<?php echo $value["id"] ?>"><?php echo $value["department_name"] ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-sm-6">
                        <div class="drawer-input-group">
                            <label class="drawer-label"><?php echo $this->lang->line('first_name'); ?> <span class="text-danger">*</span></label>
                            <input id="drawer_name" name="name" type="text" class="drawer-ctrl" placeholder="e.g. John" required />
                        </div>
                    </div>

                    <?php if (!empty($sch_setting->staff_last_name)) { ?>
                        <div class="col-sm-6">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('last_name'); ?></label>
                                <input id="drawer_surname" name="surname" type="text" class="drawer-ctrl" placeholder="e.g. Doe" />
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-sm-6">
                        <div class="drawer-input-group">
                            <label class="drawer-label"><?php echo $this->lang->line('email'); ?> <span class="text-danger">*</span></label>
                            <input id="drawer_email" name="email" type="email" class="drawer-ctrl" placeholder="john.doe@example.com" required />
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="drawer-input-group">
                            <label class="drawer-label"><?php echo $this->lang->line('gender'); ?> <span class="text-danger">*</span></label>
                            <select class="drawer-ctrl" name="gender" required>
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php if (!empty($genderList)) { foreach ($genderList as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="drawer-input-group">
                            <label class="drawer-label"><?php echo $this->lang->line('date_of_birth'); ?> <span class="text-danger">*</span></label>
                            <input id="drawer_dob" name="dob" type="text" class="drawer-ctrl date" placeholder="DD/MM/YYYY" required />
                        </div>
                    </div>

                    <?php if (!empty($sch_setting->staff_phone)) { ?>
                        <div class="col-sm-6">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('phone'); ?></label>
                                <input id="drawer_contactno" name="contactno" type="text" class="drawer-ctrl" placeholder="e.g. +91 9876543210" />
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!empty($sch_setting->staff_date_of_joining)) { ?>
                        <div class="col-sm-6">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('date_of_joining'); ?></label>
                                <input id="drawer_date_of_joining" name="date_of_joining" type="text" class="drawer-ctrl date" placeholder="DD/MM/YYYY" />
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!empty($sch_setting->staff_marital_status)) { ?>
                        <div class="col-sm-6">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('marital_status'); ?></label>
                                <select class="drawer-ctrl" name="marital_status">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php if (!empty($marital_status)) { foreach ($marital_status as $makey => $mavalue) { ?>
                                        <option value="<?php echo $mavalue ?>"><?php echo $mavalue; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!empty($sch_setting->staff_emergency_contact)) { ?>
                        <div class="col-sm-6">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('emergency_contact_number'); ?></label>
                                <input id="drawer_emergency_no" name="emergency_no" type="text" class="drawer-ctrl" placeholder="e.g. +91 9876543210" />
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!empty($sch_setting->staff_photo)) { ?>
                        <div class="col-sm-6">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('photo'); ?></label>
                                <input class="drawer-ctrl" type='file' name='file' id="drawer_file" style="padding: 6px 10px !important;" />
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!empty($sch_setting->staff_qualification)) { ?>
                        <div class="col-sm-6">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('qualification'); ?></label>
                                <select id="drawer_qualification" name="qualification" class="drawer-ctrl">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php if (!empty($qualification_list)) { foreach ($qualification_list as $q) { ?>
                                        <option value="<?php echo htmlspecialchars($q['qualification_name']); ?>"><?php echo htmlspecialchars($q['qualification_name']); ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!empty($sch_setting->staff_work_experience)) { ?>
                        <div class="col-sm-6">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('work_experience'); ?></label>
                                <select id="drawer_work_exp" name="work_exp" class="drawer-ctrl">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php if (!empty($workexperience_list)) { foreach ($workexperience_list as $e) { ?>
                                        <option value="<?php echo htmlspecialchars($e['work_experience']); ?>"><?php echo htmlspecialchars($e['work_experience']); ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!empty($sch_setting->staff_current_address)) { ?>
                        <div class="col-sm-12">
                            <div class="drawer-input-group">
                                <label class="drawer-label"><?php echo $this->lang->line('current'); ?> <?php echo $this->lang->line('address'); ?></label>
                                <textarea name="address" class="drawer-ctrl" rows="2" placeholder="Enter current address..."></textarea>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Optional Additional Details Card Box -->
            <div class="drawer-card-box">
                <div class="drawer-card-header" style="margin-bottom: 0;">
                    <a data-toggle="collapse" href="#drawerCollapseMore" class="collapsed" style="display: flex; align-items: center; justify-content: space-between; width: 100%; text-decoration: none; color: #1e293b;">
                        <h4 class="drawer-card-title"><i class="fa fa-sliders"></i> Payroll, Bank & Leaves Allocation</h4>
                        <i class="fa fa-chevron-down" style="font-size: 11px; color: #64748b;"></i>
                    </a>
                </div>

                <div id="drawerCollapseMore" class="collapse" style="margin-top: 14px; padding-top: 14px; border-top: 1px dashed #cbd5e1;">
                    <div class="row">
                        <?php if (!empty($sch_setting->staff_basic_salary)) { ?>
                            <div class="col-sm-6">
                                <div class="drawer-input-group">
                                    <label class="drawer-label"><?php echo $this->lang->line('basic_salary'); ?></label>
                                    <input type="text" class="drawer-ctrl" name="basic_salary" placeholder="0.00" />
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (!empty($sch_setting->staff_contract_type)) { ?>
                            <div class="col-sm-6">
                                <div class="drawer-input-group">
                                    <label class="drawer-label"><?php echo $this->lang->line('contract_type'); ?></label>
                                    <select class="drawer-ctrl" name="contract_type">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php if (!empty($contract_type)) { foreach ($contract_type as $key => $value) { ?>
                                            <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (!empty($sch_setting->staff_account_details)) { ?>
                            <div class="col-sm-6">
                                <div class="drawer-input-group">
                                    <label class="drawer-label"><?php echo $this->lang->line('bank_account_number'); ?></label>
                                    <input id="drawer_bank_account_no" name="bank_account_no" type="text" class="drawer-ctrl" placeholder="Account Number" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="drawer-input-group">
                                    <label class="drawer-label"><?php echo $this->lang->line('bank_name'); ?></label>
                                    <input id="drawer_bank_name" name="bank_name" type="text" class="drawer-ctrl" placeholder="Bank Name" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="drawer-input-group">
                                    <label class="drawer-label"><?php echo $this->lang->line('ifsc_code'); ?></label>
                                    <input id="drawer_ifsc_code" name="ifsc_code" type="text" class="drawer-ctrl" placeholder="IFSC Code" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="drawer-input-group">
                                    <label class="drawer-label"><?php echo $this->lang->line('bank_branch_name'); ?></label>
                                    <input id="drawer_bank_branch" name="bank_branch" type="text" class="drawer-ctrl" placeholder="Branch Name" />
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (!empty($sch_setting->staff_leaves) && !empty($leavetypeList)) { ?>
                            <?php foreach ($leavetypeList as $key => $leave) { ?>
                                <div class="col-sm-6">
                                    <div class="drawer-input-group">
                                        <label class="drawer-label"><?php echo $leave["type"]; ?> Leaves</label>
                                        <input name="leave_type[]" type="hidden" readonly value="<?php echo $leave['id'] ?>" />
                                        <input name="alloted_leave_<?php echo $leave['id'] ?>" placeholder="Allotted Leaves Count" type="number" class="drawer-ctrl" />
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modern Footer Buttons -->
        <div class="staff-drawer-footer">
            <button type="button" class="drawer-btn drawer-btn-cancel" id="btnCancelStaffDrawer">
                <?php echo $this->lang->line('cancel'); ?>
            </button>
            <button type="submit" id="btnSubmitStaffDrawer" class="drawer-btn drawer-btn-primary">
                <i class="fa fa-check"></i> <?php echo $this->lang->line('save'); ?>
            </button>
        </div>
    </form>
</div>

<script type="text/javascript">
    // Pure Vanilla JS functions for instant execution
    window.openStaffDrawer = function() {
        console.log("openStaffDrawer called");
        var backdrop = document.getElementById('staffDrawerBackdrop');
        var drawer = document.getElementById('staffRightDrawer');
        if (backdrop) {
            backdrop.classList.add('active');
            backdrop.style.setProperty('display', 'block', 'important');
            backdrop.style.setProperty('opacity', '1', 'important');
            backdrop.style.setProperty('visibility', 'visible', 'important');
            backdrop.style.setProperty('pointer-events', 'auto', 'important');
        }
        if (drawer) {
            drawer.classList.add('active');
            drawer.style.setProperty('display', 'flex', 'important');
            drawer.style.setProperty('right', '0px', 'important');
            drawer.style.setProperty('visibility', 'visible', 'important');
        }
        document.body.style.overflow = 'hidden';
    };

    window.closeStaffDrawer = function() {
        console.log("closeStaffDrawer called");
        var backdrop = document.getElementById('staffDrawerBackdrop');
        var drawer = document.getElementById('staffRightDrawer');
        if (backdrop) {
            backdrop.classList.remove('active');
            backdrop.style.setProperty('opacity', '0', 'important');
            backdrop.style.setProperty('visibility', 'hidden', 'important');
            backdrop.style.setProperty('pointer-events', 'none', 'important');
            setTimeout(function() {
                if (!backdrop.classList.contains('active')) {
                    backdrop.style.setProperty('display', 'none', 'important');
                }
            }, 300);
        }
        if (drawer) {
            drawer.classList.remove('active');
            drawer.style.setProperty('right', '-750px', 'important');
        }
        document.body.style.overflow = '';
    };

    // Global listener on window
    window.addEventListener('click', function(e) {
        var btn = e.target.closest ? e.target.closest('.btn-open-staff-drawer') : null;
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            window.openStaffDrawer();
            return;
        }

        var closeBtn = e.target.closest ? e.target.closest('#btnCloseStaffDrawer, #btnCancelStaffDrawer') : null;
        if (closeBtn || (e.target && e.target.id === 'staffDrawerBackdrop')) {
            e.preventDefault();
            e.stopPropagation();
            window.closeStaffDrawer();
            return;
        }
    }, true);

    window.addEventListener('keydown', function(e) {
        if (e.key === "Escape" || e.keyCode === 27) {
            window.closeStaffDrawer();
        }
    });

    $(document).ready(function () {
        var debounceTimer;
        var base_url = '<?php echo base_url(); ?>';

        function performAjaxSearch() {
            var role_id = $('#role_select').val();
            var search_text = $('#search_text_input').val();
            var csrf_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
            var csrf_hash = '<?php echo $this->security->get_csrf_hash(); ?>';

            $('#search_loading_spinner').show();

            var post_data = {
                'role_id': role_id,
                'search_text': search_text,
                'is_active': 1
            };
            post_data[csrf_name] = csrf_hash;

            $.ajax({
                type: "POST",
                url: base_url + "admin/staff/ajaxsearch",
                data: post_data,
                dataType: "json",
                success: function (data) {
                    $('#search_loading_spinner').hide();
                    if (data.status === 'success') {
                        $('#card_view_container').html(data.card_html);
                        $('#list_view_container').html(data.list_html);
                        $('#results_counter_badge').html('<i class="fa fa-users"></i> ' + data.count + ' Staff Found');
                    }
                },
                error: function () {
                    $('#search_loading_spinner').hide();
                }
            });
        }

        $('#role_select').on('change', function () {
            performAjaxSearch();
        });

        $('#search_text_input').on('input keyup', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                performAjaxSearch();
            }, 300);
        });

        $('#btn_clear_search').on('click', function () {
            $('#search_text_input').val('');
            $('#role_select').val('');
            performAjaxSearch();
        });

        // Initialize Datepickers for Drawer inputs
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        if ($.fn.datepicker) {
            $('#drawer_dob, #drawer_date_of_joining, .staff-right-drawer .date').datepicker({
                format: date_format,
                autoclose: true,
                todayHighlight: true
            });
        }

        $('#drawer_add_staff_form').on('submit', function() {
            $('#btnSubmitStaffDrawer').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        });
    });
</script>