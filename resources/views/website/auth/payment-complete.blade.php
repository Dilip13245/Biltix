<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('auth.completing_registration') }} - Biltix</title>
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .completion-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 48px;
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .status-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 40px;
        }

        .status-icon.loading {
            background: linear-gradient(135deg, #4A90E2, #5C9CE5);
            color: white;
            animation: pulse 1.5s ease-in-out infinite;
        }

        .status-icon.success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        .status-icon.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .status-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .status-message {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .btn-dashboard {
            background: linear-gradient(135deg, #FF8C42, #FF6B35);
            border: none;
            color: white;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 140, 66, 0.4);
            color: white;
        }

        .btn-retry {
            background: #f3f4f6;
            border: none;
            color: #4b5563;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-retry:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .subscription-info {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
            text-align: {{ is_rtl() ? 'right' : 'left' }};
        }

        .subscription-info h4 {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .subscription-info .plan-name {
            font-size: 20px;
            font-weight: 700;
            color: #4A90E2;
        }

        .subscription-info .expires {
            font-size: 14px;
            color: #6b7280;
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <div class="completion-card">
        <!-- Loading State -->
        <div id="loadingState">
            <div class="status-icon loading">
                <i class="fas fa-circle-notch spinner"></i>
            </div>
            <h2 class="status-title">{{ __('auth.processing_payment') }}</h2>
            <p class="status-message">{{ __('auth.please_wait') }}</p>
        </div>

        <!-- Success State -->
        <div id="successState" style="display: none;">
            <div class="status-icon success">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="status-title">{{ __('auth.registration_successful') }}!</h2>
            <p class="status-message">{{ __('auth.welcome_to_biltix') }}</p>
            
            <div class="subscription-info" id="subscriptionInfo"></div>
            
            <a href="/dashboard" class="btn-dashboard">
                <i class="fas fa-arrow-right me-2"></i>
                {{ __('auth.go_to_dashboard') }}
            </a>
        </div>

        <!-- Error State -->
        <div id="errorState" style="display: none;">
            <div class="status-icon error">
                <i class="fas fa-times"></i>
            </div>
            <h2 class="status-title">{{ __('auth.payment_failed') }}</h2>
            <p class="status-message" id="errorMessage"></p>
            
            <a href="/register" class="btn-retry">
                <i class="fas fa-redo me-2"></i>
                {{ __('auth.try_again') }}
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('website/js/api.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            // Get URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const paymentId = urlParams.get('id');
            const token = urlParams.get('token');
            const status = urlParams.get('status');

            // If payment failed at Moyasar level
            if (status === 'failed') {
                showError('{{ __("auth.payment_declined") }}');
                return;
            }

            // If missing required params
            if (!paymentId || !token) {
                showError('{{ __("auth.invalid_payment_callback") }}');
                return;
            }

            try {
                // Complete registration via API
                const response = await fetch('/api/v1/payment/complete_registration', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'api-key': '{{ config("constant.API_KEY") }}',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        payment_id: paymentId,
                        token: token
                    })
                });

                const data = await response.json();

                if (data.code === 200) {
                    // Store session data
                    const userData = data.data;
                    
                    sessionStorage.setItem('user', JSON.stringify(userData));
                    sessionStorage.setItem('user_id', userData.id);
                    sessionStorage.setItem('token', userData.token);
                    sessionStorage.setItem('browser_session_active', 'true');

                    // Set Laravel session
                    await fetch('/auth/set-session', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
                        body: JSON.stringify({
                            user_id: userData.id,
                            token: userData.token,
                            user: userData
                        })
                    });

                    // Show success
                    showSuccess(userData);
                } else {
                    showError(data.message || '{{ __("auth.registration_error") }}');
                }
            } catch (error) {
                console.error('Registration completion error:', error);
                showError('{{ __("auth.connection_error") }}');
            }
        });

        function showSuccess(userData) {
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('successState').style.display = 'block';

            // Show subscription info if available
            if (userData.subscription) {
                document.getElementById('subscriptionInfo').innerHTML = `
                    <h4>{{ __('auth.your_subscription') }}</h4>
                    <div class="plan-name">${userData.subscription.plan_name} Plan</div>
                    <div class="expires">{{ __('auth.valid_until') }}: ${userData.subscription.expires_at}</div>
                `;
            }

            // Auto redirect after 3 seconds
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 3000);
        }

        function showError(message) {
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('errorState').style.display = 'block';
            document.getElementById('errorMessage').textContent = message;
        }
    </script>
</body>

</html>
