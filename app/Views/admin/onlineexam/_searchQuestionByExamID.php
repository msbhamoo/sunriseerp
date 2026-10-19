<style type="text/css">
.inpwidth40 {
    width: 65px;
    height: 28px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    padding: 2px 8px;
    font-size: 13px;
    font-weight: 600;
}
.modern-ques-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.modern-ques-item:hover {
    border-color: #c7d2fe;
    box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.08);
}
.modern-ques-item.is-selected {
    border-color: #818cf8;
    background: #f8faff;
}
</style>

<?php
if (!empty($questionList)) {
    foreach ($questionList as $question_key => $question_value) {
        $checkbox_status = "";
        $is_selected_class = "";
        if ($question_value->onlineexam_question_id != 0) {
            $checkbox_status = "checked";
            $is_selected_class = "is-selected";
        }
        ?>
        <div class="modern-ques-item <?php echo $is_selected_class; ?> section-box">
            <div style="display: flex; align-items: flex-start; gap: 14px;">
                <?php if ($this->rbac->hasPrivilege('add_questions_in_exam', 'can_edit')) { ?>
                    <div style="padding-top: 4px;">
                        <input type="checkbox" class="question_chk" style="width: 18px; height: 18px; cursor: pointer;" value="<?php echo $question_value->id; ?>" <?php echo $checkbox_status; ?>>
                    </div>
                <?php } ?>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span class="badge bg-purple" style="background: #6366f1; font-weight: 700; font-size: 12px; padding: 4px 8px; border-radius: 4px;">
                            <?php echo $this->lang->line('q_id') ?>: <?php echo $question_value->id; ?>
                        </span>
                        <div style="display: flex; gap: 6px;">
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
                    </div>

                    <div style="font-size: 14px; color: #1e293b; line-height: 1.6; margin-bottom: 12px;">
                        <?php echo readmorelink($question_value->question, site_url('admin/question/read/' . $question_value->id)); ?>
                    </div>

                    <div style="display: flex; align-items: center; gap: 20px; background: #f8fafc; padding: 8px 14px; border-radius: 6px; border: 1px solid #f1f5f9;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <label style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 0;"><?php echo $this->lang->line('marks') ?>:</label>
                            <input type="text" name="question_marks" value="<?php echo $question_value->onlineexam_question_marks; ?>" placeholder="Marks" class="inpwidth40">
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <label style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 0;"><?php echo $this->lang->line('negative_marks') ?>:</label>
                            <input type="text" name="question_neg_marks" value="<?php echo $question_value->onlineexam_question_neg_marks; ?>" placeholder="Neg" class="inpwidth40">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>