




// Mobile Menu JavaScript with RTL Support
document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebarNav = document.getElementById('sidebarNav');
    const sidebarClose = document.getElementById('sidebarClose');
    const mobileOverlay = document.getElementById('mobileOverlay');
    
    // Check if RTL
    const isRTL = document.documentElement.getAttribute('dir') === 'rtl';

    // Open sidebar
    mobileMenuToggle.addEventListener('click', function () {
        sidebarNav.classList.add('sidebar-open');
        mobileOverlay.classList.add('overlay-active');
        document.body.style.overflow = 'hidden';
    });

    // Close sidebar
    function closeSidebar() {
        sidebarNav.classList.remove('sidebar-open');
        mobileOverlay.classList.remove('overlay-active');
        document.body.style.overflow = '';
    }

    sidebarClose.addEventListener('click', closeSidebar);
    mobileOverlay.addEventListener('click', closeSidebar);

    // Close sidebar on window resize (if screen becomes larger than 991px)
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            closeSidebar();
        }
    });

    // Close sidebar when clicking on nav links (mobile only)
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                closeSidebar();
            }
        });
    });
    
    // Handle orientation change for mobile
    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        }, 100);
    });
});


// =================================================================================================


/*
      Jquery Wow Js
      ============================*/

if ($(".wow").length) {
    var wow = new WOW({
        boxClass: "wow", // animated element css class (default is wow)
        animateClass: "animated", // animation css class (default is animated)
        offset: 0, // distance to the element when triggering the animation (default is 0)
        mobile: false, // trigger animations on mobile devices (default is true)
        live: true, // act on asynchronously loaded content (default is true)
    });
    wow.init();
}

// Country Code Selection with RTL support
if ($("#mobile_code").length) {
    $("#mobile_code").intlTelInput({
        initialCountry: "in",
        separateDialCode: true,
        // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
    });
}
// --sign in and varifaction code -------------
document.addEventListener('DOMContentLoaded', function () {
    var sendOtpBtn = document.getElementById('sendOtpBtn');
    if (sendOtpBtn) {
        sendOtpBtn.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector('.SignIN_content').style.display = 'none';
            document.querySelector('.Verification_content').style.display = 'block';
        });
    }
});