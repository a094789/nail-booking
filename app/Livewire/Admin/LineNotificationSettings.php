<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('LINE 通知設定')]
class LineNotificationSettings extends Component
{
    public $notificationEnabled;
    
    public function mount()
    {
        // 從快取或設定檔讀取當前狀態
        $this->notificationEnabled = Cache::get('line_notification_enabled', config('line.notification_enabled', true));
    }
    
    public function toggleNotification()
    {
        $this->notificationEnabled = !$this->notificationEnabled;
        
        // 儲存到快取中（永久保存）
        Cache::forever('line_notification_enabled', $this->notificationEnabled);
        
        // 記錄日誌
        Log::info('LINE 通知設定已更新', [
            'enabled' => $this->notificationEnabled,
            'admin_user' => \Illuminate\Support\Facades\Auth::user()->name
        ]);
        
        $message = $this->notificationEnabled ? 'LINE 通知已啟用' : 'LINE 通知已關閉';
        session()->flash('success', $message);
    }
    
    public function getStatusTextProperty()
    {
        return $this->notificationEnabled ? '啟用中' : '已關閉';
    }
    
    public function getStatusColorProperty()
    {
        return $this->notificationEnabled ? 'text-green-600' : 'text-red-600';
    }
    
    public function getButtonTextProperty()
    {
        return $this->notificationEnabled ? '關閉通知' : '啟用通知';
    }
    
    public function getButtonColorProperty()
    {
        return $this->notificationEnabled ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600';
    }
    
    public function render()
    {
        return view('livewire.admin.line-notification-settings');
    }
} 