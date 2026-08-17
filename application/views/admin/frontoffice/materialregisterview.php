<?php
$total_entries = count($material_list);
$inward_count  = 0;
$outward_count = 0;
$today_str     = date('Y-m-d');
$today_count   = 0;
foreach ($material_list as $m) {
    if (isset($m['direction']) && $m['direction'] === 'inward') {
        $inward_count++;
    } elseif (isset($m['direction']) && $m['direction'] === 'outward') {
        $outward_count++;
    }
    if (isset($m['date']) && date('Y-m-d', strtotime($m['date'])) === $today_str) {
        $today_count++;
    }
}
?>

<div class="content-wrapper" style="min-height: 348px;">
    <section class="content-header">
        <h1><i class="fa fa-truck"></i> <?php echo $this->lang->line('material_register'); ?></h1>
    </section>
    
    <section class="content">
        <!-- Modern KPI Summary Stat Cards -->
        <div class="modern-stat-grid">
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label"><?php echo $this->lang->line('total_entries') ? $this->lang->line('total_entries') : 'Total Records'; ?></div>
                    <div class="stat-value"><?php echo $total_entries; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                    <i class="fa fa-cubes"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label"><?php echo $this->lang->line('inward'); ?></div>
                    <div class="stat-value text-success" style="color: #059669;"><?php echo $inward_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-arrow-circle-down"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label"><?php echo $this->lang->line('outward'); ?></div>
                    <div class="stat-value text-warning" style="color: #d97706;"><?php echo $outward_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                    <i class="fa fa-arrow-circle-up"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label"><?php echo $this->lang->line('today_entries') ? $this->lang->line('today_entries') : "Today's Movement"; ?></div>
                    <div class="stat-value" style="color: #0284c7;"><?php echo $today_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                    <i class="fa fa-calendar-check-o"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-list text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('material_register_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('material_register', 'can_add')) { ?>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-open-material-drawer">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_material_entry'); ?>
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('material_register_list'); ?></div>
                        <?php echo $this->session->flashdata('msg'); ?>
                        <div class="mailbox-messages table-responsive">
                            <table class="table table-hover table-striped table-bordered example" data-export-title="<?php echo $this->lang->line('material_register_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('direction'); ?></th>
                                        <th><?php echo $this->lang->line('material_name'); ?></th>
                                        <th><?php echo $this->lang->line('quantity'); ?></th>
                                        <th><?php echo $this->lang->line('carried_by'); ?></th>
                                        <th><?php echo $this->lang->line('party_name'); ?></th>
                                        <th><?php echo $this->lang->line('vehicle_no'); ?></th>
                                        <th><?php echo $this->lang->line('gate_pass_no'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($material_list as $value) { ?>
                                        <tr>
                                            <td class="white-space-nowrap"><i class="fa fa-calendar-o text-muted" style="margin-right: 4px;"></i> <?php echo date($this->customlib->getSchoolDateFormat(), strtotime($value['date'])); ?></td>
                                            <td>
                                                <?php if ($value['direction'] == 'inward') { ?>
                                                    <span class="label label-success"><i class="fa fa-arrow-down" style="margin-right: 3px;"></i> <?php echo $this->lang->line('inward'); ?></span>
                                                <?php } else { ?>
                                                    <span class="label label-warning"><i class="fa fa-arrow-up" style="margin-right: 3px;"></i> <?php echo $this->lang->line('outward'); ?></span>
                                                <?php } ?>
                                            </td>
                                            <td><strong style="color: #0f172a;"><?php echo html_escape($value['material_name']); ?></strong></td>
                                            <td><span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;"><?php echo html_escape(trim($value['quantity'] . ' ' . $value['unit'])); ?></span></td>
                                            <td>
                                                <?php echo html_escape($value['carried_by']);
                                                if (!empty($value['contact'])) {
                                                    echo '<br><small class="text-muted"><i class="fa fa-phone" style="margin-right: 2px;"></i> ' . html_escape($value['contact']) . '</small>';
                                                } ?>
                                            </td>
                                            <td><?php echo html_escape($value['party_name']); ?></td>
                                            <td>
                                                <?php if (!empty($value['vehicle_no'])) { ?>
                                                    <span class="badge" style="background: #f8fafc; color: #475569; border: 1px solid #cbd5e1;"><i class="fa fa-truck" style="margin-right: 3px;"></i> <?php echo html_escape($value['vehicle_no']); ?></span>
                                                <?php } else { echo '-'; } ?>
                                            </td>
                                            <td><code style="background: #f1f5f9; color: #4f46e5; padding: 2px 6px; border-radius: 4px; font-weight: 600;"><?php echo html_escape($value['gate_pass_no']); ?></code></td>
                                            <td class="text-right white-space-nowrap">
                                                <button type="button" class="btn btn-default btn-xs btn-view-slip" data-id="<?php echo $value['id']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <?php if (!empty($value['image'])) { ?>
                                                    <a href="<?php echo site_url('admin/materialregister/download/' . $value['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('attachment'); ?>"><i class="fa fa-paperclip"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('material_register', 'can_edit')) { ?>
                                                    <a href="<?php echo site_url('admin/materialregister/edit/' . $value['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil text-primary"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('material_register', 'can_delete')) { ?>
                                                    <a href="<?php echo site_url('admin/materialregister/delete/' . $value['id']); ?>" class="btn btn-default btn-xs" onclick="return confirm('<?php echo $this->lang->line('delete_confirm'); ?>');" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash text-danger"></i></a>
                                                <?php } ?>
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
    </section>
</div>

<!-- Right Side Slide-in Drawer for Adding Material Entry -->
<?php if ($this->rbac->hasPrivilege('material_register', 'can_add')) { ?>
    <div id="material-drawer-overlay" class="modern-drawer-overlay"></div>
    <div id="material-drawer-panel" class="modern-drawer-panel">
        <form id="materialform" action="<?php echo site_url('admin/materialregister'); ?>" method="post" enctype="multipart/form-data">
            <div class="modern-drawer-header">
                <h4 class="modern-drawer-title"><i class="fa fa-plus-circle" style="color: var(--primary-theme-color, #4f46e5);"></i> <?php echo $this->lang->line('add_material_entry'); ?></h4>
                <button type="button" class="modern-drawer-close" id="btn-close-material-drawer">&times;</button>
            </div>
            <?php echo $this->customlib->getCSRF(); ?>
            <div class="modern-drawer-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('direction'); ?></label> <small class="req">*</small>
                            <select class="form-control" name="direction">
                                <option value="inward" <?php echo set_select('direction', 'inward', true); ?>><?php echo $this->lang->line('inward'); ?></option>
                                <option value="outward" <?php echo set_select('direction', 'outward'); ?>><?php echo $this->lang->line('outward'); ?></option>
                            </select>
                            <span class="text-danger"><?php echo form_error('direction'); ?></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('date'); ?></label> <small class="req">*</small>
                            <input type="text" class="form-control date" name="date" readonly value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>"/>
                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $this->lang->line('material_name'); ?></label> <small class="req">*</small>
                    <?php echo material_select('material_name', 'item', $masters['item'], set_value('material_name'), $this->lang->line('select')); ?>
                    <span class="text-danger"><?php echo form_error('material_name'); ?></span>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('quantity'); ?></label>
                            <?php echo material_select('quantity', 'quantity', $masters['quantity'], set_value('quantity'), $this->lang->line('select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('unit'); ?></label>
                            <?php echo material_select('unit', 'unit', $masters['unit'], set_value('unit'), $this->lang->line('select')); ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('carried_by'); ?></label>
                            <input class="form-control" name="carried_by" placeholder="e.g. John Doe" value="<?php echo set_value('carried_by'); ?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('contact'); ?></label>
                            <input class="form-control" name="contact" placeholder="e.g. +91 9876543210" value="<?php echo set_value('contact'); ?>"/>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $this->lang->line('party_name'); ?></label>
                    <?php echo material_select('party_name', 'party', $masters['party'], set_value('party_name'), $this->lang->line('from_to_hint')); ?>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('vehicle_no'); ?></label>
                            <?php echo material_select('vehicle_no', 'vehicle', $masters['vehicle'], set_value('vehicle_no'), $this->lang->line('select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('gate_pass_no'); ?></label>
                            <input class="form-control" name="gate_pass_no" value="<?php echo set_value('gate_pass_no', $next_gate_pass_no); ?>" placeholder="Auto-generated"/>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $this->lang->line('driver_name'); ?></label>
                    <?php echo material_select('driver_name', 'driver', $masters['driver'], set_value('driver_name'), $this->lang->line('select')); ?>
                </div>
                
                <div class="form-group">
                    <label><?php echo $this->lang->line('received_issued_by'); ?></label>
                    <select class="form-control staff-select2" name="staff_id">
                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                        <?php foreach ($stafflist as $s) { ?>
                            <option value="<?php echo $s['id']; ?>" <?php echo set_select('staff_id', $s['id']); ?>><?php echo html_escape($s['name'] . ' ' . $s['surname'] . ' (' . $s['employee_id'] . ')'); ?></option>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('department'); ?></label>
                            <?php echo material_select('department', 'department', $masters['department'], set_value('department'), $this->lang->line('select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('approved_by'); ?></label>
                            <select class="form-control staff-select2" name="approved_by">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php foreach ($stafflist as $s) { ?>
                                    <option value="<?php echo html_escape($s['name'] . ' ' . $s['surname']); ?>" <?php echo set_select('approved_by', $s['name'] . ' ' . $s['surname']); ?>><?php echo html_escape($s['name'] . ' ' . $s['surname'] . ' (' . $s['employee_id'] . ')'); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('in_time'); ?></label>
                            <input class="form-control" name="in_time" placeholder="HH:MM" value="<?php echo set_value('in_time'); ?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('out_time'); ?></label>
                            <input class="form-control" name="out_time" placeholder="HH:MM" value="<?php echo set_value('out_time'); ?>"/>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $this->lang->line('attachment'); ?></label>
                    <input type="file" name="image" class="form-control" style="padding: 4px; height: auto;"/>
                    <small class="text-muted"><?php echo $this->lang->line('photo_challan_hint'); ?></small>
                    <span class="text-danger"><?php echo form_error('image'); ?></span>
                </div>
                
                <div class="form-group">
                    <label><?php echo $this->lang->line('remarks'); ?></label>
                    <textarea class="form-control" name="remarks" rows="2" placeholder="Enter any extra details or observations..."><?php echo set_value('remarks'); ?></textarea>
                </div>
            </div>
            <div class="modern-drawer-footer">
                <button type="button" class="btn btn-default" id="btn-cancel-material-drawer"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $this->lang->line('save'); ?></button>
            </div>
        </form>
    </div>
<?php } ?>

<!-- View / Print Material Gate Pass Slip Modal -->
<div class="modal fade" id="viewSlipModal" tabindex="-1" role="dialog" aria-labelledby="viewSlipModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="viewSlipModalLabel"><i class="fa fa-file-text-o text-primary"></i> Material Gate Pass Slip</h4>
            </div>
            <div class="modal-body" id="printableSlip">
                <div style="text-align: center; border-bottom: 2px dashed #cbd5e1; padding-bottom: 14px; margin-bottom: 18px;">
                    <h3 style="margin: 0; font-weight: 800; color: #0f172a; letter-spacing: 0.5px;">MATERIAL GATE PASS</h3>
                    <p style="margin: 6px 0 0 0; font-size: 13px;" id="slip-direction-badge"></p>
                </div>
                <table class="table table-bordered" style="margin-bottom: 0;">
                    <tr>
                        <th style="width: 25%; background: #f8fafc; color: #475569;">Gate Pass / Challan No.</th>
                        <td style="width: 25%; font-weight: 700;" id="slip-gate-pass-no"></td>
                        <th style="width: 25%; background: #f8fafc; color: #475569;">Date</th>
                        <td style="width: 25%;" id="slip-date"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc; color: #475569;">Material / Item Name</th>
                        <td style="font-weight: 600;" id="slip-material-name"></td>
                        <th style="background: #f8fafc; color: #475569;">Quantity</th>
                        <td id="slip-quantity"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc; color: #475569;">From / To (Party)</th>
                        <td id="slip-party-name"></td>
                        <th style="background: #f8fafc; color: #475569;">Carried By</th>
                        <td id="slip-carried-by"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc; color: #475569;">Vehicle No.</th>
                        <td id="slip-vehicle-no"></td>
                        <th style="background: #f8fafc; color: #475569;">Driver Name</th>
                        <td id="slip-driver-name"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc; color: #475569;">Received / Issued By</th>
                        <td id="slip-staff-name"></td>
                        <th style="background: #f8fafc; color: #475569;">Department</th>
                        <td id="slip-department"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc; color: #475569;">Approved By</th>
                        <td id="slip-approved-by"></td>
                        <th style="background: #f8fafc; color: #475569;">In Time / Out Time</th>
                        <td id="slip-time"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc; color: #475569;">Remarks</th>
                        <td colspan="3" id="slip-remarks"></td>
                    </tr>
                    <tr id="slip-image-row" style="display: none;">
                        <th style="background: #f8fafc; color: #475569;">Attachment / Photo</th>
                        <td colspan="3" id="slip-image-container"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
                <button type="button" class="btn btn-primary" onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var MATERIAL_ADDITEM_URL = "<?php echo site_url('admin/materialregister/additem'); ?>";
    var MATERIAL_DETAILS_URL = "<?php echo site_url('admin/materialregister/get_details_json/'); ?>";
    var HAS_FORM_ERRORS = <?php echo (validation_errors() != '') ? 'true' : 'false'; ?>;

    $(document).ready(function () {
        initMaterialSelect2();

        // Right side drawer toggle actions
        $('#btn-open-material-drawer').on('click', function () {
            openMaterialDrawer();
        });
        $('#btn-close-material-drawer, #btn-cancel-material-drawer, #material-drawer-overlay').on('click', function () {
            closeMaterialDrawer();
        });

        // Open drawer automatically if form validation failed
        if (HAS_FORM_ERRORS) {
            openMaterialDrawer();
        }

        // View slip details
        $('.btn-view-slip').on('click', function () {
            var id = $(this).data('id');
            $.getJSON(MATERIAL_DETAILS_URL + id, function (res) {
                if (res.status === 'success') {
                    var d = res.data;
                    $('#slip-direction-badge').html(
                        d.direction === 'inward' 
                        ? '<span class="label label-success"><i class="fa fa-arrow-down"></i> INWARD ENTRY</span>' 
                        : '<span class="label label-warning"><i class="fa fa-arrow-up"></i> OUTWARD ENTRY</span>'
                    );
                    $('#slip-gate-pass-no').text(d.gate_pass_no || '-');
                    $('#slip-date').text(d.formatted_date || d.date || '-');
                    $('#slip-material-name').text(d.material_name || '-');
                    $('#slip-quantity').text($.trim((d.quantity || '') + ' ' + (d.unit || '')) || '-');
                    $('#slip-party-name').text(d.party_name || '-');
                    $('#slip-carried-by').html((d.carried_by || '-') + (d.contact ? ' (' + d.contact + ')' : ''));
                    $('#slip-vehicle-no').text(d.vehicle_no || '-');
                    $('#slip-driver-name').text(d.driver_name || '-');
                    $('#slip-staff-name').text(d.staff_full_name || '-');
                    $('#slip-department').text(d.department || '-');
                    $('#slip-approved-by').text(d.approved_by || '-');
                    $('#slip-time').text('In: ' + (d.in_time || '-') + ' | Out: ' + (d.out_time || '-'));
                    $('#slip-remarks').text(d.remarks || '-');

                    if (d.image) {
                        $('#slip-image-container').html('<a href="<?php echo site_url('admin/materialregister/download/'); ?>' + d.id + '" target="_blank" class="btn btn-xs btn-default"><i class="fa fa-paperclip"></i> View Attachment</a>');
                        $('#slip-image-row').show();
                    } else {
                        $('#slip-image-row').hide();
                    }

                    $('#viewSlipModal').modal('show');
                }
            });
        });
    });

    function openMaterialDrawer() {
        $('#material-drawer-overlay').addClass('is-active');
        $('#material-drawer-panel').addClass('is-open');
        $('body').addClass('drawer-open').css('overflow', 'hidden');
    }

    function closeMaterialDrawer() {
        $('#material-drawer-panel').removeClass('is-open');
        $('#material-drawer-overlay').removeClass('is-active');
        $('body').removeClass('drawer-open').css('overflow', '');
    }

    function initMaterialSelect2() {
        $('.staff-select2').select2({ width: '100%' });

        $('.material-select2').each(function () {
            var $s = $(this);
            $s.data('known', $s.find('option').map(function () { return this.value; }).get());
            $s.select2({
                width: '100%',
                tags: true,
                allowClear: true,
                placeholder: $s.data('placeholder') || '',
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') { return null; }
                    return { id: term, text: term, newTag: true };
                }
            });
        });

        $('.material-select2').on('select2:select', function (e) {
            var $s    = $(this);
            var data  = e.params.data;
            var val   = data.id;
            var known = $s.data('known') || [];
            if (val && (data.newTag === true || known.indexOf(val) === -1)) {
                known.push(val);
                $s.data('known', known);
                $.post(MATERIAL_ADDITEM_URL, { type: $s.data('type'), name: val }, function () {}, 'json');
            }
        });
    }
</script>
