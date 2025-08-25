<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phase Tasks</title>
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('website/css/responsive.css') }}" />
</head>
<body>
    <div class="content_wraper F_poppins">
        <header class="project-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-12 d-flex align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-outline-primary" onclick="history.back()">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <h4 class="mb-0">{{ __('messages.tasks') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </header>
<div class="content-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
  <div>
    <h2>{{ __('messages.tasks') }}</h2>
    <p>{{ __('messages.manage_track_tasks') }}</p>
  </div>
  <div class="d-flex align-items-center gap-2 gap-md-3 flex-wrap">
    <form class="serchBar position-relative serchBar2">
      <input class="form-control" type="search" placeholder="{{ __('messages.search_task_name') }}" aria-label="Search" dir="auto">
      <span class="search_icon"><img src="{{ asset('website/images/icons/search.svg') }}" alt="search"></span>
    </form>
    <select class="form-select w-auto">
      <option>{{ __('messages.all_status') }}</option>
      <option>{{ __('messages.active') }}</option>
      <option>{{ __('messages.completed') }}</option>
    </select>
    <button class="btn orange_btn py-2" data-bs-toggle="modal" data-bs-target="#addTaskModal">
      <i class="fas fa-plus"></i>
      {{ __('messages.add_new_task') }}
    </button>
  </div>
</div>
<section class="px-md-4">
  <div class="container-fluid ">
    <div class="row  gy-4 card_wraPper">
      <!-- Card 1 -->
      <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0s">
        <div class="card h-100 B_shadow">
          <div class="card-body p-md-4">
            <div>
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-semibold black_color">{{ __('messages.pour_concrete_slab') }}</h5>
                <span class="badge badge2 fw-normal" style="font-size: 0.9em;">{{ __('messages.pending') }}</span>
              </div>
              <p class="mb-2 text-muted fw-medium  d-flex align-items-center gap-1">
                <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_861_2334)">
                    <g clip-path="url(#clip1_861_2334)">
                      <path
                        d="M3.5 0.75C3.98398 0.75 4.375 1.14102 4.375 1.625V2.5H7.875V1.625C7.875 1.14102 8.26602 0.75 8.75 0.75C9.23398 0.75 9.625 1.14102 9.625 1.625V2.5H10.9375C11.6621 2.5 12.25 3.08789 12.25 3.8125V5.125H0V3.8125C0 3.08789 0.587891 2.5 1.3125 2.5H2.625V1.625C2.625 1.14102 3.01602 0.75 3.5 0.75ZM0 6H12.25V13.4375C12.25 14.1621 11.6621 14.75 10.9375 14.75H1.3125C0.587891 14.75 0 14.1621 0 13.4375V6ZM1.75 8.1875V9.0625C1.75 9.30313 1.94687 9.5 2.1875 9.5H3.0625C3.30312 9.5 3.5 9.30313 3.5 9.0625V8.1875C3.5 7.94688 3.30312 7.75 3.0625 7.75H2.1875C1.94687 7.75 1.75 7.94688 1.75 8.1875ZM5.25 8.1875V9.0625C5.25 9.30313 5.44688 9.5 5.6875 9.5H6.5625C6.80312 9.5 7 9.30313 7 9.0625V8.1875C7 7.94688 6.80312 7.75 6.5625 7.75H5.6875C5.44688 7.75 5.25 7.94688 5.25 8.1875ZM9.1875 7.75C8.94687 7.75 8.75 7.94688 8.75 8.1875V9.0625C8.75 9.30313 8.94687 9.5 9.1875 9.5H10.0625C10.3031 9.5 10.5 9.30313 10.5 9.0625V8.1875C10.5 7.94688 10.3031 7.75 10.0625 7.75H9.1875ZM1.75 11.6875V12.5625C1.75 12.8031 1.94687 13 2.1875 13H3.0625C3.30312 13 3.5 12.8031 3.5 12.5625V11.6875C3.5 11.4469 3.30312 11.25 3.0625 11.25H2.1875C1.94687 11.25 1.75 11.4469 1.75 11.6875ZM5.6875 11.25C5.44688 11.25 5.25 11.4469 5.25 11.6875V12.5625C5.25 12.8031 5.44688 13 5.6875 13H6.5625C6.80312 13 7 12.8031 7 12.5625V11.6875C7 11.4469 6.80312 11.25 6.5625 11.25H5.6875ZM8.75 11.6875V12.5625C8.75 12.8031 8.94687 13 9.1875 13H10.0625C10.3031 13 10.5 12.8031 10.5 12.5625V11.6875C10.5 11.4469 10.3031 11.25 10.0625 11.25H9.1875C8.94687 11.25 8.75 11.4469 8.75 11.6875Z"
                        fill="#6B7280" />
                    </g>
                  </g>
                  <defs>
                    <clipPath id="clip0_861_2334">
                      <rect width="12.25" height="14" fill="white" transform="translate(0 0.75)" />
                    </clipPath>
                    <clipPath id="clip1_861_2334">
                      <path d="M0 0.75H12.25V14.75H0V0.75Z" fill="white" />
                    </clipPath>
                  </defs>
                </svg>

                {{ __("messages.due") }}: {{ __("messages.march") }} 26, 2025
              </p>
              <p class="mb-4 text-muted">{{ __('messages.pour_slab_description') }}</p>
            </div>
            <div>
              <button class="btn btn-primary w-100 py-2" onclick="openTaskDetails('{{ __('messages.pour_concrete_slab') }}', 'Pour slab for level 2, ensure curing compound is ready.', 'March 26, 2025', 'March 24, 2025', 'John Smith', 'medium', 'pending', 25)">{{ __('messages.view_details') }}</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay=".4s">
        <div class="card h-100 B_shadow">
          <div class="card-body p-md-4">
            <div>
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-semibold black_color">{{ __("messages.steel_reinforcement_inspection") }}</h5>
                <span class="badge badge4 fw-normal" style="font-size: 0.9em;">{{ __("messages.ongoing") }}</span>
              </div>
              <p class="mb-2 text-muted fw-medium  d-flex align-items-center gap-1">
                <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_861_2334)">
                    <g clip-path="url(#clip1_861_2334)">
                      <path
                        d="M3.5 0.75C3.98398 0.75 4.375 1.14102 4.375 1.625V2.5H7.875V1.625C7.875 1.14102 8.26602 0.75 8.75 0.75C9.23398 0.75 9.625 1.14102 9.625 1.625V2.5H10.9375C11.6621 2.5 12.25 3.08789 12.25 3.8125V5.125H0V3.8125C0 3.08789 0.587891 2.5 1.3125 2.5H2.625V1.625C2.625 1.14102 3.01602 0.75 3.5 0.75ZM0 6H12.25V13.4375C12.25 14.1621 11.6621 14.75 10.9375 14.75H1.3125C0.587891 14.75 0 14.1621 0 13.4375V6ZM1.75 8.1875V9.0625C1.75 9.30313 1.94687 9.5 2.1875 9.5H3.0625C3.30312 9.5 3.5 9.30313 3.5 9.0625V8.1875C3.5 7.94688 3.30312 7.75 3.0625 7.75H2.1875C1.94687 7.75 1.75 7.94688 1.75 8.1875ZM5.25 8.1875V9.0625C5.25 9.30313 5.44688 9.5 5.6875 9.5H6.5625C6.80312 9.5 7 9.30313 7 9.0625V8.1875C7 7.94688 6.80312 7.75 6.5625 7.75H5.6875C5.44688 7.75 5.25 7.94688 5.25 8.1875ZM9.1875 7.75C8.94687 7.75 8.75 7.94688 8.75 8.1875V9.0625C8.75 9.30313 8.94687 9.5 9.1875 9.5H10.0625C10.3031 9.5 10.5 9.30313 10.5 9.0625V8.1875C10.5 7.94688 10.3031 7.75 10.0625 7.75H9.1875ZM1.75 11.6875V12.5625C1.75 12.8031 1.94687 13 2.1875 13H3.0625C3.30312 13 3.5 12.8031 3.5 12.5625V11.6875C3.5 11.4469 3.30312 11.25 3.0625 11.25H2.1875C1.94687 11.25 1.75 11.4469 1.75 11.6875ZM5.6875 11.25C5.44688 11.25 5.25 11.4469 5.25 11.6875V12.5625C5.25 12.8031 5.44688 13 5.6875 13H6.5625C6.80312 13 7 12.8031 7 12.5625V11.6875C7 11.4469 6.80312 11.25 6.5625 11.25H5.6875ZM8.75 11.6875V12.5625C8.75 12.8031 8.94687 13 9.1875 13H10.0625C10.3031 13 10.5 12.8031 10.5 12.5625V11.6875C10.5 11.4469 10.3031 11.25 10.0625 11.25H9.1875C8.94687 11.25 8.75 11.4469 8.75 11.6875Z"
                        fill="#6B7280" />
                    </g>
                  </g>
                  <defs>
                    <clipPath id="clip0_861_2334">
                      <rect width="12.25" height="14" fill="white" transform="translate(0 0.75)" />
                    </clipPath>
                    <clipPath id="clip1_861_2334">
                      <path d="M0 0.75H12.25V14.75H0V0.75Z" fill="white" />
                    </clipPath>
                  </defs>
                </svg>
                {{ __("messages.due") }}: {{ __("messages.march") }} 28, 2025
              </p>
              <p class="mb-4 text-muted">{{ __('messages.inspect_steel_description') }}</p>
            </div>
            <div>
              <button class="btn btn-primary w-100 py-2" onclick="openTaskDetails('{{ __("messages.steel_reinforcement_inspection") }}', 'Inspect steel reinforcement placement before concrete pour.', 'March 28, 2025', 'March 26, 2025', 'Sarah Johnson', 'high', 'ongoing', 60)">{{ __("messages.view_details") }}</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay=".8s">
        <div class="card h-100 B_shadow">
          <div class="card-body p-md-4">
            <div>
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-semibold black_color">{{ __("messages.foundation_quality_check") }}</h5>
                <span class="badge badge1 fw-normal" style="font-size: 0.9em;">{{ __('messages.completed') }}</span>
              </div>
              <p class="mb-2 text-muted fw-medium  d-flex align-items-center gap-1">
                <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_861_2334)">
                    <g clip-path="url(#clip1_861_2334)">
                      <path
                        d="M3.5 0.75C3.98398 0.75 4.375 1.14102 4.375 1.625V2.5H7.875V1.625C7.875 1.14102 8.26602 0.75 8.75 0.75C9.23398 0.75 9.625 1.14102 9.625 1.625V2.5H10.9375C11.6621 2.5 12.25 3.08789 12.25 3.8125V5.125H0V3.8125C0 3.08789 0.587891 2.5 1.3125 2.5H2.625V1.625C2.625 1.14102 3.01602 0.75 3.5 0.75ZM0 6H12.25V13.4375C12.25 14.1621 11.6621 14.75 10.9375 14.75H1.3125C0.587891 14.75 0 14.1621 0 13.4375V6ZM1.75 8.1875V9.0625C1.75 9.30313 1.94687 9.5 2.1875 9.5H3.0625C3.30312 9.5 3.5 9.30313 3.5 9.0625V8.1875C3.5 7.94688 3.30312 7.75 3.0625 7.75H2.1875C1.94687 7.75 1.75 7.94688 1.75 8.1875ZM5.25 8.1875V9.0625C5.25 9.30313 5.44688 9.5 5.6875 9.5H6.5625C6.80312 9.5 7 9.30313 7 9.0625V8.1875C7 7.94688 6.80312 7.75 6.5625 7.75H5.6875C5.44688 7.75 5.25 7.94688 5.25 8.1875ZM9.1875 7.75C8.94687 7.75 8.75 7.94688 8.75 8.1875V9.0625C8.75 9.30313 8.94687 9.5 9.1875 9.5H10.0625C10.3031 9.5 10.5 9.30313 10.5 9.0625V8.1875C10.5 7.94688 10.3031 7.75 10.0625 7.75H9.1875ZM1.75 11.6875V12.5625C1.75 12.8031 1.94687 13 2.1875 13H3.0625C3.30312 13 3.5 12.8031 3.5 12.5625V11.6875C3.5 11.4469 3.30312 11.25 3.0625 11.25H2.1875C1.94687 11.25 1.75 11.4469 1.75 11.6875ZM5.6875 11.25C5.44688 11.25 5.25 11.4469 5.25 11.6875V12.5625C5.25 12.8031 5.44688 13 5.6875 13H6.5625C6.80312 13 7 12.8031 7 12.5625V11.6875C7 11.4469 6.80312 11.25 6.5625 11.25H5.6875ZM8.75 11.6875V12.5625C8.75 12.8031 8.94687 13 9.1875 13H10.0625C10.3031 13 10.5 12.8031 10.5 12.5625V11.6875C10.5 11.4469 10.3031 11.25 10.0625 11.25H9.1875C8.94687 11.25 8.75 11.4469 8.75 11.6875Z"
                        fill="#6B7280" />
                    </g>
                  </g>
                  <defs>
                    <clipPath id="clip0_861_2334">
                      <rect width="12.25" height="14" fill="white" transform="translate(0 0.75)" />
                    </clipPath>
                    <clipPath id="clip1_861_2334">
                      <path d="M0 0.75H12.25V14.75H0V0.75Z" fill="white" />
                    </clipPath>
                  </defs>
                </svg>

                {{ __("messages.due") }}: {{ __("messages.march") }} 22, 2025
              </p>
              <p class="mb-4 text-muted">{{ __('messages.foundation_quality_description') }}</p>
            </div>
            <div>
              <button class="btn btn-primary w-100 py-2" onclick="openTaskDetails('{{ __("messages.foundation_quality_check") }}', 'Quality assessment of foundation work completed successfully.', 'March 22, 2025', 'March 20, 2025', 'Mike Wilson', 'medium', 'completed', 100)">{{ __("messages.view_details") }}</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="1.2s">
        <div class="card h-100 B_shadow">
          <div class="card-body p-md-4">
            <div>
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-semibold black_color">{{ __("messages.electrical_installation_review") }}</h5>
                <span class="badge badge2 fw-normal" style="font-size: 0.9em;">{{ __('messages.pending') }}</span>
              </div>
              <p class="mb-2 text-muted fw-medium  d-flex align-items-center gap-1">
                <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_861_2334)">
                    <g clip-path="url(#clip1_861_2334)">
                      <path
                        d="M3.5 0.75C3.98398 0.75 4.375 1.14102 4.375 1.625V2.5H7.875V1.625C7.875 1.14102 8.26602 0.75 8.75 0.75C9.23398 0.75 9.625 1.14102 9.625 1.625V2.5H10.9375C11.6621 2.5 12.25 3.08789 12.25 3.8125V5.125H0V3.8125C0 3.08789 0.587891 2.5 1.3125 2.5H2.625V1.625C2.625 1.14102 3.01602 0.75 3.5 0.75ZM0 6H12.25V13.4375C12.25 14.1621 11.6621 14.75 10.9375 14.75H1.3125C0.587891 14.75 0 14.1621 0 13.4375V6ZM1.75 8.1875V9.0625C1.75 9.30313 1.94687 9.5 2.1875 9.5H3.0625C3.30312 9.5 3.5 9.30313 3.5 9.0625V8.1875C3.5 7.94688 3.30312 7.75 3.0625 7.75H2.1875C1.94687 7.75 1.75 7.94688 1.75 8.1875ZM5.25 8.1875V9.0625C5.25 9.30313 5.44688 9.5 5.6875 9.5H6.5625C6.80312 9.5 7 9.30313 7 9.0625V8.1875C7 7.94688 6.80312 7.75 6.5625 7.75H5.6875C5.44688 7.75 5.25 7.94688 5.25 8.1875ZM9.1875 7.75C8.94687 7.75 8.75 7.94688 8.75 8.1875V9.0625C8.75 9.30313 8.94687 9.5 9.1875 9.5H10.0625C10.3031 9.5 10.5 9.30313 10.5 9.0625V8.1875C10.5 7.94688 10.3031 7.75 10.0625 7.75H9.1875ZM1.75 11.6875V12.5625C1.75 12.8031 1.94687 13 2.1875 13H3.0625C3.30312 13 3.5 12.8031 3.5 12.5625V11.6875C3.5 11.4469 3.30312 11.25 3.0625 11.25H2.1875C1.94687 11.25 1.75 11.4469 1.75 11.6875ZM5.6875 11.25C5.44688 11.25 5.25 11.4469 5.25 11.6875V12.5625C5.25 12.8031 5.44688 13 5.6875 13H6.5625C6.80312 13 7 12.8031 7 12.5625V11.6875C7 11.4469 6.80312 11.25 6.5625 11.25H5.6875ZM8.75 11.6875V12.5625C8.75 12.8031 8.94687 13 9.1875 13H10.0625C10.3031 13 10.5 12.8031 10.5 12.5625V11.6875C10.5 11.4469 10.3031 11.25 10.0625 11.25H9.1875C8.94687 11.25 8.75 11.4469 8.75 11.6875Z"
                        fill="#6B7280" />
                    </g>
                  </g>
                  <defs>
                    <clipPath id="clip0_861_2334">
                      <rect width="12.25" height="14" fill="white" transform="translate(0 0.75)" />
                    </clipPath>
                    <clipPath id="clip1_861_2334">
                      <path d="M0 0.75H12.25V14.75H0V0.75Z" fill="white" />
                    </clipPath>
                  </defs>
                </svg>

                {{ __("messages.due") }}: {{ __("messages.march") }} 30, 2025
              </p>
              <p class="mb-4 text-muted">{{ __('messages.electrical_review_description') }}</p>
            </div>
            <div>
              <button class="btn btn-primary w-100 py-2" onclick="openTaskDetails('{{ __("messages.electrical_installation_review") }}', 'Review electrical installation compliance with building codes.', 'March 30, 2025', 'March 28, 2025', 'Lisa Brown', 'high', 'pending', 0)">{{ __("messages.view_details") }}</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 5 -->
      <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="1.6s">
        <div class="card h-100 B_shadow">
          <div class="card-body p-md-4">
            <div>
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-semibold black_color">{{ __("messages.plumbing_system_inspection") }}</h5>
                <span class="badge badge4 fw-normal" style="font-size: 0.9em;">{{ __("messages.ongoing") }}</span>
              </div>
              <p class="mb-2 text-muted fw-medium  d-flex align-items-center gap-1">
                <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_861_2334)">
                    <g clip-path="url(#clip1_861_2334)">
                      <path
                        d="M3.5 0.75C3.98398 0.75 4.375 1.14102 4.375 1.625V2.5H7.875V1.625C7.875 1.14102 8.26602 0.75 8.75 0.75C9.23398 0.75 9.625 1.14102 9.625 1.625V2.5H10.9375C11.6621 2.5 12.25 3.08789 12.25 3.8125V5.125H0V3.8125C0 3.08789 0.587891 2.5 1.3125 2.5H2.625V1.625C2.625 1.14102 3.01602 0.75 3.5 0.75ZM0 6H12.25V13.4375C12.25 14.1621 11.6621 14.75 10.9375 14.75H1.3125C0.587891 14.75 0 14.1621 0 13.4375V6ZM1.75 8.1875V9.0625C1.75 9.30313 1.94687 9.5 2.1875 9.5H3.0625C3.30312 9.5 3.5 9.30313 3.5 9.0625V8.1875C3.5 7.94688 3.30312 7.75 3.0625 7.75H2.1875C1.94687 7.75 1.75 7.94688 1.75 8.1875ZM5.25 8.1875V9.0625C5.25 9.30313 5.44688 9.5 5.6875 9.5H6.5625C6.80312 9.5 7 9.30313 7 9.0625V8.1875C7 7.94688 6.80312 7.75 6.5625 7.75H5.6875C5.44688 7.75 5.25 7.94688 5.25 8.1875ZM9.1875 7.75C8.94687 7.75 8.75 7.94688 8.75 8.1875V9.0625C8.75 9.30313 8.94687 9.5 9.1875 9.5H10.0625C10.3031 9.5 10.5 9.30313 10.5 9.0625V8.1875C10.5 7.94688 10.3031 7.75 10.0625 7.75H9.1875ZM1.75 11.6875V12.5625C1.75 12.8031 1.94687 13 2.1875 13H3.0625C3.30312 13 3.5 12.8031 3.5 12.5625V11.6875C3.5 11.4469 3.30312 11.25 3.0625 11.25H2.1875C1.94687 11.25 1.75 11.4469 1.75 11.6875ZM5.6875 11.25C5.44688 11.25 5.25 11.4469 5.25 11.6875V12.5625C5.25 12.8031 5.44688 13 5.6875 13H6.5625C6.80312 13 7 12.8031 7 12.5625V11.6875C7 11.4469 6.80312 11.25 6.5625 11.25H5.6875ZM8.75 11.6875V12.5625C8.75 12.8031 8.94687 13 9.1875 13H10.0625C10.3031 13 10.5 12.8031 10.5 12.5625V11.6875C10.5 11.4469 10.3031 11.25 10.0625 11.25H9.1875C8.94687 11.25 8.75 11.4469 8.75 11.6875Z"
                        fill="#6B7280" />
                    </g>
                  </g>
                  <defs>
                    <clipPath id="clip0_861_2334">
                      <rect width="12.25" height="14" fill="white" transform="translate(0 0.75)" />
                    </clipPath>
                    <clipPath id="clip1_861_2334">
                      <path d="M0 0.75H12.25V14.75H0V0.75Z" fill="white" />
                    </clipPath>
                  </defs>
                </svg>

                {{ __("messages.due") }}: {{ __("messages.april") }} 2, 2025
              </p>
              <p class="mb-4 text-muted">{{ __('messages.plumbing_inspection_description') }}</p>
            </div>
            <div>
              <button class="btn btn-primary w-100 py-2" onclick="openTaskDetails('{{ __("messages.plumbing_system_inspection") }}', 'Comprehensive inspection of plumbing systems and fixtures.', 'April 2, 2025', 'March 30, 2025', 'John Smith', 'medium', 'ongoing', 40)">{{ __("messages.view_details") }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@include('website.modals.add-task-modal')
@include('website.modals.drawing-modal')
@include('website.modals.task-details-modal')

<script>
function openTaskDetails(title, description, dueDate, startDate, assignedTo, priority, status, progress) {
  document.getElementById('taskDetailTitle').textContent = title;
  document.getElementById('taskDetailDescription').textContent = description;
  document.getElementById('taskDetailDueDate').textContent = dueDate;
  document.getElementById('taskDetailStartDate').textContent = startDate;
  document.getElementById('taskDetailAssignedTo').textContent = assignedTo;
  document.getElementById('taskDetailPriority').textContent = priority;
  document.getElementById('taskDetailPriority').className = `badge badge-${priority}`;
  document.getElementById('taskDetailStatus').textContent = status;
  document.getElementById('taskDetailProgressBar').style.width = progress + '%';
  document.getElementById('taskDetailProgressText').textContent = progress + '% Complete';
  
  const modal = new bootstrap.Modal(document.getElementById('taskDetailsModal'));
  modal.show();
}



// Add Task Form Handler
document.addEventListener('DOMContentLoaded', function() {
  const addTaskForm = document.getElementById('addTaskForm');
  if (addTaskForm) {
    addTaskForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      console.log('Task form submitted');
      
      const fileInput = document.getElementById('taskImages');
      
      if (fileInput.files && fileInput.files.length > 0) {
        // Store all files
        window.selectedTaskFiles = fileInput.files;
        
        // Open drawing modal with image markup config
        openDrawingModal({
          title: 'Task Image Markup',
          saveButtonText: 'Save Task',
          mode: 'image',
          onSave: function(imageData) {
            console.log('Saving task with image markup:', imageData);
            
            // Close drawing modal
            const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
            if (drawingModal) drawingModal.hide();
            
            // Close add task modal
            const addTaskModal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
            if (addTaskModal) addTaskModal.hide();
            
            alert('Task with image markup saved successfully!');
            location.reload();
          }
        });
        
        // Load images after modal is shown
        document.getElementById('drawingModal').addEventListener('shown.bs.modal', function() {
          if (window.selectedTaskFiles.length === 1) {
            loadImageToCanvas(window.selectedTaskFiles[0]);
          } else {
            loadMultipleFiles(window.selectedTaskFiles);
          }
        }, { once: true });
        
      } else {
        // Open drawing modal with blank canvas config
        openDrawingModal({
          title: 'Task Drawing',
          saveButtonText: 'Save Task',
          mode: 'blank',
          onSave: function(imageData) {
            console.log('Saving task drawing:', imageData);
            
            // Close drawing modal
            const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
            if (drawingModal) drawingModal.hide();
            
            // Close add task modal
            const addTaskModal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
            if (addTaskModal) addTaskModal.hide();
            
            alert('Task with drawing saved successfully!');
            location.reload();
          }
        });
      }
    });
  }
  
  // Search functionality
  const searchInput = document.querySelector('input[type="search"]');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const taskCards = document.querySelectorAll('.card_wraPper .col-md-6');
      
      taskCards.forEach(card => {
        const taskTitle = card.querySelector('h5').textContent.toLowerCase();
        if (taskTitle.includes(searchTerm)) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  }
  
  // Filter functionality
  const filterSelect = document.querySelector('select.form-select');
  if (filterSelect) {
    filterSelect.addEventListener('change', function() {
      const filterValue = this.value.toLowerCase();
      const taskCards = document.querySelectorAll('.card_wraPper .col-md-6');
      
      taskCards.forEach(card => {
        const taskStatus = card.querySelector('.badge').textContent.toLowerCase();
        if (filterValue === 'all status' || taskStatus.includes(filterValue)) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  }
});
</script>
<script src="{{ asset('website/js/drawing.js') }}"></script>

    </div>
    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('website/js/drawing.js') }}"></script>
</body>
</html>