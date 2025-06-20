<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Jobs\SendLineNotificationJob;
use Carbon\Carbon;

class SendConfirmationRequests extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'booking:send-confirmation-requests';

    /**
     * The console command description.
     */
    protected $description = '發送行前確認請求通知';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('開始發送行前確認請求...');

        // 取得需要發送確認請求的預約
        // 條件：已確認的預約、需要確認、尚未確認、尚未發送確認提醒、確認截止時間在未來
        $bookings = Booking::where('status', Booking::STATUS_APPROVED)
            ->where('requires_confirmation', true)
            ->where('is_confirmed', false)
            ->where('confirmation_reminder_sent', false)
            ->where('confirmation_deadline', '>', now())
            ->whereDate('booking_time', '>', now()) // 預約時間在未來
            ->with('user')
            ->get();

        $sentCount = 0;

        foreach ($bookings as $booking) {
            // 檢查用戶是否有 LINE ID
            if ($booking->user && $booking->user->line_id) {
                // 🔑 生成安全的確認Token
                $token = $booking->generateConfirmationToken();
                
                // 發送確認請求通知
                SendLineNotificationJob::dispatch($booking, 'confirmation_request');
                
                // 標記已發送確認提醒
                $booking->update(['confirmation_reminder_sent' => true]);
                
                $sentCount++;
                
                $this->line("已發送確認請求給：{$booking->customer_name} (預約時間：{$booking->booking_time->format('Y/m/d H:i')})");
                $this->line("確認連結：https://nn.wwcyu.com/booking/confirm/{$token}");
            } else {
                $this->warn("跳過：{$booking->customer_name} (沒有 LINE ID)");
            }
        }

        $this->info("完成！共發送 {$sentCount} 則確認請求");
        
        return Command::SUCCESS;
    }
} 