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
        // 為 bookings 表添加搜尋索引
        Schema::table('bookings', function (Blueprint $table) {
            // 客戶資訊搜尋索引
            $table->index('customer_line_name', 'idx_bookings_customer_line_name');
            $table->index('customer_line_id', 'idx_bookings_customer_line_id');
            $table->index('customer_phone', 'idx_bookings_customer_phone');
            
            // 預約狀態和時間索引
            $table->index('status', 'idx_bookings_status');
            $table->index('booking_time', 'idx_bookings_booking_time');
            $table->index('created_at', 'idx_bookings_created_at');
            
            // 複合索引 - 常用的查詢組合
            $table->index(['status', 'booking_time'], 'idx_bookings_status_time');
            $table->index(['user_id', 'status'], 'idx_bookings_user_status');
            $table->index(['created_at', 'status'], 'idx_bookings_created_status');
        });

        // 為 users 表添加搜尋索引
        Schema::table('users', function (Blueprint $table) {
            // 基本資訊搜尋索引
            $table->index('name', 'idx_users_name');
            $table->index('line_name', 'idx_users_line_name');
            $table->index('line_contact_id', 'idx_users_line_contact_id');
            $table->index('phone', 'idx_users_phone');
            
            // 狀態和時間索引
            $table->index('is_active', 'idx_users_is_active');
            $table->index('role', 'idx_users_role');
            $table->index('created_at', 'idx_users_created_at');
            
            // 複合索引
            $table->index(['is_active', 'role'], 'idx_users_active_role');
            $table->index(['created_at', 'is_active'], 'idx_users_created_active');
        });

        // 為 booking_images 表添加索引
        Schema::table('booking_images', function (Blueprint $table) {
            // 圖片路徑索引 (用於檢查重複或搜尋)
            $table->index('image_path', 'idx_booking_images_path');
            $table->index('created_at', 'idx_booking_images_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 移除 bookings 表的索引
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_customer_line_name');
            $table->dropIndex('idx_bookings_customer_line_id');
            $table->dropIndex('idx_bookings_customer_phone');
            $table->dropIndex('idx_bookings_status');
            $table->dropIndex('idx_bookings_booking_time');
            $table->dropIndex('idx_bookings_created_at');
            $table->dropIndex('idx_bookings_status_time');
            $table->dropIndex('idx_bookings_user_status');
            $table->dropIndex('idx_bookings_created_status');
        });

        // 移除 users 表的索引
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_name');
            $table->dropIndex('idx_users_line_name');
            $table->dropIndex('idx_users_line_contact_id');
            $table->dropIndex('idx_users_phone');
            $table->dropIndex('idx_users_is_active');
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_created_at');
            $table->dropIndex('idx_users_active_role');
            $table->dropIndex('idx_users_created_active');
        });

        // 移除 booking_images 表的索引
        Schema::table('booking_images', function (Blueprint $table) {
            $table->dropIndex('idx_booking_images_path');
            $table->dropIndex('idx_booking_images_created');
        });
    }
};
