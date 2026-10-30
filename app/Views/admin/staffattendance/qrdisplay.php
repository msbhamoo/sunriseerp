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

    // Dynamic mode: precisely synchronized timer to fetch new QR token
    // on every window change and update countdown smoothly.
    (function () {
        var isDynamic = <?php echo ($setting['qr_mode'] === 'dynamic') ? 'true' : 'false'; ?>;
        if (!isDynamic) { return; }
        var IMG_URL  = "<?php echo site_url('admin/staffattendance/qrimage'); ?>";
        var interval = <?php echo max(5, (int) $setting['dynamic_interval_seconds']); ?>;
        var img = document.getElementById('qr-img');
        var cd  = document.getElementById('qr-countdown');
        var isFetching = false;

        function fetchQr() {
            if (isFetching) return;
            isFetching = true;
            fetch(IMG_URL, { 
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                cache: 'no-store'
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res && res.img) { 
                    img.src = res.img; 
                }
                if (res && res.interval) { 
                    interval = parseInt(res.interval, 10); 
                }
                isFetching = false;
            })
            .catch(function () { 
                isFetching = false; 
            });
        }

        // Periodic tick every 1 second: calculate exact remaining seconds in current window
        function updateTimer() {
            var nowSec = Math.floor(Date.now() / 1000);
            var remaining = interval - (nowSec % interval);
            
            if (remaining === interval || remaining <= 1) {
                fetchQr();
            }

            if (cd) {
                cd.innerHTML = '<i class="fa fa-refresh ' + (remaining <= 3 ? 'fa-spin text-primary' : '') + '"></i> New dynamic code in <strong style="font-size:16px; color:#007bff;">' + remaining + 's</strong>';
            }
        }

        // Initial fetch and start interval
        fetchQr();
        updateTimer();
        setInterval(updateTimer, 1000);
    })();
</script>
