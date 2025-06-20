<?php

use Illuminate\Support\Facades\Route;

// 🔧 添加測試路由
Route::get('/test-livewire', function () {
    return view('test-livewire');
})->name('test.livewire');

// 測試 LINE 通知格式的路由
Route::get('/test-line-format/{booking}', function ($bookingId) {
    $booking = \App\Models\Booking::find($bookingId);
    
    if (!$booking) {
        return response()->json(['error' => '找不到預約']);
    }
    
    $lineService = new \App\Services\LineNotificationService();
    
    // 使用反射來調用私有方法
    $reflection = new \ReflectionClass($lineService);
    $method = $reflection->getMethod('buildBookingReceivedMessage');
    $method->setAccessible(true);
    
    $message = $method->invoke($lineService, $booking);
    
    return response()->json([
        'booking_id' => $booking->id,
        'style_type' => $booking->style_type,
        'need_removal' => $booking->need_removal,
        'message_text' => $message->getText(),
        'service_file_path' => (new \ReflectionClass($lineService))->getFileName()
    ]);
})->name('test.line.format');

// 載入各個路由檔案
require __DIR__.'/auth.php';       // 認證相關路由
require __DIR__.'/customer.php';   // 客戶功能路由（包含公開路由）
require __DIR__.'/admin.php';      // 管理員功能路由