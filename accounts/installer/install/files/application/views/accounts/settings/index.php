<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calculator"></i> <?php echo $this->lang->line('accounts'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-cogs"></i> Accounts Integration Settings</h3>
                    </div>

                    <form id="form1" action="<?php echo site_url('accounts/settings') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                                ?>
                            <?php } ?>
                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="page-header mt0">Fee Collection Integration</h4>
                                    <div class="alert alert-info" style="background-color: #e0f2fe !important; border-color: #bae6fd !important; color: #0369a1 !important;">
                                        <h4><i class="fa fa-info-circle"></i> How does Fee Sync work?</h4>
                                        <p>When a student pays their fee, this system automatically transfers that fee record into your Accounts module without any manual data entry. You just need to tell the system:</p>
                                        <ul style="margin-top: 5px;">
                                            <li><strong>Where does the money go?</strong> (e.g., Main Bank Account or Cash Account)</li>
                                            <li><strong>What type of income is it?</strong> (e.g., Tuition Fees Collection)</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Enable Auto Fee Voucher Sync</label>
                                        <div class="material-switch pull-right">
                                            <input id="enable_fee_sync" name="enable_fee_sync" type="checkbox" value="1" <?php echo set_checkbox('enable_fee_sync', '1', (isset($settings['enable_fee_sync']) && $settings['enable_fee_sync'] == 1)); ?> />
                                            <label for="enable_fee_sync" class="label-success"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" id="fee_mapping_section" style="<?php echo (isset($settings['enable_fee_sync']) && $settings['enable_fee_sync'] == 1) ? '' : 'display:none;'; ?>">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fee_receipt_ledger_id"><i class="fa fa-money text-success"></i> Cash Receiving Account <small class="text-danger"> *</small></label>
                                        <select autofocus="" id="fee_receipt_ledger_id" name="fee_receipt_ledger_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($bank_cash_ledgers as $ledger) {
                                                ?>
                                                <option value="<?php echo $ledger['id'] ?>"<?php
                                                if (set_value('fee_receipt_ledger_id', isset($settings['fee_receipt_ledger_id']) ? $settings['fee_receipt_ledger_id'] : '') == $ledger['id']) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $ledger['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('fee_receipt_ledger_id'); ?></span>
                                        <p class="text-muted small">When fee is paid via <strong>Cash</strong>, money goes to this account (e.g. Cash Account).</p>
                                        
                                        <div class="checkbox mt10">
                                            <label>
                                                <input type="checkbox" name="run_bulk_fee_sync" value="1"> <strong>Also sync previous un-synced fee deposits immediately</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fee_bank_receipt_ledger_id"><i class="fa fa-university text-primary"></i> Bank Receiving Account (Non-Cash) <small class="text-muted">(Optional)</small></label>
                                        <select id="fee_bank_receipt_ledger_id" name="fee_bank_receipt_ledger_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?> — Same as Cash Account</option>
                                            <?php
                                            foreach ($bank_cash_ledgers as $ledger) {
                                                ?>
                                                <option value="<?php echo $ledger['id'] ?>"<?php
                                                if (set_value('fee_bank_receipt_ledger_id', isset($settings['fee_bank_receipt_ledger_id']) ? $settings['fee_bank_receipt_ledger_id'] : '') == $ledger['id']) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $ledger['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('fee_bank_receipt_ledger_id'); ?></span>
                                        <p class="text-muted small">When fee is paid via <strong>UPI, Cheque, DD, Card, Bank Transfer</strong>, money goes to this account (e.g. Main Bank Account). If left empty, it uses the Cash Account for all modes.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fee_income_ledger_id">What type of Income is this? (Income Ledger) <small class="text-danger"> *</small></label>
                                        <select id="fee_income_ledger_id" name="fee_income_ledger_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($income_ledgers as $ledger) {
                                                ?>
                                                <option value="<?php echo $ledger['id'] ?>"<?php
                                                if (set_value('fee_income_ledger_id', isset($settings['fee_income_ledger_id']) ? $settings['fee_income_ledger_id'] : '') == $ledger['id']) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $ledger['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('fee_income_ledger_id'); ?></span>
                                        <p class="text-muted small">Usually an income ledger like 'Tuition Fees Collection' or 'School Fees'.</p>
                                </div>
                            </div>
                            
                            <div class="row" id="fee_advanced_mapping_section" style="<?php echo (isset($settings['enable_fee_sync']) && $settings['enable_fee_sync'] == 1) ? '' : 'display:none;'; ?>">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gateway_clearing_ledger_id">Online Payment Gateway Account (Optional)</label>
                                        <select id="gateway_clearing_ledger_id" name="gateway_clearing_ledger_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($bank_cash_ledgers as $ledger) {
                                                ?>
                                                <option value="<?php echo $ledger['id'] ?>"<?php
                                                if (set_value('gateway_clearing_ledger_id', isset($settings['gateway_clearing_ledger_id']) ? $settings['gateway_clearing_ledger_id'] : '') == $ledger['id']) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $ledger['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('gateway_clearing_ledger_id'); ?></span>
                                        <p class="text-muted small">If fees are paid online, the money temporarily stays in a gateway account before reaching your bank.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fee_discount_expense_ledger_id">Discount / Concession Expense (Optional)</label>
                                        <select id="fee_discount_expense_ledger_id" name="fee_discount_expense_ledger_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($expense_ledgers as $ledger) {
                                                ?>
                                                <option value="<?php echo $ledger['id'] ?>"<?php
                                                if (set_value('fee_discount_expense_ledger_id', isset($settings['fee_discount_expense_ledger_id']) ? $settings['fee_discount_expense_ledger_id'] : '') == $ledger['id']) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $ledger['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('fee_discount_expense_ledger_id'); ?></span>
                                        <p class="text-muted small">If you give discounts, which expense ledger should bear the cost of the discount?</p>
                                    </div>
                                </div>
                            </div>

                            <hr/>

                            <div class="row mt10">
                                <div class="col-md-12">
                                    <h4 class="page-header mt0">Payroll Integration</h4>
                                    <p class="text-muted">When enabled, a Payment Voucher is automatically created in Accounts whenever a staff salary is paid.</p>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Enable Auto Payroll Voucher Sync</label>
                                        <div class="material-switch pull-right">
                                            <input id="enable_payroll_sync" name="enable_payroll_sync" type="checkbox" value="1" <?php echo set_checkbox('enable_payroll_sync', '1', (isset($settings['enable_payroll_sync']) && $settings['enable_payroll_sync'] == 1)); ?> />
                                            <label for="enable_payroll_sync" class="label-success"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="payroll_mapping_section" style="<?php echo (isset($settings['enable_payroll_sync']) && $settings['enable_payroll_sync'] == 1) ? '' : 'display:none;'; ?>">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payroll_expense_ledger_id">Payroll Expense Ledger (Debit) <small class="text-danger"> *</small></label>
                                        <select id="payroll_expense_ledger_id" name="payroll_expense_ledger_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($expense_ledgers as $ledger) {
                                                ?>
                                                <option value="<?php echo $ledger['id'] ?>"<?php
                                                if (set_value('payroll_expense_ledger_id', isset($settings['payroll_expense_ledger_id']) ? $settings['payroll_expense_ledger_id'] : '') == $ledger['id']) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $ledger['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('payroll_expense_ledger_id'); ?></span>
                                        <p class="text-muted small">The Expense ledger that represents salary payouts.</p>
                                        
                                        <div class="checkbox mt10">
                                            <label>
                                                <input type="checkbox" name="run_bulk_payroll_sync" value="1"> <strong>Also sync previous un-synced payroll immediately</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payroll_payment_ledger_id">Payroll Payment Ledger (Credit) <small class="text-danger"> *</small></label>
                                        <select id="payroll_payment_ledger_id" name="payroll_payment_ledger_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($bank_cash_ledgers as $ledger) {
                                                ?>
                                                <option value="<?php echo $ledger['id'] ?>"<?php
                                                if (set_value('payroll_payment_ledger_id', isset($settings['payroll_payment_ledger_id']) ? $settings['payroll_payment_ledger_id'] : '') == $ledger['id']) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $ledger['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('payroll_payment_ledger_id'); ?></span>
                                        <p class="text-muted small">The Bank or Cash ledger where the money is paid from.</p>
                                    </div>
                                </div>
                            </div>

                            <hr/>

                            <div class="row mt10">
                                <div class="col-md-12">
                                    <h4 class="page-header mt0">Native Expense Sync</h4>
                                    <p class="text-muted">When enabled, any expense added in the core Smart School module automatically generates a Payment Voucher in Accounts.</p>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Enable Native Expense Auto-Sync</label>
                                        <div class="material-switch pull-right">
                                            <input id="enable_expense_sync" name="enable_expense_sync" type="checkbox" value="1" <?php echo set_checkbox('enable_expense_sync', '1', (isset($settings['enable_expense_sync']) && $settings['enable_expense_sync'] == 1)); ?> />
                                            <label for="enable_expense_sync" class="label-success"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="expense_mapping_section" style="<?php echo (isset($settings['enable_expense_sync']) && $settings['enable_expense_sync'] == 1) ? '' : 'display:none;'; ?>">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expense_payment_ledger_id">Expense Default Payment Ledger (Credit) <small class="text-danger"> *</small></label>
                                        <select id="expense_payment_ledger_id" name="expense_payment_ledger_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($bank_cash_ledgers as $ledger) {
                                                ?>
                                                <option value="<?php echo $ledger['id'] ?>"<?php
                                                if (set_value('expense_payment_ledger_id', isset($settings['expense_payment_ledger_id']) ? $settings['expense_payment_ledger_id'] : '') == $ledger['id']) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $ledger['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('expense_payment_ledger_id'); ?></span>
                                        <p class="text-muted small">The Cash/Bank ledger to credit when an expense is recorded.</p>
                                    </div>
                                </div>
                            </div>

                            <hr/>

                            <div class="row mt10">
                                <div class="col-md-12">
                                    <h4 class="page-header mt0">Native Income Sync</h4>
                                    <p class="text-muted">When enabled, any income added in the core Smart School module automatically generates a Receipt Voucher in Accounts.</p>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Enable Native Income Auto-Sync</label>
                                        <div class="material-switch pull-right">
                                            <input id="enable_income_sync" name="enable_income_sync" type="checkbox" value="1" <?php echo set_checkbox('enable_income_sync', '1', (isset($settings['enable_income_sync']) && $settings['enable_income_sync'] == 1)); ?> />
                                            <label for="enable_income_sync" class="label-success"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="income_mapping_section" style="<?php echo (isset($settings['enable_income_sync']) && $settings['enable_income_sync'] == 1) ? '' : 'display:none;'; ?>">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="income_receipt_ledger_id">Income Default Receipt Ledger (Debit) <small class="text-danger"> *</small></label>
                                        <select id="income_receipt_ledger_id" name="income_receipt_ledger_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($bank_cash_ledgers as $ledger) {
                                                ?>
                                                <option value="<?php echo $ledger['id'] ?>"<?php
                                                if (set_value('income_receipt_ledger_id', isset($settings['income_receipt_ledger_id']) ? $settings['income_receipt_ledger_id'] : '') == $ledger['id']) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $ledger['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('income_receipt_ledger_id'); ?></span>
                                        <p class="text-muted small">The Cash/Bank ledger to debit when income is recorded.</p>
                                    </div>
                                </div>
                            </div>

                            <hr/>

                            <div class="row mt10">
                                <div class="col-md-12">
                                    <h4 class="page-header mt0"><i class="fa fa-list-ol"></i> Voucher Prefixes</h4>
                                    <p class="text-muted">Customize the prefix letters that appear before the auto-generated voucher numbers (e.g. PAY-0001).</p>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="payment_prefix">Payment Prefix</label>
                                        <input type="text" id="payment_prefix" name="payment_prefix" class="form-control" value="<?php echo set_value('payment_prefix', isset($sequences['payment_prefix']) ? $sequences['payment_prefix'] : 'PAY-'); ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="payment_series">Payment Series</label>
                                        <input type="number" id="payment_series" name="payment_series" class="form-control" value="<?php echo set_value('payment_series', isset($sequences['payment_series']) ? $sequences['payment_series'] : '0'); ?>" />
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="receipt_prefix">Receipt Prefix</label>
                                        <input type="text" id="receipt_prefix" name="receipt_prefix" class="form-control" value="<?php echo set_value('receipt_prefix', isset($sequences['receipt_prefix']) ? $sequences['receipt_prefix'] : 'REC-'); ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="receipt_series">Receipt Series</label>
                                        <input type="number" id="receipt_series" name="receipt_series" class="form-control" value="<?php echo set_value('receipt_series', isset($sequences['receipt_series']) ? $sequences['receipt_series'] : '0'); ?>" />
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="contra_prefix">Contra Prefix</label>
                                        <input type="text" id="contra_prefix" name="contra_prefix" class="form-control" value="<?php echo set_value('contra_prefix', isset($sequences['contra_prefix']) ? $sequences['contra_prefix'] : 'CON-'); ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="contra_series">Contra Series</label>
                                        <input type="number" id="contra_series" name="contra_series" class="form-control" value="<?php echo set_value('contra_series', isset($sequences['contra_series']) ? $sequences['contra_series'] : '0'); ?>" />
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="journal_prefix">Journal Prefix</label>
                                        <input type="text" id="journal_prefix" name="journal_prefix" class="form-control" value="<?php echo set_value('journal_prefix', isset($sequences['journal_prefix']) ? $sequences['journal_prefix'] : 'JOU-'); ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="journal_series">Journal Series</label>
                                        <input type="number" id="journal_series" name="journal_series" class="form-control" value="<?php echo set_value('journal_series', isset($sequences['journal_series']) ? $sequences['journal_series'] : '0'); ?>" />
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="purchase_prefix">Purchase Prefix</label>
                                        <input type="text" id="purchase_prefix" name="purchase_prefix" class="form-control" value="<?php echo set_value('purchase_prefix', isset($sequences['purchase_prefix']) ? $sequences['purchase_prefix'] : 'PUR-'); ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="purchase_series">Purchase Series</label>
                                        <input type="number" id="purchase_series" name="purchase_series" class="form-control" value="<?php echo set_value('purchase_series', isset($sequences['purchase_series']) ? $sequences['purchase_series'] : '0'); ?>" />
                                    </div>
                                </div>
                            </div>

                            <hr/>
                            <div class="row mt10">
                                <div class="col-md-12">
                                    <h4 class="page-header mt0"><i class="fa fa-pencil-square-o"></i> Print Voucher Signatures &amp; Autographed Names</h4>
                                    <p class="text-muted">Customize the labels, authorized names, and upload signature photos for the three placeholders displayed at the bottom of your transaction vouchers. If a signature photo is not uploaded, a beautiful handwritten signature will be dynamically generated based on the Authorized Name!</p>
                                </div>
                                
                                <!-- Signature 1 (Left) -->
                                <div class="col-md-4">
                                    <div class="box box-solid" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                                        <div class="box-header with-border" style="background:#f8fafc; font-weight:700;">Signature 1 (Left Column)</div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="signature_1_label">Signature Label</label>
                                                <input type="text" id="signature_1_label" name="signature_1_label" class="form-control" value="<?php echo set_value('signature_1_label', isset($settings['signature_1_label']) ? $settings['signature_1_label'] : 'Prepared By'); ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="signature_1_name">Authorized Name (for handwritten signature)</label>
                                                <input type="text" id="signature_1_name" name="signature_1_name" class="form-control" placeholder="e.g. John Doe" value="<?php echo set_value('signature_1_name', isset($settings['signature_1_name']) ? $settings['signature_1_name'] : ''); ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="signature_1_photo">Upload Signature Image <small class="text-muted">(Optional)</small></label>
                                                <input type="file" id="signature_1_photo" name="signature_1_photo" class="form-control" accept="image/*" />
                                                <?php if (!empty($settings['signature_1_photo'])): ?>
                                                    <div style="margin-top: 10px; border: 1px dashed #cbd5e1; padding: 10px; text-align: center; border-radius: 6px; background: #fafafa;">
                                                        <img src="<?php echo base_url('uploads/accounts/signatures/' . $settings['signature_1_photo']); ?>" style="max-height: 50px; max-width: 100%;" /><br>
                                                        <div class="checkbox" style="margin-bottom: 0; margin-top: 5px;">
                                                            <label style="font-size: 11px; font-weight: 600; color: #ef4444;">
                                                                <input type="checkbox" name="delete_signature_1_photo" value="1" /> Delete existing photo
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Signature 2 (Middle) -->
                                <div class="col-md-4">
                                    <div class="box box-solid" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                                        <div class="box-header with-border" style="background:#f8fafc; font-weight:700;">Signature 2 (Middle Column)</div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="signature_2_label">Signature Label</label>
                                                <input type="text" id="signature_2_label" name="signature_2_label" class="form-control" value="<?php echo set_value('signature_2_label', isset($settings['signature_2_label']) ? $settings['signature_2_label'] : 'Checked By'); ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="signature_2_name">Authorized Name (for handwritten signature)</label>
                                                <input type="text" id="signature_2_name" name="signature_2_name" class="form-control" placeholder="e.g. Jane Smith" value="<?php echo set_value('signature_2_name', isset($settings['signature_2_name']) ? $settings['signature_2_name'] : ''); ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="signature_2_photo">Upload Signature Image <small class="text-muted">(Optional)</small></label>
                                                <input type="file" id="signature_2_photo" name="signature_2_photo" class="form-control" accept="image/*" />
                                                <?php if (!empty($settings['signature_2_photo'])): ?>
                                                    <div style="margin-top: 10px; border: 1px dashed #cbd5e1; padding: 10px; text-align: center; border-radius: 6px; background: #fafafa;">
                                                        <img src="<?php echo base_url('uploads/accounts/signatures/' . $settings['signature_2_photo']); ?>" style="max-height: 50px; max-width: 100%;" /><br>
                                                        <div class="checkbox" style="margin-bottom: 0; margin-top: 5px;">
                                                            <label style="font-size: 11px; font-weight: 600; color: #ef4444;">
                                                                <input type="checkbox" name="delete_signature_2_photo" value="1" /> Delete existing photo
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Signature 3 (Right) -->
                                <div class="col-md-4">
                                    <div class="box box-solid" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                                        <div class="box-header with-border" style="background:#f8fafc; font-weight:700;">Signature 3 (Right Column)</div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="signature_3_label">Signature Label</label>
                                                <input type="text" id="signature_3_label" name="signature_3_label" class="form-control" value="<?php echo set_value('signature_3_label', isset($settings['signature_3_label']) ? $settings['signature_3_label'] : 'Authorized Signatory'); ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="signature_3_name">Authorized Name (for handwritten signature)</label>
                                                <input type="text" id="signature_3_name" name="signature_3_name" class="form-control" placeholder="e.g. Director" value="<?php echo set_value('signature_3_name', isset($settings['signature_3_name']) ? $settings['signature_3_name'] : ''); ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="signature_3_photo">Upload Signature Image <small class="text-muted">(Optional)</small></label>
                                                <input type="file" id="signature_3_photo" name="signature_3_photo" class="form-control" accept="image/*" />
                                                <?php if (!empty($settings['signature_3_photo'])): ?>
                                                    <div style="margin-top: 10px; border: 1px dashed #cbd5e1; padding: 10px; text-align: center; border-radius: 6px; background: #fafafa;">
                                                        <img src="<?php echo base_url('uploads/accounts/signatures/' . $settings['signature_3_photo']); ?>" style="max-height: 50px; max-width: 100%;" /><br>
                                                        <div class="checkbox" style="margin-bottom: 0; margin-top: 5px;">
                                                            <label style="font-size: 11px; font-weight: 600; color: #ef4444;">
                                                                <input type="checkbox" name="delete_signature_3_photo" value="1" /> Delete existing photo
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr/>

                            <div class="row mt10">
                                <div class="col-md-12">
                                    <h4 class="page-header mt0"><i class="fa fa-refresh"></i> Bulk Sync Historical Data</h4>
                                    <p class="text-muted">Use these tools to manually synchronize old data that was created before the Accounts module was installed or before sync was enabled.</p>
                                </div>
                                
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered">
                                        <tbody>
                                            <tr>
                                                <td><b>Student Fees Collection</b><br><small class="text-muted">Finds all un-synced fee deposits and generates Receipt Vouchers.</small></td>
                                                <td class="text-right"><button type="button" class="btn btn-primary btn-sm btn-bulk-sync" data-type="fees"><i class="fa fa-refresh"></i> Sync Un-synced Fees</button></td>
                                            </tr>
                                            <tr>
                                                <td><b>Staff Payroll</b><br><small class="text-muted">Finds all un-synced paid payslips and generates Payment Vouchers.</small></td>
                                                <td class="text-right"><button type="button" class="btn btn-primary btn-sm btn-bulk-sync" data-type="payroll"><i class="fa fa-refresh"></i> Sync Un-synced Payroll</button></td>
                                            </tr>
                                            <tr>
                                                <td><b>Native Expenses</b><br><small class="text-muted">Finds all un-synced core expenses and generates Payment Vouchers.</small></td>
                                                <td class="text-right"><button type="button" class="btn btn-primary btn-sm btn-bulk-sync" data-type="expenses"><i class="fa fa-refresh"></i> Sync Un-synced Expenses</button></td>
                                            </tr>
                                            <tr>
                                                <td><b>Native Incomes</b><br><small class="text-muted">Finds all un-synced core incomes and generates Receipt Vouchers.</small></td>
                                                <td class="text-right"><button type="button" class="btn btn-primary btn-sm btn-bulk-sync" data-type="income"><i class="fa fa-refresh"></i> Sync Un-synced Incomes</button></td>
                                            </tr>
                                            <tr>
                                                <td><b>Retry Failed Queue</b><br><small class="text-muted">Retries pending/failed sync queue items using the built-in retry processor.</small></td>
                                                <td class="text-right"><button type="button" class="btn btn-warning btn-sm btn-bulk-sync" data-type="queue_retry"><i class="fa fa-repeat"></i> Retry Sync Queue</button></td>
                                            </tr>
                                            <tr>
                                                <td><b>Reconciliation Check</b><br><small class="text-muted">Runs non-destructive checks for orphan vouchers, queue status, and un-synced fees/payroll.</small></td>
                                                <td class="text-right"><button type="button" class="btn btn-info btn-sm btn-bulk-sync" data-type="reconciliation"><i class="fa fa-search"></i> Run Reconciliation</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#enable_fee_sync').change(function() {
            if($(this).is(":checked")) {
                $('#fee_mapping_section').slideDown();
                $('#fee_advanced_mapping_section').slideDown();
            } else {
                $('#fee_mapping_section').slideUp();
                $('#fee_advanced_mapping_section').slideUp();
            }
        });

        $('#enable_payroll_sync').change(function() {
            if($(this).is(":checked")) {
                $('#payroll_mapping_section').slideDown();
            } else {
                $('#payroll_mapping_section').slideUp();
            }
        });

        $('#enable_expense_sync').change(function() {
            if($(this).is(":checked")) {
                $('#expense_mapping_section').slideDown();
            } else {
                $('#expense_mapping_section').slideUp();
            }
        });

        $('#enable_income_sync').change(function() {
            if($(this).is(":checked")) {
                $('#income_mapping_section').slideDown();
            } else {
                $('#income_mapping_section').slideUp();
            }
        });

        // Bulk Sync Logic
        $('.btn-bulk-sync').click(function() {
            var btn = $(this);
            var type = btn.data('type');
            
            if(confirm('Are you sure you want to run the bulk sync? Depending on the data size, this might take a few minutes.')) {
                var originalText = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> Syncing...').prop('disabled', true);
                
                $.ajax({
                    url: '<?php echo site_url("accounts/settings/bulksync"); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {type: type},
                    success: function(response) {
                        if(response.status) {
                            alert(response.message);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('A server error occurred during sync.');
                    },
                    complete: function() {
                        btn.html(originalText).prop('disabled', false);
                    }
                });
            }
        });
    });
</script>
