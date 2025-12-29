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
        // Check if table already exists (custom user_notifications table)
        if (!Schema::hasTable('user_notifications')) {
            Schema::create('user_notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        } else {
            // If custom table exists, check if it has Laravel notification structure
            // If not, we'll need to migrate data or rename tables
            // For now, we'll add columns if they don't exist
            Schema::table('user_notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('user_notifications', 'notifiable_type')) {
                    $table->string('notifiable_type')->nullable();
                    $table->unsignedBigInteger('notifiable_id')->nullable();
                    $table->index(['notifiable_type', 'notifiable_id']);
                }
                if (!Schema::hasColumn('user_notifications', 'read_at')) {
                    $table->timestamp('read_at')->nullable();
                }
                if (!Schema::hasColumn('user_notifications', 'data')) {
                    $table->text('data')->nullable();
                }
                if (!Schema::hasColumn('user_notifications', 'type')) {
                    $table->string('type')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop if it's a custom table, just remove Laravel columns
        if (Schema::hasTable('user_notifications')) {
            Schema::table('user_notifications', function (Blueprint $table) {
                $columns = ['notifiable_type', 'notifiable_id', 'read_at', 'data', 'type'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('user_notifications', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
