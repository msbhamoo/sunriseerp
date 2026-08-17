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

<!-- Attendance Scan Pop-Up Modal -->
<div class="modal fade" id="scanResultModal" tabindex="-1" role="dialog" aria-labelledby="scanResultModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:400px; margin: 40px auto;">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 12px 35px rgba(0,0,0,0.25); overflow:hidden;">
            <div class="modal-header" id="modal-header-bg" style="background:#28a745; color:#fff; padding:22px 15px; text-align:center; position:relative;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:0.8; position:absolute; right:15px; top:12px; font-size:24px;"><span aria-hidden="true">&times;</span></button>
                <div id="modal-icon" style="font-size:42px; margin-bottom:6px; line-height:1;">
                    <i class="fa fa-check-circle"></i>
                </div>
                <h4 class="modal-title" id="modal-status-title" style="font-weight:700; margin:0; font-size:18px; text-transform:uppercase; letter-spacing:0.5px;">Check-In Successful</h4>
            </div>
            <div class="modal-body" style="padding:22px 25px;">
                <p id="modal-message-text" class="text-center text-muted" style="font-size:14px; margin-bottom:18px; font-weight:500;"></p>
                
                <div style="background:#f8f9fa; border-radius:10px; padding:16px; border:1px solid #e9ecef;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; border-bottom:1px dashed #dee2e6; padding-bottom:8px;">
                        <span style="color:#6c757d; font-size:13px; font-weight:600;"><i class="fa fa-calendar" style="width:16px; text-align:center;"></i> Date:</span>
                        <span id="modal-date" style="font-weight:600; color:#333; font-size:13px;">-</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; border-bottom:1px dashed #dee2e6; padding-bottom:8px;">
                        <span style="color:#6c757d; font-size:13px; font-weight:600;"><i class="fa fa-sign-in" style="width:16px; text-align:center;"></i> Check-In Time:</span>
                        <span id="modal-in-time" style="font-weight:700; color:#28a745; font-size:14px;">-</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; border-bottom:1px dashed #dee2e6; padding-bottom:8px;">
                        <span style="color:#6c757d; font-size:13px; font-weight:600;"><i class="fa fa-sign-out" style="width:16px; text-align:center;"></i> Check-Out Time:</span>
                        <span id="modal-out-time" style="font-weight:700; color:#dc3545; font-size:14px;">-</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="color:#6c757d; font-size:13px; font-weight:600;"><i class="fa fa-info-circle" style="width:16px; text-align:center;"></i> Attendance Status:</span>
                        <span id="modal-att-status" class="label label-success" style="font-size:12px; padding:5px 12px; border-radius:12px; text-transform:capitalize;">Present</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center; background:#f8f9fa; border-top:1px solid #e9ecef; padding:14px 20px;">
                <button type="button" class="btn btn-primary btn-block btn-lg" data-dismiss="modal" style="border-radius:8px; font-weight:600; font-size:15px; padding:10px 16px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">OK / Done</button>
            </div>
        </div>
    </div>
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

    function showAttendanceModal(res) {
        var s = res.status;
        var inTime = res.in_time || '--';
        var outTime = res.out_time || '--';
        var attType = res.attendance_type || 'Present';
        var attDate = res.date || '<?php echo date("d M Y"); ?>';

        var title = 'Attendance Status';
        var iconHtml = '<i class="fa fa-check-circle"></i>';
        var headerBg = '#28a745'; // default green
        var badgeClass = 'label-success';

        var lowerType = attType.toLowerCase();
        if (lowerType.indexOf('late') !== -1) {
            badgeClass = 'label-warning';
        } else if (lowerType.indexOf('half') !== -1) {
            badgeClass = 'label-info';
        } else if (lowerType.indexOf('absent') !== -1) {
            badgeClass = 'label-danger';
        }

        if (s === 'marked_in') {
            title = 'Check-In Successful';
            headerBg = '#28a745';
            iconHtml = '<i class="fa fa-sign-in"></i>';
        } else if (s === 'marked_out') {
            title = 'Check-Out Successful';
            headerBg = '#007bff';
            iconHtml = '<i class="fa fa-sign-out"></i>';
        } else if (s === 'already_complete') {
            title = 'Attendance Completed';
            headerBg = '#17a2b8';
            iconHtml = '<i class="fa fa-check-square-o"></i>';
        } else if (s === 'cooldown') {
            title = 'Already Checked In';
            headerBg = '#f39c12';
            iconHtml = '<i class="fa fa-clock-o"></i>';
        } else if (s === 'break_out') {
            title = 'Stepped Out';
            headerBg = '#e67e22';
            iconHtml = '<i class="fa fa-coffee"></i>';
        } else if (s === 'break_in') {
            title = 'Welcome Back';
            headerBg = '#27ae60';
            iconHtml = '<i class="fa fa-level-up"></i>';
        }

        $('#modal-header-bg').css('background-color', headerBg);
        $('#modal-icon').html(iconHtml);
        $('#modal-status-title').text(title);
        $('#modal-message-text').text(res.message || '');
        $('#modal-date').text(attDate);
        $('#modal-in-time').text(inTime);
        $('#modal-out-time').text(outTime);
        $('#modal-att-status').text(attType).attr('class', 'label ' + badgeClass);

        $('#scanResultModal').modal('show');
    }

    function handleResult(res) {
        var s = res.status;
        hideAllPanels();
        if (s === 'marked_in' || s === 'marked_out' || s === 'break_out' || s === 'break_in' || s === 'already_complete' || s === 'cooldown') {
            showAttendanceModal(res);
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
