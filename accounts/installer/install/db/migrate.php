<?php
$conn = new mysqli('localhost', 'adminsips', '(Admin@123)', 'sips');
$conn->query('ALTER TABLE acc_voucher_items ADD INDEX idx_ledger_debit_credit (ledger_id, debit_amount, credit_amount)');
echo "Migration done";
