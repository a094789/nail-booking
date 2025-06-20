<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
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
        // 確保用戶已登入
        if (!Auth::check()) {
            // 🔑 修改：改為導向首頁而不是 admin login
            return redirect()->route('home');
        }

        // 檢查用戶角色
        $user = Auth::user();
        
        if (!$user || $user->role !== 'admin') {
            abort(403, '無權限訪問此頁面');
        }

        return $next($request);
    }
}