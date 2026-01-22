<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MoyasarHelper;
use App\Helpers\SubscriptionHelper;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Initialize payment for subscription
     * 
     * POST /api/v1/payment/init
     * Body: { plan_id: required, user_id: optional (for existing users) }
     */
    public function initPayment(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'plan_id' => 'required|exists:subscription_plans,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->validateResponse($validator->errors());
        }

        $plan = SubscriptionPlan::find($request->plan_id);
        
        if (!$plan || !$plan->is_active) {
            return $this->toJsonEnc([], __('api.subscription.plan_not_available'), 400);
        }

        // Generate unique payment token for tracking
        $paymentToken = Str::uuid()->toString();
        
        // Calculate amount in halalas (smallest currency unit)
        $amountInHalalas = MoyasarHelper::toHalalas($plan->price);
        
        // Prepare description
        $description = "Biltix {$plan->name} Plan - " . ucfirst($plan->billing_cycle ?? 'monthly');
        
        // Build callback URL with payment token
        $callbackUrl = config('moyasar.callback_url') . '?token=' . $paymentToken;
        
        // Store pending payment data in cache (expires in 1 hour)
        $paymentData = [
            'plan_id' => $plan->id,
            'user_id' => $request->user_id,
            'amount' => $amountInHalalas,
            'created_at' => now()->toISOString(),
        ];
        
        Cache::put("payment_pending_{$paymentToken}", $paymentData, 3600);
        
        // Get form configuration for frontend
        $formConfig = MoyasarHelper::getPaymentFormConfig(
            $amountInHalalas,
            $description,
            $callbackUrl,
            ['plan_id' => $plan->id, 'token' => $paymentToken]
        );
        
        return $this->toJsonEnc([
            'payment_token' => $paymentToken,
            'amount' => $amountInHalalas,
            'amount_display' => MoyasarHelper::formatAmount($amountInHalalas),
            'currency' => config('moyasar.currency', 'SAR'),
            'description' => $description,
            'form_config' => $formConfig,
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'price' => $plan->price,
                'billing_cycle' => $plan->billing_cycle,
            ]
        ], __('api.payment.config_retrieved'));
    }

    /**
     * Handle payment callback from Moyasar
     * 
     * GET /api/v1/payment/callback
     * Query: id (payment_id from Moyasar), token (our payment_token)
     */
    public function callback(Request $request)
    {
        $paymentId = $request->query('id');
        $paymentToken = $request->query('token');
        $status = $request->query('status');
        
        Log::info('Payment callback received', [
            'payment_id' => $paymentId,
            'token' => $paymentToken,
            'status' => $status
        ]);

        // Get pending payment data
        $pendingPayment = Cache::get("payment_pending_{$paymentToken}");
        
        if (!$pendingPayment) {
            Log::error('Payment token not found or expired', ['token' => $paymentToken]);
            return $this->toJsonEnc([], __('api.payment.registration_expired'), 400);
        }

        // Verify payment with Moyasar
        $payment = MoyasarHelper::verifyPayment($paymentId);
        
        if (!$payment) {
            return $this->toJsonEnc([], __('api.payment.payment_not_completed'), 400);
        }

        // Check if payment is successful
        if (!MoyasarHelper::isPaymentSuccessful($payment)) {
            return $this->toJsonEnc([
                'status' => $payment['status'] ?? 'failed'
            ], __('api.payment.payment_not_completed'), 400);
        }

        // Verify amount matches
        if ($payment['amount'] !== $pendingPayment['amount']) {
            Log::error('Payment amount mismatch', [
                'expected' => $pendingPayment['amount'],
                'received' => $payment['amount']
            ]);
            return $this->toJsonEnc([], __('api.payment.amount_mismatch'), 400);
        }

        // Get user
        $userId = $pendingPayment['user_id'];
        $user = User::find($userId);
        
        if (!$user) {
            return $this->toJsonEnc([], __('api.auth.user_not_found'), 404);
        }

        // Activate subscription
        $subscription = SubscriptionHelper::createSubscription(
            $user->id,
            $pendingPayment['plan_id'],
            $paymentId,
            MoyasarHelper::fromHalalas($pendingPayment['amount'])
        );

        // Clear pending payment cache
        Cache::forget("payment_pending_{$paymentToken}");

        Log::info('Subscription activated via payment', [
            'user_id' => $user->id,
            'plan_id' => $pendingPayment['plan_id'],
            'subscription_id' => $subscription->id
        ]);

        return $this->toJsonEnc([
            'subscription_id' => $subscription->id,
            'plan_name' => $subscription->plan->name,
            'status' => $subscription->status,
            'expires_at' => $subscription->expires_at->format('Y-m-d'),
            'payment_id' => $paymentId,
        ], __('api.payment.subscription_activated'));
    }

    /**
     * Verify payment status manually
     * 
     * POST /api/v1/payment/verify
     * Body: { payment_id: required }
     */
    public function verifyPayment(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'payment_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validateResponse($validator->errors());
        }

        $payment = MoyasarHelper::verifyPayment($request->payment_id);
        
        if (!$payment) {
            return $this->toJsonEnc([], __('api.payment.payment_not_completed'), 400);
        }

        return $this->toJsonEnc([
            'payment_id' => $payment['id'],
            'status' => $payment['status'],
            'amount' => $payment['amount'],
            'amount_display' => MoyasarHelper::formatAmount($payment['amount']),
            'currency' => $payment['currency'] ?? config('moyasar.currency', 'SAR'),
            'is_paid' => MoyasarHelper::isPaymentSuccessful($payment),
            'created_at' => $payment['created_at'] ?? null,
        ], 'Payment details retrieved');
    }

    /**
     * Get payment configuration for frontend
     * 
     * GET /api/v1/payment/config
     */
    public function getConfig()
    {
        return $this->toJsonEnc([
            'publishable_key' => MoyasarHelper::getPublishableKey(),
            'currency' => config('moyasar.currency', 'SAR'),
            'methods' => config('moyasar.methods'),
            'supported_networks' => config('moyasar.supported_networks'),
        ], __('api.payment.config_retrieved'));
    }

    /**
     * Initialize payment for new user registration
     * Stores user data in cache, returns payment form config
     * 
     * POST /api/v1/payment/init_registration
     * Body: { user_data: {...}, plan_id: required }
     */
    public function initRegistrationPayment(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'plan_id' => 'required|exists:subscription_plans,id',
            'user_data' => 'required|array',
            'user_data.name' => 'required|string|max:255',
            'user_data.email' => 'required|email|unique:users,email',
            'user_data.phone' => 'required|string|unique:users,phone',
            'user_data.password' => 'required|string|min:8',
            'user_data.role' => 'required|string',
            'user_data.company_name' => 'required|string|max:255',
            'user_data.employee_count' => 'nullable|integer',
            'user_data.designation' => 'nullable|string|max:255',
        ], [
            'user_data.email.unique' => __('api.auth.email_unique'),
            'user_data.phone.unique' => __('api.auth.phone_number_unique'),
        ]);

        if ($validator->fails()) {
            return $this->validateResponse($validator->errors());
        }

        $plan = SubscriptionPlan::find($request->plan_id);
        
        if (!$plan || !$plan->is_active) {
            return $this->toJsonEnc([], __('api.subscription.plan_not_available'), 400);
        }

        // Generate unique registration token
        $registrationToken = Str::uuid()->toString();
        
        // Calculate amount in halalas
        $amountInHalalas = MoyasarHelper::toHalalas($plan->price);
        
        // Prepare description
        $description = "Biltix {$plan->name} Plan - " . ucfirst($plan->billing_cycle ?? 'monthly');
        
        // Build callback URL for registration flow
        $callbackUrl = config('moyasar.web_callback_url') . '?token=' . $registrationToken;
        
        // Hash password before storing
        $userData = $request->user_data;
        $userData['password_hash'] = \Hash::make($userData['password']);
        unset($userData['password']); // Don't store plain password
        
        // Store registration data in cache (expires in 1 hour)
        $registrationData = [
            'user_data' => $userData,
            'plan_id' => $plan->id,
            'amount' => $amountInHalalas,
            'device_type' => $request->device_type ?? 'W',
            'created_at' => now()->toISOString(),
            'type' => 'registration', // Mark as registration payment
        ];
        
        Cache::put("registration_pending_{$registrationToken}", $registrationData, 3600);
        
        Log::info('Registration payment initiated', [
            'token' => $registrationToken,
            'email' => $userData['email'],
            'plan_id' => $plan->id
        ]);
        
        // Get form configuration for frontend
        $formConfig = MoyasarHelper::getPaymentFormConfig(
            $amountInHalalas,
            $description,
            $callbackUrl,
            ['plan_id' => $plan->id, 'token' => $registrationToken, 'type' => 'registration']
        );
        
        return $this->toJsonEnc([
            'registration_token' => $registrationToken,
            'amount' => $amountInHalalas,
            'amount_display' => MoyasarHelper::formatAmount($amountInHalalas),
            'currency' => config('moyasar.currency', 'SAR'),
            'description' => $description,
            'callback_url' => $callbackUrl,
            'form_config' => $formConfig,
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'price' => $plan->price,
                'billing_cycle' => $plan->billing_cycle,
            ]
        ], __('api.payment.registration_stored'));
    }

    /**
     * Complete registration after payment success
     * 
     * POST /api/v1/payment/complete_registration
     * Body: { payment_id: required, token: required }
     */
    public function completeRegistration(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'payment_id' => 'required|string',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validateResponse($validator->errors());
        }

        $paymentId = $request->payment_id;
        $registrationToken = $request->token;
        
        Log::info('Completing registration after payment', [
            'payment_id' => $paymentId,
            'token' => $registrationToken
        ]);

        // Get pending registration data
        $registrationData = Cache::get("registration_pending_{$registrationToken}");
        
        if (!$registrationData) {
            Log::error('Registration token not found or expired', ['token' => $registrationToken]);
            return $this->toJsonEnc([], __('api.payment.registration_expired'), 400);
        }

        // Verify payment with Moyasar
        $payment = MoyasarHelper::verifyPayment($paymentId);
        
        if (!$payment) {
            return $this->toJsonEnc([], __('api.payment.payment_not_completed'), 400);
        }

        // Check if payment is successful
        if (!MoyasarHelper::isPaymentSuccessful($payment)) {
            return $this->toJsonEnc([
                'status' => $payment['status'] ?? 'failed'
            ], __('api.payment.payment_not_completed'), 400);
        }

        // Verify amount matches
        if ($payment['amount'] !== $registrationData['amount']) {
            Log::error('Payment amount mismatch', [
                'expected' => $registrationData['amount'],
                'received' => $payment['amount']
            ]);
            return $this->toJsonEnc([], __('api.payment.amount_mismatch'), 400);
        }

        // Check if user already exists (edge case: user refreshed page)
        $userData = $registrationData['user_data'];
        $existingUser = User::where('email', $userData['email'])->first();
        
        if ($existingUser) {
            Log::warning('User already exists during registration completion', [
                'email' => $userData['email']
            ]);
            
            // Clear cache and return existing user's token
            Cache::forget("registration_pending_{$registrationToken}");
            
            // Get or create device token
            $token = $this->getOrCreateUserToken($existingUser, $registrationData['device_type']);
            
            return $this->toJsonEnc([
                'user' => $existingUser,
                'token' => $token,
                'subscription' => $existingUser->getActiveSubscription(),
                'already_registered' => true
            ], __('api.payment.registration_complete'));
        }

        // Create new user
        $user = new User();
        $user->name = $userData['name'];
        $user->email = $userData['email'];
        $user->phone = $userData['phone'];
        $user->password = $userData['password_hash'];
        $user->role = $userData['role'];
        $user->company_name = $userData['company_name'];
        $user->designation = $userData['designation'] ?? '';
        $user->employee_count = $userData['employee_count'] ?? null;
        $user->is_active = true;
        $user->is_verified = true;
        $user->save();

        // Create subscription
        $subscription = SubscriptionHelper::createSubscription(
            $user->id,
            $registrationData['plan_id'],
            $paymentId,
            MoyasarHelper::fromHalalas($registrationData['amount'])
        );

        // Generate access token
        $accessToken = bin2hex(random_bytes(32));
        
        // Create device entry
        \App\Models\UserDevice::updateOrCreate(
            ['user_id' => $user->id],
            [
                'token' => $accessToken,
                'device_type' => $registrationData['device_type'],
                'ip_address' => $request->ip() ?? '',
            ]
        );

        // Clear registration cache
        Cache::forget("registration_pending_{$registrationToken}");

        Log::info('Registration completed successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'subscription_id' => $subscription->id
        ]);

        // Prepare response
        $user->token = $accessToken;
        $user->subscription = [
            'id' => $subscription->id,
            'plan_name' => $subscription->plan->name,
            'status' => $subscription->status,
            'expires_at' => $subscription->expires_at->format('Y-m-d'),
        ];

        return $this->toJsonEnc($user, __('api.payment.registration_complete'));
    }

    /**
     * Get or create user token for existing user
     */
    private function getOrCreateUserToken(User $user, string $deviceType): string
    {
        $device = \App\Models\UserDevice::where('user_id', $user->id)->first();
        
        if ($device && $device->token) {
            return $device->token;
        }
        
        $token = bin2hex(random_bytes(32));
        
        \App\Models\UserDevice::updateOrCreate(
            ['user_id' => $user->id],
            [
                'token' => $token,
                'device_type' => $deviceType,
            ]
        );
        
        return $token;
    }

    /**
     * Handle Moyasar webhook notifications
     * This handles cases where redirect callback fails
     * 
     * POST /api/v1/payment/webhook
     */
    public function webhook(Request $request)
    {
        Log::info('Moyasar webhook received', $request->all());

        $payload = $request->all();
        
        // Verify webhook signature if available
        // Moyasar sends 'signature' header for verification
        // For now, we'll verify by fetching payment status from API
        
        $paymentId = $payload['id'] ?? null;
        $status = $payload['status'] ?? null;
        $metadata = $payload['metadata'] ?? [];
        
        if (!$paymentId) {
            Log::warning('Webhook: Missing payment ID');
            return response()->json(['status' => 'ignored', 'reason' => 'missing_payment_id']);
        }

        // Only process successful payments
        if ($status !== 'paid') {
            Log::info('Webhook: Payment not successful', ['status' => $status]);
            return response()->json(['status' => 'ignored', 'reason' => 'not_paid']);
        }

        // Get token from metadata
        $registrationToken = $metadata['token'] ?? null;
        $paymentType = $metadata['type'] ?? 'subscription';

        if (!$registrationToken) {
            Log::warning('Webhook: Missing registration token in metadata');
            return response()->json(['status' => 'ignored', 'reason' => 'missing_token']);
        }

        // Check if this is a registration payment
        if ($paymentType === 'registration') {
            return $this->handleRegistrationWebhook($paymentId, $registrationToken, $payload);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Handle registration payment webhook
     */
    private function handleRegistrationWebhook(string $paymentId, string $registrationToken, array $payload)
    {
        // Check if already processed (user already created)
        $cacheKey = "registration_pending_{$registrationToken}";
        $registrationData = Cache::get($cacheKey);
        
        if (!$registrationData) {
            // Token not found - either expired or already processed
            Log::info('Webhook: Registration already processed or expired', ['token' => $registrationToken]);
            return response()->json(['status' => 'already_processed']);
        }

        // Check if user already exists (from redirect callback)
        $userData = $registrationData['user_data'];
        $existingUser = User::where('email', $userData['email'])->first();
        
        if ($existingUser) {
            // User already created (by redirect callback), just clear cache
            Cache::forget($cacheKey);
            Log::info('Webhook: User already exists, clearing cache', ['email' => $userData['email']]);
            return response()->json(['status' => 'already_processed']);
        }

        // Verify payment with Moyasar API (double verification)
        $payment = MoyasarHelper::verifyPayment($paymentId);
        
        if (!$payment || !MoyasarHelper::isPaymentSuccessful($payment)) {
            Log::error('Webhook: Payment verification failed', ['payment_id' => $paymentId]);
            return response()->json(['status' => 'error', 'reason' => 'payment_not_verified'], 400);
        }

        // Verify amount
        if ($payment['amount'] !== $registrationData['amount']) {
            Log::error('Webhook: Amount mismatch', [
                'expected' => $registrationData['amount'],
                'received' => $payment['amount']
            ]);
            return response()->json(['status' => 'error', 'reason' => 'amount_mismatch'], 400);
        }

        // Create user
        $user = new User();
        $user->name = $userData['name'];
        $user->email = $userData['email'];
        $user->phone = $userData['phone'];
        $user->password = $userData['password_hash'];
        $user->role = $userData['role'];
        $user->company_name = $userData['company_name'];
        $user->designation = $userData['designation'] ?? '';
        $user->employee_count = $userData['employee_count'] ?? null;
        $user->is_active = true;
        $user->is_verified = true;
        $user->save();

        // Create subscription
        SubscriptionHelper::createSubscription(
            $user->id,
            $registrationData['plan_id'],
            $paymentId,
            MoyasarHelper::fromHalalas($registrationData['amount'])
        );

        // Clear cache
        Cache::forget($cacheKey);

        Log::info('Webhook: User created successfully', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return response()->json(['status' => 'ok', 'user_id' => $user->id]);
    }

    /**
     * Check payment status (for polling from frontend)
     * 
     * GET /api/v1/payment/status/{token}
     */
    public function checkStatus(string $token)
    {
        // Check if registration is still pending
        $registrationData = Cache::get("registration_pending_{$token}");
        
        if (!$registrationData) {
            // Either processed or expired
            // Check if user was created
            return $this->toJsonEnc([
                'status' => 'completed_or_expired',
                'message' => 'Registration has been processed or the session has expired'
            ], 'Status checked');
        }

        return $this->toJsonEnc([
            'status' => 'pending',
            'message' => 'Payment is still pending'
        ], 'Status checked');
    }
}

