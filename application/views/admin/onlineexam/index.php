<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/adapters/jquery.js"></script>
<?php
$language      = $this->customlib->getLanguage();
$language_name = $language["short_code"];
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.onlineexam-studio-wrapper {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* Modern KPI Summary Stat Cards */
.modern-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}
.modern-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    transition: all 0.2s ease;
}
.modern-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
}
.modern-stat-info .stat-label {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.modern-stat-info .stat-value {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
}
.modern-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

/* Modern Card & Tab Container */
.modern-exam-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    margin-bottom: 25px;
}

.modern-exam-card .nav-tabs-custom {
    box-shadow: none;
    margin-bottom: 0;
    border-radius: 12px;
    overflow: hidden;
}

.modern-exam-card .nav-tabs {
    border-bottom: 1px solid #f1f5f9;
    padding: 6px 16px 0 16px;
    background: #f8fafc;
}

.modern-exam-card .nav-tabs > li > a {
    border-radius: 8px 8px 0 0;
    font-weight: 700;
    font-size: 13.5px;
    color: #64748b;
    padding: 10px 18px;
    border: 1px solid transparent;
    transition: all 0.15s ease;
}

.modern-exam-card .nav-tabs > li.active > a,
.modern-exam-card .nav-tabs > li.active > a:focus,
.modern-exam-card .nav-tabs > li.active > a:hover {
    color: #4f46e5;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-bottom-color: #ffffff;
}

.modern-exam-card .tab-content {
    padding: 20px;
    background: #ffffff;
}

/* Slide-in Right Side Drawer */
.modern-drawer-overlay {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    background: rgba(15, 23, 42, 0.6) !important;
    backdrop-filter: blur(4px) !important;
    z-index: 99998 !important;
    opacity: 0 !important;
    visibility: hidden !important;
    transition: opacity 0.3s ease, visibility 0.3s ease !important;
    pointer-events: none !important;
}
.modern-drawer-overlay.is-active {
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important;
}
.modern-drawer-panel {
    position: fixed !important;
    top: 0 !important;
    right: 0 !important;
    width: 720px !important;
    max-width: 95vw !important;
    height: 100vh !important;
    max-height: 100vh !important;
    background: #ffffff !important;
    z-index: 99999 !important;
    box-shadow: -10px 0 25px -5px rgba(0, 0, 0, 0.15) !important;
    display: flex !important;
    flex-direction: column !important;
    overflow: hidden !important;
    transform: translateX(110%) !important;
    visibility: hidden !important;
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.35s ease !important;
}
.modern-drawer-panel.is-open {
    transform: translateX(0%) !important;
    visibility: visible !important;
}
.modern-drawer-header {
    background: #ffffff;
    color: #0f172a;
    padding: 18px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}
.modern-drawer-title {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}
.modern-drawer-close {
    background: transparent;
    border: none;
    color: #64748b;
    font-size: 24px;
    cursor: pointer;
    line-height: 1;
    opacity: 0.8;
    transition: all 0.15s ease;
}
.modern-drawer-close:hover {
    color: #0f172a;
    opacity: 1;
}
.modern-drawer-body {
    padding: 22px 24px 40px 24px;
    overflow-y: auto !important;
    overflow-x: hidden;
    flex: 1 1 auto;
    background: #ffffff;
    -webkit-overflow-scrolling: touch;
}
.modern-drawer-footer {
    padding: 14px 24px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    flex-shrink: 0;
}

.modern-form-section-title {
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 15px 0 10px 0;
    padding-bottom: 6px;
    border-bottom: 1px dashed #e2e8f0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.modern-checkbox-tile {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 12px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    transition: all 0.15s ease;
    cursor: pointer;
}
.modern-checkbox-tile:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}
.modern-checkbox-tile label {
    margin-bottom: 0;
    cursor: pointer;
    font-weight: 600;
    font-size: 13px;
    color: #334155;
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
}

@media print {
    .noprint {
        visibility: hidden;
    }
}
</style>

<div class="content-wrapper onlineexam-studio-wrapper">
    <section class="content-header" style="padding-top: 15px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center;">
                <i class="fa fa-newspaper-o" style="color: #6366f1; margin-right: 8px;"></i> <?php echo $this->lang->line('online_exam_list'); ?>
                <small style="font-size: 13px; color: #64748b; margin-left: 8px;">CBT & Online Examination Studio</small>
            </h1>
            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" class="btn btn-primary btn-sm question-btn" data-recordid="0" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 7px 16px; border-radius: 6px; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.35); font-size: 13px; cursor: pointer;">
                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_exam'); ?>
                </button>
            </div>
        </div>
    </section>

    <section class="content" style="padding-top: 10px;">
        <!-- Modern KPI Stat Grid -->
        <div class="modern-stat-grid">
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Total Exams</div>
                    <div class="stat-value" style="color: #6366f1;">
                        <?php 
                        $total_exams = $this->db->where('session_id', $this->setting_model->getCurrentSession())->count_all_results('onlineexam');
                        echo number_format($total_exams); 
                        ?>
                    </div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                    <i class="fa fa-list-alt"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Published / Active</div>
                    <div class="stat-value" style="color: #10b981;">
                        <?php 
                        $active_exams = $this->db->where('session_id', $this->setting_model->getCurrentSession())->where('is_active', 1)->count_all_results('onlineexam');
                        echo number_format($active_exams); 
                        ?>
                    </div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Subject Repositories</div>
                    <div class="stat-value" style="color: #0284c7;">
                        <?php echo count($subjectlist); ?>
                    </div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(2, 132, 199, 0.12); color: #0284c7;">
                    <i class="fa fa-book"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Active Classes</div>
                    <div class="stat-value" style="color: #8b5cf6;">
                        <?php echo count($classList); ?>
                    </div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
                    <i class="fa fa-graduation-cap"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="modern-exam-card">
                    <div class="nav-tabs-custom theme-shadow">
                        <div style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; border-bottom: 1px solid #f1f5f9; padding: 6px 16px 0 16px;">
                            <ul class="nav nav-tabs" style="border-bottom: none; background: transparent; padding: 0;">
                                <li class="active"><a href="#tab_1" data-toggle="tab"><i class="fa fa-calendar-check-o text-primary" style="margin-right: 5px;"></i> <?php echo $this->lang->line('upcoming_exams'); ?></a></li>
                                <li><a href="#tab_3" class="closed-exam" data-toggle="tab"><i class="fa fa-history text-muted" style="margin-right: 5px;"></i> <?php echo $this->lang->line('closed_exams'); ?></a></li>
                            </ul>
                            <div style="padding-bottom: 6px;">
                                <button type="button" class="btn btn-primary btn-sm question-btn" data-recordid="0" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 6px 14px; border-radius: 6px; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.35); font-size: 12px; cursor: pointer;">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_exam'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="mailbox-messages">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover exam-list" data-export-title="<?php echo $this->lang->line('online_exam_list'); ?>">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('exam'); ?></th>
                                                    <th><?php echo $this->lang->line('quiz'); ?></th>
                                                    <th width="150"><?php echo $this->lang->line('questions'); ?></th>
                                                    <th><?php echo $this->lang->line('attempt'); ?></th>
                                                    <th><?php echo $this->lang->line('exam_from'); ?></th>
                                                    <th><?php echo $this->lang->line('exam_to') ?></th>
                                                    <th><?php echo $this->lang->line('duration') ?></th>
                                                    <th><?php echo $this->lang->line('exam_published'); ?></th>
                                                    <th><?php echo $this->lang->line('result_published'); ?></th>
                                                    <th><?php echo $this->lang->line('description'); ?></th>
                                                    <th class="white-space-nowrap pull-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_3">
                                <form action="<?php echo site_url('admin/onlineexam/ajax_delete') ?>" method="POST" id="deletebulk">
                                    <div class="mailbox-messages">
                                        <div class="checkbox" style="padding-bottom: 10px;">
                                            <label><input type="checkbox" name="checkAll"> <b><?php echo $this->lang->line('select_all'); ?></b> </label>

                                            <?php if ($this->rbac->hasPrivilege('online_examination', 'can_delete')) {?>
                                                <button type="submit" class="btn btn-danger btn-sm pull-right mb10" id="load" style="border-radius: 6px; font-weight: 600;" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"> 
                                                    <i class="fa fa-trash"></i> <?php echo $this->lang->line('delete') ?>
                                                </button>
                                            <?php }?>
                                        </div>
                                        <div class="table-responsive full-width">
                                            <table class="table table-striped table-bordered table-hover closed-exam-list" data-export-title="<?php echo $this->lang->line('online_exam_list'); ?>">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><?php echo $this->lang->line('exam'); ?></th>
                                                        <th><?php echo $this->lang->line('quiz'); ?></th>
                                                        <th width="150"><?php echo $this->lang->line('questions'); ?></th>
                                                        <th><?php echo $this->lang->line('attempt'); ?></th>
                                                        <th><?php echo $this->lang->line('exam_from'); ?></th>
                                                        <th><?php echo $this->lang->line('exam_to') ?></th>
                                                        <th><?php echo $this->lang->line('duration') ?></th>
                                                        <th><?php echo $this->lang->line('exam_published'); ?></th>
                                                        <th><?php echo $this->lang->line('result_published'); ?></th>
                                                        <th><?php echo $this->lang->line('description'); ?></th>
                                                        <th class="pull-right noExport white-space-nowrap"><?php echo $this->lang->line('action'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
function findOption($questionOpt, $find)
{
    foreach ($questionOpt as $quet_opt_key => $quet_opt_value) {
        if ($quet_opt_key == $find) {
            return $quet_opt_value;
        }
    }
    return false;
}
?>

<!-- Slide-in Right Side Drawer: Add / Edit Exam -->
<div id="drawerExamOverlay" class="modern-drawer-overlay" onclick="closeExamDrawer()"></div>
<div id="drawerExamPanel" class="modern-drawer-panel">
    <div class="modern-drawer-header">
        <h4 class="modern-drawer-title" id="drawerExamTitle">
            <i class="fa fa-newspaper-o" style="color: #6366f1;"></i> Exam Configuration & Schedule
        </h4>
        <button type="button" class="modern-drawer-close" onclick="closeExamDrawer()">&times;</button>
    </div>
    <form action="<?php echo site_url('admin/onlineexam/add'); ?>" method="POST" id="formsubject" style="display: flex; flex-direction: column; flex: 1; overflow: hidden; margin-bottom: 0;">
        <div class="modern-drawer-body">
            <input type="hidden" name="recordid" value="0">
            
            <div class="modern-form-section-title">
                <i class="fa fa-info-circle text-primary"></i> Basic Exam Information
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="modern-checkbox-tile">
                        <label>
                            <input type="checkbox" class="is_quiz" value="1" name="is_quiz">
                            <span><b><?php echo $this->lang->line('quiz'); ?></b> - <small class="text-muted"><?php echo $this->lang->line('check_on_quiz_message'); ?></small></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="exam" style="font-weight: 600; color: #334155;"><?php echo $this->lang->line('exam_title'); ?></label><small class="req"> *</small>
                        <input type="text" class="form-control" id="exam" name="exam" placeholder="e.g. CBSE Term-1 Assessment" style="border-radius: 6px;">
                        <span class="text text-danger exam_error"></span>
                    </div>
                </div>
            </div>

            <div class="modern-form-section-title">
                <i class="fa fa-clock-o text-primary"></i> Schedule & Timing
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="exam_from" style="font-weight: 600; color: #334155;"><?php echo $this->lang->line('exam_from'); ?></label><small class="req"> *</small>
                        <div class="input-group">
                            <input class="form-control datetime_twelve_hour" name="exam_from" type="text" id="exam_from" style="border-radius: 6px 0 0 6px;">
                            <span class="input-group-addon" style="border-radius: 0 6px 6px 0; background: #f8fafc;">
                                <i class="fa fa-calendar text-muted"></i>
                            </span>
                        </div>
                        <span class="text text-danger exam_from_error"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="exam_to" style="font-weight: 600; color: #334155;"><?php echo $this->lang->line('exam_to'); ?></label><small class="req"> *</small>
                        <div class="input-group">
                            <input class="form-control datetime_twelve_hour" name="exam_to" type="text" id="exam_to" style="border-radius: 6px 0 0 6px;">
                            <span class="input-group-addon" style="border-radius: 0 6px 6px 0; background: #f8fafc;">
                                <i class="fa fa-calendar text-muted"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="auto_publish_date" style="font-weight: 600; color: #334155;"><?php echo $this->lang->line('auto_result_publish_date') ?></label>
                        <div class="input-group">
                            <input class="form-control datetime_twelve_hour" name="auto_publish_date" type="text" id="auto_publish_date" style="border-radius: 6px 0 0 6px;">
                            <span class="input-group-addon" style="border-radius: 0 6px 6px 0; background: #f8fafc;">
                                <i class="fa fa-calendar text-muted"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-form-section-title">
                <i class="fa fa-sliders text-primary"></i> Duration, Attempts & Grading
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="duration" style="font-weight: 600; color: #334155;"><?php echo $this->lang->line('time_duration'); ?></label><small class="req"> *</small>
                        <input type="text" class="form-control timepicker" id="duration" name="duration" style="border-radius: 6px;">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="attempt" style="font-weight: 600; color: #334155;"><?php echo $this->lang->line('attempt'); ?></label><small class="req"> *</small>
                        <input type="number" min="1" class="form-control" id="attempt" name="attempt" value="1" style="border-radius: 6px;">
                        <span class="text text-danger attempt_error"></span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="passing_percentage" style="font-weight: 600; color: #334155;"><?php echo $this->lang->line('passing_percentage'); ?></label><small class="req"> *</small>
                        <input type="number" min="1" max="100" class="form-control" id="passing_percentage" name="passing_percentage" placeholder="33" style="border-radius: 6px;">
                        <span class="text text-danger passing_percentage_error"></span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="word_limit" style="font-weight: 600; color: #334155;"><?php echo $this->lang->line('answer_word_limit'); ?></label><small class="req"> *</small>
                        <input type="number" min="-1" class="form-control" id="word_limit" value="-1" name="word_limit" style="border-radius: 6px;">
                        <span class="text text-muted" style="font-size: 11px;"><?php echo $this->lang->line('set_minus_one_for_no_limit'); ?></span>
                    </div>
                </div>
            </div>

            <div class="modern-form-section-title">
                <i class="fa fa-toggle-on text-primary"></i> Exam Controls & Options
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="modern-checkbox-tile">
                        <label>
                            <input type="checkbox" class="is_active" name="is_active" value="1">
                            <span><?php echo $this->lang->line('publish_exam'); ?></span>
                        </label>
                    </div>
                    <div class="modern-checkbox-tile">
                        <label>
                            <input type="checkbox" class="publish_result" name="publish_result" value="1">
                            <span><?php echo $this->lang->line('publish_result'); ?></span>
                        </label>
                    </div>
                    <div class="modern-checkbox-tile">
                        <label>
                            <input type="checkbox" class="is_neg_marking" name="is_neg_marking" value="1">
                            <span><?php echo $this->lang->line('negative_marking') ?></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="modern-checkbox-tile">
                        <label>
                            <input type="checkbox" class="is_marks_display" name="is_marks_display" value="1">
                            <span><?php echo $this->lang->line('display_marks_in_exam'); ?></span>
                        </label>
                    </div>
                    <div class="modern-checkbox-tile">
                        <label>
                            <input type="checkbox" class="is_random_question" name="is_random_question" value="1">
                            <span><?php echo $this->lang->line('random_question_order'); ?></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="modern-form-section-title">
                <i class="fa fa-align-left text-primary"></i> Instructions / Description
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="description" style="font-weight: 600; color: #334155;"><?php echo $this->lang->line('description'); ?><small class="req"> *</small></label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                        <span class="text text-danger description_error"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modern-drawer-footer">
            <button type="button" class="btn btn-default" onclick="closeExamDrawer()" style="border-radius: 6px;">Cancel</button>
            <button type="submit" class="btn btn-primary" id="load" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 8px 20px; border-radius: 6px; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.35);" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving...">
                <i class="fa fa-save"></i> <?php echo $this->lang->line('save') ?>
            </button>
        </div>
    </form>
</div>

<!-- Slide-in Right Side Drawer: Select & Add Questions to Exam -->
<div id="drawerSelectQuestionOverlay" class="modern-drawer-overlay" onclick="closeSelectQuestionDrawer()"></div>
<div id="drawerSelectQuestionPanel" class="modern-drawer-panel" style="width: 820px !important;">
    <div class="modern-drawer-header">
        <h4 class="modern-drawer-title">
            <i class="fa fa-plus-circle" style="color: #6366f1;"></i> <?php echo $this->lang->line('select_questions'); ?>
        </h4>
        <button type="button" class="modern-drawer-close" onclick="closeSelectQuestionDrawer()">&times;</button>
    </div>
    <div class="modern-drawer-body" style="padding: 16px 20px;">
        <input type="hidden" name="modal_exam_id" value="0" id="modal_exam_id">
        <input type="hidden" name="modal_is_quiz" value="0" id="modal_is_quiz">
        <form action="" method="POST" accept-charset="utf-8" id="form_search">
            <div class="row" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 10px; margin-bottom: 14px;">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group" style="margin-bottom: 8px;">
                        <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('seach_by_keyword'); ?></label>
                        <input type="text" class="form-control input-sm" name="keyword" id="keyword" placeholder="Search question..." style="border-radius: 6px;">
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="form-group" style="margin-bottom: 8px;">
                        <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('question_type'); ?></label>
                        <select class="form-control input-sm" name="question_type" id="question_type" style="border-radius: 6px;">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php foreach ($question_type as $question_type_key => $question_type_value) { ?>
                                <option value="<?php echo $question_type_key; ?>"><?php echo $question_type_value; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="form-group" style="margin-bottom: 8px;">
                        <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('question_level'); ?></label>
                        <select class="form-control input-sm" name="question_level" id="question_level" style="border-radius: 6px;">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php foreach ($question_level as $question_level_key => $question_level_value) { ?>
                                <option value="<?php echo $question_level_key; ?>"><?php echo $question_level_value; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="form-group" style="margin-bottom: 8px;">
                        <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('subject') ?></label>
                        <select class="form-control input-sm" name="search_box" id="search_box" style="border-radius: 6px;">
                            <option value=""><?php echo $this->lang->line('select') ?></option>
                            <?php foreach ($subjectlist as $subject_key => $subject_value) { ?>
                                <option value="<?php echo $subject_value['id']; ?>"><?php echo $subject_value['name']; ?> <?php if($subject_value['code']){ echo '('.$subject_value['code'].')'; } ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="form-group" style="margin-bottom: 8px;">
                        <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('class') ?></label>
                        <select class="form-control input-sm" name="class_id" id="class_id" style="border-radius: 6px;">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php foreach ($classList as $class_key => $class_value) { ?>
                                <option value="<?php echo $class_value['id']; ?>"><?php echo $class_value['class']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="form-group" style="margin-bottom: 8px;">
                        <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;"><?php echo $this->lang->line('section') ?></label>
                        <select id="section_id" name="section_id" class="form-control input-sm" style="border-radius: 6px;">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6" style="padding-top: 21px;">
                    <button type="button" class="btn btn-primary btn-sm post_search_submit" style="width: 100%; border-radius: 6px; font-weight: 700; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; padding: 5px 10px;">
                        <i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?>
                    </button>
                </div>
            </div>
            
            <div class="search_box_result quescroll">
            </div>
            
            <div class="row" style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #e2e8f0;">
                <div class="col-sm-12 col-md-5">
                    <div style="font-weight: 600; color: #64748b; font-size: 13px; padding-top: 8px;">
                        <?php echo $this->lang->line('showing'); ?> <span class="row_from"></span> <?php echo $this->lang->line('to'); ?> <span class="row_to"></span> <?php echo $this->lang->line('of'); ?> <span class="row_count"></span> <?php echo $this->lang->line('search'); ?>
                    </div>
                </div>
                <div class="col-sm-12 col-md-7 search_box_pagination text-right">
                </div>
            </div>
        </form>
    </div>
    <div class="modern-drawer-footer">
        <button type="button" class="btn btn-default btn-sm" onclick="closeSelectQuestionDrawer()" style="border-radius: 6px; font-weight: 600;">Done / Close</button>
    </div>
</div>

<!-- Slide-in Right Side Drawer: Exam Questions List -->
<div id="drawerExamQuestionListOverlay" class="modern-drawer-overlay" onclick="closeExamQuestionListDrawer()"></div>
<div id="drawerExamQuestionListPanel" class="modern-drawer-panel" style="width: 780px !important;">
    <div class="modern-drawer-header">
        <h4 class="modern-drawer-title">
            <i class="fa fa-list" style="color: #6366f1;"></i> <span id="examQuesListHeader">Exam Question Repository</span>
        </h4>
        <button type="button" class="modern-drawer-close" onclick="closeExamQuestionListDrawer()">&times;</button>
    </div>
    <div class="modern-drawer-body">
        <div class="question_list_result quescroll">
        </div>
    </div>
    <div class="modern-drawer-footer">
        <button type="button" class="btn btn-default btn-sm" onclick="closeExamQuestionListDrawer()" style="border-radius: 6px; font-weight: 600;">Close</button>
    </div>
</div>

<!-- Slide-in Right Side Drawer: Generate Rank -->
<div id="drawerRankOverlay" class="modern-drawer-overlay" onclick="closeRankDrawer()"></div>
<div id="drawerRankPanel" class="modern-drawer-panel" style="width: 780px !important;">
    <div class="modern-drawer-header">
        <h4 class="modern-drawer-title">
            <i class="fa fa-trophy" style="color: #f59e0b;"></i> <span id="examRankTitle">Generate Rank</span>
        </h4>
        <button type="button" class="modern-drawer-close" onclick="closeRankDrawer()">&times;</button>
    </div>
    <div class="modern-drawer-body drawerRankBody modalminheight">
    </div>
    <div class="modern-drawer-footer">
        <button type="button" class="btn btn-default btn-sm" onclick="closeRankDrawer()" style="border-radius: 6px; font-weight: 600;">Close</button>
    </div>
</div>

<!-- Modal: Delete Confirmation -->
<div class="modal fade" id="mydeleteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <form action="<?php echo site_url('admin/onlineexam/deleteExamQuestions') ?>" id="delete_question" method="POST">
                <input type="hidden" value="0" id="question_id" name="question_id"/>
                <div class="modal-header" style="background: #fef2f2; border-bottom: 1px solid #fee2e2; padding: 16px 20px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-danger" id="myModalLabel" style="font-weight: 700;">
                        <i class="fa fa-trash"></i> <?php echo $this->lang->line('delete_question'); ?>
                    </h4>
                </div>
                <div class="modal-body" style="padding: 20px; font-size: 14px; color: #334155;">
                    <?php echo $this->lang->line('delete_confirm'); ?>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;"><?php echo $this->lang->line('close') ?></button>
                    <button type="submit" class="btn btn-danger pull-right" style="border-radius: 6px; font-weight: 600;" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>">
                        <i class="fa fa-trash"></i> <?php echo $this->lang->line('delete'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openExamDrawer(recordid = 0) {
        $('input[name=recordid]').val(recordid);
        if (recordid == 0) {
            $('#drawerExamTitle').html('<i class="fa fa-plus-circle" style="color: #6366f1;"></i> <?php echo $this->lang->line('add_exam'); ?>');
            $('#formsubject')[0].reset();
            $('.is_quiz').prop('checked', false);
            $("input.publish_result").removeAttr("disabled");
            $("input#auto_publish_date").removeAttr("disabled");
            if (CKEDITOR.instances['description']) {
                CKEDITOR.instances['description'].setData('');
            }
            $('#drawerExamOverlay').addClass('is-active');
            $('#drawerExamPanel').addClass('is-open');
        } else {
            $('#drawerExamTitle').html('<i class="fa fa-pencil" style="color: #6366f1;"></i> <?php echo $this->lang->line('edit_exam'); ?>');
            $('#drawerExamOverlay').addClass('is-active');
            $('#drawerExamPanel').addClass('is-open');
        }
    }

    function closeExamDrawer() {
        $('#drawerExamOverlay').removeClass('is-active');
        $('#drawerExamPanel').removeClass('is-open');
    }

    function openSelectQuestionDrawer(exam_id, is_quiz) {
        if(is_quiz == 1){
            $("select#question_type option[value*='descriptive']").prop('disabled', true);
        }else{
            $("select#question_type option[value*='descriptive']").prop('disabled', false);
        }
        $('#modal_exam_id').val(exam_id);
        $('#modal_is_quiz').val(is_quiz);
        getQuestionByExam(1, exam_id, is_quiz);
        $('#drawerSelectQuestionOverlay').addClass('is-active');
        $('#drawerSelectQuestionPanel').addClass('is-open');
    }

    function closeSelectQuestionDrawer() {
        $('#drawerSelectQuestionOverlay').removeClass('is-active');
        $('#drawerSelectQuestionPanel').removeClass('is-open');
        $('#drawerSelectQuestionPanel').find("input,textarea,select").val('');
        $('.search_box_result').html("");
        $('.search_box_pagination').html("");
        if ($.fn.DataTable.isDataTable('.exam-list')) {
            $('.exam-list').DataTable().ajax.reload(null, false);
        }
    }

    function openExamQuestionListDrawer() {
        $('#drawerExamQuestionListOverlay').addClass('is-active');
        $('#drawerExamQuestionListPanel').addClass('is-open');
    }

    function closeExamQuestionListDrawer() {
        $('#drawerExamQuestionListOverlay').removeClass('is-active');
        $('#drawerExamQuestionListPanel').removeClass('is-open');
        if ($.fn.DataTable.isDataTable('.exam-list')) {
            $('.exam-list').DataTable().ajax.reload(null, false);
        }
    }

    function openRankDrawer(examid, examtitle) {
        $('#examRankTitle').html('<?php echo $this->lang->line('generate_exam_rank'); ?> (' + examtitle + ')');
        $('#drawerRankOverlay').addClass('is-active');
        $('#drawerRankPanel').addClass('is-open');
        getRankRecord(examid, examtitle);
    }

    function closeRankDrawer() {
        $('#drawerRankOverlay').removeClass('is-active');
        $('#drawerRankPanel').removeClass('is-open');
    }

    $(document).ready(function () {
        CKEDITOR.env.isCompatible = true;
        $('[id="description"]').ckeditor({
            toolbar: 'Admin_Exam',
            allowedContent: true,
            enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            customConfig: baseurl + '/backend/js/ckeditor_config.js',
        });

        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>

<script type="text/javascript">
$(document).on('submit','#delete_question',function(e) {
    e.preventDefault();
    var form = $(this);
    var question_id=form.find("input[id='question_id']").val();
    var url = form.attr('action');
    var $this = form.find("button[type=submit]:focus");
    $this.button('loading');
    $.ajax({
        url: url,
        type: "POST",
        data: new FormData(this),
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $this.button('loading');
        },
        success: function (res) {
            $('.question_row_'+question_id).remove();
            $this.button('reset');
            if (res.status == 1) {
                $('#mydeleteModal').modal('hide');
                successMsg(res.message);
            }
        },
        error: function (xhr) {
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');
        },
        complete: function () {
            $this.button('reset');
        }
    });
});

$('#mydeleteModal').on('shown.bs.modal', function (e) {
    var question_id = $(e.relatedTarget).data('onlineexamQuestionId');
    $("#mydeleteModal input[id='question_id']").val(question_id);
});

$(document).ready(function () {
    $('#mydeleteModal').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });

    var date_format_js = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy']) ?>';

    $(function () {
        var dateNow = new Date();
        $('.timepicker').datetimepicker({
            format: 'HH:mm:ss',
            defaultDate: moment(dateNow).hours(0).minutes(0).seconds(0).milliseconds(0)
        });
    });

    $(document).on('click', '.question-btn', function () {
        var recordid = $(this).data('recordid');
        openExamDrawer(recordid);
    });

    $(document).on('click', '.add-question-drawer-btn', function () {
        var exam_id = $(this).data('recordid');
        var is_quiz = $(this).data('is_quiz');
        openSelectQuestionDrawer(exam_id, is_quiz);
    });

    $(document).on('click', '.generate_rank', function () {
        var $this = $(this);
        var examid = $this.data('recordid');
        var examtitle = $this.data('examTitle');
        openRankDrawer(examid, examtitle);
    });

    $(document).on('click', '.download_exam', function () {
        var $this = $(this);
        var recordid = $(this).data('recordid');
        $.ajax({
            type: 'POST',
            url: baseurl + "admin/onlineexam/download_exam",
            data: {'recordid': recordid},
            dataType: 'JSON',
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {
                Popup(data.page);
                $this.button('reset');
            },
            error: function (xhr) {
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    });

    $(document).on('click', '.question-btn-edit', function () {
        var $this = $(this);
        var recordid = $this.data('recordid');
        $('input[name=recordid]').val(recordid);
        openExamDrawer(recordid);
        $.ajax({
            type: 'POST',
            url: baseurl + "admin/onlineexam/getOnlineExamByID",
            data: {'recordid': recordid},
            dataType: 'JSON',
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {
                if (data.status) {
                    var date_exam_from = new Date(data.result.exam_from);
                    var date_exam_to = new Date(data.result.exam_to);

                    if(data.result.auto_publish_date != null && data.result.auto_publish_date != "" && data.result.auto_publish_date != "0000-00-00"){
                        var date_auto_publish_date = new Date(data.result.auto_publish_date);
                        $('#auto_publish_date').data("DateTimePicker").date(date_auto_publish_date);
                    } else {
                        $('#auto_publish_date').val('');
                    }
                    $('#word_limit').val(data.result.answer_word_count);
                    $('#duration').val(data.result.duration);
                    $('#passing_percentage').val(data.result.passing_percentage);
                    $('#exam_to').data("DateTimePicker").date(date_exam_to);
                    $('#exam_from').data("DateTimePicker").date(date_exam_from);
                    $('#exam').val(data.result.exam);
                    $('#attempt').val(data.result.attempt);
                    if (CKEDITOR.instances['description']) {
                        CKEDITOR.instances['description'].setData(data.result.description);
                    }

                    var is_quiz = (data.result.is_quiz == 0) ? false : true;
                    $('input[name=is_quiz]').prop('checked', is_quiz);

                    if(is_quiz){
                        $("input.publish_result").attr("disabled", true);
                        document.getElementById('auto_publish_date').disabled = true;
                    } else {
                        $("input.publish_result").removeAttr("disabled");
                        document.getElementById('auto_publish_date').disabled = false;
                    }

                    var chk_status = (data.result.is_active == 0) ? false : true;
                    $('input[name=is_active]').prop('checked', chk_status);

                    var chk_is_marks_display = (data.result.is_marks_display == 0) ? false : true;
                    $('input[name=is_marks_display]').prop('checked', chk_is_marks_display);

                    var chk_is_neg_marking = (data.result.is_neg_marking == 0) ? false : true;
                    $('input[name=is_neg_marking]').prop('checked', chk_is_neg_marking);

                    var chk_result_status = (data.result.publish_result == 0) ? false : true;
                    $('input[name=publish_result]').prop('checked', chk_result_status);

                    var chk_is_random_question = (data.result.is_random_question == 0) ? false : true;
                    $('input[name=is_random_question]').prop('checked', chk_is_random_question);
                }
                $this.button('reset');
            },
            error: function (xhr) {
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    });
});

$(document).on('submit',"form#saverank",function(e){
    e.preventDefault();
    var form = $(this);
    var url = form.attr('action');
    var submit_button = form.find(':submit');
    var post_params = form.serialize();

    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        dataType: "JSON",
        beforeSend: function () {
            submit_button.button('loading');
        },
        success: function (data) {
            successMsg(data.message);
            var examid=$('#generate_exam_id').val();
            var examtitle=$('#examRankTitle').text();
            getRankRecord(examid,examtitle);
        },
        error: function (xhr) {
            submit_button.button('reset');
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
        },
        complete: function () {
            submit_button.button('reset');
        }
    });
});

$("form#formsubject").submit(function (e) {
    e.preventDefault();
    var form = $(this);
    var url = form.attr('action');
    var submit_button = form.find(':submit');
    var post_params = form.serialize();
    for (var instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }

    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        dataType: "JSON",
        beforeSend: function () {
            $("[class$='_error']").html("");
            submit_button.button('loading');
        },
        success: function (data) {
            if (!data.status) {
                var message = "";
                $.each(data.error, function (index, value) {
                    message += value;
                });
                errorMsg(message);
            } else {
                successMsg(data.message);
                closeExamDrawer();
                $('.exam-list').DataTable().ajax.reload(null, false);
                if ($.fn.DataTable.isDataTable('.closed-exam-list')) {
                    $('.closed-exam-list').DataTable().ajax.reload(null, false);
                }
            }
        },
        error: function (xhr) {
            submit_button.button('reset');
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
        },
        complete: function () {
            submit_button.button('reset');
        }
    });
});

function getQuestionByExam(page, exam_id,is_quiz) {
    var search = $("#search_box").val();
    var keyword = $('#form_search #keyword').val();
    var question_type = $('#form_search #question_type').val();
    var question_level = $('#form_search #question_level').val();
    var class_id = $('#form_search #class_id').val();
    var section_id = $('#form_search #section_id').val();
    $.ajax({
        type: "POST",
        url: base_url + 'admin/onlineexam/searchQuestionByExamID',
        data: {'page': page, 'exam_id': exam_id, 'search': search,'keyword':keyword,'question_type':question_type,'question_level': question_level,'class_id':class_id,'section_id':section_id,'is_quiz':is_quiz},
        dataType: "JSON",
        beforeSend: function () {
        },
        success: function (data) {
            $('.search_box_result').html(data.content);
            $('.search_box_pagination').html(data.navigation);
            $('.row_from').html(data.show_from);
            $('.row_to').html(data.show_to);
            $('.row_count').html(data.total_display);
            if(data.show_to==0){
                $('.search_box_result').html('<div class="alert alert-danger" style="border-radius: 8px;"><?php echo $this->lang->line("no_record_found"); ?></div>');
            }
        },
        error: function (xhr) {
           alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
        },
        complete: function () {
        }
    });
}

/* Pagination Clicks */
$(document).on('click', '.search_box_pagination li.activee', function (e) {
    var _exam_id = $('#modal_exam_id').val();
    var _is_quiz = $('#modal_is_quiz').val();
    var page = $(this).attr('p');
    getQuestionByExam(page, _exam_id,_is_quiz);
});

$(document).on('click', '.post_search_submit', function (e) {
    var _exam_id = $('#modal_exam_id').val();
    var __is_quiz = $('#modal_is_quiz').val();
    getQuestionByExam(1, _exam_id,__is_quiz);
});

$(document).on('change', '.question_chk', function () {
    var _exam_id = $('#modal_exam_id').val();
    var ques_mark =$(this).closest('div.section-box').find("input[name='question_marks']").val();
    var ques_neg_mark =$(this).closest('div.section-box').find("input[name='question_neg_marks']").val();
    updateCheckbox($(this).val(), _exam_id,ques_mark,ques_neg_mark);
});

function updateCheckbox(question_id, exam_id,ques_mark,ques_neg_mark) {
    $.ajax({
        type: 'POST',
        url: base_url + 'admin/onlineexam/questionAdd',
        dataType: 'JSON',
        data: {'question_id': question_id, 'onlineexam_id': exam_id,'ques_mark':ques_mark,'ques_neg_mark':ques_neg_mark},
        beforeSend: function () {
        },
        success: function (data) {
            if (data.status) {
                successMsg(data.message);
            }
        },
        error: function (xhr) {
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
        },
        complete: function () {
        },
    });
}

$(document).on('change', '#class_id', function (e) {
    $('#section_id').html("");
    var class_id = $(this).val();
    getSectionByClass(class_id, section_id);
});

function getSectionByClass(class_id, section_id) {
    if (class_id != "") {
        $('#section_id').html("");
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "sections/getByClass",
            data: {'class_id': class_id},
            dataType: "json",
            beforeSend: function () {
                $('#section_id').addClass('dropdownloading');
            },
            success: function (data) {
                $.each(data, function (i, obj) {
                    var sel = "";
                    if (section_id == obj.section_id) {
                        sel = "selected";
                    }
                    div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                });
                $('#section_id').append(div_data);
            },
            complete: function () {
                $('#section_id').removeClass('dropdownloading');
            }
        });
    }
}

$(document).on('click', '.exam_ques_list', function () {
    var $this=$(this);
    var recordid = $(this).data('recordid');
    $('input[name=recordid]').val(recordid);
    $.ajax({
        type: 'POST',
        url: baseurl + "admin/onlineexam/getExamQuestions",
        data: {'recordid': recordid},
        dataType: 'JSON',
        beforeSend: function () {
            $this.button('loading');
        },
        success: function (data) {
            openExamQuestionListDrawer();
            $('#drawerExamQuestionListPanel #examQuesListHeader').html(data.exam.exam);
            $('#drawerExamQuestionListPanel .question_list_result').html(data.result);
            $this.button('reset');
        },
        error: function (xhr) {
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');
        },
        complete: function () {
            $this.button('reset');
        }
    });
});

$(document).on('click', '.subject_pills li', function () {
    var $this=$(this);
    $this.addClass('active').siblings().removeClass('active');
    var subject_pill_selected=($this.find('a').data('subjectId'));
    if(subject_pill_selected != 0){
        $("div[class*='subject_div_']").css("display","none");
        $('.subject_div_'+subject_pill_selected).css("display","block");
    }else{
        $("div[class*='subject_div_']").css("display","block");
    }
});

function getRankRecord(examid,examtitle){
    $.ajax({
        type: "POST",
        url: base_url+"/admin/onlineexam/rankgenerate",
        data: {"examid":examid},
        dataType: "JSON",
        beforeSend: function () {
            $('#drawerRankPanel').addClass('modal_loading');
        },
        success: function (data) {
            $('#drawerRankPanel .drawerRankBody').html(data.page);
            $('#drawerRankPanel').removeClass('modal_loading');
        },
        error: function (xhr) {
            $('#drawerRankPanel').removeClass('modal_loading');
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
        },
        complete: function () {
            $('#drawerRankPanel').removeClass('modal_loading');
        }
    });
}
</script>

<script type="text/javascript">
    $(".is_quiz").change(function() {
        if(this.checked) {
            $("input.publish_result").attr("disabled", true);
            $("input#auto_publish_date").val("").attr("disabled", true);
        }else{
            $("input.publish_result").removeAttr("disabled");
            $("input#auto_publish_date").removeAttr("disabled");
        }
    });
</script>

<script>
    $(document).ready(function () {
        initDatatable('exam-list','admin/onlineexam/getexamlist'); // for upcoming exam datatable will be loaded by default

        $("a[href='#tab_3']").on('shown.bs.tab', function (e) {
            initDatatable('closed-exam-list','admin/onlineexam/getclosedexamlist'); // for closed exam
        });
    });
</script>

<script type="text/javascript">
    $("#deletebulk").submit(function (e) {
        e.preventDefault();
        var checkCount = $("input[name='exam[]']:checked").length;

        if (checkCount == 0) {
            alert("<?php echo $this->lang->line('atleast_one_student_should_be_select'); ?>");
        } else {
            if (confirm("<?php echo $this->lang->line('are_you_sure_you_want_to_delete'); ?>")) {
                var form = $(this);
                var url = form.attr('action');
                var submit_button = form.find(':submit');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    dataType: "JSON",
                    beforeSend: function () {
                        submit_button.button('loading');
                    },
                    success: function (data) {
                        var message = "";
                        if (!data.status) {
                            $.each(data.error, function (index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            location.reload();
                        }
                    },
                    error: function (xhr) {
                        submit_button.button('reset');
                        alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    },
                    complete: function () {
                        submit_button.button('reset');
                    }
                });
            }
        }
    });

    $("input[name='checkAll']").click(function () {
        $("input[name='exam[]']").not(this).prop('checked', this.checked);
    });

    function Popup(data, winload = false) {
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write('<html>');
        newWin.document.write('<head>');
        newWin.document.write('<title></title>');
        newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/bootstrap/css/bootstrap.min.css">');
        newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/font-awesome.min.css">');
        newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/sh-print.css">');
        newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/ionicons.min.css">');
        newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/AdminLTE.min.css">');
        newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/skins/_all-skins.min.css">');
        newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/plugins/iCheck/flat/blue.css">');
        newWin.document.write('</head>');
        newWin.document.write('<body onload="window.print()">');
        newWin.document.write(data);
        newWin.document.write('</body>');
        newWin.document.write('</html>');
        newWin.document.close();

        newWin.onload = function () {
            setTimeout(function () {
                newWin.focus();
                newWin.print();
                newWin.close();
                if (winload) {
                    window.location.reload(true);
                }
            }, 500);
        };
        return true;
    }
</script>