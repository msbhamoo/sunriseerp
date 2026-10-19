<style>
.staff-card-grid {
    display: flex;
    flex-wrap: wrap;
    margin: -10px;
}
.staff-card-grid > [class*='col-'] {
    display: flex;
    flex-direction: column;
    padding: 10px;
    margin-bottom: 0;
}
.staffinfo-box {
    background: #ffffff;
    border: 1px solid #eef2f6;
    border-radius: 16px;
    padding: 20px 18px 18px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    cursor: pointer;
    transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}
.staffinfo-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #114B5F 0%, #2092EC 100%);
    opacity: 0;
    transition: opacity 0.25s ease;
}
.staffinfo-box:hover {
    box-shadow: 0 16px 32px -8px rgba(17, 75, 95, 0.12), 0 4px 12px -2px rgba(15, 23, 42, 0.04);
    border-color: #cbd5e1;
    transform: translateY(-4px);
}
.staffinfo-box:hover::before {
    opacity: 1;
}

/* Top Action */
.staff-card-top-action {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 5;
}
.staff-icon-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: all 0.18s ease;
    text-decoration: none !important;
}
.staff-icon-btn:hover {
    background: #114B5F;
    color: #ffffff;
    border-color: #114B5F;
    box-shadow: 0 2px 8px rgba(17, 75, 95, 0.25);
    transform: scale(1.05);
}

.staff-card-body {
    flex-grow: 1;
}

/* Avatar Styling */
.staff-avatar-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 12px;
    padding: 3px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e2e8f0 0%, #f8fafc 100%);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    transition: transform 0.25s ease;
}
.staffinfo-box:hover .staff-avatar-wrap {
    transform: scale(1.04);
    background: linear-gradient(135deg, #114B5F 0%, #2092EC 100%);
}
.staff-avatar-img {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    object-fit: cover;
    border: 2.5px solid #ffffff;
    background: #f8fafc;
    display: block;
}

/* Staff Title & Role */
.staff-name-text {
    font-weight: 800;
    font-size: 15.5px;
    color: #0f172a;
    letter-spacing: -0.2px;
    line-height: 1.3;
    margin: 0;
    transition: color 0.15s ease;
    display: block;
}
.staffinfo-box:hover .staff-name-text {
    color: #114B5F;
}

.staff-role-badge {
    background: #f0fdfa;
    color: #0f766e;
    border: 1px solid #ccfbf1;
    font-size: 11px;
    font-weight: 700;
    border-radius: 20px;
    padding: 3px 10px;
    display: inline-block;
    margin-top: 6px;
    letter-spacing: 0.2px;
}
.staff-role-badge.badge-admin {
    background: #fdf4ff;
    color: #9333ea;
    border-color: #f5d0fe;
}
.staff-role-badge.badge-teacher {
    background: #ecfdf5;
    color: #059669;
    border-color: #a7f3d0;
}

/* Metadata List */
.staff-meta-box {
    margin-top: 16px;
    background: #f8fafc;
    border-radius: 10px;
    padding: 10px 12px;
    border: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    gap: 7px;
}
.staff-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px;
}
.staff-meta-label {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #64748b;
    font-weight: 600;
    font-size: 11.5px;
}
.staff-meta-label i {
    width: 14px;
    color: #114B5F;
    font-size: 12px;
    text-align: center;
}
.staff-meta-val {
    color: #0f172a;
    font-weight: 700;
    font-size: 12px;
    text-align: right;
    max-width: 140px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
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
        $can_view_profile = ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) || ($userdata["id"] == $staff["id"]);
        $profile_url = base_url() . "admin/staff/profile/" . $staff["id"];
        $can_edit = 0;
        if (($staff["user_type"] == "Super Admin") && $userdata["id"] == $staff["id"]) {
            $can_edit = 1;
        } elseif (($this->rbac->hasPrivilege('staff', 'can_edit')) && ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view'))) {
            $can_edit = 1;
        }

        $user_role_lower = strtolower((string)$staff["user_type"]);
        $role_badge_class = 'staff-role-badge';
        if (strpos($user_role_lower, 'admin') !== false || strpos($user_role_lower, 'principal') !== false) {
            $role_badge_class .= ' badge-admin';
        } elseif (strpos($user_role_lower, 'teacher') !== false || strpos($user_role_lower, 'faculty') !== false) {
            $role_badge_class .= ' badge-teacher';
        }
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="staffinfo-box" <?php if ($can_view_profile) { ?>onclick="window.location.href='<?php echo $profile_url; ?>'"<?php } ?>>
                <?php if ($can_edit == 1) { ?>
                    <div class="staff-card-top-action" onclick="event.stopPropagation();">
                        <a href="<?php echo base_url() . "admin/staff/edit/" . $staff["id"]; ?>" class="staff-icon-btn" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                <?php } ?>

                <div class="staff-card-body">
                    <div class="text-center">
                        <div class="staff-avatar-wrap">
                            <img src="<?php echo $this->media_storage->getImageURL("uploads/staff_images/" . $image); ?>" class="staff-avatar-img" alt="Staff photo" />
                        </div>
                    </div>
                    <div class="text-center">
                        <h5 class="staff-name-text">
                            <?php echo $staff["name"] . " " . $staff["surname"]; ?>
                        </h5>
                        <span class="<?php echo $role_badge_class; ?>">
                            <?php echo !empty($staff["user_type"]) ? $staff["user_type"] : 'Staff'; ?>
                        </span>
                    </div>

                    <div class="staff-meta-box">
                        <div class="staff-meta-row">
                            <span class="staff-meta-label"><i class="fa fa-id-badge"></i> Staff ID</span>
                            <span class="staff-meta-val"><?php echo $staff["employee_id"]; ?></span>
                        </div>
                        <?php if (!empty($staff["contact_no"])) { ?>
                            <div class="staff-meta-row">
                                <span class="staff-meta-label"><i class="fa fa-phone"></i> Phone</span>
                                <span class="staff-meta-val"><?php echo $staff["contact_no"]; ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($staff["department"])) { ?>
                            <div class="staff-meta-row">
                                <span class="staff-meta-label"><i class="fa fa-building-o"></i> Dept</span>
                                <span class="staff-meta-val"><?php echo $staff["department"]; ?></span>
                            </div>
                        <?php } ?>
                        <?php if (!empty($staff["designation"])) { ?>
                            <div class="staff-meta-row">
                                <span class="staff-meta-label"><i class="fa fa-user-md"></i> Designation</span>
                                <span class="staff-meta-val"><?php echo $staff["designation"]; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php }
} ?>
</div>
