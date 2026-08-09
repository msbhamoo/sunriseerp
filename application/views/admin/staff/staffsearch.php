<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
            <?php if ($this->rbac->hasPrivilege('staff', 'can_add')) { ?>
                <small class="pull-right">
                    <?php if ($this->rbac->hasPrivilege('staff', 'can_edit')) { ?>
                        <a href="<?php echo base_url(); ?>admin/staff/bulk_update" class="btn btn-info btn-sm" style="margin-right: 5px;">
                            <i class="fa fa-upload"></i> Bulk Update Staff
                        </a>
                    <?php } ?>
                    <a href="<?php echo base_url(); ?>admin/staff/create" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_staff'); ?>
                    </a>
                </small>

            <?php } ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Search & Filter Card -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('staff', 'can_edit')) { ?>
                                <a href="<?php echo base_url(); ?>admin/staff/bulk_update" class="btn btn-info btn-sm" style="margin-right: 5px;">
                                    <i class="fa fa-upload"></i> Bulk Update Staff
                                </a>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('staff', 'can_add')) { ?>
                                <a href="<?php echo base_url(); ?>admin/staff/create" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_staff'); ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                        <?php } ?>
                        
                        <form id="ajax_search_form" onsubmit="return false;">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line("role"); ?></label>
                                        <select id="role_select" name="role_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line("all") ? $this->lang->line("all") : "All"; ?></option>
                                            <?php foreach ($role as $key => $role_value) { ?>
                                                <option value="<?php echo $role_value['id'] ?>"><?php echo $role_value['type'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                        <div class="input-group">
                                            <input type="text" id="search_text_input" name="search_text" class="form-control" placeholder="<?php echo $this->lang->line('search_by_staff'); ?>" autocomplete="off">
                                            <span class="input-group-btn">
                                                <button type="button" id="btn_clear_search" class="btn btn-default" title="Clear Search"><i class="fa fa-times"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Results Container -->
                <div class="box box-primary">
                    <div class="nav-tabs-custom border0">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-th-large"></i> <?php echo $this->lang->line('card_view'); ?></a></li>
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-list"></i> <?php echo $this->lang->line('list_view'); ?></a></li>
                            <li class="pull-right">
                                <span id="search_loading_spinner" style="display: none; padding: 10px 15px; color: #3b82f6; font-weight: 500;">
                                    <i class="fa fa-circle-o-notch fa-spin"></i> Searching...
                                </span>
                                <span id="results_counter_badge" class="badge bg-blue" style="margin: 10px 15px;">
                                    <?php echo count($resultlist); ?> Staff Found
                                </span>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Card View Tab -->
                            <div class="tab-pane active" id="tab_1">
                                <div id="card_view_container">
                                    <?php $this->load->view('admin/staff/_staff_card_view', array('resultlist' => $resultlist)); ?>
                                </div>
                            </div>

                            <!-- List View Tab -->
                            <div class="tab-pane" id="tab_2">
                                <div id="list_view_container">
                                    <?php $this->load->view('admin/staff/_staff_list_view', array('resultlist' => $resultlist, 'fields' => $fields)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var debounceTimer;
        var base_url = '<?php echo base_url(); ?>';

        function performAjaxSearch() {
            var role_id = $('#role_select').val();
            var search_text = $('#search_text_input').val();
            var csrf_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
            var csrf_hash = '<?php echo $this->security->get_csrf_hash(); ?>';

            $('#search_loading_spinner').show();

            var post_data = {
                'role_id': role_id,
                'search_text': search_text,
                'is_active': 1
            };
            post_data[csrf_name] = csrf_hash;

            $.ajax({
                type: "POST",
                url: base_url + "admin/staff/ajaxsearch",
                data: post_data,
                dataType: "json",
                success: function (data) {
                    $('#search_loading_spinner').hide();
                    if (data.status === 'success') {
                        $('#card_view_container').html(data.card_html);
                        $('#list_view_container').html(data.list_html);
                        $('#results_counter_badge').text(data.count + ' Staff Found');
                    }
                },
                error: function () {
                    $('#search_loading_spinner').hide();
                }
            });
        }

        // Live search on typing with 300ms debounce
        $('#search_text_input').on('keyup input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(performAjaxSearch, 300);
        });

        // Search on role change
        $('#role_select').on('change', function () {
            performAjaxSearch();
        });

        // Clear search
        $('#btn_clear_search').on('click', function () {
            $('#search_text_input').val('');
            $('#role_select').val('');
            performAjaxSearch();
        });
    });
</script>