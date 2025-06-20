<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Jobs\SendLineNotificationJob;
use Carbon\Carbon;

class SendBookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'booking:send-reminders';

    /**
     * The console command description.
     */
    protected $description = '發送明日預約提醒通知';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('開始發送預約提醒通知...');

        // 取得明天的預約（狀態為 approved）
        $tomorrow = Carbon::tomorrow();
        $bookings = Booking::where('status', 'approved')
            ->whereDate('booking_time', $tomorrow)
            ->with('user')
            ->get();

        $sentCount = 0;

        foreach ($bookings as $booking) {
            // 檢查用戶是否有 LINE ID
            if ($booking->user && $booking->user->line_id) {
                // 發送提醒通知
                SendLineNotificationJob::dispatch($booking, 'reminder');
                $sentCount++;
                
                $this->line("已發送提醒給：{$booking->customer_name} (預約時間：{$booking->booking_time->format('Y/m/d H:i')})");
            } else {
                $this->warn("跳過：{$booking->customer_name} (沒有 LINE ID)");
            }
        }

        $this->info("完成！共發送 {$sentCount} 則提醒通知");
        
        return Command::SUCCESS;
    }
} 