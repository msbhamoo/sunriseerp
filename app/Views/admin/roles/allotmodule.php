<style>
    .perm-role-header {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 16px;
        border: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .perm-role-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .perm-role-title h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }
    .perm-role-badge {
        background: rgba(79, 70, 229, 0.1);
        color: var(--primary-theme-color, #4f46e5);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .perm-search-box {
        position: relative;
        min-width: 280px;
    }
    .perm-search-box input {
        border-radius: 8px;
        padding-left: 36px;
        height: 38px;
        font-size: 13px;
        border: 1px solid #cbd5e1;
    }
    .perm-search-box i {
        position: absolute;
        left: 12px;
        top: 11px;
        color: #94a3b8;
        font-size: 14px;
    }
    .perm-group-header {
        background: #f8fafc !important;
        font-weight: 800;
        color: #1e293b;
        font-size: 13px;
        vertical-align: middle !important;
        border-bottom: 2px solid #e2e8f0 !important;
    }
    .perm-action-pills {
        display: flex;
        gap: 4px;
        margin-top: 6px;
    }
    .perm-action-pill {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        user-select: none;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        transition: all 0.15s ease;
    }
    .perm-action-pill:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .perm-action-pill input {
        margin: 0;
        cursor: pointer;
    }
    .perm-chk-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .perm-chk-wrapper input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary-theme-color, #4f46e5);
    }
    .table-fixed-header thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px;
        border-bottom: 2px solid #e2e8f0;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-shield"></i> Role Permissions</h1>
    </section>

    <section class="content">
        <!-- Role Header Card -->
        <div class="perm-role-header">
            <div class="perm-role-title">
                <i class="fa fa-key text-primary" style="font-size: 24px;"></i>
                <div>
                    <h3><?php echo $this->lang->line('assign_permission'); ?></h3>
                    <small class="text-muted">Configure module & feature level privileges for this role</small>
                </div>
                <span class="perm-role-badge"><i class="fa fa-user-circle-o" style="margin-right:4px;"></i> <?php echo html_escape($role['name']); ?></span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div class="perm-search-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="perm_module_search" class="form-control" placeholder="Search module or feature...">
                </div>
                <a href="<?php echo site_url('admin/roles'); ?>" class="btn btn-default btn-sm" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                </a>
            </div>
        </div>

        <!-- Live KPI Stats Grid -->
        <div class="modern-stat-grid" style="margin-bottom: 16px;">
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Total Modules</div>
                    <div class="stat-value" id="kpi_total_modules"><?php echo count($role_permission); ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                    <i class="fa fa-th-large"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">View Access</div>
                    <div class="stat-value" id="kpi_view_count" style="color: #0284c7;">0</div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(2, 132, 199, 0.12); color: #0284c7;">
                    <i class="fa fa-eye"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Add Access</div>
                    <div class="stat-value" id="kpi_add_count" style="color: #059669;">0</div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="fa fa-plus-circle"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Edit / Delete Access</div>
                    <div class="stat-value" id="kpi_edit_delete_count" style="color: #d97706;">0</div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                    <i class="fa fa-pencil-square-o"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div style="position: relative; min-height: 300px;">
                        <div class="box-body no-padding">
                        <div class="modal_loader_div" style="display: none;"></div>
                        <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="role_id" value="<?php echo $role['id'] ?>" />
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-fixed-header" id="permission_table">
                                    <thead class="header">
                                        <tr>
                                            <th style="min-width: 220px;"><?php echo $this->lang->line('module'); ?> <label class="pull-right perm-action-pill" style="margin:0;"><input type="checkbox" id="select_all_global"> Select All</label></th>
                                            <th style="min-width: 200px;"><?php echo $this->lang->line('feature'); ?></th>
                                            <th class="text-center" style="width: 110px;"><?php echo $this->lang->line('view'); ?> <label class="perm-action-pill" style="margin-left: 4px;"><input type="checkbox" class="select_column" data-column="can_view"> All</label></th>
                                            <th class="text-center" style="width: 110px;"><?php echo $this->lang->line('add'); ?> <label class="perm-action-pill" style="margin-left: 4px;"><input type="checkbox" class="select_column" data-column="can_add"> All</label></th>
                                            <th class="text-center" style="width: 110px;"><?php echo $this->lang->line('edit'); ?> <label class="perm-action-pill" style="margin-left: 4px;"><input type="checkbox" class="select_column" data-column="can_edit"> All</label></th>
                                            <th class="text-center" style="width: 110px;"><?php echo $this->lang->line('delete'); ?> <label class="perm-action-pill" style="margin-left: 4px;"><input type="checkbox" class="select_column" data-column="can_delete"> All</label></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($role_permission as $key => $value) {
                                        ?>
                                            <tr class="perm-row group-parent-<?php echo $value->id; ?>">
                                                <td class="perm-group-header">
                                                    <div style="font-weight: 700;"><?php echo $value->name ?></div>
                                                    <div class="perm-action-pills">
                                                        <label class="perm-action-pill" title="Select All in Group"><input type="checkbox" class="select_group" data-group="<?php echo $value->id; ?>"> All</label>
                                                        <label class="perm-action-pill" title="Select All View in Group"><input type="checkbox" class="select_group_col" data-group="<?php echo $value->id; ?>" data-column="can_view"> V</label>
                                                        <label class="perm-action-pill" title="Select All Add in Group"><input type="checkbox" class="select_group_col" data-group="<?php echo $value->id; ?>" data-column="can_add"> A</label>
                                                        <label class="perm-action-pill" title="Select All Edit in Group"><input type="checkbox" class="select_group_col" data-group="<?php echo $value->id; ?>" data-column="can_edit"> E</label>
                                                        <label class="perm-action-pill" title="Select All Delete in Group"><input type="checkbox" class="select_group_col" data-group="<?php echo $value->id; ?>" data-column="can_delete"> D</label>
                                                    </div>
                                                </td>
                                                <?php
                                                if (!empty($value->permission_category)) {
                                                ?>
                                                    <td>
                                                        <input type="hidden" name="per_cat[]" value="<?php echo $value->permission_category[0]->id; ?>" />
                                                        <input type="hidden" name="<?php echo "roles_permissions_id_" . $value->permission_category[0]->id; ?>" value="<?php echo $value->permission_category[0]->roles_permissions_id; ?>" />
                                                        <strong style="color: #334155;"><?php echo $value->permission_category[0]->name ?></strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($value->permission_category[0]->enable_view == 1) {
                                                        ?>
                                                            <div class="perm-chk-wrapper">
                                                                <input class="permission_chk group_<?php echo $value->id; ?>" data-action="can_view" data-role_id="<?php echo $role['id'] ?>" data-per_cat="<?php echo $value->permission_category[0]->id ?>" type="checkbox" name="<?php echo "can_view-perm_" . $value->permission_category[0]->id; ?>" value="1" <?php echo set_checkbox("can_view-perm_" . $value->permission_category[0]->id, $value->permission_category[0]->id, ($value->permission_category[0]->can_view == 1) ? true : false); ?>>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($value->permission_category[0]->enable_add == 1) {
                                                        ?>
                                                            <div class="perm-chk-wrapper">
                                                                <input class="permission_chk group_<?php echo $value->id; ?>" data-action="can_add" data-role_id="<?php echo $role['id'] ?>" data-per_cat="<?php echo $value->permission_category[0]->id ?>" type="checkbox" name="<?php echo "can_add-perm_" . $value->permission_category[0]->id; ?>" value="1" <?php echo set_checkbox("can_add-perm_" . $value->permission_category[0]->id, $value->permission_category[0]->id, ($value->permission_category[0]->can_add == 1) ? true : false); ?>>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($value->permission_category[0]->enable_edit == 1) {
                                                        ?>
                                                            <div class="perm-chk-wrapper">
                                                                <input class="permission_chk group_<?php echo $value->id; ?>" data-action="can_edit" data-role_id="<?php echo $role['id'] ?>" data-per_cat="<?php echo $value->permission_category[0]->id ?>" type="checkbox" name="<?php echo "can_edit-perm_" . $value->permission_category[0]->id; ?>" value="1" <?php echo set_checkbox("can_edit-perm_" . $value->permission_category[0]->id, $value->permission_category[0]->id, ($value->permission_category[0]->can_edit == 1) ? true : false); ?>>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($value->permission_category[0]->enable_delete == 1) {
                                                        ?>
                                                            <div class="perm-chk-wrapper">
                                                                <input class="permission_chk group_<?php echo $value->id; ?>" data-action="can_delete" data-role_id="<?php echo $role['id'] ?>" data-per_cat="<?php echo $value->permission_category[0]->id ?>" type="checkbox" name="<?php echo "can_delete-perm_" . $value->permission_category[0]->id; ?>" value="1" <?php echo set_checkbox("can_delete-perm_" . $value->permission_category[0]->id, $value->permission_category[0]->id, ($value->permission_category[0]->can_delete == 1) ? true : false); ?>>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                <?php
                                                } else {
                                                ?>
                                                    <td colspan="5"></td>
                                                <?php
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                            if (!empty($value->permission_category) && count($value->permission_category) > 1) {
                                                unset($value->permission_category[0]);
                                                foreach ($value->permission_category as $new_feature_key => $new_feature_value) {
                                            ?>
                                                    <tr class="perm-row group-child-<?php echo $value->id; ?>">
                                                        <td style="border-top: 1px dashed #f1f5f9;"></td>
                                                        <td>
                                                            <input type="hidden" name="per_cat[]" value="<?php echo $new_feature_value->id; ?>" />
                                                            <input type="hidden" name="<?php echo "roles_permissions_id_" . $new_feature_value->id; ?>" value="<?php echo $new_feature_value->roles_permissions_id; ?>" />
                                                            <span style="color: #475569; padding-left: 8px; border-left: 2px solid #cbd5e1;"><?php echo $new_feature_value->name ?></span>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            if ($new_feature_value->enable_view == 1) {
                                                            ?>
                                                                <div class="perm-chk-wrapper">
                                                                    <input class="permission_chk group_<?php echo $value->id; ?>" data-action="can_view" data-role_id="<?php echo $role['id'] ?>" data-per_cat="<?php echo $new_feature_value->id ?>" type="checkbox" name="<?php echo "can_view-perm_" . $new_feature_value->id; ?>" value="<?php echo $new_feature_value->id; ?>" <?php echo set_checkbox("can_view-perm_" . $new_feature_value->id, $new_feature_value->id, ($new_feature_value->can_view == 1) ? true : false); ?>>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            if ($new_feature_value->enable_add == 1) {
                                                            ?>
                                                                <div class="perm-chk-wrapper">
                                                                    <input class="permission_chk group_<?php echo $value->id; ?>" data-action="can_add" data-role_id="<?php echo $role['id'] ?>" data-per_cat="<?php echo $new_feature_value->id ?>" type="checkbox" name="<?php echo "can_add-perm_" . $new_feature_value->id; ?>" value="<?php echo $new_feature_value->id; ?>" <?php echo set_checkbox("can_add-perm_" . $new_feature_value->id, $new_feature_value->id, ($new_feature_value->can_add == 1) ? true : false); ?>>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            if ($new_feature_value->enable_edit == 1) {
                                                            ?>
                                                                <div class="perm-chk-wrapper">
                                                                    <input class="permission_chk group_<?php echo $value->id; ?>" data-action="can_edit" data-role_id="<?php echo $role['id'] ?>" data-per_cat="<?php echo $new_feature_value->id ?>" type="checkbox" name="<?php echo "can_edit-perm_" . $new_feature_value->id; ?>" value="<?php echo $new_feature_value->id; ?>" <?php echo set_checkbox("can_edit-perm_" . $new_feature_value->id, $new_feature_value->id, ($new_feature_value->can_edit == 1) ? true : false); ?>>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            if ($new_feature_value->enable_delete == 1) {
                                                            ?>
                                                                <div class="perm-chk-wrapper">
                                                                    <input class="permission_chk group_<?php echo $value->id; ?>" data-action="can_delete" data-role_id="<?php echo $role['id'] ?>" data-per_cat="<?php echo $new_feature_value->id ?>" type="checkbox" name="<?php echo "can_delete-perm_" . $new_feature_value->id; ?>" value="<?php echo $new_feature_value->id; ?>" <?php echo set_checkbox("can_delete-perm_" . $new_feature_value->id, $new_feature_value->id, ($new_feature_value->can_delete == 1) ? true : false); ?>>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('.table-fixed-header').fixedHeader();
    });

    (function($) {

        $.fn.fixedHeader = function(options) {
            var config = {
                topOffset: 50
                //bgColor: 'white'
            };
            if (options) {
                $.extend(config, options);
            }

            return this.each(function() {
                var o = $(this);

                var $win = $(window);
                var $head = $('thead.header', o);
                var isFixed = 0;
                var headTop = $head.length && $head.offset().top - config.topOffset;

                function processScroll() {
                    if (!o.is(':visible')) {
                        return;
                    }
                    if ($('thead.header-copy').size()) {
                        $('thead.header-copy').width($('thead.header').width());
                    }
                    var i;
                    var scrollTop = $win.scrollTop();
                    var t = $head.length && $head.offset().top - config.topOffset;
                    if (!isFixed && headTop !== t) {
                        headTop = t;
                    }
                    if (scrollTop >= headTop && !isFixed) {
                        isFixed = 1;
                    } else if (scrollTop <= headTop && isFixed) {
                        isFixed = 0;
                    }
                    isFixed ? $('thead.header-copy', o).offset({
                        left: $head.offset().left
                    }).removeClass('hide') : $('thead.header-copy', o).addClass('hide');
                }
                $win.on('scroll', processScroll);

                // hack sad times - holdover until rewrite for 2.1
                $head.on('click', function() {
                    if (!isFixed) {
                        setTimeout(function() {
                            $win.scrollTop($win.scrollTop() - 47);
                        }, 10);
                    }
                });

                $head.clone().removeClass('header').addClass('header-copy header-fixed').appendTo(o);
                var header_width = $head.width();
                o.find('thead.header-copy').width(header_width);
                o.find('thead.header > tr:first > th').each(function(i, h) {
                    var w = $(h).width();
                    o.find('thead.header-copy> tr > th:eq(' + i + ')').width(w);
                });
                $head.css({
                    margin: '0 auto',
                    width: o.width(),
                    'background-color': config.bgColor
                });
                processScroll();
            });
        };

    })(jQuery);

    $(document).ready(function() {

        $(document).on('change', '#select_all_global', function() {
            var is_checked = $(this).is(':checked');
            $('.select_group').prop('checked', is_checked);
            
            var updates = [];
            $('.permission_chk').each(function() {
                if ($(this).is(':checked') !== is_checked) {
                    $(this).prop('checked', is_checked);
                    updates.push({
                        action: $(this).data('action'),
                        per_cat: $(this).data('per_cat'),
                        role_id: $(this).data('role_id'),
                        add_remove: is_checked ? 1 : 0
                    });
                }
            });
            
            if (updates.length > 0) {
                sendBulkUpdates(updates);
            }
            syncCheckboxes();
        });

        $(document).on('change', '.select_group', function() {
            var is_checked = $(this).is(':checked');
            var group_id = $(this).data('group');
            
            // visually sync the V A E D checkboxes
            $('.select_group_col[data-group="'+group_id+'"]').prop('checked', is_checked);

            var updates = [];
            $('.group_' + group_id).each(function() {
                if ($(this).is(':checked') !== is_checked) {
                    $(this).prop('checked', is_checked);
                    updates.push({
                        action: $(this).data('action'),
                        per_cat: $(this).data('per_cat'),
                        role_id: $(this).data('role_id'),
                        add_remove: is_checked ? 1 : 0
                    });
                }
            });
            
            if (updates.length > 0) {
                sendBulkUpdates(updates);
            }
            syncCheckboxes();
        });

        $(document).on('change', '.select_group_col', function() {
            var is_checked = $(this).is(':checked');
            var group_id = $(this).data('group');
            var column_action = $(this).data('column');
            
            var updates = [];
            $('.group_' + group_id + '[data-action="' + column_action + '"]').each(function() {
                if ($(this).is(':checked') !== is_checked) {
                    $(this).prop('checked', is_checked);
                    updates.push({
                        action: $(this).data('action'),
                        per_cat: $(this).data('per_cat'),
                        role_id: $(this).data('role_id'),
                        add_remove: is_checked ? 1 : 0
                    });
                }
            });
            
            if (updates.length > 0) {
                sendBulkUpdates(updates);
            }
            syncCheckboxes();
        });

        $(document).on('change', '.select_column', function() {
            var is_checked = $(this).is(':checked');
            var column_action = $(this).data('column');
            
            var updates = [];
            $('.permission_chk[data-action="' + column_action + '"]').each(function() {
                if ($(this).is(':checked') !== is_checked) {
                    $(this).prop('checked', is_checked);
                    updates.push({
                        action: $(this).data('action'),
                        per_cat: $(this).data('per_cat'),
                        role_id: $(this).data('role_id'),
                        add_remove: is_checked ? 1 : 0
                    });
                }
            });
            
            if (updates.length > 0) {
                sendBulkUpdates(updates);
            }
            syncCheckboxes();
        });

        function sendBulkUpdates(updates) {
            $.ajax({
                type: "POST",
                url: base_url + "admin/roles/savebulk",
                data: { updates: updates },
                dataType: "json",
                beforeSend: function() {
                    $('.modal_loader_div').css("display", "block");
                },
                success: function(data) {
                    if (data.status == 0) {
                        errorMsg(data.error);
                    } else {
                        successMsg(data.message);
                    }
                    $('.modal_loader_div').fadeOut(400);
                },
                error: function(xhr) {
                    alert("<?php echo $this->lang->line('error_occured_please_try_again'); ?>");
                    $('.modal_loader_div').fadeOut(400);
                }
            });
        }

        function syncCheckboxes() {
            // Sync Group Columns (V A E D)
            $('.select_group_col').each(function() {
                var group_id = $(this).data('group');
                var column_action = $(this).data('column');
                var chks = $('.group_' + group_id + '[data-action="' + column_action + '"]');
                if(chks.length > 0) {
                    var all_checked = chks.length === chks.filter(':checked').length;
                    $(this).prop('checked', all_checked);
                }
            });
            
            // Sync Group All
            $('.select_group').each(function() {
                var group_id = $(this).data('group');
                var chks = $('.group_' + group_id);
                if(chks.length > 0) {
                    var all_checked = chks.length === chks.filter(':checked').length;
                    $(this).prop('checked', all_checked);
                }
            });

            // Sync Column All
            $('.select_column').each(function() {
                var column_action = $(this).data('column');
                var chks = $('.permission_chk[data-action="' + column_action + '"]');
                if(chks.length > 0) {
                    var all_checked = chks.length === chks.filter(':checked').length;
                    $(this).prop('checked', all_checked);
                }
            });

            // Sync Global All
            var all_chks = $('.permission_chk');
            if(all_chks.length > 0) {
                var all_checked = all_chks.length === all_chks.filter(':checked').length;
                $('#select_all_global').prop('checked', all_checked);
            }

            // Update live KPI statistics cards
            var viewCount = $('.permission_chk[data-action="can_view"]:checked').length;
            var addCount = $('.permission_chk[data-action="can_add"]:checked').length;
            var editDeleteCount = $('.permission_chk[data-action="can_edit"]:checked, .permission_chk[data-action="can_delete"]:checked').length;

            $('#kpi_view_count').text(viewCount);
            $('#kpi_add_count').text(addCount);
            $('#kpi_edit_delete_count').text(editDeleteCount);
        }

        // Initial sync on page load
        syncCheckboxes();

        $(document).on('change', '.permission_chk', function() {
       let add_remove=0;
            if ($(this).is(':checked')) {
                add_remove=1;
            } else {
                
                add_remove=0;
            }
            syncCheckboxes();

            $.ajax({
                type: "POST",
                url: base_url + "admin/roles/savecheck",
                data: {
                    'action': $(this).data('action'),
                    'per_cat': $(this).data('per_cat'),
                    'role_id': $(this).data('role_id'),
                    'add_remove': add_remove,
                },
                dataType: "json",
                beforeSend: function() {
                    $('.modal_loader_div').css("display", "block");
                },
                success: function(data)   {
                        if (data.status == 0) {
                            var message = "";
                            $.each(data.error, function (index, value) {

                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                        }
                        $('.modal_loader_div').fadeOut(400);
                       
                    },
                     error: function(xhr) { // if error occured
                       alert("<?php echo $this->lang->line('error_occured_please_try_again'); ?>");
                        $('.modal_loader_div').fadeOut(400);
                       
                     },
                    complete: function() {
                        $('#section_id').removeClass('dropdownloading');
                        $('.modal_loader_div').fadeOut(400);
                    }
                });
            });

            // Live module/feature search filter
            $('#perm_module_search').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase().trim();
            if (searchTerm === '') {
                $('.perm-row').show();
                return;
            }
            
            // Loop through each parent group
            $('tr[class*="group-parent-"]').each(function() {
                var parentRow = $(this);
                var classList = parentRow.attr('class').split(' ');
                var groupId = '';
                for (var i = 0; i < classList.length; i++) {
                    if (classList[i].indexOf('group-parent-') === 0) {
                        groupId = classList[i].replace('group-parent-', '');
                        break;
                    }
                }

                var moduleName = parentRow.find('.perm-group-header').text().toLowerCase();
                var childRows = $('.group-child-' + groupId);
                var groupMatched = moduleName.indexOf(searchTerm) > -1;
                var anyChildMatched = false;

                childRows.each(function() {
                    var childText = $(this).text().toLowerCase();
                    if (childText.indexOf(searchTerm) > -1) {
                        anyChildMatched = true;
                        $(this).show();
                    } else {
                        if (groupMatched) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                });

                if (groupMatched || anyChildMatched) {
                    parentRow.show();
                } else {
                    parentRow.hide();
                }
            });
        });
    });
</script>