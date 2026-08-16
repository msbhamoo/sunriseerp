<style>
/* Modern Right Side Drawer Styles */
.material-drawer-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(2px);
    z-index: 1040;
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.material-drawer-overlay.is-active {
    display: block;
    opacity: 1;
}
.material-drawer-panel {
    position: fixed;
    top: 0;
    right: -550px;
    width: 520px;
    max-width: 92vw;
    height: 100vh;
    background: #ffffff;
    z-index: 1050;
    box-shadow: -8px 0 30px rgba(0, 0, 0, 0.18);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.material-drawer-panel.is-open {
    right: 0;
}
.material-drawer-panel form {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 0;
    overflow: hidden;
    margin: 0;
}
.material-drawer-header {
    padding: 16px 22px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8fafc;
    flex-shrink: 0;
}
.material-drawer-title {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
}
.material-drawer-close {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    width: 30px;
    height: 30px;
    font-size: 18px;
    color: #64748b;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.material-drawer-close:hover {
    background: #fee2e2;
    color: #ef4444;
    border-color: #fca5a5;
}
.material-drawer-body {
    padding: 22px;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch;
    flex: 1;
    min-height: 0;
}
.material-drawer-footer {
    padding: 14px 22px;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}
/* Slip Print Modal Styling */
@media print {
    body * {
        visibility: hidden;
    }
    #printableSlip, #printableSlip * {
        visibility: visible;
    }
    #printableSlip {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>

<div class="content-wrapper" style="min-height: 348px;">
    <section class="content-header">
        <h1><i class="fa fa-truck"></i> <?php echo $this->lang->line('material_register'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('material_register_list'); ?></h3>
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
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($value['date'])); ?></td>
                                            <td>
                                                <?php if ($value['direction'] == 'inward') { ?>
                                                    <span class="label label-success"><?php echo $this->lang->line('inward'); ?></span>
                                                <?php } else { ?>
                                                    <span class="label label-warning"><?php echo $this->lang->line('outward'); ?></span>
                                                <?php } ?>
                                            </td>
                                            <td><strong><?php echo html_escape($value['material_name']); ?></strong></td>
                                            <td><?php echo html_escape(trim($value['quantity'] . ' ' . $value['unit'])); ?></td>
                                            <td>
                                                <?php echo html_escape($value['carried_by']);
                                                if (!empty($value['contact'])) {
                                                    echo '<br><small class="text-muted"><i class="fa fa-phone"></i> ' . html_escape($value['contact']) . '</small>';
                                                } ?>
                                            </td>
                                            <td><?php echo html_escape($value['party_name']); ?></td>
                                            <td><?php echo html_escape($value['vehicle_no']); ?></td>
                                            <td><code><?php echo html_escape($value['gate_pass_no']); ?></code></td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-default btn-xs btn-view-slip" data-id="<?php echo $value['id']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <?php if (!empty($value['image'])) { ?>
                                                    <a href="<?php echo site_url('admin/materialregister/download/' . $value['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('attachment'); ?>"><i class="fa fa-paperclip"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('material_register', 'can_edit')) { ?>
                                                    <a href="<?php echo site_url('admin/materialregister/edit/' . $value['id']); ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('material_register', 'can_delete')) { ?>
                                                    <a href="<?php echo site_url('admin/materialregister/delete/' . $value['id']); ?>" class="btn btn-primary btn-xs" onclick="return confirm('<?php echo $this->lang->line('delete_confirm'); ?>');" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-remove"></i></a>
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

<!-- Right Side Drawer for Adding Material Entry -->
<?php if ($this->rbac->hasPrivilege('material_register', 'can_add')) { ?>
    <div id="material-drawer-overlay" class="material-drawer-overlay"></div>
    <div id="material-drawer-panel" class="material-drawer-panel">
        <div class="material-drawer-header">
            <h4 class="material-drawer-title"><i class="fa fa-plus-circle text-primary"></i> <?php echo $this->lang->line('add_material_entry'); ?></h4>
            <button type="button" class="material-drawer-close" id="btn-close-material-drawer">&times;</button>
        </div>
        <form id="materialform" action="<?php echo site_url('admin/materialregister'); ?>" method="post" enctype="multipart/form-data">
            <?php echo $this->customlib->getCSRF(); ?>
            <div class="material-drawer-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('direction'); ?></label> <small class="req"> *</small>
                            <select class="form-control" name="direction">
                                <option value="inward" <?php echo set_select('direction', 'inward', true); ?>><?php echo $this->lang->line('inward'); ?></option>
                                <option value="outward" <?php echo set_select('direction', 'outward'); ?>><?php echo $this->lang->line('outward'); ?></option>
                            </select>
                            <span class="text-danger"><?php echo form_error('direction'); ?></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('date'); ?></label> <small class="req"> *</small>
                            <input type="text" class="form-control date" name="date" readonly value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>"/>
                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo $this->lang->line('material_name'); ?></label> <small class="req"> *</small>
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
                            <input class="form-control" name="carried_by" value="<?php echo set_value('carried_by'); ?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('contact'); ?></label>
                            <input class="form-control" name="contact" value="<?php echo set_value('contact'); ?>"/>
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
                            <small class="text-muted">Auto-generated sequence</small>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo $this->lang->line('driver_name'); ?></label>
                    <?php echo material_select('driver_name', 'driver', $masters['driver'], set_value('driver_name'), $this->lang->line('select')); ?>
                </div>
                <div class="form-group">
                    <label><?php echo $this->lang->line('received_issued_by'); ?></label>
                    <select class="form-control" name="staff_id">
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
                    <input type="file" name="image"/>
                    <small class="text-muted"><?php echo $this->lang->line('photo_challan_hint'); ?></small>
                    <span class="text-danger"><?php echo form_error('image'); ?></span>
                </div>
                <div class="form-group">
                    <label><?php echo $this->lang->line('remarks'); ?></label>
                    <textarea class="form-control" name="remarks" rows="2"><?php echo set_value('remarks'); ?></textarea>
                </div>
            </div>
            <div class="material-drawer-footer">
                <button type="button" class="btn btn-default" id="btn-cancel-material-drawer"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> <?php echo $this->lang->line('save'); ?></button>
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
                <h4 class="modal-title" id="viewSlipModalLabel"><i class="fa fa-file-text-o"></i> Material Pass Slip</h4>
            </div>
            <div class="modal-body" id="printableSlip">
                <div style="text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 16px;">
                    <h3 style="margin: 0; font-weight: 700; color: #1e293b;">MATERIAL GATE PASS</h3>
                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 13px;" id="slip-direction-badge"></p>
                </div>
                <table class="table table-bordered" style="margin-bottom: 0;">
                    <tr>
                        <th style="width: 25%; background: #f8fafc;">Gate Pass / Challan No.</th>
                        <td style="width: 25%;" id="slip-gate-pass-no"></td>
                        <th style="width: 25%; background: #f8fafc;">Date</th>
                        <td style="width: 25%;" id="slip-date"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc;">Material / Item Name</th>
                        <td id="slip-material-name"></td>
                        <th style="background: #f8fafc;">Quantity</th>
                        <td id="slip-quantity"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc;">From / To (Party)</th>
                        <td id="slip-party-name"></td>
                        <th style="background: #f8fafc;">Carried By</th>
                        <td id="slip-carried-by"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc;">Vehicle No.</th>
                        <td id="slip-vehicle-no"></td>
                        <th style="background: #f8fafc;">Driver Name</th>
                        <td id="slip-driver-name"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc;">Received / Issued By</th>
                        <td id="slip-staff-name"></td>
                        <th style="background: #f8fafc;">Department</th>
                        <td id="slip-department"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc;">Approved By</th>
                        <td id="slip-approved-by"></td>
                        <th style="background: #f8fafc;">In Time / Out Time</th>
                        <td id="slip-time"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc;">Remarks</th>
                        <td colspan="3" id="slip-remarks"></td>
                    </tr>
                    <tr id="slip-image-row" style="display: none;">
                        <th style="background: #f8fafc;">Attachment / Photo</th>
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
                        ? '<span class="label label-success">INWARD ENTRY</span>' 
                        : '<span class="label label-warning">OUTWARD ENTRY</span>'
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
