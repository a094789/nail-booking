<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'user_id',
        'booking_time',
        'customer_name',
        'customer_line_name',
        'customer_line_id',
        'customer_phone',
        'need_removal',
        'style_type',
        'notes',
        'status',
        'cancellation_reason',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'booking_time' => 'datetime',
            'need_removal' => 'boolean',
            'amount' => 'decimal:2',
        ];
    }

    // 關聯
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(BookingImage::class)->orderBy('sort_order');
    }

    // 🔑 狀態常數 - 與資料庫 ENUM 一致
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';    // 🔑 資料庫中使用 approved，不是 confirmed
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    // 款式常數
    const STYLE_SINGLE = 'single_color';
    const STYLE_DESIGN = 'design';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => '待審核',
            self::STATUS_APPROVED => '預約成功',     // 🔑 使用 approved
            self::STATUS_CANCELLED => '已取消',
            self::STATUS_COMPLETED => '已完成',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    // 🔑 取得狀態文字屬性
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => '待審核',
            self::STATUS_APPROVED => '預約成功',    // 🔑 使用 approved
            self::STATUS_CANCELLED => '已取消',
            self::STATUS_COMPLETED => '已完成',
            default => '未知狀態',
        };
    }

    // 🔑 取得款式類型文字屬性
    public function getStyleTypeTextAttribute()
    {
        return match ($this->style_type) {
            self::STYLE_SINGLE => '單色',      // 'single_color' => '單色'
            self::STYLE_DESIGN => '造型',      // 'design' => '造型'
            default => '未知類型',
        };
    }

    // 🔑 取得狀態顏色屬性
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_APPROVED => 'green',      // 🔑 使用 approved
            self::STATUS_CANCELLED => 'red',
            self::STATUS_COMPLETED => 'blue',
            default => 'gray',
        };
    }

    // 🔑 改進預約單號生成邏輯
    public static function generateBookingNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastBooking = self::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastBooking ?
            intval(substr($lastBooking->booking_number, -3)) + 1 : 1;

        return $date . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    // 🔑 檢查是否可以取消
    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED])  // 🔑 使用 approved
            && $this->booking_time > Carbon::now()->addHours(24);
    }

    // 🔑 Boot 方法，自動生成預約單號
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = self::generateBookingNumber();
            }
        });
    }
}