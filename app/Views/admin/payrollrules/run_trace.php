<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> Run Trace Details</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Simulation Results (Run #<?php echo $run_id; ?>) - <?php echo $run_detail['month'].' '.$run_detail['year']; ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($run_detail['status'] == 'completed') { ?>
                                <a href="<?php echo site_url('admin/payrollrules/applySimulation/'.$run_id); ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure? This will generate payslips for all staff in this simulation.');"><i class="fa fa-check"></i> <?php echo $this->lang->line('apply_to_payroll'); ?></a>
                            <?php } ?>
                            <a href="<?php echo site_url('admin/payrollrules/runlogs'); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back to Logs</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { echo $this->session->flashdata('msg'); } ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Staff</th>
                                        <th>Role</th>
                                        <th>Basic</th>
                                        <th>Present</th>
                                        <th>Absent/Late</th>
                                        <th>Self Hol.</th>
                                        <th>Earnings</th>
                                        <th>Deductions</th>
                                        <th>Tax (TDS)</th>
                                        <th>Net Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $res) { ?>
                                        <tr>
                                            <td><?php echo $res['staff_name']; ?></td>
                                            <td><?php echo $res['role_name']; ?></td>
                                            <td><?php echo $res['basic_salary']; ?></td>
                                            <td><?php echo $res['present_days']; ?></td>
                                            <td><?php echo $res['absent_days'].' / '.$res['late_days']; ?></td>
                                            <td><?php echo $res['holidays']; ?> (Self: <?php echo max(0, $res['total_days'] - $res['present_days'] - $res['holidays'] - $res['leaves_taken'] - $res['half_days']); ?>)</td>
                                            <td><?php echo $res['total_earnings']; ?></td>
                                            <td><?php echo $res['total_deductions']; ?></td>
                                            <td><?php echo $res['tax_amount']; ?></td>
                                            <td><b><?php echo $res['net_salary']; ?></b></td>
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
