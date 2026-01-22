<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\User;
use App\Helpers\SubscriptionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    /**
     * Get all available subscription plans
     */
    public function getPlans(Request $request)
    {
        try {
            $plans = SubscriptionHelper::getAvailablePlans();

            return $this->toJsonEnc($plans, trans('api.subscription.plans_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    /**
     * Get current user's subscription
     */
    public function getCurrentSubscription(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            
            if (!$user_id) {
                return $this->toJsonEnc([], trans('api.auth.user_id_required'), Config::get('constant.ERROR'));
            }

            $user = User::find($user_id);
            
            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $subscriptionInfo = SubscriptionHelper::getUserSubscriptionInfo($user);

            return $this->toJsonEnc($subscriptionInfo, trans('api.subscription.subscription_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    /**
     * Get features available in user's subscription
     */
    public function getSubscriptionFeatures(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            
            if (!$user_id) {
                return $this->toJsonEnc([], trans('api.auth.user_id_required'), Config::get('constant.ERROR'));
            }

            $user = User::find($user_id);
            
            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $subscription = SubscriptionHelper::getActiveSubscription($user);
            
            if (!$subscription) {
                return $this->toJsonEnc([
                    'has_subscription' => false,
                    'features' => []
                ], trans('api.subscription.no_active_subscription'), Config::get('constant.SUCCESS'));
            }

            $features = $subscription->plan->getFeatureKeys();

            return $this->toJsonEnc([
                'has_subscription' => true,
                'plan_name' => $subscription->plan->display_name,
                'features' => $features
            ], trans('api.subscription.features_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    /**
     * Check if user has access to a specific feature
     */
    public function checkFeatureAccess(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'feature_key' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $user = User::find($request->user_id);
            
            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $hasAccess = SubscriptionHelper::hasFeature($user, $request->feature_key);

            return $this->toJsonEnc([
                'feature_key' => $request->feature_key,
                'has_access' => $hasAccess
            ], $hasAccess ? trans('api.subscription.feature_accessible') : trans('api.subscription.feature_not_accessible'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    /**
     * Subscribe user to a plan
     */
    public function subscribe(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'plan_id' => 'required|integer|exists:subscription_plans,id',
                'payment_reference' => 'nullable|string',
                'payment_method' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $user = User::find($request->user_id);
            
            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Get company owner
            $companyOwner = SubscriptionHelper::getCompanyOwner($user);
            
            if (!$companyOwner) {
                return $this->toJsonEnc([], trans('api.subscription.company_owner_not_found'), Config::get('constant.ERROR'));
            }

            // Check if user is sub-user (team member can't subscribe)
            if ($user->is_sub_user) {
                return $this->toJsonEnc([], trans('api.subscription.team_member_cannot_subscribe'), Config::get('constant.ERROR'));
            }

            $plan = SubscriptionPlan::find($request->plan_id);
            
            if (!$plan || !$plan->is_active) {
                return $this->toJsonEnc([], trans('api.subscription.plan_not_available'), Config::get('constant.ERROR'));
            }

            // Check if already has active subscription
            $existingSubscription = SubscriptionHelper::getActiveSubscription($user);
            
            if ($existingSubscription) {
                // Update existing subscription
                $existingSubscription->plan_id = $plan->id;
                $existingSubscription->payment_reference = $request->payment_reference;
                $existingSubscription->payment_method = $request->payment_method;
                $existingSubscription->amount_paid = $plan->price;
                $existingSubscription->expires_at = now()->addYear(); // Reset expiry
                $existingSubscription->status = 'active';
                $existingSubscription->save();

                SubscriptionHelper::clearSubscriptionCache($user->id);

                return $this->toJsonEnc(
                    $existingSubscription->toInfoArray(), 
                    trans('api.subscription.subscription_upgraded'), 
                    Config::get('constant.SUCCESS')
                );
            }

            // Create new subscription
            $subscription = UserSubscription::create([
                'user_id' => $companyOwner->id,
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'expires_at' => now()->addYear(),
                'status' => 'active',
                'payment_reference' => $request->payment_reference,
                'payment_method' => $request->payment_method ?? 'manual',
                'amount_paid' => $plan->price,
                'auto_renew' => false,
                'is_trial' => false
            ]);

            SubscriptionHelper::clearSubscriptionCache($user->id);

            return $this->toJsonEnc(
                $subscription->toInfoArray(), 
                trans('api.subscription.subscription_created'), 
                Config::get('constant.SUCCESS')
            );
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    /**
     * Check subscription expiry status
     */
    public function checkExpiry(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            
            if (!$user_id) {
                return $this->toJsonEnc([], trans('api.auth.user_id_required'), Config::get('constant.ERROR'));
            }

            $user = User::find($user_id);
            
            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $expiryInfo = SubscriptionHelper::checkSubscriptionExpiry($user);

            return $this->toJsonEnc($expiryInfo, $expiryInfo['message'], Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}
