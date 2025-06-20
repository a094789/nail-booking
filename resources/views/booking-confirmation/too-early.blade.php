<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>預約確認尚未開放</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans TC', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <!-- 時鐘圖標 -->
            <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-4">預約確認尚未開放</h1>
            
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                <p class="text-orange-800 font-medium mb-2">預約資訊</p>
                <div class="text-sm text-orange-700 space-y-1">
                    <p><span class="font-medium">預約編號：</span>{{ $booking->booking_number }}</p>
                    <p><span class="font-medium">預約時間：</span>{{ $booking->booking_time->format('Y年m月d日 H:i') }}</p>
                    <p><span class="font-medium">客戶姓名：</span>{{ $booking->customer_name }}</p>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-blue-800 font-medium mb-2">⏰ 確認開放時間</p>
                <p class="text-lg font-bold text-blue-900">{{ $openTime->format('Y年m月d日 H:i') }}</p>
                <p class="text-sm text-blue-600 mt-1">（預約日前一天早上9點）</p>
            </div>

            <div class="text-sm text-gray-600 mb-6">
                <p>為了確保預約的準確性，預約確認功能將在指定時間開放。</p>
                <p class="mt-2">請在開放時間後再次訪問此頁面進行確認。</p>
            </div>

            <!-- 倒數計時器 -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600 mb-2">距離確認開放還有：</p>
                <div id="countdown" class="text-lg font-bold text-gray-900"></div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('booking.index') }}" 
                   class="flex-1 bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors text-center font-medium">
                    返回預約列表
                </a>
                <button onclick="location.reload()" 
                        class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    重新整理
                </button>
            </div>
        </div>
    </div>

    <script>
        // 倒數計時器
        const openTime = new Date('{{ $openTime->format("Y-m-d H:i:s") }}').getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = openTime - now;
            
            if (distance < 0) {
                document.getElementById('countdown').innerHTML = '確認功能已開放！';
                // 自動重新整理頁面
                setTimeout(() => location.reload(), 2000);
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            let countdownText = '';
            if (days > 0) countdownText += days + '天 ';
            if (hours > 0) countdownText += hours + '小時 ';
            if (minutes > 0) countdownText += minutes + '分鐘 ';
            countdownText += seconds + '秒';
            
            document.getElementById('countdown').innerHTML = countdownText;
        }
        
        // 每秒更新倒數
        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);
    </script>
</body>
</html> 