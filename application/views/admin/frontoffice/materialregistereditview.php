<div class="content-wrapper" style="min-height: 348px;">
    <section class="content-header">
        <h1><i class="fa fa-truck"></i> <?php echo $this->lang->line('material_register'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-pencil-square-o text-primary" style="margin-right: 6px;"></i> <?php echo $this->lang->line('edit_material_entry'); ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/materialregister'); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></a>
                        </div>
                    </div>
                    <form id="materialeditform" action="<?php echo site_url('admin/materialregister/edit/' . $material_data['id']); ?>" method="post" enctype="multipart/form-data">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('direction'); ?></label> <small class="req">*</small>
                                        <select class="form-control" name="direction">
                                            <option value="inward" <?php echo set_select('direction', 'inward', $material_data['direction'] == 'inward'); ?>><?php echo $this->lang->line('inward'); ?></option>
                                            <option value="outward" <?php echo set_select('direction', 'outward', $material_data['direction'] == 'outward'); ?>><?php echo $this->lang->line('outward'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('direction'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date'); ?></label> <small class="req">*</small>
                                        <input type="text" class="form-control date" name="date" readonly value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat(), strtotime($material_data['date']))); ?>"/>
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Repeatable Materials/Items Section -->
                            <div class="material-items-wrapper" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 18px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <label style="margin: 0; font-weight: 700; color: #1e293b; font-size: 13px;">
                                        <i class="fa fa-cubes text-primary" style="margin-right: 5px;"></i> Materials / Items <small class="req">*</small>
                                    </label>
                                    <button type="button" class="btn btn-primary btn-xs btn-add-item-row" style="border-radius: 4px; padding: 4px 10px; font-weight: 600;">
                                        <i class="fa fa-plus"></i> Add Item
                                    </button>
                                </div>
                                <span class="text-danger"><?php echo form_error('material_name[]'); ?></span>

                                <div id="material-items-container">
                                    <?php
                                    $existing_items = !empty($material_data['items']) ? $material_data['items'] : array();
                                    if (empty($existing_items) && !empty($material_data['material_name'])) {
                                        $existing_items[] = array(
                                            'material_name' => $material_data['material_name'],
                                            'quantity'      => $material_data['quantity'],
                                            'unit'          => $material_data['unit'],
                                        );
                                    }
                                    if (empty($existing_items)) {
                                        $existing_items[] = array('material_name' => '', 'quantity' => '', 'unit' => '');
                                    }

                                    $count_items = count($existing_items);
                                    foreach ($existing_items as $index => $item_row) {
                                    ?>
                                        <div class="material-item-row" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-bottom: 10px; position: relative;">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group" style="margin-bottom: 0;">
                                                        <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('material_name'); ?> <small class="req">*</small></label>
                                                        <?php echo material_select_array('material_name[]', 'item', $masters['item'], $item_row['material_name'], $this->lang->line('select')); ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group" style="margin-bottom: 0;">
                                                        <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('quantity'); ?></label>
                                                        <?php echo material_select_array('quantity[]', 'quantity', $masters['quantity'], $item_row['quantity'], $this->lang->line('select')); ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group" style="margin-bottom: 0;">
                                                        <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('unit'); ?></label>
                                                        <?php echo material_select_array('unit[]', 'unit', $masters['unit'], $item_row['unit'], $this->lang->line('select')); ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group" style="margin-bottom: 0;">
                                                        <label style="font-size: 12px; color: #64748b;">Total Cost</label>
                                                        <input type="number" step="any" min="0" class="form-control" name="total_cost[]" placeholder="0.00" value="<?php echo html_escape(isset($item_row['total_cost']) ? $item_row['total_cost'] : ''); ?>" style="height: 34px; font-size: 13px;"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1" style="display: flex; align-items: flex-end; justify-content: center; height: 59px;">
                                                    <button type="button" class="btn btn-danger btn-xs btn-remove-item-row" title="Remove Item" style="<?php echo ($count_items <= 1) ? 'display:none;' : ''; ?> border-radius: 4px; padding: 5px 8px;">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('carried_by'); ?></label>
                                        <input class="form-control" name="carried_by" placeholder="e.g. John Doe" value="<?php echo set_value('carried_by', $material_data['carried_by']); ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('contact'); ?></label>
                                        <input class="form-control" name="contact" placeholder="e.g. +91 9876543210" value="<?php echo set_value('contact', $material_data['contact']); ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('party_name'); ?></label>
                                        <?php echo material_select('party_name', 'party', $masters['party'], set_value('party_name', $material_data['party_name']), $this->lang->line('from_to_hint')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-sm-4">
                                     <div class="form-group">
                                         <label><?php echo $this->lang->line('vehicle_no'); ?></label>
                                         <?php echo material_select('vehicle_no', 'vehicle', $masters['vehicle'], set_value('vehicle_no', $material_data['vehicle_no']), $this->lang->line('select')); ?>
                                     </div>
                                 </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('gate_pass_no'); ?></label>
                                        <input class="form-control" name="gate_pass_no" value="<?php echo set_value('gate_pass_no', $material_data['gate_pass_no']); ?>"/>
                                    </div>
                                </div>
                                 <div class="col-sm-4">
                                     <div class="form-group">
                                         <label><?php echo $this->lang->line('driver_name'); ?></label>
                                         <?php echo material_select('driver_name', 'driver', $masters['driver'], set_value('driver_name', $material_data['driver_name']), $this->lang->line('select')); ?>
                                     </div>
                                 </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('received_issued_by'); ?></label>
                                        <select class="form-control staff-select2" name="staff_id">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($stafflist as $s) { ?>
                                                <option value="<?php echo $s['id']; ?>" <?php echo set_select('staff_id', $s['id'], $material_data['staff_id'] == $s['id']); ?>><?php echo $s['name'] . ' ' . $s['surname'] . ' (' . $s['employee_id'] . ')'; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('department'); ?></label>
                                        <?php echo material_select('department', 'department', $masters['department'], set_value('department', $material_data['department']), $this->lang->line('select')); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('approved_by'); ?></label>
                                        <?php $appr = set_value('approved_by', $material_data['approved_by']); ?>
                                        <select class="form-control staff-select2" name="approved_by">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                             $staff_names = array();
                                             foreach ($stafflist as $s) {
                                                 $full          = $s['name'] . ' ' . $s['surname'];
                                                 $staff_names[] = $full;
                                                 echo '<option value="' . html_escape($full) . '" ' . ($appr == $full ? 'selected' : '') . '>' . html_escape($full . ' (' . $s['employee_id'] . ')') . '</option>';
                                             }
                                             if ($appr !== '' && !in_array($appr, $staff_names, true)) {
                                                 echo '<option value="' . html_escape($appr) . '" selected>' . html_escape($appr) . '</option>';
                                             }
                                             ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('in_time'); ?></label>
                                        <input class="form-control" name="in_time" placeholder="HH:MM" value="<?php echo set_value('in_time', $material_data['in_time']); ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('out_time'); ?></label>
                                        <input class="form-control" name="out_time" placeholder="HH:MM" value="<?php echo set_value('out_time', $material_data['out_time']); ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label style="font-weight: 600; color: #1e293b;"><?php echo $this->lang->line('attachment') ? $this->lang->line('attachment') : 'Attachment (Photo / Challan)'; ?></label>
                                        <div class="custom-file-upload-box" style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 12px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease; position: relative;" onclick="$('#edit_material_image_input').trigger('click');">
                                            <input type="file" name="image" id="edit_material_image_input" style="display: none;" accept=".jpg,.jpeg,.png,.gif,.pdf" onchange="var name = this.files[0] ? this.files[0].name : ''; $('#edit-upload-file-name-display').text(name || 'Click to choose file');"/>
                                            <i class="fa fa-cloud-upload" style="font-size: 22px; color: #6366f1; margin-bottom: 4px;"></i>
                                            <div id="edit-upload-file-name-display" style="font-size: 12px; font-weight: 600; color: #334155;">Click to replace file</div>
                                            <small class="text-muted" style="display: block; font-size: 11px;"><?php echo $this->lang->line('photo_challan_hint') ? $this->lang->line('photo_challan_hint') : 'JPG, PNG, GIF or PDF up to 5 MB'; ?></small>
                                        </div>
                                        <?php if (!empty($material_data['image'])) { ?>
                                            <div style="margin-top: 6px;">
                                                <a href="<?php echo site_url('admin/materialregister/download/' . $material_data['id']); ?>" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-paperclip"></i> <?php echo $this->lang->line('current_file') ? $this->lang->line('current_file') : 'Current File'; ?></a>
                                            </div>
                                        <?php } ?>
                                        <span class="text-danger"><?php echo form_error('image'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('remarks'); ?></label>
                                <textarea class="form-control" name="remarks" rows="2" placeholder="Enter any extra details..."><?php echo set_value('remarks', $material_data['remarks']); ?></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="<?php echo site_url('admin/materialregister'); ?>" class="btn btn-default"><?php echo $this->lang->line('cancel'); ?></a>
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> <?php echo $this->lang->line('update'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<template id="material-item-template">
    <div class="material-item-row" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-bottom: 10px; position: relative;">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('material_name'); ?> <small class="req">*</small></label>
                    <?php echo material_select_array('material_name[]', 'item', $masters['item'], '', $this->lang->line('select')); ?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('quantity'); ?></label>
                    <?php echo material_select_array('quantity[]', 'quantity', $masters['quantity'], '', $this->lang->line('select')); ?>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('unit'); ?></label>
                    <?php echo material_select_array('unit[]', 'unit', $masters['unit'], '', $this->lang->line('select')); ?>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-size: 12px; color: #64748b;">Total Cost</label>
                    <input type="number" step="any" min="0" class="form-control" name="total_cost[]" placeholder="0.00" style="height: 34px; font-size: 13px;"/>
                </div>
            </div>
            <div class="col-sm-1" style="display: flex; align-items: flex-end; justify-content: center; height: 59px;">
                <button type="button" class="btn btn-danger btn-xs btn-remove-item-row" title="Remove Item" style="border-radius: 4px; padding: 5px 8px;">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    var MATERIAL_ADDITEM_URL = "<?php echo site_url('admin/materialregister/additem'); ?>";
    $(document).ready(function () {
        initMaterialSelect2($('#materialeditform'));

        // Add more item rows
        $(document).on('click', '.btn-add-item-row', function () {
            var template = document.getElementById('material-item-template');
            if (template) {
                var clone = $(template.content.cloneNode(true));
                $('#material-items-container').append(clone);
                initMaterialSelect2($('#material-items-container .material-item-row:last'));
                updateRemoveButtonsVisibility();
            }
        });

        // Remove item row
        $(document).on('click', '.btn-remove-item-row', function () {
            $(this).closest('.material-item-row').remove();
            updateRemoveButtonsVisibility();
        });
    });

    function updateRemoveButtonsVisibility() {
        var rows = $('#material-items-container .material-item-row');
        if (rows.length > 1) {
            rows.find('.btn-remove-item-row').show();
        } else {
            rows.find('.btn-remove-item-row').hide();
        }
    }

    function initMaterialSelect2(context) {
        var $ctx = context ? $(context) : $(document);
        $ctx.find('.staff-select2').select2({ width: '100%' });

        $ctx.find('.material-select2').each(function () {
            var $s = $(this);
            if ($s.hasClass('select2-hidden-accessible')) {
                return;
            }
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

        $ctx.find('.material-select2').on('select2:select', function (e) {
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
