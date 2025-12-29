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
        Schema::table('users', function (Blueprint $table) {
               
             $table->string('used_invitation_code', 50)
                ->nullable()
                ->after('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop string column
            $table->dropColumn('used_invitation_code');
            
            // Restore foreign key column
            $table->foreignId('used_invitation_code_id')
                ->nullable()
                ->after('role_id')
                ->constrained('invitation_codes')
                ->nullOnDelete();
        });
    }
};
