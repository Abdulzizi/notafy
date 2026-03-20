<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocr_results', function (Blueprint $table) {
            // Platform & category
            $table->string('platform', 50)->nullable()->after('custom_prompt');
            $table->string('category', 20)->nullable()->after('platform');

            // Transaction identifiers
            $table->string('transaction_id', 100)->nullable()->after('category');
            $table->date('transaction_date')->nullable()->after('transaction_id');
            $table->string('transaction_time', 5)->nullable()->after('transaction_date');

            // Parties
            $table->string('vendor_name', 200)->nullable()->after('transaction_time');
            $table->string('employee_name', 100)->nullable()->after('vendor_name');

            // Financials (IDR, no decimals)
            $table->unsignedInteger('subtotal')->nullable()->after('employee_name');
            $table->unsignedInteger('discount')->nullable()->after('subtotal');
            $table->unsignedInteger('delivery_fee')->nullable()->after('discount');
            $table->unsignedInteger('service_fee')->nullable()->after('delivery_fee');
            $table->unsignedInteger('tax')->nullable()->after('service_fee');
            $table->unsignedInteger('total_amount')->nullable()->after('tax');

            // Payment & source
            $table->string('payment_method', 100)->nullable()->after('total_amount');
            $table->string('source_type', 30)->nullable()->after('payment_method');

            // Notafy confidence (0.0–1.0), separate from Tesseract's 0–100 'confidence' column
            $table->float('confidence_score', 3, 2)->nullable()->after('source_type');
            $table->boolean('needs_review')->default(false)->after('confidence_score');
        });
    }

    public function down(): void
    {
        Schema::table('ocr_results', function (Blueprint $table) {
            $table->dropColumn([
                'platform',
                'category',
                'transaction_id',
                'transaction_date',
                'transaction_time',
                'vendor_name',
                'employee_name',
                'subtotal',
                'discount',
                'delivery_fee',
                'service_fee',
                'tax',
                'total_amount',
                'payment_method',
                'source_type',
                'confidence_score',
                'needs_review',
            ]);
        });
    }
};
