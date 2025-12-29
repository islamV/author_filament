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
            $table->unsignedInteger('invitation_reward_points')->nullable();
            $table->unsignedInteger('invitation_money_uses')->nullable()->after('invitation_reward_points');
            $table->decimal('invitation_reward_money', 10, 2)->nullable()->after('invitation_money_uses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'invitation_reward_points',
                'invitation_money_uses',
                'invitation_reward_money',
            ]);
        });
    }
};
