<div class="table-responsive mailbox-messages overflow-visible">
    <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="dt-body-left dt-head-left"><?php echo $this->lang->line('staff_id'); ?></th>
                <th><?php echo $this->lang->line('name'); ?></th>
                <th><?php echo $this->lang->line('role'); ?></th>
                <th><?php echo $this->lang->line('department'); ?></th>
                <th><?php echo $this->lang->line('designation'); ?></th>
                <th><?php echo $this->lang->line('mobile_number'); ?></th>
                <?php if (!empty($fields)) {
                    foreach ($fields as $fields_value) { ?>
                        <th><?php echo $fields_value->name; ?></th>
                    <?php }
                } ?>
                <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($resultlist)) {
                $userdata = $this->customlib->getUserData();
                foreach ($resultlist as $staff) { ?>
                    <tr>
                        <td class="dt-body-left dt-head-left"><?php echo $staff['employee_id']; ?></td>
                        <td>
                            <a href="<?php echo base_url(); ?>admin/staff/profile/<?php echo $staff['id']; ?>">
                                <?php echo $staff['name'] . " " . $staff['surname']; ?>
                            </a>
                        </td>
                        <td><span class="label label-info"><?php echo !empty($staff['user_type']) ? $staff['user_type'] : ''; ?></span></td>
                        <td><?php echo !empty($staff['department']) ? $staff['department'] : ''; ?></td>
                        <td><?php echo !empty($staff['designation']) ? $staff['designation'] : ''; ?></td>
                        <td><?php echo !empty($staff['contact_no']) ? $staff['contact_no'] : ''; ?></td>
                        <?php if (!empty($fields)) {
                            foreach ($fields as $fields_value) {
                                $display_field = isset($staff[$fields_value->name]) ? $staff[$fields_value->name] : '';
                                ?>
                                <td><?php echo $display_field; ?></td>
                            <?php }
                        } ?>
                        <td class="pull-right white-space-nowrap">
                            <?php if (($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) || ($userdata["id"] == $staff["id"])) { ?>
                                <a href="<?php echo base_url(); ?>admin/staff/profile/<?php echo $staff['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                    <i class="fa fa-reorder"></i>
                                </a>
                            <?php } ?>
                            <?php
                            $can_edit = 0;
                            if (($staff["user_type"] == "Super Admin") && $userdata["id"] == $staff["id"]) {
                                $can_edit = 1;
                            } elseif (($this->rbac->hasPrivilege('staff', 'can_edit')) && ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view'))) {
                                $can_edit = 1;
                            }
                            if ($can_edit == 1) { ?>
                                <a href="<?php echo base_url(); ?>admin/staff/edit/<?php echo $staff['id'] ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
</div>
