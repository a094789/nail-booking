<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;

class GenerateConfirmationTokens extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'booking:generate-confirmation-tokens';

    /**
     * The console command description.
     */
    protected $description = '為現有需要確認的預約生成確認Token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('開始為現有預約生成確認Token...');

        // 找出所有需要確認但還沒有Token的預約
        $bookings = Booking::where('status', Booking::STATUS_APPROVED)
            ->where('requires_confirmation', true)
            ->where('is_confirmed', false)
            ->whereNull('confirmation_token')
            ->where('confirmation_deadline', '>', now())
            ->get();

        $generatedCount = 0;

        foreach ($bookings as $booking) {
            // 生成Token
            $token = $booking->generateConfirmationToken();
            
            $generatedCount++;
            
            $this->line("已為預約 {$booking->booking_number} 生成Token");
            $this->line("確認連結：https://nn.wwcyu.com/booking/confirm/{$token}");
            $this->line("");
        }

        if ($generatedCount === 0) {
            $this->info('沒有需要生成Token的預約');
        } else {
            $this->info("完成！共為 {$generatedCount} 個預約生成了確認Token");
        }

        return Command::SUCCESS;
    }
}
