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
            <!-- Report {{ __('messages.type') }} Selection -->
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
                                    <input type="week" name="week" id="weekInput"
                                        class="form-control form-control-lg">
                                </div>
                            </div>

                            <!-- Monthly Selection -->
                            <div id="monthlySelection" style="display: none;">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="far fa-calendar-alt {{ margin_end(2) }} text-primary"></i>
                                    {{ __('messages.select_month') }}
                                </label>
                                <div style="position: relative;">
                                    <input type="month" name="month" id="monthInput"
                                        class="form-control form-control-lg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Generate Button -->
                <div class="row mb-5">
                    <div class="col-12">
                        <button type="button" id="generateBtn"
                            class="btn orange_btn w-100 py-3 rounded-3 fs-5 fw-medium d-flex align-items-center justify-content-center gap-2">
                            <span id="generateBtnText">{{ __('messages.generate_report') }}</span>
                            <span id="generateBtnLoader" class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </div>
                </div>
            </form>

            <div id="reportPreview" class="d-none mb-5">
                <style>
                    /* --- PREVIEW CSS (Scoped & Prefixed) --- */
                    .preview-container {
                        width: 100%;
                        max-width: 210mm;
                        margin: 0 auto;
                        background: white;
                        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
                        font-family: 'Segoe UI', Arial, sans-serif;
                    }

                    /* Header */
                    .preview-header-strip {
                        height: 180px;
                        background-color: #102a4e;
                        background-image: url("{{ asset('website/images/image.png') }}");
                        background-size: cover;
                        background-position: center;
                        background-repeat: no-repeat;
                        color: white;
                        position: relative;
                        overflow: hidden;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .preview-header-pattern {
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background-image:
                            linear-gradient(45deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                            linear-gradient(-45deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
                        background-size: 50px 50px;
                        z-index: 1;
                    }

                    .preview-header-content {
                        z-index: 2;
                        width: 100%;
                        height: 100%;
                        position: relative;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }

                    .preview-brand-logo {
                        position: absolute;
                        left: 45px;
                        bottom: 20px;
                        z-index: 10;
                    }

                    .preview-brand-logo img {
                        height: 59px;
                        width: auto;
                    }

                    .preview-center-title-block {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        text-align: center;
                        margin-top: 10px;
                    }

                    .preview-title-icon-circle {
                        width: 50px;
                        height: 50px;
                        border: 1px solid rgba(255, 255, 255, 0.3);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-bottom: 15px;
                        background: rgba(255, 255, 255, 0.05);
                        font-size: 20px;
                        color: white;
                    }

                    .preview-header-title {
                        font-size: 24px;
                        font-weight: 500;
                        letter-spacing: 0.5px;
                        margin-bottom: 8px;
                        text-transform: none;
                        color: white;
                    }

                    .preview-title-underline {
                        width: 80px;
                        height: 4px;
                        background: radial-gradient(circle, #ea6b1e 0%, transparent 100%);
                        border-radius: 50%;
                        opacity: 0.8;
                    }

                    /* RTL Header Support */
                    [dir="rtl"] .preview-header-strip {
                        transform: scaleX(-1);
                    }

                    [dir="rtl"] .preview-header-content {
                        transform: scaleX(-1);
                    }

                    [dir="rtl"] .preview-brand-logo {
                        left: auto;
                        right: 45px;
                    }

                    /* Content */
                    .preview-content {
                        padding: 25px 40px;
                    }

                    /* Info Table */
                    .preview-info-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                        font-size: 14px;
                        border: 2px solid #1b3d6d;
                    }

                    .preview-info-table td {
                        padding: 12px 15px;
                        border: 1px solid #1b3d6d;
                    }

                    .preview-lbl-blue {
                        background-color: #1b3d6d;
                        color: white;
                        width: 30%;
                        font-weight: 500;
                    }

                    .preview-lbl-orange {
                        background-color: #ea6b1e;
                        color: white;
                        width: 30%;
                        font-weight: 500;
                    }

                    .preview-val-white {
                        background-color: white;
                        color: #000;
                        font-weight: 500;
                    }

                    /* Sections */
                    .preview-section-header {
                        padding: 8px 15px;
                        color: white;
                        font-weight: 600;
                        font-size: 14px;
                        margin-top: 20px;
                        margin-bottom: 10px;
                    }

                    .preview-bg-blue {
                        background-color: #1b3d6d;
                        background-image: url("{{ asset('website/images/section_bg.png') }}");
                        background-size: cover;
                        background-position: center;
                        background-repeat: no-repeat;
                    }

                    .preview-bg-orange {
                        background-color: #ea6b1e;
                        background-image: url("{{ asset('website/images/section_bg_org.png') }}");
                        background-size: cover;
                        background-position: center;
                        background-repeat: no-repeat;
                    }

                    .preview-bg-gradients {
                        background: linear-gradient(90deg, #102a4e 0%, #ea6b1e 100%);
                    }

                    .preview-bg-gradient-reverse {
                        background: linear-gradient(90deg, #ea6b1e 0%, #102a4e 100%);
                    }

                    .preview-bg-dark-gred {
                        background: linear-gradient(90deg, #2c3e50 0%, #1f2d3d 100%);
                    }

                    .preview-bordered-box {
                        border: 2px solid #1b3d6d;
                        padding: 20px;
                        margin-bottom: 20px;
                    }

                    /* Tables */
                    .preview-data-table {
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 12px;
                        margin-bottom: 10px;
                    }

                    .preview-data-table th {
                        background-color: #1b3d6d;
                        color: white;
                        padding: 8px;
                        text-align: left;
                        font-weight: 500;
                        border: 1px solid white;
                    }

                    .preview-data-table td {
                        border: 1px solid #ccc;
                        padding: 6px 8px;
                        vertical-align: middle;
                    }

                    /* Performance Table */
                    .preview-perf-table {
                        width: 100%;
                        border: 1px solid #ea6b1e;
                        border-collapse: collapse;
                        font-size: 13px;
                    }

                    .preview-perf-table tr {
                        border-bottom: 1px solid #b0c4de;
                    }

                    .preview-perf-table tr:last-child {
                        border-bottom: none;
                    }

                    .preview-perf-label-cell {
                        background-color: #fff5ec;
                        width: 35%;
                        padding: 15px;
                        vertical-align: top;
                        color: #333;
                        font-weight: 500;
                    }

                    .preview-perf-value-cell {
                        background-color: white;
                        padding: 15px;
                        vertical-align: top;
                        line-height: 1.5;
                        color: #333;
                        border-left: 1px solid #b0c4de;
                    }

                    .preview-num-badge {
                        display: inline-block;
                        background-color: #ea6b1e;
                        color: white;
                        width: 22px;
                        height: 22px;
                        text-align: center;
                        line-height: 22px;
                        border-radius: 3px;
                        margin-right: 8px;
                        font-weight: bold;
                        font-size: 12px;
                    }

                    /* Meetings */
                    .preview-meeting-block {
                        margin-bottom: 20px;
                    }

                    .preview-meeting-block:last-child {
                        margin-bottom: 0;
                    }

                    .preview-meeting-badge {
                        background-color: #102a4e;
                        color: white;
                        padding: 4px 12px;
                        border-radius: 4px;
                        font-weight: 500;
                        font-size: 13px;
                        display: inline-block;
                    }

                    .preview-meeting-details {
                        padding-left: 5px;
                        margin-top: 5px;
                        font-size: 13px;
                    }

                    .preview-detail-label {
                        font-weight: 700;
                        color: #2c3e50;
                        margin-right: 2px;
                    }

                    /* Lists */
                    .preview-orange-list {
                        list-style: none;
                        padding: 0;
                        font-size: 12px;
                    }

                    .preview-orange-list li {
                        position: relative;
                        padding-left: 15px;
                        margin-bottom: 5px;
                    }

                    .preview-orange-list li::before {
                        content: "■";
                        color: #ea6b1e;
                        font-size: 8px;
                        position: absolute;
                        left: 2px;
                        top: 5px;
                    }

                    /* Circular Progress Graph */
                    .circular-progress-container {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        gap: 15px;
                    }

                    .circular-progress-wrapper {
                        position: relative;
                        width: 120px;
                        height: 120px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .circular-progress-svg {
                        width: 100%;
                        height: 100%;
                        transform: rotate(-90deg);
                    }

                    .circular-progress-circle {
                        fill: none;
                        stroke-width: 8;
                    }

                    .circular-progress-bg {
                        stroke: #e9ecef;
                    }

                    .circular-progress-fill {
                        stroke: url(#progressGradient);
                        stroke-linecap: round;
                        transition: stroke-dashoffset 0.5s ease;
                    }

                    .circular-progress-text {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        text-align: center;
                    }

                    .circular-progress-percentage {
                        font-size: 24px;
                        font-weight: bold;
                        color: #1b3d6d;
                    }

                    .circular-progress-label {
                        font-size: 12px;
                        color: #6c757d;
                        margin-top: 15px;
                        text-align: center;
                        font-weight: 500;
                    }

                    .circular-graphs-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                        gap: 30px;
                        width: 100%;
                    }

                    [dir="rtl"] .circular-progress-svg {
                        transform: rotate(90deg) scaleX(-1);
                    }

                    /* Footer */
                    .preview-footer-strip {
                        height: 60px;
                        background-color: #1b3d6d;
                        background-image: url("{{ asset('website/images/footer_bg.png') }}");
                        background-size: cover;
                        background-position: center;
                        background-repeat: no-repeat;
                        position: relative;
                        margin-top: auto;
                    }

                    .preview-page-footer-text {
                        text-align: center;
                        font-size: 12px;
                        color: #6c757d;
                        margin-top: 40px;
                        border-top: 2px solid #dbe2e8;
                        padding-top: 15px;
                        line-height: 1.6;
                        margin-bottom: 20px;
                    }

                    .preview-signature-box {
                        background-color: white;
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                        padding: 20px 30px;
                    }

                    .preview-sig-field {
                        display: flex;
                        flex-direction: column;
                        gap: 15px;
                    }

                    .preview-sig-label {
                        color: #555;
                        font-size: 14px;
                    }

                    .preview-sig-value {
                        font-weight: 300;
                        font-size: 14px;
                        color: #000;
                    }

                    .preview-sig-line {
                        width: 250px;
                        border-bottom: 2px solid #6c85a3;
                        padding-bottom: 5px;
                        margin-top: 5px;
                    }

                    .preview-sig-text {
                        font-style: italic;
                        color: #8faad9;
                        font-size: 15px;
                    }

                    /* --- RESPONSIVE STYLES --- */
                    @media (max-width: 768px) {
                        .preview-container {
                            max-width: 100%;
                            box-shadow: none;
                        }

                        .preview-header-strip {
                            height: auto;
                            min-height: 150px;
                            padding: 30px 20px;
                        }

                        .preview-header-content {
                            flex-direction: column;
                            gap: 20px;
                        }

                        .preview-brand-logo {
                            position: static;
                            margin-bottom: 10px;
                        }

                        .preview-brand-logo img {
                            height: 45px;
                        }

                        .preview-center-title-block {
                            margin-top: 0;
                        }

                        .preview-header-title {
                            font-size: 18px;
                        }

                        .preview-content {
                            padding: 15px 20px;
                        }

                        .preview-info-table td {
                            padding: 8px 10px;
                            font-size: 12px;
                        }

                        .preview-lbl-blue,
                        .preview-lbl-orange {
                            width: 40%;
                        }

                        .preview-data-table {
                            display: block;
                            overflow-x: auto;
                            white-space: nowrap;
                        }

                        .preview-perf-table tr {
                            display: flex;
                            flex-direction: column;
                        }

                        .preview-perf-label-cell,
                        .preview-perf-value-cell {
                            width: 100%;
                            border-left: none !important;
                            border-right: none !important;
                        }

                        .preview-perf-value-cell {
                            border-top: 1px solid #b0c4de;
                        }

                        .preview-signature-box {
                            flex-direction: column;
                            align-items: center;
                            gap: 25px;
                            padding: 20px;
                            text-align: center;
                        }

                        .preview-sig-line {
                            width: 200px;
                            margin: 5px auto 0;
                        }
                    }
                </style>
                <div class="preview-container">
                    <!-- Header -->
                    <div class="preview-header-strip">
                        <div class="preview-header-pattern"></div>
                        <div class="preview-header-content">
                            <div class="preview-brand-logo">
                                <img src="{{ asset('website/images/icons/logo.svg') }}" alt="BILTIX Logo">
                            </div>
                            <div class="preview-center-title-block">
                                <div class="preview-title-icon-circle">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="preview-header-title" id="previewReport{{ __('messages.type') }}">Progress
                                    Report</div>
                                <div class="preview-title-underline"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Completion Percentages Circular Graph -->
                    <div class="preview-content" id="completionGraphSection" style="display: none;">
                        <div class="preview-section-header preview-bg-blue">{{ __('messages.completion_percentages') }}
                        </div>
                        <div class="preview-bordered-box">
                            <div class="row g-4" id="completionGraphsContainer">
                                <!-- Circular graphs will be inserted here -->
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Content -->
                    <div class="preview-content" id="reportContent"></div>

                    <!-- General {{ __('messages.remarks') }} Input Section -->
                    <div class="preview-content pt-0">
                        <div class="preview-section-header preview-bg-dark-gred">{{ __('messages.sixth') }}:
                            {{ __('messages.general_remarks') }}</div>
                        <div class="preview-bordered-box">
                            <div class="mb-3">
                                <label for="general{{ __('messages.remarks') }}"
                                    class="form-label fw-semibold">{{ __('messages.enter_remarks') }}</label>
                                <textarea class="form-control" id="general{{ __('messages.remarks') }}" rows="4"
                                    placeholder="{{ __('messages.enter_general_remarks') }}"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="general{{ __('messages.remarks') }}File"
                                    class="form-label fw-semibold">{{ __('messages.attach_file') }}</label>
                                <input class="form-control" type="file" id="general{{ __('messages.remarks') }}File"
                                    accept=".jpg,.jpeg,.png,.pdf">
                                <div class="form-text">{{ __('messages.supported_formats') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Signature Block -->
                    <div class="preview-content pt-0">
                        <div class="preview-section-header preview-bg-blue" style="margin-bottom: 0;">
                            {{ __('messages.site_engineer_consultant') }}</div>
                        <div class="preview-bordered-box preview-signature-box">
                            <div class="preview-sig-field">
                                <div class="preview-sig-label">{{ __('messages.name') }}:</div>
                                <div class="preview-sig-value">
                                    {{ __('messages.eng_mohammed_al_rashid') }}</div>
                            </div>
                            <div class="preview-sig-field">
                                <div class="preview-sig-label">{{ __('messages.signature') }}:</div>
                                <div class="preview-sig-line">
                                    <span class="preview-sig-text">[Signed]</span>
                                </div>
                            </div>
                            <div class="preview-sig-field">
                                <div class="preview-sig-label">{{ __('messages.date') }}:</div>
                                <div class="preview-sig-value">{{ date('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Strip -->
                    <div class="preview-footer-strip">
                        <div class="preview-header-pattern"></div>
                    </div>
                </div>
                <button type="button" id="makePdfBtn" class="btn orange_btn w-100 py-3 rounded-3 fs-5 fw-medium mt-4">
                    <span id="pdfBtnText">{{ __('messages.make_pdf') }}</span>
                    <span id="pdfBtnLoader" class="spinner-border spinner-border-sm d-none"></span>
                </button>
            </div>

            <!-- Report History -->
            <div class="row">
                <div class="col-12">
                    <h5 class="fw-bold mb-3">{{ __('messages.report_history') }}</h5>
                    <div id="historyContainer" class="d-flex flex-column gap-3">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('website.modals.share-report-modal')

    <script>
        const projectId = {{ $project_id ?? 'null' }};
        const userId = {{ auth()->user()->id ?? 'null' }};
        let currentReportData = null;

        if (!projectId || !userId) {
            console.error('Project ID or User ID not found');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const reportRadios = document.querySelectorAll('.report-radio');
            const dateRangeSection = document.getElementById('dateRangeSection');
            const weeklySelection = document.getElementById('weeklySelection');
            const monthlySelection = document.getElementById('monthlySelection');
            const generateBtn = document.getElementById('generateBtn');
            const makePdfBtn = document.getElementById('makePdfBtn');
            const weekInput = document.getElementById('weekInput');
            const monthInput = document.getElementById('monthInput');

            // Open calendar on input click
            weekInput.addEventListener('click', function() {
                this.showPicker();
            });

            monthInput.addEventListener('click', function() {
                this.showPicker();
            });

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

            generateBtn.addEventListener('click', generateReport);
            makePdfBtn.addEventListener('click', savePdf);
            loadHistory();
        });

        async function generateReport() {
            const report{{ __('messages.type') }} = document.querySelector('input[name="report_type"]:checked').value;
            const week = document.getElementById('weekInput').value;
            const month = document.getElementById('monthInput').value;

            // Validation
            if (report{{ __('messages.type') }} === 'weekly' && !week) {
                alert('Please select a week');
                return;
            }
            if (report{{ __('messages.type') }} === 'monthly' && !month) {
                alert('Please select a month');
                return;
            }

            const generateBtn = document.getElementById('generateBtn');
            const generateBtnText = document.getElementById('generateBtnText');
            const generateBtnLoader = document.getElementById('generateBtnLoader');

            generateBtn.disabled = true;
            generateBtnText.classList.add('d-none');
            generateBtnLoader.classList.remove('d-none');

            try {
                const requestData = {
                    project_id: projectId,
                    report_type: report{{ __('messages.type') }}
                };

                if (report{{ __('messages.type') }} === 'weekly') {
                    requestData.week = week;
                }
                if (report{{ __('messages.type') }} === 'monthly') {
                    requestData.month = month;
                }

                const response = await api.generateReport(requestData);

                if (response.code === 200) {
                    currentReportData = response.data;
                    displayReportPreview(response.data);
                } else {
                    alert(response.message || 'Failed to generate report');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error generating report');
            } finally {
                generateBtn.disabled = false;
                generateBtnText.classList.remove('d-none');
                generateBtnLoader.classList.add('d-none');
            }
        }

        function displayCompletionGraphs(data) {
            const graphSection = document.getElementById('completionGraphSection');
            const graphsContainer = document.getElementById('completionGraphsContainer');

            graphSection.style.display = 'block';
            graphsContainer.innerHTML = '';

            const overallProgress = data.progress_percentage || 0;
            graphsContainer.innerHTML = createCircularGraph(
                Math.round(overallProgress),
                '{{ __('messages.completion_percentages') }}'
            );
        }

        function createCircularGraph(percentage, label) {
            const radius = 45;
            const circumference = 2 * Math.PI * radius;
            const strokeDashoffset = circumference - (percentage / 100) * circumference;

            return `
                <div class="circular-progress-container">
                    <div class="circular-progress-wrapper">
                        <svg class="circular-progress-svg" viewBox="0 0 120 120">
                            <defs>
                                <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#4477C4;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#F58D2E;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <circle class="circular-progress-circle circular-progress-bg" cx="60" cy="60" r="${radius}"></circle>
                            <circle class="circular-progress-circle circular-progress-fill" 
                                cx="60" cy="60" r="${radius}" 
                                style="stroke-dasharray: ${circumference}; stroke-dashoffset: ${strokeDashoffset};"></circle>
                        </svg>
                        <div class="circular-progress-text">
                            <div class="circular-progress-percentage">${percentage}%</div>
                        </div>
                    </div>
                    <div class="circular-progress-label">${label}</div>
                </div>
            `;
        }

        function displayReportPreview(data) {
            const reportPreview = document.getElementById('reportPreview');
            const reportContent = document.getElementById('reportContent');
            const previewReport{{ __('messages.type') }} = document.getElementById(
                'previewReport{{ __('messages.type') }}');

            previewReport{{ __('messages.type') }}.textContent = data.report_type.charAt(0).toUpperCase() + data
                .report_type.slice(1) + ' Progress Report';

            const formatDate = (dateStr) => {
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            };

            let html = `
                <table class="preview-info-table">
                    <tr>
                        <td class="preview-lbl-blue">{{ __('messages.project') }}:</td>
                        <td class="preview-val-white">${data.project.project_title || data.project.title || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td class="preview-lbl-blue">{{ __('messages.owner') }}:</td>
                        <td class="preview-val-white">${data.project.owner?.name || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td class="preview-lbl-blue">{{ __('messages.contractor') }}:</td>
                        <td class="preview-val-white">${data.project.contractor?.name || data.project.contractor_name || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td class="preview-lbl-orange">{{ __('messages.report_number') }}:</td>
                        <td class="preview-val-white">${data.report_number}</td>
                    </tr>
                    <tr>
                        <td class="preview-lbl-orange">{{ __('messages.period') }}:</td>
                        <td class="preview-val-white">{{ __('messages.from') }}: ${formatDate(data.date_range.start)} - {{ __('messages.to') }}: ${formatDate(data.date_range.end)}</td>
                    </tr>
                </table>
            `;

            // Completion Percentages Circular Graph
            // Find current phase (first one not 100% or last one)
            let currentPhase = null;
            if (data.phases && data.phases.length > 0) {
                currentPhase = data.phases.find(p => p.time_progress < 100) || data.phases[data.phases.length - 1];
            }

            const overallProgress = Math.round(data.progress_percentage || 0);
            const overallProgressDecimal = (data.progress_percentage || 0).toFixed(2);
            const radius = 60;
            const circumference = 2 * Math.PI * radius;
            const strokeDashoffset = circumference - (overallProgress / 100) * circumference;

            html += `
                <div class="preview-section-header preview-bg-blue">{{ __('messages.completion_percentages') }}</div>
                <div class="preview-bordered-box" style="padding: 30px; background-color: #fff;">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 50px; flex-wrap: wrap;">
                        <!-- Circular Chart -->
                        <div style="position: relative; width: 160px; height: 160px;">
                            <svg width="160" height="160" viewBox="0 0 160 160" style="transform: rotate(-90deg);">
                                <circle cx="80" cy="80" r="${radius}" fill="none" stroke="#e9ecef" stroke-width="15" />
                                <circle cx="80" cy="80" r="${radius}" fill="none" stroke="#4477C4" stroke-width="15" 
                                    stroke-dasharray="${circumference}" 
                                    stroke-dashoffset="${strokeDashoffset}" 
                                    stroke-linecap="round" />
                            </svg>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 20px; font-weight: bold; color: #4477C4;">
                                ${overallProgressDecimal}%
                            </div>
                        </div>

                        <!-- Text Info -->
                        <div style="flex: 1; min-width: 250px;">
                            <div style="font-size: 28px; font-weight: bold; color: #4477C4; margin-bottom: 5px;">
                                ${overallProgressDecimal}% {{ __('messages.complete') }}
                            </div>
                            <div style="color: #6c757d; font-size: 16px; margin-bottom: 20px;">
                                {{ __('messages.overall_project_progress') }}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Statement of Executed Works
            html += `
                <div class="preview-section-header preview-bg-blue">{{ __('messages.section_1') }}</div>
                <table class="preview-data-table">
                    <thead>
                        <tr>
                            <th style="width:5%">{{ __('messages.table_no') }}</th>
                            <th style="width:15%">{{ __('messages.type') }}</th>
                            <th style="width:35%">{{ __('messages.description_of_works') }}</th>
                            <th style="width:20%">{{ __('messages.location') }}</th>
                            <th style="width:25%">{{ __('messages.remarks') }}</th>
                        </tr>
                    </thead>
                    <tbody>`;

            if (data.tasks.length > 0 || data.inspections.length > 0 || data.snags.length > 0) {
                let counter = 1;

                data.tasks.forEach(task => {
                    const remark = task.status.charAt(0).toUpperCase() + task.status.slice(1);
                    html += `
                        <tr>
                            <td style="text-align:center">${counter++}</td>
                            <td>{{ __('messages.task') }}</td>
                            <td>${task.title}</td>
                            <td>${data.project.project_location || 'N/A'}</td>
                            <td>${remark}</td>
                        </tr>
                    `;
                });

                data.inspections.forEach(inspection => {
                    const remark = inspection.comment || '-';
                    html += `
                        <tr>
                            <td style="text-align:center">${counter++}</td>
                            <td>{{ __('messages.inspection') }}</td>
                            <td>${inspection.description}</td>
                            <td>${data.project.project_location || 'N/A'}</td>
                            <td>${remark}</td>
                        </tr>
                    `;
                });

                data.snags.forEach(snag => {
                    const remark = (snag.comments && snag.comments.length > 0) ?
                        snag.comments[0].comment :
                        (snag.comment || '-');
                    html += `
                        <tr>
                            <td style="text-align:center">${counter++}</td>
                            <td>{{ __('messages.snag') }}</td>
                            <td>${snag.title}</td>
                            <td>${snag.location || 'N/A'}</td>
                            <td>${remark}</td>
                        </tr>
                    `;
                });
            } else {
                html += `
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 15px;">{{ __('messages.no_data') }}</td>
                    </tr>
                `;
            }

            html += `
                    </tbody>
                </table>
            `;

            // Contractor Performance Evaluation
            html += `
                <div class="preview-section-header preview-bg-orange">{{ __('messages.section_2') }}</div>
                <table class="preview-data-table" style="border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #b0c4de;">
                        <td style="background-color: #fff5ec; width: 35%; padding: 15px; vertical-align: top; font-weight: 500;">
                            <span style="display: inline-block; background-color: #ea6b1e; color: white; width: 22px; height: 22px; text-align: center; line-height: 22px; border-radius: 3px; margin-right: 8px; font-weight: bold; font-size: 12px;">1</span>
                            {{ __('messages.quality_speed_work') }}:
                        </td>
                        <td style="padding: 15px; vertical-align: top; border-left: 1px solid #b0c4de;">`;

            if (data.quality_work && data.quality_work.length > 0) {
                html += '<ul class="preview-orange-list">';
                data.quality_work.forEach(item => {
                    html += `<li>${item.description}</li>`;
                });
                html += '</ul>';
            } else {
                html += '{{ __('messages.no_data') }}';
            }

            html += `</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #b0c4de;">
                        <td style="background-color: #fff5ec; padding: 15px; vertical-align: top; font-weight: 500;">
                            <span style="display: inline-block; background-color: #ea6b1e; color: white; width: 22px; height: 22px; text-align: center; line-height: 22px; border-radius: 3px; margin-right: 8px; font-weight: bold; font-size: 12px;">2</span>
                            {{ __('messages.adequacy_manpower') }}:
                        </td>
                        <td style="padding: 15px; vertical-align: top; border-left: 1px solid #b0c4de;">`;

            if (data.activities && data.activities.length > 0) {
                html += '<ul class="preview-orange-list">';
                data.activities.forEach(activity => {
                    html += `<li>${activity.description}</li>`;
                });
                html += '</ul>';
            } else {
                html += '{{ __('messages.no_data') }}';
            }

            html += `</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #b0c4de;">
                        <td style="background-color: #fff5ec; padding: 15px; vertical-align: top; font-weight: 500;">
                            <span style="display: inline-block; background-color: #ea6b1e; color: white; width: 22px; height: 22px; text-align: center; line-height: 22px; border-radius: 3px; margin-right: 8px; font-weight: bold; font-size: 12px;">3</span>
                            {{ __('messages.adequacy_equipment') }}:
                        </td>
                        <td style="padding: 15px; vertical-align: top; border-left: 1px solid #b0c4de;">`;

            if (data.manpower_equipment && data.manpower_equipment.length > 0) {
                html += '<ul class="preview-orange-list">';
                data.manpower_equipment.forEach(item => {
                    html += `<li>${item.category}: ${item.count} units</li>`;
                });
                html += '</ul>';
            } else {
                html += '{{ __('messages.no_data') }}';
            }

            html += `</td>
                    </tr>
                    <tr>
                        <td style="background-color: #fff5ec; padding: 15px; vertical-align: top; font-weight: 500;">
                            <span style="display: inline-block; background-color: #ea6b1e; color: white; width: 22px; height: 22px; text-align: center; line-height: 22px; border-radius: 3px; margin-right: 8px; font-weight: bold; font-size: 12px;">4</span>
                            {{ __('messages.adequacy_stored_materials') }}:
                        </td>
                        <td style="padding: 15px; vertical-align: top; border-left: 1px solid #b0c4de;">`;

            if (data.material_adequacy && data.material_adequacy.length > 0) {
                html += '<ul class="preview-orange-list">';
                data.material_adequacy.forEach(item => {
                    html += `<li>${item.description}</li>`;
                });
                html += '</ul>';
            } else {
                html += '{{ __('messages.no_data') }}';
            }

            html += `</td>
                    </tr>
                </table>
            `;

            // Overdue Tasks Section
            html += `
                <div class="preview-section-header preview-bg-gradients">
                    {{ __('messages.section_3') }}
                </div>
                <div class="preview-bordered-box">`;

            if (data.overdue_tasks && data.overdue_tasks.length > 0) {
                html += '<ul class="preview-orange-list">';
                data.overdue_tasks.forEach(task => {
                    const dueDate = new Date(task.due_date).toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                    html += `<li>${task.title} - Due: ${dueDate}</li>`;
                });
                html += '</ul>';
            } else {
                html += '<p>{{ __('messages.no_data') }}</p>';
            }

            html += `</div>`;

            // Meetings Section
            html += `
                <div class="preview-section-header preview-bg-gradient-reverse">
                    {{ __('messages.section_4') }}
                </div>
                <div class="preview-bordered-box">`;

            if (data.meetings && data.meetings.length > 0) {
                data.meetings.forEach((meeting, index) => {
                    const meetingDate = new Date(meeting.date).toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                    html += `
                        <div style="margin-bottom: ${index < data.meetings.length - 1 ? '20px' : '0'};">
                            <div style="margin-bottom: 8px;">
                                <span style="background-color: #102a4e; color: white; padding: 4px 12px; border-radius: 4px; font-weight: 500; font-size: 13px; display: inline-block;">{{ __('messages.meeting') }} #${index + 1}</span>
                                <span style="margin-left: 10px; font-weight: 600; font-size: 13px;">{{ __('messages.date') }}: ${meetingDate}</span>
                            </div>
                            <div style="padding-left: 5px; margin-top: 5px; font-size: 13px;">
                                <div style="margin-bottom: 4px;"><span style="font-weight: 700; color: #2c3e50;">{{ __('messages.attendees') }}:</span> ${meeting.attendees && meeting.attendees.length > 0 ? meeting.attendees.map(a => a.name).filter(Boolean).join(', ') : 'N/A'}</div>
                                ${meeting.description ? `<div style="margin-bottom: 4px;"><span style="font-weight: 700; color: #2c3e50;">{{ __('messages.agenda') }}:</span> ${meeting.description}</div>` : ''}
                                <div style="margin-bottom: 4px;"><span style="font-weight: 700; color: #2c3e50;">{{ __('messages.decisions') }}:</span> -</div>
                            </div>
                        </div>
                    `;
                });
            } else {
                html += '<p>{{ __('messages.no_data') }}</p>';
            }

            html += `</div>`;

            // Fifth: Statement of Materials Approved / Stored During the Week
            html += `
                <div class="preview-section-header preview-bg-blue">{{ __('messages.section_5') }}</div>
                <table class="preview-data-table">
                    <thead>
                        <tr>
                            <th style="width:5%">{{ __('messages.table_no') }}</th>
                            <th style="width:20%">{{ __('messages.type_of_material') }}</th>
                            <th style="width:15%">{{ __('messages.approved_storage') }}</th>
                            <th style="width:20%">{{ __('messages.place_of_use') }}</th>
                            <th style="width:15%">{{ __('messages.quantity_stored') }}</th>
                            <th style="width:25%">{{ __('messages.remarks') }}</th>
                        </tr>
                    </thead>
                    <tbody>`;

            if (data.raw_materials && data.raw_materials.length > 0) {
                data.raw_materials.forEach((material, index) => {
                    const status = material.status ? material.status.charAt(0).toUpperCase() + material.status
                        .slice(1) : '-';
                    html += `
                        <tr>
                            <td style="text-align:center">${index + 1}</td>
                            <td>${material.name || '-'}</td>
                            <td>${status}</td>
                            <td>-</td>
                            <td>${material.quantity || '-'}</td>
                            <td>${material.description || '-'}</td>
                        </tr>
                    `;
                });
            } else {
                html += `
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 15px;">{{ __('messages.no_data') }}</td>
                    </tr>
                `;
            }

            html += `
                    </tbody>
                </table>
            `;

            reportContent.innerHTML = html;
            reportPreview.classList.remove('d-none');
        }

        async function savePdf() {
            if (!currentReportData) return;

            const report{{ __('messages.type') }} = document.querySelector('input[name="report_type"]:checked').value;
            const week = document.getElementById('weekInput').value;
            const month = document.getElementById('monthInput').value;
            const general{{ __('messages.remarks') }} = document.getElementById('general{{ __('messages.remarks') }}')
                .value;
            const general{{ __('messages.remarks') }}File = document.getElementById(
                'general{{ __('messages.remarks') }}File').files[0];

            const makePdfBtn = document.getElementById('makePdfBtn');
            const pdfBtnText = document.getElementById('pdfBtnText');
            const pdfBtnLoader = document.getElementById('pdfBtnLoader');

            makePdfBtn.disabled = true;
            pdfBtnText.classList.add('d-none');
            pdfBtnLoader.classList.remove('d-none');

            try {
                const formData = new FormData();
                formData.append('project_id', projectId);
                formData.append('user_id', userId);
                formData.append('report_type', report{{ __('messages.type') }});
                formData.append('report_data', JSON.stringify(currentReportData));

                if (general{{ __('messages.remarks') }}) {
                    formData.append('general_remarks', general{{ __('messages.remarks') }});
                }

                if (general{{ __('messages.remarks') }}File) {
                    formData.append('general_remarks_attachment', general{{ __('messages.remarks') }}File);
                }

                if (report{{ __('messages.type') }} === 'weekly') {
                    formData.append('date_range', week);
                } else if (report{{ __('messages.type') }} === 'monthly') {
                    formData.append('date_range', month);
                }

                // Use makeFormDataRequest implicitly by passing FormData to a custom method or modifying api-client
                // Since api.saveReport expects data, we need to ensure it handles FormData or we use a direct call
                // Looking at api-client.js, saveReport calls makeRequest which defaults to JSON.
                // We need to use makeFormDataRequest. 
                // Let's assume we can modify api-client or just use a direct fetch here? 
                // Better to use the API client properly. 
                // The api.saveReport method in api-client.js uses makeRequest. We should check if we can change it to support FormData.
                // Actually, let's look at api-client.js again. 
                // It has makeFormDataRequest. We should probably update api-client.js to use makeFormDataRequest for saveReport if data is FormData.
                // But I can't change api-client.js easily from here without another tool call.
                // Wait, I can just use `api.makeFormDataRequest('reports/save', formData)` directly if I have access to the instance.
                // `window.api` is available.

                const response = await api.saveReport(formData);

                if (response.code === 200) {
                    toastr.success('{{ __('messages.pdf_generated_success') }}');
                    loadHistory();
                    document.getElementById('reportPreview').classList.add('d-none');
                    currentReportData = null;
                    document.getElementById('general{{ __('messages.remarks') }}').value = '';
                    document.getElementById('general{{ __('messages.remarks') }}File').value = '';
                } else {
                    toastr.error(response.message || 'Failed to save PDF');
                }
            } catch (error) {
                console.error('Error:', error);
                toastr.error('Error saving PDF');
            } finally {
                makePdfBtn.disabled = false;
                pdfBtnText.classList.remove('d-none');
                pdfBtnLoader.classList.add('d-none');
            }
        }

        async function loadHistory() {
            const historyContainer = document.getElementById('historyContainer');

            try {
                const response = await api.getReportHistory({
                    project_id: projectId
                });

                if (response.code === 200 && response.data.length > 0) {
                    historyContainer.innerHTML = response.data.map(report => `
                        <div class="card history-card border-0 rounded-3">
                            <div class="card-body py-3 px-4">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div class="d-flex flex-column gap-2">
                                        <h6 class="text-primary fw-bold mb-0 fs-5">${report.report_type.charAt(0).toUpperCase() + report.report_type.slice(1)} Report</h6>
                                        <div class="d-flex flex-column gap-1">
                                            <small class="text-muted d-flex align-items-center gap-2">
                                                <i class="far fa-user"></i>
                                                {{ __('messages.generated_by') }} ${report.generated_by?.name || 'N/A'}
                                            </small>
                                            <small class="text-muted d-flex align-items-center gap-2">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ __('messages.generation_date') }} ${new Date(report.created_at).toLocaleDateString()}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="/storage/${report.file_path}" download class="btn action-icon">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button class="btn action-icon" onclick="shareReport('${report.file_path}')">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    historyContainer.innerHTML = '<p class="text-center text-muted">No reports found</p>';
                }
            } catch (error) {
                console.error('Error:', error);
                historyContainer.innerHTML = '<p class="text-center text-danger">Error loading history</p>';
            }
        }

        function shareReport(filePath) {
            openShareReportModal(filePath);
        }
    </script>
@endsection
