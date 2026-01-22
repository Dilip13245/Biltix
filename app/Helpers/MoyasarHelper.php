<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoyasarHelper
{
    /**
     * Get Moyasar API base URL
     */
    public static function getApiUrl(): string
    {
        return config('moyasar.api_base_url', 'https://api.moyasar.com/v1');
    }

    /**
     * Get secret key for server-side API calls
     */
    public static function getSecretKey(): string
    {
        return config('moyasar.secret_key');
    }

    /**
     * Get publishable key for client-side form
     */
    public static function getPublishableKey(): string
    {
        return config('moyasar.publishable_key');
    }

    /**
     * Get payment form configuration for frontend
     */
    public static function getPaymentFormConfig(int $amount, string $description, string $callbackUrl, array $metadata = []): array
    {
        return [
            'publishable_key' => self::getPublishableKey(),
            'amount' => $amount, // In halalas (smallest unit)
            'currency' => config('moyasar.currency', 'SAR'),
            'description' => $description,
            'callback_url' => $callbackUrl,
            'methods' => config('moyasar.methods', ['creditcard']),
            'supported_networks' => config('moyasar.supported_networks', ['visa', 'mastercard', 'mada']),
            'metadata' => $metadata,
        ];
    }

    /**
     * Verify payment status using Moyasar API
     * 
     * @param string $paymentId Payment ID from Moyasar
     * @return array|null Payment details or null if failed
     */
    public static function verifyPayment(string $paymentId): ?array
    {
        try {
            $response = Http::withBasicAuth(self::getSecretKey(), '')
                ->get(self::getApiUrl() . '/payments/' . $paymentId);

            if ($response->successful()) {
                $payment = $response->json();
                
                Log::info('Moyasar payment verification', [
                    'payment_id' => $paymentId,
                    'status' => $payment['status'] ?? 'unknown',
                    'amount' => $payment['amount'] ?? 0
                ]);

                return $payment;
            }

            Log::error('Moyasar payment verification failed', [
                'payment_id' => $paymentId,
                'status_code' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Moyasar API error', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Check if payment is successful
     */
    public static function isPaymentSuccessful(array $payment): bool
    {
        return isset($payment['status']) && $payment['status'] === 'paid';
    }

    /**
     * Get payment amount in display format (SAR)
     */
    public static function formatAmount(int $amountInHalalas): string
    {
        $amount = $amountInHalalas / 100;
        return number_format($amount, 2) . ' ' . config('moyasar.currency', 'SAR');
    }

    /**
     * Convert amount to halalas (smallest currency unit)
     */
    public static function toHalalas(float $amount): int
    {
        return (int) round($amount * 100);
    }

    /**
     * Convert halalas to amount
     */
    public static function fromHalalas(int $halalas): float
    {
        return $halalas / 100;
    }
}
