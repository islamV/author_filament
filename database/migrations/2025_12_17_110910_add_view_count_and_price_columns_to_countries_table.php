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
        Schema::table('countries', function (Blueprint $table) {
            $table->string('code', 10)->nullable()->after('id');
            $table->integer('view_count')->default(1000)->after('name');
            $table->decimal('price', 10, 2)->default(0)->after('view_count');
            $table->decimal('desktop_price', 10, 2)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn(['code', 'view_count', 'price', 'desktop_price']);
        });
    }
};
