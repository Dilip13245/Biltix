@extends('website.layout.app')

@section('title', 'Safety Checklist')

@section('content')
<div class="content-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
      <div>
        <h2>{{ __('messages.safety_checklist') }}</h2>
        <p>{{ __('messages.ensure_safety_compliance') }}</p>
      </div>
      @can('safety_checklists', 'create')
          <button class="btn orange_btn" data-bs-toggle="modal" data-bs-target="#addSafetyChecklistModal">
            <i class="fas fa-plus {{ margin_end(2) }}"></i>
            {{ __('messages.add_checklist') }}
          </button>
      @endcan
    </div>

    <div class="CarDs-grid">
      <!-- Safety Checklist Card 1 -->
      <div class="CustOm_Card wow fadeInUp" data-wow-delay="0s">
        <div class="carD-details">
          <div class="d-flex justify-content-between align-items-start mb-2" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
            <h3>{{ __('messages.daily') }} {{ __('messages.safety_inspection') }}</h3>
            <span class="badge bg-success">{{ __('messages.completed') }}</span>
          </div>
          <p class="text-muted mb-2">{{ __('messages.march_25_2025') }}</p>
          <div class="mb-3">
            <div class="form-check" style="{{ is_rtl() ? 'text-align: right;' : '' }}">
              <input class="form-check-input" type="checkbox" checked disabled style="{{ is_rtl() ? 'float: right; margin-left: 0.5rem; margin-right: 0;' : '' }}">
              <label class="form-check-label">{{ __('messages.all_workers_helmets') }}</label>
            </div>
            <div class="form-check" style="{{ is_rtl() ? 'text-align: right;' : '' }}">
              <input class="form-check-input" type="checkbox" checked disabled style="{{ is_rtl() ? 'float: right; margin-left: 0.5rem; margin-right: 0;' : '' }}">
              <label class="form-check-label">{{ __('messages.safety_barriers_place') }}</label>
            </div>
            <div class="form-check" style="{{ is_rtl() ? 'text-align: right;' : '' }}">
              <input class="form-check-input" type="checkbox" checked disabled style="{{ is_rtl() ? 'float: right; margin-left: 0.5rem; margin-right: 0;' : '' }}">
              <label class="form-check-label">{{ __('messages.emergency_exits_clear') }}</label>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
            <span class="small text-muted">{{ __('messages.checked_by') }}: {{ __('messages.mike_wilson') }}</span>
            <button class="btn btn-sm btn-primary">{{ __('messages.view_full_list') }}</button>
          </div>
        </div>
      </div>

      <!-- Safety Checklist Card 2 -->
      <div class="CustOm_Card wow fadeInUp" data-wow-delay=".4s">
        <div class="carD-details">
          <div class="d-flex justify-content-between align-items-start mb-2" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
            <h3>{{ __('messages.equipment_safety_check') }}</h3>
            <span class="badge bg-warning">{{ __('messages.pending') }}</span>
          </div>
          <p class="text-muted mb-2">{{ __('messages.march_26_2025') }}</p>
          <div class="mb-3">
            <div class="form-check" style="{{ is_rtl() ? 'text-align: right;' : '' }}">
              <input class="form-check-input" type="checkbox" disabled style="{{ is_rtl() ? 'float: right; margin-left: 0.5rem; margin-right: 0;' : '' }}">
              <label class="form-check-label">{{ __('messages.crane_inspection') }}</label>
            </div>
            <div class="form-check" style="{{ is_rtl() ? 'text-align: right;' : '' }}">
              <input class="form-check-input" type="checkbox" disabled style="{{ is_rtl() ? 'float: right; margin-left: 0.5rem; margin-right: 0;' : '' }}">
              <label class="form-check-label">{{ __('messages.power_tools_check') }}</label>
            </div>
            <div class="form-check" style="{{ is_rtl() ? 'text-align: right;' : '' }}">
              <input class="form-check-input" type="checkbox" disabled style="{{ is_rtl() ? 'float: right; margin-left: 0.5rem; margin-right: 0;' : '' }}">
              <label class="form-check-label">{{ __('messages.scaffolding_stability') }}</label>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
            <span class="small text-muted">{{ __('messages.due') }}: {{ __('messages.tomorrow') }}</span>
            <button class="btn btn-sm btn-primary">{{ __('messages.start_check') }}</button>
          </div>
        </div>
      </div>

      <!-- Safety Checklist Card 3 -->
      <div class="CustOm_Card wow fadeInUp" data-wow-delay=".8s">
        <div class="carD-details">
          <div class="d-flex justify-content-between align-items-start mb-2" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
            <h3>{{ __('messages.weekly_safety_meeting') }}</h3>
            <span class="badge bg-info">{{ __('messages.scheduled') }}</span>
          </div>
          <p class="text-muted mb-2">{{ __('messages.march_27_2025') }} - 9:00 AM</p>
          <div class="mb-3">
            <p class="text-muted">{{ __('messages.topics_to_cover') }}:</p>
            <ul class="text-muted" style="{{ is_rtl() ? 'text-align: right; padding-right: 1.5rem; padding-left: 0;' : '' }}">
              <li>{{ __('messages.new_safety_protocols') }}</li>
              <li>{{ __('messages.incident_reporting') }}</li>
              <li>{{ __('messages.equipment_updates') }}</li>
            </ul>
          </div>
          <div class="d-flex justify-content-between align-items-center" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
            <span class="small text-muted">{{ __('messages.conference_room_a') }}</span>
            <button class="btn btn-sm btn-primary">{{ __('messages.view_agenda') }}</button>
          </div>
        </div>
      </div>
</div>

@include('website.modals.add-safety-checklist-modal')

<script>
// Add Safety Checklist Form Handler
document.addEventListener('DOMContentLoaded', function() {
  const addSafetyChecklistForm = document.getElementById('addSafetyChecklistForm');
  if (addSafetyChecklistForm) {
    addSafetyChecklistForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Show loading state
      const submitBtn = document.querySelector('#addSafetyChecklistModal .btn.orange_btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __("messages.creating") }}...';
      submitBtn.disabled = true;
      
      // Simulate checklist creation
      setTimeout(() => {
        alert('{{ __("messages.safety_checklist_created_successfully") }}');
        bootstrap.Modal.getInstance(document.getElementById('addSafetyChecklistModal')).hide();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        addSafetyChecklistForm.reset();
        
        // Reset safety items to default
        document.getElementById('safetyItems').innerHTML = `
          <div class="input-group mb-2">
            <input type="text" class="form-control" name="items[]" placeholder="{{ __('messages.safety_item_placeholder') }}">
            <button class="btn btn-outline-danger" type="button" onclick="removeItem(this)">
              <i class="fas fa-times"></i>
            </button>
          </div>
        `;
        
        location.reload();
      }, 2000);
    });
  }
  
  // Make checklist buttons functional
  const viewButtons = document.querySelectorAll('.btn-primary');
  viewButtons.forEach(button => {
    button.addEventListener('click', function() {
      const card = this.closest('.CustOm_Card');
      const title = card.querySelector('h3').textContent;
      const status = card.querySelector('.badge').textContent;
      const date = card.querySelector('.text-muted').textContent;
      
      if (this.textContent.includes('{{ __("messages.view_full_list") }}')) {
        alert(`${title}\n\n{{ __("messages.status") }}: ${status}\n{{ __("messages.date") }}: ${date}\n\n{{ __("messages.full_checklist_details") }}`);
      } else if (this.textContent.includes('{{ __("messages.start_check") }}')) {
        alert(`{{ __("messages.starting") }} ${title}\n\n{{ __("messages.interactive_checklist_info") }}`);
      } else if (this.textContent.includes('{{ __("messages.view_agenda") }}')) {
        alert(`${title} {{ __("messages.agenda") }}\n\n{{ __("messages.meeting_details_info") }}`);
      }
    });
  });
});
</script>

@endsection