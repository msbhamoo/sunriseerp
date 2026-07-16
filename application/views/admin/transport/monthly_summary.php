<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> Monthly Bus Summary
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Select Month & Year</h3>
                    </div>
                    <form action="<?php echo site_url('admin/transportattendance/monthly_summary') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Month</label><small class="req"> *</small>
                                        <select class="form-control" name="month">
                                            <?php
                                            for ($m = 1; $m <= 12; $m++) {
                                                $m_padded = str_pad($m, 2, '0', STR_PAD_LEFT);
                                                $month_name = date('F', mktime(0, 0, 0, $m, 1));
                                                $selected = ($m_padded == $month) ? 'selected' : '';
                                                echo "<option value='{$m_padded}' {$selected}>{$month_name}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Year</label><small class="req"> *</small>
                                        <select class="form-control" name="year">
                                            <?php
                                            $current_year = date('Y');
                                            for ($y = $current_year - 5; $y <= $current_year + 1; $y++) {
                                                $selected = ($y == $year) ? 'selected' : '';
                                                echo "<option value='{$y}' {$selected}>{$y}</option>";
                                            }
                                            ?>
                                        </select>
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
                        <h3 class="box-title"><i class="fa fa-users"></i> Monthly Bus Summary for <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Bus Number</th>
                                        <th>Driver</th>
                                        <th>Attendant</th>
                                        <th class="text-center">Total Present (Month)</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($summary)) { ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No vehicles found.</td>
                                        </tr>
                                    <?php } else {
                                        foreach ($summary as $row) { 
                                            // Format stats for Morning
                                            $morning_present_str = 'Morning: ' . $row['morning_present_month'];
                                            if ($row['morning_custom_month'] > 0) {
                                                $morning_present_str .= ' (+' . $row['morning_custom_month'] . ' Custom)';
                                            }
                                            
                                            // Format stats for Evening
                                            $evening_present_str = 'Evening: ' . $row['evening_present_month'];
                                            if ($row['evening_custom_month'] > 0) {
                                                $evening_present_str .= ' (+' . $row['evening_custom_month'] . ' Custom)';
                                            }
                                    ?>
                                        <tr>
                                            <td><?php echo $row['vehicle_no']; ?></td>
                                            <td><?php echo $row['driver_name'] ? $row['driver_name'] : '-'; ?></td>
                                            <td><?php echo $row['attendant_name'] ? $row['attendant_name'] : '-'; ?></td>
                                            <td class="text-center">
                                                <div><strong>Total: <?php echo $row['total_present_month']; ?></strong></div>
                                                <small class="text-muted"><?php echo $morning_present_str; ?></small><br>
                                                <small class="text-muted"><?php echo $evening_present_str; ?></small>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-default btn-xs view_detail" data-vehicle_id="<?php echo $row['vehicle_id']; ?>" data-month="<?php echo $month; ?>" data-year="<?php echo $year; ?>" title="View Detail"><i class="fa fa-reorder"></i> View</button>
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
                <h4 class="modal-title">Monthly Attendance Detail Breakdown</h4>
            </div>
            <div class="modal-body" id="detail_content">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.view_detail').click(function(){
            var vehicle_id = $(this).data('vehicle_id');
            var month = $(this).data('month');
            var year = $(this).data('year');
            
            $('#detail_content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
            $('#detailModal').modal('show');
            
            $.ajax({
                url: '<?php echo site_url("admin/transportattendance/get_monthly_summary_detail") ?>',
                type: 'POST',
                data: {
                    vehicle_id: vehicle_id,
                    month: month,
                    year: year
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
