<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calculator"></i> <?php echo $this->lang->line('accounts'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('outstanding'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('accounts/reports/outstanding') ?>" method="post">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('type'); ?></label><small class="req"> *</small>
                                        <select name="type" class="form-control" required>
                                            <option value="payable" <?php echo set_select('type', 'payable', (isset($type) && $type == 'payable')); ?>>Payable (Creditors)</option>
                                            <option value="receivable" <?php echo set_select('type', 'receivable', (isset($type) && $type == 'receivable')); ?>>Receivable (Debtors)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm btn-block"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php if (isset($result)) { ?>
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('outstanding'); ?> (<?php echo ucfirst($type); ?>)</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover example-outstanding">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('ledger_name'); ?></th>
                                    <th><?php echo $this->lang->line('mobile'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('balance'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
                                $grand_total = 0;
                                
                                if (!empty($result)) {
                                    foreach ($result as $row) {
                                        $bal = $row['current_balance'];
                                        $grand_total += $bal;
                                        ?>
                                        <tr>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['mobile']; ?></td>
                                            <td class="text-right"><?php echo $currency_symbol . amountFormat(abs($bal)) . ' ' . ($bal >= 0 ? 'Dr' : 'Cr'); ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right"><?php echo $this->lang->line('total'); ?>:</th>
                                    <th class="text-right"><?php echo $currency_symbol . amountFormat(abs($grand_total)) . ' ' . ($grand_total >= 0 ? 'Dr' : 'Cr'); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.example-outstanding').DataTable({
            "bSort": false,
            "paging": false,
        });
    });
</script>

<style>
.acc-status-posted {
    background-color: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;
}
.acc-status-reversed {
    background-color: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;
}
.acc-status-draft {
    background-color: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;
}
</style>
<script>
$(document).ready(function() {
    setTimeout(function() {
        $('table th').each(function(index) {
            var text = $(this).text().toLowerCase();
            if (text.indexOf('amount') !== -1 || text.indexOf('debit') !== -1 || text.indexOf('credit') !== -1 || text.indexOf('balance') !== -1 || text.indexOf('total') !== -1) {
                $(this).css('text-align', 'right');
                $(this).closest('table').find('tr').each(function() {
                    var $td = $(this).children('td').eq(index);
                    if ($td.length && $td.text().match(/[0-9]/)) {
                        $td.css({
                            'text-align': 'right',
                            'font-family': '"Consolas", "Courier New", monospace',
                            'font-weight': '600'
                        });
                    }
                });
            }
        });

        $('table td').each(function() {
            var txt = $.trim($(this).text()).toLowerCase();
            if (txt === 'posted') {
                $(this).html('<span class="acc-status-posted">Posted</span>');
            } else if (txt === 'reversed') {
                $(this).html('<span class="acc-status-reversed">Reversed</span>');
            } else if (txt === 'draft') {
                $(this).html('<span class="acc-status-draft">Draft</span>');
            }
        });
    }, 1000); // 1s delay to let datatables render
    
    // Also attach to DataTables draw event if available
    $(document).on('draw.dt', function () {
        $('table td').each(function() {
            var txt = $.trim($(this).text()).toLowerCase();
            if (txt === 'posted') {
                $(this).html('<span class="acc-status-posted">Posted</span>');
            } else if (txt === 'reversed') {
                $(this).html('<span class="acc-status-reversed">Reversed</span>');
            } else if (txt === 'draft') {
                $(this).html('<span class="acc-status-draft">Draft</span>');
            }
        });
        
        $('table th').each(function(index) {
            var text = $(this).text().toLowerCase();
            if (text.indexOf('amount') !== -1 || text.indexOf('debit') !== -1 || text.indexOf('credit') !== -1 || text.indexOf('balance') !== -1 || text.indexOf('total') !== -1) {
                $(this).css('text-align', 'right');
                $(this).closest('table').find('tr').each(function() {
                    var $td = $(this).children('td').eq(index);
                    if ($td.length && $td.text().match(/[0-9]/)) {
                        $td.css({
                            'text-align': 'right',
                            'font-family': '"Consolas", "Courier New", monospace',
                            'font-weight': '600'
                        });
                    }
                });
            }
        });
    });
});
</script>