// ✨ Modern Toastr Configuration with Auto-RTL Detection
const isRtl = document.documentElement.getAttribute("dir") === "rtl";

toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: true,
    progressBar: true,
    positionClass: isRtl ? "toast-top-left" : "toast-top-right",
    preventDuplicates: true,
    onclick: null,
    showDuration: 300,
    hideDuration: 800,
    timeOut: 4000,
    extendedTimeOut: 1200,
    showEasing: "easeOutCubic",
    hideEasing: "easeInCubic",
    showMethod: "slideDown",
    hideMethod: "slideUp",
    rtl: isRtl
};

// 🎨 Redesigned + RTL Supported Toast Styles
const toastStyles = `
<style>

/* ============================
   🔵 POSITION CLASSES
=============================== */
.toast-top-right { top: 20px; right: 20px; }
.toast-top-left  { top: 20px; left: 20px; }
.toast-bottom-right { bottom: 20px; right: 20px; }
.toast-bottom-left  { bottom: 20px; left: 20px; }

/* ============================
   🔵 BASE TOAST
=============================== */
.toast {
    position: relative !important; /* needed for absolute close button */
    border-radius: 10px !important;
    box-shadow: 0 6px 24px rgba(0,0,0,0.15) !important;
    border: none !important;
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    font-size: 15px !important;
    min-width: 280px !important;
    max-width: 360px !important;
    padding: 14px 18px !important;
    padding-right: 42px !important; /* space for close button */
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    transition: transform 0.3s ease, opacity 0.3s ease;
}

/* Gradient Backgrounds */
.toast-success { background: linear-gradient(145deg,#22c55e,#16a34a) !important; color:#fff !important; }
.toast-error   { background: linear-gradient(145deg,#ef4444,#b91c1c) !important; color:#fff !important; }
.toast-warning { background: linear-gradient(145deg,#f59e0b,#b45309) !important; color:#fff !important; }
.toast-info    { background: linear-gradient(145deg,#3b82f6,#1d4ed8) !important; color:#fff !important; }

/* Typography */
.toast-title {
    font-weight: 600 !important;
    margin-bottom: 4px !important;
}
.toast-message {
    font-weight: 400 !important;
    line-height: 1.5 !important;
}

/* ============================
   ❌ FIXED CLOSE BUTTON
=============================== */
.toast-close-button {
    position: absolute !important;
    top: 10px !important;
    right: 10px !important;
    color: rgba(255,255,255,0.7) !important;
    font-size: 16px !important;
    font-weight: bold !important;
    opacity: 0.7 !important;
    transition: opacity 0.2s ease;
}
.toast-close-button:hover {
    opacity: 1 !important;
    color: #fff !important;
}

/* ============================
   📊 Progress Bar
=============================== */
.toast-progress {
    background: rgba(255,255,255,0.5) !important;
    height: 3px !important;
    border-radius: 2px !important;
}

/* ============================
   📱 MOBILE RESPONSIVE
=============================== */
@media (max-width: 768px) {
    .toast-top-right,
    .toast-top-left {
        top: 10px; left: 10px; right: 10px;
    }
    .toast-bottom-right,
    .toast-bottom-left {
        bottom: 10px; left: 10px; right: 10px;
    }
    .toast {
        width: 100% !important;
        min-width: unset !important;
        margin-bottom: 8px !important;
    }
}

/* ============================
   🌍 RTL MODE FIXES
=============================== */
body.toastr-rtl .toast {
    direction: rtl !important;
    text-align: right !important;
    padding-right: 18px !important;
    padding-left: 42px !important; /* space for close button on left */
}

body.toastr-rtl .toast-close-button {
    right: auto !important;
    left: 10px !important;
}

body.toastr-rtl .toast-progress {
    right: auto !important;
    left: 0 !important;
}

body.toastr-rtl .toast-message,
body.toastr-rtl .toast-title {
    direction: rtl !important;
    text-align: right !important;
    word-break: break-word !important;
}

</style>`;

// Inject Styles
document.head.insertAdjacentHTML("beforeend", toastStyles);

// Enable RTL CLASS
if (isRtl) {
    document.body.classList.add("toastr-rtl");
}
