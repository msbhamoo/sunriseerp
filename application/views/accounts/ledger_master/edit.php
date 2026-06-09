<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calculator"></i> <?php echo $this->lang->line('accounts'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('acc_ledger_master', 'can_edit')) { ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_ledger'); ?></h3>
                        </div>
                        <form action="<?php echo site_url('accounts/ledgermaster/edit/' . $id) ?>" method="post" accept-charset="utf-8">
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
                                            <option value="<?php echo $group['id'] ?>" <?php echo set_select('group_id', $group['id'], ($ledger['group_id'] == $group['id'])); ?>><?php echo $group['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('group_id'); ?></span>
                                </div>
                                
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('ledger_name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" name="name" type="text" class="form-control" value="<?php echo set_value('name', $ledger['name']); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('mobile'); ?></label>
                                    <input name="mobile" type="text" class="form-control" value="<?php echo set_value('mobile', $ledger['mobile']); ?>" />
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('email'); ?></label>
                                    <input name="email" type="text" class="form-control" value="<?php echo set_value('email', $ledger['email']); ?>" />
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('address'); ?></label>
                                    <textarea name="address" class="form-control"><?php echo set_value('address', $ledger['address']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('state'); ?></label>
                                    <input name="state" type="text" class="form-control" value="<?php echo set_value('state', $ledger['state']); ?>" />
                                </div>
                                
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('gst_no'); ?></label>
                                    <input name="gst_no" type="text" class="form-control" value="<?php echo set_value('gst_no', $ledger['gst_no']); ?>" />
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('pan_no'); ?></label>
                                    <input name="pan_no" type="text" class="form-control" value="<?php echo set_value('pan_no', $ledger['pan_no']); ?>" />
                                </div>
                                
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('aadhar_no'); ?></label>
                                    <input name="aadhar_no" type="text" class="form-control" value="<?php echo set_value('aadhar_no', $ledger['aadhar_no']); ?>" />
                                </div>

                                <div id="bank_details_section" style="display:none;">
                                    <hr>
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('bank'); ?></label>
                                        <select name="bank_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select_bank'); ?></option>
                                            <?php foreach ($banks as $bank) { ?>
                                                <option value="<?php echo $bank['id'] ?>" <?php echo set_select('bank_id', $bank['id'], ($ledger['bank_id'] == $bank['id'])); ?>><?php echo $bank['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('account_no'); ?></label>
                                        <input name="account_no" type="text" class="form-control" value="<?php echo set_value('account_no', $ledger['account_no']); ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('ifsc_code'); ?></label>
                                        <input name="ifsc_code" type="text" class="form-control" value="<?php echo set_value('ifsc_code', $ledger['ifsc_code']); ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('branch'); ?></label>
                                        <input name="branch" type="text" class="form-control" value="<?php echo set_value('branch', $ledger['branch']); ?>" />
                                    </div>
                                </div>
                                <hr>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('opening_balance'); ?></label>
                                    <input name="opening_balance" type="number" step="0.01" class="form-control" value="<?php echo set_value('opening_balance', $ledger['opening_balance']); ?>" />
                                </div>
                                
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('opening_type'); ?></label>
                                    <select name="opening_type" class="form-control">
                                        <option value="Dr" <?php echo set_select('opening_type', 'Dr', ($ledger['opening_type'] == 'Dr')); ?>><?php echo $this->lang->line('dr'); ?></option>
                                        <option value="Cr" <?php echo set_select('opening_type', 'Cr', ($ledger['opening_type'] == 'Cr')); ?>><?php echo $this->lang->line('cr'); ?></option>
                                    </select>
                                </div>
                                
                                <?php $opening_date = ($ledger['opening_date'] && $ledger['opening_date'] != '0000-00-00') ? date($this->customlib->getSchoolDateFormat(), strtotime($ledger['opening_date'])) : ''; ?>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('opening_date'); ?></label>
                                    <input name="opening_date" type="text" class="form-control date" value="<?php echo set_value('opening_date', $opening_date); ?>" readonly="readonly" />
                                </div>

                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-<?php echo ($this->rbac->hasPrivilege('acc_ledger_master', 'can_edit')) ? '8' : '12'; ?>">
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
</script>
