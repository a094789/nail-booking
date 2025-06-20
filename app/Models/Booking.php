<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Jobs\SendLineNotificationJob;

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
        'rejection_reason',              // 🔑 新增：拒絕原因
        'cancelled_at',
        'cancelled_by',                  // 🔑 新增：記錄取消者
        'cancellation_requested',        // 🔑 新增
        'cancellation_requested_at',     // 🔑 新增
        'amount',
        'created_by_admin',              // 標識是否由管理員創建
        // 🔑 行前確認相關欄位
        'requires_confirmation',
        'is_confirmed',
        'confirmed_at',
        'confirmation_deadline',
        'confirmation_reminder_sent',
        'confirmation_token',
        'confirmation_token_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'booking_time' => 'datetime',
            'need_removal' => 'boolean',
            'cancelled_at' => 'datetime',               // 🔑 新增
            'cancellation_requested' => 'boolean',      // 🔑 新增
            'cancellation_requested_at' => 'datetime',  // 🔑 新增
            'amount' => 'integer',
            'created_by_admin' => 'boolean',            // 標識是否由管理員創建
            // 🔑 行前確認相關欄位
            'requires_confirmation' => 'boolean',
            'is_confirmed' => 'boolean',
            'confirmed_at' => 'datetime',
            'confirmation_deadline' => 'datetime',
            'confirmation_reminder_sent' => 'boolean',
            'confirmation_token_expires_at' => 'datetime',
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

    // 狀態常數
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    // 款式常數
    const STYLE_SINGLE = 'single_color';
    const STYLE_DESIGN = 'design';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => '待審核',
            self::STATUS_APPROVED => '預約成功',
            self::STATUS_CANCELLED => '已取消',
            self::STATUS_COMPLETED => '已完成',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => '待審核',
            self::STATUS_APPROVED => '預約成功',
            self::STATUS_CANCELLED => '已取消',
            self::STATUS_COMPLETED => '已完成',
            default => '未知狀態',
        };
    }

    public function getStyleTypeTextAttribute()
    {
        return match ($this->style_type) {
            self::STYLE_SINGLE => '單色',
            self::STYLE_DESIGN => '造型',
            default => '未知類型',
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_APPROVED => 'green',
            self::STATUS_CANCELLED => 'red',
            self::STATUS_COMPLETED => 'blue',
            default => 'gray',
        };
    }

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

    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED])
            && $this->booking_time > Carbon::now()->addHours(24);
    }

    // 🔑 新增：檢查是否有取消申請待審核
    public function hasPendingCancellation()
    {
        return $this->cancellation_requested && $this->status === self::STATUS_APPROVED;
    }

    // 🔑 新增：行前確認相關方法
    public function needsConfirmation()
    {
        return $this->requires_confirmation && 
               !$this->is_confirmed && 
               $this->status === self::STATUS_APPROVED &&
               $this->confirmation_deadline > now();
    }

    public function isConfirmationOverdue()
    {
        return $this->requires_confirmation && 
               !$this->is_confirmed && 
               $this->status === self::STATUS_APPROVED &&
               $this->confirmation_deadline < now();
    }

    public function confirmBooking()
    {
        $this->update([
            'is_confirmed' => true,
            'confirmed_at' => now(),
        ]);
    }

    public function setConfirmationDeadline()
    {
        // 設定確認截止時間為預約當日 00:10
        $deadline = $this->booking_time->copy()->startOfDay()->addMinutes(10);
        
        // 🔧 使用 updateQuietly() 避免觸發 Model Events
        $this->updateQuietly(['confirmation_deadline' => $deadline]);
    }

    // 🔑 新增：生成安全的確認Token
    public function generateConfirmationToken()
    {
        // 生成64字元的隨機Token
        $token = \Illuminate\Support\Str::random(64);
        
        // Token有效期為預約當日 00:10
        $expiresAt = $this->booking_time->copy()->startOfDay()->addMinutes(10);
        
        $this->updateQuietly([
            'confirmation_token' => $token,
            'confirmation_token_expires_at' => $expiresAt,
        ]);
        
        return $token;
    }

    // 🔑 新增：驗證確認Token
    public function isValidConfirmationToken($token)
    {
        return $this->confirmation_token === $token &&
               $this->confirmation_token_expires_at &&
               $this->confirmation_token_expires_at > now();
    }

    // 🔑 新增：清除確認Token（確認後）
    public function clearConfirmationToken()
    {
        $this->updateQuietly([
            'confirmation_token' => null,
            'confirmation_token_expires_at' => null,
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = self::generateBookingNumber();
            }
        });

        // 只在非管理員預約時增加使用者的預約次數
        static::created(function ($booking) {
            if (!$booking->created_by_admin && $booking->user && $booking->user->userProfile) {
                $booking->user->userProfile->incrementBookings();
            }
        });

        // 只在非管理員預約時減少使用者的預約次數（取消預約時）
        static::deleted(function ($booking) {
            if (!$booking->created_by_admin && $booking->user && $booking->user->userProfile) {
                $booking->user->userProfile->decrementBookings();
            }
        });

        // 🔑 新增：LINE 通知處理
        static::created(function ($booking) {
            // 新預約建立時發送「已收到預約」通知
            if ($booking->status === self::STATUS_PENDING) {
                SendLineNotificationJob::dispatch($booking, 'booking_received');
            }
        });

        static::updated(function ($booking) {
            // 🔑 修復：當狀態從 pending 變為 approved 時發送審核通過通知並設定確認截止時間
            if ($booking->isDirty('status') && $booking->getOriginal('status') === self::STATUS_PENDING && $booking->status === self::STATUS_APPROVED) {
                // 先設定確認截止時間（使用 updateQuietly 避免遞迴）
                $deadline = $booking->booking_time->copy()->startOfDay()->addMinutes(10);
                $booking->updateQuietly(['confirmation_deadline' => $deadline]);
                
                // 再發送 LINE 通知
                SendLineNotificationJob::dispatch($booking, 'booking_approved');
                
                \Illuminate\Support\Facades\Log::info('Booking approved with LINE notification', [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'user_id' => $booking->user_id,
                    'user_has_line_id' => $booking->user ? !empty($booking->user->line_id) : false,
                    'notification_type' => 'booking_approved'
                ]);
            }
            
            // 🔑 修改：當狀態變為 cancelled 時，根據 cancelled_by 決定通知類型
            if ($booking->isDirty('status') && $booking->status === self::STATUS_CANCELLED) {
                $originalStatus = $booking->getOriginal('status');
                
                // 根據 cancelled_by 和原始狀態決定通知類型
                if ($booking->cancelled_by === 'user' && $originalStatus === self::STATUS_PENDING) {
                    // 用戶自行取消待審核預約
                    SendLineNotificationJob::dispatch($booking, 'booking_self_cancelled');
                } elseif ($booking->cancelled_by === 'admin' && $originalStatus === self::STATUS_PENDING) {
                    // 管理員拒絕待審核預約
                    SendLineNotificationJob::dispatch($booking, 'booking_rejected');
                } elseif ($booking->cancelled_by === 'admin' && $originalStatus === self::STATUS_APPROVED) {
                    // 管理員同意取消申請（原本是已預約成功的狀態）
                    SendLineNotificationJob::dispatch($booking, 'cancellation_approved');
                } elseif ($booking->cancelled_by === 'system') {
                    // 系統自動取消
                    SendLineNotificationJob::dispatch($booking, 'booking_auto_cancelled');
                } else {
                    // 其他情況（向後兼容）
                    SendLineNotificationJob::dispatch($booking, 'booking_cancelled');
                }
                
                \Illuminate\Support\Facades\Log::info('Booking cancelled with appropriate notification', [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'cancelled_by' => $booking->cancelled_by,
                    'original_status' => $originalStatus,
                    'current_status' => $booking->status
                ]);
            }
            
            // 當狀態變為 completed 時發送完成通知
            if ($booking->isDirty('status') && $booking->status === self::STATUS_COMPLETED) {
                SendLineNotificationJob::dispatch($booking, 'booking_completed');
                
                \Illuminate\Support\Facades\Log::info('Booking completed with LINE notification', [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'notification_type' => 'booking_completed'
                ]);
            }
            
            // 取消申請相關通知
            if ($booking->isDirty('cancellation_requested') && $booking->cancellation_requested) {
                SendLineNotificationJob::dispatch($booking, 'cancellation_requested');
                
                \Illuminate\Support\Facades\Log::info('Cancellation requested with LINE notification', [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'notification_type' => 'cancellation_requested'
                ]);
            }
        });
    }
}