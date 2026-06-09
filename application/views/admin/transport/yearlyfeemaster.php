<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bus"></i> <?php echo $this->lang->line('transport'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('transport_fees_master', 'can_add')) { ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo "Add Yearly Fees"; // $this->lang->line('add_yearly_fees'); ?></h3>
                        </div>
                        <form id="form1" action="<?php echo site_url('admin/transportyearlyfee') ?>" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                
                                <div class="form-group">
                                    <label for="route_id"><?php echo $this->lang->line('route_list'); ?> <small class="req"> *</small></label>
                                    <select autofocus="" id="route_id" name="route_id" class="form-control" onchange="get_pickup_point(this.value)">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($vehroutelist as $vehroute) { ?>
                                            <optgroup label=" <?php echo $vehroute->route_title; ?>">
                                                <?php foreach ($vehroute->vehicles as $vehicle) { ?>
                                                    <option value="<?php echo $vehicle->vec_route_id ?>" <?php if (set_value('route_id') == $vehicle->vec_route_id) echo "selected"; ?>><?php echo $vehicle->vehicle_no ?></option>
                                                <?php } ?>
                                            </optgroup>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('route_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="route_pickup_point_id"><?php echo $this->lang->line('pickup_point'); ?> <small class="req"> *</small></label>
                                    <select id="pickup_point" name="route_pickup_point_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('route_pickup_point_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="feetype_id"><?php echo $this->lang->line('fees_type'); ?> <small class="req"> *</small></label>
                                    <select id="feetype_id" name="feetype_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($feetypelist as $feetype) { ?>
                                            <option value="<?php echo $feetype['id'] ?>" <?php if (set_value('feetype_id') == $feetype['id']) echo "selected"; ?>><?php echo $feetype['type'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('feetype_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="class_id"><?php echo $this->lang->line('class'); ?> <small class="req"> *</small></label>
                                    <select id="class_id" name="class_id[]" class="form-control select2" multiple="multiple">
                                        <?php foreach ($classlist as $class) { ?>
                                            <option value="<?php echo $class['id'] ?>" <?php if ($this->input->post('class_id') && in_array($class['id'], $this->input->post('class_id'))) echo "selected='selected'"; ?>><?php echo $class['class'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id[]'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="amount"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>) <small class="req"> *</small></label>
                                    <input id="amount" name="amount" type="text" class="form-control" value="<?php echo set_value('amount'); ?>" />
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="due_date"><?php echo $this->lang->line('due_date'); ?> <small class="req"> *</small></label>
                                    <input id="due_date" name="due_date" type="text" class="form-control date" value="<?php echo set_value('due_date'); ?>" />
                                    <span class="text-danger"><?php echo form_error('due_date'); ?></span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="input-type"><?php echo $this->lang->line('fine_type'); ?></label>
                                    <div id="input-type" class="row">
                                        <div class="col-sm-4">
                                            <label class="radio-inline">
                                                <input name="account_type" class="finetype" id="input-type-student" value="" type="radio" <?php echo set_radio('account_type', '', true); ?> />None
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="radio-inline">
                                                <input name="account_type" class="finetype" id="input-type-student" value="percentage" type="radio" <?php echo set_radio('account_type', 'percentage'); ?> />Percentage
                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="radio-inline">
                                                <input name="account_type" class="finetype" id="input-type-tutor" value="fix" type="radio" <?php echo set_radio('account_type', 'fix'); ?> />Fix Amount
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="fine_percentage">Percentage (%)</label>
                                        <input id="fine_percentage" name="fine_percentage" type="text" class="form-control" value="<?php echo set_value('fine_percentage'); ?>" readonly />
                                        <span class="text-danger"><?php echo form_error('fine_percentage'); ?></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="fine_amount">Fix Amount (<?php echo $currency_symbol; ?>)</label>
                                        <input id="fine_amount" name="fine_amount" type="text" class="form-control" value="<?php echo set_value('fine_amount'); ?>" readonly />
                                        <span class="text-danger"><?php echo form_error('fine_amount'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="assign_to">Assignment</label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="radio-inline">
                                                <input name="assign_to" value="all" type="radio" checked />Apply to All Students
                                            </label>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="radio-inline">
                                                <input name="assign_to" value="new" type="radio" />Apply to New Students
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>

            <div class="col-md-<?php if ($this->rbac->hasPrivilege('transport_fees_master', 'can_add')) { echo "8"; } else { echo "12"; } ?>">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Yearly Fees List</h3>
                    </div>
                    <div class="box-body">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-6">
                                <label><?php echo $this->lang->line('route_list'); ?></label>
                                <select id="filter_route_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($vehroutelist as $vehroute) { ?>
                                        <optgroup label=" <?php echo $vehroute->route_title; ?>">
                                            <?php foreach ($vehroute->vehicles as $vehicle) { ?>
                                                <option value="<?php echo $vehicle->vec_route_id ?>"><?php echo $vehicle->vehicle_no ?></option>
                                            <?php } ?>
                                        </optgroup>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label><?php echo $this->lang->line('pickup_point'); ?></label>
                                <select id="filter_pickup_point" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                            <table class="table table-striped table-bordered table-hover example" id="yearlyfee_table">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('route_list'); ?></th>
                                        <th><?php echo $this->lang->line('pickup_point'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('fees_type'); ?></th>
                                        <th><?php echo $this->lang->line('due_date'); ?></th>
                                        <th><?php echo $this->lang->line('amount'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    try {
                                        $this->load->view('admin/transport/_yearlyfeelist', array('yearlyfeelist' => $yearlyfeelist, 'currency_symbol' => isset($currency_symbol) ? $currency_symbol : '')); 
                                    } catch (Throwable $e) {
                                        echo "<tr><td colspan='5'>ERROR: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "</td></tr>";
                                    }
                                    ?>
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
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy','M'=>'MM']) ?>';
    
    $(document).ready(function() {
        $('.date').datepicker({
            format: date_format,
            autoclose: true,
            todayHighlight: true
        });
        
        // Re-populate pickup points if route was selected but form failed validation
        var route_id = $('#route_id').val();
        var selected_pickup_point = '<?php echo set_value('route_pickup_point_id'); ?>';
        if(route_id != "") {
            get_pickup_point(route_id, selected_pickup_point);
        }

        $('.select2').select2({
            closeOnSelect: false
        });
    });

    $(document).on('change', '.finetype', function() {
        var radio_value = $(this).val();
        if (radio_value == "percentage") {
            $('#fine_amount').val("").prop('readonly', true);
            $('#fine_percentage').prop('readonly', false);
        } else if (radio_value == "fix") {
            $('#fine_percentage').val("").prop('readonly', true);
            $('#fine_amount').prop('readonly', false);
        } else if (radio_value == "") {
            $('#fine_percentage').val("").prop('readonly', true);
            $('#fine_amount').val("").prop('readonly', true);
        }
    });

    function get_pickup_point(route_id, selected = "") {
        $('#pickup_point').html("");
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/pickuppoint/get_pickupdropdownlist",
            data: {'vehroute_id': route_id},
            dataType: "json",
            success: function(data) {
                $.each(data, function(i, obj) {
                    var sel = "";
                    if (selected == obj.route_pickup_point_id) {
                        sel = "selected";
                    }
                    div_data += "<option value=" + obj.route_pickup_point_id + " " + sel + ">" + obj.name + "</option>";
                });
                $('#pickup_point').append(div_data);
            }
        });
    }

    $(document).on('change', '#filter_route_id', function() {
        var route_id = $(this).val();
        $('#filter_pickup_point').html("");
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        if(route_id != "") {
            $.ajax({
                type: "POST",
                url: base_url + "admin/pickuppoint/get_pickupdropdownlist",
                data: {'vehroute_id': route_id},
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.route_pickup_point_id + ">" + obj.name + "</option>";
                    });
                    $('#filter_pickup_point').append(div_data);
                }
            });
        } else {
            $('#filter_pickup_point').append(div_data);
        }
        load_yearly_fees();
    });

    $(document).on('change', '#filter_pickup_point', function() {
        load_yearly_fees();
    });

    function load_yearly_fees() {
        var route_id = $('#filter_route_id').val();
        var pickup_point_id = $('#filter_pickup_point').val();
        $.ajax({
            type: "POST",
            url: base_url + "admin/transportyearlyfee/search_yearly_fees",
            data: {'route_id': route_id, 'pickup_point_id': pickup_point_id},
            dataType: "json",
            success: function(data) {
                var table = $('.example').DataTable();
                table.clear().destroy();
                $('.example tbody').html(data.page);
                $('.example').DataTable();
            }
        });
    }
</script>
<style>
    .select2-results__option[aria-selected="true"] {
        background-color: #f8f9fa !important;
        color: #333 !important;
        position: relative;
    }
    .select2-results__option[aria-selected="true"]::after {
        content: '\2713';
        position: absolute;
        right: 15px;
        color: #28a745;
        font-weight: bold;
    }
</style>
