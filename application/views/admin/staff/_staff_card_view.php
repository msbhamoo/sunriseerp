<style>
.staff-card-grid {
    display: flex;
    flex-wrap: wrap;
}
.staff-card-grid > [class*='col-'] {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}
.staffinfo-box {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 15px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.staffinfo-box:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border-color: #cbd5e1;
}
.staff-card-body {
    flex-grow: 1;
}
</style>

<div class="row staff-card-grid">
<?php if (empty($resultlist)) { ?>
    <div class="col-md-12">
        <div class="alert alert-info text-center">
            <i class="fa fa-info-circle"></i> <?php echo $this->lang->line('no_record_found'); ?>
        </div>
    </div>
<?php } else {
    $userdata = $this->customlib->getUserData();
    foreach ($resultlist as $staff) {
        if (!empty($staff["image"])) {
            $image = $staff["image"];
        } else {
            $image = ($staff['gender'] == 'Male') ? "default_male.jpg" : "default_female.jpg";
        }
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="staffinfo-box">
                <div class="staff-card-body">
                    <div class="text-center mb10">
                        <img src="<?php echo $this->media_storage->getImageURL("uploads/staff_images/" . $image); ?>" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #f1f5f9;" />
                    </div>
                    <div class="text-center">
                        <h5 style="margin: 8px 0 4px; font-weight: 600; font-size: 16px;">
                            <a href="<?php echo base_url() . "admin/staff/profile/" . $staff["id"]; ?>" style="color: #1e293b; text-decoration: none;">
                                <?php echo $staff["name"] . " " . $staff["surname"]; ?>
                            </a>
                        </h5>
                        <span class="label label-primary" style="background-color: #3b82f6; font-size: 11px; font-weight: 500; border-radius: 12px; padding: 3px 8px;">
                            <?php echo !empty($staff["user_type"]) ? $staff["user_type"] : 'Staff'; ?>
                        </span>
                    </div>
                    <hr style="margin: 12px 0; border-top: 1px solid #f1f5f9;">
                    <div style="font-size: 12px; color: #64748b; line-height: 1.8;">
                        <div><i class="fa fa-id-badge text-muted" style="width: 16px;"></i> <strong>ID:</strong> <?php echo $staff["employee_id"]; ?></div>
                        <?php if (!empty($staff["contact_no"])) { ?>
                            <div><i class="fa fa-phone text-muted" style="width: 16px;"></i> <strong>Phone:</strong> <?php echo $staff["contact_no"]; ?></div>
                        <?php } ?>
                        <?php if (!empty($staff["department"])) { ?>
                            <div><i class="fa fa-building-o text-muted" style="width: 16px;"></i> <strong>Dept:</strong> <?php echo $staff["department"]; ?></div>
                        <?php } ?>
                        <?php if (!empty($staff["designation"])) { ?>
                            <div><i class="fa fa-user-md text-muted" style="width: 16px;"></i> <strong>Desig:</strong> <?php echo $staff["designation"]; ?></div>
                        <?php } ?>
                    </div>
                </div>

                <div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid #f1f5f9; text-align: right;">
                    <?php if (($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) || ($userdata["id"] == $staff["id"])) { ?>
                        <a href="<?php echo base_url() . "admin/staff/profile/" . $staff["id"]; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
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
                        <a href="<?php echo base_url() . "admin/staff/edit/" . $staff["id"]; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php }
} ?>
</div>
