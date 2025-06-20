<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">
                    可預約時段查詢
                </h1>
                <p class="text-lg opacity-90 mb-6">
                    查看近期可預約的美甲服務時段{{ Auth::check() ? '，點擊時段即可立即預約' : '' }}
                </p>
                
                <!-- Login/Status Reminder -->
                @guest
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm">查看時段無需登入，預約服務請先</span>
                        <a href="{{ route('customer.login') }}" class="text-yellow-200 hover:text-white font-medium ml-1">登入</a>
                    </div>
                @else
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm">您已登入，點擊時段可直接預約</span>
                        @if(!$canBookThisMonth)
                            <span class="text-yellow-200 ml-2">（本月已達預約上限 {{ $monthlyBookingCount }}/{{ $monthlyBookingLimit }}）</span>
                        @endif
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Month & Date Selection -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Month Selection -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-300">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-pink-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        選擇月份
                    </h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($monthOptions as $month)
                        <button type="button"
                            wire:click="selectMonth('{{ $month['value'] }}')"
                            class="p-3 rounded-lg border-2 text-center transition-all duration-200 hover:border-pink-300 hover:bg-pink-50 {{ $selectedMonth === $month['value'] ? 'border-pink-500 bg-pink-50 text-pink-700' : 'border-gray-200 text-gray-700' }}">
                            <div class="font-medium">{{ $month['label'] }}</div>
                            @if($month['is_current'])
                            <div class="text-xs text-pink-500 mt-1">本月</div>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Date Selection -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-300">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        可預約日期
                        <span class="text-sm font-normal text-gray-500 ml-2">({{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('Y年m月') }})</span>
                    </h2>

                    @if(count($availableDates) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                        @foreach($availableDates as $date)
                        <button type="button"
                            wire:click="selectDate('{{ $date['date'] }}')"
                            class="relative p-4 rounded-lg border-2 text-center transition-all duration-200 hover:border-purple-300 hover:bg-purple-50 {{ $selectedDate === $date['date'] ? 'border-purple-500 bg-purple-50 text-purple-700' : 'border-gray-200 text-gray-700' }} {{ $date['is_weekend'] ? 'bg-blue-50' : '' }}">
                            <div class="font-bold text-lg">{{ $date['day'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $date['day_of_week'] }}</div>
                            <div class="text-xs text-green-600 mt-1">{{ $date['available_count'] }}個時段</div>
                            @if($date['is_today'])
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></div>
                            @endif
                        </button>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">該月份暫無可預約日期</h3>
                        <p class="mt-1 text-sm text-gray-500">請選擇其他月份</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Time Slots -->
            <div class="space-y-6">
                @if($selectedDate && count($availableTimes) > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        可預約時段
                    </h2>
                    
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="text-sm text-green-800">
                            <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('Y年m月d日') }}</strong>
                            <span class="ml-2">{{ \Carbon\Carbon::parse($selectedDate)->locale('zh_TW')->dayName }}</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        @foreach($availableTimes as $time)
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-between hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium text-gray-900">{{ $time['time'] }}</span>
                            </div>
                            
                            @auth
                                @if($canBookThisMonth)
                                    <!-- 🔑 登入用戶可直接點擊預約 -->
                                    <button wire:click="selectTimeSlot({{ $time['id'] }})"
                                            class="px-3 py-1 bg-gradient-to-r from-pink-500 to-purple-600 text-white text-xs font-medium rounded-full hover:from-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                                        立即預約
                                    </button>
                                @else
                                    <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded-full">已達上限</span>
                                @endif
                            @else
                                <!-- 未登入用戶顯示可預約狀態 -->
                                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">可預約</span>
                            @endauth
                        </div>
                        @endforeach
                    </div>

                    <!-- Action Button - 根據登入狀態顯示不同內容 -->
                    @guest
                    <div class="mt-6 p-4 bg-gradient-to-r from-pink-50 to-purple-50 border border-pink-200 rounded-lg">
                        <div class="text-center">
                            <p class="text-sm text-gray-700 mb-3">想要預約這些時段嗎？</p>
                            <a href="{{ route('customer.login') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg text-sm font-medium transition-all duration-200 hover:from-pink-600 hover:to-purple-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                立即登入預約
                            </a>
                        </div>
                    </div>
                    @endguest
                </div>
                @elseif($selectedDate)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">可預約時段</h2>
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">該日期暫無可預約時段</h3>
                        <p class="mt-1 text-sm text-gray-500">請選擇其他日期</p>
                    </div>
                </div>
                @else
                <!-- Instructions -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-300">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        使用說明
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-white text-xs font-bold">1</div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700">選擇月份查看該月可預約日期</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-white text-xs font-bold">2</div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700">點選日期查看該日可預約時段</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-white text-xs font-bold">3</div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700">{{ Auth::check() ? '點擊時段上的「立即預約」按鈕' : '登入後即可點擊時段進行預約' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Service Info -->
                <div class="bg-gradient-to-br from-pink-50 to-purple-50 border border-pink-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">服務說明</h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-700">
                            <svg class="w-4 h-4 text-pink-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            需要提前三天預約
                        </div>
                        <div class="flex items-center text-sm text-gray-700">
                            <svg class="w-4 h-4 text-pink-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            每月最多預約三次
                        </div>
                        <div class="flex items-center text-sm text-gray-700">
                            <svg class="w-4 h-4 text-pink-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            提供單色與造型服務
                        </div>
                        <div class="flex items-center text-sm text-gray-700">
                            <svg class="w-4 h-4 text-pink-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            預約需管理員審核
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔑 預約彈窗 Modal -->
    @if($showBookingModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
            <!-- Modal Header - 固定不動 -->
            <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white p-6 rounded-t-xl flex-shrink-0">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold">預約資訊填寫</h3>
                    <button wire:click="closeBookingModal" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                @if($selectedTimeDetails)
                <div class="mt-3 p-3 bg-white/20 rounded-lg">
                    <div class="text-sm">
                        <strong>預約時間：</strong>{{ $selectedTimeDetails['formatted'] }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Modal Body - 可滾動區域 -->
            <div class="flex-1 overflow-y-auto">
                <form wire:submit.prevent="submitBooking" id="booking-form" class="p-6">
                <div class="space-y-6">
                    <!-- 個人資訊 -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                            <h4 class="text-lg font-medium text-gray-900">個人資訊</h4>
                            <a href="{{ route('profile.edit') }}" 
                               class="text-sm text-pink-600 hover:text-pink-700 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                修改資料
                            </a>
                        </div>

                        <!-- 提示訊息 -->
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                個人資訊將自動帶入您的帳戶資料，如需修改請至個人資料頁面
                            </p>
                        </div>

                        <!-- 第一行：姓名 + LINE名稱 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">姓名 *</label>
                                <input type="text" 
                                    value="{{ $customer_name }}"
                                    readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">LINE名稱 *</label>
                                <input type="text" 
                                    value="{{ $customer_line_name }}"
                                    readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                            </div>
                        </div>

                        <!-- 第二行：LINE ID + 電話 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    LINE ID 
                                    <span class="text-gray-500 font-normal">（選填，建議填寫）</span>
                                </label>
                                <input type="text" 
                                    wire:model="customer_line_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                    placeholder="例：john123（供客服聯繫用）">
                                @error('customer_line_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">建議填寫 LINE ID 以便客服聯繫</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">電話 *</label>
                                <input type="tel" 
                                    value="{{ $customer_phone }}"
                                    readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    <!-- 服務需求 -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">服務需求</h4>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="need_removal"
                                    class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">需要卸甲服務</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">款式選擇 *</label>
                            <div class="grid grid-cols-2 gap-4">
                                <!-- 單色選項 -->
                                <label class="relative cursor-pointer">
                                    <input type="radio"
                                        wire:model.live="style_type"
                                        value="single_color"
                                        name="style_type"
                                        class="sr-only">
                                    <div class="p-4 border-2 rounded-lg transition-all duration-200 {{ $style_type === 'single_color' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300' }}">
                                        <div class="text-center">
                                            <div class="font-medium text-gray-900">單色</div>
                                            <div class="text-sm text-gray-500 mt-1">簡約純色設計</div>
                                        </div>
                                    </div>
                                </label>

                                <!-- 造型選項 -->
                                <label class="relative cursor-pointer">
                                    <input type="radio"
                                        wire:model.live="style_type"
                                        value="design"
                                        name="style_type"
                                        class="sr-only">
                                    <div class="p-4 border-2 rounded-lg transition-all duration-200 {{ $style_type === 'design' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300' }}">
                                        <div class="text-center">
                                            <div class="font-medium text-gray-900">造型</div>
                                            <div class="text-sm text-gray-500 mt-1">創意圖案設計</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('style_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div x-data="{ count: $refs.textarea ? $refs.textarea.value.length : 0 }">
                            <label class="block text-sm font-medium text-gray-700 mb-1">備註</label>
                            <textarea
                                wire:model="notes"
                                x-ref="textarea"
                                maxlength="100"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                placeholder="請描述您想要的款式、顏色或其他特殊需求..."
                                @input="count = $refs.textarea.value.length"></textarea>
                            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            <div class="text-sm text-gray-500 mt-1">
                                <span x-text="count"></span>/100 字元
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>

            <!-- Modal Footer - 固定在底部 -->
            <div class="flex items-center justify-end space-x-4 p-6 border-t border-gray-200 bg-white rounded-b-xl flex-shrink-0">
                <button type="button" wire:click="closeBookingModal"
                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                    取消
                </button>
                <button type="submit" form="booking-form"
                    class="px-6 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:from-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                    確認預約
                </button>
            </div>
        </div>
    </div>
    @endif
</div>