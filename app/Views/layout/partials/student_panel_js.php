<script type="text/javascript">
/*
 * Student / parent panel: label table cells with their column headings so that
 * wide data tables can be shown as stacked cards on phones (see .sp-cards CSS).
 * Applies to tables with a header row of 4+ columns that haven't opted out
 * with the "sp-no-cards" class.
 */
(function ($) {
    function labelTable(table) {
        var $table = $(table);
        if ($table.hasClass('sp-no-cards') || $table.data('spLabelled')) {
            return;
        }
        var $headRow = $table.find('> thead > tr').last();
        if (!$headRow.length) {
            $headRow = $table.find('> tbody > tr').first().filter(function () {
                return $(this).children('th').length > 0;
            });
        }
        var labels = $headRow.children('th, td').map(function () {
            return $.trim($(this).text());
        }).get();
        if (labels.length < 4) {
            return;
        }
        $table.find('> tbody > tr').each(function () {
            if (this === $headRow[0]) {
                $(this).addClass('sp-head-row');
                return;
            }
            var col = 0;
            $(this).children('td').each(function () {
                var span = parseInt($(this).attr('colspan') || 1, 10);
                $(this).attr('data-label', span > 1 ? '' : (labels[col] || ''));
                col += span;
            });
        });
        $table.addClass('sp-cards').data('spLabelled', true);
    }

    function labelAll() {
        $('.content-wrapper table.table').each(function () {
            labelTable(this);
        });
    }

    // Photos whose files are missing fall back to the default "no image" picture
    var fallbackImg = '<?php echo base_url('uploads/student_images/no_image.png'); ?>';
    function useFallback(img) {
        if (img.dataset.spFallback) {
            return;
        }
        img.dataset.spFallback = '1';
        img.src = fallbackImg;
    }
    document.addEventListener('error', function (e) {
        if (e.target && e.target.tagName === 'IMG' && e.target.closest('.content-wrapper, .main-header')) {
            useFallback(e.target);
        }
    }, true);

    $(function () {
        $('.content-wrapper img, .main-header img').each(function () {
            if (this.complete && this.naturalWidth === 0 && this.getAttribute('src')) {
                useFallback(this);
            }
        });
        labelAll();
        // Tables rendered later by AJAX / DataTables
        $(document).on('draw.dt ajaxComplete', function () {
            setTimeout(labelAll, 0);
        });
    });
})(jQuery);
</script>
