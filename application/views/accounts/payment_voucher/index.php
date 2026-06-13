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
    
    /* Premium Toast Styles */
    .acc-toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .acc-toast {
        background: #ffffff;
        border-left: 4px solid #3b82f6;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.15), 0 4px 6px -2px rgba(0,0,0,0.05);
        padding: 14px 20px;
        border-radius: 6px;
        color: #1f2937;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 320px;
        max-width: 450px;
        transform: translateX(120%);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .acc-toast.show {
        transform: translateX(0);
        opacity: 1;
    }
    .acc-toast.toast-error {
        border-left-color: #ef4444;
    }
    .acc-toast.toast-warning {
        border-left-color: #f59e0b;
    }
    .acc-toast.toast-success {
        border-left-color: #10b981;
    }
    .acc-toast.toast-info {
        border-left-color: #3b82f6;
    }
    .acc-toast-close {
        margin-left: auto;
        cursor: pointer;
        color: #9ca3af;
        font-size: 16px;
        font-weight: bold;
    }
    .acc-toast-close:hover {
        color: #4b5563;
    }
</style>

<div class="acc-toast-container" id="accToastContainer"></div>
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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('payment_voucher_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('acc_payment_voucher', 'can_add')) { ?>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="toggleOffcanvas()"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_payment_voucher'); ?></button>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover payment-voucher-list">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('voucher_no'); ?></th>
                                        <th>Transaction Details</th>
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

            <?php if ($this->rbac->hasPrivilege('acc_payment_voucher', 'can_add') || (isset($id) && $this->rbac->hasPrivilege('acc_payment_voucher', 'can_edit'))) { ?>
                <div class="offcanvas-right" id="voucherOffcanvas">
                    <div class="offcanvas-header">
                        <h4 class="m-0"><b><?php echo isset($id) ? $this->lang->line('edit') : $this->lang->line('add_payment_voucher'); ?></b></h4>
                        <button type="button" class="close" onclick="toggleOffcanvas()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="<?php echo isset($id) ? site_url('accounts/paymentvoucher/edit/' . $id) : site_url('accounts/paymentvoucher/index') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
                                            <label><?php echo $this->lang->line('cash_bank'); ?> (Cr)</label><small class="req"> *</small>
                                            <?php 
                                            $cr_selected = '';
                                            if(isset($voucher) && !empty($voucher['items'])){
                                                foreach($voucher['items'] as $it) {
                                                    if($it['credit_amount'] > 0) {
                                                        $cr_selected = $it['ledger_id'];
                                                    }
                                                }
                                            }
                                            ?>
                                            <div class="input-group">
                                                <select name="cr_ledger_id" id="cr_ledger_id" class="form-control" style="width: 100%;">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($cash_bank_ledgers as $ledger) { ?>
                                                        <option value="<?php echo $ledger['id'] ?>" data-system-name="<?php echo isset($ledger['system_name']) ? $ledger['system_name'] : ''; ?>" <?php echo set_select('cr_ledger_id', $ledger['id'], ($cr_selected == $ledger['id'])); ?>><?php echo $ledger['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="showAddPaymentModeModal()">
                                                    <i class="fa fa-plus text-primary"></i>
                                                </div>
                                            </div>
                                            <div id="cr_ledger_balance" style="font-size:12px; font-weight:600; margin-top:4px;"></div>
                                            <span class="text-danger"><?php echo form_error('cr_ledger_id'); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('payment_method'); ?></label>
                                            <select name="payment_method" id="payment_method" class="form-control">
                                                <option value="Cash" <?php echo set_select('payment_method', 'Cash', (isset($voucher) && strtolower($voucher['payment_method']) == 'cash')); ?>>Cash</option>
                                                <option value="Cheque" <?php echo set_select('payment_method', 'Cheque', (isset($voucher) && strtolower($voucher['payment_method']) == 'cheque')); ?>>Cheque</option>
                                                <option value="DD" <?php echo set_select('payment_method', 'DD', (isset($voucher) && strtolower($voucher['payment_method']) == 'dd')); ?>>DD</option>
                                                <option value="Bank Transfer" <?php echo set_select('payment_method', 'Bank Transfer', (isset($voucher) && in_array(strtolower($voucher['payment_method']), ['bank transfer', 'bank_transfer']))); ?>>Bank Transfer</option>
                                                <option value="Net Banking" <?php echo set_select('payment_method', 'Net Banking', (isset($voucher) && strtolower($voucher['payment_method']) == 'net banking')); ?>>Net Banking</option>
                                                <option value="UPI" <?php echo set_select('payment_method', 'UPI', (isset($voucher) && strtolower($voucher['payment_method']) == 'upi')); ?>>UPI</option>
                                                <option value="Card" <?php echo set_select('payment_method', 'Card', (isset($voucher) && strtolower($voucher['payment_method']) == 'card')); ?>>Card</option>
                                                <option value="Online" <?php echo set_select('payment_method', 'Online', (isset($voucher) && strtolower($voucher['payment_method']) == 'online')); ?>>Online</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="payment_details_section" style="display:none;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label id="lbl_ref_no">Reference No</label>
                                            <?php 
                                            $ref_val = '';
                                            if(isset($voucher)) {
                                                if(!empty($voucher['cheque_no'])) $ref_val = $voucher['cheque_no'];
                                                elseif(!empty($voucher['upi_transaction_id'])) $ref_val = $voucher['upi_transaction_id'];
                                                elseif(!empty($voucher['net_banking_ref'])) $ref_val = $voucher['net_banking_ref'];
                                            }
                                            ?>
                                            <input name="reference_no" id="reference_no" type="text" class="form-control" value="<?php echo set_value('reference_no', $ref_val); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label id="lbl_payment_date">Payment Date</label>
                                            <?php $p_date = (isset($voucher) && !empty($voucher['payment_date']) && $voucher['payment_date'] != '0000-00-00') ? date($this->customlib->getSchoolDateFormat(), strtotime($voucher['payment_date'])) : date($this->customlib->getSchoolDateFormat()); ?>
                                            <input name="payment_date" type="text" class="form-control date" value="<?php echo set_value('payment_date', $p_date); ?>" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-md-12 bank_field_group" style="display:none;">
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <div class="input-group">
                                                <select name="bank_name" id="bank_name" class="form-control">
                                                    <option value="">Select Bank</option>
                                                    <?php foreach($banks as $bank) { ?>
                                                        <option value="<?php echo $bank['name']; ?>" <?php echo set_select('bank_name', $bank['name'], (isset($voucher) && $voucher['bank_name'] == $bank['name'])); ?>><?php echo $bank['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="showAddBankModal()">
                                                    <i class="fa fa-plus text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered" id="item_table">
                                            <thead>
                                                <tr style="background:#f3f4f6;">
                                                    <th><?php echo $this->lang->line('ledger_account'); ?> (Dr)</th>
                                                    <th>Category/Head</th>
                                                    <th width="150"><?php echo $this->lang->line('amount'); ?></th>
                                                    <th width="50"><button type="button" class="btn btn-sm btn-primary add_row"><i class="fa fa-plus"></i></button></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $ledger_options = '<option value="">'.$this->lang->line('select').'</option>';
                                                foreach($ledgers as $l) {
                                                    $ledger_options .= '<option value="'.$l['id'].'">'.$l['name'].'</option>';
                                                }

                                                $expense_options = '<option value="">'.$this->lang->line('select').'</option>';
                                                foreach($expense_types as $et) {
                                                    $expense_options .= '<option value="'.$et['id'].'">'.$et['name'].'</option>';
                                                }
                                                
                                                if (isset($voucher) && !empty($voucher['items'])) {
                                                    foreach ($voucher['items'] as $item) {
                                                        if($item['debit_amount'] > 0) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <select name="ledger_id[]" class="form-control ledger_select" required style="width: 100%;">
                                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                        <?php foreach($ledgers as $l) { ?>
                                                                            <option value="<?php echo $l['id']; ?>" <?php if($l['id'] == $item['ledger_id']) echo 'selected'; ?>><?php echo $l['name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    <div class="input-group-addon quick-add-ledger-btn" style="padding: 6px 10px; cursor: pointer;">
                                                                        <i class="fa fa-plus text-primary"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="dr_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>
                                                            </td>
                                                            <td>
                                                                <select name="expense_type_id[]" class="form-control">
                                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                    <?php foreach($expense_types as $et) { ?>
                                                                        <option value="<?php echo $et['id']; ?>" <?php if($et['id'] == $item['expense_type_id']) echo 'selected'; ?>><?php echo $et['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td><input type="number" step="0.01" name="amount[]" class="form-control amount" value="<?php echo $item['debit_amount']; ?>" required></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger remove_row"><i class="fa fa-remove"></i></button></td>
                                                        </tr>
                                                        <?php
                                                        }
                                                    }
                                                } else {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <select name="ledger_id[]" class="form-control ledger_select" required style="width: 100%;">
                                                                <?php echo $ledger_options; ?>
                                                            </select>
                                                            <div class="input-group-addon quick-add-ledger-btn" style="padding: 6px 10px; cursor: pointer;">
                                                                <i class="fa fa-plus text-primary"></i>
                                                            </div>
                                                        </div>
                                                        <div class="dr_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>
                                                    </td>
                                                    <td>
                                                        <select name="expense_type_id[]" class="form-control">
                                                            <?php echo $expense_options; ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" step="0.01" name="amount[]" class="form-control amount" required></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger remove_row"><i class="fa fa-remove"></i></button></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2" class="text-right"><?php echo $this->lang->line('total'); ?></th>
                                                    <th><input type="text" id="total_amount" class="form-control" value="0.00" readonly></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('narration'); ?></label>
                                            <textarea name="narration" class="form-control" rows="3"><?php echo set_value('narration', isset($voucher) ? $voucher['narration'] : ''); ?></textarea>
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
                            <button type="submit" class="btn btn-info pull-right"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
                            <button type="button" class="btn btn-default pull-right" style="margin-right: 5px;" onclick="<?php echo isset($id) ? "window.location.href='".site_url("accounts/paymentvoucher")."'" : "toggleOffcanvas()"; ?>"><?php echo $this->lang->line('cancel'); ?></button>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </div>
    </section>
</div>
<script type="text/javascript">
    var ledger_opts = '<?php echo $ledger_options; ?>';
    var expense_opts = '<?php echo $expense_options; ?>';
    var activeLedgerSelect = null;
    
    function toggleOffcanvas() {
        $('#voucherOffcanvas').toggleClass('open');
        $('#voucherOverlay').toggleClass('open');
    }
    $('#voucherOverlay').click(function() {
        <?php if (isset($id)) { ?>
            window.location.href = '<?php echo site_url("accounts/paymentvoucher"); ?>';
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
    
    function showAccToast(message, type = 'info') {
        var iconClass = 'fa-info-circle text-info';
        var toastClass = 'toast-info';
        if (type === 'success') {
            iconClass = 'fa-check-circle text-success';
            toastClass = 'toast-success';
        } else if (type === 'warning') {
            iconClass = 'fa-warning text-warning';
            toastClass = 'toast-warning';
        } else if (type === 'error') {
            iconClass = 'fa-times-circle text-danger';
            toastClass = 'toast-error';
        }
        
        var toastId = 'toast_' + Date.now();
        var html = '<div class="acc-toast ' + toastClass + '" id="' + toastId + '">';
        html += '<i class="fa ' + iconClass + '" style="font-size:16px;"></i>';
        html += '<span>' + message + '</span>';
        html += '<span class="acc-toast-close" onclick="closeAccToast(\'' + toastId + '\')">&times;</span>';
        html += '</div>';
        
        $('#accToastContainer').append(html);
        setTimeout(function() {
            $('#' + toastId).addClass('show');
        }, 50);
        
        setTimeout(function() {
            closeAccToast(toastId);
        }, 5000);
    }

    function closeAccToast(id) {
        var $el = $('#' + id);
        $el.removeClass('show').css('opacity', 0);
        setTimeout(function() {
            $el.remove();
        }, 300);
    }

    <?php if ($this->session->flashdata('msg_toast_success')) { ?>
        $(document).ready(function() {
            showAccToast("<?php echo htmlspecialchars($this->session->flashdata('msg_toast_success')); ?>", 'success');
        });
    <?php } ?>
    <?php if ($this->session->flashdata('msg_toast_warning')) { ?>
        $(document).ready(function() {
            showAccToast("<?php echo htmlspecialchars($this->session->flashdata('msg_toast_warning')); ?>", 'warning');
        });
    <?php } ?>
    <?php if ($this->session->flashdata('msg_toast_error')) { ?>
        $(document).ready(function() {
            showAccToast("<?php echo htmlspecialchars($this->session->flashdata('msg_toast_error')); ?>", 'error');
        });
    <?php } ?>

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

    $(document).ready(function() {
        if ($('#cr_ledger_id').val()) {
            fetchBalance($('#cr_ledger_id').val(), $('#cr_ledger_balance'));
        }
        $('#cr_ledger_id').change(function() {
            fetchBalance($(this).val(), $('#cr_ledger_balance'));
            
            var selectedText = $(this).find('option:selected').text();
            if (selectedText !== '' && selectedText.toLowerCase().indexOf('cash') === -1) {
                $('#payment_method').val('Net Banking').trigger('change');
                if ($('#bank_name option[value="Punjab National Bank"]').length === 0) {
                    $('#bank_name').append(new Option('Punjab National Bank', 'Punjab National Bank'));
                }
                $('#bank_name').val('Punjab National Bank').trigger('change');
            } else if (selectedText !== '' && selectedText.toLowerCase().indexOf('cash') !== -1) {
                $('#payment_method').val('Cash').trigger('change');
            }
        });
        
        $(document).on('change', '.ledger_select', function() {
            var row = $(this).closest('tr');
            var balDiv = row.find('.dr_ledger_balance');
            fetchBalance($(this).val(), balDiv);
        });
        
        $('.ledger_select').each(function() {
            if($(this).val()) fetchBalance($(this).val(), $(this).closest('tr').find('.dr_ledger_balance'));
        });
        <?php if(!isset($id)) { ?>
        initDatatable('payment-voucher-list', 'accounts/paymentvoucher/getlist', [], [], 100);
        <?php } ?>

        $('.add_row').click(function() {
            var html = '<tr>';
            html += '<td><div class="input-group"><select name="ledger_id[]" class="form-control" required style="width: 100%;">'+ledger_opts+'</select><div class="input-group-addon quick-add-ledger-btn" style="padding: 6px 10px; cursor: pointer;"><i class="fa fa-plus text-primary"></i></div></div></td>';
            html += '<td><select name="expense_type_id[]" class="form-control">'+expense_opts+'</select></td>';
            html += '<td><input type="number" step="0.01" name="amount[]" class="form-control amount" required></td>';
            html += '<td><button type="button" class="btn btn-sm btn-danger remove_row"><i class="fa fa-remove"></i></button></td>';
            html += '</tr>';
            $('#item_table tbody').append(html);
        });

        $(document).on('click', '.remove_row', function() {
            $(this).closest('tr').remove();
            calculateTotal();
        });

        $(document).on('keyup change', '.amount', function() {
            calculateTotal();
        });

        $(document).on('click', '.quick-add-ledger-btn', function() {
            activeLedgerSelect = $(this).closest('.input-group').find('select');
            showAddLedgerModal();
        });

        function calculateTotal() {
            var total = 0;
            $('.amount').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total_amount').val(total.toFixed(2));
        }
        
        calculateTotal();

        $('#pm_group_id').change(function() {
            var val = $(this).find('option:selected').text();
            if (val.toLowerCase() === 'bank account' || val.toLowerCase() === 'bank accounts') {
                $('#pm_bank_details_section').slideDown();
            } else {
                $('#pm_bank_details_section').slideUp();
            }
        });
        $('#payment_method').change(function() {
            var method = $(this).val();
            if(method === 'Cash') {
                $('#payment_details_section').slideUp();
            } else {
                $('#payment_details_section').slideDown();
                if(method === 'Cheque') {
                    $('#lbl_ref_no').text('Cheque No');
                    $('.bank_field_group').show();
                } else if(method === 'UPI') {
                    $('#lbl_ref_no').text('UPI Transaction ID');
                    $('.bank_field_group').hide();
                } else if(method === 'Net Banking') {
                    $('#lbl_ref_no').text('Net Banking Ref');
                    $('.bank_field_group').show();
                } else {
                    $('#lbl_ref_no').text('Reference No');
                    $('.bank_field_group').hide();
                }
            }
        });
        // Trigger on load
        if ($('#payment_method').val() !== 'Cash') {
            $('#payment_method').trigger('change');
        }
        
        $('#cr_ledger_id').change(function() {
            var sysName = $(this).find('option:selected').data('system-name');
            if (sysName === 'bank') {
                $('#payment_method').val('Net Banking').trigger('change');
                setTimeout(function() {
                    $('#bank_name').val('Punjab National Bank').trigger('change');
                }, 100);
            }
        });
    });

    function showAddPaymentModeModal() {
        $('#new_pm_name').val('');
        $('#pm_group_id').val('').trigger('change');
        $('#pm_bank_id').val('');
        $('#new_pm_account_no').val('');
        $('#addPaymentModeModal').modal('show');
    }

    function submitNewPaymentMode() {
        var name = $('#new_pm_name').val().trim();
        var group_id = $('#pm_group_id').val();
        if (name === '' || !group_id) {
            alert('Please enter a name and select a group.');
            return;
        }
        
        var bank_id = $('#pm_bank_id').val();
        var account_no = $('#new_pm_account_no').val().trim();

        $.ajax({
            url: '<?php echo site_url("accounts/paymentvoucher/quick_add_ledger_ajax"); ?>',
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
                    var newOption = new Option(res.name, res.id, true, true);
                    $('select[name="cr_ledger_id"]').append(newOption).trigger('change');
                    $('#addPaymentModeModal').modal('hide');
                } else {
                    alert(res.error);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    }

    function showAddLedgerModal() {
        $('#new_ledger_name').val('');
        $('#ledger_group_id').val('');
        $('#addLedgerModal').modal('show');
    }

    function submitNewLedger() {
        var name = $('#new_ledger_name').val().trim();
        var group_id = $('#ledger_group_id').val();
        if (name === '' || !group_id) {
            alert('Please enter a name and select a group.');
            return;
        }

        $.ajax({
            url: '<?php echo site_url("accounts/paymentvoucher/quick_add_ledger_ajax"); ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                group_id: group_id,
                name: name,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(res) {
                if (res.status === 'success') {
                    var newOption = new Option(res.name, res.id, true, true);
                    // Update in dynamic select options variable as well
                    ledger_opts = ledger_opts + '<option value="' + res.id + '">' + res.name + '</option>';
                    if (activeLedgerSelect) {
                        activeLedgerSelect.append(newOption).trigger('change');
                    }
                    $('#addLedgerModal').modal('hide');
                } else {
                    alert(res.error);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    }
</script>

<div class="modal fade" id="addPaymentModeModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addPaymentModeModalLabel">Add Payment Mode (Cash/Bank Account)</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Account Name</label><small class="req"> *</small>
                    <input type="text" id="new_pm_name" class="form-control" placeholder="e.g. HDFC Current Account, Petty Cash">
                </div>
                <div class="form-group">
                    <label>Group</label><small class="req"> *</small>
                    <select id="pm_group_id" class="form-control">
                        <option value="">Select Group</option>
                        <?php foreach($ledger_groups as $group) { 
                            if ($group['id'] == 1 || $group['id'] == 2) { ?>
                                <option value="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></option>
                            <?php } 
                        } ?>
                    </select>
                </div>
                <div id="pm_bank_details_section" style="display:none;">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <select id="pm_bank_id" class="form-control">
                            <option value="">Select Bank</option>
                            <?php foreach($banks as $bank) { ?>
                                <option value="<?php echo $bank['id']; ?>"><?php echo $bank['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Account No</label>
                        <input type="text" id="new_pm_account_no" class="form-control" placeholder="Enter Account Number">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitNewPaymentMode()">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addLedgerModal" tabindex="-1" role="dialog" aria-labelledby="addLedgerModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addLedgerModalLabel">Add General Ledger Account</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Ledger Account Name</label><small class="req"> *</small>
                    <input type="text" id="new_ledger_name" class="form-control" placeholder="e.g. Rent Expense, Electricity Charge">
                </div>
                <div class="form-group">
                    <label>Ledger Group</label><small class="req"> *</small>
                    <select id="ledger_group_id" class="form-control">
                        <option value="">Select Group</option>
                        <?php foreach($ledger_groups as $group) { ?>
                            <option value="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitNewLedger()">Save</button>
        </div>
    </div>
</div>

<div class="modal fade" id="addBankModal" tabindex="-1" role="dialog" aria-labelledby="addBankModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addBankModalLabel">Add Bank</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Bank Name</label><small class="req"> *</small>
                    <input type="text" id="new_bank_name" class="form-control" placeholder="e.g. State Bank of India">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitNewBank()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
function showAddBankModal() {
    $('#new_bank_name').val('');
    $('#addBankModal').modal('show');
}

function submitNewBank() {
    var name = $('#new_bank_name').val().trim();
    if (name === '') {
        alert('Please enter a bank name.');
        return;
    }

    var newOption = new Option(name, name, true, true);
    $('#bank_name').append(newOption).trigger('change');
    $('#pm_bank_id').append(new Option(name, name));
    $('#addBankModal').modal('hide');
}
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
                url: base_url + 'accounts/paymentvoucher/approve_voucher',
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
                        $('.payment-voucher-list').DataTable().ajax.reload(null, false);
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
                url: base_url + 'accounts/paymentvoucher/approve_voucher',
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
                        $('.payment-voucher-list').DataTable().ajax.reload(null, false);
                    } else {
                        showAccToast(res.message, 'error');
                    }
                }
            });
        }
    });
});
</script>