<style>
    .offcanvas-right {
        position: fixed;
        top: 0;
        right: -600px;
        width: 600px;
        height: 100vh;
        background: #fff;
        z-index: 1050;
        box-shadow: -2px 0 8px rgba(0,0,0,0.1);
        transition: right 0.3s ease;
        overflow-y: auto;
    }
    .offcanvas-right.open {
        right: 0;
    }
    .offcanvas-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.5);
        z-index: 1040;
        display: none;
    }
    .offcanvas-overlay.open {
        display: block;
    }
    .offcanvas-header {
        padding: 15px;
        border-bottom: 1px solid #e5e5e5;
        background: #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .offcanvas-body {
        padding: 15px;
    }
</style>

<div class="offcanvas-overlay" id="voucherOverlay"></div>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calculator"></i> <?php echo $this->lang->line('accounts'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            
            <?php if(!isset($id)) { ?>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('contra_voucher_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('acc_contra_voucher', 'can_add')) { ?>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="toggleOffcanvas()"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_contra_voucher'); ?></button>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover contra-voucher-list">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('voucher_no'); ?></th>
                                        <th>Transfer Details</th>
                                        <th><?php echo $this->lang->line('amount'); ?></th>
                                        <th>More Info</th>
                                        <th>Narration</th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if ($this->rbac->hasPrivilege('acc_contra_voucher', 'can_add') || (isset($id) && $this->rbac->hasPrivilege('acc_contra_voucher', 'can_edit'))) { ?>
                <div class="offcanvas-right" id="voucherOffcanvas">
                    <div class="offcanvas-header">
                        <h4 class="m-0"><b><?php echo isset($id) ? $this->lang->line('edit') : $this->lang->line('add_contra_voucher'); ?></b></h4>
                        <button type="button" class="close" onclick="toggleOffcanvas()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="<?php echo isset($id) ? site_url('accounts/contravoucher/edit/' . $id) : site_url('accounts/contravoucher/index') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="offcanvas-body">
                            <?php if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                            } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('voucher_no'); ?></label><small class="req"> *</small>
                                            <input name="voucher_no" type="text" class="form-control" value="<?php echo set_value('voucher_no', isset($voucher) ? $voucher['voucher_no'] : $next_voucher_no); ?>" readonly/>
                                            <span class="text-danger"><?php echo form_error('voucher_no'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <?php $v_date = (isset($voucher)) ? date($this->customlib->getSchoolDateFormat(), strtotime($voucher['voucher_date'])) : date($this->customlib->getSchoolDateFormat()); ?>
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('voucher_date'); ?></label><small class="req"> *</small>
                                            <input name="voucher_date" type="text" class="form-control date" value="<?php echo set_value('voucher_date', $v_date); ?>" readonly="readonly" />
                                            <span class="text-danger"><?php echo form_error('voucher_date'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ref No.</label>
                                            <input name="reference_no" id="reference_no" type="text" class="form-control" value="<?php echo set_value('reference_no', isset($voucher) ? $voucher['reference_no'] : ''); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Transaction Type</label><small class="req"> *</small>
                                            <?php
                                                // Determine transaction type and dr/cr ledgers if editing
                                                $transaction_type = 'Cash To Bank';
                                                $dr_ledger_id = '';
                                                $cr_ledger_id = '';
                                                if (isset($voucher) && !empty($voucher['items'])) {
                                                    foreach ($voucher['items'] as $item) {
                                                        if ($item['debit_amount'] > 0) $dr_ledger_id = $item['ledger_id'];
                                                        if ($item['credit_amount'] > 0) $cr_ledger_id = $item['ledger_id'];
                                                    }
                                                    // Determine type based on group_ids of these ledgers. Let's just guess based on Dr/Cr for edit.
                                                    // In reality, bank group = 2, cash group = 1
                                                }
                                            ?>
                                            <select name="transaction_type" id="transaction_type" class="form-control" required>
                                                <option value="Cash To Bank">Cash To Bank</option>
                                                <option value="Bank To Cash">Bank To Cash</option>
                                                <option value="Bank To Bank">Bank To Bank</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr style="margin-top:5px; margin-bottom:15px;">
                                <div class="row">
                                    <div class="col-md-12" id="credit_account_wrapper">
                                        <div class="form-group">
                                            <label id="lbl_cr_account">Withdraw From (Credit)</label><small class="req"> *</small>
                                            <select name="cr_ledger_id" id="cr_ledger_id" class="form-control ledger_select" required>
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach($cash_bank_ledgers as $l) { ?>
                                                    <option value="<?php echo $l['id']; ?>" data-group="<?php echo $l['group_id']; ?>" data-system-group="<?php echo $l['system_name']; ?>" <?php if($l['id'] == $cr_ledger_id) echo 'selected'; ?>><?php echo $l['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" id="debit_account_wrapper">
                                        <div class="form-group">
                                            <label id="lbl_dr_account">Deposit Into (Debit)</label><small class="req"> *</small>
                                            <select name="dr_ledger_id" id="dr_ledger_id" class="form-control ledger_select" required>
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach($cash_bank_ledgers as $l) { ?>
                                                    <option value="<?php echo $l['id']; ?>" data-group="<?php echo $l['group_id']; ?>" data-system-group="<?php echo $l['system_name']; ?>" <?php if($l['id'] == $dr_ledger_id) echo 'selected'; ?>><?php echo $l['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('amount'); ?></label><small class="req"> *</small>
                                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="<?php echo isset($voucher) ? $voucher['total_amount'] : ''; ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Remark</label>
                                            <textarea name="narration" class="form-control" rows="2" maxlength="150" placeholder="0/150 characters"><?php echo set_value('narration', isset($voucher) ? $voucher['narration'] : ''); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('attach_document'); ?></label>
                                            <input type="file" name="attachment" class="form-control filestyle">
                                            <?php if(isset($voucher) && !empty($voucher['attachment'])) { ?>
                                                <div style="margin-top:5px;">
                                                    <a href="<?php echo base_url('uploads/accounts/vouchers/'.$voucher['attachment']); ?>" target="_blank" class="text-info"><i class="fa fa-paperclip"></i> View Current Attachment</a>
                                                    &nbsp; | &nbsp;
                                                    <label class="text-danger" style="font-weight:normal; cursor:pointer;"><input type="checkbox" name="delete_attachment" value="1"> Delete</label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="box-footer" style="background:#f8f9fa;">
                            <button type="submit" class="btn btn-info pull-right" id="btn_save"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
                            <button type="button" class="btn btn-default pull-right" style="margin-right: 5px;" onclick="<?php echo isset($id) ? "window.location.href='".site_url("accounts/contravoucher")."'" : "toggleOffcanvas()"; ?>"><?php echo $this->lang->line('cancel'); ?></button>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </div>
    </section>
</div>

<script type="text/javascript">
    function toggleOffcanvas() {
        $('#voucherOffcanvas').toggleClass('open');
        $('#voucherOverlay').toggleClass('open');
    }
    $('#voucherOverlay').click(function() {
        <?php if (isset($id)) { ?>
            window.location.href = '<?php echo site_url("accounts/contravoucher"); ?>';
        <?php } else { ?>
            toggleOffcanvas();
        <?php } ?>
    });

    <?php if (isset($id) || validation_errors()) { ?>
        // Open offcanvas automatically if editing or there are validation errors
        $(document).ready(function() {
            toggleOffcanvas();
        });
    <?php } ?>

    $(document).ready(function() {
        <?php if(!isset($id)) { ?>
        initDatatable('contra-voucher-list', 'accounts/contravoucher/getlist', [], [], 100);
        <?php } ?>

        var dr_options_html = $('#dr_ledger_id').html();
        var cr_options_html = $('#cr_ledger_id').html();

        function updateLedgerDropdowns() {
            var type = $('#transaction_type').val();
            var dr_select = $('#dr_ledger_id');
            var cr_select = $('#cr_ledger_id');
            
            var dr_val = dr_select.val();
            var cr_val = cr_select.val();
            
            // Restore all options first
            dr_select.html(dr_options_html);
            cr_select.html(cr_options_html);

            if (type === 'Cash To Bank') {
                $('#lbl_dr_account').text('Select Bank (Debit)');
                $('#lbl_cr_account').text('Select Cash (Credit)');
                dr_select.find('option[data-system-group!="bank"][value!=""]').remove();
                cr_select.find('option[data-system-group!="cash"][value!=""]').remove();
            } else if (type === 'Bank To Cash') {
                $('#lbl_dr_account').text('Select Cash (Debit)');
                $('#lbl_cr_account').text('Select Bank (Credit)');
                dr_select.find('option[data-system-group!="cash"][value!=""]').remove();
                cr_select.find('option[data-system-group!="bank"][value!=""]').remove();
            } else if (type === 'Bank To Bank') {
                $('#lbl_dr_account').text('Receiving Bank (Debit)');
                $('#lbl_cr_account').text('Sending Bank (Credit)');
                dr_select.find('option[data-system-group!="bank"][value!=""]').remove();
                cr_select.find('option[data-system-group!="bank"][value!=""]').remove();
            }
            
            // Restore selected values if still valid
            if (dr_select.find('option[value="' + dr_val + '"]').length > 0) dr_select.val(dr_val);
            else dr_select.val('');
            
            if (cr_select.find('option[value="' + cr_val + '"]').length > 0) cr_select.val(cr_val);
            else cr_select.val('');
        }

        $('#transaction_type').change(function() {
            updateLedgerDropdowns();
        });

        // Initialize on load
        updateLedgerDropdowns();
        
        // Character counter for narration
        $('textarea[name="narration"]').on('input', function() {
            var len = $(this).val().length;
            $(this).attr('placeholder', len + '/150 characters');
        });

        // Live Balance Handling
        function fetchBalance(ledgerId, el) {
            if(!ledgerId) { el.html(''); return; }
            el.html('<i class="fa fa-spinner fa-spin" style="color:#6b7280;"></i>');
            $.ajax({
                url: base_url + 'accounts/dashboard/get_ledger_balance',
                type: 'POST',
                data: { ledger_id: ledgerId },
                success: function(res) {
                    try {
                        var r = typeof res === 'string' ? JSON.parse(res) : res;
                        if (r.status === 'success') {
                            var color = (r.raw_balance >= 0) ? '#10b981' : '#ef4444'; 
                            el.html('Balance: <span style="color:' + color + ';">' + r.balance + ' ' + r.type + '</span>');
                        } else {
                            el.html('');
                        }
                    } catch(e) { el.html(''); }
                }
            });
        }
        
        $(document).on('change', '.ledger_select', function() {
            var wrapper = $(this).closest('.form-group');
            var balDiv = wrapper.find('.ledger_balance');
            fetchBalance($(this).val(), balDiv);
        });
        
        $('.ledger_select').each(function() {
            if($(this).val()) {
                fetchBalance($(this).val(), $(this).closest('.form-group').find('.ledger_balance'));
            }
        });
    });
</script>

<script>
$(document).ready(function() {
    function fetchLedgerBalance($select) {
        var ledger_id = $select.val();
        var $badgeContainer = $select.closest('.input-group').siblings('.cr_ledger_balance, .dr_ledger_balance, .ledger_balance_badge');
        if ($badgeContainer.length === 0) {
            $badgeContainer = $select.siblings('.cr_ledger_balance, .dr_ledger_balance, .ledger_balance_badge');
        }
        if ($badgeContainer.length === 0) {
            $badgeContainer = $('<div class="ledger_balance_badge" style="font-size:11px; font-weight:600; margin-top:2px;"></div>');
            if ($select.parent('.input-group').length > 0) {
                $select.parent('.input-group').after($badgeContainer);
            } else {
                $select.after($badgeContainer);
            }
        }

        if (!ledger_id) {
            $badgeContainer.html('');
            return;
        }

        $badgeContainer.html('<i class="fa fa-spinner fa-spin"></i> Fetching...');
        
        $.ajax({
            url: base_url + 'accounts/ledgermaster/get_balance',
            type: 'POST',
            data: { id: ledger_id },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    var color = (res.type === 'Cr') ? '#10b981' : '#ef4444'; 
                    var badge = '<span style="color:' + color + '">Bal: ' + parseFloat(res.balance).toFixed(2) + ' ' + res.type + '</span>';
                    $badgeContainer.html(badge);
                } else {
                    $badgeContainer.html('<span style="color:#ef4444">Error</span>');
                }
            }
        });
    }

    $(document).on('change', 'select[name="ledger_id[]"], select[name="cr_ledger_id"], select[name="dr_ledger_id"], select[name="payment_mode_id"], select[name="supplier_ledger_id"], select[name="expense_ledger_id[]"]', function() {
        fetchLedgerBalance($(this));
    });

    setTimeout(function() {
        $('select[name="ledger_id[]"], select[name="cr_ledger_id"], select[name="dr_ledger_id"], select[name="payment_mode_id"], select[name="supplier_ledger_id"], select[name="expense_ledger_id[]"]').each(function() {
            if ($(this).val()) {
                fetchLedgerBalance($(this));
            }
        });
    }, 500);
});
</script>
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
    
    $(document).on('click', '.approve-voucher-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to approve this voucher?')) {
            $.ajax({
                url: base_url + 'accounts/contravoucher/approve_voucher',
                type: 'POST',
                data: {
                    voucher_id: id,
                    action: 'approve',
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        showAccToast(res.message, 'success');
                        $('.contra-voucher-list').DataTable().ajax.reload(null, false);
                    } else {
                        showAccToast(res.message, 'error');
                    }
                }
            });
        }
    });

    $(document).on('click', '.reject-voucher-btn', function() {
        var id = $(this).data('id');
        var reason = prompt('Please enter a reason for rejection:');
        if (reason !== null) {
            if (reason.trim() === '') {
                alert('Reason is required for rejection.');
                return;
            }
            $.ajax({
                url: base_url + 'accounts/contravoucher/approve_voucher',
                type: 'POST',
                data: {
                    voucher_id: id,
                    action: 'reject',
                    reason: reason,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        showAccToast(res.message, 'success');
                        $('.contra-voucher-list').DataTable().ajax.reload(null, false);
                    } else {
                        showAccToast(res.message, 'error');
                    }
                }
            });
        }
    });
});
</script>