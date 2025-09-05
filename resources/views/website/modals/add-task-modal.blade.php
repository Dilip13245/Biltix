<!-- {{ __("messages.add_task") }} Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
        <h5 class="modal-title" id="addTaskModalLabel">
          <i class="fas fa-plus {{ margin_end(2) }}"></i>{{ __("messages.add_new_task") }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addTaskForm">
          @csrf
          <div class="mb-3">
            <label for="taskName" class="form-label fw-medium">{{ __("messages.task_name") }}</label>
            <input type="text" class="form-control Input_control" id="taskName" name="task_name" required
              placeholder="{{ __("messages.enter_task_name") }}">
          </div>

          <div class="mb-3">
            <label for="taskDescription" class="form-label fw-medium">{{ __("messages.task_description") }} <span class="text-muted">({{ __("messages.optional") }})</span></label>
            <textarea class="form-control Input_control" id="taskDescription" name="description" rows="3"
              placeholder="{{ __("messages.brief_task_description") }}"></textarea>
          </div>

          <div class="mb-3">
            <label for="dueDate" class="form-label fw-medium">{{ __("messages.due_date") }}</label>
            @include('website.includes.date-picker', [
              'id' => 'dueDate',
              'name' => 'due_date',
              'placeholder' => __('messages.select_due_date'),
              'minDate' => date('Y-m-d'),
              'required' => true
            ])
          </div>

          <div class="mb-4">
            <label for="taskImages" class="form-label fw-medium">{{ __("messages.upload_images") }} <span class="text-muted">({{ __("messages.optional") }})</span></label>
            <input type="file" class="form-control Input_control" id="taskImages" name="images[]" 
              accept="image/*" multiple>
            <div class="form-text">{{ __("messages.upload_task_images") }}</div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("messages.cancel") }}</button>
        <button type="submit" form="addTaskForm" class="btn orange_btn">
          {{ __("messages.next") }} <i class="fas fa-arrow-right ms-2"></i>
        </button>
      </div>
    </div>
  </div>
</div>

