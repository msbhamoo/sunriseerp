<style>
.staff-card-grid {
    display: flex;
    flex-wrap: wrap;
    margin: -8px;
}
.staff-card-grid > [class*='col-'] {
    display: flex;
    flex-direction: column;
    padding: 8px;
    margin-bottom: 0;
}
.staffinfo-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
}
.staffinfo-box:hover {
    box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.08), 0 4px 6px -2px rgba(15, 23, 42, 0.04);
    border-color: #cbd5e1;
    transform: translateY(-2px);
}
.staff-card-body {
    flex-grow: 1;
}
.staff-avatar-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 10px;
}
.staff-avatar-img {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e2e8f0;
    background: #f8fafc;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.06);
}
.staff-name-link {
    font-weight: 800;
    font-size: 15px;
    color: #0f172a;
    text-decoration: none;
    transition: color 0.15s ease;
}
.staff-name-link:hover {
    color: #114B5F;
    text-decoration: none;
}
.staff-role-badge {
    background-color: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    font-size: 11px;
    font-weight: 700;
    border-radius: 6px;
    padding: 3px 8px;
    display: inline-block;
    margin-top: 4px;
}
.staff-meta-list {
    font-size: 12px;
    color: #475569;
    line-height: 1.7;
    margin-top: 12px;
    background: #f8fafc;
    border-radius: 8px;
    padding: 10px 12px;
    border: 1px solid #f1f5f9;
}
.staff-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.staff-meta-item i {
    width: 14px;
    color: #114B5F;
    font-size: 12px;
    text-align: center;
}
.staff-meta-item strong {
    color: #64748b;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.staff-meta-item span {
    color: #0f172a;
    font-weight: 600;
    overflow: hidden;
    text-overflow: ellipsis;
}
.staff-actions-bar {
    margin-top: 14px;
    padding-top: 10px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
    gap: 6px;
}
.staff-action-btn {
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 600;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-decoration: none !important;
    transition: all 0.15s ease;
}
.staff-action-btn:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #94a3b8;
}
.staff-action-btn-primary {
    background: #114B5F;
    border-color: #114B5F;
    color: #ffffff;
}
.staff-action-btn-primary:hover {
    background: #0c3847;
    border-color: #0c3847;
    color: #ffffff;
}
</style>

<div class="row staff-card-grid">
<?php if (empty($resultlist)) { ?>
    <div class="col-md-12">
        <div class="alert alert-info text-center" style="border-radius: 8px; background-color: #f0fdfa; border-color: #99f6e4; color: #0f766e;">
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
                    <?php
                    $can_view_profile = ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) || ($userdata["id"] == $staff["id"]);
                    ?>
                    <div class="text-center">
                        <div class="staff-avatar-wrap">
                            <?php if ($can_view_profile) { ?>
                                <a href="<?php echo base_url() . "admin/staff/profile/" . $staff["id"]; ?>">
                                    <img src="<?php echo $this->media_storage->getImageURL("uploads/staff_images/" . $image); ?>" class="staff-avatar-img" alt="Staff photo" />
                                </a>
                            <?php } else { ?>
                                <img src="<?php echo $this->media_storage->getImageURL("uploads/staff_images/" . $image); ?>" class="staff-avatar-img" alt="Staff photo" />
                            <?php } ?>
                        </div>
                    </div>
                    <div class="text-center">
                        <h5 style="margin: 0 0 4px;">
                            <?php if ($can_view_profile) { ?>
                                <a href="<?php echo base_url() . "admin/staff/profile/" . $staff["id"]; ?>" class="staff-name-link">
                                    <?php echo $staff["name"] . " " . $staff["surname"]; ?>
                                </a>
                            <?php } else { ?>
                                <span class="staff-name-link" style="cursor: default; color: #0f172a;">
                                    <?php echo $staff["name"] . " " . $staff["surname"]; ?>
                                </span>
                            <?php } ?>
                        </h5>
                        <span class="staff-role-badge">
                            <?php echo !empty($staff["user_type"]) ? $staff["user_type"] : 'Staff'; ?>
                        </span>
                    </div>

                    <div class="staff-meta-list">
                        <div class="staff-meta-item">
                            <i class="fa fa-id-badge"></i>
                            <strong>ID:</strong>
                            <span><?php echo $staff["employee_id"]; ?></span>
                        </div>
                        <?php if (!empty($staff["contact_no"])) { ?>
                            <div class="staff-meta-item">
                                <i class="fa fa-phone"></i>
                                <strong>Phone:</strong>
                                <span><?php echo $staff["contact_no"]; ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($staff["department"])) { ?>
                            <div class="staff-meta-item">
                                <i class="fa fa-building-o"></i>
                                <strong>Dept:</strong>
                                <span><?php echo $staff["department"]; ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($staff["designation"])) { ?>
                            <div class="staff-meta-item">
                                <i class="fa fa-user-md"></i>
                                <strong>Desig:</strong>
                                <span><?php echo $staff["designation"]; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="staff-actions-bar">
                    <?php if (($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) || ($userdata["id"] == $staff["id"])) { ?>
                        <a href="<?php echo base_url() . "admin/staff/profile/" . $staff["id"]; ?>" class="staff-action-btn" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
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
                        <a href="<?php echo base_url() . "admin/staff/edit/" . $staff["id"]; ?>" class="staff-action-btn staff-action-btn-primary" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php }
} ?>
</div>
