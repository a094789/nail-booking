<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'line_id',
        'line_name',
        'avatar_url', // 🔑 新增：使用者頭像 URL
        'line_contact_id', // 🔑 新增：使用者可編輯的 LINE 聯繫 ID
        'provider',
        'role', // 🔑 新增角色欄位
        'phone',
        'is_active',
        'terms_accepted',
        'terms_accepted_at',
        'last_name_update',
        'last_phone_update',
        'last_email_update',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $attributes = [
        'provider' => 'email',
        'is_active' => true,
        'terms_accepted' => false,
        'role' => 'user', // 🔑 預設為一般使用者
    ];

    // 🔑 角色常數
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'terms_accepted' => 'boolean',
            'is_active' => 'boolean',
            'terms_accepted_at' => 'datetime',
            'last_name_update' => 'datetime',
            'last_phone_update' => 'datetime',
            'last_email_update' => 'datetime',
        ];
    }

    // 關聯
    public function userProfile()
    {
        return $this->hasOne(\App\Models\UserProfile::class);
    }

    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    // 🔑 角色檢查方法
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    // 現有的輔助方法...
    public function canEditName(): bool
    {
        return !$this->last_name_update || $this->last_name_update->diffInMonths(now()) >= 3;
    }

    public function canEditPhone(): bool
    {
        return !$this->last_phone_update || $this->last_phone_update->diffInMonths(now()) >= 3;
    }

    public function canEditEmail(): bool
    {
        return !$this->last_email_update || $this->last_email_update->diffInMonths(now()) >= 3;
    }

    public function canBookThisMonth(): bool
    {
        if (!$this->userProfile) {
            return false;
        }

        return $this->userProfile->monthly_bookings_count < $this->userProfile->monthly_booking_limit;
    }

    // 🔑 新增：頭像相關方法
    public function getAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function hasAvatar(): bool
    {
        return !empty($this->avatar_url);
    }

    public function getInitials(): string
    {
        return substr($this->name, 0, 1);
    }

    protected static function boot()
    {
        parent::boot();

        // 🔑 修正：只在用戶創建時創建 UserProfile，避免重複創建
        static::created(function ($user) {
            // 檢查是否已存在 UserProfile
            if (!$user->userProfile()->exists()) {
                \App\Models\UserProfile::create([
                    'user_id' => $user->id,
                    'monthly_booking_limit' => 3, // 🔑 明確設定預設值
                    'monthly_bookings_count' => 0,
                    'booking_count_reset_date' => now()->startOfMonth()->addMonth(),
                ]);
            }
        });
    }
}
