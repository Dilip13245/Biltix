@extends('website.layout.app')

@section('title', 'Reports')

@section('content')
    <style>
        .report-card {
            background-color: #4477C4;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid transparent;
        }

        .date-range-section {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .date-range-section.show {
            max-height: 200px;
            opacity: 1;
            margin-bottom: 1.5rem;
        }

        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(68, 119, 196, 0.3);
        }

        .report-radio:checked+.report-card {
            border-color: #F58D2E;
            background-color: #3a66a8;
        }

        .custom-radio-indicator {
            width: 24px;
            height: 24px;
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            position: relative;
            transition: all 0.2s;
        }

        .report-radio:checked+.report-card .custom-radio-indicator {
            border-color: #fff;
        }

        .report-radio:checked+.report-card .custom-radio-indicator::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 12px;
            height: 12px;
            background-color: #fff;
            border-radius: 50%;
        }

        .history-card {
            background-color: #F0F7FF;
            border: 1px solid #E1E9F4;
            transition: all 0.2s;
        }

        .history-card:hover {
            background-color: #E6F0FA;
        }

        .action-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
            color: #4477C4;
        }

        .action-icon:hover {
            background-color: rgba(68, 119, 196, 0.1);
        }

        /* RTL Support for Date Inputs */
        [dir="rtl"] input[type="week"],
        [dir="rtl"] input[type="month"] {
            direction: rtl;
            text-align: right;
            padding-left: 2.5rem;
            padding-right: 0.75rem;
            position: relative;
        }

        [dir="rtl"] input[type="week"]::-webkit-calendar-picker-indicator,
        [dir="rtl"] input[type="month"]::-webkit-calendar-picker-indicator {
            position: absolute;
            left: 10px;
            right: auto;
        }
    </style>

    <div class="content-header border-0 shadow-none mb-4 d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <div>
            <h2>{{ __('messages.reports') }}</h2>
            <p>{{ __('messages.download_reports') }}</p>
        </div>
    </div>

    <section class="px-md-4">
        <div class="container-fluid">
            <!-- Report Type Selection -->
            <form action="#" method="POST">
                @csrf
                <div class="row g-4 mb-4">
                    <!-- Daily Report -->
                    <div class="col-md-4">
                        <label class="w-100 cursor-pointer">
                            <input type="radio" name="report_type" value="daily" class="d-none report-radio" checked>
                            <div class="card report-card border-0 rounded-4">
                                <div class="card-body w-100 h-100 d-flex align-items-center justify-content-center gap-3">
                                    <div class="custom-radio-indicator"></div>
                                    <h4 class="text-white mb-0 fw-semibold">{{ __('messages.daily_report') }}</h4>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Weekly Report -->
                    <div class="col-md-4">
                        <label class="w-100 cursor-pointer">
                            <input type="radio" name="report_type" value="weekly" class="d-none report-radio">
                            <div class="card report-card border-0 rounded-4">
                                <div class="card-body w-100 h-100 d-flex align-items-center justify-content-center gap-3">
                                    <div class="custom-radio-indicator"></div>
                                    <h4 class="text-white mb-0 fw-semibold">{{ __('messages.weekly_report') }}</h4>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Monthly Report -->
                    <div class="col-md-4">
                        <label class="w-100 cursor-pointer">
                            <input type="radio" name="report_type" value="monthly" class="d-none report-radio">
                            <div class="card report-card border-0 rounded-4">
                                <div class="card-body w-100 h-100 d-flex align-items-center justify-content-center gap-3">
                                    <div class="custom-radio-indicator"></div>
                                    <h4 class="text-white mb-0 fw-semibold">{{ __('messages.monthly_report') }}</h4>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Date Range Selection (Hidden by default) -->
                <div class="date-range-section" id="dateRangeSection">
                    <div class="card border-0 rounded-4"
                        style="background-color: #F0F7FF; border: 2px solid #4477C4 !important;">
                        <div class="card-body p-4">
                            <!-- Weekly Selection -->
                            <div id="weeklySelection" style="display: none;">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="far fa-calendar-alt {{ margin_end(2) }} text-primary"></i>
                                    {{ __('messages.select_week') }}
                                </label>
                                <div style="position: relative;">
                                    <input type="week" name="week" id="weekInput" class="form-control form-control-lg">
                                </div>
                            </div>

                            <!-- Monthly Selection -->
                            <div id="monthlySelection" style="display: none;">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="far fa-calendar-alt {{ margin_end(2) }} text-primary"></i>
                                    {{ __('messages.select_month') }}
                                </label>
                                <div style="position: relative;">
                                    <input type="month" name="month" id="monthInput" class="form-control form-control-lg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Generate Button -->
                <div class="row mb-5">
                    <div class="col-12">
                        <button type="button"
                            class="btn orange_btn w-100 py-3 rounded-3 fs-5 fw-medium d-flex align-items-center justify-content-center gap-2">
                            {{ __('messages.generate_report') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Report History -->
            <div class="row">
                <div class="col-12">
                    <h5 class="fw-bold mb-3">{{ __('messages.report_history') }}</h5>
                    <div class="d-flex flex-column gap-3">
                        @foreach ($reportHistory as $report)
                            <div class="card history-card border-0 rounded-3">
                                <div class="card-body py-3 px-4">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        <div class="d-flex flex-column gap-2">
                                            <h6 class="text-primary fw-bold mb-0 fs-5">{{ $report['name'] }}</h6>
                                            <div class="d-flex flex-column gap-1">
                                                <small class="text-muted d-flex align-items-center gap-2">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ __('messages.generated_by') }} {{ $report['generated_by'] }}
                                                </small>
                                                <small class="text-muted d-flex align-items-center gap-2">
                                                    <i class="far fa-user"></i>
                                                    {{ __('messages.generation_date') }} {{ $report['date'] }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="btn action-icon">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn action-icon">
                                                <i class="fas fa-share-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reportRadios = document.querySelectorAll('.report-radio');
            const dateRangeSection = document.getElementById('dateRangeSection');
            const weeklySelection = document.getElementById('weeklySelection');
            const monthlySelection = document.getElementById('monthlySelection');

            reportRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'weekly') {
                        dateRangeSection.classList.add('show');
                        weeklySelection.style.display = 'block';
                        monthlySelection.style.display = 'none';
                    } else if (this.value === 'monthly') {
                        dateRangeSection.classList.add('show');
                        weeklySelection.style.display = 'none';
                        monthlySelection.style.display = 'block';
                    } else {
                        dateRangeSection.classList.remove('show');
                        weeklySelection.style.display = 'none';
                        monthlySelection.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
