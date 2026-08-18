<style>
.staff-list-table {
    border-collapse: separate !important;
    border-spacing: 0 !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    overflow: hidden !important;
    width: 100% !important;
}
.staff-list-table thead th {
    background: #f8fafc !important;
    color: #475569 !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    border-bottom: 1px solid #e2e8f0 !important;
    border-top: none !important;
    padding: 10px 12px !important;
}
.staff-list-table tbody td {
    padding: 10px 12px !important;
    font-size: 12.5px !important;
    border-color: #f1f5f9 !important;
    vertical-align: middle !important;
}
.staff-list-table tbody tr:hover td {
    background: #f8fafc !important;
}
.staff-list-name {
    font-weight: 700;
    color: #0f172a;
    text-decoration: none;
}
.staff-list-name:hover {
    color: #114B5F;
    text-decoration: none;
}
.staff-list-role {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    font-size: 11px;
    font-weight: 700;
    border-radius: 6px;
    padding: 2px 7px;
    display: inline-block;
}
</style>

<div class="table-responsive mailbox-messages overflow-visible">
    <table class="table table-hover example staff-list-table" cellspacing="0" width="100%">
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
                    <?php
                    $can_view_profile = ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) || ($userdata["id"] == $staff["id"]);
                    ?>
                    <tr>
                        <td class="dt-body-left dt-head-left"><strong style="color:#114B5F;"><?php echo $staff['employee_id']; ?></strong></td>
                        <td>
                            <?php if ($can_view_profile) { ?>
                                <a href="<?php echo base_url(); ?>admin/staff/profile/<?php echo $staff['id']; ?>" class="staff-list-name">
                                    <?php echo $staff['name'] . " " . $staff['surname']; ?>
                                </a>
                            <?php } else { ?>
                                <span class="staff-list-name" style="color: #0f172a; cursor: default;">
                                    <?php echo $staff['name'] . " " . $staff['surname']; ?>
                                </span>
                            <?php } ?>
                        </td>
                        <td><span class="staff-list-role"><?php echo !empty($staff['user_type']) ? $staff['user_type'] : ''; ?></span></td>
                        <td><?php echo !empty($staff['department']) ? $staff['department'] : '-'; ?></td>
                        <td><?php echo !empty($staff['designation']) ? $staff['designation'] : '-'; ?></td>
                        <td><?php echo !empty($staff['contact_no']) ? $staff['contact_no'] : '-'; ?></td>
                        <?php if (!empty($fields)) {
                            foreach ($fields as $fields_value) {
                                $display_field = isset($staff[$fields_value->name]) ? $staff[$fields_value->name] : '';
                                ?>
                                <td><?php echo $display_field; ?></td>
                            <?php }
                        } ?>
                        <td class="pull-right white-space-nowrap">
                            <?php if (($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) || ($userdata["id"] == $staff["id"])) { ?>
                                <a href="<?php echo base_url(); ?>admin/staff/profile/<?php echo $staff['id'] ?>" class="staff-action-btn" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                    <i class="fa fa-reorder"></i> View
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
                                <a href="<?php echo base_url(); ?>admin/staff/edit/<?php echo $staff['id'] ?>" class="staff-action-btn staff-action-btn-primary" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
</div>
