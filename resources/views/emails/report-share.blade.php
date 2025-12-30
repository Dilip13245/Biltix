<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.report_shared') }}</title>
    <style>
        /* Reset & Basics */
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        /* Container */
        .email-wrapper {
            width: 100%;
            background-color: #f4f7fa;
            padding: 40px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .email-header {
            background-color: #102a4e;
            padding: 30px 40px;
            text-align: center;
            background-image: linear-gradient(135deg, #102a4e 0%, #1b3d6d 100%);
        }
        .logo-text {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;
        }

        /* Body */
        .email-body {
            padding: 40px;
            color: #333333;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #102a4e;
        }
        .message-text {
            font-size: 15px;
            line-height: 1.6;
            color: #555555;
            margin-bottom: 30px;
        }

        /* File Card */
        .file-card {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .file-detail-row {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
        }
        .file-detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #102a4e;
            width: 100px;
            flex-shrink: 0;
        }
        .detail-value {
            color: #333;
            flex-grow: 1;
        }

        /* Button */
        .action-button-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .action-button {
            display: inline-block;
            background-color: #F58D2E;
            color: #ffffff !important;
            font-size: 16px;
            font-weight: 600;
            padding: 14px 32px;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 6px rgba(245, 141, 46, 0.2);
        }
        .action-button:hover {
            background-color: #e07d22;
        }

        /* Footer */
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer-text {
            font-size: 12px;
            color: #888888;
            line-height: 1.5;
            margin: 0;
        }
        .footer-links {
            margin-top: 10px;
        }
        .footer-link {
            color: #4477C4;
            text-decoration: none;
            font-size: 12px;
            margin: 0 5px;
        }

        /* RTL Support */
        [dir="rtl"] .detail-label {
            margin-left: 10px;
        }
        [dir="rtl"] .email-header,
        [dir="rtl"] .action-button-container,
        [dir="rtl"] .email-footer {
            text-align: center;
        }
        
        /* Mobile Responsive */
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 25px;
            }
            .email-header {
                padding: 20px;
            }
            .file-detail-row {
                flex-direction: column;
            }
            .detail-label {
                width: 100%;
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <!-- You can replace text with an img tag if you have a hosted logo URL -->
                <a href="{{ url('/') }}" class="logo-text">BILTIX</a>
            </div>

            <!-- Body -->
            <div class="email-body">
                <div class="greeting">
                    {{ __('messages.hello') }} {{ $recipient_name }},
                </div>

                <div class="message-text">
                    {{ __('messages.report_shared_message') }}
                </div>

                <!-- File Details Card -->
                <div class="file-card">
                    <div class="file-detail-row">
                        <span class="detail-label">{{ __('messages.project') }}:</span>
                        <span class="detail-value">{{ $project_title }}</span>
                    </div>
                    <div class="file-detail-row">
                        <span class="detail-label">{{ __('messages.file_name') }}:</span>
                        <span class="detail-value">{{ $file_name }}</span>
                    </div>
                    <div class="file-detail-row">
                        <span class="detail-label">{{ __('messages.shared_by') }}:</span>
                        <span class="detail-value">{{ $sender_name }}</span>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="action-button-container">
                    <a href="{{ $file_url }}" class="action-button">
                        {{ __('messages.download_report') }}
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p class="footer-text">
                    &copy; {{ date('Y') }} BILTIX. {{ __('messages.all_rights_reserved') }}
                </p>
                <p class="footer-text" style="margin-top: 5px;">
                    {{ __('messages.email_footer_message') }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>
