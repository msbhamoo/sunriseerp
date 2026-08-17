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
                    <form action="<?php echo site_url('admin/materialregister/edit/' . $material_data['id']); ?>" method="post" enctype="multipart/form-data">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('direction'); ?></label> <small class="req">*</small>
                                        <select class="form-control" name="direction">
                                            <option value="inward" <?php echo set_select('direction', 'inward', $material_data['direction'] == 'inward'); ?>><?php echo $this->lang->line('inward'); ?></option>
                                            <option value="outward" <?php echo set_select('direction', 'outward', $material_data['direction'] == 'outward'); ?>><?php echo $this->lang->line('outward'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('direction'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date'); ?></label> <small class="req">*</small>
                                        <input type="text" class="form-control date" name="date" readonly value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat(), strtotime($material_data['date']))); ?>"/>
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('material_name'); ?></label> <small class="req">*</small>
                                        <?php echo material_select('material_name', 'item', $masters['item'], set_value('material_name', $material_data['material_name']), $this->lang->line('select')); ?>
                                        <span class="text-danger"><?php echo form_error('material_name'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('quantity'); ?></label>
                                        <?php echo material_select('quantity', 'quantity', $masters['quantity'], set_value('quantity', $material_data['quantity']), $this->lang->line('select')); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('unit'); ?></label>
                                        <?php echo material_select('unit', 'unit', $masters['unit'], set_value('unit', $material_data['unit']), $this->lang->line('select')); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('carried_by'); ?></label>
                                        <input class="form-control" name="carried_by" placeholder="e.g. John Doe" value="<?php echo set_value('carried_by', $material_data['carried_by']); ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('contact'); ?></label>
                                        <input class="form-control" name="contact" placeholder="e.g. +91 9876543210" value="<?php echo set_value('contact', $material_data['contact']); ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-8">
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
                                        <label><?php echo $this->lang->line('attachment'); ?></label>
                                        <input type="file" name="image" class="form-control" style="padding: 4px; height: auto;"/>
                                        <?php if (!empty($material_data['image'])) { ?>
                                            <div style="margin-top: 6px;">
                                                <a href="<?php echo site_url('admin/materialregister/download/' . $material_data['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-paperclip"></i> <?php echo $this->lang->line('current_file'); ?></a>
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

<script>
    var MATERIAL_ADDITEM_URL = "<?php echo site_url('admin/materialregister/additem'); ?>";
    $(document).ready(function () {
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
    });
</script>
