<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BookingImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'image_path',
        'sort_order',
    ];

    // 關聯
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // 🔑 修正：統一的圖片 URL 生成方法
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        // 直接使用存在資料庫中的路徑，因為上傳時已經包含 booking_images/ 前綴
        // 例如：booking_images/booking_1_1672531200_abc123.jpg
        return asset('storage/' . $this->image_path);
    }

    // 🔑 支援 blade 中使用 $image->url 的方式（與 getImageUrlAttribute 保持一致）
    public function getUrlAttribute()
    {
        return $this->getImageUrlAttribute();
    }

    // 🔑 檢查圖片檔案是否存在
    public function getFileExistsAttribute()
    {
        if (!$this->image_path) {
            return false;
        }

        return Storage::disk('public')->exists($this->image_path);
    }

    // 輔助方法：獲取完整檔案系統路徑
    public function getFullPathAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        return storage_path('app/public/' . $this->image_path);
    }

    // 🔑 生成縮圖 URL（如果需要的話）
    public function getThumbnailUrlAttribute()
    {
        // 這裡可以實作縮圖邏輯，目前先返回原圖
        return $this->getImageUrlAttribute();
    }

    // 🔑 獲取圖片檔案名稱（不含路徑）
    public function getFilenameAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        return basename($this->image_path);
    }

    // 🔑 獲取圖片檔案大小（bytes）
    public function getFileSizeAttribute()
    {
        if (!$this->file_exists) {
            return 0;
        }

        return Storage::disk('public')->size($this->image_path);
    }

    // 🔑 獲取格式化的檔案大小
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }
}