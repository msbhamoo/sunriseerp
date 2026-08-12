<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-qrcode"></i> QR Attendance Settings</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Configure QR-based Staff Attendance</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/staffattendance/qrdisplay'); ?>" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-desktop"></i> Open Display QR</a>
                        </div>
                    </div>
                    <form action="<?php echo site_url('admin/staffattendance/qrsettings'); ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php
                            if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                            }
                            ?>
                            <input type="hidden" name="save_qr_setting" value="1">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="is_enabled" value="1" <?php echo !empty($setting['is_enabled']) ? 'checked' : ''; ?>>
                                            Enable QR Attendance
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>QR Mode</label>
                                        <select name="qr_mode" class="form-control">
                                            <option value="daily"  <?php echo ($setting['qr_mode'] === 'daily')  ? 'selected' : ''; ?>>Daily (new code each day)</option>
                                            <option value="static" <?php echo ($setting['qr_mode'] === 'static') ? 'selected' : ''; ?>>Static (fixed code)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <label>
                                            <input type="checkbox" name="regenerate_static" value="1">
                                            Regenerate static code on save
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h4>Scan Rules</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Re-scan cooldown (minutes)</label>
                                        <input type="number" min="0" name="rescan_cooldown_minutes" class="form-control" value="<?php echo htmlspecialchars($setting['rescan_cooldown_minutes']); ?>">
                                        <small class="text-muted">Rapid re-scans within this window are ignored (prevents accidental double-scan).</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Earliest exit time source</label>
                                        <select name="earliest_out_source" class="form-control">
                                            <option value="schedule" <?php echo ($setting['earliest_out_source'] === 'schedule') ? 'selected' : ''; ?>>From role schedule (in-time + institute hours)</option>
                                            <option value="manual"   <?php echo ($setting['earliest_out_source'] === 'manual')   ? 'selected' : ''; ?>>Manual fixed time</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Manual earliest exit time</label>
                                        <input type="time" name="manual_earliest_out_time" class="form-control" value="<?php echo htmlspecialchars($setting['manual_earliest_out_time']); ?>">
                                        <small class="text-muted">Used only when source is "Manual". Scanning out before this asks for confirmation.</small>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h4>Location Restriction (Anti-spoofing)</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Allowed IP addresses / prefixes</label>
                                        <input type="text" name="ip_allowlist" class="form-control" placeholder="e.g. 203.0.113.10, 203.0.113." value="<?php echo htmlspecialchars($setting['ip_allowlist']); ?>">
                                        <small class="text-muted">Comma-separated. Exact IPs or prefixes ending in a dot (203.0.113. matches the whole range). Leave blank to allow any network.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="gps_enabled" value="1" <?php echo !empty($setting['gps_enabled']) ? 'checked' : ''; ?>>
                                            Require GPS proximity
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>School latitude</label>
                                        <input type="text" name="gps_lat" class="form-control" value="<?php echo htmlspecialchars($setting['gps_lat']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>School longitude</label>
                                        <input type="text" name="gps_lng" class="form-control" value="<?php echo htmlspecialchars($setting['gps_lng']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Allowed radius (metres)</label>
                                        <input type="number" min="10" name="gps_radius_m" class="form-control" value="<?php echo htmlspecialchars($setting['gps_radius_m']); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
