<?php
// app/Livewire/Admin/BookingManagement.php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Booking;
use App\Models\BookingImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BookingManagement extends Component
{
    use WithPagination;
    use WithFileUploads;

    // 篩選條件
    public $searchTerm = '';
    public $statusFilter = 'all';
    public $dateFilter = '';
    public $monthFilter = '';

    // 模態窗相關
    public $showBookingModal = false;
    public $selectedBooking = null;

    // 編輯模態窗
    public $showEditModal = false;
    public $editingBooking = null;

    // 圖片上傳相關
    public $showImageUploadModal = false;
    public $uploadImages = [];

    // 圖片預覽相關屬性
    public $showImageModal = false;
    public $currentImageUrl = '';
    public $currentImages = [];
    public $currentImageIndex = 0;

    // 表單欄位
    public $customer_name = '';
    public $customer_line_name = '';
    public $customer_line_id = '';
    public $customer_phone = '';
    public $cancellation_reason = '';
    public $amount = '';

    // 編輯表單欄位
    public $edit_status = '';
    public $edit_booking_time = '';
    public $edit_style_type = '';
    public $edit_need_removal = false;
    public $edit_notes = '';
    public $edit_customer_name = '';
    public $edit_customer_line_name = '';
    public $edit_customer_phone = '';
    public $edit_amount = '';

    protected $listeners = [
        'deleteImage' => 'deleteBookingImageFromViewer',
        'booking-created' => '$refresh',
        'booking-updated' => '$refresh'
    ];

    // 圖片上傳驗證規則
    protected $rules = [
        'uploadImages.*' => 'image|max:2048',
        'amount' => 'nullable|numeric|min:0',
        'cancellation_reason' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'uploadImages.*.image' => '檔案必須是圖片格式',
        'uploadImages.*.max' => '圖片大小不能超過 2MB',
    ];

    public function mount()
    {
        // 預設篩選為待審核和取消申請
        $this->statusFilter = 'pending_and_cancellation';
    }

    public function openCreateBookingModal()
    {
        // 觸發子組件的事件
        $this->dispatch('open-create-modal');
    }

    // 🔧 優化：使用快取來減少重複查詢
    public function getMonthlyStatsProperty()
    {
        return cache()->remember('monthly_stats_' . date('Y-m'), 600, function () {
            $currentMonth = now()->startOfMonth();
            
            return [
                'total' => Booking::whereMonth('created_at', $currentMonth->month)->count(),
                'pending' => Booking::where('status', 'pending')->whereMonth('created_at', $currentMonth->month)->count(),
                'approved' => Booking::where('status', 'approved')->whereMonth('created_at', $currentMonth->month)->count(),
                'completed' => Booking::where('status', 'completed')->whereMonth('created_at', $currentMonth->month)->count(),
                'cancelled' => Booking::where('status', 'cancelled')->whereMonth('created_at', $currentMonth->month)->count(),
                'cancellation_requests' => Booking::where('cancellation_requested', true)->where('status', 'approved')->count(),
            ];
        });
    }

    // 🔧 修正：簡化篩選方法
    public function clearFilters()
    {
        // 🔧 修正：使用 reset 方法一次性重置所有篩選
        $this->reset(['searchTerm', 'statusFilter', 'dateFilter', 'monthFilter']);
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    public function filterPendingAndCancellation()
    {
        // 🔧 修正：避免狀態衝突
        $this->reset(['dateFilter', 'monthFilter']);
        $this->statusFilter = 'pending_and_cancellation';
        $this->resetPage();
    }

    public function filterPending()
    {
        // 🔧 修正：避免狀態衝突
        $this->reset(['dateFilter', 'monthFilter']);
        $this->statusFilter = 'pending';
        $this->resetPage();
    }

    public function filterCancellationRequests()
    {
        // 🔧 修正：避免狀態衝突
        $this->reset(['dateFilter', 'monthFilter']);
        $this->statusFilter = 'cancellation_requested';
        $this->resetPage();
    }

    public function filterToday()
    {
        // 🔧 修正：使用單一操作避免狀態衝突
        $this->reset(['monthFilter', 'statusFilter']);
        $this->dateFilter = Carbon::now()->format('Y-m-d');
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    public function filterThisMonth()
    {
        // 🔧 修正：使用單一操作避免狀態衝突
        $this->reset(['dateFilter', 'statusFilter']);
        $this->monthFilter = Carbon::now()->format('Y-m');
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    // 🔧 測試方法
    public function testFilter()
    {
        Log::info('TEST: testFilter called');
        session()->flash('success', '測試方法被成功調用！');
    }

    // 🔧 修正：添加所有篩選屬性的更新監聽器
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
    }

    public function updatedMonthFilter()
    {
        $this->resetPage();
    }

    // 🔑 修正的 confirmApproval 方法
    public function confirmApproval()
    {
        try {
            if (!$this->selectedBooking || $this->selectedBooking->status !== 'pending') {
                session()->flash('error', '無效的預約或預約狀態不正確');
                $this->closeBookingModal();
                return;
            }

            // 🔑 使用 Eloquent 模型觸發 Model Events
            $booking = Booking::find($this->selectedBooking->id);
            
            if (!$booking) {
                session()->flash('error', '預約記錄不存在');
                $this->closeBookingModal();
                return;
            }

            // 使用事務處理確保資料一致性
            DB::beginTransaction();
            
            try {
                // 準備更新資料
                $updateData = ['status' => 'approved'];
                
                if ($this->amount) {
                    $updateData['amount'] = $this->amount;
                }
                
                // 🔑 使用 Eloquent update() 觸發 Model Events
                // 這會自動觸發 Booking.php 中的 static::updated() 事件
                $booking->update($updateData);
                
                // 提交事務
                DB::commit();
                
                Log::info('Booking approved successfully with model events', [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'amount' => $this->amount,
                    'memory_usage' => memory_get_usage(true)
                ]);
                
                $message = '預約已批准' . ($this->amount ? '，報價已設定' : '') . '，LINE 通知將自動發送';
                session()->flash('success', $message);
                
                $this->closeBookingModal();
                
            } catch (\Exception $e) {
                // 回滾事務
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Error in confirmApproval', [
                'booking_id' => $this->selectedBooking ? $this->selectedBooking->id : null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'memory_usage' => memory_get_usage(true)
            ]);
            
            session()->flash('error', '批准預約時發生錯誤：' . $e->getMessage());
            $this->closeBookingModal();
        }
    }

    public function approveBooking($bookingId)
    {
        try {
            // 🔧 只載入必要的欄位
            $this->selectedBooking = Booking::select([
                'id', 'booking_number', 'customer_name', 'customer_line_name', 'customer_phone',
                'booking_time', 'status', 'style_type', 'need_removal', 'amount', 'notes',
                'cancellation_requested', 'cancellation_reason', 'cancellation_requested_at',
                'created_at', 'updated_at', 'user_id'
            ])->find($bookingId);
            
            if ($this->selectedBooking && $this->selectedBooking->status === 'pending') {
                $this->showBookingModal = true;
                $this->amount = $this->selectedBooking->amount ?? '';
            }
        } catch (\Exception $e) {
            Log::error('Error in approveBooking', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage(),
                'memory_usage' => memory_get_usage(true)
            ]);
            session()->flash('error', '開啟預約詳情時發生錯誤');
        }
    }

    public function showBookingDetails($bookingId)
    {
        try {
            $this->selectedBooking = Booking::with(['user', 'images'])->find($bookingId);
            
            if ($this->selectedBooking) {
                $this->showBookingModal = true;
                $this->amount = $this->selectedBooking->amount ?? '';
            }
        } catch (\Exception $e) {
            Log::error('Error in showBookingDetails', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', '載入預約詳情時發生錯誤');
        }
    }

    public function closeBookingModal()
    {
        $this->showBookingModal = false;
        $this->selectedBooking = null;
        $this->amount = '';
    }

    public function editBooking($bookingId)
    {
        try {
            $this->editingBooking = Booking::with(['user', 'images'])->find($bookingId);
            
            if ($this->editingBooking) {
                // 填充編輯表單
                $this->edit_status = $this->editingBooking->status;
                $this->edit_booking_time = $this->editingBooking->booking_time->format('Y-m-d\TH:i');
                $this->edit_style_type = $this->editingBooking->style_type;
                $this->edit_need_removal = $this->editingBooking->need_removal;
                $this->edit_notes = $this->editingBooking->notes;
                $this->edit_customer_name = $this->editingBooking->customer_name;
                $this->edit_customer_line_name = $this->editingBooking->customer_line_name;
                $this->edit_customer_phone = $this->editingBooking->customer_phone;
                $this->edit_amount = $this->editingBooking->amount;
                
                $this->showEditModal = true;
                $this->closeBookingModal();
            }
        } catch (\Exception $e) {
            Log::error('Error in editBooking', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', '載入編輯資料時發生錯誤');
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingBooking = null;
        $this->resetEditForm();
    }

    private function resetEditForm()
    {
        $this->edit_status = '';
        $this->edit_booking_time = '';
        $this->edit_style_type = '';
        $this->edit_need_removal = false;
        $this->edit_notes = '';
        $this->edit_customer_name = '';
        $this->edit_customer_line_name = '';
        $this->edit_customer_phone = '';
        $this->edit_amount = '';
        $this->cancellation_reason = '';
    }

    public function updateBooking()
    {
        $this->validate([
            'edit_customer_name' => 'required|string|max:255',
            'edit_customer_line_name' => 'required|string|max:255',
            'edit_customer_phone' => 'required|string|max:255',
            'edit_booking_time' => 'required|date',
            'edit_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            if (!$this->editingBooking) {
                session()->flash('error', '無效的預約記錄');
                return;
            }

            // 🔑 使用 Eloquent 模型觸發事件
            $booking = Booking::find($this->editingBooking->id);
            
            if (!$booking) {
                session()->flash('error', '預約記錄不存在');
                return;
            }

            DB::beginTransaction();
            
            try {
                // 🔑 使用 Eloquent update 觸發 Model Events
                $booking->update([
                    'status' => $this->edit_status,
                    'booking_time' => $this->edit_booking_time,
                    'style_type' => $this->edit_style_type,
                    'need_removal' => $this->edit_need_removal,
                    'notes' => $this->edit_notes,
                    'customer_name' => $this->edit_customer_name,
                    'customer_line_name' => $this->edit_customer_line_name,
                    'customer_phone' => $this->edit_customer_phone,
                    'amount' => $this->edit_amount,
                ]);
                
                DB::commit();
                
                session()->flash('success', '預約資料已更新');
                $this->closeEditModal();
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Error in updateBooking', [
                'booking_id' => $this->editingBooking ? $this->editingBooking->id : null,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', '更新預約時發生錯誤：' . $e->getMessage());
        }
    }

    // 取消預約
    public function cancelBooking()
    {
        $this->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        try {
            if (!$this->editingBooking) {
                session()->flash('error', '無效的預約記錄');
                return;
            }

            // 🔑 使用 Eloquent 模型觸發事件
            $booking = Booking::find($this->editingBooking->id);
            
            if (!$booking) {
                session()->flash('error', '預約記錄不存在');
                return;
            }

            DB::beginTransaction();
            
            try {
                // 🔑 使用 Eloquent update 觸發 Model Events
                $booking->update([
                    'status' => 'cancelled',
                    'cancellation_reason' => $this->cancellation_reason,
                    'cancelled_at' => now()
                ]);
                
                DB::commit();
                
                session()->flash('success', '預約已取消，LINE 通知將自動發送');
                $this->closeEditModal();
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Error in cancelBooking', [
                'booking_id' => $this->editingBooking ? $this->editingBooking->id : null,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', '取消預約時發生錯誤：' . $e->getMessage());
        }
    }

    // 完成預約
    public function completeBooking()
    {
        try {
            if (!$this->editingBooking) {
                session()->flash('error', '無效的預約記錄');
                return;
            }

            // 🔑 使用 Eloquent 模型觸發事件
            $booking = Booking::find($this->editingBooking->id);
            
            if (!$booking) {
                session()->flash('error', '預約記錄不存在');
                return;
            }

            DB::beginTransaction();
            
            try {
                // 準備更新資料
                $updateData = ['status' => 'completed'];
                
                if ($this->amount) {
                    $updateData['amount'] = $this->amount;
                }
                
                // 🔑 使用 Eloquent update 觸發 Model Events
                $booking->update($updateData);
                
                DB::commit();
                
                session()->flash('success', '預約已完成，LINE 通知將自動發送');
                $this->closeEditModal();
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Error in completeBooking', [
                'booking_id' => $this->editingBooking ? $this->editingBooking->id : null,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', '完成預約時發生錯誤：' . $e->getMessage());
        }
    }

    // 批准取消申請
    public function approveCancellation()
    {
        try {
            if (!$this->selectedBooking && !$this->editingBooking) {
                session()->flash('error', '無效的預約記錄');
                return;
            }

            $bookingId = $this->selectedBooking ? $this->selectedBooking->id : $this->editingBooking->id;
            $booking = Booking::find($bookingId);
            
            if (!$booking) {
                session()->flash('error', '預約記錄不存在');
                return;
            }

            DB::beginTransaction();
            
            try {
                // 🔑 使用 Eloquent update 觸發 Model Events
                $booking->update([
                    'status' => 'cancelled',
                    'cancellation_requested' => false,
                    'cancelled_at' => now()
                ]);
                
                DB::commit();
                
                session()->flash('success', '取消申請已批准，LINE 通知將自動發送');
                $this->closeBookingModal();
                $this->closeEditModal();
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Error in approveCancellation', [
                'booking_id' => $bookingId ?? null,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', '批准取消申請時發生錯誤：' . $e->getMessage());
        }
    }

    // 拒絕取消申請
    public function rejectCancellation()
    {
        try {
            if (!$this->selectedBooking && !$this->editingBooking) {
                session()->flash('error', '無效的預約記錄');
                return;
            }

            $bookingId = $this->selectedBooking ? $this->selectedBooking->id : $this->editingBooking->id;
            $booking = Booking::find($bookingId);
            
            if (!$booking) {
                session()->flash('error', '預約記錄不存在');
                return;
            }

            // 🔑 使用 Eloquent update
                $booking->update([
                    'cancellation_requested' => false,
                    'cancellation_requested_at' => null,
                'cancellation_reason' => null
            ]);
            
            session()->flash('success', '取消申請已拒絕');
                $this->closeBookingModal();
                $this->closeEditModal();
            
        } catch (\Exception $e) {
            Log::error('Error in rejectCancellation', [
                'booking_id' => $bookingId ?? null,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', '拒絕取消申請時發生錯誤：' . $e->getMessage());
        }
    }

    // 圖片預覽相關方法
    public function openImageViewer($imageId)
    {
        try {
            $image = BookingImage::find($imageId);
            
            if ($image) {
                $booking = $image->booking;
                $this->currentImages = $booking->images->pluck('url')->toArray();
                $this->currentImageIndex = $booking->images->search(function($img) use ($imageId) {
                    return $img->id == $imageId;
                });
                $this->currentImageUrl = $image->url;
                $this->showImageModal = true;
            }
        } catch (\Exception $e) {
            Log::error('Error in openImageViewer', [
                'image_id' => $imageId,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function closeImageViewer()
    {
        $this->showImageModal = false;
        $this->currentImageUrl = '';
        $this->currentImages = [];
        $this->currentImageIndex = 0;
    }

    public function nextImage()
    {
        if ($this->currentImageIndex < count($this->currentImages) - 1) {
            $this->currentImageIndex++;
            $this->currentImageUrl = $this->currentImages[$this->currentImageIndex];
        }
    }

    public function prevImage()
    {
        if ($this->currentImageIndex > 0) {
            $this->currentImageIndex--;
            $this->currentImageUrl = $this->currentImages[$this->currentImageIndex];
        }
    }

    public function render()
    {
        // 🔧 調試日誌
        Log::info('BookingManagement render called', [
            'searchTerm' => $this->searchTerm,
            'statusFilter' => $this->statusFilter,
            'dateFilter' => $this->dateFilter,
            'monthFilter' => $this->monthFilter
        ]);

        $query = Booking::with(['user', 'images'])
            ->select([
                'id', 'booking_number', 'user_id', 'booking_time', 'customer_name', 
                'customer_line_name', 'customer_line_id', 'customer_phone', 'style_type', 'need_removal',
                'status', 'amount', 'cancellation_requested', 'cancellation_reason',
                'cancellation_requested_at', 'created_at', 'updated_at'
            ]);

        // 搜尋篩選
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('booking_number', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('customer_line_name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('customer_line_id', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // 狀態篩選
        if ($this->statusFilter !== 'all') {
            if ($this->statusFilter === 'pending_and_cancellation') {
                $query->where(function ($q) {
                    $q->where('status', 'pending')
                      ->orWhere(function ($subQuery) {
                          $subQuery->where('status', 'approved')
                                   ->where('cancellation_requested', true);
                      });
                });
            } elseif ($this->statusFilter === 'cancellation_requested') {
                $query->where('cancellation_requested', true)
                      ->where('status', 'approved');
            } else {
                $query->where('status', $this->statusFilter);
            }
        }

        // 日期篩選
        if ($this->dateFilter) {
            $query->whereDate('booking_time', $this->dateFilter);
        }

        // 月份篩選
        if ($this->monthFilter) {
            try {
                // 🔧 修正：使用更安全的日期範圍查詢，而不是 LIKE
                $startOfMonth = Carbon::createFromFormat('Y-m', $this->monthFilter)->startOfMonth();
                $endOfMonth = Carbon::createFromFormat('Y-m', $this->monthFilter)->endOfMonth();
                $query->whereBetween('booking_time', [$startOfMonth, $endOfMonth]);
            } catch (\Exception $e) {
                Log::error('Invalid month filter format', [
                    'monthFilter' => $this->monthFilter,
                    'error' => $e->getMessage()
                ]);
                // 如果日期格式無效，忽略篩選
            }
        }

        // 排序
        $query->orderBy('created_at', 'desc');

        $bookings = $query->paginate(15);

        return view('livewire.admin.booking-management', [
            'bookings' => $bookings,
            'monthlyStats' => $this->monthlyStats,
        ])->layout('layouts.app');
    }
}