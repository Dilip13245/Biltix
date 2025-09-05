// ✨ Modern Toastr Configuration
toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: true,
    progressBar: true,
    positionClass: "toast-top-right", // move to bottom for less intrusive UX
    preventDuplicates: true,
    onclick: null,
    showDuration: 300,
    hideDuration: 800,
    timeOut: 4000,
    extendedTimeOut: 1200,
    showEasing: "easeOutCubic",
    hideEasing: "easeInCubic",
    showMethod: "slideDown",
    hideMethod: "slideUp"
};

// 🎨 Redesigned Toast Styles
const toastStyles = `
<style>
.toast-bottom-right {
    bottom: 20px;
    right: 20px;
}

.toast {
    border-radius: 10px !important;
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15) !important;
    border: none !important;
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    font-size: 15px !important;
    font-weight: 400 !important;
    min-width: 280px !important;
    max-width: 360px !important;
    padding: 14px 18px !important;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    transition: transform 0.3s ease, opacity 0.3s ease;
}

/* ✅ Subtle gradients */
.toast-success {
    background: linear-gradient(145deg, #22c55e, #16a34a) !important;
    color: #fff !important;
}

.toast-error {
    background: linear-gradient(145deg, #ef4444, #b91c1c) !important;
    color: #fff !important;
}

.toast-warning {
    background: linear-gradient(145deg, #f59e0b, #b45309) !important;
    color: #fff !important;
}

.toast-info {
    background: linear-gradient(145deg, #3b82f6, #1d4ed8) !important;
    color: #fff !important;
}

/* 📝 Typography */
.toast-title {
    font-weight: 600 !important;
    margin-bottom: 4px !important;
}

.toast-message {
    font-weight: 400 !important;
    line-height: 1.5 !important;
}

/* ❌ Close button */
.toast-close-button {
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 16px !important;
    font-weight: bold !important;
    opacity: 0.7 !important;
    transition: opacity 0.2s ease;
}
.toast-close-button:hover {
    opacity: 1 !important;
    color: #fff !important;
}

/* 📊 Progress bar */
.toast-progress {
    background: rgba(255, 255, 255, 0.5) !important;
    height: 3px !important;
    border-radius: 2px !important;
}

/* 📱 Mobile-friendly */
@media (max-width: 768px) {
    .toast-bottom-right {
        bottom: 10px;
        right: 10px;
        left: 10px;
    }
    .toast {
        width: 100% !important;
        min-width: unset !important;
        margin-bottom: 8px !important;
    }
}
</style>`;

// Inject redesigned styles
document.head.insertAdjacentHTML("beforeend", toastStyles);
