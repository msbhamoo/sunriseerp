<?php $this->load->view('layout/cbseexam_css.php'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-users"></i> Seating Allocations</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Allocations List</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('cbse_exam_seating', 'can_add')) { ?>
                                <a href="<?php echo site_url('cbseexam/seatingarrangement/create') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Create New Allocation</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
                            echo $this->session->flashdata('msg');
                        } ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped example">
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <th>Date</th>
                                        <th>Strategy</th>
                                        <th>Rooms Used</th>
                                        <th>Students Allocated</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allocations as $alloc) { ?>
                                        <tr>
                                            <td><?php echo $alloc['exam_name']; ?></td>
                                            <td><?php echo $this->customlib->dateformat($alloc['exam_date']); ?></td>
                                            <td><?php echo ucfirst($alloc['allocation_strategy']); ?></td>
                                            <td><?php echo $alloc['total_rooms_used']; ?></td>
                                            <td><?php echo $alloc['total_students_allocated']; ?></td>
                                            <td>
                                                <?php if($alloc['status'] == 'draft') { ?>
                                                    <span class="label label-warning">Draft</span>
                                                <?php } else { ?>
                                                    <span class="label label-success"><?php echo ucfirst($alloc['status']); ?></span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-right">
                                                <?php if ($this->rbac->hasPrivilege('cbse_exam_seating', 'can_edit')) { ?>
                                                    <?php if($alloc['status'] == 'draft') { ?>
                                                        <a href="<?php echo site_url('cbseexam/seatingarrangement/change_status/'.$alloc['id'].'/finalized'); ?>" class="btn btn-default btn-xs" onclick="return confirm('Mark as finalized?');" data-toggle="tooltip" title="Mark Finalized"><i class="fa fa-check"></i></a>
                                                    <?php } else if($alloc['status'] == 'finalized') { ?>
                                                        <a href="<?php echo site_url('cbseexam/seatingarrangement/change_status/'.$alloc['id'].'/locked'); ?>" class="btn btn-default btn-xs" onclick="return confirm('Lock this allocation?');" data-toggle="tooltip" title="Lock"><i class="fa fa-lock"></i></a>
                                                        <a href="<?php echo site_url('cbseexam/seatingarrangement/change_status/'.$alloc['id'].'/draft'); ?>" class="btn btn-default btn-xs" onclick="return confirm('Revert to draft?');" data-toggle="tooltip" title="Revert to Draft"><i class="fa fa-undo"></i></a>
                                                    <?php } ?>
                                                    <a href="<?php echo site_url('cbseexam/seatingarrangement/assign_invigilators/'.$alloc['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Assign Invigilators"><i class="fa fa-user-plus"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('cbse_exam_seating', 'can_delete')) { ?>
                                                    <a href="<?php echo site_url('cbseexam/seatingarrangement/delete/'.$alloc['id']); ?>" class="btn btn-default btn-xs" onclick="return confirm('Are you sure you want to delete this allocation? This will delete all associated seat assignments and invigilator duties.');" data-toggle="tooltip" title="Delete"><i class="fa fa-remove"></i></a>
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
<script>
    $(document).ready(function() {
        $('.example').DataTable({
            "aaSorting": [],
            "bSort": true,
            "bPaginate": true,
            "bInfo": true,
            "bFilter": true
        });
    });
</script>
