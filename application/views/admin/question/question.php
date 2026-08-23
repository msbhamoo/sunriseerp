<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/adapters/jquery.js"></script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.question-studio-wrapper {
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

/* Slide-in Right Side Drawer */
.modern-drawer-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    z-index: 1050;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.modern-drawer-overlay.is-active {
    opacity: 1;
    visibility: visible;
}
.modern-drawer-panel {
    position: fixed !important;
    top: 0 !important;
    right: -720px !important;
    width: 680px !important;
    max-width: 95vw !important;
    height: 100vh !important;
    max-height: 100vh !important;
    background: #ffffff !important;
    z-index: 99999 !important;
    box-shadow: -10px 0 25px -5px rgba(0, 0, 0, 0.15) !important;
    display: flex !important;
    flex-direction: column !important;
    overflow: hidden !important;
    transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
}
.modern-drawer-panel.is-open {
    right: 0 !important;
}
.modern-drawer-header {
    background: #ffffff;
    color: #0f172a;
    padding: 16px 20px;
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

.ques-option-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.ques-option-card.is-correct {
    background: #ecfdf5;
    border-color: #a7f3d0;
    color: #065f46;
    font-weight: 600;
}
</style>

<div class="content-wrapper question-studio-wrapper">
    <section class="content-header" style="padding-top: 15px;">
        <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <i class="fa fa-question-circle" style="color: #6366f1; margin-right: 8px;"></i> Question & Answer Bank Studio
                <small style="font-size: 13px; color: #64748b; margin-left: 6px;">CBSE & School Question Repository</small>
            </div>
            <div class="box-tools pull-right" style="display: flex; gap: 8px;">
                <?php if ($this->rbac->hasPrivilege('question_bank', 'can_add')) {?>
                    <button type="button" class="btn btn-primary btn-sm" onclick="openAiQuestionModal()" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 6px 14px; border-radius: 6px; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.35);">
                        <i class="fa fa-bolt"></i> AI Question Generator
                    </button>
                    <button type="button" class="btn btn-default btn-sm question-btn" data-recordid="0" style="border: 1px solid #cbd5e1; font-weight: 600; padding: 6px 14px; border-radius: 6px; background: #ffffff; color: #334155;">
                        <i class="fa fa-plus text-primary"></i> <?php echo $this->lang->line('add_question'); ?>
                    </button>
                <?php }?>
                <?php if ($this->rbac->hasPrivilege('import_question', 'can_view')) {?>
                    <button type="button" class="btn btn-default btn-sm import-question" data-toggle="modal" data-target="#myQuesImportModal" style="border: 1px solid #cbd5e1; font-weight: 600; padding: 6px 12px; border-radius: 6px; background: #ffffff;">
                        <i class="fa fa-upload text-muted"></i> <?php echo $this->lang->line('import'); ?>
                    </button>
                <?php }?>
                <?php if ($this->rbac->hasPrivilege('question_bank', 'can_delete')) {?>
                    <button type="button" class="btn btn-default btn-sm text-danger deleteSelected" style="border: 1px solid #cbd5e1; font-weight: 600; padding: 6px 12px; border-radius: 6px; background: #ffffff;">
                        <i class="fa fa-trash"></i> <?php echo $this->lang->line('bulk_delete'); ?>
                    </button>
                <?php }?>
            </div>
        </h1>
    </section>

    <section class="content" style="padding-top: 10px;">
        <!-- Modern KPI Stat Grid -->
        <div class="modern-stat-grid">
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Total Questions</div>
                    <div class="stat-value" id="kpi_total_questions" style="color: #6366f1;">
                        <?php 
                        $total_q = $this->db->count_all('questions');
                        echo number_format($total_q); 
                        ?>
                    </div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                    <i class="fa fa-database"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Active Classes</div>
                    <div class="stat-value" style="color: #10b981;">
                        <?php echo count($classlist); ?>
                    </div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-graduation-cap"></i>
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
                    <div class="stat-label">AI Generation</div>
                    <div class="stat-value" style="color: #8b5cf6; font-size: 18px;">
                        <i class="fa fa-bolt text-warning"></i> Active
                    </div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
                    <i class="fa fa-sparkles"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- Search & Filters Box -->
                <div class="box box-primary" style="border-radius: 12px; border-top: 3px solid #6366f1; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 20px;">
                    <div class="box-header" style="padding: 14px 18px; border-bottom: 1px solid #f1f5f9;">
                        <h3 class="box-title" style="font-size: 14px; font-weight: 700; color: #1e293b;">
                            <i class="fa fa-filter text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('select_criteria'); ?>
                        </h3>
                    </div>
                    <div class="box-body" style="padding: 16px 18px;">
                        <form role="form" action="<?php echo site_url('admin/question/questionsearchvalidation') ?>" method="post" class="" id="questionsearchform">
                            <div class="row">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group" style="margin-bottom: 10px;">
                                        <label style="font-size: 12px; font-weight: 600; color: #475569;"><?php echo $this->lang->line('class'); ?></label>
                                        <select autofocus="" id="class_id" name="class" class="form-control" style="border-radius: 6px; font-size: 13px;">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($classlist as $class) {
    ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                <?php

}
?>
                                        </select>
                                         <span class="text-danger" id="error_class_id"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group" style="margin-bottom: 10px;">
                                        <label style="font-size: 12px; font-weight: 600; color: #475569;"><?php echo $this->lang->line('section'); ?></label>
                                        <select  id="search_section_id" name="section" class="form-control" style="border-radius: 6px; font-size: 13px;">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('subject'); ?></label>
                                        <select class="form-control" name="subject">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($subjectlist as $subject_key => $subject_value) {
    ?>
                                            <option value="<?php echo $subject_value['id']; ?>"><?php echo $subject_value['name']; ?> <?php if($subject_value['code']){ echo '('.$subject_value['code'].')'; } ?></option>
                                        <?php
}
?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('question_type'); ?></label>
                                        <select class="form-control" name="question_type">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($question_type as $question_type_key => $question_type_value) {
    ?>
                                                    <option value="<?php echo $question_type_key; ?>"><?php echo $question_type_value; ?></option>
                                            <?php
}
?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('question_level'); ?></label>
                                        <select class="form-control" name="question_level" id="question_level">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($question_level as $question_level_key => $question_level_value) {
    ?>
                                                    <option value="<?php echo $question_level_key; ?>"><?php echo $question_level_value; ?></option>
                                            <?php
}
?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('created_by'); ?></label>
                                        <select class="form-control" name="created_by" id="created_by">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($staff_list as $staff_list_key => $staff_list_value) {
    ?>
                                                    <option value="<?php echo $staff_list_value->id; ?>"><?php echo $staff_list_value->name.' '.$staff_list_value->surname.' ('.$staff_list_value->employee_id.')'; ?></option>
                                            <?php
}
?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div><!--./row-->
                        </form>
                    <div class="box-header" style="padding: 14px 18px; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                        <h3 class="box-title" style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">
                            <i class="fa fa-list text-primary" style="margin-right: 6px;"></i> <?php echo $this->lang->line('question_bank'); ?>
                        </h3>
                        <div class="box-tools pull-right" style="display: flex; gap: 8px;">
                            <button type="button" class="btn btn-primary btn-sm" onclick="openAiQuestionModal()" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 6px 14px; border-radius: 6px; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.35);">
                                <i class="fa fa-bolt"></i> AI Question Generator
                            </button>
                            <button type="button" class="btn btn-default btn-sm question-btn" data-recordid="0" style="border: 1px solid #cbd5e1; font-weight: 600; padding: 6px 14px; border-radius: 6px; background: #ffffff; color: #334155;">
                                <i class="fa fa-plus text-primary"></i> <?php echo $this->lang->line('add_question'); ?>
                            </button>
                            <button type="button" class="btn btn-default btn-sm import-question" data-toggle="modal" data-target="#myQuesImportModal" style="border: 1px solid #cbd5e1; font-weight: 600; padding: 6px 12px; border-radius: 6px; background: #ffffff;">
                                <i class="fa fa-upload text-muted"></i> <?php echo $this->lang->line('import'); ?>
                            </button>
                            <button type="button" class="btn btn-default btn-sm text-danger deleteSelected" style="border: 1px solid #cbd5e1; font-weight: 600; padding: 6px 12px; border-radius: 6px; background: #ffffff;">
                                <i class="fa fa-trash"></i> <?php echo $this->lang->line('bulk_delete'); ?>
                            </button>
                        </div>
                    </div>
                    <div class="box-body table-responsive" style="padding: 14px 18px;">
                         <div id="import_msg"></div>
                         <table class="table table-striped table-bordered table-hover all-list" data-export-title="<?php echo $this->lang->line('question_bank'); ?>">
                            <thead>
                                <tr>
                                     <th><?php if ($this->rbac->hasPrivilege('question_bank', 'can_delete')) {?><input type="checkbox" id="masterCheck" value="checkUncheckAll"><?php }?></th>
                                    <th><?php echo $this->lang->line('q_id'); ?></th>
                                    <th><?php echo $this->lang->line('class'); ?></th>
                                    <th><?php echo $this->lang->line('subject') ?></th>
                                    <th><?php echo $this->lang->line('question_type') ?></th>
                                    <th><?php echo $this->lang->line('level'); ?></th>
                                    <th><?php echo $this->lang->line('question') ?></th>
                                    <th><?php echo $this->lang->line('created_by') ?></th>
                                    <th class="pull-right noExport" width="15%"><?php echo $this->lang->line('action'); ?></th>
                                </tr>
                            </thead>
                        </table>
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
<!-- Modal -->
<!-- Slide-in Right Side Drawer: View Question Details -->
<div id="drawerQuestionViewOverlay" class="modern-drawer-overlay" onclick="closeQuestionViewDrawer()"></div>
<div id="drawerQuestionViewPanel" class="modern-drawer-panel">
    <div class="modern-drawer-header">
        <h4 class="modern-drawer-title">
            <i class="fa fa-eye" style="color: #6366f1;"></i> Question Inspection & Preview
        </h4>
        <button type="button" class="modern-drawer-close" onclick="closeQuestionViewDrawer()">&times;</button>
    </div>
    <div class="modern-drawer-body" id="drawerQuestionViewBody">
        <div style="text-align: center; padding: 40px; color: #64748b;">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <div style="margin-top: 10px;">Loading question details...</div>
        </div>
    </div>
    <div class="modern-drawer-footer">
        <button type="button" class="btn btn-default btn-sm" onclick="closeQuestionViewDrawer()">Close</button>
        <button type="button" class="btn btn-primary btn-sm" id="btnDrawerEditTrigger" onclick="" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700;">
            <i class="fa fa-pencil"></i> Edit Question
        </button>
    </div>
</div>

<!-- Slide-in Right Side Drawer: Add / Edit Question Editor -->
<div id="drawerQuestionEditOverlay" class="modern-drawer-overlay" onclick="closeQuestionEditDrawer()"></div>
<div id="drawerQuestionEditPanel" class="modern-drawer-panel">
    <div class="modern-drawer-header">
        <h4 class="modern-drawer-title" id="drawerQuestionEditTitle">
            <i class="fa fa-edit" style="color: #6366f1;"></i> Question Studio Editor
        </h4>
        <button type="button" class="modern-drawer-close" onclick="closeQuestionEditDrawer()">&times;</button>
    </div>
    <form action="<?php echo site_url('admin/question/add'); ?>" method="POST" id="formsubject" style="display: flex; flex-direction: column; flex: 1; overflow: hidden; margin-bottom: 0;">
        <div class="modern-drawer-body add_question_body" id="drawerQuestionEditBody">
            <div style="text-align: center; padding: 40px; color: #64748b;">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
                <div style="margin-top: 10px;">Loading question form...</div>
            </div>
        </div>
        <div class="modern-drawer-footer">
            <button type="button" class="btn btn-default" onclick="closeQuestionEditDrawer()">Cancel</button>
            <button type="submit" class="btn btn-primary" id="load" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 8px 18px;" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving...">
                <i class="fa fa-save"></i> Save Question
            </button>
        </div>
    </form>
</div>

<div id="myModal" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body add_question_body_legacy"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myimgModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title imgModal-title"><?php echo $this->lang->line('images'); ?> </h4>
      </div>
      <div class="modal-body imgModal-body pupscroll">
          <div class="form-group">
            <input type="text" name="search_box" id="search_box" class="form-control" placeholder="<?php echo $this->lang->line('search') ?>..." />
          </div>
          <div class="div_load">
          <div class="loading-overlay">
            <div class="overlay-content"> <?php echo $this->lang->line('loading'); ?> </div>
        </div>
             <label class="total displaynone"></label>
<div class="row" id="media_div">

</div>
<div id="pagination">

</div>
          </div>
      </div>
<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?> </button>
                <button type="button" class="btn btn-primary add_media"><?php echo $this->lang->line('add'); ?></button>
            </div>
    </div>
  </div>
</div>

<div id="myQuesImportModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <a class="btn btn-primary pull-right btn-xs download_excel mt3" 
                data-toggle="tooltip" title='<?php echo $this->lang->line("download");?>' 
                href="<?php echo site_url('admin/question/exportformat'); ?>" target="_blank"><i class="fa fa-download"></i>
                </a>
                <h4 class="modal-title"> <?php echo $this->lang->line('import_question'); ?></h4>
            </div>
            <form action="<?php echo site_url('admin/question/uploadfile'); ?>" method="POST" id="formimportquestion">
                <div class="modal-body add_question_import_body">
                       <div class="form-group">
                            <label><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                            <select autofocus="" id="subject_id" name="subject_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
foreach ($subjectlist as $subject) {
    $sub_code = ($subject['code'] != "") ? " (" . $subject['code'] . ")" : "";
    ?>

                                    <option value="<?php echo $subject['id'] ?>" <?php
if (set_value('subject_id') == $subject['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $subject['name'] . $sub_code; ?></option>
                                            <?php
}
?>
                            </select>
                            <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                        </div>
                 <div class="form-group">
                            <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                            <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
foreach ($classlist as $class) {
    ?>
                                    <option value="<?php echo $class['id'] ?>" <?php
if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                            <?php
}
?>
                            </select>
                            <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                        </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                <select  id="section_id" name="section_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                            </div>
                <div class="form-group">
                <label for="exampleInputEmail1"> <?php echo $this->lang->line('attach_file'); ?></label><small class="req"> *</small>
                <input id="my-file-selector" name="file" placeholder="" type="file" class="filestyle form-control"  value="<?php echo set_value('file'); ?>" />
                <span class="text-danger"><?php echo form_error('file'); ?></span>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('saving'); ?>"><?php echo $this->lang->line('upload') ?></button>
                </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    (function ($) {
        'use strict';
        $(document).ready(function () {
            initDatatable('all-list', 'admin/question/getDatatable', [],[], 50,
            [
                {
                    targets: [-1], // last column
                    orderable: false,
                    className: 'dt-body-right dt-head-right'
                },
                {
                    targets: [1], // last column
                    orderable: false,
                    className: 'dt-body-left dt-head-left'
                },
                 {
                    targets: [0], // last column
                    orderable: false,
                }
            ]
            );
        });
    }(jQuery))
</script>

<script type="text/javascript">
function openQuestionViewDrawer(id) {
    $('#drawerQuestionViewOverlay').addClass('is-active');
    $('#drawerQuestionViewPanel').addClass('is-open');
    $('body').css('overflow', 'hidden');
    $('#btnDrawerEditTrigger').attr('onclick', `openQuestionEditDrawer(${id})`);

    const viewBody = $('#drawerQuestionViewBody');
    viewBody.html(`
        <div style="text-align: center; padding: 50px 20px; color: #64748b;">
            <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
            <div style="margin-top: 12px; font-weight: 600;">Fetching question details...</div>
        </div>
    `);

    $.ajax({
        type: 'POST',
        url: baseurl + "admin/question/getQuestionByID",
        data: { 'recordid': id },
        dataType: 'JSON',
        success: function (data) {
            if (data.status && data.result) {
                const q = data.result;
                let optHtml = '';
                
                if (q.question_type === 'singlechoice' || q.question_type === 'multichoice') {
                    const opts = ['a', 'b', 'c', 'd', 'e'];
                    opts.forEach(letter => {
                        const optKey = 'opt_' + letter;
                        if (q[optKey] && q[optKey].trim() !== '') {
                            const isCorrect = (q.correct === optKey || (q.correct && q.correct.indexOf(optKey) !== -1));
                            optHtml += `
                                <div class="ques-option-card ${isCorrect ? 'is-correct' : ''}">
                                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: ${isCorrect ? '#10b981' : '#e2e8f0'}; color: ${isCorrect ? '#ffffff' : '#334155'}; font-weight: 700; font-size: 12px;">
                                        ${letter.toUpperCase()}
                                    </span>
                                    <div style="flex: 1;">${q[optKey]}</div>
                                    ${isCorrect ? '<span class="label label-success"><i class="fa fa-check"></i> Correct Answer</span>' : ''}
                                </div>
                            `;
                        }
                    });
                } else if (q.question_type === 'true_false') {
                    optHtml = `
                        <div class="ques-option-card ${q.correct === 'true' ? 'is-correct' : ''}">
                            <span style="font-weight: 700;">A. True</span>
                            ${q.correct === 'true' ? '<span class="label label-success pull-right"><i class="fa fa-check"></i> Correct</span>' : ''}
                        </div>
                        <div class="ques-option-card ${q.correct === 'false' ? 'is-correct' : ''}">
                            <span style="font-weight: 700;">B. False</span>
                            ${q.correct === 'false' ? '<span class="label label-success pull-right"><i class="fa fa-check"></i> Correct</span>' : ''}
                        </div>
                    `;
                }

                viewBody.html(`
                    <div style="margin-bottom: 18px;">
                        <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px;">
                            <span class="label label-primary" style="background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; font-size: 12px; padding: 4px 8px;">
                                <i class="fa fa-graduation-cap"></i> ${q.class_name || 'Class'} ${(q.section_name ? '(' + q.section_name + ')' : '')}
                            </span>
                            <span class="label label-info" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 12px; padding: 4px 8px;">
                                <i class="fa fa-book"></i> ${q.name || 'Subject'} ${(q.code ? '(' + q.code + ')' : '')}
                            </span>
                            <span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-size: 11px;">
                                ${q.question_type || 'singlechoice'}
                            </span>
                            <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 11px; text-transform: uppercase;">
                                Level: ${q.level || 'medium'}
                            </span>
                        </div>

                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; margin-bottom: 18px;">
                            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">
                                Question Text:
                            </label>
                            <div style="font-size: 15px; color: #0f172a; line-height: 1.6; font-weight: 500;">
                                ${q.question}
                            </div>
                        </div>

                        ${optHtml ? `
                            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: block;">
                                Options & Answer Key:
                            </label>
                            <div style="margin-bottom: 18px;">${optHtml}</div>
                        ` : ''}

                        ${q.explanation ? `
                            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px 16px; margin-top: 14px;">
                                <strong style="color: #166534; font-size: 12px; display: block; margin-bottom: 4px;">
                                    <i class="fa fa-lightbulb-o"></i> Solution / Marking Rubric:
                                </strong>
                                <div style="font-size: 13px; color: #14532d;">${q.explanation}</div>
                            </div>
                        ` : ''}
                    </div>
                `);

                // Auto render MathJax/KaTeX math equations if present
                if (window.renderMathInElement) {
                    renderMathInElement(document.getElementById('drawerQuestionViewBody'), {
                        delimiters: [
                            {left: '$$', right: '$$', display: true},
                            {left: '$', right: '$', display: false}
                        ]
                    });
                }
            } else {
                viewBody.html('<div class="alert alert-danger">Unable to load question details.</div>');
            }
        },
        error: function() {
            viewBody.html('<div class="alert alert-danger">Error communicating with server.</div>');
        }
    });
}

function closeQuestionViewDrawer() {
    $('#drawerQuestionViewPanel').removeClass('is-open');
    $('#drawerQuestionViewOverlay').removeClass('is-active');
    $('body').css('overflow', '');
}

function openQuestionEditDrawer(recordid) {
    closeQuestionViewDrawer();

    $('#drawerQuestionEditOverlay').addClass('is-active');
    $('#drawerQuestionEditPanel').addClass('is-open');
    $('body').css('overflow', 'hidden');

    const editTitle = (recordid && recordid > 0) ? `Edit Question #${recordid}` : 'Create New Question';
    $('#drawerQuestionEditTitle').html(`<i class="fa fa-edit" style="color: #6366f1;"></i> ${editTitle}`);

    const editBody = $('#drawerQuestionEditBody');
    editBody.html(`
        <div style="text-align: center; padding: 50px 20px; color: #64748b;">
            <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
            <div style="margin-top: 12px; font-weight: 600;">Loading editor canvas...</div>
        </div>
    `);

    const apiUrl = (recordid && recordid > 0) ? (baseurl + "admin/question/editform") : (baseurl + "admin/question/addform");

    $.ajax({
        type: 'POST',
        url: apiUrl,
        data: { 'recordid': recordid },
        dataType: 'JSON',
        success: function (data) {
            if (data.status) {
                editBody.html(data.page);
                const elem = editBody.find('.ckeditor');
                $(elem).each(function(_, ckeditor) {
                    CKEDITOR.env.isCompatible = true;
                    CKEDITOR.replace(ckeditor, {
                        toolbar: 'Ques',
                        allowedContent: true,
                        extraPlugins: 'ckeditor_wiris',
                        enterMode: CKEDITOR.ENTER_BR,
                        shiftEnterMode: CKEDITOR.ENTER_P,
                        customConfig: baseurl + '/backend/js/ckeditor_config.js'
                    });
                });
            } else {
                editBody.html('<div class="alert alert-danger">Failed to load question editor form.</div>');
            }
        },
        error: function() {
            editBody.html('<div class="alert alert-danger">Error communicating with server.</div>');
        }
    });
}

function closeQuestionEditDrawer() {
    // Destroy CKEditor instances to avoid memory leaks
    for (name in CKEDITOR.instances) {
        try { CKEDITOR.instances[name].destroy(true); } catch(e) {}
    }
    $('#drawerQuestionEditPanel').removeClass('is-open');
    $('#drawerQuestionEditOverlay').removeClass('is-active');
    $('body').css('overflow', '');
}

$(document).ready(function () {
    $(document).on('click', '.question-btn', function () {
        const recordid = $(this).data('recordid') || 0;
        openQuestionEditDrawer(recordid);
    });

    $(document).on('click', '.question-btn-edit', function () {
        const recordid = $(this).data('recordid');
        openQuestionEditDrawer(recordid);
    });
});

    $("form#formimportquestion").submit(function (e) {
     //stop submit the form, we will post it manually.
            event.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var submit_button = form.find(':submit');
            var form_record = $('#formimportquestion')[0];
            var form_data = new FormData(form_record);
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                dataType: 'JSON',
                data: form_data,
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function () {

               },
                success: function (data) {

             if (data.status == "0") {
             var message = "";
             $.each(data.error, function (index, value) {
              message += value;
            });
            errorMsg(message);
             } else {
            $('#formimportquestion')[0].reset();

             $('#import_msg').html('<div class="alert alert-success text-center">'+data.message+'</div>');
             $('#myQuesImportModal').modal('hide');
            }
                },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function () {

            }
            });
    });

   $("form#formsubject").submit(function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var url = form.attr('action');
        var submit_button = form.find(':submit');
        var post_params = form.serialize();
        for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {
                $("[class$='_error']").html("");
                submit_button.button('loading');
            },
            success: function (data)
            {

            if (!data.status) {
            var message = "";
            $.each(data.error, function (index, value) {
            message += value;

            });
         errorMsg(message);
                } else {
                    location.reload();
                }
            },
            error: function (xhr) { // if error occured
                submit_button.button('reset');
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function () {
                submit_button.button('reset');
            }
        });
    });
</script>

<script>
$(document).ready(function(){
    var target_textbox="";
    $(document).on('click','#question,#opt_a,#opt_b,#opt_c,#opt_d,#opt_e',function(){
     getImages(1);
    });
});

function getImages(page,query=""){
         $.ajax({
            type: "POST",
            url: baseurl+'admin/question/getimages',
           data:{page:page, query:query},
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {
$('.loading-overlay').css("display", "block");
            },
            success: function (data)
            {

             $('label.total').html("").html("<?php echo $this->lang->line('total_record'); ?>: "+data.count).css("display", "block");

            $('.imgModal-body #media_div').html("").html(data.page);
            $('.imgModal-body #pagination').html("").html(data.pagination);
$('.loading-overlay').css("display", "none");
            },
            error: function (xhr) { // if error occured

                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
$('.loading-overlay').css("display", "none");
            },
            complete: function () {
$('.loading-overlay').css("display", "none");
            }
        });
}

        $(document).on('click', '.img_div_modal', function (event) {
            $('.img_div_modal div.fadeoverlay').removeClass('active');
            $(this).closest('.img_div_modal').find('.fadeoverlay').addClass('active');
        });

 $(document).on('click', '.add_media', function (event) {
            var content_html = $('div#media_div').find('.fadeoverlay.active').find('img').data('img');
            var is_image = $('div#media_div').find('.fadeoverlay.active').find('img').data('is_image');
            var content_name = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_name');
            var content_type = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_type');
            var vid_url = $('div#media_div').find('.fadeoverlay.active').find('img').data('vid_url');
            var content = "";
                if (typeof content_html !== "undefined") {
                    if (is_image === 1) {
                        content = '<img src="' + content_html + '">';
                    }
                    InsertHTML(content);
                    $('#myimgModal').modal('hide');
                }
        });

    function InsertHTML(content_html) {
        var aaa=target_textbox+"_textbox";
        // Get the editor instance that we want to interact with.
        var editor = CKEDITOR.instances[aaa];
        console.log(editor);
        // Check the active editing mode.
        if (editor.mode == 'wysiwyg')
        {
            editor.insertHtml(content_html);
        } else
            alert("<?php echo $this->lang->line('you_must_be_in_wysiwyg_mode'); ?>");
    }

$('#myimgModal').on('shown.bs.modal', function (event) {
      button = $(event.relatedTarget);
      target_textbox = button.data('location');
      console.log(target_textbox);
})

 $('.modal').on("hidden.bs.modal", function (e) { //fire on closing modal box

        if ($('.modal:visible').length) { // check whether parent modal is opend after child modal close
            $('body').addClass('modal-open'); // if open mean length is 1 then add a bootstrap css class to body of the page
        }
    });

 function CKupdate(){
    for ( instance in CKEDITOR.instances ){
    CKEDITOR.instances[instance].setData('');
    }
}

 $(document).on('keyup', '#search_box', function (event) {
         var query = $('#search_box').val();
         getImages(1, query);
        });

  $(document).on('click', '.page-link', function(){
      var page = $(this).data('page_number');
      var query = $('#search_box').val();
      getImages(page, query);
    });

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        $('#search_section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, section_id);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_id').html("");
            $('#search_section_id').html("");
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
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                    $('#search_section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('change','#question_type',function(){
      if($(this).val() == "singlechoice"){
        $('.ans').show();
        $('.ans_true_false').hide();
        $('.ans_checkbox').hide();
        $('.option_list').show();

      }else if($(this).val() == "true_false"){
        $('.ans').hide();
        $('.ans_true_false').show();
        $('.ans_checkbox').hide();
        $('.option_list').hide();

      }else if($(this).val() == "multichoice"){
        $('.ans_true_false').hide();
        $('.ans_checkbox').show();
        $('.option_list').show();
        $('.ans').hide();

      }else if($(this).val() == "descriptive"){
        $('.ans_true_false').hide();
        $('.ans_checkbox').hide();
        $('.option_list').hide();
        $('.ans').hide();

      }else{
        $('.ans_true_false').hide();
        $('.ans_checkbox').hide();
        $('.option_list').hide();
        $('.ans').hide();

      }
    });
</script>

<script type="text/javascript">
    $(document).on('click','#masterCheck',function(){
     if ($(this).prop("checked")) {
       $("input:checkbox[name^='question_']").prop("checked", true);
     } else {
       $("input:checkbox[name^='question_']").prop("checked", false);
     }
    });

     $(document).on('click', '.deleteSelected', function () {
            var array_delete = [];
             var $this = $(this);
            $.each($("input[name^='question_']:checked"), function () {
                var question_id = $(this).data('questionId');

                array_delete.push(question_id);
            });
            if (array_delete.length === 0) {
                alert("<?php echo $this->lang->line('no_record_selected'); ?>");
            } else {
                if(confirm("<?php echo $this->lang->line('delete_confirm') ?>")) {
                $.ajax({
                type: 'POST',
                url: baseurl + "admin/question/bulkdelete",
                data: {'recordid': array_delete},
                dataType: 'JSON',
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (data) {
                    if(data.status){
                        successMsg(data.message);
                     table.ajax.reload( null, false );
                    }
                    $this.button('reset');
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
                },
                complete: function () {
                    $this.button('reset');
                }
            });
  }
            }
        });
</script>

<!-- Slide-in Right Side Drawer: AI Instant Question Generator -->
<div id="drawerAiQuestionOverlay" class="modern-drawer-overlay" onclick="closeAiQuestionDrawer()"></div>
<div id="drawerAiQuestionPanel" class="modern-drawer-panel" style="width: 640px !important;">
    <div class="modern-drawer-header" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: #ffffff;">
        <h4 class="modern-drawer-title" style="color: #ffffff;">
            <i class="fa fa-bolt" style="color: #fbbf24;"></i> AI Question Generator Studio
        </h4>
        <button type="button" class="modern-drawer-close" style="color: #cbd5e1;" onclick="closeAiQuestionDrawer()">&times;</button>
    </div>
    <div class="modern-drawer-body" style="padding: 20px 24px;">
        <form id="aiQuestionGenForm">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 13px; color: #1e293b;">1. Class <small class="text-danger">*</small></label>
                        <select id="ai_gen_class" class="form-control" onchange="onAiClassChange()" required>
                            <option value="">-- Select Class --</option>
                            <?php if (!empty($classlist)) {
                                foreach ($classlist as $cls) { ?>
                                    <option value="<?php echo $cls['id']; ?>" data-name="<?php echo htmlspecialchars($cls['class']); ?>"><?php echo $cls['class']; ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 13px; color: #1e293b;">2. Subject <small class="text-danger">*</small></label>
                        <select id="ai_gen_subject" class="form-control" onchange="onAiSubjectChange()" required>
                            <option value="">-- Choose Class First --</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label style="font-weight: 700; font-size: 13px; margin: 0; color: #1e293b;">
                        3. Chapter / Syllabus Scope <small class="text-muted">(Multi-select or All)</small>
                    </label>
                    <span id="aiChapterLoadingStatus" style="font-size: 11px; color: #6366f1; display: none;"><i class="fa fa-spinner fa-spin"></i> Loading chapters...</span>
                </div>
                <div style="margin-bottom: 6px; display: flex; gap: 8px;">
                    <button type="button" class="btn btn-default btn-xs" onclick="toggleAllChapters(true)" style="border-radius: 4px; font-weight: 600;">Select All</button>
                    <button type="button" class="btn btn-default btn-xs" onclick="toggleAllChapters(false)" style="border-radius: 4px; font-weight: 600;">Clear All</button>
                </div>
                <div id="ai_chapter_checklist_container" style="max-height: 150px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; background: #f8fafc;">
                    <label style="font-weight: 500; font-size: 12px; display: block; margin-bottom: 4px; color: #64748b;">
                        <input type="checkbox" id="chk_all_chapters" value="Complete Syllabus" checked onchange="onAllChaptersCheckboxChange()"> <strong>All Chapters (Complete Subject Syllabus)</strong>
                    </label>
                    <div id="ai_chapter_dynamic_items"></div>
                </div>
                <input type="text" id="ai_gen_topic_custom" class="form-control" style="margin-top: 8px;" placeholder="Optional: Type custom topic or specific learning unit...">
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 13px; margin-bottom: 6px; display: block; color: #1e293b;">
                            4. Question Types <small class="text-muted">(Select Multiple)</small>
                        </label>
                        <div style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; background: #f8fafc;">
                            <label style="font-weight: 500; font-size: 12px; display: block; margin-bottom: 4px; color: #334155;">
                                <input type="checkbox" name="ai_gen_type[]" value="singlechoice" checked> Multiple Choice (Single - 1M)
                            </label>
                            <label style="font-weight: 500; font-size: 12px; display: block; margin-bottom: 4px; color: #334155;">
                                <input type="checkbox" name="ai_gen_type[]" value="multichoice"> Multiple Choice (Multiple Correct)
                            </label>
                            <label style="font-weight: 500; font-size: 12px; display: block; margin-bottom: 4px; color: #334155;">
                                <input type="checkbox" name="ai_gen_type[]" value="true_false"> True / False
                            </label>
                            <label style="font-weight: 500; font-size: 12px; display: block; margin-bottom: 0; color: #334155;">
                                <input type="checkbox" name="ai_gen_type[]" value="descriptive"> Descriptive / Subjective (2M - 5M)
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 13px; margin-bottom: 6px; display: block; color: #1e293b;">
                            5. Difficulty Levels <small class="text-muted">(Select Multiple)</small>
                        </label>
                        <div style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; background: #f8fafc;">
                            <label style="font-weight: 500; font-size: 12px; display: block; margin-bottom: 4px; color: #16a34a;">
                                <input type="checkbox" name="ai_gen_level[]" value="easy"> 🟢 Easy (Knowledge / Recall)
                            </label>
                            <label style="font-weight: 500; font-size: 12px; display: block; margin-bottom: 4px; color: #d97706;">
                                <input type="checkbox" name="ai_gen_level[]" value="medium" checked> 🟡 Medium (Standard Board Exam)
                            </label>
                            <label style="font-weight: 500; font-size: 12px; display: block; margin-bottom: 0; color: #dc2626;">
                                <input type="checkbox" name="ai_gen_level[]" value="hard"> 🔴 Hard (Application / HOTS)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">6. Number of Questions</label>
                        <input type="number" id="ai_gen_count" class="form-control" value="5" min="1" max="25" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">7. AI Model Engine</label>
                        <select id="ai_gen_engine" class="form-control">
                            <option value="openrouter_ox" selected>OpenRouter (stealth/ox-alpha Free 1M)</option>
                            <option value="gemini">Google Gemini 2.0 Flash</option>
                            <option value="groq">Groq Cloud (LLaMA-3.3 70B)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="aiGenStatusAlert" style="display: none; margin-top: 10px;" class="alert"></div>
        </form>
    </div>
    <div class="modern-drawer-footer">
        <button type="button" class="btn btn-default btn-sm" onclick="closeAiQuestionDrawer()">Cancel</button>
        <button type="button" id="btnRunAiQuestionGen" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 8px 18px;" onclick="runAiQuestionGeneration()">
            <i class="fa fa-bolt"></i> Generate & Add to Question Bank
        </button>
    </div>
</div>

<script type="text/javascript">
function openAiQuestionModal() {
    $('#aiGenStatusAlert').hide();
    $('#drawerAiQuestionOverlay').addClass('is-active');
    $('#drawerAiQuestionPanel').addClass('is-open');
    $('body').css('overflow', 'hidden');
}

function closeAiQuestionDrawer() {
    $('#drawerAiQuestionPanel').removeClass('is-open');
    $('#drawerAiQuestionOverlay').removeClass('is-active');
    $('body').css('overflow', '');
}

function onAiClassChange() {
    const classId = $('#ai_gen_class').val();
    const subSelect = $('#ai_gen_subject');
    
    subSelect.html('<option value="">-- Loading Mapped Subjects... --</option>').prop('disabled', true);

    if (!classId) {
        subSelect.html('<option value="">-- Choose Class First --</option>').prop('disabled', false);
        return;
    }

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/get_subjects_by_class_ajax',
        type: 'POST',
        dataType: 'json',
        data: { class_id: classId },
        success: function(res) {
            subSelect.prop('disabled', false).html('<option value="">-- Choose Subject --</option>');
            if (res.status === 'success' && res.subjects && res.subjects.length > 0) {
                res.subjects.forEach(function(sub) {
                    subSelect.append(`<option value="${sub.id}" data-name="${sub.name}">${sub.name}${sub.code ? ' (' + sub.code + ')' : ''}</option>`);
                });
            } else {
                subSelect.append('<option value="">No subjects mapped</option>');
            }
        },
        error: function() {
            subSelect.prop('disabled', false).html('<option value="">-- Choose Subject --</option>');
        }
    });
}

function onAiSubjectChange() {
    const className = $('#ai_gen_class option:selected').data('name') || '';
    const subjectName = $('#ai_gen_subject option:selected').data('name') || '';
    const dynContainer = $('#ai_chapter_dynamic_items');
    const loadingStatus = $('#aiChapterLoadingStatus');

    dynContainer.empty();
    $('#chk_all_chapters').prop('checked', true);

    if (!className || !subjectName) {
        return;
    }

    loadingStatus.show();

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/get_or_fetch_chapters_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_name: className,
            subject_name: subjectName,
            api_engine: $('#ai_gen_engine').val() || 'gemini',
            force_reload: 0
        },
        success: function(res) {
            loadingStatus.hide();
            if (res.status === 'success' && res.chapters && res.chapters.length > 0) {
                res.chapters.forEach(function(ch, idx) {
                    dynContainer.append(`
                        <label style="font-weight: 400; font-size: 12px; display: block; margin-bottom: 3px; color: #1e293b;">
                            <input type="checkbox" class="ai-chapter-item" value="${ch}" onchange="onSingleChapterChange()"> Chapter ${idx + 1}: ${ch}
                        </label>
                    `);
                });
            }
        },
        error: function() {
            loadingStatus.hide();
        }
    });
}

function toggleAllChapters(check) {
    $('#chk_all_chapters').prop('checked', check);
    $('.ai-chapter-item').prop('checked', check);
}

function onAllChaptersCheckboxChange() {
    const checked = $('#chk_all_chapters').is(':checked');
    $('.ai-chapter-item').prop('checked', checked);
}

function onSingleChapterChange() {
    const total = $('.ai-chapter-item').length;
    const checked = $('.ai-chapter-item:checked').length;
    $('#chk_all_chapters').prop('checked', total > 0 && total === checked);
}

function runAiQuestionGeneration() {
    const classId = $('#ai_gen_class').val();
    const className = $('#ai_gen_class option:selected').data('name');
    const subjectId = $('#ai_gen_subject').val();
    const subjectName = $('#ai_gen_subject option:selected').data('name');

    // Collect selected chapters
    let selectedChapters = [];
    if ($('#chk_all_chapters').is(':checked')) {
        selectedChapters.push('Complete Syllabus');
    } else {
        $('.ai-chapter-item:checked').each(function() {
            selectedChapters.push($(this).val());
        });
    }

    const customTopic = $('#ai_gen_topic_custom').val().trim();
    if (customTopic) {
        selectedChapters.push(customTopic);
    }
    if (selectedChapters.length === 0) {
        selectedChapters.push('Complete Syllabus');
    }

    // Collect selected question types
    let selectedTypes = [];
    $('input[name="ai_gen_type[]"]:checked').each(function() {
        selectedTypes.push($(this).val());
    });
    if (selectedTypes.length === 0) {
        selectedTypes.push('singlechoice');
    }

    // Collect selected difficulty levels
    let selectedLevels = [];
    $('input[name="ai_gen_level[]"]:checked').each(function() {
        selectedLevels.push($(this).val());
    });
    if (selectedLevels.length === 0) {
        selectedLevels.push('medium');
    }

    const count = $('#ai_gen_count').val();
    const engine = $('#ai_gen_engine').val();

    if (!classId || !subjectId) {
        alert('Please select both Class and Subject.');
        return;
    }

    const btn = $('#btnRunAiQuestionGen');
    const alertBox = $('#aiGenStatusAlert');

    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generating with AI...');
    alertBox.removeClass('alert-danger alert-success').addClass('alert-info').text('AI model is generating questions across your selected types, levels & chapters...').show();

    $.ajax({
        url: '<?php echo base_url(); ?>admin/question/ai_generate_questions_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_id: classId,
            class_name: className,
            subject_id: subjectId,
            subject_name: subjectName,
            topic: selectedChapters.join(', '),
            question_types: selectedTypes,
            levels: selectedLevels,
            count: count,
            api_engine: engine
        },
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Generate & Add to Question Bank');
            if (res.status === 'success') {
                alertBox.removeClass('alert-info alert-danger').addClass('alert-success').html(`<strong><i class="fa fa-check-circle"></i> Success:</strong> ${res.message || 'Questions added successfully!'}`);
                
                setTimeout(function() {
                    closeAiQuestionDrawer();
                    if ($('#questionsearchform').length) {
                        $('#questionsearchform').trigger('submit');
                    } else {
                        location.reload();
                    }
                }, 1200);
            } else {
                alertBox.removeClass('alert-info alert-success').addClass('alert-danger').text(res.message || 'Failed to generate questions.');
            }
        },
        error: function(xhr, status, err) {
            btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Generate & Add to Question Bank');
            alertBox.removeClass('alert-info alert-success').addClass('alert-danger').text('Server communication error. Please try again.');
        }
    });
}
</script>

<script type="text/javascript">
$(document).ready(function(){
$(document).on('submit','#questionsearchform',function(e){
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var $this = $(this).find("button[type=submit]:focus");
    var form = $(this);
    var url = form.attr('action');
    var form_data = form.serializeArray();
    form_data.push({name: 'search_type', value: $this.attr('value')});

    $.ajax({
           url: url,
           type: "POST",
           dataType:'JSON',
           data: form_data, // serializes the form's elements.
              beforeSend: function () {
                $('[id^=error]').html("");
                $this.button('loading');

               },
              success: function(response) { // your success handler
                if(!response.status){
                    $.each(response.error, function(key, value) {
                    $('#error_' + key).html(value);

                    });
                }else{

                    initDatatable('all-list', 'admin/question/getDatatable',response.params,[],50,
                            [{
                                    targets: [0],
                                    orderable: true,
                                    className: 'dt-body-left dt-head-left'
                                },
                                {
                                    targets: [-1],
                                    orderable: false,
                                    className: 'dt-right dt-body-right'
                                }
                            ]
                   );

                }
              },
             error: function() { // your error handler
                 $this.button('reset');
             },
             complete: function() {
             $this.button('reset');
             }
         });
        });
    });
</script>