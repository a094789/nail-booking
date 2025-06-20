<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Livewire 測試</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Livewire 測試</h1>
        
        <div class="space-y-6">
            <!-- 基本測試 -->
            <div class="p-4 bg-green-50 border border-green-200 rounded">
                <h3 class="font-semibold text-green-800 mb-2">✅ 頁面載入成功</h3>
                <p class="text-green-700">如果您看到這個頁面，表示基本的 Laravel 和 Blade 模板功能正常。</p>
            </div>
            
            <!-- Alpine.js 測試 -->
            <div class="p-4 bg-blue-50 border border-blue-200 rounded">
                <h3 class="font-semibold text-blue-800 mb-2">Alpine.js 測試</h3>
                <div x-data="{ count: 0 }" class="space-y-2">
                    <p class="text-blue-700">計數器: <span x-text="count" class="font-bold"></span></p>
                    <button @click="count++" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        點擊增加
                    </button>
                </div>
            </div>
            
            <!-- 簡單的 Livewire 測試組件 -->
            <div class="p-4 bg-purple-50 border border-purple-200 rounded">
                <h3 class="font-semibold text-purple-800 mb-2">Livewire 測試</h3>
                <div x-data="{ counter: 0, message: '' }" class="space-y-3">
                    <p class="text-purple-700">本地計數器: <span x-text="counter" class="font-bold"></span></p>
                    <div class="flex space-x-2">
                        <button @click="counter++" class="bg-purple-500 text-white px-3 py-2 rounded hover:bg-purple-600 text-sm">
                            本地增加
                        </button>
                        <button @click="testLivewireRequest()" class="bg-orange-500 text-white px-3 py-2 rounded hover:bg-orange-600 text-sm">
                            測試 Livewire 請求
                        </button>
                    </div>
                    <div x-show="message" x-text="message" class="text-sm p-2 bg-gray-100 rounded"></div>
                </div>
            </div>
            
            <!-- 系統資訊 -->
            <div class="p-4 bg-gray-50 border border-gray-200 rounded">
                <h3 class="font-semibold text-gray-800 mb-2">系統資訊</h3>
                <div class="text-sm text-gray-700 space-y-1">
                    <p><strong>Laravel 版本:</strong> {{ app()->version() }}</p>
                    <p><strong>PHP 版本:</strong> {{ PHP_VERSION }}</p>
                    <p><strong>環境:</strong> {{ app()->environment() }}</p>
                    <p><strong>時間:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-gray-50 rounded">
            <h3 class="font-semibold mb-2">CSRF Token:</h3>
            <p class="text-xs text-gray-600 break-all">{{ csrf_token() }}</p>
        </div>
        
        <div class="mt-6 flex space-x-4">
            <a href="{{ route('admin.bookings.index') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                測試預約管理
            </a>
            <a href="{{ route('admin.dashboard') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                測試管理後台
            </a>
        </div>
    </div>
    
    @livewireScripts
    
    <script>
        // 測試 Livewire 請求的函數
        function testLivewireRequest() {
            console.log('🔧 開始測試 Livewire 請求');
            
            // 檢查 Livewire 是否已載入
            if (typeof window.Livewire === 'undefined') {
                console.error('❌ Livewire 未載入');
                // 使用更安全的方式更新 Alpine.js 資料
                const alpineData = document.querySelector('[x-data*="message"]');
                if (alpineData && alpineData._x_dataStack) {
                    alpineData._x_dataStack[0].message = '❌ Livewire 未載入';
                }
                return;
            }
            
            console.log('✅ Livewire 已載入');
            // 使用更安全的方式更新 Alpine.js 資料
            const alpineData = document.querySelector('[x-data*="message"]');
            if (alpineData && alpineData._x_dataStack) {
                alpineData._x_dataStack[0].message = '✅ Livewire 已載入，測試完成';
            }
        }
        
        // 基本的 Livewire 狀態檢查
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔧 Livewire 測試頁面載入完成');
            console.log('Livewire 物件:', typeof window.Livewire !== 'undefined' ? '✅ 已載入' : '❌ 未載入');
            console.log('Alpine.js:', typeof window.Alpine !== 'undefined' ? '✅ 已載入' : '❌ 未載入');
            console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]') ? '✅ 存在' : '❌ 不存在');
            
            // 監控所有網路請求
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                const [url, options = {}] = args;
                
                console.log('🌐 網路請求:', {
                    url: url,
                    method: options.method || 'GET',
                    isLivewire: typeof url === 'string' && url.includes('livewire')
                });
                
                // 特別檢查 Livewire 請求
                if (typeof url === 'string' && url.includes('livewire/update')) {
                    console.warn('⚠️ 檢測到 Livewire 更新請求:', {
                        url,
                        method: options.method || 'GET',
                        headers: options.headers
                    });
                    
                    // 如果是 GET 請求，記錄錯誤
                    if (!options.method || options.method.toUpperCase() === 'GET') {
                        console.error('❌ 錯誤：Livewire 請求使用 GET 方法！');
                    }
                }
                
                return originalFetch.apply(this, args);
            };
            
            // 簡單的狀態指示
            const statusDiv = document.createElement('div');
            statusDiv.className = 'mt-4 p-3 bg-gray-100 rounded text-sm';
            statusDiv.innerHTML = `
                <strong>JavaScript 狀態:</strong><br>
                • Livewire: ${typeof window.Livewire !== 'undefined' ? '✅ 正常' : '❌ 錯誤'}<br>
                • Alpine.js: ${typeof window.Alpine !== 'undefined' ? '✅ 正常' : '❌ 錯誤'}<br>
                • CSRF: ${document.querySelector('meta[name="csrf-token"]') ? '✅ 正常' : '❌ 錯誤'}
            `;
            document.querySelector('.max-w-2xl').appendChild(statusDiv);
        });
    </script>
</body>
</html> 