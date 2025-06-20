<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingConfirmationController extends Controller
{
    /**
     * 顯示預約確認頁面
     */
    public function show($token)
    {
        // 🔒 使用Token查找預約
        $booking = Booking::where('confirmation_token', $token)
            ->where('status', Booking::STATUS_APPROVED)
            ->where('requires_confirmation', true)
            ->where('is_confirmed', false)
            ->first();

        if (!$booking) {
            return view('booking-confirmation.not-found');
        }

        // 🔒 驗證Token是否有效（包含過期檢查）
        if (!$booking->isValidConfirmationToken($token)) {
            return view('booking-confirmation.expired', compact('booking'));
        }

        // 🔒 要求用戶登入
        if (!Auth::check()) {
            // 將Token保存到session中，登入後重新導向
            session(['confirmation_token' => $token]);
            return redirect()->route('customer.login')->with('message', '請先登入以確認您的預約');
        }

        // 🔒 驗證是否為預約人本人
        if ($booking->user_id !== Auth::id()) {
            return view('booking-confirmation.not-found')->with('error', '您無權確認此預約，這不是您的預約');
        }

        // 🔒 檢查確認功能是否已開放（預約日前一天00:00後）
        $confirmationOpenTime = $booking->booking_time->copy()->subDay()->startOfDay();
        if (now() < $confirmationOpenTime) {
            return view('booking-confirmation.too-early', [
                'booking' => $booking,
                'openTime' => $confirmationOpenTime
            ]);
        }

        // 檢查是否逾期
        if ($booking->isConfirmationOverdue()) {
            return view('booking-confirmation.expired', compact('booking'));
        }

        return view('booking-confirmation.confirm', compact('booking'));
    }

    /**
     * 處理預約確認
     */
    public function confirm(Request $request, $token)
    {
        // 🔒 使用Token查找預約
        $booking = Booking::where('confirmation_token', $token)
            ->where('status', Booking::STATUS_APPROVED)
            ->where('requires_confirmation', true)
            ->where('is_confirmed', false)
            ->first();

        if (!$booking) {
            return response()->json(['error' => '找不到預約或預約已確認'], 404);
        }

        // 🔒 驗證Token是否有效（包含過期檢查）
        if (!$booking->isValidConfirmationToken($token)) {
            return response()->json(['error' => '確認連結已過期或無效'], 400);
        }

        // 🔒 要求用戶登入
        if (!Auth::check()) {
            return response()->json(['error' => '請先登入'], 401);
        }

        // 🔒 驗證是否為預約人本人
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['error' => '您無權確認此預約，這不是您的預約'], 403);
        }

        // 🔒 檢查確認功能是否已開放（預約日前一天00:00後）
        $confirmationOpenTime = $booking->booking_time->copy()->subDay()->startOfDay();
        if (now() < $confirmationOpenTime) {
            return response()->json([
                'error' => '確認功能尚未開放',
                'message' => '預約確認將於 ' . $confirmationOpenTime->format('Y/m/d H:i') . ' 開放'
            ], 400);
        }

        // 檢查是否逾期
        if ($booking->isConfirmationOverdue()) {
            return response()->json(['error' => '確認時間已過期'], 400);
        }

        // 確認預約
        $booking->confirmBooking();
        
        // 🔒 清除Token（一次性使用）
        $booking->clearConfirmationToken();

        // 發送確認成功 LINE 通知
        \App\Jobs\SendLineNotificationJob::dispatch($booking, 'booking_confirmed');

        return response()->json([
            'success' => true,
            'message' => '預約確認成功！'
        ]);
    }
} 