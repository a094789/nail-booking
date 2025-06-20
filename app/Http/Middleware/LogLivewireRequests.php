<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class LogLivewireRequests
{
    public function handle(Request $request, Closure $next)
    {
        // 只處理 Livewire 相關的請求
        if ($request->is('livewire/*')) {
            // 記錄請求詳情
            Log::info('Livewire Request Details', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'headers' => $request->headers->all(),
                'has_csrf_token' => $request->hasHeader('X-CSRF-TOKEN'),
                'csrf_token' => $request->header('X-CSRF-TOKEN'),
                'is_livewire' => $request->hasHeader('X-Livewire'),
                'content_type' => $request->header('Content-Type'),
                'body_size' => strlen($request->getContent()),
            ]);
            
            // 🔧 只記錄錯誤的 GET 請求，但不阻止它們
            if ($request->is('livewire/update') && $request->method() === 'GET') {
                Log::warning('GET request to livewire/update detected', [
                    'url' => $request->fullUrl(),
                    'referer' => $request->header('Referer'),
                    'user_agent' => $request->header('User-Agent'),
                ]);
                
                // 不返回 JSON 響應，讓 Laravel 正常處理路由錯誤
            }
        }

        return $next($request);
    }
} 