<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('simulate_payroll'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Run Simulation</h3>
                    </div>
                    <form action="<?php echo site_url('admin/payrollrules/runsimulation'); ?>" method="post">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { echo $this->session->flashdata('msg'); } ?>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('month'); ?> <small class="req"> *</small></label>
                                <select name="month" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php
                                    $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                                    foreach ($months as $m) {
                                        echo "<option value='$m'>$m</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('year'); ?> <small class="req"> *</small></label>
                                <select name="year" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php
                                    $current = date('Y');
                                    for ($i = $current - 1; $i <= $current + 1; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('role'); ?></label>
                                <select name="role_id" class="form-control">
                                    <option value="">All Roles</option>
                                    <?php foreach ($roles as $role) { ?>
                                        <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-play"></i> Run Simulation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
