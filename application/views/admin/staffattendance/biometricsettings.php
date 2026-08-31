<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-fingerprint"></i> Staff Biometric Attendance Settings & Diagnostics</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav-tabs nav">
                        <li class="active"><a href="#tab_config" data-toggle="tab"><i class="fa fa-cogs"></i> API Configuration</a></li>
                        <li><a href="#tab_mapping" data-toggle="tab"><i class="fa fa-users"></i> Staff Biometric Mapping (<span id="total-staff-count"><?php echo count($staff_list); ?></span>)</a></li>
                        <li><a href="#tab_sync" data-toggle="tab"><i class="fa fa-refresh"></i> Manual Date Sync & Logs</a></li>
                    </ul>
                    <div class="tab-content" style="padding: 20px;">
                        
                        <!-- TAB 1: API CONFIGURATION -->
                        <div class="tab-pane active" id="tab_config">
                            <?php
                            if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                            }
                            ?>
                            <form action="<?php echo site_url('admin/staffattendance/biometricsettings'); ?>" method="post" accept-charset="utf-8" id="form-bio-setting">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="save_biometric_setting" value="1">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Integration Status</label><br>
                                            <label class="checkbox-inline" style="font-weight: 600;">
                                                <input type="checkbox" name="is_enabled" value="1" <?php echo !empty($setting['is_enabled']) ? 'checked' : ''; ?>>
                                                Enable e-TimeOffice Biometric Integration
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>API Base URL</label>
                                            <input type="text" name="api_url" id="cfg_api_url" class="form-control" value="<?php echo htmlspecialchars($setting['api_url']); ?>" required>
                                            <small class="text-muted">Standard: <code>https://api.etimeoffice.com/api/</code></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Corporate ID <span class="text-danger">*</span></label>
                                            <input type="text" name="corporate_id" id="cfg_corporate_id" class="form-control" value="<?php echo htmlspecialchars($setting['corporate_id']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>API Username <span class="text-danger">*</span></label>
                                            <input type="text" name="username" id="cfg_username" class="form-control" value="<?php echo htmlspecialchars($setting['username']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>API Password</label>
                                            <input type="password" name="password" id="cfg_password" class="form-control" placeholder="Leave blank to keep existing password">
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Settings</button>
                                        <button type="button" class="btn btn-info" id="btn-test-connection" style="margin-left: 8px;"><i class="fa fa-plug"></i> Test API Connection</button>
                                        <span id="test-conn-result" style="margin-left: 12px; font-weight: 600;"></span>
                                    </div>
                                </div>

                                <hr>
                                <h4><i class="fa fa-clock-o"></i> Automated Sync & CRON Endpoint</h4>
                                <p class="text-muted">You can set up a recurring task or Windows Task Scheduler to call this endpoint every 15–30 minutes:</p>
                                <div class="well well-sm" style="background:#f8fafc; font-family:monospace; font-size:13px;">
                                    <?php echo site_url('admin/staffattendance/sync_biometric_cron?token=' . $setting['cron_token']); ?>
                                </div>
                            </form>
                        </div>

                        <!-- TAB 2: STAFF BIOMETRIC MAPPING & DIAGNOSTICS -->
                        <div class="tab-pane" id="tab_mapping">
                            <div class="row" style="margin-bottom: 14px;">
                                <div class="col-md-5">
                                    <input type="text" id="filter-mapping-search" class="form-control input-sm" placeholder="🔍 Search by staff name, role, LMS Employee ID or Machine ID...">
                                </div>
                                <div class="col-md-7 text-right">
                                    <a href="<?php echo site_url('admin/staffattendance/export_biometric_template'); ?>" class="btn btn-sm btn-success" style="margin-right: 6px;" title="Export Staff in e-TimeOffice bulk upload format"><i class="fa fa-download"></i> Export for e-TimeOffice (CSV)</a>
                                    <div class="btn-group" id="filter-status-group">
                                        <button type="button" class="btn btn-sm btn-default active" data-filter="all">All (<span class="badge"><?php echo count($staff_list); ?></span>)</button>
                                        <button type="button" class="btn btn-sm btn-default" data-filter="active"><i class="fa fa-check-circle text-success"></i> Active Punches</button>
                                        <button type="button" class="btn btn-sm btn-default" data-filter="pending"><i class="fa fa-clock-o text-warning"></i> Pending Device Upload</button>
                                        <button type="button" class="btn btn-sm btn-default" data-filter="unconfigured"><i class="fa fa-exclamation-triangle text-danger"></i> Missing ID</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="table-mapping">
                                    <thead>
                                        <tr style="background:#f8fafc;">
                                            <th width="4%">#</th>
                                            <th>Staff Name</th>
                                            <th>Role</th>
                                            <th>LMS Employee ID</th>
                                            <th>Biometric Machine Code</th>
                                            <th>Biometric Status</th>
                                            <th>Last Synced Punch</th>
                                            <th width="10%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sn = 1;
                                        foreach ($staff_list as $st) {
                                            $emp_id = trim((string)($st['employee_id'] ?? ''));
                                            $bio_id = trim((string)($st['biometric_emp_id'] ?? ''));
                                            $effective_code = ($bio_id !== '') ? $bio_id : $emp_id;
                                            $has_punch = !empty($st['last_biometric_date']);

                                            if (empty($effective_code)) {
                                                $status_class = 'unconfigured';
                                            } elseif ($has_punch) {
                                                $status_class = 'active';
                                            } else {
                                                $status_class = 'pending';
                                            }
                                        ?>
                                            <tr class="mapping-row status-<?php echo $status_class; ?>" data-staff-id="<?php echo $st['id']; ?>">
                                                <td><?php echo $sn++; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars((string)($st['name'] . ' ' . $st['surname'])); ?></strong>
                                                    <?php if (!empty($st['contact_no'])) { ?>
                                                        <br><small class="text-muted"><i class="fa fa-phone"></i> <?php echo htmlspecialchars((string)$st['contact_no']); ?></small>
                                                    <?php } ?>
                                                </td>
                                                <td><span class="label label-default"><?php echo htmlspecialchars((string)($st['role_name'] ?? '')); ?></span></td>
                                                <td><code><?php echo ($emp_id !== '') ? htmlspecialchars($emp_id) : '<span class="text-danger">Not Set</span>'; ?></code></td>
                                                <td>
                                                    <div class="input-group input-group-sm" style="max-width: 180px;">
                                                        <input type="text" class="form-control input-bio-id" value="<?php echo htmlspecialchars($bio_id); ?>" placeholder="<?php echo ($emp_id !== '') ? htmlspecialchars($emp_id) : 'Enter code'; ?>">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-save-bio-id" type="button" title="Save Custom Machine ID"><i class="fa fa-check text-success"></i></button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($status_class === 'active') { ?>
                                                        <span class="label label-success" style="font-size:11px;"><i class="fa fa-check-circle"></i> Active on Device (<?php echo htmlspecialchars($effective_code); ?>)</span>
                                                    <?php } elseif ($status_class === 'pending') { ?>
                                                        <span class="label label-warning" style="font-size:11px; background:#fff3cd; color:#856404; border:1px solid #ffeeba;"><i class="fa fa-clock-o"></i> Pending Device Upload (<?php echo htmlspecialchars($effective_code); ?>)</span>
                                                    <?php } else { ?>
                                                        <span class="label label-danger" style="font-size:11px;"><i class="fa fa-exclamation-triangle"></i> Missing Employee ID</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($st['last_biometric_date'])) { ?>
                                                        <span class="text-success"><i class="fa fa-calendar-check-o"></i> <?php echo date('d M Y', strtotime($st['last_biometric_date'])); ?></span>
                                                    <?php } else { ?>
                                                        <span class="text-muted"><i class="fa fa-minus-circle"></i> No punches on device yet</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-xs btn-primary btn-sync-single" data-staff-id="<?php echo $st['id']; ?>" title="Sync Today for Staff"><i class="fa fa-refresh"></i></button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 3: MANUAL DATE SYNC & AUDIT LOGS -->
                        <div class="tab-pane" id="tab_sync">
                            <div class="box box-solid" style="border: 1px solid #e2e8f0; border-radius: 6px;">
                                <div class="box-header with-border" style="background:#f8fafc;">
                                    <h4 class="box-title"><i class="fa fa-cloud-download"></i> Manual Date Range Synchronization</h4>
                                </div>
                                <div class="box-body" style="padding: 16px;">
                                    <form id="form-range-sync" class="form-inline">
                                        <div class="form-group" style="margin-right: 12px;">
                                            <label style="margin-right: 6px;">From Date:</label>
                                            <input type="text" name="from_date" id="sync_from_date" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly>
                                        </div>
                                        <div class="form-group" style="margin-right: 12px;">
                                            <label style="margin-right: 6px;">To Date:</label>
                                            <input type="text" name="to_date" id="sync_to_date" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly>
                                        </div>
                                        <button type="button" id="btn-trigger-range-sync" class="btn btn-primary"><i class="fa fa-refresh"></i> Fetch & Sync Punches</button>
                                        <span id="range-sync-spinner" style="display:none; margin-left: 10px;"><i class="fa fa-spinner fa-spin text-primary"></i> Contacting e-TimeOffice API...</span>
                                    </form>
                                    <div id="range-sync-alert" style="margin-top: 14px; display:none;"></div>
                                </div>
                            </div>

                            <hr>
                            <h4><i class="fa fa-history"></i> Recent Sync Logs (Audit Trail)</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="background:#f8fafc;">
                                            <th width="15%">Timestamp</th>
                                            <th width="10%">Mode</th>
                                            <th width="15%">Target Date</th>
                                            <th width="10%">Punches Fetched</th>
                                            <th width="10%">Synced Count</th>
                                            <th width="10%">Status</th>
                                            <th>Summary</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($logs)) {
                                            foreach ($logs as $lg) { ?>
                                                <tr>
                                                    <td><?php echo date('d M Y, h:i A', strtotime($lg['created_at'])); ?></td>
                                                    <td><span class="label label-info"><?php echo strtoupper($lg['sync_mode']); ?></span></td>
                                                    <td><?php echo htmlspecialchars($lg['target_date']); ?></td>
                                                    <td><strong><?php echo $lg['total_records_fetched']; ?></strong></td>
                                                    <td><span class="text-success font-weight-bold"><?php echo $lg['synced_count']; ?></span></td>
                                                    <td>
                                                        <?php if ($lg['status'] === 'success') { ?>
                                                            <span class="label label-success">Success</span>
                                                        <?php } else { ?>
                                                            <span class="label label-danger">Error</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td><small><?php echo htmlspecialchars($lg['log_summary']); ?></small></td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No synchronization logs recorded yet.</td>
                                            </tr>
                                        <?php } ?>
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

<script type="text/javascript">
(function() {
    // 1. Test Connection
    $('#btn-test-connection').on('click', function() {
        var $btn = $(this);
        var $res = $('#test-conn-result');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Testing...');
        $res.html('');

        $.post("<?php echo site_url('admin/staffattendance/test_biometric_connection_ajax'); ?>", {
            corporate_id: $('#cfg_corporate_id').val(),
            username: $('#cfg_username').val(),
            password: $('#cfg_password').val(),
            api_url: $('#cfg_api_url').val()
        }, function(data) {
            $btn.prop('disabled', false).html('<i class="fa fa-plug"></i> Test API Connection');
            if (data.status === 'success') {
                $res.html('<span class="text-success"><i class="fa fa-check-circle"></i> ' + data.message + '</span>');
            } else {
                $res.html('<span class="text-danger"><i class="fa fa-times-circle"></i> ' + data.message + '</span>');
            }
        }, 'json').fail(function() {
            $btn.prop('disabled', false).html('<i class="fa fa-plug"></i> Test API Connection');
            $res.html('<span class="text-danger"><i class="fa fa-times-circle"></i> Request failed. Check server connectivity.</span>');
        });
    });

    // 2. Filter Staff Mapping List
    $('#filter-mapping-search').on('keyup', function() {
        var q = $(this).val().toLowerCase();
        $('#table-mapping tbody tr').each(function() {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(q) > -1);
        });
    });

    $('#filter-status-group .btn').on('click', function() {
        $('#filter-status-group .btn').removeClass('btn-primary active').addClass('btn-default');
        $(this).removeClass('btn-default').addClass('btn-primary active');
        var f = $(this).data('filter');
        if (f === 'all') {
            $('#table-mapping tbody tr').show();
        } else {
            $('#table-mapping tbody tr').hide();
            $('#table-mapping tbody tr.status-' + f).show();
        }
    });

    // 3. Save Custom Biometric ID Inline
    $(document).on('click', '.btn-save-bio-id', function() {
        var $btn = $(this);
        var $row = $btn.closest('tr');
        var staff_id = $row.data('staff-id');
        var bio_id = $.trim($row.find('.input-bio-id').val());

        $btn.find('i').removeClass('fa-check text-success').addClass('fa-spinner fa-spin');
        $.post("<?php echo site_url('admin/staffattendance/save_staff_biometric_id_ajax'); ?>", {
            staff_id: staff_id,
            biometric_emp_id: bio_id
        }, function(data) {
            $btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-check text-success');
            if (data.status === 'success') {
                location.reload();
            }
        }, 'json').fail(function() {
            $btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-times text-danger');
        });
    });

    // 4. Single Staff Sync
    $(document).on('click', '.btn-sync-single', function() {
        var $btn = $(this);
        var originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.post("<?php echo site_url('admin/staffattendance/sync_biometric_ajax'); ?>", {}, function(res) {
            $btn.prop('disabled', false).html(originalHtml);
            if (res.status === 'success') {
                alert(res.message);
                location.reload();
            } else {
                alert(res.message || 'Sync failed.');
            }
        }, 'json').fail(function() {
            $btn.prop('disabled', false).html(originalHtml);
            alert('Failed to connect to server.');
        });
    });

    // 5. Trigger Manual Range Sync
    $('#btn-trigger-range-sync').on('click', function() {
        var $btn = $(this);
        var $spin = $('#range-sync-spinner');
        var $alert = $('#range-sync-alert');

        $btn.prop('disabled', true);
        $spin.show();
        $alert.hide().removeClass('alert-success alert-danger alert-info');

        $.post("<?php echo site_url('admin/staffattendance/sync_biometric_ajax'); ?>", {
            from_date: $('#sync_from_date').val(),
            to_date: $('#sync_to_date').val()
        }, function(res) {
            $btn.prop('disabled', false);
            $spin.hide();
            if (res.status === 'success') {
                $alert.addClass('alert alert-success').html('<i class="fa fa-check-circle"></i> ' + res.message).show();
                setTimeout(function() { location.reload(); }, 2000);
            } else {
                $alert.addClass('alert alert-danger').html('<i class="fa fa-times-circle"></i> ' + (res.message || 'Sync failed.')).show();
            }
        }, 'json').fail(function() {
            $btn.prop('disabled', false);
            $spin.hide();
            $alert.addClass('alert alert-danger').html('<i class="fa fa-times-circle"></i> Server communication error.').show();
        });
    });

})();
</script>
