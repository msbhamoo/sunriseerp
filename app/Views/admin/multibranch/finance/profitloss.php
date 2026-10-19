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
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('consolidated_profit_loss'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('admin/multibranch/finance/profitloss') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date_from'); ?></label>
                                    <input type="text" name="from_date" class="form-control date" value="<?php echo set_value('from_date', $search_from_date); ?>" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date_to'); ?></label>
                                    <input type="text" name="to_date" class="form-control date" value="<?php echo set_value('to_date', $search_to_date); ?>" readonly="readonly">
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
                        <div class="download_label"><?php echo $this->lang->line('consolidated_profit_loss'); ?></div>
                        <div class="row">
                            <!-- Expenses Side -->
                            <div class="col-md-6">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('expense'); ?> <?php echo $this->lang->line('ledger'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($results['expenses'])) {
                                            foreach ($results['expenses'] as $exp) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $exp['name']; ?></td>
                                                    <td class="text-right"><?php echo amountFormat($exp['amount']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <?php if ($results['net_profit'] > 0) { ?>
                                        <tr>
                                            <td><strong><?php echo $this->lang->line('net_profit'); ?></strong></td>
                                            <td class="text-right"><strong><?php echo amountFormat($results['net_profit']); ?></strong></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Incomes Side -->
                            <div class="col-md-6">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('income'); ?> <?php echo $this->lang->line('ledger'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($results['incomes'])) {
                                            foreach ($results['incomes'] as $inc) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $inc['name']; ?></td>
                                                    <td class="text-right"><?php echo amountFormat($inc['amount']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <?php if ($results['net_profit'] < 0) { ?>
                                        <tr>
                                            <td><strong><?php echo $this->lang->line('net_loss'); ?></strong></td>
                                            <td class="text-right"><strong><?php echo amountFormat(abs($results['net_profit'])); ?></strong></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6">
                                <table class="table table-striped table-bordered">
                                    <tfoot>
                                        <tr>
                                            <th><?php echo $this->lang->line('total'); ?></th>
                                            <th class="text-right"><?php echo amountFormat($results['total_expense'] + ($results['net_profit'] > 0 ? $results['net_profit'] : 0)); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-striped table-bordered">
                                    <tfoot>
                                        <tr>
                                            <th><?php echo $this->lang->line('total'); ?></th>
                                            <th class="text-right"><?php echo amountFormat($results['total_income'] + ($results['net_profit'] < 0 ? abs($results['net_profit']) : 0)); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
