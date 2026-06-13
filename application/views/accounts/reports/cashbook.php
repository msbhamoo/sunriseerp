<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calculator"></i> <?php echo $this->lang->line('accounts'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('cash_book_report'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('accounts/reports/cashbook') ?>" method="post">
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
            
            <?php if (isset($cash_result) || isset($bank_result) || isset($opening_balances)) { 
                $cash_ob = isset($opening_balances) ? $opening_balances['cash'] : 0;
                $bank_ob = isset($opening_balances) ? $opening_balances['bank'] : 0;
                
                $all_txns = [];
                if (!empty($cash_result)) {
                    foreach ($cash_result as $r) {
                        $r['account_type'] = 'Cash';
                        $all_txns[] = $r;
                    }
                }
                if (!empty($bank_result)) {
                    foreach ($bank_result as $r) {
                        $r['account_type'] = 'Bank';
                        $all_txns[] = $r;
                    }
                }
                
                usort($all_txns, function($a, $b) {
                    return strtotime($a['voucher_date']) - strtotime($b['voucher_date']);
                });
                
                $income_txns = [];
                $expense_txns = [];
                $total_income = 0;
                $total_expense = 0;
                
                $total_receipts_cash = $cash_ob >= 0 ? $cash_ob : 0;
                $total_receipts_bank = $bank_ob >= 0 ? $bank_ob : 0;
                $total_payments_cash = $cash_ob < 0 ? abs($cash_ob) : 0;
                $total_payments_bank = $bank_ob < 0 ? abs($bank_ob) : 0;
                
                foreach ($all_txns as $r) {
                    if ($r['debit_amount'] > 0) {
                        $income_txns[] = $r;
                        $total_income += $r['debit_amount'];
                        if ($r['account_type'] == 'Cash') $total_receipts_cash += $r['debit_amount'];
                        if ($r['account_type'] == 'Bank') $total_receipts_bank += $r['debit_amount'];
                    }
                    if ($r['credit_amount'] > 0) {
                        $expense_txns[] = $r;
                        $total_expense += $r['credit_amount'];
                        if ($r['account_type'] == 'Cash') $total_payments_cash += $r['credit_amount'];
                        if ($r['account_type'] == 'Bank') $total_payments_bank += $r['credit_amount'];
                    }
                }
                
                $cb_cash = $total_receipts_cash - $total_payments_cash;
                $cb_bank = $total_receipts_bank - $total_payments_bank;
                
                $date_range_str = '';
                if(isset($date_from) && isset($date_to) && $date_from != '' && $date_to != '') {
                    $df_parsed = $this->customlib->dateFormatToYYYYMMDD($date_from);
                    $dt_parsed = $this->customlib->dateFormatToYYYYMMDD($date_to);
                    $date_range_str = "<br><small class='text-muted' style='font-size:14px;font-weight:normal;'><i class='fa fa-calendar'></i> " . date('F j, Y', strtotime($df_parsed)) . " to " . date('F j, Y', strtotime($dt_parsed)) . "</small>";
                }
            ?>
            <div class="col-md-12">
                <div style="margin-bottom: 20px; text-align: center;">
                    <h3 style="margin-top: 0; font-weight: 600; color: #444;">
                        <?php echo $this->lang->line('cash_book_report'); ?>
                        <?php echo $date_range_str; ?>
                    </h3>
                </div>
            
                <!-- Opening Balances -->
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="box box-solid" style="border: 1px solid #d2d6de; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-left: 4px solid #00c0ef;">
                            <div class="box-body text-center" style="padding: 15px;">
                                <span style="font-size: 11px; font-weight: 700; color: #777; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo $this->lang->line('opening_balance'); ?> (Cash)</span>
                                <h3 style="margin: 10px 0 0 0; font-weight: 600; color: #333; font-size: 22px;"><?php echo $this->customlib->getSchoolCurrencyFormat() . ' ' . amountFormat(abs($cash_ob)) . ($cash_ob < 0 ? ' <small class="text-danger">(Cr)</small>' : ''); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="box box-solid" style="border: 1px solid #d2d6de; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-left: 4px solid #00c0ef;">
                            <div class="box-body text-center" style="padding: 15px;">
                                <span style="font-size: 11px; font-weight: 700; color: #777; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo $this->lang->line('opening_balance'); ?> (Bank)</span>
                                <h3 style="margin: 10px 0 0 0; font-weight: 600; color: #333; font-size: 22px;"><?php echo $this->customlib->getSchoolCurrencyFormat() . ' ' . amountFormat(abs($bank_ob)) . ($bank_ob < 0 ? ' <small class="text-danger">(Cr)</small>' : ''); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Income Panel -->
                    <div class="col-md-6">
                        <div class="box box-success" style="border-top-width: 3px; box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
                            <div class="box-header with-border" style="padding: 12px 15px;">
                                <h3 class="box-title" style="font-weight: 600;"><i class="fa fa-arrow-down text-success" style="margin-right: 5px;"></i> Income</h3>
                                <div class="box-tools pull-right">
                                    <span class="label label-success" style="font-size: 13px; padding: 5px 10px; border-radius: 4px;">TOTAL: <?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($total_income); ?></span>
                                </div>
                            </div>
                            <div class="box-body table-responsive" style="padding: 0;">
                                <table class="table table-striped table-bordered table-hover" style="margin-bottom: 0; border: none;">
                                    <thead>
                                        <tr style="background-color: #f9fafb;">
                                            <th style="border-top: none; font-size: 12px; color: #555;">SR NO.</th>
                                            <th style="border-top: none; font-size: 12px; color: #555;">DATE</th>
                                            <th style="border-top: none; font-size: 12px; color: #555;">LEDGER</th>
                                            <th style="border-top: none; font-size: 12px; color: #555;">ACCOUNT TYPE</th>
                                            <th class="text-right" style="border-top: none; font-size: 12px; color: #555;">AMOUNT</th>
                                            <th style="border-top: none; font-size: 12px; color: #555;">NARRATION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($income_txns)) { ?>
                                            <tr><td colspan="6" class="text-center text-muted" style="padding: 20px;">No Income Transactions</td></tr>
                                        <?php } else {
                                            $i = 1;
                                            foreach($income_txns as $r) {
                                        ?>
                                        <tr>
                                            <td style="font-size: 13px;"><?php echo $i++; ?></td>
                                            <td style="font-size: 13px;"><?php echo date('F j, Y', strtotime($r['voucher_date'])); ?></td>
                                            <td style="font-size: 13px;"><strong><?php echo $r['ledger_name']; ?></strong></td>
                                            <td style="font-size: 13px;"><?php echo $r['account_type']; ?></td>
                                            <td class="text-right" style="font-size: 13px; font-weight: 600;"><?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($r['debit_amount']); ?></td>
                                            <td style="font-size: 13px;"><?php echo !empty($r['item_narration']) ? $r['item_narration'] : $r['narration']; ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #f4f6f9;">
                                            <th colspan="4" class="text-right" style="font-size: 14px;">Total Income:</th>
                                            <th class="text-right text-success" style="font-size: 15px;"><?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($total_income); ?></th>
                                            <th colspan="1"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Expense Panel -->
                    <div class="col-md-6">
                        <div class="box box-danger" style="border-top-width: 3px; box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
                            <div class="box-header with-border" style="padding: 12px 15px;">
                                <h3 class="box-title" style="font-weight: 600;"><i class="fa fa-arrow-up text-danger" style="margin-right: 5px;"></i> Expense</h3>
                                <div class="box-tools pull-right">
                                    <span class="label label-danger" style="font-size: 13px; padding: 5px 10px; border-radius: 4px;">TOTAL: <?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($total_expense); ?></span>
                                </div>
                            </div>
                            <div class="box-body table-responsive" style="padding: 0;">
                                <table class="table table-striped table-bordered table-hover" style="margin-bottom: 0; border: none;">
                                    <thead>
                                        <tr style="background-color: #f9fafb;">
                                            <th style="border-top: none; font-size: 12px; color: #555;">SR NO.</th>
                                            <th style="border-top: none; font-size: 12px; color: #555;">DATE</th>
                                            <th style="border-top: none; font-size: 12px; color: #555;">LEDGER</th>
                                            <th style="border-top: none; font-size: 12px; color: #555;">ACCOUNT TYPE</th>
                                            <th class="text-right" style="border-top: none; font-size: 12px; color: #555;">AMOUNT</th>
                                            <th style="border-top: none; font-size: 12px; color: #555;">NARRATION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($expense_txns)) { ?>
                                            <tr><td colspan="6" class="text-center text-muted" style="padding: 20px;">No Expense Transactions</td></tr>
                                        <?php } else {
                                            $i = 1;
                                            foreach($expense_txns as $r) {
                                        ?>
                                        <tr>
                                            <td style="font-size: 13px;"><?php echo $i++; ?></td>
                                            <td style="font-size: 13px;"><?php echo date('F j, Y', strtotime($r['voucher_date'])); ?></td>
                                            <td style="font-size: 13px;"><strong><?php echo $r['ledger_name']; ?></strong></td>
                                            <td style="font-size: 13px;"><?php echo $r['account_type']; ?></td>
                                            <td class="text-right" style="font-size: 13px; font-weight: 600;"><?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($r['credit_amount']); ?></td>
                                            <td style="font-size: 13px;"><?php echo !empty($r['item_narration']) ? $r['item_narration'] : $r['narration']; ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #f4f6f9;">
                                            <th colspan="4" class="text-right" style="font-size: 14px;">Total Expense:</th>
                                            <th class="text-right text-danger" style="font-size: 15px;"><?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($total_expense); ?></th>
                                            <th colspan="1"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Closing Balances -->
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="box box-solid" style="border: 1px solid #d2d6de; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-left: 4px solid #dd4b39;">
                            <div class="box-body text-center" style="padding: 15px;">
                                <span style="font-size: 11px; font-weight: 700; color: #777; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo $this->lang->line('closing_balance'); ?> (Cash)</span>
                                <h3 style="margin: 10px 0 0 0; font-weight: 600; color: #333; font-size: 22px;"><?php echo $this->customlib->getSchoolCurrencyFormat() . ' ' . amountFormat(abs($cb_cash)) . ($cb_cash < 0 ? ' <small class="text-danger">(Cr)</small>' : ''); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="box box-solid" style="border: 1px solid #d2d6de; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-left: 4px solid #dd4b39;">
                            <div class="box-body text-center" style="padding: 15px;">
                                <span style="font-size: 11px; font-weight: 700; color: #777; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo $this->lang->line('closing_balance'); ?> (Bank)</span>
                                <h3 style="margin: 10px 0 0 0; font-weight: 600; color: #333; font-size: 22px;"><?php echo $this->customlib->getSchoolCurrencyFormat() . ' ' . amountFormat(abs($cb_bank)) . ($cb_bank < 0 ? ' <small class="text-danger">(Cr)</small>' : ''); ?></h3>
                            </div>
                        </div>
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