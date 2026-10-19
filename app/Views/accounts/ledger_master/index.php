<?php if (isset($_GET['layout']) && $_GET['layout'] == 'iframe'): ?>
<style>
    .main-header, .main-sidebar, .content-header, .main-footer { display: none !important; }
    .content-wrapper { margin-left: 0 !important; padding-top: 0 !important; min-height: 100vh !important; background: #fff !important; }
    .box { border-top: none !important; box-shadow: none !important; margin-bottom: 0; }
    .col-md-8 { display: none !important; } /* Hide the list */
    .col-md-4 { width: 100% !important; } /* Expand form */
</style>
<?php endif; ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calculator"></i> <?php echo $this->lang->line('accounts'); ?></h1>
    </section>
    <section class="content" <?php if(isset($_GET['layout']) && $_GET['layout'] == 'iframe') echo 'style="padding: 0 !important;"'; ?>>
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('acc_ledger_master', 'can_add')) { ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_ledger'); ?></h3>
                        </div>
                        <form action="<?php echo site_url('accounts/ledgermaster/index') . (isset($_GET['layout']) ? '?layout='.$_GET['layout'] : ''); ?>" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('ledger_group'); ?></label><small class="req"> *</small>
                                    <select name="group_id" class="form-control" id="group_id">
                                        <option value=""><?php echo $this->lang->line('select_group'); ?></option>
                                        <?php foreach ($ledger_groups as $group) { ?>
                                            <option value="<?php echo $group['id'] ?>" <?php echo set_select('group_id', $group['id']); ?>><?php echo $group['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('group_id'); ?></span>
                                </div>
                                
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('ledger_name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" name="name" type="text" class="form-control" value="<?php echo set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('mobile'); ?></label>
                                    <input name="mobile" type="text" class="form-control" value="<?php echo set_value('mobile'); ?>" />
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('email'); ?></label>
                                    <input name="email" type="text" class="form-control" value="<?php echo set_value('email'); ?>" />
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('address'); ?></label>
                                    <textarea name="address" class="form-control"><?php echo set_value('address'); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('state'); ?></label>
                                    <input name="state" type="text" class="form-control" value="<?php echo set_value('state'); ?>" />
                                </div>
                                
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('gst_no'); ?></label>
                                    <input name="gst_no" type="text" class="form-control" value="<?php echo set_value('gst_no'); ?>" />
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('pan_no'); ?></label>
                                    <input name="pan_no" type="text" class="form-control" value="<?php echo set_value('pan_no'); ?>" />
                                </div>
                                
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('aadhar_no'); ?></label>
                                    <input name="aadhar_no" type="text" class="form-control" value="<?php echo set_value('aadhar_no'); ?>" />
                                </div>

                                <div id="bank_details_section" style="display:none;">
                                    <hr>
                                     <div class="form-group">
                                         <label><?php echo $this->lang->line('bank'); ?></label>
                                         <div class="input-group">
                                             <select name="bank_id" class="form-control" style="width: 100%;">
                                                 <option value=""><?php echo $this->lang->line('select_bank'); ?></option>
                                                 <?php foreach ($banks as $bank) { ?>
                                                     <option value="<?php echo $bank['id'] ?>" <?php echo set_select('bank_id', $bank['id']); ?>><?php echo $bank['name'] ?></option>
                                                 <?php } ?>
                                             </select>
                                             <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="showAddBankModal()">
                                                 <i class="fa fa-plus text-primary"></i>
                                             </div>
                                         </div>
                                     </div>
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('account_no'); ?></label>
                                        <input name="account_no" type="text" class="form-control" value="<?php echo set_value('account_no'); ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('ifsc_code'); ?></label>
                                        <input name="ifsc_code" type="text" class="form-control" value="<?php echo set_value('ifsc_code'); ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('branch'); ?></label>
                                        <input name="branch" type="text" class="form-control" value="<?php echo set_value('branch'); ?>" />
                                    </div>
                                </div>
                                <hr>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('opening_balance'); ?></label>
                                    <input name="opening_balance" type="number" step="0.01" class="form-control" value="<?php echo set_value('opening_balance', '0.00'); ?>" />
                                </div>
                                
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('opening_type'); ?></label>
                                    <select name="opening_type" class="form-control">
                                        <option value="Dr" <?php echo set_select('opening_type', 'Dr'); ?>><?php echo $this->lang->line('dr'); ?></option>
                                        <option value="Cr" <?php echo set_select('opening_type', 'Cr'); ?>><?php echo $this->lang->line('cr'); ?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('opening_date'); ?></label>
                                    <input name="opening_date" type="text" class="form-control date" value="<?php echo set_value('opening_date'); ?>" readonly="readonly" />
                                </div>

                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-<?php echo ($this->rbac->hasPrivilege('acc_ledger_master', 'can_add')) ? '8' : '12'; ?>">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('ledger_list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover ledger-master-list">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('ledger_name'); ?></th>
                                        <th><?php echo $this->lang->line('ledger_group'); ?></th>
                                        <th><?php echo $this->lang->line('mobile'); ?></th>
                                        <th><?php echo $this->lang->line('opening_balance'); ?></th>
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
        </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        initDatatable('ledger-master-list', 'accounts/ledgermaster/getlist', [], [], 100);
        
        $('#group_id').change(function() {
            var val = $(this).find('option:selected').text();
            if (val.toLowerCase() === 'bank account' || val.toLowerCase() === 'bank accounts') {
                $('#bank_details_section').slideDown();
            } else {
                $('#bank_details_section').slideUp();
            }
        });
        
        // Trigger on load
        $('#group_id').trigger('change');
    });

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
        $.ajax({
            url: '<?php echo site_url("accounts/ledgermaster/addbank"); ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                name: name,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(res) {
                if (res.status === 'success') {
                    var newOption = new Option(res.name, res.id, true, true);
                    $('select[name="bank_id"]').append(newOption).trigger('change');
                    $('#addBankModal').modal('hide');
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

<div class="modal fade" id="addBankModal" tabindex="-1" role="dialog" aria-labelledby="addBankModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addBankModalLabel">Add Bank Name</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Bank Name</label><small class="req"> *</small>
                    <input type="text" id="new_bank_name" class="form-control" placeholder="Enter bank name e.g. Chase Bank">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitNewBank()">Save</button>
            </div>
        </div>
    </div>
</div>
