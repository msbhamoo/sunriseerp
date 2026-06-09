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
            
            <?php if (isset($cash_result) || isset($bank_result) || isset($opening_balances)) { ?>
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('cash_book_report'); ?></h3>
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="download_label"><?php echo $this->lang->line('cash_book_report'); ?></div>
                        <style>
                            .cashbook-table { border-collapse: separate; border-spacing: 0; width: 100%; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 6px; overflow: hidden; border: 1px solid #e2e8f0; }
                            .cashbook-table th, .cashbook-table td { padding: 10px 12px; font-size: 13px; vertical-align: middle; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; }
                            .cashbook-table th:last-child, .cashbook-table td:last-child { border-right: none; }
                            .cashbook-table tbody tr:last-child td { border-bottom: none; }
                            .cashbook-table thead th { text-align: center; font-weight: 600; }
                            .cashbook-table tbody tr:hover { background-color: #f8fafc; }
                            .cashbook-table .split-border { border-left: 3px solid #cbd5e1 !important; }
                            .cashbook-header-dr { background-color: #ecfdf5 !important; font-size: 14px; padding: 12px !important; color:#065f46; border-bottom: 2px solid #a7f3d0 !important;}
                            .cashbook-header-cr { background-color: #fef2f2 !important; font-size: 14px; padding: 12px !important; border-left: 3px solid #fecaca !important; color:#991b1b; border-bottom: 2px solid #fecaca !important;}
                            .download_label { font-size: 18px; font-weight: 600; margin-bottom: 15px; color: #334155; }
                        </style>
                        <table class="table table-bordered cashbook-table">
                            <thead>
                                <tr>
                                    <th colspan="5" class="text-center cashbook-header-dr">RECEIPTS (Dr.)</th>
                                    <th colspan="5" class="text-center cashbook-header-cr">PAYMENTS (Cr.)</th>
                                </tr>
                                <tr>
                                    <th width="8%">Date</th>
                                    <th width="15%">Particulars</th>
                                    <th width="6%">Vch No.</th>
                                    <th width="10%" class="text-right">Cash (<?php echo $this->customlib->getSchoolCurrencyFormat(); ?>)</th>
                                    <th width="10%" class="text-right">Bank (<?php echo $this->customlib->getSchoolCurrencyFormat(); ?>)</th>
                                    <th width="8%" class="split-border">Date</th>
                                    <th width="15%">Particulars</th>
                                    <th width="6%">Vch No.</th>
                                    <th width="10%" class="text-right">Cash (<?php echo $this->customlib->getSchoolCurrencyFormat(); ?>)</th>
                                    <th width="10%" class="text-right">Bank (<?php echo $this->customlib->getSchoolCurrencyFormat(); ?>)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $receipts = [];
                                $payments = [];
                                
                                $total_receipts_cash = 0;
                                $total_receipts_bank = 0;
                                $total_payments_cash = 0;
                                $total_payments_bank = 0;

                                if (isset($opening_balances)) {
                                    $cash_ob = $opening_balances['cash'];
                                    $bank_ob = $opening_balances['bank'];
                                    
                                    // Opening balances usually go to Receipts if Debit (positive), Payments if Credit (negative)
                                    // For standard cash book, let's put both OB on the receipt side as "To Balance b/d" if positive
                                    if ($cash_ob >= 0 || $bank_ob >= 0) {
                                        $c_amt = $cash_ob >= 0 ? $cash_ob : 0;
                                        $b_amt = $bank_ob >= 0 ? $bank_ob : 0;
                                        $total_receipts_cash += $c_amt;
                                        $total_receipts_bank += $b_amt;
                                        
                                        $receipts[] = array(
                                            'date' => '-',
                                            'particulars' => '<b>To Balance b/d</b>',
                                            'vch_no' => '-',
                                            'cash' => $c_amt >= 0 ? amountFormat($c_amt) : '',
                                            'bank' => $b_amt >= 0 ? amountFormat($b_amt) : ''
                                        );
                                    }
                                    if ($cash_ob < 0 || $bank_ob < 0) {
                                        $c_amt = $cash_ob < 0 ? abs($cash_ob) : 0;
                                        $b_amt = $bank_ob < 0 ? abs($bank_ob) : 0;
                                        $total_payments_cash += $c_amt;
                                        $total_payments_bank += $b_amt;
                                        
                                        $payments[] = array(
                                            'date' => '-',
                                            'particulars' => '<b>By Balance b/d</b> (Overdraft)',
                                            'vch_no' => '-',
                                            'cash' => $c_amt > 0 ? amountFormat($c_amt) : '',
                                            'bank' => $b_amt > 0 ? amountFormat($b_amt) : ''
                                        );
                                    }
                                }

                                // Process transactions
                                // We combine cash and bank results, separating them into Receipts (Dr to Cash/Bank) and Payments (Cr to Cash/Bank)
                                $all_txns = [];
                                if (!empty($cash_result)) {
                                    foreach ($cash_result as $r) {
                                        $r['is_cash'] = true;
                                        $all_txns[] = $r;
                                    }
                                }
                                if (!empty($bank_result)) {
                                    foreach ($bank_result as $r) {
                                        $r['is_cash'] = false;
                                        $all_txns[] = $r;
                                    }
                                }
                                
                                // Sort by date
                                usort($all_txns, function($a, $b) {
                                    return strtotime($a['voucher_date']) - strtotime($b['voucher_date']);
                                });
                                
                                foreach ($all_txns as $r) {
                                    $date = date($this->customlib->getSchoolDateFormat(), strtotime($r['voucher_date']));
                                    $particulars = "<b>To {$r['ledger_name']}</b><br><small>{$r['item_narration']}</small>";
                                    $particulars_cr = "<b>By {$r['ledger_name']}</b><br><small>{$r['item_narration']}</small>";
                                    
                                    if ($r['debit_amount'] > 0) {
                                        // Receipt
                                        $c_amt = $r['is_cash'] ? $r['debit_amount'] : 0;
                                        $b_amt = !$r['is_cash'] ? $r['debit_amount'] : 0;
                                        $total_receipts_cash += $c_amt;
                                        $total_receipts_bank += $b_amt;
                                        
                                        $receipts[] = array(
                                            'date' => $date,
                                            'particulars' => $particulars,
                                            'vch_no' => $r['voucher_no'],
                                            'cash' => $c_amt > 0 ? amountFormat($c_amt) : '',
                                            'bank' => $b_amt > 0 ? amountFormat($b_amt) : ''
                                        );
                                    }
                                    if ($r['credit_amount'] > 0) {
                                        // Payment
                                        $c_amt = $r['is_cash'] ? $r['credit_amount'] : 0;
                                        $b_amt = !$r['is_cash'] ? $r['credit_amount'] : 0;
                                        $total_payments_cash += $c_amt;
                                        $total_payments_bank += $b_amt;
                                        
                                        $payments[] = array(
                                            'date' => $date,
                                            'particulars' => $particulars_cr,
                                            'vch_no' => $r['voucher_no'],
                                            'cash' => $c_amt > 0 ? amountFormat($c_amt) : '',
                                            'bank' => $b_amt > 0 ? amountFormat($b_amt) : ''
                                        );
                                    }
                                }

                                // Calculate Closing Balances
                                $cb_cash = $total_receipts_cash - $total_payments_cash;
                                $cb_bank = $total_receipts_bank - $total_payments_bank;

                                if ($cb_cash >= 0 || $cb_bank >= 0) {
                                    $c_amt = $cb_cash >= 0 ? $cb_cash : 0;
                                    $b_amt = $cb_bank >= 0 ? $cb_bank : 0;
                                    $total_payments_cash += $c_amt;
                                    $total_payments_bank += $b_amt;
                                    
                                    $payments[] = array(
                                        'date' => '-',
                                        'particulars' => '<b class="text-danger">By Balance c/d</b>',
                                        'vch_no' => '-',
                                        'cash' => $c_amt >= 0 ? amountFormat($c_amt) : '',
                                        'bank' => $b_amt >= 0 ? amountFormat($b_amt) : ''
                                    );
                                }
                                
                                if ($cb_cash < 0 || $cb_bank < 0) {
                                    $c_amt = $cb_cash < 0 ? abs($cb_cash) : 0;
                                    $b_amt = $cb_bank < 0 ? abs($cb_bank) : 0;
                                    $total_receipts_cash += $c_amt;
                                    $total_receipts_bank += $b_amt;
                                    
                                    $receipts[] = array(
                                        'date' => '-',
                                        'particulars' => '<b class="text-danger">To Balance c/d</b> (Overdraft)',
                                        'vch_no' => '-',
                                        'cash' => $c_amt > 0 ? amountFormat($c_amt) : '',
                                        'bank' => $b_amt > 0 ? amountFormat($b_amt) : ''
                                    );
                                }

                                // Render T-Shape rows
                                $max_rows = max(count($receipts), count($payments));
                                
                                for ($i = 0; $i < $max_rows; $i++) {
                                    echo "<tr>";
                                    // Receipts
                                    if (isset($receipts[$i])) {
                                        echo "<td>{$receipts[$i]['date']}</td>";
                                        echo "<td>{$receipts[$i]['particulars']}</td>";
                                        echo "<td>{$receipts[$i]['vch_no']}</td>";
                                        echo "<td class='text-right'>{$receipts[$i]['cash']}</td>";
                                        echo "<td class='text-right'>{$receipts[$i]['bank']}</td>";
                                    } else {
                                        echo "<td></td><td></td><td></td><td></td><td></td>";
                                    }
                                    
                                    // Payments
                                    if (isset($payments[$i])) {
                                        echo "<td class='split-border'>{$payments[$i]['date']}</td>";
                                        echo "<td>{$payments[$i]['particulars']}</td>";
                                        echo "<td>{$payments[$i]['vch_no']}</td>";
                                        echo "<td class='text-right'>{$payments[$i]['cash']}</td>";
                                        echo "<td class='text-right'>{$payments[$i]['bank']}</td>";
                                    } else {
                                        echo "<td class='split-border'></td><td></td><td></td><td></td><td></td>";
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f3f4f6; font-weight: bold;">
                                    <td colspan="3" class="text-right">TOTAL RECEIPTS:</td>
                                    <td class="text-right"><?php echo amountFormat($total_receipts_cash); ?></td>
                                    <td class="text-right"><?php echo amountFormat($total_receipts_bank); ?></td>
                                    <td colspan="3" class="text-right split-border">TOTAL PAYMENTS:</td>
                                    <td class="text-right"><?php echo amountFormat($total_payments_cash); ?></td>
                                    <td class="text-right"><?php echo amountFormat($total_payments_bank); ?></td>
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