<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calculator"></i> <?php echo $this->lang->line('accounts'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('statement'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('accounts/reports/statement') ?>" method="post">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('ledger_account'); ?></label><small class="req"> *</small>
                                        <select name="ledger_id" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($ledgers as $ledger) { ?>
                                                <option value="<?php echo $ledger['id'] ?>" <?php echo set_select('ledger_id', $ledger['id'], (isset($ledger_id) && $ledger_id == $ledger['id'])); ?>><?php echo $ledger['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date_from'); ?></label><small class="req"> *</small>
                                        <input type="text" name="date_from" class="form-control date" value="<?php echo set_value('date_from', isset($date_from) ? $date_from : date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date_to'); ?></label><small class="req"> *</small>
                                        <input type="text" name="date_to" class="form-control date" value="<?php echo set_value('date_to', isset($date_to) ? $date_to : date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm btn-block"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php if (isset($result)) { ?>
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('statement'); ?></h3>
                        <div class="box-tools pull-right">
                            <form id="export-form" method="post" action="" target="_blank" style="display:inline;">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="ledger_id" value="<?php echo isset($ledger_id) ? $ledger_id : ''; ?>">
                                <input type="hidden" name="date_from" value="<?php echo isset($date_from) ? $date_from : ''; ?>">
                                <input type="hidden" name="date_to" value="<?php echo isset($date_to) ? $date_to : ''; ?>">
                                <button type="button" class="btn btn-default btn-xs" style="margin-right: 5px;" onclick="submitExport('print')"><i class="fa fa-print"></i> Print</button>
                                <button type="button" class="btn btn-default btn-xs" onclick="exportToExcel('Statement_Report')"><i class="fa fa-file-excel-o"></i> Excel</button>
                            </form>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('date'); ?></th>
                                    <th><?php echo $this->lang->line('vch_no'); ?></th>
                                    <th><?php echo $this->lang->line('vch_type'); ?></th>
                                    <th><?php echo $this->lang->line('particulars'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('debit'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('credit'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('balance'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
                                $balance = $result['opening_balance'];
                                ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b><?php echo $this->lang->line('opening_balance'); ?></b></td>
                                    <td class="text-right"><?php echo ($balance > 0) ? $currency_symbol . amountFormat(abs($balance)) : ''; ?></td>
                                    <td class="text-right"><?php echo ($balance < 0) ? $currency_symbol . amountFormat(abs($balance)) : ''; ?></td>
                                    <td class="text-right"><?php echo $currency_symbol . amountFormat(abs($balance)) . ' ' . ($balance >= 0 ? 'Dr' : 'Cr'); ?></td>
                                </tr>
                                <?php
                                $total_dr = ($balance > 0) ? $balance : 0;
                                $total_cr = ($balance < 0) ? abs($balance) : 0;
                                
                                if (!empty($result['transactions'])) {
                                    foreach ($result['transactions'] as $row) {
                                        $dr = $row['debit_amount'];
                                        $cr = $row['credit_amount'];
                                        $balance += ($dr - $cr);
                                        $total_dr += $dr;
                                        $total_cr += $cr;
                                        ?>
                                        <tr>
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($row['voucher_date'])); ?></td>
                                            <td><?php echo $row['voucher_no']; ?></td>
                                            <td><?php echo ucfirst($row['voucher_type']); ?></td>
                                            <td><b><?php echo $row['opposite_ledger_name'] ? $row['opposite_ledger_name'] : 'System'; ?></b><br><small><?php echo !empty($row['narration']) ? $row['narration'] : (isset($row['voucher_narration']) ? $row['voucher_narration'] : ''); ?></small></td>
                                            <td class="text-right"><?php echo ($dr > 0) ? $currency_symbol . amountFormat($dr) : ''; ?></td>
                                            <td class="text-right"><?php echo ($cr > 0) ? $currency_symbol . amountFormat($cr) : ''; ?></td>
                                            <td class="text-right"><?php echo $currency_symbol . amountFormat(abs($balance)) . ' ' . ($balance >= 0 ? 'Dr' : 'Cr'); ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right"><?php echo $this->lang->line('closing_balance'); ?>:</th>
                                    <th class="text-right"><?php echo $currency_symbol . amountFormat($total_dr); ?></th>
                                    <th class="text-right"><?php echo $currency_symbol . amountFormat($total_cr); ?></th>
                                    <th class="text-right"><?php echo $currency_symbol . amountFormat(abs($balance)) . ' ' . ($balance >= 0 ? 'Dr' : 'Cr'); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>
</div>

<style>
.acc-status-posted {
    background-color: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;
}
.acc-status-reversed {
    background-color: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;
}
.acc-status-draft {
    background-color: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;
}
</style>
<script>
$(document).ready(function() {
    $('.select2').select2();
    
    setTimeout(function() {
        $('table th').each(function(index) {
            var text = $(this).text().toLowerCase();
            if (text.indexOf('amount') !== -1 || text.indexOf('debit') !== -1 || text.indexOf('credit') !== -1 || text.indexOf('balance') !== -1 || text.indexOf('total') !== -1) {
                $(this).css('text-align', 'right');
                $(this).closest('table').find('tr').each(function() {
                    var $td = $(this).children('td').eq(index);
                    if ($td.length && $td.text().match(/[0-9]/)) {
                        $td.css({
                            'text-align': 'right',
                            'font-family': '"Consolas", "Courier New", monospace',
                            'font-weight': '600'
                        });
                    }
                });
            }
        });

        $('table td').each(function() {
            var txt = $.trim($(this).text()).toLowerCase();
            if (txt === 'posted') {
                $(this).html('<span class="acc-status-posted">Posted</span>');
            } else if (txt === 'reversed') {
                $(this).html('<span class="acc-status-reversed">Reversed</span>');
            } else if (txt === 'draft') {
                $(this).html('<span class="acc-status-draft">Draft</span>');
            }
        });
    }, 1000); // 1s delay to let datatables render
    
    // Also attach to DataTables draw event if available
    $(document).on('draw.dt', function () {
        $('table td').each(function() {
            var txt = $.trim($(this).text()).toLowerCase();
            if (txt === 'posted') {
                $(this).html('<span class="acc-status-posted">Posted</span>');
            } else if (txt === 'reversed') {
                $(this).html('<span class="acc-status-reversed">Reversed</span>');
            } else if (txt === 'draft') {
                $(this).html('<span class="acc-status-draft">Draft</span>');
            }
        });
        
        $('table th').each(function(index) {
            var text = $(this).text().toLowerCase();
            if (text.indexOf('amount') !== -1 || text.indexOf('debit') !== -1 || text.indexOf('credit') !== -1 || text.indexOf('balance') !== -1 || text.indexOf('total') !== -1) {
                $(this).css('text-align', 'right');
                $(this).closest('table').find('tr').each(function() {
                    var $td = $(this).children('td').eq(index);
                    if ($td.length && $td.text().match(/[0-9]/)) {
                        $td.css({
                            'text-align': 'right',
                            'font-family': '"Consolas", "Courier New", monospace',
                            'font-weight': '600'
                        });
                    }
                });
            }
        });
    });
});

function submitExport(type) {
    var form = document.getElementById('export-form');
    form.action = '<?php echo site_url('accounts/reports/export_statement/') ?>' + type;
    form.submit();
}

function exportToExcel(filename) {
    var uri = 'data:application/vnd.ms-excel;base64,';
    var template = '\x3Chtml xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"\x3E\x3Chead\x3E\x3C!--[if gte mso 9]\x3E\x3Cxml\x3E\x3Cx:ExcelWorkbook\x3E\x3Cx:ExcelWorksheets\x3E\x3Cx:ExcelWorksheet\x3E\x3Cx:Name\x3E{worksheet}\x3C/x:Name\x3E\x3Cx:WorksheetOptions\x3E\x3Cx:DisplayGridlines/\x3E\x3C/x:WorksheetOptions\x3E\x3C/x:ExcelWorksheet\x3E\x3C/x:ExcelWorksheets\x3E\x3C/x:ExcelWorkbook\x3E\x3C/xml\x3E\x3C![endif]--\x3E\x3Cmeta charset="UTF-8"\x3E\x3C/head\x3E\x3Cbody\x3E\x3Ctable\x3E{table}\x3C/table\x3E\x3C/body\x3E\x3C/html\x3E';
    var base64 = function (s) {
        return window.btoa(unescape(encodeURIComponent(s)));
    };
    var format = function (s, c) {
        return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; });
    };

    var tables = document.querySelectorAll('.box-body.table-responsive table');
    var combinedHtml = "";
    tables.forEach(function(table) {
        combinedHtml += table.outerHTML + "\x3Cbr\x3E\x3Cbr\x3E";
    });

    var ctx = {
        worksheet: 'Worksheet',
        table: combinedHtml
    };

    var link = document.createElement("a");
    link.download = filename + ".xls";
    link.href = uri + base64(format(template, ctx));
    link.click();
}
</script>