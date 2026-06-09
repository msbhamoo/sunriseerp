<?php
$views = [
    'receipt_voucher/index.php',
    'payment_voucher/index.php',
    'contra_voucher/index.php',
    'journal_voucher/index.php',
    'purchase_entry/index.php'
];

$script = <<<EOT

<script>
$(document).ready(function() {
    function fetchLedgerBalance(\$select) {
        var ledger_id = \$select.val();
        var \$badgeContainer = \$select.closest('.input-group').siblings('.cr_ledger_balance, .dr_ledger_balance, .ledger_balance_badge');
        if (\$badgeContainer.length === 0) {
            \$badgeContainer = \$select.siblings('.cr_ledger_balance, .dr_ledger_balance, .ledger_balance_badge');
        }
        if (\$badgeContainer.length === 0) {
            \$badgeContainer = \$('<div class="ledger_balance_badge" style="font-size:11px; font-weight:600; margin-top:2px;"></div>');
            if (\$select.parent('.input-group').length > 0) {
                \$select.parent('.input-group').after(\$badgeContainer);
            } else {
                \$select.after(\$badgeContainer);
            }
        }

        if (!ledger_id) {
            \$badgeContainer.html('');
            return;
        }

        \$badgeContainer.html('<i class="fa fa-spinner fa-spin"></i> Fetching...');
        
        \$.ajax({
            url: base_url + 'accounts/ledgermaster/get_balance',
            type: 'POST',
            data: { id: ledger_id },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    var color = (res.type === 'Cr') ? '#10b981' : '#ef4444'; 
                    var badge = '<span style="color:' + color + '">Bal: ' + parseFloat(res.balance).toFixed(2) + ' ' + res.type + '</span>';
                    \$badgeContainer.html(badge);
                } else {
                    \$badgeContainer.html('<span style="color:#ef4444">Error</span>');
                }
            }
        });
    }

    \$(document).on('change', 'select[name="ledger_id[]"], select[name="cr_ledger_id"], select[name="dr_ledger_id"], select[name="payment_mode_id"], select[name="supplier_ledger_id"], select[name="expense_ledger_id[]"]', function() {
        fetchLedgerBalance(\$(this));
    });

    setTimeout(function() {
        \$('select[name="ledger_id[]"], select[name="cr_ledger_id"], select[name="dr_ledger_id"], select[name="payment_mode_id"], select[name="supplier_ledger_id"], select[name="expense_ledger_id[]"]').each(function() {
            if (\$(this).val()) {
                fetchLedgerBalance(\$(this));
            }
        });
    }, 500);
});
</script>
EOT;

$base_dir = "c:\\wamp64\\www\\sunriselms\\addon\\accounts\\installer\\install\\files\\application\\views\\accounts\\";

foreach ($views as $view) {
    $path = $base_dir . $view;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        // prevent double inject
        if (strpos($content, 'fetchLedgerBalance') === false) {
            file_put_contents($path, $content . $script);
        }
    }
}

echo "Badges injected.";
