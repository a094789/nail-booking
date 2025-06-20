<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LineJoin extends Component
{
    public $user;
    public $lineOfficialId;
    public $lineQrCodeUrl;
    public $lineAddUrl;

    public function mount()
    {
        $this->user = Auth::user(); // 可能為 null（未登入用戶）
        
        // 設定 LINE 官方帳號資訊
        $this->lineOfficialId = '@799bkrmo'; // 實際的 LINE 官方帳號 ID
        $this->lineQrCodeUrl = 'https://qr-official.line.me/gs/M_799bkrmo_GW.png?oat_content=qr'; // 實際的 QR Code URL
        $this->lineAddUrl = 'https://line.me/R/ti/p/@799bkrmo'; // 加好友連結
    }

    public function skipLineJoin()
    {
        // 未登入用戶導向登入頁面
        if (!$this->user) {
            return redirect()->route('customer.login')
                ->with('info', '請先登入以使用預約系統');
        }

        // 跳過 LINE 加入，直接檢查是否需要完成個人資料
        if ($this->needsProfileCompletion($this->user)) {
            return redirect()->route('profile.edit')
                ->with('info', '請完成個人基本資料填寫');
        }

        // 如果資料已完整，直接進入 dashboard
        return redirect()->route('dashboard');
    }

    public function continueToProfile()
    {
        // 未登入用戶導向登入頁面
        if (!$this->user) {
            return redirect()->route('customer.login')
                ->with('info', '請先登入以使用預約系統');
        }

        // 繼續到個人資料頁面或 dashboard
        if ($this->needsProfileCompletion($this->user)) {
            return redirect()->route('profile.edit')
                ->with('info', '請完成個人基本資料填寫');
        }

        // 如果資料已完整，直接進入 dashboard
        return redirect()->route('dashboard')
            ->with('success', '歡迎使用美甲預約系統！');
    }

    /**
     * 檢查是否需要完成個人資料
     */
    private function needsProfileCompletion($user)
    {
        $hasRealName = !empty($user->name) && $user->name !== 'LINE用戶';
        $hasPhone = !empty($user->phone);
        
        // 針對 LINE 用戶的檢查邏輯
        if ($user->provider === 'line') {
            // LINE 用戶：需要真實姓名、電話，並且要有真實 email（非假 email）
            $hasRealEmail = !empty($user->email) && !str_contains($user->email, '@temp.line.local');
            return !($hasRealName && $hasPhone && $hasRealEmail);
        } else {
            // 一般用戶：需要姓名、電話、email
            $hasEmail = !empty($user->email);
            return !($hasRealName && $hasPhone && $hasEmail);
        }
    }

    public function render()
    {
        return view('livewire.line-join');
    }
} 