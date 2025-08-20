<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification - Biltix</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b35;
        }
        .otp-code {
            background-color: #ff6b35;
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 5px;
        }
        .message {
            text-align: center;
            color: #666;
            line-height: 1.6;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">BILTIX</div>
            <h2>OTP Verification</h2>
        </div>
        
        <div class="message">
            <p>Your One-Time Password (OTP) for Biltix is:</p>
        </div>
        
        <div class="otp-code">
            {{ $otp }}
        </div>
        
        <div class="message">
            <p>This OTP is valid for 10 minutes. Please do not share this code with anyone.</p>
            <p>If you didn't request this OTP, please ignore this email.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Biltix. All rights reserved.</p>
        </div>
    </div>
</body>
</html>