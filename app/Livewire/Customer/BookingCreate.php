<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\AvailableTime;
use App\Models\Booking;
use App\Models\UserProfile;
use Carbon\Carbon;

class BookingCreate extends Component
{
    public $selectedMonth;
    public $selectedDate = null;
    public $selectedTimeId = null;
    public $availableTimes = [];
    public $availableDates = [];
    public $monthOptions = [];

    // 🔑 新增：預約彈窗相關屬性
    public $showBookingModal = false;
    public $selectedTimeDetails = null;

    // 表單欄位
    public $customer_name = '';
    public $customer_line_name = '';
    public $customer_line_id = '';
    public $customer_phone = '';
    public $need_removal = false;
    public $style_type = 'single_color';
    public $notes = '';

    // 狀態變數
    public $canBookThisMonth = true;
    public $monthlyBookingCount = 0;
    public $monthlyBookingLimit = 3;
    public $isLoggedIn = false;

    protected $rules = [
        'selectedTimeId' => 'required|exists:available_times,id',
        'customer_name' => 'required|string|max:255',
        'customer_line_name' => 'required|string|max:255',
        'customer_line_id' => 'required|string|max:255',
        'customer_phone' => 'required|regex:/^09\d{8}$/',
        'need_removal' => 'boolean',
        'style_type' => 'required|in:single_color,design',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'selectedTimeId.required' => '請選擇預約時間',
        'selectedTimeId.exists' => '選擇的時間不存在',
        'customer_name.required' => '請填寫姓名',
        'customer_line_name.required' => '請填寫LINE名稱',
        'customer_line_id.required' => '請填寫LINE ID',
        'customer_phone.required' => '請填寫電話',
        'customer_phone.regex' => '電話格式不正確，請輸入正確的台灣手機號碼',
        'style_type.required' => '請選擇款式類型',
        'style_type.in' => '款式類型選擇無效',
        'notes.max' => '備註不能超過1000個字元',
    ];

    public function mount()
    {
        $this->selectedMonth = request()->get('month', Carbon::now()->format('Y-m'));
        $this->selectedDate = request()->get('date');
        $this->isLoggedIn = Auth::check();

        // 🔑 確保預設值
        $this->style_type = 'single_color';

        // 確保不能查看過去的月份
        $monthStart = Carbon::createFromFormat('Y-m', $this->selectedMonth)->startOfMonth();
        if ($monthStart->isBefore(Carbon::now()->startOfMonth())) {
            $this->selectedMonth = Carbon::now()->format('Y-m');
        }

        if ($this->isLoggedIn) {
            $user = Auth::user();

            // 預填使用者資料
            $this->customer_name = $user->name ?? '';
            $this->customer_line_name = $user->line_name ?? '';
            $this->customer_line_id = $user->line_contact_id ?? '';
            $this->customer_phone = $user->phone ?? '';

            // 檢查每月預約限制
            $this->checkMonthlyBookingLimit();
        }

        $this->loadMonthOptions();
        $this->loadAvailableDates();
        
        if ($this->selectedDate) {
            $this->loadAvailableTimes();
        }
    }

    public function checkMonthlyBookingLimit()
    {
        $user = Auth::user();
        $userProfile = $user->userProfile;

        if ($userProfile) {
            $this->monthlyBookingLimit = $userProfile->monthly_booking_limit;

            // 重置每月計數（如果需要）
            $userProfile->resetMonthlyBookings();
            $userProfile->refresh();

            $this->monthlyBookingCount = $userProfile->monthly_bookings_count;
            $this->canBookThisMonth = $this->monthlyBookingCount < $this->monthlyBookingLimit;
        }
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
        $monthStart = Carbon::createFromFormat('Y-m', $this->selectedMonth)->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $this->selectedMonth)->endOfMonth();
        
        $this->availableDates = AvailableTime::available()
            ->where('available_time', '>=', Carbon::now()->addDays(3)) // 提前3天限制
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

    public function selectMonth($month)
    {
        $this->selectedMonth = $month;
        $this->selectedDate = null;
        $this->availableTimes = [];
        $this->loadAvailableDates();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->loadAvailableTimes();
    }

    public function loadAvailableTimes()
    {
        if (!$this->selectedDate) {
            $this->availableTimes = [];
            return;
        }

        $this->availableTimes = AvailableTime::available()
            ->whereDate('available_time', $this->selectedDate)
            ->where('available_time', '>=', Carbon::now()->addDays(3))
            ->orderBy('available_time')
            ->get()
            ->map(function ($time) {
                return [
                    'id' => $time->id,
                    'time' => $time->available_time->format('H:i'),
                    'formatted' => $time->available_time->format('Y/m/d H:i'),
                    'day_of_week' => $time->available_time->locale('zh_TW')->dayName,
                    'is_bookable' => $time->isBookable(),
                ];
            })->toArray();
    }

    public function selectTime($timeId)
    {
        $this->selectedTimeId = $timeId;
    }

    // 🔑 新增：選擇時段並開啟預約彈窗（適用於登入用戶）
    public function selectTimeSlot($timeId)
    {
        // 檢查是否已登入
        if (!$this->isLoggedIn) {
            session()->flash('error', '請先登入才能預約');
            return redirect()->route('customer.login');
        }

        // 檢查是否可以預約
        if (!$this->canBookThisMonth) {
            session()->flash('error', '本月預約次數已達上限');
            return;
        }

        // 尋找選中的時段
        $selectedTime = collect($this->availableTimes)->firstWhere('id', $timeId);
        if (!$selectedTime) {
            session()->flash('error', '選擇的時段不存在');
            return;
        }

        // 設定選中的時段資訊
        $this->selectedTimeId = $timeId;
        $this->selectedTimeDetails = $selectedTime;
        $this->showBookingModal = true;
    }

    // 🔑 新增：關閉預約彈窗
    public function closeBookingModal()
    {
        $this->showBookingModal = false;
        $this->selectedTimeId = null;
        $this->selectedTimeDetails = null;
        $this->resetValidation();
    }

    public function submitBooking()
    {
        // 檢查是否已登入
        if (!$this->isLoggedIn) {
            session()->flash('error', '請先登入才能進行預約');
            return redirect()->route('customer.login');
        }

        // 檢查每月預約限制
        $this->checkMonthlyBookingLimit();
        if (!$this->canBookThisMonth) {
            session()->flash('error', '本月預約次數已達上限');
            return;
        }

        try {
            // 驗證表單
            $this->validate();

            // 檢查選擇的時間是否仍然可用
            $selectedTime = AvailableTime::find($this->selectedTimeId);
            if (!$selectedTime || !$selectedTime->isBookable()) {
                session()->flash('error', '選擇的時間已不可預約，請重新選擇');
                $this->loadAvailableTimes();
                $this->closeBookingModal();
                return;
            }

            // 建立預約
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'booking_time' => $selectedTime->available_time,
                'customer_name' => $this->customer_name,
                'customer_line_name' => $this->customer_line_name,
                'customer_line_id' => $this->customer_line_id,
                'customer_phone' => $this->customer_phone,
                'need_removal' => $this->need_removal,
                'style_type' => $this->style_type,
                'notes' => $this->notes,
                'status' => Booking::STATUS_PENDING,
            ]);

            // 標記時間為已預約
            $selectedTime->markAsBooked(Auth::id());

            // 關閉彈窗並顯示成功訊息
            $this->closeBookingModal();
            session()->flash('success', "預約成功！您的預約單號是：{$booking->booking_number}，請等待管理員審核。");

            // 重新載入可用時段
            $this->loadAvailableTimes();
            $this->checkMonthlyBookingLimit();

            return redirect()->route('booking.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Booking creation error: ' . $e->getMessage());
            session()->flash('error', '預約失敗，請稍後再試');
        }
    }

    public function render()
    {
        return view('livewire.customer.booking-create')
            ->layout('layouts.app'); // 🔑 關鍵：指定使用 app.blade.php layout
    }
}