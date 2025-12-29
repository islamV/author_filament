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
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'is_published')) {
                $table->dropColumn('is_published');
            }

            $table->enum('publish_status', ['published', 'draft', 'scheduled'])
                  ->default('published')
                  ->after('title');

            $table->dateTime('scheduled_until')->nullable()->after('publish_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->boolean('is_published')->default(0)->after('title');
            $table->dropColumn('publish_status');
            $table->dropColumn('scheduled_until');
        });
    }
};
