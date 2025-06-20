<?php
// app/Livewire/Admin/ImageUpload.php - 完善版本

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Booking;
use App\Models\BookingImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUpload extends Component
{
    use WithFileUploads;

    public $booking;
    public $images = [];
    public $existingImages = [];
    public $showUploadModal = false;
    
    // 🔑 新增屬性
    public $uploadProgress = 0;
    public $isUploading = false;
    public $selectedImageForView = null;
    public $showImageViewer = false;
    public $showDeleteConfirm = false;
    public $imageToDelete = null;

    protected $listeners = [
        'refreshImages' => 'refreshImages',
        'bookingUpdated' => 'refreshBooking'
    ];

    protected $rules = [
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB Max，限制格式
    ];

    protected $messages = [
        'images.*.image' => '檔案必須是圖片格式',
        'images.*.mimes' => '只支援 JPEG、PNG、JPG、GIF 格式',
        'images.*.max' => '圖片大小不能超過 2MB',
    ];

    public function mount($bookingId = null)
    {
        if ($bookingId) {
            $this->booking = Booking::with('images')->find($bookingId);
            $this->loadExistingImages();
        }
    }

    public function loadExistingImages()
    {
        if ($this->booking) {
            $this->existingImages = $this->booking->fresh()->images()->orderBy('sort_order')->get();
        }
    }

    public function refreshImages()
    {
        $this->loadExistingImages();
    }

    public function refreshBooking()
    {
        if ($this->booking) {
            $this->booking = $this->booking->fresh();
            $this->loadExistingImages();
        }
    }

    public function openUploadModal($bookingId = null)
    {
        // 如果沒有傳入 bookingId，使用現有的 booking
        if ($bookingId) {
            $this->booking = Booking::with('images')->find($bookingId);
        } elseif (!$this->booking) {
            // 如果沒有 booking，則無法開啟模態窗
            return;
        }
        
        $this->loadExistingImages();
        $this->images = [];
        $this->uploadProgress = 0;
        $this->isUploading = false;
        $this->showUploadModal = true;
        $this->resetValidation();
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->images = [];
        $this->uploadProgress = 0;
        $this->isUploading = false;
        $this->resetValidation();
    }

    public function openImageViewer($imageId)
    {
        $this->selectedImageForView = $this->existingImages->find($imageId);
        $this->showImageViewer = true;
    }

    public function closeImageViewer()
    {
        $this->showImageViewer = false;
        $this->selectedImageForView = null;
    }

    public function confirmDeleteImage($imageId)
    {
        $this->imageToDelete = $imageId;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->imageToDelete = null;
    }

    public function uploadImages()
    {
        $this->validate();

        if (empty($this->images)) {
            session()->flash('error', '請選擇要上傳的圖片');
            return;
        }

        // 檢查總圖片數量（現有 + 新上傳）
        $currentImageCount = $this->existingImages->count();
        $newImageCount = count($this->images);
        
        if ($currentImageCount + $newImageCount > 5) {
            session()->flash('error', '每個預約最多只能上傳 5 張圖片');
            return;
        }

        $this->isUploading = true;
        $this->uploadProgress = 10; // 開始進度

        try {
            $uploadedCount = 0;
            $totalImages = count($this->images);
            
            foreach ($this->images as $index => $image) {
                // 更新進度 (10% 到 90%)
                $this->uploadProgress = 10 + (($index + 1) / $totalImages) * 80;
                
                // 驗證圖片
                if (!$this->validateImage($image)) {
                    continue;
                }
                
                // 生成唯一檔名
                $extension = $image->getClientOriginalExtension();
                $filename = 'booking_' . $this->booking->id . '_' . time() . '_' . Str::random(8) . '.' . $extension;
                
                // 儲存到 storage/app/public/booking_images
                $path = $image->storeAs('booking_images', $filename, 'public');
                
                // 確保檔案成功儲存
                if ($path) {
                    // 儲存到資料庫
                    BookingImage::create([
                        'booking_id' => $this->booking->id,
                        'image_path' => $path,
                        'sort_order' => $currentImageCount + $uploadedCount,
                    ]);
                    
                    $uploadedCount++;
                }
            }

            $this->uploadProgress = 100;
            session()->flash('success', "成功上傳 {$uploadedCount} 張圖片");
            
            // 重新載入圖片
            $this->loadExistingImages();
            
            // 通知父組件重新整理
            $this->dispatch('images-uploaded');
            
            // 延遲關閉模態窗以顯示完成狀態
            $this->js('setTimeout(() => { $wire.closeUploadModal(); }, 1500);');
            
        } catch (\Exception $e) {
            \Log::error('Image upload error: ' . $e->getMessage());
            session()->flash('error', '圖片上傳失敗：' . $e->getMessage());
        } finally {
            $this->isUploading = false;
        }
    }

    private function validateImage($image)
    {
        // 檢查檔案大小 (2MB)
        if ($image->getSize() > 2048 * 1024) {
            return false;
        }
        
        // 檢查檔案類型
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!in_array($image->getMimeType(), $allowedMimes)) {
            return false;
        }
        
        return true;
    }

    public function deleteImage($imageId = null)
    {
        $imageId = $imageId ?: $this->imageToDelete;
        
        if (!$imageId) {
            return;
        }

        try {
            $image = BookingImage::find($imageId);
            
            if ($image && $image->booking_id === $this->booking->id) {
                // 刪除實體檔案
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                
                $currentOrder = $image->sort_order;
                
                // 刪除資料庫記錄
                $image->delete();
                
                // 重新排序剩餘圖片
                BookingImage::where('booking_id', $this->booking->id)
                    ->where('sort_order', '>', $currentOrder)
                    ->decrement('sort_order');
                
                // 重新載入圖片
                $this->loadExistingImages();
                
                session()->flash('success', '圖片已刪除');
                $this->dispatch('images-uploaded');
                
                // 如果在查看器中刪除，關閉查看器
                if ($this->showImageViewer && $this->selectedImageForView && $this->selectedImageForView->id === $imageId) {
                    $this->closeImageViewer();
                }
            }
        } catch (\Exception $e) {
            \Log::error('Image delete error: ' . $e->getMessage());
            session()->flash('error', '刪除圖片失敗：' . $e->getMessage());
        } finally {
            $this->cancelDelete();
        }
    }

    public function moveImageUp($imageId)
    {
        $this->moveImage($imageId, 'up');
    }

    public function moveImageDown($imageId)
    {
        $this->moveImage($imageId, 'down');
    }

    private function moveImage($imageId, $direction)
    {
        $image = $this->existingImages->find($imageId);
        if (!$image) return;

        $currentOrder = $image->sort_order;
        $targetOrder = null;
        
        if ($direction === 'up' && $currentOrder > 0) {
            $targetOrder = $currentOrder - 1;
        } elseif ($direction === 'down' && $currentOrder < $this->existingImages->count() - 1) {
            $targetOrder = $currentOrder + 1;
        }
        
        if ($targetOrder === null) {
            return; // 已經在邊界位置
        }

        try {
            // 找到目標位置的圖片
            $targetImage = $this->existingImages->where('sort_order', $targetOrder)->first();
            
            if ($targetImage) {
                // 交換順序
                $image->update(['sort_order' => $targetOrder]);
                $targetImage->update(['sort_order' => $currentOrder]);
                
                // 重新載入圖片
                $this->loadExistingImages();
                
                session()->flash('success', '圖片順序已更新');
                $this->dispatch('images-uploaded');
            }
        } catch (\Exception $e) {
            \Log::error('Move image error: ' . $e->getMessage());
            session()->flash('error', '移動圖片失敗：' . $e->getMessage());
        }
    }

    public function reorderImages($orderedIds)
    {
        try {
            foreach ($orderedIds as $index => $imageId) {
                BookingImage::where('id', $imageId)
                    ->where('booking_id', $this->booking->id)
                    ->update(['sort_order' => $index]);
            }
            
            // 重新載入圖片
            $this->loadExistingImages();
            
            session()->flash('success', '圖片順序已更新');
            $this->dispatch('images-uploaded');
            
        } catch (\Exception $e) {
            \Log::error('Image reorder error: ' . $e->getMessage());
            session()->flash('error', '更新順序失敗：' . $e->getMessage());
        }
    }

    // 🔑 新增：批量刪除功能
    public function deleteAllImages()
    {
        try {
            $images = $this->existingImages;
            
            foreach ($images as $image) {
                // 刪除實體檔案
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                
                // 刪除資料庫記錄
                $image->delete();
            }
            
            // 重新載入圖片
            $this->loadExistingImages();
            
            session()->flash('success', '所有圖片已刪除');
            $this->dispatch('images-uploaded');
            
        } catch (\Exception $e) {
            \Log::error('Delete all images error: ' . $e->getMessage());
            session()->flash('error', '刪除圖片失敗：' . $e->getMessage());
        }
    }

    // 🔑 新增：圖片統計資訊
    public function getImageStatsProperty()
    {
        $totalSize = 0;
        foreach ($this->existingImages as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                $totalSize += Storage::disk('public')->size($image->image_path);
            }
        }
        
        return [
            'count' => $this->existingImages->count(),
            'total_size' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
            'remaining_slots' => 5 - $this->existingImages->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.image-upload', [
            'imageStats' => $this->imageStats,
        ]);
    }
}