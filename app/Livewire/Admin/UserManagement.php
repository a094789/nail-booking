<?php
// app/Livewire/Admin/UserManagement.php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UserManagement extends Component
{
    use WithPagination;

    // 篩選條件
    public $searchTerm = '';
    public $statusFilter = 'all'; // all, active, inactive
    public $registrationFilter = ''; // 註冊時間篩選
    public $roleFilter = 'all'; // 🔑 新增：角色篩選 all, user, admin
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // 模態窗相關
    public $showUserModal = false;
    public $selectedUser = null;
    public $editingBookingLimit = false;
    public $newBookingLimit = 3;

    // 編輯使用者基本資訊相關
    public $editingUserInfo = false;
    public $editName = '';
    public $editEmail = '';
    public $editLineName = '';
    public $editPhone = '';
    
    // 🔑 新增：編輯使用者角色相關
    public $editingUserRole = false;
    public $editRole = '';
    
    // 🔑 新增：切換顯示模式的屬性
    public $showAllUsers = false;

    protected $paginationTheme = 'tailwind';

    public function getUsersProperty()
    {
        // 🔑 修改：根據角色篩選決定基礎查詢
        if ($this->roleFilter === 'user') {
            $query = User::where('role', 'user');
        } elseif ($this->roleFilter === 'admin') {
            $query = User::where('role', 'admin');
        } else {
            // roleFilter === 'all' 時顯示所有使用者
            $query = User::query();
        }
        
        $query = $query->with(['userProfile', 'bookings'])
            ->withCount(['bookings']);

        // 搜尋篩選
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('line_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('phone', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // 狀態篩選
        if ($this->statusFilter !== 'all') {
            $isActive = $this->statusFilter === 'active';
            $query->where('is_active', $isActive);
        }

        // 註冊時間篩選
        if ($this->registrationFilter) {
            switch ($this->registrationFilter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', Carbon::now()->startOfWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', Carbon::now()->startOfMonth());
                    break;
                case '3months':
                    $query->where('created_at', '>=', Carbon::now()->subMonths(3));
                    break;
            }
        }

        // 排序
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(10);
    }

    // 🔑 新增：取得所有使用者（包含管理員）的屬性
    public function getAllUsersProperty()
    {
        // 🔧 修復：加入角色篩選邏輯
        if ($this->roleFilter === 'user') {
            $query = User::where('role', 'user');
        } elseif ($this->roleFilter === 'admin') {
            $query = User::where('role', 'admin');
        } else {
            // roleFilter === 'all' 時顯示所有使用者
            $query = User::query();
        }
        
        $query = $query->with(['userProfile', 'bookings'])
            ->withCount(['bookings']);

        // 搜尋篩選
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('line_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('phone', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // 狀態篩選
        if ($this->statusFilter !== 'all') {
            $isActive = $this->statusFilter === 'active';
            $query->where('is_active', $isActive);
        }

        // 註冊時間篩選
        if ($this->registrationFilter) {
            switch ($this->registrationFilter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', Carbon::now()->startOfWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', Carbon::now()->startOfMonth());
                    break;
                case '3months':
                    $query->where('created_at', '>=', Carbon::now()->subMonths(3));
                    break;
            }
        }

        // 排序
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(10);
    }

    public function getStatsProperty()
    {
        return [
            'total_users' => User::where('role', 'user')->count(),
            'active_users' => User::where('role', 'user')->where('is_active', true)->count(),
            'inactive_users' => User::where('role', 'user')->where('is_active', false)->count(),
            'new_this_month' => User::where('role', 'user')
                ->where('created_at', '>=', Carbon::now()->startOfMonth())
                ->count(),
        ];
    }

    // 🔑 新增：取得所有使用者統計（包含管理員）
    public function getAllStatsProperty()
    {
        return [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'regular_users' => User::where('role', 'user')->count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'new_this_month' => User::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
        ];
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
        $this->statusFilter = 'all';
        $this->registrationFilter = '';
        $this->roleFilter = 'all'; // 🔑 新增
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    public function applyFilters()
    {
        // 重新載入頁面以應用篩選條件
        $this->resetPage();
    }

    public function filterActive()
    {
        // 🔧 修正：清除其他篩選，確保互斥性
        $this->reset(['registrationFilter', 'roleFilter']);
        $this->statusFilter = 'active';
        $this->roleFilter = 'all';
        $this->resetPage();
    }

    public function filterInactive()
    {
        // 🔧 修正：清除其他篩選，確保互斥性
        $this->reset(['registrationFilter', 'roleFilter']);
        $this->statusFilter = 'inactive';
        $this->roleFilter = 'all';
        $this->resetPage();
    }

    public function filterNewThisMonth()
    {
        // 🔧 修正：清除其他篩選，確保互斥性
        $this->reset(['statusFilter', 'roleFilter']);
        $this->registrationFilter = 'month';
        $this->statusFilter = 'all';
        $this->roleFilter = 'all';
        $this->resetPage();
    }

    // 🔑 新增：角色篩選方法
    public function filterAdmins()
    {
        // 🔧 修正：清除其他篩選，確保互斥性
        $this->reset(['statusFilter', 'registrationFilter']);
        $this->roleFilter = 'admin';
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    public function filterUsers()
    {
        // 🔧 修正：清除其他篩選，確保互斥性
        $this->reset(['statusFilter', 'registrationFilter']);
        $this->roleFilter = 'user';
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    public function showUserDetails($userId)
    {
        $this->selectedUser = User::with(['userProfile', 'bookings' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }])->find($userId);
        
        if ($this->selectedUser) {
            $this->newBookingLimit = $this->selectedUser->userProfile->monthly_booking_limit ?? 3;
            $this->editingBookingLimit = false;
            $this->editingUserInfo = true; // 直接進入編輯模式
            $this->startEditingUserInfo(); // 載入編輯資料
            $this->showUserModal = true;
        }
    }

    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->selectedUser = null;
        $this->editingBookingLimit = false;
        $this->editingUserInfo = false;
        $this->editingUserRole = false; // 🔑 新增
        $this->newBookingLimit = 3;
        $this->editRole = ''; // 🔑 新增
        $this->resetEditForm();
    }

    public function startEditingUserInfo()
    {
        if ($this->selectedUser) {
            $this->editingUserInfo = true;
            $this->editName = $this->selectedUser->name;
            $this->editEmail = $this->selectedUser->email;
            $this->editLineName = $this->selectedUser->line_name ?? '';
            $this->editPhone = $this->selectedUser->phone ?? '';
        }
    }

    public function cancelEditingUserInfo()
    {
        $this->editingUserInfo = false;
        $this->resetEditForm();
    }

    // 🔑 新增：開始編輯使用者角色
    public function startEditingUserRole()
    {
        if ($this->selectedUser) {
            $this->editingUserRole = true;
            $this->editRole = $this->selectedUser->role;
        }
    }

    // 🔑 新增：取消編輯使用者角色
    public function cancelEditingUserRole()
    {
        $this->editingUserRole = false;
        $this->editRole = '';
        $this->resetErrorBag(['editRole']);
    }

    // 🔑 新增：更新使用者角色
    public function updateUserRole()
    {
        if (!$this->selectedUser) {
            return;
        }

        $this->validate([
            'editRole' => 'required|in:user,admin',
        ], [
            'editRole.required' => '請選擇使用者角色',
            'editRole.in' => '角色必須是 user 或 admin',
        ]);

        try {
            // 防止最後一個管理員被降級
            if ($this->selectedUser->role === 'admin' && $this->editRole === 'user') {
                $adminCount = User::where('role', 'admin')->count();
                if ($adminCount <= 1) {
                    session()->flash('error', '無法將最後一個管理員降級為一般使用者');
                    return;
                }
            }

            $this->selectedUser->update([
                'role' => $this->editRole,
            ]);

            $this->selectedUser = $this->selectedUser->fresh();
            $this->editingUserRole = false;
            $this->editRole = '';
            
            $roleText = $this->editRole === 'admin' ? '管理員' : '一般使用者';
            session()->flash('success', "使用者角色已更新為{$roleText}");
            
        } catch (\Exception $e) {
            Log::error('Update user role error: ' . $e->getMessage());
            session()->flash('error', '更新失敗：' . $e->getMessage());
        }
    }

    public function updateUserInfo()
    {
        if (!$this->selectedUser) {
            return;
        }

        $this->validate([
            'editName' => 'required|string|max:255',
            'editEmail' => 'required|email|max:255|unique:users,email,' . $this->selectedUser->id,
            'editLineName' => 'nullable|string|max:255',
            'editPhone' => 'nullable|string|max:20',
        ], [
            'editName.required' => '姓名為必填項目',
            'editName.max' => '姓名不能超過255個字元',
            'editEmail.required' => 'Email為必填項目',
            'editEmail.email' => 'Email格式不正確',
            'editEmail.unique' => '此Email已被其他使用者使用',
            'editEmail.max' => 'Email不能超過255個字元',
            'editLineName.max' => 'LINE名稱不能超過255個字元',
            'editPhone.max' => '電話號碼不能超過20個字元',
        ]);

        try {
            $this->selectedUser->update([
                'name' => $this->editName,
                'email' => $this->editEmail,
                'line_name' => $this->editLineName ?: null,
                'phone' => $this->editPhone ?: null,
            ]);

            $this->selectedUser = $this->selectedUser->fresh();
            $this->editingUserInfo = false;
            $this->resetEditForm();
            session()->flash('success', '使用者資訊已更新');
            
        } catch (\Exception $e) {
            Log::error('Update user info error: ' . $e->getMessage());
            session()->flash('error', '更新失敗：' . $e->getMessage());
        }
    }

    private function resetEditForm()
    {
        $this->editName = '';
        $this->editEmail = '';
        $this->editLineName = '';
        $this->editPhone = '';
        $this->resetErrorBag(['editName', 'editEmail', 'editLineName', 'editPhone']);
    }

    public function toggleUserStatus($userId)
    {
        $user = User::find($userId);
        if ($user) {
            // 🔑 修改：防止停用最後一個啟用的管理員
            if ($user->role === 'admin' && $user->is_active) {
                $activeAdminCount = User::where('role', 'admin')->where('is_active', true)->count();
                if ($activeAdminCount <= 1) {
                    session()->flash('error', '無法停用最後一個啟用的管理員');
                    return;
                }
            }
            
            $user->update(['is_active' => !$user->is_active]);
            
            $status = $user->is_active ? '啟用' : '停用';
            session()->flash('success', "使用者帳號已{$status}");
        }
    }

    public function updateBookingLimit()
    {
        if (!$this->selectedUser) {
            return;
        }

        $this->validate([
            'newBookingLimit' => 'required|integer|min:0|max:10'
        ], [
            'newBookingLimit.required' => '請填寫預約限制次數',
            'newBookingLimit.integer' => '預約限制次數必須是整數',
            'newBookingLimit.min' => '預約限制次數不能小於0',
            'newBookingLimit.max' => '預約限制次數不能大於10',
        ]);

        try {
            $userProfile = $this->selectedUser->userProfile;
            if ($userProfile) {
                $userProfile->update(['monthly_booking_limit' => $this->newBookingLimit]);
            } else {
                UserProfile::create([
                    'user_id' => $this->selectedUser->id,
                    'monthly_booking_limit' => $this->newBookingLimit,
                    'monthly_bookings_count' => 0,
                    'booking_count_reset_date' => Carbon::now()->startOfMonth()->addMonth(),
                ]);
            }

            $this->selectedUser = $this->selectedUser->fresh();
            $this->editingBookingLimit = false;
            session()->flash('success', '預約限制已更新');
            
        } catch (\Exception $e) {
            Log::error('Update booking limit error: ' . $e->getMessage());
            session()->flash('error', '更新失敗：' . $e->getMessage());
        }
    }

    public function resetMonthlyBookings($userId)
    {
        $user = User::with('userProfile')->find($userId);
        if ($user && $user->userProfile) {
            $user->userProfile->update([
                'monthly_bookings_count' => 0,
                'booking_count_reset_date' => Carbon::now()->startOfMonth()->addMonth(),
            ]);
            
            session()->flash('success', '使用者本月預約次數已重置');
            
            if ($this->selectedUser && $this->selectedUser->id === $userId) {
                $this->selectedUser = $this->selectedUser->fresh();
            }
        }
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedRegistrationFilter()
    {
        $this->resetPage();
    }

    // 🔑 新增：角色篩選監聽器
    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    // 🔑 新增：切換顯示模式的方法
    public function toggleShowAllUsers()
    {
        $this->showAllUsers = !$this->showAllUsers;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.user-management', [
            'users' => $this->users,
            'stats' => $this->roleFilter === 'all' ? $this->allStats : $this->stats,
            'showAllUsers' => $this->roleFilter === 'all' || $this->roleFilter === 'admin',
        ])->layout('layouts.app');
    }
}