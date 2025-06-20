<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'monthly_booking_limit',
        'monthly_bookings_count',
        'booking_count_reset_date',
    ];

    protected function casts(): array
    {
        return [
            'booking_count_reset_date' => 'date',
        ];
    }

    // 關聯
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 重置每月預約次數
    public function resetMonthlyBookings()
    {
        if ($this->booking_count_reset_date <= now()) {
            $this->update([
                'monthly_bookings_count' => 0,
                'booking_count_reset_date' => now()->startOfMonth()->addMonth(),
            ]);
        }
    }

    // 增加預約次數
    public function incrementBookings()
    {
        $this->resetMonthlyBookings();
        $this->increment('monthly_bookings_count');
    }

    // 🔑 新增：減少預約次數（取消預約時使用）
    public function decrementBookings()
    {
        $this->resetMonthlyBookings();
        if ($this->monthly_bookings_count > 0) {
            $this->decrement('monthly_bookings_count');
        }
    }
}