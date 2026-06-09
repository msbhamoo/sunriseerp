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
    .journal-totals input {
        font-weight: bold;
        font-size: 16px;
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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('journal_voucher_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('acc_journal_voucher', 'can_add')) { ?>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="toggleOffcanvas()"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_journal_voucher'); ?></button>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover journal-voucher-list">
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

            <?php if ($this->rbac->hasPrivilege('acc_journal_voucher', 'can_add') || (isset($id) && $this->rbac->hasPrivilege('acc_journal_voucher', 'can_edit'))) { ?>
                <div class="offcanvas-right" id="voucherOffcanvas">
                    <div class="offcanvas-header">
                        <h4 class="m-0"><b><?php echo isset($id) ? $this->lang->line('edit') : $this->lang->line('add_journal_voucher'); ?></b></h4>
                        <button type="button" class="close" onclick="toggleOffcanvas()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="<?php echo isset($id) ? site_url('accounts/journalvoucher/edit/' . $id) : site_url('accounts/journalvoucher/index') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered" id="item_table">
                                            <thead>
                                                <tr style="background:#f3f4f6;">
                                                    <th width="100"><?php echo $this->lang->line('type'); ?> (Dr/Cr)</th>
                                                    <th><?php echo $this->lang->line('account'); ?></th>
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
                                                
                                                if (isset($voucher) && !empty($voucher['items'])) {
                                                    foreach ($voucher['items'] as $item) {
                                                        $type = ($item['debit_amount'] > 0) ? 'Dr' : 'Cr';
                                                        $amount = ($item['debit_amount'] > 0) ? $item['debit_amount'] : $item['credit_amount'];
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <select name="type[]" class="form-control type_select">
                                                                    <option value="Dr" <?php if($type == 'Dr') echo 'selected'; ?>>Dr</option>
                                                                    <option value="Cr" <?php if($type == 'Cr') echo 'selected'; ?>>Cr</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="ledger_id[]" class="form-control" required>
                                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                    <?php foreach($ledgers as $l) { ?>
                                                                        <option value="<?php echo $l['id']; ?>" <?php if($l['id'] == $item['ledger_id']) echo 'selected'; ?>><?php echo $l['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td><input type="number" step="0.01" name="amount[]" class="form-control amount" value="<?php echo $amount; ?>" required></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger remove_row"><i class="fa fa-remove"></i></button></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <select name="type[]" class="form-control type_select">
                                                            <option value="Dr">Dr</option>
                                                            <option value="Cr">Cr</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="ledger_id[]" class="form-control" required>
                                                            <?php echo $ledger_options; ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" step="0.01" name="amount[]" class="form-control amount" required></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger remove_row"><i class="fa fa-remove"></i></button></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot class="journal-totals">
                                                <tr style="background:#fef2f2;">
                                                    <th colspan="2" class="text-right"><?php echo $this->lang->line('total_debit'); ?></th>
                                                    <th><input type="text" id="total_dr" class="form-control" value="0.00" readonly style="color:#991b1b;"></th>
                                                    <th></th>
                                                </tr>
                                                <tr style="background:#f0fdf4;">
                                                    <th colspan="2" class="text-right"><?php echo $this->lang->line('total_credit'); ?></th>
                                                    <th><input type="text" id="total_cr" class="form-control" value="0.00" readonly style="color:#065f46;"></th>
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
                            <button type="submit" class="btn btn-info pull-right" id="btn_save"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
                            <button type="button" class="btn btn-default pull-right" style="margin-right: 5px;" onclick="<?php echo isset($id) ? "window.location.href='".site_url("accounts/journalvoucher")."'" : "toggleOffcanvas()"; ?>"><?php echo $this->lang->line('cancel'); ?></button>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </div>
    </section>
</div>
<script type="text/javascript">
    var ledger_opts = '<?php echo $ledger_options; ?>';
    
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

    function toggleOffcanvas() {
        $('#voucherOffcanvas').toggleClass('open');
        $('#voucherOverlay').toggleClass('open');
    }
    $('#voucherOverlay').click(function() {
        <?php if (isset($id)) { ?>
            window.location.href = '<?php echo site_url("accounts/journalvoucher"); ?>';
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
        initDatatable('journal-voucher-list', 'accounts/journalvoucher/getlist', [], [], 100);
        <?php } ?>

        $('.add_row').click(function() {
            var html = '<tr>';
            html += '<td><select name="type[]" class="form-control type_select"><option value="Dr">Dr</option><option value="Cr">Cr</option></select></td>';
            html += '<td><select name="ledger_id[]" class="form-control" required>'+ledger_opts+'</select></td>';
            html += '<td><input type="number" step="0.01" name="amount[]" class="form-control amount" required></td>';
            html += '<td><button type="button" class="btn btn-sm btn-danger remove_row"><i class="fa fa-remove"></i></button></td>';
            html += '</tr>';
            $('#item_table tbody').append(html);
        });

        $(document).on('click', '.remove_row', function() {
            $(this).closest('tr').remove();
            calculateTotal();
        });

        $(document).on('keyup change', '.amount, .type_select', function() {
            calculateTotal();
        });

        function calculateTotal() {
            var total_dr = 0;
            var total_cr = 0;
            $('#item_table tbody tr').each(function() {
                var type = $(this).find('.type_select').val();
                var amt = parseFloat($(this).find('.amount').val()) || 0;
                if(type === 'Dr') {
                    total_dr += amt;
                } else {
                    total_cr += amt;
                }
            });
            $('#total_dr').val(total_dr.toFixed(2));
            $('#total_cr').val(total_cr.toFixed(2));

            if(total_dr !== total_cr || total_dr === 0) {
                $('#total_dr, #total_cr').css('border-color', 'red');
            } else {
                $('#total_dr, #total_cr').css('border-color', 'green');
            }
        }
        
        calculateTotal();

        $('form').submit(function(e) {
            var dr = parseFloat($('#total_dr').val());
            var cr = parseFloat($('#total_cr').val());
            if(dr !== cr || dr === 0) {
                e.preventDefault();
                alert("<?php echo $this->lang->line('debit_credit_must_be_equal'); ?>");
                return false;
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
                url: base_url + 'accounts/journalvoucher/approve_voucher',
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
                        $('.journal-voucher-list').DataTable().ajax.reload(null, false);
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
                url: base_url + 'accounts/journalvoucher/approve_voucher',
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
                        $('.journal-voucher-list').DataTable().ajax.reload(null, false);
                    } else {
                        showAccToast(res.message, 'error');
                    }
                }
            });
        }
    });
});
</script>