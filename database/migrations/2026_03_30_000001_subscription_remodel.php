<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Replace pro_until with subscription_expires_at
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('pro_until', 'subscription_expires_at');
        });

        // Drop Stripe/Cashier columns from users
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'stripe_id')) {
                $table->dropIndex(['stripe_id']);
                $table->dropColumn(['stripe_id', 'pm_type', 'pm_last_four', 'trial_ends_at']);
            }
        });

        // Drop Cashier subscription tables
        Schema::dropIfExists('subscription_items');
        Schema::dropIfExists('subscriptions');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('subscription_expires_at', 'pro_until');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->string('pm_last_four', 4)->nullable();
            $table->timestamp('trial_ends_at')->nullable();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('type');
            $table->string('stripe_id')->unique();
            $table->string('stripe_status');
            $table->string('stripe_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'stripe_status']);
        });

        Schema::create('subscription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id');
            $table->string('stripe_id')->unique();
            $table->string('stripe_product');
            $table->string('stripe_price');
            $table->integer('quantity')->nullable();
            $table->timestamps();
            $table->index(['subscription_id', 'stripe_price']);
        });
    }
};
