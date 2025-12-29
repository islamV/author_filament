<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update status based on verified_by_admin before dropping it
        DB::statement("UPDATE users SET status = 'refused' WHERE verified_by_admin = 'refused'");
        DB::statement("UPDATE users SET status = 'active' WHERE verified_by_admin = 'accept'");
        DB::statement("UPDATE users SET status = 'pending' WHERE verified_by_admin = 'pending' OR verified_by_admin IS NULL");

        Schema::table('users', function (Blueprint $table) {
            // Drop verified_by_admin column
            $table->dropColumn('verified_by_admin');
        });

        // Ensure status is enum with the correct values
        Schema::table('users', function (Blueprint $table) {
            // Modify status column to ensure it's enum with correct values
            $table->enum('status', ['active', 'pending', 'suspended', 'refused'])
                ->default('pending')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add verified_by_admin back
            $table->enum('verified_by_admin', ['pending', 'accept', 'refused'])
                ->default('pending')
                ->after('is_active');
        });

        // Restore data (approximate)
        DB::statement("UPDATE users SET verified_by_admin = 'accept' WHERE status = 'active'");
        DB::statement("UPDATE users SET verified_by_admin = 'refused' WHERE status = 'refused'");
        DB::statement("UPDATE users SET verified_by_admin = 'pending' WHERE status IN ('pending', 'suspended')");
    }
};
