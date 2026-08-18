<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

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
                <a href="<?php echo base_url(); ?>admin/staff/create" class="staff-btn staff-btn-primary">
                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_staff'); ?>
                </a>
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
                                            <?php foreach ($role as $key => $role_value) { ?>
                                                <option value="<?php echo $role_value['id'] ?>"><?php echo $role_value['type'] ?></option>
                                            <?php } ?>
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

<script type="text/javascript">
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
    });
</script>