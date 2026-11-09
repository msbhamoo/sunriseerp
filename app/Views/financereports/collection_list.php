<?php $search_type = $search_type ?? ''; ?>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$period_label    = (isset($searchlist[$search_type]) && $search_type !== '') ? $searchlist[$search_type] : '';
$quick_periods   = array('this_session', 'today', 'this_week', 'this_month', 'this_year');

// Payment mode -> icon + tone, for the mode chips
if (!function_exists('fc_mode_meta')) {
    function fc_mode_meta($mode)
    {
        $m = strtolower(trim((string) $mode));
        if ($m === 'cash') return array('fa-money', 'fc-tone-green');
        if ($m === 'upi') return array('fa-mobile', 'fc-tone-violet');
        if (strpos($m, 'cheque') !== false || $m === 'dd') return array('fa-file-text-o', 'fc-tone-amber');
        if (strpos($m, 'card') !== false) return array('fa-credit-card', 'fc-tone-blue');
        if (strpos($m, 'bank') !== false || strpos($m, 'transfer') !== false || strpos($m, 'neft') !== false) return array('fa-university', 'fc-tone-sky');
        if (strpos($m, 'online') !== false) return array('fa-globe', 'fc-tone-sky');
        return array('fa-exchange', 'fc-tone-slate');
    }
}
?>

<div class="content-wrapper fc-page">
    <section class="content-header fc-header">
        <div class="fc-header-text">
            <h1><span class="fc-header-icon"><i class="fa fa-money"></i></span> <?php echo $this->lang->line('fees_collection'); ?></h1>
            <p class="fc-subtitle">Fee Collection Report<?php if ($period_label !== '') { ?> &middot; <strong><?php echo $period_label; ?></strong><?php } ?></p>
        </div>
    </section>

    <section class="content">
        <!-- Filters -->
        <div class="box fc-card fc-filter-card" style="overflow: visible; z-index: 50;">
            <div class="box-body" style="overflow: visible;">
                <form role="form" action="<?php echo site_url('financereports/collection_list') ?>" method="post" class="" id="fc_filter_form">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <!-- Hidden Search Duration field -->
                    <select class="form-control" name="search_type" id="search_type_hidden" style="display:none;">
                        <?php foreach ($searchlist as $key => $search) { ?>
                            <option value="<?php echo $key ?>" <?php
                            if ((isset($search_type)) && ($search_type == $key)) {
                                echo "selected";
                            }
                            ?>><?php echo $search ?></option>
                        <?php } ?>
                    </select>
                    <div id='date_result' style="display:none;"></div>

                    <div class="fc-filter-row">
                        <div class="fc-filter-period">
                            <label class="fc-label"><i class="fa fa-calendar"></i> <?php echo $this->lang->line('search_duration'); ?></label>
                            <div class="fc-chips" role="group" aria-label="Quick periods">
                                <?php foreach ($quick_periods as $qp) { if (!isset($searchlist[$qp])) continue; ?>
                                    <button type="button" class="fc-chip<?php echo ($search_type == $qp) ? ' active' : ''; ?>" data-period="<?php echo $qp; ?>"><?php echo $searchlist[$qp]; ?></button>
                                <?php } ?>
                                <select class="form-control fc-select<?php echo (!in_array($search_type, $quick_periods) && $search_type !== '') ? ' active' : ''; ?>" id="search_type_select" onchange="fcSubmitPeriod(this.value);" aria-label="More periods">
                                    <?php foreach ($searchlist as $key => $search) { ?>
                                        <option value="<?php echo $key ?>" <?php if ((isset($search_type)) && ($search_type == $key)) { echo "selected"; } ?>><?php echo ($key === '') ? 'More periods…' : $search; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="fc-filter-search search-student-wrapper">
                            <label class="fc-label"><i class="fa fa-user"></i> Quick Search Student</label>
                            <div class="fc-input-icon">
                                <i class="fa fa-search"></i>
                                <input type="text" name="search_student" id="search_student_ajax" class="form-control" value="<?php echo set_value('search_student', isset($search_student) ? $search_student : ''); ?>" placeholder="Name, roll no or scholar no — opens fee collection" autocomplete="off">
                                <div id="ajax_student_search_results_container" class="custom-ajax-search-container"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (empty($results)) { ?>
            <div class="box fc-card">
                <div class="box-body fc-empty">
                    <div class="fc-empty-icon"><i class="fa fa-inbox"></i></div>
                    <h4><?php echo $this->lang->line('no_record_found'); ?></h4>
                    <p>No fee collections in this period. Try a wider range such as <strong><?php echo isset($searchlist['this_year']) ? $searchlist['this_year'] : 'This Year'; ?></strong>.</p>
                </div>
            </div>
        <?php } else {
            $total_records = count($results);
            $total_collected_val = 0;
            $reverted_count = 0;
            $fee_receipts_count = 0;
            $transport_receipts_count = 0;
            $unique_students = array();

            foreach ($results as $res_item) {
                if (!empty($res_item['student_id'])) {
                    $unique_students[$res_item['student_id']] = true;
                } elseif (!empty($res_item['admission_no'])) {
                    $unique_students[$res_item['admission_no']] = true;
                }

                if ($res_item['custom_receipt_status'] != 'Reversed') {
                    $total_collected_val += (two_digit_float($res_item['amount']) + two_digit_float($res_item['amount_fine']));
                } else {
                    $reverted_count++;
                }

                $type_lower = strtolower($res_item['type'] ?? '');
                if (strpos($type_lower, 'transport') !== false || strpos($type_lower, 'bus') !== false) {
                    $transport_receipts_count++;
                } else {
                    $fee_receipts_count++;
                }
            }
            $total_unique_students = count($unique_students);
        ?>
            <!-- KPI summary -->
            <div class="fc-kpis">
                <div class="fc-kpi fc-kpi-hero">
                    <div class="fc-kpi-icon fc-tone-green"><span class="fc-currency"><?php echo $currency_symbol; ?></span></div>
                    <div>
                        <div class="fc-kpi-label">Total Amount Collected</div>
                        <div class="fc-kpi-value"><?php echo $currency_symbol . " " . number_format($total_collected_val, 2); ?></div>
                        <div class="fc-kpi-hint">Excludes reverted receipts</div>
                    </div>
                </div>
                <div class="fc-kpi">
                    <div class="fc-kpi-icon fc-tone-indigo"><i class="fa fa-file-text-o"></i></div>
                    <div>
                        <div class="fc-kpi-label">Fee Receipts</div>
                        <div class="fc-kpi-value"><?php echo $fee_receipts_count; ?></div>
                    </div>
                </div>
                <div class="fc-kpi">
                    <div class="fc-kpi-icon fc-tone-amber"><i class="fa fa-bus"></i></div>
                    <div>
                        <div class="fc-kpi-label">Transport Receipts</div>
                        <div class="fc-kpi-value"><?php echo $transport_receipts_count; ?></div>
                    </div>
                </div>
                <div class="fc-kpi">
                    <div class="fc-kpi-icon fc-tone-sky"><i class="fa fa-graduation-cap"></i></div>
                    <div>
                        <div class="fc-kpi-label">Unique Students</div>
                        <div class="fc-kpi-value"><?php echo $total_unique_students; ?></div>
                    </div>
                </div>
                <div class="fc-kpi">
                    <div class="fc-kpi-icon fc-tone-red"><i class="fa fa-undo"></i></div>
                    <div>
                        <div class="fc-kpi-label">Reverted</div>
                        <div class="fc-kpi-value"><?php echo $reverted_count; ?></div>
                    </div>
                </div>
            </div>

            <div class="box fc-card">
                <div class="fc-toolbar">
                    <div class="fc-toolbar-title">
                        <i class="fa fa-list-ul"></i> Fee Collection List
                        <span class="fc-count" id="fc_row_count"><?php echo $total_records; ?> records</span>
                    </div>
                    <div class="fc-toolbar-actions">
                        <div class="fc-input-icon fc-table-search">
                            <i class="fa fa-filter"></i>
                            <input type="text" id="table_filter" class="form-control" placeholder="Filter receipt, name, class, mode…" autocomplete="off">
                        </div>
                        <a class="btn fc-btn" id="btnExport" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('download_excel'); ?>" onclick="fnExcelReport();"><i class="fa fa-file-excel-o"></i> CSV</a>
                        <a class="btn fc-btn" id="print" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('print'); ?>" onclick="printDiv()"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>

                            <div class="box-body table-responsive fc-table-wrap" id="transfee">
                                <div id="printhead"><center><b><h4>Fee Collection List<br><?php $this->customlib->get_postmessage(); ?></h4></b></center></div>
                                <div class="download_label">Fee Collection List<br><?php $this->customlib->get_postmessage(); ?></div>

                                <table class="table table-hover example fc-table" style="border: none;" id="headerTable">
                                    <thead class="header">
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Receipt No.</th>
                                            <th>Admission No.</th>
                                            <th>Deposit Date</th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th>Father Name</th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th>Fees Type</th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th><?php echo $this->lang->line('mode'); ?></th>
                                            <th>Transaction Details</th>
                                            <th>Status</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                        $count = 1;
                                        $grdTotalLabel = array();
                                        $date_format = $this->customlib->getSchoolDateFormat();

                                        foreach ($results as $collect) {
                                            $t1 = two_digit_float($collect['amount']) + two_digit_float($collect['amount_fine']);
                                            $grdTotalLabel[] = $t1;
                                            $is_reversed = ($collect['custom_receipt_status'] == 'Reversed');
                                            $type_lower  = strtolower($collect['type'] ?? '');
                                            $is_transport = (strpos($type_lower, 'transport') !== false || strpos($type_lower, 'bus') !== false);
                                            $mode_meta   = fc_mode_meta($collect['payment_mode']);
                                        ?>
                                            <tr class="<?php echo $is_reversed ? 'fc-row-reversed' : ''; ?>">
                                                <td class="fc-muted"><?php echo $count++; ?></td>
                                                <td><span class="fc-receipt"><?php echo $collect['custom_receipt_no']; ?></span></td>
                                                <td class="fc-mono"><?php echo $collect['admission_no']; ?></td>
                                                <td class="fc-nowrap"><?php echo date($date_format, $this->customlib->dateyyyymmddTodateformat($collect['date'])); ?></td>
                                                <td><span class="fc-name"><?php echo $this->customlib->getFullName($collect['firstname'], $collect['middlename'], $collect['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></span></td>
                                                <td class="fc-muted"><?php echo isset($collect['father_name']) ? $collect['father_name'] : ''; ?></td>
                                                <td><span class="fc-class"><?php echo $collect['class'] . " (" . $collect['section'] . ")"; ?></span></td>
                                                <td>
                                                    <span class="fc-type"><i class="fa <?php echo $is_transport ? 'fa-bus fc-ic-amber' : 'fa-tag fc-ic-indigo'; ?>"></i>
                                                    <?php
                                                        if ( $collect['is_system']) {
                                                            echo $this->lang->line($collect['type']);
                                                        } else {
                                                            echo $collect['type'] ;
                                                            echo " (".$collect['code'].")";
                                                        }
                                                    ?>
                                                    </span>
                                                </td>
                                                <td class="text text-right fc-amount">
                                                    <?php echo two_digit_float($t1); ?>
                                                </td>
                                                <td><span class="fc-pill <?php echo $mode_meta[1]; ?>"><i class="fa <?php echo $mode_meta[0]; ?>"></i> <?php echo $this->lang->line(strtolower($collect['payment_mode'])); ?></span></td>
                                                <td class="fc-tx">
                                                    <?php
                                                        $tx_details = [];
                                                        if (!empty($collect['reference_no'])) {
                                                            $tx_details[] = "<strong>Ref:</strong> " . $collect['reference_no'];
                                                        }
                                                        if (!empty($collect['description'])) {
                                                            $tx_details[] = "<strong>Desc:</strong> " . $collect['description'];
                                                        }
                                                        echo empty($tx_details) ? '<span class="fc-muted">—</span>' : implode("<br>", $tx_details);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if($is_reversed) { ?>
                                                        <span class="fc-pill fc-tone-red"><i class="fa fa-undo"></i> Reverted</span>
                                                    <?php } else { ?>
                                                        <span class="fc-pill fc-tone-green"><i class="fa fa-check-circle"></i> Received</span>
                                                    <?php } ?>
                                                    <?php if (!empty($acc_sync_enabled) && isset($collect['acc_sync'])) {
                                                        $as = $collect['acc_sync'];
                                                        if ($as['state'] === 'synced' && $is_reversed) { ?>
                                                            <div><span style="display:inline-block; margin-top:3px; font-size:10px; font-weight:700; padding:1px 6px; border-radius:4px; background:#fee2e2; color:#b91c1c;" title="This receipt is reverted but voucher <?php echo html_escape($as['voucher_no']); ?> is still posted in Accounts"><i class="fa fa-exclamation-triangle"></i> Voucher not reversed</span></div>
                                                        <?php } elseif ($as['state'] === 'synced') { ?>
                                                            <div><span style="display:inline-block; margin-top:3px; font-size:10px; font-weight:700; padding:1px 6px; border-radius:4px; background:#dcfce7; color:#15803d;" title="Posted in Accounts as <?php echo html_escape($as['voucher_no']); ?>"><i class="fa fa-link"></i> Synced</span></div>
                                                        <?php } elseif ($as['state'] === 'pending' && !$is_reversed) { ?>
                                                            <div><span style="display:inline-block; margin-top:3px; font-size:10px; font-weight:700; padding:1px 6px; border-radius:4px; background:#fef3c7; color:#b45309;" title="No posted voucher found in Accounts for this invoice"><i class="fa fa-chain-broken"></i> Not synced</span></div>
                                                        <?php } elseif ($as['state'] === 'reversed') { ?>
                                                            <div><span style="display:inline-block; margin-top:3px; font-size:10px; font-weight:700; padding:1px 6px; border-radius:4px; background:#f1f5f9; color:#475569;" title="Voucher <?php echo html_escape($as['voucher_no']); ?> was reversed in Accounts"><i class="fa fa-undo"></i> Reversed</span></div>
                                                        <?php }
                                                    } ?>
                                                </td>
                                                <td class="text-right white-space-nowrap">
                                                    <?php if($collect['custom_receipt_status'] == 'Collected' || empty($collect['custom_receipt_status']) || $collect['custom_receipt_status'] != 'Reversed') { ?>
                                                        <a href="javascript:void(0);" class="fc-icon-btn" onclick="printReceipt('<?php echo $collect['id']; ?>', '<?php echo $collect['inv_no']; ?>')" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>" aria-label="<?php echo $this->lang->line('print'); ?>">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                        <tr class="fc-total-row">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="fc-total-label"><?php echo $this->lang->line('grand_total'); ?></td>
                                            <td class="text text-right fc-total-value"><?php echo $currency_symbol.amountFormat(array_sum($grdTotalLabel)); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="fc-no-match" id="fc_no_match" style="display:none;"><i class="fa fa-search"></i> No rows match your filter.</div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
<?php
if ($search_type == 'period') {
    ?>
        $(document).ready(function () {
            showdate('period');
        });
    <?php
}
?>

function fcSubmitPeriod(value) {
    if (value === '') return;
    $('#search_type_hidden').val(value);
    $('#search_type_select').val(value);
    $('.fc-page').addClass('fc-loading');
    $('#fc_filter_form').submit();
}

// CSV export of the visible rows (the page referenced fnExcelReport but it was never defined)
if (typeof fnExcelReport !== 'function') {
    window.fnExcelReport = function () {
        var rows = [];
        $('#headerTable tr:visible').each(function () {
            var cells = [];
            $(this).children('th,td').each(function (i, el) {
                if ($(el).is(':last-child')) return; // skip Action column
                var text = $(el).text().replace(/\s+/g, ' ').trim();
                cells.push('"' + text.replace(/"/g, '""') + '"');
            });
            rows.push(cells.join(','));
        });
        var bom = String.fromCharCode(0xFEFF); // lets Excel detect UTF-8
        var blob = new Blob([bom + rows.join(String.fromCharCode(13, 10))], {type: 'text/csv;charset=utf-8;'});
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'fee_collection_list.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };
}

function printReceipt(invoice_id, sub_invoice_id) {
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'studentfee/printFeesReceiptPopup',
        type: 'post',
        dataType: 'json',
        data: {'student_fees_deposite_id': invoice_id, 'sub_invoice_id': sub_invoice_id},
        success: function (response) {
            if(response.status == 1) {
                $('#receiptPreviewModalBody').html(response.page);
                $('#receiptPreviewModal').modal('show');
                
                // Set default to show both copies
                $('.receipt-copy.office-copy').show();
                $('.receipt-copy.receiver-copy').show();
                
                // Auto-print both copies by default after a short delay
                setTimeout(function() {
                    printReceiptCopies();
                }, 500);
            }
        }
    });
}

function printReceiptCopies(mode = 'both') {
    if(mode === 'office') {
        $('.receipt-copy.office-copy').show();
        $('.receipt-copy.receiver-copy').hide();
    } else if(mode === 'receiver') {
        $('.receipt-copy.office-copy').hide();
        $('.receipt-copy.receiver-copy').show();
    } else {
        $('.receipt-copy.office-copy').show();
        $('.receipt-copy.receiver-copy').show();
    }
    
    var printContent = document.getElementById('receiptPreviewModalBody').innerHTML;
    Popup(printContent);
    
    // Restore visibility in modal just in case
    $('.receipt-copy.office-copy').show();
    $('.receipt-copy.receiver-copy').show();
}
function Popup(data)
{
    var frame1 = $('<iframe />');
    frame1[0].name = "frame1";
    frame1.css({"position": "absolute", "top": "-1000000px"});
    $("body").append(frame1);
    var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
    frameDoc.document.open();
    //Create a new HTML document.
    frameDoc.document.write('<html>');
    frameDoc.document.write('<head>');
    frameDoc.document.write('<title></title>');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
    frameDoc.document.write('</head>');
    frameDoc.document.write('<body>');
    frameDoc.document.write(data);
    frameDoc.document.write('</body>');
    frameDoc.document.write('</html>');
    frameDoc.document.close();
    setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        frame1.remove();
    }, 500);
    return true;
}

if (document.getElementById("print")) {
    document.getElementById("print").style.display = "block";
}
if (document.getElementById("btnExport")) {
    document.getElementById("btnExport").style.display = "block";
}
if (document.getElementById("printhead")) {
    document.getElementById("printhead").style.display = "none";
}

function printDiv() {
    document.getElementById("print").style.display = "none";
    document.getElementById("btnExport").style.display = "none";
    document.getElementById("printhead").style.display = "block";
    var divElements = document.getElementById('transfee').innerHTML;
    var oldPage = document.body.innerHTML;
    document.body.innerHTML =
            "<html><head><title>Fee Collection List</title></head><body>" +
            divElements + "</body>";
    window.print();
    document.body.innerHTML = oldPage;
    document.getElementById("printhead").style.display = "none";
    location.reload(true);
}

$(document).ready(function() {
    let searchTimeoutAjax;
    const searchInputAjax = $('#search_student_ajax');
    const resultsContainerAjax = $('#ajax_student_search_results_container');

    searchInputAjax.on('keyup', function() {
        clearTimeout(searchTimeoutAjax);
        const query = $(this).val().trim();

        if (query.length >= 2) {
            searchTimeoutAjax = setTimeout(function() {
                var postData = {
                    search_text: query
                };
                
                var csrfInput = $('form input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]');
                if (csrfInput.length > 0) {
                    var csrfName = csrfInput.attr('name');
                    var csrfHash = csrfInput.val();
                    if (csrfName && csrfHash) {
                        postData[csrfName] = csrfHash;
                    }
                }

                $.ajax({
                    url: baseurl + 'admin/admin/ajax_search',
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function(response) {
                        resultsContainerAjax.empty().show();
                        if (response.status === 'success' && response.data && response.data.length > 0) {
                            response.data.forEach(function(student) {
                                const fatherName = student.father_name ? student.father_name : 'N/A';
                                const motherName = student.mother_name ? student.mother_name : 'N/A';
                                const className = student.class ? student.class : '';
                                const sectionName = student.section ? student.section : '';
                                const admNo = student.admission_no ? student.admission_no : '';

                                const html = `
                                    <a href="${baseurl}studentfee/addfee/${student.student_session_id}" class="custom-ajax-search-item">
                                        <img src="${student.image}" alt="Student" class="custom-ajax-search-avatar">
                                        <div class="custom-ajax-search-details">
                                            <div class="custom-ajax-search-col">
                                                <span class="custom-ajax-search-name">${student.full_name}</span>
                                                <span class="custom-ajax-search-meta">${className} - ${sectionName} &nbsp;&nbsp; ${admNo}</span>
                                            </div>
                                            <div class="custom-ajax-search-parents">
                            <span style="display:flex; align-items:center; margin-bottom:4px;"><span class="parent-badge badge-father">F</span> ${fatherName}</span>
                            <span style="display:flex; align-items:center;"><span class="parent-badge badge-mother">M</span> ${motherName}</span>
                        </div>                    </div>
                                        <i class="fa fa-share custom-ajax-search-icon"></i>
                                    </a>
                                `;
                                resultsContainerAjax.append(html);
                            });
                        } else {
                            resultsContainerAjax.html('<div class="ajax-search-no-results" style="padding: 10px; text-align: center;">No students found</div>');
                        }
                    },
                    error: function() {
                        resultsContainerAjax.html('<div class="ajax-search-error" style="padding: 10px; text-align: center; color: red;">Error fetching results</div>');
                    }
                });
            }, 300);
        } else {
            resultsContainerAjax.empty().hide();
        }
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#search_student_ajax').length && !$(e.target).closest('#ajax_student_search_results_container').length) {
            resultsContainerAjax.hide();
        }
    });

    // Client-side list filter (grand total row always stays)
    $("#table_filter").on("input keyup", function() {
        var value = $(this).val().toLowerCase();
        var shown = 0;
        $("#headerTable tbody tr").not(':last').each(function() {
            var match = $(this).text().toLowerCase().indexOf(value) > -1;
            $(this).toggle(match);
            if (match) shown++;
        });
        var total = $("#headerTable tbody tr").length - 1;
        $("#fc_row_count").text(value ? (shown + " of " + total + " records") : (total + " records"));
        $("#fc_no_match").toggle(shown === 0);
    });

    // Quick period chips
    $(".fc-chip").on("click", function() {
        $(".fc-chip").removeClass("active");
        $(this).addClass("active");
        fcSubmitPeriod($(this).data("period"));
    });
});
</script>

<!-- Receipt Preview Modal -->
<div class="modal fade" id="receiptPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 850px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Receipt Preview</h4>
            </div>
            <div class="modal-body" id="receiptPreviewModalBody" style="background: #f3f4f6; padding: 20px;">
                <!-- Receipt content will be loaded here -->
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="printReceiptCopies('office')"><i class="fa fa-print"></i> Print Office Copy Only</button>
                <button type="button" class="btn btn-info" onclick="printReceiptCopies('receiver')"><i class="fa fa-print"></i> Print Receiver Copy Only</button>
                <button type="button" class="btn btn-primary" onclick="printReceiptCopies('both')"><i class="fa fa-print"></i> Print Both Copies</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* ---- Fee collection page ---- */
    .fc-page { --fc-ink: #0f172a; --fc-body: #334155; --fc-muted: #64748b; --fc-line: #e2e8f0; --fc-soft: #f8fafc; --fc-accent: var(--primary-theme-color, #4f46e5); }
    .fc-page.fc-loading .content { opacity: .55; pointer-events: none; transition: opacity .15s; }
    .fc-header { display: flex; align-items: flex-end; justify-content: space-between; padding-bottom: 4px; }
    .fc-header h1 { display: flex; align-items: center; gap: 10px; font-weight: 700; color: var(--fc-ink); margin: 0; font-size: 22px; }
    .fc-header-icon { width: 36px; height: 36px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; background: rgba(16,185,129,.12); color: #059669; font-size: 17px; }
    .fc-subtitle { margin: 6px 0 0 46px; color: var(--fc-muted); font-size: 13px; }
    .fc-subtitle strong { color: var(--fc-body); }

    .fc-card { border: 1px solid var(--fc-line); border-top: 1px solid var(--fc-line); border-radius: 12px; box-shadow: 0 1px 2px rgba(15,23,42,.04); background: #fff; }
    .fc-filter-card .box-body { padding: 16px 18px; }
    .fc-filter-row { display: flex; flex-wrap: wrap; gap: 18px; align-items: flex-end; }
    .fc-filter-period { flex: 1 1 420px; }
    .fc-filter-search { flex: 1 1 320px; }
    .fc-label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: var(--fc-muted); margin-bottom: 8px; }
    .fc-label .fa { margin-right: 4px; }
    .fc-chips { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
    .fc-chip { border: 1px solid var(--fc-line); background: #fff; color: var(--fc-body); border-radius: 999px; padding: 6px 14px; font-size: 13px; font-weight: 600; line-height: 1.3; cursor: pointer; transition: all .15s; }
    .fc-chip:hover { border-color: var(--fc-accent); color: var(--fc-accent); }
    .fc-chip.active { background: var(--fc-accent); border-color: var(--fc-accent); color: #fff; box-shadow: 0 2px 6px rgba(79,70,229,.25); }
    .fc-chip:focus-visible, .fc-icon-btn:focus-visible, .fc-btn:focus-visible { outline: 2px solid var(--fc-accent); outline-offset: 2px; }
    .fc-select { width: auto; min-width: 150px; height: 32px; border-radius: 999px; padding: 4px 12px; font-size: 13px; font-weight: 600; color: var(--fc-body); border-color: var(--fc-line); }
    .fc-select.active { border-color: var(--fc-accent); color: var(--fc-accent); }
    .fc-input-icon { position: relative; }
    .fc-page .fc-input-icon > .fa { position: absolute; left: 13px; top: 19px; transform: translateY(-50%); color: #94a3b8; pointer-events: none; z-index: 2; }
    .fc-page .fc-input-icon > input.form-control { padding-left: 36px !important; height: 38px; border-radius: 8px; border-color: var(--fc-line); box-shadow: none; }
    .fc-input-icon > .form-control:focus { border-color: var(--fc-accent); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
    .fc-filter-search .custom-ajax-search-container { top: calc(100% + 6px); }

    .fc-kpis { display: grid; grid-template-columns: minmax(0, 1.7fr) repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 20px; }
    .fc-kpi { display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid var(--fc-line); border-radius: 12px; padding: 16px 18px; box-shadow: 0 1px 2px rgba(15,23,42,.04); min-width: 0; }
    .fc-kpi-hero { background: linear-gradient(135deg, #ecfdf5 0%, #ffffff 70%); border-color: #a7f3d0; }
    .fc-kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0; }
    .fc-currency { font-weight: 800; font-size: 18px; line-height: 1; }
    .fc-kpi > div:last-child { min-width: 0; }
    .fc-kpi-label { font-size: 12px; font-weight: 600; color: var(--fc-muted); margin-bottom: 2px; line-height: 1.3; }
    .fc-kpi-value { font-size: 22px; font-weight: 800; color: var(--fc-ink); line-height: 1.2; font-variant-numeric: tabular-nums; white-space: nowrap; }
    .fc-kpi-hero .fc-kpi-value { color: #047857; font-size: clamp(18px, 1.7vw, 24px); white-space: normal; overflow-wrap: anywhere; }
    .fc-kpi-hint { font-size: 11px; color: var(--fc-muted); margin-top: 2px; }

    .fc-tone-green { background: rgba(16,185,129,.12); color: #047857; }
    .fc-tone-indigo { background: rgba(99,102,241,.12); color: #4338ca; }
    .fc-tone-violet { background: rgba(139,92,246,.12); color: #6d28d9; }
    .fc-tone-amber { background: rgba(245,158,11,.14); color: #b45309; }
    .fc-tone-sky { background: rgba(14,165,233,.12); color: #0369a1; }
    .fc-tone-blue { background: rgba(59,130,246,.12); color: #1d4ed8; }
    .fc-tone-red { background: rgba(239,68,68,.12); color: #b91c1c; }
    .fc-tone-slate { background: #f1f5f9; color: #475569; }
    .fc-ic-amber { color: #d97706; }
    .fc-ic-indigo { color: #6366f1; }

    .fc-toolbar { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 18px; border-bottom: 1px solid var(--fc-line); }
    .fc-toolbar-title { font-size: 15px; font-weight: 700; color: var(--fc-ink); display: flex; align-items: center; gap: 8px; }
    .fc-toolbar-title .fa { color: var(--fc-muted); }
    .fc-count { font-size: 12px; font-weight: 600; color: var(--fc-muted); background: var(--fc-soft); border: 1px solid var(--fc-line); padding: 2px 10px; border-radius: 999px; }
    .fc-toolbar-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
    .fc-table-search { width: 260px; }
    .fc-page .fc-table-search > input.form-control { height: 34px; }
    .fc-page .fc-table-search > .fa { top: 17px; }
    .fc-btn { display: inline-flex !important; align-items: center; gap: 6px; height: 34px; padding: 0 14px; border: 1px solid var(--fc-line); border-radius: 8px; background: #fff; color: var(--fc-body); font-weight: 600; font-size: 13px; }
    .fc-btn:hover { background: var(--fc-soft); border-color: #cbd5e1; color: var(--fc-ink); }
    .fc-btn .fa-file-excel-o { color: #16a34a; }
    .fc-btn .fa-print { color: var(--fc-accent); }

    .fc-table-wrap { padding: 0; max-height: 70vh; overflow: auto; }
    .fc-table { margin: 0; font-size: 13px; }
    .fc-table > thead > tr > th { position: sticky; top: 0; z-index: 3; background: var(--fc-soft); color: var(--fc-muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; border-bottom: 1px solid var(--fc-line) !important; padding: 10px 12px; white-space: nowrap; }
    .fc-table > tbody > tr > td { padding: 10px 12px; vertical-align: middle; border-top: 1px solid #f1f5f9; color: var(--fc-body); }
    .fc-table > tbody > tr:hover > td { background: #f8fafc; }
    .fc-muted { color: var(--fc-muted) !important; }
    .fc-mono { font-variant-numeric: tabular-nums; }
    .fc-nowrap { white-space: nowrap; }
    .fc-receipt { font-weight: 700; color: var(--fc-ink); font-variant-numeric: tabular-nums; }
    .fc-name { font-weight: 700; color: var(--fc-ink); }
    .fc-class { display: inline-block; padding: 2px 8px; border-radius: 6px; background: #eef2ff; color: #4338ca; font-weight: 600; font-size: 12px; white-space: nowrap; }
    .fc-type { display: inline-flex; align-items: center; gap: 6px; }
    .fc-amount { font-weight: 800; color: var(--fc-ink); font-variant-numeric: tabular-nums; white-space: nowrap; }
    .fc-pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; text-transform: uppercase; letter-spacing: .02em; }
    .fc-tx { font-size: 12px; color: var(--fc-muted); max-width: 220px; }
    .fc-icon-btn { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px; border: 1px solid var(--fc-line); color: var(--fc-accent); background: #fff; transition: all .15s; }
    .fc-icon-btn:hover { background: var(--fc-accent); color: #fff; border-color: var(--fc-accent); }
    .fc-row-reversed > td { background: #fef2f2 !important; color: #94a3b8 !important; }
    .fc-row-reversed .fc-amount, .fc-row-reversed .fc-receipt, .fc-row-reversed .fc-name { text-decoration: line-through; color: #94a3b8; }
    .fc-total-row > td { background: #f0fdf4 !important; border-top: 2px solid #bbf7d0 !important; font-weight: 800; }
    .fc-total-label { text-align: right; color: var(--fc-ink); text-transform: uppercase; font-size: 12px; letter-spacing: .04em; }
    .fc-total-value { color: #047857; font-size: 15px; white-space: nowrap; font-variant-numeric: tabular-nums; }
    .fc-no-match { padding: 28px; text-align: center; color: var(--fc-muted); }

    .fc-empty { text-align: center; padding: 48px 20px; }
    .fc-empty-icon { width: 64px; height: 64px; margin: 0 auto 12px; border-radius: 50%; background: var(--fc-soft); color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 28px; }
    .fc-empty h4 { font-weight: 700; color: var(--fc-ink); margin-bottom: 6px; }
    .fc-empty p { color: var(--fc-muted); margin: 0; }

    @media (max-width: 1199px) { .fc-kpis { grid-template-columns: repeat(3, 1fr); } .fc-kpi-hero { grid-column: 1 / -1; } }
    @media (max-width: 767px) {
        .fc-kpis { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .fc-kpi { padding: 12px; }
        .fc-kpi-value { font-size: 18px; }
        .fc-subtitle { margin-left: 0; }
        .fc-table-search { width: 100%; }
        .fc-toolbar-actions { width: 100%; }
        .custom-ajax-search-container { min-width: 0 !important; }
    }
    @media print { .fc-table-wrap { max-height: none; overflow: visible; } .fc-table > thead > tr > th { position: static; } }
    .search-student-wrapper {
        position: relative;
        z-index: 1050;
    }
    .custom-ajax-search-container {
        width: 100%; 
        min-width: 480px; 
        max-width: 100vw;
        position: absolute; 
        z-index: 99999 !important; 
        background: #ffffff; 
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.18), 0 4px 12px rgba(15, 23, 42, 0.08); 
        display: none; 
        max-height: 420px; 
        overflow-y: auto;
        border-radius: 10px;
        padding: 10px;
        border: 1px solid #e2e8f0;
        top: calc(100% + 6px);
        left: 0;
    }
    .custom-ajax-search-item {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        margin-bottom: 6px;
        background-color: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        text-decoration: none !important;
        color: #1e293b;
        transition: all 0.15s ease-in-out;
    }
    .custom-ajax-search-item:nth-child(even) {
        background-color: #f8fafc;
    }
    .custom-ajax-search-item:last-child {
        margin-bottom: 0;
    }
    .custom-ajax-search-item:hover, .custom-ajax-search-item:focus {
        background-color: var(--primary-theme-color, #4f46e5);
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        border-color: var(--primary-theme-color, #4f46e5);
        outline: none;
    }
    .custom-ajax-search-item:hover .custom-ajax-search-name {
        color: #ffffff !important;
    }
    .custom-ajax-search-item:hover .custom-ajax-search-meta {
        color: #e0e7ff !important;
    }
    .custom-ajax-search-item:hover .custom-ajax-search-parents {
        color: #ffffff !important;
    }
    .custom-ajax-search-item:hover .custom-ajax-search-icon {
        color: #ffffff !important;
    }
    .custom-ajax-search-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 14px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.06);
        background: #fff;
        flex-shrink: 0;
    }
    .custom-ajax-search-details {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-width: 0;
        gap: 12px;
    }
    .custom-ajax-search-col {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-width: 0;
    }
    .custom-ajax-search-name {
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 2px;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .custom-ajax-search-meta {
        font-size: 12px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .custom-ajax-search-parents {
        display: flex;
        flex-direction: column;
        font-size: 11px;
        color: #475569;
        font-weight: 600;
        min-width: 140px;
        flex-shrink: 0;
    }
    .parent-badge {
        color: #fff !important;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        margin-right: 6px;
        font-weight: bold;
        flex-shrink: 0;
    }
    .badge-father {
        background-color: #3b82f6 !important;
    }
    .badge-mother {
        background-color: #ec4899 !important;
    }
    .custom-ajax-search-icon {
        color: #94a3b8;
        margin-left: 12px;
        font-size: 16px;
        flex-shrink: 0;
    }
    /* Scrollbar styling for the container */
    .custom-ajax-search-container::-webkit-scrollbar {
        width: 6px;
    }
    .custom-ajax-search-container::-webkit-scrollbar-track {
        background: #f1f5f9; 
        border-radius: 4px;
    }
    .custom-ajax-search-container::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 4px;
    }
    .custom-ajax-search-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; 
    }
</style>
