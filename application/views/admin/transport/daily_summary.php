<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> Daily Bus Summary
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Select Date</h3>
                    </div>
                    <form action="<?php echo site_url('admin/transportattendance/daily_summary') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date">Date</label> <small class="req"> *</small>
                                        <input type="text" id="date" name="date" class="form-control date" value="<?php echo set_value('date', $date); ?>" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="col-md-2" style="margin-top:25px;">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm btn-block"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <?php if (isset($summary)) { ?>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i> Daily Bus Summary for <?php echo date($this->customlib->getSchoolDateFormat(), strtotime($this->customlib->dateFormatToYYYYMMDD($date))); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Bus Number</th>
                                        <th>Driver</th>
                                        <th>Attendant</th>
                                        <th class="text-center">Morning Shift (Present/Total)</th>
                                        <th class="text-center">Evening Shift (Present/Total)</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($summary)) { ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No vehicles found.</td>
                                        </tr>
                                    <?php } else {
                                        foreach ($summary as $row) { 
                                            // Format stats for Morning
                                            $morning_present_str = $row['morning_present'] . ' / ' . $row['total_assigned'];
                                            if ($row['morning_custom'] > 0) {
                                                $morning_present_str .= ' <span class="label label-info" title="Custom Riders">+' . $row['morning_custom'] . '</span>';
                                            }
                                            
                                            // Format stats for Evening
                                            $evening_present_str = $row['evening_present'] . ' / ' . $row['total_assigned'];
                                            if ($row['evening_custom'] > 0) {
                                                $evening_present_str .= ' <span class="label label-info" title="Custom Riders">+' . $row['evening_custom'] . '</span>';
                                            }
                                    ?>
                                        <tr>
                                            <td><?php echo $row['vehicle_no']; ?></td>
                                            <td><?php echo $row['driver_name'] ? $row['driver_name'] : '-'; ?></td>
                                            <td><?php echo $row['attendant_name'] ? $row['attendant_name'] : '-'; ?></td>
                                            <td class="text-center"><?php echo $morning_present_str; ?></td>
                                            <td class="text-center"><?php echo $evening_present_str; ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-default btn-xs view_detail" data-vehicle_id="<?php echo $row['vehicle_id']; ?>" data-date="<?php echo $date; ?>" title="View Detail"><i class="fa fa-reorder"></i> View</button>
                                            </td>
                                        </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Attendance Detail</h4>
            </div>
            <div class="modal-body" id="detail_content">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.date').datepicker({
            format: '<?php echo $this->customlib->getSchoolDateFormat(); ?>',
            autoclose: true
        });
        
        $('.view_detail').click(function(){
            var vehicle_id = $(this).data('vehicle_id');
            var date = $(this).data('date');
            
            $('#detail_content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
            $('#detailModal').modal('show');
            
            $.ajax({
                url: '<?php echo site_url("admin/transportattendance/get_summary_detail") ?>',
                type: 'POST',
                data: {
                    vehicle_id: vehicle_id,
                    date: date
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 1) {
                        $('#detail_content').html(res.html);
                    } else {
                        $('#detail_content').html('<div class="alert alert-danger">' + res.msg + '</div>');
                    }
                }
            });
        });
    });
</script>
