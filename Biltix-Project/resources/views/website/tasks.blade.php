@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Tasks')

@section('content')
<div class="content-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
  <div>
    <h2>Tasks</h2>
    <p>Manage and track task activities</p>
  </div>
  <div class="d-flex align-items-center gap-2 gap-md-3 flex-wrap">
    <form class="serchBar position-relative serchBar2">
      <input class="form-control " type="search" placeholder="Search Task Name " aria-label="Search">
      <span class="search_icon"><img src="{{ asset('assets/images/icons/search.svg') }}" alt="search"></span>
    </form>
    <select class="form-select w-auto">
      <option>All Status</option>
      <option>Active</option>
      <option>Completed</option>
    </select>
    <button class="btn orange_btn py-2">
      <i class="fas fa-plus"></i>
      Add New Task
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
                <h5 class="fw-semibold black_color">Pour Concrete Slab</h5>
                <span class="badge badge2 fw-normal" style="font-size: 0.9em;">Pending</span>
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

                Due: March 26, 2025
              </p>
              <p class="mb-4 text-muted">Pour slab for level 2, ensure curing compound is ready.</p>
            </div>
            <div>
              <button class="btn btn-primary w-100 py-2">View Details</button>
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
                <h5 class="fw-semibold black_color">Steel Reinforcement Inspection</h5>
                <span class="badge badge4 fw-normal" style="font-size: 0.9em;">Ongoing</span>
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
                Due: March 28, 2025
              </p>
              <p class="mb-4 text-muted">Inspect steel reinforcement placement before concrete pour.</p>
            </div>
            <div>
              <button class="btn btn-primary w-100 py-2">View Details</button>
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
                <h5 class="fw-semibold black_color">Foundation Quality Check</h5>
                <span class="badge badge1 fw-normal" style="font-size: 0.9em;">Completed</span>
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

                Due: March 22, 2025
              </p>
              <p class="mb-4 text-muted">Quality assessment of foundation work completed successfully.</p>
            </div>
            <div>
              <button class="btn btn-primary w-100 py-2">View Details</button>
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
                <h5 class="fw-semibold black_color">Electrical Installation Review</h5>
                <span class="badge badge2 fw-normal" style="font-size: 0.9em;">Pending</span>
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

                Due: March 30, 2025
              </p>
              <p class="mb-4 text-muted">Review electrical installation compliance with building codes.</p>
            </div>
            <div>
              <button class="btn btn-primary w-100 py-2">View Details</button>
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
                <h5 class="fw-semibold black_color">Plumbing System Inspection</h5>
                <span class="badge badge4 fw-normal" style="font-size: 0.9em;">Ongoing</span>
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

                Due: April 2, 2025
              </p>
              <p class="mb-4 text-muted">Comprehensive inspection of plumbing systems and fixtures.</p>
            </div>
            <div>
              <button class="btn btn-primary w-100 py-2">View Details</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection