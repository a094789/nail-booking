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
        // 修正 booking_images 表中的 image_path 格式
        // 確保所有路徑都是 booking_images/filename.ext 格式
        
        $images = DB::table('booking_images')->get();
        
        foreach ($images as $image) {
            $originalPath = $image->image_path;
            $newPath = $originalPath;
            
            // 如果路徑不是以 booking_images/ 開頭，添加前綴
            if (!str_starts_with($originalPath, 'booking_images/')) {
                $newPath = 'booking_images/' . $originalPath;
            }
            
            // 如果路徑被錯誤地包含了 storage/ 前綴，移除它
            if (str_starts_with($originalPath, 'storage/booking_images/')) {
                $newPath = str_replace('storage/', '', $originalPath);
            }
            
            // 如果路徑被錯誤地包含了完整的檔案系統路徑，只保留相對路徑
            if (str_contains($originalPath, 'storage/app/public/booking_images/')) {
                $newPath = 'booking_images/' . basename($originalPath);
            }
            
            // 更新路徑（如果有變化）
            if ($newPath !== $originalPath) {
                DB::table('booking_images')
                    ->where('id', $image->id)
                    ->update(['image_path' => $newPath]);
                    
                echo "Updated image path: {$originalPath} -> {$newPath}\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 由於這是資料修正，通常不需要回滾
        // 如果需要，可以記錄原始路徑並在這裡還原
    }
};