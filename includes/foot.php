<?php
$__base_foot = '';
if (strpos($_SERVER['PHP_SELF'], '/modul/') !== false) {
    $__base_foot = '../../';
}
?>
<script src="<?= $__base_foot ?>assets/vendors/js/vendor.bundle.base.js"></script>
<script src="<?= $__base_foot ?>assets/vendors/chart.js/Chart.min.js"></script>
<script>
$(document).ready(function() {
    // Sidebar scroll position restore
    var sidebarNav = document.querySelector('.sidebar-nav');
    if (sidebarNav) {
        var savedScroll = sessionStorage.getItem('sidebarScroll');
        if (savedScroll) sidebarNav.scrollTop = parseInt(savedScroll);
        sidebarNav.addEventListener('scroll', function() {
            sessionStorage.setItem('sidebarScroll', sidebarNav.scrollTop);
        });
    }

    // Sidebar toggle (mobile)
    $('#menuToggle').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#sidebarOverlay').toggleClass('active');
        $('body').toggleClass('sidebar-open');
    });

    $('#sidebarOverlay').on('click', function() {
        $('#sidebar').removeClass('active');
        $(this).removeClass('active');
        $('body').removeClass('sidebar-open');
    });

    // Close sidebar on nav click (mobile)
    $(window).on('resize', function() {
        if ($(window).width() > 992) {
            $('#sidebar').removeClass('active');
            $('#sidebarOverlay').removeClass('active');
            $('body').removeClass('sidebar-open');
        }
    });

    // Close sidebar on nav click (mobile)
    if ($(window).width() <= 992) {
        $('.sidebar .nav-link').on('click', function() {
            $('#sidebar').removeClass('active');
            $('#sidebarOverlay').removeClass('active');
            $('body').removeClass('sidebar-open');
        });
    }

    // Profile dropdown
    $('#profileDropdown').on('click', function(e) {
        e.stopPropagation();
        $(this).find('.dropdown-menu-custom').toggleClass('show');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#profileDropdown').length) {
            $('.dropdown-menu-custom').removeClass('show');
        }
    });

    // Flash message auto-dismiss
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);

    // Confirm delete
    $(document).on('click', '.btn-delete', function(e) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            e.preventDefault();
        }
    });

    // Back to top
    var $backToTop = $('#backToTop');
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
            $backToTop.addClass('show');
        } else {
            $backToTop.removeClass('show');
        }
    });

    $backToTop.on('click', function() {
        $('html, body').animate({ scrollTop: 0 }, 400, 'swing');
    });

    // Chart.js defaults
    if (typeof Chart !== 'undefined' && Chart.defaults) {
        if (Chart.defaults.font) {
            Chart.defaults.font.family = "'Inter', 'Segoe UI', system-ui, sans-serif";
            Chart.defaults.font.size = 12;
            Chart.defaults.font.color = '#64748b';
        }
        if (Chart.defaults.plugins && Chart.defaults.plugins.legend && Chart.defaults.plugins.legend.labels) {
            Chart.defaults.plugins.legend.labels.usePointStyle = true;
            Chart.defaults.plugins.legend.labels.padding = 16;
        }
        Chart.defaults.animation.duration = 800;
        Chart.defaults.animation.easing = 'easeOutQuart';
    }
});
</script>
