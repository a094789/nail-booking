{{-- resources/views/livewire/admin/booking-management.blade.php --}}
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">預約管理</h1>
                    <p class="mt-1 text-sm text-gray-600">管理所有使用者的預約申請</p>
                </div>

                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <!-- 🔑 使用 AdminBookingCreate 組件，它包含按鈕和模態窗 -->
                    @livewire('admin.admin-booking-create')

                    <!-- 保持原來的時段管理按鈕 -->
                    <a href="{{ route('admin.available-times.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        時段管理
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔑 統計卡片區域 -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $monthlyStats['total'] }}</div>
                <div class="text-sm text-gray-600">本月總預約</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-yellow-600">{{ $monthlyStats['pending'] }}</div>
                <div class="text-sm text-gray-600">待審核</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-green-600">{{ $monthlyStats['approved'] }}</div>
                <div class="text-sm text-gray-600">預約成功</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-purple-600">{{ $monthlyStats['completed'] }}</div>
                <div class="text-sm text-gray-600">已完成</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-red-600">{{ $monthlyStats['cancelled'] }}</div>
                <div class="text-sm text-gray-600">已取消</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-orange-600">{{ $monthlyStats['cancellation_requests'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">取消申請</div>
            </div>
        </div>
    </div>

    <!-- 篩選狀態摘要 -->
    @if($searchTerm || $statusFilter !== 'all' || $dateFilter || $monthFilter)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <p class="text-blue-800 font-semibold">目前篩選條件</p>
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
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="mr-2">狀態: {{ ucfirst($statusFilter) }}</span>
                    <button wire:click="$set('statusFilter', 'all')" class="flex-shrink-0 ml-1 hover:text-yellow-600 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
                @endif

                @if($dateFilter)
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="mr-2">日期: {{ $dateFilter }}</span>
                    <button wire:click="$set('dateFilter', '')" class="flex-shrink-0 ml-1 hover:text-green-600 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
                @endif

                @if($monthFilter)
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="mr-2">月份: {{ $monthFilter }}</span>
                    <button wire:click="$set('monthFilter', '')" class="flex-shrink-0 ml-1 hover:text-purple-600 transition-colors">
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

    <!-- 篩選功能 (收折式) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div x-data="{ showFilters: false }" class="mb-8">
            <!-- 篩選切換按鈕 -->
            <button @click="showFilters = !showFilters"
                type="button"
                class="group flex items-center w-full sm:w-auto mb-6 px-4 py-3 text-gray-700 
                           bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md 
                           hover:bg-gray-50 hover:text-gray-900 hover:border-pink-300
                           transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-opacity-50">

                <!-- 篩選圖標 -->
                <div class="flex items-center justify-center w-8 h-8 mr-3 rounded-lg 
                            bg-gradient-to-br from-pink-50 to-purple-100
                            group-hover:from-pink-100 group-hover:to-purple-200
                            transition-all duration-200">
                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                </div>

                <div class="flex-1 text-left">
                    <span class="block text-base font-bold tracking-wide" x-text="showFilters ? '收起篩選' : '展開篩選'">展開篩選</span>
                </div>

                <!-- 篩選狀態指示器 -->
                @if($searchTerm || $statusFilter !== 'all' || $dateFilter || $monthFilter)
                <div class="mr-3 flex items-center">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                 bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-md">
                        <div class="w-1.5 h-1.5 bg-white rounded-full mr-1.5 animate-pulse"></div>
                        篩選中
                    </span>
                </div>
                @endif

                <!-- 展開/收起箭頭 -->
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
                class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-2xl shadow-inner">

                <div class="p-8">
                    <!-- 快速篩選按鈕 -->
                    <div class="mb-8">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">快速篩選</h3>
                        <div class="flex flex-wrap gap-2">
                            <button wire:click="clearFilters"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ !$searchTerm && $statusFilter === 'all' && !$dateFilter && !$monthFilter ? 'bg-pink-100 text-pink-700 border border-pink-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                全部記錄
                            </button>
                            <button wire:click="filterPendingAndCancellation"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $statusFilter === 'pending_and_cancellation' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                待處理 (預設)
                            </button>
                            <button wire:click="filterPending"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $statusFilter === 'pending' ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                待審核
                            </button>
                            <button wire:click="filterCancellationRequests"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $statusFilter === 'cancellation_requested' ? 'bg-orange-100 text-orange-700 border border-orange-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                取消申請
                            </button>
                            <button wire:click="filterToday"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $dateFilter === now()->format('Y-m-d') ? 'bg-blue-100 text-blue-700 border border-blue-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                今天
                            </button>
                            <button wire:click="filterThisMonth"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $monthFilter === now()->format('Y-m') ? 'bg-purple-100 text-purple-700 border border-purple-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                本月
                            </button>
                            <button wire:click="testFilter"
                                class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-700 hover:bg-red-200 transition-colors">
                                🔧 測試
                            </button>
                        </div>
                    </div>

                    <!-- 詳細篩選 -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-4">詳細篩選</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- 搜尋 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">搜尋</label>
                                <input type="text" wire:model.live="searchTerm"
                                    placeholder="預約單號、姓名、電話..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 bg-white text-gray-900">
                            </div>

                            <!-- 狀態篩選 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">狀態</label>
                                <select wire:model.live="statusFilter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 bg-white text-gray-900">
                                    <option value="all">全部狀態</option>
                                    <option value="pending">待審核</option>
                                    <option value="approved">預約成功</option>
                                    <option value="cancelled">已取消</option>
                                    <option value="completed">已完成</option>
                                    <option value="cancellation_requested">取消申請</option>
                                </select>
                            </div>

                            <!-- 日期篩選 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    指定日期
                                    @if($dateFilter)
                                    <span class="text-xs text-blue-600 font-normal">(已篩選)</span>
                                    @endif
                                </label>
                                <div class="space-y-2">
                                    <input type="date" wire:model.live="dateFilter"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 bg-white text-gray-900">
                                    @if($dateFilter)
                                    <button wire:click="$set('dateFilter', '')"
                                        class="inline-flex items-center px-2 py-1 text-xs text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        清除日期
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <!-- 月份篩選 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    月份
                                    @if($monthFilter)
                                    <span class="text-xs text-purple-600 font-normal">(已篩選)</span>
                                    @endif
                                </label>
                                <div class="space-y-2">
                                    <input type="month" wire:model.live="monthFilter"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 bg-white text-gray-900">
                                    @if($monthFilter)
                                    <button wire:click="$set('monthFilter', '')"
                                        class="inline-flex items-center px-2 py-1 text-xs text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        清除月份
                                    </button>
                                    @endif
                                </div>
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

        <!-- Bookings Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- 🔑 手機版卡片視圖 -->
            <div class="md:hidden">
                @forelse($bookings as $booking)
                <div x-data="{ expanded: false }" class="border-b border-gray-200 last:border-b-0">
                    <!-- 卡片頭部 -->
                    <div class="p-4" @click="expanded = !expanded">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="font-bold text-gray-900">{{ $booking->booking_number }}</div>
                                    <div class="flex flex-wrap gap-1">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @switch($booking->status)
                    @case('pending') bg-yellow-100 text-yellow-800 @break
                    @case('approved') bg-green-100 text-green-800 @break
                    @case('cancelled') bg-red-100 text-red-800 @break
                    @case('completed') bg-blue-100 text-blue-800 @break
                @endswitch">
                                            {{ $booking->status_text }}
                                        </span>
                                        @if($booking->cancellation_requested && $booking->status === 'approved')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            取消申請中
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">{{ $booking->customer_name }} • {{ $booking->booking_time->format('m/d H:i') }}</div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="expanded ? 'transform rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- 展開的詳細信息 -->
                    <div x-show="expanded" x-transition class="px-4 pb-4 space-y-3 bg-gray-50">
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">LINE名稱：</span>
                                <span class="text-gray-900">{{ $booking->customer_line_name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">電話：</span>
                                <span class="text-gray-900">{{ $booking->customer_phone }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">款式：</span>
                                <span class="text-gray-900">{{ $booking->style_type_text }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">卸甲：</span>
                                <span class="text-gray-900">{{ $booking->need_removal ? '需要' : '不需要' }}</span>
                            </div>
                        </div>
                        <div class="text-sm mt-2">
                            <span class="font-medium text-gray-700">金額：</span>
                            @if($booking->amount)
                            <span class="text-green-600 font-semibold">NT$ {{ number_format($booking->amount) }}</span>
                            @else
                            <span class="text-gray-400">未設定</span>
                            @endif
                        </div>
                        <div class="text-sm">
                            <span class="font-medium text-gray-700">申請時間：</span>
                            <span class="text-gray-900">{{ $booking->created_at->format('Y/m/d H:i') }}</span>
                        </div>

                        <!-- 操作按鈕 -->
                        <div class="pt-2" @click.stop>
                            @livewire('admin.admin-booking-actions', ['booking' => $booking], key('mobile-booking-actions-'.$booking->id))
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-gray-500">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">沒有找到符合條件的預約記錄</h3>
                    <p class="text-gray-500">嘗試調整篩選條件或清除所有篩選</p>
                </div>
                @endforelse
            </div>

            <!-- 🔑 桌面版表格視圖 -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">預約資訊</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">客戶資訊</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">服務內容</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">金額</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">狀態</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <!-- 預約資訊 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->booking_number }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->booking_time->format('Y/m/d H:i') }}</div>
                                <div class="text-xs text-gray-400">申請：{{ $booking->created_at->format('Y/m/d H:i') }}</div>
                            </td>
                            <!-- 客戶資訊 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->customer_line_name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->customer_phone }}</div>
                            </td>
                            <!-- 服務內容 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->style_type_text }}</div>
                                <div class="text-sm {{ $booking->need_removal ? 'text-orange-600' : 'text-gray-500' }}">
                                    {{ $booking->need_removal ? '需卸甲' : '不需卸甲' }}
                                </div>
                            </td>
                            <!-- 金額 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->amount)
                                <div class="text-sm font-semibold text-green-600">NT$ {{ number_format($booking->amount) }}</div>
                                @else
                                <div class="text-sm text-gray-400">未設定</div>
                                @endif
                            </td>
                            <!-- 狀態 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @switch($booking->status)
                @case('pending') bg-yellow-100 text-yellow-800 @break
                @case('approved') bg-green-100 text-green-800 @break
                @case('cancelled') bg-red-100 text-red-800 @break
                @case('completed') bg-blue-100 text-blue-800 @break
            @endswitch">
                                        {{ $booking->status_text }}
                                    </span>
                                    @if($booking->cancellation_requested && $booking->status === 'approved')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                        取消申請中
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <!-- 操作 -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @livewire('admin.admin-booking-actions', ['booking' => $booking], key('booking-actions-'.$booking->id))
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">沒有找到符合條件的預約記錄</h3>
                                    <p class="text-gray-500">嘗試調整篩選條件或清除所有篩選</p>
                                    @if($searchTerm || $statusFilter !== 'all' || $dateFilter || $monthFilter)
                                    <button wire:click="clearFilters"
                                        class="mt-3 px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 text-sm">
                                        清除所有篩選
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $bookings->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- 🔑 圖片預覽模態窗 -->
    @if($showImageModal)
    <div class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-[60] p-4"
        wire:click="closeImageViewer">
        <div class="relative max-w-4xl max-h-full">
            <!-- 關閉按鈕 -->
            <button wire:click.stop="closeImageViewer"
                class="absolute top-4 right-4 z-10 bg-black bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-70 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- 上一張按鈕 -->
            @if($currentImageIndex > 0)
            <button wire:click.stop="prevImage"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-black bg-opacity-50 text-white rounded-full w-12 h-12 flex items-center justify-center hover:bg-opacity-70 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            @endif

            <!-- 下一張按鈕 -->
            @if($currentImageIndex < count($currentImages) - 1)
                <button wire:click.stop="nextImage"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-black bg-opacity-50 text-white rounded-full w-12 h-12 flex items-center justify-center hover:bg-opacity-70 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                </button>
                @endif

                <!-- 圖片 -->
                <img src="{{ $currentImageUrl }}" alt="預覽圖片"
                    class="max-w-full max-h-full object-contain rounded-lg"
                    wire:click.stop>

                <!-- 圖片資訊 -->
                @if(count($currentImages) > 1)
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full text-sm">
                    {{ $currentImageIndex + 1 }} / {{ count($currentImages) }}
                </div>
                @endif
        </div>
    </div>
    @endif
</div>

