<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calculator"></i> <?php echo $this->lang->line('accounts'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('day_book_report'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('accounts/reports/daybook') ?>" method="post">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date_from'); ?></label><small class="req"> *</small>
                                        <input type="text" name="date_from" class="form-control date" value="<?php echo set_value('date_from', isset($date_from) ? $date_from : date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly">
                                        <span class="text-danger"><?php echo form_error('date_from'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date_to'); ?></label><small class="req"> *</small>
                                        <input type="text" name="date_to" class="form-control date" value="<?php echo set_value('date_to', isset($date_to) ? $date_to : date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly">
                                        <span class="text-danger"><?php echo form_error('date_to'); ?></span>
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
                        <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('day_book_report'); ?></h3>
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="download_label"><?php echo $this->lang->line('day_book_report'); ?></div>
                        <style>
                            .report-table { border-collapse: separate; border-spacing: 0; width: 100%; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 6px; overflow: hidden; border: 1px solid #e2e8f0; }
                            .report-table th, .report-table td { padding: 10px 12px; font-size: 13px; vertical-align: middle; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; }
                            .report-table th:last-child, .report-table td:last-child { border-right: none; }
                            .report-table tbody tr:last-child td { border-bottom: none; }
                            .report-table thead th { background-color: #f1f5f9; text-align: left; font-weight: 600; color: #334155; }
                            .report-table tbody tr:hover { background-color: #f8fafc; }
                            .download_label { font-size: 18px; font-weight: 600; margin-bottom: 15px; color: #334155; }
                        </style>
                        <table class="table table-striped table-bordered table-hover report-table">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('date'); ?></th>
                                    <th><?php echo $this->lang->line('vch_no'); ?></th>
                                    <th><?php echo $this->lang->line('vch_type'); ?></th>
                                    <th><?php echo $this->lang->line('particulars'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('debit'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('credit'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
                                $total_dr = 0;
                                $total_cr = 0;
                                
                                if (!empty($result) || isset($opening_balances)) {
                                    $cash_opening = isset($opening_balances) ? $opening_balances['cash'] : 0;
                                    $bank_opening = isset($opening_balances) ? $opening_balances['bank'] : 0;
                                    
                                    if (isset($cash_opening)) {
                                        $dr_val = $cash_opening >= 0 ? $cash_opening : 0;
                                        $cr_val = $cash_opening < 0 ? abs($cash_opening) : 0;
                                        $total_dr += $dr_val;
                                        $total_cr += $cr_val;
                                        ?>
                                        <tr style="background-color: #f0fdf4;">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Cash Opening Balance</b></td>
                                            <td class="text-right text-success"><b><?php echo ($dr_val >= 0) ? $currency_symbol . amountFormat($dr_val) : ''; ?></b></td>
                                            <td class="text-right text-danger"><b><?php echo ($cr_val > 0) ? $currency_symbol . amountFormat($cr_val) : ''; ?></b></td>
                                        </tr>
                                        <?php
                                    }
                                    if (isset($bank_opening)) {
                                        $dr_val = $bank_opening >= 0 ? $bank_opening : 0;
                                        $cr_val = $bank_opening < 0 ? abs($bank_opening) : 0;
                                        $total_dr += $dr_val;
                                        $total_cr += $cr_val;
                                        ?>
                                        <tr style="background-color: #f0fdf4;">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Bank Opening Balance</b></td>
                                            <td class="text-right text-success"><b><?php echo ($dr_val >= 0) ? $currency_symbol . amountFormat($dr_val) : ''; ?></b></td>
                                            <td class="text-right text-danger"><b><?php echo ($cr_val > 0) ? $currency_symbol . amountFormat($cr_val) : ''; ?></b></td>
                                        </tr>
                                        <?php
                                    }
                                    
                                    if (!empty($result)) {
                                        foreach ($result as $row) {
                                        $dr = $row['debit_amount'];
                                        $cr = $row['credit_amount'];
                                        $total_dr += $dr;
                                        $total_cr += $cr;
                                        ?>
                                        <tr>
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($row['voucher_date'])); ?></td>
                                            <td><?php echo $row['voucher_no']; ?></td>
                                            <td><?php echo ucfirst($row['voucher_type']); ?></td>
                                            <td>
                                                <b><?php echo $row['ledger_name']; ?></b><br>
                                                <small><?php echo $row['item_narration'] ? $row['item_narration'] : $row['narration']; ?></small>
                                            </td>
                                            <td class="text-right"><?php echo ($dr > 0) ? $currency_symbol . amountFormat($dr) : ''; ?></td>
                                            <td class="text-right"><?php echo ($cr > 0) ? $currency_symbol . amountFormat($cr) : ''; ?></td>
                                        </tr>
                                        <?php
                                    }
                                } // closes if (!empty($result))
                                    
                                    $cash_closing = isset($closing_balances) ? $closing_balances['cash'] : 0;
                                    $bank_closing = isset($closing_balances) ? $closing_balances['bank'] : 0;
                                    
                                    if (isset($cash_closing)) {
                                        $dr_val = $cash_closing >= 0 ? $cash_closing : 0;
                                        $cr_val = $cash_closing < 0 ? abs($cash_closing) : 0;
                                        // Closing balances shouldn't be added to total dr/cr of transactions for "Total" row to balance,
                                        // or we display them after total? Let's display them in the table before Total.
                                        $total_dr += $dr_val;
                                        $total_cr += $cr_val;
                                        ?>
                                        <tr style="background-color: #fef2f2;">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Cash Closing Balance</b></td>
                                            <td class="text-right text-success"><b><?php echo ($dr_val >= 0) ? $currency_symbol . amountFormat($dr_val) : ''; ?></b></td>
                                            <td class="text-right text-danger"><b><?php echo ($cr_val > 0) ? $currency_symbol . amountFormat($cr_val) : ''; ?></b></td>
                                        </tr>
                                        <?php
                                    }
                                    if (isset($bank_closing)) {
                                        $dr_val = $bank_closing >= 0 ? $bank_closing : 0;
                                        $cr_val = $bank_closing < 0 ? abs($bank_closing) : 0;
                                        $total_dr += $dr_val;
                                        $total_cr += $cr_val;
                                        ?>
                                        <tr style="background-color: #fef2f2;">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Bank Closing Balance</b></td>
                                            <td class="text-right text-success"><b><?php echo ($dr_val >= 0) ? $currency_symbol . amountFormat($dr_val) : ''; ?></b></td>
                                            <td class="text-right text-danger"><b><?php echo ($cr_val > 0) ? $currency_symbol . amountFormat($cr_val) : ''; ?></b></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right"><?php echo $this->lang->line('total'); ?>:</th>
                                    <th class="text-right"><?php echo $currency_symbol . amountFormat($total_dr); ?></th>
                                    <th class="text-right"><?php echo $currency_symbol . amountFormat($total_cr); ?></th>
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