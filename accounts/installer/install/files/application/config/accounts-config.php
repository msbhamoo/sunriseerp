<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['addon_prod'] = 'smart_school_accounts_module';
$config['addon_ver'] = '1.0';

// Configuration specific to Accounts Module
$config['accounts_payment_modes'] = array(
    'Cash' => 'Cash',
    'Cheque' => 'Cheque',
    'DD' => 'DD',
    'Bank Transfer' => 'Bank Transfer',
    'UPI' => 'UPI',
    'Card' => 'Card'
);
