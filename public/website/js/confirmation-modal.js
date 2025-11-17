// Global Confirmation Modal
class ConfirmationModal {
    constructor() {
        this.createModal();
    }

    createModal() {
        const isRtl = document.documentElement.dir === 'rtl';
        const modalHtml = `
            <div class="modal fade" id="confirmationModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <style>
                                #confirmationModal .modal-header .btn-close {
                                    position: static !important;
                                    right: auto !important;
                                    top: auto !important;
                                    margin: 0 !important;
                                }

                                #confirmationModal .modal-header {
                                    position: relative !important;
                                }
                            </style>
                            ${isRtl ? `
                                <div class="d-flex justify-content-between align-items-center w-100" style="direction: rtl;">
                                    <h5 class="modal-title" id="confirmationTitle">Confirm Action</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                            ` : `
                                <h5 class="modal-title" id="confirmationTitle">Confirm Action</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            `}
                        </div>
                        <div class="modal-body text-center py-4">
                            <div class="mb-3">
                                <i id="confirmationIcon" class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <p id="confirmationMessage" class="mb-0" style="${isRtl ? 'text-align: right;' : ''}">Are you sure you want to proceed?</p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="confirmationCancel">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmationConfirm">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        if (!document.getElementById('confirmationModal')) {
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }
    }

    show(options = {}) {
        const {
            title = 'Confirm Action',
            message = 'Are you sure you want to proceed?',
            icon = 'fas fa-exclamation-triangle text-warning',
            confirmText = 'Confirm',
            cancelText = 'Cancel',
            confirmClass = 'btn-danger',
            onConfirm = () => { },
            onCancel = () => { }
        } = options;

        document.getElementById('confirmationTitle').textContent = title;
        document.getElementById('confirmationMessage').textContent = message;
        document.getElementById('confirmationIcon').className = icon;
        document.getElementById('confirmationConfirm').textContent = confirmText;
        document.getElementById('confirmationCancel').textContent = cancelText;
        document.getElementById('confirmationConfirm').className = `btn ${confirmClass}`;

        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));

        document.getElementById('confirmationConfirm').onclick = () => {
            modal.hide();
            onConfirm();
        };

        document.getElementById('confirmationCancel').onclick = () => {
            modal.hide();
            onCancel();
        };

        modal.show();
    }
}

// Global instance
window.confirmationModal = new ConfirmationModal();