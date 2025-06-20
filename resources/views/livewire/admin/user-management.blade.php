{{-- resources/views/livewire/admin/user-management.blade.php --}}

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">使用者管理</h1>
                    <p class="mt-1 text-sm text-gray-600">管理所有註冊使用者</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <!-- 🔑 新增：顯示模式切換按鈕 -->
        <div class="mb-6 flex justify-end">
            <button wire:click="toggleShowAllUsers"
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ $showAllUsers ? '顯示使用者' : '顯示使用者權限' }}
            </button>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-{{ $showAllUsers ? '6' : '4' }} gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_users'] }}</div>
                <div class="text-sm text-gray-600">總使用者</div>
            </div>
            @if($showAllUsers)
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-indigo-600">{{ $stats['admin_users'] }}</div>
                <div class="text-sm text-gray-600">管理員</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-cyan-600">{{ $stats['regular_users'] }}</div>
                <div class="text-sm text-gray-600">一般使用者</div>
            </div>
            @endif
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-green-600">{{ $stats['active_users'] }}</div>
                <div class="text-sm text-gray-600">啟用中</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-red-600">{{ $stats['inactive_users'] }}</div>
                <div class="text-sm text-gray-600">已停用</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['new_this_month'] }}</div>
                <div class="text-sm text-gray-600">本月新增</div>
            </div>
        </div>
    </div>

    <!-- 篩選狀態摘要 -->
    @if($searchTerm || $statusFilter !== 'all' || $registrationFilter || $roleFilter !== 'all')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="p-5 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl border border-purple-200">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <p class="text-purple-800 font-semibold">目前篩選條件</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($searchTerm)
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="mr-2">搜尋: {{ Str::limit($searchTerm, 20) }}</span>
                    <button wire:click="$set('searchTerm', '')" class="flex-shrink-0 ml-1 hover:text-blue-600 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
                @endif

                @if($statusFilter !== 'all')
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="mr-2">狀態: {{ $statusFilter === 'active' ? '啟用中' : '已停用' }}</span>
                    <button wire:click="$set('statusFilter', 'all')" class="flex-shrink-0 ml-1 hover:text-green-600 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
                @endif

                @if($registrationFilter)
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="mr-2">註冊: 
                        @switch($registrationFilter)
                            @case('today') 今天 @break
                            @case('week') 本週 @break
                            @case('month') 本月 @break
                            @case('3months') 近三個月 @break
                            @default {{ $registrationFilter }}
                        @endswitch
                    </span>
                    <button wire:click="$set('registrationFilter', '')" class="flex-shrink-0 ml-1 hover:text-yellow-600 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
                @endif

                @if($roleFilter !== 'all')
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="mr-2">角色: {{ $roleFilter === 'admin' ? '管理員' : '一般使用者' }}</span>
                    <button wire:click="$set('roleFilter', 'all')" class="flex-shrink-0 ml-1 hover:text-purple-600 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
                @endif

                <button wire:click="clearFilters"
                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 hover:bg-red-200 transition-colors duration-200">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    清除所有篩選
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div x-data="{ showFilters: false }" class="mb-8">
            <!-- 篩選切換按鈕 -->
            <button @click="showFilters = !showFilters"
                type="button"
                class="group flex items-center w-full sm:w-auto mb-6 px-4 py-3 text-gray-700 
                           bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md 
                           hover:bg-gray-50 hover:text-gray-900 hover:border-purple-300
                           transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50">

                <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg 
                            bg-gradient-to-br from-purple-50 to-blue-100
                            group-hover:from-purple-100 group-hover:to-blue-200
                            transition-all duration-200">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                </div>

                <div class="flex-1 text-left">
                    <span class="block text-base font-bold tracking-wide" x-text="showFilters ? '收起篩選' : '展開篩選'">展開篩選</span>
                </div>

                <!-- 篩選狀態指示器 -->
                @if($searchTerm || $statusFilter !== 'all' || $registrationFilter || $roleFilter !== 'all')
                <div class="mr-3 flex items-center">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                 bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-md">
                        <div class="w-1.5 h-1.5 bg-white rounded-full mr-1.5 animate-pulse"></div>
                        篩選中
                    </span>
                </div>
                @endif

                <svg class="w-5 h-5 transition-transform duration-300 ease-out text-gray-400 group-hover:text-gray-600"
                    :class="showFilters ? 'transform rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- 篩選表單 -->
            <div x-show="showFilters"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-300 rounded-2xl shadow-inner">

                <div class="p-8">
                    <!-- 快速篩選按鈕 -->
                    <div class="mb-8">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">快速篩選</h3>
                        <div class="flex flex-wrap gap-2">
                            <button wire:click="clearFilters"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ !$searchTerm && $statusFilter === 'all' && !$registrationFilter && $roleFilter === 'all' ? 'bg-purple-100 text-purple-700 border border-purple-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                全部使用者
                            </button>
                            <button wire:click="filterActive"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $statusFilter === 'active' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                啟用中
                            </button>
                            <button wire:click="filterInactive"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $statusFilter === 'inactive' ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                已停用
                            </button>
                            <button wire:click="filterNewThisMonth"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $registrationFilter === 'month' ? 'bg-blue-100 text-blue-700 border border-blue-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                本月新增
                            </button>
                            <button wire:click="filterAdmins"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $roleFilter === 'admin' ? 'bg-purple-100 text-purple-700 border border-purple-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                管理員
                            </button>
                            <button wire:click="filterUsers"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $roleFilter === 'user' ? 'bg-cyan-100 text-cyan-700 border border-cyan-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                一般使用者
                            </button>
                        </div>
                    </div>

                    <!-- 詳細篩選 -->
                                            <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-4">詳細篩選</h3>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- 搜尋 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">搜尋使用者</label>
                                <input type="text" wire:model.live="searchTerm"
                                    placeholder="姓名、Email、LINE名稱、電話..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-gray-900">
                            </div>

                            <!-- 狀態篩選 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">帳號狀態</label>
                                <select wire:model.live="statusFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-gray-900">
                                    <option value="all">全部狀態</option>
                                    <option value="active">啟用中</option>
                                    <option value="inactive">已停用</option>
                                </select>
                            </div>

                            <!-- 註冊時間篩選 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">註冊時間</label>
                                <select wire:model.live="registrationFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-gray-900">
                                    <option value="">全部時間</option>
                                    <option value="today">今天</option>
                                    <option value="week">本週</option>
                                    <option value="month">本月</option>
                                    <option value="3months">近三個月</option>
                                </select>
                            </div>

                            <!-- 🔑 新增：角色篩選 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">使用者角色</label>
                                <select wire:model.live="roleFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-gray-900">
                                    <option value="all">全部角色</option>
                                    <option value="user">一般使用者</option>
                                    <option value="admin">管理員</option>
                                </select>
                            </div>
                        </div>

                        <!-- 按鈕區域 -->
                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 mt-6">
                            <button wire:click="clearFilters"
                                class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                重置篩選
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow border border-gray-300 overflow-hidden">
            <!-- 手機版卡片視圖 -->
            <div class="md:hidden">
                @forelse($users as $user)
                <div x-data="{ expanded: false }" class="border-b border-gray-200 last:border-b-0">
                    <!-- 卡片頭部 -->
                    <div class="p-4" @click="expanded = !expanded">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                    @if($showAllUsers)
                                    <div class="flex items-center w-fit px-3 py-1.5 bg-gradient-to-r {{ $user->role === 'admin' ? 'from-purple-50 to-indigo-100 border border-purple-200' : 'from-gray-50 to-slate-100 border border-gray-200' }} rounded-xl">
                                        <div class="w-2 h-2 {{ $user->role === 'admin' ? 'bg-purple-500' : 'bg-gray-500' }} rounded-full mr-2"></div>
                                        <span class="text-xs font-semibold {{ $user->role === 'admin' ? 'text-purple-800' : 'text-gray-800' }}">{{ $user->role === 'admin' ? '管理員' : '使用者' }}</span>
                                    </div>
                                    @endif
                                    <div class="flex items-center w-fit px-3 py-1.5 bg-gradient-to-r {{ $user->is_active ? 'from-emerald-50 to-green-100 border border-emerald-200' : 'from-red-50 to-rose-100 border border-red-200' }} rounded-xl">
                                        <div class="w-2 h-2 {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }} rounded-full mr-2"></div>
                                        <span class="text-xs font-semibold {{ $user->is_active ? 'text-emerald-800' : 'text-red-800' }}">{{ $user->is_active ? '啟用' : '停用' }}</span>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600 truncate" title="{{ $user->email }}">
                                    {{ Str::limit($user->email, 30) }}
                                </div>
                                <div class="text-xs text-gray-500">註冊：{{ $user->created_at->format('Y/m/d') }}</div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="expanded ? 'transform rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- 展開內容 -->
                    <div x-show="expanded" x-transition class="px-4 pb-4 space-y-3 bg-gray-50">
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">LINE名稱：</span>
                                <span class="text-gray-900 break-all" title="{{ $user->line_name ?? '未設定' }}">
                                    {{ Str::limit($user->line_name ?? '未設定', 15) }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">電話：</span>
                                <span class="text-gray-900">{{ $user->phone ?? '未設定' }}</span>
                            </div>
                            @if(!$showAllUsers)
                            <div>
                                <span class="font-medium text-gray-700">預約次數：</span>
                                <span class="text-gray-900">{{ $user->bookings_count }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">本月限制：</span>
                                <span class="text-gray-900">{{ $user->userProfile->monthly_bookings_count ?? 0 }}/{{ $user->userProfile->monthly_booking_limit ?? 3 }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- 操作按鈕 -->
                        <div class="flex space-x-2 pt-2">
                            <button wire:click="showUserDetails({{ $user->id }})"
                                class="group flex-1 flex items-center justify-center px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span class="text-sm font-medium">編輯</span>
                            </button>
                            <button wire:click="toggleUserStatus({{ $user->id }})"
                                class="group flex-1 flex items-center justify-center px-4 py-2.5 bg-gradient-to-r {{ $user->is_active ? 'from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700' : 'from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700' }} text-white rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($user->is_active)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @endif
                                </svg>
                                <span class="text-sm font-semibold">{{ $user->is_active ? '停用' : '啟用' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-gray-500">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">沒有找到符合條件的使用者</h3>
                    <p class="text-gray-500">嘗試調整篩選條件</p>
                </div>
                @endforelse
            </div>

            <!-- 桌面版表格視圖 -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button wire:click="sortBy('name')" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>使用者資訊</span>
                                    @if($sortBy === 'name')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">聯絡資訊</th>
                            @if($showAllUsers)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button wire:click="sortBy('role')" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>角色</span>
                                    @if($sortBy === 'role')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button wire:click="sortBy('created_at')" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>註冊時間</span>
                                    @if($sortBy === 'created_at')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            @if(!$showAllUsers)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">預約統計</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">狀態</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <!-- 筆數 ID -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <!-- 使用者資訊 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center max-w-xs">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->hasAvatar())
                                            <img src="{{ $user->getAvatarUrl() }}" 
                                                alt="{{ $user->name }}" 
                                                class="h-10 w-10 rounded-full object-cover"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center" style="display: none;">
                                                <span class="text-white font-semibold text-sm">
                                                    {{ $user->getInitials() }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">
                                                    {{ $user->getInitials() }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 truncate" title="{{ $user->email }}">
                                            {{ Str::limit($user->email, 30) }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- 聯絡資訊 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="max-w-xs">
                                    <div class="text-sm text-gray-900 truncate" title="{{ $user->line_name ?? '未設定' }}">
                                        {{ Str::limit($user->line_name ?? '未設定', 20) }}
                                    </div>
                                    <div class="text-sm text-gray-500 truncate" title="{{ $user->phone ?? '未設定' }}">
                                        {{ $user->phone ?? '未設定' }}
                                    </div>
                                </div>
                            </td>

                            @if($showAllUsers)
                            <!-- 🔑 新增：角色 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center w-fit px-3 py-2 bg-gradient-to-r {{ $user->role === 'admin' ? 'from-purple-50 to-indigo-100 border border-purple-200' : 'from-gray-50 to-slate-100 border border-gray-200' }} rounded-xl">
                                    <div class="w-2.5 h-2.5 {{ $user->role === 'admin' ? 'bg-purple-500' : 'bg-gray-500' }} rounded-full mr-2"></div>
                                    <span class="text-sm font-semibold {{ $user->role === 'admin' ? 'text-purple-800' : 'text-gray-800' }}">{{ $user->role === 'admin' ? '管理員' : '一般使用者' }}</span>
                                </div>
                            </td>
                            @endif

                            <!-- 註冊時間 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->created_at->format('Y/m/d') }}</div>
                                <div class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                            </td>

                            @if(!$showAllUsers)
                            <!-- 預約統計 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">總計：{{ $user->bookings_count }}</div>
                                <div class="text-sm text-gray-500">
                                    本月：{{ $user->userProfile->monthly_bookings_count ?? 0 }}/{{ $user->userProfile->monthly_booking_limit ?? 3 }}
                                </div>
                            </td>
                            @endif

                            <!-- 狀態 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center w-fit px-3 py-2 bg-gradient-to-r {{ $user->is_active ? 'from-emerald-50 to-green-100 border border-emerald-200' : 'from-red-50 to-rose-100 border border-red-200' }} rounded-xl">
                                    <div class="w-2.5 h-2.5 {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }} rounded-full mr-2"></div>
                                    <span class="text-sm font-semibold {{ $user->is_active ? 'text-emerald-800' : 'text-red-800' }}">{{ $user->is_active ? '啟用中' : '已停用' }}</span>
                                </div>
                            </td>

                            <!-- 操作 -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <!-- 編輯按鈕 -->
                                    <button wire:click="showUserDetails({{ $user->id }})"
                                        class="group flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="font-medium">編輯</span>
                                    </button>

                                    <!-- 啟用/停用按鈕 -->
                                    <button wire:click="toggleUserStatus({{ $user->id }})"
                                        class="group flex items-center px-4 py-2 bg-gradient-to-r {{ $user->is_active ? 'from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700' : 'from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700' }} text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($user->is_active)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @endif
                                        </svg>
                                        <span class="font-semibold">{{ $user->is_active ? '停用' : '啟用' }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $showAllUsers ? '7' : '7' }}" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">沒有找到符合條件的使用者</h3>
                                    <p class="text-gray-500">嘗試調整篩選條件</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- 使用者詳情模態窗 -->
    @if($showUserModal && $selectedUser)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col border border-gray-300">
            <!-- Modal Header - 固定不滾動 -->
            <div class="bg-gradient-to-r from-purple-500 to-pink-600 text-white p-6 rounded-t-xl flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- 使用者頭像 -->
                        <div class="flex-shrink-0">
                            @if($selectedUser->hasAvatar())
                                <img src="{{ $selectedUser->getAvatarUrl() }}" 
                                    alt="{{ $selectedUser->name }}" 
                                    class="h-12 w-12 rounded-full object-cover border-2 border-white/20"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center border-2 border-white/20" style="display: none;">
                                    <span class="text-white font-semibold text-lg">
                                        {{ $selectedUser->getInitials() }}
                                    </span>
                                </div>
                            @else
                                <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center border-2 border-white/20">
                                    <span class="text-white font-semibold text-lg">
                                        {{ $selectedUser->getInitials() }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold">編輯使用者 - {{ $selectedUser->name }}</h3>
                    </div>
                    <button wire:click="closeUserModal" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body - 可滾動區域 -->
            <div class="flex-1 overflow-y-auto">
                <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- 基本資訊 -->
                    <div>
                        <div class="mb-4">
                            <h4 class="text-lg font-medium text-gray-900">基本資訊</h4>
                        </div>

                        @if($editingUserInfo)
                            <!-- 編輯模式 -->
                            <form wire:submit.prevent="updateUserInfo" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">姓名 *</label>
                                    <input type="text" wire:model="editName" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    @error('editName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" wire:model="editEmail" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    @error('editEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">LINE名稱</label>
                                    <input type="text" wire:model="editLineName" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    @error('editLineName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">電話</label>
                                    <input type="text" wire:model="editPhone" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    @error('editPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex space-x-3 pt-4">
                                    <button type="submit"
                                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        儲存變更
                                    </button>
                                    <button type="button" wire:click="cancelEditingUserInfo"
                                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        查看模式
                                    </button>
                                </div>
                            </form>
                        @else
                            <!-- 顯示模式 -->
                            <div class="flex justify-end mb-4">
                                <button wire:click="startEditingUserInfo"
                                    class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-sm rounded-full hover:bg-blue-200 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    編輯模式
                                </button>
                            </div>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">頭像</dt>
                                    <dd class="flex items-center space-x-3">
                                        @if($selectedUser->hasAvatar())
                                            <img src="{{ $selectedUser->getAvatarUrl() }}" 
                                                alt="{{ $selectedUser->name }}" 
                                                class="h-16 w-16 rounded-full object-cover border border-gray-200"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center border border-gray-200" style="display: none;">
                                                <span class="text-white font-semibold text-xl">
                                                    {{ $selectedUser->getInitials() }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center border border-gray-200">
                                                <span class="text-white font-semibold text-xl">
                                                    {{ $selectedUser->getInitials() }}
                                                </span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm text-gray-900">
                                                {{ $selectedUser->hasAvatar() ? 'LINE 頭像' : '預設頭像' }}
                                            </div>
                                            @if($selectedUser->hasAvatar())
                                                <div class="text-xs text-gray-500">來自 LINE</div>
                                            @endif
                                        </div>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">姓名</dt>
                                    <dd class="text-sm text-gray-900">{{ $selectedUser->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ $selectedUser->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">LINE名稱</dt>
                                    <dd class="text-sm text-gray-900">{{ $selectedUser->line_name ?? '未設定' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">電話</dt>
                                    <dd class="text-sm text-gray-900">{{ $selectedUser->phone ?? '未設定' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">註冊時間</dt>
                                    <dd class="text-sm text-gray-900">{{ $selectedUser->created_at->format('Y年m月d日 H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">帳號狀態</dt>
                                    <dd>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $selectedUser->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $selectedUser->is_active ? '啟用中' : '已停用' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        @endif
                    </div>

                    <!-- 預約設定 -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">預約設定</h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">總預約次數</dt>
                                <dd class="text-sm text-gray-900">{{ $selectedUser->bookings_count }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">本月預約次數</dt>
                                <dd class="text-sm text-gray-900">{{ $selectedUser->userProfile->monthly_bookings_count ?? 0 }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">每月預約限制</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($editingBookingLimit)
                                    <form wire:submit.prevent="updateBookingLimit" class="flex items-center space-x-2">
                                        <input type="number" wire:model="newBookingLimit" min="0" max="10"
                                            class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-purple-500">
                                        <button type="submit"
                                            class="px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                            儲存
                                        </button>
                                        <button type="button" wire:click="$set('editingBookingLimit', false)"
                                            class="px-2 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600">
                                            取消
                                        </button>
                                    </form>
                                    @error('newBookingLimit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                    <div class="flex items-center space-x-2">
                                        <span>{{ $selectedUser->userProfile->monthly_booking_limit ?? 3 }}</span>
                                        <button wire:click="$set('editingBookingLimit', true)"
                                            class="text-blue-600 hover:text-blue-800 text-xs">
                                            編輯
                                        </button>
                                    </div>
                                    @endif
                                </dd>
                            </div>
                        </dl>

                        <!-- 重置按鈕 -->
                        <div class="mt-4">
                            <button wire:click="resetMonthlyBookings({{ $selectedUser->id }})"
                                class="px-4 py-2 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700"
                                onclick="return confirm('確定要重置使用者本月預約次數嗎？')">
                                重置本月預約次數
                            </button>
                        </div>
                    </div>

                    <!-- 🔑 新增：角色設定 -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">角色設定</h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">目前角色</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($editingUserRole)
                                    <form wire:submit.prevent="updateUserRole" class="flex items-center space-x-2">
                                        <select wire:model="editRole"
                                            class="w-32 px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-purple-500">
                                            <option value="user">一般使用者</option>
                                            <option value="admin">管理員</option>
                                        </select>
                                        <button type="submit"
                                            class="px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                            儲存
                                        </button>
                                        <button type="button" wire:click="cancelEditingUserRole"
                                            class="px-2 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600">
                                            取消
                                        </button>
                                    </form>
                                    @error('editRole') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $selectedUser->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $selectedUser->role === 'admin' ? '管理員' : '一般使用者' }}
                                        </span>
                                        <button wire:click="startEditingUserRole"
                                            class="text-blue-600 hover:text-blue-800 text-xs">
                                            編輯
                                        </button>
                                    </div>
                                    @endif
                                </dd>
                            </div>
                        </dl>

                        <!-- 角色說明 -->
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <h5 class="text-sm font-medium text-blue-900 mb-2">角色權限說明</h5>
                            <ul class="text-xs text-blue-800 space-y-1">
                                <li>• <strong>一般使用者</strong>：可以預約、查看自己的預約記錄</li>
                                <li>• <strong>管理員</strong>：可以管理所有預約、使用者、時段設定等</li>
                            </ul>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Modal Footer - 固定不滾動 -->
            <div class="bg-white px-6 py-4 flex justify-end flex-shrink-0 border-t border-gray-200 rounded-b-xl">
                <button wire:click="closeUserModal" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    關閉
                </button>
            </div>
        </div>
    </div>
    @endif
</div>