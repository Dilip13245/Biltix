<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserSubscription extends Model
{
    use HasFactory;

    // Match existing table columns
    protected $fillable = [
        'user_id',
        'plan_id',
        'starts_at',
        'expires_at',
        'status',
        'amount_paid',
        'payment_method',
        'transaction_id',      // existing column
        'payment_details',     // existing column (json)
        'cancelled_at',
        'auto_renew',
        'is_deleted'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'payment_details' => 'array',
        'auto_renew' => 'boolean',
        'is_deleted' => 'boolean'
    ];

    /**
     * Get the company owner (main user who subscribed)
     */
    public function companyUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the subscription plan
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    /**
     * Scope: Only active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('expires_at', '>', now());
    }

    /**
     * Scope: Expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'expired')
              ->orWhere('expires_at', '<=', now());
        });
    }

    /**
     * Check if subscription is currently active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at > now();
    }

    /**
     * Check if subscription has expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->expires_at <= now();
    }

    /**
     * Get days remaining
     */
    public function daysRemaining(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        return (int) now()->diffInDays($this->expires_at, false);
    }

    /**
     * Check if subscription is expiring soon (within 7 days)
     */
    public function isExpiringSoon(int $days = 7): bool
    {
        return $this->isActive() && $this->daysRemaining() <= $days;
    }

    /**
     * Check if user has access to a feature
     */
    public function hasFeature(string $featureKey): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        return $this->plan->hasFeature($featureKey);
    }

    /**
     * Extend subscription by given period
     */
    public function extend(int $days = 365): void
    {
        $this->expires_at = $this->expires_at->addDays($days);
        $this->status = 'active';
        $this->save();
    }

    /**
     * Cancel subscription
     */
    public function cancel(): void
    {
        $this->status = 'cancelled';
        $this->auto_renew = false;
        $this->save();
    }

    /**
     * Mark as expired
     */
    public function markExpired(): void
    {
        $this->status = 'expired';
        $this->save();
    }

    /**
     * Get subscription info array
     */
    public function toInfoArray(): array
    {
        return [
            'id' => $this->id,
            'plan_id' => $this->plan_id,
            'plan_name' => $this->plan->display_name ?? $this->plan->name,
            'status' => $this->status,
            'is_active' => $this->isActive(),
            'starts_at' => $this->starts_at->toDateTimeString(),
            'expires_at' => $this->expires_at->toDateTimeString(),
            'days_remaining' => $this->daysRemaining(),
            'is_expiring_soon' => $this->isExpiringSoon(),
            'is_trial' => $this->is_trial,
            'auto_renew' => $this->auto_renew,
            'amount_paid' => $this->amount_paid,
            'currency' => $this->plan->currency ?? 'SAR',
            'transaction_id' => $this->transaction_id
        ];
    }
}
