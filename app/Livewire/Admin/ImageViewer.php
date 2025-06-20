<?php
// app/Livewire/Admin/ImageViewer.php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\BookingImage;

class ImageViewer extends Component
{
    public $showViewer = false;
    public $images = [];
    public $currentImageIndex = 0;
    public $currentImage = null;
    public $booking = null;
    
    // 縮放和旋轉狀態
    public $scale = 1;
    public $rotation = 0;
    public $panX = 0;
    public $panY = 0;

    protected $listeners = [
        'openImageViewer' => 'openViewer',
        'closeImageViewer' => 'closeViewer'
    ];

    public function openViewer($bookingId, $imageId = null)
    {
        // 載入該預約的所有圖片
        $this->booking = \App\Models\Booking::with('images')->find($bookingId);
        
        if (!$this->booking) {
            return;
        }

        $this->images = $this->booking->images()->orderBy('sort_order')->get()->toArray();
        
        // 如果指定了特定圖片，找到它的索引
        if ($imageId) {
            $index = collect($this->images)->search(function ($image) use ($imageId) {
                return $image['id'] == $imageId;
            });
            $this->currentImageIndex = $index !== false ? $index : 0;
        } else {
            $this->currentImageIndex = 0;
        }

        $this->updateCurrentImage();
        $this->resetTransform();
        $this->showViewer = true;
    }

    public function closeViewer()
    {
        $this->showViewer = false;
        $this->images = [];
        $this->currentImage = null;
        $this->booking = null;
        $this->resetTransform();
    }

    public function nextImage()
    {
        if ($this->currentImageIndex < count($this->images) - 1) {
            $this->currentImageIndex++;
            $this->updateCurrentImage();
            $this->resetTransform();
        }
    }

    public function previousImage()
    {
        if ($this->currentImageIndex > 0) {
            $this->currentImageIndex--;
            $this->updateCurrentImage();
            $this->resetTransform();
        }
    }

    public function goToImage($index)
    {
        if ($index >= 0 && $index < count($this->images)) {
            $this->currentImageIndex = $index;
            $this->updateCurrentImage();
            $this->resetTransform();
        }
    }

    private function updateCurrentImage()
    {
        if (isset($this->images[$this->currentImageIndex])) {
            $this->currentImage = $this->images[$this->currentImageIndex];
        }
    }

    public function zoomIn()
    {
        $this->scale = min($this->scale * 1.2, 5); // 最大5倍
    }

    public function zoomOut()
    {
        $this->scale = max($this->scale / 1.2, 0.1); // 最小0.1倍
    }

    public function resetZoom()
    {
        $this->scale = 1;
        $this->panX = 0;
        $this->panY = 0;
    }

    public function rotateLeft()
    {
        $this->rotation = ($this->rotation - 90) % 360;
    }

    public function rotateRight()
    {
        $this->rotation = ($this->rotation + 90) % 360;
    }

    private function resetTransform()
    {
        $this->scale = 1;
        $this->rotation = 0;
        $this->panX = 0;
        $this->panY = 0;
    }

    public function downloadImage()
    {
        if ($this->currentImage) {
            $this->dispatch('downloadImage', $this->currentImage['url']);
        }
    }

    public function deleteCurrentImage()
    {
        if ($this->currentImage) {
            $this->dispatch('deleteImage', $this->currentImage['id']);
        }
    }

    public function getCurrentImageUrlAttribute()
    {
        if (!$this->currentImage) {
            return null;
        }

        // 確保 URL 格式正確
        $imagePath = $this->currentImage['image_path'];
        
        if (!str_starts_with($imagePath, 'booking_images/')) {
            $imagePath = 'booking_images/' . $imagePath;
        }
        
        return asset('storage/' . $imagePath);
    }

    public function render()
    {
        return view('livewire.admin.image-viewer');
    }
}