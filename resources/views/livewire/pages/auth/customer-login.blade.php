<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>美甲預約系統</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-50 min-h-screen">
    <div class="min-h-screen flex flex-col justify-center items-center px-4">
        
        <!-- Logo 區域 -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-pink-100 rounded-full mb-4">
                <span class="text-3xl">💅</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">美甲預約系統</h1>
            <p class="text-gray-600">使用 LINE 快速登入開始預約</p>
        </div>

        <!-- 登入卡片 -->
        <div class="w-full max-w-sm">
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- LINE 登入按鈕 -->
                <a href="{{ route('line.login') }}" 
                   class="w-full flex justify-center items-center px-6 py-4 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-green-500/25 transform hover:-translate-y-0.5">
                    
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.627-.63h2.386c.349 0 .63.285.63.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.627-.63.349 0 .631.285.631.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.628-.629.628M24 10.314C24 4.943 18.615.572 12.017.572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                    </svg>
                    
                    使用 LINE 登入
                </a>

                <div class="mt-4 text-center">
                    <p class="text-sm text-green-600 font-medium">✨ 一鍵登入，快速預約</p>
                </div>
            </div>

            <!-- 返回首頁 -->
            <div class="text-center mt-6">
                <a href="/" class="text-gray-600 hover:text-pink-600 transition-colors duration-200">
                    ← 返回首頁
                </a>
            </div>
        </div>
    </div>
</body>
</html>