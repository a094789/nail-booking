{{-- resources/views/livewire/admin/image-viewer.blade.php --}}

@if($showViewer && $currentImage)
<div class="fixed inset-0 bg-black bg-opacity-95 z-[60] flex items-center justify-center">
    <!-- 關閉按鈕 -->
    <button wire:click="closeViewer" 
            class="absolute top-4 right-4 z-[70] bg-black bg-opacity-50 text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-70 transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <!-- 工具列 -->
    <div class="absolute top-4 left-4 z-[70] flex items-center space-x-2">
        <!-- 預約資訊 -->
        @if($booking)
        <div class="bg-black bg-opacity-50 text-white px-3 py-2 rounded-lg text-sm">
            預約單號：{{ $booking['booking_number'] }}
        </div>
        @endif
        
        <!-- 圖片計數 -->
        <div class="bg-black bg-opacity-50 text-white px-3 py-2 rounded-lg text-sm">
            {{ $currentImageIndex + 1 }} / {{ count($images) }}
        </div>
    </div>

    <!-- 控制按鈕 -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 z-[70] flex items-center space-x-2 bg-black bg-opacity-50 rounded-lg p-2">
        <!-- 放大縮小 -->
        <button wire:click="zoomOut" 
                class="text-white hover:text-gray-300 p-2 rounded hover:bg-white hover:bg-opacity-20 transition-all"
                title="縮小">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/>
            </svg>
        </button>
        
        <button wire:click="resetZoom" 
                class="text-white hover:text-gray-300 p-2 rounded hover:bg-white hover:bg-opacity-20 transition-all text-xs"
                title="重置">
            {{ round($scale * 100) }}%
        </button>
        
        <button wire:click="zoomIn" 
                class="text-white hover:text-gray-300 p-2 rounded hover:bg-white hover:bg-opacity-20 transition-all"
                title="放大">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
            </svg>
        </button>

        <!-- 分隔線 -->
        <div class="w-px h-6 bg-gray-400"></div>

        <!-- 旋轉 -->
        <button wire:click="rotateLeft" 
                class="text-white hover:text-gray-300 p-2 rounded hover:bg-white hover:bg-opacity-20 transition-all"
                title="向左旋轉">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </button>
        
        <button wire:click="rotateRight" 
                class="text-white hover:text-gray-300 p-2 rounded hover:bg-white hover:bg-opacity-20 transition-all"
                title="向右旋轉">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 4v5h-.582m0 0a8.003 8.003 0 00-15.356 2m15.356-2H15M4 20v-5h.581m0 0a8.003 8.003 0 0015.357-2M4.581 15H9"/>
            </svg>
        </button>

        <!-- 分隔線 -->
        <div class="w-px h-6 bg-gray-400"></div>

        <!-- 下載 -->
        <a href="{{ $this->currentImageUrl }}" 
           download="{{ $booking['booking_number'] ?? 'image' }}_{{ $currentImageIndex + 1 }}.jpg"
           class="text-white hover:text-gray-300 p-2 rounded hover:bg-white hover:bg-opacity-20 transition-all"
           title="下載圖片">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </a>

        <!-- 刪除 -->
        <button wire:click="deleteCurrentImage" 
                onclick="return confirm('確定要刪除這張圖片嗎？')"
                class="text-white hover:text-red-400 p-2 rounded hover:bg-white hover:bg-opacity-20 transition-all"
                title="刪除圖片">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    </div>

    <!-- 導航箭頭 -->
    @if(count($images) > 1)
        <!-- 上一張 -->
        @if($currentImageIndex > 0)
        <button wire:click="previousImage" 
                class="absolute left-4 top-1/2 transform -translate-y-1/2 z-[70] bg-black bg-opacity-50 text-white rounded-full w-12 h-12 flex items-center justify-center hover:bg-opacity-70 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        @endif

        <!-- 下一張 -->
        @if($currentImageIndex < count($images) - 1)
        <button wire:click="nextImage" 
                class="absolute right-4 top-1/2 transform -translate-y-1/2 z-[70] bg-black bg-opacity-50 text-white rounded-full w-12 h-12 flex items-center justify-center hover:bg-opacity-70 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
        @endif
    @endif

    <!-- 縮圖導航 (多張圖片時顯示) -->
    @if(count($images) > 1)
    <div class="absolute bottom-20 left-1/2 transform -translate-x-1/2 z-[70] flex items-center space-x-2 bg-black bg-opacity-50 rounded-lg p-2 max-w-md overflow-x-auto">
        @foreach($images as $index => $image)
        <button wire:click="goToImage({{ $index }})" 
                class="flex-shrink-0 w-12 h-12 rounded border-2 overflow-hidden hover:opacity-80 transition-opacity {{ $index === $currentImageIndex ? 'border-white' : 'border-transparent' }}">
            <img src="{{ asset('storage/' . (str_starts_with($image['image_path'], 'booking_images/') ? $image['image_path'] : 'booking_images/' . $image['image_path'])) }}" 
                 alt="縮圖 {{ $index + 1 }}"
                 class="w-full h-full object-cover">
        </button>
        @endforeach
    </div>
    @endif

    <!-- 主圖片容器 -->
    <div class="relative w-full h-full flex items-center justify-center overflow-hidden"
         x-data="{ 
            isDragging: false, 
            startX: 0, 
            startY: 0, 
            initialPanX: @entangle('panX'),
            initialPanY: @entangle('panY')
         }"
         @mousedown="isDragging = true; startX = $event.clientX; startY = $event.clientY; initialPanX = $wire.panX; initialPanY = $wire.panY"
         @mousemove="if(isDragging && {{ $scale }} > 1) { 
            $wire.panX = initialPanX + ($event.clientX - startX); 
            $wire.panY = initialPanY + ($event.clientY - startY); 
         }"
         @mouseup="isDragging = false"
         @mouseleave="isDragging = false"
         @wheel.prevent="
            const delta = $event.deltaY;
            if(delta < 0) {
                $wire.zoomIn();
            } else {
                $wire.zoomOut();
            }
         "
         style="cursor: {{ $scale > 1 ? 'grab' : 'default' }}">
        
        <img src="{{ $this->currentImageUrl }}" 
             alt="預約圖片"
             class="max-w-none max-h-none object-contain transition-transform duration-200"
             style="transform: scale({{ $scale }}) rotate({{ $rotation }}deg) translate({{ $panX }}px, {{ $panY }}px);
                    cursor: {{ $scale > 1 ? 'grab' : 'default' }};"
             @dblclick="$wire.scale === 1 ? $wire.zoomIn() : $wire.resetZoom()"
             draggable="false">
    </div>

    <!-- 載入指示器 -->
    <div wire:loading wire:target="nextImage,previousImage,goToImage" 
         class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[80]">
        <div class="bg-white rounded-lg p-4 flex items-center space-x-3">
            <svg class="animate-spin h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700">載入中...</span>
        </div>
    </div>

    <!-- 鍵盤提示 -->
    <div class="absolute top-20 right-4 z-[70] bg-black bg-opacity-50 text-white text-xs rounded-lg p-3 space-y-1">
        <div><kbd class="bg-gray-700 px-1 rounded">ESC</kbd> 關閉</div>
        <div><kbd class="bg-gray-700 px-1 rounded">←/→</kbd> 切換圖片</div>
        <div><kbd class="bg-gray-700 px-1 rounded">滾輪</kbd> 縮放</div>
        <div><kbd class="bg-gray-700 px-1 rounded">雙擊</kbd> 重置縮放</div>
        <div><kbd class="bg-gray-700 px-1 rounded">拖拽</kbd> 移動圖片</div>
    </div>
</div>

<!-- 鍵盤事件監聽 -->
<script>
document.addEventListener('keydown', function(e) {
    if (!@json($showViewer)) return;
    
    switch(e.key) {
        case 'Escape':
            @this.closeViewer();
            break;
        case 'ArrowLeft':
            @this.previousImage();
            break;
        case 'ArrowRight':
            @this.nextImage();
            break;
        case '+':
        case '=':
            @this.zoomIn();
            break;
        case '-':
            @this.zoomOut();
            break;
        case '0':
            @this.resetZoom();
            break;
    }
});
</script>
@endif