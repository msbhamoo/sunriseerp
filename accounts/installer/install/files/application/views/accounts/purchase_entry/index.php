<style>
    .offcanvas-right {
        position: fixed;
        top: 0;
        right: -800px;
        width: 800px;
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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('purchase_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('acc_purchase_entry', 'can_add')) { ?>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="toggleOffcanvas()"><i class="fa fa-plus"></i> <?php echo $this->lang->line('purchase_entry'); ?></button>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover purchase-entry-list">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('invoice_no'); ?></th>
                                        <th><?php echo $this->lang->line('supplier'); ?></th>
                                        <th><?php echo $this->lang->line('net_amount'); ?></th>
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

            <?php if ($this->rbac->hasPrivilege('acc_purchase_entry', 'can_add') || (isset($id) && $this->rbac->hasPrivilege('acc_purchase_entry', 'can_edit'))) { ?>
                <div class="offcanvas-right" id="voucherOffcanvas">
                    <div class="offcanvas-header">
                        <h4 class="m-0"><b><?php echo isset($id) ? $this->lang->line('edit') : $this->lang->line('purchase_entry'); ?></b></h4>
                        <button type="button" class="close" onclick="toggleOffcanvas()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="<?php echo (isset($id) ? site_url('accounts/purchaseentry/edit/' . $id) : site_url('accounts/purchaseentry/index')); ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="offcanvas-body">
                            <?php if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                            } ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            
                            <div class="row">
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label><?php echo $this->lang->line('supplier'); ?></label><small class="req"> *</small>
                                         <div class="input-group">
                                             <select name="supplier_ledger_id" class="form-control" style="width: 100%;">
                                                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                 <?php foreach ($suppliers as $supplier) { ?>
                                                     <option value="<?php echo $supplier['id'] ?>" <?php echo set_select('supplier_ledger_id', $supplier['id'], (isset($purchase) && $purchase['supplier_ledger_id'] == $supplier['id'])); ?>><?php echo $supplier['name'] ?></option>
                                                 <?php } ?>
                                             </select>
                                             <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="showAddSupplierModal()">
                                                 <i class="fa fa-plus text-primary"></i>
                                             </div>
                                         </div>
                                         <span class="text-danger"><?php echo form_error('supplier_ledger_id'); ?></span>
                                     </div>
                                 </div>
                                <div class="col-md-4">
                                    <?php $p_date = (isset($purchase)) ? date($this->customlib->getSchoolDateFormat(), strtotime($purchase['purchase_date'])) : date($this->customlib->getSchoolDateFormat()); ?>
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('voucher_date'); ?></label><small class="req"> *</small>
                                        <input name="purchase_date" type="text" class="form-control date" value="<?php echo set_value('purchase_date', $p_date); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('purchase_date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('invoice_no'); ?></label>
                                        <input name="invoice_no" type="text" class="form-control" value="<?php echo set_value('invoice_no', isset($purchase) ? $purchase['invoice_no'] : ($next_invoice_no ?? '')); ?>" readonly />
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered" id="item_table">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('item_description'); ?></th>
                                                <th><?php echo $this->lang->line('expense_type'); ?></th>
                                                <th width="100"><?php echo $this->lang->line('qty'); ?></th>
                                                <th width="150"><?php echo $this->lang->line('rate'); ?></th>
                                                <th width="150"><?php echo $this->lang->line('amount'); ?></th>
                                                <th width="50"><button type="button" class="btn btn-sm btn-primary add_row"><i class="fa fa-plus"></i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $expense_options = '<option value="">'.$this->lang->line('select').'</option>';
                                            foreach($expense_types as $et) {
                                                $expense_options .= '<option value="'.$et['id'].'">'.$et['name'].'</option>';
                                            }
                                            
                                            if (isset($purchase) && !empty($purchase['items'])) {
                                                foreach ($purchase['items'] as $item) {
                                                    ?>
                                                    <tr>
                                                        <td><input type="text" name="item_description[]" class="form-control" value="<?php echo $item['item_description']; ?>" required></td>
                                                        <td>
                                                            <select name="expense_type_id[]" class="form-control">
                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                <?php foreach($expense_types as $et) { ?>
                                                                    <option value="<?php echo $et['id']; ?>" <?php if($et['id'] == $item['expense_type_id']) echo 'selected'; ?>><?php echo $et['name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="number" step="0.01" name="qty[]" class="form-control qty" value="<?php echo $item['qty']; ?>" required></td>
                                                        <td><input type="number" step="0.01" name="rate[]" class="form-control purchase_rate" value="<?php echo $item['rate']; ?>" required></td>
                                                        <td><input type="number" step="0.01" name="amount[]" class="form-control amount" value="<?php echo $item['amount']; ?>" readonly></td>
                                                        <td><button type="button" class="btn btn-sm btn-danger remove_row"><i class="fa fa-remove"></i></button></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                            ?>
                                            <tr>
                                                <td><input type="text" name="item_description[]" class="form-control" required></td>
                                                <td>
                                                    <select name="expense_type_id[]" class="form-control">
                                                        <?php echo $expense_options; ?>
                                                    </select>
                                                </td>
                                                <td><input type="number" step="0.01" name="qty[]" class="form-control qty" value="1.00" required></td>
                                                <td><input type="number" step="0.01" name="rate[]" class="form-control purchase_rate" required></td>
                                                <td><input type="number" step="0.01" name="amount[]" class="form-control amount" readonly></td>
                                                <td><button type="button" class="btn btn-sm btn-danger remove_row"><i class="fa fa-remove"></i></button></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-right"><?php echo $this->lang->line('total'); ?></th>
                                                <th><input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" value="<?php echo set_value('total_amount', isset($purchase) ? $purchase['total_amount'] : '0.00'); ?>" readonly></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-right"><?php echo $this->lang->line('discount'); ?></th>
                                                <th><input type="number" step="0.01" name="discount" id="discount" class="form-control calc_net" value="<?php echo set_value('discount', isset($purchase) ? $purchase['discount'] : '0.00'); ?>"></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-right"><?php echo $this->lang->line('gst_amount'); ?></th>
                                                <th><input type="number" step="0.01" name="gst_amount" id="gst_amount" class="form-control calc_net" value="<?php echo set_value('gst_amount', isset($purchase) ? $purchase['gst_amount'] : '0.00'); ?>"></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-right"><?php echo $this->lang->line('net_amount'); ?></th>
                                                <th><input type="number" step="0.01" name="net_amount" id="net_amount" class="form-control" value="<?php echo set_value('net_amount', isset($purchase) ? $purchase['net_amount'] : '0.00'); ?>" readonly></th>
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
                                            <textarea name="narration" class="form-control" rows="3"><?php echo set_value('narration', isset($purchase) ? $purchase['narration'] : ''); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('attach_document'); ?></label>
                                            <input type="file" name="attachment" class="form-control filestyle">
                                            <?php if(isset($purchase) && !empty($purchase['attachment'])) { ?>
                                                <div style="margin-top:5px;">
                                                    <a href="<?php echo base_url('uploads/accounts/vouchers/'.$purchase['attachment']); ?>" target="_blank" class="text-info"><i class="fa fa-paperclip"></i> View Current Attachment</a>
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
                            <button type="button" class="btn btn-default pull-right" style="margin-right: 5px;" onclick="<?php echo isset($id) ? "window.location.href='".site_url("accounts/purchaseentry")."'" : "toggleOffcanvas()"; ?>"><?php echo $this->lang->line('cancel'); ?></button>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </div>
    </section>
</div>
<script type="text/javascript">
    var expense_opts = '<?php echo $expense_options; ?>';
    
    function toggleOffcanvas() {
        $('#voucherOffcanvas').toggleClass('open');
        $('#voucherOverlay').toggleClass('open');
    }
    $('#voucherOverlay').click(function() {
        <?php if (isset($id)) { ?>
            window.location.href = '<?php echo site_url("accounts/purchaseentry"); ?>';
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
        initDatatable('purchase-entry-list', 'accounts/purchaseentry/getlist', [], [], 100);
        <?php } ?>

        $('.add_row').click(function() {
            var html = '<tr>';
            html += '<td><input type="text" name="item_description[]" class="form-control" required></td>';
            html += '<td><select name="expense_type_id[]" class="form-control">'+expense_opts+'</select></td>';
            html += '<td><input type="number" step="0.01" name="qty[]" class="form-control qty" value="1.00" required></td>';
            html += '<td><input type="number" step="0.01" name="rate[]" class="form-control purchase_rate" required></td>';
            html += '<td><input type="number" step="0.01" name="amount[]" class="form-control amount" readonly></td>';
            html += '<td><button type="button" class="btn btn-sm btn-danger remove_row"><i class="fa fa-remove"></i></button></td>';
            html += '</tr>';
            $('#item_table tbody').append(html);
        });

        $(document).on('click', '.remove_row', function() {
            $(this).closest('tr').remove();
            calculateTotal();
        });

        $(document).on('keyup change', '.qty, .purchase_rate', function() {
            var row = $(this).closest('tr');
            var qty = parseFloat(row.find('.qty').val()) || 0;
            var rate = parseFloat(row.find('.purchase_rate').val()) || 0;
            var amount = qty * rate;
            row.find('.amount').val(amount.toFixed(2));
            calculateTotal();
        });
        
        $(document).on('keyup change', '.calc_net', function() {
            calculateTotal();
        });

        function calculateTotal() {
            var total = 0;
            $('.amount').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total_amount').val(total.toFixed(2));
            
            var discount = parseFloat($('#discount').val()) || 0;
            var gst = parseFloat($('#gst_amount').val()) || 0;
            
            var net = total - discount + gst;
            $('#net_amount').val(net.toFixed(2));
        }
    });

    function showAddSupplierModal() {
        $('#new_supplier_name').val('');
        $('#new_supplier_mobile').val('');
        $('#addSupplierModal').modal('show');
    }

    function submitNewSupplier() {
        var name = $('#new_supplier_name').val().trim();
        var mobile = $('#new_supplier_mobile').val().trim();
        if (name === '') {
            alert('Please enter a supplier name.');
            return;
        }
        $.ajax({
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
                    $('select[name="supplier_ledger_id"]').append(newOption).trigger('change');
                    $('#addSupplierModal').modal('hide');
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

<div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Supplier Name</label><small class="req"> *</small>
                    <input type="text" id="new_supplier_name" class="form-control" placeholder="Enter supplier/company name">
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="text" id="new_supplier_mobile" class="form-control" placeholder="Enter contact number">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitNewSupplier()">Save</button>
            </div>
        </div>
    </div>
</div>

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
    
    $(document).on('click', '.approve-purchase-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to approve this purchase entry?')) {
            $.ajax({
                url: base_url + 'accounts/purchaseentry/approve_purchase',
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
                        $('.purchase-entry-list').DataTable().ajax.reload(null, false);
                    } else {
                        showAccToast(res.message, 'error');
                    }
                }
            });
        }
    });

    $(document).on('click', '.reject-purchase-btn', function() {
        var id = $(this).data('id');
        var reason = prompt('Please enter a reason for rejection:');
        if (reason !== null) {
            if (reason.trim() === '') {
                alert('Reason is required for rejection.');
                return;
            }
            $.ajax({
                url: base_url + 'accounts/purchaseentry/approve_purchase',
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
                        $('.purchase-entry-list').DataTable().ajax.reload(null, false);
                    } else {
                        showAccToast(res.message, 'error');
                    }
                }
            });
        }
    });
});
</script>