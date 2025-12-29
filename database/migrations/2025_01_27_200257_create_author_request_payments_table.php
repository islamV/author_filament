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
        Schema::create('author_request_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal("requested_amount", 10, 2);
            $table->foreignId("user_id")->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum("status",["pending", "approved", "rejected"])->default("pending");
            $table->string('payment_method');
            $table->string('phone',11)->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('iban')->nullable();
            $table->string('wallet_id')->nullable();
            $table->string('wallet_name')->nullable();
            $table->string('email_binance')->nullable();
            $table->string('beneficiary_address')->nullable();
            $table->string('swift_bio_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_request_payments');
    }
};
