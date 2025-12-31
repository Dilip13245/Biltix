<!DOCTYPE html>
<html lang="{{ $locale ?? app()->getLocale() }}" dir="{{ $is_rtl ?? app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('report.title', ['type' => __('report.' . $report_type)]) }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* --- CORE VARIABLES --- */
        :root {
            --brand-blue: #1b3d6d;
            --brand-orange: #ea6b1e;
            --border-color: #8faad9;
            --table-header-text: #ffffff;
            --bg-gray: #f2f2f2;
        }

        body {
            background-color: #e0e0e0;
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        [dir="rtl"] body {
            text-align: right;
        }

        [dir="ltr"] body {
            text-align: left;
        }

        /* --- A4 PAPER CONTAINER --- */
        .a4-page {
            width: 100%;
            min-height: 297mm;
            background: white;
            margin: 0 auto;
            position: relative;
            padding-bottom: 50px;
        }

        /* --- HEADER SECTION --- */
        .header-strip {
            height: 180px;
            background-color: #102a4e;
            background-image: url("{{ public_path('website/images/image.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        [dir="rtl"] .header-strip {
            background-image: url("{{ public_path('website/images/image.png') }}");
            transform: scaleX(-1);
        }

        [dir="rtl"] .header-content {
            transform: scaleX(-1);
            color: white;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Geometric Pattern Overlay */
        .header-pattern {
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

        .header-content {
            z-index: 2;
            width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .brand-logo {
            position: absolute;
            {{ $is_rtl ?? false ? 'right' : 'left' }}: 45px;
            bottom: 20px;
            z-index: 10;
        }

        .brand-logo img {
            height: 59px;
            width: auto;
        }

        .center-title-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-top: 10px;
        }

        .title-icon-circle {
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

        .header-title {
            font-size: 24px;
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            text-transform: none;
            color: white;
        }

        .title-underline {
            width: 80px;
            height: 4px;
            background: radial-gradient(circle, #ea6b1e 0%, transparent 100%);
            border-radius: 50%;
            opacity: 0.8;
        }

        /* --- CONTENT PADDING --- */
        .page-content {
            padding: 25px 40px;
        }

        /* --- TOP INFO TABLE (Label | Value) --- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
            border: 2px solid var(--brand-blue);
        }

        .info-table td {
            padding: 12px 15px;
            border: 1px solid var(--brand-blue);
        }

        [dir="rtl"] .info-table td {
            text-align: right;
        }

        [dir="rtl"] .info-table .lbl-blue,
        [dir="rtl"] .info-table .lbl-orange {
            text-align: center !important;
        }

        /* The Colored Labels */
        .lbl-blue {
            background-color: var(--brand-blue);
            color: white;
            width: 30%;
            font-weight: 500;
            text-align: center;
            vertical-align: middle;
        }

        .lbl-orange {
            background-color: var(--brand-orange);
            color: white;
            width: 30%;
            font-weight: 500;
            text-align: center;
            vertical-align: middle;
        }

        .val-white {
            background-color: white;
            color: #000;
            font-weight: 500;
        }

        [dir="rtl"] .val-white {
            padding-right: 20px;
        }

        /* --- SECTION HEADERS --- */
        .section-header {
            color: white;
            font-weight: 600;
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .section-header {
            padding: 12px 20px;
        }

        .bg-blue {
            background-color: var(--brand-blue);
            background-image: url("{{ public_path('website/images/section_bg.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        [dir="rtl"] .header-strip {
            background-image: url("{{ public_path('website/images/image.png') }}");
            transform: scaleX(-1);
        }

        [dir="rtl"] .header-content {
            transform: scaleX(-1);
        }

        .bg-orange {
            background-color: var(--brand-orange);
            background-image: url("{{ public_path('website/images/section_bg_org.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        [dir="rtl"] .header-strip {
            background-image: url("{{ public_path('website/images/image.png') }}");
            transform: scaleX(-1);
        }

        [dir="rtl"] .header-content {
            transform: scaleX(-1);
        }

        .bg-gradients {
            background-color: #102a4e;
            background: linear-gradient(90deg, #102a4e 0%, #ea6b1e 100%);
        }

        .bg-gradient-reverse {
            background-color: #ea6b1e;
            background: linear-gradient(90deg, #ea6b1e 0%, #102a4e 100%);
        }

        .bordered-box {
            border: 2px solid var(--brand-blue);
            padding: 20px;
            padding-right: {{ $is_rtl ?? false ? '25px' : '20px' }};
            padding-left: {{ $is_rtl ?? false ? '20px' : '25px' }};
            margin-bottom: 20px;
            overflow: hidden;
        }

        /* --- STANDARD DATA TABLES --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .data-table th {
            background-color: var(--brand-blue);
            color: white;
            padding: 8px;
            padding-right: {{ $is_rtl ?? false ? '25px' : '8px' }};
            padding-left: {{ $is_rtl ?? false ? '8px' : '25px' }};
            font-weight: 500;
            border: 1px solid white;
        }

        .data-table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            padding-right: {{ $is_rtl ?? false ? '20px' : '8px' }};
            padding-left: {{ $is_rtl ?? false ? '8px' : '20px' }};
            vertical-align: middle;
        }



        .data-table td[colspan],
        .no-data-cell {
            text-align: center !important;
        }

        .col-no {
            width: 5%;
            text-align: center;
        }

        .col-desc {
            width: 45%;
        }

        .col-loc {
            width: 25%;
        }

        .col-rem {
            width: 25%;
        }

        /* --- PERFORMANCE TABLE --- */
        .perf-table {
            width: 100%;
            border: 1px solid var(--brand-orange);
            border-collapse: collapse;
            font-size: 13px;
        }

        .perf-table tr {
            border-bottom: 1px solid #b0c4de;
        }

        .perf-table tr:last-child {
            border-bottom: none;
        }

        .perf-label-cell {
            background-color: #fff5ec;
            width: 45%;
            padding: 15px;
            padding-right: {{ $is_rtl ?? false ? '25px' : '15px' }};
            padding-left: {{ $is_rtl ?? false ? '15px' : '25px' }};
            vertical-align: top;
            color: #333;
            font-weight: 500;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .perf-value-cell {
            background-color: white;
            padding: 15px;
            padding-right: {{ $is_rtl ?? false ? '25px' : '15px' }};
            padding-left: {{ $is_rtl ?? false ? '15px' : '25px' }};
            vertical-align: top;
            line-height: 1.5;
            color: #333;
            text-align: inherit;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-all;
        }

        html[dir="ltr"] .perf-value-cell {
            border-left: 1px solid #b0c4de;
        }

        html[dir="rtl"] .perf-value-cell {
            border-right: 1px solid #b0c4de;
        }

        .num-badge {
            display: inline-block;
            background-color: var(--brand-orange);
            color: white;
            width: 22px;
            height: 22px;
            text-align: center;
            line-height: 22px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }

        html[dir="ltr"] .num-badge {
            margin-right: 8px;
        }

        html[dir="rtl"] .num-badge {
            margin-left: 8px;
        }

        /* --- MEETINGS SECTION --- */
        .meeting-block {
            margin-bottom: 20px;
        }

        .meeting-block:last-child {
            margin-bottom: 0;
        }

        .meeting-badge {
            background-color: #102a4e;
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 13px;
            display: inline-block;
        }

        .meeting-details {
            margin-top: 5px;
            font-size: 13px;
        }

        html[dir="ltr"] .meeting-details {
            padding-left: 5px;
        }

        html[dir="rtl"] .meeting-details {
            padding-right: 5px;
            text-align: right;
        }

        .meeting-details div {
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .detail-label {
            font-weight: 700;
            color: #2c3e50;
        }

        html[dir="ltr"] .detail-label {
            margin-right: 2px;
        }

        html[dir="rtl"] .detail-label {
            margin-left: 2px;
        }

        .bg-dark-gred {
            background-color: #1f2d3d;
            background: linear-gradient(90deg, #2c3e50 0%, #1f2d3d 100%);
        }

        /* --- MEETINGS TABLE (wkhtmltopdf Safe) --- */
        .meeting-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 13px;
            page-break-inside: avoid;
        }

        .meeting-header {
            padding-bottom: 8px;
        }

        .meeting-badge {
            background: #102a4e;
            color: #fff;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 13px;
            display: inline-block;
        }

        .meeting-date {
            font-weight: 600;
            font-size: 13px;
        }

        html[dir="ltr"] .meeting-date {
            margin-left: 10px;
        }

        html[dir="rtl"] .meeting-date {
            margin-right: 10px;
        }

        .meeting-label {
            width: 120px;
            font-weight: 700;
            color: #2c3e50;
            vertical-align: top;
            padding: 4px 0;
        }

        .meeting-value {
            padding: 4px 10px;
            vertical-align: top;
        }

        /* --- BULLET LISTS (Orange Dots) --- */
        .orange-list {
            list-style: none;
            padding: 0;
            font-size: 12px;
        }

        .orange-list li {
            position: relative;
            margin-bottom: 5px;
        }

        html[dir="ltr"] .orange-list li {
            padding-left: 15px;
        }

        html[dir="rtl"] .orange-list li {
            padding-right: 15px;
        }

        .orange-list li::before {
            content: "■";
            color: var(--brand-orange);
            font-size: 8px;
            position: absolute;
            top: 5px;
        }

        html[dir="ltr"] .orange-list li::before {
            left: 2px;
        }

        html[dir="rtl"] .orange-list li::before {
            right: 2px;
        }

        /* --- FOOTER SECTION --- */
        .footer-strip {
            height: 60px;
            background-color: var(--brand-blue);
            background-image: url("{{ public_path('website/images/footer_bg.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        [dir="rtl"] .header-strip {
            background-image: url("{{ public_path('website/images/image.png') }}");
            transform: scaleX(-1);
        }

        [dir="rtl"] .header-content {
            transform: scaleX(-1);
            position: relative;
            bottom: 0;
            left: 0;
            right: 0;
            overflow: hidden;
        }

        .signature-box {
            border: 2px solid var(--brand-blue);
            padding: 20px 30px;
            display: table;
            width: 100%;
            background-color: white;
        }

        .sig-field {
            display: table-cell;
            width: 33%;
            vertical-align: top;
        }

        .sig-field>div {
            margin-bottom: 15px;
        }

        .sig-label {
            color: #555;
            font-size: 14px;
        }

        .sig-value {
            font-weight: 300;
            font-size: 14px;
            color: #000;
        }

        .sig-line-container {
            width: 250px;
            border-bottom: 2px solid #6c85a3;
            padding-bottom: 5px;
            margin-top: 5px;
        }

        .signed-text {
            font-style: italic;
            color: #8faad9;
            font-size: 15px;
        }

        .page-footer-text {
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            margin-top: 40px;
            border-top: 2px solid #dbe2e8;
            padding-top: 15px;
            line-height: 1.6;
        }

        [dir="rtl"] .bordered-box,




        @media print {
            body {
                background: none;
                margin: 0;
                padding: 0;
            }

            .a4-page {
                box-shadow: none;
                margin: 0;
                width: 100%;
                height: auto;
            }

            .section-header,
            .lbl-blue,
            .lbl-orange,
            .header-strip,
            .footer-strip,
            .perf-icon,
            .bg-gradients,
            .bg-gradient-reverse,
            .bg-dark-gred,
            .perf-label-cell,
            .num-badge,
            .meeting-badge {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>

    <div class="a4-page">
        <div class="header-strip">
            <div class="header-pattern"></div>
            <div class="header-content">
                <div class="brand-logo">
                    <img src="{{ public_path('website/images/icons/logo.svg') }}" alt="BILTIX Logo">
                </div>
                <div class="center-title-block">
                    <div class="title-icon-circle">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="header-title">{{ __('report.title', ['type' => __('report.' . $report_type)]) }}</div>
                    <div class="title-underline"></div>
                </div>
            </div>
        </div>

        <div class="page-content">

            <table class="info-table">
                <tr>
                    <td class="lbl-blue">{{ __('report.project') }}:</td>
                    <td class="val-white">{{ $project['project_title'] ?? ($project['title'] ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <td class="lbl-blue">{{ __('report.owner') }}:</td>
                    <td class="val-white">{{ $project['owner']['name'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="lbl-blue">{{ __('report.contractor') }}:</td>
                    <td class="val-white">
                        {{ $project['contractor']['name'] ?? ($project['contractor_name'] ?? 'N/A') }}
                    </td>
                </tr>
                <tr>
                    <td class="lbl-orange" style="border-bottom: none;">{{ __('report.report_number') }}:</td>
                    <td class="val-white" style="border-bottom: none;">{{ $report_number }}</td>
                </tr>
                <tr>
                    <td class="lbl-orange" style="border-top: none;">{{ __('report.week_period') }}:</td>
                    <td class="val-white" style="border-top: none;">{{ __('report.from') }}:
                        {{ \Carbon\Carbon::parse($date_range['start'])->format('d/m/Y') }} - {{ __('report.to') }}:
                        {{ \Carbon\Carbon::parse($date_range['end'])->format('d/m/Y') }}</td>
                </tr>
            </table>

            <!-- Completion Percentages Section -->
            <div class="section-header bg-blue"
                style="padding-right: {{ $is_rtl ?? false ? '40px' : '20px' }}; padding-left: 20px;">
                {{ __('messages.completion_percentages') }}
            </div>

            <div class="bordered-box" style="padding: 30px; background-color: #fff;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 50px; flex-wrap: wrap;">
                    @php
                        $overallProgress = number_format($progress_percentage ?? 0, 2);
                        $radius = 60;
                        $circumference = 2 * pi() * $radius;
                        $strokeDashoffset = $circumference - (($progress_percentage ?? 0) / 100) * $circumference;
                    @endphp

                    <!-- Circular Chart -->
                    <div style="position: relative; width: 160px; height: 160px;">
                        <svg width="160" height="160" viewBox="0 0 160 160" style="transform: rotate(-90deg);">
                            <circle cx="80" cy="80" r="60" fill="none" stroke="#e9ecef"
                                stroke-width="15" />
                            <circle cx="80" cy="80" r="60" fill="none" stroke="#4477C4"
                                stroke-width="15" stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $strokeDashoffset }}" stroke-linecap="round" />
                        </svg>
                        <div
                            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 20px; font-weight: bold; color: #4477C4;">
                            {{ $overallProgress }}%
                        </div>
                    </div>

                    <!-- Text Info -->
                    <div style="flex: 1; min-width: 250px;">
                        <div style="font-size: 28px; font-weight: bold; color: #4477C4; margin-bottom: 5px;">
                            {{ $overallProgress }}% {{ __('messages.complete') }}
                        </div>
                        <div style="color: #6c757d; font-size: 16px; margin-bottom: 20px;">
                            {{ __('messages.overall_project_progress') }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                style="background-color: #fff5ec; border-{{ $is_rtl ?? false ? 'right' : 'left' }}: 5px solid var(--brand-orange); padding: 12px 20px; margin: 20px 0; font-style: italic; color: #555; font-size: 14px; text-align: {{ $is_rtl ?? false ? 'right' : 'left' }};">
                {{ __('report.for_use_by') }}
            </div>

            <div class="section-header bg-blue"
                style="padding-right: {{ $is_rtl ?? false ? '40px' : '20px' }}; padding-left: 20px;">
                {{ __('report.section_1') }}</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="col-no">{{ __('report.table_no') }}</th>
                        <th>{{ __('messages.type') }}</th>
                        <th class="col-desc">{{ __('report.description_of_works') }}</th>
                        <th class="col-loc">{{ __('report.location') }}</th>
                        <th class="col-rem">{{ __('report.remarks') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($tasks) > 0 || count($inspections) > 0 || count($snags) > 0)
                        @php $counter = 1; @endphp
                        @foreach ($tasks as $task)
                            <tr>
                                <td class="text-center">{{ $counter++ }}</td>
                                <td>{{ __('messages.task') }}</td>
                                <td>{{ $task['title'] }}</td>
                                <td>{{ $project->project_location ?? 'N/A' }}</td>
                                <td>{{ ucfirst($task['status']) }}</td>
                            </tr>
                        @endforeach
                        @foreach ($inspections as $inspection)
                            <tr>
                                <td class="text-center">{{ $counter++ }}</td>
                                <td>{{ __('messages.inspection') }}</td>
                                <td>{{ $inspection['description'] }}</td>
                                <td>{{ $project->project_location ?? 'N/A' }}</td>
                                <td>{{ $inspection['comment'] ?: '-' }}</td>
                            </tr>
                        @endforeach
                        @foreach ($snags as $snag)
                            <tr>
                                <td class="text-center">{{ $counter++ }}</td>
                                <td>{{ __('messages.snag') }}</td>
                                <td>{{ $snag['title'] }}</td>
                                <td>{{ $snag['location'] ?? 'N/A' }}</td>
                                <td>{{ $snag['comments'][0]['comment'] ?? ($snag['comment'] ?? '-') }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="no-data-cell"
                                style="text-align:center !important; padding: 15px;">{{ __('report.no_data') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="section-header bg-orange"
                style="padding-right: {{ $is_rtl ?? false ? '40px' : '20px' }}; padding-left: 20px;">
                {{ __('report.section_2') }}</div>

            <table class="perf-table">
                <tr>
                    <td class="perf-label-cell" style="direction: ltr; text-align: left;">
                        <span class="num-badge">1</span> Quality and Speed of Work:
                    </td>
                    <td class="perf-value-cell">
                        @if (count($quality_work) > 0)
                            <ul class="orange-list">
                                @foreach ($quality_work as $item)
                                    <li>{{ $item['description'] }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ __('report.no_data') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="perf-label-cell" style="direction: ltr; text-align: left;">
                        <span class="num-badge">2</span> Adequacy of Manpower:
                    </td>
                    <td class="perf-value-cell">
                        @if (count($activities) > 0)
                            <ul class="orange-list">
                                @foreach ($activities as $activity)
                                    <li>{{ $activity['description'] }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ __('report.no_data') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="perf-label-cell" style="direction: ltr; text-align: left;">
                        <span class="num-badge">3</span> Adequacy of Equipment:
                    </td>
                    <td class="perf-value-cell">
                        @if (count($manpower_equipment) > 0)
                            <ul class="orange-list">
                                @foreach ($manpower_equipment as $item)
                                    <li>{{ $item['category'] }}: {{ $item['count'] }} {{ __('report.units') }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ __('report.no_data') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="perf-label-cell" style="direction: ltr; text-align: left;">
                        <span class="num-badge">4</span> Adequacy of Stored Materials:
                    </td>
                    <td class="perf-value-cell">
                        @if (count($material_adequacy) > 0)
                            <ul class="orange-list">
                                @foreach ($material_adequacy as $item)
                                    <li>{{ $item['description'] }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ __('report.no_data') }}
                        @endif
                    </td>
                </tr>
            </table>

            <div class="section-header bg-gradients"
                style="padding-right: {{ $is_rtl ?? false ? '40px' : '20px' }}; padding-left: 20px;">
                {{ __('report.section_3') }}</div>
            <div class="bordered-box">
                @if (count($overdue_tasks) > 0)
                    <ul class="orange-list">
                        @foreach ($overdue_tasks as $task)
                            <li>{{ $task['title'] }} - {{ __('report.due') }}:
                                {{ \Carbon\Carbon::parse($task['due_date'])->format('d/m/Y') }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>{{ __('report.no_data') }}</p>
                @endif
            </div>

            <div class="section-header bg-gradient-reverse"
                style="padding-right: {{ $is_rtl ?? false ? '40px' : '20px' }}; padding-left: 20px;">
                {{ __('report.section_4') }}</div>
            <div class="bordered-box">
                @if (count($meetings) > 0)
                    @foreach ($meetings as $index => $meeting)
                        <table class="meeting-table">
                            <tr>
                                <td colspan="2" class="meeting-header">
                                    <span class="meeting-badge">{{ __('report.meeting') }}
                                        #{{ $index + 1 }}</span>
                                    <span class="meeting-date">{{ __('report.date') }}:
                                        {{ \Carbon\Carbon::parse($meeting['date'])->format('d/m/Y') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="meeting-label">{{ __('report.attendees') }}:</td>
                                <td class="meeting-value">
                                    {{ collect($meeting['attendees'])->pluck('name')->join(', ') ?: 'N/A' }}</td>
                            </tr>
                            @if ($meeting['description'])
                                <tr>
                                    <td class="meeting-label">{{ __('report.agenda') }}:</td>
                                    <td class="meeting-value">{{ $meeting['description'] }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="meeting-label">{{ __('report.decisions') }}:</td>
                                <td class="meeting-value">-</td>
                            </tr>
                        </table>
                    @endforeach
                @else
                    <p>{{ __('report.no_data') }}</p>
                @endif
            </div>

            <div class="section-header bg-blue"
                style="padding-right: {{ $is_rtl ?? false ? '40px' : '20px' }}; padding-left: 20px;">
                {{ __('report.section_5') }}</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="col-no">{{ __('report.table_no') }}</th>
                        <th>{{ __('report.type_of_material') }}</th>
                        <th>{{ __('report.approved_storage') }}</th>
                        <th>{{ __('report.place_of_use') }}</th>
                        <th>{{ __('report.quantity_stored') }}</th>
                        <th>{{ __('report.remarks') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($raw_materials) > 0)
                        @foreach ($raw_materials as $index => $material)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $material['name'] }}</td>
                                <td>{{ ucfirst($material['status']) }}</td>
                                <td>-</td>
                                <td>{{ $material['quantity'] }}</td>
                                <td>{{ $material['description'] ?: '-' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="no-data-cell"
                                style="text-align:center !important; padding: 15px;">{{ __('report.no_data') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="section-header bg-dark-gred"
                style="padding-right: {{ $is_rtl ?? false ? '40px' : '20px' }}; padding-left: 20px;">
                {{ __('report.section_6') }}</div>
            <div class="bordered-box">
                @if (!empty($general_remarks))
                    <p>{{ $general_remarks }}</p>
                @endif

                @if (!empty($general_remarks_attachment))
                    <p><strong>{{ __('report.attachment') }}:</strong> {{ __('report.attachment_text') }}</p>
                    @php
                        $extension = pathinfo($general_remarks_attachment, PATHINFO_EXTENSION);
                    @endphp
                    @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                        <div style="margin-top: 10px; text-align: center;">
                            <img src="{{ public_path('storage/' . $general_remarks_attachment) }}"
                                style="max-width: 100%; max-height: 400px;" alt="Attachment">
                        </div>
                    @endif
                @endif

                @if (empty($general_remarks) && empty($general_remarks_attachment))
                    <p>{{ __('report.no_data') }}</p>
                @endif
            </div>

            <div class="section-header bg-blue"
                style="margin-bottom: 0; padding-right: {{ $is_rtl ?? false ? '40px' : '20px' }}; padding-left: 20px;">
                {{ __('report.site_engineer') }}</div>
            <div class="signature-box">
                <div class="sig-field" style="text-align: center;">
                    <div class="sig-label" style="margin-bottom: 8px;">{{ __('report.name') }}:</div>
                    <div class="sig-value">{{ $site_engineer_name ?? 'N/A' }}</div>
                </div>
                <div class="sig-field" style="text-align: center;">
                    <div class="sig-label" style="margin-bottom: 8px;">{{ __('report.signature') }}:</div>
                    <div class="sig-line-container" style="margin: 0 auto;">
                        <span class="signed-text">{{ __('report.signed') }}</span>
                    </div>
                </div>
                <div class="sig-field" style="text-align: center;">
                    <div class="sig-label" style="margin-bottom: 8px;">{{ __('report.date') }}:</div>
                    <div class="sig-value">{{ $signature_date ?? now()->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="page-footer-text">
                <div>{{ __('report.footer_text') }}</div>
                <div>{{ __('report.report_no') }}: {{ $report_number }}</div>
            </div>

        </div>

        <div class="footer-strip">
            <div class="header-pattern"></div>
        </div>
    </div>

</body>

</html>
