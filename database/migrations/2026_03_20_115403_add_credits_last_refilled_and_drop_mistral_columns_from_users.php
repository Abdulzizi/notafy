<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('credits_last_refilled_at')->nullable()->after('credits');

            // Drop legacy Mistral monthly-call tracking columns (replaced by credits)
            $table->dropColumn([
                'monthly_mistral_calls',
                'mistral_calls_month',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('credits_last_refilled_at');

            $table->unsignedInteger('monthly_mistral_calls')->default(0);
            $table->date('mistral_calls_month')->nullable();
        });
    }
};
