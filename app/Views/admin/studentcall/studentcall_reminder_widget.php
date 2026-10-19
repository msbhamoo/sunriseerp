<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>
<!-- Student Call Reminder Widget & Modal (Queue-based) -->
<style>
/* Toast Notification Bottom-Right (Screenshot 2 Match) */
#studentcall-reminder-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 1040;
    min-width: 320px;
    max-width: 380px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18), 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    display: none;
    animation: scSlideInUp 0.3s ease-out;
    overflow: hidden;
}
@keyframes scSlideInUp {
    from { transform: translateY(100%); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.sc-toast-header-bar {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 6px 12px 0 12px;
}
.sc-toast-close-x {
    color: #94a3b8;
    background: transparent;
    border: none;
    font-size: 16px;
    cursor: pointer;
    line-height: 1;
    padding: 2px 4px;
}
.sc-toast-close-x:hover {
    color: #475569;
}
.sc-toast-body {
    display: flex;
    align-items: flex-start;
    padding: 6px 16px 14px 16px;
    cursor: pointer;
}
.sc-toast-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #fffbeb;
    border: 2px dashed #f59e0b;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 14px;
    flex-shrink: 0;
    position: relative;
}
.sc-toast-icon-box i {
    font-size: 20px;
    color: #d97706;
    animation: scPulseIcon 2s infinite ease-in-out;
}
@keyframes scPulseIcon {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.12); }
}
.sc-toast-badge-count {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ef4444;
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    border: 2px solid #ffffff;
}
.sc-toast-content {
    flex: 1;
}
.sc-toast-title {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 3px 0;
    line-height: 1.3;
}
.sc-toast-sub {
    font-size: 12px;
    color: #64748b;
    margin: 0;
}
.sc-toast-actions {
    display: flex;
    border-top: 1px solid #f1f5f9;
    background: #fafafa;
}
.sc-toast-btn {
    flex: 1;
    text-align: center;
    padding: 10px 8px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-decoration: none;
    cursor: pointer;
    border: none;
    background: transparent;
    transition: background 0.15s ease;
}
.sc-toast-btn-snooze {
    color: #10b981;
    border-right: 1px solid #f1f5f9;
}
.sc-toast-btn-snooze:hover {
    background: #ecfdf5;
    color: #059669;
}
.sc-toast-btn-dismiss {
    color: #f43f5e;
}
.sc-toast-btn-dismiss:hover {
    background: #fff1f2;
    color: #e11d48;
}

/* Modal Styling (Screenshot 1 Match: "It's time to call.") */
#studentcallReminderModal .modal-dialog {
    max-width: 480px;
    margin: 60px auto;
}
#studentcallReminderModal .modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 20px 40px rgba(0,0,0,0.22);
    overflow: hidden;
}
.sc-modal-header-banner {
    background: #2d3748;
    color: #10b981;
    text-align: center;
    padding: 14px 16px;
    position: relative;
}
.sc-modal-header-banner h4 {
    margin: 0;
    font-size: 19px;
    font-weight: 700;
    color: #34d399;
    letter-spacing: 0.3px;
}
.sc-modal-queue-indicator {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 2px;
    font-weight: 500;
}
.sc-modal-body-content {
    padding: 24px;
    background: #ffffff;
}
.sc-profile-row {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 18px;
}
.sc-profile-avatar {
    width: 68px;
    height: 68px;
    border-radius: 8px;
    object-fit: cover;
    background: #e2e8f0;
    border: 1px solid #cbd5e1;
    flex-shrink: 0;
}
.sc-profile-info {
    flex: 1;
}
.sc-profile-name {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
    line-height: 1.2;
}
.sc-profile-meta-grid {
    display: grid;
    grid-template-columns: 95px 1fr;
    row-gap: 5px;
    font-size: 13px;
}
.sc-meta-label {
    color: #64748b;
    font-weight: 500;
}
.sc-meta-val {
    color: #1e293b;
    font-weight: 600;
    word-break: break-all;
}
.sc-meta-val a {
    color: #0284c7;
    text-decoration: none;
}
.sc-meta-val a:hover {
    text-decoration: underline;
}
.sc-purpose-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    background: #e0f2fe;
    color: #0369a1;
}

/* Quick Log Section inside Modal */
.sc-quick-log-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 14px;
    margin-top: 12px;
}
.sc-quick-log-box label {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    margin-bottom: 4px;
    display: block;
}

/* Modal Footer Buttons */
.sc-modal-footer-actions {
    padding: 14px 20px;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.sc-btn-call {
    background: #10b981;
    color: #ffffff !important;
    border: none;
    padding: 8px 18px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: background 0.15s ease;
}
.sc-btn-call:hover {
    background: #059669;
    color: #ffffff;
}
.sc-btn-later {
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
}
.sc-btn-later:hover {
    background: #f1f5f9;
}
.sc-btn-skip {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    padding: 8px 14px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
}
.sc-btn-skip:hover {
    background: #e2e8f0;
}
.sc-btn-close-group .btn {
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}
</style>

<!-- Reminder Toast View (Bottom Right) -->
<div id="studentcall-reminder-toast">
    <div class="sc-toast-header-bar">
        <button type="button" class="sc-toast-close-x" onclick="StudentCallReminder.dismissCurrentSession()" title="Dismiss">&times;</button>
    </div>
    <div class="sc-toast-body" onclick="StudentCallReminder.openModal()">
        <div class="sc-toast-icon-box">
            <i class="fa fa-clock-o"></i>
            <span class="sc-toast-badge-count" id="sc_toast_badge">1</span>
        </div>
        <div class="sc-toast-content">
            <div class="sc-toast-title" id="sc_toast_title">Call scheduled with Student</div>
            <div class="sc-toast-sub" id="sc_toast_sub">Calls Due Today</div>
        </div>
    </div>
    <div class="sc-toast-actions">
        <div class="btn-group dropup" style="flex:1;">
            <button type="button" class="sc-toast-btn sc-toast-btn-snooze dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:100%;">
                SNOOZE <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" style="min-width: 140px; font-size: 12px;">
                <li><a href="javascript:void(0)" onclick="StudentCallReminder.snooze(15)"><i class="fa fa-clock-o text-warning"></i> 15 Minutes</a></li>
                <li><a href="javascript:void(0)" onclick="StudentCallReminder.snooze(30)"><i class="fa fa-clock-o text-warning"></i> 30 Minutes</a></li>
                <li><a href="javascript:void(0)" onclick="StudentCallReminder.snooze(60)"><i class="fa fa-clock-o text-warning"></i> 1 Hour</a></li>
                <li><a href="javascript:void(0)" onclick="StudentCallReminder.snooze(120)"><i class="fa fa-clock-o text-warning"></i> 2 Hours</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="javascript:void(0)" onclick="StudentCallReminder.snoozeTomorrow()"><i class="fa fa-calendar text-info"></i> Tomorrow</a></li>
            </ul>
        </div>
        <button type="button" class="sc-toast-btn sc-toast-btn-dismiss" onclick="StudentCallReminder.dismissCurrentSession()">DISMISS</button>
    </div>
</div>

<!-- Reminder Modal ("It's time to call.") -->
<div class="modal fade" id="studentcallReminderModal" tabindex="-1" role="dialog" aria-labelledby="studentcallReminderModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Header Banner -->
            <div class="sc-modal-header-banner">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:0.8; position:absolute; right:15px; top:12px;">&times;</button>
                <h4>It's time to call.</h4>
                <div class="sc-modal-queue-indicator" id="sc_modal_queue_counter">Queue: 1 of 1</div>
            </div>

            <!-- Modal Body -->
            <div class="sc-modal-body-content">
                <div class="sc-profile-row">
                    <img id="sc_modal_avatar" src="<?php echo base_url('uploads/student_images/no_image.png'); ?>" alt="Student Avatar" class="sc-profile-avatar">
                    <div class="sc-profile-info">
                        <div class="sc-profile-name" id="sc_modal_name">Student Name</div>
                        
                        <div class="sc-profile-meta-grid">
                            <div class="sc-meta-label">Lead Owner</div>
                            <div class="sc-meta-val" id="sc_modal_owner">-</div>

                            <div class="sc-meta-label">Mobile</div>
                            <div class="sc-meta-val" id="sc_modal_phone">-</div>

                            <div class="sc-meta-label">Class</div>
                            <div class="sc-meta-val" id="sc_modal_class">-</div>

                            <div class="sc-meta-label">Lead Status</div>
                            <div class="sc-meta-val" id="sc_modal_status"><span class="sc-purpose-badge">Pending</span></div>

                            <div class="sc-meta-label">Purpose</div>
                            <div class="sc-meta-val" id="sc_modal_purpose">-</div>
                        </div>
                    </div>
                </div>

                <!-- Call Outcome Logger within Modal -->
                <div class="sc-quick-log-box" id="sc_quick_log_box">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>Call Outcome</label>
                            <select id="sc_log_call_status" class="form-control input-sm">
                                <option value="Connected">Connected</option>
                                <option value="Not Answered">Not Answered</option>
                                <option value="Busy">Busy</option>
                                <option value="Switched Off">Switched Off</option>
                                <option value="Wrong Number">Wrong Number</option>
                                <option value="Callback Requested">Callback Requested</option>
                            </select>
                        </div>
                        <div class="col-xs-6">
                            <label>Next Follow-up (Opt)</label>
                            <input type="text" id="sc_log_next_date" class="form-control input-sm date" placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                    <div class="row" style="margin-top:8px;">
                        <div class="col-xs-12">
                            <label>Remarks / Notes</label>
                            <input type="text" id="sc_log_remarks" class="form-control input-sm" placeholder="Spoke with parent, confirmed payment tomorrow...">
                        </div>
                    </div>
                </div>

                <!-- Reschedule Form (Hidden by default, shown on "Later") -->
                <div class="sc-quick-log-box" id="sc_reschedule_box" style="display:none; background:#fffbeb; border-color:#fde68a;">
                    <label style="color:#b45309;"><i class="fa fa-calendar"></i> Reschedule Call To:</label>
                    <div class="row">
                        <div class="col-xs-6">
                            <input type="text" id="sc_resched_date" class="form-control input-sm date" placeholder="Pick new date">
                        </div>
                        <div class="col-xs-6">
                            <input type="time" id="sc_resched_time" class="form-control input-sm" placeholder="Pick time (optional)">
                            <div style="margin-top:4px; display:flex; gap:4px; flex-wrap:wrap;">
                                <span class="btn btn-default btn-xs" onclick="$('#sc_resched_time').val('10:00');" style="font-size:10px; padding:1px 5px;">10:00 AM</span>
                                <span class="btn btn-default btn-xs" onclick="$('#sc_resched_time').val('14:00');" style="font-size:10px; padding:1px 5px;">02:00 PM</span>
                                <span class="btn btn-default btn-xs" onclick="$('#sc_resched_time').val('17:00');" style="font-size:10px; padding:1px 5px;">05:00 PM</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:8px; text-align:right;">
                        <button type="button" class="btn btn-default btn-xs" onclick="$('#sc_reschedule_box').slideUp();">Cancel</button>
                        <button type="button" class="btn btn-warning btn-xs" onclick="StudentCallReminder.saveReschedule()">Save & Next</button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer Actions -->
            <div class="sc-modal-footer-actions">
                <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
                    <button type="button" class="sc-btn-call" id="sc_btn_call_dial" onclick="StudentCallReminder.dialAndSave()">
                        <i class="fa fa-phone"></i> Call & Save
                    </button>
                    <button type="button" class="btn btn-success btn-sm" id="sc_btn_whatsapp" onclick="StudentCallReminder.openWhatsApp()" title="Send WhatsApp Message" style="border-radius:6px; font-weight:600; background-color:#25d366; border-color:#22bf5b;">
                        <i class="fa fa-whatsapp"></i> WhatsApp
                    </button>
                    <button type="button" class="sc-btn-later" onclick="StudentCallReminder.showLaterOptions()">
                        Later
                    </button>
                    <button type="button" class="sc-btn-skip" id="sc_btn_skip" onclick="StudentCallReminder.nextInQueue()">
                        Skip <i class="fa fa-angle-right"></i>
                    </button>
                </div>
                
                <div class="btn-group sc-btn-close-group">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var StudentCallReminder = (function() {
    var queue = [];
    var currentIndex = 0;
    var isPollingActive = true;
    var checkIntervalMs = 60000; // 60 seconds
    var snoozeKey = 'sc_reminder_snooze_until';
    var dismissKey = 'sc_reminder_dismissed_ids';
    var hasNotifiedIds = {};

    // Web Audio Chime Generator (No external audio file needed)
    function playChime() {
        try {
            var AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            var ctx = new AudioContext();
            
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            osc.type = 'sine';
            // Pleasant 2-tone notification bell
            osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
            osc.frequency.exponentialRampToValueAtTime(880.00, ctx.currentTime + 0.15); // A5
            
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.6);
            
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.6);
        } catch(e) {
            // Audio context blocked or unsupported
        }
    }

    // Native Desktop Push Notification
    function triggerDesktopNotification(item, totalCount) {
        if (!("Notification" in window)) return;

        function showNotification() {
            var title = "📞 Call Reminder: " + item.student_name;
            var body = "Class: " + item.class_name + " • Phone: " + item.phone_display + (totalCount > 1 ? " (+" + (totalCount - 1) + " more in queue)" : "");
            var notif = new Notification(title, {
                body: body,
                icon: item.image || (base_url + 'backend/images/s-favican.png')
            });

            notif.onclick = function() {
                window.focus();
                openModal();
                notif.close();
            };
        }

        if (Notification.permission === "granted") {
            showNotification();
        } else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(function(permission) {
                if (permission === "granted") {
                    showNotification();
                }
            });
        }
    }

    function init() {
        // Request desktop notification permission politely if supported
        if ("Notification" in window && Notification.permission === "default") {
            setTimeout(function() {
                Notification.requestPermission();
            }, 8000);
        }

        // Initial fetch after 4 seconds of page load
        setTimeout(checkReminders, 4000);
        setInterval(checkReminders, checkIntervalMs);
    }

    function updateNavCounter(count) {
        var $badge = $('#sc_nav_call_count');
        if ($badge.length) {
            if (count > 0) {
                $badge.text(count).show();
            } else {
                $badge.hide();
            }
        }
    }

    function checkReminders() {
        if (!isPollingActive) return;

        // Check if snoozed
        var snoozeUntil = localStorage.getItem(snoozeKey);
        if (snoozeUntil && parseInt(snoozeUntil) > Date.now()) {
            return; // Still in snooze period
        }

        $.ajax({
            url: base_url + 'admin/studentcall/get_pending_reminders_ajax',
            type: 'GET',
            dataType: 'json',
            success: function(resp) {
                if (resp && resp.status === 'success' && resp.count > 0) {
                    var dismissedList = getDismissedList();
                    // Filter out calls already dismissed in this session
                    var activeCalls = resp.calls.filter(function(c) {
                        return dismissedList.indexOf(c.followup_id) === -1;
                    });

                    updateNavCounter(activeCalls.length);

                    if (activeCalls.length > 0) {
                        var firstCall = activeCalls[0];
                        var isNewlyTriggered = !hasNotifiedIds[firstCall.followup_id];

                        queue = activeCalls;
                        renderToast();

                        if (isNewlyTriggered) {
                            hasNotifiedIds[firstCall.followup_id] = true;
                            playChime();
                            triggerDesktopNotification(firstCall, activeCalls.length);
                        }
                    } else {
                        hideToast();
                    }
                } else {
                    updateNavCounter(0);
                    hideToast();
                }
            }
        });
    }

    function getDismissedList() {
        try {
            var val = sessionStorage.getItem(dismissKey);
            return val ? JSON.parse(val) : [];
        } catch(e) {
            return [];
        }
    }

    function addDismissedId(id) {
        var list = getDismissedList();
        if (list.indexOf(id) === -1) {
            list.push(id);
            sessionStorage.setItem(dismissKey, JSON.stringify(list));
        }
    }

    function renderToast() {
        if (queue.length === 0) {
            hideToast();
            return;
        }

        var current = queue[currentIndex] || queue[0];
        $('#sc_toast_badge').text(queue.length);
        $('#sc_toast_title').text('Call scheduled with ' + current.student_name);
        
        var dueText = 'Calls Due on ' + current.due_formatted;
        if (queue.length > 1) {
            dueText += ' (' + queue.length + ' pending in queue)';
        }
        $('#sc_toast_sub').text(dueText);

        $('#studentcall-reminder-toast').fadeIn(200);
    }

    function hideToast() {
        $('#studentcall-reminder-toast').fadeOut(200);
    }

    function openModal(index, forceFetch) {
        if (queue.length === 0 || forceFetch) {
            // Fetch immediately so user never sees dummy/blank placeholders
            $.ajax({
                url: base_url + 'admin/studentcall/get_pending_reminders_ajax',
                type: 'GET',
                dataType: 'json',
                success: function(resp) {
                    if (resp && resp.status === 'success' && resp.count > 0) {
                        queue = resp.calls;
                        updateNavCounter(queue.length);
                        currentIndex = 0;
                        loadModalData(currentIndex);
                        $('#studentcallReminderModal').modal('show');
                    } else {
                        updateNavCounter(0);
                        if (typeof successMsg === 'function') {
                            successMsg('Great job! No pending call reminders scheduled for today.');
                        } else {
                            alert('No pending call reminders scheduled for today.');
                        }
                    }
                }
            });
            return;
        }

        if (typeof index !== 'undefined') {
            currentIndex = index;
        }
        if (currentIndex >= queue.length) currentIndex = 0;
        
        loadModalData(currentIndex);
        $('#studentcallReminderModal').modal('show');
    }

    function loadModalData(index) {
        if (!queue || queue.length === 0 || !queue[index]) {
            $('#studentcallReminderModal').modal('hide');
            hideToast();
            return;
        }

        var item = queue[index];
        $('#sc_modal_queue_counter').text('Queue: ' + (index + 1) + ' of ' + queue.length);
        $('#sc_modal_avatar').attr('src', item.image);
        $('#sc_modal_name').text(item.student_name);
        $('#sc_modal_owner').text(item.lead_owner || '-');
        
        var phoneHtml = item.phone_display || '-';
        if (item.phone) {
            phoneHtml = '<a href="tel:' + item.phone + '" class="text-primary"><i class="fa fa-phone"></i> ' + item.phone_display + '</a>';
        }
        $('#sc_modal_phone').html(phoneHtml);
        $('#sc_modal_class').text(item.class_name + (item.admission_no ? ' [Adm: ' + item.admission_no + ']' : ''));
        $('#sc_modal_status').html('<span class="sc-purpose-badge">' + (item.lead_status || 'Pending') + '</span>');
        $('#sc_modal_purpose').text(item.purpose + ' (Due: ' + item.due_formatted + ')');

        // Reset inputs
        $('#sc_log_call_status').val('Connected');
        $('#sc_log_next_date').val('');
        $('#sc_log_remarks').val('');
        $('#sc_reschedule_box').hide();

        if (queue.length <= 1) {
            $('#sc_btn_skip').hide();
        } else {
            $('#sc_btn_skip').show();
        }
    }

    function dialAndSave() {
        var item = queue[currentIndex];
        if (!item) return;

        // If phone exists, trigger dial protocol
        if (item.phone) {
            window.location.href = 'tel:' + item.phone;
        }

        var postData = {
            followup_id: item.followup_id,
            student_call_id: item.student_call_id,
            student_id: item.student_id,
            call_status: $('#sc_log_call_status').val(),
            status: 'Completed',
            remarks: $('#sc_log_remarks').val(),
            next_date: $('#sc_log_next_date').val()
        };

        $.ajax({
            url: base_url + 'admin/studentcall/quick_log_reminder_ajax',
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(resp) {
                if (resp && resp.status === 'success') {
                    successMsg('Call logged for ' + item.student_name);
                    removeCurrentAndAdvance();
                } else {
                    errorMsg(resp.message || 'Could not save call log');
                }
            }
        });
    }

    function openWhatsApp() {
        var item = queue[currentIndex];
        if (!item || !item.phone) {
            errorMsg('No phone number available for WhatsApp');
            return;
        }

        var cleanPhone = item.phone.replace(/[^0-9]/g, '');
        if (cleanPhone.length === 10) {
            cleanPhone = '91' + cleanPhone; // Default India prefix if 10 digits
        }

        var msgText = "Dear Parent, Greetings from Sunrise School. We are following up regarding " + item.student_name + " (" + item.class_name + "). Please let us know a convenient time to speak. Thank you.";
        var waUrl = "https://wa.me/" + cleanPhone + "?text=" + encodeURIComponent(msgText);
        window.open(waUrl, '_blank');
    }

    function showLaterOptions() {
        $('#sc_reschedule_box').slideToggle(150);
    }

    function saveReschedule() {
        var item = queue[currentIndex];
        if (!item) return;

        var newDate = $('#sc_resched_date').val();
        var newTime = $('#sc_resched_time').val();

        if (!newDate) {
            errorMsg('Please select a new follow-up date');
            return;
        }

        $.ajax({
            url: base_url + 'admin/studentcall/quick_reschedule_reminder_ajax',
            type: 'POST',
            data: {
                followup_id: item.followup_id,
                new_date: newDate,
                new_time: newTime
            },
            dataType: 'json',
            success: function(resp) {
                if (resp && resp.status === 'success') {
                    successMsg('Rescheduled ' + item.student_name);
                    removeCurrentAndAdvance();
                } else {
                    errorMsg(resp.message || 'Reschedule failed');
                }
            }
        });
    }

    function removeCurrentAndAdvance() {
        var item = queue[currentIndex];
        if (item) {
            addDismissedId(item.followup_id);
        }
        queue.splice(currentIndex, 1);
        if (currentIndex >= queue.length) {
            currentIndex = 0;
        }

        updateNavCounter(queue.length);

        if (queue.length > 0) {
            loadModalData(currentIndex);
            renderToast();
        } else {
            $('#studentcallReminderModal').modal('hide');
            hideToast();
        }
    }

    function nextInQueue() {
        if (queue.length <= 1) return;
        currentIndex = (currentIndex + 1) % queue.length;
        loadModalData(currentIndex);
    }

    function snooze(minutes) {
        var snoozeUntil = Date.now() + (minutes * 60 * 1000);
        localStorage.setItem(snoozeKey, snoozeUntil);
        hideToast();
        $('#studentcallReminderModal').modal('hide');
        successMsg('Call reminders snoozed for ' + minutes + ' minutes');
    }

    function snoozeTomorrow() {
        // Snooze until tomorrow 8:00 AM
        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(8, 0, 0, 0);
        localStorage.setItem(snoozeKey, tomorrow.getTime());
        hideToast();
        $('#studentcallReminderModal').modal('hide');
        successMsg('Call reminders snoozed until tomorrow');
    }

    function dismissCurrentSession() {
        queue.forEach(function(c) {
            addDismissedId(c.followup_id);
        });
        hideToast();
    }

    return {
        init: init,
        openModal: openModal,
        dialAndSave: dialAndSave,
        openWhatsApp: openWhatsApp,
        showLaterOptions: showLaterOptions,
        saveReschedule: saveReschedule,
        nextInQueue: nextInQueue,
        snooze: snooze,
        snoozeTomorrow: snoozeTomorrow,
        dismissCurrentSession: dismissCurrentSession
    };
})();

$(document).ready(function() {
    StudentCallReminder.init();
});
</script>

