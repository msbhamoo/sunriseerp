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
            
                <!-- Balances Summary: Opening + Closing -->
                <?php
                    $cur = $this->customlib->getSchoolCurrencyFormat();
                    $cash_delta = $cb_cash - $cash_ob;
                    $bank_delta = $cb_bank - $bank_ob;
                    $balance_cards = [
                        ['label' => $this->lang->line('opening_balance') . ' (Cash)', 'value' => $cash_ob, 'icon' => 'fa-money',       'accent' => '#0ea5e9', 'bg' => '#f0f9ff', 'type' => 'opening'],
                        ['label' => $this->lang->line('opening_balance') . ' (Bank)', 'value' => $bank_ob, 'icon' => 'fa-university',  'accent' => '#0ea5e9', 'bg' => '#f0f9ff', 'type' => 'opening'],
                        ['label' => $this->lang->line('closing_balance') . ' (Cash)', 'value' => $cb_cash, 'icon' => 'fa-money',       'accent' => ($cb_cash < 0 ? '#dc2626' : '#059669'), 'bg' => ($cb_cash < 0 ? '#fef2f2' : '#ecfdf5'), 'type' => 'closing', 'delta' => $cash_delta],
                        ['label' => $this->lang->line('closing_balance') . ' (Bank)', 'value' => $cb_bank, 'icon' => 'fa-university',  'accent' => ($cb_bank < 0 ? '#dc2626' : '#059669'), 'bg' => ($cb_bank < 0 ? '#fef2f2' : '#ecfdf5'), 'type' => 'closing', 'delta' => $bank_delta],
                    ];
                ?>
                <div class="row cashbook-balance-row">
                    <?php foreach ($balance_cards as $c) { ?>
                        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                            <div class="cashbook-balance-card" style="background: <?php echo $c['bg']; ?>; border: 1px solid #e2e8f0; border-left: 4px solid <?php echo $c['accent']; ?>; border-radius: 8px; padding: 16px 18px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: transform .15s ease, box-shadow .15s ease; height: 100%;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                    <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo $c['label']; ?></span>
                                    <i class="fa <?php echo $c['icon']; ?>" style="color: <?php echo $c['accent']; ?>; font-size: 16px; opacity: 0.9;"></i>
                                </div>
                                <div style="font-weight: 700; color: #0f172a; font-size: 22px; font-variant-numeric: tabular-nums; line-height: 1.2;">
                                    <?php echo $cur . ' ' . amountFormat(abs($c['value'])); ?>
                                    <?php if ($c['value'] < 0) echo ' <small style="color:#dc2626; font-weight:600;">(Cr)</small>'; ?>
                                </div>
                                <?php if ($c['type'] === 'closing') {
                                    $d = $c['delta'];
                                    if ($d > 0) {
                                        echo '<div style="margin-top:6px; font-size:11px; font-weight:600; color:#059669;"><i class="fa fa-arrow-up"></i> ' . $cur . amountFormat(abs($d)) . ' vs opening</div>';
                                    } elseif ($d < 0) {
                                        echo '<div style="margin-top:6px; font-size:11px; font-weight:600; color:#dc2626;"><i class="fa fa-arrow-down"></i> ' . $cur . amountFormat(abs($d)) . ' vs opening</div>';
                                    } else {
                                        echo '<div style="margin-top:6px; font-size:11px; font-weight:600; color:#64748b;"><i class="fa fa-minus"></i> No change</div>';
                                    }
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <style>
                    .cashbook-balance-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

                    /* --- Cashbook Transaction Tables --- */
                    .cashbook-txn-table { width: 100%; border-collapse: separate; border-spacing: 0; }
                    .cashbook-txn-table thead th {
                        white-space: nowrap; font-size: 11px !important; letter-spacing: 0.4px;
                        background: #f8fafc !important; color: #475569 !important;
                        padding: 10px 8px !important; vertical-align: middle; border-bottom: 2px solid #e2e8f0;
                    }
                    .cashbook-txn-table tbody td {
                        padding: 9px 8px !important; font-size: 12.5px !important;
                        vertical-align: middle; color: #1e293b;
                    }
                    /* SR NO. - narrow, centered */
                    .cashbook-txn-table thead th:nth-child(1),
                    .cashbook-txn-table tbody td:nth-child(1) { text-align: center; color: #94a3b8; font-weight: 600; width: 50px; }
                    /* Date - keep on one line */
                    .cashbook-txn-table tbody td:nth-child(2) { white-space: nowrap; color: #475569; font-variant-numeric: tabular-nums; }
                    /* Ledger - primary text */
                    .cashbook-txn-table tbody td:nth-child(3) strong { color: #0f172a; font-weight: 600; }
                    /* Account Type - dot prefix */
                    .cashbook-txn-table tbody td:nth-child(4) { white-space: nowrap; color: #475569; }
                    .cashbook-txn-table tbody td:nth-child(4)::before {
                        content: ''; display: inline-block; width: 6px; height: 6px; border-radius: 50%;
                        background: #94a3b8; margin-right: 6px; vertical-align: middle;
                    }
                    /* Amount - bold, tabular, colored by table type */
                    .cashbook-txn-table thead th.text-right,
                    .cashbook-txn-table tbody td.text-right {
                        white-space: nowrap; font-variant-numeric: tabular-nums; font-weight: 700 !important;
                    }
                    .box-success .cashbook-txn-table tbody td.text-right { color: #059669; }
                    .box-danger  .cashbook-txn-table tbody td.text-right { color: #dc2626; }
                    /* Row states */
                    .cashbook-txn-table tbody tr:hover td { background-color: #f1f5f9 !important; }
                    .cashbook-txn-table.table-striped > tbody > tr:nth-of-type(odd) { background-color: #fafbfc; }
                    /* Footer */
                    .cashbook-txn-table tfoot th {
                        background: #f8fafc !important; padding: 10px 8px !important;
                        font-size: 13px !important; border-top: 2px solid #e2e8f0;
                    }
                    /* Empty-state row */
                    .cashbook-txn-table tbody td[colspan] { color: #94a3b8 !important; font-style: italic; padding: 24px 12px !important; }

                    /* Inner vertical scroll so long lists don't push the whole page */
                    .cashbook-txn-scroll {
                        max-height: 520px; overflow: auto;
                        padding: 0; margin: 0; border-top: 1px solid #e2e8f0;
                    }
                    .cashbook-txn-scroll .cashbook-txn-table { margin: 0 !important; border: none !important; }
                    .cashbook-txn-scroll::-webkit-scrollbar { width: 8px; height: 8px; }
                    .cashbook-txn-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
                    .cashbook-txn-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
                    .cashbook-txn-scroll::-webkit-scrollbar-track { background: transparent; }
                    /* Sticky header + footer inside the scroll area */
                    .cashbook-txn-scroll .cashbook-txn-table thead th {
                        position: sticky; top: 0; z-index: 3;
                        background: #f8fafc !important;
                        box-shadow: inset 0 -2px 0 #e2e8f0, 0 2px 4px rgba(0,0,0,0.03);
                        border-bottom: none !important;
                    }
                    .cashbook-txn-scroll .cashbook-txn-table tfoot th {
                        position: sticky; bottom: 0; z-index: 3;
                        background: #f8fafc !important;
                        box-shadow: inset 0 2px 0 #e2e8f0, 0 -2px 4px rgba(0,0,0,0.03);
                        border-top: none !important;
                    }
                </style>

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
                            <div class="cashbook-txn-scroll">
                                <table class="table table-striped table-bordered table-hover cashbook-txn-table" style="margin-bottom: 0; border: none;">
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
                                            <?php $__n = !empty($r['item_narration']) ? $r['item_narration'] : $r['narration']; ?>
                                            <td title="<?php echo htmlspecialchars((string)$__n, ENT_QUOTES); ?>"><?php echo $__n; ?></td>
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
                            <div class="cashbook-txn-scroll">
                                <table class="table table-striped table-bordered table-hover cashbook-txn-table" style="margin-bottom: 0; border: none;">
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
                                            <?php $__n = !empty($r['item_narration']) ? $r['item_narration'] : $r['narration']; ?>
                                            <td title="<?php echo htmlspecialchars((string)$__n, ENT_QUOTES); ?>"><?php echo $__n; ?></td>
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