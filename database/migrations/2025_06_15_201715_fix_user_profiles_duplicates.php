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
        // 1. 清理重複數據 - 保留每個用戶最新的記錄
        DB::statement("
            DELETE t1 FROM user_profiles t1
            INNER JOIN user_profiles t2 
            WHERE t1.user_id = t2.user_id 
            AND t1.id < t2.id
        ");

        // 2. 添加唯一約束
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });
    }
};