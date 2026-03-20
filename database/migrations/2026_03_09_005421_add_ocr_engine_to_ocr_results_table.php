<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocr_results', function (Blueprint $table) {
            $table->string('ocr_engine', 20)->default('tesseract')->after('status');
            $table->text('custom_prompt')->nullable()->after('ocr_engine');
        });
    }

    public function down(): void
    {
        Schema::table('ocr_results', function (Blueprint $table) {
            $table->dropColumn(['ocr_engine', 'custom_prompt']);
        });
    }
};
