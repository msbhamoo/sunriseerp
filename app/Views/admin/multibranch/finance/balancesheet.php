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
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('consolidated_balance_sheet'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('admin/multibranch/finance/balancesheet') ?>" method="post" class="">
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
                        <div class="download_label"><?php echo $this->lang->line('consolidated_balance_sheet'); ?></div>
                        <div class="row">
                            <!-- Liabilities Side -->
                            <div class="col-md-6">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('liabilities'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($results['liabilities'])) {
                                            foreach ($results['liabilities'] as $liab) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $liab['name']; ?></td>
                                                    <td class="text-right"><?php echo amountFormat($liab['amount']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Assets Side -->
                            <div class="col-md-6">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('assets'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($results['assets'])) {
                                            foreach ($results['assets'] as $asset) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $asset['name']; ?></td>
                                                    <td class="text-right"><?php echo amountFormat($asset['amount']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
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
                                            <th class="text-right"><?php echo amountFormat($results['total_liabilities']); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-striped table-bordered">
                                    <tfoot>
                                        <tr>
                                            <th><?php echo $this->lang->line('total'); ?></th>
                                            <th class="text-right"><?php echo amountFormat($results['total_assets']); ?></th>
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
