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
            // 新增：使用者可編輯的 LINE 聯繫 ID（例如：@john123）
            $table->string('line_contact_id')->nullable()->after('line_name')
                  ->comment('使用者自訂的 LINE ID，用於聯繫顯示');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('line_contact_id');
        });
    }
};
