<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-qrcode"></i> Mark My Attendance</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-body" style="text-align:center;">

                        <?php if (empty($setting['is_enabled'])) { ?>
                            <div class="alert alert-warning">QR attendance is currently disabled. Please contact admin.</div>
                        <?php } else { ?>

                        <p class="text-muted">Point your camera at the attendance QR code on screen.</p>

                        <div id="qr-status" class="alert alert-info" style="display:none;"></div>

                        <div id="scanner-wrap" style="position:relative;max-width:360px;margin:0 auto;">
                            <video id="qr-video" style="width:100%;border-radius:8px;background:#000;" playsinline muted></video>
                            <canvas id="qr-canvas" style="display:none;"></canvas>
                        </div>

                        <!-- Early-exit confirmation -->
                        <div id="early-box" style="display:none;margin-top:15px;text-align:left;">
                            <div class="alert alert-warning" id="early-msg"></div>
                            <div class="form-group">
                                <label>Reason for leaving early (optional)</label>
                                <input type="text" id="early-reason" class="form-control" placeholder="e.g. medical, personal">
                            </div>
                            <button class="btn btn-danger" id="btn-confirm-early">Yes, I'm leaving early</button>
                            <button class="btn btn-default" id="btn-cancel-early">No, stay marked in</button>
                        </div>

                        <!-- Action choices when already checked in (step out / leave / step in) -->
                        <div id="choose-box" style="display:none;margin-top:15px;text-align:left;">
                            <div class="alert alert-info" id="choose-msg"></div>
                            <div id="breakout-reason-wrap" style="display:none;margin-bottom:10px;">
                                <label>Reason for stepping out (optional)</label>
                                <input type="text" id="breakout-reason" class="form-control" placeholder="e.g. bank, medical, personal work">
                            </div>
                            <button class="btn btn-warning" id="btn-break-out" style="display:none;"><i class="fa fa-sign-out"></i> Step Out (will return)</button>
                            <button class="btn btn-success" id="btn-break-in" style="display:none;"><i class="fa fa-sign-in"></i> Step In (returned)</button>
                            <button class="btn btn-danger" id="btn-final-out" style="display:none;"><i class="fa fa-power-off"></i> Mark Out (end of day)</button>
                            <button class="btn btn-default" id="btn-choose-cancel"><i class="fa fa-times"></i> Cancel</button>
                        </div>

                        <div style="margin-top:15px;">
                            <button class="btn btn-default" id="btn-rescan" style="display:none;"><i class="fa fa-refresh"></i> Scan again</button>
                        </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php if (!empty($setting['is_enabled'])) { ?>
<script src="<?php echo base_url('backend/plugins/jsqr/jsQR.js'); ?>"></script>
<script type="text/javascript">
(function () {
    var MARK_URL   = "<?php echo site_url('admin/staffattendance/markqr'); ?>";
    var GPS_NEEDED = <?php echo !empty($setting['gps_enabled']) ? 'true' : 'false'; ?>;

    var video   = document.getElementById('qr-video');
    var canvas  = document.getElementById('qr-canvas');
    var ctx     = canvas.getContext('2d');
    var statusEl = document.getElementById('qr-status');
    var earlyBox = document.getElementById('early-box');
    var earlyMsg = document.getElementById('early-msg');
    var rescanBtn = document.getElementById('btn-rescan');

    var chooseBox = document.getElementById('choose-box');
    var chooseMsg = document.getElementById('choose-msg');

    var stream = null;
    var scanning = false;
    var lastToken = null;   // token being processed (kept for re-submits)
    var curLat = null;      // location captured on the scan, reused for actions
    var curLng = null;

    function showStatus(msg, cls) {
        statusEl.style.display = 'block';
        statusEl.className = 'alert ' + (cls || 'alert-info');
        statusEl.innerHTML = msg;
    }

    function stopCamera() {
        scanning = false;
        if (stream) { stream.getTracks().forEach(function (t) { t.stop(); }); stream = null; }
    }

    function startCamera() {
        earlyBox.style.display = 'none';
        chooseBox.style.display = 'none';
        rescanBtn.style.display = 'none';
        statusEl.style.display = 'none';
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            showStatus('Camera not supported on this browser. Please use a modern mobile browser over HTTPS.', 'alert-danger');
            return;
        }
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(function (s) {
                stream = s;
                video.srcObject = s;
                video.setAttribute('playsinline', true);
                video.play();
                scanning = true;
                requestAnimationFrame(tick);
            })
            .catch(function () {
                showStatus('Unable to access the camera. Please allow camera permission.', 'alert-danger');
            });
    }

    function tick() {
        if (!scanning) { return; }
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            var img = ctx.getImageData(0, 0, canvas.width, canvas.height);
            var code = jsQR(img.data, img.width, img.height, { inversionAttempts: 'dontInvert' });
            if (code && code.data) {
                scanning = false;
                stopCamera();
                onDecoded(code.data);
                return;
            }
        }
        requestAnimationFrame(tick);
    }

    function onDecoded(token) {
        lastToken = token;
        showStatus('<i class="fa fa-spinner fa-spin"></i> Reading QR...', 'alert-info');
        if (GPS_NEEDED) {
            if (!navigator.geolocation) {
                showStatus('Location is required but not supported by this browser.', 'alert-danger');
                rescanBtn.style.display = 'inline-block';
                return;
            }
            navigator.geolocation.getCurrentPosition(
                function (pos) { curLat = pos.coords.latitude; curLng = pos.coords.longitude; submit({}); },
                function () {
                    showStatus('Please enable location access and scan again.', 'alert-danger');
                    rescanBtn.style.display = 'inline-block';
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        } else {
            curLat = null; curLng = null;
            submit({});
        }
    }

    // extra: { action, reason, confirm_early }
    function submit(extra) {
        extra = extra || {};
        var data = { token: lastToken };
        if (curLat !== null) { data.lat = curLat; data.lng = curLng; }
        if (extra.action) { data.action = extra.action; }
        if (extra.reason) { data.reason = extra.reason; }
        if (extra.confirm_early) { data.confirm_early = 1; }

        $.ajax({
            url: MARK_URL, type: 'POST', data: data, dataType: 'json'
        }).done(function (res) {
            handleResult(res);
        }).fail(function () {
            showStatus('Network error. Please try again.', 'alert-danger');
            rescanBtn.style.display = 'inline-block';
        });
    }

    function hideAllPanels() {
        earlyBox.style.display = 'none';
        chooseBox.style.display = 'none';
    }

    function handleResult(res) {
        var s = res.status;
        hideAllPanels();
        if (s === 'marked_in' || s === 'marked_out' || s === 'break_out' || s === 'break_in') {
            showStatus('<i class="fa fa-check-circle"></i> ' + res.message + (res.time ? ' (' + res.time + ')' : ''), 'alert-success');
            rescanBtn.style.display = 'inline-block';
        } else if (s === 'already_complete' || s === 'cooldown') {
            showStatus('<i class="fa fa-info-circle"></i> ' + res.message, 'alert-info');
            rescanBtn.style.display = 'inline-block';
        } else if (s === 'confirm_early') {
            statusEl.style.display = 'none';
            earlyMsg.innerHTML = res.message;
            earlyBox.style.display = 'block';
        } else if (s === 'choose') {
            statusEl.style.display = 'none';
            var acts = res.actions || [];
            chooseMsg.innerHTML = res.message;
            document.getElementById('breakout-reason-wrap').style.display = (acts.indexOf('break_out') !== -1) ? 'block' : 'none';
            document.getElementById('btn-break-out').style.display = (acts.indexOf('break_out') !== -1) ? 'inline-block' : 'none';
            document.getElementById('btn-break-in').style.display  = (acts.indexOf('break_in')  !== -1) ? 'inline-block' : 'none';
            document.getElementById('btn-final-out').style.display = (acts.indexOf('final_out') !== -1) ? 'inline-block' : 'none';
            chooseBox.style.display = 'block';
        } else {
            // no_schedule or error
            showStatus('<i class="fa fa-exclamation-triangle"></i> ' + res.message, 'alert-danger');
            rescanBtn.style.display = 'inline-block';
        }
    }

    document.getElementById('btn-confirm-early').addEventListener('click', function () {
        var reason = document.getElementById('early-reason').value;
        earlyBox.style.display = 'none';
        showStatus('<i class="fa fa-spinner fa-spin"></i> Marking out...', 'alert-info');
        submit({ action: 'final_out', confirm_early: true, reason: reason });
    });

    document.getElementById('btn-cancel-early').addEventListener('click', function () {
        earlyBox.style.display = 'none';
        showStatus('<i class="fa fa-info-circle"></i> No changes made. You are still marked in.', 'alert-info');
        rescanBtn.style.display = 'inline-block';
    });

    document.getElementById('btn-break-out').addEventListener('click', function () {
        var reason = document.getElementById('breakout-reason').value;
        chooseBox.style.display = 'none';
        showStatus('<i class="fa fa-spinner fa-spin"></i> Recording your step out...', 'alert-info');
        submit({ action: 'break_out', reason: reason });
    });

    document.getElementById('btn-break-in').addEventListener('click', function () {
        chooseBox.style.display = 'none';
        showStatus('<i class="fa fa-spinner fa-spin"></i> Recording your return...', 'alert-info');
        submit({ action: 'break_in' });
    });

    document.getElementById('btn-final-out').addEventListener('click', function () {
        chooseBox.style.display = 'none';
        showStatus('<i class="fa fa-spinner fa-spin"></i> Marking out...', 'alert-info');
        submit({ action: 'final_out' });
    });

    document.getElementById('btn-choose-cancel').addEventListener('click', function () {
        chooseBox.style.display = 'none';
        showStatus('<i class="fa fa-info-circle"></i> No changes made.', 'alert-info');
        rescanBtn.style.display = 'inline-block';
    });

    rescanBtn.addEventListener('click', startCamera);

    startCamera();
})();
</script>
<?php } ?>
