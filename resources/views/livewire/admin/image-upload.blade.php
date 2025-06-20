{{-- resources/views/livewire/admin/image-upload.blade.php - 完善版本 --}}

<div>
    <!-- 🔑 管理按鈕與統計資訊 -->
    @if($booking && $booking->status === 'completed')
    <div class="flex items-center justify-between">
        <button wire:click.prevent="openUploadModal"
                class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full hover:bg-blue-200 transition-colors">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            管理圖片
        </button>
        
        @if($existingImages && $existingImages->count() > 0)
        <div class="text-xs text-gray-500">
            {{ $imageStats['count'] }}/5 張圖片 • {{ $imageStats['total_size_mb'] }}MB
        </div>
        @endif
    </div>
    @endif

    <!-- 🔑 現有圖片預覽（增強版） -->
    @if($existingImages && $existingImages->count() > 0)
    <div class="mt-3">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
            @foreach($existingImages as $image)
            <div class="relative group">
                <!-- 圖片預覽 -->
                <img src="{{ $image->url }}" alt="完成圖片" 
                     wire:click="openImageViewer({{ $image->id }})"
                     class="w-full h-20 object-cover rounded border border-gray-200 cursor-pointer hover:opacity-75 transition-opacity">
                
                <!-- 圖片順序標籤 -->
                <div class="absolute top-1 left-1 bg-black bg-opacity-50 text-white text-xs px-1 py-0.5 rounded">
                    {{ $loop->iteration }}
                </div>
                
                <!-- Hover 控制按鈕 -->
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded flex items-center justify-center opacity-0 group-hover:opacity-100">
                    <div class="flex space-x-1">
                        <!-- 上移按鈕 -->
                        @if(!$loop->first)
                        <button wire:click="moveImageUp({{ $image->id }})"
                                class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-blue-600 transition-colors"
                                title="上移">
                            ↑
                        </button>
                        @endif
                        
                        <!-- 下移按鈕 -->
                        @if(!$loop->last)
                        <button wire:click="moveImageDown({{ $image->id }})"
                                class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-blue-600 transition-colors"
                                title="下移">
                            ↓
                        </button>
                        @endif
                        
                        <!-- 刪除按鈕 -->
                        <button wire:click="confirmDeleteImage({{ $image->id }})"
                                class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors"
                                title="刪除">
                            ×
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- 圖片統計與批量操作 -->
        <div class="flex items-center justify-between mt-2">
            <div class="text-xs text-gray-500">
                {{ $imageStats['count'] }}/5 張圖片，還可上傳 {{ $imageStats['remaining_slots'] }} 張
            </div>
            @if($existingImages->count() > 1)
            <button wire:click="deleteAllImages" 
                    onclick="return confirm('確定要刪除所有圖片嗎？此操作無法復原！')"
                    class="text-xs text-red-600 hover:text-red-800 transition-colors">
                刪除全部
            </button>
            @endif
        </div>
    </div>
    @endif

    <!-- 🔑 圖片查看器模態窗 -->
    @if($showImageViewer && $selectedImageForView)
    <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-60 p-4">
        <div class="relative max-w-4xl max-h-full">
            <!-- 關閉按鈕 -->
            <button wire:click="closeImageViewer" 
                    class="absolute top-4 right-4 bg-black bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75 transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <!-- 大圖顯示 -->
            <img src="{{ $selectedImageForView->url }}" alt="完成圖片" 
                 class="max-w-full max-h-full object-contain rounded-lg">
            
            <!-- 圖片資訊 -->
            <div class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg">
                <div class="text-sm">
                    圖片 {{ $existingImages->search(function($item) { return $item->id === $selectedImageForView->id; }) + 1 }} / {{ $existingImages->count() }}
                </div>
            </div>
            
            <!-- 操作按鈕 -->
            <div class="absolute bottom-4 right-4 flex space-x-2">
                <button wire:click="confirmDeleteImage({{ $selectedImageForView->id }})"
                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition-colors text-sm">
                    刪除
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- 🔑 刪除確認對話框 -->
    @if($showDeleteConfirm)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-60 p-4">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.98-.833-2.75 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900">確認刪除</h3>
            </div>
            <p class="text-gray-600 mb-6">確定要刪除這張圖片嗎？此操作無法復原。</p>
            <div class="flex justify-end space-x-3">
                <button wire:click="cancelDelete" 
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 transition-colors">
                    取消
                </button>
                <button wire:click="deleteImage" 
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                    確定刪除
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- 🔑 上傳模態窗（增強版） -->
    @if($showUploadModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold">圖片管理</h3>
                        @if($booking)
                        <div class="mt-1 text-sm opacity-90">
                            預約單號：{{ $booking->booking_number }}
                        </div>
                        @endif
                    </div>
                    <button wire:click="closeUploadModal" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- 上傳進度條 -->
                @if($isUploading)
                <div class="mt-4">
                    <div class="bg-white bg-opacity-20 rounded-full h-2">
                        <div class="bg-white rounded-full h-2 transition-all duration-300" style="width: {{ $uploadProgress }}%"></div>
                    </div>
                    <div class="text-sm mt-1 opacity-90">上傳進度：{{ $uploadProgress }}%</div>
                </div>
                @endif
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- 現有圖片管理 -->
                @if($existingImages && $existingImages->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-medium text-gray-900">
                            現有圖片 ({{ $existingImages->count() }}/5)
                        </h4>
                        <div class="text-sm text-gray-500">
                            總大小：{{ $imageStats['total_size_mb'] }}MB
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($existingImages as $image)
                        <div class="relative group">
                            <!-- 圖片預覽 -->
                            <img src="{{ $image->url }}" alt="完成圖片" 
                                 wire:click="openImageViewer({{ $image->id }})"
                                 class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-75 transition-opacity">
                            
                            <!-- 圖片順序 -->
                            <div class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                {{ $loop->iteration }}
                            </div>
                            
                            <!-- 控制按鈕 -->
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex space-x-1">
                                <!-- 移動按鈕 -->
                                @if(!$loop->first)
                                <button wire:click="moveImageUp({{ $image->id }})"
                                        class="bg-blue-500 text-white rounded w-6 h-6 flex items-center justify-center text-xs hover:bg-blue-600 transition-colors"
                                        title="上移">
                                    ↑
                                </button>
                                @endif
                                
                                @if(!$loop->last)
                                <button wire:click="moveImageDown({{ $image->id }})"
                                        class="bg-blue-500 text-white rounded w-6 h-6 flex items-center justify-center text-xs hover:bg-blue-600 transition-colors"
                                        title="下移">
                                    ↓
                                </button>
                                @endif
                                
                                <!-- 刪除按鈕 -->
                                <button wire:click="confirmDeleteImage({{ $image->id }})"
                                        class="bg-red-500 text-white rounded w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors"
                                        title="刪除">
                                    ×
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- 上傳新圖片 -->
                @if($imageStats['remaining_slots'] > 0)
                <div class="border-t border-gray-200 pt-8">
                    <form wire:submit.prevent="uploadImages">
                        <div class="mb-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">
                                上傳新圖片 (還可上傳 {{ $imageStats['remaining_slots'] }} 張)
                            </h4>
                            
                            <!-- 拖拽上傳區域 -->
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition-colors">
                                <input type="file" wire:model="images" multiple accept="image/*" 
                                       class="hidden" id="image-upload-{{ $booking->id ?? 'new' }}"
                                       {{ $isUploading ? 'disabled' : '' }}>
                                
                                <label for="image-upload-{{ $booking->id ?? 'new' }}" class="cursor-pointer {{ $isUploading ? 'pointer-events-none' : '' }}">
                                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="text-lg font-medium text-gray-900 mb-2">
                                        {{ $isUploading ? '上傳中...' : '點擊選擇圖片' }}
                                    </div>
                                    <div class="text-sm text-gray-600 mb-1">或拖拽圖片到這裡</div>
                                    <div class="text-xs text-gray-500">
                                        支援 JPG、PNG、GIF 格式，單張最大 2MB
                                    </div>
                                </label>
                            </div>

                            <!-- 上傳錯誤訊息 -->
                            @error('images.*') 
                            <div class="text-red-500 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div> 
                            @enderror
                            
                            <!-- 文件載入狀態 -->
                            <div wire:loading wire:target="images" class="mt-3 text-sm text-blue-600 flex items-center">
                                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                處理文件中...
                            </div>
                        </div>

                        <!-- 預覽選中的圖片 -->
                        @if($images && !$isUploading)
                        <div class="mb-6">
                            <h5 class="font-medium text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                待上傳圖片 ({{ count($images) }} 張)
                            </h5>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($images as $index => $image)
                                <div class="relative">
                                    <img src="{{ $image->temporaryUrl() }}" alt="預覽" 
                                         class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                    <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                        新 {{ $index + 1 }}
                                    </div>
                                    <!-- 圖片大小資訊 -->
                                    <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xs px-1 py-0.5 rounded">
                                        {{ round($image->getSize() / 1024 / 1024, 1) }}MB
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- 上傳提示與規則 -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <div class="font-semibold mb-2">上傳規則</div>
                                    <ul class="space-y-1 list-disc list-inside">
                                        <li>每個預約最多可上傳 5 張圖片</li>
                                        <li>支援 JPG、PNG、GIF 格式</li>
                                        <li>單張圖片大小不超過 2MB</li>
                                        <li>建議上傳清晰的完成作品照片</li>
                                        <li>上傳後可以調整圖片順序</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <button type="button" wire:click="closeUploadModal"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200"
                                {{ $isUploading ? 'disabled' : '' }}>
                                {{ $isUploading ? '上傳中...' : '關閉' }}
                            </button>
                            @if($images && !$isUploading)
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                上傳 {{ count($images) }} 張圖片
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
                @else
                <!-- 已達上傳上限 -->
                <div class="text-center py-12 border-t border-gray-200">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">已達上傳上限</h3>
                    <p class="text-gray-500 mb-4">
                        此預約已上傳 5 張圖片，如需更換請先刪除現有圖片
                    </p>
                    
                    <div class="flex justify-center space-x-3">
                        <button wire:click="deleteAllImages"
                                onclick="return confirm('確定要刪除所有圖片嗎？此操作無法復原！')"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            刪除所有圖片
                        </button>
                        <button wire:click="closeUploadModal"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            關閉
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- 🔑 全局加載狀態遮罩 -->
    <div wire:loading wire:target="uploadImages" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-70">
        <div class="bg-white rounded-xl p-8 flex flex-col items-center space-y-4 max-w-sm mx-4">
            <!-- 動畫載入圖標 -->
            <div class="relative">
                <svg class="animate-spin h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                
                <!-- 進度環 -->
                @if($isUploading && $uploadProgress > 0)
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-xs font-bold text-blue-600">{{ $uploadProgress }}%</span>
                </div>
                @endif
            </div>
            
            <div class="text-center">
                <div class="text-lg font-medium text-gray-900 mb-1">
                    @if($isUploading)
                        上傳中...
                    @else
                        處理中...
                    @endif
                </div>
                <div class="text-sm text-gray-600">
                    @if($isUploading)
                        請勿關閉瀏覽器
                    @else
                        請稍候片刻
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 🔑 其他載入狀態 -->
    <div wire:loading wire:target="deleteImage,deleteAllImages,moveImageUp,moveImageDown" class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-60">
        <div class="bg-white rounded-lg p-4 flex items-center space-x-3 shadow-lg">
            <svg class="animate-spin h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 text-sm">處理中...</span>
        </div>
    </div>
</div>

<!-- 🔑 Alpine.js 增強功能（可選） -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('imageUpload', () => ({
        dragOver: false,
        
        handleDragOver(e) {
            e.preventDefault();
            this.dragOver = true;
        },
        
        handleDragLeave(e) {
            e.preventDefault();
            this.dragOver = false;
        },
        
        handleDrop(e) {
            e.preventDefault();
            this.dragOver = false;
            
            const files = Array.from(e.dataTransfer.files);
            const imageFiles = files.filter(file => file.type.startsWith('image/'));
            
            if (imageFiles.length > 0) {
                // 觸發 Livewire 文件上傳
                const input = document.getElementById('image-upload-{{ $booking->id ?? "new" }}');
                if (input) {
                    input.files = e.dataTransfer.files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        }
    }));
});
</script>