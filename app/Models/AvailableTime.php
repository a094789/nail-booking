<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AvailableTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'available_time',
        'is_available',
        'booked_by',
    ];

    protected function casts(): array
    {
        return [
            'available_time' => 'datetime',
            'is_available' => 'boolean',
        ];
    }

    // 關聯 - 🔑 更新方法名稱
    public function bookedByUser()
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    // 🔑 保留原有方法名稱
    public function bookedBy()
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    // 🔑 新增：關聯到預約記錄
    public function booking()
    {
        return $this->hasOne(Booking::class, 'booking_time', 'available_time')
                    ->where('user_id', $this->booked_by);
    }

    // 範圍查詢 - 🔑 更新邏輯
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // 🔑 新增：即將到來的時段（提前3天限制）
    public function scopeUpcoming($query, $daysFromNow = 3)
    {
        return $query->where('available_time', '>=', Carbon::now()->addDays($daysFromNow));
    }

    // 🔑 新增：日期範圍查詢
    public function scopeInDateRange($query, $startDate, $endDate = null)
    {
        $query->whereDate('available_time', '>=', $startDate);
        
        if ($endDate) {
            $query->whereDate('available_time', '<=', $endDate);
        }
        
        return $query;
    }

    public function scopeInMonth($query, $year, $month)
    {
        return $query->whereYear('available_time', $year)
                    ->whereMonth('available_time', $month);
    }

    // 🔑 新增：格式化時間屬性
    public function getFormattedTimeAttribute()
    {
        return $this->available_time->format('Y/m/d H:i');
    }

    public function getFormattedDateAttribute()
    {
        return $this->available_time->format('Y/m/d');
    }

    public function getFormattedTimeOnlyAttribute()
    {
        return $this->available_time->format('H:i');
    }

    public function getDayOfWeekAttribute()
    {
        $days = [
            'Sunday' => '星期日',
            'Monday' => '星期一',
            'Tuesday' => '星期二',
            'Wednesday' => '星期三',
            'Thursday' => '星期四',
            'Friday' => '星期五',
            'Saturday' => '星期六',
        ];
        
        return $days[$this->available_time->format('l')] ?? '';
    }

    // 🔑 新增：檢查是否可以預約（提前3天限制）
    public function isBookable()
    {
        return $this->is_available && 
               $this->available_time >= Carbon::now()->addDays(3);
    }

    // 預約時段 - 🔑 更新方法名稱
    public function markAsBooked($userId)
    {
        $this->update([
            'is_available' => false,
            'booked_by' => $userId,
        ]);
    }

    // 🔑 保留原有方法
    public function bookBy(User $user)
    {
        $this->update([
            'is_available' => false,
            'booked_by' => $user->id,
        ]);
    }

    // 釋放時段
    public function release()
    {
        $this->update([
            'is_available' => true,
            'booked_by' => null,
        ]);
    }
}