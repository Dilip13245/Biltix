<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.renew_subscription') }} - Biltix</title>
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Moyasar -->
    <script src="https://cdn.moyasar.com/mpf/1.14.0/moyasar.js"></script>
    <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.14.0/moyasar.css">

    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            padding: 32px;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .auth-card {
                padding: 24px;
                max-width: 100%;
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo {
            width: 56px;
            height: 56px;
            background: #FF8C42;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .logo-text {
            color: #4A90E2;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 14px;
            text-align: center;
            margin-bottom: 24px;
        }

        /* Status Banners */
        .status-banner {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .status-banner.expired {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1px solid #f87171;
            color: #dc2626;
        }

        .status-banner.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            color: #92400e;
        }

        .status-banner i {
            font-size: 24px;
        }

        /* Plan Cards */
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .plan-card {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .plan-card:hover {
            border-color: #4A90E2;
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(74, 144, 226, 0.15);
        }

        .plan-card.selected {
            border-color: #FF8C42;
            background: #fff7ed;
        }

        .plan-card.current::after {
            content: '{{ __("messages.current_plan") }}';
            position: absolute;
            top: -10px;
            {{ is_rtl() ? 'left' : 'right' }}: 16px;
            background: #FF8C42;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .plan-name {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .plan-price {
            font-size: 28px;
            font-weight: 800;
            color: #FF8C42;
            margin-bottom: 4px;
        }

        .plan-price small {
            font-size: 14px;
            color: #6b7280;
            font-weight: 400;
        }

        .plan-description {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .plan-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .plan-features li {
            padding: 6px 0;
            color: #374151;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .plan-features li i.fa-check-circle {
            color: #10b981;
        }

        .plan-features li i.fa-times-circle {
            color: #ef4444;
        }

        .plan-features li.disabled {
            color: #9ca3af;
        }

        /* Payment Section */
        .payment-section {
            background: #f9fafb;
            border-radius: 12px;
            padding: 24px;
            margin-top: 24px;
            display: none;
        }

        .payment-section.active {
            display: block;
        }

        .payment-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Buttons */
        .btn-primary-custom {
            background: #4A90E2;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            background: #3b7dd8;
            color: white;
        }

        .btn-primary-custom:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .btn-orange {
            background: #FF8C42;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s ease;
        }

        .btn-orange:hover {
            background: #e67a32;
            color: white;
        }

        .back-link {
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 24px;
        }

        .back-link:hover {
            color: #4A90E2;
        }

        /* Loading */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.hidden {
            display: none;
        }
    </style>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
            <p class="text-muted">{{ __('messages.loading') ?? 'Loading' }}...</p>
        </div>
    </div>

    <div class="auth-card">
        <!-- Back Link -->
        <a href="{{ route('dashboard') }}" class="back-link">
            <i class="fas fa-arrow-{{ is_rtl() ? 'right' : 'left' }}"></i>
            {{ __('messages.back_to_dashboard') ?? 'Back to Dashboard' }}
        </a>

        <!-- Logo -->
        <div class="logo-container">
            <div class="logo">
                <img src="{{ asset('website/images/icons/logo.svg') }}" alt="Logo" style="width: 32px; height: 32px;">
            </div>
            <div class="logo-text">BILTIX</div>
        </div>

        <!-- Status Banner (Loaded via API) -->
        <div id="statusBanner"></div>

        <!-- Subtitle -->
        <p class="subtitle">{{ __('messages.select_plan_to_renew') }}</p>

        <!-- Plans Grid (Loaded via API) -->
        <div class="plans-grid" id="plansGrid">
            <div class="text-center py-4">
                <div class="spinner-border text-primary"></div>
                <p class="text-muted mt-2">{{ __('messages.loading') ?? 'Loading' }}...</p>
            </div>
        </div>

        <!-- Proceed Button -->
        <button class="btn-orange" id="proceedBtn" disabled onclick="showPayment()">
            <i class="fas fa-credit-card me-2"></i>{{ __('messages.proceed_to_payment') ?? 'Proceed to Payment' }}
        </button>

        <!-- Payment Section -->
        <div class="payment-section" id="paymentSection">
            <div class="payment-title">
                <i class="fas fa-lock text-success"></i>
                {{ __('auth.secure_payment') ?? 'Secure Payment' }}
            </div>
            <p class="text-muted mb-3" id="selectedPlanSummary"></p>
            <div id="moyasar-form"></div>
            <button class="btn btn-secondary w-100 mt-3" onclick="hidePayment()">
                {{ __('messages.cancel') ?? 'Cancel' }}
            </button>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <small class="text-muted">
                {{ __('auth.need_help') ?? 'Need help?' }} 
                <a href="mailto:support@biltix.com" class="text-primary">{{ __('auth.contact_support') ?? 'Contact Support' }}</a>
            </small>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/api-helpers.js') }}"></script>
    <script src="{{ asset('website/js/universal-auth.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}?v={{ time() }}"></script>

    <script>
        const currentUserId = {{ $currentUser->id ?? 'null' }};
        let selectedPlanId = null;
        let selectedPlanName = '';
        let selectedPlanPrice = 0;
        let subscriptionInfo = null;
        let plans = [];

        // Toastr config
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-{{ is_rtl() ? 'left' : 'right' }}",
            timeOut: 3000
        };

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', async function() {
            await loadSubscriptionStatus();
            await loadPlans();
            document.getElementById('loadingOverlay').classList.add('hidden');
        });

        // Load subscription status from API
        async function loadSubscriptionStatus() {
            try {
                const response = await api.checkSubscriptionExpiry({
                    user_id: currentUserId
                });

                if (response.code === 200 && response.data) {
                    subscriptionInfo = response.data;
                    renderStatusBanner(subscriptionInfo);
                }
            } catch (error) {
                console.error('Failed to load subscription status:', error);
            }
        }

        // Load plans from API
        async function loadPlans() {
            try {
                const response = await api.getSubscriptionPlans();

                if (response.code === 200 && response.data) {
                    plans = response.data;
                    renderPlans(plans);
                } else {
                    document.getElementById('plansGrid').innerHTML = `
                        <div class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                            <p>{{ __('auth.failed_to_load_plans') ?? 'Failed to load plans' }}</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Failed to load plans:', error);
                document.getElementById('plansGrid').innerHTML = `
                    <div class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                        <p>{{ __('auth.failed_to_load_plans') ?? 'Failed to load plans' }}</p>
                    </div>
                `;
            }
        }

        // Render status banner
        function renderStatusBanner(info) {
            const banner = document.getElementById('statusBanner');
            
            if (!info.is_valid) {
                banner.innerHTML = `
                    <div class="status-banner expired">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>{{ __('messages.subscription_expired') }}</strong>
                            <p class="mb-0" style="font-size: 13px;">{{ __('messages.subscription_expired_renew') }}</p>
                        </div>
                    </div>
                `;
            } else if (info.warning) {
                banner.innerHTML = `
                    <div class="status-banner warning">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>{{ __('messages.subscription_expiring_soon', ['days' => '']) }}${info.days_remaining} {{ __('messages.days') ?? 'days' }}</strong>
                            <p class="mb-0" style="font-size: 13px;">{{ __('messages.renew_now') }} {{ __('messages.to_avoid_interruption') ?? 'to avoid interruption' }}</p>
                        </div>
                    </div>
                `;
            }
        }

        // Render plans
        function renderPlans(plansData) {
            const grid = document.getElementById('plansGrid');
            const currentPlan = subscriptionInfo?.plan_name || '';
            
            grid.innerHTML = plansData.map((plan, index) => {
                const isCurrent = plan.name === currentPlan;
                const modules = plan.enabled_modules || [];
                
                return `
                    <div class="plan-card ${isCurrent ? 'current' : ''}" 
                         data-plan-id="${plan.id}" 
                         data-plan-name="${plan.display_name}"
                         data-plan-price="${plan.price}"
                         data-plan-currency="${plan.currency || 'SAR'}"
                         onclick="selectPlan(this)">
                        <div class="plan-name">${plan.display_name}</div>
                        <div class="plan-price">
                            ${plan.currency || 'SAR'} ${parseFloat(plan.price).toFixed(0)}
                            <small>/${plan.billing_cycle === 'monthly' ? '{{ __("messages.month") ?? "month" }}' : '{{ __("messages.year") ?? "year" }}'}</small>
                        </div>
                        <p class="plan-description">${plan.description || ''}</p>
                        <ul class="plan-features">
                            <li><i class="fas fa-check-circle"></i> ${plan.max_projects || '{{ __("messages.unlimited") ?? "Unlimited" }}'} {{ __('messages.projects') }}</li>
                            <li><i class="fas fa-check-circle"></i> ${plan.max_team_members || '{{ __("messages.unlimited") ?? "Unlimited" }}'} {{ __('messages.team_members') }}</li>
                            ${modules.includes('tasks') ? '<li><i class="fas fa-check-circle"></i> {{ __("messages.tasks") ?? "Tasks" }}</li>' : ''}
                            ${modules.includes('inspections') ? '<li><i class="fas fa-check-circle"></i> {{ __("messages.inspections") ?? "Inspections" }}</li>' : '<li class="disabled"><i class="fas fa-times-circle"></i> {{ __("messages.inspections") ?? "Inspections" }}</li>'}
                            ${modules.includes('reports') ? '<li><i class="fas fa-check-circle"></i> {{ __("messages.reports") ?? "Reports" }}</li>' : '<li class="disabled"><i class="fas fa-times-circle"></i> {{ __("messages.reports") ?? "Reports" }}</li>'}
                        </ul>
                    </div>
                `;
            }).join('');
        }

        // Select plan
        function selectPlan(element) {
            // Remove selection from all
            document.querySelectorAll('.plan-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selection to clicked
            element.classList.add('selected');
            
            // Store selected plan info
            selectedPlanId = element.dataset.planId;
            selectedPlanName = element.dataset.planName;
            selectedPlanPrice = parseFloat(element.dataset.planPrice);
            
            // Enable proceed button
            document.getElementById('proceedBtn').disabled = false;
        }

        // Show payment section
        function showPayment() {
            if (!selectedPlanId) {
                toastr.warning('{{ __("messages.please_select_plan") ?? "Please select a plan" }}');
                return;
            }
            
            document.getElementById('selectedPlanSummary').innerHTML = 
                `<strong>${selectedPlanName}</strong> - SAR ${selectedPlanPrice.toFixed(2)}`;
            
            document.getElementById('paymentSection').classList.add('active');
            document.getElementById('paymentSection').scrollIntoView({ behavior: 'smooth' });
            
            initializeMoyasar();
        }

        // Hide payment section
        function hidePayment() {
            document.getElementById('paymentSection').classList.remove('active');
        }

        // Initialize Moyasar
        async function initializeMoyasar() {
            try {
                document.getElementById('moyasar-form').innerHTML = `
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary"></div>
                        <p class="text-muted mt-2">{{ __('messages.loading') ?? 'Loading' }}...</p>
                    </div>
                `;
                
                const configResponse = await api.getPaymentConfig();
                
                if (configResponse.code !== 200) {
                    throw new Error('Failed to load payment config');
                }

                const config = configResponse.data;
                const formElement = document.getElementById('moyasar-form');
                
                if (!formElement) {
                    throw new Error('Payment form element not found');
                }
                
                formElement.innerHTML = '';
                
                Moyasar.init({
                    element: formElement,
                    amount: selectedPlanPrice * 100,
                    currency: config.currency || 'SAR',
                    description: `Subscription Renewal - ${selectedPlanName}`,
                    publishable_api_key: config.publishable_key,
                    callback_url: `${window.location.origin}/payment/complete?type=renewal&plan_id=${selectedPlanId}&user_id=${currentUserId}`,
                    methods: config.payment_methods || ['creditcard'],
                    on_completed: function(payment) {
                        toastr.success('{{ __("messages.payment_successful") ?? "Payment successful!" }}');
                    },
                    on_failed: function(error) {
                        toastr.error('{{ __("auth.payment_failed") ?? "Payment failed" }}');
                    }
                });
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('moyasar-form').innerHTML = `
                    <div class="alert alert-danger">
                        {{ __('auth.failed_to_initialize_payment') ?? 'Failed to initialize payment' }}
                    </div>
                `;
            }
        }
    </script>
</body>
</html>
