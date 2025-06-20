<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Agreement extends Component
{
    public $agreed = false;
    public $showScrollIndicator = true;
    public $hasScrolledToBottom = false; // 新增：是否滾動到底部

    public function updatedAgreed()
    {
        // 當同意狀態改變時的處理
        Log::info('Agreement status changed to: ' . ($this->agreed ? 'true' : 'false'));
    }

    public function markScrolledToBottom()
    {
        $this->hasScrolledToBottom = true;
        $this->showScrollIndicator = false;
        Log::info('User has scrolled to bottom');
    }

    public function acceptTerms()
    {
        Log::info('acceptTerms called, agreed: ' . ($this->agreed ? 'true' : 'false'));
        
        if (!$this->agreed) {
            session()->flash('error', '請先同意條款與隱私權政策');
            return;
        }

        if (!$this->hasScrolledToBottom) {
            session()->flash('error', '請先完整閱讀條款內容');
            return;
        }

        try {
            // 更新用戶同意狀態
            $user = Auth::user();
            
            if (!$user) {
                Log::error('No authenticated user found');
                session()->flash('error', '請先登入');
                // 🔑 修改：改為導向首頁而不是 login
                return redirect()->route('home');
            }
            
            $user->update([
                'terms_accepted_at' => now(),
                'terms_accepted' => true
            ]);
            
            Log::info('User agreement updated successfully for user: ' . $user->id);

            // 🔑 修改：同意條款後先跳轉到 LINE 加入頁面
            return redirect()->route('line.join')
                ->with('success', '條款同意成功！請加入官方 LINE 以接收預約通知');
            
        } catch (\Exception $e) {
            Log::error('Error in acceptTerms: ' . $e->getMessage());
            session()->flash('error', '發生錯誤，請稍後再試');
        }
    }

    /**
     * 🔑 修正：檢查是否需要完成個人資料
     */
    private function needsProfileCompletion($user)
    {
        $hasRealName = !empty($user->name) && $user->name !== 'LINE用戶';
        $hasPhone = !empty($user->phone);
        
        // 🔑 針對 LINE 用戶的檢查邏輯
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

    public function onScroll()
    {
        $this->showScrollIndicator = false;
    }

    public function render()
    {
        return view('livewire.agreement');
    }
}