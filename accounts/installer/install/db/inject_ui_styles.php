<?php
$views = [
    'reports/daybook.php',
    'reports/cashbook.php',
    'reports/bankbook.php',
    'reports/statement.php',
    'reports/outstanding.php',
    'reports/trialbalance.php',
    'reports/profitloss.php',
    'reports/balancesheet.php',
    'reports/expincome_type.php',
    'receipt_voucher/index.php',
    'payment_voucher/index.php',
    'contra_voucher/index.php',
    'journal_voucher/index.php',
    'purchase_entry/index.php'
];

$script = <<<EOT

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
            var text = \$(this).text().toLowerCase();
            if (text.indexOf('amount') !== -1 || text.indexOf('debit') !== -1 || text.indexOf('credit') !== -1 || text.indexOf('balance') !== -1 || text.indexOf('total') !== -1) {
                \$(this).css('text-align', 'right');
                \$(this).closest('table').find('tr').each(function() {
                    var \$td = \$(this).children('td').eq(index);
                    if (\$td.length && \$td.text().match(/[0-9]/)) {
                        \$td.css({
                            'text-align': 'right',
                            'font-family': '"Consolas", "Courier New", monospace',
                            'font-weight': '600'
                        });
                    }
                });
            }
        });

        $('table td').each(function() {
            var txt = \$.trim(\$(this).text()).toLowerCase();
            if (txt === 'posted') {
                \$(this).html('<span class="acc-status-posted">Posted</span>');
            } else if (txt === 'reversed') {
                \$(this).html('<span class="acc-status-reversed">Reversed</span>');
            } else if (txt === 'draft') {
                \$(this).html('<span class="acc-status-draft">Draft</span>');
            }
        });
    }, 1000); // 1s delay to let datatables render
    
    // Also attach to DataTables draw event if available
    $(document).on('draw.dt', function () {
        $('table td').each(function() {
            var txt = \$.trim(\$(this).text()).toLowerCase();
            if (txt === 'posted') {
                \$(this).html('<span class="acc-status-posted">Posted</span>');
            } else if (txt === 'reversed') {
                \$(this).html('<span class="acc-status-reversed">Reversed</span>');
            } else if (txt === 'draft') {
                \$(this).html('<span class="acc-status-draft">Draft</span>');
            }
        });
        
        $('table th').each(function(index) {
            var text = \$(this).text().toLowerCase();
            if (text.indexOf('amount') !== -1 || text.indexOf('debit') !== -1 || text.indexOf('credit') !== -1 || text.indexOf('balance') !== -1 || text.indexOf('total') !== -1) {
                \$(this).css('text-align', 'right');
                \$(this).closest('table').find('tr').each(function() {
                    var \$td = \$(this).children('td').eq(index);
                    if (\$td.length && \$td.text().match(/[0-9]/)) {
                        \$td.css({
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
EOT;

$base_dir = "c:\\wamp64\\www\\sunriselms\\addon\\accounts\\installer\\install\\files\\application\\views\\accounts\\";

foreach ($views as $view) {
    $path = $base_dir . $view;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        if (strpos($content, 'acc-status-posted') === false) {
            file_put_contents($path, $content . $script);
        }
    }
}

echo "UI styling injected.";
