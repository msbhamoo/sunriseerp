<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> Run Logs</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Engine Run Logs</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Run ID</th>
                                        <th>Type</th>
                                        <th>Month / Year</th>
                                        <th>Target Role</th>
                                        <th>Total Staff</th>
                                        <th>Processed</th>
                                        <th>Status</th>
                                        <th>Run By</th>
                                        <th>Time</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($runs as $run) { ?>
                                        <tr>
                                            <td>#<?php echo $run['id']; ?></td>
                                            <td><span class="label label-<?php echo $run['run_type'] == 'simulation' ? 'info' : 'success'; ?>"><?php echo strtoupper($run['run_type']); ?></span></td>
                                            <td><?php echo $run['month'].' '.$run['year']; ?></td>
                                            <td><?php echo $run['role_filter']; ?></td>
                                            <td><?php echo $run['total_staff']; ?></td>
                                            <td><?php echo $run['total_processed']; ?></td>
                                            <td><span class="label label-<?php echo $run['status'] == 'completed' ? 'success' : 'danger'; ?>"><?php echo strtoupper($run['status']); ?></span></td>
                                            <td><?php echo $run['staff_name'].' '.$run['staff_surname']; ?></td>
                                            <td><?php echo date('d-m-Y h:i A', strtotime($run['created_at'])); ?></td>
                                            <td class="text-right">
                                                <a href="<?php echo site_url('admin/payrollrules/runtrace/'.$run['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="View Results"><i class="fa fa-eye"></i></a>
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
