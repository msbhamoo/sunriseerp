<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="text-align:center;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-qrcode"></i> Staff Attendance QR</h3>
                    </div>
                    <div class="box-body">
                        <?php if (empty($setting['is_enabled'])) { ?>
                            <div class="alert alert-warning">
                                QR attendance is currently <strong>disabled</strong>.
                                <a href="<?php echo site_url('admin/staffattendance/qrsettings'); ?>">Enable it in settings</a> before displaying this code.
                            </div>
                        <?php } ?>

                        <p class="text-muted">
                            Mode: <strong><?php echo ucfirst($setting['qr_mode']); ?></strong>
                            &nbsp;|&nbsp; Generated: <strong><?php echo $generated_at; ?></strong>
                        </p>

                        <img id="qr-img" src="<?php echo $qr_base64; ?>" alt="Attendance QR" style="width:320px;max-width:90%;height:auto;border:8px solid #fff;box-shadow:0 0 12px rgba(0,0,0,.15);">

                        <?php if ($setting['qr_mode'] === 'dynamic') { ?>
                            <p style="margin-top:12px;"><span id="qr-countdown" class="text-muted"></span></p>
                        <?php } ?>

                        <p style="margin-top:20px;font-size:16px;">
                            Staff: log in to your account &rarr; <strong>Mark My Attendance</strong> &rarr; scan this code.
                        </p>

                        <?php if ($setting['qr_mode'] === 'daily') { ?>
                            <p class="text-muted"><small>This code is valid for today only and refreshes automatically at midnight.</small></p>
                        <?php } elseif ($setting['qr_mode'] === 'dynamic') { ?>
                            <p class="text-muted"><small>This code rotates automatically every <?php echo (int) $setting['dynamic_interval_seconds']; ?> seconds. Keep this screen open.</small></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    // Daily mode: reload after midnight so the displayed code always matches
    // today's token. Also refresh periodically as a safety net.
    (function () {
        var isDaily = <?php echo ($setting['qr_mode'] === 'daily') ? 'true' : 'false'; ?>;
        if (!isDaily) { return; }
        var now = new Date();
        var midnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 1, 0);
        var msToMidnight = midnight.getTime() - now.getTime();
        setTimeout(function () { window.location.reload(); }, msToMidnight);
        // Hourly safety refresh.
        setTimeout(function () { window.location.reload(); }, 3600 * 1000);
    })();

    // Dynamic mode: poll the live QR image endpoint and swap the code on each
    // rotation, with a small countdown so staff know it is live.
    (function () {
        var isDynamic = <?php echo ($setting['qr_mode'] === 'dynamic') ? 'true' : 'false'; ?>;
        if (!isDynamic) { return; }
        var IMG_URL  = "<?php echo site_url('admin/staffattendance/qrimage'); ?>";
        var interval = <?php echo max(5, (int) $setting['dynamic_interval_seconds']); ?>;
        var img = document.getElementById('qr-img');
        var cd  = document.getElementById('qr-countdown');
        var remaining = interval;

        function refresh() {
            fetch(IMG_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res && res.img) { img.src = res.img; }
                    if (res && res.interval) { interval = res.interval; }
                    remaining = interval;
                })
                .catch(function () { /* keep showing last code on transient error */ });
        }
        // Rotate on the interval.
        setInterval(refresh, interval * 1000);
        // Countdown tick.
        setInterval(function () {
            remaining -= 1;
            if (remaining < 0) { remaining = interval; }
            if (cd) { cd.textContent = 'New code in ' + remaining + 's'; }
        }, 1000);
    })();
</script>
