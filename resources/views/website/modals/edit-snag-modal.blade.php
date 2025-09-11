<!-- Edit Snag Modal -->
<div class="modal fade" id="editSnagModal" tabindex="-1" aria-labelledby="editSnagModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="@if(app()->getLocale() == 'ar') flex-direction: row-reverse; @endif">
                @if(app()->getLocale() == 'ar')
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="editSnagModalLabel">{{ __('messages.edit_snag') }}</h5>
                @else
                    <h5 class="modal-title" id="editSnagModalLabel">{{ __('messages.edit_snag') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            <div class="modal-body">
                <form id="editSnagForm">
                    <input type="hidden" id="editSnagId" name="snag_id">
                    
                    <!-- Issue Type -->
                    <div class="mb-3">
                        <label for="editIssueType" class="form-label fw-medium">{{ __('messages.type_of_issue') }}</label>
                        <input type="text" class="form-control" id="editIssueType" name="issue_type" readonly>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="editDescription" class="form-label fw-medium">{{ __('messages.description') }}</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3" 
                                placeholder="{{ __('messages.provide_detailed_description') }}" required></textarea>
                    </div>

                    <!-- Location -->
                    <div class="mb-3">
                        <label for="editLocation" class="form-label fw-medium">{{ __('messages.location') }}</label>
                        <input type="text" class="form-control" id="editLocation" name="location" readonly>
                    </div>

                    <!-- Priority -->
                    <div class="mb-3">
                        <label for="editPriority" class="form-label fw-medium">{{ __('messages.priority') }}</label>
                        <select class="form-select" id="editPriority" name="priority" required>
                            <option value="low">{{ __('messages.low') }}</option>
                            <option value="medium" selected>{{ __('messages.medium') }}</option>
                            <option value="high">{{ __('messages.high') }}</option>
                            <option value="urgent">{{ __('messages.urgent') }}</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="editStatus" class="form-label fw-medium">{{ __('messages.status') }}</label>
                        <select class="form-select" id="editStatus" name="status" required>
                            <option value="open">{{ __('messages.open') }}</option>
                            <option value="assigned">{{ __('messages.assigned') }}</option>
                            <option value="in_progress">{{ __('messages.in_progress') }}</option>
                            <option value="resolved">{{ __('messages.resolved') }}</option>
                            <option value="closed">{{ __('messages.closed') }}</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="@if(app()->getLocale() == 'ar') flex-direction: row-reverse; @endif">
                @if(app()->getLocale() == 'ar')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" form="editSnagForm" class="btn orange_btn">{{ __('messages.update_snag') }}</button>
                @else
                    <button type="submit" form="editSnagForm" class="btn orange_btn">{{ __('messages.update_snag') }}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                @endif
            </div>
        </div>
    </div>
</div>