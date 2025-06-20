<div>
    <!-- 預約檢視模態窗 -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- 背景遮罩 -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true"></div>

        <!-- 模態窗內容 -->
        <div class="relative bg-white rounded-xl shadow-2xl max-w-6xl w-full max-h-[90vh] flex flex-col">
                
                @if($booking)
                <!-- 模態窗標題 - 固定不滾動 -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-t-xl flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold">預約詳細資訊</h3>
                            <p class="text-blue-100 text-sm mt-1">預約編號：{{ $booking->booking_number }}</p>
                        </div>
                        <button wire:click="close" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- 模態窗內容 - 可滾動區域 -->
                <div class="flex-1 overflow-y-auto">
                    <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- 左側：預約資訊 -->
                        <div class="space-y-6">
                            <!-- 基本資訊 -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    基本資訊
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">預約時間：</span>
                                        <span class="font-medium">{{ $booking->booking_time->format('Y年m月d日 H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">申請時間：</span>
                                        <span class="font-medium">{{ $booking->created_at->format('Y年m月d日 H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">預約狀態：</span>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            bg-{{ $this->statusColor }}-100 text-{{ $this->statusColor }}-800">
                                            {{ $this->statusText }}
                                        </span>
                                    </div>
                                    @if($booking->amount)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">服務金額：</span>
                                        <span class="font-bold text-green-600">NT$ {{ number_format($booking->amount) }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- 服務內容 -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                                    </svg>
                                    服務內容
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">服務類型：</span>
                                        <span class="font-medium">{{ $booking->style_type_text }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">卸甲服務：</span>
                                        <span class="font-medium {{ $booking->need_removal ? 'text-orange-600' : 'text-gray-500' }}">
                                            {{ $booking->need_removal ? '需要卸甲' : '不需卸甲' }}
                                        </span>
                                    </div>
                                    @if($booking->notes)
                                    <div>
                                        <span class="text-gray-600 block mb-1">備註：</span>
                                        <div class="bg-white rounded p-3 text-sm">{{ $booking->notes }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- 客戶資訊 -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    客戶資訊
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">姓名：</span>
                                        <span class="font-medium">{{ $booking->customer_name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">電話：</span>
                                        <span class="font-medium">{{ $booking->customer_phone }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">LINE 名稱：</span>
                                        <span class="font-medium">{{ $booking->customer_line_name }}</span>
                                    </div>
                                    @if($booking->customer_line_id)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">LINE ID：</span>
                                        <span class="font-medium">{{ $booking->customer_line_id }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- 特殊標記 -->
                            @if($booking->created_by_admin)
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-purple-800 font-medium">管理員代為預約</span>
                                </div>
                                <p class="text-purple-700 text-sm mt-1">此預約由管理員建立，不計入您的月預約限制</p>
                            </div>
                            @endif

                            <!-- 取消資訊 -->
                            @if($booking->cancellation_requested && $booking->status === 'approved')
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <h5 class="font-medium text-orange-800 mb-2">取消申請資訊</h5>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-orange-600">申請時間：</span>
                                        <span>{{ $booking->cancellation_requested_at->format('Y/m/d H:i') }}</span>
                                    </div>
                                    @if($booking->cancellation_reason)
                                    <div>
                                        <span class="text-orange-600 block">取消原因：</span>
                                        <div class="bg-white rounded p-2 mt-1">{{ $booking->cancellation_reason }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- 右側：圖片展示 -->
                        <div class="space-y-4">
                            @if($booking->images && $booking->images->count() > 0)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    完成作品 ({{ $booking->images->count() }} 張)
                                </h4>
                                
                                <!-- 主圖片 -->
                                <div class="relative mb-4">
                                    <div class="aspect-w-4 aspect-h-3 bg-gray-200 rounded-lg overflow-hidden cursor-pointer group"
                                         wire:click="openImageViewer">
                                        <img src="{{ asset('storage/' . $booking->images[$selectedImageIndex]->image_path) }}" 
                                             alt="完成作品 {{ $selectedImageIndex + 1 }}"
                                             class="w-full h-64 object-cover group-hover:opacity-90 transition-opacity">
                                        
                                        <!-- 放大圖標 -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- 圖片導航按鈕 -->
                                    @if($booking->images->count() > 1)
                                    <button wire:click="previousImage" 
                                            class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-75 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>
                                    <button wire:click="nextImage" 
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-75 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                    @endif
                                    
                                    <!-- 圖片計數 -->
                                    <div class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                        {{ $selectedImageIndex + 1 }} / {{ $booking->images->count() }}
                                    </div>
                                </div>

                                <!-- 縮圖列表 -->
                                @if($booking->images->count() > 1)
                                <div class="flex space-x-2 overflow-x-auto pb-2">
                                    @foreach($booking->images as $index => $image)
                                    <div class="relative group">
                                        <button wire:click="selectImage({{ $index }})"
                                                class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all
                                                    {{ $selectedImageIndex === $index ? 'border-blue-500' : 'border-gray-300 hover:border-gray-400' }}">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                 alt="縮圖 {{ $index + 1 }}"
                                                 class="w-full h-full object-cover">
                                        </button>
                                        
                                        <!-- 縮圖放大按鈕 -->
                                        <button wire:click="selectImageAndView({{ $index }})"
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-200 flex items-center justify-center rounded-lg">
                                            <svg class="w-4 h-4 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                            </svg>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @else
                            <!-- 無圖片狀態 -->
                            <div class="bg-gray-50 rounded-lg p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">暫無完成作品</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    @if($booking->status === 'completed')
                                        管理員尚未上傳完成作品圖片
                                    @else
                                        預約完成後，管理員會上傳完成作品
                                    @endif
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                    </div>
                </div>

                <!-- 模態窗底部 - 固定不滾動 -->
                <div class="bg-white px-6 py-4 flex justify-end flex-shrink-0 border-t border-gray-200 rounded-b-xl">
                    <button wire:click="close" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        關閉
                    </button>
                </div>
                @endif
        </div>
    </div>
    @endif

    <!-- 圖片放大檢視模態窗 -->
    @if($showImageViewer && $booking && $booking->images->count() > 0)
    <div class="fixed inset-0 z-[60] bg-black bg-opacity-90 flex items-center justify-center p-4"
         wire:click="closeImageViewer">
        <div class="relative max-w-5xl max-h-full">
            <!-- 關閉按鈕 -->
            <button wire:click.stop="closeImageViewer"
                    class="absolute top-4 right-4 z-10 bg-black bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-70 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- 上一張按鈕 -->
            @if($selectedImageIndex > 0)
            <button wire:click.stop="previousImage"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-black bg-opacity-50 text-white rounded-full w-12 h-12 flex items-center justify-center hover:bg-opacity-70 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            @endif

            <!-- 下一張按鈕 -->
            @if($selectedImageIndex < $booking->images->count() - 1)
            <button wire:click.stop="nextImage"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-black bg-opacity-50 text-white rounded-full w-12 h-12 flex items-center justify-center hover:bg-opacity-70 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            @endif

            <!-- 放大的圖片 -->
            <img src="{{ asset('storage/' . $booking->images[$selectedImageIndex]->image_path) }}" 
                 alt="完成作品 {{ $selectedImageIndex + 1 }}"
                 class="max-w-full max-h-full object-contain rounded-lg"
                 wire:click.stop>

            <!-- 圖片資訊 -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full text-sm">
                {{ $selectedImageIndex + 1 }} / {{ $booking->images->count() }}
            </div>

            <!-- 縮圖導航 (如果有多張圖片) -->
            @if($booking->images->count() > 1)
            <div class="absolute bottom-16 left-1/2 transform -translate-x-1/2 flex space-x-2 bg-black bg-opacity-30 rounded-lg p-2">
                @foreach($booking->images as $index => $image)
                <button wire:click.stop="selectImage({{ $index }})"
                        class="w-12 h-12 rounded overflow-hidden border-2 transition-all
                            {{ $selectedImageIndex === $index ? 'border-white' : 'border-gray-400 hover:border-gray-200' }}">
                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                         alt="縮圖 {{ $index + 1 }}"
                         class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
