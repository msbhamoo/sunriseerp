<div class="row">
    <input type="hidden" name="id" value="<?php echo set_value('id', $editvehicle->id); ?>" >
    <div class="col-lg-12 col-md-12 col-sm-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 20px;">
            <li role="presentation" class="active"><a href="#edit_basic" aria-controls="edit_basic" role="tab" data-toggle="tab">Basic Info</a></li>
            <li role="presentation"><a href="#edit_insurance" aria-controls="edit_insurance" role="tab" data-toggle="tab">Insurance & Tax</a></li>
            <li role="presentation"><a href="#edit_compliance" aria-controls="edit_compliance" role="tab" data-toggle="tab">Compliance & Safety</a></li>
            <li role="presentation"><a href="#edit_driver" aria-controls="edit_driver" role="tab" data-toggle="tab">Driver Details</a></li>
            <li role="presentation"><a href="#edit_documents" aria-controls="edit_documents" role="tab" data-toggle="tab">Documents</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" style="padding-bottom:10px;">
            <!-- Tab 1: Basic Info -->
            <div role="tabpanel" class="tab-pane active" id="edit_basic">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('vehicle_number'); ?></label><small class="req"> *</small>
                            <input id="vehicle_no" name="vehicle_no" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('vehicle_no',$editvehicle->vehicle_no); ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('vehicle_model'); ?></label>
                            <input id="vehicle_model" name="vehicle_model" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('vehicle_model',$editvehicle->vehicle_model); ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('year_made'); ?> </label>
                            <input id="manufacture_year" name="manufacture_year" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('manufacture_year',$editvehicle->manufacture_year); ?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('registration_number'); ?> </label>
                            <input id="registration_number" name="registration_number" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('registration_number',$editvehicle->registration_number); ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('chasis_number'); ?> </label>
                            <input id="chasis_number" name="chasis_number" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('chasis_number',$editvehicle->chasis_number); ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('max_seating_capacity'); ?> </label>
                            <input id="max_seating_capacity" name="max_seating_capacity" placeholder="" type="text" class="form-control custom-input"  value="<?php echo set_value('max_seating_capacity',$editvehicle->max_seating_capacity); ?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Vehicle Status</label>
                            <select name="vehicle_status" class="form-control custom-input">
                                <?php $vstatus = isset($editvehicle->vehicle_status) ? $editvehicle->vehicle_status : 'Active'; ?>
                                <option value="Active" <?php if($vstatus == 'Active') echo 'selected'; ?>>Active</option>
                                <option value="Inactive" <?php if($vstatus == 'Inactive') echo 'selected'; ?>>Inactive</option>
                                <option value="Maintenance" <?php if($vstatus == 'Maintenance') echo 'selected'; ?>>Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('note'); ?></label>
                            <textarea class="form-control custom-input" id="note" name="note" placeholder="" rows="2"><?php echo set_value('note',$editvehicle->note); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Insurance & Tax -->
            <div role="tabpanel" class="tab-pane" id="edit_insurance">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Insurance Provider</label>
                            <input name="insurance_provider" type="text" class="form-control custom-input" value="<?php echo set_value('insurance_provider', isset($editvehicle->insurance_provider) ? $editvehicle->insurance_provider : ''); ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Policy Number</label>
                            <input name="policy_number" type="text" class="form-control custom-input" value="<?php echo set_value('policy_number', isset($editvehicle->policy_number) ? $editvehicle->policy_number : ''); ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Insurance Type</label>
                            <input name="insurance_type" type="text" class="form-control custom-input" value="<?php echo set_value('insurance_type', isset($editvehicle->insurance_type) ? $editvehicle->insurance_type : ''); ?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Insurance Start Date</label>
                            <input name="insurance_start" type="date" class="form-control custom-input" value="<?php echo (!empty($editvehicle->insurance_start) && $editvehicle->insurance_start != '0000-00-00') ? $editvehicle->insurance_start : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Insurance Expiry Date</label>
                            <input name="insurance_expiry" type="date" class="form-control custom-input" value="<?php echo (!empty($editvehicle->insurance_expiry) && $editvehicle->insurance_expiry != '0000-00-00') ? $editvehicle->insurance_expiry : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Road Tax Valid Until</label>
                            <input name="road_tax_valid" type="date" class="form-control custom-input" value="<?php echo (!empty($editvehicle->road_tax_valid) && $editvehicle->road_tax_valid != '0000-00-00') ? $editvehicle->road_tax_valid : ''; ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Compliance & Safety -->
            <div role="tabpanel" class="tab-pane" id="edit_compliance">
                <h5 style="margin-top:0; color:#475569; font-weight:600; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">Permits & Certificates</h5>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Permit Number</label>
                            <input name="permit_number" type="text" class="form-control custom-input" value="<?php echo set_value('permit_number', isset($editvehicle->permit_number) ? $editvehicle->permit_number : ''); ?>" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Permit Expiry</label>
                            <input name="permit_expiry" type="date" class="form-control custom-input" value="<?php echo (!empty($editvehicle->permit_expiry) && $editvehicle->permit_expiry != '0000-00-00') ? $editvehicle->permit_expiry : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Fitness Number</label>
                            <input name="fitness_number" type="text" class="form-control custom-input" value="<?php echo set_value('fitness_number', isset($editvehicle->fitness_number) ? $editvehicle->fitness_number : ''); ?>" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Fitness Expiry</label>
                            <input name="fitness_expiry" type="date" class="form-control custom-input" value="<?php echo (!empty($editvehicle->fitness_expiry) && $editvehicle->fitness_expiry != '0000-00-00') ? $editvehicle->fitness_expiry : ''; ?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>PUC Number</label>
                            <input name="puc_number" type="text" class="form-control custom-input" value="<?php echo set_value('puc_number', isset($editvehicle->puc_number) ? $editvehicle->puc_number : ''); ?>" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>PUC Expiry</label>
                            <input name="puc_expiry" type="date" class="form-control custom-input" value="<?php echo (!empty($editvehicle->puc_expiry) && $editvehicle->puc_expiry != '0000-00-00') ? $editvehicle->puc_expiry : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Last Maintenance</label>
                            <input name="last_maintenance_date" type="date" class="form-control custom-input" value="<?php echo (!empty($editvehicle->last_maintenance_date) && $editvehicle->last_maintenance_date != '0000-00-00') ? $editvehicle->last_maintenance_date : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Next Maintenance Due</label>
                            <input name="next_maintenance_due" type="date" class="form-control custom-input" value="<?php echo (!empty($editvehicle->next_maintenance_due) && $editvehicle->next_maintenance_due != '0000-00-00') ? $editvehicle->next_maintenance_due : ''; ?>" />
                        </div>
                    </div>
                </div>

                <h5 style="margin-top:15px; color:#475569; font-weight:600; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">Safety Features</h5>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>GPS Installed</label>
                            <select name="has_gps" class="form-control custom-input">
                                <?php $gps = isset($editvehicle->has_gps) ? $editvehicle->has_gps : 'No'; ?>
                                <option value="No" <?php if($gps == 'No') echo 'selected'; ?>>No</option>
                                <option value="Yes" <?php if($gps == 'Yes') echo 'selected'; ?>>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>CCTV Installed</label>
                            <select name="has_cctv" class="form-control custom-input">
                                <?php $cctv = isset($editvehicle->has_cctv) ? $editvehicle->has_cctv : 'No'; ?>
                                <option value="No" <?php if($cctv == 'No') echo 'selected'; ?>>No</option>
                                <option value="Yes" <?php if($cctv == 'Yes') echo 'selected'; ?>>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Fire Extinguisher</label>
                            <select name="has_fire_extinguisher" class="form-control custom-input">
                                <?php $fire = isset($editvehicle->has_fire_extinguisher) ? $editvehicle->has_fire_extinguisher : 'No'; ?>
                                <option value="No" <?php if($fire == 'No') echo 'selected'; ?>>No</option>
                                <option value="Yes" <?php if($fire == 'Yes') echo 'selected'; ?>>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Speed Governor</label>
                            <select name="has_speed_governor" class="form-control custom-input">
                                <?php $speed = isset($editvehicle->has_speed_governor) ? $editvehicle->has_speed_governor : 'No'; ?>
                                <option value="No" <?php if($speed == 'No') echo 'selected'; ?>>No</option>
                                <option value="Yes" <?php if($speed == 'Yes') echo 'selected'; ?>>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Driver Details -->
            <div role="tabpanel" class="tab-pane" id="edit_driver">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('driver_name'); ?></label>
                            <select name="driver_name" class="form-control custom-input" onchange="populateContact(this, 'driver_contact_edit')">
                                <option value="">Select</option>
                                <?php if(isset($stafflist)) { foreach($stafflist as $staff) { 
                                    $staff_name = trim($staff['name'] . ' ' . $staff['surname']);
                                ?>
                                    <option value="<?php echo $staff_name; ?>" data-contact="<?php echo $staff['contact_no']; ?>" <?php echo ($staff_name == $editvehicle->driver_name) ? 'selected' : ''; ?>>
                                        <?php echo $staff_name; ?> (<?php echo $staff['employee_id']; ?>)
                                    </option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('driver_contact'); ?></label>
                            <input name="driver_contact" id="driver_contact_edit" type="text" class="form-control custom-input" value="<?php echo set_value('driver_contact',$editvehicle->driver_contact); ?>" />
                        </div>
                    </div>
                </div>

                <!-- Multiple Attendants Section -->
                <?php
                $saved_att_names = !empty($editvehicle->attendant_name) ? array_map('trim', explode(',', $editvehicle->attendant_name)) : array('');
                $saved_att_contacts = !empty($editvehicle->attendant_contact) ? array_map('trim', explode(',', $editvehicle->attendant_contact)) : array('');
                $saved_att_routes = !empty($editvehicle->attendant_route) ? array_map('trim', explode(',', $editvehicle->attendant_route)) : array('');
                $bus_assigned_routes = isset($assigned_routes) ? $assigned_routes : array();
                ?>
                <script>
                    window.editVehicleAssignedRoutes = <?php echo json_encode(array_values($bus_assigned_routes)); ?>;
                </script>
                <div class="row">
                    <div class="col-sm-12">
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:12px; margin-bottom:15px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                                <label style="font-weight:700; color:#1e293b; margin:0;"><i class="fa fa-users text-primary"></i> Attendants / Helpers (Multiple Allowed)</label>
                                <button type="button" class="btn btn-xs btn-primary" onclick="addAttendantRow('edit_attendants_container')" style="font-weight:600; border-radius:4px;">
                                    <i class="fa fa-plus"></i> Add Attendant
                                </button>
                            </div>
                            <div id="edit_attendants_container">
                                <?php foreach ($saved_att_names as $a_idx => $curr_att_name) { 
                                    $curr_att_contact = isset($saved_att_contacts[$a_idx]) ? $saved_att_contacts[$a_idx] : '';
                                    $curr_att_route = isset($saved_att_routes[$a_idx]) ? $saved_att_routes[$a_idx] : '';
                                ?>
                                    <div class="row attendant-row" style="margin-bottom:8px;">
                                        <div class="col-sm-4">
                                            <label style="font-size:11px; color:#64748b;">Attendant Name</label>
                                            <select name="attendant_name[]" class="form-control custom-input attendant-select" onchange="populateAttendantRowContact(this)">
                                                <option value="">Select</option>
                                                <?php if(isset($stafflist)) { foreach($stafflist as $staff) { 
                                                    $staff_name = trim($staff['name'] . ' ' . $staff['surname']);
                                                ?>
                                                    <option value="<?php echo $staff_name; ?>" data-contact="<?php echo $staff['contact_no']; ?>" <?php echo ($staff_name == $curr_att_name) ? 'selected' : ''; ?>>
                                                        <?php echo $staff_name; ?> (<?php echo $staff['employee_id']; ?>)
                                                    </option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label style="font-size:11px; color:#64748b;">Attendant Contact</label>
                                            <input name="attendant_contact[]" type="text" class="form-control custom-input attendant-contact-input" value="<?php echo htmlspecialchars($curr_att_contact); ?>" placeholder="Contact number..." />
                                        </div>
                                        <div class="col-sm-4">
                                            <label style="font-size:11px; color:#64748b;">Assigned Route (Optional)</label>
                                            <select name="attendant_route[]" class="form-control custom-input attendant-route-select">
                                                <?php if(!empty($bus_assigned_routes)) { ?>
                                                    <option value="">All Routes of this Bus</option>
                                                    <?php foreach($bus_assigned_routes as $r_title) { ?>
                                                        <option value="<?php echo htmlspecialchars($r_title); ?>" <?php echo ($r_title == $curr_att_route) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($r_title); ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <option value="">All Routes (No route assigned yet)</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-1 text-right" style="padding-top:23px;">
                                            <button type="button" class="btn btn-default btn-sm text-danger" onclick="removeAttendantRow(this)" title="Remove" style="border:none; background:transparent;">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('driver_license'); ?></label>
                            <input name="driver_licence" type="text" class="form-control custom-input" value="<?php echo set_value('driver_licence',$editvehicle->driver_licence); ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>License Expiry</label>
                            <input name="license_expiry" type="date" class="form-control custom-input" value="<?php echo (!empty($editvehicle->license_expiry) && $editvehicle->license_expiry != '0000-00-00') ? $editvehicle->license_expiry : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Background Verification</label>
                            <select name="background_verification" class="form-control custom-input">
                                <?php $bg = isset($editvehicle->background_verification) ? $editvehicle->background_verification : ''; ?>
                                <option value="">Select Status</option>
                                <option value="Pending" <?php if($bg == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Cleared" <?php if($bg == 'Cleared') echo 'selected'; ?>>Cleared</option>
                                <option value="Failed" <?php if($bg == 'Failed') echo 'selected'; ?>>Failed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 5: Documents -->
            <div role="tabpanel" class="tab-pane" id="edit_documents">
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
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>License Copy</label>
                            <input name="doc_license" type="file" class="filestyle form-control custom-input" data-height="30" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>