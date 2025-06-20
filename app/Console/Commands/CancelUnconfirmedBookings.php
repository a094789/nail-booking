<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Jobs\SendLineNotificationJob;
use Carbon\Carbon;

class CancelUnconfirmedBookings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'booking:cancel-unconfirmed';

    /**
     * The console command description.
     */
    protected $description = '取消逾期未確認的預約';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('開始檢查逾期未確認的預約...');

        // 取得逾期未確認的預約
        $bookings = Booking::where('status', Booking::STATUS_APPROVED)
            ->where('requires_confirmation', true)
            ->where('is_confirmed', false)
            ->where('confirmation_deadline', '<', now())
            ->with('user')
            ->get();

        $cancelledCount = 0;

        foreach ($bookings as $booking) {
            // 取消預約
            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
                'cancellation_reason' => '逾期未確認預約，系統自動取消',
                'cancelled_at' => now(),
                'cancelled_by' => 'system'
            ]);

            // LINE 通知將由 Booking 模型的 updated 事件自動處理

            $cancelledCount++;
            
            $this->line("已取消：{$booking->customer_name} (預約時間：{$booking->booking_time->format('Y/m/d H:i')})");
        }

        $this->info("完成！共取消 {$cancelledCount} 個逾期未確認的預約");
        
        return Command::SUCCESS;
    }
} 