<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?></h1>
    </section>
    
    <section class="content">
        <!-- Unified Header & Criteria Box -->
        <div class="box box-primary" style="overflow: visible; z-index: 50;">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter text-muted" style="margin-right: 6px;"></i> Fee Collection Report</h3>
            </div>
            <div class="box-body" style="overflow: visible;">
                <form role="form" action="<?php echo site_url('financereports/collection_list') ?>" method="post" class="">
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

                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('search_duration'); ?></label>
                                <select class="form-control" id="search_type_select" onchange="$('#search_type_hidden').val(this.value); this.form.submit();">
                                    <?php foreach ($searchlist as $key => $search) { ?>
                                        <option value="<?php echo $key ?>" <?php if ((isset($search_type)) && ($search_type == $key)) { echo "selected"; } ?>><?php echo $search ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-6">
                            <div class="form-group search-student-wrapper">
                                <label>Quick Search Student</label>
                                <div>
                                    <input type="text" name="search_student" id="search_student_ajax" class="form-control" value="<?php echo set_value('search_student', isset($search_student) ? $search_student : ''); ?>" placeholder="Search student name, roll no, scholar no..." autocomplete="off">
                                    <div id="ajax_student_search_results_container" class="custom-ajax-search-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (empty($results)) { ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="alert alert-info" style="margin-bottom:0; border-radius: 8px;">
                       <?php echo $this->lang->line('no_record_found'); ?>
                    </div>
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
            <!-- Modern KPI Summary Stat Grid -->
            <div class="modern-stat-grid">
                <div class="modern-stat-card">
                    <div class="modern-stat-info">
                        <div class="stat-label">Total Fee Receipts</div>
                        <div class="stat-value"><?php echo $fee_receipts_count; ?></div>
                    </div>
                    <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                </div>
                
                <div class="modern-stat-card">
                    <div class="modern-stat-info">
                        <div class="stat-label">Transport Receipts</div>
                        <div class="stat-value text-warning" style="color: #d97706;"><?php echo $transport_receipts_count; ?></div>
                    </div>
                    <div class="modern-stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                        <i class="fa fa-bus"></i>
                    </div>
                </div>
                
                <div class="modern-stat-card">
                    <div class="modern-stat-info">
                        <div class="stat-label">Unique Students</div>
                        <div class="stat-value" style="color: #0284c7;"><?php echo $total_unique_students; ?></div>
                    </div>
                    <div class="modern-stat-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                </div>
                
                <div class="modern-stat-card">
                    <div class="modern-stat-info">
                        <div class="stat-label">Total Amount Collected</div>
                        <div class="stat-value text-success" style="color: #059669; font-size: 20px;"><?php echo $currency_symbol . " " . number_format($total_collected_val, 2); ?></div>
                    </div>
                    <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                        <i class="fa fa-inr"></i>
                    </div>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title titlefix"><i class="fa fa-list text-muted" style="margin-right: 6px;"></i> Fee Collection List</h3>
                    <div class="box-tools pull-right">
                        <a class="btn btn-default btn-sm" id="btnExport" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('download_excel'); ?>" onclick="fnExcelReport();"><i class="fa fa-file-excel-o text-success"></i> CSV</a>
                        <a class="btn btn-default btn-sm" id="print" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('print'); ?>" onclick="printDiv()"><i class="fa fa-print text-primary"></i> Print</a>
                    </div>
                </div>
                            
                            <div class="box-body table-responsive" id="transfee">
                                <div id="printhead"><center><b><h4>Fee Collection List<br><?php $this->customlib->get_postmessage(); ?></h4></b></center></div>
                                <div class="download_label">Fee Collection List<br><?php $this->customlib->get_postmessage(); ?></div>

                                <table class="table table-striped table-hover example" style="border: none;" id="headerTable">
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                        $count = 1;
                                        $grdTotalLabel = array();
                                     
                                        foreach ($results as $collect) {           
                                            $t1 = two_digit_float($collect['amount']) + two_digit_float($collect['amount_fine']);
                                            $grdTotalLabel[] = $t1;
                                            $status_class = ($collect['custom_receipt_status'] == 'Reversed') ? 'text-danger' : 'text-success';
                                            $strike_class = ($collect['custom_receipt_status'] == 'Reversed') ? 'style="text-decoration: line-through;"' : '';
                                        ?>
                                            <tr <?php echo $strike_class; ?>>
                                                <td><?php echo $count++; ?></td>
                                                <td><span style="font-weight: 700; color: #0f172a;"><?php echo $collect['custom_receipt_no']; ?></span></td>                
                                                <td><?php echo $collect['admission_no']; ?></td>                
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($collect['date'])); ?></td>                
                                                <td><span style="font-weight: 700; color: #1e293b;"><?php echo $this->customlib->getFullName($collect['firstname'], $collect['middlename'], $collect['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></span></td>                
                                                <td><?php echo isset($collect['father_name']) ? $collect['father_name'] : ''; ?></td>                
                                                <td><span class="label label-info" style="border-radius: 4px; font-weight: 600;"><?php echo $collect['class'] . " (" . $collect['section'] . ")"; ?></span></td>                
                                                <td>
                                                    <?php
                                                        if ( $collect['is_system']) {
                                                            echo $this->lang->line($collect['type']);
                                                        } else {
                                                            echo $collect['type'] ;
                                                            echo " (".$collect['code'].")";
                                                        }    
                                                    ?>
                                                </td>
                                                <td class="text text-right" style="font-weight: 800; color: #0f172a;">
                                                    <?php echo two_digit_float($t1); ?>
                                                </td>                              
                                                <td><span class="label label-default" style="border-radius: 4px; text-transform: uppercase; font-size: 10px;"><?php echo $this->lang->line(strtolower($collect['payment_mode'])); ?></span></td>
                                                <td>
                                                    <?php 
                                                        $tx_details = [];
                                                        if (!empty($collect['reference_no'])) {
                                                            $tx_details[] = "<strong>Ref:</strong> " . $collect['reference_no'];
                                                        }
                                                        if (!empty($collect['description'])) {
                                                            $tx_details[] = "<strong>Desc:</strong> " . $collect['description'];
                                                        }
                                                        echo implode("<br>", $tx_details);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if($collect['custom_receipt_status'] == 'Reversed') { ?>
                                                        <span class="label label-danger">Reverted</span>
                                                    <?php } else { ?>
                                                        <span class="label label-success">Received</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-right white-space-nowrap">
                                                    <?php if($collect['custom_receipt_status'] == 'Collected' || empty($collect['custom_receipt_status']) || $collect['custom_receipt_status'] != 'Reversed') { ?>
                                                        <a href="javascript:void(0);" class="btn btn-default btn-xs" onclick="printReceipt('<?php echo $collect['id']; ?>', '<?php echo $collect['inv_no']; ?>')" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>">
                                                            <i class="fa fa-print text-primary"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>                    
                                        <?php } ?>                            
                                        
                                        <tr style="background: #f8fafc; font-weight: 800;">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="font-weight:800; color: #0f172a;"><?php echo $this->lang->line('grand_total'); ?></td>
                                            <td class="text text-right" style="font-weight:800; color: #059669; font-size: 15px;"><?php echo $currency_symbol.amountFormat(array_sum($grdTotalLabel)); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
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

    // Client-side list filter
    $("#table_filter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#headerTable tbody tr").not(':last').filter(function() { // exclude grand total row if we want, or just filter all
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
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
