# LINE 通知系統完整說明書

## 📋 目錄
1. [系統概述](#系統概述)
2. [設定與安裝](#設定與安裝)
3. [通知類型與觸發規則](#通知類型與觸發規則)
4. [導航欄整合](#導航欄整合)
5. [官方帳號設定](#官方帳號設定)
6. [訊息內容修改](#訊息內容修改)
7. [測試與監控](#測試與監控)
8. [故障排除](#故障排除)
9. [維護指南](#維護指南)
10. [技術架構](#技術架構)

---

## 📱 系統概述

### 功能目標
美甲預約系統的 LINE 通知功能，提供完整預約生命週期的自動化通知服務，包含 8 種通知類型。

### 技術架構
```
用戶操作/系統事件 → Booking Model Events → SendLineNotificationJob → Queue → LineNotificationService → LINE API → 用戶接收通知
```

### 核心特色
- ✅ **8 種通知類型**：涵蓋預約完整流程
- ✅ **自動觸發**：基於 Model Events 自動發送
- ✅ **非同步處理**：使用 Queue 系統避免阻塞
- ✅ **錯誤處理**：完整的日誌記錄和錯誤恢復
- ✅ **重複發送防護**：避免通知重複發送
- ✅ **導航整合**：多處 LINE 加好友入口
- ✅ **通知開關**：管理員可控制通知發送

---

## ⚙️ 設定與安裝

### 1. 安裝 LINE Bot SDK
```bash
composer require linecorp/line-bot-sdk
```

### 2. 環境變數設定
在 `.env` 檔案中添加：
```env
# LINE Login API
LINE_CLIENT_ID=your_channel_id
LINE_CLIENT_SECRET=your_channel_secret
LINE_REDIRECT_URI=https://your-domain.com/auth/line/callback

# LINE Messaging API
LINE_MESSAGING_CHANNEL_ACCESS_TOKEN=your_messaging_access_token
LINE_MESSAGING_CHANNEL_SECRET=your_messaging_channel_secret

# Queue 設定
QUEUE_CONNECTION=database

# 時區設定
APP_TIMEZONE=Asia/Taipei
```

### 3. 服務設定檔
確認 `config/services.php` 中的設定：
```php
'line' => [
    'client_id' => env('LINE_CLIENT_ID'),
    'client_secret' => env('LINE_CLIENT_SECRET'),
    'redirect' => env('LINE_REDIRECT_URI'),
    'channel_access_token' => env('LINE_MESSAGING_CHANNEL_ACCESS_TOKEN'),
    'channel_secret' => env('LINE_MESSAGING_CHANNEL_SECRET'),
],
```

### 4. 通知開關設定
創建 `config/line.php`：
```php
<?php

return [
    'notification_enabled' => env('LINE_NOTIFICATION_ENABLED', true),
];
```

### 5. 排程設定
在 `bootstrap/app.php` 中：
```php
->withSchedule(function (Schedule $schedule) {
    // 每天早上 9:00 發送行前確認請求
    $schedule->command('booking:send-confirmation-requests')
        ->dailyAt('09:00')
        ->withoutOverlapping();

    // 每天凌晨 00:10 取消逾期未確認預約
    $schedule->command('booking:cancel-unconfirmed')
        ->dailyAt('00:10')
        ->withoutOverlapping();
})
```

### 6. Queue 系統設定

#### 啟動 Queue Worker
```bash
# 手動啟動
php artisan queue:work

# 使用 Supervisor 自動管理（推薦）
sudo supervisorctl start laravel-worker
```

#### Supervisor 設定範例
創建 `/etc/supervisor/conf.d/laravel-worker.conf`：
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/nail-booking/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/nail-booking/storage/logs/worker.log
```

---

## 🔔 通知類型與觸發規則

### 即時狀態變更通知

#### 1. 📋 預約已收到 (`booking_received`)
**觸發位置**：`Booking::created()` Event
**觸發條件**：新預約建立且狀態為 `pending`
**發送時機**：立即發送
**訊息範例**：
```
📋 預約已收到

親愛的 {客戶姓名}，
您的預約已收到，待管理員審核

預約單號：{預約單號}
預約日期：{預約日期}
預約時間：{預約時間}
服務項目：{服務項目}
卸甲服務：{是否需要}

我們會盡快審核您的預約，請耐心等候。
```

#### 2. ✅ 預約審核通過 (`booking_approved`)
**觸發位置**：`Booking::updated()` Event
**觸發條件**：狀態從 `pending` 變為 `approved`
**發送時機**：立即發送
**額外動作**：自動設定行前確認截止時間

#### 3. ❌ 預約審核未通過 (`booking_rejected`)
**觸發位置**：`Booking::updated()` Event
**觸發條件**：狀態從 `pending` 變為 `cancelled`

#### 4. 🚫 預約已取消 (`booking_cancelled`)
**觸發位置**：`Booking::updated()` Event
**觸發條件**：狀態變為 `cancelled`
**智能判斷**：根據 `cancelled_by` 欄位決定通知類型

#### 5. 🎉 服務完成 (`booking_completed`)
**觸發位置**：`Booking::updated()` Event
**觸發條件**：狀態變為 `completed`

#### 6. 📝 取消申請已送出 (`cancellation_requested`)
**觸發位置**：`Booking::updated()` Event
**觸發條件**：`cancellation_requested` 設為 `true`

### 定時排程通知

#### 7. 🔔 行前確認請求 (`confirmation_request`)
**執行時間**：每天早上 9:00
**指令**：`php artisan booking:send-confirmation-requests`
**觸發條件**：
- 預約狀態為 `approved`
- `requires_confirmation = true`
- `is_confirmed = false`
- `confirmation_reminder_sent = false`
- 確認截止時間在未來

#### 8. ⏰ 系統自動取消 (`booking_auto_cancelled`)
**執行時間**：每天凌晨 00:10
**指令**：`php artisan booking:cancel-unconfirmed`
**觸發條件**：
- 預約狀態為 `approved`
- `requires_confirmation = true`
- `is_confirmed = false`
- 確認截止時間已過

---

## 🧭 導航欄整合

### 已實現的 LINE 加好友入口

#### 1. 桌面版（已登入用戶）
- **位置**: 導航欄右側，用戶頭像旁邊
- **樣式**: 綠色按鈕，帶 LINE 圖示
- **文字**: "加入 LINE"（在較大螢幕顯示）

#### 2. 桌面版下拉選單（已登入用戶）
- **位置**: 用戶頭像下拉選單中
- **樣式**: 選單項目，綠色 hover 效果
- **文字**: "加入官方 LINE"

#### 3. 桌面版（未登入用戶）
- **位置**: 導航欄右側，登入按鈕旁邊
- **樣式**: 綠色按鈕，帶 LINE 圖示

#### 4. 手機版選單（已登入/未登入用戶）
- **位置**: 側邊選單的個人設定區域
- **樣式**: 選單項目，綠色 hover 效果

#### 5. LINE 通知設定頁面
- **位置**: 管理後台 → 系統管理 → LINE 通知設定
- **功能**: 管理員可開啟/關閉 LINE 通知功能
- **路由**: `/admin/line-settings`

### 技術實現

#### 使用的 LINE 圖示
```html
<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
    <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755..."/>
</svg>
```

#### 連結格式
```html
<a href="https://line.me/R/ti/p/@your_line_official_id" target="_blank">
```

---

## 🏢 官方帳號設定

### 1. 獲取官方帳號資訊

#### 在 LINE Official Account Manager 中：
1. 登入 [LINE Official Account Manager](https://manager.line.biz/)
2. 選擇您的官方帳號
3. 前往「設定」→「帳號設定」

#### 需要取得的資訊：
- **官方帳號 ID**：例如 `@abc1234`
- **QR Code URL**：在「推廣工具」中可找到
- **加好友連結**：格式為 `https://line.me/R/ti/p/@your_id`

### 2. 更新系統設定

#### A. 編輯 `app/Livewire/LineJoin.php` 檔案：
```php
public function mount()
{
    $this->user = Auth::user();
    
    // 🔑 請更新以下資訊為您的實際官方帳號資訊
    $this->lineOfficialId = '@your_actual_line_id';           // 替換為實際 ID
    $this->lineQrCodeUrl = 'https://qr-official.line.me/gs/M_your_qr_code'; // 替換為實際 QR Code URL
    $this->lineAddUrl = 'https://line.me/R/ti/p/@your_actual_line_id';       // 替換為實際加好友連結
}
```

#### B. 快速替換所有連結：
```bash
# 使用 sed 指令一次性替換所有連結
sed -i 's/@your_line_official_id/@your_actual_line_id/g' resources/views/layouts/nav.blade.php
sed -i 's/@your_line_official_id/@your_actual_line_id/g' app/Livewire/LineJoin.php
```

### 3. 更新 QR Code 顯示（可選）

如果您想顯示實際的 QR Code 圖片：

1. **下載 QR Code 圖片**：
   - 從 LINE Official Account Manager 下載
   - 儲存到 `public/images/line-qr-code.png`

2. **更新視圖檔案** `resources/views/livewire/line-join.blade.php`：
```html
<div class="w-48 h-48 bg-white rounded-2xl shadow-lg mx-auto mb-4 flex items-center justify-center">
    <img src="{{ asset('images/line-qr-code.png') }}" 
         alt="LINE QR Code" 
         class="w-44 h-44 rounded-xl">
</div>
```

---

## ✏️ 訊息內容修改

### 修改位置
**檔案**：`app/Services/LineNotificationService.php`
**方法**：私有方法 `build{NotificationType}Message()`

### 訊息建構方法對照表

| 通知類型 | 方法名稱 | 約略行數 |
|---------|---------|---------|
| 📋 預約已收到 | `buildBookingReceivedMessage()` | 163-200 |
| ✅ 預約審核通過 | `buildBookingApprovedMessage()` | 202-240 |
| ❌ 預約審核未通過 | `buildBookingRejectedMessage()` | 241-258 |
| 🚫 預約已取消 | `buildBookingCancelledMessage()` | 259-276 |
| 🎉 服務完成 | `buildBookingCompletedMessage()` | 277-315 |
| 📝 取消申請已送出 | `buildCancellationRequestedMessage()` | 316-338 |
| ⏰ 系統自動取消 | `buildBookingAutoCancelledMessage()` | 339-361 |
| 🔔 行前確認請求 | `buildBookingConfirmationRequestMessage()` | 362-401 |

### 修改範例

**修改「預約已收到」訊息**：
```php
private function buildBookingReceivedMessage(Booking $booking)
{
    $text = "📋 預約已收到\n\n";
    $text .= "親愛的 {$booking->customer_name}，\n";
    $text .= "您的預約已收到，待管理員審核\n\n";
    $text .= "預約單號：{$booking->booking_number}\n";
    
    if ($booking->booking_time) {
        $text .= "預約日期：" . $booking->booking_time->format('Y/m/d') . "\n";
        $text .= "預約時間：" . $booking->booking_time->format('H:i') . "\n";
    }
    
    if ($booking->style_type) {
        $styleTypes = [
            'single' => '單色美甲',
            'design' => '造型美甲'
        ];
        $serviceType = $styleTypes[$booking->style_type] ?? $booking->style_type;
        $text .= "服務項目：{$serviceType}\n";
    }
    
    if ($booking->need_removal) {
        $text .= "卸甲服務：是\n";
    }
    
    // 🔧 可修改這裡的結尾訊息
    $text .= "\n我們會盡快審核您的預約，請耐心等候。";

    return new TextMessage([
        'type' => MessageType::TEXT,
        'text' => $text
    ]);
}
```

### 可自訂的內容元素

**基本文字**：
- 開頭問候語：`親愛的 {$booking->customer_name}，`
- 說明文字：`您的預約已收到，待管理員審核`
- 結尾訊息：`我們會盡快審核您的預約，請耐心等候。`

**動態資料**：
- `{$booking->customer_name}` - 客戶姓名
- `{$booking->booking_number}` - 預約單號
- `$booking->booking_time->format('Y/m/d')` - 預約日期
- `$booking->booking_time->format('H:i')` - 預約時間
- `{$booking->amount}` - 服務金額

### 修改後的格式問題排除

如果修改後通知還是顯示舊格式，請執行以下步驟：

#### 1. 清除所有快取
```bash
# 清除 Laravel 快取
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 清除 Queue（重要！）
php artisan queue:clear

# 重新生成 Composer autoload
composer dump-autoload
```

#### 2. 清除 PHP OPCache
```bash
# 重啟 Web 服務器
sudo systemctl restart apache2
# 或
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm
```

#### 3. 一鍵清除命令
創建 `app/Console/Commands/ClearAllCache.php`：
```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAllCache extends Command
{
    protected $signature = 'cache:clear-all';
    protected $description = '清除所有快取包括 OPCache';

    public function handle()
    {
        $this->info('開始清除所有快取...');
        
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('queue:clear');
        
        if (function_exists('opcache_reset')) {
            opcache_reset();
            $this->info('✓ OPCache 已清除');
        }
        
        $this->info('🎉 所有快取已清除完成！');
        return 0;
    }
}
```

使用方法：
```bash
php artisan cache:clear-all
```

---

## 🧪 測試與監控

### 手動測試指令

#### 測試特定通知類型
```bash
# 基本語法
php artisan test:line-notification --booking={預約ID} --type={通知類型}

# 範例
php artisan test:line-notification --booking=123 --type=booking_received
php artisan test:line-notification --booking=123 --type=booking_approved
php artisan test:line-notification --booking=123 --type=booking_rejected
php artisan test:line-notification --booking=123 --type=booking_cancelled
php artisan test:line-notification --booking=123 --type=booking_completed
php artisan test:line-notification --booking=123 --type=cancellation_requested
php artisan test:line-notification --booking=123 --type=booking_auto_cancelled
php artisan test:line-notification --booking=123 --type=confirmation_request
```

#### 測試所有通知類型
```bash
php artisan test:line-notification --booking=123 --all
```

### 系統狀態檢查

#### 1. Queue 狀態
```bash
# 查看失敗的任務
php artisan queue:failed

# 重試失敗的任務
php artisan queue:retry all

# 清除失敗的任務
php artisan queue:flush
```

#### 2. Supervisor 狀態
```bash
# 查看 Worker 狀態
sudo supervisorctl status

# 重啟 Worker
sudo supervisorctl restart laravel-worker
```

#### 3. 日誌檢查
```bash
# 查看 Laravel 日誌
tail -f storage/logs/laravel.log

# 搜尋 LINE 相關日誌
grep "LINE" storage/logs/laravel.log

# 查看 Worker 日誌
tail -f storage/logs/worker.log
```

### 監控指標
- **Worker 運行時間**：應持續運行
- **Queue 處理速度**：通常 < 1 秒
- **通知成功率**：應 > 95%
- **錯誤日誌頻率**：應無重複錯誤

---

## 🔧 故障排除

### 常見問題與解決方案

#### 1. 通知沒有發送

**可能原因**：
- 用戶沒有 LINE ID
- Queue Worker 未運行
- LINE Token 錯誤
- 通知功能已關閉

**檢查步驟**：
```bash
# 1. 檢查用戶 LINE ID
php artisan tinker
>>> App\Models\User::find(123)->line_id

# 2. 檢查 Worker 狀態
sudo supervisorctl status

# 3. 檢查通知開關
php artisan tinker
>>> Cache::get('line_notification_enabled', true)

# 4. 測試 LINE 連接
php artisan test:line-notification --booking=123 --type=booking_received

# 5. 查看錯誤日誌
tail -f storage/logs/laravel.log | grep ERROR
```

#### 2. 通知重複發送

**原因**：Model Events 和手動發送重複
**解決方案**：確保只使用一種發送方式（建議使用 Model Events）

#### 3. Worker 頻繁重啟

**可能原因**：
- 記憶體不足
- PHP 錯誤
- 資料庫連接問題

**解決方法**：
```bash
# 檢查系統資源
free -h
df -h

# 檢查 Worker 日誌
tail -f storage/logs/worker.log

# 重啟 Worker
sudo supervisorctl restart laravel-worker
```

#### 4. 排程任務未執行

**檢查項目**：
- [ ] Cron Job 是否設定
- [ ] 伺服器時區是否正確
- [ ] 任務是否有錯誤

**檢查指令**：
```bash
# 查看 Cron Job
crontab -l

# 手動執行排程
php artisan schedule:run

# 檢查特定任務
php artisan booking:send-confirmation-requests
php artisan booking:cancel-unconfirmed
```

### 緊急修復步驟

#### 完全重啟系統
```bash
# 1. 停止 Worker
sudo supervisorctl stop laravel-worker

# 2. 清除 Queue
php artisan queue:flush

# 3. 清除快取
php artisan cache:clear-all

# 4. 重新載入 autoload
composer dump-autoload

# 5. 重啟 Worker
sudo supervisorctl start laravel-worker

# 6. 測試功能
php artisan test:line-notification --booking=123 --type=booking_received
```

---

## 🛠️ 維護指南

### 日常維護

#### 每日檢查
- [ ] Worker 運行狀態
- [ ] 錯誤日誌檢查
- [ ] 通知發送統計
- [ ] Queue 積壓情況

#### 每週維護
- [ ] 清理舊日誌檔案
- [ ] 檢查磁碟空間
- [ ] 更新依賴套件
- [ ] 備份重要設定

#### 每月維護
- [ ] 系統效能評估
- [ ] 安全性檢查
- [ ] LINE API Token 檢查
- [ ] 統計報告產生

### 備份與恢復

#### 重要檔案備份
```bash
# 備份環境設定
cp .env ~/backup/.env.$(date +%Y%m%d)

# 備份 Supervisor 設定
cp /etc/supervisor/conf.d/laravel-worker.conf ~/backup/

# 備份核心檔案
tar -czf ~/backup/line-notification-$(date +%Y%m%d).tar.gz \
    app/Services/LineNotificationService.php \
    app/Jobs/SendLineNotificationJob.php \
    app/Console/Commands/Send*.php \
    app/Console/Commands/Cancel*.php \
    app/Console/Commands/Test*.php
```

### 效能優化

#### 建議優化項目
1. **增加 Worker 數量**：處理大量通知
2. **使用 Redis Queue**：提升處理速度
3. **設定 Queue 優先級**：重要通知優先
4. **實施通知批次處理**：減少 API 呼叫
5. **監控記憶體使用**：避免記憶體洩漏

---

## 🏗️ 技術架構

### 核心組件

#### 1. LineNotificationService
- **位置**: `app/Services/LineNotificationService.php`
- **功能**: 處理所有 LINE API 呼叫和訊息建構
- **特點**: 使用最新 LINE Bot SDK

#### 2. SendLineNotificationJob
- **位置**: `app/Jobs/SendLineNotificationJob.php`
- **功能**: 非同步處理通知任務
- **特點**: 支援重試機制和錯誤處理

#### 3. Booking Model Events
- **位置**: `app/Models/Booking.php`
- **功能**: 自動觸發通知
- **事件**: created, updated

#### 4. Console Commands
- **SendConfirmationRequests**: 發送行前確認請求
- **CancelUnconfirmedBookings**: 自動取消逾期預約
- **TestLineNotification**: 測試通知功能

#### 5. LineNotificationSettings
- **位置**: `app/Livewire/Admin/LineNotificationSettings.php`
- **功能**: 管理員通知開關控制
- **特點**: 即時切換、狀態持久化

### 檔案結構
```
app/
├── Models/Booking.php                    # 預約模型（事件觸發）
├── Jobs/SendLineNotificationJob.php     # 通知任務（異步處理）
├── Services/LineNotificationService.php # LINE 服務（API 呼叫）
├── Console/Commands/                     # 定時任務
│   ├── SendBookingReminders.php
│   ├── SendConfirmationRequests.php
│   ├── CancelUnconfirmedBookings.php
│   └── TestLineNotification.php
├── Http/Controllers/Auth/LineController.php # LINE 登入整合
├── Livewire/Admin/LineNotificationSettings.php # 通知設定
└── Livewire/LineJoin.php                 # LINE 加入頁面

config/
├── services.php                         # LINE API 設定
└── line.php                            # 通知開關設定

resources/views/
├── livewire/line-join.blade.php         # LINE 加入頁面
├── livewire/admin/line-notification-settings.blade.php # 設定頁面
└── layouts/nav.blade.php                # 導航整合

routes/
├── auth.php                            # LINE 登入路由
└── admin.php                           # 管理員路由

bootstrap/app.php                        # 排程設定
```

### 資料流程
```
1. 用戶操作 → 2. 資料庫變更 → 3. Model Event 觸發 → 4. Job 派發
     ↓
8. 用戶收到通知 ← 7. LINE API → 6. LineNotificationService ← 5. Queue 處理
```

---

## 📞 技術支援

### 相關文件
- [LINE Messaging API 文檔](https://developers.line.biz/en/reference/messaging-api/)
- [Laravel Queue 文檔](https://laravel.com/docs/queues)
- [Laravel Task Scheduling 文檔](https://laravel.com/docs/scheduling)

### 有用連結
- [LINE Developers Console](https://developers.line.biz/console/)
- [Laravel 官方文件](https://laravel.com/docs)
- [Supervisor 官方文件](http://supervisord.org/)

### 檢查清單
- [ ] 環境變數設定完整
- [ ] LINE API 權杖有效
- [ ] Queue Worker 正在運行
- [ ] Cron Job 設定正確
- [ ] 用戶已綁定 LINE ID
- [ ] 通知開關已啟用

---

**最後更新**：2025年1月21日  
**版本**：v5.0  
**系統狀態**：✅ 正常運行  
**新增功能**：✅ 導航整合、通知開關、格式修復指南 