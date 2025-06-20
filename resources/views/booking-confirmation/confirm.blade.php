<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>預約確認 - {{ $booking->booking_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-md">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-500 text-white p-6 text-center">
                <h1 class="text-2xl font-bold">✅ 預約確認</h1>
                <p class="text-blue-100 mt-2">請確認您的預約資訊</p>
            </div>

            <!-- Booking Info -->
            <div class="p-6">
                <div class="space-y-4">
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
                        <span class="font-semibold text-blue-600">
                            {{ $booking->booking_time->format('Y/m/d H:i') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-gray-600">服務類型</span>
                        <span class="font-semibold">{{ $booking->style_type_text }}</span>
                    </div>
                    
                    @if($booking->amount)
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-gray-600">服務金額</span>
                        <span class="font-semibold text-green-600">NT$ {{ number_format($booking->amount) }}</span>
                    </div>
                    @else
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-gray-600">服務金額</span>
                        <span class="font-semibold text-gray-500">未設定</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-gray-600">確認截止</span>
                        <span class="font-semibold text-red-600">
                            {{ $booking->confirmation_deadline->format('Y/m/d 23:59') }}
                        </span>
                    </div>
                </div>

                @if($booking->notes)
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2">備註</h3>
                    <p class="text-gray-600">{{ $booking->notes }}</p>
                </div>
                @endif

                <!-- Warning -->
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <span class="text-red-500 text-xl mr-2">⚠️</span>
                        <div>
                            <h3 class="font-semibold text-red-700">重要提醒</h3>
                            <p class="text-red-600 text-sm mt-1">
                                請於截止時間前確認預約，否則系統將自動取消您的預約。
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Confirm Button -->
                <div class="mt-8" x-data="{ 
                    confirming: false, 
                    confirmed: false,
                    confirmBooking() {
                        this.confirming = true;
                        
                        fetch(`{{ route('booking.confirm.submit', $booking->confirmation_token) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.confirming = false;
                            if (data.success) {
                                this.confirmed = true;
                                // 2秒後重定向到預約列表
                                setTimeout(() => {
                                    window.location.href = '{{ route("booking.index") }}';
                                }, 2000);
                            } else {
                                alert(data.error || '確認失敗，請稍後再試');
                            }
                        })
                        .catch(error => {
                            this.confirming = false;
                            alert('確認失敗，請稍後再試');
                            console.error('Error:', error);
                        });
                    }
                }">
                    <button 
                        @click="confirmBooking()"
                        :disabled="confirming || confirmed"
                        :class="{
                            'bg-blue-500 hover:bg-blue-600': !confirming && !confirmed,
                            'bg-gray-400 cursor-not-allowed': confirming || confirmed,
                            'bg-green-500': confirmed
                        }"
                        class="w-full text-white font-bold py-4 px-6 rounded-lg transition-colors duration-200"
                    >
                        <span x-show="!confirming && !confirmed">✅ 確認預約</span>
                        <span x-show="confirming">⏳ 確認中...</span>
                        <span x-show="confirmed">✅ 已確認</span>
                    </button>

                    <!-- Success Message -->
                    <div x-show="confirmed" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-green-500 text-xl mr-2">✅</span>
                            <div>
                                <h3 class="font-semibold text-green-700">預約確認成功！</h3>
                                <p class="text-green-600 text-sm mt-1">
                                    感謝您的確認，我們期待為您服務！
                                </p>
                                <p class="text-green-500 text-xs mt-2">
                                    2秒後將自動返回預約列表...
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-500 text-sm">
            <p>如有任何問題，歡迎聯繫我們</p>
        </div>
    </div>


</body>
</html>