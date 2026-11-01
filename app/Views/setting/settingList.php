<style>
    .setg-wrap { --setg-accent: var(--primary-theme-color, #4f46e5); --setg-ink: #0f172a; --setg-muted: #64748b; --setg-line: #e9eef5; --setg-soft: #f8fafc; }
    .setg-hero {
        display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
        background: #fff; border: 1px solid var(--setg-line); border-radius: 14px;
        padding: 18px 22px; margin-bottom: 16px; box-shadow: 0 2px 12px rgba(15,23,42,.03);
    }
    .setg-hero-left { display: flex; align-items: center; gap: 14px; min-width: 0; }
    .setg-hero-icon {
        width: 46px; height: 46px; border-radius: 12px; flex: 0 0 46px;
        display: flex; align-items: center; justify-content: center; font-size: 20px; color: #fff;
        background: var(--setg-accent); box-shadow: 0 6px 16px rgba(79,70,229,.25);
    }
    .setg-hero h3 { margin: 0; font-size: 18px; font-weight: 800; color: var(--setg-ink); }
    .setg-hero p { margin: 3px 0 0; font-size: 13px; color: var(--setg-muted); }
    .setg-chip {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 999px;
        font-size: 12px; font-weight: 700; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;
    }
    .setg-chip.dirty { background: #fffbeb; color: #b45309; border-color: #fde68a; }

    .setg-note {
        display: flex; gap: 10px; align-items: flex-start; padding: 12px 16px; border-radius: 10px;
        background: #eff6ff; border: 1px solid #dbeafe; color: #1e40af; font-size: 13px; margin-bottom: 16px;
    }
    .setg-note i { margin-top: 2px; }

    /* Underline tabs attached to the top of the active section; one section visible at a time */
    .setg-tabs {
        display: flex; flex-wrap: wrap; gap: 2px;
        background: #fff; border: 1px solid var(--setg-line); border-bottom: 0;
        border-radius: 14px 14px 0 0; padding: 0 10px;
    }
    .setg-tabs a {
        position: relative; white-space: nowrap; padding: 14px 14px 12px; font-size: 13px; font-weight: 700;
        color: var(--setg-muted); text-decoration: none; border-bottom: 3px solid transparent; transition: color .15s, border-color .15s;
    }
    .setg-tabs a:hover { color: var(--setg-ink); }
    .setg-tabs a.active { color: var(--setg-accent); border-bottom-color: var(--setg-accent); }
    .setg-tabs a i { margin-right: 6px; }
    .setg-tabs a.has-error::after {
        content: ""; position: absolute; top: 10px; right: 6px; width: 7px; height: 7px; border-radius: 50%; background: #ef4444;
    }

    .setg-card {
        display: none; background: #fff; border: 1px solid var(--setg-line); border-radius: 0 0 14px 14px; margin-bottom: 16px;
        box-shadow: 0 2px 12px rgba(15,23,42,.03); overflow: hidden; animation: setgFade .18s ease;
    }
    .setg-card.active { display: block; }
    @keyframes setgFade { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: none; } }
    .setg-savebar .setg-steps { display: flex; gap: 8px; }
    .setg-savebar .setg-steps .btn { background: #fff; border: 1px solid var(--setg-line); color: #334155; padding: 8px 14px; }
    .setg-savebar .setg-steps .btn:disabled { opacity: .4; }
    .setg-card-head { display: flex; gap: 12px; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--setg-line); }
    .setg-card-icon {
        width: 36px; height: 36px; border-radius: 10px; flex: 0 0 36px;
        display: flex; align-items: center; justify-content: center; font-size: 15px;
        background: rgba(79,70,229,.08); color: var(--setg-accent);
    }
    .setg-card-head h4 { margin: 0; font-size: 15px; font-weight: 800; color: var(--setg-ink); }
    .setg-card-head p { margin: 2px 0 0; font-size: 12.5px; color: var(--setg-muted); }
    .setg-card-body { padding: 18px 20px 4px; }

    .setg-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 0 18px; }
    .setg-col-12 { grid-column: span 12; } .setg-col-6 { grid-column: span 6; } .setg-col-4 { grid-column: span 4; }
    @media (max-width: 991px) { .setg-col-6, .setg-col-4 { grid-column: span 12; } }

    .setg-field { margin-bottom: 16px; }
    .setg-field label { display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px; }
    .setg-field .req { color: #ef4444; }
    .setg-field .form-control {
        height: 40px; border-radius: 9px; border: 1px solid #dbe2ea; box-shadow: none; font-size: 13.5px;
        transition: border-color .15s, box-shadow .15s;
    }
    .setg-field .form-control:focus { border-color: var(--setg-accent); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
    .setg-field .setg-input-icon { position: relative; }
    .setg-field .setg-input-icon > i { position: absolute; left: 12px; top: 13px; color: #94a3b8; font-size: 13px; pointer-events: none; }
    .setg-field .setg-input-icon .form-control { padding-left: 34px; }
    .setg-help { display: block; font-size: 11.5px; color: var(--setg-muted); margin-top: 5px; }
    .setg-field .text-danger:empty { display: none; }

    .setg-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
    .setg-tag { font-size: 11.5px; font-weight: 600; padding: 3px 10px; border-radius: 999px; background: var(--setg-soft); border: 1px solid var(--setg-line); color: #334155; }
    .setg-tag.bad { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }

    .setg-savebar {
        position: sticky; bottom: 0; z-index: 25; display: flex; align-items: center; justify-content: space-between; gap: 12px;
        background: rgba(255,255,255,.96); backdrop-filter: blur(6px);
        border: 1px solid var(--setg-line); border-radius: 14px; padding: 12px 18px; margin-bottom: 16px;
        box-shadow: 0 -6px 20px rgba(15,23,42,.06);
    }
    .setg-savebar small { color: var(--setg-muted); font-size: 12px; }
    .setg-savebar .btn { padding: 9px 26px; font-weight: 700; border-radius: 9px; }
    @media (max-width: 767px) { .setg-savebar small { display: none; } }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sliders"></i> <?php echo $this->lang->line('system_settings'); ?></h1>
    </section>

    <section class="content">
        <div class="row">

            <?php $this->load->view('setting/_settingmenu'); ?>

            <div class="col-lg-9 col-md-8 col-sm-8 setg-wrap">

                <div class="setg-hero">
                    <div class="setg-hero-left">
                        <div class="setg-hero-icon"><i class="fa fa-gear"></i></div>
                        <div>
                            <h3><?php echo $this->lang->line('general_setting'); ?></h3>
                            <p>School identity, academic session, regional formats and system paths.</p>
                        </div>
                    </div>
                    <span class="setg-chip" id="setg_status"><i class="fa fa-check-circle"></i> <span>All changes saved</span></span>
                </div>

                <div class="setg-note">
                    <i class="fa fa-info-circle"></i>
                    <div><strong><?php echo $this->lang->line('note'); ?>:</strong> <?php echo $this->lang->line('after_saving_general_setting_please_once_logout_then_relogin_so_changes_will_be_come_in_effect'); ?></div>
                </div>

                <nav class="setg-tabs" id="setg_tabs">
                    <a href="#setg_school" class="active"><i class="fa fa-university"></i>School</a>
                    <a href="#setg_session"><i class="fa fa-calendar"></i><?php echo $this->lang->line('academic_session'); ?></a>
                    <a href="#setg_datetime"><i class="fa fa-clock-o"></i><?php echo $this->lang->line('date_time'); ?></a>
                    <a href="#setg_currency"><i class="fa fa-money"></i><?php echo $this->lang->line('currency') ?></a>
                    <a href="#setg_digest"><i class="fa fa-envelope-o"></i>Email Digest</a>
                    <a href="#setg_paths"><i class="fa fa-folder-open-o"></i>Paths</a>
                </nav>

                <form role="form" id="schsetting_form" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">

                    <div class="setg-card active" id="setg_school">
                        <div class="setg-card-head">
                            <div class="setg-card-icon"><i class="fa fa-university"></i></div>
                            <div><h4>School Information</h4><p>How your school appears on reports, receipts and emails.</p></div>
                        </div>
                        <div class="setg-card-body">
                            <div class="setg-grid">
                                <div class="setg-col-6 setg-field">
                                    <label for="name"><?php echo $this->lang->line('school_name'); ?> <span class="req">*</span></label>
                                    <div class="setg-input-icon"><i class="fa fa-building-o"></i>
                                        <input type="text" class="form-control" id="name" name="sch_name" value="<?php echo $result->name; ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="setg-col-6 setg-field">
                                    <label for="dise_code"><?php echo $this->lang->line('school_code'); ?></label>
                                    <div class="setg-input-icon"><i class="fa fa-barcode"></i>
                                        <input type="text" class="form-control" id="dise_code" name="sch_dise_code" value="<?php echo $result->dise_code; ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('dise_code'); ?></span>
                                </div>
                                <div class="setg-col-12 setg-field">
                                    <label for="address"><?php echo $this->lang->line('address'); ?> <span class="req">*</span></label>
                                    <div class="setg-input-icon"><i class="fa fa-map-marker"></i>
                                        <input type="text" class="form-control" id="address" name="sch_address" value="<?php echo $result->address; ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('address'); ?></span>
                                </div>
                                <div class="setg-col-6 setg-field">
                                    <label for="phone"><?php echo $this->lang->line('phone'); ?> <span class="req">*</span></label>
                                    <div class="setg-input-icon"><i class="fa fa-phone"></i>
                                        <input type="text" class="form-control" id="phone" name="sch_phone" value="<?php echo $result->phone; ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('phone'); ?></span>
                                </div>
                                <div class="setg-col-6 setg-field">
                                    <label for="email"><?php echo $this->lang->line('email'); ?> <span class="req">*</span></label>
                                    <div class="setg-input-icon"><i class="fa fa-envelope-o"></i>
                                        <input type="text" class="form-control" id="email" name="sch_email" value="<?php echo $result->email; ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="setg-card" id="setg_session">
                        <div class="setg-card-head">
                            <div class="setg-card-icon"><i class="fa fa-calendar"></i></div>
                            <div><h4><?php echo $this->lang->line('academic_session'); ?></h4><p>The active session drives admissions, fees, attendance and exams.</p></div>
                        </div>
                        <div class="setg-card-body">
                            <div class="setg-grid">
                                <div class="setg-col-6 setg-field">
                                    <label for="session_id"><?php echo $this->lang->line('session'); ?> <span class="req">*</span></label>
                                    <select id="session_id" name="sch_session_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($sessionlist as $session) { ?>
                                            <option value="<?php echo $session['id'] ?>" <?php
                                            if ($session['id'] == $result->session_id) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $session['session'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="setg-help">Changing this switches the whole system to another year.</span>
                                    <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                </div>
                                <div class="setg-col-6 setg-field">
                                    <label for="start_month"><?php echo $this->lang->line('session_start_month'); ?> <span class="req">*</span></label>
                                    <select id="start_month" name="sch_start_month" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($monthList as $key => $month) { ?>
                                            <option value="<?php echo $key ?>" <?php
                                            if ($key == $result->start_month) {
                                                echo "selected";
                                            }
                                            ?> ><?php echo $month ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('start_month'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="setg-card" id="setg_datetime">
                        <div class="setg-card-head">
                            <div class="setg-card-icon"><i class="fa fa-clock-o"></i></div>
                            <div><h4><?php echo $this->lang->line('date_time'); ?></h4><p>Regional formats used across every screen and printout.</p></div>
                        </div>
                        <div class="setg-card-body">
                            <div class="setg-grid">
                                <div class="setg-col-4 setg-field">
                                    <label for="date_format"><?php echo $this->lang->line('date_format'); ?> <span class="req">*</span></label>
                                    <select id="date_format" name="sch_date_format" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($dateFormatList as $key => $dateformat) { ?>
                                            <option value="<?php echo $key ?>" <?php
                                            if ($key == $result->date_format) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $dateformat; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('date_format'); ?></span>
                                </div>
                                <div class="setg-col-4 setg-field">
                                    <label for="language_id"><?php echo $this->lang->line('timezone'); ?> <span class="req">*</span></label>
                                    <select id="language_id" name="sch_timezone" class="form-control">
                                        <option value="">--<?php echo $this->lang->line('select') ?>--</option>
                                        <?php foreach ($timezoneList as $key => $timezone) { ?>
                                            <option value="<?php echo $key ?>" <?php
                                            if ($key == $result->timezone) {
                                                echo "selected";
                                            }
                                            ?> ><?php echo $timezone ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('timezone'); ?></span>
                                </div>
                                <div class="setg-col-4 setg-field">
                                    <label for="start_week"><?php echo $this->lang->line('start_day_of_week') ?> <span class="req">*</span></label>
                                    <select id="start_week" name="sch_start_week" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($daysList as $day_key => $day_value) { ?>
                                            <option value="<?php echo $day_key ?>" <?php
                                            if ($day_key == $result->start_week) {
                                                echo "selected";
                                            }
                                            ?> ><?php echo $day_value ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('sch_start_week'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="setg-card" id="setg_currency">
                        <div class="setg-card-head">
                            <div class="setg-card-icon"><i class="fa fa-money"></i></div>
                            <div><h4><?php echo $this->lang->line('currency') ?></h4><p>How amounts are displayed in fees, payroll and reports.</p></div>
                        </div>
                        <div class="setg-card-body">
                            <div class="setg-grid">
                                <div class="setg-col-6 setg-field">
                                    <label for="currency_format"><?php echo $this->lang->line('currency_format'); ?> <span class="req">*</span></label>
                                    <select id="currency_format" name="currency_format" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($currency_formats as $cur_format_key => $cur_format) { ?>
                                            <option value="<?php echo $cur_format_key ?>" <?php
                                            if ($cur_format_key == $result->currency_format) {
                                                echo "selected";
                                            }
                                            ?> ><?php echo $cur_format; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('currency_format'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="setg-card" id="setg_digest">
                        <div class="setg-card-head">
                            <div class="setg-card-icon"><i class="fa fa-envelope-o"></i></div>
                            <div><h4>Email Digest Settings</h4><p>Who receives the automated summary emails, and when.</p></div>
                        </div>
                        <div class="setg-card-body">
                            <div class="setg-grid">
                                <div class="setg-col-12 setg-field">
                                    <label for="email_digest_recipients">Recipients</label>
                                    <div class="setg-input-icon"><i class="fa fa-users"></i>
                                        <input type="text" class="form-control" id="email_digest_recipients" name="email_digest_recipients" value="<?php echo $result->email_digest_recipients; ?>" placeholder="admin@school.com, director@school.com">
                                    </div>
                                    <span class="setg-help">Separate multiple addresses with commas.</span>
                                    <div class="setg-tags" id="setg_recipient_tags"></div>
                                </div>
                                <div class="setg-col-4 setg-field">
                                    <label for="daily_digest_time">Daily Digest</label>
                                    <input type="time" class="form-control" id="daily_digest_time" name="daily_digest_time" value="<?php echo $result->daily_digest_time; ?>">
                                    <span class="setg-help">Sent every day</span>
                                </div>
                                <div class="setg-col-4 setg-field">
                                    <label for="weekly_digest_time">Weekly Digest</label>
                                    <input type="time" class="form-control" id="weekly_digest_time" name="weekly_digest_time" value="<?php echo $result->weekly_digest_time; ?>">
                                    <span class="setg-help">Sent every Sunday</span>
                                </div>
                                <div class="setg-col-4 setg-field">
                                    <label for="monthly_digest_time">Monthly Digest</label>
                                    <input type="time" class="form-control" id="monthly_digest_time" name="monthly_digest_time" value="<?php echo $result->monthly_digest_time; ?>">
                                    <span class="setg-help">Sent on the 1st of each month</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="setg-card" id="setg_paths">
                        <div class="setg-card-head">
                            <div class="setg-card-icon"><i class="fa fa-folder-open-o"></i></div>
                            <div><h4><?php echo $this->lang->line('file_upload_path'); ?></h4><p>Advanced: where the app is served from and where uploads are stored.</p></div>
                        </div>
                        <div class="setg-card-body">
                            <div class="setg-grid">
                                <div class="setg-col-6 setg-field">
                                    <label for="base_url"><?php echo $this->lang->line('base_url'); ?> <span class="req">*</span></label>
                                    <div class="setg-input-icon"><i class="fa fa-globe"></i>
                                        <input type="text" class="form-control" id="base_url" name="base_url" value="<?php echo $result->base_url; ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('base_url'); ?></span>
                                </div>
                                <div class="setg-col-6 setg-field">
                                    <label for="folder_path"><?php echo $this->lang->line('file_upload_path'); ?> <span class="req">*</span></label>
                                    <div class="setg-input-icon"><i class="fa fa-hdd-o"></i>
                                        <input type="text" class="form-control" id="folder_path" name="folder_path" value="<?php echo $result->folder_path; ?>">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('folder_path'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="setg-savebar">
                        <div class="setg-steps">
                            <button type="button" class="btn" id="setg_prev"><i class="fa fa-angle-left"></i> Previous</button>
                            <button type="button" class="btn" id="setg_next">Next <i class="fa fa-angle-right"></i></button>
                        </div>
                        <?php
                        if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                            ?>
                            <button type="button" class="btn btn-primary submit_schsetting pull-right edit_setting" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>" title="Ctrl + S"> <i class="fa fa-check" style="margin-right: 4px;"></i> <?php echo $this->lang->line('save'); ?></button>
                            <?php
                        }
                        ?>
                    </div>
                </form>
            </div><!--/.col (left) -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">

    var base_url = '<?php echo base_url(); ?>';

    // Unsaved-changes indicator
    var gsSaved = $('#schsetting_form').serialize();
    function gsRefreshStatus() {
        var dirty = $('#schsetting_form').serialize() !== gsSaved;
        $('#setg_status').toggleClass('dirty', dirty)
            .find('i').attr('class', dirty ? 'fa fa-pencil' : 'fa fa-check-circle');
        $('#setg_status span').text(dirty ? 'Unsaved changes' : 'All changes saved');
    }
    $('#schsetting_form').on('input change', gsRefreshStatus);
    $(window).on('beforeunload', function () {
        if ($('#schsetting_form').serialize() !== gsSaved) {
            return 'You have unsaved changes.';
        }
    });

    $(".edit_setting").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/generalsetting") ?>',
            type: 'POST',
            data: $('#schsetting_form').serialize(),
            dataType: 'json',

            success: function (data) {

                if (data.status == "fail") {
                    var message = "", firstTab = null;
                    $('#setg_tabs a').removeClass('has-error');
                    $.each(data.error, function (index, value) {

                        message += value;
                        if (value) {
                            var $card = $('#schsetting_form [name="' + index + '"]').closest('.setg-card');
                            if ($card.length) {
                                var $tab = $('#setg_tabs a[href="#' + $card.attr('id') + '"]').addClass('has-error');
                                firstTab = firstTab || $tab.attr('href');
                            }
                        }
                    });
                    if (firstTab) {
                        setgShow(firstTab);
                    }
                    errorMsg(message);
                } else {
                    $('#setg_tabs a').removeClass('has-error');
                    gsSaved = $('#schsetting_form').serialize();
                    gsRefreshStatus();
                    successMsg(data.message);
                }

                $this.button('reset');
            }
        });
    });

    // Ctrl/Cmd + S saves
    $(document).on('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S') && $('.edit_setting').length) {
            e.preventDefault();
            $('.edit_setting').trigger('click');
        }
    });

    // Recipient chips with simple email validation
    function gsRenderRecipients() {
        var $box = $('#setg_recipient_tags').empty();
        $.each(($('#email_digest_recipients').val() || '').split(','), function (i, v) {
            v = $.trim(v);
            if (!v) return;
            var ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
            $('<span class="setg-tag"></span>').toggleClass('bad', !ok).text(v).appendTo($box);
        });
    }
    $('#email_digest_recipients').on('input', gsRenderRecipients);
    gsRenderRecipients();

    // Section tabs: show one section at a time (hidden fields still submit with the form)
    var setgOrder = $('#setg_tabs a').map(function () { return $(this).attr('href'); }).get();
    function setgShow(target) {
        if (setgOrder.indexOf(target) === -1) target = setgOrder[0];
        $('#setg_tabs a').removeClass('active').filter('[href="' + target + '"]').addClass('active');
        $('.setg-card').removeClass('active');
        $(target).addClass('active');
        var i = setgOrder.indexOf(target);
        $('#setg_prev').prop('disabled', i <= 0);
        $('#setg_next').prop('disabled', i >= setgOrder.length - 1);
        try { localStorage.setItem('setg_tab', target); } catch (err) {}
    }
    $('#setg_tabs a').on('click', function (e) {
        e.preventDefault();
        setgShow($(this).attr('href'));
    });
    $('#setg_prev, #setg_next').on('click', function () {
        var i = setgOrder.indexOf($('#setg_tabs a.active').attr('href')) + ($(this).is('#setg_next') ? 1 : -1);
        if (setgOrder[i]) setgShow(setgOrder[i]);
    });
    var setgStart = null;
    try { setgStart = localStorage.getItem('setg_tab'); } catch (err) {}
    setgShow(setgStart || setgOrder[0]);

    function togglePasswordVisibility(inputId, btn) {
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
