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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('site_name')->nullable()->after('id');
            $table->string('site_favicon')->nullable()->after('site_logo');
            $table->text('site_description')->nullable()->after('site_name');
            $table->string('site_email')->nullable()->after('site_description');
            $table->string('site_phone')->nullable()->after('site_email');
            $table->string('site_address')->nullable()->after('site_phone');

            $table->text('meta_keywords')->nullable()->after('contact_info');
            $table->text('meta_description')->nullable()->after('meta_keywords');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_name',
                'site_favicon',
                'site_description',
                'site_email',
                'site_phone',
                'site_address',
                'meta_keywords',
                'meta_description',
            ]);
        });
    }
};
