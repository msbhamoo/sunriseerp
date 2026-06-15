<?php
if (!empty($feesessiongroup_model)) {
    ?>
    <div class="table-responsive border0">
        <table class="table mb0">
            <tbody>
                <?php
                foreach ($feesessiongroup_model as $feesessiongroup_key => $feesessiongroup_value) {
                    $total_fees = 0;

                    if (isset($feesessiongroup_value->student_fees_master_id) && $feesessiongroup_value->student_fees_master_id > 0) {
                        ?>
                        <input type="hidden" name="prev_fees_group[]" value="<?php echo $feesessiongroup_value->id ?>">
                        <?php
                    }

                    foreach ($feesessiongroup_value->feetypes as $fee_type_key => $fee_type_value) {
                        $total_fees += $fee_type_value->amount;
                    }
                    ?>
                    <tr>
                        <td colspan="3" class="mailbox-name white-space-nowrap border0 v-align-top">
                            <div class="panel-group1 mb0">
                                <div class="panel panel-default1">
                                    <div class="panel-heading pt5 pb5">
                                        <h6 class="panel-title panel-title1 overflow-hidden">
                                            <input class="fee_group_chk vertical-middle"
                                                type="checkbox"
                                                name="fee_session_group_id[]"
                                                value="<?php echo $feesessiongroup_value->id; ?>"
                                                <?php 
                                                // Support set_checkbox if needed, but fall back to checking if student_fees_master_id is present
                                                $is_checked = isset($feesessiongroup_value->student_fees_master_id) && $feesessiongroup_value->student_fees_master_id > 0;
                                                echo $is_checked ? 'checked="checked"' : ''; 
                                                ?>>
                                            <a class="display-inline collapsed box-plus-panel"
                                                data-toggle="collapse"
                                                href="#collapse_fees_<?php echo $feesessiongroup_value->id ?>">
                                                <span class="font14"><?php echo $feesessiongroup_value->group_name; ?></span>
                                            </a>
                                            <span class="float-right bmedium fee_group_total"
                                                data-amount="<?php echo ($total_fees); ?>"><?php echo amountFormat($total_fees); ?></span>
                                        </h6>
                                    </div>
                                    <div id="collapse_fees_<?php echo $feesessiongroup_value->id ?>"
                                        class="panel-collapse collapse">
                                        <ul class="list-group student_fee_list ui-sortable student-list-sm">
                                            <li class="list-group-item">
                                                <div class="displayinline stfirstdiv bmedium font14 pl-65">
                                                    <?php echo $this->lang->line('fees_type'); ?>
                                                </div>
                                                <div class="due_date bmedium font14">
                                                    <?php echo $this->lang->line('due_date'); ?>
                                                </div>
                                                <div class="tools bmedium font14">
                                                    <?php echo $this->lang->line('amount'); ?>
                                                    (<?php echo $currency_symbol; ?>)
                                                </div>
                                            </li>
                                            <?php
                                            foreach ($feesessiongroup_value->feetypes as $fee_type_key => $fee_type_value) {
                                                ?>
                                                <li class="list-group-item">
                                                    <div class="displayinline stfirstdiv pl-65">
                                                        <?php echo $fee_type_value->type . " (" . $fee_type_value->code . ")" ?>
                                                    </div>
                                                    <small class="due_date"><i class="fa fa-calendar"></i> <?php
                                                        echo $this->customlib->dateformat($fee_type_value->due_date);
                                                        ?></small>
                                                    <div class="tools">
                                                        <?php echo amountFormat($fee_type_value->amount); ?>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
} else {
    echo '<p class="text-info">No Fee Groups mapped to this class.</p>';
}
?>
