<?php 
$alerts = 0;
$alert_data = array();
$today = time();
$dates_to_check = array(
    'insurance_expiry' => array('label'=>'Insurance', 'doc_field'=>'doc_insurance'),
    'fitness_expiry' => array('label'=>'Fitness Cert', 'doc_field'=>'doc_fitness'),
    'puc_expiry' => array('label'=>'PUC Cert', 'doc_field'=>'doc_puc'),
    'permit_expiry' => array('label'=>'Permit', 'doc_field'=>'doc_permit'),
    'license_expiry' => array('label'=>'Driver License', 'doc_field'=>'doc_license')
);
foreach ($dates_to_check as $date_field => $info) {
    if (!empty($editvehicle->{$date_field}) && $editvehicle->{$date_field} != '0000-00-00' && $editvehicle->{$date_field} != '1970-01-01') {
        $diff = strtotime($editvehicle->{$date_field}) - $today;
        $days = floor($diff / (60 * 60 * 24));
        if ($days <= 30) { 
            $alerts++; 
            $alert_text = ($days < 0) ? "Expired " . abs($days) . " days ago" : "Expires in " . $days . " days";
            $alert_data[] = array(
                'label' => $info['label'],
                'text' => $alert_text,
                'field_name' => $date_field,
                'doc_field_name' => $info['doc_field'],
                'current_date' => date($this->customlib->getSchoolDateFormat(), strtotime($editvehicle->{$date_field})),
                'days' => $days
            );
        }
    }
}
?>
<style>
.detail-label { font-weight: 600; color: #475569; display: block; font-size: 13px; margin-bottom: 2px;}
.detail-value { color: #0f172a; font-size: 14px; margin-bottom: 15px; }
.doc-link { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f1f5f9; border-radius: 6px; color: #3b82f6; text-decoration: none; font-size: 13px; font-weight: 500;}
.doc-link:hover { background: #e2e8f0; color: #2563eb; }
.alert-row { background: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px; padding: 15px; margin-bottom: 15px; display:flex; flex-wrap:wrap; align-items:center; gap: 15px; justify-content: space-between; }
.alert-row h5 { margin: 0; font-size: 15px; font-weight: 600; color: #be123c; }
</style>
<div class="row">
    <div class="col-md-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 20px;">
            <li role="presentation" class="active"><a href="#view_basic" aria-controls="view_basic" role="tab" data-toggle="tab">Basic Info</a></li>
            <li role="presentation"><a href="#view_insurance" aria-controls="view_insurance" role="tab" data-toggle="tab">Insurance & Tax</a></li>
            <li role="presentation"><a href="#view_compliance" aria-controls="view_compliance" role="tab" data-toggle="tab">Compliance & Safety</a></li>
            <li role="presentation"><a href="#view_driver" aria-controls="view_driver" role="tab" data-toggle="tab">Driver Details</a></li>
            <li role="presentation"><a href="#view_documents" aria-controls="view_documents" role="tab" data-toggle="tab">Documents</a></li>
            <li role="presentation"><a href="#view_routes" aria-controls="view_routes" role="tab" data-toggle="tab">Routes & Stops</a></li>
            <?php if($alerts > 0) { ?>
                <li role="presentation"><a href="#view_alerts" aria-controls="view_alerts" role="tab" data-toggle="tab" style="color:#ef4444; font-weight:bold;"><i class="fa fa-bell"></i> Alerts (<?php echo $alerts; ?>)</a></li>
            <?php } ?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" style="padding: 10px 15px;">
            <!-- Tab 1: Basic Info -->
            <div role="tabpanel" class="tab-pane active" id="view_basic">
                <div class="row">
                    <div class="col-sm-3 col-md-2">
                        <?php if(!empty($editvehicle->vehicle_photo)){ ?>
                            <img class="img-responsive img-rounded" src="<?php echo $this->media_storage->getImageURL('/uploads/vehicle_photo/'.$editvehicle->vehicle_photo); ?>" style="width:100%; max-width:120px; border-radius:8px;">
                        <?php }else{ ?>
                            <div style="width:100%; height:100px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; border-radius:8px; color:#94a3b8;"><i class="fa fa-bus fa-3x"></i></div>
                        <?php } ?>
                    </div>
                    <div class="col-sm-9 col-md-10">
                        <div class="row">
                            <div class="col-sm-4">
                                <span class="detail-label"><?php echo $this->lang->line('vehicle_number'); ?></span>
                                <div class="detail-value"><?php echo $editvehicle->vehicle_no; ?></div>
                            </div>
                            <div class="col-sm-4">
                                <span class="detail-label"><?php echo $this->lang->line('vehicle_model'); ?></span>
                                <div class="detail-value"><?php echo $editvehicle->vehicle_model; ?></div>
                            </div>
                            <div class="col-sm-4">
                                <span class="detail-label"><?php echo $this->lang->line('year_made'); ?></span>
                                <div class="detail-value"><?php echo $editvehicle->manufacture_year; ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <span class="detail-label"><?php echo $this->lang->line('registration_number'); ?></span>
                                <div class="detail-value"><?php echo $editvehicle->registration_number; ?></div>
                            </div>
                            <div class="col-sm-4">
                                <span class="detail-label"><?php echo $this->lang->line('chasis_number'); ?></span>
                                <div class="detail-value"><?php echo $editvehicle->chasis_number; ?></div>
                            </div>
                            <div class="col-sm-4">
                                <span class="detail-label"><?php echo $this->lang->line('max_seating_capacity'); ?></span>
                                <div class="detail-value"><?php echo $editvehicle->max_seating_capacity; ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <span class="detail-label">Vehicle Status</span>
                                <div class="detail-value"><?php echo isset($editvehicle->vehicle_status) ? $editvehicle->vehicle_status : 'Active'; ?></div>
                            </div>
                            <div class="col-sm-8">
                                <span class="detail-label"><?php echo $this->lang->line('note'); ?></span>
                                <div class="detail-value"><?php echo $editvehicle->note; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Insurance & Tax -->
            <div role="tabpanel" class="tab-pane" id="view_insurance">
                <div class="row">
                    <div class="col-sm-4">
                        <span class="detail-label">Insurance Provider</span>
                        <div class="detail-value"><?php echo !empty($editvehicle->insurance_provider) ? $editvehicle->insurance_provider : '-'; ?></div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label">Policy Number</span>
                        <div class="detail-value"><?php echo !empty($editvehicle->policy_number) ? $editvehicle->policy_number : '-'; ?></div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label">Insurance Type</span>
                        <div class="detail-value"><?php echo !empty($editvehicle->insurance_type) ? $editvehicle->insurance_type : '-'; ?></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span class="detail-label">Insurance Start Date</span>
                        <div class="detail-value"><?php echo (!empty($editvehicle->insurance_start) && $editvehicle->insurance_start != '0000-00-00') ? date($this->customlib->getSchoolDateFormat(), strtotime($editvehicle->insurance_start)) : '-'; ?></div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label">Insurance Expiry Date</span>
                        <div class="detail-value"><?php echo (!empty($editvehicle->insurance_expiry) && $editvehicle->insurance_expiry != '0000-00-00') ? date($this->customlib->getSchoolDateFormat(), strtotime($editvehicle->insurance_expiry)) : '-'; ?></div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label">Road Tax Valid Until</span>
                        <div class="detail-value"><?php echo (!empty($editvehicle->road_tax_valid) && $editvehicle->road_tax_valid != '0000-00-00') ? date($this->customlib->getSchoolDateFormat(), strtotime($editvehicle->road_tax_valid)) : '-'; ?></div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Compliance & Safety -->
            <div role="tabpanel" class="tab-pane" id="view_compliance">
                <div class="row">
                    <div class="col-sm-3">
                        <span class="detail-label">Permit Number</span>
                        <div class="detail-value"><?php echo !empty($editvehicle->permit_number) ? $editvehicle->permit_number : '-'; ?></div>
                    </div>
                    <div class="col-sm-3">
                        <span class="detail-label">Permit Expiry</span>
                        <div class="detail-value"><?php echo (!empty($editvehicle->permit_expiry) && $editvehicle->permit_expiry != '0000-00-00') ? date($this->customlib->getSchoolDateFormat(), strtotime($editvehicle->permit_expiry)) : '-'; ?></div>
                    </div>
                    <div class="col-sm-3">
                        <span class="detail-label">Fitness Number</span>
                        <div class="detail-value"><?php echo !empty($editvehicle->fitness_number) ? $editvehicle->fitness_number : '-'; ?></div>
                    </div>
                    <div class="col-sm-3">
                        <span class="detail-label">Fitness Expiry</span>
                        <div class="detail-value"><?php echo (!empty($editvehicle->fitness_expiry) && $editvehicle->fitness_expiry != '0000-00-00') ? date($this->customlib->getSchoolDateFormat(), strtotime($editvehicle->fitness_expiry)) : '-'; ?></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <span class="detail-label">PUC Number</span>
                        <div class="detail-value"><?php echo !empty($editvehicle->puc_number) ? $editvehicle->puc_number : '-'; ?></div>
                    </div>
                    <div class="col-sm-3">
                        <span class="detail-label">PUC Expiry</span>
                        <div class="detail-value"><?php echo (!empty($editvehicle->puc_expiry) && $editvehicle->puc_expiry != '0000-00-00') ? date($this->customlib->getSchoolDateFormat(), strtotime($editvehicle->puc_expiry)) : '-'; ?></div>
                    </div>
                    <div class="col-sm-3">
                        <span class="detail-label">Last Maintenance</span>
                        <div class="detail-value"><?php echo (!empty($editvehicle->last_maintenance_date) && $editvehicle->last_maintenance_date != '0000-00-00') ? date($this->customlib->getSchoolDateFormat(), strtotime($editvehicle->last_maintenance_date)) : '-'; ?></div>
                    </div>
                    <div class="col-sm-3">
                        <span class="detail-label">Next Maintenance Due</span>
                        <div class="detail-value"><?php echo (!empty($editvehicle->next_maintenance_due) && $editvehicle->next_maintenance_due != '0000-00-00') ? date($this->customlib->getSchoolDateFormat(), strtotime($editvehicle->next_maintenance_due)) : '-'; ?></div>
                    </div>
                </div>
                <hr style="margin-top:5px; margin-bottom:15px; border-color:#e2e8f0;" />
                <div class="row">
                    <div class="col-sm-3">
                        <span class="detail-label">GPS Installed</span>
                        <div class="detail-value"><?php echo isset($editvehicle->has_gps) ? $editvehicle->has_gps : 'No'; ?></div>
                    </div>
                    <div class="col-sm-3">
                        <span class="detail-label">CCTV Installed</span>
                        <div class="detail-value"><?php echo isset($editvehicle->has_cctv) ? $editvehicle->has_cctv : 'No'; ?></div>
                    </div>
                    <div class="col-sm-3">
                        <span class="detail-label">Fire Extinguisher</span>
                        <div class="detail-value"><?php echo isset($editvehicle->has_fire_extinguisher) ? $editvehicle->has_fire_extinguisher : 'No'; ?></div>
                    </div>
                    <div class="col-sm-3">
                        <span class="detail-label">Speed Governor</span>
                        <div class="detail-value"><?php echo isset($editvehicle->has_speed_governor) ? $editvehicle->has_speed_governor : 'No'; ?></div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Driver Details -->
            <div role="tabpanel" class="tab-pane" id="view_driver">
                <div class="row">
                    <div class="col-sm-4">
                        <span class="detail-label"><?php echo $this->lang->line('driver_name'); ?></span>
                        <div class="detail-value"><?php echo $editvehicle->driver_name; ?></div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label"><?php echo $this->lang->line('driver_contact'); ?></span>
                        <div class="detail-value">
                            <?php if(!empty($editvehicle->driver_contact)) { ?>
                                <a href="tel:<?php echo $editvehicle->driver_contact; ?>"><i class="fa fa-phone"></i> <?php echo $editvehicle->driver_contact; ?></a>
                            <?php } else { echo '-'; } ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label">Attendant/Helper Name & Contact</span>
                        <div class="detail-value">
                            <?php echo !empty($editvehicle->attendant_name) ? $editvehicle->attendant_name : '-'; ?>
                            <?php if(!empty($editvehicle->attendant_contact)) { ?>
                                <br><a href="tel:<?php echo $editvehicle->attendant_contact; ?>"><i class="fa fa-phone"></i> <?php echo $editvehicle->attendant_contact; ?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span class="detail-label"><?php echo $this->lang->line('driver_license'); ?></span>
                        <div class="detail-value"><?php echo $editvehicle->driver_licence; ?></div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label">License Expiry</span>
                        <div class="detail-value"><?php echo (!empty($editvehicle->license_expiry) && $editvehicle->license_expiry != '0000-00-00') ? date($this->customlib->getSchoolDateFormat(), strtotime($editvehicle->license_expiry)) : '-'; ?></div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label">Background Verification</span>
                        <div class="detail-value"><?php echo !empty($editvehicle->background_verification) ? $editvehicle->background_verification : '-'; ?></div>
                    </div>
                </div>
            </div>

            <!-- Tab 5: Documents -->
            <div role="tabpanel" class="tab-pane" id="view_documents">
                <div class="row">
                    <div class="col-sm-4">
                        <span class="detail-label">RC Copy</span>
                        <div class="detail-value">
                            <?php if(!empty($editvehicle->doc_rc)) { ?>
                                <a href="<?php echo base_url('uploads/vehicle_documents/'.$editvehicle->doc_rc); ?>" target="_blank" class="doc-link"><i class="fa fa-download"></i> View RC</a>
                            <?php } else { echo '-'; } ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label">Insurance Copy</span>
                        <div class="detail-value">
                            <?php if(!empty($editvehicle->doc_insurance)) { ?>
                                <a href="<?php echo base_url('uploads/vehicle_documents/'.$editvehicle->doc_insurance); ?>" target="_blank" class="doc-link"><i class="fa fa-download"></i> View Insurance</a>
                            <?php } else { echo '-'; } ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label">Fitness Certificate</span>
                        <div class="detail-value">
                            <?php if(!empty($editvehicle->doc_fitness)) { ?>
                                <a href="<?php echo base_url('uploads/vehicle_documents/'.$editvehicle->doc_fitness); ?>" target="_blank" class="doc-link"><i class="fa fa-download"></i> View Fitness Cert</a>
                            <?php } else { echo '-'; } ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span class="detail-label">PUC Certificate</span>
                        <div class="detail-value">
                            <?php if(!empty($editvehicle->doc_puc)) { ?>
                                <a href="<?php echo base_url('uploads/vehicle_documents/'.$editvehicle->doc_puc); ?>" target="_blank" class="doc-link"><i class="fa fa-download"></i> View PUC</a>
                            <?php } else { echo '-'; } ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label">Permit Copy</span>
                        <div class="detail-value">
                            <?php if(!empty($editvehicle->doc_permit)) { ?>
                                <a href="<?php echo base_url('uploads/vehicle_documents/'.$editvehicle->doc_permit); ?>" target="_blank" class="doc-link"><i class="fa fa-download"></i> View Permit</a>
                            <?php } else { echo '-'; } ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <span class="detail-label">License Copy</span>
                        <div class="detail-value">
                            <?php if(!empty($editvehicle->doc_license)) { ?>
                                <a href="<?php echo base_url('uploads/vehicle_documents/'.$editvehicle->doc_license); ?>" target="_blank" class="doc-link"><i class="fa fa-download"></i> View License</a>
                            <?php } else { echo '-'; } ?>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            
            <!-- Tab 6: Routes & Stops -->
            <div role="tabpanel" class="tab-pane" id="view_routes">
                <div class="row">
                    <div class="col-sm-12">
                        <?php if(!empty($vehicle_routes)){ 
                            $current_route = '';
                            foreach($vehicle_routes as $route_stop) {
                                if($current_route != $route_stop['route_title']) {
                                    if($current_route != '') echo "</ul></div>";
                                    $current_route = $route_stop['route_title'];
                                    echo "<div style='margin-bottom:15px;'><h4 style='color:#0f172a; margin-bottom:10px;'><i class='fa fa-map-marker text-danger'></i> Route: " . $current_route . "</h4><ul style='list-style:none; padding-left:15px;'>";
                                }
                                echo "<li style='padding:5px 0; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between;'><span>" . $route_stop['pickup_point_name'] . "</span><span class='text-muted'>" . (!empty($route_stop['pickup_time']) ? $route_stop['pickup_time'] : '-') . "</span></li>";
                            }
                            if($current_route != '') echo "</ul></div>";
                        } else { ?>
                            <div class="alert alert-info">No routes assigned to this vehicle yet.</div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <!-- Tab 7: Alerts -->
            <?php if($alerts > 0) { ?>
            <div role="tabpanel" class="tab-pane" id="view_alerts">
                <div class="row">
                    <div class="col-sm-12">
                        <?php foreach($alert_data as $alert) { ?>
                            <form id="form_alert_<?php echo $alert['field_name']; ?>" method="post" class="alert-row form-inline" enctype="multipart/form-data">
                                <div>
                                    <h5><i class="fa fa-exclamation-circle"></i> <?php echo $alert['label']; ?> - <?php echo $alert['text']; ?></h5>
                                    <div style="font-size: 12px; color: #881337; margin-top:4px;">Current Expiry: <?php echo $alert['current_date']; ?></div>
                                </div>
                                <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end;">
                                    <input type="hidden" name="vehicle_id" value="<?php echo $editvehicle->id; ?>">
                                    <input type="hidden" name="field_name" value="<?php echo $alert['field_name']; ?>">
                                    <input type="hidden" name="doc_field_name" value="<?php echo $alert['doc_field_name']; ?>">
                                    
                                    <div class="form-group" style="margin:0;">
                                        <label style="font-size:11px; margin-bottom:2px; display:block;">New Expiry Date</label>
                                        <input type="text" name="new_date" class="form-control date" placeholder="Update Date" style="width: 120px;" required>
                                    </div>
                                    <div class="form-group" style="margin:0;">
                                        <label style="font-size:11px; margin-bottom:2px; display:block;">Upload New Document (Optional)</label>
                                        <input type="file" name="new_document" class="form-control" style="width: 220px; padding:4px;">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm" style="height:34px;">Update</button>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<script>
    $('.date').datepicker({
        format: date_format,
        autoclose: true
    });
    
    $('[id^=form_alert_]').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        var originalBtnText = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
        
        $.ajax({
            url: "<?php echo base_url(); ?>admin/vehicle/update_alert",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if(response.status == 'success') {
                    successMsg(response.message);
                    $('#vehicledetails').modal('hide');
                    window.location.reload();
                } else {
                    errorMsg(response.message);
                    btn.html(originalBtnText).prop('disabled', false);
                }
            },
            error: function() {
                errorMsg("Something went wrong");
                btn.html(originalBtnText).prop('disabled', false);
            }
        });
    });
</script>
