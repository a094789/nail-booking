<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50">
    <!-- 桌面版本 -->
    <div class="hidden md:flex items-center justify-center p-8 min-h-screen">
        <div class="w-full max-w-4xl mx-auto grid grid-cols-2 gap-12 items-center">
            <!-- 左側：LINE 官方帳號資訊 -->
            <div class="space-y-8">
                <div>
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-green-500 to-blue-600 rounded-full mb-6">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">加入官方 LINE</h1>
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-green-500 to-blue-600 bg-clip-text text-transparent mb-6">接收預約通知</h2>
                    <p class="text-xl text-gray-600 leading-relaxed">為了讓您及時收到預約相關通知，請加入我們的官方 LINE 帳號</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-gray-700">預約審核結果通知</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-700">行前確認提醒</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-gray-700">服務完成通知</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-700">重要訊息推播</span>
                    </div>
                </div>
            </div>

            <!-- 右側：加入方式 -->
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">加入方式</h3>
                    <p class="text-gray-600">選擇以下任一方式加入官方 LINE</p>
                </div>

                <!-- 方式一：QR Code -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-full text-sm font-semibold mr-3">1</span>
                        <h4 class="text-lg font-medium text-gray-900">掃描 QR Code</h4>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-6 text-center">
                        <div class="w-48 h-48 bg-white rounded-2xl shadow-lg mx-auto mb-4 flex items-center justify-center overflow-hidden">
                            <!-- 真實的 QR Code -->
                            <img src="{{ $lineQrCodeUrl }}" 
                                 alt="LINE 官方帳號 QR Code" 
                                 class="w-44 h-44 object-contain">
                        </div>
                        <p class="text-sm text-gray-600">使用 LINE App 掃描上方 QR Code</p>
                    </div>
                </div>

                <!-- 方式二：官方帳號 ID -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mr-3">2</span>
                        <h4 class="text-lg font-medium text-gray-900">搜尋官方帳號 ID</h4>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <div class="flex items-center justify-between bg-white rounded-lg p-4 border-2 border-dashed border-gray-300">
                            <code class="text-lg font-mono text-gray-800">{{ $lineOfficialId }}</code>
                            <button 
                                onclick="navigator.clipboard.writeText('{{ $lineOfficialId }}')"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                複製
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-3">在 LINE 中搜尋上方 ID 並加為好友</p>
                    </div>
                </div>

                <!-- 方式三：直接加好友 -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-full text-sm font-semibold mr-3">3</span>
                        <h4 class="text-lg font-medium text-gray-900">直接加好友</h4>
                    </div>
                    <a href="{{ $lineAddUrl }}" 
                       target="_blank"
                       class="block w-full py-4 px-6 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl text-center font-medium hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <span class="flex items-center justify-center">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                            </svg>
                            點擊加入 LINE 官方帳號
                        </span>
                    </a>
                </div>

                <!-- 操作按鈕 -->
                <div class="border-t border-gray-200 pt-6 space-y-3">
                    @auth
                    <button
                        wire:click="continueToProfile"
                        type="button"
                        class="w-full py-3 px-6 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl font-medium hover:from-pink-600 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        我已加入，繼續使用系統
                    </button>
                    
                    <button
                        wire:click="skipLineJoin"
                        type="button"
                        class="w-full py-3 px-6 bg-gray-100 text-gray-600 rounded-xl font-medium hover:bg-gray-200 transition-all duration-200">
                        暫時跳過
                    </button>
                    @else
                    <a href="{{ route('customer.login') }}"
                       class="block w-full py-3 px-6 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl font-medium hover:from-pink-600 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl text-center">
                        登入開始預約
                    </a>
                    
                    <a href="{{ route('booking.create') }}"
                       class="block w-full py-3 px-6 bg-gray-100 text-gray-600 rounded-xl font-medium hover:bg-gray-200 transition-all duration-200 text-center">
                        查看可預約時段
                    </a>
                    @endauth
                </div>

                <p class="text-xs text-gray-500 text-center mt-4">
                    💡 建議加入官方 LINE 以獲得最佳使用體驗
                </p>
            </div>
        </div>
    </div>

    <!-- 手機版本 -->
    <div class="md:hidden flex items-center justify-center p-4 min-h-screen">
        <div class="w-full max-w-md mx-auto">
            <!-- 手機版標題 -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-green-500 to-blue-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">加入官方 LINE</h1>
                <p class="text-gray-600">接收預約通知</p>
            </div>

            <!-- 手機版主要卡片 -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6">
                    <!-- 通知類型說明 -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">您將收到的通知</h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="text-gray-700">審核結果</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <span class="text-gray-700">行前確認</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="text-gray-700">服務完成</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <span class="text-gray-700">重要訊息</span>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code 區域 -->
                    <div class="text-center mb-6">
                        <div class="w-40 h-40 bg-gray-100 rounded-2xl mx-auto mb-4 flex items-center justify-center overflow-hidden">
                            <!-- 真實的 QR Code -->
                            <img src="{{ $lineQrCodeUrl }}" 
                                 alt="LINE 官方帳號 QR Code" 
                                 class="w-36 h-36 object-contain">
                        </div>
                        <p class="text-sm text-gray-600 mb-4">掃描 QR Code 加好友</p>
                        
                        <!-- 官方帳號 ID -->
                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                            <p class="text-xs text-gray-500 mb-1">官方帳號 ID</p>
                            <div class="flex items-center justify-between">
                                <code class="text-sm font-mono text-gray-800">{{ $lineOfficialId }}</code>
                                <button 
                                    onclick="navigator.clipboard.writeText('{{ $lineOfficialId }}')"
                                    class="px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">
                                    複製
                                </button>
                            </div>
                        </div>

                        <!-- 直接加好友按鈕 -->
                        <a href="{{ $lineAddUrl }}" 
                           target="_blank"
                           class="block w-full py-3 px-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl text-center font-medium hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg mb-4">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                </svg>
                                點擊加入 LINE 官方帳號
                            </span>
                        </a>
                    </div>
                </div>

                <!-- 手機版操作按鈕 -->
                <div class="border-t border-gray-200 p-6 space-y-3">
                    @auth
                    <button
                        wire:click="continueToProfile"
                        type="button"
                        class="w-full py-3 px-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl font-medium hover:from-pink-600 hover:to-purple-700 transition-all duration-200 shadow-lg">
                        我已加入，繼續使用系統
                    </button>
                    
                    <button
                        wire:click="skipLineJoin"
                        type="button"
                        class="w-full py-3 px-4 bg-gray-100 text-gray-600 rounded-xl font-medium hover:bg-gray-200 transition-all duration-200">
                        暫時跳過
                    </button>
                    @else
                    <a href="{{ route('customer.login') }}"
                       class="block w-full py-3 px-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl font-medium hover:from-pink-600 hover:to-purple-700 transition-all duration-200 shadow-lg text-center">
                        登入開始預約
                    </a>
                    
                    <a href="{{ route('booking.create') }}"
                       class="block w-full py-3 px-4 bg-gray-100 text-gray-600 rounded-xl font-medium hover:bg-gray-200 transition-all duration-200 text-center">
                        查看可預約時段
                    </a>
                    @endauth
                </div>

                <p class="text-xs text-gray-500 text-center pb-4">
                    💡 建議加入官方 LINE 以獲得最佳使用體驗
                </p>
            </div>
        </div>
    </div>
</div> 