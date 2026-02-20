(function () {
    var STORAGE_KEY = 'agbc_sidebar_collapsed';
    var PUSHMENU_SELECTOR = '[data-widget="pushmenu"]';

    function setState(isCollapsed) {
        try {
            localStorage.setItem(STORAGE_KEY, isCollapsed ? '1' : '0');
        } catch (e) {
            // ignore localStorage failures
        }
    }

    function getState() {
        try {
            return localStorage.getItem(STORAGE_KEY);
        } catch (e) {
            return null;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var body = document.body;
        var savedState = getState();
        var pushmenuBtn = document.querySelector(PUSHMENU_SELECTOR);

        // Restore using real toggle when possible so layout recalculates correctly.
        if (pushmenuBtn) {
            if (savedState === '1' && !body.classList.contains('sidebar-collapse')) {
                pushmenuBtn.click();
            }

            if (savedState === '0' && body.classList.contains('sidebar-collapse')) {
                pushmenuBtn.click();
            }
        } else {
            if (savedState === '1') {
                body.classList.add('sidebar-collapse');
            } else if (savedState === '0') {
                body.classList.remove('sidebar-collapse');
            }
        }

        if (window.jQuery) {
            window.jQuery(document)
                .on('collapsed.lte.pushmenu', PUSHMENU_SELECTOR, function () {
                    setState(true);
                })
                .on('shown.lte.pushmenu', PUSHMENU_SELECTOR, function () {
                    setState(false);
                });
            return;
        }

        // Fallback if jQuery is not available.
        document.addEventListener('click', function () {
            setTimeout(function () {
                setState(body.classList.contains('sidebar-collapse'));
            }, 10);
        });
    });
})();
