<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Services\LineNotificationService;
use Illuminate\Console\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputOption;

class TestLineNotification extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'line:test {booking_id? : 預約ID (可選)} {--list : 顯示可用的預約列表} {--booking-approved : 測試預約審核通過通知} {--booking-self-cancelled : 測試使用者自行取消預約通知}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '測試 LINE 通知功能';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('測試 LINE 通知功能...');
        
        try {
            // 如果指定了 --list 參數，顯示可用預約列表
            if ($this->option('list')) {
                return $this->showBookingList();
            }
            
            // 🔧 新增：測試預約審核通過通知
            if ($this->option('booking-approved')) {
                $this->testBookingApproved();
                return;
            }
            
            // 🔧 新增：測試使用者自行取消預約通知
            if ($this->option('booking-self-cancelled')) {
                $this->testBookingSelfCancelled();
                return;
            }
            
            // 🔧 修復：使用實際的預約測試而不是 sendTestMessage
            $bookingId = $this->argument('booking_id');
            
            if ($bookingId) {
                // 測試指定的預約ID
                $booking = \App\Models\Booking::with('user')->find($bookingId);
                
                if (!$booking) {
                    $this->error("找不到預約ID: {$bookingId}");
                    $this->info('使用 --list 參數查看可用的預約列表');
                    return 1;
                }
                
                if (!$booking->user) {
                    $this->error('預約沒有關聯的用戶');
                    return 1;
                }
                
                if (!$booking->user->line_id) {
                    $this->error('用戶沒有 LINE ID，無法發送通知');
                    $this->info('用戶需要先綁定 LINE 帳號才能接收通知');
                    return 1;
                }
                
                return $this->testBookingNotification($booking);
                
            } else {
                // 顯示使用說明
                $this->info('🔔 LINE 通知測試工具');
                $this->info('');
                $this->info('使用方法：');
                $this->info('  測試指定預約：php artisan line:test [預約ID]');
                $this->info('  查看預約列表：php artisan line:test --list');
                $this->info('  測試審核通知：php artisan line:test --booking-approved');
                $this->info('  測試使用者自行取消：php artisan line:test --booking-self-cancelled');
                $this->info('');
                $this->info('範例：');
                $this->info('  php artisan line:test 42                    # 測試預約ID 42');
                $this->info('  php artisan line:test --list                # 查看可用預約');
                $this->info('  php artisan line:test --booking-approved    # 測試審核通過通知');
                $this->info('  php artisan line:test --booking-self-cancelled    # 測試使用者自行取消預約通知');
                
                return 0;
            }
            
        } catch (\Exception $e) {
            $this->error('發生錯誤: ' . $e->getMessage());
            $this->error('詳細錯誤: ' . $e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Get the console command options.
     */
    protected function getOptions()
    {
        return [
            ['booking-approved', null, InputOption::VALUE_NONE, '測試預約審核通過通知'],
            ['booking-self-cancelled', null, InputOption::VALUE_NONE, '測試使用者自行取消預約通知'],
        ];
    }

    /**
     * 顯示可用的預約列表
     */
    private function showBookingList()
    {
        $this->info('📋 可用的預約列表：');
        $this->newLine();

        // 顯示有 LINE ID 的預約
        $bookingsWithLine = Booking::with('user')
            ->whereHas('user', function ($query) {
                $query->whereNotNull('line_id');
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        if ($bookingsWithLine->count() > 0) {
            $this->info('✅ 有 LINE ID 的預約 (推薦測試):');
            $headers = ['ID', '預約單號', '客戶姓名', '狀態', 'LINE ID'];
            $rows = [];

            foreach ($bookingsWithLine as $booking) {
                $rows[] = [
                    $booking->id,
                    $booking->booking_number,
                    $booking->customer_name,
                    $booking->status,
                    $booking->user->line_id ? '✅ 有' : '❌ 無'
                ];
            }

            $this->table($headers, $rows);
        }

        $this->newLine();

        // 顯示沒有 LINE ID 的預約
        $bookingsWithoutLine = Booking::with('user')
            ->whereHas('user', function ($query) {
                $query->whereNull('line_id');
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        if ($bookingsWithoutLine->count() > 0) {
            $this->info('⚠️ 沒有 LINE ID 的預約 (無法測試通知):');
            $headers = ['ID', '預約單號', '客戶姓名', '狀態'];
            $rows = [];

            foreach ($bookingsWithoutLine as $booking) {
                $rows[] = [
                    $booking->id,
                    $booking->booking_number,
                    $booking->customer_name,
                    $booking->status
                ];
            }

            $this->table($headers, $rows);
        }

        $this->newLine();
        $this->info('💡 使用方法:');
        $this->info('測試指定預約: php artisan line:test [預約ID]');
        $this->info('測試預約審核通過: php artisan line:test --booking-approved');
        $this->info('測試使用者自行取消: php artisan line:test --booking-self-cancelled');
        $this->info('自動選擇預約: php artisan line:test');
        
        return 0;
    }

    /**
     * 自動尋找可測試的預約
     */
    private function findTestableBooking()
    {
        // 優先尋找待審核的預約
        $booking = Booking::with('user')
            ->where('status', 'pending')
            ->whereHas('user', function ($query) {
                $query->whereNotNull('line_id');
            })
            ->first();

        if ($booking) {
            return $booking;
        }

        // 如果沒有待審核的，尋找任何有 LINE ID 的預約
        return Booking::with('user')
            ->whereHas('user', function ($query) {
                $query->whereNotNull('line_id');
            })
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * 測試預約通知
     */
    private function testBookingNotification($booking)
    {
        $this->info("🧪 測試預約通知");
        $this->info("預約ID：{$booking->id}");
        $this->info("預約單號：{$booking->booking_number}");
        $this->info("客戶姓名：{$booking->customer_name}");
        $this->info("LINE ID：{$booking->user->line_id}");
        $this->info("預約狀態：{$booking->status}");
        $this->newLine();

        $lineService = app(LineNotificationService::class);

        try {
            // 測試預約批准通知
            $this->info('🔔 測試預約批准通知...');
            $result = $lineService->sendBookingApproved($booking);
            
            if ($result) {
                $this->info('✅ 預約批准通知發送成功');
            } else {
                $this->error('❌ 預約批准通知發送失敗');
            }

            $this->newLine();

            // 測試 Queue Job
            $this->info('⚡ 測試 Queue Job 派發...');
            \App\Jobs\SendLineNotificationJob::dispatch($booking, 'booking_approved');
            $this->info('✅ Queue Job 已派發到佇列');

            $this->newLine();

            // 測試其他通知類型
            $this->info('🔄 測試其他通知類型...');
            
            $notifications = [
                'booking_received' => '預約已收到',
                'booking_completed' => '預約已完成',
                'booking_cancelled' => '預約已取消'
            ];

            foreach ($notifications as $type => $description) {
                $this->info("測試 {$description} 通知...");
                \App\Jobs\SendLineNotificationJob::dispatch($booking, $type);
                $this->info("✅ {$description} 通知已派發");
            }

        } catch (\Exception $e) {
            $this->error('❌ 發送通知時發生錯誤：' . $e->getMessage());
            $this->error('錯誤詳情：' . $e->getTraceAsString());
            return 1;
        }

        $this->newLine();
        $this->info('🎉 測試完成！');
        $this->info('💡 提示：檢查用戶的 LINE 是否收到通知訊息');
        
        return 0;
    }

    /**
     * 測試預約審核通過通知
     */
    private function testBookingApproved()
    {
        $this->info('🧪 測試預約審核通過通知...');
        
        // 尋找一個待審核的預約來測試
        $booking = Booking::where('status', 'pending')
            ->whereHas('user', function($query) {
                $query->whereNotNull('line_id');
            })
            ->first();
            
        if (!$booking) {
            $this->error('❌ 找不到合適的待審核預約來測試');
            return;
        }
        
        $this->info("📋 使用預約: {$booking->booking_number} (客戶: {$booking->customer_name})");
        
        $lineService = new LineNotificationService();
        
        try {
            $result = $lineService->sendBookingApproved($booking);
            
            if ($result) {
                $this->info('✅ 預約審核通過通知發送成功！');
                $this->info('📱 請檢查 LINE 是否收到以下格式的通知：');
                $this->line('');
                $this->line('✅ 預約已審核通過');
                $this->line('');
                $this->line("親愛的 {$booking->customer_name}，");
                $this->line('您的預約已審核通過');
                $this->line('');
                $this->line("預約單號：{$booking->booking_number}");
                $this->line('');
                $this->line('如有需要，歡迎您與我們聯繫。');
            } else {
                $this->error('❌ 預約審核通過通知發送失敗');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ 發送失敗: ' . $e->getMessage());
        }
    }

    /**
     * 測試使用者自行取消預約通知
     */
    private function testBookingSelfCancelled()
    {
        $this->info('🧪 測試使用者自行取消預約通知...');
        
        // 尋找一個已取消的預約來測試
        $booking = Booking::where('status', 'cancelled')
            ->whereHas('user', function($query) {
                $query->whereNotNull('line_contact_id');
            })
            ->first();
            
        if (!$booking) {
            $this->error('❌ 找不到合適的已取消預約來測試');
            return;
        }
        
        $this->info("📋 使用預約: {$booking->booking_number} (客戶: {$booking->customer_name})");
        
        $lineService = new LineNotificationService();
        
        try {
            $result = $lineService->sendBookingSelfCancelled($booking);
            
            if ($result) {
                $this->info('✅ 使用者自行取消通知發送成功！');
                $this->info('📱 請檢查 LINE 是否收到以下格式的通知：');
                $this->line('');
                $this->line('❌ 預約已自行取消');
                $this->line('');
                $this->line("親愛的 {$booking->customer_name}，");
                $this->line('您已自行取消預約');
                $this->line('');
                $this->line("預約單號：{$booking->booking_number}");
                $this->line('');
                $this->line('如有需要，歡迎您重新預約。');
            } else {
                $this->error('❌ 使用者自行取消通知發送失敗');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ 發送失敗: ' . $e->getMessage());
        }
    }
} 