<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->renameColumn('mayar_transaction_id', 'midtrans_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('credit_transactions', function (Blueprint $table) {
            $table->renameColumn('midtrans_transaction_id', 'mayar_transaction_id');
        });
    }
};
