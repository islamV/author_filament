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
        Schema::table('payment_gateways', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('payment_gateway');
            $table->string('account_number')->nullable()->after('phone_number');
            $table->string('bank_name')->nullable()->after('account_number');
            $table->string('receiver_name')->nullable()->after('bank_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_gateways', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'account_number', 'bank_name', 'receiver_name']);
        });
    }
};
