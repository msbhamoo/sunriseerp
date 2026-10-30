<div id="annual_calendar_view"></div>

<!-- FullCalendar JS -->
<script src="<?php echo base_url(); ?>backend/plugins/fullcalendar/fullcalendar.min.js"></script>
<script>
$(document).ready(function() {
    $('#annual_calendar_view').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: new Date(),
        navLinks: true, // can click day/week names to navigate views
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        events: "<?php echo site_url('admin/holiday/get_calendar_events'); ?>",
        eventRender: function(event, element) {
            element.attr('title', event.title);
            element.css('cursor', 'pointer');
        },
        eventClick: function(event) {
            // Check if user can edit
            <?php if ($this->rbac->hasPrivilege('annual_calendar', 'can_edit')) { ?>
            get(event.id);
            <?php } else { ?>
            alert(event.title);
            <?php } ?>
        }
    });

    // Re-render calendar when tab is shown to fix width/height issues
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if($(e.target).attr('href') == '#tab_calendar') {
            $('#annual_calendar_view').fullCalendar('render');
        }
    });
});
</script>
