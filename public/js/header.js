$(document).ready(function() {

    setActiveLink();

    function setActiveLink() {
        const $navLinks = $('.nav .nav-link'); 

        $navLinks.each(function() {
            const linkHref = $(this).attr('href');

            if (!linkHref || linkHref === '#') {
                $(this).removeClass('active');
                return;
            }
        
            const linkPath = new URL(linkHref, window.location.origin).pathname.replace(/\/$/, '');
            const currentPath = window.location.pathname.replace(/\/$/, '');
            
            if (currentPath === linkPath || currentPath.startsWith(linkPath + '/')) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });  
    }
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    var flashMessages = $('.flash-messages .alert');
    
    if (flashMessages.length > 0) {
        setTimeout(function() {
            flashMessages.hide();
        }, 4000);
    }
});