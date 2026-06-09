<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calculator"></i> Trial Balance</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Select Criteria</h3>
                    </div>
                    <form role="form" action="<?php echo site_url('accounts/reports/trialbalance') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date'); ?> <?php echo $this->lang->line('to'); ?></label>
                                    <input type="text" autocomplete="off" name="date_to" class="form-control date" value="<?php echo set_value('date_to', date($this->customlib->getSchoolDateFormat())); ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm btn-block"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <?php if (isset($result)) { ?>
                    <div class="box box-info" id="report-card">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-list"></i> Trial Balance as on <?php echo isset($date_to) ? $date_to : ''; ?></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-default btn-xs" style="margin-right: 5px;" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
                                <button type="button" class="btn btn-default btn-xs" onclick="exportToExcel('Trial_Balance_Report')"><i class="fa fa-file-excel-o"></i> Excel</button>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Particulars (Ledger)</th>
                                        <th>Group</th>
                                        <th class="text-right">Debit Balance</th>
                                        <th class="text-right">Credit Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_dr = 0;
                                    $total_cr = 0;
                                    foreach ($result as $row) { 
                                        $dr = $cr = 0;
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
                                            <td class="text-right"><?php echo number_format($dr, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($cr, 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right"><strong>Total</strong></th>
                                        <th class="text-right"><strong><?php echo number_format($total_dr, 2); ?></strong></th>
                                        <th class="text-right"><strong><?php echo number_format($total_cr, 2); ?></strong></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <style>
                    @media print {
                        body * {
                            visibility: hidden;
                        }
                        #report-card, #report-card * {
                            visibility: visible;
                        }
                        #report-card {
                            position: absolute;
                            left: 0;
                            top: 0;
                            width: 100%;
                            border: none !important;
                            box-shadow: none !important;
                        }
                        .box-tools, .btn, .box-header button, .main-footer, .header {
                            display: none !important;
                        }
                        .border-right {
                            border-right: 1px solid #ddd !important;
                        }
                        table {
                            width: 100% !important;
                        }
                    }
                    </style>

                    <script type="text/javascript">
                    function exportToExcel(filename) {
                        var uri = 'data:application/vnd.ms-excel;base64,';
                        var template = '\x3Chtml xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"\x3E\x3Chead\x3E\x3C!--[if gte mso 9]\x3E\x3Cxml\x3E\x3Cx:ExcelWorkbook\x3E\x3Cx:ExcelWorksheets\x3E\x3Cx:ExcelWorksheet\x3E\x3Cx:Name\x3E{worksheet}\x3C/x:Name\x3E\x3Cx:WorksheetOptions\x3E\x3Cx:DisplayGridlines/\x3E\x3C/x:WorksheetOptions\x3E\x3C/x:ExcelWorksheet\x3E\x3C/x:ExcelWorksheets\x3E\x3C/x:ExcelWorkbook\x3E\x3C/xml\x3E\x3C![endif]--\x3E\x3Cmeta charset="UTF-8"\x3E\x3C/head\x3E\x3Cbody\x3E\x3Ctable\x3E{table}\x3C/table\x3E\x3C/body\x3E\x3C/html\x3E';
                        var base64 = function (s) {
                            return window.btoa(unescape(encodeURIComponent(s)));
                        };
                        var format = function (s, c) {
                            return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; });
                        };

                        var tables = document.querySelectorAll('#report-card table');
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
                <?php } ?>
            </div>
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
</script>