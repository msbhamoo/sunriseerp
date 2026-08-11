<?php
if (!empty($result)) {
    $is_all_term = ($templatedata['marksheet_type'] == "all_term");
?>

<div class="table-responsive">
    <table class="table table-strippedn table-hover mb10 linkexam-merged">
        <thead>
            <tr class="active">
                <th width="34%"><?php echo $this->lang->line('term_exam'); ?></th>
                <th width="10%" class="text-center"><?php echo $this->lang->line('include'); ?></th>
                <th width="16%" class="text-center"><?php echo $this->lang->line('weightage'); ?></th>
                <th width="13%" class="text-center"><?php echo $this->lang->line('print_grade'); ?></th>
                <th width="13%" class="text-center"><?php echo $this->lang->line('print_remark'); ?></th>
                <th width="13%" class="text-center"><?php echo $this->lang->line('print_note'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $key => $value) {
                $term_checked = ($is_all_term && isset($templatedata['term_exam']) && array_key_exists($key, $templatedata['term_exam']));
                $term_weightage = ($is_all_term && isset($templatedata['term_details'][$key]['weightage'])) ? $templatedata['term_details'][$key]['weightage'] : '';
            ?>
            <tr class="linkexam-term-row">
                <th>
                    <input type="checkbox" class="checkbox checkBoxExam termcheckbox" data-id="<?php echo $key; ?>" name="terms[]" value="<?php echo $key; ?>" <?php if ($term_checked) { echo "checked"; } ?>>
                    <b><?php echo $value['name']; ?></b>
                </th>
                <td></td>
                <td class="text-center">
                    <input type="text" class="form-control term_weightage_input" name="term_weightage[<?php echo $key; ?>]" value="<?php echo $term_weightage; ?>" placeholder="0" style="width:70px; margin:0 auto; text-align:center; display:inline-block;">
                    <span class="text-muted">%</span>
                </td>
                <td colspan="3" class="text-center text-muted" style="font-size:12px;"><?php echo $this->lang->line('select_an_exam_below'); ?> &darr;</td>
            </tr>
            <?php foreach ($value['exam'] as $examkey => $examvalue) {
                $exam_checked = ($is_all_term && isset($templatedata['term_exam'][$key]) && array_key_exists($examvalue['id'], $templatedata['term_exam'][$key]));
                $grade_checked  = ($is_all_term && $examvalue['id'] == $templatedata['gradeexam_id']);
                $remark_checked = ($is_all_term && $examvalue['id'] == $templatedata['remarkexam_id']);
                $note_checked   = ($is_all_term && $examvalue['id'] == $templatedata['subjectnoteexam_id']);
            ?>
            <tr class="linkexam-exam-row">
                <td style="padding-left:34px;"><?php echo $examvalue['name']; ?></td>
                <td class="text-center">
                    <input type="checkbox" class="checkbox checkBoxExam examcheckbox_<?php echo $key; ?>" name="exam[<?php echo $key; ?>][<?php echo $examvalue['id']; ?>]" value="<?php echo $examvalue['id']; ?>" <?php if ($exam_checked) { echo "checked"; } ?>>
                </td>
                <td></td>
                <td class="text-center">
                    <input type="radio" class="checkbox checkBoxExam grading_<?php echo $key; ?>" name="grading" value="<?php echo $examvalue['id']; ?>" <?php if ($grade_checked) { echo "checked"; } ?>>
                </td>
                <td class="text-center">
                    <input type="radio" class="checkbox checkBoxExam remark_<?php echo $key; ?>" name="teacher_remark" value="<?php echo $examvalue['id']; ?>" <?php if ($remark_checked) { echo "checked"; } ?>>
                </td>
                <td class="text-center">
                    <input type="radio" class="checkbox checkBoxExam subject_note_<?php echo $key; ?>" name="subject_note" value="<?php echo $examvalue['id']; ?>" <?php if ($note_checked) { echo "checked"; } ?>>
                </td>
            </tr>
            <?php } ?>
            <?php } ?>
        </tbody>
    </table>

    <div class="clearfix" style="margin:6px 2px 4px;">
        <button type="button" class="btn btn-default btn-sm pull-left" id="split_evenly_btn"><i class="fa fa-columns"></i> <?php echo $this->lang->line('split_evenly'); ?></button>
        <div class="pull-right" style="line-height:30px;">
            <span class="text-muted"><?php echo $this->lang->line('total_weightage'); ?>:</span>
            <span id="weightage_total_badge" class="label label-default" style="font-size:13px; padding:5px 10px;">0 / 100%</span>
        </div>
    </div>
    <div class="clearfix"></div>
    <p class="text-muted" style="font-size:12px; margin-top:6px;"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('print_selectors_help'); ?></p>
</div>

<?php } else { ?>
<div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
<?php } ?>

<script type="text/javascript">
    (function () {
        // Selecting a term auto-selects/clears its exams (unchanged behaviour)
        $('.termcheckbox').off('change.linkexam').on('change.linkexam', function () {
            var termcheckbox = $(this).attr('data-id');
            if (this.checked) {
                $(".examcheckbox_" + termcheckbox).prop('checked', true);
            } else {
                $(".examcheckbox_" + termcheckbox).prop('checked', false);
                $(".grading_" + termcheckbox).prop('checked', false);
                $(".remark_" + termcheckbox).prop('checked', false);
                $(".subject_note_" + termcheckbox).prop('checked', false);
            }
        });

        function recalcWeightageTotal() {
            var total = 0;
            $('.term_weightage_input').each(function () {
                var v = parseFloat($(this).val());
                if (!isNaN(v)) { total += v; }
            });
            total = Math.round(total * 100) / 100;
            var $badge = $('#weightage_total_badge');
            var $save = $('#formlink button[type=submit]');
            $badge.text(total + ' / 100%');
            $badge.removeClass('label-default label-success label-danger');
            if (total === 100) {
                $badge.addClass('label-success');
                $save.prop('disabled', false);
            } else {
                $badge.addClass('label-danger');
                $save.prop('disabled', true);
            }
        }

        $(document).off('input.linkexam', '.term_weightage_input')
            .on('input.linkexam', '.term_weightage_input', recalcWeightageTotal);

        // Split 100 evenly across the terms whose checkbox is ticked
        $('#split_evenly_btn').off('click.linkexam').on('click.linkexam', function () {
            var $checkedTerms = $('.termcheckbox:checked');
            var count = $checkedTerms.length;
            if (count === 0) { return; }
            var base = Math.floor((100 / count) * 100) / 100;
            var assigned = 0;
            $checkedTerms.each(function (i) {
                var id = $(this).attr('data-id');
                var val = (i === count - 1) ? Math.round((100 - assigned) * 100) / 100 : base;
                assigned += base;
                $("input[name='term_weightage[" + id + "]']").val(val);
            });
            recalcWeightageTotal();
        });

        recalcWeightageTotal();
    })();
</script>
