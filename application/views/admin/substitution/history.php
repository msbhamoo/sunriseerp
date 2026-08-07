<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f8fafc;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    .hist-header-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .hist-header-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .hist-header-title i {
        color: #0284c7;
        font-size: 20px;
    }

    .hist-search-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .hist-table-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
    }

    /* Modern Table Styling */
    .table-history {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    .table-history thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        padding: 12px 16px !important;
        border-bottom: 2px solid #e2e8f0 !important;
        border-top: none !important;
    }
    .table-history tbody td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        color: #1e293b !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }
    .table-history tbody tr:hover {
        background-color: #f8fafc !important;
    }

    /* Custom DataTables Header & Toolbar Styling */
    .hist-card-title-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 16px;
        margin-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .hist-card-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .hist-card-title i {
        color: #0284c7;
    }

    .hist-table-card .dt-buttons {
        margin-bottom: 0 !important;
        display: inline-flex !important;
        gap: 8px !important;
    }
    .hist-table-card .dt-button {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 6px 14px !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #334155 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03) !important;
        transition: all 0.2s ease !important;
    }
    .hist-table-card .dt-button:hover {
        background: #f8fafc !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
    }
    .dt-controls-row {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        margin-bottom: 16px !important;
    }
    .hist-table-card .dataTables_filter {
        margin-bottom: 0 !important;
        float: left !important;
    }
    .hist-table-card .dataTables_filter input {
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        padding: 7px 14px !important;
        font-size: 13px !important;
        outline: none !important;
        box-shadow: none !important;
        width: 220px !important;
        background: #ffffff !important;
    }
    .hist-table-card .dataTables_length {
        float: right !important;
        margin-bottom: 0 !important;
    }
    .hist-table-card .dataTables_length select {
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        background: #ffffff !important;
    }

    /* Custom Badges */
    .badge-sub-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-sub-status.success { background: #dcfce7; color: #15803d; }
    .badge-sub-status.warning { background: #fef3c7; color: #b45309; }
    .badge-sub-status.danger { background: #fee2e2; color: #b91c1c; }
    .badge-sub-status.info { background: #e0f2fe; color: #0369a1; }
</style>

<div class="content-wrapper dashboard2-wrapper" style="min-height: 946px;">
    <section class="content">
        <!-- Header Card -->
        <div class="hist-header-card">
            <div class="hist-header-title">
                <i class="fa fa-history"></i> Substitution History Logs
            </div>
            <div>
                <a href="<?php echo site_url('admin/substitution/index'); ?>" class="btn btn-sm btn-default" style="border-radius: 8px; font-weight: 600; border: 1px solid #cbd5e1; background: #ffffff; color: #334155;"><i class="fa fa-calendar-check-o"></i> Substitute Planning</a>
                <a href="<?php echo site_url('admin/substitution/todays_schedule'); ?>" class="btn btn-sm btn-primary" style="border-radius: 8px; font-weight: 600; background: #0284c7; border-color: #0284c7;"><i class="fa fa-clock-o"></i> Today's Schedule</a>
            </div>
        </div>

        <!-- Search Filter Card -->
        <div class="hist-search-card">
            <form role="form" action="<?php echo site_url('admin/substitution/history') ?>" method="post">
                <?php echo $this->customlib->getCSRF(); ?>
                <div class="row" style="display: flex; align-items: flex-end; flex-wrap: wrap;">
                    <div class="col-md-4 col-sm-5">
                        <div class="form-group mb0">
                            <label style="font-weight: 700; color: #475569;"><?php echo $this->lang->line('date'); ?></label>
                            <input type="text" id="date" name="date" class="form-control date" value="<?php echo set_value('date', $search_date); ?>" style="border-radius: 8px; border: 1px solid #cbd5e1;" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5">
                        <div class="form-group mb0">
                            <label style="font-weight: 700; color: #475569;">Filter by Teacher</label>
                            <select id="staff_id" name="staff_id" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1;">
                                <option value=""><?php echo $this->lang->line('select'); ?> (All Teachers)</option>
                                <?php
                                foreach ($staff_list as $staff) {
                                    $selected = ($staff['id'] == $search_staff_id) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $staff['id'] ?>" <?php echo $selected; ?>><?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ")" ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-2 text-right">
                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-block" style="border-radius: 8px; font-weight: 700; padding: 7px 16px; background: #0284c7; border-color: #0284c7;"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                    </div>
                </div>
            </form>
        </div>

        <!-- History Records Table Card -->
        <div class="hist-table-card">
            <!-- Header Bar matching reference layout -->
            <div class="hist-card-title-bar">
                <div class="hist-card-title">
                    <i class="fa fa-th"></i> Substitution History List
                </div>
                <div id="export_buttons_container"></div>
            </div>

            <div class="table-responsive">
                <table class="table table-history substitution-history-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Absent Teacher</th>
                            <th>Substitute Teacher</th>
                            <th>Class (Section)</th>
                            <th>Subject</th>
                            <th>Time</th>
                            <th>Period</th>
                            <th>Assigned By</th>
                            <th>Conflict Status</th>
                            <th>Leave Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($history)) { foreach ($history as $h) { ?>
                            <tr>
                                <td style="white-space: nowrap; font-weight: 700; color: #0f172a;">
                                    <i class="fa fa-calendar-o text-muted" style="margin-right: 4px;"></i>
                                    <?php echo date($this->customlib->getSchoolDateFormat(), strtotime($h['date'])); ?>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #e11d48;"><?php echo $h['absent_name'] . " " . $h['absent_surname']; ?></div>
                                    <div style="font-size: 11px; color: #64748b;">ID: <?php echo $h['absent_emp_id']; ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #059669;"><?php echo $h['sub_name'] . " " . $h['sub_surname']; ?></div>
                                    <div style="font-size: 11px; color: #64748b;">ID: <?php echo $h['sub_emp_id']; ?></div>
                                </td>
                                <td>
                                    <span class="badge-sub-status info"><i class="fa fa-graduation-cap"></i> <?php echo $h['class'] . " (" . $h['section'] . ")"; ?></span>
                                </td>
                                <td style="font-weight: 700; color: #1e293b;"><?php echo $h['subject_name']; ?></td>
                                <td style="white-space: nowrap;">
                                    <i class="fa fa-clock-o text-muted"></i> <?php echo $h['time_from'] . " - " . $h['time_to']; ?>
                                </td>
                                <td>
                                    <?php if (!empty($h['period_number'])) { ?>
                                        <span class="badge-sub-status info" style="font-weight: 800;">Period <?php echo $h['period_number']; ?></span>
                                    <?php } else { ?>
                                        <span class="badge-sub-status info">N/A</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #334155;"><?php echo $h['admin_name'] . " " . $h['admin_surname']; ?></div>
                                    <div style="font-size: 11px; color: #94a3b8;"><?php echo $h['admin_emp_id']; ?></div>
                                </td>
                                <td>
                                    <?php if($h['override_conflict_timetable_id']) { ?>
                                        <span class="badge-sub-status warning"><i class="fa fa-exclamation-triangle"></i> Override (Slot #<?php echo $h['override_conflict_timetable_id']; ?>)</span>
                                    <?php } else { ?>
                                        <span class="badge-sub-status success"><i class="fa fa-check-circle"></i> Clean</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($h['is_unplanned'] == 0) { ?>
                                        <span class="badge-sub-status success"><i class="fa fa-calendar-check-o"></i> Planned</span>
                                    <?php } else { ?>
                                        <span class="badge-sub-status danger"><i class="fa fa-flash"></i> Unplanned</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('.substitution-history-table')) {
            $('.substitution-history-table').DataTable().destroy();
        }
        
        var date_format = '<?php echo strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy', 'M' => 'M']); ?>';
        $('.date').datepicker({
            format: date_format,
            autoclose: true,
            todayHighlight: true
        });

        // Initialize DataTable with Excel, CSV, and Print Buttons
        var table = $('.substitution-history-table').DataTable({
            dom: '<"dt-controls-row"fl>' +
                 'tr' +
                 '<"row" <"col-sm-5"i><"col-sm-7"p>>',
            buttons: [
                {
                    extend: 'excel',
                    className: 'btn btn-default',
                    text: '<i class="fa fa-file-excel-o text-success"></i> Excel',
                    title: 'Substitution History Log',
                    exportOptions: { columns: ':visible' }
                },
                {
                    extend: 'csv',
                    className: 'btn btn-default',
                    text: '<i class="fa fa-file-text-o"></i> CSV',
                    title: 'Substitution History Log',
                    exportOptions: { columns: ':visible' }
                },
                {
                    extend: 'print',
                    className: 'btn btn-default',
                    text: '<i class="fa fa-print"></i> Print',
                    title: 'Substitution History Log',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 9] }, // Excludes Assigned By (7) and Conflict Status (8)
                    customize: function (win) {
                        var body = $(win.document.body);
                        body.css('font-family', "'Inter', system-ui, -apple-system, sans-serif")
                            .css('padding', '15px')
                            .css('background-color', '#ffffff');

                        // Inject media print styles to force color backgrounds & print graphics
                        $(win.document.head).append(`
                            <style type="text/css">
                                @page {
                                    size: A4 portrait;
                                    margin: 10mm;
                                }
                                @media print {
                                    body {
                                        -webkit-print-color-adjust: exact !important;
                                        print-color-adjust: exact !important;
                                        color-adjust: exact !important;
                                    }
                                    .badge-sub-status {
                                        -webkit-print-color-adjust: exact !important;
                                        print-color-adjust: exact !important;
                                        border: 1px solid rgba(0,0,0,0.1) !important;
                                    }
                                    .badge-sub-status.success { background-color: #dcfce7 !important; color: #15803d !important; }
                                    .badge-sub-status.warning { background-color: #fef3c7 !important; color: #b45309 !important; }
                                    .badge-sub-status.danger { background-color: #fee2e2 !important; color: #b91c1c !important; }
                                    .badge-sub-status.info { background-color: #e0f2fe !important; color: #0369a1 !important; }
                                    th {
                                        background-color: #f8fafc !important;
                                        -webkit-print-color-adjust: exact !important;
                                    }
                                }
                            </style>
                        `);

                        body.find('h1')
                            .text('Substitution History Report')
                            .css({
                                'text-align': 'center',
                                'font-size': '18px',
                                'font-weight': '800',
                                'color': '#0f172a',
                                'margin-bottom': '12px',
                                'border-bottom': '2px solid #0284c7',
                                'padding-bottom': '6px'
                            });

                        var $table = body.find('table');
                        $table.removeClass('table-striped table-bordered')
                            .css({
                                'width': '100%',
                                'border-collapse': 'collapse',
                                'margin-top': '8px'
                            });

                        $table.find('th').css({
                            'background-color': '#f8fafc',
                            'color': '#334155',
                            'font-size': '10px',
                            'font-weight': '700',
                            'text-transform': 'uppercase',
                            'letter-spacing': '0.5px',
                            'padding': '6px 8px',
                            'border-bottom': '2px solid #cbd5e1',
                            'text-align': 'left'
                        });

                        $table.find('td').css({
                            'padding': '6px 8px',
                            'font-size': '10px',
                            'color': '#1e293b',
                            'border-bottom': '1px solid #e2e8f0',
                            'vertical-align': 'middle'
                        });

                        // Keep badges styled nicely for print
                        body.find('.badge-sub-status').css({
                            'display': 'inline-block',
                            'padding': '2px 6px',
                            'border-radius': '4px',
                            'font-size': '9px',
                            'font-weight': '700'
                        });
                    }
                }
            ],
            "aaSorting": [],
            "language": {
                "search": "",
                "searchPlaceholder": "Search",
                "lengthMenu": "_MENU_"
            }
        });

        // Append buttons to the header bar top-right
        table.buttons().container().appendTo('#export_buttons_container');
    });
</script>
