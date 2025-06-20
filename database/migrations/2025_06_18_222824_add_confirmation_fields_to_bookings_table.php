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
        Schema::table('bookings', function (Blueprint $table) {
            // 行前確認相關欄位
            $table->boolean('requires_confirmation')->default(true)->comment('是否需要行前確認');
            $table->boolean('is_confirmed')->default(false)->comment('是否已確認');
            $table->timestamp('confirmed_at')->nullable()->comment('確認時間');
            $table->timestamp('confirmation_deadline')->nullable()->comment('確認截止時間');
            $table->boolean('confirmation_reminder_sent')->default(false)->comment('是否已發送確認提醒');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'requires_confirmation',
                'is_confirmed',
                'confirmed_at',
                'confirmation_deadline',
                'confirmation_reminder_sent'
            ]);
        });
    }
};
