<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\LineController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;

// Laravel Breeze 基本功能（管理員登入）
Route::middleware('guest')->group(function () {
    Volt::route('/admin/login', 'pages.auth.login')->name('admin.login');
    
    // 🔒 暫時隱藏註冊和密碼重設功能 - 用戶只能透過 LINE 登入
    // 如果 LINE SSO 出問題，可以取消註解以下路由
    // Volt::route('/admin/register', 'pages.auth.register')->name('register');
    // Volt::route('/admin/forgot-password', 'pages.auth.forgot-password')->name('password.request');
    // Volt::route('/admin/reset-password/{token}', 'pages.auth.reset-password')->name('password.reset');
});

// 🔑 修改：login 路由重定向到首頁
Route::get('/login', function() {
    return redirect()->route('home');
})->name('login');

// 🔑 客戶 LINE 登入頁面 - 直接處理，不使用 guest 中間件
Route::get('/customer/login', function () {
    // 如果已經登入，檢查應該去哪裡
    if (Auth::check()) {
        $user = Auth::user();
        
        // 🐛 調試日誌
        \Illuminate\Support\Facades\Log::info('/customer/login 路由被訪問', [
            'user_id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'provider' => $user->provider,
            'terms_accepted' => $user->terms_accepted,
            'current_url' => request()->fullUrl(),
            'referer' => request()->header('referer')
        ]);
        
        // 如果沒同意條款，去條款頁面
        if (!$user->terms_accepted) {
            \Illuminate\Support\Facades\Log::info('用戶未同意條款，重定向到條款頁面');
            return redirect()->route('terms.agreement');
        }
        
        // 🔑 檢查個人資料是否完整（根據用戶類型）
        $hasRealName = !empty($user->name) && $user->name !== 'LINE用戶';
        $hasPhone = !empty($user->phone);
        
        if ($user->provider === 'line') {
            // LINE 用戶：需要真實姓名、電話，並且要有真實 email（非假 email）
            $hasRealEmail = !empty($user->email) && !str_contains($user->email, '@temp.line.local');
            
            \Illuminate\Support\Facades\Log::info('LINE用戶資料檢查 (auth.php)', [
                'hasRealName' => $hasRealName,
                'hasPhone' => $hasPhone,
                'hasRealEmail' => $hasRealEmail,
                'email_is_fake' => str_contains($user->email, '@temp.line.local')
            ]);
            
            if (!($hasRealName && $hasPhone && $hasRealEmail)) {
                \Illuminate\Support\Facades\Log::info('LINE用戶資料不完整，重定向到個人資料編輯頁面');
                return redirect()->route('profile.edit');
            }
        } else {
            // 一般用戶：需要姓名、電話、email
            $hasEmail = !empty($user->email);
            
            \Illuminate\Support\Facades\Log::info('一般用戶資料檢查 (auth.php)', [
                'hasRealName' => $hasRealName,
                'hasPhone' => $hasPhone,
                'hasEmail' => $hasEmail
            ]);
            
            if (!($hasRealName && $hasPhone && $hasEmail)) {
                \Illuminate\Support\Facades\Log::info('一般用戶資料不完整，重定向到個人資料編輯頁面');
                return redirect()->route('profile.edit');
            }
        }
        
        // 🔑 檢查是否有待確認的Token
        if (session('confirmation_token')) {
            $token = session('confirmation_token');
            session()->forget('confirmation_token');
            \Illuminate\Support\Facades\Log::info('用戶登入後重定向到確認頁面', ['token' => $token]);
            return redirect()->route('booking.confirm', ['token' => $token]);
        }
        
        // 都完成了，去 dashboard
        \Illuminate\Support\Facades\Log::info('用戶資料完整，重定向到 dashboard');
        return redirect()->route('dashboard');
    }
    
    // 未登入，顯示登入頁面
    \Illuminate\Support\Facades\Log::info('用戶未登入，顯示登入頁面');
    return view('livewire.pages.auth.customer-login');
})->name('customer.login');

// 🔑 客戶登入後的重定向邏輯 - 單獨處理
Route::get('/customer/redirect', function () {
    // 如果已經登入，檢查應該去哪裡
    if (Auth::check()) {
        $user = Auth::user();
        
        // 如果沒同意條款，去條款頁面
        if (!$user->terms_accepted) {
            return redirect()->route('terms.agreement');
        }
        
        // 🔑 檢查個人資料是否完整（根據用戶類型）
        $hasRealName = !empty($user->name) && $user->name !== 'LINE用戶';
        $hasPhone = !empty($user->phone);
        
        if ($user->provider === 'line') {
            // LINE 用戶：需要真實姓名、電話，並且要有真實 email（非假 email）
            $hasRealEmail = !empty($user->email) && !str_contains($user->email, '@temp.line.local');
            
            if (!($hasRealName && $hasPhone && $hasRealEmail)) {
                return redirect()->route('profile.edit');
            }
        } else {
            // 一般用戶：需要姓名、電話、email
            $hasEmail = !empty($user->email);
            
            if (!($hasRealName && $hasPhone && $hasEmail)) {
                return redirect()->route('profile.edit');
            }
        }
        
        // 🔑 檢查是否有待確認的Token
        if (session('confirmation_token')) {
            $token = session('confirmation_token');
            session()->forget('confirmation_token');
            return redirect()->route('booking.confirm', ['token' => $token]);
        }
        
        // 都完成了，去 dashboard
        return redirect()->route('dashboard');
    }
    
    // 未登入，導向登入頁面
    return redirect()->route('customer.login');
})->name('customer.redirect');

// 已認證用戶功能
Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')->name('password.confirm');
});

// LINE 登入路由
Route::get('/auth/line', [LineController::class, 'redirectToLine'])->name('line.login');
Route::get('/auth/line/callback', [LineController::class, 'handleLineCallback'])->name('line.callback');