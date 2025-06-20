<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingView extends Component
{
    public $booking;
    public $showModal = false;
    public $selectedImageIndex = 0;
    public $showImageViewer = false;

    protected $listeners = ['showBookingView' => 'show'];
    
    public function mount()
    {
        // 確保初始狀態正確
        $this->showModal = false;
        $this->booking = null;
        $this->selectedImageIndex = 0;
        $this->showImageViewer = false;
    }

    public function show($bookingId)
    {
        Log::info("BookingView show method called with ID: " . $bookingId);
        
        $this->booking = Booking::with(['images', 'user'])
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->first();
        
        if ($this->booking) {
            Log::info("Booking found, showing modal");
            $this->showModal = true;
            $this->selectedImageIndex = 0;
        } else {
            Log::info("Booking not found or unauthorized");
        }
    }

    public function close()
    {
        $this->showModal = false;
        $this->booking = null;
        $this->selectedImageIndex = 0;
        $this->showImageViewer = false;
    }

    public function previousImage()
    {
        if ($this->booking && $this->booking->images->count() > 0) {
            $this->selectedImageIndex = ($this->selectedImageIndex - 1 + $this->booking->images->count()) % $this->booking->images->count();
        }
    }

    public function nextImage()
    {
        if ($this->booking && $this->booking->images->count() > 0) {
            $this->selectedImageIndex = ($this->selectedImageIndex + 1) % $this->booking->images->count();
        }
    }

    public function selectImage($index)
    {
        $this->selectedImageIndex = $index;
    }

    public function openImageViewer()
    {
        if ($this->booking && $this->booking->images->count() > 0) {
            $this->showImageViewer = true;
        }
    }

    public function closeImageViewer()
    {
        $this->showImageViewer = false;
    }

    public function selectImageAndView($index)
    {
        $this->selectedImageIndex = $index;
        $this->openImageViewer();
    }

    public function getStatusColorProperty()
    {
        if (!$this->booking) return 'gray';
        
        return match($this->booking->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'completed' => 'blue',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    public function getStatusTextProperty()
    {
        if (!$this->booking) return '';
        
        return match($this->booking->status) {
            'pending' => '待審核',
            'approved' => '預約成功',
            'completed' => '已完成',
            'cancelled' => '已取消',
            default => '未知狀態'
        };
    }

    public function render()
    {
        return view('livewire.customer.booking-view');
    }
}
