<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-money"></i> <?php echo $this->lang->line('finance'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('consolidated_trial_balance'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('admin/multibranch/finance/trialbalance') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date'); ?></label>
                                    <input type="text" name="to_date" class="form-control date" value="<?php echo set_value('to_date', $search_date); ?>" readonly="readonly">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="box-header ptbnull"></div>
                    <div class="box-body table-responsive">
                        <div class="download_label"><?php echo $this->lang->line('consolidated_trial_balance'); ?></div>
                        <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('ledger'); ?></th>
                                    <th><?php echo $this->lang->line('group'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('debit'); ?> (<?php echo $currency_symbol; ?>)</th>
                                    <th class="text-right"><?php echo $this->lang->line('credit'); ?> (<?php echo $currency_symbol; ?>)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_dr = 0;
                                $total_cr = 0;
                                if (!empty($results)) {
                                    foreach ($results as $row) {
                                        $dr = 0;
                                        $cr = 0;
                                        if ($row['closing_balance'] > 0) {
                                            $dr = $row['closing_balance'];
                                            $total_dr += $dr;
                                        } else {
                                            $cr = abs($row['closing_balance']);
                                            $total_cr += $cr;
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['group_name']; ?></td>
                                            <td class="text-right"><?php echo ($dr > 0) ? amountFormat($dr) : ''; ?></td>
                                            <td class="text-right"><?php echo ($cr > 0) ? amountFormat($cr) : ''; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right"><?php echo $this->lang->line('total'); ?></th>
                                    <th class="text-right"><?php echo amountFormat($total_dr); ?></th>
                                    <th class="text-right"><?php echo amountFormat($total_cr); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
