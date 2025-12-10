document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarClose = document.querySelector('.sidebar-close');
    if (!menuToggle || !sidebar || !sidebarClose) return;
    menuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        document.body.classList.add('sidebar-open');
        sidebar.classList.add('mobile-open');
    });
    sidebarClose.addEventListener('click', function(e) {
        e.stopPropagation();
        document.body.classList.remove('sidebar-open');
        sidebar.classList.remove('mobile-open');
    });
    document.addEventListener('click', function(e) {
        if (document.body.classList.contains('sidebar-open') && 
            !sidebar.contains(e.target) && 
            !menuToggle.contains(e.target)) {
            document.body.classList.remove('sidebar-open');
            sidebar.classList.remove('mobile-open');
        }
    });
    const navLinks = sidebar.querySelectorAll('.sidebar-item');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            document.body.classList.remove('sidebar-open');
            sidebar.classList.remove('mobile-open');
        });
    });
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 1024) {
                document.body.classList.remove('sidebar-open');
                if (sidebar) {
                    sidebar.classList.remove('mobile-open');
                }
            }
        }, 250);
    });
});