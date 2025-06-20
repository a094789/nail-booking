<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserProfile;

class CreateMissingUserProfiles extends Command
{
    protected $signature = 'users:create-missing-profiles';
    protected $description = '為沒有 UserProfile 的使用者創建對應的記錄';

    public function handle()
    {
        $this->info('開始檢查缺少 UserProfile 的使用者...');

        $usersWithoutProfile = User::whereDoesntHave('userProfile')->get();
        
        if ($usersWithoutProfile->count() === 0) {
            $this->info('所有使用者都已有 UserProfile 記錄。');
            return;
        }

        $this->info("找到 {$usersWithoutProfile->count()} 個使用者需要創建 UserProfile。");

        $createdCount = 0;
        foreach ($usersWithoutProfile as $user) {
            try {
                UserProfile::create([
                    'user_id' => $user->id,
                    'monthly_booking_limit' => 3,
                    'monthly_bookings_count' => 0,
                    'booking_count_reset_date' => now()->startOfMonth()->addMonth(),
                ]);
                
                $this->line("為使用者 {$user->name} (ID: {$user->id}) 創建了 UserProfile");
                $createdCount++;
            } catch (\Exception $e) {
                $this->error("為使用者 {$user->name} (ID: {$user->id}) 創建 UserProfile 失敗: {$e->getMessage()}");
            }
        }

        $this->info("完成！成功創建了 {$createdCount} 個 UserProfile 記錄。");
    }
} 