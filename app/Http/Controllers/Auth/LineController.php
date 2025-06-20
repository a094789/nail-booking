<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class LineController extends Controller
{
    /**
     * 重新導向到 LINE 登入頁面
     */
    public function redirectToLine()
    {
        try {
            Log::info('開始 LINE 登入重定向', [
                'line_client_id' => config('services.line.client_id') ? '已設定' : '未設定',
                'line_client_secret' => config('services.line.client_secret') ? '已設定' : '未設定',
                'line_redirect' => config('services.line.redirect'),
                'current_url' => request()->fullUrl()
            ]);
            
            return Socialite::driver('line')->redirect();
        } catch (\Exception $e) {
            Log::error('LINE 重定向失敗', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('customer.login')
                ->with('error', 'LINE登入服務暫時無法使用：' . $e->getMessage());
        }
    }

    /**
     * 處理 LINE 登入回調
     */
    public function handleLineCallback()
    {
        try {
            Log::info('LINE 登入回調開始', [
                'request_params' => request()->all(),
                'has_code' => request()->has('code'),
                'has_state' => request()->has('state'),
                'has_error' => request()->has('error')
            ]);
            
            if (request()->has('error')) {
                Log::error('LINE 登入回調包含錯誤', [
                    'error' => request()->get('error'),
                    'error_description' => request()->get('error_description')
                ]);
                throw new \Exception('LINE登入被取消或失敗：' . request()->get('error_description', request()->get('error')));
            }
            
            $lineUser = Socialite::driver('line')->user();
            
            Log::info('成功獲取 LINE 用戶信息', [
                'line_id' => $lineUser->getId(),
                'line_name' => $lineUser->getName(),
                'line_email' => $lineUser->getEmail(),
                'has_avatar' => !empty($lineUser->getAvatar())
            ]);

            // 🔑 先查找現有使用者
            $user = User::where('line_id', $lineUser->getId())->first();

            if ($user) {
                // 使用者已存在，更新 LINE 相關資訊（包括頭像）
                $user->update([
                    'line_name' => $lineUser->getName() ?? 'LINE用戶',
                    'avatar_url' => $lineUser->getAvatar(), // 🔑 更新頭像
                    'email_verified_at' => now(),
                ]);
            } else {
                // 🔑 新使用者，處理 email 問題
                $email = $lineUser->getEmail();
                if (empty($email)) {
                    // 如果 LINE 沒有提供 email，創建一個唯一的假 email
                    $email = "line_{$lineUser->getId()}_" . time() . "@temp.line.local";
                }

                // 新使用者，建立帳號
                $user = User::create([
                    'line_id' => $lineUser->getId(),
                    'name' => $lineUser->getName() ?? 'LINE用戶',
                    'email' => $email, // 使用處理過的 email
                    'line_name' => $lineUser->getName() ?? 'LINE用戶',
                    'avatar_url' => $lineUser->getAvatar(), // 🔑 儲存頭像
                    'provider' => 'line',
                    'role' => 'user',
                    'password' => Hash::make('line-user-' . time()),
                    'email_verified_at' => now(),
                ]);
            }
            
            // 建立使用者 profile
            if (!$user->userProfile()->exists()) {
                UserProfile::create([
                    'user_id' => $user->id,
                    'monthly_booking_limit' => 3,
                    'monthly_bookings_count' => 0,
                    'booking_count_reset_date' => now()->startOfMonth()->addMonth(),
                ]);
            }

            // 登入使用者
            Auth::login($user);

            // 儲存 LINE 頭像
            if ($lineUser->getAvatar()) {
                session(['line_avatar' => $lineUser->getAvatar()]);
            }

            // 🎯 重導向邏輯 - 優先處理確認Token
            
            // 0. 檢查是否有待處理的確認Token
            if (session()->has('confirmation_token')) {
                $token = session()->pull('confirmation_token');
                return redirect()->route('booking.confirm', $token);
            }
            
            // 1. 如果沒同意條款，去同意條款
            if (!$user->terms_accepted) {
                return redirect()->route('terms.agreement');
            }

            // 2. 如果資料不完整，去填寫資料
            if ($this->needsProfileCompletion($user)) {
                return redirect()->route('profile.edit')
                    ->with('info', '請完成個人基本資料填寫');
            }

            // 3. 都完成了，進入 dashboard
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            Log::error('LINE Login Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => request()->all()
            ]);
            return redirect()->route('customer.login')
                ->with('error', 'LINE登入失敗：' . $e->getMessage());
        }
    }

    /**
     * 🔑 修正：檢查是否需要完成個人資料 - 配合 LINE 用戶特性
     */
    private function needsProfileCompletion($user)
    {
        $hasRealName = !empty($user->name) && $user->name !== 'LINE用戶';
        $hasPhone = !empty($user->phone);
        
        // 🔑 重要：針對 LINE 用戶的檢查邏輯
        if ($user->provider === 'line') {
            // LINE 用戶：需要真實姓名、電話，並且要有真實 email（非假 email）
            $hasRealEmail = !empty($user->email) && !str_contains($user->email, '@temp.line.local');
            $needsCompletion = !($hasRealName && $hasPhone && $hasRealEmail);
            
            // 🐛 調試日誌
            Log::info('LINE用戶資料檢查', [
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'provider' => $user->provider,
                'hasRealName' => $hasRealName,
                'hasPhone' => $hasPhone,
                'hasRealEmail' => $hasRealEmail,
                'needsCompletion' => $needsCompletion,
                'terms_accepted' => $user->terms_accepted
            ]);
            
            return $needsCompletion;
        } else {
            // 一般用戶：需要姓名、電話、email
            $hasEmail = !empty($user->email);
            $needsCompletion = !($hasRealName && $hasPhone && $hasEmail);
            
            // 🐛 調試日誌
            Log::info('一般用戶資料檢查', [
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'provider' => $user->provider,
                'hasRealName' => $hasRealName,
                'hasPhone' => $hasPhone,
                'hasEmail' => $hasEmail,
                'needsCompletion' => $needsCompletion,
                'terms_accepted' => $user->terms_accepted
            ]);
            
            return $needsCompletion;
        }
    }
}