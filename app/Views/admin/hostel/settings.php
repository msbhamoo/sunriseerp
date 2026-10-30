<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-cogs"></i> <?php echo $this->lang->line('hostel_settings'); ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <li>
                            <a href="#fee_map" class="active" data-toggle="tab"><?php echo $this->lang->line('fee_group_map'); ?></a>
                        </li>
                        <li>
                            <a href="#asset_items" data-toggle="tab"><?php echo $this->lang->line('hostel_room_assets'); ?></a>
                        </li>
                        <li>
                            <a href="#warden_assignment" data-toggle="tab">Warden Assignment</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-10">
                <div class="tab-content">
                    <!-- Fee Map Tab -->
                    <div class="tab-pane active" id="fee_map">
                        <div class="box box-primary">
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><?php echo $this->lang->line('fee_group_map'); ?></h3>
                            </div>
                            <form role="form" id="fee_map_form" action="<?php echo site_url('admin/hostelsettings/save_fee_map'); ?>" method="post">
                                <div class="box-body">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg') ?>
                                    <?php } ?>
                                    
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> Select the Fee Groups that represent Hostel Fees. These will be automatically assigned to a student when they are given a bed.
                                    </div>

                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <tr>
                                                <th width="50">#</th>
                                                <th><?php echo $this->lang->line('name'); ?></th>
                                                <th><?php echo $this->lang->line('description'); ?></th>
                                                <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (empty($fee_groups)) {
                                                ?>
                                                <tr>
                                                    <td colspan="4" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                </tr>
                                                <?php
                                            } else {
                                                $count = 1;
                                                foreach ($fee_groups as $fee_group) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $count++; ?></td>
                                                        <td><?php echo $fee_group['name']; ?></td>
                                                        <td><?php echo $fee_group['description']; ?></td>
                                                        <td class="text-right">
                                                            <div class="material-switch pull-right">
                                                                <input id="fee_group_<?php echo $fee_group['id']; ?>" name="fee_groups[]" type="checkbox" value="<?php echo $fee_group['id']; ?>" <?php echo $fee_group['is_hostel_fee'] ? 'checked' : ''; ?> />
                                                                <label for="fee_group_<?php echo $fee_group['id']; ?>" class="label-info-success"></label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Asset Items Tab -->
                    <div class="tab-pane" id="asset_items">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Add Asset Item</h3>
                                    </div>
                                    <form id="form1" action="<?php echo site_url('admin/hostelsettings/save_asset_item') ?>" method="post">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Item Name</label><small class="req"> *</small>
                                                <input autofocus="" id="item_name" name="item_name" type="text" class="form-control" required/>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="box box-primary">
                                    <div class="box-header ptbnull">
                                        <h3 class="box-title titlefix">Asset Item List</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive mailbox-messages">
                                            <table class="table table-striped table-bordered table-hover example">
                                                <thead>
                                                    <tr>
                                                        <th>Item Name</th>
                                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($asset_items as $item) { ?>
                                                        <tr>
                                                            <td class="mailbox-name"><?php echo $item['item_name'] ?></td>
                                                            <td class="mailbox-date pull-right">
                                                                <a href="<?php echo base_url(); ?>admin/hostelsettings/delete_asset_item/<?php echo $item['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                    <i class="fa fa-remove"></i>
                                                                </a>
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
                    </div>

                    <!-- Warden Assignment Tab -->
                    <div class="tab-pane" id="warden_assignment">
                        <div class="box box-primary">
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix">Hostel Warden Assignment</h3>
                            </div>
                            <form role="form" id="warden_assignment_form" action="<?php echo site_url('admin/hostelsettings/save_warden'); ?>" method="post">
                                <div class="box-body">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> Select a staff member to assign as a Warden for each hostel.
                                    </div>

                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <tr>
                                                <th width="50">#</th>
                                                <th>Hostel Name</th>
                                                <th>Hostel Type</th>
                                                <th>Assign Warden</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (empty($hostellist)) {
                                                ?>
                                                <tr>
                                                    <td colspan="4" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                </tr>
                                                <?php
                                            } else {
                                                $count = 1;
                                                foreach ($hostellist as $hostel) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $count++; ?></td>
                                                        <td><?php echo $hostel['hostel_name']; ?></td>
                                                        <td><?php echo $hostel['type']; ?></td>
                                                        <td>
                                                            <select name="warden_id[<?php echo $hostel['id']; ?>]" class="form-control">
                                                                <option value="">Select Warden</option>
                                                                <?php foreach ($staff_list as $staff) { ?>
                                                                    <option value="<?php echo $staff['id']; ?>" <?php if ($hostel['warden_id'] == $staff['id']) echo "selected"; ?>><?php echo $staff['name'] . ' ' . $staff['surname'] . ' (' . $staff['employee_id'] . ')'; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.tablists a').on('click', function (e) {
        e.preventDefault();
        $('.tablists a').removeClass('active');
        $(this).addClass('active');
        $(this).tab('show');
    });
});
</script>
