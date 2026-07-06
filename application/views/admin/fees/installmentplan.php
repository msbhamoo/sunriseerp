<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> Fees Collection
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('installment_plan', 'can_add')) { ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Installment Plan</h3>
                        </div>
                        <form id="form1" action="<?php echo site_url('admin/installmentplan/add') ?>" id="installmentform" name="installmentform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>

                                <?php if (isset($edit_plan)) { ?>
                                    <input type="hidden" name="id" value="<?php echo $edit_plan['id']; ?>">
                                <?php } ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Plan Name <small class="req">*</small></label>
                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo isset($edit_plan) ? $edit_plan['name'] : set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label>Is Global Plan? (Applies to all classes)</label><br>
                                    <label class="radio-inline"><input type="radio" name="is_global" value="1" <?php echo (!isset($edit_plan) || $edit_plan['is_global'] == 1) ? 'checked' : ''; ?> onclick="toggleClasses(true)"> Yes</label>
                                    <label class="radio-inline"><input type="radio" name="is_global" value="0" <?php echo (isset($edit_plan) && $edit_plan['is_global'] == 0) ? 'checked' : ''; ?> onclick="toggleClasses(false)"> No</label>
                                </div>
                                <div class="form-group" id="class_section" style="<?php echo (isset($edit_plan) && $edit_plan['is_global'] == 0) ? '' : 'display:none;'; ?>">
                                    <label>Select Classes <small class="req">*</small></label>
                                    <select id="class_id" name="class_id[]" multiple class="form-control" >
                                        <?php foreach ($classlist as $class) { 
                                            $selected = '';
                                            if(isset($edit_classes) && in_array($class['id'], $edit_classes)) $selected = 'selected';
                                        ?>
                                            <option value="<?php echo $class['id'] ?>" <?php echo $selected; ?>><?php echo $class['class'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <?php
                                $edit_item_vals = array();
                                if(isset($edit_items)){
                                    foreach($edit_items as $ei) {
                                        $edit_item_vals[] = $ei['fee_source_type'] . '-' . $ei['fee_source_id'];
                                    }
                                }
                                ?>
                                <div class="form-group">
                                    <label>Included Fee Items</label><br>
                                    <b>Academic Fees (Fee Groups)</b><br>
                                    <?php foreach ($feegroupList as $fg) { 
                                        $is_hostel = false;
                                        foreach($hostelFeegroupList as $hfg) {
                                            if($hfg['id'] == $fg['id']) { $is_hostel = true; break; }
                                        }
                                        if(!$is_hostel) {
                                            $checked = in_array('fee_group-'.$fg['id'], $edit_item_vals) ? 'checked' : '';
                                    ?>
                                    <label class="checkbox-inline"><input type="checkbox" name="fee_items[]" value="fee_group-<?php echo $fg['id'] ?>" <?php echo $checked; ?>> <?php echo $fg['name'] ?></label><br>
                                    <?php } } ?>
                                    <br><b>Hostel Fees</b><br>
                                    <?php foreach ($hostelFeegroupList as $hfg) { 
                                        $checked = in_array('fee_group-'.$hfg['id'], $edit_item_vals) ? 'checked' : '';
                                    ?>
                                    <label class="checkbox-inline"><input type="checkbox" name="fee_items[]" value="fee_group-<?php echo $hfg['id'] ?>" <?php echo $checked; ?>> <?php echo $hfg['name'] ?></label><br>
                                    <?php } ?>
                                    <br><b>Transport Yearly Fees</b><br>
                                    <?php foreach ($transportYearlyList as $ty) { 
                                        $checked = in_array('transport_yearly-'.$ty['id'], $edit_item_vals) ? 'checked' : '';
                                    ?>
                                    <label class="checkbox-inline"><input type="checkbox" name="fee_items[]" value="transport_yearly-<?php echo $ty['id'] ?>" <?php echo $checked; ?>> <?php echo $ty['name'] ?></label><br>
                                    <?php } ?>
                                </div>

                                <div class="form-group">
                                    <label>Total Installments <small class="req">*</small></label>
                                    <select name="total_installments" id="total_installments" class="form-control" onchange="generateInstallmentRows()">
                                        <option value="">Select</option>
                                        <?php for($i=1; $i<=12; $i++) { 
                                            $selected = (isset($edit_plan) && $edit_plan['total_installments'] == $i) ? 'selected' : '';
                                            echo "<option value='$i' $selected>$i</option>"; 
                                        } ?>
                                    </select>
                                </div>

                                <div id="installment_rows">
                                    <?php 
                                    if(isset($edit_details)) {
                                        foreach($edit_details as $det) {
                                            $i = $det['installment_number'];
                                            $date = $this->customlib->dateformat($det['due_date']);
                                    ?>
                                        <h4>Installment <?php echo $i; ?></h4>
                                        <div class="row">
                                        <div class="col-md-3"><label>Academic %</label><input type="number" step="0.01" class="form-control ac_perc" name="academic_percentage_<?php echo $i; ?>" value="<?php echo $det['academic_percentage']; ?>"></div>
                                        <div class="col-md-3"><label>Transport %</label><input type="number" step="0.01" class="form-control tr_perc" name="transport_percentage_<?php echo $i; ?>" value="<?php echo $det['transport_percentage']; ?>"></div>
                                        <div class="col-md-3"><label>Hostel %</label><input type="number" step="0.01" class="form-control ho_perc" name="hostel_percentage_<?php echo $i; ?>" value="<?php echo $det['hostel_percentage']; ?>"></div>
                                        <div class="col-md-3"><label>Due Date</label><input type="text" class="form-control date" name="due_date_<?php echo $i; ?>" value="<?php echo $date; ?>"></div>
                                        </div><hr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>

                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-info pull-right" onclick="submitPlan()">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('installment_plan', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Installment Plan List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Installments</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($planList as $plan) { ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $plan['name'] ?></td>
                                            <td class="mailbox-name"><?php echo $plan['is_global'] ? 'Global' : 'Per Class' ?></td>
                                            <td class="mailbox-name"><?php echo $plan['total_installments'] ?></td>
                                            <td class="mailbox-date pull-right">
                                                <?php
                                                if ($this->rbac->hasPrivilege('installment_plan', 'can_edit')) {
                                                    ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/installmentplan/edit/<?php echo $plan['id']; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php }
                                                if ($this->rbac->hasPrivilege('installment_plan', 'can_view')) {
                                                    ?>
                                                    <a data-placement="left" onclick="viewPlan(<?php echo $plan['id']; ?>)" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                                        <i class="fa fa-reorder"></i>
                                                    </a>
                                                <?php }
                                                if ($this->rbac->hasPrivilege('installment_plan', 'can_delete')) {
                                                    ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/installmentplan/delete/<?php echo $plan['id']; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
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

<script type="text/javascript">
    function toggleClasses(isGlobal) {
        if(isGlobal) {
            $('#class_section').hide();
        } else {
            $('#class_section').show();
        }
    }

    function generateInstallmentRows() {
        var count = $('#total_installments').val();
        var html = '';
        for(var i=1; i<=count; i++) {
            html += '<h4>Installment ' + i + '</h4>';
            html += '<div class="row">';
            html += '<div class="col-md-3"><label>Academic %</label><input type="number" step="0.01" class="form-control ac_perc" name="academic_percentage_' + i + '" value="0"></div>';
            html += '<div class="col-md-3"><label>Transport %</label><input type="number" step="0.01" class="form-control tr_perc" name="transport_percentage_' + i + '" value="0"></div>';
            html += '<div class="col-md-3"><label>Hostel %</label><input type="number" step="0.01" class="form-control ho_perc" name="hostel_percentage_' + i + '" value="0"></div>';
            html += '<div class="col-md-3"><label>Due Date</label><input type="text" class="form-control date" name="due_date_' + i + '"></div>';
            html += '</div><hr>';
        }
        $('#installment_rows').html(html);
    }

    function submitPlan() {
        // Validation for 100%
        var totalAc = 0, totalTr = 0, totalHo = 0;
        $('.ac_perc').each(function(){ totalAc += parseFloat($(this).val()) || 0; });
        $('.tr_perc').each(function(){ totalTr += parseFloat($(this).val()) || 0; });
        $('.ho_perc').each(function(){ totalHo += parseFloat($(this).val()) || 0; });
        
        var isAcChecked = false, isTrChecked = false, isHoChecked = false;
        $('input[name="fee_items[]"]:checked').each(function() {
            var val = $(this).val();
            // simple check, we should probably check properly based on actual types
            if(val.startsWith('fee_group-')) {
                // assume academic or hostel
                isAcChecked = true; 
                isHoChecked = true;
            }
            if(val.startsWith('transport_yearly-')) {
                isTrChecked = true;
            }
        });

        // if an item is checked, its percentages must sum to 100
        if(isAcChecked && totalAc > 0 && Math.abs(totalAc - 100) > 0.01) {
            alert('Total Academic Percentage must be exactly 100% (currently ' + totalAc + '%)');
            return false;
        }
        if(isTrChecked && totalTr > 0 && Math.abs(totalTr - 100) > 0.01) {
            alert('Total Transport Percentage must be exactly 100% (currently ' + totalTr + '%)');
            return false;
        }
        if(isHoChecked && totalHo > 0 && Math.abs(totalHo - 100) > 0.01) {
            alert('Total Hostel Percentage must be exactly 100% (currently ' + totalHo + '%)');
            return;
        }
        
        var dateMissing = false;
        $('.date').each(function() {
            if ($(this).val().trim() === '') {
                dateMissing = true;
            }
        });
        
        if (dateMissing) {
            alert('Please select a Due Date for every installment.');
            return;
        }

        var $form = $('#form1');
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: $form.serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.status == 'fail') {
                    var message = "";
                    $.each(res.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            }
        });
    }
</script>

<div class="modal fade" id="viewPlanModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Installment Plan Details</h4>
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Installment</th>
                                        <th>Academic %</th>
                                        <th>Transport %</th>
                                        <th>Hostel %</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody id="viewPlanBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function viewPlan(id) {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/installmentplan/getPlanDetails",
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (res)
            {
                var html = "";
                $.each(res.installments, function (index, value) {
                    html += "<tr>";
                    html += "<td>Installment " + value.installment_number + "</td>";
                    html += "<td>" + value.academic_percentage + "%</td>";
                    html += "<td>" + value.transport_percentage + "%</td>";
                    html += "<td>" + value.hostel_percentage + "%</td>";
                    html += "<td>" + value.due_date + "</td>";
                    html += "</tr>";
                });
                $('#viewPlanBody').html(html);
                $('#viewPlanModal').modal('show');
            }
        });
    }
</script>
