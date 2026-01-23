<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip if table already exists
        if (Schema::hasTable('user_subscriptions')) {
            return;
        }
        
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // The main registered user
            $table->foreignId('plan_id')->constrained('subscription_plans')->onDelete('cascade');
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->enum('status', ['active', 'expired', 'cancelled', 'pending'])->default('active');
            $table->string('transaction_id')->nullable(); // Renamed from payment_reference
            $table->string('payment_method')->nullable(); 
            $table->json('payment_details')->nullable(); // Added to match model
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->boolean('auto_renew')->default(false);
            $table->timestamp('cancelled_at')->nullable(); // Added to match model
            $table->boolean('is_deleted')->default(false); // Added to match model
            $table->text('notes')->nullable();
            $table->boolean('is_trial')->default(false);
            $table->timestamps();

            // Index for quick lookups
            $table->index(['user_id', 'status']);
            $table->index(['expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
