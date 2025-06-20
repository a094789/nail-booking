<?php

use App\Livewire\Agreement;
use App\Livewire\LineJoin;
use App\Livewire\Customer\Dashboard;
use App\Livewire\Customer\BookingCreate;    
use App\Livewire\Customer\BookingIndex;
use App\Livewire\PrivacyPolicy;
use App\Livewire\TermsOfUse;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 🔑 公開頁面（無需登入）
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 🔑 預約頁面 - 無需登入即可查看（公開）
Route::get('/booking/create', BookingCreate::class)->name('booking.create');

// 🔑 預約確認頁面 - 無需登入（透過 token 驗證）
Route::get('/booking/confirm/{token}', [\App\Http\Controllers\BookingConfirmationController::class, 'show'])->name('booking.confirm');
Route::post('/booking/confirm/{token}', [\App\Http\Controllers\BookingConfirmationController::class, 'confirm'])->name('booking.confirm.submit');

// 🔑 隱私權政策頁面 - 公開頁面（供 LINE Developers 使用）
Route::get('/privacy-policy', PrivacyPolicy::class)->name('privacy.policy');

// 🔑 使用條款頁面 - 公開頁面（供 LINE Developers 使用）
Route::get('/terms-of-use', TermsOfUse::class)->name('terms.of.use');

// 🔑 條款同意頁面 - 使用你現有的 Livewire 組件
Route::middleware(['auth'])->get('/terms/agreement', Agreement::class)->name('terms.agreement');

// 🔑 LINE 加入頁面 - 可選身份驗證（未登入用戶也可以訪問）
Route::get('/line/join', LineJoin::class)->name('line.join');

// 🔑 處理條款同意 - 新增這個路由
Route::middleware(['auth'])->post('/terms/accept', function() {
    $user = Auth::user();
    
    // 直接更新資料庫
    \App\Models\User::where('id', $user->id)->update([
        'terms_accepted_at' => now(),
        'terms_accepted' => true
    ]);
    
    // 檢查個人資料是否完整
    $hasRealName = !empty($user->name) && $user->name !== 'LINE用戶';
    $hasPhone = !empty($user->phone);
    
    if ($user->provider === 'line') {
        // LINE 用戶：需要真實姓名、電話，並且要有真實 email（非假 email）
        $hasRealEmail = !empty($user->email) && !str_contains($user->email, '@temp.line.local');
        $needsProfile = !($hasRealName && $hasPhone && $hasRealEmail);
    } else {
        // 一般用戶：需要姓名、電話、email
        $hasEmail = !empty($user->email);
        $needsProfile = !($hasRealName && $hasPhone && $hasEmail);
    }
    
    if ($needsProfile) {
        return response()->json([
            'success' => true,
            'redirect' => route('profile.edit')
        ]);
    }
    
    return response()->json([
        'success' => true,
        'redirect' => route('dashboard')
    ]);
})->name('terms.accept');

// 🔑 需要登入但不需要其他檢查的路由群組
Route::middleware(['auth'])->group(function () {
    // 🚨 個人資料頁面 - 移除所有其他中間件！
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
});

// 🔑 需要登入、條款同意且需要完整個人資料的路由群組
Route::middleware(['auth', 'profile.complete'])->group(function () {
    // 客戶儀表板
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    // 預約管理功能
    Route::get('/booking', BookingIndex::class)->name('booking.index');
    
    // 登出
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});