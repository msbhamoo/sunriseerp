<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th><?php echo $this->lang->line('parameter'); ?></th>
                    <th><?php echo $this->lang->line('maximum_marks'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($resultlist)) { foreach ($resultlist as $row) { ?>
                    <tr>
                        <td><?php echo is_object($row) ? ($row->name ?? '') : ($row['name'] ?? ''); ?></td>
                        <td><?php echo is_object($row) ? ($row->maximum_marks ?? '') : ($row['maximum_marks'] ?? ''); ?></td>
                    </tr>
                <?php } } else { ?>
                    <tr><td colspan="2" class="text-center"><?php echo $this->lang->line('no_record_found'); ?></td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
