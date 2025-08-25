<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phase Inspections</title>
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
                            <h4 class="mb-0">{{ __('messages.inspections') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    <div class="content-header">
        <h2>{{ __('messages.inspections') }}</h2>
        <p>{{ __('messages.manage_track_inspections') }}</p>
    </div>
    <div class="container-fluid ">
        {{-- <div class="row  px-md-4 wow fadeInUp" data-wow-delay="0.9s">
    <!-- Quick Stats -->
    <div class=" col-12 col-lg-6 mb-4">
      <div class="card h-100 B_shadow border-0 bglight">
        <div class="card-body p-md-4">
          <h5 class="mb-3 mb-md-4 text-muted fw-medium">{{ __('messages.quick_stats') }}</h5>
          <div class="d-flex  gap-4 flex-wrap justify-content-between  mx-md-4">
            <div class="text-center">
              <div class="fs-2 fw-bold text-success">12</div>
              <div class="small text-muted">{{ __('messages.passed') }}</div>
            </div>
            <div class="text-center">
              <div class="fs-2 fw-bold text-danger">3</div>
              <div class="small text-muted">{{ __('messages.failed') }}</div>
            </div>
            <div class="text-center">
              <div class="fs-2 fw-bold text-warning">5</div>
              <div class="small text-muted">{{ __('messages.pending') }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> --}}
        <div class="row g-4 px-md-4 wow fadeInUp" data-wow-delay="1.2s">
            <!-- Create New Inspection -->
            <div class="col-lg-6">
                <div class="card h-100 B_shadow">
                    <div class="card-body p-md-4">
                        <h5 class="mb-3 mb-md-4 fw-semibold black_color">{{ __('messages.create_new_inspection') }}</h5>
                        <button class="btn  orange_btn w-100 mb-3 mb-md-4 justify-content-center py-3" data-bs-toggle="modal"
                            data-bs-target="#createInspectionModal">
                            <i class="fas fa-plus"></i>
                            {{ __('messages.create_inspection') }}
                        </button>
                        <div
                            class="file_upload_card d-flex flex-column align-items-center justify-content-center  text-muted">
                            <svg width="28" height="37" viewBox="0 0 28 37" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_861_2044)">
                                    <g clip-path="url(#clip1_861_2044)">
                                        <path
                                            d="M14 0.5C11.0609 0.5 8.55781 2.37734 7.63672 5H5C2.51797 5 0.5 7.01797 0.5 9.5V32C0.5 34.482 2.51797 36.5 5 36.5H23C25.482 36.5 27.5 34.482 27.5 32V9.5C27.5 7.01797 25.482 5 23 5H20.3633C19.4422 2.37734 16.9391 0.5 14 0.5ZM14 5C14.5967 5 15.169 5.23705 15.591 5.65901C16.0129 6.08097 16.25 6.65326 16.25 7.25C16.25 7.84674 16.0129 8.41903 15.591 8.84099C15.169 9.26295 14.5967 9.5 14 9.5C13.4033 9.5 12.831 9.26295 12.409 8.84099C11.9871 8.41903 11.75 7.84674 11.75 7.25C11.75 6.65326 11.9871 6.08097 12.409 5.65901C12.831 5.23705 13.4033 5 14 5ZM5.5625 19.625C5.5625 19.1774 5.74029 18.7482 6.05676 18.4318C6.37322 18.1153 6.80245 17.9375 7.25 17.9375C7.69755 17.9375 8.12678 18.1153 8.44324 18.4318C8.75971 18.7482 8.9375 19.1774 8.9375 19.625C8.9375 20.0726 8.75971 20.5018 8.44324 20.8182C8.12678 21.1347 7.69755 21.3125 7.25 21.3125C6.80245 21.3125 6.37322 21.1347 6.05676 20.8182C5.74029 20.5018 5.5625 20.0726 5.5625 19.625ZM12.875 18.5H21.875C22.4937 18.5 23 19.0063 23 19.625C23 20.2437 22.4937 20.75 21.875 20.75H12.875C12.2563 20.75 11.75 20.2437 11.75 19.625C11.75 19.0063 12.2563 18.5 12.875 18.5ZM5.5625 26.375C5.5625 25.9274 5.74029 25.4982 6.05676 25.1818C6.37322 24.8653 6.80245 24.6875 7.25 24.6875C7.69755 24.6875 8.12678 24.8653 8.44324 25.1818C8.75971 25.4982 8.9375 25.9274 8.9375 26.375C8.9375 26.8226 8.75971 27.2518 8.44324 27.5682C8.12678 27.8847 7.69755 28.0625 7.25 28.0625C6.80245 28.0625 6.37322 27.8847 6.05676 27.5682C5.74029 27.2518 5.5625 26.8226 5.5625 26.375ZM11.75 26.375C11.75 25.7563 12.2563 25.25 12.875 25.25H21.875C22.4937 25.25 23 25.7563 23 26.375C23 26.9937 22.4937 27.5 21.875 27.5H12.875C12.2563 27.5 11.75 26.9937 11.75 26.375Z"
                                            fill="#9CA3AF" />
                                    </g>
                                </g>
                                <defs>
                                    <clipPath id="clip0_861_2044">
                                        <rect width="27" height="36" fill="white" transform="translate(0.5 0.5)" />
                                    </clipPath>
                                    <clipPath id="clip1_861_2044">
                                        <path d="M0.5 0.5H27.5V36.5H0.5V0.5Z" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <div class="fw-normal mb-1 mt-2">{{ __('messages.start_new_inspection') }}</div>
                            <small>{{ __('messages.click_button_above') }}</small>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Previous Inspections -->
            <div class="col-lg-6">
                <div class="card h-100 B_shadow">
                    <div class="card-body p-md-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold black_color mb-0">{{ __('messages.previous_inspections') }}</h5>
                            <select class="form-select w-auto small" style="min-width: 140px;">
                                <option>{{ __('messages.all_categories') }}</option>
                            </select>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="small text-muted">{{ __('messages.title') }}</th>
                                        <th class="small text-muted">{{ __('messages.date') }}</th>
                                        <th class="small text-muted">{{ __('messages.status') }}</th>
                                        <th class="small text-muted">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border-0">
                                            <div class="fw-semibold">{{ __('messages.foundation_check') }}</div>
                                            <div class="small text-muted">{{ __('messages.structural') }}</div>
                                        </td>
                                        <td class="border-0">{{ __('messages.jan_15_2024') }}</td>
                                        <td class="border-0">
                                            <span class="badge badge1 d-inline-flex align-items-center gap-1">
                                                <i class="fas fa-check-circle"></i> {{ __('messages.passed') }}
                                            </span>
                                        </td>
                                        <td class="border-0">
                                            <a href="#" class="text-primary  small">>{{ __('messages.view') }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-0">
                                            <div class="fw-semibold">{{ __('messages.electrical_wiring') }}</div>
                                            <div class="small text-muted">{{ __('messages.electrical') }}</div>
                                        </td>
                                        <td class="border-0">{{ __('messages.jan_12_2024') }}</td>
                                        <td class="border-0">
                                            <span class="badge badge2 text-danger d-inline-flex align-items-center gap-1">
                                                <i class="fas fa-times-circle"></i> {{ __('messages.failed') }}
                                            </span>
                                        </td>
                                        <td class="border-0">
                                            <a href="#" class="text-primary  small">>{{ __('messages.view') }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-0">
                                            <div class="fw-semibold">{{ __('messages.plumbing_system') }}</div>
                                            <div class="small text-muted">{{ __('messages.plumbing') }}</div>
                                        </td>
                                        <td class="border-0">{{ __('messages.jan_10_2024') }}</td>
                                        <td class="border-0">
                                            <span class="badge badge3  d-inline-flex align-items-center gap-1">
                                                <i class="fas fa-hourglass-half"></i> {{ __('messages.pending') }}
                                            </span>
                                        </td>
                                        <td class="border-0">
                                            <a href="#" class="text-primary  small">>{{ __('messages.view') }}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('website.modals.create-inspection-modal')
    @include('website.modals.drawing-modal')

    <script>
        // Removed duplicate form handler - it's in the modal file
        document.addEventListener('DOMContentLoaded', function() {

            // Filter functionality
            const categoryFilter = document.querySelector('select.form-select');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', function() {
                    const filterValue = this.value.toLowerCase();
                    const inspectionRows = document.querySelectorAll('tbody tr');

                    inspectionRows.forEach(row => {
                        const categoryCell = row.querySelector('td:nth-child(1) .small');
                        if (categoryCell) {
                            const category = categoryCell.textContent.toLowerCase();
                            if (filterValue === 'all categories' || category.includes(
                                    filterValue)) {
                                row.style.display = 'table-row';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                });
            }
        });

        function viewInspection(title, type, date, status) {
            alert(
                `Inspection Details:\n\nTitle: ${title}\nType: ${type}\nDate: ${date}\nStatus: ${status}\n\nDetailed inspection view would open here.`
                );
        }
    </script>
    <script src="{{ asset('website/js/drawing.js') }}"></script>

    </div>
    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('website/js/drawing.js') }}"></script>
</body>
</html>
