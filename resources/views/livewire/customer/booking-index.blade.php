<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">預約管理</h1>
            <p class="text-gray-600">查看和管理您的美甲預約</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-blue-600">{{ $monthlyStats['total'] }}</div>
                <div class="text-sm text-gray-600">本月總預約</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-yellow-600">{{ $monthlyStats['pending'] }}</div>
                <div class="text-sm text-gray-600">待審核</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-green-600">{{ $monthlyStats['confirmed'] }}</div>
                <div class="text-sm text-gray-600">預約成功</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-purple-600">{{ $monthlyStats['completed'] }}</div>
                <div class="text-sm text-gray-600">已完成</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-red-600">{{ $monthlyStats['cancelled'] }}</div>
                <div class="text-sm text-gray-600">已取消</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-300">
                <div class="text-2xl font-bold text-indigo-600">{{ $monthlyStats['remaining'] }}</div>
                <div class="text-sm text-gray-600">剩餘次數</div>
            </div>
        </div>

        <!-- 篩選狀態摘要 -->
        @if($selectedMonth || $selectedStatus !== 'all')
        <div class="p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 mb-8">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <p class="text-blue-800 font-semibold">目前篩選條件</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($selectedMonth)
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="mr-2">月份: {{ $selectedMonth }}</span>
                    <button wire:click="$set('selectedMonth', '')" class="flex-shrink-0 ml-1 hover:text-purple-600 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
                @endif

                @if($selectedStatus !== 'all')
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="mr-2">狀態: {{ ucfirst($selectedStatus) }}</span>
                    <button wire:click="$set('selectedStatus', 'all')" class="flex-shrink-0 ml-1 hover:text-yellow-600 transition-colors">
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
        @endif

        <!-- 篩選功能 (收折式) -->
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
                @if($selectedMonth || $selectedStatus !== 'all')
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
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ !$selectedMonth && $selectedStatus === 'all' ? 'bg-pink-100 text-pink-700 border border-pink-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                全部記錄
                            </button>
                            <button wire:click="filterPending"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $selectedStatus === 'pending' ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                待審核
                            </button>
                            <button wire:click="filterToday"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $selectedDate === now()->format('Y-m-d') ? 'bg-blue-100 text-blue-700 border border-blue-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                今天
                            </button>
                            <button wire:click="filterThisMonth"
                                class="px-3 py-1 text-sm rounded-full transition-colors {{ $selectedMonth === now()->format('Y-m') ? 'bg-purple-100 text-purple-700 border border-purple-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                本月
                            </button>
                        </div>
                    </div>

                    <!-- 詳細篩選 -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-4">詳細篩選</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- 月份篩選 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    月份
                                    @if($selectedMonth)
                                    <span class="text-xs text-purple-600 font-normal">(已篩選)</span>
                                    @endif
                                </label>
                                <div class="space-y-2">
                                    <input type="month" wire:model.live="selectedMonth"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 bg-white text-gray-900">
                                    @if($selectedMonth)
                                    <button wire:click="$set('selectedMonth', '')"
                                        class="inline-flex items-center px-2 py-1 text-xs text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        清除月份
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <!-- 狀態篩選 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">狀態</label>
                                <select wire:model.live="selectedStatus"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 bg-white text-gray-900">
                                    <option value="all">全部狀態</option>
                                    <option value="pending">待審核</option>
                                    <option value="approved">預約成功</option>
                                    <option value="completed">已完成</option>
                                    <option value="cancelled">已取消</option>
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

        <!-- Bookings List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-300">
            @if($bookings->count() > 0)
            <!-- 手機版收折卡片 -->
            <div class="md:hidden">
                @foreach($bookings as $booking)
                <div x-data="{ expanded: false }" class="border-b border-gray-200 last:border-b-0">
                    <!-- 卡片頭部 -->
                    <div class="p-4 flex justify-between items-center" @click="expanded = !expanded">
                        <div>
                            <div class="font-bold text-gray-900">{{ $booking->booking_number }}</div>
                            <div class="text-sm text-gray-600">{{ $booking->booking_time->format('Y/m/d H:i') }}</div>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <!-- 主要狀態 -->
                                <div class="flex items-center">
                                    @switch($booking->status)
                                    @case('pending')
                                    <div class="flex items-center px-3 py-1.5 bg-gradient-to-r from-amber-50 to-yellow-100 border border-amber-200 rounded-xl">
                                        <div class="w-2 h-2 bg-amber-400 rounded-full mr-2 animate-pulse"></div>
                                        <span class="text-xs font-semibold text-amber-800">{{ $booking->status_text }}</span>
                                    </div>
                                    @break
                                    @case('approved')
                                    <div class="flex items-center px-3 py-1.5 bg-gradient-to-r from-emerald-50 to-green-100 border border-emerald-200 rounded-xl">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></div>
                                        <span class="text-xs font-semibold text-emerald-800">{{ $booking->status_text }}</span>
                                    </div>
                                    @if($booking->is_confirmed)
                                    <div class="flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-50 to-cyan-100 border border-blue-200 rounded-xl">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                        <span class="text-xs font-semibold text-blue-800">行程已確認</span>
                                    </div>
                                    @endif
                                    @break
                                    @case('cancelled')
                                    <div class="flex items-center px-3 py-1.5 bg-gradient-to-r from-red-50 to-rose-100 border border-red-200 rounded-xl">
                                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                        <span class="text-xs font-semibold text-red-800">{{ $booking->status_text }}</span>
                                    </div>
                                    @break
                                    @case('completed')
                                    <div class="flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-50 to-indigo-100 border border-blue-200 rounded-xl">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                        <span class="text-xs font-semibold text-blue-800">{{ $booking->status_text }}</span>
                                    </div>
                                    @break
                                    @endswitch
                                </div>

                                <!-- 取消申請狀態 -->
                                @if($booking->cancellation_requested && $booking->status === 'approved')
                                <div class="flex items-center px-3 py-1.5 bg-gradient-to-r from-orange-50 to-amber-100 border border-orange-200 rounded-xl">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full mr-2 animate-pulse"></div>
                                    <span class="text-xs font-semibold text-orange-800">取消申請中</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="expanded ? 'transform rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <!-- 展開內容 -->
                    <div x-show="expanded" x-transition class="px-4 pb-4 bg-gray-50">
                        <div class="text-sm text-gray-700 mb-2"><b>服務內容：</b>{{ $booking->style_type_text }}</div>
                        <div class="text-sm mb-2"><b>卸甲：</b>{{ $booking->need_removal ? '需卸甲' : '不需卸甲' }}</div>
                        @if($booking->created_by_admin)
                        <div class="flex items-center text-sm text-purple-800 mb-2">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <b>管理員預約</b>
                        </div>
                        @endif
                        @if($booking->notes)
                        <div class="text-xs text-gray-500 mb-2"><b>備註：</b>{{ Str::limit($booking->notes, 30) }}</div>
                        @endif
                        <div class="text-xs text-gray-400 mb-2">申請：{{ $booking->created_at->format('Y/m/d H:i') }}</div>
                        @if($booking->amount)
                        <div class="text-sm mb-2"><b>服務金額：</b><span class="text-green-600 font-semibold">NT$ {{ number_format($booking->amount) }}</span></div>
                        @endif

                        <!-- 取消申請資訊 -->
                        @if($booking->cancellation_requested && $booking->status === 'approved')
                        <div class="bg-orange-50 border border-orange-200 rounded p-2 mb-2">
                            <div class="text-xs text-orange-700">
                                <b>取消申請時間：</b>{{ $booking->cancellation_requested_at->format('Y/m/d H:i') }}
                            </div>
                            @if($booking->cancellation_reason)
                            <div class="text-xs text-orange-700 mt-1">
                                <b>取消原因：</b>{{ $booking->cancellation_reason }}
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- 操作按鈕 -->
                        <div class="flex items-center gap-2 pt-3">
                            <!-- 檢視按鈕 -->
                            <button wire:click="viewBooking({{ $booking->id }})"
                                class="group flex items-center px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="text-sm font-medium">檢視</span>
                            </button>

                            <!-- 🔑 預約確認按鈕 -->
                            @if($this->canConfirmBooking($booking) && $booking->confirmation_token)
                            <a href="{{ route('booking.confirm', $booking->confirmation_token) }}" target="_blank"
                                class="group flex items-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-semibold">行前確認</span>
                            </a>
                            @endif

                            @if($booking->canBeCancelled())
                            @if($booking->status === 'pending')
                            <button wire:click="openCancelModal({{ $booking->id }})"
                                class="group flex items-center px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl hover:from-red-600 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="text-sm font-semibold">取消預約</span>
                            </button>
                            @elseif($booking->status === 'approved' && !$booking->cancellation_requested)
                            <button wire:click="openCancelRequestModal({{ $booking->id }})"
                                class="group flex items-center px-4 py-2.5 bg-gradient-to-r from-amber-500 to-yellow-600 text-white rounded-xl hover:from-amber-600 hover:to-yellow-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.98-.833-2.75 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <span class="text-sm font-semibold">申請取消</span>
                            </button>
                            @elseif($booking->cancellation_requested)
                            <div class="flex items-center px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-600">取消申請待審核中</span>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- 桌面版 table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">預約資訊</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">服務內容</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">金額</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">狀態</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <!-- 1. 預約資訊 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->booking_number }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->booking_time->format('Y/m/d H:i') }}</div>
                                <div class="text-xs text-gray-400">申請：{{ $booking->created_at->format('Y/m/d H:i') }}</div>
                            </td>

                            <!-- 2. 服務內容 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->style_type_text }}</div>
                                <div class="text-sm {{ $booking->need_removal ? 'text-orange-600' : 'text-gray-500' }}">
                                    {{ $booking->need_removal ? '需卸甲' : '不需卸甲' }}
                                </div>
                                @if($booking->created_by_admin)
                                <span class="inline-flex items-center px-2 py-1 mt-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    管理員預約
                                </span>
                                @endif
                                @if($booking->notes)
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($booking->notes, 30) }}</div>
                                @endif
                            </td>

                            <!-- 3. 金額 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->amount)
                                <div class="text-sm font-semibold text-green-600">NT$ {{ number_format($booking->amount) }}</div>
                                <div class="text-xs text-gray-500">服務費用</div>
                                @else
                                <div class="text-sm text-gray-400">未設定</div>
                                @endif
                            </td>

                            <!-- 4. 狀態 -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-2">
                                    <!-- 主要狀態 -->
                                    @switch($booking->status)
                                    @case('pending')
                                    <div class="flex items-center w-fit px-3 py-2 bg-gradient-to-r from-amber-50 to-yellow-100 border border-amber-200 rounded-xl">
                                        <div class="w-2.5 h-2.5 bg-amber-400 rounded-full mr-2 animate-pulse"></div>
                                        <span class="text-sm font-semibold text-amber-800">{{ $booking->status_text }}</span>
                                    </div>
                                    @break
                                    @case('approved')
                                    <div class="flex items-center w-fit px-3 py-2 bg-gradient-to-r from-emerald-50 to-green-100 border border-emerald-200 rounded-xl">
                                        <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-emerald-800">{{ $booking->status_text }}</span>
                                    </div>
                                    @if($booking->is_confirmed)
                                    <div class="flex items-center w-fit px-3 py-2 bg-gradient-to-r from-blue-50 to-cyan-100 border border-blue-200 rounded-xl">
                                        <div class="w-2.5 h-2.5 bg-blue-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-blue-800">行程已確認</span>
                                    </div>
                                    @endif
                                    @break
                                    @case('cancelled')
                                    <div class="flex items-center w-fit px-3 py-2 bg-gradient-to-r from-red-50 to-rose-100 border border-red-200 rounded-xl">
                                        <div class="w-2.5 h-2.5 bg-red-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-red-800">{{ $booking->status_text }}</span>
                                    </div>
                                    @break
                                    @case('completed')
                                    <div class="flex items-center w-fit px-3 py-2 bg-gradient-to-r from-blue-50 to-indigo-100 border border-blue-200 rounded-xl">
                                        <div class="w-2.5 h-2.5 bg-blue-500 rounded-full mr-2"></div>
                                        <span class="text-sm font-semibold text-blue-800">{{ $booking->status_text }}</span>
                                    </div>
                                    @break
                                    @endswitch

                                    <!-- 取消申請狀態 -->
                                    @if($booking->cancellation_requested && $booking->status === 'approved')
                                    <div class="flex items-center w-fit px-3 py-2 bg-gradient-to-r from-orange-50 to-amber-100 border border-orange-200 rounded-xl">
                                        <div class="w-2.5 h-2.5 bg-orange-500 rounded-full mr-2 animate-pulse"></div>
                                        <span class="text-sm font-semibold text-orange-800">取消申請中</span>
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- 5. 操作 -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <!-- 檢視按鈕 -->
                                    <button wire:click="viewBooking({{ $booking->id }})"
                                        class="group flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="font-medium">檢視</span>
                                    </button>

                                    <!-- 🔑 預約確認按鈕 -->
                                    @if($this->canConfirmBooking($booking) && $booking->confirmation_token)
                                    <a href="{{ route('booking.confirm', $booking->confirmation_token) }}" target="_blank"
                                        class="group flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-semibold">行前確認</span>
                                    </a>
                                    @endif

                                    @if($booking->canBeCancelled())
                                    @if($booking->status === 'pending')
                                    <button wire:click="openCancelModal({{ $booking->id }})"
                                        class="group flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg hover:from-red-600 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="font-semibold">取消預約</span>
                                    </button>
                                    @elseif($booking->status === 'approved' && !$booking->cancellation_requested)
                                    <button wire:click="openCancelRequestModal({{ $booking->id }})"
                                        class="group flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-yellow-600 text-white rounded-lg hover:from-amber-600 hover:to-yellow-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.98-.833-2.75 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        <span class="font-semibold">申請取消</span>
                                    </button>
                                    @elseif($booking->cancellation_requested)
                                    <div class="flex items-center px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-600 font-medium">取消申請待審核中</span>
                                    </div>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $bookings->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">尚無預約紀錄</h3>
                <p class="mt-2 text-gray-500">
                    @if($selectedStatus !== 'all')
                    此狀態下沒有預約紀錄
                    @else
                    您還沒有任何預約，立即開始您的第一次預約吧！
                    @endif
                </p>
                @if($selectedStatus === 'all')
                <div class="mt-6">
                    <a href="{{ route('booking.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        立即預約
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Cancel Modal -->
    @if($showCancelModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">取消預約</h3>
                            <div class="mt-4">
                                @if($cancellingBooking && $cancellingBooking->status === 'approved')
                                <div>
                                    <p class="text-sm text-gray-500">請填寫取消原因（50字內）</p>
                                    <div class="mt-4">
                                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">取消原因 *</label>
                                        <textarea wire:model="cancellationReason" id="cancellation_reason" rows="3"
                                            maxlength="50"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm"
                                            placeholder="請填寫取消原因..."></textarea>
                                        <div class="text-xs text-gray-400 text-right mt-1">
                                            剩餘字數：{{ 50 - strlen($cancellationReason ?? '') }}
                                        </div>
                                        @error('cancellationReason')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                @elseif($cancellingBooking && $cancellingBooking->status === 'pending')
                                <div>
                                    <p class="text-sm text-gray-500">確定要取消這筆預約嗎？</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="cancelBooking" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        確認取消
                    </button>
                    <button wire:click="closeCancelModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        返回
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 申請取消預約模態框 -->
    @if($showCancelRequestModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">申請取消預約</h3>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">請填寫取消原因，管理員將審核您的申請。</p>
                                <div class="mt-4">
                                    <label for="cancellation_reason_request" class="block text-sm font-medium text-gray-700">取消原因 *</label>
                                    <textarea wire:model="cancellationReason" id="cancellation_reason_request" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm"
                                        placeholder="請詳細說明取消原因..."></textarea>
                                    @error('cancellationReason')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="requestCancellation" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                        送出申請
                    </button>
                    <button wire:click="closeCancelRequestModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        返回
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- BookingView 組件 -->
    @livewire('customer.booking-view')
</div>