@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Project Files')

@section('content')
<div
  class="content-header border-0 shadow-none mb-4 d-flex align-items-center justify-content-between gap-2 flex-wrap">
  <div>
    <h2>Project Files</h2>
    <p>View and access project documents and files</p>
  </div>
  <div class="gallery-filters d-flex align-items-center gap-3 flex-wrap">
    <!-- Filter Button -->
    <button class="filter-btn d-flex align-items-center border rounded-3 px-3 py-2 bg-light">
      <svg width="17" height="14" class="me-2" viewBox="0 0 17 14" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0.606331 0.715625C0.812581 0.278125 1.25008 0 1.73446 0H15.2345C15.7188 0 16.1563 0.278125 16.3626 0.715625C16.5688 1.15313 16.5063 1.66875 16.2001 2.04375L10.4845 9.02812V13C10.4845 13.3781 10.272 13.725 9.93133 13.8938C9.59071 14.0625 9.18758 14.0281 8.88446 13.8L6.88446 12.3C6.63133 12.1125 6.48446 11.8156 6.48446 11.5V9.02812L0.765706 2.04063C0.462581 1.66875 0.396956 1.15 0.606331 0.715625Z"
          fill="#4477C4" />
      </svg>
      <span class="text-black">Filter</span>
    </button>
    <!-- Sort Button -->
    <button class="sort-btn d-flex align-items-center border rounded-3 px-3 py-2 bg-light">
      <svg width="11" height="14" class="me-2" viewBox="0 0 11 14" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M4.38729 0.293945C4.77791 -0.0966797 5.41229 -0.0966797 5.80291 0.293945L9.80291 4.29395C10.0904 4.58145 10.1748 5.00957 10.0185 5.38457C9.86229 5.75957 9.49979 6.00332 9.09354 6.00332H1.09354C0.690412 6.00332 0.324787 5.75957 0.168537 5.38457C0.0122866 5.00957 0.0997865 4.58145 0.384162 4.29395L4.38416 0.293945H4.38729ZM4.38729 13.7096L0.387287 9.70957C0.0997865 9.42207 0.0154115 8.99395 0.171662 8.61895C0.327912 8.24395 0.690411 8.0002 1.09666 8.0002H9.09354C9.49666 8.0002 9.86229 8.24395 10.0185 8.61895C10.1748 8.99395 10.0873 9.42207 9.80291 9.70957L5.80291 13.7096C5.41229 14.1002 4.77791 14.1002 4.38729 13.7096Z"
          fill="#4477C4" />
      </svg>
      <span class="text-black">Sort</span>
    </button>
  </div>
</div>
<section class="px-md-4">
  <div class="container-fluid">
    <div class="row g-4">
      <div class="col-12 col-lg-3 col-md-4 col-md-6 col-12 wow fadeInUp" data-wow-delay="0s">
        <div class="card h-100 B_shadow">
          <div class="card-body d-flex align-items-center p-md-4">
            <div>
              <div class="small_tXt">Total Files</div>
              <div class="stat-value">247</div>
            </div>
            <span class="ms-auto stat-icon bg1">
              <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_861_2984)">
                  <path
                    d="M0 2C0 0.896875 0.896875 0 2 0H7V4C7 4.55312 7.44688 5 8 5H12V14C12 15.1031 11.1031 16 10 16H2C0.896875 16 0 15.1031 0 14V2ZM12 4H8V0L12 4Z"
                    fill="#4477C4" />
                </g>
                <defs>
                  <clipPath id="clip0_861_2984">
                    <path d="M0 0H12V16H0V0Z" fill="white" />
                  </clipPath>
                </defs>
              </svg>
            </span>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-3 col-md-4 col-md-6 col-12 wow fadeInUp" data-wow-delay=".4s">
        <div class="card h-100 B_shadow">
          <div class="card-body d-flex align-items-center p-md-4">
            <div>
              <div class="small_tXt">Drawings</div>
              <div class="stat-value">89</div>
            </div>
            <span class="ms-auto stat-icon bg2">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M11 3C11 3.44688 10.9031 3.87187 10.725 4.25625L12.375 7.10625C11.6344 7.89687 10.6812 8.48438 9.60938 8.78125L8 6L5.875 9.67188C6.54688 9.88437 7.25938 10 8.00313 10C10.2125 10 12.1844 8.97812 13.4688 7.375C13.8156 6.94375 14.4438 6.875 14.875 7.21875C15.3062 7.5625 15.375 8.19375 15.0312 8.625C13.3781 10.6812 10.8438 12 8 12C6.89375 12 5.83125 11.8 4.85313 11.4344L3.08437 14.4906C2.9375 14.7438 2.71875 14.95 2.45625 15.0813L0.725 15.9469C0.56875 16.025 0.384375 16.0156 0.2375 15.925C0.090625 15.8344 0 15.6719 0 15.5V13.7688C0 13.5063 0.06875 13.2469 0.203125 13.0156L2.07812 9.775C1.67813 9.425 1.30625 9.04062 0.975 8.625C0.628125 8.19375 0.7 7.56563 1.13125 7.21875C1.5625 6.87187 2.19062 6.94375 2.5375 7.375C2.71562 7.59688 2.90625 7.80625 3.10625 8.00313L5.275 4.25625C5.1 3.875 5 3.45 5 3C5 1.34375 6.34375 0 8 0C9.65625 0 11 1.34375 11 3ZM11.6562 12.3094C12.675 11.9094 13.6094 11.3469 14.4344 10.6562L15.8 13.0156C15.9313 13.2437 16.0031 13.5031 16.0031 13.7688V15.5C16.0031 15.6719 15.9125 15.8344 15.7656 15.925C15.6187 16.0156 15.4344 16.025 15.2781 15.9469L13.5469 15.0813C13.2844 14.95 13.0656 14.7438 12.9187 14.4906L11.6562 12.3094ZM8 4C8.26522 4 8.51957 3.89464 8.70711 3.70711C8.89464 3.51957 9 3.26522 9 3C9 2.73478 8.89464 2.48043 8.70711 2.29289C8.51957 2.10536 8.26522 2 8 2C7.73478 2 7.48043 2.10536 7.29289 2.29289C7.10536 2.48043 7 2.73478 7 3C7 3.26522 7.10536 3.51957 7.29289 3.70711C7.48043 3.89464 7.73478 4 8 4Z"
                  fill="#F58D2E" />
              </svg>

            </span>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-3 col-md-4 col-md-6 col-12  wow fadeInUp" data-wow-delay=".8s">
        <div class="card h-100 B_shadow">
          <div class="card-body d-flex align-items-center p-md-4">
            <div>
              <div class="small_tXt">Documents</div>
              <div class="stat-value">158</div>
            </div>
            <span class="ms-auto stat-icon bg1">
              <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M2 0C0.896875 0 0 0.896875 0 2V14C0 15.1031 0.896875 16 2 16H10C11.1031 16 12 15.1031 12 14V5H8C7.44688 5 7 4.55312 7 4V0H2ZM8 0V4H12L8 0ZM3.5 8H8.5C8.775 8 9 8.225 9 8.5C9 8.775 8.775 9 8.5 9H3.5C3.225 9 3 8.775 3 8.5C3 8.225 3.225 8 3.5 8ZM3.5 10H8.5C8.775 10 9 10.225 9 10.5C9 10.775 8.775 11 8.5 11H3.5C3.225 11 3 10.775 3 10.5C3 10.225 3.225 10 3.5 10ZM3.5 12H8.5C8.775 12 9 12.225 9 12.5C9 12.775 8.775 13 8.5 13H3.5C3.225 13 3 12.775 3 12.5C3 12.225 3.225 12 3.5 12Z"
                  fill="#4477C4" />
              </svg>
            </span>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-3 col-md-4 col-md-6 col-12  wow fadeInUp" data-wow-delay="1.2s">
        <div class="card h-100 B_shadow">
          <div class="card-body d-flex align-items-center p-md-4">
            <div>
              <div class="small_tXt">PDFs</div>
              <div class="stat-value">49</div>
            </div>
            <span class="ms-auto stat-icon bg2">
              <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M0 2C0 0.896875 0.896875 0 2 0H14C15.1031 0 16 0.896875 16 2V7.7625C15.4688 7.2875 14.7688 7 14 7H2C1.23125 7 0.53125 7.2875 0 7.7625V2ZM2 8H14C15.1031 8 16 8.89688 16 10V12C16 13.1031 15.1031 14 14 14H2C0.896875 14 0 13.1031 0 12V10C0 8.89688 0.896875 8 2 8ZM10 12C10.2652 12 10.5196 11.8946 10.7071 11.7071C10.8946 11.5196 11 11.2652 11 11C11 10.7348 10.8946 10.4804 10.7071 10.2929C10.5196 10.1054 10.2652 10 10 10C9.73478 10 9.48043 10.1054 9.29289 10.2929C9.10536 10.4804 9 10.7348 9 11C9 11.2652 9.10536 11.5196 9.29289 11.7071C9.48043 11.8946 9.73478 12 10 12ZM14 11C14 10.7348 13.8946 10.4804 13.7071 10.2929C13.5196 10.1054 13.2652 10 13 10C12.7348 10 12.4804 10.1054 12.2929 10.2929C12.1054 10.4804 12 10.7348 12 11C12 11.2652 12.1054 11.5196 12.2929 11.7071C12.4804 11.8946 12.7348 12 13 12C13.2652 12 13.5196 11.8946 13.7071 11.7071C13.8946 11.5196 14 11.2652 14 11Z"
                  fill="#F58D2E" />
              </svg>

            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="row wow fadeInUp" data-wow-delay="0.9s">
      <div class="col-lg-12 mb-4 mt-4">
        <div class="card B_shadow">
          <div class="card-body card-body py-md-4 px-0">
            <h5 class="fw-semibold  black_color px-md-4 px-2 mb-4">Recent Files</h5>
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th>File Name</th>
                    <th>Type</th>
                    <th>Upload Date</th>
                    <th>Size</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <path
                            d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z"
                            fill="#FEE2E2" />
                          <path
                            d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z"
                            stroke="#E5E7EB" />
                          <path d="M24 28H8V4H24V28Z" stroke="#E5E7EB" />
                          <g clip-path="url(#clip0_861_3078)">
                            <path
                              d="M8 10C8 8.89688 8.89688 8 10 8H15V12C15 12.5531 15.4469 13 16 13H20V17.5H13.5C12.3969 17.5 11.5 18.3969 11.5 19.5V24H10C8.89688 24 8 23.1031 8 22V10ZM20 12H16V8L20 12ZM13.5 19H14.5C15.4656 19 16.25 19.7844 16.25 20.75C16.25 21.7156 15.4656 22.5 14.5 22.5H14V23.5C14 23.775 13.775 24 13.5 24C13.225 24 13 23.775 13 23.5V22V19.5C13 19.225 13.225 19 13.5 19ZM14.5 21.5C14.9156 21.5 15.25 21.1656 15.25 20.75C15.25 20.3344 14.9156 20 14.5 20H14V21.5H14.5ZM17.5 19H18.5C19.3281 19 20 19.6719 20 20.5V22.5C20 23.3281 19.3281 24 18.5 24H17.5C17.225 24 17 23.775 17 23.5V19.5C17 19.225 17.225 19 17.5 19ZM18.5 23C18.775 23 19 22.775 19 22.5V20.5C19 20.225 18.775 20 18.5 20H18V23H18.5ZM21 19.5C21 19.225 21.225 19 21.5 19H23C23.275 19 23.5 19.225 23.5 19.5C23.5 19.775 23.275 20 23 20H22V21H23C23.275 21 23.5 21.225 23.5 21.5C23.5 21.775 23.275 22 23 22H22V23.5C22 23.775 21.775 24 21.5 24C21.225 24 21 23.775 21 23.5V21.5V19.5Z"
                              fill="#DC2626" />
                          </g>
                          <defs>
                            <clipPath id="clip0_861_3078">
                              <path d="M8 8H24V24H8V8Z" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
                        <span class="fw-medium text-black">Structural Plans v2.1.pdf</span>
                      </div>
                    </td>
                    <td>PDF</td>
                    <td>Dec 15, 2024</td>
                    <td>2.4 MB</td>
                    <td><button class="btn orange_btn  btn-sm py-2 px-3">View</button></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <path
                            d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z"
                            fill="#DBEAFE" />
                          <path
                            d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z"
                            stroke="#E5E7EB" />
                          <path d="M22 28H10V4H22V28Z" stroke="#E5E7EB" />
                          <g clip-path="url(#clip0_861_3096)">
                            <path
                              d="M12 8C10.8969 8 10 8.89688 10 10V22C10 23.1031 10.8969 24 12 24H20C21.1031 24 22 23.1031 22 22V13H18C17.4469 13 17 12.5531 17 12V8H12ZM18 8V12H22L18 8ZM13.4688 16.0344L14.3063 18.8219L15.2937 16C15.4 15.7 15.6844 15.4969 16.0031 15.4969C16.3219 15.4969 16.6063 15.6969 16.7125 16L17.7 18.8219L18.5312 16.0344C18.65 15.6375 19.0687 15.4125 19.4656 15.5312C19.8625 15.65 20.0875 16.0687 19.9688 16.4656L18.4688 21.4656C18.375 21.7781 18.0938 21.9937 17.7688 22C17.4438 22.0063 17.15 21.8062 17.0437 21.4969L16 18.5188L14.9594 21.4969C14.8531 21.8031 14.5594 22.0063 14.2344 22C13.9094 21.9937 13.625 21.7781 13.5344 21.4656L12.0344 16.4656C11.9156 16.0687 12.1406 15.65 12.5375 15.5312C12.9344 15.4125 13.3531 15.6375 13.4719 16.0344H13.4688Z"
                              fill="#2563EB" />
                          </g>
                          <defs>
                            <clipPath id="clip0_861_3096">
                              <path d="M10 8H22V24H10V8Z" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
                        <span class="fw-medium text-black">Project Specifications.docx</span>
                      </div>
                    </td>
                    <td>DOCX</td>
                    <td>Dec 14, 2024</td>
                    <td>1.2 MB</td>
                    <td><button class="btn orange_btn  btn-sm py-2 px-3">View</button></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <path
                            d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z"
                            fill="#4477C4" fill-opacity="0.1" />
                          <path
                            d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z"
                            stroke="#E5E7EB" />
                          <path d="M24 28H8V4H24V28Z" stroke="#E5E7EB" />
                          <g clip-path="url(#clip0_861_3114)">
                            <path
                              d="M19 11C19 11.4469 18.9031 11.8719 18.725 12.2562L20.375 15.1062C19.6344 15.8969 18.6812 16.4844 17.6094 16.7812L16 14L13.875 17.6719C14.5469 17.8844 15.2594 18 16.0031 18C18.2125 18 20.1844 16.9781 21.4688 15.375C21.8156 14.9437 22.4438 14.875 22.875 15.2188C23.3062 15.5625 23.375 16.1938 23.0312 16.625C21.3781 18.6812 18.8438 20 16 20C14.8938 20 13.8313 19.8 12.8531 19.4344L11.0844 22.4906C10.9375 22.7438 10.7188 22.95 10.4562 23.0813L8.725 23.9469C8.56875 24.025 8.38437 24.0156 8.2375 23.925C8.09063 23.8344 8 23.6719 8 23.5V21.7688C8 21.5063 8.06875 21.2469 8.20312 21.0156L10.0781 17.775C9.67813 17.425 9.30625 17.0406 8.975 16.625C8.62813 16.1938 8.7 15.5656 9.13125 15.2188C9.5625 14.8719 10.1906 14.9437 10.5375 15.375C10.7156 15.5969 10.9062 15.8063 11.1063 16.0031L13.275 12.2562C13.1 11.875 13 11.45 13 11C13 9.34375 14.3438 8 16 8C17.6562 8 19 9.34375 19 11ZM19.6562 20.3094C20.675 19.9094 21.6094 19.3469 22.4344 18.6562L23.8 21.0156C23.9313 21.2437 24.0031 21.5031 24.0031 21.7688V23.5C24.0031 23.6719 23.9125 23.8344 23.7656 23.925C23.6187 24.0156 23.4344 24.025 23.2781 23.9469L21.5469 23.0813C21.2844 22.95 21.0656 22.7438 20.9187 22.4906L19.6562 20.3094ZM16 12C16.2652 12 16.5196 11.8946 16.7071 11.7071C16.8946 11.5196 17 11.2652 17 11C17 10.7348 16.8946 10.4804 16.7071 10.2929C16.5196 10.1054 16.2652 10 16 10C15.7348 10 15.4804 10.1054 15.2929 10.2929C15.1054 10.4804 15 10.7348 15 11C15 11.2652 15.1054 11.5196 15.2929 11.7071C15.4804 11.8946 15.7348 12 16 12Z"
                              fill="#4477C4" />
                          </g>
                          <defs>
                            <clipPath id="clip0_861_3114">
                              <path d="M8 8H24V24H8V8Z" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
                        <span class="fw-medium text-black">Site Layout Drawing.dwg</span>
                      </div>
                    </td>
                    <td>DWG</td>
                    <td>Dec 13, 2024</td>
                    <td>5.8 MB</td>
                    <td><button class="btn orange_btn  btn-sm py-2 px-3">View</button></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <path
                            d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z"
                            fill="#DCFCE7" />
                          <path
                            d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z"
                            stroke="#E5E7EB" />
                          <path d="M22 28H10V4H22V28Z" stroke="#E5E7EB" />
                          <g clip-path="url(#clip0_861_3132)">
                            <path
                              d="M12 8C10.8969 8 10 8.89688 10 10V22C10 23.1031 10.8969 24 12 24H20C21.1031 24 22 23.1031 22 22V13H18C17.4469 13 17 12.5531 17 12V8H12ZM18 8V12H22L18 8ZM14.8656 15.8187L16 17.4406L17.1344 15.8187C17.3719 15.4781 17.8406 15.3969 18.1781 15.6344C18.5156 15.8719 18.6 16.3406 18.3625 16.6781L16.9156 18.75L18.3656 20.8188C18.6031 21.1594 18.5219 21.625 18.1812 21.8625C17.8406 22.1 17.375 22.0188 17.1375 21.6781L16 20.0562L14.8656 21.6781C14.6281 22.0188 14.1594 22.1 13.8219 21.8625C13.4844 21.625 13.4 21.1562 13.6375 20.8188L15.0844 18.75L13.6344 16.6812C13.3969 16.3406 13.4781 15.875 13.8187 15.6375C14.1594 15.4 14.625 15.4813 14.8625 15.8219L14.8656 15.8187Z"
                              fill="#16A34A" />
                          </g>
                          <defs>
                            <clipPath id="clip0_861_3132">
                              <path d="M10 8H22V24H10V8Z" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
                        <span class="fw-medium text-black">Cost Estimation.xlsx</span>
                      </div>
                    </td>
                    <td>XLSX</td>
                    <td>Dec 12, 2024</td>
                    <td>890 KB</td>
                    <td><button class="btn orange_btn  btn-sm py-2 px-3">View</button></td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <path
                            d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z"
                            fill="#F3E8FF" />
                          <path
                            d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z"
                            stroke="#E5E7EB" />
                          <path d="M24 28H8V4H24V28Z" stroke="#E5E7EB" />
                          <path d="M24 24H8V8H24V24Z" stroke="#E5E7EB" />
                          <path
                            d="M8 11C8 9.89688 8.89688 9 10 9H22C23.1031 9 24 9.89688 24 11V21C24 22.1031 23.1031 23 22 23H10C8.89688 23 8 22.1031 8 21V11ZM18.1187 14.3281C17.9781 14.1219 17.7469 14 17.5 14C17.2531 14 17.0188 14.1219 16.8813 14.3281L14.1625 18.3156L13.3344 17.2812C13.1906 17.1031 12.975 17 12.75 17C12.525 17 12.3063 17.1031 12.1656 17.2812L10.1656 19.7812C9.98438 20.0063 9.95 20.3156 10.075 20.575C10.2 20.8344 10.4625 21 10.75 21H13.75H14.75H21.25C21.5281 21 21.7844 20.8469 21.9125 20.6C22.0406 20.3531 22.025 20.0562 21.8687 19.8281L18.1187 14.3281ZM11.5 14C11.8978 14 12.2794 13.842 12.5607 13.5607C12.842 13.2794 13 12.8978 13 12.5C13 12.1022 12.842 11.7206 12.5607 11.4393C12.2794 11.158 11.8978 11 11.5 11C11.1022 11 10.7206 11.158 10.4393 11.4393C10.158 11.7206 10 12.1022 10 12.5C10 12.8978 10.158 13.2794 10.4393 13.5607C10.7206 13.842 11.1022 14 11.5 14Z"
                            fill="#9333EA" />
                        </svg>
                        <span class="fw-medium text-black">Site Progress Photo.jpg</span>
                      </div>
                    </td>
                    <td>JPG</td>
                    <td>Dec 11, 2024</td>
                    <td>3.2 MB</td>
                    <td><button class="btn orange_btn  btn-sm py-2 px-3">View</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Filter button functionality
  const filterBtn = document.querySelector('.filter-btn');
  if (filterBtn) {
    filterBtn.addEventListener('click', function() {
      alert('File Filter Options:\n\n• By File Type (PDF, DOCX, DWG, etc.)\n• By Upload Date\n• By File Size\n• By Category (Plans, Reports, etc.)\n\nAdvanced filtering would be implemented here.');
    });
  }
  
  // Sort button functionality
  const sortBtn = document.querySelector('.sort-btn');
  if (sortBtn) {
    sortBtn.addEventListener('click', function() {
      alert('Sort Options:\n\n• Name (A-Z / Z-A)\n• Date (Newest / Oldest)\n• Size (Largest / Smallest)\n• Type (Alphabetical)\n\nSorting functionality would be implemented here.');
    });
  }
  
  // View file buttons
  const viewButtons = document.querySelectorAll('.btn.orange_btn');
  viewButtons.forEach(button => {
    button.addEventListener('click', function() {
      const row = this.closest('tr');
      const fileName = row.querySelector('.fw-medium').textContent;
      const fileType = row.cells[1].textContent;
      const fileSize = row.cells[3].textContent;
      
      // Simulate file viewing
      if (fileType === 'PDF') {
        alert(`Opening PDF Viewer for: ${fileName}\n\nFile would open in:\n• Built-in PDF viewer\n• Download option available\n• Print and share options`);
      } else if (fileType === 'DWG') {
        alert(`Opening CAD Viewer for: ${fileName}\n\nFile would open in:\n• AutoCAD Web viewer\n• Zoom and pan capabilities\n• Layer management\n• Measurement tools`);
      } else if (fileType === 'JPG') {
        alert(`Opening Image Viewer for: ${fileName}\n\nFile would open in:\n• Full-screen image viewer\n• Zoom and pan\n• Download option`);
      } else {
        alert(`Opening ${fileType} file: ${fileName}\n\nFile Size: ${fileSize}\n\nFile would be downloaded or opened in appropriate application.`);
      }
    });
  });
});
</script>

@endsection