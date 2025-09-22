<div class="modal fade" id="snagDetailsModal" tabindex="-1" aria-labelledby="snagDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content B_shadow">
            <div class="modal-header border-0 pb-0">
                <style>
                    #snagDetailsModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #snagDetailsModal .modal-header {
                        position: relative !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h4 class="modal-title fw-semibold black_color" id="snagDetailsModalLabel">
                            {{ __('messages.snag_details') }}<i class="fas fa-exclamation-triangle orange_color ms-2"></i>
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                @else
                    <h4 class="modal-title fw-semibold black_color" id="snagDetailsModalLabel">
                        <i class="fas fa-exclamation-triangle orange_color me-2"></i>{{ __('messages.snag_details') }}
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            <div class="modal-body px-4 pb-4">
                <div id="snagDetailsContent">
                    <div class="text-center py-5">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-3 text-muted">{{ __('messages.loading') }}...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>