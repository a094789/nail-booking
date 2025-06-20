<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認時間已過期</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-md">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-orange-500 text-white p-6 text-center">
                <h1 class="text-2xl font-bold">⏰ 確認時間已過期</h1>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="text-6xl mb-4">⌛</div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">很抱歉，確認時間已過期</h2>
                </div>

                <!-- Booking Info -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-gray-600">預約編號</span>
                        <span class="font-semibold">{{ $booking->booking_number }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-gray-600">客戶姓名</span>
                        <span class="font-semibold">{{ $booking->customer_name }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-gray-600">預約時間</span>
                        <span class="font-semibold">{{ $booking->booking_time->format('Y/m/d H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-gray-600">確認截止</span>
                        <span class="font-semibold text-red-600">
                            {{ $booking->confirmation_deadline->format('Y/m/d 23:59') }}
                        </span>
                    </div>
                </div>

                <!-- Warning -->
                <div class="p-4 bg-orange-50 border border-orange-200 rounded-lg mb-6">
                    <div class="flex items-start">
                        <span class="text-orange-500 text-xl mr-2">⚠️</span>
                        <div>
                            <h3 class="font-semibold text-orange-700">預約將被取消</h3>
                            <p class="text-orange-600 text-sm mt-1">
                                由於未在截止時間前確認，此預約將由系統自動取消。
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-gray-600 mb-4">如需重新預約，請聯繫我們或使用線上預約系統。</p>
                    
                    <div class="space-y-3">
                        <a href="/" class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            返回首頁
                        </a>
                        <a href="/booking/create" class="block w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            重新預約
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 