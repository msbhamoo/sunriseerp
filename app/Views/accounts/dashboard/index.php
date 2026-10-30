<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<!-- Chart.js 2.9.4 — capture before footer's older version can overwrite -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="<?php echo base_url('backend/js/Chart.min.js'); ?>"></script>
<script>window.AccountsChart = window.Chart;</script>

<?php
// Developer Sandbox: Super Admin only (role_id 1 or 7)
$__admin_role = isset($this->session->userdata('admin')['role_id'])
    ? (int)$this->session->userdata('admin')['role_id'] : 0;
$__is_super_admin = in_array($__admin_role, [1, 7]);

// Currency symbol
$curr = $this->customlib->getSchoolCurrencyFormat();

// Monthly P&L helpers
$m_income  = isset($monthly_pl['total_income'])  ? (float)$monthly_pl['total_income']  : 0;
$m_expense = isset($monthly_pl['total_expense']) ? (float)$monthly_pl['total_expense'] : 0;
$m_net     = isset($monthly_pl['net_profit'])    ? (float)$monthly_pl['net_profit']    : 0;
?>

<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f4f6f9;
        font-family: 'Inter', sans-serif;
    }
    .d2-card {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #eaeaea;
    }
    .d2-title {
        font-size: 12px;
        font-weight: 600;
        color: #8a8a8a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }
    .d2-box {
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #f0f0f0;
    }
    .d2-box-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .d2-box-val {
        font-size: 22px;
        font-weight: 700;
    }
    .d2-box-sub {
        font-size: 11px;
        color: #888;
    }
    .d2-box.students { background: #fdfaf6; border-color: #f7eedf; }
    .d2-box.students .d2-box-title { color: #d68940; }
    
    .d2-box.staff { background: #fdfaff; border-color: #f4e8fb; }
    .d2-box.staff .d2-box-title { color: #9d50ce; }

    .d2-box.attendance { background: #f6fffa; border-color: #dcf2e6; }
    .d2-box.attendance .d2-box-title { color: #3b9b65; }

    .d2-box.feerecovery { background: #fff5f8; border-color: #fbe0e8; }
    .d2-box.feerecovery .d2-box-title { color: #d8456a; }
</style>
<div class="content-wrapper dashboard2-wrapper">
    <section class="content pb0">

        <!-- Flash messages -->
        <?php if ($this->session->flashdata('msg')): ?>
            <div class="dashalert alert alert-success alert-dismissible" role="alert">
                <button type="button" class="alertclose close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        <?php endif; ?>

        <!-- Developer Sandbox (Super Admin only) -->
        <?php if ($__is_super_admin): ?>
        <div class="box box-primary" >
            <div class="box-body">
                <strong style="color:#856404;"><i class="fa fa-terminal"></i> Dev Sandbox</strong>
                <span style="color:#856404; margin-left:10px;">Seed realistic mock transactions to populate charts &amp; metrics</span>
                <div class="pull-right">
                    <a href="<?php echo site_url('accounts/dashboard/generate_demo_data'); ?>" class="btn btn-success btn-xs" onclick="return confirm('Load 22 realistic transactions over 6 months?');"><i class="fa fa-database"></i> Seed Data</a>
                    <a href="<?php echo site_url('accounts/dashboard/reset_demo_data'); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete all demo data and reset opening balances?');"><i class="fa fa-trash-o"></i> Clear</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ROW 1: 4 METRIC CARDS -->
        <div class="row">
            <div class="col-md-12">
                <div class="d2-card">
                    <div class="d2-title">Financial Overview</div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="d2-box attendance">
                                <div class="d2-box-title">Cash Balance</div>
                                <div class="d2-box-val"><?php echo $curr . number_format($cash_balance, 2); ?></div>
                                <div class="d2-box-sub"><a href="<?php echo site_url('accounts/reports/cashbook'); ?>" style="color:inherit;">View Cash Book &rarr;</a></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d2-box staff">
                                <div class="d2-box-title">Bank Balance</div>
                                <div class="d2-box-val"><?php echo $curr . number_format($bank_balance, 2); ?></div>
                                <div class="d2-box-sub"><a href="<?php echo site_url('accounts/reports/bankbook'); ?>" style="color:inherit;">View Bank Book &rarr;</a></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d2-box students">
                                <div class="d2-box-title">Receivables</div>
                                <div class="d2-box-val"><?php echo $curr . number_format($receivables, 2); ?></div>
                                <div class="d2-box-sub"><a href="<?php echo site_url('accounts/reports/outstanding'); ?>" style="color:inherit;">View Outstanding &rarr;</a></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d2-box feerecovery">
                                <div class="d2-box-title">Payables</div>
                                <div class="d2-box-val"><?php echo $curr . number_format($payables, 2); ?></div>
                                <div class="d2-box-sub"><a href="<?php echo site_url('accounts/reports/outstanding'); ?>" style="color:inherit;">View Outstanding &rarr;</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <!-- Recent Transactions -->
                <div class="d2-card">
                    <div class="d2-title"> Recent Transactions</div><div style="display:none;">
                        
                    </div><div>
                        <?php if (!empty($recent_vouchers)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Voucher No</th>
                                        <th>Type</th>
                                        <th>Narration</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_vouchers as $v):
                                        $vtype  = $v['voucher_type'];
                                        $isCredit = in_array($vtype, ['receipt']);
                                        $amt   = number_format((float)$v['total_amount'], 2);
                                        $narr  = isset($v['narration']) && $v['narration']
                                               ? mb_substr($v['narration'], 0, 45) . (mb_strlen($v['narration']) > 45 ? '…' : '')
                                               : '—';
                                    ?>
                                    <tr>
                                        <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($v['voucher_date'])); ?></td>
                                        <td><a href="<?php echo site_url('accounts/' . $vtype . 'voucher/edit/' . $v['id']); ?>"><?php echo $v['voucher_no']; ?></a></td>
                                        <td>
                                            <?php if($vtype == 'receipt') { ?>
                                                <span class="label label-success">Receipt</span>
                                            <?php } elseif($vtype == 'payment') { ?>
                                                <span class="label label-danger">Payment</span>
                                            <?php } elseif($vtype == 'contra') { ?>
                                                <span class="label label-info">Contra</span>
                                            <?php } elseif($vtype == 'journal') { ?>
                                                <span class="label label-warning">Journal</span>
                                            <?php } else { ?>
                                                <span class="label label-default"><?php echo ucfirst($vtype); ?></span>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $narr; ?></td>
                                        <td class="text-right <?php echo $isCredit ? 'text-success' : 'text-danger'; ?>">
                                            <strong><?php echo $curr . $amt; ?></strong>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div style="text-align: center; padding: 20px;">
                            <img src="https://smart-school.in/ssappresource/images/addnewitem.svg" width="150" class="center-block mt20">
                            <p class="text-muted mt10">No vouchers recorded yet.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Trend Chart -->
                <div class="d2-card">
                    <div class="d2-title"> Revenue &amp; Expense — 6-Month Trend</div><div style="display:none;">
                    </div><div>
                        <canvas id="accTrendChart" height="90"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12">
                <!-- Global Search -->
                <div class="d2-card">
                    <div class="d2-title"> Voucher Search</div><div style="display:none;">
                    </div><div>
                        <div class="acc-global-search-container" style="width: 100%;">
                            <div class="input-group">
                                <input type="text" id="accGlobalSearch" class="form-control" placeholder="Search Voucher (No., Ledger, Amt)..." autocomplete="off" />
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            </div>
                            <div id="accSearchDropdown" class="acc-search-dropdown-menu" style="top:auto;"></div>
                        </div>
                    </div>
                </div>


                <!-- Monthly Summary -->
                <div class="d2-card">
                    <div class="d2-title"> <?php echo date('F'); ?> Summary</div><div style="display:none;">
                    </div><div>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b><i class="fa fa-arrow-down text-success"></i> Total Income</b> <a class="pull-right text-success"><?php echo $curr . number_format($m_income, 2); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fa fa-arrow-up text-danger"></i> Total Expenses</b> <a class="pull-right text-danger"><?php echo $curr . number_format($m_expense, 2); ?></a>
                            </li>
                            <li class="list-group-item" style="background: <?php echo $m_net >= 0 ? '#dff0d8' : '#f2dede'; ?>; padding: 10px; margin-top: 10px;">
                                <b><?php echo $m_net >= 0 ? '▲ Net Surplus' : '▼ Net Deficit'; ?></b> 
                                <a class="pull-right" style="color: <?php echo $m_net >= 0 ? '#3c763d' : '#a94442'; ?>; font-weight:bold; font-size:16px;"><?php echo $curr . number_format(abs($m_net), 2); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Ledger Lookup -->
                <div class="d2-card">
                    <div class="d2-title"> Quick Balance Lookup</div><div style="display:none;">
                    </div><div>
                        <div class="form-group">
                            <select id="acc-ledger-select" class="form-control">
                                <option value="">— Select a ledger account —</option>
                                <?php foreach ($all_ledgers as $lg): ?>
                                    <option value="<?php echo $lg['id']; ?>"><?php echo htmlspecialchars($lg['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="acc-lookup-result well well-sm text-center" id="acc-lookup-result" style="margin-bottom:0;">
                            <span class="text-muted"><i class="fa fa-info-circle"></i> Select a ledger above</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Helper form for outstanding redirects -->
<form id="outstanding-redirect-form" method="post" action="<?php echo site_url('accounts/reports/outstanding'); ?>" style="display:none;">
    <input type="hidden" name="type" id="outstanding-form-type" value="receivable">
    <input type="hidden" name="search" value="search">
</form>

<script>
(function() {
    console.log("Accounts Dashboard JS initializing...");

    // ── Trend Chart ─────────────────────────────────────────────
    try {
        var trendData = <?php echo isset($months_trend) ? json_encode($months_trend) : '[]'; ?>;
        if (!trendData) trendData = [];
        
        var trendLabels = Array.isArray(trendData) ? trendData.map(function(d) { return d.label; }) : [];
        var incomeVals  = Array.isArray(trendData) ? trendData.map(function(d) { return parseFloat(d.income); }) : [];
        var expenseVals = Array.isArray(trendData) ? trendData.map(function(d) { return parseFloat(d.expense); }) : [];

        var ctxTrend = document.getElementById('accTrendChart');
        if (ctxTrend && typeof AccountsChart !== 'undefined') {
            var ig = ctxTrend.getContext('2d').createLinearGradient(0, 0, 0, 280);
            ig.addColorStop(0, 'rgba(59,130,246,0.12)');
            ig.addColorStop(1, 'rgba(59,130,246,0)');
            var eg = ctxTrend.getContext('2d').createLinearGradient(0, 0, 0, 280);
            eg.addColorStop(0, 'rgba(239,68,68,0.10)');
            eg.addColorStop(1, 'rgba(239,68,68,0)');

            new AccountsChart(ctxTrend.getContext('2d'), {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        {
                            label: 'Revenue',
                            data: incomeVals,
                            borderColor: '#3b82f6',
                            backgroundColor: ig,
                            borderWidth: 2.5,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            lineTension: 0.45,
                            fill: true
                        },
                        {
                            label: 'Expenses',
                            data: expenseVals,
                            borderColor: '#ef4444',
                            backgroundColor: eg,
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#ef4444',
                            pointBorderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            lineTension: 0.45,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#1e293b',
                        titleFontFamily: "'Inter', sans-serif",
                        bodyFontFamily: "'Inter', sans-serif",
                        titleFontSize: 12,
                        bodyFontSize: 12,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ti, data) {
                                var ds  = data.datasets[ti.datasetIndex];
                                var val = parseFloat(ti.yLabel).toLocaleString('en-US', { minimumFractionDigits: 2 });
                                return ' ' + ds.label + ': <?php echo addslashes($curr); ?>' + val;
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: { display: false },
                            ticks: {
                                fontSize: 11,
                                fontFamily: "'Inter', sans-serif",
                                fontColor: '#9ca3af'
                            }
                        }],
                        yAxes: [{
                            gridLines: { color: '#f3f4f6', drawBorder: false },
                            ticks: {
                                fontSize: 11,
                                fontFamily: "'Inter', sans-serif",
                                fontColor: '#9ca3af',
                                callback: function(v) {
                                    return v >= 1000 ? '<?php echo addslashes($curr); ?>' + (v/1000).toFixed(0) + 'k' : '<?php echo addslashes($curr); ?>' + v;
                                },
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            console.log("Trend Chart initialized successfully.");
        } else {
            console.warn("Trend Chart element or AccountsChart class missing.");
        }
    } catch(e) {
        console.error("Trend Chart initialization failed:", e);
    }

    // ── Ledger Balance Lookup AJAX ─────────────────────────────
    try {
        var ledgerSelect = document.getElementById('acc-ledger-select');
        var lookupResult = document.getElementById('acc-lookup-result');

        if (ledgerSelect) {
            ledgerSelect.addEventListener('change', function() {
                var id = this.value;
                if (!id) {
                    lookupResult.innerHTML = '<span class="prompt-text"><i class="fa fa-info-circle"></i> Select a ledger above</span>';
                    return;
                }
                lookupResult.innerHTML = '<span class="prompt-text"><i class="fa fa-spinner fa-spin"></i> Loading…</span>';

                if (window.jQuery) {
                    window.jQuery.ajax({
                        url: '<?php echo site_url('accounts/dashboard/get_ledger_balance'); ?>',
                        type: 'POST',
                        data: {
                            ledger_id: id,
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                        },
                        success: function(res) {
                            try {
                                var r = typeof res === 'string' ? JSON.parse(res) : res;
                                if (r.status === 'success') {
                                    lookupResult.innerHTML =
                                        '<div class="balance-display">' +
                                        '<span class="bal-name">' + r.ledger_name + '</span>' +
                                        '<span class="bal-amount">' + r.balance + '</span>' +
                                        '<span class="bal-type">' + r.type + '</span>' +
                                        '</div>';
                                } else {
                                    lookupResult.innerHTML = '<span class="prompt-text" style="color:#ef4444;"><i class="fa fa-exclamation-circle"></i> ' + (r.message || 'Error') + '</span>';
                                }
                            } catch(e) {
                                lookupResult.innerHTML = '<span class="prompt-text" style="color:#ef4444;"><i class="fa fa-exclamation-circle"></i> Parse error</span>';
                            }
                        },
                        error: function() {
                            lookupResult.innerHTML = '<span class="prompt-text" style="color:#ef4444;"><i class="fa fa-exclamation-triangle"></i> Network error</span>';
                        }
                    });
                } else {
                    console.warn("jQuery missing for Ledger Balance Lookup.");
                }
            });
            console.log("Ledger Lookup handler bound.");
        }
    } catch(e) {
        console.error("Ledger Lookup initialization failed:", e);
    }

    // ── Dynamic Offcanvas Sidebars & Namespaced Logic ────────────
    try {
        // Safe scoping of all variables & calculation routines
        
        // PHP-to-JS Options injection
        var receipt_ledger_opts = '<?php 
            $opt = "";
            foreach($ledgers as $l) {
                $opt .= "<option value=\"".$l['id']."\">".addslashes(htmlspecialchars($l['name']))."</option>";
            }
            echo $opt;
        ?>';
        var receipt_expense_opts = '<?php
            $opt = "";
            foreach($expense_types as $et) {
                $opt .= "<option value=\"".$et['id']."\">".addslashes(htmlspecialchars($et['name']))."</option>";
            }
            echo $opt;
        ?>';
        
        var payment_ledger_opts = receipt_ledger_opts;
        var payment_expense_opts = receipt_expense_opts;
        var purchase_expense_opts = receipt_expense_opts;
        var journal_ledger_opts = receipt_ledger_opts;
        
        var activeLedgerSelect = null;

        // Move fixed elements directly to body to prevent styling/clipping issues
        if (window.jQuery) {
            window.jQuery('.acc-offcanvas').appendTo('body');
            window.jQuery('#accQuickOverlay').appendTo('body');
        }

        // ── Navigation & Control ──
        window.openSidebar = function(type) {
            closeAllSidebars();
            var $sidebar = window.jQuery('#accQuickSidebar_' + type);
            var $overlay = window.jQuery('#accQuickOverlay');
            if ($sidebar.length) {
                $sidebar.addClass('show');
                $overlay.addClass('show');
                
                // Initialize Select2 dropdowns
                if (window.jQuery.fn.select2) {
                    $sidebar.find('.select2-namespaced').select2({ width: '100%' });
                    $sidebar.find('.ledger_select').select2({ width: '100%' });
                }
                
                // Contextual initialization
                if (type === 'receipt') {
                    receipt_calculate_total();
                } else if (type === 'payment') {
                    payment_calculate_total();
                } else if (type === 'contra') {
                    contra_update_ledgers();
                } else if (type === 'purchase') {
                    purchase_calculate_total();
                } else if (type === 'journal') {
                    journal_calculate_total();
                }
            }
        };

        window.closeAllSidebars = function() {
            window.jQuery('.acc-offcanvas').removeClass('show');
            window.jQuery('#accQuickOverlay').removeClass('show');
        };

        // Event delegation for opening and closing sidebars
        if (window.jQuery) {
            window.jQuery(document).on('click', '.btn-open-quick', function(e) {
                e.preventDefault();
                var type = window.jQuery(this).attr('data-type');
                openSidebar(type);
            });
            window.jQuery(document).on('click', '#accQuickOverlay', function(e) {
                e.preventDefault();
                closeAllSidebars();
            });
        }

        // ── Shared Balance Lookup AJAX ──
        window.fetchLedgerBalance = function(ledgerId, $badgeEl) {
            if (!$badgeEl || $badgeEl.length === 0) return;
            if (!ledgerId) {
                $badgeEl.html('');
                return;
            }
            $badgeEl.html('<i class="fa fa-spinner fa-spin" style="color:#6b7280;"></i>');
            window.jQuery.ajax({
                url: '<?php echo site_url("accounts/ledgermaster/get_balance"); ?>',
                type: 'POST',
                data: {
                    id: ledgerId,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'JSON',
                success: function(res) {
                    if (res.status === 'success') {
                        var color = (res.type === 'Cr') ? '#10b981' : '#ef4444';
                        $badgeEl.html('Balance: <span style="color:' + color + '; font-weight: 600;">' + parseFloat(res.balance).toFixed(2) + ' ' + res.type + '</span>');
                    } else {
                        $badgeEl.html('');
                    }
                },
                error: function() {
                    $badgeEl.html('');
                }
            });
        };

        // ── Receipt Voucher Section ──
        window.receipt_add_row = function() {
            var html = '<tr>' +
                '<td>' +
                '    <div class="input-group">' +
                '        <select name="ledger_id[]" class="form-control ledger_select" required style="width: 100%;">' +
                '            <option value="">Select</option>' + receipt_ledger_opts +
                '        </select>' +
                '        <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="receipt_showAddLedgerModal(window.jQuery(this).closest(\'.input-group\').find(\'select\'))">' +
                '            <i class="fa fa-plus text-primary"></i>' +
                '        </div>' +
                '    </div>' +
                '    <div class="cr_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>' +
                '</td>' +
                '<td>' +
                '    <select name="expense_type_id[]" class="form-control">' +
                '        <option value="">Select</option>' + receipt_expense_opts +
                '    </select>' +
                '</td>' +
                '<td><input type="number" step="0.01" name="amount[]" class="form-control receipt_amount" required></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger" onclick="receipt_remove_row(this)"><i class="fa fa-remove"></i></button></td>' +
                '</tr>';
            var $row = window.jQuery(html);
            window.jQuery('#receipt_item_table tbody').append($row);
            if (window.jQuery.fn.select2) {
                $row.find('.ledger_select').select2({ width: '100%' });
            }
        };

        window.receipt_remove_row = function(el) {
            window.jQuery(el).closest('tr').remove();
            receipt_calculate_total();
        };

        window.receipt_calculate_total = function() {
            var total = 0;
            window.jQuery('.receipt_amount').each(function() {
                total += parseFloat(window.jQuery(this).val()) || 0;
            });
            window.jQuery('#receipt_total_amount').val(total.toFixed(2));
        };

        window.receipt_showAddPaymentModeModal = function() {
            window.jQuery('#new_pm_name').val('');
            window.jQuery('#pm_group_id').val('').trigger('change');
            window.jQuery('#pm_bank_id').val('');
            window.jQuery('#new_pm_account_no').val('');
            window.jQuery('#addPaymentModeModal').modal('show');
        };

        window.receipt_showAddLedgerModal = function($select) {
            activeLedgerSelect = $select;
            window.jQuery('#new_ledger_name').val('');
            window.jQuery('#ledger_group_id').val('');
            window.jQuery('#addLedgerModal').modal('show');
        };

        window.jQuery(document).on('keyup change', '.receipt_amount', function() {
            receipt_calculate_total();
        });

        window.jQuery(document).on('change', '#receipt_dr_ledger_id', function() {
            fetchLedgerBalance($(this).val(), window.jQuery('#receipt_dr_ledger_balance'));
        });

        window.jQuery(document).on('change', '#receipt_item_table .ledger_select', function() {
            var row = window.jQuery(this).closest('tr');
            fetchLedgerBalance($(this).val(), row.find('.cr_ledger_balance'));
        });

        window.jQuery(document).on('change', '#receipt_payment_method', function() {
            var method = $(this).val();
            if (method === 'Cash') {
                window.jQuery('#receipt_payment_details_section').slideUp();
            } else {
                window.jQuery('#receipt_payment_details_section').slideDown();
                if (method === 'Cheque') {
                    window.jQuery('#receipt_lbl_ref_no').text('Cheque No');
                    window.jQuery('.receipt_bank_field_group').show();
                } else if (method === 'UPI') {
                    window.jQuery('#receipt_lbl_ref_no').text('UPI Transaction ID');
                    window.jQuery('.receipt_bank_field_group').hide();
                } else if (method === 'Net Banking') {
                    window.jQuery('#receipt_lbl_ref_no').text('Net Banking Ref');
                    window.jQuery('.receipt_bank_field_group').show();
                } else {
                    window.jQuery('#receipt_lbl_ref_no').text('Reference No');
                    window.jQuery('.receipt_bank_field_group').hide();
                }
            }
        });

        // ── Payment Voucher Section ──
        window.payment_add_row = function() {
            var html = '<tr>' +
                '<td>' +
                '    <div class="input-group">' +
                '        <select name="ledger_id[]" class="form-control ledger_select" required style="width: 100%;">' +
                '            <option value="">Select</option>' + payment_ledger_opts +
                '        </select>' +
                '        <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="payment_showAddLedgerModal(window.jQuery(this).closest(\'.input-group\').find(\'select\'))">' +
                '            <i class="fa fa-plus text-primary"></i>' +
                '        </div>' +
                '    </div>' +
                '    <div class="dr_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>' +
                '</td>' +
                '<td>' +
                '    <select name="expense_type_id[]" class="form-control">' +
                '        <option value="">Select</option>' + payment_expense_opts +
                '    </select>' +
                '</td>' +
                '<td><input type="number" step="0.01" name="amount[]" class="form-control payment_amount" required></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger" onclick="payment_remove_row(this)"><i class="fa fa-remove"></i></button></td>' +
                '</tr>';
            var $row = window.jQuery(html);
            window.jQuery('#payment_item_table tbody').append($row);
            if (window.jQuery.fn.select2) {
                $row.find('.ledger_select').select2({ width: '100%' });
            }
        };

        window.payment_remove_row = function(el) {
            window.jQuery(el).closest('tr').remove();
            payment_calculate_total();
        };

        window.payment_calculate_total = function() {
            var total = 0;
            window.jQuery('.payment_amount').each(function() {
                total += parseFloat(window.jQuery(this).val()) || 0;
            });
            window.jQuery('#payment_total_amount').val(total.toFixed(2));
        };

        window.payment_showAddPaymentModeModal = function() {
            receipt_showAddPaymentModeModal();
        };

        window.payment_showAddLedgerModal = function($select) {
            receipt_showAddLedgerModal($select);
        };

        window.jQuery(document).on('keyup change', '.payment_amount', function() {
            payment_calculate_total();
        });

        window.jQuery(document).on('change', '#payment_cr_ledger_id', function() {
            fetchLedgerBalance($(this).val(), window.jQuery('#payment_cr_ledger_balance'));
        });

        window.jQuery(document).on('change', '#payment_item_table .ledger_select', function() {
            var row = window.jQuery(this).closest('tr');
            fetchLedgerBalance($(this).val(), row.find('.dr_ledger_balance'));
        });

        window.jQuery(document).on('change', '#payment_payment_method', function() {
            var method = $(this).val();
            if (method === 'Cash') {
                window.jQuery('#payment_payment_details_section').slideUp();
            } else {
                window.jQuery('#payment_payment_details_section').slideDown();
                if (method === 'Cheque') {
                    window.jQuery('#payment_lbl_ref_no').text('Cheque No');
                    window.jQuery('.payment_bank_field_group').show();
                } else if (method === 'UPI') {
                    window.jQuery('#payment_lbl_ref_no').text('UPI Transaction ID');
                    window.jQuery('.payment_bank_field_group').hide();
                } else if (method === 'Net Banking') {
                    window.jQuery('#payment_lbl_ref_no').text('Net Banking Ref');
                    window.jQuery('.payment_bank_field_group').show();
                } else {
                    window.jQuery('#payment_lbl_ref_no').text('Reference No');
                    window.jQuery('.payment_bank_field_group').hide();
                }
            }
        });

        // ── Contra Voucher Section ──
        window.contra_update_ledgers = function() {
            var type = window.jQuery('#contra_transaction_type').val();
            var dr_select = window.jQuery('#contra_dr_ledger_id');
            var cr_select = window.jQuery('#contra_cr_ledger_id');
            
            if (dr_select.data('select2')) dr_select.select2('destroy');
            if (cr_select.data('select2')) cr_select.select2('destroy');
            
            dr_select.find('option').prop('disabled', false).show();
            cr_select.find('option').prop('disabled', false).show();
            
            if (type === 'Cash To Bank') {
                window.jQuery('#contra_lbl_dr_account').text('Deposit Into Bank (Debit) *');
                window.jQuery('#contra_lbl_cr_account').text('Withdraw From Cash (Credit) *');
                dr_select.find('option[data-system-group!="bank"][value!=""]').prop('disabled', true).hide();
                cr_select.find('option[data-system-group!="cash"][value!=""]').prop('disabled', true).hide();
            } else if (type === 'Bank To Cash') {
                window.jQuery('#contra_lbl_dr_account').text('Deposit Into Cash (Debit) *');
                window.jQuery('#contra_lbl_cr_account').text('Withdraw From Bank (Credit) *');
                dr_select.find('option[data-system-group!="cash"][value!=""]').prop('disabled', true).hide();
                cr_select.find('option[data-system-group!="bank"][value!=""]').prop('disabled', true).hide();
            } else if (type === 'Bank To Bank') {
                window.jQuery('#contra_lbl_dr_account').text('Receiving Bank (Debit) *');
                window.jQuery('#contra_lbl_cr_account').text('Sending Bank (Credit) *');
                dr_select.find('option[data-system-group!="bank"][value!=""]').prop('disabled', true).hide();
                cr_select.find('option[data-system-group!="bank"][value!=""]').prop('disabled', true).hide();
            }
            
            if (dr_select.find('option:selected').prop('disabled')) dr_select.val('');
            if (cr_select.find('option:selected').prop('disabled')) cr_select.val('');
            
            if (window.jQuery.fn.select2) {
                dr_select.select2({ width: '100%' });
                cr_select.select2({ width: '100%' });
            }
        };

        window.jQuery(document).on('change', '#contra_transaction_type', function() {
            contra_update_ledgers();
        });

        window.jQuery(document).on('change', '#contra_dr_ledger_id', function() {
            var row = window.jQuery(this).closest('.form-group');
            fetchLedgerBalance($(this).val(), row.find('.contra_ledger_balance'));
        });

        window.jQuery(document).on('change', '#contra_cr_ledger_id', function() {
            var row = window.jQuery(this).closest('.form-group');
            fetchLedgerBalance($(this).val(), row.find('.contra_ledger_balance'));
        });

        // ── Purchase Entry Section ──
        window.purchase_add_row = function() {
            var html = '<tr>' +
                '<td><input type="text" name="item_description[]" class="form-control" required></td>' +
                '<td>' +
                '    <select name="expense_type_id[]" class="form-control">' +
                '        <option value="">Select</option>' + purchase_expense_opts +
                '    </select>' +
                '</td>' +
                '<td><input type="number" step="0.01" name="qty[]" class="form-control purchase_qty" value="1.00" required></td>' +
                '<td><input type="number" step="0.01" name="rate[]" class="form-control purchase_rate" required></td>' +
                '<td><input type="number" step="0.01" name="amount[]" class="form-control purchase_amount" readonly></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger" onclick="purchase_remove_row(this)"><i class="fa fa-remove"></i></button></td>' +
                '</tr>';
            window.jQuery('#purchase_item_table tbody').append(html);
        };

        window.purchase_remove_row = function(el) {
            window.jQuery(el).closest('tr').remove();
            purchase_calculate_total();
        };

        window.purchase_calculate_total = function() {
            var total = 0;
            window.jQuery('.purchase_amount').each(function() {
                total += parseFloat(window.jQuery(this).val()) || 0;
            });
            window.jQuery('#purchase_total_amount').val(total.toFixed(2));
            
            var discount = parseFloat(window.jQuery('#purchase_discount').val()) || 0;
            var gst = parseFloat(window.jQuery('#purchase_gst_amount').val()) || 0;
            
            var net = total - discount + gst;
            window.jQuery('#purchase_net_amount').val(net.toFixed(2));
        };

        window.jQuery(document).on('keyup change', '.purchase_qty, .purchase_rate', function() {
            var row = window.jQuery(this).closest('tr');
            var qty = parseFloat(row.find('.purchase_qty').val()) || 0;
            var rate = parseFloat(row.find('.purchase_rate').val()) || 0;
            var amount = qty * rate;
            row.find('.purchase_amount').val(amount.toFixed(2));
            purchase_calculate_total();
        });

        window.jQuery(document).on('keyup change', '.purchase_calc_net', function() {
            purchase_calculate_total();
        });

        // ── Journal Voucher Section ──
        window.journal_add_row = function() {
            var html = '<tr>' +
                '<td>' +
                '    <select name="type[]" class="form-control journal_type_select" style="width:100%;">' +
                '        <option value="Dr">Dr</option>' +
                '        <option value="Cr">Cr</option>' +
                '    </select>' +
                '</td>' +
                '<td>' +
                '    <div class="input-group">' +
                '        <select name="ledger_id[]" class="form-control ledger_select" required style="width: 100%;">' +
                '            <option value="">Select</option>' + journal_ledger_opts +
                '        </select>' +
                '        <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="journal_showAddLedgerModal(window.jQuery(this).closest(\'.input-group\').find(\'select\'))">' +
                '            <i class="fa fa-plus text-primary"></i>' +
                '        </div>' +
                '    </div>' +
                '    <div class="journal_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>' +
                '</td>' +
                '<td><input type="number" step="0.01" name="amount[]" class="form-control journal_amount" required></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger" onclick="journal_remove_row(this)"><i class="fa fa-remove"></i></button></td>' +
                '</tr>';
            var $row = window.jQuery(html);
            window.jQuery('#journal_item_table tbody').append($row);
            if (window.jQuery.fn.select2) {
                $row.find('.ledger_select').select2({ width: '100%' });
            }
        };

        window.journal_remove_row = function(el) {
            window.jQuery(el).closest('tr').remove();
            journal_calculate_total();
        };

        window.journal_calculate_total = function() {
            var dr = 0;
            var cr = 0;
            window.jQuery('#journal_item_table tbody tr').each(function() {
                var type = window.jQuery(this).find('.journal_type_select').val();
                var val = parseFloat(window.jQuery(this).find('.journal_amount').val()) || 0;
                if (type === 'Dr') {
                    dr += val;
                } else {
                    cr += val;
                }
            });
            window.jQuery('#journal_total_dr').val(dr.toFixed(2));
            window.jQuery('#journal_total_cr').val(cr.toFixed(2));
            
            if (dr !== cr || dr === 0) {
                window.jQuery('#journal_total_dr, #journal_total_cr').css('border-color', 'red');
            } else {
                window.jQuery('#journal_total_dr, #journal_total_cr').css('border-color', 'green');
            }
        };

        window.journal_showAddLedgerModal = function($select) {
            receipt_showAddLedgerModal($select);
        };

        window.jQuery(document).on('keyup change', '.journal_amount, .journal_type_select', function() {
            journal_calculate_total();
        });

        window.jQuery(document).on('change', '#journal_item_table select[name="ledger_id[]"]', function() {
            var row = window.jQuery(this).closest('tr');
            fetchLedgerBalance($(this).val(), row.find('.journal_ledger_balance'));
        });

        window.jQuery('#journal_form_submit').submit(function(e) {
            var dr = parseFloat(window.jQuery('#journal_total_dr').val()) || 0;
            var cr = parseFloat(window.jQuery('#journal_total_cr').val()) || 0;
            if (dr !== cr || dr === 0) {
                e.preventDefault();
                alert("Debit and Credit amounts must be equal and greater than zero.");
                return false;
            }
        });

        // ── Shared Quick Add Modals Logic ──
        window.showAddBankModal = function() {
            window.jQuery('#new_bank_name').val('');
            window.jQuery('#addBankModal').modal('show');
        };

        window.showAddSupplierModal = function() {
            window.jQuery('#new_supplier_name').val('');
            window.jQuery('#new_supplier_mobile').val('');
            window.jQuery('#addSupplierModal').modal('show');
        };

        window.jQuery('#pm_group_id').change(function() {
            var val = window.jQuery(this).find('option:selected').text();
            if (val.toLowerCase() === 'bank account' || val.toLowerCase() === 'bank accounts') {
                window.jQuery('#pm_bank_details_section').slideDown();
            } else {
                window.jQuery('#pm_bank_details_section').slideUp();
            }
        });

        window.submitNewPaymentMode = function() {
            var name = window.jQuery('#new_pm_name').val().trim();
            var group_id = window.jQuery('#pm_group_id').val();
            if (name === '' || !group_id) {
                alert('Please enter a name and select a group.');
                return;
            }
            
            var bank_id = window.jQuery('#pm_bank_id').val();
            var account_no = window.jQuery('#new_pm_account_no').val().trim();
            
            window.jQuery.ajax({
                url: '<?php echo site_url("accounts/receiptvoucher/quick_add_ledger_ajax"); ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    group_id: group_id,
                    name: name,
                    bank_id: bank_id,
                    account_no: account_no,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        var newOption1 = new Option(res.name, res.id, true, true);
                        var newOption2 = new Option(res.name, res.id, true, true);
                        var newOption3 = new Option(res.name, res.id, true, true);
                        var newOption4 = new Option(res.name, res.id, true, true);
                        
                        // Add metadata attribute for Contra grouping
                        window.jQuery(newOption3).attr('data-group', group_id);
                        window.jQuery(newOption4).attr('data-group', group_id);
                        
                        window.jQuery('#receipt_dr_ledger_id').append(newOption1).trigger('change');
                        window.jQuery('#payment_cr_ledger_id').append(newOption2).trigger('change');
                        window.jQuery('#contra_dr_ledger_id').append(newOption3).trigger('change');
                        window.jQuery('#contra_cr_ledger_id').append(newOption4).trigger('change');
                        
                        window.jQuery('#addPaymentModeModal').modal('hide');
                    } else {
                        alert(res.error);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        };

        window.submitNewLedger = function() {
            var name = window.jQuery('#new_ledger_name').val().trim();
            var group_id = window.jQuery('#ledger_group_id').val();
            if (name === '' || !group_id) {
                alert('Please enter a name and select a group.');
                return;
            }
            
            window.jQuery.ajax({
                url: '<?php echo site_url("accounts/receiptvoucher/quick_add_ledger_ajax"); ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    group_id: group_id,
                    name: name,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        // Append to options so future dynamic rows have it
                        var optHtml = '<option value="' + res.id + '">' + res.name + '</option>';
                        receipt_ledger_opts += optHtml;
                        payment_ledger_opts += optHtml;
                        journal_ledger_opts += optHtml;
                        
                        // Synced update: append to all existing ledger selects
                        window.jQuery('select.ledger_select').each(function() {
                            var newOpt = new Option(res.name, res.id, false, false);
                            window.jQuery(this).append(newOpt);
                        });
                        
                        // Select it in the active dropdown that triggered it
                        if (activeLedgerSelect && activeLedgerSelect.length) {
                            activeLedgerSelect.val(res.id).trigger('change');
                        }
                        
                        window.jQuery('#addLedgerModal').modal('hide');
                    } else {
                        alert(res.error);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        };

        window.submitNewBank = function() {
            var name = window.jQuery('#new_bank_name').val().trim();
            if (name === '') {
                alert('Please enter a bank name.');
                return;
            }
            
            var newOption1 = new Option(name, name);
            var newOption2 = new Option(name, name);
            var newOption3 = new Option(name, name);
            
            window.jQuery('#receipt_bank_name').append(newOption1);
            window.jQuery('#payment_bank_name').append(newOption2);
            window.jQuery('#pm_bank_id').append(newOption3);
            
            window.jQuery('#addBankModal').modal('hide');
        };

        window.submitNewSupplier = function() {
            var name = window.jQuery('#new_supplier_name').val().trim();
            var mobile = window.jQuery('#new_supplier_mobile').val().trim();
            if (name === '') {
                alert('Please enter a supplier name.');
                return;
            }
            
            window.jQuery.ajax({
                url: '<?php echo site_url("accounts/purchaseentry/addsupplier"); ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    name: name,
                    mobile: mobile,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        var newOption = new Option(res.name, res.id, true, true);
                        window.jQuery('#purchase_supplier_ledger_id').append(newOption).trigger('change');
                        window.jQuery('#addSupplierModal').modal('hide');
                    } else {
                        alert(res.error);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        };

        // Global Autocomplete Voucher Search logic
        (function() {
            var $search = window.jQuery('#accGlobalSearch');
            var $dropdown = window.jQuery('#accSearchDropdown');
            var searchTimeout = null;

            $search.on('input', function() {
                var query = $search.val().trim();
                clearTimeout(searchTimeout);

                if (query.length < 1) {
                    $dropdown.html('').hide();
                    return;
                }

                searchTimeout = setTimeout(function() {
                    window.jQuery.ajax({
                        url: '<?php echo site_url("accounts/dashboard/search_vouchers_ajax"); ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            query: query,
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                        },
                        success: function(response) {
                            if (response.status === 'success' && response.results.length > 0) {
                                var html = '';
                                response.results.forEach(function(item) {
                                    var badgeClass = 'badge-' + item.type;
                                    var typeLabel = item.type.toUpperCase();
                                    
                                    html += '<a href="' + item.url + '" target="_blank" class="acc-search-item">';
                                    html += '  <div class="acc-search-item-info">';
                                    html += '    <div class="acc-search-item-no">' + item.no + '</div>';
                                    html += '    <div class="acc-search-item-meta">' + item.ledgers + ' (' + item.date + ')</div>';
                                    html += '  </div>';
                                    html += '  <div class="acc-search-item-amount">';
                                    html += '    <span>' + '<?php echo $curr; ?>' + parseFloat(item.amount).toFixed(2) + '</span>';
                                    html += '    <span class="acc-search-item-badge ' + badgeClass + '">' + typeLabel + '</span>';
                                    html += '  </div>';
                                    html += '</a>';
                                });
                                $dropdown.html(html).show();
                            } else {
                                $dropdown.html('<div style="padding:12px 16px; text-align:center; color:#64748b; font-size:12px;"><i class="fa fa-info-circle"></i> No vouchers found</div>').show();
                            }
                        },
                        error: function() {
                            $dropdown.html('<div style="padding:12px 16px; text-align:center; color:#ef4444; font-size:12px;"><i class="fa fa-exclamation-triangle"></i> Search failed</div>').show();
                        }
                    });
                }, 300);
            });

            // Close dropdown when clicking outside
            window.jQuery(document).on('click', function(e) {
                if (!window.jQuery(e.target).closest('.acc-global-search-container').length) {
                    $dropdown.hide();
                }
            });

            $search.on('focus', function() {
                if ($dropdown.html() !== '') {
                    $dropdown.show();
                }
            });
        })();

        console.log("Quick Actions Offcanvas Sidebars initialized successfully.");
    } catch (e) {
        console.error("Quick Actions Offcanvas Sidebars initialization failed:", e);
    }

})();
</script>
