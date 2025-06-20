<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Services\LineNotificationService;
use Carbon\Carbon;

class BookingIndex extends Component
{
    use WithPagination;

    public $selectedMonth = '';
    public $selectedStatus = 'all';
    public $selectedDate = '';
    public $showCancelModal = false;
    public $showCancelRequestModal = false;
    public $cancellingBooking = null;
    public $cancellationReason = '';

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'selectedMonth' => ['except' => ''],
        'selectedStatus' => ['except' => 'all'],
        'selectedDate' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $rules = [
        'cancellationReason' => 'required|string|max:500',
    ];

    protected $messages = [
        'cancellationReason.required' => '請填寫取消原因',
        'cancellationReason.max' => '取消原因不能超過500個字元',
    ];

    public function mount()
    {
        // 初始化完成，統計資料會透過 getMonthlyStatsProperty 自動計算
    }

    public function filterPending()
    {
        $this->selectedStatus = 'pending';
        $this->selectedDate = '';
        $this->selectedMonth = '';
        $this->resetPage();
    }

    public function filterToday()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->selectedMonth = '';
        $this->selectedStatus = 'all';
        $this->resetPage();
    }

    public function filterThisMonth()
    {
        $this->selectedMonth = now()->format('Y-m');
        $this->selectedDate = '';
        $this->selectedStatus = 'all';
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->selectedMonth = '';
        $this->selectedStatus = 'all';
        $this->selectedDate = '';
        $this->resetPage();
    }

    public function applyFilters()
    {
        // 重新載入頁面以應用篩選條件
        $this->resetPage();
    }

    public function getBookingsProperty()
    {
        $query = Booking::where('user_id', Auth::id())
            ->with(['images'])
            ->orderBy('created_at', 'desc');

        if ($this->selectedMonth) {
            $query->whereYear('booking_time', substr($this->selectedMonth, 0, 4))
                  ->whereMonth('booking_time', substr($this->selectedMonth, 5, 2));
        }

        if ($this->selectedDate) {
            $query->whereDate('booking_time', $this->selectedDate);
        }

        if ($this->selectedStatus !== 'all') {
            $query->where('status', $this->selectedStatus);
        }

        return $query->paginate(5);
    }

    public function getMonthlyStatsProperty()
    {
        $userProfile = Auth::user()->userProfile;
        $limit = $userProfile ? $userProfile->monthly_booking_limit : 3;
        $count = $userProfile ? $userProfile->monthly_bookings_count : 0;

        $currentMonth = Carbon::now()->format('Y-m');
        $stats = Booking::where('user_id', Auth::id())
            ->where('created_by_admin', false)
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$currentMonth])
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled
            ')
            ->first();

        return [
            'total' => $stats->total ?? 0,
            'pending' => $stats->pending ?? 0,
            'confirmed' => $stats->confirmed ?? 0,
            'completed' => $stats->completed ?? 0,
            'cancelled' => $stats->cancelled ?? 0,
            'limit' => $limit,
            'remaining' => max(0, $limit - $count),
        ];
    }

    public function getAvailableMonthsProperty()
    {
        $months = Booking::where('user_id', Auth::id())
            ->selectRaw('DATE_FORMAT(booking_time, "%Y-%m") as month')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('month');

        $currentMonth = Carbon::now()->format('Y-m');
        if (!$months->contains($currentMonth)) {
            $months->prepend($currentMonth);
        }

        return $months->map(function ($month) {
            return [
                'value' => $month,
                'label' => Carbon::createFromFormat('Y-m', $month)->format('Y年m月'),
            ];
        });
    }

    public function openCancelModal($bookingId)
    {
        $this->cancellingBooking = Booking::find($bookingId);
        if ($this->cancellingBooking && $this->cancellingBooking->status === 'pending') {
            $this->cancellationReason = '';
            $this->showCancelModal = true;
        }
    }

    public function openCancelRequestModal($bookingId)
    {
        $this->cancellingBooking = Booking::find($bookingId);
        if ($this->cancellingBooking && $this->cancellingBooking->status === 'approved' && !$this->cancellingBooking->cancellation_requested) {
            $this->cancellationReason = '';
            $this->showCancelRequestModal = true;
        }
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
        $this->cancellingBooking = null;
        $this->cancellationReason = '';
        $this->resetValidation();
    }

    public function closeCancelRequestModal()
    {
        $this->showCancelRequestModal = false;
        $this->cancellingBooking = null;
        $this->cancellationReason = '';
        $this->resetValidation();
    }

    public function viewBooking($bookingId)
    {
        // 觸發 BookingView 組件顯示
        Log::info("Dispatching showBookingView event with ID: " . $bookingId);
        $this->dispatch('showBookingView', $bookingId);
        session()->flash('success', '正在載入預約詳情...');
    }



    // 🔑 新增：檢查預約是否可以確認
    public function canConfirmBooking($booking)
    {
        if (!$booking->requires_confirmation || $booking->is_confirmed || $booking->status !== Booking::STATUS_APPROVED) {
            return false;
        }

        // 檢查確認功能是否已開放
        $confirmationOpenTime = $booking->booking_time->copy()->subDay()->startOfDay();
        if (now() < $confirmationOpenTime) {
            return false;
        }

        // 檢查是否逾期
        if ($booking->isConfirmationOverdue()) {
            return false;
        }

        return true;
    }

    // 🔑 新增：頁面內確認預約功能
    public function confirmBooking($bookingId)
    {
        $booking = Booking::find($bookingId);
        
        if (!$booking || $booking->user_id !== Auth::id()) {
            session()->flash('error', '預約不存在或無權限');
            return;
        }

        if (!$this->canConfirmBooking($booking)) {
            session()->flash('error', '預約無法確認');
            return;
        }

        try {
            $booking->confirmBooking();
            session()->flash('success', '預約確認成功！感謝您的確認，我們期待為您服務！');
            
            // 刷新頁面數據
            $this->render();
            
        } catch (\Exception $e) {
            Log::error('預約確認失敗', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', '確認失敗，請稍後再試');
        }
    }

    public function cancelBooking()
    {
        // 只有預約成功才需要取消原因
        if ($this->cancellingBooking && $this->cancellingBooking->status === 'approved') {
            $this->validate([
                'cancellationReason' => 'required|string|max:50'
            ]);
        }

        if (!$this->cancellingBooking || !in_array($this->cancellingBooking->status, ['pending', 'approved'])) {
            return;
        }

        $updateData = [
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => 'user',  // 🔑 新增：標記為用戶取消
        ];
        // 只有預約成功才記錄取消原因
        if ($this->cancellingBooking->status === 'approved') {
            $updateData['cancellation_reason'] = $this->cancellationReason;
        } else {
            $updateData['cancellation_reason'] = null;
        }

        // 🔑 修改：只更新模型，讓模型事件處理 LINE 通知
        $this->cancellingBooking->update($updateData);

        // 釋放時段
        $availableTime = \App\Models\AvailableTime::where('available_time', $this->cancellingBooking->booking_time)
            ->where('booked_by', $this->cancellingBooking->user_id)
            ->first();
        if ($availableTime) {
            $availableTime->release();
        }

        $this->showCancelModal = false;
        $this->cancellingBooking = null;
        $this->cancellationReason = '';
        $this->resetValidation();

        session()->flash('success', '預約已取消');
    }

    public function requestCancellation()
    {
        $this->validate([
            'cancellationReason' => 'required|string|max:500'
        ]);

        if (!$this->cancellingBooking || $this->cancellingBooking->status !== 'approved') {
            return;
        }

        $this->cancellingBooking->update([
            'cancellation_requested' => true,
            'cancellation_reason' => $this->cancellationReason,
            'cancellation_requested_at' => now(),
        ]);

        $this->closeCancelRequestModal();
        session()->flash('success', '取消預約申請已送出，等待管理員審核');
    }

    public function updatedSelectedMonth()
    {
        $this->resetPage();
    }

    public function updatedSelectedStatus()
    {
        $this->resetPage();
    }

    public function updatedSelectedDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Booking::with(['user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // 月份篩選
        if ($this->selectedMonth) {
            $startOfMonth = Carbon::parse($this->selectedMonth . '-01')->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            $query->whereBetween('booking_time', [$startOfMonth, $endOfMonth]);
        }

        // 狀態篩選
        if ($this->selectedStatus !== 'all') {
            $query->where('status', $this->selectedStatus);
        }

        // 日期篩選
        if ($this->selectedDate) {
            $selectedDate = Carbon::parse($this->selectedDate);
            $query->whereDate('booking_time', $selectedDate->toDateString());
        }

        $bookings = $query->paginate(10);

        return view('livewire.customer.booking-index', [
            'bookings' => $bookings,
            'monthlyStats' => $this->monthlyStats,
        ])->layout('layouts.app');
    }
}