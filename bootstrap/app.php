<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')->group(base_path('routes/auth.php'));
            Route::middleware('web')->group(base_path('routes/customer.php'));
            Route::middleware(['web', 'auth', 'admin'])->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 🔧 完全移除 LogLivewireRequests 中間件，它造成 headers already sent 錯誤
        // $middleware->append(\App\Http\Middleware\LogLivewireRequests::class);
        $middleware->validateCsrfTokens(except: [
            'livewire/*',
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckAdmin::class,
            'profile.complete' => \App\Http\Middleware\CheckProfileComplete::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // 🔑 每天早上 9 點發送行前確認請求（預約日期的前一天）
        $schedule->command('booking:send-confirmation-requests')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // 🔑 每天凌晨 00:10 檢查並取消逾期未確認的預約
        $schedule->command('booking:cancel-unconfirmed')
            ->dailyAt('00:10')
            ->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
