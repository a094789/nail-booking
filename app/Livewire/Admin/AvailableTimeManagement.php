<?php
// app/Livewire/Admin/AvailableTimeManagement.php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AvailableTime;
use Carbon\Carbon;

class AvailableTimeManagement extends Component
{
    public $selectedMonth;
    public $selectedDate = '';
    public $showBulkModal = false;
    public $showTimeModal = false;
    
    // 單日時段管理
    public $customTime = '';
    public $batchStartTime = '09:00';
    public $batchEndTime = '17:00';
    public $batchInterval = 60;
    public $dayTimeSlots = [];
    
    // 快速新增相關
    public $startDate = '';
    public $endDate = '';
    public $selectedDays = [1, 2, 3, 4, 5]; // 預設週一到週五
    public $selectedTimes = ['09:00', '10:00', '14:00', '15:00']; // 預設時段

    protected $rules = [
        'startDate' => 'required|date|after_or_equal:today',
        'endDate' => 'required|date|after_or_equal:startDate',
        'selectedDays' => 'required|array|min:1',
        'selectedTimes' => 'required|array|min:1',
        'batchInterval' => 'required|integer|min:15|max:180',
    ];

    protected $messages = [
        'startDate.required' => '請選擇開始日期',
        'startDate.after_or_equal' => '開始日期不能早於今天',
        'endDate.required' => '請選擇結束日期',
        'endDate.after_or_equal' => '結束日期不能早於開始日期',
        'selectedDays.required' => '請選擇至少一個星期',
        'selectedTimes.required' => '請選擇至少一個時段',
        'batchInterval.required' => '請填寫間隔時間',
        'batchInterval.integer' => '間隔時間必須是數字',
        'batchInterval.min' => '間隔時間不能少於15分鐘',
        'batchInterval.max' => '間隔時間不能超過180分鐘',
    ];

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->format('Y-m');
        $this->startDate = Carbon::now()->format('Y-m-d');
        $this->endDate = Carbon::now()->addDays(30)->format('Y-m-d');
    }

    // 月曆導航
    public function previousMonth()
    {
        $current = Carbon::createFromFormat('Y-m', $this->selectedMonth);
        $this->selectedMonth = $current->subMonth()->format('Y-m');
    }

    public function nextMonth()
    {
        $current = Carbon::createFromFormat('Y-m', $this->selectedMonth);
        $this->selectedMonth = $current->addMonth()->format('Y-m');
    }

    // 獲取時段數據
    public function getTimeSlotsProperty()
    {
        $monthStart = Carbon::createFromFormat('Y-m', $this->selectedMonth)->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $this->selectedMonth)->endOfMonth();

        $slots = AvailableTime::whereBetween('available_time', [$monthStart, $monthEnd])
            ->orderBy('available_time')
            ->get()
            ->groupBy(function ($item) {
                return $item->available_time->format('Y-m-d');
            });

        // 轉換成數組格式
        $result = [];
        foreach ($slots as $date => $dateSlots) {
            $result[$date] = $dateSlots->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'available_time' => $slot->available_time->toDateTimeString(),
                    'is_available' => $slot->is_available,
                    'booked_by' => $slot->booked_by,
                ];
            })->toArray();
        }

        return $result;
    }

    // 開啟單日時段模態窗
    public function openTimeModal($date)
    {
        $this->selectedDate = $date;
        $this->customTime = '';
        $this->loadDayTimeSlots();
        $this->showTimeModal = true;
    }

    public function closeTimeModal()
    {
        $this->showTimeModal = false;
        $this->selectedDate = '';
        $this->dayTimeSlots = [];
        $this->resetValidation();
    }

    // 載入單日時段
    public function loadDayTimeSlots()
    {
        if (!$this->selectedDate) return;

        $this->dayTimeSlots = AvailableTime::whereDate('available_time', $this->selectedDate)
            ->orderBy('available_time')
            ->get()
            ->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'available_time' => $slot->available_time->toDateTimeString(),
                    'is_available' => $slot->is_available,
                    'booked_by' => $slot->booked_by,
                ];
            })->toArray();
    }

    // 新增單個時段（帶時間參數）
    public function addSingleTimeSlot($date, $time)
    {
        try {
            $dateTime = Carbon::parse($date . ' ' . $time);
            
            // 檢查是否已存在
            $exists = AvailableTime::where('available_time', $dateTime)->exists();
            
            if (!$exists) {
                AvailableTime::create([
                    'available_time' => $dateTime,
                    'is_available' => true,
                ]);
                
                $this->loadDayTimeSlots(); // 重新載入當日時段
                session()->flash('success', '時段新增成功');
            } else {
                session()->flash('error', '該時段已存在');
            }
        } catch (\Exception $e) {
            session()->flash('error', '新增失敗：' . $e->getMessage());
        }
    }

    // 新增自定義時間
    public function addCustomTime()
    {
        if ($this->customTime && $this->selectedDate) {
            $this->addSingleTimeSlot($this->selectedDate, $this->customTime);
            $this->customTime = '';
        }
    }

    // 批量新增時段（單日）
    public function addBatchTimeSlots()
    {
        if (!$this->selectedDate || !$this->batchStartTime || !$this->batchEndTime) {
            session()->flash('error', '請填寫完整資訊');
            return;
        }

        try {
            $start = Carbon::parse($this->selectedDate . ' ' . $this->batchStartTime);
            $end = Carbon::parse($this->selectedDate . ' ' . $this->batchEndTime);
            $created = 0;

            if ($start >= $end) {
                session()->flash('error', '結束時間必須晚於開始時間');
                return;
            }

            while ($start < $end) {
                $exists = AvailableTime::where('available_time', $start)->exists();
                
                if (!$exists) {
                    AvailableTime::create([
                        'available_time' => $start,
                        'is_available' => true,
                    ]);
                    $created++;
                }
                
                $start->addMinutes($this->batchInterval);
            }

            $this->loadDayTimeSlots();
            session()->flash('success', "成功新增 {$created} 個時段");
        } catch (\Exception $e) {
            session()->flash('error', '批量新增失敗：' . $e->getMessage());
        }
    }

    // 刪除時段
    public function deleteTimeSlot($timeSlotId)
    {
        $timeSlot = AvailableTime::find($timeSlotId);
        
        if ($timeSlot) {
            if ($timeSlot->is_available) {
                $timeSlot->delete();
                $this->loadDayTimeSlots(); // 重新載入當日時段
                session()->flash('success', '時段已刪除');
            } else {
                session()->flash('error', '無法刪除已預約的時段');
            }
        }
    }

    // 顯示日期詳情（未來擴展用）
    public function showDayDetail($date)
    {
        $this->openTimeModal($date);
    }

    // 快速新增模態窗
    public function openBulkModal()
    {
        $this->showBulkModal = true;
    }

    public function closeBulkModal()
    {
        $this->showBulkModal = false;
        $this->reset(['selectedDays', 'startDate', 'endDate', 'selectedTimes']);
        $this->selectedDays = [1, 2, 3, 4, 5]; // 重設為週一到週五
        $this->selectedTimes = ['09:00', '10:00', '14:00', '15:00']; // 重設預設時段
        $this->startDate = Carbon::now()->format('Y-m-d');
        $this->endDate = Carbon::now()->addDays(30)->format('Y-m-d');
    }

    // 新增時段到選擇列表
    public function addNewTimeSlot()
    {
        $this->selectedTimes[] = '09:00'; // 預設時間
    }

    // 移除時段
    public function removeTimeSlot($index)
    {
        unset($this->selectedTimes[$index]);
        $this->selectedTimes = array_values($this->selectedTimes); // 重新排序索引
    }

    // 快速新增時段（多日）
    public function generateTimeSlots()
    {
        $this->validate();

        try {
            $start = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);
            $created = 0;

            while ($start <= $end) {
                $dayOfWeek = $start->dayOfWeek;
                
                if (in_array($dayOfWeek, $this->selectedDays)) {
                    foreach ($this->selectedTimes as $time) {
                        $dateTime = Carbon::parse($start->format('Y-m-d') . ' ' . $time);
                        
                        // 檢查是否已存在
                        $exists = AvailableTime::where('available_time', $dateTime)->exists();
                        
                        if (!$exists) {
                            AvailableTime::create([
                                'available_time' => $dateTime,
                                'is_available' => true,
                            ]);
                            $created++;
                        }
                    }
                }
                
                $start->addDay();
            }

            session()->flash('success', "成功新增 {$created} 個時段");
            $this->closeBulkModal();

        } catch (\Exception $e) {
            session()->flash('error', '新增失敗：' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.available-time-management', [
            'timeSlots' => $this->timeSlots,
        ])->layout('layouts.app');
    }
}