<div class="min-h-screen bg-gradient-to-br from-pink-50 via-white to-purple-50">
    <!-- 桌面版本 - 隱藏在 md 以下 -->
    <div class="hidden md:flex items-center justify-center p-8 min-h-screen">
        <div class="w-full max-w-4xl mx-auto grid grid-cols-2 gap-12 items-center">
            <!-- 左側：歡迎區域 -->
            <div class="space-y-8">
                <div>
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">歡迎使用</h1>
                    <h2 class="text-4xl font-bold bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent mb-6">美甲預約系統</h2>
                    <p class="text-xl text-gray-600 leading-relaxed">享受專業的美甲服務，輕鬆預約您的美麗時光</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-pink-500 rounded-full"></div>
                        <span class="text-gray-700">專業美甲服務</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <span class="text-gray-700">便捷線上預約</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-pink-500 rounded-full"></div>
                        <span class="text-gray-700">LINE 快速登入</span>
                    </div>
                </div>
            </div>

            <!-- 右側：條款區域 -->
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">使用條款與隱私權政策</h3>
                    <p class="text-gray-600">請先閱讀並同意以下條款</p>
                </div>

                <!-- 桌面版條款內容 -->
                <div class="max-h-80 overflow-y-auto space-y-6 mb-8 pr-2"
                    id="terms-content"
                    x-data="{ 
                         checkScroll() {
                             const element = this.$el;
                             const isScrolledToBottom = element.scrollHeight - element.scrollTop <= element.clientHeight + 5;
                             if (isScrolledToBottom) {
                                 @this.call('markScrolledToBottom');
                             }
                         }
                     }"
                    x-on:scroll="checkScroll()"
                    x-init="checkScroll()">

                    <!-- 服務說明 -->
                    <div>
                        <div class="flex items-center mb-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-pink-100 text-pink-600 rounded-full text-sm font-semibold mr-3">1</span>
                            <h4 class="text-lg font-medium text-gray-900">服務說明</h4>
                        </div>
                        <p class="text-gray-700 ml-11 leading-relaxed">本系統提供美甲預約服務，使用者可以透過本平台預約美甲服務。我們致力於提供優質的預約體驗。</p>
                    </div>

                    <!-- 預約規則 -->
                    <div>
                        <div class="flex items-center mb-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-full text-sm font-semibold mr-3">2</span>
                            <h4 class="text-lg font-medium text-gray-900">預約規則</h4>
                        </div>
                        <div class="ml-11 space-y-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-pink-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">需提前三天預約</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-pink-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">每月最多預約三次</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-pink-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">個人資料每三個月可修改一次</span>
                            </div>
                        </div>
                    </div>

                    <!-- 隱私權保護 -->
                    <div>
                        <div class="flex items-center mb-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-full text-sm font-semibold mr-3">3</span>
                            <h4 class="text-lg font-medium text-gray-900">隱私權保護</h4>
                        </div>
                        <p class="text-gray-700 ml-11 leading-relaxed">我們會保護您的個人資料，不會將您的資料提供給第三方。所有資料都經過加密處理，確保您的隱私安全。</p>
                    </div>

                    <!-- LINE 登入 -->
                    <div>
                        <div class="flex items-center mb-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full text-sm font-bold mr-3">LINE</span>
                            <h4 class="text-lg font-medium text-gray-900">LINE 登入說明</h4>
                        </div>
                        <p class="text-gray-700 ml-11 leading-relaxed">使用 LINE 登入時，我們會取得您的 LINE ID 和顯示名稱，僅用於帳號識別和通知服務。</p>
                    </div>

                    <!-- 預約及取消政策 -->
                    <div>
                        <div class="flex items-center mb-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-yellow-100 text-yellow-600 rounded-full text-sm font-semibold mr-3">5</span>
                            <h4 class="text-lg font-medium text-gray-900">預約及取消政策</h4>
                        </div>
                        <p class="text-gray-700 ml-11 leading-relaxed">預約及取消皆需要經過管理員審核，我們會透過 LINE 通知您審核結果。</p>
                    </div>

                    <!-- 費用說明 -->
                    <div>
                        <div class="flex items-center mb-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-600 rounded-full text-sm font-semibold mr-3">6</span>
                            <h4 class="text-lg font-medium text-gray-900">費用說明</h4>
                        </div>
                        <p class="text-gray-700 ml-11 leading-relaxed">服務費用依據所選服務項目而定，預約時會顯示詳細費用，付款方式支援現金及網路銀行匯款。</p>
                    </div>

                    <!-- 滾動底部標記 -->
                    <div class="text-center py-4 text-sm text-gray-500 border-t border-gray-200">
                        ✅ 您已閱讀完所有條款內容，現在可以勾選同意
                    </div>
                </div>

                <!-- 桌面版同意區域 -->
                <div class="border-t border-gray-200 pt-6">
                    @if (session()->has('error'))
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 text-sm rounded-lg">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if (session()->has('success'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 text-sm rounded-lg flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-green-700 hover:text-green-800">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    @endif

                    <!-- 滾動狀態指示 -->
                    @if(!$hasScrolledToBottom)
                    <div class="mb-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 text-sm rounded">
                        📖 請先滾動閱讀完整的條款內容，才能勾選同意
                    </div>
                    @endif

                    <div class="flex items-start space-x-4 mb-6">
                        <div class="relative flex-shrink-0 mt-1">
                            <input
                                type="checkbox"
                                id="agree-desktop"
                                wire:model.live="agreed"
                                wire:change="$refresh"
                                {{ !$hasScrolledToBottom ? 'disabled' : '' }}
                                class="w-6 h-6 text-pink-600 bg-gray-100 border-gray-300 rounded-lg focus:ring-pink-500 focus:ring-2
                                       {{ !$hasScrolledToBottom ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
                        </div>
                        <label for="agree-desktop" class="text-base text-gray-700 leading-6 {{ !$hasScrolledToBottom ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
                            我已閱讀並同意上述<span class="font-medium text-pink-600">條款與隱私權政策</span>
                            @if(!$hasScrolledToBottom)
                            <span class="text-xs text-gray-500 block mt-1">請先完整閱讀條款內容</span>
                            @endif
                        </label>
                    </div>

                    <!-- 同意按鈕 -->
                    <button
                        wire:click="acceptTerms"
                        type="button"
                        class="w-full py-4 px-6 rounded-xl text-lg font-medium transition-all duration-200
                               {{ $agreed && $hasScrolledToBottom
                                  ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow-lg hover:shadow-xl cursor-pointer' 
                                  : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                        {{ !$agreed || !$hasScrolledToBottom ? 'disabled' : '' }}>
                        <span class="flex items-center justify-center">
                            @if($agreed && $hasScrolledToBottom)
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            @endif
                            同意並繼續
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 手機版本 - 顯示在 md 以下 -->
    <div class="md:hidden flex items-center justify-center p-4 min-h-screen">
        <div class="w-full max-w-md mx-auto">
            <!-- 手機版歡迎標題 -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">歡迎使用美甲預約系統</h1>
                <p class="text-gray-600">請先閱讀並同意以下條款</p>
            </div>

            <!-- 手機版主要卡片 -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- 手機版條款內容區域 -->
                <div class="relative">
                    @if($showScrollIndicator && !$hasScrolledToBottom)
                    <div class="absolute top-4 right-4 z-10 bg-pink-500 text-white text-xs px-3 py-2 rounded-full animate-bounce">
                        向下滾動
                    </div>
                    @endif

                    <div
                        class="max-h-80 overflow-y-auto p-6 text-sm leading-relaxed"
                        id="terms-content-mobile"
                        x-data="{ 
                            checkScroll() {
                                const element = this.$el;
                                const isScrolledToBottom = element.scrollHeight - element.scrollTop <= element.clientHeight + 5;
                                if (isScrolledToBottom) {
                                    @this.call('markScrolledToBottom');
                                }
                            }
                        }"
                        x-on:scroll="checkScroll()"
                        x-init="checkScroll()">
                        
                        <!-- 條款標題 -->
                        <div class="flex items-center mb-4">
                            <div class="w-1 h-6 bg-gradient-to-b from-pink-500 to-purple-600 rounded-full mr-3"></div>
                            <h3 class="text-lg font-semibold text-gray-900">使用條款與隱私權政策</h3>
                        </div>

                        <!-- 精簡版條款內容 -->
                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-pink-100 text-pink-600 rounded-full text-xs font-semibold mr-2">1</span>
                                    <h4 class="font-medium text-gray-900">服務說明</h4>
                                </div>
                                <p class="text-gray-700 ml-8 text-sm">本系統提供美甲預約服務，使用者可以透過本平台預約美甲服務。我們致力於提供優質的預約體驗。</p>
                            </div>

                            <div>
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-purple-100 text-purple-600 rounded-full text-xs font-semibold mr-2">2</span>
                                    <h4 class="font-medium text-gray-900">預約規則</h4>
                                </div>
                                <div class="ml-8 space-y-1 text-sm">
                                    <div class="flex items-center">
                                        <div class="w-1.5 h-1.5 bg-pink-500 rounded-full mr-2"></div>
                                        <span class="text-gray-700">需提前一天預約</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-1.5 h-1.5 bg-pink-500 rounded-full mr-2"></div>
                                        <span class="text-gray-700">每月最多預約三次</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-1.5 h-1.5 bg-pink-500 rounded-full mr-2"></div>
                                        <span class="text-gray-700">個人資料每三個月可修改一次</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-600 rounded-full text-xs font-semibold mr-2">3</span>
                                    <h4 class="font-medium text-gray-900">隱私權保護</h4>
                                </div>
                                <p class="text-gray-700 ml-8 text-sm">我們會保護您的個人資料，不會將您的資料提供給第三方。所有資料都經過加密處理，確保您的隱私安全。</p>
                            </div>

                            <div>
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-green-500 text-white rounded-full text-xs font-bold mr-2">LINE</span>
                                    <h4 class="font-medium text-gray-900">LINE 登入說明</h4>
                                </div>
                                <p class="text-gray-700 ml-8 text-sm">使用 LINE 登入時，我們會取得您的 LINE ID 和顯示名稱，僅用於帳號識別和通知服務。</p>
                            </div>

                            <div>
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full text-xs font-semibold mr-2">5</span>
                                    <h4 class="font-medium text-gray-900">預約及取消政策</h4>
                                </div>
                                <p class="text-gray-700 ml-8 text-sm">預約及取消皆需要經過管理員審核，我們會透過 LINE 通知您審核結果。</p>
                            </div>

                            <!-- 費用說明 -->
                            <div>
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-orange-100 text-orange-600 rounded-full text-xs font-semibold mr-2">6</span>
                                    <h4 class="font-medium text-gray-900">費用說明</h4>
                                </div>
                                <p class="text-gray-700 ml-8 text-sm">服務費用依據所選服務項目而定，付款方式支援現金及網路銀行匯款。</p>
                            </div>

                            <!-- 滾動底部標記 -->
                            <div class="text-center py-4 text-sm text-gray-500 border-t border-gray-200">
                                ✅ 您已閱讀完所有條款內容，現在可以勾選同意
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 手機版同意區域 -->
                <div class="border-t border-gray-200 p-6">
                    @if (session()->has('error'))
                    <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-700 text-sm rounded">
                        {{ session('error') }}
                    </div>
                    @endif

                    <!-- 滾動狀態指示 -->
                    @if(!$hasScrolledToBottom)
                    <div class="mb-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 text-xs rounded">
                        📖 請先滾動閱讀完整的條款內容
                    </div>
                    @endif

                    <div class="flex items-start space-x-3 mb-6">
                        <div class="relative flex-shrink-0 mt-0.5">
                            <input
                                type="checkbox"
                                id="agree-mobile"
                                wire:model.live="agreed"
                                wire:change="$refresh"
                                {{ !$hasScrolledToBottom ? 'disabled' : '' }}
                                class="w-5 h-5 text-pink-600 bg-gray-100 border-gray-300 rounded focus:ring-pink-500 focus:ring-2
                                       {{ !$hasScrolledToBottom ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
                        </div>
                        <label for="agree-mobile" class="text-sm text-gray-700 leading-5 {{ !$hasScrolledToBottom ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
                            我已閱讀並同意上述<span class="font-medium text-pink-600">條款與隱私權政策</span>
                            @if(!$hasScrolledToBottom)
                                <span class="text-xs text-gray-500 block mt-1">請先完整閱讀條款內容</span>
                            @endif
                        </label>
                    </div>

                    <button
                        wire:click="acceptTerms"
                        type="button"
                        class="w-full py-3 px-4 rounded-xl font-medium transition-all duration-200 transform
                               {{ $agreed && $hasScrolledToBottom
                                  ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow-lg hover:shadow-xl hover:scale-105 active:scale-95' 
                                  : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                        {{ !$agreed || !$hasScrolledToBottom ? 'disabled' : '' }}>
                        <span class="flex items-center justify-center">
                            @if($agreed && $hasScrolledToBottom)
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            @endif
                            同意並繼續
                        </span>
                    </button>
                </div>
            </div>

            <!-- 手機版底部說明 -->
            <p class="text-center text-xs text-gray-500 mt-6">
                如有任何問題，請聯繫客服人員
            </p>
        </div>
    </div>
</div>