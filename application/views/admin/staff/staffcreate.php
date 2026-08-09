<div class="content-wrapper">
    <section class="content-header" style="margin-bottom: 15px;">
        <h1 style="font-weight: 700; color: #1e293b; font-size: 24px;">
            <i class="fa fa-user-plus text-primary" style="margin-right: 8px;"></i> <?php echo $this->lang->line('human_resource'); ?>
            <small style="font-size: 14px; color: #64748b; font-weight: 400;">/ Add New Staff Member</small>
            <a class="btn btn-sm btn-primary pull-right" href="<?php echo base_url(); ?>admin/staff/import" style="border-radius: 6px; font-weight: 500; padding: 6px 16px;">
                <i class="fa fa-upload"></i> <?php echo $this->lang->line('import_staff'); ?>
            </a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="border-top: 3px solid #3b82f6; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); background: #ffffff;">
                    <form id="form1" action="<?php echo site_url('admin/staff/create') ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body" style="padding: 25px;">
                            
                            <div class="alert alert-info" style="border-radius: 8px; background-color: #eff6ff; border-color: #bfdbfe; color: #1e40af; padding: 12px 18px; margin-bottom: 25px;">
                                <i class="fa fa-info-circle" style="font-size: 16px; margin-right: 6px;"></i>
                                Staff email is their login username. Password is generated automatically and sent to the staff email. Superadmin can change staff passwords on their profile page.
                            </div>

                            <!-- Basic Information Card -->
                            <div class="panel panel-default" style="border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 25px; box-shadow: none;">
                                <div class="panel-heading" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 12px 20px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                                    <h3 class="panel-title" style="font-weight: 600; color: #0f172a; font-size: 16px;">
                                        <i class="fa fa-user text-info" style="margin-right: 8px;"></i> <?php echo $this->lang->line('basic_information'); ?>
                                    </h3>
                                </div>
                                <div class="panel-body" style="padding: 20px;">
                                    
                                    <?php 
                                        $errors = [];
                                        if (form_error('validate_resource')) {
                                            $errors[] = form_error('validate_resource');
                                        }
                                        if (form_error('validate_storage')) {
                                            $errors[] = form_error('validate_storage');
                                        }
                                        if (!empty($errors)): ?>
                                            <div class="alert alert-danger" style="border-radius: 6px;">
                                                <ul style="margin-bottom: 0;">
                                                    <?php foreach ($errors as $error): ?>
                                                        <li><?php echo $error; ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif;
                                    ?>

                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                                    <?php } ?>
                                    <?php echo $this->customlib->getCSRF(); ?>

                                    <div class="row">
                                        <?php if (!$staffid_auto_insert) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('staff_id'); ?> <small class="text-danger">*</small></label>
                                                    <input autofocus="" id="employee_id" name="employee_id" type="text" class="form-control" value="<?php echo set_value('employee_id') ?>" style="border-radius: 6px;" />
                                                    <span class="text-danger"><?php echo form_error('employee_id'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('role'); ?> <small class="text-danger">*</small></label>
                                                <select id="role" name="role" class="form-control" style="border-radius: 6px;">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($roles as $key => $role) { ?>
                                                        <option value="<?php echo $role['id'] ?>" <?php echo set_select('role', $role['id'], set_value('role')); ?>><?php echo $role["name"] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('role'); ?></span>
                                            </div>
                                        </div>

                                        <?php if ($sch_setting->staff_designation) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('designation'); ?></label>
                                                    <select id="designation" name="designation" class="form-control" style="border-radius: 6px;">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($designation as $key => $value) { ?>
                                                            <option value="<?php echo $value["id"] ?>" <?php echo set_select('designation', $value['id'], set_value('designation')); ?>><?php echo $value["designation"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('designation'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($sch_setting->staff_department) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('department'); ?></label>
                                                    <select id="department" name="department" class="form-control" style="border-radius: 6px;">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($department as $key => $value) { ?>
                                                            <option value="<?php echo $value["id"] ?>" <?php echo set_select('department', $value['id'], set_value('department')); ?>><?php echo $value["department_name"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('department'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('first_name'); ?> <small class="text-danger">*</small></label>
                                                <input id="name" name="name" type="text" class="form-control" value="<?php echo set_value('name') ?>" style="border-radius: 6px;" />
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->staff_last_name) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('last_name'); ?></label>
                                                    <input id="surname" name="surname" type="text" class="form-control" value="<?php echo set_value('surname') ?>" style="border-radius: 6px;" />
                                                    <span class="text-danger"><?php echo form_error('surname'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->staff_father_name) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('father_name'); ?></label>
                                                    <input id="father_name" name="father_name" type="text" class="form-control" value="<?php echo set_value('father_name') ?>" style="border-radius: 6px;" />
                                                    <span class="text-danger"><?php echo form_error('father_name'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->staff_mother_name) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('mother_name'); ?></label>
                                                    <input id="mother_name" name="mother_name" type="text" class="form-control" value="<?php echo set_value('mother_name') ?>" style="border-radius: 6px;" />
                                                    <span class="text-danger"><?php echo form_error('mother_name'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('email'); ?> (Username) <small class="text-danger">*</small></label>
                                                <input id="email" name="email" type="text" class="form-control" value="<?php echo set_value('email') ?>" style="border-radius: 6px;" />
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('gender'); ?> <small class="text-danger">*</small></label>
                                                <select class="form-control" name="gender" style="border-radius: 6px;">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($genderList as $key => $value) { ?>
                                                        <option value="<?php echo $key; ?>" <?php echo set_select('gender', $key, set_value('gender')); ?>><?php echo $value; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('date_of_birth'); ?> <small class="text-danger">*</small></label>
                                                <input id="dob" name="dob" type="text" class="form-control date" value="<?php echo set_value('dob') ?>" style="border-radius: 6px;" />
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->staff_date_of_joining) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('date_of_joining'); ?></label>
                                                    <input id="date_of_joining" name="date_of_joining" type="text" class="form-control date" value="<?php echo set_value('date_of_joining') ?>" style="border-radius: 6px;" />
                                                    <span class="text-danger"><?php echo form_error('date_of_joining'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <?php if ($sch_setting->staff_phone) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('phone'); ?></label>
                                                    <input id="mobileno" name="contactno" type="text" class="form-control" value="<?php echo set_value('contactno') ?>" style="border-radius: 6px;" />
                                                    <span class="text-danger"><?php echo form_error('contactno'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->staff_emergency_contact) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('emergency_contact_number'); ?></label>
                                                    <input id="mobileno" name="emergency_no" type="text" class="form-control" value="<?php echo set_value('emergency_no') ?>" style="border-radius: 6px;" />
                                                    <span class="text-danger"><?php echo form_error('emergency_no'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->staff_marital_status) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('marital_status'); ?></label>
                                                    <select class="form-control" name="marital_status" style="border-radius: 6px;">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php foreach ($marital_status as $makey => $mavalue) { ?>
                                                            <option value="<?php echo $mavalue ?>" <?php echo set_select('marital_status', $mavalue, set_value('marital_status')); ?>><?php echo $mavalue; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('marital_status'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->staff_photo) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('photo'); ?></label>
                                                    <input class="filestyle form-control" type='file' name='file' id="file" size='20' style="border-radius: 6px;" />
                                                    <span class="text-danger"><?php echo form_error('file'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <?php if ($sch_setting->staff_current_address) { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('current'); ?> <?php echo $this->lang->line('address'); ?></label>
                                                    <textarea name="address" class="form-control" rows="2" style="border-radius: 6px;"><?php echo set_value('address'); ?></textarea>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->staff_permanent_address) { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('permanent_address'); ?></label>
                                                    <textarea name="permanent_address" class="form-control" rows="2" style="border-radius: 6px;"><?php echo set_value('permanent_address'); ?></textarea>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <?php if ($sch_setting->staff_qualification) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('qualification'); ?></label>
                                                    <select id="qualification" name="qualification" class="form-control" style="border-radius: 6px;">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php if (!empty($qualification_list)) { foreach ($qualification_list as $q) { ?>
                                                            <option value="<?php echo htmlspecialchars($q['qualification_name']); ?>" <?php echo set_select('qualification', $q['qualification_name']); ?>><?php echo htmlspecialchars($q['qualification_name']); ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('qualification'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($sch_setting->staff_work_experience) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('work_experience'); ?></label>
                                                    <select id="work_exp" name="work_exp" class="form-control" style="border-radius: 6px;">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php if (!empty($workexperience_list)) { foreach ($workexperience_list as $e) { ?>
                                                            <option value="<?php echo htmlspecialchars($e['work_experience']); ?>" <?php echo set_select('work_exp', $e['work_experience']); ?>><?php echo htmlspecialchars($e['work_experience']); ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('work_exp'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($sch_setting->staff_note) { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('note'); ?></label>
                                                    <textarea name="note" class="form-control" rows="2" style="border-radius: 6px;"><?php echo set_value('note'); ?></textarea>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <?php echo display_custom_fields('staff'); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Details Accordion / Sections -->
                            <div class="panel-group" id="accordion_more_details" style="margin-bottom: 20px;">
                                <div class="panel panel-default" style="border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden;">
                                    <div class="panel-heading" style="background: #f1f5f9; padding: 12px 20px;">
                                        <h4 class="panel-title" style="font-weight: 600;">
                                            <a data-toggle="collapse" data-parent="#accordion_more_details" href="#collapseMoreDetails" class="collapsed" style="color: #1e293b; display: block; text-decoration: none;">
                                                <i class="fa fa-plus-circle text-primary" style="margin-right: 8px;"></i> <?php echo $this->lang->line('add_more_details'); ?> (Payroll, Leaves, Bank Account & Documents)
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseMoreDetails" class="panel-collapse collapse">
                                        <div class="panel-body" style="padding: 20px; background: #ffffff;">
                                            
                                            <!-- Payroll Section -->
                                            <div class="panel panel-default" style="border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 20px;">
                                                <div class="panel-heading" style="background: #f8fafc; font-weight: 600; color: #334155;">
                                                    <i class="fa fa-money text-success" style="margin-right: 6px;"></i> <?php echo $this->lang->line('payroll'); ?>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <?php if ($sch_setting->staff_epf_no) { ?>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('epf_no'); ?></label>
                                                                    <input id="epf_no" name="epf_no" type="text" class="form-control" value="<?php echo set_value('epf_no') ?>" style="border-radius: 6px;" />
                                                                    <span class="text-danger"><?php echo form_error('epf_no'); ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if ($sch_setting->staff_basic_salary) { ?>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('basic_salary'); ?></label>
                                                                    <input type="text" class="form-control" name="basic_salary" value="<?php echo set_value('basic_salary') ?>" style="border-radius: 6px;">
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if ($sch_setting->staff_contract_type) { ?>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('contract_type'); ?></label>
                                                                    <select class="form-control" name="contract_type" style="border-radius: 6px;">
                                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                                        <?php foreach ($contract_type as $key => $value) { ?>
                                                                            <option value="<?php echo $key ?>" <?php echo set_select('contract_type', $key, set_value('contract_type')); ?>><?php echo $value ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    <span class="text-danger"><?php echo form_error('contract_type'); ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if ($sch_setting->staff_work_shift) { ?>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('work_shift'); ?></label>
                                                                    <input id="shift" name="shift" type="text" class="form-control" value="<?php echo set_value('shift') ?>" style="border-radius: 6px;" />
                                                                    <span class="text-danger"><?php echo form_error('shift'); ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if ($sch_setting->staff_work_location) { ?>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('work_location'); ?></label>
                                                                    <input id="location" name="location" type="text" class="form-control" value="<?php echo set_value('location') ?>" style="border-radius: 6px;" />
                                                                    <span class="text-danger"><?php echo form_error('location'); ?></span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Leaves Section -->
                                            <?php if ($sch_setting->staff_leaves) { ?>
                                                <div class="panel panel-default" style="border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 20px;">
                                                    <div class="panel-heading" style="background: #f8fafc; font-weight: 600; color: #334155;">
                                                        <i class="fa fa-calendar-check-o text-warning" style="margin-right: 6px;"></i> <?php echo $this->lang->line('leaves'); ?>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <?php foreach ($leavetypeList as $key => $leave) { ?>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label style="font-weight: 500; color: #334155;"><?php echo $leave["type"]; ?></label>
                                                                        <input name="leave_type[]" type="hidden" readonly class="form-control" value="<?php echo $leave['id'] ?>" />
                                                                        <input name="alloted_leave_<?php echo $leave['id'] ?>" placeholder="<?php echo $this->lang->line('number_of_leaves'); ?>" type="text" class="form-control" style="border-radius: 6px;" />
                                                                        <span class="text-danger"><?php echo form_error('alloted_leave'); ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <!-- Bank Account Details Section -->
                                            <?php if ($sch_setting->staff_account_details) { ?>
                                                <div class="panel panel-default" style="border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 20px;">
                                                    <div class="panel-heading" style="background: #f8fafc; font-weight: 600; color: #334155;">
                                                        <i class="fa fa-university text-primary" style="margin-right: 6px;"></i> <?php echo $this->lang->line('bank_account_details'); ?>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('account_title'); ?></label>
                                                                    <input id="account_title" name="account_title" type="text" class="form-control" value="<?php echo set_value('account_title') ?>" style="border-radius: 6px;" />
                                                                    <span class="text-danger"><?php echo form_error('account_title'); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('bank_account_number'); ?></label>
                                                                    <input id="bank_account_no" name="bank_account_no" type="text" class="form-control" value="<?php echo set_value('bank_account_no') ?>" style="border-radius: 6px;" />
                                                                    <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('bank_name'); ?></label>
                                                                    <input id="bank_name" name="bank_name" type="text" class="form-control" value="<?php echo set_value('bank_name') ?>" style="border-radius: 6px;" />
                                                                    <span class="text-danger"><?php echo form_error('bank_name'); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('ifsc_code'); ?></label>
                                                                    <input id="ifsc_code" name="ifsc_code" type="text" class="form-control" value="<?php echo set_value('ifsc_code') ?>" style="border-radius: 6px;" />
                                                                    <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('bank_branch_name'); ?></label>
                                                                    <input id="bank_branch" name="bank_branch" type="text" class="form-control" value="<?php echo set_value('bank_branch') ?>" style="border-radius: 6px;" />
                                                                    <span class="text-danger"><?php echo form_error('bank_branch'); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <!-- Social Media Section -->
                                            <?php if ($sch_setting->staff_social_media) { ?>
                                                <div class="panel panel-default" style="border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 20px;">
                                                    <div class="panel-heading" style="background: #f8fafc; font-weight: 600; color: #334155;">
                                                        <i class="fa fa-share-alt text-info" style="margin-right: 6px;"></i> <?php echo $this->lang->line('social_media'); ?>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('facebook_url'); ?></label>
                                                                    <input name="facebook" type="text" class="form-control" value="<?php echo set_value('facebook') ?>" style="border-radius: 6px;" />
                                                                    <span class="text-danger"><?php echo form_error('facebook'); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('twitter_url'); ?></label>
                                                                    <input name="twitter" type="text" class="form-control" value="<?php echo set_value('twitter') ?>" style="border-radius: 6px;" />
                                                                    <span class="text-danger"><?php echo form_error('twitter'); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('linkedin_url'); ?></label>
                                                                    <input name="linkedin" type="text" class="form-control" value="<?php echo set_value('linkedin') ?>" style="border-radius: 6px;" />
                                                                    <span class="text-danger"><?php echo form_error('linkedin'); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label style="font-weight: 500; color: #334155;"><?php echo $this->lang->line('instagram_url'); ?></label>
                                                                    <input id="instagram" name="instagram" type="text" class="form-control" value="<?php echo set_value('instagram') ?>" style="border-radius: 6px;" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <!-- Upload Documents Section -->
                                            <?php if ($sch_setting->staff_upload_documents) { ?>
                                                <div id='upload_documents_hide_show'>
                                                    <div class="panel panel-default" style="border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 0;">
                                                        <div class="panel-heading" style="background: #f8fafc; font-weight: 600; color: #334155;">
                                                            <i class="fa fa-folder-open text-danger" style="margin-right: 6px;"></i> <?php echo $this->lang->line('upload_documents'); ?>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr style="background: #f8fafc;">
                                                                                <th style="width: 10px">#</th>
                                                                                <th><?php echo $this->lang->line('title'); ?></th>
                                                                                <th><?php echo $this->lang->line('documents'); ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1.</td>
                                                                                <td><?php echo $this->lang->line('resume'); ?></td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='first_doc' id="doc1">
                                                                                    <span class="text-danger"><?php echo form_error('first_doc'); ?></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3.</td>
                                                                                <td><?php echo $this->lang->line('resignation_letter'); ?></td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='third_doc' id="doc3">
                                                                                    <span class="text-danger"><?php echo form_error('third_doc'); ?></span>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr style="background: #f8fafc;">
                                                                                <th style="width: 10px">#</th>
                                                                                <th><?php echo $this->lang->line('title'); ?></th>
                                                                                <th><?php echo $this->lang->line('documents'); ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>2.</td>
                                                                                <td><?php echo $this->lang->line('joining_letter'); ?></td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='second_doc' id="doc2">
                                                                                    <span class="text-danger"><?php echo form_error('second_doc'); ?></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4.</td>
                                                                                <td><?php echo $this->lang->line('other_documents'); ?><input type="hidden" name='fourth_title' class="form-control" placeholder="Other Documents"></td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='fourth_doc' id="doc4">
                                                                                    <span class="text-danger"><?php echo form_error('fourth_doc'); ?></span>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer" style="padding: 15px 25px; background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                            <button type="submit" id="submitbtn" class="btn btn-primary pull-right btn-lg" style="font-weight: 600; padding: 10px 28px; border-radius: 6px;">
                                <i class="fa fa-check-circle" style="margin-right: 6px;"></i> <?php echo $this->lang->line('save'); ?> Staff Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(function(){
        $('#form1').submit(function() {
            $("#submitbtn").button('loading');
        });
    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>