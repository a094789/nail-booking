{{-- resources/views/livewire/admin/admin-booking-actions.blade.php --}}

<div>
    <!-- 快速操作按鈕組 -->
    <div class="flex items-center gap-2 flex-wrap">
        <!-- 檢視按鈕 -->
        <button wire:click="showDetails"
            class="group inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md text-sm font-medium">
            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            檢視
        </button>

        <!-- 編輯按鈕 -->
        <button wire:click="showEdit"
            class="group inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:border-purple-300 hover:bg-purple-50 hover:text-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md text-sm font-medium">
            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            編輯
        </button>

        <!-- 快速批准按鈕（僅待審核狀態顯示） -->
        @if($booking->status === 'pending')
        <button wire:click="quickApprove"
            wire:loading.attr="disabled"
            wire:target="quickApprove"
            class="group inline-flex items-center px-3 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-lg hover:from-emerald-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none text-sm font-medium">
            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span wire:loading.remove wire:target="quickApprove">批准</span>
            <span wire:loading wire:target="quickApprove" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                處理中...
            </span>
        </button>
        @endif

        <!-- 處理取消申請按鈕（僅有取消申請時顯示） -->
        @if($booking->cancellation_requested && $booking->status === 'approved')
        <button wire:click="showCancellation"
            class="group inline-flex items-center px-3 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-lg hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg text-sm font-medium">
            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            處理取消
        </button>
        @endif
    </div>

    <!-- 預約詳情模態窗 -->
    @if($showDetailsModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4" 
         x-data 
         x-show="true" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] flex flex-col overflow-hidden"
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform scale-95" 
             x-transition:enter-end="opacity-100 transform scale-100">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white bg-opacity-20 rounded-full p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">預約詳情</h3>
                            <p class="text-indigo-100 text-sm">{{ $booking->booking_number }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" 
                            class="text-white hover:text-gray-200 transition-colors rounded-full p-2 hover:bg-white hover:bg-opacity-20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-1 bg-gray-50">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- 預約資訊卡片 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-indigo-100 rounded-lg p-2 mr-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v9a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h3z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900">預約資訊</h4>
                        </div>
                        <dl class="space-y-4">
                            <div class="flex justify-between items-start py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-500">預約時間</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $booking->booking_time->format('Y年m月d日 H:i') }}</dd>
                            </div>
                            <div class="flex justify-between items-start py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-500">服務類型</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $booking->style_type_text }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between items-start py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-500">卸甲需求</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->need_removal ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $booking->need_removal ? '需要' : '不需要' }}
                                    </span>
                                </dd>
                            </div>
                            @if($booking->notes)
                            <div class="py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-500 mb-2">備註</dt>
                                <dd class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $booking->notes }}</dd>
                            </div>
                            @endif
                            @if($booking->amount)
                            <div class="flex justify-between items-start py-2">
                                <dt class="text-sm font-medium text-gray-500">服務金額</dt>
                                <dd class="text-lg text-emerald-600 font-bold">NT$ {{ number_format($booking->amount) }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- 客戶資訊卡片 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-pink-100 rounded-lg p-2 mr-3">
                                <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900">客戶資訊</h4>
                        </div>
                        <dl class="space-y-4">
                            <div class="flex justify-between items-start py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-500">姓名</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $booking->customer_name }}</dd>
                            </div>
                            <div class="flex justify-between items-start py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-500">LINE名稱</dt>
                                <dd class="text-sm text-gray-900">{{ $booking->customer_line_name }}</dd>
                            </div>
                            <div class="flex justify-between items-start py-2">
                                <dt class="text-sm font-medium text-gray-500">LINE ID</dt>
                                <dd class="text-sm text-gray-900">{{ $booking->customer_line_id }}</dd>
                            </div>
                            
                            <div class="flex justify-between items-start py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-500">電話</dt>
                                <dd class="text-sm text-gray-900">{{ $booking->customer_phone }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- 快速操作區域 -->
                @if(in_array($booking->status, ['pending', 'approved']) || $booking->cancellation_requested)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-emerald-100 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">快速操作</h4>
                    </div>
                    
                    @if($booking->status === 'pending')
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">服務金額（選填）</label>
                        <input type="number"
                            wire:model="amount"
                            placeholder="請輸入服務金額"
                            step="0.01"
                            min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                    </div>
                    @endif
                    
                    <div class="flex flex-wrap gap-3">
                        @if($booking->status === 'pending')
                        <button wire:click="confirmApproval"
                            wire:loading.attr="disabled"
                            wire:target="confirmApproval"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed font-medium">
                            <span wire:loading.remove wire:target="confirmApproval">快速批准</span>
                            <span wire:loading wire:target="confirmApproval" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                處理中...
                            </span>
                        </button>
                        @endif

                        <button wire:click="showEdit"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors font-medium">
                            編輯預約
                        </button>
                    </div>
                </div>
                @endif

                <!-- 完成作品區域 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">完成作品</h4>
                    </div>
                    
                    @if($booking->images->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($booking->images as $image)
                        <div class="relative group">
                            <img src="{{ $image->url }}" alt="完成圖片"
                                class="w-full h-24 object-cover rounded-xl border-2 border-gray-200 cursor-pointer hover:border-purple-300 transition-all duration-200 shadow-sm hover:shadow-md"
                                wire:click="openImageViewer({{ $image->id }})">
                            <div class="absolute top-2 left-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded-full font-medium">
                                {{ $loop->iteration }}
                            </div>
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-2 text-gray-500 text-sm">尚未上傳完成作品照片</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 編輯預約模態窗 -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] flex flex-col overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white bg-opacity-20 rounded-full p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">編輯預約</h3>
                            <p class="text-purple-100 text-sm">{{ $booking->booking_number }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" 
                            class="text-white hover:text-gray-200 transition-colors rounded-full p-2 hover:bg-white hover:bg-opacity-20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-1 bg-gray-50">
                <form wire:submit.prevent="updateBooking">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- 預約狀態與資訊 -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                <div class="bg-indigo-100 rounded-lg p-2 mr-3">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                預約狀態與資訊
                            </h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">預約狀態</label>
                                    <select wire:model="edit_status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                        <option value="pending">待審核</option>
                                        <option value="approved">預約成功</option>
                                        <option value="cancelled">已取消</option>
                                        <option value="completed">已完成</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">預約時間</label>
                                    <input type="datetime-local" wire:model="edit_booking_time"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">服務類型</label>
                                    <select wire:model="edit_style_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                        <option value="single_color">單色</option>
                                        <option value="design">造型</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <input type="checkbox" wire:model="edit_need_removal" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 mr-3">
                                        <span class="text-sm text-gray-700 font-medium">需要卸甲</span>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">服務金額</label>
                                    <input type="number" wire:model="edit_amount" step="0.01" min="0"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- 客戶資訊 -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                <div class="bg-pink-100 rounded-lg p-2 mr-3">
                                    <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                客戶資訊
                            </h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">姓名</label>
                                    <input type="text" wire:model="edit_customer_name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">LINE名稱</label>
                                    <input type="text" wire:model="edit_customer_line_name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">LINE ID</label>
                                    <input type="text" wire:model="edit_customer_line_id"
                                        placeholder="例：@john123"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">電話</label>
                                    <input type="text" wire:model="edit_customer_phone"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">備註</label>
                                    <textarea wire:model="edit_notes" rows="4"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 按鈕區域 -->
                    <div class="flex justify-end space-x-3 pt-6 mt-6">
                        <button type="button" wire:click="closeModal"
                            class="inline-flex items-center px-6 py-3 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                            取消
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            儲存變更
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- 處理取消申請模態窗 - 參考 booking-view 結構 -->
    @if($showCancellationModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
            
            <!-- Modal Header - 固定不滾動 -->
            <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white p-4 rounded-t-xl flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold">處理取消申請</h3>
                        <p class="text-orange-100 text-sm">預約編號：{{ $booking->booking_number }}</p>
                    </div>
                    <button wire:click="closeModal" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body - 可滾動區域 -->
            <div class="p-6 overflow-y-auto flex-1">
                <!-- 取消申請資訊 -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-orange-800 mb-3">申請資訊</h4>
                    <div class="space-y-2 text-sm text-orange-700">
                        <div class="flex justify-between">
                            <span class="font-medium">申請時間：</span>
                            <span>{{ $booking->cancellation_requested_at->format('Y/m/d H:i') }}</span>
                        </div>
                        @if($booking->cancellation_reason)
                        <div>
                            <span class="font-medium">取消原因：</span>
                            <div class="mt-1 bg-orange-100 p-2 rounded text-orange-900">
                                {{ $booking->cancellation_reason }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 處理選項 -->
                <div class="space-y-4">
                    <!-- 批准取消 -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h5 class="font-semibold text-red-800 mb-2">批准取消申請</h5>
                        <p class="text-sm text-red-600 mb-3">預約將被永久取消，此操作無法撤銷</p>
                        <button wire:click="approveCancellation"
                            wire:loading.attr="disabled"
                            wire:target="approveCancellation"
                            class="w-full px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="approveCancellation">批准取消</span>
                            <span wire:loading wire:target="approveCancellation">處理中...</span>
                        </button>
                    </div>

                    <!-- 拒絕申請 -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-800 mb-2">拒絕取消申請</h5>
                        <p class="text-sm text-gray-600 mb-3">預約將維持原狀態，請填寫拒絕原因</p>
                        <div class="space-y-3">
                            <textarea wire:model="rejection_reason" 
                                      rows="3"
                                      placeholder="請填寫拒絕原因，將發送給客戶..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm resize-none"></textarea>
                            @error('rejection_reason')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer - 固定不滾動 -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 flex-shrink-0 border-t border-gray-200 rounded-b-xl">
                <button wire:click="closeModal"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    取消
                </button>
                <button wire:click="rejectCancellation"
                        wire:loading.attr="disabled"
                        wire:target="rejectCancellation"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors disabled:opacity-50">
                    <span wire:loading.remove wire:target="rejectCancellation">拒絕申請</span>
                    <span wire:loading wire:target="rejectCancellation">處理中...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>