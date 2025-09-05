// Simple Toastr Configuration
const isRtl = document.documentElement.dir === 'rtl';

toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": isRtl ? "toast-top-left" : "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "300",
    "timeOut": "4000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
    "tapToDismiss": true,
    "escapeHtml": false,
    "closeHtml": isRtl ? '<button type="button" style="float: left;">&times;</button>' : '<button type="button">&times;</button>',
    "iconClasses": {
        error: 'toast-error',
        info: 'toast-info',
        success: 'toast-success',
        warning: 'toast-warning'
    },
    "rtl": isRtl
};