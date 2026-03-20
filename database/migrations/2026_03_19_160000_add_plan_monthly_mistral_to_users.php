<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('plan', ['free', 'pro'])->default('free')->after('remember_token');
            $table->enum('billing_gateway', ['stripe', 'mayar'])->nullable()->after('plan');
            $table->timestamp('pro_until')->nullable()->after('billing_gateway');
            $table->unsignedSmallInteger('monthly_mistral_calls')->default(0)->after('pro_until');
            $table->date('mistral_calls_month')->nullable()->after('monthly_mistral_calls');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['plan', 'billing_gateway', 'pro_until', 'monthly_mistral_calls', 'mistral_calls_month']);
        });
    }
};
