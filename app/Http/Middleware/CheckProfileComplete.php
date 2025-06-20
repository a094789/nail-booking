<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // 1. 首先檢查是否已同意使用條款
            if (!$user->terms_accepted) {
                // 如果當前路由不是條款相關頁面，則重定向到條款頁面
                if (!$request->routeIs('terms.agreement')) {
                    return redirect()->route('terms.agreement')
                        ->with('info', '請先閱讀並同意使用條款');
                }
            }
            // 2. 然後檢查基本資料是否完整
            elseif ($this->isProfileIncomplete($user)) {
                // 如果當前路由不是編輯個人資料頁面，則重定向到編輯頁面
                if (!$request->routeIs('profile.edit') && !$request->routeIs('profile.update')) {
                    return redirect()->route('profile.edit')
                        ->with('info', '請先完成基本資料填寫');
                }
            }
        }
        
        return $next($request);
    }

    /**
     * 檢查個人資料是否不完整
     */
    private function isProfileIncomplete($user)
    {
        $hasRealName = !empty($user->name) && $user->name !== 'LINE用戶';
        $hasPhone = !empty($user->phone);
        
        // 🔑 針對 LINE 用戶的檢查邏輯
        if ($user->provider === 'line') {
            // LINE 用戶：需要真實姓名、電話，並且要有真實 email（非假 email）
            $hasRealEmail = !empty($user->email) && !str_contains($user->email, '@temp.line.local');
            return !($hasRealName && $hasPhone && $hasRealEmail);
        } else {
            // 一般用戶：需要姓名、電話、email
            $hasEmail = !empty($user->email);
            return !($hasRealName && $hasPhone && $hasEmail);
        }
    }
}