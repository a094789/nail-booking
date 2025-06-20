<x-app-layout>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-pink-500 to-purple-600 text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.1\"%3E%3Ccircle cx=\"7\" cy=\"7\" r=\"1\"/%3E%3Ccircle cx=\"37\" cy=\"7\" r=\"1\"/%3E%3Ccircle cx=\"7\" cy=\"37\" r=\"1\"/%3E%3Ccircle cx=\"37\" cy=\"37\" r=\"1\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    專業美甲服務
                </h1>
                <p class="text-xl md:text-2xl opacity-90 mb-8 max-w-2xl mx-auto leading-relaxed">
                    讓我們為您打造完美指甲藝術，展現您的獨特魅力
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('booking.create') }}"
                       class="inline-flex items-center px-8 py-4 bg-white text-pink-600 rounded-2xl text-lg font-semibold shadow-lg transition-all duration-300 hover:bg-gray-50 hover:shadow-xl hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        查看可預約時段
                    </a>
                    @guest
                        <a href="{{ route('customer.login') }}" 
                           class="inline-flex items-center px-8 py-4 bg-purple-700 text-white rounded-2xl text-lg font-semibold shadow-lg transition-all duration-300 hover:bg-purple-800 hover:shadow-xl hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            立即預約
                        </a>
                    @else
                        <a href="{{ route('booking.create') }}" 
                           class="inline-flex items-center px-8 py-4 bg-purple-700 text-white rounded-2xl text-lg font-semibold shadow-lg transition-all duration-300 hover:bg-purple-800 hover:shadow-xl hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            立即預約
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    為什麼選擇我們
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    專業技術、優質服務、便利預約，讓您享受最佳的美甲體驗
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="group text-center p-8 rounded-2xl bg-white shadow-lg border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-20 h-20 bg-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-pink-200 transition-colors duration-300">
                        <svg class="w-10 h-10 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">線上預約</h3>
                    <p class="text-gray-600 leading-relaxed">24小時線上預約系統，隨時查看可預約時段</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="group text-center p-8 rounded-2xl bg-white shadow-lg border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-purple-200 transition-colors duration-300">
                        <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">專業技術</h3>
                    <p class="text-gray-600 leading-relaxed">經驗豐富的美甲師，提供專業的美甲服務</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="group text-center p-8 rounded-2xl bg-white shadow-lg border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-200 transition-colors duration-300">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">貼心服務</h3>
                    <p class="text-gray-600 leading-relaxed">LINE通知提醒，讓您不錯過任何預約資訊</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="group text-center p-8 rounded-2xl bg-white shadow-lg border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-green-200 transition-colors duration-300">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">快速便利</h3>
                    <p class="text-gray-600 leading-relaxed">LINE登入，快速完成預約，節省您的寶貴時間</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    服務項目
                </h2>
                <p class="text-lg text-gray-600">
                    多樣化的美甲服務，滿足您的不同需求
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Single Color Service -->
                <div class="group bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="p-10">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-pink-100 rounded-2xl flex items-center justify-center mr-6 group-hover:bg-pink-200 transition-colors duration-300">
                                <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">單色美甲</h3>
                        </div>
                        <p class="text-gray-600 mb-8 leading-relaxed text-lg">
                            簡約而不失優雅的純色設計，適合日常生活和職場需求。提供多種顏色選擇，讓您展現專業形象。
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">多種顏色選擇</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">持久不脫落</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">適合日常配搭</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Design Service -->
                <div class="group bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="p-10">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mr-6 group-hover:bg-purple-200 transition-colors duration-300">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">造型美甲</h3>
                        </div>
                        <p class="text-gray-600 mb-8 leading-relaxed text-lg">
                            創意十足的圖案設計，讓您的指甲成為時尚亮點。專業美甲師為您量身打造獨特造型。
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">創意圖案設計</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">個人化定制</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">節慶特色主題</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-20 bg-gradient-to-r from-pink-500 to-purple-600 text-white">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                準備開始您的美甲之旅？
            </h2>
            <p class="text-xl mb-10 opacity-90 max-w-2xl mx-auto leading-relaxed">
                立即預約，讓專業美甲師為您打造完美指甲造型
            </p>
            <a href="{{ route('booking.create') }}" 
               class="inline-flex items-center px-10 py-5 bg-white text-pink-600 rounded-2xl text-xl font-bold shadow-lg transition-all duration-300 hover:bg-gray-50 hover:shadow-xl hover:-translate-y-1">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                立即預約
            </a>
        </div>
    </div>
</x-app-layout>