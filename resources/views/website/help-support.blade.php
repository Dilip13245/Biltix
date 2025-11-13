@extends('website.layout.app')

@section('title', __('messages.help_support'))

@section('content')
    <div class="content-header border-0 shadow-none mb-4">
        <h2>{{ __('messages.help_support') }}</h2>
        <p>{{ __('messages.help_support_description') }}</p>
    </div>
    <section class="px-md-4">
        <div class="container-fluid">
            <div class="row">
                <!-- Help & Support Section -->
                <div class="col-12 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card B_shadow">
                        <div class="border-bottom pb-3 px-md-4 px-2 py-3 py-md-4">
                            <h5 class="fw-semibold black_color mb-3">
                                <svg width="18" height="19" class="{{ margin_end(1) }}" viewBox="0 0 18 19"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 1.9375C4.96055 1.9375 1.6875 5.21055 1.6875 9.25V10.6562C1.6875 11.1238 1.31133 11.5 0.84375 11.5C0.376172 11.5 0 11.1238 0 10.6562V9.25C0 4.27891 4.02891 0.25 9 0.25C13.9711 0.25 18 4.27891 18 9.25V14.316C18 16.0246 16.6148 17.4098 14.9027 17.4098L11.025 17.4062C10.7332 17.909 10.1883 18.25 9.5625 18.25H8.4375C7.50586 18.25 6.75 17.4941 6.75 16.5625C6.75 15.6309 7.50586 14.875 8.4375 14.875H9.5625C10.1883 14.875 10.7332 15.216 11.025 15.7188L14.9062 15.7223C15.6832 15.7223 16.3125 15.093 16.3125 14.316V9.25C16.3125 5.21055 13.0395 1.9375 9 1.9375ZM5.0625 7.5625H5.625C6.24727 7.5625 6.75 8.06523 6.75 8.6875V12.625C6.75 13.2473 6.24727 13.75 5.625 13.75H5.0625C3.82148 13.75 2.8125 12.741 2.8125 11.5V9.8125C2.8125 8.57148 3.82148 7.5625 5.0625 7.5625ZM12.9375 7.5625C14.1785 7.5625 15.1875 8.57148 15.1875 9.8125V11.5C15.1875 12.741 14.1785 13.75 12.9375 13.75H12.375C11.7527 13.75 11.25 13.2473 11.25 12.625V8.6875C11.25 8.06523 11.7527 7.5625 12.375 7.5625H12.9375Z"
                                        fill="#4477C4" />
                                </svg>
                                {{ __('messages.help_support') }}
                            </h5>
                        </div>
                        <div class="card-body p-md-4">
                            <form id="supportForm" novalidate>
                                @csrf
                                <div class="mb-3">
                                    <label for="fullName"
                                        class="form-label fw-medium">{{ __('messages.full_name') }}</label>
                                    <input type="text" class="form-control Input_control" id="fullName" name="full_name"
                                        style="{{ is_rtl() ? 'text-align: right;' : '' }}">
                                </div>

                                <div class="mb-3">
                                    <label for="email"
                                        class="form-label fw-medium">{{ __('messages.email_address') }}</label>
                                    <input type="email" class="form-control Input_control" id="email" name="email"
                                        placeholder="{{ __('messages.enter_email_address') }}"
                                        style="{{ is_rtl() ? 'text-align: right;' : '' }}">
                                </div>

                                <div class="mb-4">
                                    <label for="message" class="form-label fw-medium">{{ __('messages.message') }}</label>
                                    <textarea class="form-control Input_control" id="message" name="message" rows="4"
                                        placeholder="{{ __('messages.describe_issue_detail') }}" style="{{ is_rtl() ? 'text-align: right;' : '' }}"></textarea>
                                </div>

                                <button type="submit" class="btn orange_btn w-100" id="supportBtn">
                                    <i
                                        class="fas fa-paper-plane {{ margin_end(2) }}"></i>{{ __('messages.submit_request') }}
                                </button>
                            </form>

                            {{-- <div class="quick-help-links mt-4">
              <h6 class="fw-bold mb-3">{{ __('messages.quick_help') }}</h6>
              <div class="d-flex flex-column gap-2">
                <a href="#" onclick="showFAQ()" class="d-flex align-items-center text-decoration-none p-2 rounded hover-bg-light">
                  <i class="fas fa-question-circle text-primary {{ margin_end(2) }}"></i>
                  {{ __('messages.frequently_asked_questions') }}
                </a>
                <a href="#" onclick="showDocumentation()" class="d-flex align-items-center text-decoration-none p-2 rounded hover-bg-light">
                  <i class="fas fa-file-alt text-success {{ margin_end(2) }}"></i>
                  {{ __('messages.user_documentation') }}
                </a>
                <a href="#" onclick="showTutorials()" class="d-flex align-items-center text-decoration-none p-2 rounded hover-bg-light">
                  <i class="fas fa-video text-warning {{ margin_end(2) }}"></i>
                  {{ __('messages.video_tutorials') }}
                </a>
              </div>
            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
            transition: background-color 0.2s ease;
        }

        .quick-help-links a {
            color: #495057;
            border: 1px solid transparent;
        }

        .quick-help-links a:hover {
            border-color: #dee2e6;
            color: #212529;
        }

        /* RTL specific styles */
        [dir="rtl"] .quick-help-links a {
            text-align: right;
        }

        [dir="rtl"] .form-control {
            text-align: right;
        }

        [dir="rtl"] .form-label {
            text-align: right;
        }
    </style>

    <script>
        // Validation function
        function validateSupportForm() {
            const form = document.getElementById('supportForm');
            if (!form) return false;
            
            let isValid = true;
            
            // Clear previous errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            
            // Validate full name
            const fullName = form.querySelector('#fullName');
            if (!fullName.value.trim()) {
                showFieldError(fullName, '{{ __('messages.full_name') }} is required');
                isValid = false;
            }
            
            // Validate email
            const email = form.querySelector('#email');
            if (!email.value.trim()) {
                showFieldError(email, '{{ __('messages.email_address') }} is required');
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                showFieldError(email, 'Please enter a valid email address');
                isValid = false;
            }
            
            // Validate message
            const message = form.querySelector('#message');
            if (!message.value.trim()) {
                showFieldError(message, '{{ __('messages.message') }} is required');
                isValid = false;
            }
            
            if (!isValid) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('{{ __('messages.please_fill_required_fields') }}');
                } else {
                    alert('{{ __('messages.please_fill_required_fields') }}');
                }
            }
            
            return isValid;
        }

        function showFieldError(field, message) {
            field.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = message;
            field.parentElement.appendChild(errorDiv);
        }

        // Support Form Handler
        document.addEventListener('DOMContentLoaded', function() {
            const supportForm = document.getElementById('supportForm');
            if (supportForm) {
                supportForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Validate form first
                    if (!validateSupportForm()) {
                        return false;
                    }

                    // Show loading state
                    const submitBtn = document.getElementById('supportBtn');
                    if (submitBtn.disabled) return;
                    const originalText = submitBtn.innerHTML;
                    const loadingIcon = '{{ is_rtl() ? 'margin_start(2)' : 'margin_end(2)' }}';
                    submitBtn.innerHTML =
                        `<i class="fas fa-spinner fa-spin ${loadingIcon}"></i>{{ __('messages.sending') }}...`;
                    submitBtn.disabled = true;

                    try {
                        // Get form data
                        const formData = new FormData(supportForm);
                        const data = {
                            full_name: formData.get('full_name'),
                            email: formData.get('email'),
                            message: formData.get('message')
                        };

                        // Submit via API
                        const response = await api.submitHelpSupport(data);

                        if (response.code === 200) {
                            // Success
                            if (typeof toastr !== 'undefined') {
                                toastr.success(response.message ||
                                    '{{ __('messages.support_request_submitted') }}');
                            } else {
                                alert(response.message ||
                                    '{{ __('messages.support_request_submitted') }}');
                            }
                            supportForm.reset();
                        } else {
                            // Error
                            if (typeof toastr !== 'undefined') {
                                toastr.error(response.message ||
                                    '{{ __('messages.failed_to_submit_request') }}');
                            } else {
                                alert(response.message ||
                                    '{{ __('messages.failed_to_submit_request') }}');
                            }
                        }
                    } catch (error) {
                        console.error('Help support submission error:', error);
                        if (typeof toastr !== 'undefined') {
                            toastr.error('{{ __('messages.failed_to_submit_request') }}');
                        } else {
                            alert('{{ __('messages.failed_to_submit_request') }}');
                        }
                    } finally {
                        // Reset button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }
        });

        function showFAQ() {
            if (typeof toastr !== 'undefined') {
                toastr.info('{{ __('messages.faq_section_info') }}');
            } else {
                alert('{{ __('messages.faq_section_info') }}');
            }
        }

        function showDocumentation() {
            if (typeof toastr !== 'undefined') {
                toastr.info('{{ __('messages.documentation_section_info') }}');
            } else {
                alert('{{ __('messages.documentation_section_info') }}');
            }
        }

        function showTutorials() {
            if (typeof toastr !== 'undefined') {
                toastr.info('{{ __('messages.tutorials_section_info') }}');
            } else {
                alert('{{ __('messages.tutorials_section_info') }}');
            }
        }
    </script>

@endsection
