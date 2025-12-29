<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('author_request_payments', function (Blueprint $table) {
            DB::statement("ALTER TABLE author_request_payments 
            MODIFY COLUMN status ENUM('pending','approved','paid','rejected') NOT NULL DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('author_request_payments', function (Blueprint $table) {
            DB::statement("ALTER TABLE author_request_payments 
            MODIFY COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
        });
    }
};
