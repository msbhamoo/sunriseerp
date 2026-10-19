<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?> 
<div class="content-wrapper">
    <section class="content-header"></section>
    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('financereports/class_summary_report') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                                ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search') ?></button>
                        </div>
                    </form>
                    <div class="row" id="printDiv">
                        <?php if (isset($summary_data)) { ?>
                            <div class="" id="transfee">
                                <div class="box-header ">
                                    <div class="box-header  with-border">
                                        <h3 class="box-title titlefix"> Class Summary Report</h3>
                                    </div>                              
                                </div>                              
                                <div class="box-body">
                                    <?php if (!empty($summary_data)) { ?>
                                        <button type="button" class="btn btn-sm btn-primary mb5 mr0-3 print" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please wait" onclick="printDiv()"><i class="fa fa-print"></i> <?php echo $this->lang->line('print') ?> </button>
                                        <div class="clearfix"></div>
                                        <div class="table-responsive" id="print_area">
                                            <div id="printhead"><center><b><h4>Class Summary Report<br></h4></b></center></div>
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead class="header">
                                                    <tr>                 
                                                        <th class="text text-left">CLASS</th>
                                                        <th class="text text-left">SECTION</th>
                                                        <th class="text text-left">NO. OF STUDENT</th>
                                                        <th class="text text-right">FEES <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                        <th class="text text-right">DEPOSIT <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                        <th class="text text-right">DISCOUNT <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                        <th class="text text-right">BALANCE <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $grouped_data = [];
                                                    foreach ($summary_data as $key => $row) {
                                                        $class_name = $row['class'];
                                                        if (!isset($grouped_data[$class_name])) {
                                                            $grouped_data[$class_name] = [];
                                                        }
                                                        $grouped_data[$class_name][] = $row;
                                                    }
                                                    
                                                    $g_student_count = 0;
                                                    $g_totalfee = 0;
                                                    $g_deposit = 0;
                                                    $g_discount = 0;
                                                    $g_balance = 0;

                                                    foreach ($grouped_data as $class_name => $sections) {
                                                        $rowspan = count($sections);
                                                        foreach ($sections as $index => $row) {
                                                            $g_student_count += $row['student_count'];
                                                            $g_totalfee += $row['totalfee'];
                                                            $g_deposit += $row['deposit'];
                                                            $g_discount += $row['discount'];
                                                            $g_balance += $row['balance'];
                                                            ?>
                                                            <tr>
                                                                <?php if ($index == 0) { ?>
                                                                    <td align="left" rowspan="<?php echo $rowspan; ?>" style="vertical-align: middle; font-weight: bold; background-color: #fff;"><?php echo $class_name; ?></td>
                                                                <?php } ?>
                                                                <td align="left" style="font-weight: bold;"><?php echo $row['section']; ?></td>
                                                                <td align="left"><?php echo $row['student_count']; ?></td>
                                                                <td class="text text-right"><?php echo amountFormat($row['totalfee']); ?></td>
                                                                <td class="text text-right"><?php echo amountFormat($row['deposit']); ?></td>
                                                                <td class="text text-right"><?php echo amountFormat($row['discount']); ?></td>
                                                                <td class="text text-right"><?php echo amountFormat($row['balance']); ?></td>
                                                            </tr>
                                                        <?php }
                                                    } ?>
                                                    
                                                    <tr class="box box-solid total-bg">
                                                        <td colspan="2" align="right" class="text text-right" style="font-weight:bold"><?php echo $this->lang->line('grand_total'); ?></td>
                                                        <td align="left" style="font-weight:bold"><?php echo $g_student_count; ?></td>
                                                        <td class="text text-right" style="font-weight:bold"><?php echo $currency_symbol . amountFormat($g_totalfee); ?></td>
                                                        <td class="text text-right" style="font-weight:bold"><?php echo $currency_symbol . amountFormat($g_deposit); ?></td>
                                                        <td class="text text-right" style="font-weight:bold"><?php echo $currency_symbol . amountFormat($g_discount); ?></td>
                                                        <td class="text text-right" style="font-weight:bold"><?php echo $currency_symbol . amountFormat($g_balance); ?></td> 
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } else { ?>
                                        <div class="alert alert-info">
                                           <?php echo $this->lang->line('no_record_found') ; ?>
                                        </div>
                                    <?php } ?>
                                </div>                            
                            </div>                 
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';
        getSectionByClass(class_id, section_id);
    });

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }
    
    document.getElementById("printhead").style.display = "none";

    function printDiv() {
        document.getElementById("printhead").style.display = "block";
        var divElements = document.getElementById('print_area').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
                "<html><head><title>Class Summary Report</title></head><body>" +
                divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        document.getElementById("printhead").style.display = "none";
        location.reload(true);
    }
</script>
