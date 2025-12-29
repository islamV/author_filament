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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('type');

            $table->foreignId('plan_id')
                ->nullable()
                ->after('user_id')
                ->constrained('plans')
                ->nullOnDelete();

            $table->enum('status', ['pending', 'active', 'cancelled', 'expired'])->default('pending'); 

            $table->string('stripe_status')->nullable()->change();

            $table->foreignId('payment_gateway_id')->nullable()->after('plan_id')->constrained('payment_gateways')->nullOnDelete();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
            $table->dropForeign(['payment_gateway_id']);
            $table->dropColumn('payment_gateway_id');
            $table->dropColumn('status');
            $table->string('type')->after('user_id');
            $table->string('stripe_status')->nullable(false)->change();

        });
    }
};
