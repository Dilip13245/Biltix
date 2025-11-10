<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.reset_password') }} - Biltix</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4A90E2 0%, #357ABD 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: white;
            border-radius: 50%;
            font-size: 36px;
            font-weight: bold;
            color: #4A90E2;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            line-height: 80px;
            text-align: center;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .message {
            color: #666;
            line-height: 1.8;
            font-size: 15px;
            margin-bottom: 30px;
        }
        .otp-box {
            background: linear-gradient(135deg, #F58D2E 0%, #FF8C42 100%);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 4px 15px rgba(245, 141, 46, 0.3);
        }
        .otp-label {
            color: white;
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        .otp-code {
            color: white;
            font-size: 42px;
            font-weight: bold;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .warning-box {
            background: #FFF3CD;
            border-{{ is_rtl() ? 'right' : 'left' }}: 4px solid #FFC107;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }
        .warning-box strong {
            color: #856404;
        }
        .expiry {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin: 20px 0;
        }
        .expiry strong {
            color: #F58D2E;
            font-weight: 600;
        }
        .footer {
            background: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 13px;
        }
        .footer .company {
            font-weight: 600;
            color: #4A90E2;
        }
        @media only screen and (max-width: 600px) {
            .container {
                border-radius: 0;
            }
            .header, .content, .footer {
                padding: 25px 20px;
            }
            .otp-code {
                font-size: 36px;
                letter-spacing: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td align="center">
                        <div class="logo">B</div>
                    </td>
                </tr>
            </table>
            <h1>@if(app()->getLocale() == 'ar') إعادة تعيين كلمة المرور @else Reset Password @endif</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                @if(app()->getLocale() == 'ar')
                    مرحباً {{ $user->name }}،
                @else
                    Hello {{ $user->name }},
                @endif
            </div>
            
            <div class="message">
                @if(app()->getLocale() == 'ar')
                    <p>لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بك. استخدم رمز OTP أدناه لإعادة تعيين كلمة المرور:</p>
                @else
                    <p>We received a request to reset your password. Use the OTP code below to reset your password:</p>
                @endif
            </div>
            
            <div class="otp-box">
                <div class="otp-label">@if(app()->getLocale() == 'ar') رمز OTP الخاص بك @else Your OTP Code @endif</div>
                <div class="otp-code">{{ $otp }}</div>
            </div>
            
            <div class="expiry">
                @if(app()->getLocale() == 'ar')
                    <strong>⏱ ينتهي صلاحية رمز OTP خلال 10 دقائق</strong>
                @else
                    <strong>⏱ This OTP expires in 10 minutes</strong>
                @endif
            </div>
            
            <div class="warning-box">
                @if(app()->getLocale() == 'ar')
                    <p><strong>⚠️ تنبيه أمني:</strong> إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذا البريد الإلكتروني وستبقى كلمة المرور الخاصة بك دون تغيير.</p>
                @else
                    <p><strong>⚠️ Security Notice:</strong> If you didn't request this password reset, please ignore this email and your password will remain unchanged.</p>
                @endif
            </div>
        </div>
        
        <div class="footer">
            <p class="company">Biltix</p>
            <p>&copy; {{ date('Y') }} @if(app()->getLocale() == 'ar') جميع الحقوق محفوظة @else All rights reserved @endif</p>
        </div>
    </div>
</body>
</html>
