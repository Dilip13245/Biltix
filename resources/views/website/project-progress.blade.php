@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Project Progress')

@section('content')
<div class="px-md-4">
  <div class="container-fluid">
            <div class="row g-4">
                <!-- {{ __("messages.project_completion") }} Card -->
                <div class="col-12 col-md-4 wow fadeInUp" data-wow-delay="0s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body p-md-4 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center mb-3">
                                <span class="stat-icon bg1 ms-0">
                                    <svg width="20" height="19" viewBox="0 0 20 19" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.5 2C2.5 1.30859 1.94141 0.75 1.25 0.75C0.558594 0.75 0 1.30859 0 2V15.125C0 16.8516 1.39844 18.25 3.125 18.25H18.75C19.4414 18.25 20 17.6914 20 17C20 16.3086 19.4414 15.75 18.75 15.75H3.125C2.78125 15.75 2.5 15.4688 2.5 15.125V2ZM18.3828 5.38281C18.8711 4.89453 18.8711 4.10156 18.3828 3.61328C17.8945 3.125 17.1016 3.125 16.6133 3.61328L12.5 7.73047L10.2578 5.48828C9.76953 5 8.97656 5 8.48828 5.48828L4.11328 9.86328C3.625 10.3516 3.625 11.1445 4.11328 11.6328C4.60156 12.1211 5.39453 12.1211 5.88281 11.6328L9.375 8.14453L11.6172 10.3867C12.1055 10.875 12.8984 10.875 13.3867 10.3867L18.3867 5.38672L18.3828 5.38281Z"
                                            fill="#4477C4" />
                                    </svg>
                                </span>
                                <div class="{{ margin_start(3) }}">
                                    <span class="fs-3 fw-bold">67%</span>
                                </div>
                            </div>
                            <div class="mb-2 text-muted fw-medium">{{ __("messages.project_completion") }}</div>
                            <div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 67%; background: linear-gradient(90deg, #4477C4 0%, #f59e42 100%);"
                                        aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- {{ __("messages.active_workers") }} Card -->
                <div class="col-12 col-md-4 wow fadeInUp" data-wow-delay=".4s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body p-md-4 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center mb-3">
                                <span class="stat-icon bg2 ms-0"><svg width="25" height="21" viewBox="0 0 25 21"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5.625 0.5C6.4538 0.5 7.24866 0.82924 7.83471 1.41529C8.42076 2.00134 8.75 2.7962 8.75 3.625C8.75 4.4538 8.42076 5.24866 7.83471 5.83471C7.24866 6.42076 6.4538 6.75 5.625 6.75C4.7962 6.75 4.00134 6.42076 3.41529 5.83471C2.82924 5.24866 2.5 4.4538 2.5 3.625C2.5 2.7962 2.82924 2.00134 3.41529 1.41529C4.00134 0.82924 4.7962 0.5 5.625 0.5ZM20 0.5C20.8288 0.5 21.6237 0.82924 22.2097 1.41529C22.7958 2.00134 23.125 2.7962 23.125 3.625C23.125 4.4538 22.7958 5.24866 22.2097 5.83471C21.6237 6.42076 20.8288 6.75 20 6.75C19.1712 6.75 18.3763 6.42076 17.7903 5.83471C17.2042 5.24866 16.875 4.4538 16.875 3.625C16.875 2.7962 17.2042 2.00134 17.7903 1.41529C18.3763 0.82924 19.1712 0.5 20 0.5ZM0 12.168C0 9.86719 1.86719 8 4.16797 8H5.83594C6.45703 8 7.04688 8.13672 7.57812 8.37891C7.52734 8.66016 7.50391 8.95312 7.50391 9.25C7.50391 10.7422 8.16016 12.082 9.19531 13C9.1875 13 9.17969 13 9.16797 13H0.832031C0.375 13 0 12.625 0 12.168ZM15.832 13C15.8242 13 15.8164 13 15.8047 13C16.8438 12.082 17.4961 10.7422 17.4961 9.25C17.4961 8.95312 17.4688 8.66406 17.4219 8.37891C17.9531 8.13281 18.543 8 19.1641 8H20.832C23.1328 8 25 9.86719 25 12.168C25 12.6289 24.625 13 24.168 13H15.832ZM8.75 9.25C8.75 8.25544 9.14509 7.30161 9.84835 6.59835C10.5516 5.89509 11.5054 5.5 12.5 5.5C13.4946 5.5 14.4484 5.89509 15.1517 6.59835C15.8549 7.30161 16.25 8.25544 16.25 9.25C16.25 10.2446 15.8549 11.1984 15.1517 11.9017C14.4484 12.6049 13.4946 13 12.5 13C11.5054 13 10.5516 12.6049 9.84835 11.9017C9.14509 11.1984 8.75 10.2446 8.75 9.25ZM5 19.457C5 16.582 7.33203 14.25 10.207 14.25H14.793C17.668 14.25 20 16.582 20 19.457C20 20.0312 19.5352 20.5 18.957 20.5H6.04297C5.46875 20.5 5 20.0352 5 19.457Z"
                                            fill="#F58D2E" />
                                    </svg>
                                </span>
                                <div class="{{ margin_start(3) }}">
                                    <span class="fs-3 fw-bold">148</span>
                                </div>
                            </div>
                            <div class="mb-1 text-muted fw-medium">{{ __("messages.active_workers") }}</div>
                            <div class="text-muted small black_color fw-normal">+12 {{ __("messages.from_last_week") }}</div>
                        </div>
                    </div>
                </div>
                <!-- {{ __("messages.days_remaining") }} Card -->
                <div class="col-12 col-md-4  wow fadeInUp" data-wow-delay=".8s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body p-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="stat-icon bg6 ms-0">
                                    <svg width="18" height="21" viewBox="0 0 18 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.75 1.75V3H1.875C0.839844 3 0 3.83984 0 4.875V6.75H17.5V4.875C17.5 3.83984 16.6602 3 15.625 3H13.75V1.75C13.75 1.05859 13.1914 0.5 12.5 0.5C11.8086 0.5 11.25 1.05859 11.25 1.75V3H6.25V1.75C6.25 1.05859 5.69141 0.5 5 0.5C4.30859 0.5 3.75 1.05859 3.75 1.75ZM17.5 8H0V18.625C0 19.6602 0.839844 20.5 1.875 20.5H15.625C16.6602 20.5 17.5 19.6602 17.5 18.625V8Z"
                                            fill="#2563EB" />
                                    </svg>
                                </span>
                                <div class="{{ margin_start(3) }}">
                                    <span class="fs-3 fw-bold">45</span>
                                </div>
                            </div>
                            <div class="mb-2 text-muted fw-medium">{{ __("messages.days_remaining") }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 wow fadeInUp" data-wow-delay="0.9s">
                <div class="col-12">
                    <div class="card B_shadow">
                        <div class="card-body p-md-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-start">
                                <div>
                                    <h5 class="fw-semibold  mb-3 mb-md-4 black_color">{{ __("messages.project_timeline") }}</h5>
                                    <div class="row gy-3 gx-5">
                                        <div class="col-12 col-md-6">
                                            <div class="mb-2 mb-md-3">
                                                <span class="text-muted small black_color">{{ __("messages.project_name") }}</span>
                                                <div class="fw-medium d-flex align-items-center gap-1">
                                                    {{ __('messages.urban_sky_towers') }}
                                                    <a href="#" class="text-primary ms-1" title="Edit"><img
                                                            src="{{ asset('website/images/icons/edit.svg') }}" alt="edit"></a>
                                                </div>
                                            </div>
                                            <div class="mb-2 mb-md-3">
                                                <span class="text-muted small black_color">{{ __("messages.company_name") }}</span>
                                                <div class="fw-medium d-flex align-items-center gap-1p-md-4">
                                                    {{ __('messages.summit_construction') }}
                                                    <a href="#" class="text-primary ms-1" title="Edit"><img
                                                            src="{{ asset('website/images/icons/edit.svg') }}" alt="edit"></a>
                                                </div>
                                            </div>
                                            <div class="mb-2 mb-md-3">
                                                <span class="text-muted small black_color">{{ __("messages.start_date") }}</span>
                                                <div class="fw-medium">{{ __('messages.may_25_2025_full') }}</div>
                                            </div>
                                            <div class="mb-2 mb-md-3">
                                                <span class="text-muted small black_color">{{ __("messages.end_date") }}</span>
                                                <div class="fw-medium">{{ __('messages.may_8_2026') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="mb-2 mb-md-3">
                                                <span class="text-muted small black_color">{{ __("messages.project_type") }}</span>
                                                <div class="fw-medium d-flex align-items-center gap-1p-md-4">
                                                    {{ __('messages.tower') }}
                                                    <a href="#" class="text-primary ms-1" title="Edit"><img
                                                            src="{{ asset('website/images/icons/edit.svg') }}" alt="edit"></a>
                                                </div>
                                            </div>
                                            <div class="mb-2 mb-md-3">
                                                <span class="text-muted small black_color">{{ __("messages.project_manager") }}</span>
                                                <div class="fw-medium d-flex align-items-center gap-1p-md-4">
                                                    {{ __('messages.robert_parker') }}
                                                    <a href="#" class="text-primary ms-1" title="Edit"><img
                                                            src="{{ asset('website/images/icons/edit.svg') }}" alt="edit"></a>
                                                </div>
                                            </div>
                                            <div class="mb-2 mb-md-3">
                                                <span class="text-muted small black_color">{{ __("messages.site_engineer") }}</span>
                                                <div class="fw-medium d-flex align-items-center gap-1p-md-4">
                                                    {{ __('messages.johar_parker') }}
                                                    <a href="#" class="text-primary ms-1" title="Edit"><img
                                                            src="{{ asset('website/images/icons/edit.svg') }}" alt="edit"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 d-flex flex-wrap gap-2">
                                        <button
                                            class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-5 svg-hover-white">
                                            <svg width="18" height="14" viewBox="0 0 18 14" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12 13.8778L6 12.1621V0.121509L12 1.83713V13.8778ZM13 13.8403V1.76213L16.9719 0.171509C17.4656 -0.0253659 18 0.337134 18 0.868384V11.3309C18 11.6371 17.8125 11.9121 17.5281 12.0278L13 13.8371V13.8403ZM0.471875 1.97151L5 0.162134V12.2371L1.02813 13.8278C0.534375 14.0246 0 13.6621 0 13.1309V2.66838C0 2.36213 0.1875 2.08713 0.471875 1.97151Z"
                                                    fill="#4477C4" />
                                            </svg>
                                            {{ __("messages.view_plan") }}
                                        </button>
                                        <button
                                            class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-5 svg-hover-white">
                                            <svg width="21" height="16" viewBox="0 0 21 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_861_1919)">
                                                    <path
                                                        d="M4.0625 2.25C4.26141 2.25 4.45218 2.32902 4.59283 2.46967C4.73348 2.61032 4.8125 2.80109 4.8125 3C4.8125 3.19891 4.73348 3.38968 4.59283 3.53033C4.45218 3.67098 4.26141 3.75 4.0625 3.75C3.86359 3.75 3.67282 3.67098 3.53217 3.53033C3.39152 3.38968 3.3125 3.19891 3.3125 3C3.3125 2.80109 3.39152 2.61032 3.53217 2.46967C3.67282 2.32902 3.86359 2.25 4.0625 2.25ZM5.0625 5.29063C5.94688 4.90625 6.5625 4.025 6.5625 3C6.5625 1.61875 5.44375 0.5 4.0625 0.5C2.68125 0.5 1.5625 1.61875 1.5625 3C1.5625 4.025 2.17812 4.90625 3.0625 5.29063V7H1.0625C0.509375 7 0.0625 7.44688 0.0625 8C0.0625 8.55313 0.509375 9 1.0625 9H9.0625V10.7094C8.17813 11.0938 7.5625 11.975 7.5625 13C7.5625 14.3813 8.68125 15.5 10.0625 15.5C11.4438 15.5 12.5625 14.3813 12.5625 13C12.5625 11.975 11.9469 11.0938 11.0625 10.7094V9H19.0625C19.6156 9 20.0625 8.55313 20.0625 8C20.0625 7.44688 19.6156 7 19.0625 7H17.0625V5.29063C17.9469 4.90625 18.5625 4.025 18.5625 3C18.5625 1.61875 17.4438 0.5 16.0625 0.5C14.6812 0.5 13.5625 1.61875 13.5625 3C13.5625 4.025 14.1781 4.90625 15.0625 5.29063V7H5.0625V5.29063ZM15.3125 3C15.3125 2.80109 15.3915 2.61032 15.5322 2.46967C15.6728 2.32902 15.8636 2.25 16.0625 2.25C16.2614 2.25 16.4522 2.32902 16.5928 2.46967C16.7335 2.61032 16.8125 2.80109 16.8125 3C16.8125 3.19891 16.7335 3.38968 16.5928 3.53033C16.4522 3.67098 16.2614 3.75 16.0625 3.75C15.8636 3.75 15.6728 3.67098 15.5322 3.53033C15.3915 3.38968 15.3125 3.19891 15.3125 3ZM10.0625 12.25C10.2614 12.25 10.4522 12.329 10.5928 12.4697C10.7335 12.6103 10.8125 12.8011 10.8125 13C10.8125 13.1989 10.7335 13.3897 10.5928 13.5303C10.4522 13.671 10.2614 13.75 10.0625 13.75C9.86359 13.75 9.67282 13.671 9.53217 13.5303C9.39152 13.3897 9.3125 13.1989 9.3125 13C9.3125 12.8011 9.39152 12.6103 9.53217 12.4697C9.67282 12.329 9.86359 12.25 10.0625 12.25Z"
                                                        fill="#4477C4" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_861_1919">
                                                        <path d="M0.0625 0H20.0625V16H0.0625V0Z" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            {{ __("messages.timeline") }}
                                        </button>
                                        <button
                                            class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-5 svg-hover-white">
                                            <svg width="15" height="16" viewBox="0 0 15 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M3.23438 1V2H1.73438C0.90625 2 0.234375 2.67188 0.234375 3.5V5H14.2344V3.5C14.2344 2.67188 13.5625 2 12.7344 2H11.2344V1C11.2344 0.446875 10.7875 0 10.2344 0C9.68125 0 9.23438 0.446875 9.23438 1V2H5.23438V1C5.23438 0.446875 4.7875 0 4.23438 0C3.68125 0 3.23438 0.446875 3.23438 1ZM14.2344 6H0.234375V14.5C0.234375 15.3281 0.90625 16 1.73438 16H12.7344C13.5625 16 14.2344 15.3281 14.2344 14.5V6Z"
                                                    fill="#4477C4" />
                                            </svg>
                                            {{ __('messages.may_25_2025') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="ms-auto mt-3 mt-md-0">
                                    <button class="btn btn-danger d-flex align-items-center gap-2 py-md-2" onclick="deleteProject()">
                                        <i class="fas fa-trash"></i> {{ __("messages.delete_project") }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 wow fadeInUp" data-wow-delay="1.2s">
                <div class="col-12">
                    <div class="card B_shadow">
                        <div class="card-body p-md-4">
                            <h5 class="fw-semibold  mb-3 mb-md-4 black_color">{{ __("messages.project_overview") }}</h5>
                            <div class="mb-2 mb-md-3">
                                <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
                                    <h6 class="text-muted fw-medium">{{ __("messages.foundation") }}</h6>
                                    <h6 class="text-muted fw-medium">100%</h6>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 100%; background: linear-gradient(90deg, #4477C4 0%, #f59e42 100%);"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="mb-2 mb-md-3">
                                <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
                                    <h6 class="text-muted fw-medium">{{ __("messages.structure") }}</h6>
                                    <h6 class="text-muted fw-medium">85%</h6>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 85%; background: linear-gradient(90deg, #4477C4 0%, #F58D2E 100%); "
                                        aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="mb-2 mb-md-3">
                                <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
                                    <h6 class="text-muted fw-medium">{{ __("messages.roofing") }}</h6>
                                    <h6 class="text-muted fw-medium">60%</h6>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 60%; background: linear-gradient(90deg, #4477C4 0%, #f59e42 100%);"
                                        aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="mb-2 mb-md-3">
                                <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
                                    <h6 class="text-muted fw-medium">{{ __("messages.interior") }}</h6>
                                    <h6 class="text-muted fw-medium">25%</h6>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 25%; background: linear-gradient(90deg, #4477C4 0%, #f59e42 100%);"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="mb-2 mb-md-3">
                                <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
                                    <h6 class="text-muted fw-medium">{{ __("messages.finishing") }}</h6>
                                    <h6 class="text-muted fw-medium">5%</h6>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 5%; background: linear-gradient(90deg, #4477C4 0%, #F58D2E 100%);"
                                        aria-valuenow="5" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 wow fadeInUp" data-wow-delay="1.3s">
                <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                    <div class="card h-100 B_shadow">
                        <div class="card-body p-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-semibold black_color mb-0 ">{{ __("messages.ongoing_activities") }}</h5>
                                <a href="#" class="text-primary" title="Edit"><img
                                        src="{{ asset('website/images/icons/edit.svg') }}" alt="edit"></a>
                            </div>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center mb-2">
                                    <span class="{{ margin_end(2) }}" style="color:#F58D2E; font-size:1.2em;">&#9679;</span>
                                    Concrete pouring on Basement Level 2
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <span class="{{ margin_end(2) }}" style="color:#F58D2E; font-size:1.2em;">&#9679;</span>
                                    Formwork preparation for Ground Floor columns
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <span class="{{ margin_end(2) }}" style="color:#F58D2E; font-size:1.2em;">&#9679;</span>
                                    Electrical conduit layout in progress on Level 1
                                </li>
                                <li class="d-flex align-items-center">
                                    <span class="{{ margin_end(2) }}" style="color:#F58D2E; font-size:1.2em;">&#9679;</span>
                                    QA/QC inspection for rebar placement
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card h-100 B_shadow">
                        <div class="card-body p-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-semibold black_color mb-0 ">{{ __("messages.manpower_equipment") }}</h5>
                                <a href="#" class="text-primary" title="Edit"><img
                                        src="{{ asset('website/images/icons/edit.svg') }}" alt="edit"></a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __("messages.engineers") }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">3</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __("messages.foremen") }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">2</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __("messages.laborers") }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">25</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __("messages.excavators") }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">2
                                                    {{ __("messages.units") }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __("messages.concrete_mixers") }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">1
                                                    {{ __("messages.unit") }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __("messages.cranes") }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">1
                                                    {{ __("messages.unit") }}</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 wow fadeInUp" data-wow-delay="1.4s">
                <div class="col-12">
                    <div class="card B_shadow">
                        <div class="card-body p-md-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                                <h5 class="fw-semibold black_color mb-0 ">{{ __("messages.safety_category") }}</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="safety-checklist.htmll"
                                        class="btn btn-primary  d-flex align-items-center gap-2 btnsm">
                                        <i class="fas fa-eye"></i> {{ __("messages.view_checklist") }}
                                    </a>
                                    <a href="#" class="btn btn-primary d-flex align-items-center gap-2 btnsm">
                                        <i class="fas fa-plus"></i> {{ __("messages.new_checklist") }}
                                    </a>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <div class="d-flex align-items-center p-3 rounded bg4">
                                        <span class="me-3 text-success" style="font-size:1.3em;">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        {{ __("messages.ppe_check") }} Completed
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="d-flex align-items-center p-3 rounded bg5">
                                        <span class="me-3 text-warning" style="font-size:1.3em;">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                        One unsafe scaffolding identified and corrected
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="d-flex align-items-center p-3 rounded bg4">
                                        <span class="me-3 text-success" style="font-size:1.3em;">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        Fire extinguisher checks completed
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="d-flex align-items-center p-3 rounded bg4">
                                        <span class="me-3 text-success" style="font-size:1.3em;">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        Toolbox Talk Conducted (Topic: Working at Heights)
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center p-3 rounded bg4">
                                        <span class="me-3 text-success" style="font-size:1.3em;">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        No incidents reported
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
  </div>
</div>
<script>
function deleteProject() {
  if (confirm('Are you sure you want to delete this project? This action cannot be undone.')) {
    alert('Project deletion functionality would be implemented here.');
  }
}

// Make edit buttons functional
document.addEventListener('DOMContentLoaded', function() {
  const editButtons = document.querySelectorAll('a[title="Edit"]');
  editButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const field = this.previousElementSibling.textContent.trim();
      const newValue = prompt(`Edit ${field.split(' ')[0]}:`, field);
      if (newValue && newValue !== field) {
        this.previousElementSibling.textContent = newValue;
        alert('Field updated successfully!');
      }
    });
  });
  
  // Make manpower links functional
  const manpowerLinks = document.querySelectorAll('.table a');
  manpowerLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const role = this.closest('tr').querySelector('td').textContent;
      const count = this.textContent;
      alert(`${role}: ${count}\n\nDetailed view would show:\n- Individual assignments\n- Work schedules\n- Performance metrics`);
    });
  });
});
</script>

@endsection
