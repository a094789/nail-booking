<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookingImage;
use Illuminate\Support\Facades\Storage;

class DebugImagePaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:image-paths {--fix : Fix incorrect image paths}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug and optionally fix booking image paths';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Debugging Booking Image Paths');
        $this->line('');

        // 檢查 storage 連結
        $this->checkStorageLink();
        
        // 檢查圖片記錄
        $this->checkImageRecords();
        
        // 檢查檔案系統
        $this->checkFileSystem();
        
        if ($this->option('fix')) {
            $this->fixImagePaths();
        }
    }

    private function checkStorageLink()
    {
        $this->info('📂 Checking Storage Link...');
        
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');
        
        if (is_link($linkPath)) {
            $actualTarget = readlink($linkPath);
            $this->line("✅ Storage link exists: {$linkPath} -> {$actualTarget}");
            
            if ($actualTarget === $targetPath) {
                $this->info("✅ Storage link is correct");
            } else {
                $this->error("❌ Storage link target is incorrect");
                $this->line("   Expected: {$targetPath}");
                $this->line("   Actual: {$actualTarget}");
            }
        } else {
            $this->error("❌ Storage link does not exist");
            $this->line("   Run: php artisan storage:link");
        }
        $this->line('');
    }

    private function checkImageRecords()
    {
        $this->info('🗃️  Checking Database Records...');
        
        $images = BookingImage::all();
        $this->line("Found {$images->count()} image records in database");
        
        foreach ($images as $image) {
            $this->line("ID: {$image->id} | Path: {$image->image_path}");
            
            // 檢查 URL 生成
            $url = $image->url;
            $this->line("   Generated URL: {$url}");
            
            // 檢查檔案是否存在
            $exists = $image->file_exists;
            $status = $exists ? '✅' : '❌';
            $this->line("   File exists: {$status}");
            
            if (!$exists) {
                // 嘗試找到實際檔案
                $this->findActualFile($image);
            }
            
            $this->line('');
        }
    }

    private function findActualFile($image)
    {
        $possiblePaths = [
            $image->image_path,
            'booking_images/' . basename($image->image_path),
            basename($image->image_path),
        ];
        
        $this->line("   🔍 Searching for actual file...");
        
        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                $this->line("   ✅ Found at: {$path}");
                return $path;
            } else {
                $this->line("   ❌ Not found at: {$path}");
            }
        }
        
        return null;
    }

    private function checkFileSystem()
    {
        $this->info('📁 Checking File System...');
        
        $bookingImagesPath = storage_path('app/public/booking_images');
        
        if (!is_dir($bookingImagesPath)) {
            $this->error("❌ Booking images directory does not exist: {$bookingImagesPath}");
            return;
        }
        
        $files = glob($bookingImagesPath . '/*');
        $this->line("Found " . count($files) . " files in booking_images directory:");
        
        foreach ($files as $file) {
            if (is_file($file)) {
                $filename = basename($file);
                $size = filesize($file);
                $this->line("   📄 {$filename} ({$size} bytes)");
                
                // 檢查是否有對應的資料庫記錄
                $relativePath = 'booking_images/' . $filename;
                $dbRecord = BookingImage::where('image_path', $relativePath)
                    ->orWhere('image_path', $filename)
                    ->first();
                
                if ($dbRecord) {
                    $this->line("      ✅ Has DB record (ID: {$dbRecord->id})");
                } else {
                    $this->line("      ❌ No DB record found");
                }
            }
        }
        $this->line('');
    }

    private function fixImagePaths()
    {
        $this->info('🔧 Fixing Image Paths...');
        
        $images = BookingImage::all();
        $fixed = 0;
        
        foreach ($images as $image) {
            $originalPath = $image->image_path;
            $newPath = $this->calculateCorrectPath($image);
            
            if ($newPath && $newPath !== $originalPath) {
                $image->update(['image_path' => $newPath]);
                $this->line("✅ Fixed: {$originalPath} -> {$newPath}");
                $fixed++;
            }
        }
        
        $this->info("🎉 Fixed {$fixed} image paths");
    }

    private function calculateCorrectPath($image)
    {
        $originalPath = $image->image_path;
        
        // 嘗試不同的路徑格式
        $possiblePaths = [
            $originalPath,
            'booking_images/' . basename($originalPath),
            basename($originalPath),
        ];
        
        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                // 確保返回正確的格式：booking_images/filename.ext
                if (!str_starts_with($path, 'booking_images/')) {
                    return 'booking_images/' . basename($path);
                }
                return $path;
            }
        }
        
        return null;
    }
}