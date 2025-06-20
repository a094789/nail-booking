<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearAllCache extends Command
{
    protected $signature = 'cache:clear-all';
    protected $description = '清除所有快取包括 OPCache';

    public function handle()
    {
        $this->info('開始清除所有快取...');

        // 清除 Laravel 快取
        Artisan::call('cache:clear');
        $this->info('✓ Laravel 快取已清除');

        Artisan::call('config:clear');
        $this->info('✓ 設定快取已清除');

        Artisan::call('route:clear');
        $this->info('✓ 路由快取已清除');

        Artisan::call('view:clear');
        $this->info('✓ 視圖快取已清除');

        // 清除 Queue
        try {
            Artisan::call('queue:clear');
            $this->info('✓ Queue 已清除');
        } catch (\Exception $e) {
            $this->warn('⚠ Queue 清除失敗: ' . $e->getMessage());
        }

        // 重新載入 Composer autoload
        $this->info('重新載入 Composer autoload...');
        exec('composer dump-autoload', $output, $returnCode);
        if ($returnCode === 0) {
            $this->info('✓ Composer autoload 已重新載入');
        } else {
            $this->warn('⚠ Composer autoload 重新載入失敗');
        }

        // 清除 OPCache（如果可用）
        if (function_exists('opcache_reset')) {
            opcache_reset();
            $this->info('✓ OPCache 已清除');
        } else {
            $this->warn('⚠ OPCache 不可用或未啟用');
        }

        $this->info('');
        $this->info('🎉 所有快取已清除完成！');
        $this->info('');
        $this->warn('⚠ 建議重啟 Web 服務器以確保所有變更生效');
        
        return 0;
    }
} 