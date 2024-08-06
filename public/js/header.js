$(document).ready(function() {
    const $navLinks = $('.nav-link');

    setActiveLink();

    function setActiveLink() {
        const currentPath = window.location.pathname.replace(/\/$/, ''); // Normalize currentPath

        $navLinks.each(function() {
            const linkHref = $(this).attr('href');
            console.log(linkHref);

            if (!linkHref || linkHref === '#') {
                console.log(linkHref);
                console.warn('Link href is empty or undefined', $(this)); // Log the link element
                $(this).removeClass('active'); // Ensure empty hrefs do not get active class
                return; // Skip empty href
            }

            const linkPath = new URL(linkHref, window.location.origin).pathname.replace(/\/$/, ''); // Normalize linkPath

            console.log('Current Path:', currentPath);
            console.log('Link Path:', linkPath);

            if (currentPath === linkPath || currentPath.startsWith(linkPath + '/')) {
                console.log('Setting active for:', linkPath);
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