<?php foreach ($yearlyfeelist as $yearlyfee) { ?>
    <tr>
        <td class="mailbox-name"><?php echo $yearlyfee['pickup_point_name']; ?></td>
        <td class="mailbox-name"><?php echo $yearlyfee['feetype_name']; ?></td>
        <td class="mailbox-name">
            <?php echo ($yearlyfee['due_date'] && $yearlyfee['due_date'] != '0000-00-00' && $yearlyfee['due_date'] != '1970-01-01') ? $this->customlib->dateformat($yearlyfee['due_date']) : ''; ?>
        </td>
        <td class="mailbox-name"><?php echo $currency_symbol . amountFormat($yearlyfee['amount']); ?></td>
        <td class="mailbox-date pull-right">
            <?php if ($this->rbac->hasPrivilege('transport_fees_master', 'can_delete')) { 
                $ids_str = implode('-', $yearlyfee['ids']);
            ?>
                <a href="<?php echo base_url(); ?>admin/transportyearlyfee/delete_bulk/<?php echo $ids_str; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                    <i class="fa fa-remove"></i>
                </a>
            <?php } ?>
        </td>
    </tr>
<?php } ?>
