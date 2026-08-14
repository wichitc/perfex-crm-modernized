/**
 * HRM Sidebar - Highlight active submenu item and keep parent expanded
 * Runs with delay to ensure DOM ready and after other scripts
 */
(function() {
    if (typeof jQuery === 'undefined') return;
    function normalizePath(p) {
        p = (p || '').replace(/\/index\.php/, '').replace(/\/$/, '') || '/';
        return p;
    }
    function runHrmSidebarActive() {
        var path = (window.location.pathname || '') + (window.location.search || '');
        var pathNorm = normalizePath(path);
        if (pathNorm.indexOf('hrm') === -1) return;

        var $links = jQuery('#sidebar-menu a[href*="hrm"]').filter(function() {
            var h = jQuery(this).attr('href') || '';
            return h !== '#' && h.indexOf('#') !== 0;
        });
        var $active = null;
        var bestMatchLen = 0;

        $links.each(function() {
            var href = jQuery(this).attr('href');
            if (!href || href === '#') return;
            var hrefPath = href;
            if (href.indexOf('http') === 0) {
                var a = document.createElement('a');
                a.href = href;
                hrefPath = (a.pathname || '') + (a.search || '');
            }
            hrefPath = normalizePath(hrefPath);
            if (pathNorm.indexOf(hrefPath) === 0 && hrefPath.length > bestMatchLen) {
                bestMatchLen = hrefPath.length;
                $active = jQuery(this);
            }
        });

        if ($active && $active.length) {
            $active.closest('li').addClass('active');
            var $parent = $active.closest('ul').closest('li');
            if ($parent.length) {
                $parent.addClass('active');
                $parent.find('> a').removeClass('collapsed').attr('aria-expanded', 'true');
                $parent.find('> ul').addClass('in').addClass('collapse').attr('aria-expanded', 'true').css({ display: 'block', height: 'auto' });
            }
        }
    }
    jQuery(document).ready(function() {
        runHrmSidebarActive();
        setTimeout(runHrmSidebarActive, 100);
        setTimeout(runHrmSidebarActive, 500);
    });
})();
