<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserProfileController extends Controller
{
    /**
     * 顯示基本資料填寫/編輯頁面
     */
    public function edit()
    {
        $user = Auth::user();
        
        // 檢查是否為首次登入（基本資料未完整）
        $isFirstTime = empty($user->name) || empty($user->phone) || empty($user->email);
        
        // 檢查各欄位是否可編輯（距離上次編輯超過3個月）
        $canEditName = $this->canEditField($user->last_name_update);
        $canEditPhone = $this->canEditField($user->last_phone_update);
        $canEditEmail = $this->canEditField($user->last_email_update);
        
        return view('profile.edit', compact('user', 'isFirstTime', 'canEditName', 'canEditPhone', 'canEditEmail'));
    }
    
    /**
     * 更新使用者基本資料
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $isFirstTime = empty($user->name) || empty($user->phone) || empty($user->email);
        
        // 基本驗證規則
        $rules = [];
        $messages = [
            'name.required' => '姓名為必填項目',
            'name.string' => '姓名格式不正確',
            'name.max' => '姓名不能超過255個字元',
            'phone.required' => '電話為必填項目',
            'phone.regex' => '電話格式不正確，請輸入正確的台灣手機號碼',
            'email.required' => 'Email為必填項目',
            'email.email' => 'Email格式不正確',
            'email.unique' => '此Email已被其他使用者使用',
            'line_contact_id.string' => 'LINE ID格式不正確',
            'line_contact_id.max' => 'LINE ID不能超過255個字元',
        ];
        
        // LINE 聯繫 ID 驗證（可選填，隨時可編輯）
        $rules['line_contact_id'] = 'nullable|string|max:255';
        
        // 首次填寫或可編輯時才加入驗證規則
        if ($isFirstTime || $this->canEditField($user->last_name_update)) {
            $rules['name'] = 'required|string|max:255';
        }
        
        if ($isFirstTime || $this->canEditField($user->last_phone_update)) {
            $rules['phone'] = 'required|regex:/^09\d{8}$/';
        }
        
        if ($isFirstTime || $this->canEditField($user->last_email_update)) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }
        
        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $now = Carbon::now();
        $updateData = [];
        
        // 更新可編輯的欄位
        if ($isFirstTime || $this->canEditField($user->last_name_update)) {
            if ($request->filled('name')) {
                $updateData['name'] = $request->name;
                $updateData['last_name_update'] = $now;
            }
        }
        
        if ($isFirstTime || $this->canEditField($user->last_phone_update)) {
            if ($request->filled('phone')) {
                $updateData['phone'] = $request->phone;
                $updateData['last_phone_update'] = $now;
            }
        }
        
        if ($isFirstTime || $this->canEditField($user->last_email_update)) {
            if ($request->filled('email')) {
                $updateData['email'] = $request->email;
                $updateData['last_email_update'] = $now;
            }
        }
        
        // 更新 LINE 聯繫 ID（隨時可編輯）
        if ($request->has('line_contact_id')) {
            $updateData['line_contact_id'] = $request->line_contact_id;
        }
        
        if (!empty($updateData)) {
            $user->update($updateData);
        }
        
        if ($isFirstTime) {
            return redirect()->route('dashboard')->with('success', '基本資料設定完成！歡迎使用美甲預約系統。');
        } else {
            return back()->with('success', '個人資料更新成功！');
        }
    }
    
    /**
     * 檢查欄位是否可編輯（距離上次編輯超過3個月）
     */
    private function canEditField($lastUpdateTime)
    {
        if (is_null($lastUpdateTime)) {
            return true; // 從未編輯過，可以編輯
        }
        
        $lastUpdate = Carbon::parse($lastUpdateTime);
        $now = Carbon::now();
        
        return $now->diffInMonths($lastUpdate) >= 3;
    }
    
    /**
     * 取得下次可編輯的時間
     */
    public function getNextEditableDate($lastUpdateTime)
    {
        if (is_null($lastUpdateTime)) {
            return null;
        }
        
        return Carbon::parse($lastUpdateTime)->addMonths(3);
    }
}