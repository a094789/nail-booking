<div class="min-h-screen bg-gray-50 py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- 頁面標題 -->
        <div class="mb-6 sm:mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.343 12.343l1.414 1.414L8 11.414l2.243 2.243 1.414-1.414L9.414 10l2.243-2.243-1.414-1.414L8 8.586 5.757 6.343 4.343 7.757 6.586 10 4.343 12.343z"/>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">LINE 通知設定</h1>
            </div>
            <p class="text-sm sm:text-base text-gray-600">控制系統是否發送 LINE 通知訊息</p>
        </div>

        <!-- 主要設定卡片 -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <!-- 卡片標題區域 -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div class="flex items-center space-x-3">
                        <!-- 狀態指示器 -->
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full {{ $notificationEnabled ? 'bg-green-500 animate-pulse' : 'bg-red-500' }} mr-3"></div>
                            <span class="text-lg font-semibold {{ $this->statusColor }}">{{ $this->statusText }}</span>
                        </div>
                    </div>
                    
                    <!-- 狀態徽章 -->
                    <div class="flex">
                        @if($notificationEnabled)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                通知已啟用
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                通知已關閉
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 卡片內容 -->
            <div class="p-4 sm:p-6">
                <!-- 功能說明 -->
                <div class="bg-blue-50 border-l-4 border-blue-400 rounded-r-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium mb-2">功能說明：</p>
                            <ul class="space-y-1.5 text-blue-600">
                                <li class="flex items-start">
                                    <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                    <span>關閉時：系統不會發送任何 LINE 通知（適合測試環境）</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                    <span>啟用時：正常發送預約相關的 LINE 通知給用戶</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                    <span>設定會立即生效，影響所有後續的通知發送</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 控制區域 -->
                <div class="space-y-6">
                    <!-- 當前狀態顯示 -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            @if($notificationEnabled)
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-base font-medium text-green-700">LINE 通知功能已啟用</p>
                                    <p class="text-sm text-green-600">系統正常發送通知訊息</p>
                                </div>
                            @else
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-base font-medium text-red-700">LINE 通知功能已關閉</p>
                                    <p class="text-sm text-red-600">系統不會發送任何通知訊息</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- 切換按鈕 -->
                        <button wire:click="toggleNotification" 
                                class="w-full sm:w-auto px-6 py-3 {{ $this->buttonColor }} text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $notificationEnabled ? 'focus:ring-red-500' : 'focus:ring-green-500' }}">
                            <span class="flex items-center justify-center space-x-2">
                                @if($notificationEnabled)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 21l-5-5 5-5-5-5 5-5-5-5m0 0L21 3l-5 5 5 5-5 5 5 5-5 5" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.343 12.343l1.414 1.414L8 11.414l2.243 2.243 1.414-1.414L9.414 10l2.243-2.243-1.414-1.414L8 8.586 5.757 6.343 4.343 7.757 6.586 10 4.343 12.343z" />
                                    </svg>
                                @endif
                                <span>{{ $this->buttonText }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 注意事項卡片 -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-4 sm:px-6 py-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">重要注意事項</h3>
                </div>
            </div>
            
            <div class="p-4 sm:p-6">
                <div class="grid gap-4 sm:gap-6">
                    <!-- 注意事項列表 -->
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-sm sm:text-base text-gray-700">此設定僅影響 LINE 通知的發送，不影響其他系統功能</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-sm sm:text-base text-gray-700">關閉通知後，用戶將不會收到預約確認、取消等相關訊息</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-sm sm:text-base text-gray-700">建議在生產環境中保持啟用狀態</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-sm sm:text-base text-gray-700">測試環境可以關閉通知，避免發送不必要的訊息給真實用戶</p>
                        </div>
                    </div>

                    <!-- 相關功能提示 -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4">
                            <div class="flex items-center space-x-3 mb-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h4 class="text-sm font-medium text-blue-900">相關設定</h4>
                            </div>
                            <p class="text-sm text-blue-700 mb-3">如需更詳細的 LINE 設定，請參考：</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    config/line.php
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    .env 環境變數
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 返回按鈕 -->
        <div class="mt-6 sm:mt-8 flex justify-center sm:justify-start">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                返回管理後台
            </a>
        </div>
    </div>
</div> 