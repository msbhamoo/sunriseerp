 <script type="text/javascript">
        $(document).ready(function () {
            // Function to adjust position
            // Function to adjust position
            function adjustSubmenuPosition($item) {
                var $submenu = $item.find('.treeview-menu');
                var $label = $item.find('> a > span');

                // Only proceed if sidebar is collapsed
                if (!$('body').hasClass('sidebar-collapse')) return;

                if ($submenu.length || $label.length) {
                    var itemRect = $item[0].getBoundingClientRect();
                    var top = itemRect.top;
                    var left = 65; // Fixed width of collapsed sidebar

                    if ($submenu.length) {
                        // Measure height first
                        $submenu.css({ 'display': 'block', 'visibility': 'hidden' });
                        var height = $submenu.outerHeight();
                        $submenu.css({ 'visibility': 'visible' }); // Restore visibility

                        var windowHeight = $(window).height();
                        var bottomOverflow = (top + 46 + height) - windowHeight;

                        // Default position (Below)
                        var finalTop = top + 46;
                        var useScroll = false;

                        if (bottomOverflow > 0) {
                            // Check if it fits above
                            // Top position would be top - height
                            if (top - height > 0) {
                                finalTop = top - height;
                            } else {
                                // Fits neither? Use scroll below
                                useScroll = true;
                            }
                        }

                        if (useScroll) {
                            var availableHeight = windowHeight - finalTop - 10;
                            $submenu.css({
                                'position': 'fixed',
                                'top': finalTop + 'px',
                                'left': left + 'px',
                                'display': 'block',
                                'margin-top': '0',
                                'width': '200px',
                                'max-height': availableHeight + 'px',
                                'overflow-y': 'auto'
                            });
                        } else {
                            $submenu.css({
                                'position': 'fixed',
                                'top': finalTop + 'px',
                                'left': left + 'px',
                                'display': 'block',
                                'margin-top': '0',
                                'width': '200px',
                                'max-height': '',
                                'overflow-y': ''
                            });
                        }
                    }

                    if ($label.length) {
                        $label.css({
                            'position': 'fixed',
                            'top': top + 'px',
                            'left': left + 'px',
                            'display': 'block',
                            'z-index': '10001', // Ensure above menu
                            'width': '200px', // Match menu width if it's acting as a header
                            'padding': '12px 5px 12px 20px',
                            'margin-top': '0'
                        });
                    }
                }
            }

            // Restore menu state from localStorage or use active menu as fallback
            var savedMenuState = localStorage.getItem(sidebarKey('openSidebarMenuIndex'));
            var $activeMenu = $('.sidebar-menu > li.treeview.active');
            
            if ($(window).width() <= 767) {
                // MOBILE VIEW: Always start closed by default
                $('body').removeClass('sidebar-open sidebar-collapse submenu-is-open');
                $('.sidebar-menu > li .treeview-menu').removeClass('is-open');
            } else {
                // DESKTOP VIEW: Restore desktop open menu state
                if (savedMenuState === "closed" && window.sidebarRestoreOpenMenu !== false) {
                    // User explicitly closed it, do nothing
                } else if (savedMenuState !== null && window.sidebarRestoreOpenMenu !== false) {
                    var $savedMenu = $('.sidebar-menu > li.treeview').eq(savedMenuState);
                    if ($savedMenu.length) {
                        $savedMenu.find('.treeview-menu').addClass('is-open');
                        $('body').addClass('submenu-is-open');
                    }
                } else if ($activeMenu.length) {
                    var $activeSubmenu = $activeMenu.find('.treeview-menu');
                    $activeSubmenu.addClass('is-open');
                    $('body').addClass('submenu-is-open');
                    localStorage.setItem(sidebarKey('openSidebarMenuIndex'), $('.sidebar-menu > li.treeview').index($activeMenu));
                }
            }

            // Toggle submenu on click for the permanent dual-column design
            $(document).on('click', '.sidebar-menu > li.treeview > a', function (e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $parentLi = $(this).parent();
                var $submenu = $parentLi.find('.treeview-menu');
                
                if ($submenu.hasClass('is-open')) {
                    return;
                }

                $('.sidebar-menu > li .treeview-menu').removeClass('is-open');
                $submenu.addClass('is-open');
                $('body').addClass('submenu-is-open');
                localStorage.setItem(sidebarKey('openSidebarMenuIndex'), $('.sidebar-menu > li.treeview').index($parentLi));
            });

            // Auto-hide mobile drawer when tapping any submenu item link to navigate
            $(document).on('click', '.treeview-menu a', function() {
                if ($(window).width() <= 767) {
                    $('body').removeClass('sidebar-open submenu-is-open');
                    $('.sidebar-menu > li .treeview-menu').removeClass('is-open');
                }
            });

            // Close button click (The cross)
            $(document).on('click', '.close-submenu', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).closest('.treeview-menu').removeClass('is-open');
                $('body').removeClass('submenu-is-open');
                localStorage.setItem(sidebarKey('openSidebarMenuIndex'), 'closed');
            });

            // Restore desktop sidebar collapse state from localStorage
            if (localStorage.getItem(sidebarKey('sidebar_collapsed')) === '1' && $(window).width() >= 768) {
                $('body').addClass('sidebar-collapse');
                $('.sidebar-menu > li .treeview-menu').removeClass('is-open');
                $('body').removeClass('submenu-is-open');
                localStorage.setItem(sidebarKey('openSidebarMenuIndex'), 'closed');
            }

            // Sidebar toggle click handler (Desktop & Mobile)
            $(document).on('click touchstart', '.sidebar-toggle', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('.sidebar-menu > li .treeview-menu').removeClass('is-open');
                $('body').removeClass('submenu-is-open');
                localStorage.setItem(sidebarKey('openSidebarMenuIndex'), 'closed');

                if (window.innerWidth < 768) {
                    $('body').toggleClass('sidebar-open');
                } else {
                    $('body').toggleClass('sidebar-collapse');
                    var isCollapsed = $('body').hasClass('sidebar-collapse');
                    localStorage.setItem(sidebarKey('sidebar_collapsed'), isCollapsed ? '1' : '0');
                }
            });

            // Close mobile drawer when clicking/tapping outside on content wrapper
            $(document).on('click touchstart', '.content-wrapper, .main-footer', function(e) {
                if ($(e.target).closest('.main-sidebar, .sidebar-toggle').length > 0) {
                    return;
                }

                if (window.innerWidth < 768 && $('body').hasClass('sidebar-open')) {
                    $('body').removeClass('sidebar-open');
                }
            });

        });
    </script>
