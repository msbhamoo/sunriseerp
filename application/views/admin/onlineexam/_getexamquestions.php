<script src="<?php echo base_url() ?>backend/plugins/ckeditor/plugins/ckeditor_wiris/integration/WIRISplugins.js?viewer=image"></script>

<style type="text/css">
.modern-pill-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e2e8f0;
}
.modern-pill-tabs li {
    list-style: none;
}
.modern-pill-tabs li a {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    background: #f1f5f9;
    color: #475569;
    font-weight: 600;
    font-size: 12.5px;
    text-decoration: none;
    transition: all 0.15s ease;
}
.modern-pill-tabs li.active a,
.modern-pill-tabs li a:hover {
    background: #6366f1;
    color: #ffffff;
}

.modern-exam-ques-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px 18px;
    margin-bottom: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    transition: all 0.2s ease;
}
.modern-exam-ques-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}
</style>

<?php
if (!empty($questions)) {
    ?>
    <ul class="modern-pill-tabs subject_pills">
        <li class="active"><a href="#" data-subject-id="0"><?php echo $this->lang->line('all'); ?></a></li>
        <?php foreach ($questionSubjects as $questionSubjects_key => $questionSubjects_value) { ?>
            <li><a href="#" data-subject-id="<?php echo $questionSubjects_value->subject_id; ?>"><?php echo $questionSubjects_value->subject_name; ?></a></li>
        <?php } ?>
    </ul>

    <?php foreach ($questions as $question_key => $question_value) { ?>
        <div class="question_row_<?php echo $question_value->onlineexam_question_id; ?> subject_div_<?php echo $question_value->subject_id; ?> modern-exam-ques-card">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <span class="badge bg-purple" style="background: #6366f1; font-weight: 700; font-size: 12px; padding: 4px 8px; border-radius: 4px;">
                            <?php echo $this->lang->line('q_id') ?>: <?php echo $question_value->id; ?>
                        </span>
                        <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 600; font-size: 11px;">
                            <i class="fa fa-book"></i> <?php echo $question_value->subject_name; ?><?php if($question_value->subject_code){ echo ' ('.$question_value->subject_code.')'; } ?>
                        </span>
                        <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 600; font-size: 11px;">
                            <?php echo ($question_value->question_type != "") ? $question_type[$question_value->question_type] : ""; ?>
                        </span>
                        <span class="badge" style="background: #fef3c7; color: #b45309; font-weight: 600; font-size: 11px;">
                            <?php echo ($question_value->level != "") ? $question_level[$question_value->level] : ""; ?>
                        </span>
                    </div>

                    <div style="font-size: 14px; color: #1e293b; line-height: 1.6; margin-bottom: 6px;">
                        <?php echo $question_value->question; ?>
                    </div>
                </div>

                <div>
                    <button type="button" class="btn btn-default btn-xs del_exam_question" style="border: 1px solid #fecaca; color: #dc2626; background: #fef2f2; border-radius: 6px; padding: 5px 10px;" data-original-title="<?php echo $this->lang->line('delete') ?>" data-toggle="modal" data-target="#mydeleteModal" data-exam-id="<?php echo $exam->exam; ?>" data-onlineexam-question-id="<?php echo $question_value->onlineexam_question_id; ?>">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php }
} else {
    ?>
    <div class="alert alert-info" style="border-radius: 8px;"><?php echo $this->lang->line('no_record_found'); ?></div>
<?php } ?>