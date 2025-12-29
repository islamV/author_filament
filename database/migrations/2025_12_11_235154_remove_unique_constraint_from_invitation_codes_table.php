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
        Schema::table('invitation_codes', function (Blueprint $table) {
            // Drop unique index on invitation_code if it exists
            $table->dropUnique(['invitation_code']);
        });
        
        // Also drop unique index on id_device if it exists (using raw SQL as fallback)
        try {
            DB::statement('ALTER TABLE invitation_codes DROP INDEX invitation_codes_id_device_unique');
        } catch (\Exception $e) {
            // Index might not exist, ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitation_codes', function (Blueprint $table) {
            // Restore unique constraint on invitation_code
            $table->unique('invitation_code');
        });
    }
};
