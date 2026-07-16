<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<style type="text/css">
    .vehicle-ui-wrapper {
        font-family: 'Inter', sans-serif;
    }
    .modern-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        overflow: hidden;
    }
    .modern-header {
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #fdfdfd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modern-title {
        font-size: 16px;
        font-weight: 600;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modern-body {
        padding: 24px;
    }
    .custom-input {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .custom-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    .btn-premium {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-premium:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 8px -1px rgba(79, 70, 229, 0.4);
        color: white;
    }
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .modern-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    .modern-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .modern-table tbody tr {
        transition: background-color 0.2s ease;
    }
    .modern-table tbody tr:hover {
        background-color: #f8fafc;
    }
    .vehicle-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }
    .vehicle-card {
        flex: 1 1 calc(20% - 15px);
        min-width: 140px;
        border: 1px solid #f1f5f9;
        border-radius: 6px;
        transition: all 0.2s ease;
        text-align: center;
        padding: 24px 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .vehicle-card.status-active { background-color: #f0fdf4; border-color: #dcfce7; }
    .vehicle-card.status-active .vehicle-icon { color: #16a34a; }
    .vehicle-card.status-active .vehicle-title { color: #16a34a; font-weight: 600; font-size:14px; margin-top:12px;}
    .vehicle-card.status-active .vehicle-subtitle { color: #15803d; font-size: 12px; text-transform: uppercase; margin-top:4px;}
    
    .vehicle-card.status-maintenance { background-color: #fffbeb; border-color: #fef3c7; }
    .vehicle-card.status-maintenance .vehicle-icon { color: #d97706; }
    .vehicle-card.status-maintenance .vehicle-title { color: #d97706; font-weight: 600; font-size:14px; margin-top:12px;}
    .vehicle-card.status-maintenance .vehicle-subtitle { color: #b45309; font-size: 12px; text-transform: uppercase; margin-top:4px;}

    .vehicle-card.status-inactive { background-color: #fef2f2; border-color: #fee2e2; }
    .vehicle-card.status-inactive .vehicle-icon { color: #dc2626; }
    .vehicle-card.status-inactive .vehicle-title { color: #dc2626; font-weight: 600; font-size:14px; margin-top:12px;}
    .vehicle-card.status-inactive .vehicle-subtitle { color: #b91c1c; font-size: 12px; text-transform: uppercase; margin-top:4px;}

    .vehicle-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .vehicle-icon {
        font-size: 32px;
    }
    .alert-indicator {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #ef4444;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: bold;
        z-index: 10;
    }
    .action-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        opacity: 0;
        transition: opacity 0.2s ease;
        border-radius: 6px;
    }
    .vehicle-card:hover .action-overlay {
        opacity: 1;
    }
    .btn-icon {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.2s;
    }
    .btn-icon:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .btn-icon.edit:hover { color: #3b82f6; }
    .btn-icon.delete:hover { color: #ef4444; border-color:#fca5a5; background:#fef2f2;}
    .badge-soft {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .badge-blue { background: #e0f2fe; color: #0369a1; }
    .nav-tabs-custom { margin-bottom: 0; box-shadow: none; border-radius: 0; }
    .nav-tabs-custom > .nav-tabs { border-bottom-color: #e2e8f0; margin: 0; padding: 0 20px; background: #fff;}
    .nav-tabs-custom > .nav-tabs > li > a { border: none; padding: 15px 20px; color: #64748b; font-weight: 500; }
    .nav-tabs-custom > .nav-tabs > li.active > a { border-bottom: 2px solid #4f46e5; color: #4f46e5; }
    .tab-content { padding: 0; }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper vehicle-ui-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-truck"></i> <?php echo $this->lang->line('vehicle'); ?></h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="modern-card">
                    <div class="modern-header">
                        <h3 class="modern-title"><i class="fa fa-list"></i> <?php echo $this->lang->line('vehicle_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('vehicle', 'can_add')) { ?>
                            <div>
                                <button type="button" class="btn-premium" onclick="openAssignModal('')" style="margin-right: 10px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);">
                                    <i class="fa fa-user-plus"></i> Assign New Student
                                </button>
                                <button type="button" class="btn-premium" data-toggle="modal" data-backdrop="static" data-target="#myModal">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_vehicle'); ?>
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_grid" data-toggle="tab"><i class="fa fa-th-large"></i> Grid View</a></li>
                            <li><a href="#tab_list" data-toggle="tab"><i class="fa fa-list"></i> List View</a></li>
                        </ul>
                        
                        <?php
                        // Pre-calculate driver occurrences
                        $driver_counts = array();
                        foreach ($listVehicle as $v) {
                            if (!empty($v['driver_name'])) {
                                if (!isset($driver_counts[$v['driver_name']])) {
                                    $driver_counts[$v['driver_name']] = 0;
                                }
                                $driver_counts[$v['driver_name']]++;
                            }
                        }

                        // Pre-calculate vehicles with routes
                        $vehicles_with_routes = array();
                        if (isset($vehroutelist)) {
                            foreach ($vehroutelist as $vehroute) {
                                if (!empty($vehroute->vehicles)) {
                                    foreach ($vehroute->vehicles as $v) {
                                        $vehicles_with_routes[] = $v->id;
                                    }
                                }
                            }
                        }
                        ?>
                        
                        <div class="tab-content">
                            <!-- Grid View -->
                            <div class="tab-pane active modern-body" id="tab_grid">
                                <div class="vehicle-grid">
                                    <?php foreach ($listVehicle as $key => $data) { 
                                        $alerts = 0;
                                        $today = time();
                                        $dates_to_check = ['insurance_expiry', 'fitness_expiry', 'puc_expiry', 'permit_expiry', 'license_expiry'];
                                        foreach ($dates_to_check as $date_field) {
                                            if (!empty($data[$date_field]) && $data[$date_field] != '0000-00-00') {
                                                $diff = strtotime($data[$date_field]) - $today;
                                                $days = floor($diff / (60 * 60 * 24));
                                                if ($days <= 30) { $alerts++; }
                                            }
                                        }
                                        
                                        $driver_duplicate = (!empty($data['driver_name']) && isset($driver_counts[$data['driver_name']]) && $driver_counts[$data['driver_name']] > 1);
                                        $no_route_assigned = !in_array($data['id'], $vehicles_with_routes);
                                        
                                        if ($driver_duplicate) { $alerts++; }
                                        if ($no_route_assigned) { $alerts++; }
                                        
                                        $status = isset($data['vehicle_status']) ? strtolower($data['vehicle_status']) : 'active';
                                        $status_class = 'status-' . $status; 
                                        ?>
                                        <div class="vehicle-card <?php echo $status_class; ?>">
                                            <?php if ($alerts > 0) { ?>
                                                <div class="alert-indicator"><?php echo $alerts; ?> Alert<?php echo ($alerts>1)?'s':''; ?></div>
                                            <?php } ?>
                                            <i class="fa fa-bus vehicle-icon"></i>
                                            <div class="vehicle-title"><?php echo $data['vehicle_no']; ?></div>
                                            <div class="vehicle-subtitle"><?php echo !empty($data['driver_name']) ? $data['driver_name'] : 'No Driver'; ?></div>
                                            
                                            <?php if ($no_route_assigned) { ?>
                                                <div style="color: #ef4444; font-size: 11px; font-weight: bold; margin-bottom: 2px;">
                                                    <i class="fa fa-exclamation-triangle"></i> No Route Assigned
                                                </div>
                                            <?php } ?>
                                            <?php if ($driver_duplicate) { ?>
                                                <div style="color: #f59e0b; font-size: 11px; font-weight: bold; margin-bottom: 2px;">
                                                    <i class="fa fa-exclamation-triangle"></i> Driver in <?php echo $driver_counts[$data['driver_name']]; ?> Buses
                                                </div>
                                            <?php } ?>
                                            
                                            <?php 
                                            $occupied = isset($occupancy[$data['id']]) ? $occupancy[$data['id']] : 0;
                                            $capacity = $data['max_seating_capacity'];
                                            $seat_badge_color = ($occupied > $capacity && $capacity > 0) ? '#ef4444' : '#3b82f6';
                                            $seat_bg = ($occupied > $capacity && $capacity > 0) ? '#fef2f2' : '#eff6ff';
                                            ?>
                                            <div style="margin-top: 8px;">
                                                <span class="badge-soft" style="background:<?php echo $seat_bg; ?>; color:<?php echo $seat_badge_color; ?>; font-size: 11px;">
                                                    <i class="fa fa-users"></i> Seats: <?php echo $occupied; ?> / <?php echo $capacity ?: 0; ?>
                                                </span>
                                            </div>
                                            <div class="action-overlay">
                                                <?php if(!empty($data['driver_contact'])) { ?>
                                                    <a href="tel:<?php echo $data['driver_contact']; ?>" class="btn-icon" style="color:#10b981;" data-toggle="tooltip" title="Call Driver">
                                                        <i class="fa fa-phone"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php if(!empty($data['attendant_contact'])) { ?>
                                                    <a href="tel:<?php echo $data['attendant_contact']; ?>" class="btn-icon" style="color:#3b82f6;" data-toggle="tooltip" title="Call Attendant">
                                                        <i class="fa fa-phone"></i>
                                                    </a>
                                                <?php } ?>
                                                <button class="btn-icon viewstudents" data-id="<?php echo $data['id'] ?>" data-toggle="tooltip" title="View Students">
                                                    <i class="fa fa-users"></i>
                                                </button>
                                                <button class="btn-icon vehicledetails" data-id="<?php echo $data['id'] ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <?php if ($this->rbac->hasPrivilege('vehicle', 'can_edit')) { ?>
                                                    <button class="btn-icon edit editvehicle" data-id="<?php echo $data['id'] ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                <?php }if ($this->rbac->hasPrivilege('vehicle', 'can_delete')) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/vehicle/delete/<?php echo $data['id'] ?>" class="btn-icon delete" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <!-- List View -->
                            <div class="tab-pane" id="tab_list">
                                <div class="table-responsive" style="padding: 0 24px 24px 24px; margin-top:20px;">
                                    <table class="modern-table example" data-export-title="<?php echo $this->lang->line('vehicle_list');?>"> 
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('vehicle_number'); ?></th>
                                                <th><?php echo $this->lang->line('vehicle_model'); ?></th>
                                                <th>Status</th>
                                                <th><?php echo $this->lang->line('registration_number'); ?></th>
                                                <th><?php echo $this->lang->line('max_seating_capacity'); ?></th>
                                                <th><?php echo $this->lang->line('driver_name'); ?></th>
                                                <th><?php echo $this->lang->line('driver_contact'); ?></th>
                                                <th>Alerts</th>
                                                <th class="text-right noExport" width="120px"><?php echo $this->lang->line('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <?php foreach ($listVehicle as $key => $data) { 
                                                $alerts = 0;
                                                $alert_messages = array();
                                                
                                                $today = time();
                                                $dates_to_check = ['insurance_expiry', 'fitness_expiry', 'puc_expiry', 'permit_expiry'];
                                                foreach ($dates_to_check as $date_field) {
                                                    if (!empty($data[$date_field]) && $data[$date_field] != '0000-00-00') {
                                                        $diff = strtotime($data[$date_field]) - $today;
                                                        $days = floor($diff / (60 * 60 * 24));
                                                        if ($days <= 30) { $alerts++; $alert_messages[] = "Document expiring soon"; }
                                                    }
                                                }
                                                
                                                $driver_duplicate = (!empty($data['driver_name']) && isset($driver_counts[$data['driver_name']]) && $driver_counts[$data['driver_name']] > 1);
                                                $no_route_assigned = !in_array($data['id'], $vehicles_with_routes);
                                                
                                                if ($driver_duplicate) { $alerts++; $alert_messages[] = "Driver in ".$driver_counts[$data['driver_name']]." buses"; }
                                                if ($no_route_assigned) { $alerts++; $alert_messages[] = "No route assigned"; }
                                                
                                                $status = isset($data['vehicle_status']) ? $data['vehicle_status'] : 'Active';
                                                $status_color = ($status == 'Active') ? '#10b981' : (($status == 'Maintenance') ? '#f59e0b' : '#ef4444');
                                            ?>
                                                <tr>
                                                    <td><a href="#" class="vehicledetails" data-id="<?php echo $data['id'] ?>" style="font-weight:600; color:#0f172a;"><?php echo $data['vehicle_no'] ?></a></td>
                                                    <td><?php echo $data['vehicle_model'] ?></td>
                                                    <td><span class="badge-soft" style="background:<?php echo $status_color; ?>20; color:<?php echo $status_color; ?>;"><?php echo $status; ?></span></td>
                                                    <td><?php echo $data['registration_number'] ?></td>
                                                    <td>
                                                        <?php 
                                                        $occupied = isset($occupancy[$data['id']]) ? $occupancy[$data['id']] : 0;
                                                        $capacity = $data['max_seating_capacity'];
                                                        $seat_badge_color = ($occupied > $capacity && $capacity > 0) ? '#ef4444' : '#0369a1';
                                                        $seat_bg = ($occupied > $capacity && $capacity > 0) ? '#fef2f2' : '#e0f2fe';
                                                        ?>
                                                        <span class="badge-soft" style="background:<?php echo $seat_bg; ?>; color:<?php echo $seat_badge_color; ?>;">
                                                            <?php echo $occupied; ?> / <?php echo $capacity ?: 0; ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo $data['driver_name'] ?></td>
                                                    <td><?php echo $data['driver_contact'] ?></td>
                                                    <td>
                                                        <?php if ($alerts > 0) { ?>
                                                            <span class="badge-soft" style="background:#fef2f2; color:#ef4444;" data-toggle="tooltip" title="<?php echo implode(', ', $alert_messages); ?>">
                                                                <i class="fa fa-exclamation-circle"></i> <?php echo $alerts; ?>
                                                            </span>
                                                        <?php } else { ?>
                                                            <span style="color:#10b981;"><i class="fa fa-check-circle"></i></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display:flex; gap:4px; justify-content:flex-end;">
                                                            <button class="btn-icon viewstudents" style="width:28px; height:28px;" data-id="<?php echo $data['id'] ?>" data-toggle="tooltip" title="View Students">
                                                                <i class="fa fa-users"></i>
                                                            </button>
                                                            <button class="btn-icon vehicledetails" style="width:28px; height:28px;" data-id="<?php echo $data['id'] ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <?php if ($this->rbac->hasPrivilege('vehicle', 'can_edit')) { ?>
                                                                <button class="btn-icon edit editvehicle" style="width:28px; height:28px;" data-id="<?php echo $data['id'] ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>
                                                            <?php }if ($this->rbac->hasPrivilege('vehicle', 'can_delete')) { ?>
                                                                <a href="<?php echo base_url(); ?>admin/vehicle/delete/<?php echo $data['id'] ?>" class="btn-icon delete" style="width:28px; height:28px;" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modern-card" style="border:none;">
            <div class="modern-header" style="background-color: #f8fafc;">
                <h4 class="modern-title"><?php echo $this->lang->line('add_vehicle'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" style="opacity:0.6;">&times;</button>
            </div>

            <form id="addvehicleform" method="post" enctype="multipart/form-data">
                <div class="modal-body pb0 ptt10 modern-body">
                    
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 20px;">
                        <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Basic Info</a></li>
                        <li role="presentation"><a href="#insurance" aria-controls="insurance" role="tab" data-toggle="tab">Insurance & Tax</a></li>
                        <li role="presentation"><a href="#compliance" aria-controls="compliance" role="tab" data-toggle="tab">Compliance & Safety</a></li>
                        <li role="presentation"><a href="#driver" aria-controls="driver" role="tab" data-toggle="tab">Driver Details</a></li>
                        <li role="presentation"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Documents</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Tab 1: Basic Info -->
                        <div role="tabpanel" class="tab-pane active" id="basic">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('vehicle_number'); ?></label><small class="req"> *</small>
                                        <input autofocus="" id="vehicle_no" name="vehicle_no" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('vehicle_no'); ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('vehicle_model'); ?></label>
                                        <input id="vehicle_model" name="vehicle_model" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('vehicle_model'); ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('year_made'); ?> </label>
                                        <input id="manufacture_year" name="manufacture_year" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('manufacture_year'); ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('registration_number'); ?> </label>
                                        <input id="registration_number" name="registration_number" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('registration_number'); ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('chasis_number'); ?> </label>
                                        <input id="chasis_number" name="chasis_number" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('chasis_number'); ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('max_seating_capacity'); ?> </label>
                                        <input id="max_seating_capacity" name="max_seating_capacity" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('max_seating_capacity'); ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Vehicle Status</label>
                                        <select name="vehicle_status" class="form-control custom-input">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Maintenance">Maintenance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('note'); ?></label>
                                        <textarea class="form-control custom-input" id="note" name="note" placeholder="" rows="2"><?php echo set_value('note'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Insurance & Tax -->
                        <div role="tabpanel" class="tab-pane" id="insurance">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Insurance Provider</label>
                                        <input name="insurance_provider" type="text" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Policy Number</label>
                                        <input name="policy_number" type="text" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Insurance Type</label>
                                        <input name="insurance_type" type="text" class="form-control custom-input" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Insurance Start Date</label>
                                        <input name="insurance_start" type="date" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Insurance Expiry Date</label>
                                        <input name="insurance_expiry" type="date" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Road Tax Valid Until</label>
                                        <input name="road_tax_valid" type="date" class="form-control custom-input" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 3: Compliance & Safety -->
                        <div role="tabpanel" class="tab-pane" id="compliance">
                            <h5 style="margin-top:0; color:#475569; font-weight:600; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">Permits & Certificates</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Permit Number</label>
                                        <input name="permit_number" type="text" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Permit Expiry</label>
                                        <input name="permit_expiry" type="date" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Fitness Number</label>
                                        <input name="fitness_number" type="text" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Fitness Expiry</label>
                                        <input name="fitness_expiry" type="date" class="form-control custom-input" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>PUC Number</label>
                                        <input name="puc_number" type="text" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>PUC Expiry</label>
                                        <input name="puc_expiry" type="date" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Last Maintenance</label>
                                        <input name="last_maintenance_date" type="date" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Next Maintenance Due</label>
                                        <input name="next_maintenance_due" type="date" class="form-control custom-input" />
                                    </div>
                                </div>
                            </div>

                            <h5 style="margin-top:15px; color:#475569; font-weight:600; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">Safety Features</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>GPS Installed</label>
                                        <select name="has_gps" class="form-control custom-input">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>CCTV Installed</label>
                                        <select name="has_cctv" class="form-control custom-input">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Fire Extinguisher</label>
                                        <select name="has_fire_extinguisher" class="form-control custom-input">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Speed Governor</label>
                                        <select name="has_speed_governor" class="form-control custom-input">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 4: Driver Details -->
                        <div role="tabpanel" class="tab-pane" id="driver">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('driver_name'); ?></label>
                                        <select name="driver_name" class="form-control custom-input" onchange="populateContact(this, 'driver_contact_add')">
                                            <option value="">Select</option>
                                            <?php if(isset($stafflist)) { foreach($stafflist as $staff) { ?>
                                                <option value="<?php echo trim($staff['name'] . ' ' . $staff['surname']); ?>" data-contact="<?php echo $staff['contact_no']; ?>">
                                                    <?php echo trim($staff['name'] . ' ' . $staff['surname']); ?> (<?php echo $staff['employee_id']; ?>)
                                                </option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('driver_contact'); ?></label>
                                        <input name="driver_contact" id="driver_contact_add" type="text" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Attendant/Helper Name</label>
                                        <select name="attendant_name" class="form-control custom-input" onchange="populateContact(this, 'attendant_contact_add')">
                                            <option value="">Select</option>
                                            <?php if(isset($stafflist)) { foreach($stafflist as $staff) { ?>
                                                <option value="<?php echo trim($staff['name'] . ' ' . $staff['surname']); ?>" data-contact="<?php echo $staff['contact_no']; ?>">
                                                    <?php echo trim($staff['name'] . ' ' . $staff['surname']); ?> (<?php echo $staff['employee_id']; ?>)
                                                </option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Attendant Contact</label>
                                        <input name="attendant_contact" id="attendant_contact_add" type="text" class="form-control custom-input" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('driver_license'); ?></label>
                                        <input name="driver_licence" type="text" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>License Expiry</label>
                                        <input name="license_expiry" type="date" class="form-control custom-input" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Background Verification</label>
                                        <select name="background_verification" class="form-control custom-input">
                                            <option value="">Select Status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Cleared">Cleared</option>
                                            <option value="Failed">Failed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 5: Documents -->
                        <div role="tabpanel" class="tab-pane" id="documents">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('vehicle_photo'); ?></label>
                                        <input name="vehicle_photo" type="file" class="filestyle form-control custom-input" data-height="30" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>RC Copy</label>
                                        <input name="doc_rc" type="file" class="filestyle form-control custom-input" data-height="30" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Insurance Copy</label>
                                        <input name="doc_insurance" type="file" class="filestyle form-control custom-input" data-height="30" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Fitness Certificate</label>
                                        <input name="doc_fitness" type="file" class="filestyle form-control custom-input" data-height="30" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>PUC Certificate</label>
                                        <input name="doc_puc" type="file" class="filestyle form-control custom-input" data-height="30" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Permit Copy</label>
                                        <input name="doc_permit" type="file" class="filestyle form-control custom-input" data-height="30" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                    <div class="box-footer" style="background:#fff; border-top:1px solid #e2e8f0; padding:15px 24px; border-radius:0 0 12px 12px;">
                        <div class="paddA10">
                            <button type="submit" class="btn-premium pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>">
                                <i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save') ?>
                            </button>
                        </div>
                </div>
                
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editvehiclemodal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('edit_vehicle'); ?></h4>
            </div>

            <form id="editvehicleform" method="post" class="ptt10" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0 ">
                    <div id="editvehicledata"></div>
                </div>
                <div class="box-footer">

                    <div class="paddA10">
                        <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>

                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="vehicledetails" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('vehicle_details'); ?></h4>
            </div>

            <form id="editvehicleform" method="post" class="ptt10" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0 ">
                    <div id="viewvehicledata"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>   
    
    (function ($) {
        $('#myModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset'); 
        })
    })(jQuery);
    
    
    $("#addvehicleform").on('submit', (function (e) {
        
        e.preventDefault();

        var $this = $(this).find("button[type=submit]:focus");

        $.ajax({
            url: "<?php echo site_url("admin/vehicle/add") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');

            },
            success: function (res)
            {
 
                if (res.status == "fail") {

                    var message = "";
                    $.each(res.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);

                } else {

                    successMsg(res.message);

                    window.location.reload(true);
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }

        });
    }));

$('.editvehicle').click(function(){
    $('#editvehiclemodal').modal({
        backdrop: 'static',
        keyboard: false
    });
    var vehicleid = $(this).attr('data-id');

   $.ajax({
       url:'<?php echo site_url("admin/vehicle/getsinglevehicledata"); ?>',
       type:'post',
       data:{vehicleid:vehicleid},
       dataType:'json',
       success:function(response){
          $('#editvehicledata').html(response.page);
          $('.filestyle').dropify();
       }
   });
})

$('.vehicledetails').click(function(){
    $('#vehicledetails').modal({
        backdrop: 'static',
        keyboard: false
    });
    var vehicleid = $(this).attr('data-id');

   $.ajax({
       url:'<?php echo site_url("admin/vehicle/vehicledetails"); ?>',
       type:'post',
       data:{vehicleid:vehicleid},
       dataType:'json',
       success:function(response){
          $('#viewvehicledata').html(response.page);
       }
   });
})

$("#editvehicleform").on('submit', (function (e) {
    e.preventDefault();

    var $this = $(this).find("button[type=submit]:focus");

    $.ajax({
        url: "<?php echo site_url("admin/vehicle/edit") ?>",
        type: "POST",
        data: new FormData(this),
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $this.button('loading');

        },
        success: function (res)
        {

            if (res.status == "fail") {

                var message = "";
                $.each(res.error, function (index, value) {

                    message += value;
                });
                errorMsg(message);

            } else {

                successMsg(res.message);

                window.location.reload(true);
            }
        },
        error: function (xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');
        },
        complete: function () {
            $this.button('reset');
        }

    });
}));

$('.viewstudents').click(function(){
    $('#viewstudentsmodal').modal({
        backdrop: 'static',
        keyboard: false
    });
    var vehicle_id = $(this).attr('data-id');
    $('#btn-assign-new-student').attr('onclick', 'openAssignModal(' + vehicle_id + ')');
    $('#viewstudentsdata').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
    
    $.ajax({
        url:'<?php echo site_url("admin/vehicle/get_vehicle_students"); ?>',
        type:'post',
        data:{vehicle_id:vehicle_id},
        dataType:'json',
        success:function(response){
            if(response.status == 1) {
                $('#viewstudentsdata').html(response.html);
            }
        },
        error: function() {
            $('#viewstudentsdata').html('<div class="alert alert-danger">Error loading students list.</div>');
        }
    });
});

function populateContact(selectObj, targetId) {
    if(selectObj.selectedIndex > 0) {
        var selectedOption = selectObj.options[selectObj.selectedIndex];
        var contact = selectedOption.getAttribute('data-contact');
        if (contact) {
            document.getElementById(targetId).value = contact;
        } else {
            document.getElementById(targetId).value = '';
        }
    } else {
        document.getElementById(targetId).value = '';
    }
}
</script>

<div class="modal fade" id="viewstudentsmodal" role="dialog" aria-labelledby="viewstudentsmodal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modern-card" style="border:none;">
            <div class="modern-header" style="background-color: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
                <h4 class="modern-title" style="margin: 0;"><i class="fa fa-users"></i> Students Assigned to Vehicle</h4>
                <div>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-assign-new-student" style="margin-right: 15px; border-radius: 4px;">
                        <i class="fa fa-plus"></i> Assign New Student
                    </button>
                    <button type="button" class="close" data-dismiss="modal" style="opacity: 0.6; color: #000; text-shadow: none; float: none;">&times;</button>
                </div>
            </div>
            <div class="modal-body modern-body" id="viewstudentsdata">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/vehicle/assign_student_modal'); ?>