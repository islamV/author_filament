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
        Schema::table('manual_payments', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'status']);
            $table->foreignId('subscription_id')->after('user_id')->constrained()->cascadeOnDelete();
            $table->string('bank_name')->nullable()->after('subscription_id');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('beneficiary_name')->nullable()->after('account_number');
            $table->string('wallet_id')->nullable()->after('beneficiary_name');
            $table->string('wallet_name')->nullable()->after('wallet_id');
            $table->string('sender_number')->nullable()->after('payment_screen_shot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manual_payments', function (Blueprint $table) {
            $table->enum('payment_method', ['vodafone', 'instapay'])->after('user_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('payment_method');
            $table->dropColumn([
                'subscription_id',
                'bank_name',
                'account_number',
                'beneficiary_name',
                'wallet_id',
                'wallet_name',
                'sender_number',
            ]);
        });
    }
};
