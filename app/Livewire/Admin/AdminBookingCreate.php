<?php
// app/Livewire/Admin/AdminBookingCreate.php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Booking;
use App\Models\User;
use App\Models\AvailableTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminBookingCreate extends Component
{
    public $showCreateModal = false;

    // 時段選擇相關
    public $selectedMonth;
    public $selectedDate = null;
    public $selectedTimeSlot = null;
    public $availableTimes = [];
    public $availableDates = [];
    public $monthOptions = [];

    // 表單欄位
    public $selectedUser = '';
    public $customer_name = '';
    public $customer_line_name = '';
    public $customer_line_id = '';
    public $customer_phone = '';
    public $need_removal = false;
    public $style_type = 'single_color';
    public $notes = '';
    public $status = 'approved'; // 管理員建立直接核准

    // 搜尋相關
    public $userSearch = '';

    protected $listeners = [
        'open-create-modal' => 'openCreateModal',
        'openCreateModal' => 'openCreateModal'
    ];

    protected $rules = [
        'selectedUser' => 'required|exists:users,id',
        'selectedTimeSlot' => 'required|exists:available_times,id',
        'customer_name' => 'required|string|max:255',
        'customer_line_name' => 'required|string|max:255',
        'customer_line_id' => 'required|string|max:255',
        'customer_phone' => 'required|regex:/^09\d{8}$/',
        'need_removal' => 'boolean',
        'style_type' => 'required|in:single_color,design',
        'notes' => 'nullable|string|max:1000',
        'status' => 'required|in:pending,approved',
    ];

    protected $messages = [
        'selectedUser.required' => '請選擇使用者',
        'selectedUser.exists' => '選擇的使用者不存在',
        'selectedTimeSlot.required' => '請選擇預約時段',
        'selectedTimeSlot.exists' => '選擇的時段不存在',
        'customer_name.required' => '請填寫姓名',
        'customer_line_name.required' => '請填寫LINE名稱',
        'customer_line_id.required' => '請填寫LINE ID',
        'customer_phone.required' => '請填寫電話',
        'customer_phone.regex' => '電話格式不正確',
        'style_type.required' => '請選擇款式類型',
        'notes.max' => '備註不能超過1000個字元',
    ];

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->format('Y-m');
        $this->loadMonthOptions();
        $this->loadAvailableDates();
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->resetForm();
        $this->loadMonthOptions();
        $this->loadAvailableDates();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->selectedUser = '';
        $this->userSearch = '';
        $this->selectedMonth = Carbon::now()->format('Y-m');
        $this->selectedDate = null;
        $this->selectedTimeSlot = null;
        $this->availableTimes = [];
        $this->availableDates = [];
        $this->customer_name = '';
        $this->customer_line_name = '';
        $this->customer_line_id = '';
        $this->customer_phone = '';
        $this->need_removal = false;
        $this->style_type = 'single_color';
        $this->notes = '';
        $this->status = 'approved';
    }

    public function loadMonthOptions()
    {
        $this->monthOptions = collect();
        for ($i = 0; $i < 3; $i++) {
            $month = Carbon::now()->addMonths($i);
            $this->monthOptions->push([
                'value' => $month->format('Y-m'),
                'label' => $month->format('Y年m月'),
                'is_current' => $month->format('Y-m') === Carbon::now()->format('Y-m'),
            ]);
        }
    }

    public function loadAvailableDates()
    {
        if (!$this->selectedMonth) {
            $this->availableDates = [];
            return;
        }

        $monthStart = Carbon::createFromFormat('Y-m', $this->selectedMonth)->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $this->selectedMonth)->endOfMonth();
        
        $this->availableDates = AvailableTime::available()
            ->where('available_time', '>=', Carbon::now()) // 管理員可以預約當天
            ->whereBetween('available_time', [$monthStart, $monthEnd])
            ->selectRaw('DATE(available_time) as date, COUNT(*) as available_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $date = Carbon::parse($item->date);
                return [
                    'date' => $item->date,
                    'formatted' => $date->format('Y/m/d'),
                    'day' => $date->format('d'),
                    'day_of_week' => $date->locale('zh_TW')->dayName,
                    'available_count' => $item->available_count,
                    'is_today' => $date->isToday(),
                    'is_weekend' => $date->isWeekend(),
                ];
            })->toArray();
    }

    public function loadAvailableTimes()
    {
        if (!$this->selectedDate) {
            $this->availableTimes = [];
            return;
        }

        $this->availableTimes = AvailableTime::available()
            ->whereDate('available_time', $this->selectedDate)
            ->where('available_time', '>=', Carbon::now()) // 管理員可以預約當天
            ->orderBy('available_time')
            ->get()
            ->map(function ($time) {
                return [
                    'id' => $time->id,
                    'time' => $time->available_time->format('H:i'),
                    'formatted' => $time->available_time->format('Y/m/d H:i'),
                    'day_of_week' => $time->available_time->locale('zh_TW')->dayName,
                ];
            })->toArray();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->selectedTimeSlot = null; // 重置時段選擇
        $this->loadAvailableTimes();
    }

    public function selectTimeSlot($timeSlotId)
    {
        $this->selectedTimeSlot = $timeSlotId;
    }

    public function getUsersProperty()
    {
        if (empty($this->userSearch) || strlen($this->userSearch) < 2) {
            return collect();
        }

        return User::where('role', 'user')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->userSearch . '%')
                    ->orWhere('email', 'like', '%' . $this->userSearch . '%')
                    ->orWhere('line_name', 'like', '%' . $this->userSearch . '%')
                    ->orWhere('phone', 'like', '%' . $this->userSearch . '%');
            })
            ->limit(10)
            ->get();
    }

    public function selectUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $this->selectedUser = $userId;
            $this->userSearch = $user->name;
            $this->customer_name = $user->name;
            $this->customer_line_name = $user->line_name ?? '';
            $this->customer_line_id = $user->line_contact_id ?? '';
            $this->customer_phone = $user->phone ?? '';
        }
    }

    public function createBooking()
    {
        $this->validate();

        try {
            // 獲取選擇的時段
            $availableTime = AvailableTime::find($this->selectedTimeSlot);
            if (!$availableTime || !$availableTime->is_available) {
                session()->flash('error', '選擇的時段已不可用，請重新選擇');
                return;
            }

            // 生成預約編號
            $bookingNumber = 'BK' . date('Ymd') . str_pad(Booking::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);

            // 建立預約
            $booking = Booking::create([
                'user_id' => $this->selectedUser,
                'booking_number' => $bookingNumber,
                'booking_time' => $availableTime->available_time,
                'customer_name' => $this->customer_name,
                'customer_line_name' => $this->customer_line_name,
                'customer_line_id' => $this->customer_line_id,
                'customer_phone' => $this->customer_phone,
                'need_removal' => $this->need_removal,
                'style_type' => $this->style_type,
                'notes' => $this->notes,
                'status' => $this->status,
                'created_by_admin' => true, // 標識這是管理員創建的預約
                // 🔑 新增：行前確認相關設定
                'requires_confirmation' => true,
                'is_confirmed' => false,
            ]);

            // 🔑 新增：設定確認截止時間和生成確認Token
            if ($booking->status === 'approved') {
                $booking->setConfirmationDeadline();
                $booking->generateConfirmationToken();
            }

            // 更新 AvailableTime 狀態
            $availableTime->update([
                'is_available' => false,
                'booked_by' => $this->selectedUser,
            ]);

            session()->flash('success', '預約建立成功！預約編號：' . $bookingNumber);
            $this->closeCreateModal();
            
            // 通知父組件重新整理
            $this->dispatch('booking-created');

        } catch (\Exception $e) {
            Log::error('Admin booking creation error: ' . $e->getMessage());
            session()->flash('error', '建立預約時發生錯誤，請稍後再試');
        }
    }

    public function updatedSelectedMonth()
    {
        $this->selectedDate = null;
        $this->selectedTimeSlot = null;
        $this->availableTimes = [];
        $this->loadAvailableDates();
    }

    public function render()
    {
        return view('livewire.admin.admin-booking-create', [
            'users' => $this->getUsersProperty(),
        ]);
    }
}