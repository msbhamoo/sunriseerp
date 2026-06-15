<?php if (empty($logs)) { ?>
    <div class="alert alert-info">No follow-up history found.</div>
<?php } else { ?>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th><?php echo $this->lang->line('date'); ?></th>
                <th><?php echo $this->lang->line('action'); ?></th>
                <th><?php echo $this->lang->line('status'); ?></th>
                <th><?php echo $this->lang->line('remark'); ?></th>
                <th><?php echo $this->lang->line('created_by'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log) { ?>
                <tr>
                    <td><?php echo date($this->customlib->getSchoolDateFormat() . ' H:i', strtotime($log['created_at'])); ?></td>
                    <td><?php echo $log['action']; ?></td>
                    <td><?php echo $log['followup_status']; ?></td>
                    <td><?php echo $log['remark']; ?></td>
                    <td><?php echo $log['name'] . ' ' . $log['surname'] . ' (' . $log['employee_id'] . ')'; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>
