<?php
// routes/admin.php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\BookingManagement;
use App\Livewire\Admin\AvailableTimeManagement;
use App\Livewire\Admin\DailyBookingList;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\RevenueStats;
use App\Livewire\Admin\LineNotificationSettings;

// 管理員後台路由（需要登入且為管理員）
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // 🔑 修正：使用 Livewire 組件
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    
    // 預約管理 - 修正路由名稱
    Route::get('/bookings', BookingManagement::class)->name('bookings.index');
    
    // 預約時段管理
    Route::get('/available-times', AvailableTimeManagement::class)->name('available-times.index');
    
    // 每日預約清單
    Route::get('/daily-bookings', DailyBookingList::class)->name('bookings.daily');
    
    // 使用者管理
    Route::get('/users', UserManagement::class)->name('users.index');
    
    // 🔑 營業額統計
    Route::get('/revenue', RevenueStats::class)->name('revenue.index');
    
    // 🔑 LINE 通知設定
    Route::get('/line-settings', LineNotificationSettings::class)->name('line.settings');
});