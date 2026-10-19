<style type="text/css">
    .req{color:red;}
    .item-section { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 4px; }
    .item-section h4 { margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    .checkbox-list { max-height: 150px; overflow-y: auto; }
    .installment-row { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
    #matrix-container { overflow-x: auto; margin-top: 20px; }
    .matrix-table { width: 100%; min-width: 600px; }
    .matrix-table th, .matrix-table td { padding: 8px; text-align: center; }
    .matrix-table th { background: #f4f4f4; }
    .matrix-table td.text-left { text-align: left; }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="form-title">Add Installment Report Config</h3>
                    </div>
                    <form id="form1" action="<?php echo site_url('admin/installmentreportconfig/save') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body row">
                            <input type="hidden" name="id" id="plan_id" value="0">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Plan Name <small class="req"> *</small></label>
                                    <input type="text" class="form-control" name="name" id="name" required />
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Target Class (Leave empty for Global)</label>
                                    <select class="form-control" name="class_id" id="class_id">
                                        <option value="">-- Global --</option>
                                        <?php foreach ($classes as $cls) { ?>
                                            <option value="<?php echo $cls['id'] ?>"><?php echo $cls['class'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Number of Installments <small class="req"> *</small></label>
                                    <select class="form-control" name="total_installments" id="total_installments" onchange="generateMatrix()" required>
                                        <option value="">Select</option>
                                        <?php for ($i=2; $i<=12; $i++) { ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div id="dates_container" style="margin-bottom:20px; display:flex; gap: 15px; flex-wrap: wrap;"></div>
                            </div>

                            <div class="col-md-4">
                                <div class="item-section">
                                    <h4>Select Fee Groups</h4>
                                    <div class="checkbox-list">
                                        <?php foreach ($fee_groups as $fg) { ?>
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="fee_groups[]" value="<?php echo $fg['id'] ?>" data-name="<?php echo $fg['name'] ?>" data-type="fee_group" class="item-cb"> <?php echo $fg['name'] ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="item-section">
                                    <h4>Select Hostel Fee Groups</h4>
                                    <div class="checkbox-list">
                                        <?php foreach ($hostel_groups as $hg) { ?>
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="hostel_groups[]" value="<?php echo $hg['id'] ?>" data-name="Hostel: <?php echo $hg['name'] ?>" data-type="hostel" class="item-cb"> <?php echo $hg['name'] ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="item-section">
                                    <h4>Select Transport Yearly Fees</h4>
                                    <div class="checkbox-list">
                                        <?php foreach ($transport_yearly as $ty) { ?>
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="transport_yearly[]" value="<?php echo $ty['id'] ?>" data-name="Transport: <?php echo $ty['class'] ?> (<?php echo $ty['amount'] ?>)" data-type="transport_yearly" class="item-cb"> <?php echo $ty['class'] ?> - <?php echo $ty['amount'] ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" id="matrix-container" style="display:none;">
                                <h4>Percentage Matrix (Must total 100% per row)</h4>
                                <table class="table table-bordered matrix-table" id="matrix-table">
                                    <thead>
                                        <tr id="matrix-header">
                                            <th>Fee Category</th>
                                            <!-- Headers generated dynamically -->
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="matrix-body">
                                        <!-- Rows generated dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-md-12 mt-4">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Installment Report Configs</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Installments</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($plans as $plan) { ?>
                                        <tr>
                                            <td><?php echo $plan['name'] ?></td>
                                            <td><?php echo $plan['class_name'] ? $plan['class_name'] : 'Global' ?></td>
                                            <td><?php echo $plan['total_installments'] ?></td>
                                            <td class="text-right">
                                                <a href="#" onclick="editPlan(<?php echo $plan['id'] ?>)" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
                                                <a href="<?php echo site_url('admin/installmentreportconfig/delete/'.$plan['id']) ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this?')"><i class="fa fa-remove"></i></a>
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
    function generateMatrix() {
        var num = parseInt($('#total_installments').val());
        var datesContainer = $('#dates_container');
        var matrixHeader = $('#matrix-header');
        
        datesContainer.empty();
        $('#matrix-header th.inst-col').remove();
        
        if (num && num > 0) {
            $('#matrix-container').show();
            
            // Add Date Inputs
            for (var i = 1; i <= num; i++) {
                datesContainer.append(
                    '<div style="flex:1; min-width: 150px;">' +
                    '<label>Inst ' + i + ' Due Date <small class="req">*</small></label>' +
                    '<input type="text" class="form-control date" name="due_date[]" required>' +
                    '</div>'
                );
                
                // Add Matrix Headers
                $('<th class="inst-col">Inst ' + i + ' (%)</th>').insertBefore(matrixHeader.find('th:last'));
            }
            
            $('.date').datepicker({
                format: "<?php echo $this->customlib->getSchoolDateFormat(); ?>",
                autoclose: true,
                todayHighlight: true
            });
            
            rebuildMatrixRows();
        } else {
            $('#matrix-container').hide();
            $('#matrix-body').empty();
        }
    }

    function rebuildMatrixRows() {
        var num = parseInt($('#total_installments').val());
        if (!num) return;
        
        var matrixBody = $('#matrix-body');
        
        // Find what's checked
        $('.item-cb').each(function() {
            var cb = $(this);
            var id = cb.val();
            var type = cb.data('type');
            var name = cb.data('name');
            var rowId = 'matrix-row-' + type + '-' + id;
            
            if (cb.is(':checked')) {
                if ($('#' + rowId).length === 0) {
                    var tr = $('<tr id="' + rowId + '"></tr>');
                    tr.append('<td class="text-left">' + name + '</td>');
                    
                    for (var i = 1; i <= num; i++) {
                        tr.append('<td><input type="number" step="0.01" min="0" max="100" class="form-control percent-input" name="split_percentage[' + type + '][' + id + '][' + i + ']" value="0" required></td>');
                    }
                    
                    tr.append('<td class="row-total">0%</td>');
                    matrixBody.append(tr);
                } else {
                    // Row exists, but column count might be wrong if they changed total_installments
                    var existingInputs = $('#' + rowId).find('.percent-input').length;
                    if (existingInputs !== num) {
                        $('#' + rowId).remove();
                        cb.trigger('change'); // re-add
                    }
                }
            } else {
                $('#' + rowId).remove();
            }
        });
        
        calculateTotals();
    }

    function calculateTotals() {
        $('.matrix-table tbody tr').each(function() {
            var sum = 0;
            $(this).find('.percent-input').each(function() {
                sum += parseFloat($(this).val()) || 0;
            });
            var totalCell = $(this).find('.row-total');
            totalCell.text(sum.toFixed(2) + '%');
            if (Math.abs(sum - 100) > 0.01) {
                totalCell.css('color', 'red');
            } else {
                totalCell.css('color', 'green');
            }
        });
    }

    $(document).ready(function() {
        $('.item-cb').on('change', function() {
            rebuildMatrixRows();
        });
        
        $(document).on('input', '.percent-input', function() {
            calculateTotals();
        });
        
        $("#form1").on('submit', (function(e) {
            e.preventDefault();
            
            var allTotalsValid = true;
            $('.matrix-table tbody tr').each(function() {
                var sum = 0;
                $(this).find('.percent-input').each(function() {
                    sum += parseFloat($(this).val()) || 0;
                });
                if (Math.abs(sum - 100) > 0.01) {
                    allTotalsValid = false;
                }
            });
            
            if ($('.item-cb:checked').length === 0) {
                errorMsg("Please select at least one fee group.");
                return false;
            }
            
            if (!allTotalsValid) {
                errorMsg('All selected fee groups must have percentages totaling exactly 100%.');
                return false;
            }
            
            $.ajax({
                url: "<?php echo site_url('admin/installmentreportconfig/save') ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.status == "fail") {
                        errorMsg(res.error);
                    } else {
                        successMsg(res.message);
                        window.location.reload(true);
                    }
                }
            });
        }));
    });

    function editPlan(id) {
        $('#form-title').html('Edit Installment Report Config');
        $('#plan_id').val(id);
        $('.item-cb').prop('checked', false);
        $('#matrix-body').empty();
        
        $.ajax({
            url: "<?php echo site_url('admin/installmentreportconfig/get_plan') ?>",
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function(res) {
                $('#name').val(res.name);
                $('#class_id').val(res.class_id);
                $('#total_installments').val(res.total_installments);
                
                generateMatrix();
                
                if (res.dates) {
                    var dateInputs = $("input[name='due_date[]']");
                    $.each(res.dates, function(idx, d) {
                        $(dateInputs[idx]).datepicker('update', d.due_date);
                    });
                }
                
                if (res.splits) {
                    // First check the boxes
                    $.each(res.splits, function(idx, split) {
                        var cb = $(".item-cb[data-type='" + split.fee_source_type + "'][value='" + split.fee_source_id + "']");
                        cb.prop('checked', true);
                    });
                    
                    rebuildMatrixRows();
                    
                    // Then fill the values
                    $.each(res.splits, function(idx, split) {
                        var input = $("input[name='split_percentage[" + split.fee_source_type + "][" + split.fee_source_id + "][" + split.installment_number + "]']");
                        input.val(split.percentage);
                    });
                    
                    calculateTotals();
                }
            }
        });
    }
</script>